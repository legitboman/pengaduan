<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriPengaduanSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('kategori_pengaduan')->countAllResults() > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $data = [
            ['nama_kategori' => 'Norma/Permasalahan Ketenagakerjaan'],
            ['nama_kategori' => 'Kepesertaan Jaminan Sosial Ketenagakerjaan'],
            ['nama_kategori' => 'Pelatihan & Sertifikasi Kerja'],
            ['nama_kategori' => 'Penempatan Tenaga Kerja'],
        ];

        foreach ($data as &$row) {
            $row['is_active'] = 1;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $this->db->table('kategori_pengaduan')->insertBatch($data);
    }
}
