@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tambah Data Pegawai</h1>
<form id="pegawaiForm" class="space-y-4" method="POST" action="{{ route('pegawai.store') }}">
    @csrf
    <div>
        <label class="block mb-1">Nama</label>
        <input name="nama" required class="w-full p-2 border rounded" placeholder="Masukkan nama">
    </div>
    <div>
        <label class="block mb-1">Tanggal Lahir</label>
        <input name="tanggal_lahir" type="date" required class="w-full p-2 border rounded">
    </div>
    <div>
        <label class="block mb-1">Gender</label>
        <select name="gender" required class="w-full p-2 border rounded">
            <option value="">-- Pilih Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div>
        <label class="block mb-1">Kontak</label>
        <input name="kontak" required class="w-full p-2 border rounded" placeholder="Masukkan nomor kontak">
    </div>
    <div>
        <label class="block mb-1">Email</label>
        <input name="email" type="email" required class="w-full p-2 border rounded" placeholder="Masukkan email">
    </div>
    <div>
        <label class="block mb-1">Alamat</label>
        <input name="alamat" required class="w-full p-2 border rounded" placeholder="Masukkan alamat">
    </div>
    <div>
        <label class="block mb-1">Tanggal Masuk</label>
        <input name="tanggal_masuk" type="date" id="tanggal_masuk" required class="w-full p-2 border rounded">
    </div>
    <div>
        <label class="block mb-1">Masa Kerja (tahun)</label>
        <input name="masa_kerja" type="text" id="masa_kerja" class="w-full p-2 border rounded bg-gray-100" readonly placeholder="Otomatis dihitung">
    </div>
    <div>
        <label class="block mb-1">Golongan</label>
        <select name="golongan" required class="w-full p-2 border rounded">
            <option value="">-- Pilih Golongan --</option>
            <option value="Gol-1">Gol-1</option>
            <option value="Gol-2">Gol-2</option>
            <option value="Gol-3">Gol-3</option>
            <option value="Gol-4">Gol-4</option>
        </select>
    </div>
    <div>
        <label class="block mb-1">Gaji</label>
        <input name="gaji" required class="w-full p-2 border rounded" placeholder="Masukkan gaji">
    </div>
    <div>
        <label class="block mb-1">Status</label>
        <select name="status" required class="w-full p-2 border rounded">
            <option value="">-- Pilih Status --</option>
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
</form>

<!-- JavaScript untuk menghitung masa kerja -->
<script>
    document.getElementById('tanggal_masuk').addEventListener('change', function () {
        const tanggalMasuk = new Date(this.value);
        const tahunMasuk = tanggalMasuk.getFullYear();
        const tahunSekarang = new Date().getFullYear();

        if (!isNaN(tahunMasuk)) {
            const masaKerja = tahunSekarang - tahunMasuk;
            document.getElementById('masa_kerja').value = masaKerja;
        } else {
            document.getElementById('masa_kerja').value = '';
        }
    });

    // Validasi form sebelum submit
    document.getElementById('pegawaiForm').addEventListener('submit', function(e) {
        const inputs = this.querySelectorAll('input[required], select[required], textarea[required]');
        let kosong = false;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                kosong = true;
            }
        });

        if (kosong) {
            e.preventDefault();
            alert('Data masih kosong. Silakan lengkapi semua kolom yang wajib diisi.');
        }
    });
</script>
@endsection
