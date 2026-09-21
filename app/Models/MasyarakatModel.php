<?php

namespace App\Models;

use CodeIgniter\Model;

class MasyarakatModel extends Model
{
    protected $table = 'masyarakat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'nisn',
        'email',
        'password',
        'no_hp',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function findByNoHp(string $noHp): ?array
    {
        return $this->where('no_hp', $noHp)->first();
    }
}