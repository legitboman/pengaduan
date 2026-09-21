<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="pengaduan-admin-page">

  <div class="table-card admin-table-card">

    <div class="table-card-header admin-table-header">

      <div class="header-info">
        <div class="header-icon">
          <i class="bi bi-clipboard-data"></i>
        </div>

        <div>
          <div class="fw-bold fs-6">Daftar Pengaduan</div>
          <small class="text-muted">
            Data seluruh pengaduan yang telah masuk ke sistem
          </small>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2">
        <form
          action="<?= site_url('admin/pengaduan') ?>"
          method="get"
          class="filter-form"
        >

          <div class="search-box">
            <i class="bi bi-search"></i>

            <input
              type="text"
              name="q"
              value="<?= esc($q ?? '') ?>"
              class="form-control"
              placeholder="Cari judul atau pelapor..."
            >
          </div>

          <select name="status" class="form-select status-filter">

            <option value="">Semua Status</option>

            <?php foreach ([
              'Didisposisikan ke PIC',
              'Menunggu Verifikasi',
              'Dalam Penanganan',
              'Selesai',
              'Ditolak'
            ] as $s): ?>

              <option
                value="<?= esc($s) ?>"
                <?= ($status ?? '') === $s ? 'selected' : '' ?>
              >
                <?= esc($s) ?>
              </option>

            <?php endforeach; ?>

          </select>

          <button type="submit" class="btn-filter">
            <i class="bi bi-funnel"></i>
            <span>Filter</span>
          </button>

          <?php if (!empty($q) || !empty($status)): ?>

            <a
              href="<?= site_url('admin/pengaduan') ?>"
              class="btn-reset"
              title="Reset Filter"
            >
              <i class="bi bi-arrow-counterclockwise"></i>
            </a>

          <?php endif; ?>

        </form>

        <!-- TOMBOL EXCEL -->
        <a 
          href="<?= site_url('admin/pengaduan/exportExcel?q=' . urlencode($q ?? '') . '&status=' . urlencode($status ?? '')) ?>" 
          class="btn btn-success d-flex align-items-center gap-1"
          title="Export ke Excel"
        >
          <i class="bi bi-file-earmark-excel"></i>
          <span>Excel</span>
        </a>
      </div>

    </div>

    <div class="table-responsive">

      <table
        class="table admin-table align-middle mb-0"
        id="tablePengaduan"
        style="width:100%;"
      >

        <thead>
          <tr>
            <th>Nomor Tiket</th>
            <th>Judul Pengaduan</th>
            <th>Pelapor</th>
            <th>Topik</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>

        <tbody>

          <?php if (empty($list)): ?>

            <tr>
              <td colspan="7" class="empty-state">

                <div class="empty-icon">
                  <i class="bi bi-inbox"></i>
                </div>

                <div class="fw-semibold mt-3">
                  Belum Ada Pengaduan
                </div>

                <div class="small mt-1">
                  Belum ada data pengaduan yang tersedia.
                </div>

              </td>
            </tr>

          <?php endif; ?>

          <?php foreach (($list ?? []) as $row): ?>

            <?php

              $map = [
                'Didisposisikan ke PIC' => 'menunggu',
                'Didisposisikan ke PIC' => 'menunggu',
            'Menunggu Verifikasi' => 'menunggu',
                'Dalam Penanganan'   => 'proses',
                'Selesai'            => 'selesai',
                'Ditolak'            => 'ditolak'
              ];

              $cls = $map[$row['status']] ?? 'menunggu';

            ?>

            <tr>

              <td>
                <span class="ticket-badge">
                  <i class="bi bi-ticket-perforated"></i>
                  <?= esc($row['nomor_tiket']) ?>
                </span>
              </td>

              <td>
                <div class="complaint-title">
                  <?= esc($row['judul_pengaduan']) ?>
                </div>
              </td>

              <td>
                <div class="reporter-info">

                  <span class="avatar-circle">
                    <?= esc(strtoupper(substr($row['nama_pelapor'], 0, 1))) ?>
                  </span>

                  <span class="reporter-name">
                    <?= esc($row['nama_pelapor']) ?>
                  </span>

                </div>
              </td>

              <td>
                <span class="topic-badge">
                  <i class="bi bi-tag"></i>
                  <?= esc($row['topik']) ?>
                </span>
              </td>

              <td>
                <div class="date-info">
                  <i class="bi bi-calendar3"></i>
                  <?= esc($row['tanggal']) ?>
                </div>
              </td>

              <td>

                <span class="badge-status badge-<?= $cls ?>">

                  <?php if ($cls === 'menunggu'): ?>
                    <i class="bi bi-hourglass-split"></i>
                  <?php elseif ($cls === 'proses'): ?>
                    <i class="bi bi-arrow-repeat"></i>
                  <?php elseif ($cls === 'selesai'): ?>
                    <i class="bi bi-check-circle"></i>
                  <?php elseif ($cls === 'ditolak'): ?>
                    <i class="bi bi-x-circle"></i>
                  <?php endif; ?>

                  <?= esc($row['status']) ?>

                </span>

              </td>

              <td class="text-center">

                <div class="action-buttons">

                  <a
                    href="<?= site_url('admin/pengaduan/detail/' . $row['id']) ?>"
                    class="action-btn action-detail"
                    title="Detail"
                  >
                    <i class="bi bi-eye"></i>
                  </a>

                  <button
                    type="button"
                    class="action-btn action-delete"
                    title="Hapus"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal<?= $row['id'] ?>"
                  >
                    <i class="bi bi-trash"></i>
                  </button>

                </div>

                <div
                  class="modal fade"
                  id="deleteModal<?= $row['id'] ?>"
                  tabindex="-1"
                  aria-hidden="true"
                >

                  <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content delete-modal">

                      <div class="modal-body p-4 text-center">

                        <div class="delete-icon">
                          <i class="bi bi-trash3"></i>
                        </div>

                        <h5 class="fw-bold mt-4 mb-2">
                          Hapus Pengaduan?
                        </h5>

                        <p class="text-muted mb-4">
                          Tiket
                          <strong><?= esc($row['nomor_tiket']) ?></strong>
                          akan dihapus permanen dan tidak dapat dikembalikan.
                        </p>

                        <form
                          action="<?= site_url('admin/pengaduan/delete/' . $row['id']) ?>"
                          method="post"
                          class="d-flex justify-content-center gap-2"
                        >

                          <?= csrf_field() ?>

                          <button
                            type="button"
                            class="btn-modal-cancel"
                            data-bs-dismiss="modal"
                          >
                            Batal
                          </button>

                          <button
                            type="submit"
                            class="btn-modal-delete"
                          >
                            <i class="bi bi-trash me-1"></i>
                            Ya, Hapus
                          </button>

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

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
$(function () {

  $('#tablePengaduan').DataTable({
    order: [],
    columnDefs: [
      {
        orderable: false,
        targets: 6
      }
    ],
    language: DATATABLE_ID_LANG
  });

});
</script>

<?= $this->endSection() ?>