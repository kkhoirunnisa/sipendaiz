<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfakMasukModel;
use App\Models\InfakKeluarModel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanInfakController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->kategori;

        return view('laporan.laporan_infak', compact('kategori'));
    }


    public function generateReport(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kategori' => 'required|string',
        ]);

        $startDate = $request->tanggal_awal;
        $endDate = $request->tanggal_akhir;
        $kategori = $request->kategori;

        $infakMasuk = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($query) use ($startDate, $endDate, $kategori) {
                $query->where('kategori', $kategori)
                    ->whereBetween('tanggal_konfirmasi', [$startDate, $endDate]);
            })
            ->orderBy('tanggal_konfirmasi', 'asc')
            ->get();

        $infakKeluar = InfakKeluarModel::where('kategori', $kategori)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $transactions = [];

        // Gabung semua transaksi pemasukan
        foreach ($infakMasuk as $item) {
            $transactions[] = [
                'donatur' => $item->buktiTransaksi->donatur,
                'tanggal' => $item->buktiTransaksi->tanggal_infak,
                'keterangan' => $item->buktiTransaksi->keterangan ?: 'Infak masuk',
                'alamat' => $item->buktiTransaksi->alamat,
                'jenis_transaksi' => 'Pemasukan',
                'jenis_barang' => $item->buktiTransaksi->barang ?: 'Uang',
                'masuk' => $item->buktiTransaksi->nominal,
                'keluar' => 0,
                'saldo' => 0, // akan dihitung ulang nanti
            ];
        }

        // Gabung semua transaksi pengeluaran
        foreach ($infakKeluar as $item) {
            $transactions[] = [
                'tanggal' => $item->tanggal,
                'keterangan' => $item->keterangan,
                'alamat' => '-',
                'jenis_transaksi' => 'Pengeluaran',
                'jenis_barang' => $item->barang ?: 'Uang',
                'masuk' => 0,
                'keluar' => -$item->nominal,
                'saldo' => 0, // akan dihitung ulang nanti
            ];
        }

        // Urutkan berdasarkan tanggal
        usort($transactions, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

        // Hitung saldo berdasarkan urutan tanggal
        $saldo = 0;
        foreach ($transactions as $i => &$item) {
            $saldo += $item['masuk'];
            $saldo += $item['keluar'];
            $item['saldo'] = $saldo;
            $item['no'] = $i + 1;
        }
        unset($item); // Hindari reference bug

        $totalSaldo = $saldo;
        $totalMasuk = array_sum(array_column($transactions, 'masuk'));
        $totalKeluar = abs(array_sum(array_column($transactions, 'keluar')));

        return view('laporan.laporan_infak', [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'kategori' => $kategori,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalSaldo' => $totalSaldo,
        ]);
    }

    public function downloadReport(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kategori' => 'required|string',
        ]);

        $startDate = $request->tanggal_awal;
        $endDate = $request->tanggal_akhir;
        $kategori = $request->kategori;

        $infakMasuk = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($query) use ($startDate, $endDate, $kategori) {
                $query->where('kategori', $kategori)
                    ->whereBetween('tanggal_konfirmasi', [$startDate, $endDate]);
            })
            ->orderBy('tanggal_konfirmasi', 'asc')
            ->get();

        $infakKeluar = InfakKeluarModel::where('kategori', $kategori)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $transactions = [];

        // Gabung semua transaksi pemasukan
        foreach ($infakMasuk as $item) {
            $transactions[] = [
                'donatur' => $item->buktiTransaksi->donatur,
                'tanggal' => $item->buktiTransaksi->tanggal_infak,
                'keterangan' => 'Infak atas nama' . ' ' . $item->buktiTransaksi->donatur ?: 'Infak masuk',
                'alamat' => $item->buktiTransaksi->alamat,
                'jenis_transaksi' => 'Pemasukan',
                'jenis_barang' => $item->buktiTransaksi->barang ?: 'Uang',
                'masuk' => $item->buktiTransaksi->nominal,
                'keluar' => 0,
                'saldo' => 0, // akan dihitung ulang nanti
            ];
        }

        // Gabung semua transaksi pengeluaran
        foreach ($infakKeluar as $item) {
            $transactions[] = [
                'tanggal' => $item->tanggal,
                'keterangan' => $item->keterangan,
                'alamat' => '-',
                'jenis_transaksi' => 'Pengeluaran',
                'jenis_barang' => $item->barang ?: 'Uang',
                'masuk' => 0,
                'keluar' => -$item->nominal,
                'saldo' => 0, // akan dihitung ulang nanti
            ];
        }

        // Urutkan berdasarkan tanggal
        usort($transactions, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

        // Hitung saldo berdasarkan urutan tanggal
        $saldo = 0;
        foreach ($transactions as $i => &$item) {
            $saldo += $item['masuk'];
            $saldo += $item['keluar'];
            $item['saldo'] = $saldo;
            $item['no'] = $i + 1;
        }
        unset($item); // Hindari reference bug

        $totalSaldo = $saldo;
        $totalMasuk = array_sum(array_column($transactions, 'masuk'));
        $totalKeluar = abs(array_sum(array_column($transactions, 'keluar')));
        $formattedStartDate = Carbon::parse($startDate)->format('d-m-Y');
        $formattedEndDate = Carbon::parse($endDate)->format('d-m-Y');

        $pdf = Pdf::loadView('laporan.unduh_laporan_infak', [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'kategori' => $kategori,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalSaldo' => $totalSaldo,
            'formattedStartDate' => $formattedStartDate,
            'formattedEndDate' => $formattedEndDate,
        ])->setPaper('A4', 'landscape');

        // return $pdf->stream("Laporan_Infak_{$kategori}_{$formattedStartDate}_sd_{$formattedEndDate}.pdf");
        return $pdf->download("Laporan_Infak_{$kategori}_{$formattedStartDate}_sd_{$formattedEndDate}.pdf");
    }
}
