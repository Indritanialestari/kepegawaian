@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Data Pegawai</h1>

{{-- Notifikasi sukses --}}
@if(session('success'))
<div id="popupModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl text-center">
        <p class="text-green-700 font-semibold">{{ session('success') }}</p>
        <button onclick="document.getElementById('popupModal').style.display='none'" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Tutup</button>
    </div>
</div>
@endif

{{-- Klasifikasi status --}}
<div class="mb-4">
    <p class="text-sm">Jumlah Pegawai Aktif: <span class="font-bold text-green-600">{{ $jumlahAktif }}</span></p>
    <p class="text-sm">Jumlah Pegawai Tidak Aktif: <span class="font-bold text-red-600">{{ $jumlahNonAktif }}</span></p>
</div>

<div class="flex justify-between mb-4">
    <a href="{{ route('pegawai.exportPdf', request()->query()) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Download PDF</a>
    <a href="{{ route('tambah') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Data</a>
</div>

<div class="flex flex-wrap gap-4 mb-4">
    <input type="text" id="search" placeholder="Cari..." class="border p-2 rounded w-full md:w-1/3">

    <select id="genderFilter" class="border p-2 rounded">
        <option value="">Semua Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>

    <select id="masaKerjaFilter" class="border p-2 rounded">
        <option value="">Semua Masa Kerja</option>
        <option value="2">Kelipatan 2 Tahun</option>
        <option value="4">Kelipatan 4 Tahun</option>
    </select>

    <select id="statusFilter" class="border p-2 rounded">
        <option value="">Semua Status</option>
        <option value="aktif">Aktif</option>
        <option value="tidak aktif">Tidak Aktif</option>
    </select>
</div>

<form method="POST" action="{{ route('pegawai.destroy.bulk') }}">
    @csrf
    @method('DELETE')
    <div class="mb-2">
        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded" onclick="return confirm('Yakin hapus data terpilih?')">Hapus Terpilih</button>
    </div>

    <table class="table-auto w-full bg-white shadow rounded text-sm">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="px-2 py-1"><input type="checkbox" id="selectAll"></th>
                <th>Nama</th><th>Tanggal Lahir</th><th>Gender</th><th>Kontak</th>
                <th>Email</th><th>Alamat</th><th>Tanggal Masuk</th><th>Masa Kerja</th>
                <th>Golongan</th><th>Gaji</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody id="pegawaiTable">
        @foreach($pegawais as $pegawai)
        @php
            $isKelipatan4 = $pegawai->masa_kerja % 4 === 0;
        @endphp
        <tr class="border-t hover:bg-gray-100">
            <td class="px-2 py-1"><input type="checkbox" name="ids[]" value="{{ $pegawai->id }}"></td>
            <td class="px-2 py-1">{{ $pegawai->nama }}</td>
            <td>{{ $pegawai->tanggal_lahir }}</td>
            <td>{{ $pegawai->gender }}</td>
            <td>{{ $pegawai->kontak }}</td>
            <td>{{ $pegawai->email }}</td>
            <td>{{ $pegawai->alamat }}</td>
            <td>{{ $pegawai->tanggal_masuk }}</td>
            <td>{{ $pegawai->masa_kerja }}</td>
            <td class="{{ $isKelipatan4 ? 'text-red-500 font-bold' : '' }}">{{ $pegawai->golongan }}</td>
            <td>{{ $pegawai->gaji }}</td>
            <td class="{{ $pegawai->status === 'Aktif' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">{{ $pegawai->status }}</td>
            <td>
                <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="text-blue-500 underline">Detail</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</form>

<div class="mt-4 text-right space-x-2">
    <button class="bg-gray-300 px-3 py-1 rounded">&larr;</button>
    <button class="bg-gray-300 px-3 py-1 rounded">&rarr;</button>
</div>

<script>
    const searchInput = document.getElementById('search');
    const genderFilter = document.getElementById('genderFilter');
    const masaKerjaFilter = document.getElementById('masaKerjaFilter');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#pegawaiTable tr');

    function filterTable() {
        const searchVal = searchInput.value.toLowerCase();
        const genderVal = genderFilter.value.toLowerCase();
        const masaKerjaVal = masaKerjaFilter.value;
        const statusVal = statusFilter.value.toLowerCase();

        rows.forEach(row => {
            const cells = [...row.children];
            const rowText = row.innerText.toLowerCase();
            const genderText = cells[3].textContent.toLowerCase();
            const masaKerja = parseInt(cells[8].textContent);
            const statusText = cells[11].textContent.toLowerCase();

            const matchesSearch = rowText.includes(searchVal);
            const matchesGender = genderVal === "" || genderText === genderVal;
            const matchesStatus = statusVal === "" || statusText === statusVal;

            let matchesMasaKerja = true;
            if (masaKerjaVal === "2") {
                matchesMasaKerja = masaKerja % 2 === 0;
            } else if (masaKerjaVal === "4") {
                matchesMasaKerja = masaKerja % 4 === 0;
            }

            row.style.display = (matchesSearch && matchesGender && matchesMasaKerja && matchesStatus) ? "" : "none";
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    genderFilter.addEventListener('change', filterTable);
    masaKerjaFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection
