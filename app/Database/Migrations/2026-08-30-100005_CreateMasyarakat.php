<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * NOTE: `masyarakat` was not present in the original ERD (which only stored
 * pelapor name/email/phone directly on `pengaduan`). It's added here because
 * the admin panel needs a real table to CRUD "akun masyarakat" against.
 * `pengaduan.masyarakat_id` is nullable so a complaint can still be linked
 * to a registered account without forcing every submitter to have one.
 */
class CreateMasyarakat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 150],
            'nisn' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => 20],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('nisn');
        $this->forge->createTable('masyarakat');
    }

    public function down()
    {
        $this->forge->dropTable('masyarakat', true);
    }
}
