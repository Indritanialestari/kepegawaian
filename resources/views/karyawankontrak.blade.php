@extends('layouts.app') {{-- Menggunakan layout utama Anda --}}

@section('title', 'Data Karyawan Kontrak') {{-- Mengatur judul halaman --}}

@section('content')
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Data Karyawan Kontrak</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tambahkan pesan error jika ada, dari bulk delete --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('karyawan-kontrak.index') }}" method="GET" class="mb-6 p-4 border border-gray-200 rounded-md grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="col-span-full mb-4">
                <h3 class="text-lg font-semibold">Filter Data</h3>
            </div>

            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Gender</option>
                    @foreach($genders as $option)
                        <option value="{{ $option }}" {{ request('gender') == $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $option)
                        <option value="{{ $option }}" {{ request('status') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="masa_kerja" class="block text-sm font-medium text-gray-700">Masa Kerja (Tahun)</label>
                <input type="number" name="masa_kerja" id="masa_kerja" value="{{ request('masa_kerja') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       min="0">
            </div>

            {{-- Dropdown Fixed Options (hanya yang relevan untuk kontrak) --}}
            <div>
                <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                <select name="unit_kerja" id="unit_kerja" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Unit Kerja</option>
                    @foreach($unitKerjaOptions as $option)
                        <option value="{{ $option }}" {{ request('unit_kerja') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                <select name="jabatan" id="jabatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatanOptions as $option)
                        <option value="{{ $option }}" {{ request('jabatan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="bagian" class="block text-sm font-medium text-gray-700">Bagian</label>
                <select name="bagian" id="bagian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Bagian</option>
                    @foreach($bagianOptions as $option)
                        <option value="{{ $option }}" {{ request('bagian') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-full flex justify-between items-center mt-4">
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('karyawan-kontrak.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                        Reset Filter
                    </a>
                </div>
                <div>
                    <a href="{{ route('karyawan-kontrak.exportPdf', request()->query()) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                        Download PDF
                    </a>
                </div>
            </div>
        </form>

        <div class="mb-6 flex space-x-4">
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-md">
                Jumlah Karyawan Kontrak Aktif: <span class="font-bold">{{ $jumlahAktifKontrak }}</span>
            </div>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md">
                Jumlah Karyawan Kontrak Tidak Aktif: <span class="font-bold">{{ $jumlahNonAktifKontrak }}</span>
            </div>
        </div>

        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('karyawan-kontrak.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                + Tambah Karyawan Kontrak
            </a>
            {{-- Form untuk Hapus Data Terpilih --}}
            <form id="bulk-delete-form" action="{{ route('karyawan-kontrak.destroy.bulk') }}" method="POST">
                @csrf
                @method('DELETE')
                {{-- Input tersembunyi ini akan diisi oleh JavaScript sebelum submit --}}
                <input type="hidden" name="ids_to_delete" id="ids-to-delete-input">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Hapus Data Terpilih
                </button>
            </form>
        </div>

        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            <div class="flex items-center">
                                <input id="checkbox-all-items" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-all-items" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Nama</th>
                        {{-- <th scope="col" class="px-6 py-3 min-w-max-content">Nomor Induk</th> --}}
                        <th scope="col" class="px-6 py-3 min-w-max-content">Tanggal Lahir</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Gender</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Jabatan</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Bagian</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Unit Kerja</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Pendidikan</th>
                        {{-- <th scope="col" class="px-6 py-3 min-w-max-content">Klasifikasi</th> --}}
                        <th scope="col" class="px-6 py-3 min-w-max-content">Status Keluarga</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Jml. Anak</th>
                        {{-- <th scope="col" class="px-6 py-3 min-w-max-content">Tanggal Masuk</th> --}}
                        <th scope="col" class="px-6 py-3 min-w-max-content">Masa Kerja</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Tanggal Perhitungan</th> {{-- Kolom baru --}}
                        {{-- <th scope="col" class="px-6 py-3 min-w-max-content">Golongan</th> --}}
                        <th scope="col" class="px-6 py-3 min-w-max-content">Gaji</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Status</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyawanKontrak as $kontrak)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                <input id="checkbox-item-{{ $kontrak->id }}" type="checkbox" name="selected_ids[]" value="{{ $kontrak->id }}" class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-item-{{ $kontrak->id }}" class="sr-only">checkbox</label>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $kontrak->nama }}</td>
                        {{-- <td class="px-6 py-4">{{ $kontrak->nomor_induk }}</td> --}}
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($kontrak->tanggal_lahir)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">{{ ucfirst($kontrak->gender) }}</td>
                        <td class="px-6 py-4">{{ $kontrak->jabatan }}</td>
                        <td class="px-6 py-4">{{ $kontrak->bagian }}</td>
                        <td class="px-6 py-4">{{ $kontrak->unit_kerja }}</td>
                        <td class="px-6 py-4">{{ $kontrak->pendidikan }}</td>
                        {{-- <td class="px-6 py-4">{{ $kontrak->klasifikasi }}</td> --}}
                        <td class="px-6 py-4">{{ $kontrak->keluarga_status }}</td>
                        <td class="px-6 py-4 text-center">{{ $kontrak->keluarga_anak }}</td>
                        {{-- <td class="px-6 py-4">{{ \Carbon\Carbon::parse($kontrak->tanggal_masuk)->format('Y-m-d') }}</td> --}}
                        <td class="px-6 py-4 text-center">{{ $kontrak->masa_kerja ?? '-' }} tahun</td> {{-- Masa kerja bisa null --}}
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($kontrak->tanggal_perhitungan)->format('Y-m-d') }}</td> {{-- Kolom baru --}}
                        {{-- <td class="px-6 py-4 text-center">{{ $kontrak->golongan }}</td> --}}
                        <td class="px-6 py-4 text-right">Rp {{ number_format($kontrak->gaji, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $kontrak->status }}</td>
                        <td class="px-6 py-4 flex items-center space-x-2">
                            <a href="{{ route('karyawan-kontrak.edit', $kontrak->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('karyawan-kontrak.destroy', $kontrak->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="px-6 py-4 text-center text-gray-500">Tidak ada data karyawan kontrak yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $karyawanKontrak->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxAll = document.getElementById('checkbox-all-items');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const bulkDeleteForm = document.getElementById('bulk-delete-form');
            const idsToDeleteInput = document.getElementById('ids-to-delete-input');

            if (checkboxAll) {
                checkboxAll.addEventListener('change', function() {
                    itemCheckboxes.forEach(cb => cb.checked = this.checked);
                });
            }

            if (bulkDeleteForm) {
                bulkDeleteForm.addEventListener('submit', function(event) {
                    const selectedIds = Array.from(itemCheckboxes)
                                            .filter(cb => cb.checked)
                                            .map(cb => cb.value);

                    if (selectedIds.length === 0) {
                        alert('Pilih setidaknya satu data untuk dihapus.');
                        event.preventDefault();
                    } else {
                        if (!confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data terpilih?')) {
                            event.preventDefault();
                        } else {
                            idsToDeleteInput.value = JSON.stringify(selectedIds);
                        }
                    }
                });
            }
        });
    </script>
@endsection
