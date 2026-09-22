<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriPengaduanModel;
use App\Models\PicModel;
use App\Models\TopikPengaduanModel;

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

    public function index()
    {
        $q = trim($this->request->getGet('q') ?? '');

        $builder = $this->topikModel
            ->select('topik_pengaduan.*, kategori_pengaduan.nama_kategori, pic.nama_pic')
            ->join('kategori_pengaduan', 'kategori_pengaduan.id = topik_pengaduan.kategori_id', 'left')
            ->join('pic', 'pic.id = topik_pengaduan.pic_id', 'left')
            ->orderBy('kategori_pengaduan.nama_kategori', 'ASC')
            ->orderBy('topik_pengaduan.nama_topik', 'ASC');

        if ($q !== '') {
            $builder->groupStart()
                ->like('topik_pengaduan.nama_topik', $q)
                ->orLike('kategori_pengaduan.nama_kategori', $q)
                ->groupEnd();
        }

        return view('admin/topik/index', [
            'title'        => 'Topik Pengaduan',
            'active'       => 'topik',
            'pageTitle'    => 'Manajemen Topik',
            'pageSubtitle' => 'Kelola topik pengaduan',
            'list'         => $builder->findAll(),
            'q'            => $q,
        ]);
    }

    public function create()
    {
        return view('admin/topik/form', [
            'title'           => 'Tambah Topik',
            'active'          => 'topik',
            'pageTitle'       => 'Manajemen Topik',
            'pageSubtitle'    => 'Tambah topik baru',
            'kategoriOptions' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'picOptions'      => $this->picModel->active(),
        ]);
    }

    public function store()
    {
        $rules = [
            'kategori_id' => 'required',
            'nama_topik'  => 'required|min_length[3]|max_length[150]',
            'deskripsi'   => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return view('admin/topik/form', [
                'title'           => 'Tambah Topik',
                'active'          => 'topik',
                'pageTitle'       => 'Manajemen Topik',
                'pageSubtitle'    => 'Tambah topik baru',
                'kategoriOptions' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
                'picOptions'      => $this->picModel->active(),
                'validation'      => $this->validator,
            ]);
        }

        $this->topikModel->insert([
            'kategori_id' => (int) $this->request->getPost('kategori_id'),
            'pic_id'      => $this->request->getPost('pic_id') ?: null,
            'nama_topik'  => trim($this->request->getPost('nama_topik')),
            'deskripsi'   => trim($this->request->getPost('deskripsi') ?? '') ?: null,
            'is_active'   => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(site_url('admin/topik'))->with('success', 'Topik berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $item = $this->topikModel->withKategoriAndPic($id);

        if (! $item) {
            return redirect()->to(site_url('admin/topik'))->with('error', 'Topik tidak ditemukan.');
        }

        return view('admin/topik/form', [
            'title'           => 'Edit Topik',
            'active'          => 'topik',
            'pageTitle'       => 'Manajemen Topik',
            'pageSubtitle'    => 'Edit ' . $item['nama_topik'],
            'item'            => $item,
            'kategoriOptions' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'picOptions'      => $this->picModel->active(),
        ]);
    }

    public function update(int $id)
    {
        $item = $this->topikModel->find($id);

        if (! $item) {
            return redirect()->to(site_url('admin/topik'))->with('error', 'Topik tidak ditemukan.');
        }

        $rules = [
            'kategori_id' => 'required',
            'nama_topik'  => 'required|min_length[3]|max_length[150]',
            'deskripsi'   => 'permit_empty|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->topikModel->update($id, [
            'kategori_id' => (int) $this->request->getPost('kategori_id'),
            'pic_id'      => $this->request->getPost('pic_id') ?: null,
            'nama_topik'  => trim($this->request->getPost('nama_topik')),
            'deskripsi'   => trim($this->request->getPost('deskripsi') ?? '') ?: null,
            'is_active'   => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(site_url('admin/topik'))->with('success', 'Topik berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->topikModel->delete($id);

        return redirect()->to(site_url('admin/topik'))->with('success', 'Topik berhasil dihapus.');
    }
}