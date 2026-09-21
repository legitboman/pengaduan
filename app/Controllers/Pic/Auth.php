<?php

namespace App\Controllers\Pic;

use App\Controllers\BaseController;
use App\Models\PicModel;

class Auth extends BaseController
{
    protected PicModel $picModel;

    public function __construct()
    {
        $this->picModel = new PicModel();
    }

    /**
     * GET /pic/login
     */
    public function login()
    {
        if (session()->get('isPicLoggedIn')) {
            return redirect()->to(site_url('pic'));
        }

        return view('pic/login');
    }

    /**
     * POST /pic/login — login menggunakan NIP + password.
     */
    public function attempt()
    {
        $rules = [
            'nip'      => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'NIP dan password wajib diisi.');
        }

        $nip      = $this->request->getPost('nip');
        $password = $this->request->getPost('password');

        $pic = $this->picModel->findByNip($nip);

        if (! $pic || ! password_verify($password, (string) $pic['password'])) {
            return redirect()->back()->withInput()->with('error', 'NIP atau password salah.');
        }

        if ((int) $pic['is_active'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
        }

        session()->set([
            'isPicLoggedIn' => true,
            'picId'         => $pic['id'],
            'picNama'       => $pic['nama_pic'],
            'picNip'        => $pic['nip'],
            'picJabatan'    => $pic['jabatan'],
        ]);

        return redirect()->to(site_url('pic'))->with('success', 'Berhasil masuk. Selamat datang, ' . $pic['nama_pic'] . '.');
    }

    /**
     * GET /pic/logout
     */
    public function logout()
    {
        session()->remove(['isPicLoggedIn', 'picId', 'picNama', 'picNip', 'picJabatan']);

        return redirect()->to(site_url('pic/login'))->with('success', 'Anda telah keluar.');
    }
}
