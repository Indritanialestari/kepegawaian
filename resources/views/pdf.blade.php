<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Data Pegawai</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Golongan</th>
                <th>Gaji</th>
                <th>Tanggal Masuk</th>
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
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
