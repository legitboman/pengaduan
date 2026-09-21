<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SlaDefaultSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('sla_default')->countAllResults() > 0) {
            return;
        }

        $topikIds = $this->db->table('topik_pengaduan')->select('id')->get()->getResultArray();

        $tahapan = [
            ['tahap' => 'Pengaduan Diterima', 'sla_hari' => 0, 'urutan' => 1],
            ['tahap' => 'Disposisi ke PIC', 'sla_hari' => 2, 'urutan' => 2],
            ['tahap' => 'Tindak Lanjut', 'sla_hari' => 5, 'urutan' => 3],
            ['tahap' => 'Selesai', 'sla_hari' => 1, 'urutan' => 4],
        ];

        $data = [];
        foreach ($topikIds as $t) {
            foreach ($tahapan as $tahap) {
                $data[] = array_merge($tahap, ['topik_id' => $t['id']]);
            }
        }

        $this->db->table('sla_default')->insertBatch($data);
    }
}