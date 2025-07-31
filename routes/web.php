<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanTetapController;
use App\Http\Controllers\KaryawanKontrakController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute ini
| dimuat oleh RouteServiceProvider dalam sebuah grup yang
| berisi grup middleware "web". Sekarang buatlah sesuatu yang hebat!
|
*/

// Rute untuk halaman utama (root URL)
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Rute untuk Karyawan Tetap ---
// Menggunakan resource route untuk CRUD yang lebih ringkas
Route::resource('karyawan-tetap', KaryawanTetapController::class)->except(['show']);

// Rute khusus untuk bulk delete dan PDF
Route::delete('/karyawan-tetap/bulk-delete', [KaryawanTetapController::class, 'destroyBulk'])->name('karyawan-tetap.destroy.bulk');
Route::get('/karyawan-tetap/preview-pdf', [KaryawanTetapController::class, 'previewPdf'])->name('karyawan-tetap.previewPdf');
Route::get('/karyawan-tetap/export', [KaryawanTetapController::class, 'exportPdf'])->name('karyawan-tetap.exportPdf');


// --- Rute untuk Karyawan Kontrak ---
// Menggunakan resource route untuk CRUD yang lebih ringkas
Route::resource('karyawan-kontrak', KaryawanKontrakController::class)->except(['show']);

// Rute khusus untuk bulk delete dan PDF
Route::delete('/karyawan-kontrak/bulk-delete', [KaryawanKontrakController::class, 'destroyBulk'])->name('karyawan-kontrak.destroy.bulk');
Route::get('/karyawan-kontrak/preview-pdf', [KaryawanKontrakController::class, 'previewPdf'])->name('karyawan-kontrak.previewPdf');
Route::get('/karyawan-kontrak/export', [KaryawanKontrakController::class, 'exportPdf'])->name('karyawan-kontrak.exportPdf');


// --- Rute Otentikasi (tetap sama) ---
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
    Auth::logout();
    return redirect()->route('login');
})->name('logout');
