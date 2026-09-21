<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:#1d4ed8;"><i class="bi bi-file-earmark-text"></i></span>
      <div>
        <div class="stat-value"><?= esc($stats['total'] ?? 0) ?></div>
        <div class="stat-label">Total Pengaduan</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:#d97706;"><i class="bi bi-hourglass-split"></i></span>
      <div>
        <div class="stat-value"><?= esc($stats['menunggu'] ?? 0) ?></div>
        <div class="stat-label">Baru / Belum Diproses</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:#0891b2;"><i class="bi bi-arrow-repeat"></i></span>
      <div>
        <div class="stat-value"><?= esc($stats['proses'] ?? 0) ?></div>
        <div class="stat-label">Dalam Penanganan</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:#16a34a;"><i class="bi bi-check2-circle"></i></span>
      <div>
        <div class="stat-value"><?= esc($stats['selesai'] ?? 0) ?></div>
        <div class="stat-label">Selesai</div>
      </div>
    </div>
  </div>
</div>

<div class="table-card">
  <div class="table-card-header">
    <div class="fw-bold">Pengaduan Terbaru</div>
    <a href="<?= site_url('admin/pengaduan') ?>" class="small">Lihat semua &rarr;</a>
  </div>
  <div class="table-responsive">
    <table class="table" id="tableDashboardRecent" style="width:100%;">
      <thead>
        <tr>
          <th>Nomor Tiket</th>
          <th>Judul Pengaduan</th>
          <th>Pelapor</th>
          <th>Topik</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($recent ?? []) as $row): ?>
          <tr>
            <td class="fw-semibold text-primary"><?= esc($row['nomor_tiket']) ?></td>
            <td><?= esc($row['judul_pengaduan']) ?></td>
            <td><?= esc($row['nama_pelapor']) ?></td>
            <td><?= esc($row['topik']) ?></td>
            <td>
              <?php
                $map = ['Didisposisikan ke PIC' => 'menunggu', 'Menunggu Verifikasi' => 'menunggu', 'Dalam Penanganan' => 'proses', 'Selesai' => 'selesai', 'Ditolak' => 'ditolak'];
                $cls = $map[$row['status']] ?? 'menunggu';
              ?>
              <span class="badge-status badge-<?= $cls ?>"><?= esc($row['status']) ?></span>
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
    $('#tableDashboardRecent').DataTable({
      order: [],
      paging: false,
      searching: false,
      info: false,
      language: DATATABLE_ID_LANG
    });
  });
</script>
<?= $this->endSection() ?>
