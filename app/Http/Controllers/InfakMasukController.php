<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InfakMasukModel;
use Barryvdh\DomPDF\Facade\Pdf;

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
                            ->orWhere('nominal', 'like', "%{$search}%");
                    });
                }
            })
            ->orderBy('updated_at', 'desc') // urutkan dr besar ke kecil
            ->paginate(10); // menampilkan 10 data

        return view('infak_masuk.index_infak_masuk', compact('infakMasuk', 'kategori'));
    }

    // menampilkan halaman kuitansi dr infak berdasarkan id
    public function kuitansi($id)
    {
        try {

            // ambil data infak berdasarkan id
            $infak = InfakMasukModel::with('buktiTransaksi')->findOrFail($id);

            // menampilkan view kuitansi
            return view('infak_masuk.kuitansi', compact('infak'));
        } catch (Exception $e) {
            return back()->with('error', 'Data kuitansi tidak ditemukan atau terjadi kesalahan.');
        }
    }

    // mencetak kuitansi
    public function cetakKuitansiPdf($id)
    {
        try {
            $infak = InfakMasukModel::with('buktiTransaksi')->findOrFail($id);

            // load view kuitansi ke pdf
            $pdf = Pdf::loadView('infak_masuk.kuitansi', compact('infak'))
                ->setPaper('a4', 'landscape');

            // hilangkan nama donatur dr karakter khusus
            // ^ kecuali, \w semua huruf angka underscore _, \s spasi tab newline, - karakter strip -
            $namaDonatur = preg_replace('/[^\w\s-]/', '', $infak->buktiTransaksi->donatur);
            $namaFile = 'Kuitansi Infak - ' . $namaDonatur . '.pdf';

            // return $pdf->stream($namaFile); // menampilkan
            return $pdf->download($namaFile); // unduh
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mencetak kuitansi: ' . $e->getMessage());
        }
    }
}
