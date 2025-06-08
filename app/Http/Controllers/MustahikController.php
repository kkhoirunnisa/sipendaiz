<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MustahikModel;
use Barryvdh\DomPDF\Facade\Pdf;
use TheSeer\Tokenizer\Exception;

class MustahikController extends Controller
{

    // Menampilkan daftar mustahik
    public function index(Request $request)
    {
        $search = $request->input('search');

        $mustahik = MustahikModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%");
        })

            ->orderBy('updated_at', 'desc')
            ->paginate(10); //menampilkan data 10 pada tabel

        return view('mustahik.index_mustahik', compact('mustahik'));
    }

    // Menampilkan form untuk menambah mustahik
    public function create()
    {
        return view('mustahik.tambah_mustahik');
    }

    // Menyimpan mustahik baru
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:50',
                'alamat' => 'required|string|max:30',
                'kategori' => 'required|string|max:26',
            ]);

            MustahikModel::create($request->all()); // Menyimpan data mustahik
            return redirect()->route('mustahik.index')->with('success', 'Mustahik berhasil ditambahkan!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }
    // Menampilkan form untuk mengedit mustahik
    public function edit($id)
    {
        $mustahik = MustahikModel::findOrFail($id); // Mencari data mustahik berdasarkan ID
        return view('mustahik.edit_mustahik', compact('mustahik'));
    }

    // Menyimpan perubahan pada mustahik
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:50',
                'alamat' => 'required|string|max:30',
                'kategori' => 'required|string|max:26',
            ]);

            $mustahik = MustahikModel::findOrFail($id); // Mencari data mustahik berdasarkan ID
            $mustahik->update($request->all()); // Menyimpan perubahan data mustahik

            return redirect()->route('mustahik.index')->with('success', 'Mustahik berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Menghapus mustahik
    public function destroy($id)
    {
        try {
            $mustahik = MustahikModel::findOrFail($id); // Mencari data mustahik berdasarkan ID
            $mustahik->delete(); // Menghapus data mustahik

            return redirect()->route('mustahik.index')->with('success', 'Mustahik berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->route('mustahik.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    //UNDUH PDF
    public function exportPdf(Request $request)
    {
        try {
            $search = $request->input('search');

            $mustahik = MustahikModel::query()
                ->when($search, function ($query, $search) {
                    $query->where('nama', 'like', "%$search%")
                        ->orWhere('alamat', 'like', "%$search%");
                })
                ->orderBy('nama')
                ->get();
            // Cek jika data kosong
            if ($mustahik->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak dapat mengunduh PDF karena data mustahik kosong.');
            }
            $pdf = Pdf::loadView('mustahik.unduh_mustahik', compact('mustahik'))
                ->setPaper('A4', 'portrait');

            return $pdf->download('data-mustahik.pdf');
            // return $pdf->stream('data-mustahik.pdf');
        } catch (Exception $e) {
            return redirect()->route('mustahik.index')->with('error', 'Gagal mengunduh PDF mustahik: ' . $e->getMessage());
        }
    }
}
