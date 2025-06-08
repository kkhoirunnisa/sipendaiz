<?php

namespace App\Http\Controllers;

use App\Models\BuktiTransaksiModel;
use App\Models\InfakMasukModel;
use App\Models\InfakKeluarModel;
use App\Models\MustahikModel;
use App\Models\ZakatKeluarModel;
use App\Models\ZakatMasukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        // Data Infak Pembangunan Masjid (Infak Masuk)
        $totalInfakPembangunan = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Pembangunan'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        //jumlah transaksi
        $jumlahTransaksiPembangunan = InfakMasukModel::whereHas('buktiTransaksi', function ($query) {
            $query->where('kategori', 'Pembangunan')
                ->where('status', 'Terverifikasi');
        })->count();

        // Donatur Terkini (5 terakhir)
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

        //infak keluar pembangunan
        $totalPengeluaranInfakPembangunan = InfakKeluarModel::where('kategori', 'pembangunan')->sum('nominal');

        // Total saldo infak pembangunan = pemasukan - pengeluaran
        $saldoInfakPembangunan = $totalInfakPembangunan - $totalPengeluaranInfakPembangunan;



        //infak takmir
        $totalInfakTakmir = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Takmir'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // pendapatan takmir Terkini (5 terakhir)
        $pendapatantakmirterkini = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($query) {
                $query->where('status', 'Terverifikasi')
                    ->where('kategori', 'Takmir');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Total pengeluaran infak takmir
        $totalPengeluaranInfakTakmir = InfakKeluarModel::where('kategori', 'takmir')->sum('nominal');

        // Total saldo infak takmir = pemasukan - pengeluaran
        $saldoInfakTakmir = $totalInfakTakmir - $totalPengeluaranInfakTakmir;

        //jumlah transaksi infak keseluruhan
        $jumlahTransaksiInfak = InfakMasukModel::whereHas('buktiTransaksi', function ($query) {
            $query->where('status', 'Terverifikasi');
        })->count();

        // total infak
        $totalInfak = $totalInfakPembangunan + $totalInfakTakmir;




        // total zakat fitrah
        $totalZakatFitrah = ZakatMasukModel::where('jenis_zakat', 'Fitrah')->sum('nominal');

        // total zakat maal
        $totalZakatMaal = ZakatMasukModel::where('jenis_zakat', 'Maal')->sum('nominal');


        $totalZakatUangFitrah = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        $totalZakatUangMaal = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        // total zakat
        $totaZakat = $totalZakatFitrah + $totalZakatMaal;

        $totalZakatBerasFitrah = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('jumlah');

        $totalZakatBerasMaal = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->sum('jumlah');

        // Total Zakat Beras
        $totalZakatBeras = ZakatMasukModel::where('bentuk_zakat', 'Beras')->sum('jumlah');

        // Zakat terkini (5 data terbaru)
        $zakatTerkini = ZakatMasukModel::orderBy('tanggal', 'desc')->limit(5)->get();

        // total transaksi zakat
        $totalTransaksiZakatMasuk = ZakatMasukModel::count();

        // Total Pengeluaran Zakat - Uang
        // $totalPengeluaranZakatUang = ZakatKeluarModel::where('bentuk_zakat', 'Uang')->sum('nominal');

        // Total Pengeluaran Zakat - Beras
        // $totalPengeluaranZakatBeras = ZakatKeluarModel::where('bentuk_zakat', 'Beras')->sum('jumlah');

        // total mustahik
        $totalMustahik = MustahikModel::distinct('nama')->count('nama');


        //daftar mustahik
        $listMustahik = MustahikModel::orderBy('created_at', 'desc')
            ->limit(5)
            ->pluck('nama');

        $totalPengeluaranZakatUangFitrah = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        $totalPengeluaranZakatUangMaal = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        $totalPengeluaranZakatBerasFitrah = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('jumlah');

        $totalPengeluaranZakatBerasMaal = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->sum('jumlah');

        // ==== Sisa Zakat per jenis ====
        $sisaZakatUangFitrah = $totalZakatUangFitrah - $totalPengeluaranZakatUangFitrah;
        $sisaZakatUangMaal = $totalZakatUangMaal - $totalPengeluaranZakatUangMaal;
        $sisaZakatBerasFitrah = $totalZakatBerasFitrah - $totalPengeluaranZakatBerasFitrah;
        $sisaZakatBerasMaal =  $totalZakatBerasMaal - $totalPengeluaranZakatBerasMaal;
        // Data untuk chart (opsional - bisa digunakan untuk grafik)
        // $chartData = InfakMasukModel::with('buktiTransaksi')
        //     ->whereHas('buktiTransaksi', function ($query) {
        //         $query->where('status', 'Terverifikasi');
        //     })
        //     ->selectRaw('DATE(created_at) as tanggal, SUM((SELECT nominal FROM bukti_transaksi WHERE bukti_transaksi.id = infak_masuk.id_bukti_transaksi)) as total')
        //     ->groupBy('tanggal')
        //     ->orderBy('tanggal', 'desc')
        //     ->limit(7)
        //     ->get();

        return view('landing_page.index_landing_page', compact(
            'totalInfakPembangunan',
            'jumlahTransaksiPembangunan',
            'donaturTerkini',
            'totalPengeluaranInfakPembangunan',
            'saldoInfakPembangunan',

            'totalInfakTakmir',
            'pendapatantakmirterkini',
            'totalPengeluaranInfakTakmir',
            'saldoInfakTakmir',

            'jumlahDonatur',
            'jumlahTransaksiInfak',
            'totalInfak',

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
            'listMustahik',
            // 'totalPengeluaranZakatUang',
            // 'totalPengeluaranZakatBeras',
            'totaZakat',
            'sisaZakatUangFitrah',
            'sisaZakatUangMaal',
            'sisaZakatBerasFitrah',
            'sisaZakatBerasMaal'
        ));
    }

    // Method untuk mendapatkan data real-time via AJAX
    // public function getRealtimeData()
    // {
    //     $totalInfakMasuk = InfakMasukModel::with('buktiTransaksi')
    //         ->whereHas('buktiTransaksi', function($query) {
    //             $query->where('status', 'Terverifikasi');
    //         })
    //         ->sum(DB::raw('(SELECT nominal FROM bukti_transaksi WHERE bukti_transaksi.id = infak_masuk.id_bukti_transaksi)'));

    //     $jumlahTransaksi = InfakMasukModel::with('buktiTransaksi')
    //         ->whereHas('buktiTransaksi', function($query) {
    //             $query->where('status', 'Terverifikasi');
    //         })
    //         ->count();

    //     $totalInfakKeluar = InfakKeluarModel::sum('nominal');

    //     return response()->json([
    //         'total_infak_masuk' => $totalInfakMasuk,
    //         'jumlah_transaksi' => $jumlahTransaksi,
    //         'total_infak_keluar' => $totalInfakKeluar,
    //         'saldo' => $totalInfakMasuk - $totalInfakKeluar
    //     ]);
    // }
}
