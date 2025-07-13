<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\InfakMasukModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InfakKeluarModel;
use App\Services\PejabatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class LaporanInfakController extends Controller
{
    // menampilkan halaman laporan infak
    public function index(Request $request)
    {
        $kategori = $request->kategori;
        return view('laporan.laporan_infak', compact('kategori'));
    }

    // menghasilkan laporan infak berdasarkan input tgl
    public function generateReport(Request $request)
    {
        // validasi input
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kategori' => 'required|string',
        ]);

        $startDate = $request->tanggal_awal;
        $endDate = $request->tanggal_akhir;
        $kategori = $request->kategori;

        // ambil data infak sesuai tanggal dan kategori
        $infakMasuk = InfakMasukModel::with('buktiTransaksi')
            ->whereBetween('tanggal_konfirmasi', [$startDate, $endDate])
            ->whereHas('buktiTransaksi', function ($query) use ($kategori) { //memastikan hanya data infak masuk yg relasinya bukti transaksi memiliki kategori tertentu
                $query->where('kategori', $kategori);
            })
            ->orderBy('tanggal_konfirmasi', 'asc') // urutkan dr kecil ke besar
            ->get();

        // ambil infak keluar sesuai tgl dan kategori
        $infakKeluar = InfakKeluarModel::where('kategori', $kategori)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $transactions = []; // arrary menampung semua transaksi

        // gabung semua transaksi pemasukan ke array transaksi
        foreach ($infakMasuk as $item) {
            $transactions[] = [
                'donatur' => $item->buktiTransaksi->donatur,
                'tanggal' => $item->buktiTransaksi->tanggal_infak,
                'alamat' => $item->buktiTransaksi->alamat,
                'jenis_transaksi' => 'Pemasukan',
                'jenis_barang' => $item->buktiTransaksi->barang ?: 'Uang',
                'masuk' => $item->buktiTransaksi->nominal,
                'keluar' => 0, //tidak ada
                'saldo' => 0, // akan dihitung nanti diakhir
            ];
        }

        // gabung semua transaksi pengeluaran
        foreach ($infakKeluar as $item) {
            $transactions[] = [
                'tanggal' => $item->tanggal,
                'keterangan' => $item->keterangan,
                'alamat' => '-',
                'jenis_transaksi' => 'Pengeluaran',
                'jenis_barang' => $item->barang ?: 'Uang',
                'masuk' => 0,
                'keluar' => $item->nominal,
                'saldo' => 0, // akan dihitung ulang nanti
            ];
        }

        // urutkan transaksi masukkeluar berdasarkan tanggal
        usort($transactions, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

        // hitung saldo berdasarkan urutan tanggal
        $saldo = 0;
        foreach ($transactions as $i => &$item) {
            $saldo += $item['masuk'];
            $saldo -= $item['keluar'];
            $item['saldo'] = $saldo;
            $item['no'] = $i + 1; // penomoran transaksi
        }
        unset($item); // hindari reference bug

        // hitung saldo akhir
        $totalSaldo = $saldo;

        // hitung pemasukan
        $totalMasuk = array_sum(array_column($transactions, 'masuk'));

        // hitung pengeluaran
        $totalKeluar = abs(array_sum(array_column($transactions, 'keluar')));

        // manual pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $offset = ($currentPage - 1) * $perPage;

        // ambil data untuk halaman saat ini
        $currentPageItems = array_slice($transactions, $offset, $perPage);

        // buat paginator instance laravel
        $paginatedTransactions = new LengthAwarePaginator(
            $currentPageItems, // data yg ditampilkan
            count($transactions), // semua total data
            $perPage, // jml per halaman
            $currentPage, // halaman saat ini
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // kirim data ke view
        return view('laporan.laporan_infak', [
            'transactions' => $paginatedTransactions,
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
        $user = Auth::user();

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
                // 'keterangan' => 'Infak atas nama' . ' ' . $item->buktiTransaksi->donatur ?: 'Infak masuk',
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
                'keluar' => $item->nominal,
                'saldo' => 0, // akan dihitung ulang nanti
            ];
        }

        // Urutkan berdasarkan tanggal
        usort($transactions, fn($a, $b) => strtotime($a['tanggal']) <=> strtotime($b['tanggal']));

        // Hitung saldo berdasarkan urutan tanggal
        $saldo = 0;
        foreach ($transactions as $i => &$item) {
            $saldo += $item['masuk'];
            $saldo -= $item['keluar'];
            $item['saldo'] = $saldo;
            $item['no'] = $i + 1;
        }
        unset($item); // Hindari reference bug

        $totalSaldo = $saldo;
        $totalMasuk = array_sum(array_column($transactions, 'masuk'));
        $totalKeluar = abs(array_sum(array_column($transactions, 'keluar')));
        $formattedStartDate = Carbon::parse($startDate)->format('d-m-Y');
        $formattedEndDate = Carbon::parse($endDate)->format('d-m-Y');

        // Ambil tanggal saat laporan diunduh
        // $tanggalCetak = now(); // atau Carbon::now()

        $pejabat = PejabatService::getPejabatUntukLaporanInfak($kategori, $endDate);
        $ketua = $pejabat['ketua']?->nama ?? '-';
        $bendahara = $pejabat['bendahara']?->nama ?? '-';


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
            'ketua' => $ketua,
            'bendahara' => $bendahara,
            'user' => $user,
            // 'tanggalCetak' => $tanggalCetak,
        ])->setPaper('A4', 'landscape');

        // return $pdf->stream("Laporan_Infak_{$kategori}_{$formattedStartDate}_sd_{$formattedEndDate}.pdf");
        return $pdf->download("Laporan_Infak_{$kategori}_{$formattedStartDate}_sd_{$formattedEndDate}.pdf");
    }
}
