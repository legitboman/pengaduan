<?php

namespace App\Models;

use CodeIgniter\Model;

class SlaDefaultModel extends Model
{
    protected $table = 'sla_default';
    protected $primaryKey = 'id';
    protected $allowedFields = ['topik_id', 'tahap', 'sla_hari', 'urutan'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function forTopik(int $topikId)
    {
        return $this->where('topik_id', $topikId)->orderBy('urutan', 'ASC')->findAll();
    }
}
