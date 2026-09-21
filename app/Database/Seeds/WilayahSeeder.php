<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('wilayah')->countAllResults() > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $data = [
            ['nama_wilayah' => 'Kota Surabaya', 'kode_wilayah' => 'JATIM-SBY'],
            ['nama_wilayah' => 'Kabupaten Sidoarjo', 'kode_wilayah' => 'JATIM-SDA'],
            ['nama_wilayah' => 'Kabupaten Gresik', 'kode_wilayah' => 'JATIM-GRS'],
            ['nama_wilayah' => 'Kota Malang', 'kode_wilayah' => 'JATIM-MLG'],
            ['nama_wilayah' => 'Kabupaten Mojokerto', 'kode_wilayah' => 'JATIM-MJK'],
        ];

        foreach ($data as &$row) {
            $row['is_active'] = 1;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $this->db->table('wilayah')->insertBatch($data);
    }
}
