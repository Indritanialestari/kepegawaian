@extends('layouts.app') {{-- Menggunakan layout utama Anda --}}

@section('title', 'Edit Karyawan Kontrak') {{-- Mengatur judul halaman --}}

@section('content')
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-md max-w-2xl">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Karyawan Kontrak: {{ $karyawanKontrak->nama }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('karyawan-kontrak.update', $karyawanKontrak->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Penting untuk metode PUT --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $karyawanKontrak->nama) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="nomor_induk" class="block text-sm font-medium text-gray-700">Nomor Induk</label>
                    <input type="text" name="nomor_induk" id="nomor_induk" value="{{ old('nomor_induk', $karyawanKontrak->nomor_induk) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('nomor_induk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawanKontrak->tanggal_lahir ? \Carbon\Carbon::parse($karyawanKontrak->tanggal_lahir)->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('tanggal_lahir')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Gender</option>
                        <option value="Male" {{ old('gender', $karyawanKontrak->gender) == 'Male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Female" {{ old('gender', $karyawanKontrak->gender) == 'Female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatanOptions as $option)
                            <option value="{{ $option }}" {{ old('jabatan', $karyawanKontrak->jabatan) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('jabatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="bagian" class="block text-sm font-medium text-gray-700">Bagian</label>
                    <select name="bagian" id="bagian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Bagian</option>
                        @foreach($bagianOptions as $option)
                            <option value="{{ $option }}" {{ old('bagian', $karyawanKontrak->bagian) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('bagian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                    <select name="unit_kerja" id="unit_kerja" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Unit Kerja</option>
                        @foreach($unitKerjaOptions as $option)
                            <option value="{{ $option }}" {{ old('unit_kerja', $karyawanKontrak->unit_kerja) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('unit_kerja')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="pendidikan" class="block text-sm font-medium text-gray-700">Pendidikan</label>
                    <input type="text" name="pendidikan" id="pendidikan" value="{{ old('pendidikan', $karyawanKontrak->pendidikan) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('pendidikan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="keluarga_status" class="block text-sm font-medium text-gray-700">Status Keluarga</label>
                    <select name="keluarga_status" id="keluarga_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Status Keluarga</option>
                        @foreach($keluargaStatusList as $option)
                            <option value="{{ $option }}" {{ old('keluarga_status', $karyawanKontrak->keluarga_status) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('keluarga_status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="keluarga_anak" class="block text-sm font-medium text-gray-700">Jumlah Anak</label>
                    <input type="text" name="keluarga_anak" id="keluarga_anak" value="{{ old('keluarga_anak', $karyawanKontrak->keluarga_anak) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('keluarga_anak')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="masa_kerja" class="block text-sm font-medium text-gray-700">Masa Kerja (contoh: 5 tahun 3 bulan)</label>
                    <input type="text" name="masa_kerja" id="masa_kerja" value="{{ old('masa_kerja', $karyawanKontrak->masa_kerja) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('masa_kerja')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tanggal_perhitungan" class="block text-sm font-medium text-gray-700">Tanggal Perhitungan</label>
                    <input type="date" name="tanggal_perhitungan" id="tanggal_perhitungan" value="{{ old('tanggal_perhitungan', $karyawanKontrak->tanggal_perhitungan ? \Carbon\Carbon::parse($karyawanKontrak->tanggal_perhitungan)->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('tanggal_perhitungan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="gaji" class="block text-sm font-medium text-gray-700">Gaji</label>
                    <input type="text" name="gaji" id="gaji" value="{{ old('gaji', $karyawanKontrak->gaji) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('gaji')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Status</option>
                        <option value="Aktif" {{ old('status', $karyawanKontrak->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status', $karyawanKontrak->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Data
                </button>
                <a href="{{ route('karyawan-kontrak.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
