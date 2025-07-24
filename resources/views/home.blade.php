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

<form method="GET" action="{{ route('home') }}" class="flex flex-wrap gap-4 mb-4">
    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="border p-2 rounded w-full md:w-1/3">

    <select name="gender" class="border p-2 rounded">
        <option value="">Semua Gender</option>
        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
    </select>

    <select name="kelipatan" class="border p-2 rounded">
        <option value="">Semua Masa Kerja</option>
        <option value="2" {{ request('kelipatan') == '2' ? 'selected' : '' }}>Kelipatan 2 Tahun</option>
        <option value="4" {{ request('kelipatan') == '4' ? 'selected' : '' }}>Kelipatan 4 Tahun</option>
    </select>

    <select name="status" class="border p-2 rounded">
        <option value="">Semua Status</option>
        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
    </select>

    <button type="submit" class="bg-gray-300 px-4 py-2 rounded">Filter</button>
</form>

<form method="POST" action="{{ route('pegawai.destroy.bulk') }}">
    @csrf
    @method('DELETE')

    <div class="mb-2">
        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded" onclick="return confirm('Yakin hapus data terpilih?')">Hapus Terpilih</button>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full bg-white shadow rounded text-sm border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-3 py-2 border"><input type="checkbox" id="selectAll"></th>
                    <th class="px-3 py-2 border">Nama</th>
                    <th class="px-3 py-2 border">Tanggal Lahir</th>
                    <th class="px-3 py-2 border">Gender</th>
                    <th class="px-3 py-2 border">Kontak</th>
                    <th class="px-3 py-2 border">Email</th>
                    <th class="px-3 py-2 border">Alamat</th>
                    <th class="px-3 py-2 border">Tanggal Masuk</th>
                    <th class="px-3 py-2 border">Masa Kerja</th>
                    <th class="px-3 py-2 border">Golongan</th>
                    <th class="px-3 py-2 border">Gaji</th>
                    <th class="px-3 py-2 border">Status</th>
                    <th class="px-3 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawais as $pegawai)
                @php
                    $isKelipatan4 = $pegawai->masa_kerja % 4 === 0;
                @endphp
                <tr class="border-t hover:bg-gray-100">
                    <td class="px-3 py-2 border"><input type="checkbox" name="ids[]" value="{{ $pegawai->id }}"></td>
                    <td class="px-3 py-2 border">{{ $pegawai->nama }}</td>
                    <td class="px-3 py-2 border">{{ $pegawai->tanggal_lahir }}</td>
                    <td class="px-3 py-2 border">{{ $pegawai->gender }}</td>
                    <td class="px-3 py-2 border">{{ $pegawai->kontak }}</td>
                    <td class="px-3 py-2 border">{{ $pegawai->email }}</td>
                    <td class="px-3 py-2 border max-w-[200px] whitespace-normal break-words">
                        {{ $pegawai->alamat }}
                    </td>

                    <td class="px-3 py-2 border">{{ $pegawai->tanggal_masuk }}</td>
                    <td class="px-3 py-2 border">{{ $pegawai->masa_kerja }}</td>
                    <td class="px-3 py-2 border {{ $isKelipatan4 ? 'text-red-500 font-bold' : '' }}">{{ $pegawai->golongan }}</td>
                    <td class="px-3 py-2 border">{{ $pegawai->gaji }}</td>
                    <td class="px-3 py-2 border {{ $pegawai->status === 'Aktif' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">{{ $pegawai->status }}</td>
                    <td class="px-3 py-2 border">
                        <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="text-blue-500 underline">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $pegawais->withQueryString()->links() }}
        </div>

    </div>
</form>

<script>
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection
