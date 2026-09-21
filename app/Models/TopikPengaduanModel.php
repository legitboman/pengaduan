<?php

namespace App\Models;

use CodeIgniter\Model;

class TopikPengaduanModel extends Model
{
    protected $table = 'topik_pengaduan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kategori_id', 'pic_id', 'nama_topik', 'deskripsi', 'is_active'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function active()
    {
        return $this->where('is_active', 1)->orderBy('nama_topik', 'ASC')->findAll();
    }

    /**
     * Semua topik aktif beserta kategori_id dan nama PIC default-nya.
     * Dipakai untuk dropdown topik yang bergantung pada kategori terpilih
     * (filter dilakukan di sisi client via atribut data-kategori).
     */
    public function activeWithPic(): array
    {
        return $this->select('topik_pengaduan.*, pic.nama_pic')
            ->join('pic', 'pic.id = topik_pengaduan.pic_id', 'left')
            ->where('topik_pengaduan.is_active', 1)
            ->orderBy('topik_pengaduan.nama_topik', 'ASC')
            ->findAll();
    }

    public function withKategoriAndPic(int $id): ?array
    {
        return $this->select('topik_pengaduan.*, kategori_pengaduan.nama_kategori, pic.nama_pic, pic.jabatan')
            ->join('kategori_pengaduan', 'kategori_pengaduan.id = topik_pengaduan.kategori_id', 'left')
            ->join('pic', 'pic.id = topik_pengaduan.pic_id', 'left')
            ->find($id);
    }
}
