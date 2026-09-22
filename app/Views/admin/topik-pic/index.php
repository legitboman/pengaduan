<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="table-card">
  <div class="table-card-header">
    <div class="fw-bold">Penugasan PIC per Topik</div>
    <div class="text-muted small">Perubahan langsung berlaku untuk pengaduan baru</div>
  </div>

  <div class="table-responsive">
    <table class="table" id="tableTopikPic" style="width:100%;">
      <thead>
        <tr>
          <th>Kategori</th>
          <th>Topik</th>
          <th>PIC Penanggung Jawab</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($list)): ?>
          <tr><td colspan="3" class="text-center text-muted py-4">Belum ada topik.</td></tr>
        <?php endif; ?>

        <?php foreach ($list as $row): ?>
          <tr>
            <td>
              <span class="badge rounded-pill text-bg-secondary">
                <?= esc($row['nama_kategori'] ?? '-') ?>
              </span>
            </td>

            <td class="fw-semibold"><?= esc($row['nama_topik']) ?></td>

            <td>
              <form method="post" action="<?= site_url('admin/topik-pic/update/' . $row['id']) ?>"
                    class="d-flex align-items-center gap-2">
                <?= csrf_field() ?>
                <select name="pic_id" class="form-select form-select-sm" style="max-width:300px;">
                  <option value="">Belum ditentukan</option>
                  <?php foreach ($picOptions as $pic): ?>
                    <option value="<?= esc($pic['id']) ?>"
                      <?= (int) $row['pic_id'] === (int) $pic['id'] ? 'selected' : '' ?>>
                      <?= esc($pic['nama_pic']) ?> &mdash; <?= esc($pic['jabatan']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-sm btn-primary text-white">
                  <i class="bi bi-check2"></i> Simpan
                </button>
              </form>
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
    $('#tableTopikPic').DataTable({
      order: [],
      columnDefs: [{ orderable: false, targets: 2 }],
      language: DATATABLE_ID_LANG,
    });
  });
</script>
<?= $this->endSection() ?>