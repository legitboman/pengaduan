<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    /**
     * GET /admin/login
     */
    public function login()
    {
        if (session()->get('isAdminLoggedIn')) {
            return redirect()->to(site_url('admin'));
        }

        return view('admin/login');
    }

    /**
     * POST /admin/login
     */
    public function attempt()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->where('username', $username)->first();

        if (! $admin || ! password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        session()->set([
            'isAdminLoggedIn' => true,
            'adminId'         => $admin['id'],
            'adminUsername'   => $admin['username'],
            'adminNama'       => $admin['nama'],
        ]);

        return redirect()->to(site_url('admin'));
    }

    /**
     * GET /admin/logout
     */
    public function logout()
    {
        session()->remove(['isAdminLoggedIn', 'adminId', 'adminUsername', 'adminNama']);

        return redirect()->to(site_url('admin/login'))->with('success', 'Anda telah keluar.');
    }
}
