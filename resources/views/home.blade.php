@extends('layouts.app') {{-- Menggunakan layout utama Anda --}}

@section('title', 'Halaman Utama') {{-- Judul halaman yang akan muncul di tab browser --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">Selamat Datang di Sistem Informasi Kepegawaian</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {{-- Card untuk Karyawan Tetap --}}
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center justify-between transform transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-blue-200">
            <div class="text-6xl mb-6 text-blue-600">👨‍💼</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Total Karyawan Tetap</h2>
            {{-- Menampilkan jumlah karyawan tetap, default 0 jika belum ada --}}
            <p class="text-7xl font-extrabold text-blue-700 mb-8">{{ $jumlahKaryawanTetap ?? 0 }}</p>
            <a href="{{ route('karyawan-tetap.index') }}" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                Lihat Detail Karyawan Tetap
                <span class="ml-2">→</span>
            </a>
        </div>

        {{-- Card untuk Karyawan Kontrak --}}
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center justify-between transform transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-green-200">
            <div class="text-6xl mb-6 text-green-600">🤝</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Total Karyawan Kontrak</h2>
            {{-- Menampilkan jumlah karyawan kontrak, default 0 jika belum ada --}}
            <p class="text-7xl font-extrabold text-green-700 mb-8">{{ $jumlahKaryawanKontrak ?? 0 }}</p>
            <a href="{{ route('karyawan-kontrak.index') }}" class="w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus="ring-green-500 focus:ring-opacity-75">
                Lihat Detail Karyawan Kontrak
                <span class="ml-2">→</span>
            </a>
        </div>

        <!-- {{-- Anda bisa menambahkan card lain di sini jika diperlukan, misalnya untuk statistik umum --}}
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center justify-between transform transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-purple-200">
            <div class="text-6xl mb-6 text-purple-600">📊</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Ringkasan Statistik</h2>
            <p class="text-lg text-gray-600 mb-8 text-center">Lihat laporan dan statistik lainnya untuk manajemen kepegawaian yang lebih baik.</p>
            <a href="#" class="w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75">
                Lihat Laporan
                <span class="ml-2">→</span>
            </a>
        </div> -->
    </div>
</div>
@endsection
