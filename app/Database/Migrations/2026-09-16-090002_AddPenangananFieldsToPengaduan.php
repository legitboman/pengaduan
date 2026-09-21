<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Field tambahan untuk penanganan oleh PIC:
 * - sla_hari & tanggal_target_selesai : SLA yang ditetapkan PIC (dalam hari).
 * - status_validasi & catatan_validasi : hasil verifikasi validitas pengaduan.
 */
class AddPenangananFieldsToPengaduan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengaduan', [
            'sla_hari' => [
                'type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'assigned_pic_id',
            ],
            'tanggal_target_selesai' => [
                'type' => 'DATE', 'null' => true, 'after' => 'sla_hari',
            ],
            'status_validasi' => [
                'type' => 'ENUM',
                'constraint' => ['Belum Diverifikasi', 'Valid', 'Tidak Valid', 'Bukan Kewenangan'],
                'default' => 'Belum Diverifikasi',
                'after' => 'tanggal_target_selesai',
            ],
            'catatan_validasi' => [
                'type' => 'TEXT', 'null' => true, 'after' => 'status_validasi',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pengaduan', ['sla_hari', 'tanggal_target_selesai', 'status_validasi', 'catatan_validasi']);
    }
}
