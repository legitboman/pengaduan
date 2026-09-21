<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<a href="<?= site_url('admin/masyarakat') ?>" class="d-inline-flex align-items-center gap-1 text-muted small mb-3">
  <i class="bi bi-arrow-left"></i> Kembali ke Daftar Akun Masyarakat
</a>

<div class="table-card p-4" style="max-width:640px;">
  <h2 class="h5 fw-bold mb-3"><?= isset($item) ? 'Edit Akun Masyarakat' : 'Tambah Akun Masyarakat' ?></h2>

  <?php if (isset($validation)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($validation->getErrors() as $err): ?>
          <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= isset($item) ? site_url('admin/masyarakat/update/' . $item['id']) : site_url('admin/masyarakat/store') ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label class="form-label">Nama Lengkap <span class="req">*</span></label>
      <input type="text" name="nama" value="<?= esc(old('nama', $item['nama'] ?? '')) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">NISN</label>
      <input type="text" name="nisn" value="<?= esc(old('nisn', $item['nisn'] ?? '')) ?>" class="form-control" placeholder="Opsional">
    </div>

    <div class="mb-3">
      <label class="form-label">Email <span class="text-muted small">(opsional)</span></label>
      <input type="email" name="email" value="<?= esc(old('email', $item['email'] ?? '')) ?>" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">No. HP <span class="req">*</span></label>
      <input type="text" name="no_hp" value="<?= esc(old('no_hp', $item['no_hp'] ?? '')) ?>" class="form-control" required>
      <div class="form-text">Dipakai sebagai username login masyarakat.</div>
    </div>

    <div class="mb-3">
      <label class="form-label"><?= isset($item) ? 'Password Baru (opsional)' : 'Password' ?> <?= isset($item) ? '' : '<span class="req">*</span>' ?></label>
      <input type="password" name="password" class="form-control" <?= isset($item) ? '' : 'required' ?>
             placeholder="<?= isset($item) ? 'Kosongkan jika tidak ingin mengubah password' : '' ?>">
    </div>

    <div class="mb-4">
      <label class="form-label">Status Akun</label>
      <select name="is_active" class="form-select">
        <option value="1" <?= (($item['is_active'] ?? 1) == 1) ? 'selected' : '' ?>>Aktif</option>
        <option value="0" <?= (($item['is_active'] ?? 1) == 0) ? 'selected' : '' ?>>Nonaktif</option>
      </select>
    </div>

    <div class="d-flex justify-content-end gap-2">
      <a href="<?= site_url('admin/masyarakat') ?>" class="btn btn-outline-secondary-lg">Batal</a>
      <button type="submit" class="btn btn-primary btn-primary-lg text-white">Simpan</button>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
