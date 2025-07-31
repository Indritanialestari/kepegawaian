<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pegawai; // Model untuk Karyawan Tetap
use App\Models\KaryawanKontrak; // Import model KaryawanKontrak
use Illuminate\Http\Request;
use Carbon\Carbon;

class KaryawanTetapController extends Controller
{
    // === Daftar Pilihan Dropdown Fixed ===
    private $golonganOptions = ['A4', 'B1', 'B2', 'B3', 'B4', 'C1', 'C2', 'C3', 'C4', 'D1'];
    private $klasifikasiOptions = ['ADM/Keuangan', 'Hublang', 'Pengolahan', 'SPI', 'Sumber', 'Trandist'];
    private $unitKerjaOptions = ['Direksi', 'Dewas', 'Cigasong', 'Jatitujuh', 'Kadipaten', 'Majalengka', 'Panyingkiran', 'Pusat', 'Rajagaluh', 'Sukahaji', 'Sukaraja', 'Talaga', 'Usaha Terminal Air'];
    // Jabatan sekarang mencakup semua opsi, termasuk 'Kontrak'
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
        'Staf IKK Dawuan', 'Staf Produksi', 'Staf Umum',
        'Staf Pelaksana Unit: Sukaraja', // Tambahan dari data Anda
        'Pembaca Meter Cab. Talaga', // Tambahan dari data Anda
        'Pembukuan Cab. Kadipaten', // Tambahan dari data Anda
        'Staf Pembaca Meter Cab. Kadipaten', // Tambahan dari data Anda
        'Staf Pembaca Meter Unit Sukaraja', // Tambahan dari data Anda
        'Staf Pembaca Meter Unit Rajagaluh', // Tambahan dari data Anda
        'Staf Pembaca Meter Unit Cigasong', // Tambahan dari data Anda
        'Pembaca Meter Jatitujuh', // Tambahan dari data Anda
        'Baca Meter Sukahaji', // Tambahan dari data Anda
        'Pembaca Meter Talaga', // Tambahan dari data Anda
        'Staf Operator Kdp', // Tambahan dari data Anda
        'Pembaca Meter Unit Panyingkiran', // Tambahan dari data Anda
        'Staf Adm Cab. Mjl', // Tambahan dari data Anda
        'Office Boy', // Tambahan dari data Anda
    ];
    // ======================================

    /**
     * Menampilkan daftar data karyawan tetap dengan filter dan paginasi.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
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
        $genders = Pegawai::select('gender')->distinct()->whereNotNull('gender')->pluck('gender');
        $statuses = Pegawai::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $masaKerjaList = Pegawai::select('masa_kerja')->distinct()->whereNotNull('masa_kerja')->orderBy('masa_kerja')->pluck('masa_kerja');
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status');

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
     * Menampilkan form untuk menambah data karyawan (tetap atau kontrak).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengirim daftar pilihan fixed ke view 'tambah'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions; // Semua jabatan
        $bagianOptions = $this->bagianOptions;
        $genders = Pegawai::select('gender')->distinct()->whereNotNull('gender')->pluck('gender');
        $statuses = Pegawai::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status');

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
     * Menyimpan data karyawan baru (tetap atau kontrak) ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'nomor_induk' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100', // Wajib ada untuk menentukan jenis karyawan
            'bagian' => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'keluarga_status' => 'nullable|string|max:100',
            'keluarga_anak' => 'nullable|string|max:100', // String untuk fleksibilitas
            'tanggal_masuk' => 'nullable|date', // Hanya untuk Pegawai
            'masa_kerja' => 'nullable|string', // String untuk fleksibilitas (tahun dan bulan)
            'golongan' => 'nullable|string', // Hanya untuk Pegawai
            'gaji' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
            'tanggal_perhitungan' => 'nullable|date', // Hanya untuk KaryawanKontrak
        ]);

        // Tentukan apakah ini karyawan kontrak atau tetap berdasarkan jabatan
        if ($validated['jabatan'] === 'Kontrak') {
            // Data untuk Karyawan Kontrak
            KaryawanKontrak::create([
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'gender' => $validated['gender'],
                'nomor_induk' => $validated['nomor_induk'],
                'jabatan' => $validated['jabatan'], // Akan selalu 'Kontrak'
                'bagian' => $validated['bagian'],
                'unit_kerja' => $validated['unit_kerja'],
                'pendidikan' => $validated['pendidikan'],
                'keluarga_status' => $validated['keluarga_status'],
                'keluarga_anak' => $validated['keluarga_anak'],
                'masa_kerja' => $validated['masa_kerja'],
                'tanggal_perhitungan' => $validated['tanggal_perhitungan'],
                'gaji' => $validated['gaji'],
                'status' => $validated['status'],
            ]);
            $redirectRoute = 'karyawan-kontrak.index';
            $successMessage = 'Data karyawan kontrak berhasil disimpan.';
        } else {
            // Data untuk Karyawan Tetap
            // Hitung masa kerja jika tanggal masuk tersedia
            $masaKerja = null;
            if ($validated['tanggal_masuk']) {
                $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());
            }

            Pegawai::create([
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'gender' => $validated['gender'],
                'nomor_induk' => $validated['nomor_induk'],
                'jabatan' => $validated['jabatan'],
                'bagian' => $validated['bagian'],
                'unit_kerja' => $validated['unit_kerja'],
                'pendidikan' => $validated['pendidikan'],
                'klasifikasi' => $validated['klasifikasi'],
                'keluarga_status' => $validated['keluarga_status'],
                'keluarga_anak' => $validated['keluarga_anak'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'masa_kerja' => $masaKerja, // Masa kerja dihitung otomatis untuk tetap
                'golongan' => $validated['golongan'],
                'gaji' => $validated['gaji'],
                'status' => $validated['status'],
            ]);
            $redirectRoute = 'karyawan-tetap.index';
            $successMessage = 'Data pegawai tetap berhasil disimpan.';
        }

        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    /**
     * Menampilkan form untuk mengedit data karyawan (tetap atau kontrak).
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Coba cari di Pegawai
        $pegawai = Pegawai::find($id);
        $karyawanKontrak = null;
        $isKontrak = false;

        if (!$pegawai) {
            // Jika tidak ditemukan di Pegawai, coba cari di KaryawanKontrak
            $karyawanKontrak = KaryawanKontrak::find($id);
            if (!$karyawanKontrak) {
                // Jika tidak ditemukan di kedua tabel, tampilkan 404
                abort(404, 'Data karyawan tidak ditemukan.');
            }
            $isKontrak = true;
            $dataToEdit = $karyawanKontrak;
        } else {
            $dataToEdit = $pegawai;
        }

        // Mengirim daftar pilihan fixed ke view 'edit'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions; // Semua jabatan
        $bagianOptions = $this->bagianOptions;
        $genders = Pegawai::select('gender')->distinct()->whereNotNull('gender')->pluck('gender')
                    ->merge(KaryawanKontrak::select('gender')->distinct()->whereNotNull('gender')->pluck('gender'))
                    ->unique()->sort()->values();
        $statuses = Pegawai::select('status')->distinct()->whereNotNull('status')->pluck('status')
                    ->merge(KaryawanKontrak::select('status')->distinct()->whereNotNull('status')->pluck('status'))
                    ->unique()->sort()->values();
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status')
                                ->merge(KaryawanKontrak::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status'))
                                ->unique()->sort()->values();

        return view('edit', compact(
            'dataToEdit', // Sekarang ini adalah variabel yang berisi data dari Pegawai atau KaryawanKontrak
            'isKontrak', // Untuk membantu Blade menentukan tampilan
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
     * Memperbarui data karyawan (tetap atau kontrak) di database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang masuk dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'nomor_induk' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100', // Wajib ada untuk menentukan jenis karyawan
            'bagian' => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'keluarga_status' => 'nullable|string|max:100',
            'keluarga_anak' => 'nullable|string|max:100',
            'tanggal_masuk' => 'nullable|date',
            'masa_kerja' => 'nullable|string', // String untuk fleksibilitas (tahun dan bulan)
            'golongan' => 'nullable|string',
            'gaji' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
            'tanggal_perhitungan' => 'nullable|date',
        ]);

        // Tentukan model yang akan digunakan berdasarkan jabatan baru
        $newJabatanIsKontrak = ($validated['jabatan'] === 'Kontrak');

        // Coba temukan karyawan di Pegawai
        $pegawai = Pegawai::find($id);
        $karyawanKontrak = null;
        $existingIsKontrak = false;

        if (!$pegawai) {
            // Jika tidak ditemukan di Pegawai, coba cari di KaryawanKontrak
            $karyawanKontrak = KaryawanKontrak::find($id);
            if (!$karyawanKontrak) {
                // Jika tidak ditemukan di kedua tabel, error
                return back()->with('error', 'Data karyawan tidak ditemukan.');
            }
            $existingIsKontrak = true;
        }

        // --- Logika Update/Pindah ---
        if ($existingIsKontrak && $newJabatanIsKontrak) {
            // Karyawan sudah kontrak, tetap kontrak -> Update KaryawanKontrak
            $karyawanKontrak->update($validated);
            $redirectRoute = 'karyawan-kontrak.index';
            $successMessage = 'Data karyawan kontrak berhasil diperbarui.';
        } elseif (!$existingIsKontrak && !$newJabatanIsKontrak) {
            // Karyawan sudah tetap, tetap tetap -> Update Pegawai
            // Hitung masa kerja jika tanggal masuk tersedia
            $masaKerja = null;
            if ($validated['tanggal_masuk']) {
                $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());
            }
            $pegawai->update([
                ...$validated,
                'masa_kerja' => $masaKerja,
            ]);
            $redirectRoute = 'karyawan-tetap.index';
            $successMessage = 'Data pegawai tetap berhasil diperbarui.';
        } elseif ($existingIsKontrak && !$newJabatanIsKontrak) {
            // Karyawan kontrak berubah jadi tetap -> Hapus dari KaryawanKontrak, buat di Pegawai
            $karyawanKontrak->delete();
            // Hitung masa kerja jika tanggal masuk tersedia
            $masaKerja = null;
            if ($validated['tanggal_masuk']) {
                $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());
            }
            Pegawai::create([
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'gender' => $validated['gender'],
                'nomor_induk' => $validated['nomor_induk'],
                'jabatan' => $validated['jabatan'],
                'bagian' => $validated['bagian'],
                'unit_kerja' => $validated['unit_kerja'],
                'pendidikan' => $validated['pendidikan'],
                'klasifikasi' => $validated['klasifikasi'], // Klasifikasi hanya untuk tetap
                'keluarga_status' => $validated['keluarga_status'],
                'keluarga_anak' => $validated['keluarga_anak'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'masa_kerja' => $masaKerja,
                'golongan' => $validated['golongan'], // Golongan hanya untuk tetap
                'gaji' => $validated['gaji'],
                'status' => $validated['status'],
            ]);
            $redirectRoute = 'karyawan-tetap.index';
            $successMessage = 'Data karyawan kontrak berhasil dipindahkan ke pegawai tetap.';
        } elseif (!$existingIsKontrak && $newJabatanIsKontrak) {
            // Karyawan tetap berubah jadi kontrak -> Hapus dari Pegawai, buat di KaryawanKontrak
            $pegawai->delete();
            KaryawanKontrak::create([
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'gender' => $validated['gender'],
                'nomor_induk' => $validated['nomor_induk'],
                'jabatan' => $validated['jabatan'], // Akan selalu 'Kontrak'
                'bagian' => $validated['bagian'],
                'unit_kerja' => $validated['unit_kerja'],
                'pendidikan' => $validated['pendidikan'],
                'keluarga_status' => $validated['keluarga_status'],
                'keluarga_anak' => $validated['keluarga_anak'],
                'masa_kerja' => $validated['masa_kerja'],
                'tanggal_perhitungan' => $validated['tanggal_perhitungan'],
                'gaji' => $validated['gaji'],
                'status' => $validated['status'],
            ]);
            $redirectRoute = 'karyawan-kontrak.index';
            $successMessage = 'Data pegawai tetap berhasil dipindahkan ke karyawan kontrak.';
        }

        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    /**
     * Menghapus satu data karyawan tetap dari database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Fungsi destroy ini hanya untuk Pegawai, tidak menangani KaryawanKontrak
        Pegawai::findOrFail($id)->delete();
        return redirect()->route('karyawan-tetap.index')->with('success', 'Data pegawai tetap berhasil dihapus.');
    }

    /**
     * Menghapus banyak data karyawan tetap sekaligus.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBulk(Request $request)
    {
        $idsJson = $request->input('ids_to_delete');
        $ids = json_decode($idsJson, true);

        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        try {
            Pegawai::whereIn('id', $ids)->delete();
            return redirect()->route('karyawan-tetap.index')->with('success', count($ids) . ' data pegawai berhasil dihapus.');
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
    public function previewPdf(Request $request)
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
    public function exportPdf(Request $request)
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
}
