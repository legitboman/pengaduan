<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengaduanAuditTrailModel;
use App\Models\PengaduanLampiranModel;
use App\Models\PengaduanModel;
use App\Models\PengaduanTahapanModel;
use App\Models\PengaduanTanggapanModel;
use App\Models\KategoriPengaduanModel;
use App\Models\TopikPengaduanModel;
use App\Models\PicModel;

/**
 * Admin: List Pengaduan (judul, nama pelapor, topik, aksi detail & hapus) — DB-backed.
 */
class Pengaduan extends BaseController
{
    protected PengaduanModel $pengaduanModel;
    protected PengaduanTahapanModel $tahapanModel;
    protected PengaduanLampiranModel $lampiranModel;
    protected PengaduanAuditTrailModel $auditModel;
    protected PengaduanTanggapanModel $tanggapanModel;
    protected KategoriPengaduanModel $kategoriModel;
    protected TopikPengaduanModel $topikModel;
    protected PicModel $picModel;

    public function __construct()
    {
        $this->pengaduanModel = new PengaduanModel();
        $this->tahapanModel   = new PengaduanTahapanModel();
        $this->lampiranModel  = new PengaduanLampiranModel();
        $this->auditModel     = new PengaduanAuditTrailModel();
        $this->tanggapanModel = new PengaduanTanggapanModel();
        $this->kategoriModel  = new KategoriPengaduanModel();
        $this->topikModel     = new TopikPengaduanModel();
        $this->picModel       = new PicModel();
    }

    /**
     * GET /admin/pengaduan
     */
    public function index()
    {
        $q      = trim($this->request->getGet('q') ?? '');
        $status = trim($this->request->getGet('status') ?? '');

        $rows = $this->pengaduanModel->listForAdmin($q ?: null, $status ?: null);

        $list = array_map(static function ($row) {
            return [
                'id'              => $row['id'],
                'nomor_tiket'     => $row['nomor_tiket'],
                'judul_pengaduan' => $row['judul_pengaduan'],
                'nama_pelapor'    => $row['nama_pelapor'],
                'topik'           => $row['nama_topik'] ?? '-',
                'tanggal'         => date('d F Y', strtotime($row['tanggal_pengaduan'])),
                'status'          => $row['status_akhir'],
            ];
        }, $rows);

        return view('admin/pengaduan/index', [
            'title'        => 'List Pengaduan',
            'active'       => 'pengaduan',
            'pageTitle'    => 'List Pengaduan',
            'pageSubtitle' => 'Kelola seluruh pengaduan yang masuk',
            'list'         => $list,
            'q'            => $q,
            'status'       => $status,
        ]);
    }

    /**
     * GET /admin/pengaduan/detail/{id}
     */
    public function detail(int $id)
    {
        $detail = $this->pengaduanModel->detailForAdmin($id);

        if (! $detail) {
            return redirect()->to(site_url('admin/pengaduan'))->with('error', 'Data pengaduan tidak ditemukan.');
        }

        $lampiran = array_column($this->lampiranModel->pelaporFor($id), 'nama_file');

        $row = [
            'id'                => $detail['id'],
            'nomor_tiket'       => $detail['nomor_tiket'],
            'judul_pengaduan'   => $detail['judul_pengaduan'],
            'status'            => $detail['status_akhir'],
            'kategori'          => $detail['nama_kategori'] ?? '-',
            'topik'             => $detail['nama_topik'] ?? '-',
            'topik_id'          => $detail['topik_id'],
            'wilayah'           => $detail['nama_wilayah'] ?? '-',
            'pihak_dilaporkan'  => $detail['pihak_dilaporkan'],
            'kronologi'         => $detail['kronologi'],
            'nama_pelapor'      => $detail['nama_pelapor'],
            'email_pelapor'     => $detail['email_pelapor'],
            'no_hp_pelapor'     => $detail['no_hp_pelapor'],
            'pic'               => $detail['nama_pic'] ?? 'Belum ditentukan',
            'sla_hari'          => $detail['sla_hari'] ?? null,
            'status_validasi'   => $detail['status_validasi'] ?? 'Belum Diverifikasi',
            'lampiran'          => $lampiran,
        ];

        // kategori_id diturunkan dari topik yang sedang dipakai
        $topikRow = $this->topikModel->find((int) $detail['topik_id']);
        $row['kategori_id'] = $topikRow['kategori_id'] ?? null;

        return view('admin/pengaduan/detail', [
            'title'             => 'Detail Pengaduan',
            'active'            => 'pengaduan',
            'pageTitle'         => 'Detail Pengaduan',
            'pageSubtitle'      => $row['nomor_tiket'],
            'row'               => $row,
            'kategoriOptions'   => $this->kategoriModel->active(),
            'topikOptions'      => $this->topikModel->activeWithPic(),
            'buktiPenyelesaian' => $this->lampiranModel->buktiPenyelesaianFor($id),
            'tanggapan'         => $this->tanggapanModel->forPengaduan($id),
            'riwayat'           => $this->auditModel->forPengaduan($id),
        ]);
    }

