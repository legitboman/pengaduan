<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tanggapan PIC/Admin atas sebuah pengaduan.
 * - is_publik = 1 : balasan ditujukan ke pelapor, tampil di halaman riwayat masyarakat.
 * - is_publik = 0 : catatan internal, hanya terlihat oleh Admin & PIC.
 */
class CreatePengaduanTanggapan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'pengaduan_id' => ['type' => 'INT', 'unsigned' => true],
            'pic_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'admin_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'pengirim_tipe' => ['type' => 'ENUM', 'constraint' => ['pic', 'admin'], 'default' => 'pic'],
            'pengirim_nama' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'isi' => ['type' => 'TEXT'],
            'is_publik' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengaduan_id', 'pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pic_id', 'pic', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('admin_id', 'admin', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pengaduan_tanggapan');
    }

    public function down()
    {
        $this->forge->dropTable('pengaduan_tanggapan', true);
    }
}
