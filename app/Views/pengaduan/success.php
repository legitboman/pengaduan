<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="success-wrap">

  <div class="success-check">
    <div class="inner"><i class="bi bi-check-lg"></i></div>
  </div>

  <h1 class="section-title">Pengaduan Berhasil Dikirim</h1>
  <p class="text-muted">Terima kasih, pengaduan Anda telah kami terima dan akan segera kami proses.</p>

  <div class="ticket-box">
    <div class="d-flex align-items-center gap-2 text-muted small mb-1">
      <i class="bi bi-ticket-perforated"></i> Nomor Tiket
    </div>
    <div class="ticket-no mb-3"><?= esc($pengaduan['nomor_tiket']) ?></div>

    <div class="row gy-3">
      <div class="col-6">
        <div class="info-row">
          <span class="info-icon"><i class="bi bi-grid"></i></span>
          <div>
            <div class="info-label">Kategori</div>
            <div class="info-value"><?= esc($pengaduan['kategori']) ?></div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="info-row">
          <span class="info-icon"><i class="bi bi-tag"></i></span>
          <div>
            <div class="info-label">Status</div>
            <div><span class="badge-status badge-menunggu"><?= esc($pengaduan['status']) ?></span></div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="info-row">
          <span class="info-icon"><i class="bi bi-file-earmark-text"></i></span>
          <div>
            <div class="info-label">Topik</div>
            <div class="info-value"><?= esc($pengaduan['topik']) ?></div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="info-row">
          <span class="info-icon"><i class="bi bi-clock-history"></i></span>
          <div>
            <div class="info-label">SLA</div>
            <div class="info-value">Respons awal maksimal <?= esc($pengaduan['sla_hari']) ?> hari kerja</div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="info-row">
          <span class="info-icon"><i class="bi bi-calendar-check"></i></span>
          <div>
            <div class="info-label">Tanggal Pengajuan</div>
            <div class="info-value"><?= esc($pengaduan['tanggal_pengajuan']) ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="notice-box notice-success">
        <i class="bi bi-envelope-check fs-5"></i>
        <div><strong>Email terkirim</strong><br>Notifikasi telah dikirim ke email Anda.</div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="notice-box notice-success">
        <i class="bi bi-whatsapp fs-5"></i>
        <div><strong>WhatsApp terkirim</strong><br>Notifikasi telah dikirim ke WhatsApp Anda.</div>
      </div>
    </div>
  </div>

  <div class="notice-box notice-info mb-4">
    <i class="bi bi-shield-lock fs-5"></i>
    <div>
      <strong>Simpan nomor tiket dan kode akses Anda</strong><br>
      Gunakan nomor tiket dan kode akses untuk melihat perkembangan pengaduan Anda.
      <div class="fw-bold mt-1">Kode Akses: <?= esc($pengaduan['kode_akses']) ?></div>
    </div>
  </div>

  <div class="d-flex flex-wrap justify-content-center gap-2">
    <a href="<?= site_url('pengaduan/lacak/' . $pengaduan['nomor_tiket']) ?>" class="btn btn-primary btn-primary-lg text-white">
      <i class="bi bi-eye me-1"></i> Lihat Detail Tiket
    </a>
    <a href="<?= site_url('pengaduan/unduh/' . $pengaduan['nomor_tiket']) ?>" class="btn btn-outline-secondary-lg">
      <i class="bi bi-download me-1"></i> Unduh Bukti Pengaduan
    </a>
    <a href="<?= site_url('/') ?>" class="btn btn-outline-secondary-lg">
      <i class="bi bi-house me-1"></i> Kembali ke Beranda
    </a>
  </div>

</div>

<?= $this->endSection() ?>
