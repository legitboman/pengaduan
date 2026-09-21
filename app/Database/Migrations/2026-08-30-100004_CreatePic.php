<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePic extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama_pic' => ['type' => 'VARCHAR', 'constraint' => 150],
            'jabatan' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => 20],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('pic');
    }

    public function down()
    {
        $this->forge->dropTable('pic', true);
    }
}
