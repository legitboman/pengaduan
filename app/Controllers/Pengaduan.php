<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriPengaduanModel;
use App\Models\PengaduanLampiranModel;
use App\Models\PengaduanModel;
use App\Models\PengaduanTahapanModel;
use App\Models\SlaDefaultModel;
use App\Models\TopikPengaduanModel;
use App\Models\WilayahModel;

class Pengaduan extends BaseController
{
    protected PengaduanModel $pengaduanModel;
    protected PengaduanTahapanModel $tahapanModel;
    protected PengaduanLampiranModel $lampiranModel;
    protected TopikPengaduanModel $topikModel;
    protected KategoriPengaduanModel $kategoriModel;
    protected WilayahModel $wilayahModel;
    protected SlaDefaultModel $slaModel;

    public function __construct()
    {
        $this->pengaduanModel = new PengaduanModel();
        $this->tahapanModel   = new PengaduanTahapanModel();
        $this->lampiranModel  = new PengaduanLampiranModel();
        $this->topikModel     = new TopikPengaduanModel();
        $this->kategoriModel  = new KategoriPengaduanModel();
        $this->wilayahModel   = new WilayahModel();
        $this->slaModel       = new SlaDefaultModel();
    }

    private function requireLogin(string $returnPath)
    {
        if (! session()->get('isMasyarakatLoggedIn')) {
            return redirect()
                ->to(site_url('login') . '?redirect=' . urlencode(site_url($returnPath)))
                ->with('error', 'Silakan masuk terlebih dahulu untuk melanjutkan.');
        }

        return null;
    }

    /**
     * GET /pengaduan/create
     */
    public function create()
    {
        if ($guard = $this->requireLogin('pengaduan/create')) {
            return $guard;
        }

        return view('pengaduan/form', [
            'title'    => 'Buat Pengaduan',
            'kategori' => $this->kategoriModel->active(),
            'topikAll' => $this->topikModel->activeWithPic(),
            'wilayah'  => $this->wilayahModel->active(),
        ]);
    }

