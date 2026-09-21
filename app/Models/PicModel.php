<?php

namespace App\Models;

use CodeIgniter\Model;

class PicModel extends Model
{
    protected $table = 'pic';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_pic', 'nip', 'jabatan', 'email', 'password', 'no_hp', 'is_active'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function active()
    {
        return $this->where('is_active', 1)->orderBy('nama_pic', 'ASC')->findAll();
    }

    public function findByNip(string $nip): ?array
    {
        return $this->where('nip', $nip)->first();
    }
}
