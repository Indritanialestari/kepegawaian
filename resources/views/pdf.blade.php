<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Data Pegawai</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Golongan</th>
                <th>Gaji</th>
                <th>Tanggal Masuk</th>
                <th>Masa Kerja</th>
                <th>Kontak</th>
                <th>Email</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawais as $pegawai)
            <tr>
                <td>{{ $pegawai->nama }}</td>
                <td>{{ ucfirst($pegawai->gender) }}</td>
                <td>{{ $pegawai->status }}</td>
                <td>{{ $pegawai->golongan }}</td>
                <td>Rp {{ number_format($pegawai->gaji, 0, ',', '.') }}</td>
                <td>{{ $pegawai->tanggal_masuk }}</td>
                <td>{{ $pegawai->masa_kerja }} tahun</td>
                <td>{{ $pegawai->kontak }}</td>
                <td>{{ $pegawai->email }}</td>
                <td>{{ $pegawai->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
