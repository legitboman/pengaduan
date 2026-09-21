<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MasyarakatModel;
use App\Models\PengaduanModel;

/**
 * Admin: Manajemen User (CRUD akun masyarakat) — DB-backed.
 */
class Masyarakat extends BaseController
{
    protected MasyarakatModel $masyarakatModel;
    protected PengaduanModel $pengaduanModel;

    public function __construct()
    {
        $this->masyarakatModel = new MasyarakatModel();
        $this->pengaduanModel  = new PengaduanModel();
    }

    /**
     * GET /admin/masyarakat
     */
    public function index()
    {
        $q = trim($this->request->getGet('q') ?? '');

        $builder = $this->masyarakatModel;
        if ($q !== '') {
            $builder = $builder->groupStart()->like('nama', $q)->orLike('email', $q)->groupEnd();
        }

        $rows = $builder->orderBy('nama', 'ASC')->findAll();

        $list = array_map(function ($row) {
            $row['total_pengaduan'] = $this->pengaduanModel->where('masyarakat_id', $row['id'])->countAllResults();

            return $row;
        }, $rows);

        return view('admin/masyarakat/index', [
            'title'        => 'Akun Masyarakat',
            'active'       => 'masyarakat',
            'pageTitle'    => 'Manajemen Akun Masyarakat',
            'pageSubtitle' => 'Kelola akun pengguna layanan pengaduan',
            'list'         => $list,
            'q'            => $q,
        ]);
    }

    /**
     * GET /admin/masyarakat/create
     */
    public function create()
    {
        return view('admin/masyarakat/form', [
            'title'        => 'Tambah Akun Masyarakat',
            'active'       => 'masyarakat',
            'pageTitle'    => 'Manajemen Akun Masyarakat',
            'pageSubtitle' => 'Tambah akun baru',
        ]);
    }

    /**
     * POST /admin/masyarakat/store
     */
    public function store()
    {
        $rules = [
            'nama'     => 'required',
            'email'    => 'permit_empty|valid_email|is_unique[masyarakat.email]',
            'no_hp'    => 'required|is_unique[masyarakat.no_hp]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return view('admin/masyarakat/form', [
                'title' => 'Tambah Akun Masyarakat', 'active' => 'masyarakat',
                'pageTitle' => 'Manajemen Akun Masyarakat', 'pageSubtitle' => 'Tambah akun baru',
                'validation' => $this->validator,
            ]);
        }

        $this->masyarakatModel->insert([
            'nama'      => $this->request->getPost('nama'),
            'nisn'      => $this->request->getPost('nisn') ?: null,
            'email'     => $this->request->getPost('email') ?: null,
            'no_hp'     => $this->request->getPost('no_hp'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active' => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(site_url('admin/masyarakat'))->with('success', 'Akun masyarakat berhasil ditambahkan.');
    }

    /**
     * GET /admin/masyarakat/edit/{id}
     */
    public function edit(int $id)
    {
        $item = $this->masyarakatModel->find($id);

        if (! $item) {
            return redirect()->to(site_url('admin/masyarakat'))->with('error', 'Akun tidak ditemukan.');
        }

        return view('admin/masyarakat/form', [
            'title'        => 'Edit Akun Masyarakat',
            'active'       => 'masyarakat',
            'pageTitle'    => 'Manajemen Akun Masyarakat',
            'pageSubtitle' => 'Edit akun ' . $item['nama'],
            'item'         => $item,
        ]);
    }

    /**
     * POST /admin/masyarakat/update/{id}
     */
    public function update(int $id)
    {
        $item = $this->masyarakatModel->find($id);
    
        if (! $item) {
            return redirect()
                ->to(site_url('admin/masyarakat'))
                ->with('error', 'Akun tidak ditemukan.');
        }
    
        $rules = [
            'nama'  => 'required',
            'email' => 'permit_empty|valid_email',
            'no_hp' => 'required',
        ];

        // Cek email hanya jika email diubah
        $email = trim($this->request->getPost('email') ?? '');

        if ($email !== '' && $email !== $item['email']) {
            $rules['email'] .= '|is_unique[masyarakat.email]';
        }

        // Cek no_hp hanya jika diubah (dipakai untuk login, harus tetap unik)
        $noHp = trim($this->request->getPost('no_hp') ?? '');

        if ($noHp !== $item['no_hp']) {
            $rules['no_hp'] .= '|is_unique[masyarakat.no_hp]';
        }

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Data belum lengkap, silakan periksa kembali.');
        }

        $payload = [
            'nama'      => trim($this->request->getPost('nama') ?? ''),
            'nisn'      => trim($this->request->getPost('nisn') ?? '') ?: null,
            'email'     => $email ?: null,
            'no_hp'     => $noHp,
            'is_active' => (int) $this->request->getPost('is_active'),
        ];
    
        $newPassword = $this->request->getPost('password');
    
        if (! empty($newPassword)) {
            $payload['password'] = password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );
        }
    
        $result = $this->masyarakatModel->update($id, $payload);
    
        if (! $result) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui akun: ' .
                    implode(', ', $this->masyarakatModel->errors())
                );
        }
    
        return redirect()
            ->to(site_url('admin/masyarakat'))
            ->with('success', 'Akun masyarakat berhasil diperbarui.');
    }

    /**
     * POST /admin/masyarakat/delete/{id}
     */
    public function delete(int $id)
    {
        $this->masyarakatModel->delete($id);

        return redirect()->to(site_url('admin/masyarakat'))->with('success', 'Akun masyarakat berhasil dihapus.');
    }
}
