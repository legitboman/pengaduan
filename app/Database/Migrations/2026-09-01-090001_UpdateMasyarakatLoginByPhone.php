<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Login masyarakat sekarang memakai NOMOR TELEPON, bukan email.
 * - `no_hp` dijadikan unik (dipakai sebagai kredensial login).
 * - `email` dijadikan opsional (nullable) karena tidak lagi wajib.
 */
class UpdateMasyarakatLoginByPhone extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE masyarakat MODIFY email VARCHAR(150) NULL');
        $this->db->query('ALTER TABLE masyarakat ADD UNIQUE KEY masyarakat_no_hp_unique (no_hp)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE masyarakat DROP INDEX masyarakat_no_hp_unique');
        $this->db->query('ALTER TABLE masyarakat MODIFY email VARCHAR(150) NOT NULL');
    }
}
