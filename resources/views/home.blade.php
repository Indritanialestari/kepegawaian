@extends('layouts.app') {{-- Menggunakan layout yang baru dibuat --}}

@section('title', 'Home - Data Pegawai') {{-- Mengatur judul halaman --}}

@section('content')
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Data Pegawai</h1>

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

        <form action="{{ route('home') }}" method="GET" class="mb-6 p-4 border border-gray-200 rounded-md grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
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
                <label for="kelipatan" class="block text-sm font-medium text-gray-700">Masa Kerja Kelipatan</label>
                <input type="number" name="kelipatan" id="kelipatan" value="{{ request('kelipatan') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       min="1">
            </div>

            <div>
                <label for="keluarga_status" class="block text-sm font-medium text-gray-700">Status Keluarga</label>
                <select name="keluarga_status" id="keluarga_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Status Keluarga</option>
                    @foreach($keluargaStatusList as $option)
                        <option value="{{ $option }}" {{ request('keluarga_status') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Fixed Options --}}
            <div>
                <label for="golongan" class="block text-sm font-medium text-gray-700">Golongan</label>
                <select name="golongan" id="golongan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Golongan</option>
                    @foreach($golonganOptions as $option)
                        <option value="{{ $option }}" {{ request('golongan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="klasifikasi" class="block text-sm font-medium text-gray-700">Klasifikasi</label>
                <select name="klasifikasi" id="klasifikasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Klasifikasi</option>
                    @foreach($klasifikasiOptions as $option)
                        <option value="{{ $option }}" {{ request('klasifikasi') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

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
                    <a href="{{ route('home') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                        Reset Filter
                    </a>
                </div>
                <div>
                    <a href="{{ route('pegawai.exportPdf', request()->query()) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                        Download PDF
                    </a>
                </div>
            </div>
        </form>

        <div class="mb-6 flex space-x-4">
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-md">
                Jumlah Pegawai Aktif: <span class="font-bold">{{ $jumlahAktif }}</span>
            </div>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md">
                Jumlah Pegawai Tidak Aktif: <span class="font-bold">{{ $jumlahNonAktif }}</span>
            </div>
        </div>

        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('tambah') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                + Tambah Pegawai
            </a>
            {{-- Form untuk Hapus Data Terpilih --}}
            <form id="bulk-delete-form" action="{{ route('pegawai.destroy.bulk') }}" method="POST">
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
            {{-- HAPUS form id="pegawai-table-form" yang membungkus <table> --}}
            {{-- Karena tombol hapus ada di form terpisah (bulk-delete-form) --}}
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
                        <th scope="col" class="px-6 py-3 min-w-max-content">Nomor Induk</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Tanggal Lahir</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Gender</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Jabatan</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Bagian</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Unit Kerja</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Pendidikan</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Klasifikasi</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Status Keluarga</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Jml. Anak</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Tanggal Masuk</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Masa Kerja</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Golongan</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Gaji</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Status</th>
                        <th scope="col" class="px-6 py-3 min-w-max-content">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawais as $pegawai)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                {{-- Ganti name="ids[]" menjadi name="selected_ids[]" dan tambahkan class="item-checkbox" --}}
                                <input id="checkbox-item-{{ $pegawai->id }}" type="checkbox" name="selected_ids[]" value="{{ $pegawai->id }}" class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-item-{{ $pegawai->id }}" class="sr-only">checkbox</label>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $pegawai->nama }}</td>
                        <td class="px-6 py-4">{{ $pegawai->nomor_induk }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">{{ ucfirst($pegawai->gender) }}</td>
                        <td class="px-6 py-4">{{ $pegawai->jabatan }}</td>
                        <td class="px-6 py-4">{{ $pegawai->bagian }}</td>
                        <td class="px-6 py-4">{{ $pegawai->unit_kerja }}</td>
                        <td class="px-6 py-4">{{ $pegawai->pendidikan }}</td>
                        <td class="px-6 py-4">{{ $pegawai->klasifikasi }}</td>
                        <td class="px-6 py-4">{{ $pegawai->keluarga_status }}</td>
                        <td class="px-6 py-4 text-center">{{ $pegawai->keluarga_anak }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-center">{{ $pegawai->masa_kerja }} tahun</td>
                        <td class="px-6 py-4 text-center">{{ $pegawai->golongan }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($pegawai->gaji, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $pegawai->status }}</td>
                        <td class="px-6 py-4 flex items-center space-x-2">
                            <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="18" class="px-6 py-4 text-center text-gray-500">Tidak ada data pegawai yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $pegawais->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxAll = document.getElementById('checkbox-all-items');
            // Select checkboxes by class, karena name="selected_ids[]"
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const bulkDeleteForm = document.getElementById('bulk-delete-form');
            const idsToDeleteInput = document.getElementById('ids-to-delete-input');

            if (checkboxAll) {
                checkboxAll.addEventListener('change', function() {
                    itemCheckboxes.forEach(cb => cb.checked = this.checked);
                });
            }

            if (bulkDeleteForm) {
                // Hapus onsubmit="return confirm(...)" dari HTML, tangani di sini
                bulkDeleteForm.addEventListener('submit', function(event) {
                    const selectedIds = Array.from(itemCheckboxes)
                                          .filter(cb => cb.checked)
                                          .map(cb => cb.value);

                    if (selectedIds.length === 0) {
                        alert('Pilih setidaknya satu data untuk dihapus.');
                        event.preventDefault(); // Mencegah form submit jika tidak ada yang dipilih
                    } else {
                        // Tambahkan konfirmasi di sini
                        if (!confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data terpilih?')) {
                            event.preventDefault(); // Batalkan submit jika pengguna membatalkan
                        } else {
                            // Isi input tersembunyi dengan ID yang dipilih dalam format JSON string
                            idsToDeleteInput.value = JSON.stringify(selectedIds);
                            // Jika berhasil dikonfirmasi dan ID ada, form akan disubmit
                        }
                    }
                });
            }
        });
    </script>
@endsection