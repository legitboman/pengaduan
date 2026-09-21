<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengaduanDemoSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('pengaduan')->countAllResults() > 0) {
            return;
        }

        $topik = $this->indexBy('topik_pengaduan', 'nama_topik');
        $wilayah = $this->indexBy('wilayah', 'nama_wilayah');
        $masyarakat = $this->indexBy('masyarakat', 'nama');

        $demo = [
            [
                'nomor_tiket' => 'JATIM-PHK-2026-00841',
                'topik' => 'Pemutusan Hubungan Kerja (PHK)',
                'wilayah' => 'Kota Surabaya',
                'pelapor' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'hp' => '081234567890',
                'pihak_dilaporkan' => 'PT Maju Jaya Sentosa',
                'judul' => 'PHK sepihak tanpa pesangon',
                'kronologi' => "Pada tanggal 1 Agustus 2026 saya menerima surat PHK tanpa pemberitahuan sebelumnya.\nPerusahaan tidak memberikan pesangon sesuai ketentuan yang berlaku.",
                'tanggal_kejadian' => '2026-08-01',
                'tanggal_pengaduan' => '2026-08-03',
                'status' => 'Dalam Penanganan',
                'tahap_aktif' => 3, // Tindak Lanjut
            ],
            [
                'nomor_tiket' => 'JATIM-UPH-2026-00842',
                'topik' => 'Upah Tidak Dibayar / Terlambat',
                'wilayah' => 'Kabupaten Sidoarjo',
                'pelapor' => 'Siti Rahma',
                'email' => 'siti.rahma@example.com',
                'hp' => '081298765432',
                'pihak_dilaporkan' => 'CV Sumber Rejeki',
                'judul' => 'Upah lembur tidak dibayarkan',
                'kronologi' => 'Selama 3 bulan terakhir upah lembur saya tidak dibayarkan sesuai kesepakatan awal.',
                'tanggal_kejadian' => '2026-07-15',
                'tanggal_pengaduan' => '2026-08-02',
                'status' => 'Didisposisikan ke PIC',
                'tahap_aktif' => 2, // Disposisi ke PIC
            ],
            [
                'nomor_tiket' => 'JATIM-K3-2026-00843',
                'topik' => 'Keselamatan & Kesehatan Kerja (K3)',
                'wilayah' => 'Kabupaten Gresik',
                'pelapor' => 'Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'hp' => '081211122233',
                'pihak_dilaporkan' => 'PT Industri Logam Nusantara',
                'judul' => 'Kondisi kerja tidak memenuhi standar K3',
                'kronologi' => 'Tidak tersedia alat pelindung diri (APD) yang memadai di area produksi.',
                'tanggal_kejadian' => '2026-07-20',
                'tanggal_pengaduan' => '2026-07-30',
                'status' => 'Selesai',
                'tahap_aktif' => 4, // Selesai
            ],
            [
                'nomor_tiket' => 'JATIM-DIS-2026-00844',
                'topik' => 'Diskriminasi di Tempat Kerja',
                'wilayah' => 'Kota Malang',
                'pelapor' => 'Rina Melati',
                'email' => 'rina.melati@example.com',
                'hp' => '081355566677',
                'pihak_dilaporkan' => 'PT Retail Sejahtera',
                'judul' => 'Diskriminasi terhadap karyawan perempuan',
                'kronologi' => 'Karyawan perempuan tidak diberikan kesempatan promosi yang setara.',
                'tanggal_kejadian' => '2026-07-10',
                'tanggal_pengaduan' => '2026-07-28',
                'status' => 'Ditolak',
                'tahap_aktif' => 2, // berhenti di tahap disposisi
            ],
        ];

        $tahapUrutan = ['Pengaduan Diterima', 'Disposisi ke PIC', 'Tindak Lanjut', 'Selesai'];

        foreach ($demo as $d) {
            $topikId = $topik[$d['topik']]['id'] ?? null;
            $wilayahId = $wilayah[$d['wilayah']]['id'] ?? null;
            $masyarakatId = $masyarakat[$d['pelapor']]['id'] ?? null;

            $this->db->table('pengaduan')->insert([
                'nomor_tiket' => $d['nomor_tiket'],
                'topik_id' => $topikId,
                'wilayah_id' => $wilayahId,
                'masyarakat_id' => $masyarakatId,
                'lokasi_kejadian' => null,
                'judul_pengaduan' => $d['judul'],
                'tanggal_pengaduan' => $d['tanggal_pengaduan'],
                'tanggal_kejadian' => $d['tanggal_kejadian'],
                'pihak_dilaporkan' => $d['pihak_dilaporkan'],
                'kronologi' => $d['kronologi'],
                'nama_pelapor' => $d['pelapor'],
                'email_pelapor' => $d['email'],
                'no_hp_pelapor' => $d['hp'],
                'rahasiakan_identitas' => 0,
                'kode_akses' => strtoupper(bin2hex(random_bytes(4))),
                'status_akhir' => $d['status'],
                'created_at' => $d['tanggal_pengaduan'] . ' 09:00:00',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $pengaduanId = $this->db->insertID();

            foreach ($tahapUrutan as $i => $namaTahap) {
                $urutan = $i + 1;
                if ($urutan < $d['tahap_aktif']) {
                    $status = 'Selesai';
                } elseif ($urutan === $d['tahap_aktif']) {
                    $status = $d['status'] === 'Selesai' ? 'Selesai' : 'Dalam Proses';
                } else {
                    $status = 'Menunggu';
                }

                $this->db->table('pengaduan_tahapan')->insert([
                    'pengaduan_id' => $pengaduanId,
                    'tahap' => $namaTahap,
                    'urutan' => $urutan,
                    'status' => $status,
                    'sla_hari' => null,
                    'tanggal_mulai' => $status !== 'Menunggu' ? $d['tanggal_pengaduan'] . ' 09:00:00' : null,
                    'tanggal_selesai' => $status === 'Selesai' ? $d['tanggal_pengaduan'] . ' 14:00:00' : null,
                    'keterangan' => null,
                ]);
            }
        }
    }

    private function indexBy(string $table, string $column): array
    {
        $rows = $this->db->table($table)->get()->getResultArray();
        $indexed = [];
        foreach ($rows as $row) {
            $indexed[$row[$column]] = $row;
        }

        return $indexed;
    }
}