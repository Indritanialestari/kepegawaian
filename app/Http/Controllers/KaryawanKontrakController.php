<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KaryawanKontrak; // Model untuk Karyawan Kontrak
use App\Models\Pegawai; // Import model Pegawai
use Illuminate\Http\Request;
use Carbon\Carbon;

class KaryawanKontrakController extends Controller
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
        'Satpam', 'Staf Baca Meter','Pembaca Meter','Staf Distribusi & Penyambungan', 'Staf Kasir',
        'Staf Pembukuan & Keu', 'Staf Produksi Pusat', 'Staf Produksi (Operator)','Staf Operator', 'Staf Adm',
        'Staf IKK Dawuan', 'Staf Produksi', 'Staf Umum', 'Staf Pelaksana',
        'Office Boy',
    ];
    // ======================================

    /**
     * Menampilkan daftar data karyawan kontrak dengan filter dan paginasi.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = KaryawanKontrak::query();

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

        $jumlahAktifKontrak = (clone $query)->where('status', 'Aktif')->count();
        $jumlahNonAktifKontrak = (clone $query)->where('status', 'Tidak Aktif')->count();

        // Mengambil data karyawan dan menyimpannya dalam variabel $karyawanKontrak
        $karyawanKontrak = $query->paginate(10)->withQueryString();

        // --- Variabel untuk dropdown (fixed list atau distinct dari DB untuk yang lain) ---
        $genders = KaryawanKontrak::select('gender')->distinct()->whereNotNull('gender')->pluck('gender');
        $statuses = KaryawanKontrak::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $masaKerjaList = KaryawanKontrak::select('masa_kerja')->distinct()->whereNotNull('masa_kerja')->orderBy('masa_kerja')->pluck('masa_kerja');
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status');

        // Mengirim daftar pilihan fixed ke view
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;

        // Mengubah nama variabel yang dikirim ke compact() agar sesuai
        return view('karyawankontrak', compact(
            'karyawanKontrak',
            'jumlahAktifKontrak',
            'jumlahNonAktifKontrak',
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
     * Menampilkan form untuk menambah data karyawan kontrak.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengirim daftar pilihan fixed ke view 'tambah_kontrak'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = KaryawanKontrak::select('gender')->distinct()->whereNotNull('gender')->pluck('gender');
        $statuses = KaryawanKontrak::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status');

        return view('tambah_kontrak', compact(
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
     * Menyimpan data karyawan kontrak baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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

        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    /**
     * Menampilkan form untuk mengedit data karyawan kontrak.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $karyawanKontrak = KaryawanKontrak::find($id);

        if (!$karyawanKontrak) {
            abort(404, 'Data karyawan kontrak tidak ditemukan.');
        }

        // Mengirim daftar pilihan fixed ke view 'edit_kontrak'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = KaryawanKontrak::select('gender')->distinct()->whereNotNull('gender')->pluck('gender');
        $statuses = KaryawanKontrak::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status');

        return view('edit_kontrak', compact(
            'karyawanKontrak',
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
     * Memperbarui data karyawan kontrak.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari form
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
    
        // Cari data karyawan kontrak yang akan diperbarui
        $karyawanKontrak = KaryawanKontrak::find($id);
    
        if (!$karyawanKontrak) {
            return back()->with('error', 'Data karyawan kontrak tidak ditemukan.');
        }
    
        // Periksa apakah jabatan diubah dari 'Kontrak'
        if ($validated['jabatan'] !== 'Kontrak') {
            // Jika jabatan berubah menjadi karyawan tetap, pindahkan data
    
            // Hapus data dari tabel KaryawanKontrak
            $karyawanKontrak->delete();
    
            // Hitung masa kerja
            $masaKerja = null;
            if ($validated['tanggal_masuk']) {
                $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());
            }
    
            // Buat data baru di tabel Pegawai
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
    
            return redirect()->route('karyawan-tetap.index')->with('success', 'Data karyawan kontrak berhasil dipindahkan ke pegawai tetap.');
        }
    
        // Jika jabatan tidak berubah, update data seperti biasa
        $karyawanKontrak->update($validated);
    
        return redirect()->route('karyawan-kontrak.index')->with('success', 'Data karyawan kontrak berhasil diperbarui.');
    }

    /**
     * Menghapus satu data karyawan kontrak dari database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        KaryawanKontrak::findOrFail($id)->delete();
        return redirect()->route('karyawan-kontrak.index')->with('success', 'Data karyawan kontrak berhasil dihapus.');
    }

    /**
     * Menghapus banyak data karyawan kontrak sekaligus.
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
            KaryawanKontrak::whereIn('id', $ids)->delete();
            return redirect()->route('karyawan-kontrak.index')->with('success', count($ids) . ' data karyawan kontrak berhasil dihapus.');
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
    public function previewPdf(Request $request)
    {
        $query = KaryawanKontrak::query();

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

        $karyawanKontraks = (clone $query)->get();
        return view('pdf', compact('karyawanKontraks'));
    }

    /**
     * Mengekspor data karyawan kontrak ke format PDF berdasarkan filter.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $query = KaryawanKontrak::query();

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

        $karyawanKontraks = (clone $query)->get();
        $pdf = PDF::loadView('pdf', compact('karyawanKontraks'))->setPaper('a4', 'landscape');
        return $pdf->download('data_karyawan_kontrak.pdf');
    }
}
