<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="w-100 px-3 px-md-4 riwayat-container">

  <!-- Header -->



  <!-- Table Card -->

  <div class="table-card history-card">



<div class="table-responsive">
  <table class="table history-table align-middle mb-0" id="tableRiwayat" style="width:100%;">

    <thead>
      <tr>
        <th>Nomor Tiket</th>
        <th>Judul Pengaduan</th>
        <th>Topik</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>

    <tbody>

      <?php if (empty($riwayat)): ?>

        <tr>
          <td colspan="6" class="empty-state">
            <div class="empty-icon">
              <i class="bi bi-inbox"></i>
            </div>

            <div class="fw-semibold mt-3">
              Belum Ada Pengaduan
            </div>

            <div class="text-muted small mt-1">
              Anda belum pernah membuat pengaduan.
            </div>
          </td>
        </tr>

      <?php endif; ?>


      <?php foreach (($riwayat ?? []) as $row): ?>

        <?php

          $map = [
            'Didisposisikan ke PIC' => 'menunggu',
            'Menunggu Verifikasi' => 'menunggu',
            'Dalam Penanganan'   => 'proses',
            'Selesai'            => 'selesai',
            'Ditolak'            => 'ditolak'
          ];

          $cls = $map[$row['status']] ?? 'menunggu';

        ?>

        <tr>

          <!-- Nomor Tiket -->
          <td>
            <span class="ticket-number">
              <i class="bi bi-ticket-perforated me-1"></i>
              <?= esc($row['nomor_tiket']) ?>
            </span>
          </td>


          <!-- Judul -->
          <td>
            <div class="complaint-title">
              <?= esc($row['judul_pengaduan']) ?>
            </div>
          </td>


          <!-- Topik -->
          <td>
            <span class="topic-badge">
              <i class="bi bi-tag me-1"></i>
              <?= esc($row['topik']) ?>
            </span>
          </td>


          <!-- Tanggal -->
          <td>
            <div class="date-info">
              <i class="bi bi-calendar3 me-1"></i>
              <?= esc($row['tanggal']) ?>
            </div>
          </td>


          <!-- Status -->
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


          <!-- Aksi -->
          <td class="text-center">

            <a
              href="<?= site_url('pengaduan/lacak/' . $row['nomor_tiket']) ?>"
              class="btn-detail"
              title="Lihat progres pengaduan"
            >
              <i class="bi bi-eye"></i>
              <span>Lihat Progres</span>
            </a>

          </td>

        </tr>

      <?php endforeach; ?>

    </tbody>

  </table>
</div>

  </div>

</div>

<style>

/* =========================================================
   CONTAINER
========================================================= */

.riwayat-container {
  padding-bottom: 40px;
}


/* =========================================================
   TABLE CARD
========================================================= */

.history-card {
  background: #fff;
  border-radius: 16px;
  border: 1px solid #e9ecef;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,.05);
}


/* =========================================================
   HEADER
========================================================= */

.history-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  background: #f8fafc;
  border-bottom: 1px solid #e9ecef;
}

.history-header h5 {
  color: #212529;
}

.history-header small {
  color: #6c757d;
}


/* =========================================================
   TABLE
========================================================= */

.history-table {
  margin: 0;
}

.history-table thead th {
  background: #f8fafc;
  color: #495057;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .3px;
  padding: 15px 18px;
  border-bottom: 1px solid #dee2e6;
  white-space: nowrap;
}

.history-table tbody td {
  padding: 17px 18px;
  border-bottom: 1px solid #f0f1f3;
  vertical-align: middle;
}

.history-table tbody tr {
  transition: background .2s ease;
}

.history-table tbody tr:hover {
  background: #f8fbff;
}

.history-table tbody tr:last-child td {
  border-bottom: none;
}


/* =========================================================
   TICKET
========================================================= */

.ticket-number {
  display: inline-flex;
  align-items: center;
  padding: 7px 10px;
  border-radius: 8px;
  background: #eef5ff;
  color: #0d6efd;
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
}


/* =========================================================
   JUDUL
========================================================= */

.complaint-title {
  max-width: 260px;
  color: #212529;
  font-weight: 600;
  line-height: 1.4;
}


/* =========================================================
   TOPIC
========================================================= */

.topic-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 10px;
  border-radius: 7px;
  background: #f1f3f5;
  color: #495057;
  font-size: 12px;
  font-weight: 600;
}


