<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWilayah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama_wilayah' => ['type' => 'VARCHAR', 'constraint' => 150],
            'kode_wilayah' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('wilayah');
    }

    public function down()
    {
        $this->forge->dropTable('wilayah', true);
    }
}
