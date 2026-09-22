<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriPengaduanModel;
use App\Models\PicModel;
use App\Models\TopikPengaduanModel;

class TopikPic extends BaseController
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

    public function index()
    {
        $rows = $this->topikModel
            ->select('topik_pengaduan.*, kategori_pengaduan.nama_kategori, pic.nama_pic')
            ->join('kategori_pengaduan', 'kategori_pengaduan.id = topik_pengaduan.kategori_id', 'left')
            ->join('pic', 'pic.id = topik_pengaduan.pic_id', 'left')
            ->orderBy('kategori_pengaduan.nama_kategori', 'ASC')
            ->orderBy('topik_pengaduan.nama_topik', 'ASC')
            ->findAll();

        return view('admin/topik-pic/index', [
            'title'        => 'Penugasan PIC',
            'active'       => 'topik-pic',
            'pageTitle'    => 'Penugasan PIC per Topik',
            'pageSubtitle' => 'Pengaduan masuk otomatis diteruskan ke PIC penanggung jawab topiknya',
            'list'         => $rows,
            'picOptions'   => $this->picModel->active(),
        ]);
    }

    public function update(int $id)
    {
        $picId = $this->request->getPost('pic_id') ?: null;

        $this->topikModel->update($id, ['pic_id' => $picId]);

        return redirect()->to(site_url('admin/topik-pic'))->with('success', 'PIC untuk topik berhasil diperbarui.');
    }
}