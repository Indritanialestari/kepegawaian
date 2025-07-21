<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PegawaiController extends Controller
{
public function index(Request $request)
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

    if ($request->filled('kelipatan')) {
        $query->whereRaw("CAST(strftime('%Y', 'now') AS INTEGER) - CAST(strftime('%Y', tanggal_masuk) AS INTEGER) % ? = 0", [
            $request->kelipatan
        ]);
    }

    $pegawais = $query->get();

    $genders = Pegawai::select('gender')->distinct()->pluck('gender');
    $statuses = Pegawai::select('status')->distinct()->pluck('status');

    $masaKerjaList = Pegawai::selectRaw("CAST(strftime('%Y', 'now') AS INTEGER) - CAST(strftime('%Y', tanggal_masuk) AS INTEGER) as masa_kerja")
        ->distinct()
        ->pluck('masa_kerja');

    $jumlahAktif = $pegawais->where('status', 'Aktif')->count();
    $jumlahNonAktif = $pegawais->where('status', 'Tidak Aktif')->count();

    return view('home', compact(
        'pegawais',
        'jumlahAktif',
        'jumlahNonAktif',
        'genders',
        'statuses',
        'masaKerjaList'
    ));
}



    public function create()
    {
        return view('tambah');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'gender' => 'required|in:Male,Female',
        'kontak' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'alamat' => 'required|string',
        'tanggal_masuk' => 'required|date',
        'golongan' => 'required|string',
        'gaji' => 'required|numeric|min:0',
        'status' => 'required|in:Aktif,Tidak Aktif',
    ]);

    // lanjut simpan data
    Pegawai::create([
        ...$validated,
        'masa_kerja' => 0, // atau hitung otomatis jika perlu
    ]);

    return redirect()->route('pegawai.index')->with('success', 'Data berhasil ditambahkan');
}

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $tanggalMasuk = Carbon::parse($request->tanggal_masuk);
        $masaKerja = now()->year - $tanggalMasuk->year;

        $pegawai->update([
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'gender' => $request->gender,
            'kontak' => $request->kontak,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'tanggal_masuk' => $request->tanggal_masuk,
            'masa_kerja' => $masaKerja,
            'golongan' => $request->golongan,
            'gaji' => $request->gaji,
            'status' => $request->status,
        ]);

        return redirect()->route('home', [])->with('success', 'Data sudah tersimpan');
    }

    public function destroy($id)
    {
        Pegawai::findOrFail($id)->delete();
        return redirect()->route('home')->with('success', 'Data sudah terhapus');
    }

    public function destroyBulk(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids && is_array($ids)) {
            Pegawai::whereIn('id', $ids)->delete();
            return redirect()->route('home')->with('success', 'Data sudah terhapus');
        }

        return redirect()->route('home')->with('success', 'Tidak ada data yang dipilih');
    }

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

    if ($request->filled('kelipatan')) {
        $query->whereRaw("CAST(strftime('%Y', 'now') AS INTEGER) - CAST(strftime('%Y', tanggal_masuk) AS INTEGER) % ? = 0", [
            $request->kelipatan
        ]);
    }

    $pegawais = $query->get();

    return view('preview-pdf', compact('pegawais'));
}


    public function exportPdf(Request $request)
{
    $pegawais = Pegawai::query();

    if ($request->filled('search')) {
        $pegawais->where('nama', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('gender')) {
        $pegawais->where('gender', $request->gender);
    }

    if ($request->filled('status')) {
        $pegawais->where('status', $request->status);
    }

    if ($request->filled('kelipatan')) {
        $pegawais->whereRaw("CAST(strftime('%Y', 'now') AS INTEGER) - CAST(strftime('%Y', tanggal_masuk) AS INTEGER) % ? = 0", [
            $request->kelipatan
        ]);
    }

    $pegawais = $pegawais->get();

    $pdf = \PDF::loadView('pdf', compact('pegawais'));
    return $pdf->download('data-pegawai.pdf');
}

}
