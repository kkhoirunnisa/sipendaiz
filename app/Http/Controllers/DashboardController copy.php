<?php

namespace App\Http\Controllers;

use App\Models\BuktiTransaksiModel;
use App\Models\InfakMasukModel;
use App\Models\InfakKeluarModel;
use App\Models\ZakatMasukModel;
use App\Models\ZakatKeluarModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's date
        $today = Carbon::today()->toDateString();
        $currentMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Total Infak Pembangunan
        $totalInfakPembangunan = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Pembangunan'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pemasukan Infak Pembangunan Hari Ini
        $pemasukanInfakPembangunanHariIni = InfakMasukModel::with('buktiTransaksi')
            ->whereHas(
                'buktiTransaksi',
                fn($q) =>
                $q->where('kategori', 'Pembangunan')->whereDate('tanggal_infak', $today)
            )
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pemasukan Infak Pembangunan 7 Hari Terakhir
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay(); // termasuk hari ini
        $pemasukanInfakPembangunan7Hari = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($q) use ($sevenDaysAgo) {
                $q->where('kategori', 'Pembangunan')
                    ->whereDate('tanggal_infak', '>=', $sevenDaysAgo);
            })
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pengeluaran Infak Pembangunan Hari Ini
        $pengeluaranInfakPembangunanHariIni = InfakKeluarModel::where('kategori', 'Pembangunan')
            ->whereDate('tanggal', $today)
            ->sum('nominal');

        //Total pengeluran infak pembangunan
        $totalPengeluaranInfakPembangunan = InfakKeluarModel::where('kategori', 'pembangunan')->sum('nominal');

        // Pengeluaran Infak Pembangunan 7 Hari Terakhir
        $pengeluaranInfakPembangunan7Hari = InfakKeluarModel::where('kategori', 'Pembangunan')
            ->whereDate('tanggal', '>=', $sevenDaysAgo)
            ->sum('nominal');

        // Total Infak Takmir
        $totalInfakTakmir = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Takmir'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pemasukan Infak Takmir Bulan Ini
        $pemasukanInfakTakmirBulanIni = InfakMasukModel::with('buktiTransaksi')
            ->whereHas(
                'buktiTransaksi',
                fn($q) =>
                $q->where('kategori', 'Takmir')->whereBetween('tanggal_infak', [$currentMonth, $endOfMonth])
            )
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pengeluaran Infak Takmir Bulan Ini
        $pengeluaranInfakTakmirBulanIni = InfakKeluarModel::where('kategori', 'Takmir')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        // Total pengeluaran infak takmir
        $totalPengeluaranInfakTakmir = InfakKeluarModel::where('kategori', 'takmir')->sum('nominal');

        // Total saldo infak pembangunan = pemasukan - pengeluaran
        $saldoInfakPembangunan = $totalInfakPembangunan - $totalPengeluaranInfakPembangunan;

        // Total saldo infak takmir = pemasukan - pengeluaran
        $saldoInfakTakmir = $totalInfakTakmir - $totalPengeluaranInfakTakmir;

        // Transaksi Pending yang memerlukan konfirmasi
        $transaksiPending = BuktiTransaksiModel::where('status', 'Pending')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $totalPendingTransaksi = BuktiTransaksiModel::where('status', 'Pending')->count();

        // Total Zakat Uang
        $totalZakatUang = ZakatMasukModel::where('bentuk_zakat', 'Uang')->sum('nominal');

        // Pemasukan Zakat Uang Bulan Ini
        $pemasukanZakatUangBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        // Pengeluaran Zakat Uang Bulan Ini
        $pengeluaranZakatUangBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        // Total Pengeluaran Zakat - Uang
        $totalPengeluaranZakatUang = ZakatKeluarModel::where('bentuk_zakat', 'Uang')->sum('nominal');

        // Total Zakat Beras
        $totalZakatBeras = ZakatMasukModel::where('bentuk_zakat', 'Beras')->sum('jumlah');

        // Pemasukan Zakat Beras Bulan Ini
        $pemasukanZakatBerasBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        // Pengeluaran Zakat Beras Bulan Ini
        $pengeluaranZakatBerasBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        // Total Pengeluaran Zakat - Beras
        $totalPengeluaranZakatBeras = ZakatKeluarModel::where('bentuk_zakat', 'Beras')->sum('jumlah');

        // Sisa Zakat
        $sisaZakatUang = $totalZakatUang - $totalPengeluaranZakatUang;
        $sisaZakatBeras = $totalZakatBeras - $totalPengeluaranZakatBeras;

        return view('dashboard.index_dashboard', compact(
            'totalInfakPembangunan',
            'pemasukanInfakPembangunanHariIni',
            'pemasukanInfakPembangunan7Hari',
            'pengeluaranInfakPembangunanHariIni',
            'pengeluaranInfakPembangunan7Hari',
            'totalInfakTakmir',
            'pemasukanInfakTakmirBulanIni',
            'pengeluaranInfakTakmirBulanIni',
            'totalPengeluaranInfakPembangunan',
            'totalPengeluaranInfakTakmir',
            'saldoInfakPembangunan',
            'saldoInfakTakmir',
            'transaksiPending',
            'totalPendingTransaksi',
            'totalZakatUang',
            'pemasukanZakatUangBulanIni',
            'pengeluaranZakatUangBulanIni',
            'totalPengeluaranZakatUang',
            'totalZakatBeras',
            'pemasukanZakatBerasBulanIni',
            'pengeluaranZakatBerasBulanIni',
            'totalPengeluaranZakatBeras',
            'sisaZakatUang',
            'sisaZakatBeras'
        ));
    }
}
