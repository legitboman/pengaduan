<?php

namespace App\Controllers\Pic;

use App\Controllers\BaseController;
use App\Models\PengaduanAuditTrailModel;
use App\Models\PengaduanLampiranModel;
use App\Models\PengaduanModel;
use App\Models\PengaduanTahapanModel;
use App\Models\PengaduanTanggapanModel;

/**
 * PIC: memproses & menindaklanjuti tiket yang ditugaskan kepadanya.
 *
 * PENTING: seluruh aksi di controller ini di-scope ke topik_pengaduan.pic_id
 * lewat $this->ownedOrFail(), sehingga seorang PIC hanya melihat tiket dari
 * topik yang menjadi tanggung jawabnya dan tidak bisa membuka tiket PIC lain.
 */
class Pengaduan extends BaseController
{
    protected PengaduanModel $pengaduanModel;
    protected PengaduanTahapanModel $tahapanModel;
    protected PengaduanLampiranModel $lampiranModel;
    protected PengaduanTanggapanModel $tanggapanModel;
    protected PengaduanAuditTrailModel $auditModel;

    public function __construct()
    {
        $this->pengaduanModel = new PengaduanModel();
        $this->tahapanModel   = new PengaduanTahapanModel();
        $this->lampiranModel  = new PengaduanLampiranModel();
        $this->tanggapanModel = new PengaduanTanggapanModel();
        $this->auditModel     = new PengaduanAuditTrailModel();
    }

    private function picId(): int
    {
        return (int) session()->get('picId');
    }

    /**
     * Ambil pengaduan hanya jika memang ditugaskan ke PIC yang sedang login.
     */
    private function ownedOrFail(int $id): ?array
    {
        return $this->pengaduanModel->detailForPic($id, $this->picId());
    }

    /**
     * GET /pic/pengaduan — daftar tiket milik PIC ini saja.
     */
    public function index()
    {
        $rows = $this->pengaduanModel->forPic($this->picId());

        $list = array_map(static function ($row) {
            return [
                'id'              => $row['id'],
                'nomor_tiket'     => $row['nomor_tiket'],
                'judul_pengaduan' => $row['judul_pengaduan'],
                'nama_pelapor'    => $row['nama_pelapor'],
                'topik'           => $row['nama_topik'] ?? '-',
                'wilayah'         => $row['nama_wilayah'] ?? '-',
                'tanggal'         => date('d F Y', strtotime($row['tanggal_pengaduan'])),
                'status'          => $row['status_akhir'],
                'validasi'        => $row['status_validasi'] ?? 'Belum Diverifikasi',
            ];
        }, $rows);

        return view('pic/pengaduan/index', [
            'title'        => 'Tiket Saya',
            'active'       => 'pengaduan',
            'pageTitle'    => 'Tiket Pengaduan Saya',
            'pageSubtitle' => 'Hanya menampilkan pengaduan yang ditugaskan kepada Anda',
            'list'         => $list,
        ]);
    }

    /**
     * GET /pic/pengaduan/detail/{id}
     */
    public function detail(int $id)
    {
        $row = $this->ownedOrFail($id);

        if (! $row) {
            return redirect()->to(site_url('pic/pengaduan'))
                ->with('error', 'Pengaduan tidak ditemukan atau bukan ditugaskan kepada Anda.');
        }

        return view('pic/pengaduan/detail', [
            'title'             => 'Detail Tiket',
            'active'            => 'pengaduan',
            'pageTitle'         => 'Detail Tiket',
            'pageSubtitle'      => $row['nomor_tiket'],
            'row'               => $row,
            'lampiranPelapor'   => $this->lampiranModel->pelaporFor($id),
            'buktiPenyelesaian' => $this->lampiranModel->buktiPenyelesaianFor($id),
            'tanggapan'         => $this->tanggapanModel->forPengaduan($id),
            'riwayat'           => $this->auditModel->forPengaduan($id),
            'tahapan'           => $this->tahapanModel->stepperFor($id),
        ]);
    }

