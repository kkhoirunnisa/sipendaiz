<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\MustahikModel;
use App\Models\ZakatMasukModel;
use App\Models\ZakatKeluarModel;
use Illuminate\Support\Facades\Auth;

class ZakatKeluarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $zakatKeluar = ZakatKeluarModel::with(['user', 'mustahik'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('mustahik', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    })
                        ->orWhere('tanggal', 'like', "%{$search}%")
                        ->orWhere('jenis_zakat', 'like', "%{$search}%")
                        ->orWhere('bentuk_zakat', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%");
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10); //menampilkan data 10 pada tabel

        return view('zakat_keluar.index_zakat_keluar', compact('zakatKeluar'));
    }


    public function create()
    {
        $user = Auth::user();
        $mustahik = MustahikModel::all();
        // Sisa zakat berdasarkan jenis dan bentuk
        $sisa = [];

        foreach (['Fitrah', 'Maal'] as $jenis) {
            foreach (['Uang', 'Beras'] as $bentuk) {
                $masuk = ZakatMasukModel::where('jenis_zakat', $jenis)
                    ->where('bentuk_zakat', $bentuk)
                    ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

                $keluar = ZakatKeluarModel::where('jenis_zakat', $jenis)
                    ->where('bentuk_zakat', $bentuk)
                    ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

                $sisa["{$jenis}_{$bentuk}"] = $masuk - $keluar;
            }
        }


        return view('zakat_keluar.tambah_zakat_keluar', compact('user', 'mustahik', 'sisa'));
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'id_mustahik' => 'required|exists:mustahik,id',
                'tanggal' => 'required|date',
                'jenis_zakat' => 'required|string|max:10',
                'bentuk_zakat' => 'required|string|max:10',
                'nominal' => 'nullable|numeric',
                'jumlah' => 'nullable',
                'keterangan' => 'required|string|max:255',
            ]);

            $jenis = $request->jenis_zakat;
            $bentuk = $request->bentuk_zakat;

            $masuk = ZakatMasukModel::where('jenis_zakat', $jenis)
                ->where('bentuk_zakat', $bentuk)
                ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

            $keluar = ZakatKeluarModel::where('jenis_zakat', $jenis)
                ->where('bentuk_zakat', $bentuk)
                ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

            $sisa = $masuk - $keluar;

            if ($bentuk === 'Uang' && $request->nominal > $sisa) {
                return back()->withInput()->with('error', 'Nominal zakat keluar melebihi sisa saldo untuk Zakat ' . $jenis . ' sebesar Rp ' . number_format($sisa, 0, ',', '.'));
            }

            if ($bentuk === 'Beras' && $request->jumlah > $sisa) {
                return back()->withInput()->with('error', 'Jumlah beras zakat keluar melebihi sisa stok untuk Zakat ' . $jenis . ' sebesar ' . number_format($sisa, 0, ',', '.') . ' Kg');
            }

            // Simpan data zakat keluar
            ZakatKeluarModel::create([
                'id_users' => $user->id,
                'id_mustahik' => $request->id_mustahik,
                'tanggal' => $request->tanggal,
                'jenis_zakat' => $request->jenis_zakat,
                'bentuk_zakat' => $request->bentuk_zakat,
                'nominal' => $request->nominal ?: null,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('zakat_keluar.index')->with('success', 'Data zakat keluar berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $zakatKeluar = ZakatKeluarModel::findOrFail($id);
        $user = Auth::user();
        $mustahik = MustahikModel::all();
        // Tambahkan mustahik yang sudah terhapus tapi digunakan di data zakatKeluar
        if ($zakatKeluar->mustahik && !$mustahik->contains('id', $zakatKeluar->mustahik->id)) {
            $mustahik->push($zakatKeluar->mustahik);
        }
        // Sisa zakat berdasarkan jenis dan bentuk
        $sisa = [];

        foreach (['Fitrah', 'Maal'] as $jenis) {
            foreach (['Uang', 'Beras'] as $bentuk) {
                $masuk = ZakatMasukModel::where('jenis_zakat', $jenis)
                    ->where('bentuk_zakat', $bentuk)
                    ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

                $keluar = ZakatKeluarModel::where('jenis_zakat', $jenis)
                    ->where('bentuk_zakat', $bentuk)
                    ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

                $sisa["{$jenis}_{$bentuk}"] = $masuk - $keluar;
            }
        }

        return view('zakat_keluar.edit_zakat_keluar', compact('zakatKeluar', 'user', 'mustahik', 'sisa'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'id_users' => 'required|exists:users,id',
                'id_mustahik' => 'required|exists:mustahik,id',
                'tanggal' => 'required',
                'jenis_zakat' => 'required',
                'bentuk_zakat' => 'required',
                'nominal' => 'nullable|numeric',
                'jumlah' => 'nullable',
                'keterangan' => 'required',
            ]);

            $jenis = $request->jenis_zakat;
            $bentuk = $request->bentuk_zakat;

            $masuk = ZakatMasukModel::where('jenis_zakat', $jenis)
                ->where('bentuk_zakat', $bentuk)
                ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

            $keluar = ZakatKeluarModel::where('jenis_zakat', $jenis)
                ->where('bentuk_zakat', $bentuk)
                ->sum($bentuk === 'Uang' ? 'nominal' : 'jumlah');

            $sisa = $masuk - $keluar;

            if ($bentuk === 'Uang' && $request->nominal > $sisa) {
                return back()->withInput()->with('error', 'Nominal zakat keluar melebihi sisa saldo untuk Zakat ' . $jenis . ' sebesar Rp ' . number_format($sisa, 0, ',', '.'));
            }

            if ($bentuk === 'Beras' && $request->jumlah > $sisa) {
                return back()->withInput()->with('error', 'Jumlah beras zakat keluar melebihi sisa stok untuk Zakat ' . $jenis . ' sebesar ' . number_format($sisa, 0, ',', '.') . ' Kg');
            }

            // $zakatKeluar = ZakatKeluarModel::findOrFail($id);
            // $zakatKeluar->update($request->all());

            $zakatKeluar = ZakatKeluarModel::findOrFail($id);
            $zakatKeluar->fill($request->all()); // Mengisi semua atribut yang diizinkan (fillable)
            $zakatKeluar->nominal = $request->nominal ?: null; // Jika nominal kosong, set ke null
            $zakatKeluar->save(); // Simpan perubahan

            return redirect()->route('zakat_keluar.index')->with('success', 'Data zakat keluar berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $zakatKeluar = ZakatKeluarModel::findOrFail($id);
            $zakatKeluar->delete();

            return redirect()->route('zakat_keluar.index')->with('success', 'Data zakat keluar berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->route('zakat_keluar.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
