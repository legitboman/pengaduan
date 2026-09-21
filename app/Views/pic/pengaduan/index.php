<?= $this->extend('layouts/pic') ?>

<?= $this->section('content') ?>

<div class="table-card">
  <div class="table-card-header">
    <div>
      <div class="fw-bold">Tiket Pengaduan Saya</div>
      <div class="text-muted small">Daftar ini hanya berisi pengaduan yang ditugaskan kepada Anda.</div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table" id="tablePicTiket" style="width:100%;">
      <thead>
        <tr>
          <th>Nomor Tiket</th>
          <th>Judul Pengaduan</th>
          <th>Pelapor</th>
          <th>Topik</th>
          <th>Tanggal</th>
          <th>Verifikasi</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($list)): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pengaduan yang ditugaskan kepada Anda.</td></tr>
        <?php endif; ?>

        <?php foreach (($list ?? []) as $row): ?>
          <?php
            $map = ['Didisposisikan ke PIC' => 'menunggu', 'Menunggu Verifikasi' => 'menunggu', 'Dalam Penanganan' => 'proses', 'Selesai' => 'selesai', 'Ditolak' => 'ditolak'];
            $cls = $map[$row['status']] ?? 'menunggu';

            $vMap = ['Valid' => 'selesai', 'Tidak Valid' => 'ditolak', 'Bukan Kewenangan' => 'ditolak'];
            $vCls = $vMap[$row['validasi']] ?? 'menunggu';
          ?>
          <tr>
            <td class="fw-semibold text-primary"><?= esc($row['nomor_tiket']) ?></td>
            <td><?= esc($row['judul_pengaduan']) ?></td>
            <td><?= esc($row['nama_pelapor']) ?></td>
            <td><?= esc($row['topik']) ?></td>
            <td><?= esc($row['tanggal']) ?></td>
            <td><span class="badge-status badge-<?= $vCls ?>"><?= esc($row['validasi']) ?></span></td>
            <td><span class="badge-status badge-<?= $cls ?>"><?= esc($row['status']) ?></span></td>
            <td class="text-end">
              <a href="<?= site_url('pic/pengaduan/detail/' . $row['id']) ?>" class="action-icon-btn text-primary" title="Proses Tiket">
                <i class="bi bi-arrow-right-circle"></i>
              </a>
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
    $('#tablePicTiket').DataTable({
      order: [],
      columnDefs: [{ orderable: false, targets: 7 }],
      language: DATATABLE_ID_LANG
    });
  });
</script>
<?= $this->endSection() ?>
