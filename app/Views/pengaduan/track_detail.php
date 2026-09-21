<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="mx-auto" style="max-width: 900px;">

  <a href="<?= site_url('pengaduan/lacak') ?>" class="d-inline-flex align-items-center gap-1 text-muted small mb-3">
    <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Pengaduan
  </a>

  <div class="text-center mb-4">
    <h1 class="section-title mb-1"><?= esc($tiket['judul_pengaduan']) ?></h1>
    <p class="section-subtitle mb-0">Nomor Tiket: <strong><?= esc($tiket['nomor_tiket']) ?></strong></p>
  </div>

  <div class="stepper">
    <?php foreach ($tiket['tahapan'] as $i => $tahap): ?>
      <div class="step <?= $tahap['state'] ?>">
        <div class="line"></div>
        <div class="dot">
          <?= $tahap['state'] === 'done' ? '<i class="bi bi-check-lg"></i>' : ($i + 1) ?>
        </div>
        <div class="step-title"><?= esc($i + 1) ?>. <?= esc($tahap['nama']) ?></div>
        <div class="step-status <?= $tahap['state'] === 'done' ? 'selesai' : ($tahap['state'] === 'active' ? 'proses' : 'menunggu') ?>">
          <?= esc($tahap['label_status']) ?>
        </div>
        <?php if (!empty($tahap['tanggal'])): ?>
          <div class="text-muted" style="font-size:.72rem;"><?= esc($tahap['tanggal']) ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="meta-grid" style="grid-template-columns: repeat(5, 1fr);">
    <div class="meta-item">
      <div class="meta-icon"><i class="bi bi-clipboard-check"></i></div>
      <div class="meta-label">Status Pengaduan</div>
      <div class="meta-value text-primary"><?= esc($tiket['status_pengaduan']) ?></div>
    </div>
    <div class="meta-item">
      <div class="meta-icon"><i class="bi bi-person-badge"></i></div>
      <div class="meta-label">PIC Penanggung Jawab</div>
      <div class="meta-value"><?= esc($tiket['pic']) ?></div>
    </div>

    <div class="meta-item">
      <div class="meta-icon"><i class="bi bi-clock"></i></div>
      <div class="meta-label">Target Penyelesaian</div>
      <div class="meta-value">
        <?php if (! empty($tiket['target_selesai'])): ?>
          <?= esc(date('d M Y', strtotime($tiket['target_selesai']))) ?>
          <span class="text-muted small">(<?= esc($tiket['sla_hari']) ?> hari)</span>
        <?php else: ?>
          <span class="text-muted">Belum ditetapkan</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="meta-item">
      <div class="meta-icon"><i class="bi bi-calendar2-check"></i></div>
      <div class="meta-label">Update Terakhir</div>
      <div class="meta-value"><?= esc($tiket['update_terakhir']) ?></div>
    </div>
  </div>

  <!-- Catatan update dari petugas -->
  <div class="catatan-wrap mt-4">
    <h2 class="h6 fw-bold mb-3" style="color:#344767;">
      <i class="bi bi-journal-text me-2 text-primary"></i>Catatan Update Pengaduan
    </h2>

    <?php if (empty($catatan)): ?>
      <div class="catatan-kosong">
        <i class="bi bi-inbox text-muted" style="font-size:1.6rem;"></i>
        <p class="mb-0 mt-2 text-muted small">Belum ada catatan dari petugas. Anda akan melihat pembaruan di sini.</p>
      </div>
    <?php else: ?>
      <div class="catatan-list">
        <?php foreach ($catatan as $c): ?>
          <div class="catatan-item">
            <div class="catatan-header">
              <span class="catatan-nama">
                <?php if ($c['tipe'] === 'balasan'): ?>
                  <i class="bi bi-chat-left-text text-primary me-1"></i> Balasan Petugas
                <?php else: ?>
                  <i class="bi bi-arrow-repeat text-success me-1"></i> Pembaruan Status
                <?php endif; ?>
              </span>
              <span class="catatan-waktu">
                <i class="bi bi-clock me-1"></i><?= esc(date('d F Y H.i', strtotime($c['waktu']))) ?> WIB
              </span>
            </div>
            <div class="catatan-isi">
              <?= nl2br(esc($c['isi'])) ?>
              <div class="text-muted mt-2" style="font-size:.75rem;">
                &mdash; <?= esc($c['oleh']) ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Dokumen hasil penanganan -->
  <?php if (! empty($buktiPenyelesaian)): ?>
    <div class="catatan-wrap mt-4">
      <h2 class="h6 fw-bold mb-3" style="color:#344767;">
        <i class="bi bi-folder-check me-2 text-success"></i>Dokumen Hasil Penanganan
      </h2>
      <div class="catatan-list">
        <?php foreach ($buktiPenyelesaian as $file): ?>
          <div class="catatan-item">
            <div class="catatan-isi d-flex align-items-center gap-2">
              <i class="bi bi-file-earmark-check text-success"></i>
              <div>
                <div class="fw-semibold" style="font-size:.85rem;"><?= esc($file['nama_file']) ?></div>
                <?php if (! empty($file['keterangan'])): ?>
                  <div class="text-muted" style="font-size:.75rem;"><?= esc($file['keterangan']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

</div>

<style>
.catatan-wrap {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 14px;
  padding: 22px 26px;
  box-shadow: 0 2px 10px rgba(0,0,0,.04);
}

.catatan-kosong {
  text-align: center;
  padding: 28px 0;
}

.catatan-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.catatan-item {
  border: 1px solid #e9ecef;
  border-radius: 10px;
  overflow: hidden;
}

.catatan-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
  padding: 10px 16px;
  background: #f8fafc;
  border-bottom: 1px solid #e9ecef;
}

.catatan-nama {
  font-weight: 700;
  font-size: .88rem;
  color: #212529;
}

.catatan-waktu {
  font-size: .78rem;
  color: #6c757d;
}

.catatan-isi {
  padding: 12px 16px;
  font-size: .875rem;
  color: #344767;
  line-height: 1.6;
}

.catatan-empty {
  color: #adb5bd;
  font-style: italic;
}
</style>

<?= $this->endSection() ?>