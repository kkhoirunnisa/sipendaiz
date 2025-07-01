<?php

namespace App\Http\Controllers;


// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Models\ZakatMasukModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ZakatKeluarModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class LaporanZakatController extends Controller
{
    public function index()
    {
        return view('laporan.laporan_zakat');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $startDate = $request->tanggal_awal;
        $endDate = $request->tanggal_akhir;

        $zakatMasuk = ZakatMasukModel::whereBetween('tanggal', [$startDate, $endDate])->get();
        $zakatKeluar = ZakatKeluarModel::with('mustahik')->whereBetween('tanggal', [$startDate, $endDate])->get();

        $transactions = [];

        foreach ($zakatMasuk as $zm) {
            $transactions[] = [
                'tanggal' => $zm->tanggal,
                'jenis_zakat' => $zm->jenis_zakat,
                'bentuk_zakat' => $zm->bentuk_zakat,
                'jumlah_kg' => $zm->jumlah,
                'jenis_transaksi' => 'Pemasukan',
                'keterangan' => 'Zakat atas nama ' . ($zm->keterangan ?? '(tidak diketahui)'),
                'nominal' => $zm->nominal,
            ];
        }

        foreach ($zakatKeluar as $zk) {
            $transactions[] = [
                'tanggal' => $zk->tanggal,
                'jenis_zakat' => $zk->jenis_zakat,
                'bentuk_zakat' => $zk->bentuk_zakat,
                'jumlah_kg' => -$zk->jumlah,
                'jenis_transaksi' => 'Pengeluaran',
                'keterangan' => 'Dibagikan untuk mustahik ' . ($zk->mustahik->nama ?? '(tidak diketahui)'),
                'nominal' => -$zk->nominal,
            ];
        }

        usort($transactions, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

        $saldo_uang = 0;
        $saldo_beras = 0;
        $totalUang = 0;
        $pemasukanUang = 0;
        $pengeluaranUang = 0;
        $pengeluaranBeras = 0;
        $pemasukanBeras = 0; // Tambahan untuk total pemasukan beras

        foreach ($transactions as $i => &$item) {
            $saldo_uang += $item['nominal'];
            $totalUang += $item['nominal'];

            if (strtolower($item['bentuk_zakat']) === 'beras') {
                $saldo_beras += $item['jumlah_kg'];
                if ($item['jenis_transaksi'] === 'Pengeluaran') {
                    $pengeluaranBeras += abs($item['jumlah_kg']);
                } else if ($item['jenis_transaksi'] === 'Pemasukan') {
                    $pemasukanBeras += $item['jumlah_kg']; // Tambahan pemasukan beras
                }
            }

            if ($item['jenis_transaksi'] === 'Pemasukan') {
                $pemasukanUang += $item['nominal'];
            } elseif ($item['jenis_transaksi'] === 'Pengeluaran') {
                $pengeluaranUang += abs($item['nominal']);
            }

            $item['saldo'] = $saldo_uang;
            $item['saldo_beras'] = $saldo_beras;
            $item['no'] = $i + 1;
        }

        // Manual pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $offset = ($currentPage - 1) * $perPage;

        // Slice array transaksi untuk halaman saat ini
        $currentPageItems = array_slice($transactions, $offset, $perPage);

        // Buat paginator instance
        $paginatedTransactions = new LengthAwarePaginator(
            $currentPageItems,
            count($transactions),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('laporan.laporan_zakat', [
            'transactions' => $paginatedTransactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalUang' => $totalUang,
            'totalSaldo' => $saldo_uang,
            'saldoBeras' => $saldo_beras,
            'pemasukanUang' => $pemasukanUang,
            'pengeluaranUang' => $pengeluaranUang,
            'pengeluaranBeras' => $pengeluaranBeras,
            'pemasukanBeras' => $pemasukanBeras, // Tambahan untuk view
        ]);
    }

    public function downloadReport(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $startDate = $request->tanggal_awal;
        $endDate = $request->tanggal_akhir;
        $user = Auth::user();

        $zakatMasuk = ZakatMasukModel::whereBetween('tanggal', [$startDate, $endDate])->get();
        $zakatKeluar = ZakatKeluarModel::with('mustahik')->whereBetween('tanggal', [$startDate, $endDate])->get();

        $transactions = [];

        foreach ($zakatMasuk as $zm) {
            $transactions[] = [
                'tanggal' => $zm->tanggal,
                'jenis_zakat' => $zm->jenis_zakat,
                'bentuk_zakat' => $zm->bentuk_zakat,
                'jumlah_kg' => $zm->jumlah,
                'jenis_transaksi' => 'Pemasukan',
                'keterangan' => 'Zakat atas nama ' . ($zm->keterangan ?? '(tidak diketahui)'),
                'nominal' => $zm->nominal,
            ];
        }

        foreach ($zakatKeluar as $zk) {
            $transactions[] = [
                'tanggal' => $zk->tanggal,
                'jenis_zakat' => $zk->jenis_zakat,
                'bentuk_zakat' => $zk->bentuk_zakat,
                'jumlah_kg' => -$zk->jumlah,
                'jenis_transaksi' => 'Pengeluaran',
                'keterangan' => 'Dibagikan untuk mustahik ' . ($zk->mustahik->nama ?? '(tidak diketahui)'),
                'nominal' => -$zk->nominal,
            ];
        }

        usort($transactions, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

        $saldo_uang = 0;
        $saldo_beras = 0;
        $totalUang = 0;
        $pemasukanUang = 0;
        $pengeluaranUang = 0;
        $pengeluaranBeras = 0;
        $pemasukanBeras = 0; // Tambahan untuk total pemasukan beras

        foreach ($transactions as $i => &$item) {
            $saldo_uang += $item['nominal'];
            $totalUang += $item['nominal'];

            if (strtolower($item['bentuk_zakat']) === 'beras') {
                $saldo_beras += $item['jumlah_kg'];
                if ($item['jenis_transaksi'] === 'Pengeluaran') {
                    $pengeluaranBeras += abs($item['jumlah_kg']);
                } else if ($item['jenis_transaksi'] === 'Pemasukan') {
                    $pemasukanBeras += $item['jumlah_kg']; // Tambahan pemasukan beras
                }
            }

            if ($item['jenis_transaksi'] === 'Pemasukan') {
                $pemasukanUang += $item['nominal'];
            } elseif ($item['jenis_transaksi'] === 'Pengeluaran') {
                $pengeluaranUang += abs($item['nominal']);
            }

            $item['saldo'] = $saldo_uang;
            $item['saldo_beras'] = $saldo_beras;
            $item['no'] = $i + 1;
        }

        $data = [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalUang' => $totalUang,
            'totalSaldo' => $saldo_uang,
            'saldoBeras' => $saldo_beras,
            'pemasukanUang' => $pemasukanUang,
            'pengeluaranUang' => $pengeluaranUang,
            'pengeluaranBeras' => $pengeluaranBeras,
            'pemasukanBeras' => $pemasukanBeras,
            'user' => $user,
        ];

        $pdf = PDF::loadView('laporan.unduh_laporan_zakat', $data)
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan_zakat_' . date('Ymd') . '.pdf');
        //  return $pdf->stream('laporan_zakat_' . date('Ymd') . '.pdf');
    }
}
