<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriPengaduanModel;
use App\Models\TopikPengaduanModel;

class Kategori extends BaseController
{
    protected KategoriPengaduanModel $kategoriModel;
    protected TopikPengaduanModel $topikModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriPengaduanModel();
        $this->topikModel    = new TopikPengaduanModel();
    }

    public function index()
    {
        $rows = $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        $list = array_map(function ($row) {
            $row['jumlah_topik'] = $this->topikModel
                ->where('kategori_id', $row['id'])
                ->countAllResults();
            return $row;
        }, $rows);

        return view('admin/kategori/index', [
            'title'        => 'Kategori Pengaduan',
            'active'       => 'kategori',
            'pageTitle'    => 'Manajemen Kategori',
            'pageSubtitle' => 'Kelola kategori layanan pengaduan',
            'list'         => $list,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]|is_unique[kategori_pengaduan.nama_kategori]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error_store', implode(' ', $this->validator->getErrors()));
        }

        $this->kategoriModel->insert([
            'nama_kategori' => trim($this->request->getPost('nama_kategori')),
            'is_active'     => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(site_url('admin/kategori'))->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $item = $this->kategoriModel->find($id);

        if (! $item) {
            return redirect()->to(site_url('admin/kategori'))->with('error', 'Kategori tidak ditemukan.');
        }

        return view('admin/kategori/form', [
            'title'        => 'Edit Kategori',
            'active'       => 'kategori',
            'pageTitle'    => 'Manajemen Kategori',
            'pageSubtitle' => 'Edit ' . $item['nama_kategori'],
            'item'         => $item,
        ]);
    }

    public function update(int $id)
    {
        $item = $this->kategoriModel->find($id);

        if (! $item) {
            return redirect()->to(site_url('admin/kategori'))->with('error', 'Kategori tidak ditemukan.');
        }

        $namaBaru = trim($this->request->getPost('nama_kategori'));
        $rules    = ['nama_kategori' => 'required|min_length[3]|max_length[100]'];

        if ($namaBaru !== $item['nama_kategori']) {
            $rules['nama_kategori'] .= '|is_unique[kategori_pengaduan.nama_kategori]';
        }

        if (! $this->validate($rules)) {
            return view('admin/kategori/form', [
                'title'        => 'Edit Kategori',
                'active'       => 'kategori',
                'pageTitle'    => 'Manajemen Kategori',
                'pageSubtitle' => 'Edit ' . $item['nama_kategori'],
                'item'         => $item,
                'validation'   => $this->validator,
            ]);
        }

        $this->kategoriModel->update($id, [
            'nama_kategori' => $namaBaru,
            'is_active'     => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(site_url('admin/kategori'))->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $jumlahTopik = $this->topikModel->where('kategori_id', $id)->countAllResults();

        if ($jumlahTopik > 0) {
            return redirect()->to(site_url('admin/kategori'))
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki ' . $jumlahTopik . ' topik terkait.');
        }

        $this->kategoriModel->delete($id);

        return redirect()->to(site_url('admin/kategori'))->with('success', 'Kategori berhasil dihapus.');
    }
}