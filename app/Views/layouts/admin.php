<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Admin') ?> &mdash; Panel Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="admin-shell">

  <aside class="admin-sidebar">
    <div class="brand">
      <span class="brand-mark"><i class="bi bi-shield-check"></i></span>
      Admin Pengaduan
    </div>

    <div class="nav-section-label">Utama</div>
    <a href="<?= site_url('admin') ?>" class="nav-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>

    <div class="nav-section-label">Pengaduan</div>
    <a href="<?= site_url('admin/pengaduan') ?>" class="nav-link <?= ($active ?? '') === 'pengaduan' ? 'active' : '' ?>">
      <i class="bi bi-file-earmark-text"></i> List Pengaduan
    </a>
    <a href="<?= site_url('admin/kategori') ?>" class="nav-link <?= ($active ?? '') === 'kategori' ? 'active' : '' ?>">
      <i class="bi bi-folder2-open"></i> Kategori
    </a>
    <a href="<?= site_url('admin/topik') ?>" class="nav-link <?= ($active ?? '') === 'topik' ? 'active' : '' ?>">
      <i class="bi bi-tags"></i> Topik
    </a>
    <a href="<?= site_url('admin/topik-pic') ?>" class="nav-link <?= ($active ?? '') === 'topik-pic' ? 'active' : '' ?>">
      <i class="bi bi-diagram-3"></i> Penugasan PIC
    </a>

    <div class="nav-section-label">Manajemen Akun</div>
    <a href="<?= site_url('admin/masyarakat') ?>" class="nav-link <?= ($active ?? '') === 'masyarakat' ? 'active' : '' ?>">
      <i class="bi bi-people"></i> Akun Masyarakat
    </a>
    <a href="<?= site_url('admin/pic') ?>" class="nav-link <?= ($active ?? '') === 'pic' ? 'active' : '' ?>">
      <i class="bi bi-person-badge"></i> Akun PIC
    </a>

    <div class="nav-section-label">Lainnya</div>
    <a href="<?= site_url('admin/logout') ?>" class="nav-link">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
  </aside>

  <div class="admin-content">
    <div class="admin-topbar">
      <div>
        <h1 class="h5 mb-0 fw-bold"><?= esc($pageTitle ?? 'Dashboard') ?></h1>
        <div class="text-muted small"><?= esc($pageSubtitle ?? '') ?></div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="avatar-circle">AD</span>
        <div class="small">
          <div class="fw-semibold">Admin</div>
          <div class="text-muted">Super Admin</div>
        </div>
      </div>
    </div>

    <div class="admin-body">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= esc(session()->getFlashdata('success')) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= esc(session()->getFlashdata('error')) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
  const DATATABLE_ID_LANG = {
    search: 'Cari:',
    lengthMenu: 'Tampilkan _MENU_ data',
    info: 'Menampilkan _START_-_END_ dari _TOTAL_ data',
    infoEmpty: 'Tidak ada data',
    paginate: { previous: 'Sebelumnya', next: 'Berikutnya' },
    emptyTable: 'Belum ada data.',
    zeroRecords: 'Data tidak ditemukan.'
  };
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>