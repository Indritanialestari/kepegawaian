<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pegawai;
use App\Models\KaryawanKontrak;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KaryawanTetapController extends Controller
{
    // === Daftar Pilihan Dropdown Fixed ===
    private $golonganOptions = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3', 'B4', 'C1', 'C2', 'C3', 'C4', 'D1', 'D2', 'D3', 'D4'];
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
        'Satpam', 'Staf Baca Meter', 'Pembaca Meter', 'Staf Distribusi & Penyambungan', 'Staf Kasir',
        'Staf Pembukuan & Keu', 'Staf Produksi Pusat', 'Staf Produksi (Operator)', 'Staf Operator', 'Staf Adm',
        'Staf IKK Dawuan', 'Staf Produksi', 'Staf Umum', 'Staf Pelaksana',
        'Office Boy',
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
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

// --- FILTER KELIPATAN TAHUN YANG DIPERBAIKI ---
if ($request->filled('kelipatan') && $request->filled('tahun_kelipatan')) {
    $kelipatan = (int)$request->kelipatan;
    $tahun_kelipatan = (int)$request->tahun_kelipatan;

    if ($kelipatan > 0 && $tahun_kelipatan > 1900) {
        $query->whereRaw('(? - strftime("%Y", tanggal_masuk)) % ? = 0', [
            $tahun_kelipatan,
            $kelipatan
        ]);
    }
} elseif ($request->filled('kelipatan')) {
    $kelipatan = (int)$request->kelipatan;
    if ($kelipatan > 0) {
        $currentYear = date('Y');
        $query->whereRaw('(? - strftime("%Y", tanggal_masuk)) % ? = 0', [
            $currentYear,
            $kelipatan
        ]);
    }
}
// --- AKHIR PERBAIKAN ---

        // --- Filter untuk dropdown fixed ---
        if ($request->filled('golongan')) {
            $query->where('golongan', $request->golongan);
        }
        if ($request->filled('klasifikasi')) {
            $query->where('klasifikasi', $request->klasifikasi);
        }
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', $request->unit_kerja);
        }
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }
        if ($request->filled('bagian')) {
            $query->where('bagian', $request->bagian);
        }
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
        $jabatanOptions = $this->jabatanOptions;
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
            'jabatan' => 'nullable|string|max:100',
            'bagian' => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'keluarga_status' => 'nullable|string|max:100',
            'keluarga_anak' => 'nullable|string|max:100',
            'tanggal_masuk' => 'nullable|date',
            'masa_kerja' => 'nullable|string',
            'golongan' => 'nullable|string',
            'gaji' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
            'tanggal_perhitungan' => 'nullable|date',
        ]);

        if ($validated['jabatan'] === 'Kontrak') {
            KaryawanKontrak::create([
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'gender' => $validated['gender'],
                'nomor_induk' => $validated['nomor_induk'],
                'jabatan' => $validated['jabatan'],
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
// Kode baru
$masaKerja = null;
if ($validated['tanggal_masuk']) {
    // Ambil tahun dari tanggal masuk
    $tahunMasuk = Carbon::parse($validated['tanggal_masuk'])->year;
    // Ambil tahun dari tanggal saat ini
    $tahunSekarang = Carbon::now()->year;
    // Hitung masa kerja hanya berdasarkan selisih tahun
    $masaKerja = $tahunSekarang - $tahunMasuk;
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
                'masa_kerja' => $masaKerja,
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
        $pegawai = Pegawai::find($id);
        $karyawanKontrak = null;
        $isKontrak = false;

        if (!$pegawai) {
            $karyawanKontrak = KaryawanKontrak::find($id);
            if (!$karyawanKontrak) {
                abort(404, 'Data karyawan tidak ditemukan.');
            }
            $isKontrak = true;
            $dataToEdit = $karyawanKontrak;
        } else {
            $dataToEdit = $pegawai;
        }

        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
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
            'dataToEdit',
            'isKontrak',
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
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'nomor_induk' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:100',
            'bagian' => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100',
            'keluarga_status' => 'nullable|string|max:100',
            'keluarga_anak' => 'nullable|string|max:100',
            'tanggal_masuk' => 'nullable|date',
            'masa_kerja' => 'nullable|string',
            'golongan' => 'nullable|string',
            'gaji' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
            'tanggal_perhitungan' => 'nullable|date',
        ]);

        $newJabatanIsKontrak = ($validated['jabatan'] === 'Kontrak');
        $pegawai = Pegawai::find($id);
        $karyawanKontrak = null;
        $existingIsKontrak = false;

        if (!$pegawai) {
            $karyawanKontrak = KaryawanKontrak::find($id);
            if (!$karyawanKontrak) {
                return back()->with('error', 'Data karyawan tidak ditemukan.');
            }
            $existingIsKontrak = true;
        }

        if ($existingIsKontrak && $newJabatanIsKontrak) {
            $karyawanKontrak->update($validated);
            $redirectRoute = 'karyawan-kontrak.index';
            $successMessage = 'Data karyawan kontrak berhasil diperbarui.';
        } elseif (!$existingIsKontrak && !$newJabatanIsKontrak) {
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
            $karyawanKontrak->delete();
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
                'masa_kerja' => $masaKerja,
                'golongan' => $validated['golongan'],
                'gaji' => $validated['gaji'],
                'status' => $validated['status'],
            ]);
            $redirectRoute = 'karyawan-tetap.index';
            $successMessage = 'Data karyawan kontrak berhasil dipindahkan ke pegawai tetap.';
        } elseif (!$existingIsKontrak && $newJabatanIsKontrak) {
            $pegawai->delete();
            KaryawanKontrak::create([
                'nama' => $validated['nama'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'gender' => $validated['gender'],
                'nomor_induk' => $validated['nomor_induk'],
                'jabatan' => $validated['jabatan'],
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

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- FILTER KELIPATAN TAHUN YANG DIPERBAIKI V2 ---
        if ($request->filled('kelipatan') && $request->filled('tahun_kelipatan')) {
            $kelipatan = (int)$request->kelipatan;
            $tahun_kelipatan = (int)$request->tahun_kelipatan;

            if ($kelipatan > 0 && $tahun_kelipatan > 1900) {
                $query->whereRaw('(? - strftime("%Y", tanggal_masuk)) % ? = 0', [
                    $tahun_kelipatan,
                    $kelipatan
                ]);
            }
        } elseif ($request->filled('kelipatan')) {
            $kelipatan = (int)$request->kelipatan;
            if ($kelipatan > 0) {
                $currentYear = date('Y');
                $query->whereRaw('(? - strftime("%Y", tanggal_masuk)) % ? = 0', [
                    $currentYear,
                    $kelipatan
                ]);
            }
        }
        // --- AKHIR PERBAIKAN V2 ---

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }
        if ($request->filled('bagian')) {
            $query->where('bagian', $request->bagian);
        }
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', $request->unit_kerja);
        }
        if ($request->filled('klasifikasi')) {
            $query->where('klasifikasi', $request->klasifikasi);
        }
        if ($request->filled('keluarga_status')) {
            $query->where('keluarga_status', $request->keluarga_status);
        }
        if ($request->filled('golongan')) {
            $query->where('golongan', $request->golongan);
        }

        $pegawais = $query->get();
        return view('pdf', compact('pegawais'));
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

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- FILTER KELIPATAN TAHUN YANG DIPERBAIKI V2 ---
        if ($request->filled('kelipatan') && $request->filled('tahun_kelipatan')) {
            $kelipatan = (int)$request->kelipatan;
            $tahun_kelipatan = (int)$request->tahun_kelipatan;

            if ($kelipatan > 0 && $tahun_kelipatan > 1900) {
                $query->whereRaw('(? - strftime("%Y", tanggal_masuk)) % ? = 0', [
                    $tahun_kelipatan,
                    $kelipatan
                ]);
            }
        } elseif ($request->filled('kelipatan')) {
            $kelipatan = (int)$request->kelipatan;
            if ($kelipatan > 0) {
                $currentYear = date('Y');
                $query->whereRaw('(? - strftime("%Y", tanggal_masuk)) % ? = 0', [
                    $currentYear,
                    $kelipatan
                ]);
            }
        }
        // --- AKHIR PERBAIKAN V2 ---

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }
        if ($request->filled('bagian')) {
            $query->where('bagian', $request->bagian);
        }
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', $request->unit_kerja);
        }
        if ($request->filled('klasifikasi')) {
            $query->where('klasifikasi', $request->klasifikasi);
        }
        if ($request->filled('keluarga_status')) {
            $query->where('keluarga_status', $request->keluarga_status);
        }
        if ($request->filled('golongan')) {
            $query->where('golongan', $request->golongan);
        }

        $pegawais = $query->get();
        $pdf = PDF::loadView('pdf', compact('pegawais'))->setPaper('a4', 'landscape');
        return $pdf->download('data_pegawai_tetap.pdf');
    }
}