/* =========================================================
   DATE
========================================================= */

.date-info {
  display: flex;
  align-items: center;
  color: #6c757d;
  font-size: 13px;
  white-space: nowrap;
}


/* =========================================================
   STATUS
========================================================= */

.badge-status {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 11px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  white-space: nowrap;
}


/* Menunggu */
.badge-menunggu {
  background: #fff4d6;
  color: #9a6700;
}


/* Dalam proses */
.badge-proses {
  background: #e7f1ff;
  color: #0b5ed7;
}


/* Selesai */
.badge-selesai {
  background: #e4f7ec;
  color: #198754;
}


/* Ditolak */
.badge-ditolak {
  background: #fde8e8;
  color: #dc3545;
}


/* =========================================================
   BUTTON DETAIL
========================================================= */

.btn-detail {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;

  padding: 8px 13px;

  border: 1px solid #dee2e6;
  border-radius: 8px;

  background: #fff;
  color: #495057;

  font-size: 13px;
  font-weight: 600;

  text-decoration: none;

  transition:
    background .2s ease,
    color .2s ease,
    border-color .2s ease,
    transform .2s ease;
}

.btn-detail:hover {
  background: #0d6efd;
  border-color: #0d6efd;
  color: #fff;
  transform: translateY(-1px);
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
  padding: 55px 20px !important;
  text-align: center;
  color: #6c757d;
}

.empty-icon {
  width: 60px;
  height: 60px;

  margin: 0 auto;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #f1f3f5;

  color: #adb5bd;

  font-size: 26px;
}


/* =========================================================
   DATATABLE
========================================================= */

.dataTables_wrapper {
  padding: 18px 20px 20px;
}


/* Search */

.dataTables_filter {
  margin-bottom: 15px;
}

.dataTables_filter label {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #495057;
  font-size: 13px;
  font-weight: 600;
}

.dataTables_filter input {
  margin-left: 0 !important;
  min-width: 220px;

  padding: 8px 12px;

  border: 1px solid #dee2e6;
  border-radius: 8px;

  outline: none;

  transition: border-color .2s, box-shadow .2s;
}

.dataTables_filter input:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 3px rgba(13,110,253,.1);
}


/* Length */

.dataTables_length {
  margin-bottom: 15px;
}

.dataTables_length label {
  color: #6c757d;
  font-size: 13px;
}

.dataTables_length select {
  margin: 0 5px;

  padding: 6px 30px 6px 10px;

  border: 1px solid #dee2e6;
  border-radius: 7px;
}


/* Info */

.dataTables_info {
  padding-top: 15px !important;
  color: #6c757d;
  font-size: 13px;
}


/* Pagination */

.dataTables_paginate {
  padding-top: 10px !important;
}

.dataTables_paginate .paginate_button {
  border-radius: 7px !important;
  margin: 0 2px;
}

.dataTables_paginate .paginate_button.current {
  background: #0d6efd !important;
  border-color: #0d6efd !important;
  color: #fff !important;
}


/* =========================================================
   SORT ICON
========================================================= */

.history-table thead th {
  cursor: pointer;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

  .history-header {
    padding: 16px;
  }

  .history-table thead th,
  .history-table tbody td {
    padding: 13px 12px;
  }

  .complaint-title {
    max-width: 200px;
  }

  .btn-detail span {
    display: none;
  }

  .btn-detail {
    width: 36px;
    height: 36px;
    padding: 0;
  }

}

</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<link
  rel="stylesheet"
  href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"
>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>

$(function () {

  $('#tableRiwayat').DataTable({

    order: [],

    pageLength: 10,

    lengthMenu: [
      [5, 10, 25, 50],
      [5, 10, 25, 50]
    ],

    language: {

      search: 'Cari pengaduan:',

      searchPlaceholder: 'Nomor tiket atau judul...',

      lengthMenu: 'Tampilkan _MENU_ data',

      info: 'Menampilkan _START_–_END_ dari _TOTAL_ pengaduan',

      infoEmpty: 'Tidak ada pengaduan',

      infoFiltered: '(difilter dari _MAX_ total pengaduan)',

      paginate: {
        previous: '‹',
        next: '›'
      },

      emptyTable: 'Anda belum pernah membuat pengaduan.',

      zeroRecords: 'Data pengaduan tidak ditemukan.'

    }

  });

});

</script>

<?= $this->endSection() ?>
