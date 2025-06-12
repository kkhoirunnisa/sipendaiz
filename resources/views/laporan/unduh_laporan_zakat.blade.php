<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Zakat Masjid</title>
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

        <!-- HEADER TANPA TABLE -->
        <div class="header">
            <div class="header-text">
                <h1 class="judul-header">LAPORAN ZAKAT MASJID JAMI' AL MUNAWWARAH</h1>
                <div class="subjudul-header">
                    Sekretariat: Jl. Raya Maoslor Kec. Maos, Kab. Cilacap<br>
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

        <!-- TABEL DATA -->
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Jenis Zakat</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Bentuk</th>
                    <th class="text-center">Transaksi</th>
                    <th class="text-center">Uang</th>
                    <th class="text-center">Saldo Uang</th>
                    <th class="text-center">Beras</th>
                    <th class="text-center">Saldo Beras</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $trx)
                <tr>
                    <td class="text-center">{{ $trx['no'] }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($trx['tanggal'])->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $trx['jenis_zakat'] }}</td>
                    <td class="text-center">{!! nl2br(e($trx['keterangan'])) !!}</td>
                    <td class="text-center">{{ $trx['bentuk_zakat'] }}</td>
                    <td class="text-center">{{ $trx['jenis_transaksi'] }}</td>
                    <td class="text-right">
                        @if($trx['nominal'] > 0)
                        <span class="fw-semibold text-success">Rp {{ number_format($trx['nominal'], 0, ',', '.') }}</span>
                        @elseif($trx['nominal'] < 0)
                            <span class="fw-semibold text-danger">Rp -{{ number_format(abs($trx['nominal']), 0, ',', '.') }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($trx['saldo'], 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($trx['jumlah_kg'] > 0)
                        <span class="fw-semibold text-success">{{ rtrim(rtrim(number_format($trx['jumlah_kg'], 2, ',', '.'), '0'), ',') }} Kg</span>
                        @elseif($trx['jumlah_kg'] < 0)
                            <span class="fw-semibold text-danger">-{{ rtrim(rtrim(number_format(abs($trx['jumlah_kg']), 2, ',', '.'), '0'), ',') }} Kg</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                    </td>
                    <td class="text-right">
                        {{ $trx['saldo_beras'] == 0 ? '0 kg' : rtrim(rtrim(number_format($trx['saldo_beras'], 2, ',', '.'), '0'), ',') . ' kg' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-right">TOTAL</td>
                    <td class="text-right">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</td>
                    <td class="text-right">
                    </td>
                    <td class="text-right">
                        {{ $trx['saldo_beras'] == 0 ? '0 kg' : rtrim(rtrim(number_format($trx['saldo_beras'], 2, ',', '.'), '0'), ',') . ' kg' }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <!-- REKAP PEMASUKAN & PENGELUARAN -->
        <table style="width: 100%; margin-top: 25px; font-size: 12px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="2" style="background-color: #e0f1e2; text-align: left; padding: 6px; font-weight: bold;">REKAPITULASI ZAKAT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 6px;">Pemasukan Zakat Uang</td>
                    <td style="text-align: right; padding: 6px;">Rp {{ number_format($pemasukanUang, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px;">Pengeluaran Zakat Uang</td>
                    <td style="text-align: right; padding: 6px;">Rp {{ number_format($pengeluaranUang, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px;">Pemasukan Zakat Beras</td>
                    <td style="text-align: right; padding: 6px;">
                        {{ $pemasukanBeras == 0 ? '0 kg' : rtrim(rtrim(number_format($pemasukanBeras, 2, ',', '.'), '0'), ',') . ' kg' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px;">Pengeluaran Zakat Beras</td>
                    <td style="text-align: right; padding: 6px;">
                        {{ $pengeluaranBeras == 0 ? '0 kg' : rtrim(rtrim(number_format($pengeluaranBeras, 2, ',', '.'), '0'), ',') . ' kg' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; margin-top: 40px; font-size: 12px; color: green; border: none; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; border: none; text-align: left;">
                    Ketua<br><br><br><br>
                    <!-- <img src="{{ public_path('gambar/ttd_ketua.png') }}" alt="Ttd Ketua" style="width: 150px; border: none;"> -->
                    <div style=" border-top: 1px ; width: 200px; padding-top: 5px; font-style: italic; font-size: 12px;">
                        (Mukhasan, S.Ag.)
                    </div>
                </td>
                <td style="width: 50%; border: none; text-align: right;">
                    Bendahara<br><br><br><br>
                    <!-- <img src="{{ public_path('gambar/ttd_bendahara.png') }}" alt="Ttd Bendahara" style="width: 65px; border: none;"> -->
                    <div style=" border-top: 1px ; width: 200px; padding-top: 5px; font-style: italic; font-size: 12px; margin-left: auto;">
                        (Suparno)
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>