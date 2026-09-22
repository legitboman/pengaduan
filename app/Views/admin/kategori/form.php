<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<a href="<?= site_url('admin/kategori') ?>" class="d-inline-flex align-items-center gap-1 text-muted small mb-3">
  <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kategori
</a>

<div class="table-card p-4" style="max-width:560px;">
  <h2 class="h5 fw-bold mb-3">Edit Kategori</h2>

  <?php if (isset($validation)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($validation->getErrors() as $err): ?>
          <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('admin/kategori/update/' . $item['id']) ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label class="form-label">Nama Kategori <span class="req">*</span></label>
      <input type="text" name="nama_kategori"
             value="<?= esc(old('nama_kategori', $item['nama_kategori'])) ?>"
             class="form-control" required autofocus>
    </div>

    <div class="mb-4">
      <label class="form-label">Status</label>
      <select name="is_active" class="form-select">
        <option value="1" <?= $item['is_active'] ? 'selected' : '' ?>>Aktif</option>
        <option value="0" <?= ! $item['is_active'] ? 'selected' : '' ?>>Nonaktif</option>
      </select>
    </div>

    <div class="d-flex justify-content-end gap-2">
      <a href="<?= site_url('admin/kategori') ?>" class="btn btn-outline-secondary-lg">Batal</a>
      <button type="submit" class="btn btn-primary btn-primary-lg text-white">Simpan</button>
    </div>
  </form>
</div>

<?= $this->endSection() ?>