<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasyarakatModel;

class Profile extends BaseController
{
    protected MasyarakatModel $masyarakatModel;

    public function __construct()
    {
        $this->masyarakatModel = new MasyarakatModel();
    }

    private function requireLogin()
    {
        if (! session()->get('isMasyarakatLoggedIn')) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan masuk terlebih dahulu.');
        }
        return null;
    }

    /**
     * GET /profile
     */
    public function index()
    {
        if ($guard = $this->requireLogin()) return $guard;

        $user = $this->masyarakatModel->find(session()->get('masyarakatId'));

        if (! $user) {
            return redirect()->to(site_url('logout'));
        }

    return view('pengaduan/profile', [
        'title' => 'Profile Saya',
        'user'  => $user,
    ]);
    }

    /**
     * POST /profile/update
     */
    public function update()
    {
        if ($guard = $this->requireLogin()) return $guard;

        $id   = (int) session()->get('masyarakatId');
        $user = $this->masyarakatModel->find($id);

        if (! $user) {
            return redirect()->to(site_url('logout'));
        }

        $rules = [
            'nama'  => 'required|min_length[3]',
            'no_hp' => 'required',
            'email' => 'permit_empty|valid_email',
        ];

        $email = trim($this->request->getPost('email') ?? '');
        $noHp  = trim($this->request->getPost('no_hp') ?? '');

        if ($email !== '' && $email !== ($user['email'] ?? '')) {
            $rules['email'] .= '|is_unique[masyarakat.email]';
        }

        if ($noHp !== $user['no_hp']) {
            $rules['no_hp'] .= '|is_unique[masyarakat.no_hp]';
        }

        $newPassword = $this->request->getPost('password');

        if (! empty($newPassword)) {
            $rules['password']            = 'min_length[6]';
            $rules['konfirmasi_password'] = 'matches[password]';
        }

        if (! $this->validate($rules)) {
            return view('profile/index', [
                'title'      => 'Profile Saya',
                'user'       => $user,
                'validation' => $this->validator,
            ]);
        }

        $payload = [
            'nama'  => trim($this->request->getPost('nama')),
            'email' => $email ?: null,
            'no_hp' => $noHp,
        ];

        if (! empty($newPassword)) {
            $payload['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->masyarakatModel->update($id, $payload);

        session()->set([
            'masyarakatNama'  => $payload['nama'],
            'masyarakatEmail' => $payload['email'],
            'masyarakatNoHp'  => $payload['no_hp'],
        ]);

        return redirect()->to(site_url('index.php/profile'))->with('success', 'Profile berhasil diperbarui.');
    }
}