<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Penugasan PIC tidak lagi disimpan per-pengaduan.
 *
 * Alur baru: pengaduan -> topik_pengaduan -> pic
 * Artinya begitu masyarakat mengirim pengaduan dengan topik tertentu,
 * tiket langsung menjadi milik PIC penanggung jawab topik itu, tanpa
 * perlu proses penugasan/persetujuan admin.
 *
 * Kolom assigned_pic_id dihapus supaya tidak ada dua sumber kebenaran
 * (yang sebelumnya menyebabkan tiket lama ber-assigned_pic_id NULL dan
 * dashboard PIC tampak kosong).
 */
class DropAssignedPicFromPengaduan extends Migration
{
    public function up()
    {
        // Lengkapi dulu topik yang belum punya PIC, agar tidak ada tiket "yatim".
        $this->db->query("
            UPDATE topik_pengaduan tp
            JOIN (SELECT id FROM pic WHERE is_active = 1 ORDER BY id LIMIT 1) fallback
            SET tp.pic_id = fallback.id
            WHERE tp.pic_id IS NULL
        ");

        // Buang foreign key + kolomnya.
        $db = $this->db->getDatabase();
        $fk = $this->db->query("
            SELECT CONSTRAINT_NAME AS name
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '{$db}'
              AND TABLE_NAME = 'pengaduan'
              AND COLUMN_NAME = 'assigned_pic_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ")->getRowArray();

        if ($fk) {
            $this->db->query("ALTER TABLE pengaduan DROP FOREIGN KEY `{$fk['name']}`");
        }

        if ($this->db->fieldExists('assigned_pic_id', 'pengaduan')) {
            $this->forge->dropColumn('pengaduan', 'assigned_pic_id');
        }
    }

    public function down()
    {
        $this->forge->addColumn('pengaduan', [
            'assigned_pic_id' => [
                'type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'masyarakat_id',
            ],
        ]);

        $this->db->query('ALTER TABLE pengaduan ADD CONSTRAINT fk_pengaduan_pic FOREIGN KEY (assigned_pic_id) REFERENCES pic(id) ON DELETE SET NULL ON UPDATE CASCADE');

        // Isi ulang dari PIC topik masing-masing.
        $this->db->query('
            UPDATE pengaduan p
            JOIN topik_pengaduan tp ON tp.id = p.topik_id
            SET p.assigned_pic_id = tp.pic_id
        ');
    }
}
