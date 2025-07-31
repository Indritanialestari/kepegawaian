<?php

namespace App\Http\Controllers;

use App\Models\Pegawai; // Model untuk Karyawan Tetap
use App\Models\KaryawanKontrak; // Model untuk Karyawan Kontrak
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (dashboard) dengan jumlah total karyawan.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Menghitung jumlah total karyawan tetap dari model Pegawai
        $jumlahKaryawanTetap = Pegawai::count();

        // Menghitung jumlah total karyawan kontrak dari model KaryawanKontrak
        $jumlahKaryawanKontrak = KaryawanKontrak::count();

        // Mengirimkan jumlah ke view 'home'
        return view('home', compact('jumlahKaryawanTetap', 'jumlahKaryawanKontrak'));
    }
}
