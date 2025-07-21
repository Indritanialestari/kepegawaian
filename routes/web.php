<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;

// Halaman login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Proses login (sederhana)
Route::post('/login', function () {
    return redirect()->route('home');
})->name('login.submit');

// Proses logout
Route::post('/logout', function () {
    return redirect()->route('login');
})->name('logout');

// Halaman utama menampilkan data pegawai
Route::get('/', [PegawaiController::class, 'index'])->name('home');

// Halaman tambah data
Route::get('/tambah', [PegawaiController::class, 'create'])->name('tambah');

// Simpan data pegawai
Route::post('/tambah', [PegawaiController::class, 'store'])->name('pegawai.store');

// Hapus satu data
Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

// Hapus banyak data sekaligus
Route::delete('/pegawai', [PegawaiController::class, 'destroyBulk'])->name('pegawai.destroy.bulk');

// Halaman edit data (form dengan data terisi)
Route::get('/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.edit');

// Proses update data
Route::post('/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');

Route::get('/pegawai/preview-pdf', [PegawaiController::class, 'previewPdf'])->name('pegawai.previewPdf');

// Proses eksport ke PDF (setelah di-review)
Route::get('/pegawai/export', [PegawaiController::class, 'exportPdf'])->name('pegawai.exportPdf');

