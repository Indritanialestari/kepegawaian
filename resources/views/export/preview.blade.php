@extends('layout')

@section('content')
    <div class="p-4">
        <h1 class="text-xl font-bold mb-4">Preview PDF</h1>
        <table class="w-full border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Tanggal Lahir</th>
                    <th class="border p-2">Gender</th>
                    <th class="border p-2">Kontak</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Alamat</th>
                    <th class="border p-2">Tanggal Masuk</th>
                    <th class="border p-2">Masa Kerja</th>
                    <th class="border p-2">Golongan</th>
                    <th class="border p-2">Gaji</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pegawais as $pegawai)
                    <tr>
                        <td class="border p-2">{{ $pegawai->nama }}</td>
                        <td class="border p-2">{{ $pegawai->tanggal_lahir }}</td>
                        <td class="border p-2">{{ $pegawai->gender }}</td>
                        <td class="border p-2">{{ $pegawai->kontak }}</td>
                        <td class="border p-2">{{ $pegawai->email }}</td>
                        <td class="border p-2">{{ $pegawai->alamat }}</td>
                        <td class="border p-2">{{ $pegawai->tanggal_masuk }}</td>
                        <td class="border p-2">{{ $pegawai->masa_kerja }}</td>
                        <td class="border p-2">{{ $pegawai->golongan }}</td>
                        <td class="border p-2">{{ $pegawai->gaji }}</td>
                        <td class="border p-2">{{ $pegawai->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="border p-4 text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">Kembali</a>
            <a href="{{ route('pegawai.exportPdf', request()->all()) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Download PDF</a>
        </div>
    </div>
@endsection
