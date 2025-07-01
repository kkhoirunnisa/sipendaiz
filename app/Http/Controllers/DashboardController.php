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

        // total pemasukan infak pembangunan
        $totalInfakPembangunan = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Pembangunan'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pemasukan Infak Pembangunan Hari Ini
        $pemasukanInfakPembangunanHariIni = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Pembangunan')
                ->whereDate('tanggal_infak', $today))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0); // i hasil get

        // Pemasukan Infak Pembangunan 7 Hari Terakhir
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay(); // mengurangi 6 hari kebelakang -> mengatur awal hari jd jam 00
        $pemasukanInfakPembangunan7Hari = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($q) use ($sevenDaysAgo) {
                $q->where('kategori', 'Pembangunan')
                    ->whereDate('tanggal_infak', '>=', $sevenDaysAgo); // tgl min 7 hari kebelakang
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

        // total pemasukan infak takmir
        $totalInfakTakmir = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', fn($q) => $q->where('kategori', 'Takmir'))
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pemasukan Infak Takmir Bulan Ini
        $pemasukanInfakTakmirBulanIni = InfakMasukModel::with('buktiTransaksi')
            ->whereHas(
                'buktiTransaksi',
                fn($q) => $q->where('kategori', 'Takmir')
                    ->whereBetween('tanggal_infak', [$currentMonth, $endOfMonth])
            )
            ->get()
            ->sum(fn($i) => $i->buktiTransaksi->nominal ?? 0);

        // Pengeluaran Infak Takmir Bulan Ini
        $pengeluaranInfakTakmirBulanIni = InfakKeluarModel::where('kategori', 'Takmir')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        //  pengeluaran infak takmir
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

        // jumlah total konfirmasi bukti transaksi pemasukan
        $totalPendingTransaksi = BuktiTransaksiModel::where('status', 'Pending')->count();

        // ZAKAT UANG
        // total pemasukan zakat uang fitrah
        $totalZakatUangFitrah = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        // total pemasukan zakat uang maal
        $totalZakatUangMaal = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        // pemasukan zakat uang fitrah bulan ini
        $pemasukanZakatUangFitrahBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        // pemasukan zakat uang maal bulan ini
        $pemasukanZakatUangMaalBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        //pengeluaran zakat uang fitrah bulan ini
        $pengeluaranZakatUangFitrahBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        //pengeluaran zakat uang maal bulan ini
        $pengeluaranZakatUangMaalBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('nominal');

        //  pengeluaran zakat uang fitrah
        $totalPengeluaranZakatUangFitrah = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('nominal');

        //  pengeluaran zakat uang maal
        $totalPengeluaranZakatUangMaal = ZakatKeluarModel::where('bentuk_zakat', 'Uang')
            ->where('jenis_zakat', 'Maal')
            ->sum('nominal');

        // ZAKAT BERAS
        // total pemasukan zakat beras fitrah
        $totalZakatBerasFitrah = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('jumlah');

        // total pemasukan zakat beras maal
        $totalZakatBerasMaal = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->sum('jumlah');

        //  pemasukan beras fitrah bulan ini
        $pemasukanZakatBerasFitrahBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        //  pemasukan beras maal bulan ini
        $pemasukanZakatBerasMaalBulanIni = ZakatMasukModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        //  pengeluaran beras fitrah bulan ini
        $pengeluaranZakatBerasFitrahBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        //  pengeluaran beras maal bulan ini
        $pengeluaranZakatBerasMaalBulanIni = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->whereBetween('tanggal', [$currentMonth, $endOfMonth])
            ->sum('jumlah');

        //  total pengeluaran beras fitrah bulan ini
        $totalPengeluaranZakatBerasFitrah = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Fitrah')
            ->sum('jumlah');

        // total pengeluaran beras maal bulan ini
        $totalPengeluaranZakatBerasMaal = ZakatKeluarModel::where('bentuk_zakat', 'Beras')
            ->where('jenis_zakat', 'Maal')
            ->sum('jumlah');

        // sisa zakat fitrah maal
        $sisaZakatUangFitrah = $totalZakatUangFitrah - $totalPengeluaranZakatUangFitrah;
        $sisaZakatUangMaal = $totalZakatUangMaal - $totalPengeluaranZakatUangMaal;
        $sisaZakatBerasFitrah = $totalZakatBerasFitrah - $totalPengeluaranZakatBerasFitrah;
        $sisaZakatBerasMaal =  $totalZakatBerasMaal - $totalPengeluaranZakatBerasMaal;

        return view('dashboard.index_dashboard', compact(
            // infak pembangunan
            'totalInfakPembangunan',
            'pemasukanInfakPembangunanHariIni',
            'pemasukanInfakPembangunan7Hari',
            'pengeluaranInfakPembangunanHariIni',
            'pengeluaranInfakPembangunan7Hari',
            'totalPengeluaranInfakPembangunan',
            'saldoInfakPembangunan',

            // infak takmir
            'totalInfakTakmir',
            'pemasukanInfakTakmirBulanIni',
            'pengeluaranInfakTakmirBulanIni',
            'totalPengeluaranInfakTakmir',
            'saldoInfakTakmir',

            // konfirmasi transaksi
            'transaksiPending',
            'totalPendingTransaksi',

            // zakat uang
            'totalZakatUangFitrah',
            'totalZakatUangMaal',
            'pemasukanZakatUangFitrahBulanIni',
            'pemasukanZakatUangMaalBulanIni',
            'pengeluaranZakatUangFitrahBulanIni',
            'pengeluaranZakatUangMaalBulanIni',
            'totalPengeluaranZakatUangFitrah',
            'totalPengeluaranZakatUangMaal',

            // zakat beras
            'totalZakatBerasFitrah',
            'totalZakatBerasMaal',
            'pemasukanZakatBerasFitrahBulanIni',
            'pemasukanZakatBerasMaalBulanIni',
            'pengeluaranZakatBerasFitrahBulanIni',
            'pengeluaranZakatBerasMaalBulanIni',
            'totalPengeluaranZakatBerasFitrah',
            'totalPengeluaranZakatBerasMaal',

            // sisa zakat
            'sisaZakatUangFitrah',
            'sisaZakatUangMaal',
            'sisaZakatBerasFitrah',
            'sisaZakatBerasMaal'
        ));
    }
}
