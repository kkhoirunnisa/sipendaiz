<?php

// Tambahkan di app/helpers.php atau buat file helper baru

if (!function_exists('terbilang')) {
    function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim(terbilang(abs($nilai)));
        } elseif ($nilai < 12) {
            $hasil = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"][$nilai];
        } elseif ($nilai < 20) {
            $hasil = terbilang($nilai - 10) . " belas";
        } elseif ($nilai < 100) {
            $hasil = terbilang(intval($nilai / 10)) . " puluh " . terbilang($nilai % 10);
        } elseif ($nilai < 200) {
            $hasil = "seratus " . terbilang($nilai - 100);
        } elseif ($nilai < 1000) {
            $hasil = terbilang(intval($nilai / 100)) . " ratus " . terbilang($nilai % 100);
        } elseif ($nilai < 2000) {
            $hasil = "seribu " . terbilang($nilai - 1000);
        } elseif ($nilai < 1000000) {
            $hasil = terbilang(intval($nilai / 1000)) . " ribu " . terbilang($nilai % 1000);
        } elseif ($nilai < 1000000000) {
            $hasil = terbilang(intval($nilai / 1000000)) . " juta " . terbilang($nilai % 1000000);
        } elseif ($nilai < 1000000000000) {
            $hasil = terbilang(intval($nilai / 1000000000)) . " milyar " . terbilang($nilai % 1000000000);
        } elseif ($nilai < 1000000000000000) {
            $hasil = terbilang(intval($nilai / 1000000000000)) . " trilyun " . terbilang($nilai % 1000000000000);
        } else {
            $hasil = "Angka terlalu besar";
        }

        return trim($hasil);
    }
}

// Jangan lupa untuk meload helper ini di composer.json:
/*
"autoload": {
    "files": [
        "app/helpers.php"
    ]
}
*/