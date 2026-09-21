<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="mx-auto" style="max-width: 440px;">
  <div class="text-center mb-4">
    <h1 class="section-title mb-1">Masuk</h1>
    <p class="section-subtitle mb-0">Masuk untuk membuat dan memantau pengaduan Anda.</p>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <?php if (isset($validation)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($validation->getErrors() as $err): ?>
          <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card-panel">
    <form method="post" action="<?= site_url('login') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="redirect_to" value="<?= esc($_GET['redirect'] ?? '') ?>">

      <div class="mb-3">
        <label class="form-label">Nomor Telepon <span class="req">*</span></label>
        <input type="text" name="no_hp" value="<?= esc(old('no_hp')) ?>" class="form-control" placeholder="Contoh: 081234567890" required autofocus>
      </div>

      <div class="mb-4">
        <label class="form-label">Password <span class="req">*</span></label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary btn-primary-lg text-white w-100 mb-3">Masuk</button>

      <p class="text-center small text-muted mb-0">
        Belum punya akun? <a href="<?= site_url('register') ?>">Daftar di sini</a>
      </p>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
