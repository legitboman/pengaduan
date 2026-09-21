<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="table-card">
  <div class="table-card-header">
    <div class="fw-bold">Daftar Akun PIC</div>
    <div class="d-flex gap-2">
      <form action="<?= site_url('admin/pic') ?>" method="get" class="d-flex gap-2">
        <input type="text" name="q" value="<?= esc($q ?? '') ?>" class="form-control form-control-sm" placeholder="Cari nama / jabatan..." style="width:220px;">
        <button class="btn btn-sm btn-outline-secondary-lg"><i class="bi bi-search"></i></button>
      </form>
      <a href="<?= site_url('admin/pic/create') ?>" class="btn btn-sm btn-primary text-white">
        <i class="bi bi-plus-lg me-1"></i> Tambah PIC
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table" id="tablePic" style="width:100%;">
      <thead>
        <tr>
          <th>Nama PIC</th>
          <th>NIP</th>
          <th>Jabatan</th>
          <th>Email</th>
          <th>No. HP</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($list)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada akun PIC.</td></tr>
        <?php endif; ?>

        <?php foreach (($list ?? []) as $row): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="avatar-circle"><?= esc(strtoupper(substr($row['nama_pic'], 0, 1))) ?></span>
                <?= esc($row['nama_pic']) ?>
              </div>
            </td>
            <td><?= esc($row['nip'] ?? '-') ?></td>
            <td><?= esc($row['jabatan']) ?></td>
            <td><?= esc($row['email']) ?></td>
            <td><?= esc($row['no_hp']) ?></td>
            <td>
              <?php if ($row['is_active']): ?>
                <span class="badge-status badge-selesai">Aktif</span>
              <?php else: ?>
                <span class="badge-status badge-ditolak">Nonaktif</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <div class="d-inline-flex gap-2">
                <a href="<?= site_url('admin/pic/edit/' . $row['id']) ?>" class="action-icon-btn text-primary" title="Edit">
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
                      <h5 class="fw-bold">Hapus Akun PIC?</h5>
                      <p class="text-muted">Akun <strong><?= esc($row['nama_pic']) ?></strong> akan dihapus permanen.</p>
                      <form action="<?= site_url('admin/pic/delete/' . $row['id']) ?>" method="post" class="d-flex justify-content-center gap-2">
                        <?= csrf_field() ?>
                        <button type="button" class="btn btn-outline-secondary-lg" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                      </form>
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
    $('#tablePic').DataTable({ order: [], language: DATATABLE_ID_LANG });
  });
</script>
<?= $this->endSection() ?>
