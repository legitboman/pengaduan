<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PicModel;

/**
 * Admin: Manajemen PIC (CRUD akun PIC) — DB-backed.
 */
class Pic extends BaseController
{
    protected PicModel $picModel;

    public function __construct()
    {
        $this->picModel = new PicModel();
    }

    /**
     * GET /admin/pic
     */
    public function index()
    {
        $q = trim($this->request->getGet('q') ?? '');

        $builder = $this->picModel;
        if ($q !== '') {
            $builder = $builder->groupStart()->like('nama_pic', $q)->orLike('jabatan', $q)->orLike('nip', $q)->groupEnd();
        }

        return view('admin/pic/index', [
            'title'        => 'Akun PIC',
            'active'       => 'pic',
            'pageTitle'    => 'Manajemen Akun PIC',
            'pageSubtitle' => 'Kelola akun penanggung jawab penanganan pengaduan',
            'list'         => $builder->orderBy('nama_pic', 'ASC')->findAll(),
            'q'            => $q,
        ]);
    }

    /**
     * GET /admin/pic/create
     */
    public function create()
    {
        return view('admin/pic/form', [
            'title'        => 'Tambah Akun PIC',
            'active'       => 'pic',
            'pageTitle'    => 'Manajemen Akun PIC',
            'pageSubtitle' => 'Tambah akun baru',
        ]);
    }

    /**
     * POST /admin/pic/store
     */
    public function store()
    {
        $rules = [
            'nama_pic' => 'required',
            'nip'      => 'required|is_unique[pic.nip]',
            'jabatan'  => 'required',
            'email'    => 'required|valid_email|is_unique[pic.email]',
            'no_hp'    => 'required',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return view('admin/pic/form', [
                'title' => 'Tambah Akun PIC', 'active' => 'pic',
                'pageTitle' => 'Manajemen Akun PIC', 'pageSubtitle' => 'Tambah akun baru',
                'validation' => $this->validator,
            ]);
        }

        $this->picModel->insert([
            'nama_pic'  => $this->request->getPost('nama_pic'),
            'nip'       => $this->request->getPost('nip'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'email'     => $this->request->getPost('email'),
            'no_hp'     => $this->request->getPost('no_hp'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active' => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(site_url('admin/pic'))->with('success', 'Akun PIC berhasil ditambahkan.');
    }

    /**
     * GET /admin/pic/edit/{id}
     */
    public function edit(int $id)
    {
        $item = $this->picModel->find($id);

        if (! $item) {
            return redirect()->to(site_url('admin/pic'))->with('error', 'Akun PIC tidak ditemukan.');
        }

        return view('admin/pic/form', [
            'title'        => 'Edit Akun PIC',
            'active'       => 'pic',
            'pageTitle'    => 'Manajemen Akun PIC',
            'pageSubtitle' => 'Edit akun ' . $item['nama_pic'],
            'item'         => $item,
        ]);
    }

    /**
     * POST /admin/pic/update/{id}
     */
    public function update(int $id)
    {
        $rules = [
            'nama_pic' => 'required',
            'nip'      => 'required|is_unique[pic.nip,id,' . $id . ']',
            'jabatan'  => 'required',
            'email'    => 'required|valid_email|is_unique[pic.email,id,' . $id . ']',
            'no_hp'    => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data belum lengkap, silakan periksa kembali.');
        }

        $payload = [
            'nama_pic'  => $this->request->getPost('nama_pic'),
            'nip'       => $this->request->getPost('nip'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'email'     => $this->request->getPost('email'),
            'no_hp'     => $this->request->getPost('no_hp'),
            'is_active' => (int) $this->request->getPost('is_active'),
        ];

        $newPassword = $this->request->getPost('password');
        if (! empty($newPassword)) {
            $payload['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->picModel->update($id, $payload);

        return redirect()->to(site_url('admin/pic'))->with('success', 'Akun PIC berhasil diperbarui.');
    }

    /**
     * POST /admin/pic/delete/{id}
     */
    public function delete(int $id)
    {
        $this->picModel->delete($id);

        return redirect()->to(site_url('admin/pic'))->with('success', 'Akun PIC berhasil dihapus.');
    }
}
