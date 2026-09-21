<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// -----------------------------------------------------------------
// Public site
// -----------------------------------------------------------------
$routes->get('/', 'Pengaduan::create');

$routes->group('pengaduan', static function (RouteCollection $routes) {
    $routes->get('create', 'Pengaduan::create');
    $routes->post('store', 'Pengaduan::store');
    $routes->get('success/(:segment)', 'Pengaduan::success/$1');
    $routes->get('lacak', 'Pengaduan::lacak');
    $routes->get('lacak/(:segment)', 'Pengaduan::lacakDetail/$1');
});

$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::doRegister');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');

$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');

// -----------------------------------------------------------------
// Admin (accessed via /admin)
// -----------------------------------------------------------------
$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login', 'Admin\Auth::attempt');
$routes->get('admin/logout', 'Admin\Auth::logout');

$routes->group('admin', ['filter' => 'adminAuth'], static function (RouteCollection $routes) {
    $routes->get('/', 'Admin\Dashboard::index');

    $routes->group('pengaduan', static function (RouteCollection $routes) {
        $routes->get('/', 'Admin\Pengaduan::index');
        $routes->get('detail/(:num)', 'Admin\Pengaduan::detail/$1');
        $routes->post('update-status/(:num)', 'Admin\Pengaduan::updateStatus/$1');
        $routes->post('update-klasifikasi/(:num)', 'Admin\Pengaduan::updateKlasifikasi/$1');
        $routes->post('tanggapan/(:num)', 'Admin\Pengaduan::tanggapan/$1');
        $routes->post('delete/(:num)', 'Admin\Pengaduan::delete/$1');
    });

    $routes->group('topik', static function (RouteCollection $routes) {
        $routes->get('/', 'Admin\Topik::index');
        $routes->post('update-pic/(:num)', 'Admin\Topik::updatePic/$1');
    });

    $routes->group('masyarakat', static function (RouteCollection $routes) {
        $routes->get('/', 'Admin\Masyarakat::index');
        $routes->get('create', 'Admin\Masyarakat::create');
        $routes->post('store', 'Admin\Masyarakat::store');
        $routes->get('edit/(:num)', 'Admin\Masyarakat::edit/$1');
        $routes->post('update/(:num)', 'Admin\Masyarakat::update/$1');
        $routes->post('delete/(:num)', 'Admin\Masyarakat::delete/$1');
    });

    $routes->group('pic', static function (RouteCollection $routes) {
        $routes->get('/', 'Admin\Pic::index');
        $routes->get('create', 'Admin\Pic::create');
        $routes->post('store', 'Admin\Pic::store');
        $routes->get('edit/(:num)', 'Admin\Pic::edit/$1');
        $routes->post('update/(:num)', 'Admin\Pic::update/$1');
        $routes->post('delete/(:num)', 'Admin\Pic::delete/$1');
    });
});

// -----------------------------------------------------------------
// Portal PIC (accessed via /pic) — login pakai NIP + password
// -----------------------------------------------------------------
$routes->get('pic/login', 'Pic\Auth::login');
$routes->post('pic/login', 'Pic\Auth::attempt');
$routes->get('pic/logout', 'Pic\Auth::logout');

$routes->group('pic', ['filter' => 'picAuth'], static function (RouteCollection $routes) {
    $routes->get('/', 'Pic\Dashboard::index');

    $routes->group('pengaduan', static function (RouteCollection $routes) {
        $routes->get('/', 'Pic\Pengaduan::index');
        $routes->get('detail/(:num)', 'Pic\Pengaduan::detail/$1');
        $routes->post('tanggapan/(:num)', 'Pic\Pengaduan::tanggapan/$1');
        $routes->post('sla/(:num)', 'Pic\Pengaduan::setSla/$1');
        $routes->post('verifikasi/(:num)', 'Pic\Pengaduan::verifikasi/$1');
        $routes->post('selesaikan/(:num)', 'Pic\Pengaduan::selesaikan/$1');
    });
});