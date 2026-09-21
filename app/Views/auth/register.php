<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="mx-auto" style="max-width: 480px;">
  <div class="text-center mb-4">
    <h1 class="section-title mb-1">Daftar Akun</h1>
    <p class="section-subtitle mb-0">Buat akun untuk mempermudah pengajuan dan pemantauan pengaduan Anda.</p>
  </div>

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
    <form method="post" action="<?= site_url('register') ?>">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Nama Lengkap <span class="req">*</span></label>
        <input type="text" name="nama" value="<?= esc(old('nama')) ?>" class="form-control" required autofocus>
      </div>

      <div class="mb-3">
        <label class="form-label">NISN</label>
        <input type="text" name="nisn" value="<?= esc(old('nisn')) ?>" class="form-control" placeholder="Opsional">
      </div>

      <div class="mb-3">
        <label class="form-label">No. HP <span class="req">*</span></label>
        <input type="text" name="no_hp" value="<?= esc(old('no_hp')) ?>" class="form-control" required>
        <div class="form-text">Nomor ini akan digunakan untuk masuk (login).</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email <span class="text-muted small">(opsional)</span></label>
        <input type="email" name="email" value="<?= esc(old('email')) ?>" class="form-control">
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label">Password <span class="req">*</span></label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Konfirmasi Password <span class="req">*</span></label>
          <input type="password" name="konfirmasi_password" class="form-control" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-primary-lg text-white w-100 mb-3">Daftar</button>

      <p class="text-center small text-muted mb-0">
        Sudah punya akun? <a href="<?= site_url('login') ?>">Masuk di sini</a>
      </p>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
