@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Data Pegawai</h1>

<form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="space-y-4">
    @csrf

    <input type="text" name="nama" value="{{ $pegawai->nama }}" class="border p-2 rounded w-full" required>
    <input type="date" name="tanggal_lahir" value="{{ $pegawai->tanggal_lahir }}" class="border p-2 rounded w-full" required>
    
    <select name="gender" class="border p-2 rounded w-full">
        <option value="male" {{ $pegawai->gender == 'male' ? 'selected' : '' }}>Male</option>
        <option value="female" {{ $pegawai->gender == 'female' ? 'selected' : '' }}>Female</option>
    </select>

    <input type="text" name="kontak" value="{{ $pegawai->kontak }}" class="border p-2 rounded w-full" required>
    <input type="email" name="email" value="{{ $pegawai->email }}" class="border p-2 rounded w-full" required>
    <textarea name="alamat" class="border p-2 rounded w-full">{{ $pegawai->alamat }}</textarea>
    
    <input type="date" name="tanggal_masuk" value="{{ $pegawai->tanggal_masuk }}" class="border p-2 rounded w-full" required>

    <select name="golongan" class="border p-2 rounded w-full">
        <option value="Gol-1" {{ $pegawai->golongan == 'Gol-1' ? 'selected' : '' }}>Gol-1</option>
        <option value="Gol-2" {{ $pegawai->golongan == 'Gol-2' ? 'selected' : '' }}>Gol-2</option>
        <option value="Gol-3" {{ $pegawai->golongan == 'Gol-3' ? 'selected' : '' }}>Gol-3</option>
        <option value="Gol-4" {{ $pegawai->golongan == 'Gol-4' ? 'selected' : '' }}>Gol-4</option>
    </select>

    <input type="number" name="gaji" value="{{ $pegawai->gaji }}" class="border p-2 rounded w-full" required>

    <select name="status" class="border p-2 rounded w-full">
        <option value="Aktif" {{ $pegawai->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Tidak Aktif" {{ $pegawai->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
    </select>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan Perubahan</button>
</form>
@endsection
