<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSlaDefault extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'topik_id' => ['type' => 'INT', 'unsigned' => true],
            'tahap' => [
                'type' => 'ENUM',
                'constraint' => ['Pengaduan Diterima', 'Verifikasi Admin', 'Didisposisikan ke PIC', 'Tindak Lanjut', 'Selesai'],
            ],
            'sla_hari' => ['type' => 'INT', 'unsigned' => true],
            'urutan' => ['type' => 'TINYINT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('topik_id', 'topik_pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sla_default');
    }

    public function down()
    {
        $this->forge->dropTable('sla_default', true);
    }
}
