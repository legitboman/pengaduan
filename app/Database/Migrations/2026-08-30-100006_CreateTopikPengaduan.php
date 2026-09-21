<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopikPengaduan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kategori_id' => ['type' => 'INT', 'unsigned' => true],
            'pic_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'nama_topik' => ['type' => 'VARCHAR', 'constraint' => 150],
            'deskripsi' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'kategori_pengaduan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pic_id', 'pic', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('topik_pengaduan');
    }

    public function down()
    {
        $this->forge->dropTable('topik_pengaduan', true);
    }
}
