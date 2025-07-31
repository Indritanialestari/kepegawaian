<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pegawai; // Model untuk Karyawan Tetap
use App\Models\KaryawanKontrak; // BARU: Import model KaryawanKontrak
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PegawaiController extends Controller
{
    // === Daftar Pilihan Dropdown Fixed ===
    private $golonganOptions = ['A4', 'B1', 'B2', 'B3', 'B4', 'C1', 'C2', 'C3', 'C4', 'D1'];
    private $klasifikasiOptions = ['ADM/Keuangan', 'Hublang', 'Pengolahan', 'SPI', 'Sumber', 'Trandist'];
    private $unitKerjaOptions = ['Direksi', 'Dewas', 'Cigasong', 'Jatitujuh', 'Kadipaten', 'Majalengka', 'Panyingkiran', 'Pusat', 'Rajagaluh', 'Sukahaji', 'Sukaraja', 'Talaga', 'Usaha Terminal Air'];
    private $jabatanOptions = ['Direktur', 'Dewan Pengawas', 'Bendahara', 'Fungsional SPI', 'Ka SPI', 'Kabag', 'Kacab', 'Kasubag', 'Kaunit', 'Kaur', 'Staf', 'Kontrak'];
    private $bagianOptions = [
        'Dirut', 'Dewas', 'Admin & Keuangan', 'ADM Umum & Sarana', 'Baca Meter',
        'Distribusi', 'Distribusi & Penyambungan', 'Fungsional SPI ADM & Keuangan',
        'Fungsional SPI Teknik', 'Gudang', 'Hublang', 'Ka SPI', 'Kacab', 'Kasir',
        'Kaunit', 'Keu', 'Lahta', 'MSDM', 'Operator', 'Pemasaran & Informasi',
        'Pembukuan', 'Pemeliharaan', 'Pengaduan & Tagihan', 'Pengolahan Data',
        'Perencanaan', 'Produksi', 'Rutin Teknik', 'Koordinator Satpam Pusat',
        'Satpam', 'Staf Baca Meter', 'Staf Distribusi & Penyambungan', 'Staf Kasir',
        'Staf Pembukuan & Keu', 'Staf Produksi Pusat', 'Staf Produksi (Operator)',
        'Staf IKK Dawuan', 'Staf Produksi', 'Staf Umum'
    ];
    // ======================================

    /**
     * Menampilkan halaman utama (dashboard) dengan jumlah total karyawan.
     * Metode ini hanya untuk halaman home.blade.php.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Menghitung jumlah total karyawan tetap dari model Pegawai
        $jumlahKaryawanTetap = Pegawai::count();

        // Menghitung jumlah total karyawan kontrak dari model KaryawanKontrak
        // Pastikan model KaryawanKontrak telah dibuat dan terhubung ke tabel yang benar.
        $jumlahKaryawanKontrak = KaryawanKontrak::count();

        // Mengirimkan jumlah ke view 'home'
        return view('home', compact('jumlahKaryawanTetap', 'jumlahKaryawanKontrak'));
    }

    // --- Metode untuk Karyawan Tetap (Pegawai) ---

    /**
     * Menampilkan daftar data karyawan tetap dengan filter dan paginasi.
     * Ini adalah logika yang sebelumnya ada di PegawaiController@index.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showKaryawanTetap(Request $request)
    {
        $query = Pegawai::query();

        // --- Filter yang sudah ada ---
        if ($request->filled('search')) { $query->where('nama', 'like', '%' . $request->search . '%'); }
        if ($request->filled('gender')) { $query->where('gender', $request->gender); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('kelipatan')) {
            if ((int)$request->kelipatan > 0) { $query->whereRaw('masa_kerja % ? = 0', [(int)$request->kelipatan]); }
        }
        // --- Filter untuk dropdown fixed ---
        if ($request->filled('golongan')) { $query->where('golongan', $request->golongan); }
        if ($request->filled('klasifikasi')) { $query->where('klasifikasi', $request->klasifikasi); }
        if ($request->filled('unit_kerja')) { $query->where('unit_kerja', $request->unit_kerja); }
        if ($request->filled('jabatan')) { $query->where('jabatan', $request->jabatan); }
        if ($request->filled('bagian')) { $query->where('bagian', $request->bagian); }
        // -------------------------------

        $jumlahAktif = (clone $query)->where('status', 'Aktif')->count();
        $jumlahNonAktif = (clone $query)->where('status', 'Tidak Aktif')->count();

        $pegawais = $query->paginate(10)->withQueryString();

        // --- Variabel untuk dropdown (fixed list atau distinct dari DB untuk yang lain) ---
        $genders = Pegawai::select('gender')->distinct()->pluck('gender');
        $statuses = Pegawai::select('status')->distinct()->pluck('status');
        $masaKerjaList = Pegawai::select('masa_kerja')->distinct()->orderBy('masa_kerja')->pluck('masa_kerja');
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->pluck('keluarga_status');

        // Mengirim daftar pilihan fixed ke view
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;

        return view('karyawantetap', compact(
            'pegawais',
            'jumlahAktif',
            'jumlahNonAktif',
            'genders',
            'statuses',
            'masaKerjaList',
            'keluargaStatusList',
            'golonganOptions',
            'klasifikasiOptions',
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions'
        ));
    }

    /**
     * Menampilkan form untuk menambah data karyawan tetap.
     *
     * @return \Illuminate\View\View
     */
    public function createTetap()
    {
        // Mengirim daftar pilihan fixed ke view 'tambah'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = Pegawai::select('gender')->distinct()->pluck('gender');
        $statuses = Pegawai::select('status')->distinct()->pluck('status');
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->pluck('keluarga_status');

        return view('tambah', compact(
            'golonganOptions',
            'klasifikasiOptions',
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions',
            'genders',
            'statuses',
            'keluargaStatusList'
        ));
    }

    /**
     * Menyimpan data karyawan tetap baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTetap(Request $request)
    {
        // Validasi data yang masuk dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nomor_induk' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'bagian' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'required|string|max:100',
            'keluarga_status' => 'required|string|max:100',
            'keluarga_anak' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'golongan' => 'required|string',
            'gaji' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Menghitung masa kerja berdasarkan tanggal masuk
        $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());

        // Membuat record baru di tabel Pegawai
        Pegawai::create([
            ...$validated,
            'masa_kerja' => (int)$masaKerja,
        ]);

        // Redirect ke halaman daftar karyawan tetap dengan pesan sukses
        return redirect()->route('karyawan.tetap')->with('success', 'Data pegawai tetap berhasil disimpan.');
    }

    /**
     * Menampilkan form untuk mengedit data karyawan tetap.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editTetap($id)
    {
        // Mencari data pegawai berdasarkan ID, atau tampilkan 404 jika tidak ditemukan
        $pegawai = Pegawai::findOrFail($id);

        // Mengirim daftar pilihan fixed dan dinamis ke view 'edit'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = Pegawai::select('gender')->distinct()->pluck('gender');
        $statuses = Pegawai::select('status')->distinct()->pluck('status');
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->pluck('keluarga_status');

        return view('edit', compact(
            'pegawai',
            'golonganOptions',
            'klasifikasiOptions',
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions',
            'genders',
            'statuses',
            'keluargaStatusList'
        ));
    }

    /**
     * Memperbarui data karyawan tetap di database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTetap(Request $request, $id)
    {
        // Mencari data pegawai berdasarkan ID
        $pegawai = Pegawai::findOrFail($id);

        // Validasi data yang masuk dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nomor_induk' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'bagian' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'required|string|max:100',
            'keluarga_status' => 'required|string|max:100',
            'keluarga_anak' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'golongan' => 'required|string',
            'gaji' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Menghitung ulang masa kerja jika tanggal masuk berubah
        $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());

        // Memperbarui record pegawai
        $pegawai->update([
            ...$validated,
            'masa_kerja' => (int)$masaKerja,
        ]);

        // Redirect ke halaman daftar karyawan tetap dengan pesan sukses
        return redirect()->route('karyawan.tetap')->with('success', 'Data sudah diperbarui');
    }

    /**
     * Menghapus satu data karyawan tetap dari database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyTetap($id)
    {
        // Mencari dan menghapus data pegawai
        Pegawai::findOrFail($id)->delete();
        // Redirect ke halaman daftar karyawan tetap dengan pesan sukses
        return redirect()->route('karyawan.tetap')->with('success', 'Data sudah terhapus');
    }

    /**
     * Menghapus banyak data karyawan tetap sekaligus.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBulkTetap(Request $request)
    {
        $idsJson = $request->input('ids_to_delete');
        $ids = json_decode($idsJson, true);

        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        try {
            Pegawai::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' data pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan preview PDF dari data karyawan tetap berdasarkan filter.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function previewPdfTetap(Request $request)
    {
        $query = Pegawai::query();

        if ($request->filled('search')) { $query->where('nama', 'like', '%' . $request->search . '%'); }
        if ($request->filled('gender')) { $query->where('gender', $request->gender); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('kelipatan')) {
            if ((int)$request->kelipatan > 0) { $query->whereRaw('masa_kerja % ? = 0', [(int)$request->kelipatan]); }
        }
        if ($request->filled('jabatan')) { $query->where('jabatan', $request->jabatan); }
        if ($request->filled('bagian')) { $query->where('bagian', $request->bagian); }
        if ($request->filled('unit_kerja')) { $query->where('unit_kerja', $request->unit_kerja); }
        if ($request->filled('klasifikasi')) { $query->where('klasifikasi', $request->klasifikasi); }
        if ($request->filled('keluarga_status')) { $query->where('keluarga_status', $request->keluarga_status); }
        if ($request->filled('golongan')) { $query->where('golongan', $request->golongan); }

        $pegawais = $query->get();
        return view('pdf', compact('pegawais')); // Asumsi view 'pdf' bisa digunakan untuk kedua jenis karyawan
    }

    /**
     * Mengekspor data karyawan tetap ke format PDF berdasarkan filter.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdfTetap(Request $request)
    {
        $query = Pegawai::query();

        if ($request->filled('search')) { $query->where('nama', 'like', '%' . $request->search . '%'); }
        if ($request->filled('gender')) { $query->where('gender', $request->gender); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('kelipatan')) {
            if ((int)$request->kelipatan > 0) { $query->whereRaw('masa_kerja % ? = 0', [(int)$request->kelipatan]); }
        }
        if ($request->filled('jabatan')) { $query->where('jabatan', $request->jabatan); }
        if ($request->filled('bagian')) { $query->where('bagian', $request->bagian); }
        if ($request->filled('unit_kerja')) { $query->where('unit_kerja', $request->unit_kerja); }
        if ($request->filled('klasifikasi')) { $query->where('klasifikasi', $request->klasifikasi); }
        if ($request->filled('keluarga_status')) { $query->where('keluarga_status', $request->keluarga_status); }
        if ($request->filled('golongan')) { $query->where('golongan', $request->golongan); }

        $pegawais = $query->get();
        $pdf = PDF::loadView('pdf', compact('pegawais'))->setPaper('a4', 'landscape');
        return $pdf->download('data_pegawai_tetap.pdf');
    }

    // --- Metode untuk Karyawan Kontrak ---

    /**
     * Menampilkan daftar data karyawan kontrak dengan filter dan paginasi.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showKaryawanKontrak(Request $request)
    {
        $query = KaryawanKontrak::query();

        // --- Filter untuk Karyawan Kontrak (sesuaikan jika ada filter spesifik) ---
        if ($request->filled('search')) { $query->where('nama', 'like', '%' . $request->search . '%'); }
        if ($request->filled('gender')) { $query->where('gender', $request->gender); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        // Masa kerja tidak dihitung otomatis, jadi filter kelipatan mungkin tidak relevan
        // if ($request->filled('kelipatan')) { /* ... */ }

        // Filter untuk dropdown fixed (gunakan yang relevan untuk kontrak)
        if ($request->filled('unit_kerja')) { $query->where('unit_kerja', $request->unit_kerja); }
        if ($request->filled('jabatan')) { $query->where('jabatan', $request->jabatan); }
        if ($request->filled('bagian')) { $query->where('bagian', $request->bagian); }
        // Klasifikasi dan Golongan mungkin tidak ada untuk kontrak
        // if ($request->filled('klasifikasi')) { $query->where('klasifikasi', $request->klasifikasi); }
        // if ($request->filled('golongan')) { $query->where('golongan', $request->golongan); }


        $jumlahAktifKontrak = (clone $query)->where('status', 'Aktif')->count();
        $jumlahNonAktifKontrak = (clone $query)->where('status', 'Tidak Aktif')->count();

        $karyawanKontrak = $query->paginate(10)->withQueryString();

        // Variabel untuk dropdown (fixed list atau distinct dari DB untuk yang lain)
        // Sesuaikan jika ada perbedaan opsi untuk karyawan kontrak
        $genders = KaryawanKontrak::select('gender')->distinct()->pluck('gender');
        $statuses = KaryawanKontrak::select('status')->distinct()->pluck('status');
        $masaKerjaList = KaryawanKontrak::select('masa_kerja')->distinct()->orderBy('masa_kerja')->pluck('masa_kerja');
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->pluck('keluarga_status');

        // Mengirim daftar pilihan fixed ke view
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        // Klasifikasi dan Golongan mungkin tidak relevan untuk kontrak
        $klasifikasiOptions = []; // Kosongkan atau sesuaikan jika ada
        $golonganOptions = []; // Kosongkan atau sesuaikan jika ada


        return view('karyawankontrak', compact(
            'karyawanKontrak',
            'jumlahAktifKontrak',
            'jumlahNonAktifKontrak',
            'genders',
            'statuses',
            'masaKerjaList',
            'keluargaStatusList',
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions',
            'klasifikasiOptions', // Tetap dikirim, tapi mungkin kosong
            'golonganOptions' // Tetap dikirim, tapi mungkin kosong
        ));
    }

    /**
     * Menampilkan form untuk menambah data karyawan kontrak.
     *
     * @return \Illuminate\View\View
     */
    public function createKontrak()
    {
        // Mengirim daftar pilihan fixed ke view 'tambah_kontrak'
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = KaryawanKontrak::select('gender')->distinct()->pluck('gender');
        $statuses = KaryawanKontrak::select('status')->distinct()->pluck('status');
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->pluck('keluarga_status');

        return view('tambah_kontrak', compact(
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions',
            'genders',
            'statuses',
            'keluargaStatusList'
        ));
    }

    /**
     * Menyimpan data karyawan kontrak baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeKontrak(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nomor_induk' => 'required|string|max:50', // Bisa jadi NIK atau Nomor Kontrak
            'jabatan' => 'required|string|max:100',
            'bagian' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            // 'klasifikasi' tidak ada
            'keluarga_status' => 'required|string|max:100',
            'keluarga_anak' => 'required|string|max:100',
            // 'tanggal_masuk' tidak ada
            'masa_kerja' => 'nullable|integer', // Tidak dihitung otomatis, bisa diisi manual
            'tanggal_perhitungan' => 'required|date', // Kolom baru
            // 'golongan' tidak ada
            'gaji' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        KaryawanKontrak::create($validated);

        return redirect()->route('karyawan.kontrak')->with('success', 'Data karyawan kontrak berhasil disimpan.');
    }

    /**
     * Menampilkan form untuk mengedit data karyawan kontrak.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editKontrak($id)
    {
        $karyawanKontrak = KaryawanKontrak::findOrFail($id);

        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = KaryawanKontrak::select('gender')->distinct()->pluck('gender');
        $statuses = KaryawanKontrak::select('status')->distinct()->pluck('status');
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->pluck('keluarga_status');

        return view('edit_kontrak', compact(
            'karyawanKontrak',
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions',
            'genders',
            'statuses',
            'keluargaStatusList'
        ));
    }

    /**
     * Memperbarui data karyawan kontrak di database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateKontrak(Request $request, $id)
    {
        $karyawanKontrak = KaryawanKontrak::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nomor_induk' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'bagian' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'keluarga_status' => 'required|string|max:100',
            'keluarga_anak' => 'required|string|max:100',
            'masa_kerja' => 'nullable|integer',
            'tanggal_perhitungan' => 'required|date',
            'gaji' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $karyawanKontrak->update($validated);

        return redirect()->route('karyawan.kontrak')->with('success', 'Data karyawan kontrak berhasil diperbarui.');
    }

    /**
     * Menghapus satu data karyawan kontrak dari database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyKontrak($id)
    {
        KaryawanKontrak::findOrFail($id)->delete();
        return redirect()->route('karyawan.kontrak')->with('success', 'Data karyawan kontrak berhasil dihapus.');
    }

    /**
     * Menghapus banyak data karyawan kontrak sekaligus.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBulkKontrak(Request $request)
    {
        $idsJson = $request->input('ids_to_delete');
        $ids = json_decode($idsJson, true);

        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        try {
            KaryawanKontrak::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' data karyawan kontrak berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan preview PDF dari data karyawan kontrak berdasarkan filter.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function previewPdfKontrak(Request $request)
    {
        $query = KaryawanKontrak::query();

        if ($request->filled('search')) { $query->where('nama', 'like', '%' . $request->search . '%'); }
        if ($request->filled('gender')) { $query->where('gender', $request->gender); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('jabatan')) { $query->where('jabatan', $request->jabatan); }
        if ($request->filled('bagian')) { $query->where('bagian', $request->bagian); }
        if ($request->filled('unit_kerja')) { $query->where('unit_kerja', $request->unit_kerja); }
        if ($request->filled('keluarga_status')) { $query->where('keluarga_status', $request->keluarga_status); }

        $karyawanKontrak = $query->get();
        return view('pdf_kontrak', compact('karyawanKontrak')); // Asumsi ada view 'pdf_kontrak'
    }

    /**
     * Mengekspor data karyawan kontrak ke format PDF berdasarkan filter.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdfKontrak(Request $request)
    {
        $query = KaryawanKontrak::query();

        if ($request->filled('search')) { $query->where('nama', 'like', '%' . $request->search . '%'); }
        if ($request->filled('gender')) { $query->where('gender', $request->gender); }
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('jabatan')) { $query->where('jabatan', $request->jabatan); }
        if ($request->filled('bagian')) { $query->where('bagian', $request->bagian); }
        if ($request->filled('unit_kerja')) { $query->where('unit_kerja', $request->unit_kerja); }
        if ($request->filled('keluarga_status')) { $query->where('keluarga_status', $request->keluarga_status); }

        $karyawanKontrak = $query->get();
        $pdf = PDF::loadView('pdf_kontrak', compact('karyawanKontrak'))->setPaper('a4', 'landscape');
        return $pdf->download('data_karyawan_kontrak.pdf');
    }
}
