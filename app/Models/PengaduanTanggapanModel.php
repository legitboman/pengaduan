<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaduanTanggapanModel extends Model
{
    protected $table = 'pengaduan_tanggapan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pengaduan_id', 'pic_id', 'admin_id', 'pengirim_tipe', 'pengirim_nama', 'isi', 'is_publik',
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Semua tanggapan (internal + publik) — untuk Admin & PIC.
     */
    public function forPengaduan(int $pengaduanId): array
    {
        return $this->where('pengaduan_id', $pengaduanId)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Hanya tanggapan publik — untuk pelapor (masyarakat).
     */
    public function publikForPengaduan(int $pengaduanId): array
    {
        return $this->where('pengaduan_id', $pengaduanId)
            ->where('is_publik', 1)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }
}
