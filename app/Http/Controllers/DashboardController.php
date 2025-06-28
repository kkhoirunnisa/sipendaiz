<?php

namespace App\Http\Controllers;

use App\Models\BuktiTransaksiModel;
use App\Models\InfakMasukModel;
use App\Models\InfakKeluarModel;
use App\Models\ZakatMasukModel;
use App\Models\ZakatKeluarModel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // menampilkan data pada dashboard
    public function index()
    {
        // mendapatkan tgl hari ini YYYY-MM-DD
        $today = Carbon::today()->toDateString();

        // mendapatkan tgl awal bulan saat ini
        $currentMonth = Carbon::now()->startOfMonth()->toDateString();

        // mendapatkan tgl akhir bulan saat ini
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // total infak pembangunan
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
            ->limit(5)
            ->get();

        $totalPendingTransaksi = BuktiTransaksiModel::where('status', 'Pending')->count();

        // ==== Zakat Uang Fitrah & Maal ====
        $totalZakatUangFitrah = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        $totalZakatUangMaal = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        $pemasukanZakatUangFitrahBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        $pemasukanZakatUangMaalBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        $pengeluaranZakatUangFitrahBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        $pengeluaranZakatUangMaalBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        $totalPengeluaranZakatUangFitrah = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        $totalPengeluaranZakatUangMaal = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        // ==== Zakat Beras Fitrah & Maal ====
        $totalZakatBerasFitrah = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('jumlah');

        $totalZakatBerasMaal = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->sum('jumlah');

        $pemasukanZakatBerasFitrahBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        $pemasukanZakatBerasMaalBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        $pengeluaranZakatBerasFitrahBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        $pengeluaranZakatBerasMaalBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

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

        return view('dashboard.index_dashboard', compact(
            'totalInfakPembangunan',
            'pemasukanInfakPembangunanHariIni',
            'pemasukanInfakPembangunan7Hari',
            'pengeluaranInfakPembangunanHariIni',
            'pengeluaranInfakPembangunan7Hari',
            'totalPengeluaranInfakPembangunan',
            'saldoInfakPembangunan',

            'totalInfakTakmir',
            'pemasukanInfakTakmirBulanIni',
            'pengeluaranInfakTakmirBulanIni',
            'totalPengeluaranInfakTakmir',
            'saldoInfakTakmir',

            'transaksiPending',
            'totalPendingTransaksi',

            'totalZakatUangFitrah',
            'totalZakatUangMaal',
            'pemasukanZakatUangFitrahBulanIni',
            'pemasukanZakatUangMaalBulanIni',
            'pengeluaranZakatUangFitrahBulanIni',
            'pengeluaranZakatUangMaalBulanIni',
            'totalPengeluaranZakatUangFitrah',
            'totalPengeluaranZakatUangMaal',

            'totalZakatBerasFitrah',
            'totalZakatBerasMaal',
            'pemasukanZakatBerasFitrahBulanIni',
            'pemasukanZakatBerasMaalBulanIni',
            'pengeluaranZakatBerasFitrahBulanIni',
            'pengeluaranZakatBerasMaalBulanIni',
            'totalPengeluaranZakatBerasFitrah',
            'totalPengeluaranZakatBerasMaal',

            'sisaZakatUangFitrah',
            'sisaZakatUangMaal',
            'sisaZakatBerasFitrah',
            'sisaZakatBerasMaal'
        ));
    }
}
