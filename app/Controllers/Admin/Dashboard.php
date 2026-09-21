<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengaduanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $pengaduanModel = new PengaduanModel();

        $data = [
            'title'        => 'Dashboard',
            'active'       => 'dashboard',
            'pageTitle'    => 'Dashboard',
            'pageSubtitle' => 'Ringkasan pengaduan masuk',
            'stats'        => $pengaduanModel->statusCounts(),
            'recent'       => array_map(static function ($row) {
                return [
                    'nomor_tiket'     => $row['nomor_tiket'],
                    'judul_pengaduan' => $row['judul_pengaduan'],
                    'nama_pelapor'    => $row['nama_pelapor'],
                    'topik'           => $row['nama_topik'] ?? '-',
                    'status'          => $row['status_akhir'],
                ];
            }, $pengaduanModel->recentForAdmin(5)),
        ];

        return view('admin/dashboard', $data);
    }
}
