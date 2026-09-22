<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<a href="<?= site_url('admin/topik') ?>" class="d-inline-flex align-items-center gap-1 text-muted small mb-3">
  <i class="bi bi-arrow-left"></i> Kembali ke Daftar Topik
</a>

<div class="table-card p-4" style="max-width:640px;">
  <h2 class="h5 fw-bold mb-3"><?= isset($item) ? 'Edit Topik' : 'Tambah Topik' ?></h2>

  <?php if (isset($validation)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($validation->getErrors() as $err): ?>
          <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= isset($item) ? site_url('admin/topik/update/' . $item['id']) : site_url('admin/topik/store') ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label class="form-label">Kategori <span class="req">*</span></label>
      <select name="kategori_id" class="form-select" required>
        <option value="">— Pilih kategori —</option>
        <?php foreach ($kategoriOptions as $k): ?>
          <option value="<?= esc($k['id']) ?>"
            <?= (int) old('kategori_id', $item['kategori_id'] ?? 0) === (int) $k['id'] ? 'selected' : '' ?>>
            <?= esc($k['nama_kategori']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Topik <span class="req">*</span></label>
      <input type="text" name="nama_topik"
             value="<?= esc(old('nama_topik', $item['nama_topik'] ?? '')) ?>"
             class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Deskripsi <span class="text-muted small">(opsional)</span></label>
      <input type="text" name="deskripsi"
             value="<?= esc(old('deskripsi', $item['deskripsi'] ?? '')) ?>"
             class="form-control" placeholder="Penjelasan singkat topik ini">
    </div>

    <div class="mb-3">
      <label class="form-label">PIC Penanggung Jawab</label>
      <select name="pic_id" class="form-select">
        <option value="">Belum ditentukan</option>
        <?php foreach ($picOptions as $pic): ?>
          <option value="<?= esc($pic['id']) ?>"
            <?= (int) old('pic_id', $item['pic_id'] ?? 0) === (int) $pic['id'] ? 'selected' : '' ?>>
            <?= esc($pic['nama_pic']) ?> &mdash; <?= esc($pic['jabatan']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-4">
      <label class="form-label">Status</label>
      <select name="is_active" class="form-select">
        <option value="1" <?= (int) old('is_active', $item['is_active'] ?? 1) === 1 ? 'selected' : '' ?>>Aktif</option>
        <option value="0" <?= (int) old('is_active', $item['is_active'] ?? 1) === 0 ? 'selected' : '' ?>>Nonaktif</option>
      </select>
    </div>

    <div class="d-flex justify-content-end gap-2">
      <a href="<?= site_url('admin/topik') ?>" class="btn btn-outline-secondary-lg">Batal</a>
      <button type="submit" class="btn btn-primary btn-primary-lg text-white">Simpan</button>
    </div>
  </form>
</div>

<?= $this->endSection() ?>