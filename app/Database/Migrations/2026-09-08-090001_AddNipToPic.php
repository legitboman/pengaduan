<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Login PIC menggunakan NIP + password (bukan email).
 */
class AddNipToPic extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pic', [
            'nip' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'nama_pic'],
        ]);
        $this->db->query('ALTER TABLE pic ADD UNIQUE KEY pic_nip_unique (nip)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE pic DROP INDEX pic_nip_unique');
        $this->forge->dropColumn('pic', 'nip');
    }
}
