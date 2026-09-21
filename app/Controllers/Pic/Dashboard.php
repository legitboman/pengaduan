<?php

namespace App\Controllers\Pic;

use App\Controllers\BaseController;
use App\Models\PengaduanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $pengaduanModel = new PengaduanModel();
        $picId = (int) session()->get('picId');

        $recentRows = $pengaduanModel->forPic($picId, 8);

        $recent = array_map(static function ($row) {
            return [
                'id'              => $row['id'],
                'nomor_tiket'     => $row['nomor_tiket'],
                'judul_pengaduan' => $row['judul_pengaduan'],
                'nama_pelapor'    => $row['nama_pelapor'],
                'topik'           => $row['nama_topik'] ?? '-',
                'wilayah'         => $row['nama_wilayah'] ?? '-',
                'tanggal'         => date('d F Y', strtotime($row['tanggal_pengaduan'])),
                'status'          => $row['status_akhir'],
            ];
        }, $recentRows);

        $data = [
            'title'        => 'Dashboard',
            'active'       => 'dashboard',
            'pageTitle'    => 'Dashboard',
            'pageSubtitle' => 'Ringkasan pengaduan yang ditugaskan kepada Anda',
            'stats'        => $pengaduanModel->statusCountsForPic($picId),
            'recent'       => $recent,
        ];

        return view('pic/dashboard', $data);
    }
}
