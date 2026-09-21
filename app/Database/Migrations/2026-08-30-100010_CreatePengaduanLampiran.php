<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaduanLampiran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'pengaduan_id' => ['type' => 'INT', 'unsigned' => true],
            'nama_file' => ['type' => 'VARCHAR', 'constraint' => 255],
            'path_file' => ['type' => 'VARCHAR', 'constraint' => 255],
            'mime_type' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'ukuran_kb' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengaduan_id', 'pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengaduan_lampiran');
    }

    public function down()
    {
        $this->forge->dropTable('pengaduan_lampiran', true);
    }
}
