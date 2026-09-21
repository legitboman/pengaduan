<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('AdminSeeder');
        $this->call('WilayahSeeder');
        $this->call('KategoriPengaduanSeeder');
        $this->call('PicSeeder');
        $this->call('TopikPengaduanSeeder');
        $this->call('SlaDefaultSeeder');
        $this->call('MasyarakatSeeder');
        $this->call('PengaduanDemoSeeder');
    }
}
