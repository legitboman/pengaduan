<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaduanModel extends Model
{
    protected $table = 'pengaduan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nomor_tiket', 'topik_id', 'wilayah_id', 'masyarakat_id',
        'lokasi_kejadian', 'judul_pengaduan', 'tanggal_pengaduan', 'tanggal_kejadian',
        'pihak_dilaporkan', 'kronologi', 'nama_pelapor', 'email_pelapor', 'no_hp_pelapor',
        'rahasiakan_identitas', 'kode_akses', 'status_akhir', 'alasan_penolakan',
        'sla_hari', 'tanggal_target_selesai', 'status_validasi', 'catatan_validasi',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    /**
     * Admin: list pengaduan with topik name + pelapor, optional search/filter.
     */
    public function listForAdmin(?string $q = null, ?string $status = null): array
    {
        $builder = $this->select('pengaduan.*, topik_pengaduan.nama_topik')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'left')
            ->orderBy('pengaduan.created_at', 'DESC');

        if ($q) {
            $builder->groupStart()
                ->like('pengaduan.judul_pengaduan', $q)
                ->orLike('pengaduan.nama_pelapor', $q)
                ->groupEnd();
        }

        if ($status) {
            $builder->where('pengaduan.status_akhir', $status);
        }

        return $builder->findAll();
    }

    /**
     * Admin: full detail of one complaint, including kategori/topik/wilayah/pic names.
     */
    public function detailForAdmin(int $id): ?array
    {
        return $this->select('
                pengaduan.*,
                topik_pengaduan.nama_topik,
                kategori_pengaduan.nama_kategori,
                wilayah.nama_wilayah,
                pic.nama_pic
            ')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'left')
            ->join('kategori_pengaduan', 'kategori_pengaduan.id = topik_pengaduan.kategori_id', 'left')
            ->join('wilayah', 'wilayah.id = pengaduan.wilayah_id', 'left')
            ->join('pic', 'pic.id = topik_pengaduan.pic_id', 'left')
            ->find($id);
    }

    /**
     * Public: find by ticket number for the success/track pages.
     */
    public function findByTicket(string $nomorTiket): ?array
    {
        return $this->where('nomor_tiket', $nomorTiket)->first();
    }

    /**
     * Ambil pengaduan by nomor tiket beserta nama PIC — dipakai oleh halaman
     * riwayat pengaduan milik akun masyarakat (kepemilikan divalidasi di
     * controller lewat masyarakat_id, tidak lagi butuh kode akses).
     */
    public function findByTicketWithPic(string $nomorTiket): ?array
    {
        return $this->select('pengaduan.*, pic.nama_pic, pic.jabatan')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'left')
            ->join('pic', 'pic.id = topik_pengaduan.pic_id', 'left')
            ->where('pengaduan.nomor_tiket', $nomorTiket)
            ->first();
    }

    /**
     * Simple counts for the admin dashboard.
     */
    public function statusCounts(): array
    {
        $rows = $this->select('status_akhir, COUNT(*) as total')
            ->groupBy('status_akhir')
            ->findAll();

        $counts = ['total' => 0, 'menunggu' => 0, 'proses' => 0, 'selesai' => 0, 'ditolak' => 0];
        $map = [
            'Didisposisikan ke PIC' => 'menunggu',
            'Menunggu Verifikasi' => 'menunggu',
            'Dalam Penanganan'    => 'proses',
            'Selesai'             => 'selesai',
            'Ditolak'             => 'ditolak',
        ];

        foreach ($rows as $row) {
            $key = $map[$row['status_akhir']] ?? null;
            if ($key) {
                $counts[$key] = (int) $row['total'];
            }
            $counts['total'] += (int) $row['total'];
        }

        return $counts;
    }

    public function recentForAdmin(int $limit = 5): array
    {
        return $this->select('pengaduan.*, topik_pengaduan.nama_topik')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'left')
            ->orderBy('pengaduan.created_at', 'DESC')
            ->findAll($limit);
    }

    public function forMasyarakat(int $masyarakatId): array
    {
        return $this->select('pengaduan.*, topik_pengaduan.nama_topik')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'left')
            ->where('pengaduan.masyarakat_id', $masyarakatId)
            ->orderBy('pengaduan.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Semua pengaduan yang ditugaskan ke seorang PIC (dipakai dashboard PIC).
     */
    public function forPic(int $picId, int $limit = null): array
    {
        $builder = $this->select('pengaduan.*, topik_pengaduan.nama_topik, wilayah.nama_wilayah')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'left')
            ->join('wilayah', 'wilayah.id = pengaduan.wilayah_id', 'left')
            ->where('topik_pengaduan.pic_id', $picId)
            ->orderBy('pengaduan.created_at', 'DESC');

        return $limit ? $builder->findAll($limit) : $builder->findAll();
    }

    /**
     * Ringkasan status pengaduan yang ditugaskan ke seorang PIC.
     */
    public function statusCountsForPic(int $picId): array
    {
        $rows = $this->select('pengaduan.status_akhir, COUNT(*) as total')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'inner')
            ->where('topik_pengaduan.pic_id', $picId)
            ->groupBy('pengaduan.status_akhir')
            ->findAll();

        $counts = ['total' => 0, 'menunggu' => 0, 'proses' => 0, 'selesai' => 0, 'ditolak' => 0];
        $map = [
            'Didisposisikan ke PIC' => 'menunggu',
            'Menunggu Verifikasi' => 'menunggu',
            'Dalam Penanganan'    => 'proses',
            'Selesai'             => 'selesai',
            'Ditolak'             => 'ditolak',
        ];

        foreach ($rows as $row) {
            $key = $map[$row['status_akhir']] ?? null;
            if ($key) {
                $counts[$key] = (int) $row['total'];
            }
            $counts['total'] += (int) $row['total'];
        }

        return $counts;
    }

    /**
     * Detail satu pengaduan UNTUK PIC tertentu.
     *
     * Query di-scope ke topik_pengaduan.pic_id sehingga seorang PIC hanya bisa
     * membuka tiket yang memang ditugaskan kepadanya. PIC lain akan
     * mendapat null (bukan data milik orang lain).
     */
    public function detailForPic(int $id, int $picId): ?array
    {
        return $this->select('
                pengaduan.*,
                topik_pengaduan.nama_topik,
                kategori_pengaduan.nama_kategori,
                wilayah.nama_wilayah
            ')
            ->join('topik_pengaduan', 'topik_pengaduan.id = pengaduan.topik_id', 'left')
            ->join('kategori_pengaduan', 'kategori_pengaduan.id = topik_pengaduan.kategori_id', 'left')
            ->join('wilayah', 'wilayah.id = pengaduan.wilayah_id', 'left')
            ->where('pengaduan.id', $id)
            ->where('topik_pengaduan.pic_id', $picId)
            ->first();
    }
}
