<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InfakMasukModel;
use Barryvdh\DomPDF\Facade\Pdf;

class InfakMasukController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->kategori;
        $search = $request->search; // tambahkan jika ingin fitur pencarian juga

        $infakMasuk = InfakMasukModel::with('buktiTransaksi')
            ->whereHas('buktiTransaksi', function ($query) use ($kategori, $search) {
                $query->when($kategori, function ($q) use ($kategori) {
                    $q->where('kategori', $kategori);
                });

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('alamat', 'like', "%{$search}%")
                            ->orWhere('nomor_telepon', 'like', "%{$search}%")
                            ->orWhere('tanggal_infak', 'like', "%{$search}%")
                            ->orWhere('kategori', 'like', "%{$search}%")
                            ->orWhere('metode', 'like', "%{$search}%")
                            ->orWhere('barang', 'like', "%{$search}%");
                    });
                }
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('infak_masuk.index_infak_masuk', compact('infakMasuk', 'kategori'));
    }

    // KUITANSI
    public function kuitansi($id)
    {
        try {
            $infak = InfakMasukModel::with('buktiTransaksi')->findOrFail($id);
            return view('infak_masuk.kuitansi', compact('infak'));
        } catch (Exception $e) {
            return back()->with('error', 'Data kuitansi tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function cetakKuitansiPdf($id)
    {
        try {
            $infak = InfakMasukModel::with('buktiTransaksi')->findOrFail($id);

            $pdf = Pdf::loadView('infak_masuk.kuitansi', compact('infak'))
                ->setPaper('a4', 'landscape');

            $namaDonatur = preg_replace('/[^\w\s-]/', '', $infak->buktiTransaksi->donatur);
            $namaFile = 'Kuitansi Infak - ' . $namaDonatur . '.pdf';
            // return $pdf->stream($namaFile);
            return $pdf->download($namaFile);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mencetak kuitansi: ' . $e->getMessage());
        }
    }
}
