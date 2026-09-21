<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TopikPengaduanSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('topik_pengaduan')->countAllResults() > 0) {
            return;
        }

        $kategoriId = $this->db->table('kategori_pengaduan')
            ->select('id')
            ->where('nama_kategori', 'Norma/Permasalahan Ketenagakerjaan')
            ->get()->getRow('id');

        $picPengawasan = $this->db->table('pic')->select('id')->where('jabatan', 'Bidang Pengawasan Ketenagakerjaan')->get()->getRow('id');
        $picHubIndustrial = $this->db->table('pic')->select('id')->where('jabatan', 'Bidang Hubungan Industrial')->get()->getRow('id');

        $now = date('Y-m-d H:i:s');
        $data = [
            ['nama_topik' => 'Pemutusan Hubungan Kerja (PHK)', 'pic_id' => $picHubIndustrial],
            ['nama_topik' => 'Upah Tidak Dibayar / Terlambat', 'pic_id' => $picPengawasan],
            ['nama_topik' => 'Jam Kerja & Lembur', 'pic_id' => $picPengawasan],
            ['nama_topik' => 'Keselamatan & Kesehatan Kerja (K3)', 'pic_id' => $picPengawasan],
            ['nama_topik' => 'Diskriminasi di Tempat Kerja', 'pic_id' => $picHubIndustrial],
        ];

        foreach ($data as &$row) {
            $row['kategori_id'] = $kategoriId;
            $row['deskripsi'] = null;
            $row['is_active'] = 1;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $this->db->table('topik_pengaduan')->insertBatch($data);
    }
}
