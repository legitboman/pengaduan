<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<a href="<?= site_url('admin/pengaduan') ?>" class="d-inline-flex align-items-center gap-1 text-muted small mb-3">
  <i class="bi bi-arrow-left"></i> Kembali ke List Pengaduan
</a>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="table-card p-4 mb-3">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="text-muted small">Nomor Tiket</div>
          <div class="fw-bold text-primary fs-5"><?= esc($row['nomor_tiket']) ?></div>
        </div>
        <?php
          $map = ['Didisposisikan ke PIC' => 'menunggu', 'Menunggu Verifikasi' => 'menunggu', 'Dalam Penanganan' => 'proses', 'Selesai' => 'selesai', 'Ditolak' => 'ditolak'];
          $cls = $map[$row['status']] ?? 'menunggu';
        ?>
        <span class="badge-status badge-<?= $cls ?>"><?= esc($row['status']) ?></span>
      </div>

      <h2 class="h5 fw-bold"><?= esc($row['judul_pengaduan']) ?></h2>

      <div class="row g-3 my-2">
        <div class="col-md-6">
          <div class="text-muted small">Kategori</div>
          <div class="fw-semibold"><?= esc($row['kategori']) ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Topik</div>
          <div class="fw-semibold"><?= esc($row['topik']) ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Lokasi Kejadian</div>
          <div class="fw-semibold"><?= esc($row['wilayah']) ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Pihak Dilaporkan</div>
          <div class="fw-semibold"><?= esc($row['pihak_dilaporkan']) ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">Hasil Verifikasi PIC</div>
          <div class="fw-semibold"><?= esc($row['status_validasi'] ?? 'Belum Diverifikasi') ?></div>
        </div>
        <div class="col-md-6">
          <div class="text-muted small">SLA Penanganan</div>
          <div class="fw-semibold">
            <?= ! empty($row['sla_hari']) ? esc($row['sla_hari']) . ' hari' : '<span class="text-muted">Belum ditetapkan</span>' ?>
          </div>
        </div>
      </div>

      <hr>

      <div class="text-muted small mb-1">Uraian Kronologi</div>
      <p class="mb-0"><?= nl2br(esc($row['kronologi'])) ?></p>
    </div>

    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-3">Lampiran Pendukung dari Pelapor</div>
      <?php if (empty($row['lampiran'])): ?>
        <p class="text-muted mb-0">Tidak ada lampiran.</p>
      <?php else: ?>
        <?php foreach ($row['lampiran'] as $file): ?>
          <span class="file-chip"><i class="bi bi-paperclip"></i> <?= esc($file) ?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-3">Dokumen Hasil Penanganan (dari PIC)</div>
      <?php if (empty($buktiPenyelesaian)): ?>
        <p class="text-muted mb-0">Belum ada dokumen penyelesaian.</p>
      <?php else: ?>
        <?php foreach ($buktiPenyelesaian as $file): ?>
          <div class="d-flex align-items-center gap-2 border-bottom py-2">
            <i class="bi bi-file-earmark-check text-success"></i>
            <div>
              <div class="small fw-semibold"><?= esc($file['nama_file']) ?></div>
              <div class="text-muted" style="font-size:.75rem;">
                <?= esc($file['ukuran_kb']) ?> KB<?= ! empty($file['keterangan']) ? ' &middot; ' . esc($file['keterangan']) : '' ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-3">Tanggapan</div>

      <form method="post" action="<?= site_url('admin/pengaduan/tanggapan/' . $row['id']) ?>" class="mb-4">
        <?= csrf_field() ?>
        <textarea name="isi" rows="3" class="form-control mb-2" required placeholder="Tulis balasan ke pelapor atau catatan internal..."></textarea>
        <div class="d-flex gap-2">
          <select name="is_publik" class="form-select form-select-sm" style="max-width:220px;">
            <option value="1">Publik &mdash; kirim ke pelapor</option>
            <option value="0">Internal &mdash; catatan petugas</option>
          </select>
          <button type="submit" class="btn btn-sm btn-primary text-white">Kirim</button>
        </div>
      </form>

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

    <div class="table-card p-4">
      <div class="fw-bold mb-3">Riwayat Update Pengaduan</div>
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
                <?php if (! empty($log['is_publik'])): ?>
                  &middot; <span class="text-primary">terlihat oleh pelapor</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-3">Data Pelapor</div>
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="avatar-circle"><?= esc(strtoupper(substr($row['nama_pelapor'], 0, 1))) ?></span>
        <div>
          <div class="fw-semibold"><?= esc($row['nama_pelapor']) ?></div>
          <div class="text-muted small"><?= esc($row['email_pelapor']) ?></div>
        </div>
      </div>
      <div class="text-muted small">No. HP / WhatsApp</div>
      <div class="fw-semibold mb-0"><?= esc($row['no_hp_pelapor']) ?></div>
    </div>

    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-3">Update Status</div>
      <form method="post" action="<?= site_url('admin/pengaduan/update-status/' . $row['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">PIC Penanggung Jawab</label>
          <input type="text" class="form-control" value="<?= esc($row['pic']) ?>" disabled>
          <div class="form-text">
            PIC mengikuti topik pengaduan &mdash; ubah lewat panel <strong>Ubah Kategori &amp; Topik</strong> di bawah.
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php foreach (['Didisposisikan ke PIC', 'Dalam Penanganan', 'Selesai', 'Ditolak'] as $s): ?>
              <option value="<?= esc($s) ?>" <?= $row['status'] === $s ? 'selected' : '' ?>><?= esc($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-primary-lg text-white w-100">Simpan Perubahan</button>
      </form>
    </div>

    <div class="table-card p-4 mb-3">
      <div class="fw-bold mb-1">Ubah Kategori &amp; Topik</div>
      <p class="text-muted small">Koreksi klasifikasi bila pelapor salah memilih. Mengganti topik otomatis mengalihkan tiket ke PIC penanggung jawab topik baru.</p>

      <form method="post" action="<?= site_url('admin/pengaduan/update-klasifikasi/' . $row['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Kategori Layanan</label>
          <select name="kategori_id" id="adminKategoriSelect" class="form-select" required>
            <?php foreach (($kategoriOptions ?? []) as $k): ?>
              <option value="<?= esc($k['id']) ?>" <?= (int) ($row['kategori_id'] ?? 0) === (int) $k['id'] ? 'selected' : '' ?>>
                <?= esc($k['nama_kategori']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Topik Pengaduan</label>
          <select name="topik_id" id="adminTopikSelect" class="form-select" required>
            <?php foreach (($topikOptions ?? []) as $t): ?>
              <option value="<?= esc($t['id']) ?>"
                      data-kategori="<?= esc($t['kategori_id']) ?>"
                      data-pic="<?= esc($t['nama_pic'] ?? 'Belum ditentukan') ?>"
                      <?= (int) ($row['topik_id'] ?? 0) === (int) $t['id'] ? 'selected' : '' ?>>
                <?= esc($t['nama_topik']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="form-text" id="adminPicHint"></div>
        </div>

        <button type="submit" class="btn btn-outline-secondary-lg w-100">Simpan Klasifikasi</button>
      </form>
    </div>

    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
      <i class="bi bi-trash me-1"></i> Hapus Pengaduan
    </button>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-4 text-center">
        <i class="bi bi-exclamation-triangle text-danger fs-1 mb-2"></i>
        <h5 class="fw-bold">Hapus Pengaduan?</h5>
        <p class="text-muted">Tiket <strong><?= esc($row['nomor_tiket']) ?></strong> akan dihapus permanen.</p>
        <form action="<?= site_url('admin/pengaduan/delete/' . $row['id']) ?>" method="post" class="d-flex justify-content-center gap-2">
          <?= csrf_field() ?>
          <button type="button" class="btn btn-outline-secondary-lg" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (function () {
    const kategoriSelect = document.getElementById('adminKategoriSelect');
    const topikSelect = document.getElementById('adminTopikSelect');
    const picHint = document.getElementById('adminPicHint');
    if (!kategoriSelect || !topikSelect) return;

    const topikOptions = Array.from(topikSelect.querySelectorAll('option[data-kategori]'));

    function updatePicHint() {
      const opt = topikSelect.selectedOptions[0];
      picHint.textContent = (opt && opt.dataset.pic) ? ('PIC penanggung jawab: ' + opt.dataset.pic) : '';
    }

    function filterTopik(preserve) {
      const kategoriId = kategoriSelect.value;
      const previous = preserve ? topikSelect.value : '';

      topikOptions.forEach(opt => {
        const match = opt.dataset.kategori === kategoriId;
        opt.hidden = !match;
        opt.disabled = !match;
      });

      const stillValid = previous && topikOptions.some(o => o.value === previous && !o.hidden);
      if (stillValid) {
        topikSelect.value = previous;
      } else {
        const firstVisible = topikOptions.find(o => !o.hidden);
        topikSelect.value = firstVisible ? firstVisible.value : '';
      }
      updatePicHint();
    }

    kategoriSelect.addEventListener('change', () => filterTopik(false));
    topikSelect.addEventListener('change', updatePicHint);
    filterTopik(true);
  })();
</script>
<?= $this->endSection() ?>
