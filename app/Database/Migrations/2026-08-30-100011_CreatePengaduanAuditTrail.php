<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaduanAuditTrail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'pengaduan_id' => ['type' => 'INT', 'unsigned' => true],
            'admin_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'aktivitas' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengaduan_id', 'pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('admin_id', 'admin', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pengaduan_audit_trail');
    }

    public function down()
    {
        $this->forge->dropTable('pengaduan_audit_trail', true);
    }
}