    /**
     * POST /pengaduan/store
     */
    public function store()
    {
        if ($guard = $this->requireLogin('pengaduan/create')) {
            return $guard;
        }

        $rules = [
            'kategori_id'      => 'required',
            'topik_id'         => 'required',
            'judul_pengaduan'  => 'required|max_length[150]',
            'kronologi'        => 'required|max_length[4000]',
            'tanggal_kejadian' => 'required|valid_date',
            'wilayah_id'       => 'required',
            'pihak_dilaporkan' => 'required',
            'setuju'           => 'required',
            'lampiran.*'       => 'permit_empty|max_size[lampiran,5120]|ext_in[lampiran,pdf,jpg,jpeg,png]',
        ];

        if (! $this->validate($rules)) {
            return view('pengaduan/form', [
                'title'      => 'Buat Pengaduan',
                'kategori'   => $this->kategoriModel->active(),
                'topikAll'   => $this->topikModel->activeWithPic(),
                'wilayah'    => $this->wilayahModel->active(),
                'validation' => $this->validator,
            ]);
        }

        $topikRow = $this->topikModel->find((int) $this->request->getPost('topik_id'));

        if (! $topikRow || (int) $topikRow['kategori_id'] !== (int) $this->request->getPost('kategori_id')) {
            return view('pengaduan/form', [
                'title'    => 'Buat Pengaduan',
                'kategori' => $this->kategoriModel->active(),
                'topikAll' => $this->topikModel->activeWithPic(),
                'wilayah'  => $this->wilayahModel->active(),
            ])->setStatusCode(422);
        }

        $nomorTiket = 'JATIM-PHK-' . date('Y') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        $kodeAkses  = strtoupper(bin2hex(random_bytes(4)));

        $pengaduanId = $this->pengaduanModel->insert([
            'nomor_tiket'          => $nomorTiket,
            'topik_id'             => $topikRow['id'],
            'wilayah_id'           => $this->request->getPost('wilayah_id'),
            'masyarakat_id'        => session()->get('masyarakatId'),
            'judul_pengaduan'      => $this->request->getPost('judul_pengaduan'),
            'tanggal_pengaduan'    => date('Y-m-d'),
            'tanggal_kejadian'     => $this->request->getPost('tanggal_kejadian'),
            'pihak_dilaporkan'     => $this->request->getPost('pihak_dilaporkan'),
            'kronologi'            => $this->request->getPost('kronologi'),
            'nama_pelapor'         => session()->get('masyarakatNama'),
            'email_pelapor'        => session()->get('masyarakatEmail') ?: null,
            'no_hp_pelapor'        => session()->get('masyarakatNoHp'),
            'rahasiakan_identitas' => 0,
            'kode_akses'           => $kodeAkses,
            'status_akhir'         => 'Didisposisikan ke PIC',
        ], true);

        $slaRows    = $this->slaModel->forTopik((int) $topikRow['id']);
        $slaByStage = [];
        foreach ($slaRows as $row) {
            $slaByStage[$row['urutan']] = $row['sla_hari'];
        }
        $this->tahapanModel->seedForPengaduan($pengaduanId, $slaByStage);

        $files = $this->request->getFiles();
        if (! empty($files['lampiran'])) {
            foreach ($files['lampiran'] as $file) {
                if (! $file->isValid() || $file->hasMoved()) {
                    continue;
                }

                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/pengaduan', $newName);

                $this->lampiranModel->insert([
                    'pengaduan_id' => $pengaduanId,
                    'jenis'        => 'pelapor',
                    'nama_file'    => $file->getClientName(),
                    'path_file'    => 'writable/uploads/pengaduan/' . $newName,
                    'mime_type'    => $file->getClientMimeType(),
                    'ukuran_kb'    => (int) round($file->getSize() / 1024),
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Catat entri awal di riwayat update (terlihat oleh pelapor).
        // Disposisi terjadi seketika: tiket langsung menjadi milik PIC
        // penanggung jawab topik, tanpa persetujuan admin.
        $topikLengkap = $this->topikModel->withKategoriAndPic((int) $topikRow['id']);
        $namaPic      = $topikLengkap['nama_pic'] ?? null;

        (new \App\Models\PengaduanAuditTrailModel())->insert([
            'pengaduan_id' => $pengaduanId,
            'admin_id'     => null,
            'pic_id'       => $topikRow['pic_id'] ?? null,
            'aktor_tipe'   => 'sistem',
            'aktor_nama'   => 'Sistem',
            'aktivitas'    => $namaPic
                ? 'Pengaduan diterima dan langsung didisposisikan ke PIC ' . $namaPic . '.'
                : 'Pengaduan diterima dan menunggu penetapan PIC untuk topik ini.',
            'is_publik'    => 1,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('pengaduan/success/' . $nomorTiket));
    }

    /**
     * GET /pengaduan/success/{nomorTiket}
     */
    public function success(string $nomorTiket)
    {
        $row = $this->pengaduanModel->findByTicket($nomorTiket);

        if (! $row) {
            return redirect()->to(site_url('/'))->with('error', 'Tiket tidak ditemukan.');
        }

        $topik    = $this->topikModel->withKategoriAndPic((int) $row['topik_id']);
        $slaHari  = 1;
        $firstSla = $this->slaModel->forTopik((int) $row['topik_id']);
        if (! empty($firstSla)) {
            $slaHari = $firstSla[1]['sla_hari'] ?? 1;
        }

        return view('pengaduan/success', [
            'title'     => 'Pengaduan Berhasil Dikirim',
            'pengaduan' => [
                'nomor_tiket'       => $row['nomor_tiket'],
                'kategori'          => $topik['nama_kategori'] ?? '-',
                'topik'             => $topik['nama_topik'] ?? '-',
                'status'            => $row['status_akhir'],
                'sla_hari'          => $slaHari,
                'tanggal_pengajuan' => date('d F Y \p\u\k\u\l H.i', strtotime($row['created_at'])) . ' WIB',
                'kode_akses'        => $row['kode_akses'],
            ],
        ]);
    }

    /**
     * GET /pengaduan/lacak
     */
    public function lacak()
    {
        if ($guard = $this->requireLogin('pengaduan/lacak')) {
            return $guard;
        }

        $rows = $this->pengaduanModel->forMasyarakat((int) session()->get('masyarakatId'));

        $riwayat = array_map(static function ($row) {
            return [
                'id'              => $row['id'],
                'nomor_tiket'     => $row['nomor_tiket'],
                'judul_pengaduan' => $row['judul_pengaduan'],
                'topik'           => $row['nama_topik'] ?? '-',
                'tanggal'         => date('d F Y', strtotime($row['tanggal_pengaduan'])),
                'status'          => $row['status_akhir'],
            ];
        }, $rows);

        return view('pengaduan/track', [
            'title'   => 'Riwayat Pengaduan Saya',
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * GET /pengaduan/lacak/{nomorTiket}
     */
    public function lacakDetail(string $nomorTiket)
    {
        if ($guard = $this->requireLogin('pengaduan/lacak')) {
            return $guard;
        }

        $row = $this->pengaduanModel->findByTicketWithPic($nomorTiket);

        if (! $row || (int) $row['masyarakat_id'] !== (int) session()->get('masyarakatId')) {
            return redirect()->to(site_url('pengaduan/lacak'))->with('error', 'Pengaduan tidak ditemukan.');
        }

        $tahapan = $this->tahapanModel->stepperFor((int) $row['id']);

        $auditModel     = new \App\Models\PengaduanAuditTrailModel();
        $tanggapanModel = new \App\Models\PengaduanTanggapanModel();
        $lampiranModel  = new \App\Models\PengaduanLampiranModel();

        // Gabungkan catatan update publik + balasan petugas jadi satu linimasa,
        // urut dari yang terbaru.
        $catatan = [];

        foreach ($auditModel->publikForPengaduan((int) $row['id']) as $log) {
            $catatan[] = [
                'tipe'    => 'update',
                'isi'     => $log['aktivitas'],
                'oleh'    => $log['aktor_nama'] ?: ucfirst($log['aktor_tipe'] ?? 'Petugas'),
                'waktu'   => $log['created_at'],
            ];
        }

        foreach ($tanggapanModel->publikForPengaduan((int) $row['id']) as $t) {
            $catatan[] = [
                'tipe'    => 'balasan',
                'isi'     => $t['isi'],
                'oleh'    => $t['pengirim_nama'] ?: 'Petugas',
                'waktu'   => $t['created_at'],
            ];
        }

        usort($catatan, static fn ($a, $b) => strtotime($b['waktu']) <=> strtotime($a['waktu']));

        return view('pengaduan/track_detail', [
            'title' => 'Detail Progres Pengaduan',
            'tiket' => [
                'nomor_tiket'      => $row['nomor_tiket'],
                'judul_pengaduan'  => $row['judul_pengaduan'],
                'status_pengaduan' => $row['status_akhir'],
                'pic'              => $row['nama_pic'] ?? 'Belum ditugaskan',
                'prioritas'        => 'Normal',
                'sla_hari'         => $row['sla_hari'] ?? null,
                'target_selesai'   => $row['tanggal_target_selesai'] ?? null,
                'update_terakhir'  => date('d F Y H.i', strtotime($row['updated_at'])) . ' WIB',
                'tahapan'          => $tahapan,
            ],
            'catatan'           => $catatan,
            'buktiPenyelesaian' => $lampiranModel->buktiPenyelesaianFor((int) $row['id']),
        ]);
    }
}