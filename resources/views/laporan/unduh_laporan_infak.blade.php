<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Infak {{ ucfirst($kategori) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: -20px -30px;
        }

        .laporan-container {
            border: 3px solid green;
            padding: 20px 25px;
            background-color: white;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .judul-header {
            color: green;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .subjudul-header {
            font-size: 13px;
            color: green;
        }

        h2,
        h4 {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px 6px;
            white-space: normal;
        }

        th {
            background-color: #e0f1e2;
            color: #000;
        }

        tfoot td {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="laporan-container">

        <!-- HEADER -->
        <div class="header">
            <div class="header-text">
                <h1 class="judul-header">LAPORAN INFAK {{ strtoupper($kategori) }} MASJID JAMI' AL MUNAWWARAH</h1>
                <div class="subjudul-header">

                    Jl. Raya Maoslor Kec. Maos, Kab. Cilacap<br>
                    <span style="background-color: #e0f1e2; padding: 2px 6px; border-radius: 4px;">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- TANGGAL CETAK -->
        <div style="text-align: right; font-size: 11px; margin-top: 10px;">
            Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y H:i') }} WIB
        </div>

        <!-- TABEL TRANSAKSI -->
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tanggal Infak</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Barang</th>
                    <th class="text-center">Transaksi</th>
                    <th class="text-center">Masuk</th>
                    <th class="text-center">Keluar</th>
                    <th class="text-center">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $trx)
                <tr>
                    <td class="text-center">{{ $trx['no'] }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($trx['tanggal'])->format('d-m-Y') }}</td>
                    <td class="text-center">
                        @if ($trx['jenis_transaksi'] === 'Pemasukan')
                        @php
                        $donatur = $trx['donatur'] ?? '-';
                        $alamat = $trx['alamat'] ?? '-';
                        @endphp
                        Infak atas nama {{ $donatur }}@if (!empty($alamat) && $alamat !== '-') - {{ $alamat }}@endif
                        @else
                        Infak untuk {{ $trx['jenis_barang'] ?? '-' }} - {{ $trx['keterangan'] ?? '-' }}
                        @endif
                    </td>
                    <td class="text-center">{{ $trx['jenis_barang'] }}</td>
                    <td class="text-center">{{ $trx['jenis_transaksi'] }}</td>
                    <td class="text-right">Rp {{ number_format($trx['masuk'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($trx['keluar'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($trx['saldo'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="5" class="text-right fw-bold">TOTAL</td>
                    <td class="text-right fw-bold text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                    <td class="text-right fw-bold text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                    <td class="text-right fw-bold">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <table style="width: 100%; margin-top: 40px; font-size: 12px; color: green; border: none; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; border: none; text-align: left;">
                    Ketua<br><br>
                    <img src="{{ public_path('gambar/ttd_ketua.png') }}" alt="Ttd Ketua" style="width: 150px; border: none;">
                    <div style=" border-top: 1px ; width: 200px; padding-top: 5px; font-style: italic; font-size: 12px;">
                        (Agus Tisngadi, SE., M.Si.)
                    </div>
                </td>
                <td style="width: 50%; border: none; text-align: right;">
                    Bendahara<br><br>
                    <img src="{{ public_path('gambar/ttd_bendahara.png') }}" alt="Ttd Bendahara" style="width: 65px; border: none;">
                    <div style=" border-top: 1px ; width: 200px; padding-top: 5px; font-style: italic; font-size: 12px; margin-left: auto;">
                        (Benny Hermanto)
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>