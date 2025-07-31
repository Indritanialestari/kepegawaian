<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KaryawanKontrak; // Model untuk Karyawan Kontrak
use App\Models\Pegawai; // BARU: Import model Pegawai untuk fungsionalitas pindah
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
        'Satpam', 'Staf Baca Meter', 'Staf Distribusi & Penyambungan', 'Staf Kasir',
        'Staf Pembukuan & Keu', 'Staf Produksi Pusat', 'Staf Produksi (Operator)',
        'Staf IKK Dawuan', 'Staf Produksi', 'Staf Umum',
        'Staf Pelaksana Unit: Sukaraja',
        'Pembaca Meter Cab. Talaga',
        'Pembukuan Cab. Kadipaten',
        'Staf Pembaca Meter Cab. Kadipaten',
        'Staf Pembaca Meter Unit Sukaraja',
        'Staf Pembaca Meter Unit Rajagaluh',
        'Staf Pembaca Meter Unit Cigasong',
        'Pembaca Meter Jatitujuh',
        'Baca Meter Sukahaji',
        'Pembaca Meter Talaga',
        'Staf Operator Kdp',
        'Pembaca Meter Unit Panyingkiran',
        'Staf Adm Cab. Mjl',
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
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('masa_kerja')) {
            $query->where('masa_kerja', 'like', '%' . $request->masa_kerja . '%');
        }

        // Filter untuk dropdown fixed
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

        $jumlahAktifKontrak = (clone $query)->where('status', 'Aktif')->count();
        $jumlahNonAktifKontrak = (clone $query)->where('status', 'Tidak Aktif')->count();

        $karyawanKontrak = $query->paginate(10)->withQueryString();

        // Variabel untuk dropdown
        $genders = KaryawanKontrak::select('gender')->distinct()->whereNotNull('gender')->pluck('gender');
        $statuses = KaryawanKontrak::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $masaKerjaList = KaryawanKontrak::select('masa_kerja')->distinct()->whereNotNull('masa_kerja')->orderBy('masa_kerja')->pluck('masa_kerja');
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status');

        // Mengirim daftar pilihan fixed ke view
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions; // Akan berisi semua jabatan
        $bagianOptions = $this->bagianOptions;
        $klasifikasiOptions = $this->klasifikasiOptions; // Tetap dikirim untuk konsistensi filter
        $golonganOptions = $this->golonganOptions; // Tetap dikirim untuk konsistensi filter


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
            'klasifikasiOptions',
            'golonganOptions'
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
        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions; // Semua jabatan
        $bagianOptions = $this->bagianOptions;
        $golonganOptions = $this->golonganOptions; // Ditambahkan
        $klasifikasiOptions = $this->klasifikasiOptions; // Ditambahkan

        // Ambil distinct values dari database untuk dropdown dinamis
        $genders = KaryawanKontrak::select('gender')->distinct()->whereNotNull('gender')->pluck('gender')
                    ->merge(Pegawai::select('gender')->distinct()->whereNotNull('gender')->pluck('gender'))
                    ->unique()->sort()->values();
        $statuses = KaryawanKontrak::select('status')->distinct()->whereNotNull('status')->pluck('status')
                    ->merge(Pegawai::select('status')->distinct()->whereNotNull('status')->pluck('status'))
                    ->unique()->sort()->values();
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status')
                                ->merge(Pegawai::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status'))
                                ->unique()->sort()->values();

        return view('tambah_kontrak', compact(
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions',
            'golonganOptions', // Ditambahkan
            'klasifikasiOptions', // Ditambahkan
            'genders',
            'statuses',
            'keluargaStatusList'
        ));
    }

    /**
     * Menyimpan data karyawan baru (kontrak atau tetap) ke database.
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
            'jabatan' => 'required|string|max:100', // TIDAK ADA in:Kontrak lagi
            'bagian' => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100', // Untuk Pegawai
            'keluarga_status' => 'nullable|string|max:100',
            'keluarga_anak' => 'nullable|string|max:100',
            'tanggal_masuk' => 'nullable|date', // Untuk Pegawai
            'masa_kerja' => 'nullable|string', // Untuk Kontrak (manual)
            'tanggal_perhitungan' => 'nullable|date', // Untuk Kontrak
            'golongan' => 'nullable|string', // Untuk Pegawai
            'gaji' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
        ]);

        // Inisialisasi semua field yang mungkin ada di kedua model, dengan null sebagai default
        $data = array_merge([
            'nama' => null, 'tanggal_lahir' => null, 'gender' => null, 'nomor_induk' => null,
            'jabatan' => null, 'bagian' => null, 'unit_kerja' => null, 'pendidikan' => null,
            'klasifikasi' => null, 'keluarga_status' => null, 'keluarga_anak' => null,
            'tanggal_masuk' => null, 'masa_kerja' => null, 'tanggal_perhitungan' => null,
            'golongan' => null, 'gaji' => null, 'status' => null,
        ], $validated);


        // Tentukan apakah ini karyawan kontrak atau tetap berdasarkan jabatan
        if ($data['jabatan'] === 'Kontrak') {
            // Data untuk Karyawan Kontrak
            KaryawanKontrak::create([
                'nama' => $data['nama'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'gender' => $data['gender'],
                'nomor_induk' => $data['nomor_induk'],
                'jabatan' => $data['jabatan'],
                'bagian' => $data['bagian'],
                'unit_kerja' => $data['unit_kerja'],
                'pendidikan' => $data['pendidikan'],
                'keluarga_status' => $data['keluarga_status'],
                'keluarga_anak' => $data['keluarga_anak'],
                'masa_kerja' => $data['masa_kerja'],
                'tanggal_perhitungan' => $data['tanggal_perhitungan'],
                'gaji' => $data['gaji'],
                'status' => $data['status'],
            ]);
            $redirectRoute = 'karyawan.kontrak.index'; // Menggunakan nama rute yang konsisten
            $successMessage = 'Data karyawan kontrak berhasil disimpan.';
        } else {
            // Data untuk Karyawan Tetap
            // Hitung masa kerja jika tanggal masuk tersedia
            $masaKerja = null;
            if ($data['tanggal_masuk']) {
                $masaKerja = Carbon::parse($data['tanggal_masuk'])->diffInYears(Carbon::now());
            }

            Pegawai::create([
                'nama' => $data['nama'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'gender' => $data['gender'],
                'nomor_induk' => $data['nomor_induk'],
                'jabatan' => $data['jabatan'],
                'bagian' => $data['bagian'],
                'unit_kerja' => $data['unit_kerja'],
                'pendidikan' => $data['pendidikan'],
                'klasifikasi' => $data['klasifikasi'],
                'keluarga_status' => $data['keluarga_status'],
                'keluarga_anak' => $data['keluarga_anak'],
                'tanggal_masuk' => $data['tanggal_masuk'],
                'masa_kerja' => $masaKerja,
                'golongan' => $data['golongan'],
                'gaji' => $data['gaji'],
                'status' => $data['status'],
            ]);
            $redirectRoute = 'karyawan.tetap.index'; // Menggunakan nama rute yang konsisten
            $successMessage = 'Data pegawai tetap berhasil disimpan.';
        }

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
        $karyawanKontrak = KaryawanKontrak::findOrFail($id);

        $unitKerjaOptions = $this->unitKerjaOptions;
        $jabatanOptions = $this->jabatanOptions; // Semua jabatan
        $bagianOptions = $this->bagianOptions;
        $golonganOptions = $this->golonganOptions; // Ditambahkan
        $klasifikasiOptions = $this->klasifikasiOptions; // Ditambahkan

        // Ambil distinct values dari database untuk dropdown dinamis
        $genders = KaryawanKontrak::select('gender')->distinct()->whereNotNull('gender')->pluck('gender')
                    ->merge(Pegawai::select('gender')->distinct()->whereNotNull('gender')->pluck('gender'))
                    ->unique()->sort()->values();
        $statuses = KaryawanKontrak::select('status')->distinct()->whereNotNull('status')->pluck('status')
                    ->merge(Pegawai::select('status')->distinct()->whereNotNull('status')->pluck('status'))
                    ->unique()->sort()->values();
        $keluargaStatusList = KaryawanKontrak::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status')
                                ->merge(Pegawai::select('keluarga_status')->distinct()->whereNotNull('keluarga_status')->pluck('keluarga_status'))
                                ->unique()->sort()->values();

        return view('edit_kontrak', compact(
            'karyawanKontrak',
            'unitKerjaOptions',
            'jabatanOptions',
            'bagianOptions',
            'golonganOptions', // Ditambahkan
            'klasifikasiOptions', // Ditambahkan
            'genders',
            'statuses',
            'keluargaStatusList'
        ));
    }

    /**
     * Memperbarui data karyawan (kontrak atau tetap) di database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $karyawanKontrak = KaryawanKontrak::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'nomor_induk' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100', // TIDAK ADA in:Kontrak lagi
            'bagian' => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|string|max:100', // Untuk Pegawai
            'keluarga_status' => 'nullable|string|max:100',
            'keluarga_anak' => 'nullable|string|max:100',
            'tanggal_masuk' => 'nullable|date', // Untuk Pegawai
            'masa_kerja' => 'nullable|string', // Untuk Kontrak (manual)
            'tanggal_perhitungan' => 'nullable|date', // Untuk Kontrak
            'golongan' => 'nullable|string', // Untuk Pegawai
            'gaji' => 'nullable|string',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
        ]);

        // Inisialisasi semua field yang mungkin ada di kedua model, dengan null sebagai default
        $data = array_merge([
            'nama' => null, 'tanggal_lahir' => null, 'gender' => null, 'nomor_induk' => null,
            'jabatan' => null, 'bagian' => null, 'unit_kerja' => null, 'pendidikan' => null,
            'klasifikasi' => null, 'keluarga_status' => null, 'keluarga_anak' => null,
            'tanggal_masuk' => null, 'masa_kerja' => null, 'tanggal_perhitungan' => null,
            'golongan' => null, 'gaji' => null, 'status' => null,
        ], $validated);


        // Cek apakah jabatan berubah dari 'Kontrak' ke non-Kontrak
        if ($karyawanKontrak->jabatan === 'Kontrak' && $data['jabatan'] !== 'Kontrak') {
            // Pindah dari KaryawanKontrak ke Pegawai
            $karyawanKontrak->delete(); // Hapus dari tabel kontrak

            // Hitung masa kerja jika tanggal masuk tersedia (untuk Pegawai)
            $masaKerja = null;
            if ($data['tanggal_masuk']) {
                $masaKerja = Carbon::parse($data['tanggal_masuk'])->diffInYears(Carbon::now());
            }
            $data['masa_kerja'] = $masaKerja; // Set masa_kerja untuk pegawai

            // Hapus field yang hanya ada di KaryawanKontrak sebelum membuat Pegawai
            unset($data['tanggal_perhitungan']);

            Pegawai::create($data); // Buat record baru di tabel pegawai
            $redirectRoute = 'karyawan.tetap.index'; // Mengarahkan ke daftar karyawan tetap
            $successMessage = 'Data karyawan kontrak berhasil dipindahkan ke pegawai tetap.';
        } else {
            // Tetap di KaryawanKontrak (jabatan tetap 'Kontrak')
            $karyawanKontrak->update($data);
            $redirectRoute = 'karyawan.kontrak.index'; // Tetap di daftar karyawan kontrak
            $successMessage = 'Data karyawan kontrak berhasil diperbarui.';
        }

        return redirect()->route($redirectRoute)->with('success', $successMessage);
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
        return redirect()->route('karyawan.kontrak.index')->with('success', 'Data karyawan kontrak berhasil dihapus.');
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
            return redirect()->route('karyawan.kontrak.index')->with('success', count($ids) . ' data karyawan kontrak berhasil dihapus.');
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
    public function exportPdf(Request $request)
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
