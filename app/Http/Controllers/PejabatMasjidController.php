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
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            // ambil data pejabat masjid dengan filter pencarian jika ada
            $pejabat = PejabatMasjidModel::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('tanggal_selesai', 'like', "%{$search}%")
                    ->orWhere('aktif', 'like', "%{$search}%")
                    ->orWhere('tanggal_mulai', 'like', "%{$search}%");
            })
                ->orderBy('tanggal_mulai', 'desc')
                ->paginate(10)
                ->withQueryString(); // agar keyword tetap saat pindah halaman

            return view('pejabat.index_pejabat', compact('pejabat', 'search'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data pejabat: ' . $e->getMessage());
        }
    }

    // menampilkan form untuk menambahkan pejabat baru
    public function create()
    {
        $user = Auth::user(); // ambil user login
        // Jika user baru masuk form (tidak dari redirect withInput)
        if (!old()) {
            session()->forget('temp_foto_ttd');
        }
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
                'foto_ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:10240',
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

            // Simpan file tanda tangan jika ada
            if ($request->hasFile('foto_ttd')) {
                $path = $request->file('foto_ttd')->store('ttd_pejabat', 'public');
                $data['foto_ttd'] = $path;
            } elseif ($request->filled('foto_ttd_temp')) {
                $pathTemp = $request->input('foto_ttd_temp');
                $newPath = 'ttd_pejabat/' . basename($pathTemp);
                Storage::disk('public')->move($pathTemp, $newPath);
                $data['foto_ttd'] = $newPath;
            }

            // simpan ke database
            PejabatMasjidModel::create($data);

            // hapus file sementara jika ada
            if (session()->has('temp_foto_ttd')) {
                Storage::disk('public')->delete(session('temp_foto_ttd'));
                session()->forget('temp_foto_ttd');
            }


            return redirect()->route('pejabat.index')->with('success', 'Pejabat berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika ada file yang diupload, simpan sementara
            if ($request->hasFile('foto_ttd')) {
                $tempPath = $request->file('foto_ttd')->store('temp_ttd', 'public');
                session(['temp_foto_ttd' => $tempPath]);
            }

            // Kembali dengan error validasi
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Jika ada file yang diupload, simpan sementara
            if ($request->hasFile('foto_ttd')) {
                $tempPath = $request->file('foto_ttd')->store('temp_ttd', 'public');
                session(['temp_foto_ttd' => $tempPath]);
            }

            return back()->withInput()->with('error', 'Gagal menambahkan pejabat: ' . $e->getMessage());
        }
    }

    // Method edit - tambahkan pembersihan session temp jika baru masuk form
    public function edit($id)
    {
        try {
            $pejabat = PejabatMasjidModel::findOrFail($id);
            $user = Auth::user();

            // Jika user baru masuk form edit (tidak dari redirect withInput), bersihkan session temp
            if (!old()) {
                session()->forget('temp_foto_ttd');
            }

            return view('pejabat.edit_pejabat', compact('pejabat', 'user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuka form edit: ' . $e->getMessage());
        }
    }

    // Method update - perbaiki handling file upload dan session
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

            // Handle foto TTD
            if ($request->hasFile('foto_ttd')) {
                // Hapus foto lama jika ada
                if ($pejabat->foto_ttd && Storage::disk('public')->exists($pejabat->foto_ttd)) {
                    Storage::disk('public')->delete($pejabat->foto_ttd);
                }

                // Simpan foto baru
                $path = $request->file('foto_ttd')->store('ttd_pejabat', 'public');
                $data['foto_ttd'] = $path;
            } elseif ($request->filled('foto_ttd_temp')) {
                // Jika menggunakan foto dari session temp
                if ($pejabat->foto_ttd && Storage::disk('public')->exists($pejabat->foto_ttd)) {
                    Storage::disk('public')->delete($pejabat->foto_ttd);
                }

                $pathTemp = $request->input('foto_ttd_temp');
                $newPath = 'ttd_pejabat/' . basename($pathTemp);
                Storage::disk('public')->move($pathTemp, $newPath);
                $data['foto_ttd'] = $newPath;
            }

            // jika tanggal selesai diisi dan sudah lewat, set aktif ke false
            if ($request->tanggal_selesai && Carbon::parse($request->tanggal_selesai)->isPast()) {
                $data['aktif'] = false;
            }

            // simpan perubahan
            $pejabat->update($data);

            // hapus file sementara jika ada
            if (session()->has('temp_foto_ttd')) {
                Storage::disk('public')->delete(session('temp_foto_ttd'));
                session()->forget('temp_foto_ttd');
            }


            return redirect()->route('pejabat.index')->with('success', 'Pejabat berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika ada file yang diupload, simpan sementara
            if ($request->hasFile('foto_ttd')) {
                $tempPath = $request->file('foto_ttd')->store('temp_ttd', 'public');
                session(['temp_foto_ttd' => $tempPath]);
            }

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Jika ada file yang diupload, simpan sementara
            if ($request->hasFile('foto_ttd')) {
                $tempPath = $request->file('foto_ttd')->store('temp_ttd', 'public');
                session(['temp_foto_ttd' => $tempPath]);
            }

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

    // Method untuk membersihkan file temporary (optional - bisa dipanggil via scheduled job)
    public function cleanTempFiles()
    {
        $tempFiles = Storage::disk('public')->files('temp_ttd');
        foreach ($tempFiles as $file) {
            // Hapus file yang lebih dari 1 jam
            if (Storage::disk('public')->lastModified($file) < now()->subHour()->timestamp) {
                Storage::disk('public')->delete($file);
            }
        }
    }

    public function hapusGambar($id)
    {
        try {
            // Hapus file dari session jika ada
            if (session()->has('temp_foto_ttd')) {
                Storage::disk('public')->delete(session('temp_foto_ttd'));
                session()->forget('temp_foto_ttd');
                return response()->json(['success' => true, 'message' => 'File sementara dihapus']);
            }

            // Jika tidak ada session, hapus dari database
            $pejabat = PejabatMasjidModel::findOrFail($id);
            if ($pejabat->foto_ttd && Storage::disk('public')->exists($pejabat->foto_ttd)) {
                Storage::disk('public')->delete($pejabat->foto_ttd);
                $pejabat->update(['foto_ttd' => null]);
                return response()->json(['success' => true, 'message' => 'Foto lama dihapus']);
            }

            return response()->json(['success' => false, 'message' => 'Tidak ada file untuk dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
