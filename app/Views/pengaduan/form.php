<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="mx-auto" style="max-width: 900px;">

  <div class="mb-4">
    <h1 class="section-title mb-1">Formulir Pengaduan</h1>
    <p class="section-subtitle mb-0">Sampaikan pengaduan Anda terkait norma/permasalahan ketenagakerjaan. Pastikan data yang Anda isi lengkap dan benar.</p>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <?php if (isset($validation)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($validation->getErrors() as $err): ?>
          <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php
    // $old dikirim dari controller saat validasi gagal (return view langsung)
    $o = $old ?? [];
    $v = function(string $key, string $default = '') use ($o): string {
        return isset($o[$key]) ? esc((string) $o[$key]) : $default;
    };
  ?>

  <div class="card-panel">
    <form action="<?= site_url('pengaduan/store') ?>" method="post" enctype="multipart/form-data" novalidate>
      <?= csrf_field() ?>

      <div class="row g-4">

        <div class="col-md-6">
          <label class="form-label">Kategori Layanan <span class="req">*</span></label>
          <select name="kategori_id" id="kategoriSelect" class="form-select" required>
            <option value="" disabled>Pilih kategori layanan</option>
            <?php foreach (($kategori ?? []) as $k): ?>
              <option value="<?= esc($k['id']) ?>" <?= ($o['kategori_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                <?= esc($k['nama_kategori']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Topik Pengaduan <span class="req">*</span></label>
          <select name="topik_id" id="topikSelect" class="form-select" required disabled>
            <option value="" disabled>Pilih kategori terlebih dahulu</option>
            <?php foreach (($topikAll ?? []) as $t): ?>
              <option value="<?= esc($t['id']) ?>"
                      data-kategori="<?= esc($t['kategori_id']) ?>"
                      data-pic="<?= esc($t['nama_pic'] ?? 'Belum ditentukan') ?>"
                      hidden
                      <?= ($o['topik_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                <?= esc($t['nama_topik']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="form-text" id="picHint"></div>
        </div>

        <div class="col-12">
          <label class="form-label">Judul Pengaduan <span class="req">*</span></label>
          <input type="text" name="judul_pengaduan" maxlength="150" id="judulInput"
                 value="<?= $v('judul_pengaduan') ?>"
                 class="form-control" placeholder="Tuliskan judul singkat dan jelas mengenai pengaduan Anda" required>
          <div class="char-counter"><span id="judulCount">0</span>/150</div>
        </div>

        <div class="col-12">
          <label class="form-label">Uraian Kronologi <span class="req">*</span></label>
          <textarea name="kronologi" id="kronologiInput" maxlength="4000" rows="6" class="form-control"
                    placeholder="Jelaskan kronologi kejadian secara lengkap, jelas, dan berurutan" required><?= $v('kronologi') ?></textarea>
          <div class="char-counter"><span id="kronologiCount">0</span>/4000</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Tanggal Kejadian <span class="req">*</span></label>
          <input type="date" name="tanggal_kejadian" value="<?= $v('tanggal_kejadian') ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Lokasi/Kabupaten/Kota <span class="req">*</span></label>
          <select name="wilayah_id" class="form-select" required>
            <option value="" disabled>Pilih kabupaten/kota</option>
            <?php foreach (($wilayah ?? []) as $w): ?>
              <option value="<?= esc($w['id']) ?>" <?= ($o['wilayah_id'] ?? '') == $w['id'] ? 'selected' : '' ?>>
                <?= esc($w['nama_wilayah']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Pihak yang Dilaporkan <span class="req">*</span></label>
          <input type="text" name="pihak_dilaporkan" value="<?= $v('pihak_dilaporkan') ?>"
                 class="form-control" placeholder="Nama perusahaan/instansi atau perorangan yang dilaporkan" required>
        </div>



        <hr class="mt-2 mb-0">

        <div class="col-12">
          <h2 class="h6 fw-bold mb-3">Data Pelapor</h2>
          <p class="text-muted small mb-3 mt-n2">Data diambil otomatis dari akun Anda dan tidak dapat diubah di sini. Ingin memperbarui data ini? Hubungi admin.</p>
        </div>

        <div class="col-md-4">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" value="<?= esc(session()->get('masyarakatNama')) ?>" class="form-control" disabled>
        </div>
        <div class="col-md-4">
          <label class="form-label">Email <span class="text-muted small">(opsional)</span></label>
          <input type="text" value="<?= esc(session()->get('masyarakatEmail') ?: '- (belum diisi)') ?>" class="form-control" disabled>
        </div>
        <div class="col-md-4">
          <label class="form-label">No. HP / WhatsApp</label>
          <input type="text" value="<?= esc(session()->get('masyarakatNoHp')) ?>" class="form-control" disabled>
        </div>

        <hr class="mt-2 mb-0">

        <div class="col-12">
          <label class="form-label">Lampiran Pendukung</label>
          <p class="text-muted small mb-2">Unggah dokumen atau bukti pendukung (format: PDF, JPG, PNG. Maks. 5 MB per file)</p>

          <a href="<?= base_url('assets/templates/template-pengaduan.pdf') ?>" download class="btn btn-outline-secondary-lg btn-sm mb-3">
            <i class="bi bi-file-earmark-arrow-down me-1"></i> Unduh Template Formulir Pengaduan (PDF)
          </a>

          <label for="fileInput" class="dropzone d-block" id="dropzone">
            <div class="dz-icon"><i class="bi bi-cloud-arrow-up"></i></div>
            <div><span class="dz-link">Klik atau seret file ke sini untuk mengunggah</span></div>
            <div class="text-muted small mt-1">Maksimal 5 file, masing-masing maks. 5 MB</div>
          </label>
          <input type="file" id="fileInput" name="lampiran[]" accept=".pdf,.jpg,.jpeg,.png" multiple hidden>

          <div id="fileList" class="mt-3"></div>
        </div>

        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="setuju" id="setuju" required>
            <label class="form-check-label small" for="setuju">
              Saya menyetujui <a href="<?= site_url('ketentuan-layanan') ?>" target="_blank">ketentuan layanan</a>
            </label>
          </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2 pt-2">
          <button type="submit" name="aksi" value="draft" class="btn btn-outline-secondary-lg">
            <i class="bi bi-save2 me-1"></i> Simpan Draf
          </button>
          <button type="submit" name="aksi" value="kirim" class="btn btn-primary btn-primary-lg text-white">
            <i class="bi bi-send me-1"></i> Kirim Pengaduan
          </button>
        </div>

      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const kategoriSelect   = document.getElementById('kategoriSelect');
  const topikSelect      = document.getElementById('topikSelect');
  const picHint          = document.getElementById('picHint');
  const topikPlaceholder = topikSelect.querySelector('option[value=""]');
  const topikOptions     = Array.from(topikSelect.querySelectorAll('option[data-kategori]'));

  function updatePicHint() {
    const opt = topikSelect.selectedOptions[0];
    picHint.textContent = (opt && opt.dataset.pic) ? ('PIC Penanggung Jawab: ' + opt.dataset.pic) : '';
  }

  function filterTopikByKategori(preserveSelection) {
    const kategoriId    = kategoriSelect.value;
    const previousValue = preserveSelection ? topikSelect.value : '';

    topikOptions.forEach(opt => {
      const match  = kategoriId !== '' && opt.dataset.kategori === kategoriId;
      opt.hidden   = !match;
      opt.disabled = !match;
    });

    topikSelect.disabled = kategoriId === '';
    topikPlaceholder.textContent = kategoriId === '' ? 'Pilih kategori terlebih dahulu' : 'Pilih topik pengaduan';

    const stillValid = previousValue && topikOptions.some(o => o.value === previousValue && !o.hidden);
    topikSelect.value = stillValid ? previousValue : '';
    updatePicHint();
  }

  kategoriSelect.addEventListener('change', () => filterTopikByKategori(false));
  topikSelect.addEventListener('change', updatePicHint);

  // Jalankan saat page load agar topik & PIC hint muncul sesuai nilai $old
  filterTopikByKategori(true);

  const judul        = document.getElementById('judulInput');
  const judulCount   = document.getElementById('judulCount');
  judul.addEventListener('input', () => judulCount.textContent = judul.value.length);
  judulCount.textContent = judul.value.length;

  const kronologi      = document.getElementById('kronologiInput');
  const kronologiCount = document.getElementById('kronologiCount');
  kronologi.addEventListener('input', () => kronologiCount.textContent = kronologi.value.length);
  kronologiCount.textContent = kronologi.value.length;

  const dropzone  = document.getElementById('dropzone');
  const fileInput = document.getElementById('fileInput');
  const fileList  = document.getElementById('fileList');
  let currentFiles = [];

  function renderFiles() {
    fileList.innerHTML = '';
    currentFiles.forEach((file, idx) => {
      const chip = document.createElement('span');
      chip.className = 'file-chip';
      chip.innerHTML = `<i class="bi bi-paperclip"></i> ${file.name} <button type="button" data-idx="${idx}">&times;</button>`;
      fileList.appendChild(chip);
    });

    const dt = new DataTransfer();
    currentFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;

    fileList.querySelectorAll('button').forEach(btn => {
      btn.addEventListener('click', () => {
        currentFiles.splice(parseInt(btn.dataset.idx), 1);
        renderFiles();
      });
    });
  }

  fileInput.addEventListener('change', () => {
    currentFiles = currentFiles.concat(Array.from(fileInput.files)).slice(0, 5);
    renderFiles();
  });

  ['dragover', 'dragleave', 'drop'].forEach(evt => {
    dropzone.addEventListener(evt, (e) => {
      e.preventDefault();
      dropzone.classList.toggle('dragover', evt === 'dragover');
    });
  });

  dropzone.addEventListener('drop', (e) => {
    currentFiles = currentFiles.concat(Array.from(e.dataTransfer.files)).slice(0, 5);
    renderFiles();
  });
</script>
<?= $this->endSection() ?>