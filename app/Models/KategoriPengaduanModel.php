<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriPengaduanModel extends Model
{
    protected $table = 'kategori_pengaduan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_kategori', 'is_active'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function active()
    {
        return $this->where('is_active', 1)->orderBy('nama_kategori', 'ASC')->findAll();
    }
}
