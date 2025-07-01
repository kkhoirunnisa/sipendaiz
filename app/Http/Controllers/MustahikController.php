<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MustahikModel;
use Barryvdh\DomPDF\Facade\Pdf;
use TheSeer\Tokenizer\Exception;
use Illuminate\Validation\ValidationException;

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

    public function publicView()
    {
        $listMustahik = MustahikModel::pluck('nama')->toArray();
        return view('mustahik.public', compact('listMustahik'));
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
                'alamat' => 'required|string|max:50',
                'kategori' => 'required|string|max:26',
            ]);

            // Cek apakah kombinasi nama dan alamat sudah ada
            $cekDuplikat = MustahikModel::where('nama', $request->nama)
                ->where('alamat', $request->alamat)
                ->exists();

            if ($cekDuplikat) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data mustahik dengan nama dan alamat yang sama sudah ada.');
            }

            MustahikModel::create($request->all());
            return redirect()->route('mustahik.index')->with('success', 'Mustahik berhasil ditambahkan!');
        } catch (ValidationException $e) {
            $errorMessages = [];
            foreach ($e->errors() as $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }

            return redirect()->back()->withInput()->with('error', implode('. ', $errorMessages));
        } catch (\Exception $e) {
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
                'alamat' => 'required|string|max:50',
                'kategori' => 'required|string|max:26',
            ]);

            // Cek apakah kombinasi nama dan alamat sudah dimiliki mustahik lain
            $cekDuplikat = MustahikModel::where('id', '!=', $id)
                ->where('nama', $request->nama)
                ->where('alamat', $request->alamat)
                ->exists();

            if ($cekDuplikat) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data mustahik dengan nama dan alamat tersebut sudah ada.');
            }

            $mustahik = MustahikModel::findOrFail($id);
            $mustahik->update($request->all());

            return redirect()->route('mustahik.index')->with('success', 'Mustahik berhasil diperbarui!');
        } catch (ValidationException $e) {
            $errorMessages = [];
            foreach ($e->errors() as $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }

            return redirect()->back()->withInput()->with('error', implode('. ', $errorMessages));
        } catch (\Exception $e) {
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
