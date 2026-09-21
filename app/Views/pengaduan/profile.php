<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="mx-auto" style="max-width: 600px;">

  <div class="text-center mb-4">
    <div class="profile-avatar-wrap mx-auto mb-3">
      <?= esc(strtoupper(substr($user['nama'], 0, 2))) ?>
    </div>
    <h1 class="section-title mb-1"><?= esc($user['nama']) ?></h1>
    <p class="section-subtitle mb-0"><?= esc($user['email'] ?: $user['no_hp']) ?></p>
  </div>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= esc(session()->getFlashdata('success')) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
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
    <form method="post" action="<?= site_url('index.php/profile/update') ?>">
      <?= csrf_field() ?>

      <h2 class="h6 fw-bold mb-4 pb-2" style="border-bottom: 1px solid #e9ecef;">Informasi Data Diri</h2>

      <div class="mb-3">
        <label class="form-label">Nama Lengkap <span class="req">*</span></label>
        <input type="text" name="nama" value="<?= esc(old('nama', $user['nama'])) ?>" class="form-control" required autofocus>
      </div>

      <div class="mb-3">
        <label class="form-label">No. HP / WhatsApp <span class="req">*</span></label>
        <input type="text" name="no_hp" value="<?= esc(old('no_hp', $user['no_hp'])) ?>" class="form-control" required>
        <div class="form-text">Nomor ini digunakan untuk masuk (login).</div>
      </div>

      <div class="mb-4">
        <label class="form-label">Email <span class="text-muted small">(opsional)</span></label>
        <input type="email" name="email" value="<?= esc(old('email', $user['email'] ?? '')) ?>" class="form-control">
      </div>

      <h2 class="h6 fw-bold mb-3 pb-2" style="border-bottom: 1px solid #e9ecef;">Ubah Password</h2>
      <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengubah password.</p>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" id="passwordInput" class="form-control" autocomplete="new-password">
        </div>
        <div class="col-md-6">
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" name="konfirmasi_password" id="konfirmasiInput" class="form-control" autocomplete="new-password">
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center pt-2">
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger btn-sm">
          <i class="bi bi-box-arrow-right me-1"></i> Keluar
        </a>
        <button type="submit" class="btn btn-primary btn-primary-lg text-white">
          <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
        </button>
      </div>

    </form>
  </div>

  <div class="text-center mt-3">
    <div class="text-muted small">
      Akun dibuat pada: <?= esc(date('d F Y', strtotime($user['created_at']))) ?>
      &nbsp;|&nbsp;
      Diperbarui: <?= esc($user['updated_at'] ? date('d F Y H:i', strtotime($user['updated_at'])) : '-') ?>
    </div>
  </div>

</div>

<style>
.profile-avatar-wrap {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0d6efd, #6610f2);
  color: #fff;
  font-size: 28px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  letter-spacing: 1px;
}
</style>

<?= $this->endSection() ?>