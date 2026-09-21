<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PicSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('pic')->countAllResults() > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        // Demo login PIC (portal /pic): NIP di bawah ini + password "pic12345"
        $data = [
            [
                'nama_pic' => 'Hendra Kurniawan', 'nip' => '198501012010011001', 'jabatan' => 'Bidang Pengawasan Ketenagakerjaan',
                'email' => 'hendra.k@disnaker.jatimprov.go.id', 'no_hp' => '081511122233',
            ],
            [
                'nama_pic' => 'Dewi Anggraini', 'nip' => '198702022011012002', 'jabatan' => 'Bidang Hubungan Industrial',
                'email' => 'dewi.a@disnaker.jatimprov.go.id', 'no_hp' => '081522233344',
            ],
            [
                'nama_pic' => 'Fajar Nugroho', 'nip' => '199003032012011003', 'jabatan' => 'Bidang Penempatan Tenaga Kerja',
                'email' => 'fajar.n@disnaker.jatimprov.go.id', 'no_hp' => '081533344455',
            ],
        ];

        foreach ($data as &$row) {
            $row['password'] = password_hash('pic12345', PASSWORD_DEFAULT);
            $row['is_active'] = 1;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $this->db->table('pic')->insertBatch($data);
    }
}
