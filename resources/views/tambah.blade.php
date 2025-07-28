@extends('layouts.app') {{-- Menggunakan layout yang baru dibuat --}}

@section('title', 'Tambah Pegawai Baru') {{-- Mengatur judul halaman --}}

@section('content')
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-md max-w-2xl">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Pegawai Baru</h1>

        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="nomor_induk" class="block text-sm font-medium text-gray-700">Nomor Induk</label>
                    <input type="text" name="nomor_induk" id="nomor_induk" value="{{ old('nomor_induk') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @error('nomor_induk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @error('tanggal_lahir')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Gender</option>
                        @foreach($genders as $option)
                            <option value="{{ $option }}" {{ old('gender') == $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatanOptions as $option)
                            <option value="{{ $option }}" {{ old('jabatan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('jabatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="bagian" class="block text-sm font-medium text-gray-700">Bagian</label>
                    <select name="bagian" id="bagian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Bagian</option>
                        @foreach($bagianOptions as $option)
                            <option value="{{ $option }}" {{ old('bagian') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('bagian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                    <select name="unit_kerja" id="unit_kerja" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Unit Kerja</option>
                        @foreach($unitKerjaOptions as $option)
                            <option value="{{ $option }}" {{ old('unit_kerja') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('unit_kerja')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="pendidikan" class="block text-sm font-medium text-gray-700">Pendidikan</label>
                    <input type="text" name="pendidikan" id="pendidikan" value="{{ old('pendidikan') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"> {{-- Hapus 'required' di sini --}}
                    @error('pendidikan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="klasifikasi" class="block text-sm font-medium text-gray-700">Klasifikasi</label>
                    <select name="klasifikasi" id="klasifikasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Klasifikasi</option>
                        @foreach($klasifikasiOptions as $option)
                            <option value="{{ $option }}" {{ old('klasifikasi') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('klasifikasi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="keluarga_status" class="block text-sm font-medium text-gray-700">Status Keluarga</label>
                    <select name="keluarga_status" id="keluarga_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Status Keluarga</option>
                        @foreach($keluargaStatusList as $option)
                            <option value="{{ $option }}" {{ old('keluarga_status') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('keluarga_status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="keluarga_anak" class="block text-sm font-medium text-gray-700">Jumlah Anak</label>
                    <input type="number" name="keluarga_anak" id="keluarga_anak" value="{{ old('keluarga_anak') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                    @error('keluarga_anak')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @error('tanggal_masuk')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="golongan" class="block text-sm font-medium text-gray-700">Golongan</label>
                    <select name="golongan" id="golongan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Golongan</option>
                        @foreach($golonganOptions as $option)
                            <option value="{{ $option }}" {{ old('golongan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('golongan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="gaji" class="block text-sm font-medium text-gray-700">Gaji</label>
                    <input type="number" name="gaji" id="gaji" value="{{ old('gaji') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                    @error('gaji')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">Pilih Status</option>
                        @foreach($statuses as $option)
                            <option value="{{ $option }}" {{ old('status') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan Data
                </button>
                <a href="{{ route('home') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection