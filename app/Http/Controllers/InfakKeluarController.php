<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InfakMasukModel;
use App\Models\InfakKeluarModel;
use App\Models\BuktiTransaksiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InfakKeluarController extends Controller
{
    // Tampilkan semua data
    public function index($kategori, Request $request)
    {
        $search = $request->input('search');

        $infakKeluar = InfakKeluarModel::with('user')
            ->where('kategori', $kategori)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    })
                        ->orWhere('tanggal', 'like', "%{$search}%")
                        ->orWhere('nominal', 'like', "%{$search}%")
                        ->orWhere('barang', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%");
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10); //menampilkan data 10 pada tabel
        return view('infak_keluar.index_infak_keluar', compact('infakKeluar', 'kategori'));
    }

    // Form tambah data
    public function create($kategori)
    {
        $user = Auth::user();
        // Hitung sisa saldo
        $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
            ->whereIn('id', function ($query) {
                $query->select('id_bukti_transaksi')->from('infak_masuk');
            })
            ->sum('nominal');

        $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');

        $sisaSaldo = $totalMasuk - $totalKeluar;
        return view('infak_keluar.tambah_infak_keluar', compact('user', 'kategori', 'sisaSaldo'));
    }


    // Simpan data baru
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'tanggal' => 'required|date',
                'kategori' => 'required|string|max:17',
                'nominal' => 'required|numeric',
                'barang' => 'required|string|max:20',
                'keterangan' => 'required|string|max:50',
                'bukti_infak_keluar' => 'required|file|mimes:jpg,png,jpeg|max:10240',
            ]);

            $kategori = $request->kategori;

            // Total infak masuk untuk kategori tertentu, hanya yang sudah konfirmasi (ada di tabel infak_masuk)
            $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
                ->whereIn('id', function ($query) {
                    $query->select('id_bukti_transaksi')
                        ->from('infak_masuk');
                })
                ->sum('nominal');

            // Total infak keluar untuk kategori yang sama
            $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');

            $sisaSaldo = $totalMasuk - $totalKeluar;

            if ($request->nominal > $sisaSaldo) {
                return back()->withInput()->with('error', 'Nominal infak keluar melebihi sisa saldo sebesar Rp ' . number_format($sisaSaldo, 0, ',', '.'));
            }

            // Tambahkan user id ke data yang akan disimpan
            $validated['id_users'] = $user->id;

            // Simpan bukti jika ada
            if ($request->hasFile('bukti_infak_keluar')) {
                $validated['bukti_infak_keluar'] = $request->file('bukti_infak_keluar')->store('bukti', 'public');
            }

            // Simpan data infak keluar
            InfakKeluarModel::create($validated);

            return redirect()->route('infak_keluar.index', ['kategori' => $kategori])
                ->with('success', 'Data infak keluar berhasil ditambahkan');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Form edit data
    public function edit(Request $request, $id)
    {
        $infakKeluar = InfakKeluarModel::findOrFail($id);
        $user = Auth::user();
        $kategori = $infakKeluar->kategori; // Ambil kategori dari model yang sedang diedit
        // Jika ada kategori dikirim dari form, override
        if ($request->has('kategori')) {
            $kategori = $request->kategori;
        }

        // Total infak masuk yang sudah dikonfirmasi
        $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
            ->whereIn('id', function ($query) {
                $query->select('id_bukti_transaksi')
                    ->from('infak_masuk');
            })
            ->sum('nominal');

        // Total infak keluar untuk kategori sama
        $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');

        $sisaSaldo = $totalMasuk - $totalKeluar;

        if ($request->filled('nominal') && $request->nominal > $sisaSaldo) {
            return back()->withInput()->with('error', 'Nominal infak keluar melebihi sisa saldo sebesar Rp ' . number_format($sisaSaldo, 0, ',', '.'));
        }

        return view('infak_keluar.edit_infak_keluar', compact('infakKeluar', 'user', 'kategori', 'sisaSaldo'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        try {
            $infakKeluar = InfakKeluarModel::findOrFail($id);

            $validated = $request->validate([
                'id_users' => 'required|exists:users,id',
                'tanggal' => 'required|date',
                'kategori' => 'required|string|max:17',
                'nominal' => 'required|numeric',
                'barang' => 'required|string|max:20',
                'keterangan' => 'required|string|max:50',
                'bukti_infak_keluar' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            ]);
            $kategori = $request->kategori;

            // Total infak masuk untuk kategori tertentu, hanya yang sudah konfirmasi (ada di tabel infak_masuk)
            $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
                ->whereIn('id', function ($query) {
                    $query->select('id_bukti_transaksi')
                        ->from('infak_masuk');
                })
                ->sum('nominal');

            // Total infak keluar untuk kategori yang sama
            $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');

            $sisaSaldo = $totalMasuk - $totalKeluar;

            if ($request->nominal > $sisaSaldo) {
                return back()->withInput()->with('error', 'Nominal infak keluar melebihi sisa saldo sebesar Rp ' . number_format($sisaSaldo, 0, ',', '.'));
            }

            // Cek apakah ada file baru
            if ($request->hasFile('bukti_infak_keluar')) {
                // Hapus file lama jika ada
                if ($infakKeluar->bukti_infak_keluar && Storage::disk('public')->exists($infakKeluar->bukti_infak_keluar)) {
                    Storage::disk('public')->delete($infakKeluar->bukti_infak_keluar);
                }

                // Simpan file baru
                $validated['bukti_infak_keluar'] = $request->file('bukti_infak_keluar')->store('bukti', 'public');
            } else {
                // Gunakan file lama jika tidak diganti
                $validated['bukti_infak_keluar'] = $infakKeluar->bukti_infak_keluar;
            }

            $infakKeluar->update($validated);

            return redirect()->route('infak_keluar.index', ['kategori' => $infakKeluar->kategori])->with('success', 'Data infak keluar berhasil diupdate');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Hapus data
    public function destroy($id)
    {
        try {
            $infakKeluar = InfakKeluarModel::findOrFail($id);
            if ($infakKeluar->bukti_infak_keluar && Storage::disk('public')->exists($infakKeluar->bukti_infak_keluar)) {
                Storage::disk('public')->delete($infakKeluar->bukti_infak_keluar);
            }
            $infakKeluar->delete();

            return redirect()->route('infak_keluar.index', ['kategori' => $infakKeluar->kategori])->with('success', 'Data infak keluar berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->route('infak_keluar.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
