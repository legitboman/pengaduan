<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaduanAuditTrailModel extends Model
{
    protected $table = 'pengaduan_audit_trail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengaduan_id', 'admin_id', 'pic_id', 'aktor_tipe', 'aktor_nama', 'aktivitas', 'is_publik'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Catat aktivitas oleh ADMIN.
     */
    public function log(int $pengaduanId, ?int $adminId, string $aktivitas, bool $isPublik = false): void
    {
        $this->insert([
            'pengaduan_id' => $pengaduanId,
            'admin_id'     => $adminId,
            'pic_id'       => null,
            'aktor_tipe'   => 'admin',
            'aktor_nama'   => session()->get('adminNama') ?? 'Admin',
            'aktivitas'    => $aktivitas,
            'is_publik'    => $isPublik ? 1 : 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Catat aktivitas oleh PIC.
     */
    public function logPic(int $pengaduanId, ?int $picId, string $aktivitas, bool $isPublik = false): void
    {
        $this->insert([
            'pengaduan_id' => $pengaduanId,
            'admin_id'     => null,
            'pic_id'       => $picId,
            'aktor_tipe'   => 'pic',
            'aktor_nama'   => session()->get('picNama') ?? 'PIC',
            'aktivitas'    => $aktivitas,
            'is_publik'    => $isPublik ? 1 : 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Seluruh riwayat update (internal) — dipakai Admin & PIC.
     */
    public function forPengaduan(int $pengaduanId): array
    {
        return $this->where('pengaduan_id', $pengaduanId)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Hanya catatan yang ditandai publik — tampil ke masyarakat.
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
