<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PegawaiController extends Controller
{
    // === Daftar Pilihan Dropdown Fixed ===
    private $golonganOptions = ['A4', 'B1', 'B2', 'B3', 'B4', 'C1', 'C2', 'C3', 'C4', 'D1'];
    private $klasifikasiOptions = ['ADM/Keuangan', 'Hublang', 'Pengolahan', 'SPI', 'Sumber', 'Trandist'];
    private $unitKerjaOptions = ['Direksi', 'Dewas', 'Cigasong', 'Jatitujuh', 'Kadipaten', 'Majalengka', 'Panyingkiran', 'Pusat', 'Rajagaluh', 'Sukahaji', 'Sukaraja', 'Talaga', 'Usaha Terminal Air'];
    private $jabatanOptions = ['Direktur', 'Dewan Pengawas', 'Bendahara', 'Fungsional SPI', 'Ka SPI', 'Kabag', 'Kacab', 'Kasubag', 'Kaunit', 'Kaur', 'Staf', 'Kontrak']; // 'Star' diganti 'Staf' jika typo
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

        $pegawais = $query->paginate(5)->withQueryString();

        // --- Variabel untuk dropdown (fixed list atau distinct dari DB untuk yang lain) ---
        $genders = Pegawai::select('gender')->distinct()->pluck('gender'); // Tetap distinct
        $statuses = Pegawai::select('status')->distinct()->pluck('status'); // Tetap distinct
        $masaKerjaList = Pegawai::select('masa_kerja')->distinct()->orderBy('masa_kerja')->pluck('masa_kerja'); // Tetap distinct
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->pluck('keluarga_status'); // Tetap distinct

        // Mengirim daftar pilihan fixed ke view
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        // ---------------------------------------------------------------------------------

        return view('home', compact(
            'pegawais',
            'jumlahAktif',
            'jumlahNonAktif',
            'genders',
            'statuses',
            'masaKerjaList',
            'keluargaStatusList',
            // Variabel baru untuk dropdown fixed
            'golonganOptions',
            'klasifikasiOptions',
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions'
        ));
    }

    public function create()
    {
        // Mengirim daftar pilihan fixed ke view 'tambah'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = Pegawai::select('gender')->distinct()->pluck('gender'); // Untuk gender di form tambah
        $statuses = Pegawai::select('status')->distinct()->pluck('status'); // Untuk status di form tambah
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->pluck('keluarga_status'); // Untuk keluarga_status di form tambah

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

    public function store(Request $request)
    {
        // Validasi tetap sama, karena nilai yang dikirim harus tetap valid
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nomor_induk' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100', // Akan divalidasi dengan nilai yang ada di daftar fixed
            'bagian' => 'required|string|max:100',  // Akan divalidasi dengan nilai yang ada di daftar fixed
            'unit_kerja' => 'required|string|max:100',// Akan divalidasi dengan nilai yang ada di daftar fixed
            'pendidikan' => 'required|string|max:100',
            'klasifikasi' => 'required|string|max:100',// Akan divalidasi dengan nilai yang ada di daftar fixed
            'keluarga_status' => 'required|string|max:100',
            'keluarga_anak' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'golongan' => 'required|string',         // Akan divalidasi dengan nilai yang ada di daftar fixed
            'gaji' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());

        Pegawai::create([
            ...$validated,
            'masa_kerja' => (int)$masaKerja,
        ]);

        return redirect()->route('home')->with('success', 'Data pegawai berhasil disimpan.');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // Mengirim daftar pilihan fixed ke view 'edit'
        $golonganOptions = $this->golonganOptions;
        $klasifikasiOptions = $this->klasifikasiOptions;
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions;
        $bagianOptions = $this->bagianOptions;
        $genders = Pegawai::select('gender')->distinct()->pluck('gender'); // Untuk gender di form edit
        $statuses = Pegawai::select('status')->distinct()->pluck('status'); // Untuk status di form edit
        $keluargaStatusList = Pegawai::select('keluarga_status')->distinct()->pluck('keluarga_status'); // Untuk keluarga_status di form edit

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

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nomor_induk' => 'required|string|max:50',
            'jabatan' => 'required|string|max:100',
            'bagian' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'pendidikan' => 'required|string|max:100',
            'klasifikasi' => 'required|string|max:100',
            'keluarga_status' => 'required|string|max:100',
            'keluarga_anak' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'golongan' => 'required|string',
            'gaji' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $masaKerja = Carbon::parse($validated['tanggal_masuk'])->diffInYears(Carbon::now());

        $pegawai->update([
            ...$validated,
            'masa_kerja' => (int)$masaKerja,
        ]);

        return redirect()->route('home')->with('success', 'Data sudah diperbarui');
    }

    public function destroy($id)
    {
        Pegawai::findOrFail($id)->delete();
        return redirect()->route('home')->with('success', 'Data sudah terhapus');
    }

    public function destroyBulk(Request $request)
    {
        // 1. Ambil string JSON dari input tersembunyi 'ids_to_delete'
        $idsJson = $request->input('ids_to_delete');

        // 2. Decode string JSON menjadi array PHP
        $ids = json_decode($idsJson, true); // 'true' untuk mengembalikan array asosiatif, tapi untuk array of values tetap array

        // 3. Validasi apakah $ids adalah array dan tidak kosong
        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        try {
            // Lakukan penghapusan menggunakan whereIn
            Pegawai::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' data pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat penghapusan
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

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
        return view('pdf', compact('pegawais'));
    }

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
        return $pdf->download('data_pegawai.pdf');
    }
}