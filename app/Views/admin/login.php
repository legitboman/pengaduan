<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="login-shell">
  <div class="card-panel" style="width:100%; max-width:400px;">
    <div class="text-center mb-4">
      <span class="brand-mark d-inline-flex mb-2" style="width:48px;height:48px;font-size:1.3rem;"><i class="bi bi-shield-check"></i></span>
      <h1 class="h5 fw-bold mb-0">Panel Admin</h1>
      <p class="text-muted small">Layanan Pengaduan Ketenagakerjaan</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('admin/login') ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required autofocus>
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary btn-primary-lg text-white w-100">Masuk</button>
    </form>
  </div>
</div>
</body>
</html>
