<!DOCTYPE html>
<html>
<head>
    <title>Data Pegawai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }
        /* Style untuk tombol print/download */
        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .download-button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .download-button:hover {
            background-color: #45a049;
        }
        /* Hapus bagian @media print, karena kita akan menggunakan kondisional Blade */
    </style>
</head>
<body>
    {{-- Kondisional Blade: Hanya tampilkan tombol jika sedang di mode preview --}}
    @if(isset($is_preview) && $is_preview)
    <div class="button-container">
        <a href="{{ route('pegawai.exportPdf', request()->query()) }}" class="download-button">Download PDF</a>
    </div>
    @endif

    <h1>Data Pegawai</h1>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Nomor Induk</th>
                <th>Tgl Lahir</th>
                <th>Gender</th>
                <th>Jabatan</th>
                <th>Bagian</th>
                <th>Unit Kerja</th>
                <th>Pendidikan</th>
                <th>Klasifikasi</th>
                <th>Sts Keluarga</th>
                <th>Jml Anak</th>
                <th>Tgl Masuk</th>
                <th>Masa Kerja</th>
                <th>Golongan</th>
                <th>Gaji</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pegawais as $pegawai)
            <tr>
                <td>{{ $pegawai->nama }}</td>
                <td>{{ $pegawai->nomor_induk }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d') }}</td>
                <td class="text-center">{{ ucfirst($pegawai->gender) }}</td>
                <td>{{ $pegawai->jabatan }}</td>
                <td>{{ $pegawai->bagian }}</td>
                <td>{{ $pegawai->unit_kerja }}</td>
                <td>{{ $pegawai->pendidikan }}</td>
                <td>{{ $pegawai->klasifikasi }}</td>
                <td class="text-center">{{ $pegawai->keluarga_status }}</td>
                <td class="text-center">{{ $pegawai->keluarga_anak }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('Y-m-d') }}</td>
                <td class="text-center">{{ $pegawai->masa_kerja }} tahun</td>
                <td class="text-center">{{ $pegawai->golongan }}</td>
                <td class="text-right">Rp {{ number_format($pegawai->gaji, 0, ',', '.') }}</td>
                <td class="text-center">{{ $pegawai->status }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center">Tidak ada data pegawai yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>