<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menyelaraskan alur tahapan dengan kenyataan: pengaduan yang masuk
 * LANGSUNG didisposisikan ke PIC pemilik topik, tanpa verifikasi admin.
 *
 * Alur lama (5 tahap) : Diterima > Verifikasi Admin > Didisposisikan ke PIC > Tindak Lanjut > Selesai
 * Alur baru  (4 tahap): Diterima > Disposisi ke PIC > Tindak Lanjut > Selesai
 *
 * Migration ini juga menambah status "Didisposisikan ke PIC" pada
 * pengaduan.status_akhir sebagai status awal setelah pengaduan dikirim,
 * lalu membangun ulang baris pengaduan_tahapan yang sudah ada agar
 * mengikuti skema 4 tahap.
 */
class AlignTahapanWithDirectDisposisi extends Migration
{
    private const TAHAPAN = [
        1 => 'Pengaduan Diterima',
        2 => 'Disposisi ke PIC',
        3 => 'Tindak Lanjut',
        4 => 'Selesai',
    ];

    public function up()
    {
        // 1) Perluas ENUM tahap agar memuat nilai lama + baru (supaya data lama tetap valid).
        $this->db->query("
            ALTER TABLE pengaduan_tahapan
            MODIFY tahap ENUM(
                'Pengaduan Diterima','Verifikasi Admin','Didisposisikan ke PIC',
                'Disposisi ke PIC','Tindak Lanjut','Selesai'
            ) NOT NULL
        ");

        // 2) Tambah status awal 'Didisposisikan ke PIC' pada pengaduan.
        $this->db->query("
            ALTER TABLE pengaduan
            MODIFY status_akhir ENUM(
                'Didisposisikan ke PIC','Menunggu Verifikasi','Dalam Penanganan','Selesai','Ditolak'
            ) NOT NULL DEFAULT 'Didisposisikan ke PIC'
        ");

        // Tiket lama yang masih 'Menunggu Verifikasi' berarti belum disentuh PIC,
        // sekarang dianggap sudah didisposisikan.
        $this->db->query("
            UPDATE pengaduan
            SET status_akhir = 'Didisposisikan ke PIC'
            WHERE status_akhir = 'Menunggu Verifikasi'
        ");

        // 3) Bangun ulang tahapan untuk seluruh pengaduan yang ada.
        $this->db->query('DELETE FROM pengaduan_tahapan');

        $rows = $this->db->table('pengaduan')
            ->select('id, status_akhir, created_at, updated_at')
            ->get()->getResultArray();

        $stageForStatus = [
            'Didisposisikan ke PIC' => 2,
            'Dalam Penanganan'      => 3,
            'Selesai'               => 4,
            'Ditolak'               => 2,
        ];

        foreach ($rows as $p) {
            $target  = $stageForStatus[$p['status_akhir']] ?? 2;
            $selesai = $p['status_akhir'] === 'Selesai';
            $dibuat  = $p['created_at'] ?: date('Y-m-d H:i:s');
            $diubah  = $p['updated_at'] ?: $dibuat;

            foreach (self::TAHAPAN as $urutan => $nama) {
                if ($urutan < $target) {
                    $status = 'Selesai';
                } elseif ($urutan === $target) {
                    $status = $selesai ? 'Selesai' : 'Dalam Proses';
                } else {
                    $status = 'Menunggu';
                }

                $this->db->table('pengaduan_tahapan')->insert([
                    'pengaduan_id'    => $p['id'],
                    'tahap'           => $nama,
                    'urutan'          => $urutan,
                    'status'          => $status,
                    'sla_hari'        => null,
                    'tanggal_mulai'   => $status !== 'Menunggu' ? ($urutan === 1 ? $dibuat : $diubah) : null,
                    'tanggal_selesai' => $status === 'Selesai' ? ($urutan === 1 ? $dibuat : $diubah) : null,
                    'keterangan'      => null,
                ]);
            }
        }

        // 4) Rampingkan ENUM ke 4 nilai final setelah data dibersihkan.
        $this->db->query("
            ALTER TABLE pengaduan_tahapan
            MODIFY tahap ENUM(
                'Pengaduan Diterima','Disposisi ke PIC','Tindak Lanjut','Selesai'
            ) NOT NULL
        ");
    }

    public function down()
    {
        $this->db->query("
            ALTER TABLE pengaduan_tahapan
            MODIFY tahap ENUM(
                'Pengaduan Diterima','Verifikasi Admin','Didisposisikan ke PIC','Tindak Lanjut','Selesai'
            ) NOT NULL
        ");

        $this->db->query("
            ALTER TABLE pengaduan
            MODIFY status_akhir ENUM(
                'Menunggu Verifikasi','Dalam Penanganan','Selesai','Ditolak'
            ) NOT NULL DEFAULT 'Menunggu Verifikasi'
        ");
    }
}
