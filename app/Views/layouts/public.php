<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Layanan Pengaduan Ketenagakerjaan') ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="admin-shell">

  <aside class="admin-sidebar">
    <div class="brand">
      <span class="brand-mark"><i class="bi bi-shield-check"></i></span>
      Pengaduan Ketenagakerjaan
    </div>

    <div class="nav-section-label">Layanan</div>
    <a href="<?= site_url('pengaduan/create') ?>" class="nav-link <?= uri_string() === 'pengaduan/create' || uri_string() === '' ? 'active' : '' ?>">
      <i class="bi bi-file-earmark-plus"></i> Buat Pengaduan
    </a>
    <a href="<?= site_url('pengaduan/lacak') ?>" class="nav-link <?= str_starts_with(uri_string(), 'pengaduan/lacak') ? 'active' : '' ?>">
      <i class="bi bi-search"></i> Riwayat Pengaduan
    </a>

    <?php if (session()->get('isMasyarakatLoggedIn')): ?>
      <div class="nav-section-label">Akun Saya</div>
      <a href="<?= site_url('profile') ?>" class="nav-link <?= uri_string() === 'profile' ? 'active' : '' ?>">
        <i class="bi bi-person-circle"></i> Profile Saya
      </a>
      <a href="<?= site_url('logout') ?>" class="nav-link">
        <i class="bi bi-box-arrow-right"></i> Keluar
      </a>
    <?php else: ?>
      <div class="nav-section-label">Akun</div>
      <a href="<?= site_url('login') ?>" class="nav-link <?= uri_string() === 'login' ? 'active' : '' ?>">
        <i class="bi bi-box-arrow-in-right"></i> Masuk
      </a>
      <a href="<?= site_url('register') ?>" class="nav-link <?= uri_string() === 'register' ? 'active' : '' ?>">
        <i class="bi bi-person-plus"></i> Daftar
      </a>
    <?php endif; ?>
  </aside>

  <div class="admin-content">
    <div class="admin-topbar">
      <div>
        <h1 class="h5 mb-0 fw-bold"><?= esc($title ?? 'Layanan Pengaduan') ?></h1>
        <div class="text-muted small">Dinas Tenaga Kerja Provinsi Jawa Timur</div>
      </div>

      <?php if (session()->get('isMasyarakatLoggedIn')): ?>
        <div class="dropdown">
          <button class="profile-trigger d-flex align-items-center gap-2 border-0 bg-transparent p-0"
                  type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar-circle"><?= esc(strtoupper(substr(session()->get('masyarakatNama'), 0, 2))) ?></span>
            <div class="small text-start d-none d-sm-block">
              <div class="fw-semibold"><?= esc(session()->get('masyarakatNama')) ?></div>
              <div class="text-muted"><?= esc(session()->get('masyarakatEmail') ?: session()->get('masyarakatNoHp')) ?></div>
            </div>
            <i class="bi bi-chevron-down text-muted small d-none d-sm-block"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end profile-dropdown shadow-sm">
            <li>
              <div class="px-3 py-2 border-bottom">
                <div class="fw-semibold text-dark small"><?= esc(session()->get('masyarakatNama')) ?></div>
                <div class="text-muted" style="font-size:.75rem;"><?= esc(session()->get('masyarakatEmail') ?: session()->get('masyarakatNoHp')) ?></div>
              </div>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= site_url('profile') ?>">
                <i class="bi bi-person-circle text-primary"></i> Profile Saya
              </a>
            </li>
            <li><hr class="dropdown-divider my-1"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" href="<?= site_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i> Keluar
              </a>
            </li>
          </ul>
        </div>
      <?php endif; ?>
    </div>

    <div class="admin-body">
      <?php if (session()->getFlashdata('success') && ! isset($suppressGlobalFlash)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= esc(session()->getFlashdata('success')) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </div>
  </div>

</div>

<style>
.profile-trigger {
  cursor: pointer;
  border-radius: 8px;
  padding: 4px 8px !important;
  transition: background .2s;
}
.profile-trigger:hover {
  background: #f1f3f5 !important;
}
.profile-dropdown {
  min-width: 220px;
  border-radius: 12px;
  border: 1px solid #e9ecef;
  margin-top: 6px;
}
.profile-dropdown .dropdown-item {
  font-size: .875rem;
  border-radius: 6px;
  margin: 2px 6px;
  width: calc(100% - 12px);
}
.profile-dropdown .dropdown-item:hover {
  background: #f8f9fa;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>