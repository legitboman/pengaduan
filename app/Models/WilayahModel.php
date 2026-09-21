<?php

namespace App\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_wilayah', 'kode_wilayah', 'is_active'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function active()
    {
        return $this->where('is_active', 1)->orderBy('nama_wilayah', 'ASC')->findAll();
    }
}
