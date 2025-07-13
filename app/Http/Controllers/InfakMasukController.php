<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InfakMasukModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PejabatService;

class InfakMasukController extends Controller
{
    // menampilkan data infak masuk berdasarkan kategori
    public function index($kategori, Request $request)
    {
        // ambil data pencarian dr request
        $search = $request->input('search');

        // ambil data infak yg sudah dikonfirmasi sesuai kategori
        $infakMasuk = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($query) use ($kategori, $search) {
                $query->where('kategori', $kategori);

                // filter search berdasarkan kolom
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('tanggal_konfirmasi', 'like', "%{$search}%")
                            ->orWhere('tanggal_infak', 'like', "%{$search}%")
                            ->orWhere('metode', 'like', "%{$search}%")
                            ->orWhere('donatur', 'like', "%{$search}%")
                            ->orWhere('barang', 'like', "%{$search}%")
                            ->orWhere('nominal', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('nama', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('infak_masuk.index_infak_masuk', compact('infakMasuk', 'kategori', 'search'));
    }



    // menampilkan halaman kuitansi dr infak berdasarkan id

    // Method kuitansi yang diperbaiki
    public function kuitansi($id)
    {
        try {
            // Ambil data infak berdasarkan id
            $infak = InfakMasukModel::with('buktiTransaksi')->findOrFail($id);

            // ✅ SOLUSI: Ambil data pejabat berdasarkan tanggal konfirmasi
            $pejabatData = PejabatService::getPejabatUntukKuitansi($infak->tanggal_konfirmasi);

            // Ekstrak data pejabat
            $ketua = $pejabatData['ketua'];
            $bendahara = $pejabatData['bendahara'];

            // Kirim semua data ke view
            return view('infak_masuk.kuitansi', compact('infak', 'ketua', 'bendahara'));
        } catch (Exception $e) {
            return back()->with('error', 'Data kuitansi tidak ditemukan atau terjadi kesalahan.');
        }
    }

    // Method cetakKuitansiPdf yang diperbaiki
    public function cetakKuitansiPdf($id)
    {
        try {
            $infak = InfakMasukModel::with('buktiTransaksi')->findOrFail($id);

            // ✅ SOLUSI: Ambil data pejabat untuk PDF juga
            $pejabatData = PejabatService::getPejabatUntukKuitansi($infak->tanggal_konfirmasi);
            $ketua = $pejabatData['ketua'];
            $bendahara = $pejabatData['bendahara'];

            // Load view kuitansi ke pdf dengan data pejabat
            $pdf = Pdf::loadView('infak_masuk.kuitansi', compact('infak', 'ketua', 'bendahara'))
                ->setPaper('a4', 'landscape');

            // Hilangkan nama donatur dari karakter khusus
            $namaDonatur = preg_replace('/[^\w\s-]/', '', $infak->buktiTransaksi->donatur);
            $namaFile = 'Kuitansi Infak - ' . $namaDonatur . '.pdf';

            return $pdf->download($namaFile);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mencetak kuitansi: ' . $e->getMessage());
        }
    }
}
