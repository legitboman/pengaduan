<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaduanTahapanModel extends Model
{
    protected $table = 'pengaduan_tahapan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengaduan_id', 'tahap', 'urutan', 'status', 'sla_hari', 'tanggal_mulai', 'tanggal_selesai', 'keterangan'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public const TAHAPAN = [
        1 => 'Pengaduan Diterima',
        2 => 'Disposisi ke PIC',
        3 => 'Tindak Lanjut',
        4 => 'Selesai',
    ];

    /**
     * Bangun tahapan awal saat pengaduan dibuat.
     *
     * Tahap 1 (Pengaduan Diterima) langsung selesai, dan tahap 2
     * (Disposisi ke PIC) langsung berjalan — karena tiket otomatis
     * masuk ke PIC pemilik topik tanpa perlu persetujuan admin.
     */
    public function seedForPengaduan(int $pengaduanId, ?array $slaByUrutan = null): void
    {
        $now = date('Y-m-d H:i:s');

        foreach (self::TAHAPAN as $urutan => $nama) {
            if ($urutan === 1) {
                $status = 'Selesai';
            } elseif ($urutan === 2) {
                $status = 'Dalam Proses';
            } else {
                $status = 'Menunggu';
            }

            $this->insert([
                'pengaduan_id'    => $pengaduanId,
                'tahap'           => $nama,
                'urutan'          => $urutan,
                'status'          => $status,
                'sla_hari'        => $slaByUrutan[$urutan] ?? null,
                'tanggal_mulai'   => $status !== 'Menunggu' ? $now : null,
                'tanggal_selesai' => $urutan === 1 ? $now : null,
                'keterangan'      => $urutan === 2 ? 'Diteruskan otomatis ke PIC penanggung jawab topik.' : null,
            ]);
        }
    }

    /**
     * Pemetaan status_akhir pengaduan ke urutan tahap yang sedang berjalan.
     */
    public const STAGE_FOR_STATUS = [
        'Didisposisikan ke PIC' => 2,
        'Menunggu Verifikasi'   => 2, // status lama, diperlakukan sama
        'Dalam Penanganan'      => 3,
        'Selesai'               => 4,
        'Ditolak'               => 2,
    ];

    /**
     * Sinkronkan seluruh tahapan sebuah pengaduan agar sesuai status terbaru.
     * Dipakai bersama oleh Admin maupun PIC supaya logikanya tidak terpecah.
     */
    public function syncWithStatus(int $pengaduanId, string $status): void
    {
        $target  = self::STAGE_FOR_STATUS[$status] ?? 1;
        $selesai = $status === 'Selesai';

        foreach ($this->forPengaduan($pengaduanId) as $t) {
            $urutan = (int) $t['urutan'];

            if ($urutan < $target) {
                $newStatus = 'Selesai';
            } elseif ($urutan === $target) {
                $newStatus = $selesai ? 'Selesai' : 'Dalam Proses';
            } else {
                $newStatus = 'Menunggu';
            }

            if ($newStatus === $t['status']) {
                continue;
            }

            $this->update($t['id'], [
                'status'          => $newStatus,
                'tanggal_mulai'   => $newStatus !== 'Menunggu' ? ($t['tanggal_mulai'] ?? date('Y-m-d H:i:s')) : null,
                'tanggal_selesai' => $newStatus === 'Selesai' ? date('Y-m-d H:i:s') : null,
            ]);
        }
    }

    public function forPengaduan(int $pengaduanId): array
    {
        return $this->where('pengaduan_id', $pengaduanId)->orderBy('urutan', 'ASC')->findAll();
    }

    public function stepperFor(int $pengaduanId): array
    {
        $rows    = $this->forPengaduan($pengaduanId);
        $stepper = [];

        foreach ($rows as $row) {
            $state = match ($row['status']) {
                'Selesai'      => 'done',
                'Dalam Proses' => 'active',
                default        => 'pending',
            };

            if ($row['status'] === 'Selesai' && ! empty($row['tanggal_selesai'])) {
                $tanggal = $row['tanggal_selesai'];
            } elseif (! empty($row['tanggal_mulai'])) {
                $tanggal = $row['tanggal_mulai'];
            } else {
                $tanggal = null;
            }

            // Fallback ke konstanta jika kolom tahap di DB kosong
            $urutan = (int) $row['urutan'];
            $nama   = ! empty($row['tahap']) ? $row['tahap'] : (self::TAHAPAN[$urutan] ?? 'Tahap ' . $urutan);

            $stepper[] = [
                'nama'         => $nama,
                'state'        => $state,
                'label_status' => $row['status'],
                'tanggal'      => $tanggal ? date('d F Y • H.i', strtotime($tanggal)) . ' WIB' : null,
                'keterangan'   => $row['keterangan'] ?? null,
            ];
        }

        return $stepper;
    }
}