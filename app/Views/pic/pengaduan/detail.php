<?= $this->extend('layouts/pic') ?>

<?= $this->section('content') ?>

<?php
  $map  = ['Didisposisikan ke PIC' => 'menunggu', 'Menunggu Verifikasi' => 'menunggu', 'Dalam Penanganan' => 'proses', 'Selesai' => 'selesai', 'Ditolak' => 'ditolak'];
  $cls  = $map[$row['status_akhir']] ?? 'menunggu';
  $vMap = ['Valid' => 'selesai', 'Tidak Valid' => 'ditolak', 'Bukan Kewenangan' => 'ditolak'];
  $vCls = $vMap[$row['status_validasi'] ?? ''] ?? 'menunggu';
  $isClosed = in_array($row['status_akhir'], ['Selesai', 'Ditolak'], true);
?>

<a href="<?= site_url('pic/pengaduan') ?>" class="d-inline-flex align-items-center gap-1 text-muted small mb-3">
  <i class="bi bi-arrow-left"></i> Kembali ke Tiket Saya
</a>

<div class="row g-3">

  <!-- ============ KIRI: Isi pengaduan ============ -->
  <div class="col-lg-7">
    <div class="table-card p-4 mb-3">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="text-muted small">Nomor Tiket</div>
          <div class="fw-bold text-primary fs-5"><?= esc($row['nomor_tiket']) ?></div>
        </div>
        <div class="text-end">
          <span class="badge-status badge-<?= $cls ?>"><?= esc($row['status_akhir']) ?></span>
          <div class="mt-1">
            <span class="badge-status badge-<?= $vCls ?>"><?= esc($row['status_validasi'] ?? 'Belum Diverifikasi') ?></span>
          </div>
        </div>
      </div>

      <h2 class="h5 fw-bold"><?= esc($row['judul_pengaduan']) ?></h2>

      <div class="row g-3 my-2">
        <div class="col-md-6">
          <div class="text-muted small">Kategori</div>
          <div class="fw-semibold"><?= esc($row['nama_kategori'] ?? '-') ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Topik</div>
          <div class="fw-semibold"><?= esc($row['nama_topik'] ?? '-') ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Lokasi Kejadian</div>
          <div class="fw-semibold"><?= esc($row['nama_wilayah'] ?? '-') ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Tanggal Kejadian</div>
          <div class="fw-semibold"><?= esc(date('d F Y', strtotime($row['tanggal_kejadian']))) ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Pihak Dilaporkan</div>
          <div class="fw-semibold"><?= esc($row['pihak_dilaporkan']) ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">SLA Penanganan</div>
          <div class="fw-semibold">
            <?php if (! empty($row['sla_hari'])): ?>
              <?= esc($row['sla_hari']) ?> hari &mdash; target <?= esc(date('d F Y', strtotime($row['tanggal_target_selesai']))) ?>
            <?php else: ?>
              <span class="text-muted">Belum ditetapkan</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <hr>
      <div class="text-muted small mb-1">Uraian Kronologi</div>
      <p class="mb-0"><?= nl2br(esc($row['kronologi'])) ?></p>

      <?php if (! empty($row['catatan_validasi'])): ?>
        <hr>
        <div class="text-muted small mb-1">Catatan Verifikasi</div>
        <p class="mb-0"><?= nl2br(esc($row['catatan_validasi'])) ?></p>
      <?php endif; ?>
    </div>

    <!-- Data pelapor -->
    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-3">Data Pelapor</div>
      <?php if ((int) $row['rahasiakan_identitas'] === 1): ?>
        <div class="alert alert-warning py-2 small mb-3">
          <i class="bi bi-shield-lock me-1"></i> Pelapor meminta identitasnya dirahasiakan dari pihak terlapor.
        </div>
      <?php endif; ?>
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="avatar-circle"><?= esc(strtoupper(substr($row['nama_pelapor'], 0, 1))) ?></span>
        <div>
          <div class="fw-semibold"><?= esc($row['nama_pelapor']) ?></div>
          <div class="text-muted small"><?= esc($row['email_pelapor'] ?: 'Email tidak diisi') ?></div>
        </div>
      </div>
      <div class="text-muted small">No. HP / WhatsApp</div>
      <div class="fw-semibold"><?= esc($row['no_hp_pelapor']) ?></div>
    </div>

    <!-- Lampiran dari pelapor -->
    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-3">Lampiran Bukti dari Pelapor</div>
      <?php if (empty($lampiranPelapor)): ?>
        <p class="text-muted mb-0">Pelapor tidak melampirkan berkas apa pun.</p>
      <?php else: ?>
        <?php foreach ($lampiranPelapor as $file): ?>
          <div class="d-flex align-items-center justify-content-between border-bottom py-2">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-paperclip text-muted"></i>
              <div>
                <div class="small fw-semibold"><?= esc($file['nama_file']) ?></div>
                <div class="text-muted" style="font-size:.75rem;"><?= esc($file['ukuran_kb']) ?> KB</div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Bukti penyelesaian -->
    <div class="table-card p-4">
      <div class="fw-bold mb-3">Dokumen Hasil Penanganan</div>
      <?php if (empty($buktiPenyelesaian)): ?>
        <p class="text-muted mb-0">Belum ada dokumen penyelesaian yang diunggah.</p>
      <?php else: ?>
        <?php foreach ($buktiPenyelesaian as $file): ?>
          <div class="d-flex align-items-center justify-content-between border-bottom py-2">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-file-earmark-check text-success"></i>
              <div>
                <div class="small fw-semibold"><?= esc($file['nama_file']) ?></div>
                <div class="text-muted" style="font-size:.75rem;">
                  <?= esc($file['ukuran_kb']) ?> KB
                  <?= ! empty($file['keterangan']) ? ' &middot; ' . esc($file['keterangan']) : '' ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- ============ KANAN: Aksi penanganan ============ -->
  <div class="col-lg-5">

    <!-- 1. Verifikasi validitas -->
    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-1">1. Verifikasi Validitas Pengaduan</div>
      <p class="text-muted small">Periksa kronologi, lokasi, dan lampiran berkas pelapor, serta pastikan pengaduan sesuai kewenangan instansi.</p>

      <form method="post" action="<?= site_url('pic/pengaduan/verifikasi/' . $row['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Hasil Verifikasi</label>
          <select name="status_validasi" class="form-select" required>
            <option value="" disabled <?= empty($row['status_validasi']) || $row['status_validasi'] === 'Belum Diverifikasi' ? 'selected' : '' ?>>Pilih hasil verifikasi</option>
            <option value="Valid" <?= ($row['status_validasi'] ?? '') === 'Valid' ? 'selected' : '' ?>>Valid &mdash; lanjut ditindaklanjuti</option>
            <option value="Tidak Valid" <?= ($row['status_validasi'] ?? '') === 'Tidak Valid' ? 'selected' : '' ?>>Tidak Valid &mdash; data/bukti tidak memadai</option>
            <option value="Bukan Kewenangan" <?= ($row['status_validasi'] ?? '') === 'Bukan Kewenangan' ? 'selected' : '' ?>>Bukan Kewenangan Instansi</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Catatan Verifikasi</label>
          <textarea name="catatan_validasi" rows="3" class="form-control" placeholder="Alasan / temuan saat verifikasi"><?= esc($row['catatan_validasi'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-primary-lg text-white w-100">Simpan Hasil Verifikasi</button>
      </form>
    </div>

    <!-- 2. SLA -->
    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-1">2. Tetapkan SLA Penanganan</div>
      <p class="text-muted small">Tentukan target penyelesaian dalam satuan hari sejak hari ini.</p>

      <form method="post" action="<?= site_url('pic/pengaduan/sla/' . $row['id']) ?>">
        <?= csrf_field() ?>
        <div class="input-group mb-3">
          <input type="number" name="sla_hari" min="1" max="365" class="form-control"
                 value="<?= esc($row['sla_hari'] ?? '') ?>" placeholder="Contoh: 7" required>
          <span class="input-group-text">hari</span>
        </div>
        <button type="submit" class="btn btn-outline-secondary-lg w-100">Simpan SLA</button>
      </form>
    </div>

    <!-- 3. Tanggapan -->
    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-1">3. Beri Tanggapan</div>
      <p class="text-muted small">Balasan publik akan terlihat oleh pelapor di halaman riwayat pengaduannya. Catatan internal hanya terlihat oleh Admin &amp; PIC.</p>

      <form method="post" action="<?= site_url('pic/pengaduan/tanggapan/' . $row['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <textarea name="isi" rows="4" class="form-control" required
                    placeholder="Contoh: Pengaduan Anda sedang kami proses. Klarifikasi dijadwalkan pada 20 September 2026 di kantor Disnaker."></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Jenis Tanggapan</label>
          <select name="is_publik" class="form-select">
            <option value="1">Publik &mdash; kirim ke pelapor</option>
            <option value="0">Internal &mdash; catatan petugas saja</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-primary-lg text-white w-100">Kirim Tanggapan</button>
      </form>
    </div>

    <!-- 4. Bukti penyelesaian -->
    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-1">4. Unggah Bukti Penyelesaian &amp; Tutup Tiket</div>
      <p class="text-muted small">Unggah Berita Acara Pemeriksaan, Surat Panggilan, atau Surat Rekomendasi. Tiket otomatis ditutup setelah berkas tersimpan.</p>

      <?php if ($isClosed): ?>
        <div class="alert alert-secondary py-2 small mb-0">
          Tiket sudah berstatus <strong><?= esc($row['status_akhir']) ?></strong>. Anda masih bisa menambah dokumen bila diperlukan.
        </div>
        <hr>
      <?php endif; ?>

      <form method="post" action="<?= site_url('pic/pengaduan/selesaikan/' . $row['id']) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Dokumen Hasil Penanganan</label>
          <input type="file" name="bukti[]" class="form-control" multiple required
                 accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
          <div class="form-text">PDF, JPG, PNG, DOC, DOCX &mdash; maks. 5 MB per file.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Keterangan Dokumen</label>
          <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Berita Acara Pemeriksaan tanggal 20/09/2026">
        </div>
        <button type="submit" class="btn btn-success w-100">
          <i class="bi bi-check2-circle me-1"></i> Simpan &amp; Tutup Tiket
        </button>
      </form>
    </div>

  </div>
</div>

<!-- ============ Tanggapan & Riwayat ============ -->
<div class="row g-3 mt-1">
  <div class="col-lg-7">
    <div class="table-card p-4">
      <div class="fw-bold mb-3">Riwayat Tanggapan</div>
      <?php if (empty($tanggapan)): ?>
        <p class="text-muted mb-0">Belum ada tanggapan.</p>
      <?php else: ?>
        <?php foreach ($tanggapan as $t): ?>
          <div class="border-start ps-3 mb-3" style="border-width:3px !important; border-color:<?= $t['is_publik'] ? 'var(--primary)' : 'var(--border)' ?> !important;">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="fw-semibold small"><?= esc($t['pengirim_nama'] ?: 'Petugas') ?></span>
              <span class="badge-status <?= $t['is_publik'] ? 'badge-proses' : '' ?>" style="<?= $t['is_publik'] ? '' : 'background:#eef1f6;color:#64748b;' ?>">
                <?= $t['is_publik'] ? 'Publik' : 'Internal' ?>
              </span>
            </div>
            <div class="small mb-1"><?= nl2br(esc($t['isi'])) ?></div>
            <div class="text-muted" style="font-size:.72rem;"><?= esc(date('d F Y H.i', strtotime($t['created_at']))) ?> WIB</div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="table-card p-4">
      <div class="fw-bold mb-3">Riwayat Update Tiket</div>
      <?php if (empty($riwayat)): ?>
        <p class="text-muted mb-0">Belum ada aktivitas tercatat.</p>
      <?php else: ?>
        <?php foreach ($riwayat as $log): ?>
          <div class="d-flex gap-2 mb-3">
            <div class="text-primary"><i class="bi bi-dot fs-4 lh-1"></i></div>
            <div>
              <div class="small"><?= esc($log['aktivitas']) ?></div>
              <div class="text-muted" style="font-size:.72rem;">
                <?= esc($log['aktor_nama'] ?: ucfirst($log['aktor_tipe'] ?? 'sistem')) ?>
                &middot; <?= esc(date('d F Y H.i', strtotime($log['created_at']))) ?> WIB
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