    /**
     * POST /pic/pengaduan/tanggapan/{id}
     * Beri tanggapan internal atau publik (balasan ke pelapor).
     */
    public function tanggapan(int $id)
    {
        if (! $this->ownedOrFail($id)) {
            return redirect()->to(site_url('pic/pengaduan'))->with('error', 'Akses ditolak.');
        }

        if (! $this->validate(['isi' => 'required|min_length[5]'])) {
            return redirect()->back()->withInput()->with('error', 'Isi tanggapan minimal 5 karakter.');
        }

        $isPublik = $this->request->getPost('is_publik') === '1';
        $isi      = $this->request->getPost('isi');

        $this->tanggapanModel->insert([
            'pengaduan_id'  => $id,
            'pic_id'        => $this->picId(),
            'admin_id'      => null,
            'pengirim_tipe' => 'pic',
            'pengirim_nama' => session()->get('picNama'),
            'isi'           => $isi,
            'is_publik'     => $isPublik ? 1 : 0,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        $this->auditModel->logPic(
            $id,
            $this->picId(),
            $isPublik ? 'Mengirim balasan kepada pelapor.' : 'Menambahkan catatan internal.',
            $isPublik
        );

        return redirect()->to(site_url('pic/pengaduan/detail/' . $id))
            ->with('success', $isPublik ? 'Balasan berhasil dikirim ke pelapor.' : 'Catatan internal tersimpan.');
    }

    /**
     * POST /pic/pengaduan/sla/{id}
     * Menetapkan SLA penanganan dalam satuan hari.
     */
    public function setSla(int $id)
    {
        if (! $this->ownedOrFail($id)) {
            return redirect()->to(site_url('pic/pengaduan'))->with('error', 'Akses ditolak.');
        }

        if (! $this->validate(['sla_hari' => 'required|is_natural_no_zero|less_than_equal_to[365]'])) {
            return redirect()->back()->with('error', 'SLA harus berupa angka hari antara 1 sampai 365.');
        }

        $slaHari = (int) $this->request->getPost('sla_hari');
        $target  = date('Y-m-d', strtotime('+' . $slaHari . ' days'));

        $this->pengaduanModel->update($id, [
            'sla_hari'               => $slaHari,
            'tanggal_target_selesai' => $target,
        ]);

        $this->auditModel->logPic(
            $id,
            $this->picId(),
            'Menetapkan SLA penanganan ' . $slaHari . ' hari kerja (target selesai: ' . date('d F Y', strtotime($target)) . ').',
            true
        );

        return redirect()->to(site_url('pic/pengaduan/detail/' . $id))->with('success', 'SLA berhasil ditetapkan.');
    }

    /**
     * POST /pic/pengaduan/verifikasi/{id}
     * Verifikasi validitas pengaduan (kronologi, lokasi, lampiran, kewenangan).
     */
    public function verifikasi(int $id)
    {
        if (! $this->ownedOrFail($id)) {
            return redirect()->to(site_url('pic/pengaduan'))->with('error', 'Akses ditolak.');
        }

        $rules = [
            'status_validasi'  => 'required|in_list[Valid,Tidak Valid,Bukan Kewenangan]',
            'catatan_validasi' => 'permit_empty|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Pilih hasil verifikasi terlebih dahulu.');
        }

        $statusValidasi = $this->request->getPost('status_validasi');
        $catatan        = $this->request->getPost('catatan_validasi');

        $payload = [
            'status_validasi'  => $statusValidasi,
            'catatan_validasi' => $catatan ?: null,
        ];

        // Pengaduan yang valid otomatis masuk tahap penanganan;
        // yang tidak valid / bukan kewenangan langsung ditolak.
        if ($statusValidasi === 'Valid') {
            $payload['status_akhir'] = 'Dalam Penanganan';
        } else {
            $payload['status_akhir']     = 'Ditolak';
            $payload['alasan_penolakan'] = $catatan ?: $statusValidasi;
        }

        $this->pengaduanModel->update($id, $payload);
        $this->syncTahapan($id, $payload['status_akhir']);

        $this->auditModel->logPic(
            $id,
            $this->picId(),
            'Hasil verifikasi validitas: ' . $statusValidasi . ($catatan ? ' — ' . $catatan : ''),
            true
        );

        return redirect()->to(site_url('pic/pengaduan/detail/' . $id))->with('success', 'Hasil verifikasi tersimpan.');
    }

    /**
     * POST /pic/pengaduan/selesaikan/{id}
     * Unggah bukti penyelesaian (BAP, surat panggilan, surat rekomendasi) & tutup tiket.
     */
    public function selesaikan(int $id)
    {
        if (! $this->ownedOrFail($id)) {
            return redirect()->to(site_url('pic/pengaduan'))->with('error', 'Akses ditolak.');
        }

        $rules = [
            'keterangan'  => 'permit_empty|max_length[255]',
            'bukti.*'     => 'permit_empty|max_size[bukti,5120]|ext_in[bukti,pdf,jpg,jpeg,png,doc,docx]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Berkas tidak valid. Format: PDF/JPG/PNG/DOC/DOCX, maks. 5 MB per file.');
        }

        $files    = $this->request->getFiles();
        $tersimpan = 0;

        if (! empty($files['bukti'])) {
            foreach ($files['bukti'] as $file) {
                if (! $file->isValid() || $file->hasMoved()) {
                    continue;
                }

                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/penyelesaian', $newName);

                $this->lampiranModel->insert([
                    'pengaduan_id'       => $id,
                    'jenis'              => 'bukti_penyelesaian',
                    'uploaded_by_pic_id' => $this->picId(),
                    'nama_file'          => $file->getClientName(),
                    'path_file'          => 'writable/uploads/penyelesaian/' . $newName,
                    'mime_type'          => $file->getClientMimeType(),
                    'ukuran_kb'          => (int) round($file->getSize() / 1024),
                    'keterangan'         => $this->request->getPost('keterangan') ?: null,
                    'created_at'         => date('Y-m-d H:i:s'),
                ]);

                $tersimpan++;
            }
        }

        if ($tersimpan === 0) {
            return redirect()->back()->with('error', 'Unggah minimal satu dokumen bukti penyelesaian.');
        }

        // Tutup tiket
        $this->pengaduanModel->update($id, ['status_akhir' => 'Selesai']);
        $this->syncTahapan($id, 'Selesai');

        $this->auditModel->logPic(
            $id,
            $this->picId(),
            'Mengunggah ' . $tersimpan . ' dokumen bukti penyelesaian dan menutup tiket.',
            true
        );

        return redirect()->to(site_url('pic/pengaduan/detail/' . $id))
            ->with('success', 'Bukti penyelesaian tersimpan dan tiket ditutup.');
    }

    /**
     * Sinkronkan pengaduan_tahapan agar sesuai status_akhir terbaru.
     * Logika dipusatkan di PengaduanTahapanModel agar sama dengan sisi Admin.
     */
    private function syncTahapan(int $id, string $status): void
    {
        $this->tahapanModel->syncWithStatus($id, $status);
    }
}
