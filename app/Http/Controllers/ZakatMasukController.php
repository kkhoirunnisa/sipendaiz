<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Models\ZakatMasukModel;
use Illuminate\Support\Facades\Auth;

class ZakatMasukController extends Controller
{
    // Menampilkan daftar semua zakat masuk
    public function index(Request $request)
    {
        $search = $request->input('search');

        $zakatMasuk = ZakatMasukModel::with('user')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('tanggal', 'like', "%{$search}%")
                        ->orWhere('jenis_zakat', 'like', "%{$search}%")
                        ->orWhere('bentuk_zakat', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q2) use ($search) {
                            $q2->where('nama', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10); //menampilkan data 10 pada tabel

        // Menambahkan nl2br untuk mengganti \n menjadi <br> di keterangan
        foreach ($zakatMasuk as $zm) {
            $zm->keterangan = nl2br($zm->keterangan);
        }

        return view('zakat_masuk.index_zakat_masuk', compact('zakatMasuk', 'search'));
    }

    // Menampilkan form untuk menambah zakat masuk baru
    public function create()
    {
        $user = Auth::user();
        return view('zakat_masuk.tambah_zakat_masuk', compact('user'));
    }

    // Menyimpan data zakat masuk baru
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'tanggal' => 'required|date',
                'jenis_zakat' => 'required|string|max:10',
                'bentuk_zakat' => 'required|string|max:10',
                'nominal' => 'nullable|numeric',
                'jumlah' => 'nullable',
                'keterangan' => 'required|string|max:255',
            ]);

            ZakatMasukModel::create([
                'id_users' => $user->id,
                'tanggal' => $request->tanggal,
                'jenis_zakat' => $request->jenis_zakat,
                'bentuk_zakat' => $request->bentuk_zakat,
                'nominal' => $request->nominal ?: null,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('zakat_masuk.index')->with('success', 'Data zakat masuk berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    // Menampilkan form edit untuk zakat masuk tertentu
    public function edit($id)
    {
        $zakatMasuk = ZakatMasukModel::findOrFail($id);
        $user = Auth::user();
        // Tambahkan mustahik yang sudah terhapus tapi digunakan di data zakatKeluar
        // if ($zakatKeluar->mustahik && !$mustahik->contains('id', $zakatKeluar->mustahik->id)) {
        //     $mustahik->push($zakatKeluar->mustahik);
        // }
        return view('zakat_masuk.edit_zakat_masuk', compact('zakatMasuk', 'user'));
    }

    // Menyimpan update dari zakat masuk yang diedit
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'id_users' => 'required|exists:users,id',
                'tanggal' => 'required|date',
                'jenis_zakat' => 'required|string|max:10',
                'bentuk_zakat' => 'required|string|max:10',
                'nominal' => 'nullable|numeric',
                'jumlah' => 'nullable',
                'keterangan' => 'required|string|max:255',
            ]);

            // dd($request->nominal);
            // $zakatMasuk = ZakatMasukModel::findOrFail($id);
            // $zakatMasuk->update($request->all());

            // Gunakan fill() agar mutator bekerja
            // $zakatMasuk->fill($request->all());
            // $zakatMasuk->save();

            $zakatMasuk = ZakatMasukModel::findOrFail($id);
            $zakatMasuk->fill($request->all());
            $zakatMasuk->nominal = $request->nominal ?: null; // Setel menjadi null jika kosong
            $zakatMasuk->save();

            return redirect()->route('zakat_masuk.index')->with('success', 'Data zakat masuk berhasil diupdate.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Menghapus data zakat masuk
    public function destroy($id)
    {
        try {
            $zakatMasuk = ZakatMasukModel::findOrFail($id);
            $zakatMasuk->delete();

            return redirect()->route('zakat_masuk.index')->with('success', 'Data zakat masuk berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->route('zakat_masuk.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
