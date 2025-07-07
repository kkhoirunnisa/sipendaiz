<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PejabatMasjidModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PejabatMasjidController extends Controller
{
    // menampilkan daftar pejabat masjid
    public function index()
    {
        try {
            // ambil semua pejabat masjid, urutkan berdasarkan tanggal mulai terbaru
            $pejabat = PejabatMasjidModel::orderBy('tanggal_mulai', 'desc')->get();

            return view('pejabat.index_pejabat', compact('pejabat'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data pejabat: ' . $e->getMessage());
        }
    }

    // menampilkan form untuk menambahkan pejabat baru
    public function create()
    {
        $user = Auth::user(); // ambil user login

        return view('pejabat.create_pejabat', compact('user'));
    }

    // menyimpan pejabat baru
    public function store(Request $request)
    {
        try {
            // validasi input
            $request->validate([
                'jabatan' => 'required|in:ketua_takmir,bendahara_takmir,ketua_pembangunan,bendahara_pembangunan',
                'nama' => 'required|string|max:255',
                'foto_ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
                'tanggal_mulai' => 'required|date',
            ]);

            // cek apakah ada pejabat aktif untuk jabatan yang sama
            // jika ada, nonaktifkan pejabat lama dengan mengupdate tanggal selesai
            $pejabatLama = PejabatMasjidModel::getPejabatAktif($request->jabatan);
            if ($pejabatLama) {
                $pejabatLama->update([
                    'aktif' => false,
                    'tanggal_selesai' => Carbon::parse($request->tanggal_mulai)->subDay()
                ]);
            }

            // ambil data dari request form
            $data = $request->only(['jabatan', 'nama', 'tanggal_mulai']);
            $data['id_users'] = Auth::id(); // simpan ID user yang membuat data

            // simpan tanda tangan pejabat jika ada
            if ($request->hasFile('foto_ttd')) {
                $path = $request->file('foto_ttd')->store('ttd_pejabat', 'public');
                $data['foto_ttd'] = $path;
            }

            // simpan ke database
            PejabatMasjidModel::create($data);

            return redirect()->route('pejabat.index')->with('success', 'Pejabat berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan pejabat: ' . $e->getMessage());
        }
    }

    // menampilkan form untuk mengedit pejabat
    public function edit($id)
    {
        try {
            $pejabat = PejabatMasjidModel::findOrFail($id);
            $user = Auth::user();
            return view('pejabat.edit_pejabat', compact('pejabat', 'user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuka form edit: ' . $e->getMessage());
        }
    }

    // memperbarui data pejabat
    public function update(Request $request, $id)
    {
        try {
            $pejabat = PejabatMasjidModel::findOrFail($id);

            $request->validate([
                'nama' => 'required|string|max:255',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'foto_ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            ]);

            // ambil data dari form
            $data = $request->only(['nama', 'tanggal_mulai', 'tanggal_selesai']);
            $data['id_users'] = Auth::id();

            // jika upload foto baru, hapus foto lama
            if ($request->hasFile('foto_ttd')) {
                if ($pejabat->foto_ttd && Storage::disk('public')->exists($pejabat->foto_ttd)) {
                    Storage::disk('public')->delete($pejabat->foto_ttd);
                }

                // simpan foto tanda tangan baru
                $path = $request->file('foto_ttd')->store('ttd_pejabat', 'public');
                $data['foto_ttd'] = $path;
            }

            // jika tanggal selesai diisi dan sudah lewat, set aktif ke false nonaktif
            if ($request->tanggal_selesai && Carbon::parse($request->tanggal_selesai)->isPast()) {
                $data['aktif'] = false;
            }

            // simpan perubahan
            $pejabat->update($data);

            return redirect()->route('pejabat.index')->with('success', 'Pejabat berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui pejabat: ' . $e->getMessage());
        }
    }

    // menghapus pejabat
    public function destroy($id)
    {
        try {
            $pejabat = PejabatMasjidModel::findOrFail($id);

            // jika ada foto tanda tangan, hapus dari storage
            if ($pejabat->foto_ttd && Storage::disk('public')->exists($pejabat->foto_ttd)) {
                Storage::disk('public')->delete($pejabat->foto_ttd);
            }

            $pejabat->delete(); //hapus data

            return redirect()->route('pejabat.index')->with('success', 'Pejabat berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pejabat: ' . $e->getMessage());
        }
    }

    // menampilkan riwayat pejabat berdasarkan jabatan
    public function riwayat($jabatan)
    {
        try {
            // ambil semua data pejabat berdasarkan jabatan
            $riwayat = PejabatMasjidModel::where('jabatan', $jabatan)
                ->orderBy('tanggal_mulai', 'desc')
                ->get();

            return view('pejabat.riwayat_pejabat', compact('riwayat', 'jabatan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat riwayat pejabat: ' . $e->getMessage());
        }
    }
}