<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasyarakatModel;

class Auth extends BaseController
{
    protected MasyarakatModel $masyarakatModel;

    public function __construct()
    {
        $this->masyarakatModel = new MasyarakatModel();
    }

    /**
     * GET /register
     */
    public function register()
    {
        if (session()->get('isMasyarakatLoggedIn')) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/register', ['title' => 'Daftar Akun']);
    }

    /**
     * POST /register
     *
     * Fields: nama, nisn, no_hp, email (opsional), password.
     * no_hp wajib & unik karena dipakai untuk login.
     */
    public function doRegister()
    {
        $rules = [
            'nama'                 => 'required|min_length[3]',
            'nisn'                 => 'permit_empty|max_length[30]|is_unique[masyarakat.nisn]',
            'no_hp'                => 'required|is_unique[masyarakat.no_hp]',
            'email'                => 'permit_empty|valid_email|is_unique[masyarakat.email]',
            'password'             => 'required|min_length[6]',
            'konfirmasi_password'  => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return view('auth/register', [
                'title'      => 'Daftar Akun',
                'validation' => $this->validator,
            ]);
        }

        $this->masyarakatModel->insert([
            'nama'      => $this->request->getPost('nama'),
            'nisn'      => $this->request->getPost('nisn') ?: null,
            'email'     => $this->request->getPost('email') ?: null,
            'no_hp'     => $this->request->getPost('no_hp'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active' => 1,
        ]);

        return redirect()->to(site_url('login'))->with('success', 'Akun berhasil dibuat. Silakan masuk.');
    }

    /**
     * GET /login
     */
    public function login()
    {
        if (session()->get('isMasyarakatLoggedIn')) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/login', ['title' => 'Masuk']);
    }

    /**
     * POST /login — login menggunakan NOMOR TELEPON + password.
     */
    public function doLogin()
    {
        $rules = [
            'no_hp'    => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth/login', ['title' => 'Masuk', 'validation' => $this->validator]);
        }

        $noHp     = $this->request->getPost('no_hp');
        $password = $this->request->getPost('password');

        $user = $this->masyarakatModel->findByNoHp($noHp);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Nomor telepon atau password salah.');
        }

        if ((int) $user['is_active'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
        }

        session()->set([
            'isMasyarakatLoggedIn' => true,
            'masyarakatId'         => $user['id'],
            'masyarakatNama'       => $user['nama'],
            'masyarakatEmail'      => $user['email'],
            'masyarakatNoHp'       => $user['no_hp'],
        ]);

        // Kembalikan ke halaman yang tadinya minta login (mis. buat pengaduan), jika ada.
        $redirectTo = $this->request->getPost('redirect_to');
        $target = ($redirectTo && strpos($redirectTo, site_url()) === 0) ? $redirectTo : site_url('/');

        return redirect()->to($target)->with('success', 'Berhasil masuk. Selamat datang, ' . $user['nama'] . '.');
    }

    /**
     * GET /logout
     */
    public function logout()
    {
        session()->remove(['isMasyarakatLoggedIn', 'masyarakatId', 'masyarakatNama', 'masyarakatEmail', 'masyarakatNoHp']);

        return redirect()->to(site_url('/'))->with('success', 'Anda telah keluar.');
    }
}
