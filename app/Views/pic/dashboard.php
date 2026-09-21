<?= $this->extend('layouts/pic') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:#1d4ed8;"><i class="bi bi-file-earmark-text"></i></span>
      <div>
        <div class="stat-value"><?= esc($stats['total'] ?? 0) ?></div>
        <div class="stat-label">Total Ditugaskan</div>
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
    <div class="fw-bold">Pengaduan Ditugaskan ke Saya</div>
  </div>
  <div class="table-responsive">
    <table class="table" id="tablePicRecent" style="width:100%;">
      <thead>
        <tr>
          <th>Nomor Tiket</th>
          <th>Judul Pengaduan</th>
          <th>Pelapor</th>
          <th>Topik</th>
          <th>Wilayah</th>
          <th>Tanggal</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recent)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengaduan yang ditugaskan kepada Anda.</td></tr>
        <?php endif; ?>

        <?php foreach (($recent ?? []) as $row): ?>
          <?php
            $map = ['Didisposisikan ke PIC' => 'menunggu', 'Menunggu Verifikasi' => 'menunggu', 'Dalam Penanganan' => 'proses', 'Selesai' => 'selesai', 'Ditolak' => 'ditolak'];
            $cls = $map[$row['status']] ?? 'menunggu';
          ?>
          <tr>
            <td class="fw-semibold">
              <a href="<?= site_url('pic/pengaduan/detail/' . $row['id']) ?>"><?= esc($row['nomor_tiket']) ?></a>
            </td>
            <td><?= esc($row['judul_pengaduan']) ?></td>
            <td><?= esc($row['nama_pelapor']) ?></td>
            <td><?= esc($row['topik']) ?></td>
            <td><?= esc($row['wilayah']) ?></td>
            <td><?= esc($row['tanggal']) ?></td>
            <td><span class="badge-status badge-<?= $cls ?>"><?= esc($row['status']) ?></span></td>
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
    $('#tablePicRecent').DataTable({ order: [], language: DATATABLE_ID_LANG });
  });
</script>
<?= $this->endSection() ?>
