<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kuitansi Masjid</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: -25px;
            padding: 0px;
        }

        .kuitansi {
            width: 1010px;
            margin: 0 auto;
            padding: 20px 25px;
            border: 4px solid green;
            background-color: white;
        }

        .kuitansi-header {
            border-bottom: 4px solid green;
            padding-bottom: 10px;
        }

        .judul {
            color: green;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 15px 0;
        }

        .form-row {
            margin-bottom: 8px;
            font-size: 17px;
        }

        .form-row span.label {
            display: inline-block;
            width: 200px;
            font-weight: bold;
            color: green;
        }

        .form-row span.value {
            border-bottom: 1px dotted #000;
            width: 780px;
            display: inline-block;
            padding: 2px 5px;
        }

        .form-row .green-box {
            background-color: #cde3cd;
            display: inline-block;
            padding: 2px 6px;
        }

        .tanggal {
            text-align: right;
            margin-top: 30px;
            font-size: 17px;
        }
    </style>
</head>

<body>

    <div class="kuitansi">
        <div class="kuitansi-header">
            <table style="width: 100%;">
                <tr>
                    <!-- Kolom Logo -->
                    <td style="width: 15%; vertical-align: top;">
                        <img src="{{ public_path('gambar/logo.png') }}" alt="Logo" class="logo">
                    </td>

                    <!-- Kolom Teks Header -->
                    <td style="width: 85%; text-align: center;">
                        <h1 style="margin: 5px 0; color: green; font-size: 25px;">
                            @if ($infak->kategori == 'infak_pembangunan')
                            PANITIA RENOVASI MASJID JAMI' AL MUNAWWAROH
                            @else
                            PANITIA TAKMIR MASJID JAMI' AL MUNAWWAROH
                            @endif
                        </h1>
                        <h2 style="margin: 5px 0; color: green; font-size: 19px;">
                            Sekretariat: Jl. Raya Maoslor Kec. Maos, Kab. Cilacap<br>
                            HP: 0857 4287 5424 - 0856 9377 2077
                        </h2>
                        <h2 style="margin: 5px 0; color: green; font-size: 19px;">
                            Rekening: BRI: 311501040846537 |
                            Mandiri: 18000010171173 |
                            Bank Jateng: 2-012-24515-0
                        </h2>
                    </td>
                </tr>
            </table>
        </div>

        <div class="judul">KUITANSI</div>

        <div class="form-row">
            <span class="label">No.</span>
            <span class="value">: KM-{{ \Carbon\Carbon::parse($infak->tanggal_konfirmasi)->format('Y-m') }}-{{ str_pad($infak->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="form-row">
            <span class="label">Telah Diterima Dari</span>
            <span class="value">: {{ $infak->buktiTransaksi->donatur }}</span>
        </div>

        <div class="form-row">
            <span class="label">Alamat</span>
            <span class="value">: {{ $infak->buktiTransaksi->alamat }}</span>
        </div>

        <div class="form-row">
            <span class="label">Uang Sebanyak </span>
            <span class="value green-box">:
                @if(!empty($infak->buktiTransaksi->nominal) && $infak->buktiTransaksi->nominal > 0)
                Rp {{ number_format($infak->buktiTransaksi->nominal, 0, ',', '.') }}
                @else
                -
                @endif
            </span>
        </div>

        @if(!empty($infak->buktiTransaksi->nominal) && $infak->buktiTransaksi->nominal > 0)
        <div class="form-row">
            <span class="label">Terbilang </span>
            <span class="value green-box">: {{ ucwords(terbilang($infak->buktiTransaksi->nominal)) }} Rupiah</span>
        </div>
        @elseif(!empty($infak->buktiTransaksi->barang))
        <div class="form-row">
            <span class="label">Keterangan </span>
            <span class="value green-box">: Barang donasi berupa {{ $infak->buktiTransaksi->barang }}</span>
        </div>
        @endif

        <div class="tanggal" style="color: green;">
            Maos, {{ \Carbon\Carbon::parse($infak->tanggal_konfirmasi)->translatedFormat('d F Y') }}
        </div>

        <table style="width: 100%; margin-top: 40px; font-size: 17px; text-align: center; color: green;">
            <tr>
                <td style="width: 50%;">
                    Ketua<br><br>
                    <img src="{{ public_path('gambar/ttd_ketua.png') }}" alt="Ttd Ketua" style="width: 170px;  ">
                    <div style="margin-top: 30px; border-top: 1px solid black; width: 250px; margin: 0 auto; padding-top: 5px; font-style: italic; font-size: 17px;">
                        (Agus Tisngadi, SE., M.Si.)
                    </div>
                </td>
                <td style="width: 50%;">
                    Bendahara<br><br>
                    <img src="{{ public_path('gambar/ttd_bendahara.png') }}" alt="Ttd Bendahara" style="width: 68px;  ">
                    <div style="margin-top: 30px; border-top: 1px solid black; width: 250px; margin: 0 auto; padding-top: 5px; font-style: italic; font-size: 17px;">
                        (Benny Hermanto)
                    </div>
                </td>
            </tr>
        </table>

    </div>

</body>

</html>