<?php

namespace App\Http\Controllers;

use App\Models\BuktiTransaksiModel;
use App\Models\InfakMasukModel;
use App\Models\InfakKeluarModel;
use App\Models\MustahikModel;
use App\Models\ZakatKeluarModel;
use App\Models\ZakatMasukModel;

class LandingPageController extends Controller
{
    public function index()
    {
        // PEMBANGUNAN
        // pemasukan infak pembangunan
        $totalInfakPembangunan = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Pembangunan'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // jumlah transaksi pembangunan
        $jumlahTransaksiPembangunan = InfakMasukModel::whereHas('buktiTransaksi', function ($query) {
            $query->where('kategori', 'Pembangunan')
                ->where('status', 'Terverifikasi');
        })->count();

        // menampilkan 5 data infak masuk terakhir
        $donaturTerkini = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($query) {
                $query->where('status', 'Terverifikasi')
                    ->where('kategori', 'Pembangunan');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // jumlah donatur
        $jumlahDonatur =  BuktiTransaksiModel::where('status', 'Terverifikasi')
            ->distinct('donatur')
            ->count('donatur');

        // infak keluar pembangunan
        $totalPengeluaranInfakPembangunan = InfakKeluarModel::where('kategori', 'pembangunan')->sum('nominal');

        // Total saldo infak pembangunan = pemasukan - pengeluaran
        $saldoInfakPembangunan = $totalInfakPembangunan - $totalPengeluaranInfakPembangunan;

        // TAKMIR
        // pemasukan infak takmir
        $totalInfakTakmir = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Takmir'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // pemasukan takmir Terkini (5 terakhir)
        $pendapatantakmirterkini = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($query) {
                $query->where('status', 'Terverifikasi')
                    ->where('kategori', 'Takmir');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // pengeluaran infak takmir
        $totalPengeluaranInfakTakmir = InfakKeluarModel::where('kategori', 'takmir')->sum('nominal');

        // Total saldo infak takmir = pemasukan - pengeluaran
        $saldoInfakTakmir = $totalInfakTakmir - $totalPengeluaranInfakTakmir;

        // jumlah transaksi infak keseluruhan (pembangunan & takmir)
        $jumlahTransaksiInfak = InfakMasukModel::whereHas('buktiTransaksi', function ($query) {
            $query->where('status', 'Terverifikasi');
        })->count();

        // total infak keseluruhan
        $totalInfak = $totalInfakPembangunan + $totalInfakTakmir;

        //ZAKAT
        // pemasukan zakat fitrah
        $totalZakatFitrah = ZakatMasukModel::where('jenis_zakat', 'Fitrah')->sum('nominal');

        // pemasukan zakat maal
        $totalZakatMaal = ZakatMasukModel::where('jenis_zakat', 'Maal')->sum('nominal');

        // pemasukan uang zakat fitrah
        $totalZakatUangFitrah = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        // pemasukan uang zakat maal
        $totalZakatUangMaal = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        // total zakat keseluruhan
        $totaZakat = $totalZakatFitrah + $totalZakatMaal;

        // pemasukan zakat beras fitrah
        $totalZakatBerasFitrah = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('jumlah');

        // pemasukan zakat beras maal
        $totalZakatBerasMaal = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->sum('jumlah');

        // total Zakat Beras keseluruhan
        $totalZakatBeras = ZakatMasukModel::where('bentuk_zakat', 'Beras')->sum('jumlah');

        // zakat terkini (5 data terbaru)
        $zakatTerkini = ZakatMasukModel::orderBy('tanggal', 'desc')->limit(5)->get();

        // total transaksi zakat keseluruhan
        $totalTransaksiZakatMasuk = ZakatMasukModel::count();

        // total mustahik
        $totalMustahik = MustahikModel::distinct('nama')->count('nama');

        // pengeluaran uang zakat fitrah
        $totalPengeluaranZakatUangFitrah = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        // pengeluaran uang zakat maal
        $totalPengeluaranZakatUangMaal = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        // pengeluaran beras zakat fitrah
        $totalPengeluaranZakatBerasFitrah = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('jumlah');

        // pengeluaran beras zakat maal
        $totalPengeluaranZakatBerasMaal = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->sum('jumlah');

        // sisa zakat
        $sisaZakatUangFitrah = $totalZakatUangFitrah - $totalPengeluaranZakatUangFitrah;
        $sisaZakatUangMaal = $totalZakatUangMaal - $totalPengeluaranZakatUangMaal;
        $sisaZakatBerasFitrah = $totalZakatBerasFitrah - $totalPengeluaranZakatBerasFitrah;
        $sisaZakatBerasMaal =  $totalZakatBerasMaal - $totalPengeluaranZakatBerasMaal;

        return view('landing_page.index_landing_page', compact(
            // pembangunan
            'totalInfakPembangunan',
            'jumlahTransaksiPembangunan',
            'donaturTerkini',
            'totalPengeluaranInfakPembangunan',
            'saldoInfakPembangunan',

            // takmir
            'totalInfakTakmir',
            'pendapatantakmirterkini',
            'totalPengeluaranInfakTakmir',
            'saldoInfakTakmir',

            'jumlahDonatur',
            'jumlahTransaksiInfak',
            'totalInfak',

            // zakat
            'totalZakatFitrah',
            'totalZakatMaal',
            'totalZakatBeras',
            'totalZakatBerasFitrah',
            'totalZakatUangFitrah',
            'totalZakatUangMaal',
            'totalZakatBerasMaal',
            'zakatTerkini',
            'totalTransaksiZakatMasuk',
            'totalMustahik',
            'totaZakat',

            // sisa saldo
            'sisaZakatUangFitrah',
            'sisaZakatUangMaal',
            'sisaZakatBerasFitrah',
            'sisaZakatBerasMaal'
        ));
    }
}
