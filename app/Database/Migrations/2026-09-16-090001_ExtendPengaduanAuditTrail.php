<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Audit trail sekarang bisa dicatat oleh Admin maupun PIC, dan sebagian
 * catatan bisa ditandai "publik" agar ikut tampil sebagai catatan update
 * di halaman riwayat milik masyarakat.
 */
class ExtendPengaduanAuditTrail extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengaduan_audit_trail', [
            'pic_id' => [
                'type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'admin_id',
            ],
            'aktor_tipe' => [
                'type' => 'ENUM', 'constraint' => ['admin', 'pic', 'sistem'],
                'default' => 'admin', 'after' => 'pic_id',
            ],
            'aktor_nama' => [
                'type' => 'VARCHAR', 'constraint' => 150, 'null' => true, 'after' => 'aktor_tipe',
            ],
            'is_publik' => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'aktivitas',
            ],
        ]);

        $this->db->query('ALTER TABLE pengaduan_audit_trail ADD CONSTRAINT fk_audit_pic FOREIGN KEY (pic_id) REFERENCES pic(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE pengaduan_audit_trail DROP FOREIGN KEY fk_audit_pic');
        $this->forge->dropColumn('pengaduan_audit_trail', ['pic_id', 'aktor_tipe', 'aktor_nama', 'is_publik']);
    }
}
