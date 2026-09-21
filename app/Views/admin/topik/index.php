<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="topik-admin-page">

  <div class="table-card">

    <div class="table-card-header topik-header">
      <div class="header-info">
        <div class="header-icon">
          <i class="bi bi-tags"></i>
        </div>

        <div>
          <div class="fw-bold">Daftar Topik Pengaduan</div>
          <div class="text-muted small">
            Setiap topik memiliki satu PIC penanggung jawab.
            Pengaduan yang masuk dengan topik ini <strong>otomatis langsung menjadi
            tiket PIC tersebut</strong>, tanpa perlu penugasan atau persetujuan admin.
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table admin-table" id="tableTopik" style="width:100%;">
        <thead>
          <tr>
            <th>Kategori</th>
            <th>Topik</th>
            <th class="pic-column">PIC Penanggung Jawab</th>
          </tr>
        </thead>

        <tbody>

          <?php if (empty($list)): ?>

            <tr>
              <td colspan="3">
                <div class="empty-state">
                  <div class="empty-state-icon">
                    <i class="bi bi-tags"></i>
                  </div>
                  <div class="empty-state-title">
                    Belum ada data topik
                  </div>
                  <div class="empty-state-text">
                    Data topik pengaduan belum tersedia.
                  </div>
                </div>
              </td>
            </tr>

          <?php endif; ?>

          <?php foreach (($list ?? []) as $row): ?>

            <tr>

              <td>
                <span class="category-badge">
                  <i class="bi bi-folder2-open me-1"></i>
                  <?= esc($row['nama_kategori'] ?? '-') ?>
                </span>
              </td>

              <td>
                <div class="topic-name">
                  <?= esc($row['nama_topik']) ?>
                </div>
              </td>

              <td>
                <form
                  action="<?= site_url('admin/topik/update-pic/' . $row['id']) ?>"
                  method="post"
                  class="pic-form"
                >

                  <?= csrf_field() ?>

                  <select
                    name="pic_id"
                    class="form-select form-select-sm pic-select"
                  >
                    <option value="">Belum ditentukan</option>

                    <?php foreach (($picOptions ?? []) as $pic): ?>

                      <option
                        value="<?= esc($pic['id']) ?>"
                        <?= $row['pic_id'] == $pic['id'] ? 'selected' : '' ?>
                      >
                        <?= esc($pic['nama_pic']) ?>
                        &mdash;
                        <?= esc($pic['jabatan']) ?>
                      </option>

                    <?php endforeach; ?>

                  </select>

                  <button
                    type="submit"
                    class="btn btn-sm btn-primary text-white btn-save-pic"
                  >
                    <i class="bi bi-check2 me-1"></i>
                    Simpan
                  </button>

                </form>
              </td>

            </tr>

          <?php endforeach; ?>

        </tbody>
      </table>
    </div>

  </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>



<script>
  $(function () {
    $('#tableTopik').DataTable({
      order: [],
      columnDefs: [
        {
          orderable: false,
          targets: 2
        }
      ],
      language: DATATABLE_ID_LANG
    });
  });
</script>



<?= $this->endSection() ?>