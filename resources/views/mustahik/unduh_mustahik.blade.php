<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Mustahik</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Daftar Mustahik</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mustahik as $i => $m)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $m->nama }}</td>
                <td>{{ $m->kategori }}</td>
                <td>{{ $m->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

