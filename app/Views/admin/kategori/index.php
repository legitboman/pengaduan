<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

{{-- Form tambah —— langsung kelihatan di atas tabel --}}
<div class="table-card p-4 mb-3" id="formTambah">
  <div class="fw-bold mb-3">Tambah Kategori Baru</div>

  <?php if (session()->getFlashdata('error_store')): ?>
    <div class="alert alert-danger py-2"><?= esc(session()->getFlashdata('error_store')) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('admin/kategori/store') ?>">
    <?= csrf_field() ?>
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label mb-1">Nama Kategori <span class="req">*</span></label>
        <input type="text" name="nama_kategori" value="<?= esc(old('nama_kategori')) ?>"
               class="form-control" placeholder="cth. Pelanggaran Upah" required autofocus>
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1">Status</label>
        <select name="is_active" class="form-select">
          <option value="1">Aktif</option>
          <option value="0">Nonaktif</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary text-white w-100">
          <i class="bi bi-plus-lg me-1"></i> Tambah
        </button>
      </div>
    </div>
  </form>
</div>

{{-- Tabel daftar kategori --}}
<div class="table-card">
  <div class="table-card-header">
    <div class="fw-bold">Daftar Kategori</div>
  </div>

  <div class="table-responsive">
    <table class="table" id="tableKategori" style="width:100%;">
      <thead>
        <tr>
          <th>Nama Kategori</th>
          <th class="text-center">Jumlah Topik</th>
          <th class="text-center">Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($list)): ?>
          <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
        <?php endif; ?>

        <?php foreach ($list as $row): ?>
          <tr>
            <td class="fw-semibold"><?= esc($row['nama_kategori']) ?></td>

            <td class="text-center">
              <span class="badge rounded-pill text-bg-primary"><?= $row['jumlah_topik'] ?></span>
            </td>

            <td class="text-center">
              <?php if ($row['is_active']): ?>
                <span class="badge-status badge-selesai">Aktif</span>
              <?php else: ?>
                <span class="badge-status badge-ditolak">Nonaktif</span>
              <?php endif; ?>
            </td>

            <td class="text-end">
              <div class="d-inline-flex gap-2">
                <a href="<?= site_url('admin/kategori/edit/' . $row['id']) ?>"
                   class="action-icon-btn text-primary" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <button type="button" class="action-icon-btn text-danger" title="Hapus"
                        data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">
                  <i class="bi bi-trash"></i>
                </button>
              </div>

              <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-body p-4 text-center">
                      <i class="bi bi-exclamation-triangle text-danger fs-1 mb-2"></i>
                      <h5 class="fw-bold">Hapus Kategori?</h5>
                      <?php if ($row['jumlah_topik'] > 0): ?>
                        <p class="text-muted">
                          <strong><?= esc($row['nama_kategori']) ?></strong> tidak bisa dihapus
                          karena masih memiliki <strong><?= $row['jumlah_topik'] ?> topik</strong> terkait.
                        </p>
                        <button type="button" class="btn btn-outline-secondary-lg" data-bs-dismiss="modal">Tutup</button>
                      <?php else: ?>
                        <p class="text-muted">
                          Kategori <strong><?= esc($row['nama_kategori']) ?></strong> akan dihapus permanen.
                        </p>
                        <form action="<?= site_url('admin/kategori/delete/' . $row['id']) ?>" method="post"
                              class="d-flex justify-content-center gap-2">
                          <?= csrf_field() ?>
                          <button type="button" class="btn btn-outline-secondary-lg" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(function () {
    $('#tableKategori').DataTable({ order: [], language: DATATABLE_ID_LANG });
  });
</script>
<?= $this->endSection() ?>