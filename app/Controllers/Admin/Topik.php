<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriPengaduanModel;
use App\Models\PicModel;
use App\Models\TopikPengaduanModel;

/**
 * Admin: Manajemen Topik & PIC.
 *
 * Setiap topik pengaduan (topik_pengaduan) punya satu PIC default
 * (topik_pengaduan.pic_id) yang otomatis ditugaskan saat masyarakat
 * membuat pengaduan baru dengan topik tersebut.
 */
class Topik extends BaseController
{
    protected TopikPengaduanModel $topikModel;
    protected KategoriPengaduanModel $kategoriModel;
    protected PicModel $picModel;

    public function __construct()
    {
        $this->topikModel    = new TopikPengaduanModel();
        $this->kategoriModel = new KategoriPengaduanModel();
        $this->picModel      = new PicModel();
    }

    /**
     * GET /admin/topik
     */
    public function index()
    {
        $rows = $this->topikModel
            ->select('topik_pengaduan.*, kategori_pengaduan.nama_kategori, pic.nama_pic')
            ->join('kategori_pengaduan', 'kategori_pengaduan.id = topik_pengaduan.kategori_id', 'left')
            ->join('pic', 'pic.id = topik_pengaduan.pic_id', 'left')
            ->orderBy('kategori_pengaduan.nama_kategori', 'ASC')
            ->orderBy('topik_pengaduan.nama_topik', 'ASC')
            ->findAll();

        return view('admin/topik/index', [
            'title'        => 'Topik & PIC',
            'active'       => 'topik',
            'pageTitle'    => 'Manajemen Topik & PIC',
            'pageSubtitle' => 'Setiap topik pengaduan memiliki satu PIC penanggung jawab default',
            'list'         => $rows,
            'picOptions'   => $this->picModel->active(),
        ]);
    }

    /**
     * POST /admin/topik/update-pic/{id}
     */
    public function updatePic(int $id)
    {
        $picId = $this->request->getPost('pic_id') ?: null;

        $this->topikModel->update($id, ['pic_id' => $picId]);

        return redirect()->to(site_url('admin/topik'))->with('success', 'PIC untuk topik berhasil diperbarui.');
    }
}
