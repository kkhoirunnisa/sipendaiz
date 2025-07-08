<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InfakKeluarModel;
use App\Models\BuktiTransaksiModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InfakKeluarController extends Controller
{
    // Tampilkan semua data infak berdasarkan kategori
    public function index($kategori, Request $request)
    {
        $search = $request->input('search');

        $infakKeluar = InfakKeluarModel::with('user')
            ->where('kategori', $kategori)

            // search
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
            ->orderBy('updated_at', 'desc') // diurutkan dr besar ke kecil
            ->paginate(10); //menampilkan 10 data
        return view('infak_keluar.index_infak_keluar', compact('infakKeluar', 'kategori'));
    }

    // form tambah data
    public function create($kategori)
    {
        $user = Auth::user();

        // hitung total infak masuk kategori tertentu
        $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
            ->whereIn('id', function ($query) {
                $query->select('id_bukti_transaksi')->from('infak_masuk'); // hanya yg sudah ada di infak masuk
            })
            ->sum('nominal');

        // hitung total infak keluar
        $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');

        // hitung sisa saldo
        $sisaSaldo = $totalMasuk - $totalKeluar;

        if (!old()) {
            session()->forget('temp_bukti_infak_keluar');
        }

        return view('infak_keluar.tambah_infak_keluar', compact('user', 'kategori', 'sisaSaldo'));
    }

    // simpan data baru infak keluar
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // validasi input form
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'kategori' => 'required|string|max:17',
                'nominal' => 'required|numeric',
                'barang' => 'required|string|max:20',
                'keterangan' => 'required|string|max:100',
                'bukti_infak_keluar' => 'nullable|file|mimes:jpg,png,jpeg|max:10240',
            ]);

            $kategori = $request->kategori;

            // total infak masuk untuk kategori tertentu, hanya yang sudah konfirmasi (ada di tabel infak_masuk)
            $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
                ->whereIn('id', function ($query) {
                    $query->select('id_bukti_transaksi')
                        ->from('infak_masuk');
                })
                ->sum('nominal');

            // total infak keluar untuk kategori
            $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');

            // hitung sisa saldo
            $sisaSaldo = $totalMasuk - $totalKeluar;

            // mengecek apakah nominal > sisa saldo
            if ($request->nominal > $sisaSaldo) {
                // Simpan file sementara untuk preview
                if ($request->hasFile('bukti_infak_keluar')) {
                    $tempPath = $request->file('bukti_infak_keluar')->store('temp_ttd', 'public');
                    session(['temp_bukti_infak_keluar' => $tempPath]);
                }

                return back()->withInput()->with('error', 'Nominal infak keluar melebihi sisa saldo sebesar Rp ' . number_format($sisaSaldo, 0, ',', '.'));
            }

            // tambahkan user id ke data yang akan disimpan
            $validated['id_users'] = $user->id;

            // simpan bukti infak keluar
            // if ($request->hasFile('bukti_infak_keluar')) {
            //     $validated['bukti_infak_keluar'] = $request->file('bukti_infak_keluar')->store('bukti', 'public');
            // }
            // Simpan file tanda tangan jika ada
            if ($request->hasFile('bukti_infak_keluar')) {
                $path = $request->file('bukti_infak_keluar')->store('bukti', 'public');
                $validated['bukti_infak_keluar'] = $path;
            } elseif ($request->input('temp_bukti_infak_keluar')) {
                Log::info($request->input('temp_bukti_infak_keluar'));
                $pathTemp = $request->input('temp_bukti_infak_keluar');
                $newPath = 'bukti/' . basename($pathTemp);
                Storage::disk('public')->move($pathTemp, $newPath);
                $validated['bukti_infak_keluar'] = $newPath;
            }

            // simpan data infak keluar
            InfakKeluarModel::create($validated);

            // Hapus temporary file jika ada
            $this->cleanupTempImage();

            return redirect()->route('infak_keluar.index', ['kategori' => $kategori])
                ->with('success', 'Data infak keluar berhasil ditambahkan');
        } catch (Exception $e) {
            // Simpan file sementara untuk preview saat terjadi error
            if ($request->hasFile('bukti_infak_keluar')) {
                $tempPath = $request->file('bukti_infak_keluar')->store('temp_ttd', 'public');
                session(['temp_bukti_infak_keluar' => $tempPath]);
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Bersihkan file gambar sementara
     */
    private function cleanupTempImage()
    {
        $tempPath = session('temp_bukti_infak_keluar');

        if ($tempPath && Storage::disk('public')->exists($tempPath)) {
            Storage::disk('public')->delete($tempPath);
        }

        // Hapus dari session
        session()->forget('temp_bukti_infak_keluar');
    }

    // form edit data
    public function edit(Request $request, $id)
    {
        $infakKeluar = InfakKeluarModel::findOrFail($id);
        $user = Auth::user();
        $kategori = $request->has('kategori') ? $request->kategori : $infakKeluar->kategori;

        $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
            ->whereIn('id', function ($query) {
                $query->select('id_bukti_transaksi')->from('infak_masuk');
            })
            ->sum('nominal');

        $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');
        $sisaSaldo = $totalMasuk - $totalKeluar;

        $tempImagePath = $this->getTempImagePath();

        return view('infak_keluar.edit_infak_keluar', compact(
            'infakKeluar',
            'user',
            'kategori',
            'sisaSaldo',
            'tempImagePath'
        ));
    }

    // menyimpan hasil update data
    public function update(Request $request, $id)
    {
        try {
            $infakKeluar = InfakKeluarModel::findOrFail($id);

            // validasi input
            $validated = $request->validate([
                'id_users' => 'required|exists:users,id',
                'tanggal' => 'required|date',
                'kategori' => 'required|string|max:17',
                'nominal' => 'required|numeric',
                'barang' => 'required|string|max:20',
                'keterangan' => 'required|string|max:100',
                'bukti_infak_keluar' => 'nullable|file|mimes:jpg,png,jpeg|max:10240',
            ]);
            $kategori = $request->kategori;

            // total infak masuk untuk kategori tertentu, hanya yang sudah konfirmasi (ada di tabel infak_masuk)
            $totalMasuk = BuktiTransaksiModel::where('kategori', $kategori)
                ->whereIn('id', function ($query) {
                    $query->select('id_bukti_transaksi')
                        ->from('infak_masuk');
                })
                ->sum('nominal');

            // total infak keluar untuk kategori yang sama
            $totalKeluar = InfakKeluarModel::where('kategori', $kategori)->sum('nominal');

            // hitung sisa saldo
            $sisaSaldo = $totalMasuk - $totalKeluar;

            // mengecek jika nominal > sisa saldo
            if ($request->nominal > $sisaSaldo) {
                // Simpan file sementara untuk preview jika ada
                if ($request->hasFile('bukti_infak_keluar')) {
                    $tempPath = $request->file('bukti_infak_keluar')->store('temp_ttd', 'public');
                    session(['temp_bukti_infak_keluar' => $tempPath]);
                }

                return back()->withInput()->with('error', 'Nominal infak keluar melebihi sisa saldo sebesar Rp ' . number_format($sisaSaldo, 0, ',', '.'));
            }

            // cek apakah ada file baru
            if ($request->hasFile('bukti_infak_keluar')) {

                // hapus file lama jika ada
                if ($infakKeluar->bukti_infak_keluar && Storage::disk('public')->exists($infakKeluar->bukti_infak_keluar)) {
                    Storage::disk('public')->delete($infakKeluar->bukti_infak_keluar);
                }

                // simpan file baru
                $validated['bukti_infak_keluar'] = $request->file('bukti_infak_keluar')->store('bukti', 'public');
            } else {
                // gunakan file lama jika tidak diganti
                $validated['bukti_infak_keluar'] = $infakKeluar->bukti_infak_keluar;
            }

            // update data
            $infakKeluar->update($validated);

            // Hapus temporary file jika ada
            $this->cleanupTempImage();

            return redirect()->route('infak_keluar.index', ['kategori' => $infakKeluar->kategori])->with('success', 'Data infak keluar berhasil diupdate');
        } catch (Exception $e) {
            // Simpan file sementara untuk preview saat terjadi error
            if ($request->hasFile('bukti_infak_keluar')) {
                $tempPath = $request->file('bukti_infak_keluar')->store('temp_ttd', 'public');
                session(['temp_bukti_infak_keluar' => $tempPath]);
            }

            return redirect()->route('infak_keluar.edit', $id)
                ->withInput()
                ->with('error', 'Nominal infak keluar melebihi sisa saldo sebesar Rp ' . number_format($sisaSaldo, 0, ',', '.'));
        }
    }

    // hapus data
    public function destroy($id)
    {
        try {
            $infakKeluar = InfakKeluarModel::findOrFail($id);

            // hapus file bukti pengeluaran
            if ($infakKeluar->bukti_infak_keluar && Storage::disk('public')->exists($infakKeluar->bukti_infak_keluar)) {
                Storage::disk('public')->delete($infakKeluar->bukti_infak_keluar);
            }

            // hapus data dr database
            $infakKeluar->delete();

            return redirect()->route('infak_keluar.index', ['kategori' => $infakKeluar->kategori])->with('success', 'Data infak keluar berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->route('infak_keluar.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    private function getTempImagePath()
    {
        if (session()->has('temp_bukti_infak_keluar')) {
            return 'storage/' . session('temp_bukti_infak_keluar');
        }

        return null;
    }
}
