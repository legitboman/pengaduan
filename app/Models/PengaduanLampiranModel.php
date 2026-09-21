<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaduanLampiranModel extends Model
{
    protected $table = 'pengaduan_lampiran';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pengaduan_id', 'jenis', 'uploaded_by_pic_id', 'nama_file', 'path_file',
        'mime_type', 'ukuran_kb', 'keterangan',
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function forPengaduan(int $pengaduanId): array
    {
        return $this->where('pengaduan_id', $pengaduanId)->findAll();
    }

    /**
     * Lampiran bukti pendukung dari pelapor.
     */
    public function pelaporFor(int $pengaduanId): array
    {
        return $this->where('pengaduan_id', $pengaduanId)
            ->where('jenis', 'pelapor')
            ->findAll();
    }

    /**
     * Dokumen hasil penanganan yang diunggah PIC (BAP, surat panggilan, dll).
     */
    public function buktiPenyelesaianFor(int $pengaduanId): array
    {
        return $this->where('pengaduan_id', $pengaduanId)
            ->where('jenis', 'bukti_penyelesaian')
            ->findAll();
    }
}
