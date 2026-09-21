<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaduan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nomor_tiket' => ['type' => 'VARCHAR', 'constraint' => 30],
            'topik_id' => ['type' => 'INT', 'unsigned' => true],
            'wilayah_id' => ['type' => 'INT', 'unsigned' => true],
            'masyarakat_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'assigned_pic_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'lokasi_kejadian' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'judul_pengaduan' => ['type' => 'VARCHAR', 'constraint' => 150],
            'tanggal_pengaduan' => ['type' => 'DATE'],
            'tanggal_kejadian' => ['type' => 'DATE'],
            'pihak_dilaporkan' => ['type' => 'VARCHAR', 'constraint' => 150],
            'kronologi' => ['type' => 'TEXT'],
            'nama_pelapor' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email_pelapor' => ['type' => 'VARCHAR', 'constraint' => 150],
            'no_hp_pelapor' => ['type' => 'VARCHAR', 'constraint' => 20],
            'rahasiakan_identitas' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'kode_akses' => ['type' => 'VARCHAR', 'constraint' => 20],
            'status_akhir' => [
                'type' => 'ENUM',
                'constraint' => ['Menunggu Verifikasi', 'Dalam Penanganan', 'Selesai', 'Ditolak'],
                'default' => 'Menunggu Verifikasi',
            ],
            'alasan_penolakan' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nomor_tiket');
        $this->forge->addForeignKey('topik_id', 'topik_pengaduan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('wilayah_id', 'wilayah', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('masyarakat_id', 'masyarakat', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('assigned_pic_id', 'pic', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pengaduan');
    }

    public function down()
    {
        $this->forge->dropTable('pengaduan', true);
    }
}
