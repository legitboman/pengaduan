<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Lampiran kini dibedakan:
 * - 'pelapor'            : bukti pendukung yang diunggah masyarakat saat membuat pengaduan.
 * - 'bukti_penyelesaian' : dokumen hasil penanganan yang diunggah PIC saat menutup tiket
 *                          (Berita Acara Pemeriksaan, Surat Panggilan, Surat Rekomendasi, dll).
 */
class AddJenisToPengaduanLampiran extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengaduan_lampiran', [
            'jenis' => [
                'type' => 'ENUM', 'constraint' => ['pelapor', 'bukti_penyelesaian'],
                'default' => 'pelapor', 'after' => 'pengaduan_id',
            ],
            'uploaded_by_pic_id' => [
                'type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'jenis',
            ],
            'keterangan' => [
                'type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'ukuran_kb',
            ],
        ]);

        $this->db->query('ALTER TABLE pengaduan_lampiran ADD CONSTRAINT fk_lampiran_pic FOREIGN KEY (uploaded_by_pic_id) REFERENCES pic(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE pengaduan_lampiran DROP FOREIGN KEY fk_lampiran_pic');
        $this->forge->dropColumn('pengaduan_lampiran', ['jenis', 'uploaded_by_pic_id', 'keterangan']);
    }
}