    /**
     * POST /admin/pengaduan/update-status/{id}
     */
    public function updateStatus(int $id)
    {
        $status = $this->request->getPost('status');

        $this->pengaduanModel->update($id, [
            'status_akhir' => $status,
        ]);

        $this->tahapanModel->syncWithStatus($id, $status);

        $this->auditModel->log(
            $id,
            session()->get('adminId'),
            'Mengubah status menjadi "' . $status . '".',
            true
        );

        return redirect()->to(site_url('admin/pengaduan/detail/' . $id))->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * POST /admin/pengaduan/update-klasifikasi/{id}
     *
     * Admin dapat mengoreksi kategori & topik pengaduan (misal salah pilih
     * oleh pelapor). Mengganti topik otomatis memindahkan tiket ke PIC
     * penanggung jawab topik yang baru.
     */
    public function updateKlasifikasi(int $id)
    {
        $detail = $this->pengaduanModel->find($id);

        if (! $detail) {
            return redirect()->to(site_url('admin/pengaduan'))->with('error', 'Data pengaduan tidak ditemukan.');
        }

        $rules = [
            'kategori_id' => 'required',
            'topik_id'    => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Kategori dan topik wajib dipilih.');
        }

        $topikBaru = $this->topikModel->find((int) $this->request->getPost('topik_id'));

        // Pastikan topik benar-benar milik kategori yang dipilih.
        if (! $topikBaru || (int) $topikBaru['kategori_id'] !== (int) $this->request->getPost('kategori_id')) {
            return redirect()->back()->with('error', 'Topik yang dipilih tidak sesuai dengan kategorinya.');
        }

        // Tidak ada perubahan
        if ((int) $topikBaru['id'] === (int) $detail['topik_id']) {
            return redirect()->to(site_url('admin/pengaduan/detail/' . $id))
                ->with('success', 'Tidak ada perubahan klasifikasi.');
        }

        $topikLama = $this->topikModel->withKategoriAndPic((int) $detail['topik_id']);
        $topikBaruLengkap = $this->topikModel->withKategoriAndPic((int) $topikBaru['id']);

        // Cukup ubah topik — PIC penanggung jawab ikut berpindah otomatis
        // karena PIC diturunkan dari topik_pengaduan.pic_id.
        $this->pengaduanModel->update($id, ['topik_id' => $topikBaru['id']]);

        $picLama = $topikLama['nama_pic'] ?? null;
        $picBaru = $topikBaruLengkap['nama_pic'] ?? null;
        $pindahPic = $picBaru !== null && $picBaru !== $picLama;

        $pesan = sprintf(
            'Mengubah klasifikasi dari "%s / %s" menjadi "%s / %s".',
            $topikLama['nama_kategori'] ?? '-',
            $topikLama['nama_topik'] ?? '-',
            $topikBaruLengkap['nama_kategori'] ?? '-',
            $topikBaruLengkap['nama_topik'] ?? '-'
        );

        if ($pindahPic) {
            $pesan .= ' Tiket otomatis berpindah ke PIC ' . $picBaru . '.';
        }

        $this->auditModel->log($id, session()->get('adminId'), $pesan, true);

        return redirect()->to(site_url('admin/pengaduan/detail/' . $id))
            ->with('success', 'Kategori & topik pengaduan berhasil diperbarui.');
    }

    /**
     * POST /admin/pengaduan/tanggapan/{id}
     * Admin juga bisa memberi catatan/balasan pada tiket.
     */
    public function tanggapan(int $id)
    {
        if (! $this->pengaduanModel->find($id)) {
            return redirect()->to(site_url('admin/pengaduan'))->with('error', 'Data pengaduan tidak ditemukan.');
        }

        if (! $this->validate(['isi' => 'required|min_length[5]'])) {
            return redirect()->back()->withInput()->with('error', 'Isi tanggapan minimal 5 karakter.');
        }

        $isPublik = $this->request->getPost('is_publik') === '1';

        $this->tanggapanModel->insert([
            'pengaduan_id'  => $id,
            'pic_id'        => null,
            'admin_id'      => session()->get('adminId'),
            'pengirim_tipe' => 'admin',
            'pengirim_nama' => session()->get('adminNama') ?? 'Admin',
            'isi'           => $this->request->getPost('isi'),
            'is_publik'     => $isPublik ? 1 : 0,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        $this->auditModel->log(
            $id,
            session()->get('adminId'),
            $isPublik ? 'Mengirim balasan kepada pelapor.' : 'Menambahkan catatan internal.',
            $isPublik
        );

        return redirect()->to(site_url('admin/pengaduan/detail/' . $id))
            ->with('success', $isPublik ? 'Balasan terkirim ke pelapor.' : 'Catatan internal tersimpan.');
    }

    /**
     * POST /admin/pengaduan/delete/{id}
     */
    public function delete(int $id)
    {
        $this->pengaduanModel->delete($id);

        return redirect()->to(site_url('admin/pengaduan'))->with('success', 'Pengaduan berhasil dihapus.');
    }
}
