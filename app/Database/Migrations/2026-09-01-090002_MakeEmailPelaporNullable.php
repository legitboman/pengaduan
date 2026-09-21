<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Email pelapor sekarang opsional (data pelapor auto-fill dari akun yang
 * login, dan akun masyarakat tidak lagi wajib mengisi email).
 */
class MakeEmailPelaporNullable extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE pengaduan MODIFY email_pelapor VARCHAR(150) NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE pengaduan MODIFY email_pelapor VARCHAR(150) NOT NULL');
    }
}
