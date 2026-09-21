<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaduanTahapan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'pengaduan_id' => ['type' => 'INT', 'unsigned' => true],
            'tahap' => [
                'type' => 'ENUM',
                'constraint' => ['Pengaduan Diterima', 'Verifikasi Admin', 'Didisposisikan ke PIC', 'Tindak Lanjut', 'Selesai'],
            ],
            'urutan' => ['type' => 'TINYINT', 'unsigned' => true],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Menunggu', 'Dalam Proses', 'Selesai'],
                'default' => 'Menunggu',
            ],
            'sla_hari' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'tanggal_mulai' => ['type' => 'DATETIME', 'null' => true],
            'tanggal_selesai' => ['type' => 'DATETIME', 'null' => true],
            'keterangan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengaduan_id', 'pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengaduan_tahapan');
    }

    public function down()
    {
        $this->forge->dropTable('pengaduan_tahapan', true);
    }
}
