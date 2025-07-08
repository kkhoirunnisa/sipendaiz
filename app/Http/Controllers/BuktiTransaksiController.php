<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\InfakMasukModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PejabatService;
use App\Models\BuktiTransaksiModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuktiTransaksiController extends Controller
{
    // Menampilkan daftar semua bukti transaksi dengan fitur pencarian dan paginasi
    public function index(Request $request)
    {
        $search = $request->input('search');
        // Filter pencarian berdasarkan beberapa kolom
        $buktiTransaksi = BuktiTransaksiModel::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('donatur', 'like', "%{$search}%")
                    ->orWhere('jenis_infak', 'like', "%{$search}%")
                    ->orWhere('tanggal_infak', 'like', "%{$search}%")
                    ->orWhere('nominal', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('metode', 'like', "%{$search}%")
                    ->orWhere('barang', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('bukti_transaksi.index_bukti_transaksi', compact('buktiTransaksi', 'search'));
    }

    // menampilkan form tambah bukti transaksi
    public function create()
    {
        $user = Auth::user();

        // Jika user baru masuk form (tidak dari redirect withInput)
        if (!old()) {
            session()->forget('temp_bukti_transaksi');
        }

        $buktiSementara = null;
        if (session()->has('temp_bukti_transaksi')) {
            $buktiSementara = asset('storage/' . session('temp_bukti_transaksi'));
        }

        return view('bukti_transaksi.tambah_bukti_transaksi', compact('user', 'buktiSementara'));
    }

    // menyimpan bukti transaksi baru
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi input
            $validated = $request->validate([
                'donatur' => 'required|string|max:50',
                'alamat' => 'nullable|string|max:30',
                'nomor_telepon' => 'nullable|numeric|digits_between:10,14',
                'tanggal_infak' => 'required|date',
                'kategori' => 'required|string|max:12',
                'sumber' => 'required|string|max:11',
                'jenis_infak' => 'required|string|max:6',
                'nominal' => 'nullable',
                'barang' => 'nullable|string|max:20',
                'metode' => 'required|string|max:17',
                'bukti_transaksi' => 'nullable|file|mimes:jpg,png,jpeg|max:10240',
                'keterangan' => 'required|string|max:100',
            ]);

            // Handle upload file
            $buktiPath = null;

            if ($request->hasFile('bukti_transaksi')) {
                $buktiPath = $request->file('bukti_transaksi')->store('bukti', 'public');
            } elseif (session()->has('temp_bukti_transaksi')) {
                $tempPath = session('temp_bukti_transaksi');
                $namaBaru = 'bukti/' . basename($tempPath);
                Storage::disk('public')->move($tempPath, $namaBaru);
                $buktiPath = $namaBaru;
            }

            // Jika tidak ada file bukti sama sekali, kembalikan error
            if (!$buktiPath) {
                return back()->withInput()->with('error', 'Bukti transaksi wajib diunggah.');
            }

            // Simpan ke database
            BuktiTransaksiModel::create([
                'id_users' => $user->id,
                'donatur' => $request->donatur,
                'alamat' => $request->alamat,
                'nomor_telepon' => $request->nomor_telepon,
                'tanggal_infak' => $request->tanggal_infak,
                'kategori' => $request->kategori,
                'sumber' => $request->sumber,
                'jenis_infak' => $request->jenis_infak,
                'nominal' => $request->filled('nominal') ? $request->nominal : null,
                'barang' => $request->barang,
                'metode' => $request->metode,
                'bukti_transaksi' => $buktiPath,
                'keterangan' => $request->keterangan,
                'status' => 'Pending',
            ]);

            // Hapus file sementara
            if (session()->has('temp_bukti_transaksi')) {
                Storage::disk('public')->delete(session('temp_bukti_transaksi'));
                session()->forget('temp_bukti_transaksi');
            }

            // Kirim notifikasi ke bendahara
            $this->sendNotificationToBendahara($validated);

            return redirect()->route('bukti_transaksi.index')->with('success', 'Bukti transaksi berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Simpan file ke temporary jika validasi gagal
            $this->handleTemporaryFile($request);
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Simpan file ke temporary jika error umum
            $this->handleTemporaryFile($request);
            return back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Handle temporary file storage saat error
     */
    private function handleTemporaryFile(Request $request)
    {
        if ($request->hasFile('bukti_transaksi')) {
            // Hapus file temporary lama jika ada
            if (session()->has('temp_bukti_transaksi')) {
                Storage::disk('public')->delete(session('temp_bukti_transaksi'));
            }

            // Simpan file baru ke temporary
            $tempPath = $request->file('bukti_transaksi')->store('temp_bukti', 'public');
            session(['temp_bukti_transaksi' => $tempPath]);
        }
    }

    /**
     * Kirim notifikasi ke bendahara
     */
    private function sendNotificationToBendahara($validated)
    {
        $client = new Client();
        $bendaharas = UserModel::where('role', 'Bendahara')->whereNotNull('nomor_telepon')->get();

        $message = "*BUKTI TRANSAKSI BARU MASUK*\n\n";
        $message .= "Ada transaksi infak yang perlu dikonfirmasi:\n";
        $message .= "*Nama Donatur:* {$validated['donatur']}\n";
        $message .= "*Kategori:* {$validated['kategori']}\n";
        $message .= "*Metode:* {$validated['metode']}\n";
        $message .= "*Infak:* ";

        if (!empty($validated['nominal'])) {
            $message .= "Rp " . number_format($validated['nominal'], 0, ',', '.');
        } elseif (!empty($validated['barang'])) {
            $message .= $validated['barang'];
        } else {
            $message .= "-";
        }

        $message .= "\n";
        $message .= "*Tanggal:* " . Carbon::parse($validated['tanggal_infak'])->format('d-m-Y') . "\n";
        $message .= "*Status:* Pending\n\n";
        $message .= "Silakan buka halaman *Konfirmasi Transaksi* untuk meninjau.";

        foreach ($bendaharas as $bendahara) {
            try {
                $client->post('https://api.fonnte.com/send', [
                    'headers' => [
                        'Authorization' => env('FONNTE_TOKEN'),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'target' => $bendahara->nomor_telepon,
                        'message' => $message,
                    ],
                ]);
            } catch (Exception $e) {
                Log::error('WhatsApp notification error: ' . $e->getMessage());
            }
        }
    }

    // menampilkan form edit
    public function edit($id)
    {
        $buktiTransaksi = BuktiTransaksiModel::findOrFail($id);
        $user = Auth::user();

        if ($buktiTransaksi->status === 'Terverifikasi') {
            return redirect()->route('bukti_transaksi.index')->with('error', 'Data sudah terverifikasi dan tidak bisa diubah.');
        }

        return view('bukti_transaksi.edit_bukti_transaksi', compact('buktiTransaksi', 'user'));
    }

    // memperbarui data bukti transaksi
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'id_users' => 'required|exists:users,id',
                'donatur' => 'required|string|max:50',
                'alamat' => 'nullable|string|max:30',
                'nomor_telepon' => 'nullable|numeric|digits_between:10,14',
                'tanggal_infak' => 'required|date',
                'kategori' => 'required|string|max:12',
                'sumber' => 'required|string|max:11',
                'jenis_infak' => 'required|string|max:6',
                'nominal' => 'nullable',
                'barang' => 'nullable|string|max:20',
                'metode' => 'required|string|max:17',
                'bukti_transaksi' => 'nullable|file|mimes:jpg,png,jpeg|max:10240',
                'keterangan' => 'required|string|max:100',
            ]);

            $buktiTransaksi = BuktiTransaksiModel::findOrFail($id);

            //jika file baru diupload, ganti file lama
            if ($request->hasFile('bukti_transaksi')) {
                // Hapus file lama
                if ($buktiTransaksi->bukti_transaksi) {
                    Storage::disk('public')->delete($buktiTransaksi->bukti_transaksi);
                }
                // Simpan file baru
                $validated['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti', 'public');
            } else {
                // Gunakan gambar lama jika tidak diganti
                $validated['bukti_transaksi'] = $buktiTransaksi->bukti_transaksi;
            }

            // Isi data baru dan simpan
            $buktiTransaksi->fill($validated);
            // Set nominal menjadi null jika kosong
            $buktiTransaksi->nominal = $request->nominal ?: null;
            // Simpan ke database
            $buktiTransaksi->save();

            return redirect()->route('bukti_transaksi.index')->with('success', 'Bukti transaksi berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // menghapus bukti transaksi
    public function destroy($id)
    {
        try {
            $buktiTransaksi = BuktiTransaksiModel::findOrFail($id);

            // Hapus file dari storage
            if ($buktiTransaksi->bukti_transaksi && Storage::disk('public')->exists($buktiTransaksi->bukti_transaksi)) {
                Storage::disk('public')->delete($buktiTransaksi->bukti_transaksi);
            }

            $buktiTransaksi->delete();

            return redirect()->route('bukti_transaksi.index')->with('success', 'Bukti transaksi berhasil dihapus!');
        } catch (Exception $e) {
            return redirect()->route('bukti_transaksi.index')
                ->with('error', 'Gagal menghapus data:  ' . $e->getMessage());
        }
    }

    // menampilkan daftar konfirmasi transaksi dengan status pending
    public function konfirmasiIndex(Request $request)
    {
        $search = $request->input('search');

        $buktiTransaksi = BuktiTransaksiModel::where('status', 'Pending')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('donatur', 'like', "%{$search}%")
                        ->orWhere('tanggal_infak', 'like', "%{$search}%")
                        ->orWhere('jenis_infak', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('metode', 'like', "%{$search}%")
                        ->orWhere('barang', 'like', "%{$search}%")
                        ->orWhere('nominal', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('nama', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('bukti_transaksi.konfirmasi_transaksi', compact('buktiTransaksi', 'search'));
    }

    // verifikasi data bukti transaksi, kirim kuitansi dan notif WA
    public function verifikasi($id)
    {
        try {
            // ambil data bukti transaksi dan relasi user
            $bukti = BuktiTransaksiModel::with('user')->findOrFail($id);
            // ubah status menjadi terverifikasi
            $bukti->status = 'Terverifikasi';
            $bukti->save();

            //simpan ke tabel infak masuk
            $infakMasuk = InfakMasukModel::create([
                'id_bukti_transaksi' => $bukti->id,
                'tanggal_konfirmasi' => Carbon::now()->toDateString(),
            ]);

            // buat kuitansi pdf dengan DOMPDF
            $infak = $infakMasuk;

            // ✅ ambil data pejabat yang sesuai tanggal konfirmasi
            $pejabatData = PejabatService::getPejabatUntukKuitansi($infakMasuk->tanggal_konfirmasi);
            $ketua = $pejabatData['ketua'];
            $bendahara = $pejabatData['bendahara'];

            $pdf = Pdf::loadView('infak_masuk.kuitansi', compact('infak', 'ketua', 'bendahara'))
                ->setPaper('a4', 'landscape');

            // nama file kuitansi tanpa karakter khusus
            $namaDonatur = preg_replace('/[^\w\s-]/', '', $infakMasuk->buktiTransaksi->donatur);
            $namaFile = 'Kuitansi Infak - ' . $namaDonatur . '.pdf';

            // Simpan file kuitansi pdf ke folder public/storage/kuitansi
            $path = public_path('storage/kuitansi/' . $namaFile);
            if (!file_exists(public_path('storage/kuitansi'))) {
                mkdir(public_path('storage/kuitansi'), 0755, true);
            }
            $pdf->save($path);

            // Buat URL publik untuk akses kuitansi
            $urlKuitansi = asset('storage/kuitansi/' . rawurlencode($namaFile));

            // Kirim notifikasi WA
            $this->sendVerificationNotifications($bukti, $infakMasuk, $urlKuitansi);

            return redirect()->back()->with('success', 'Transaksi berhasil diverifikasi dan notifikasi WhatsApp telah dikirim.');
        } catch (Exception $e) {
            Log::error('Verifikasi error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memverifikasi transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi verifikasi via WhatsApp
     */
    private function sendVerificationNotifications($bukti, $infakMasuk, $urlKuitansi)
    {
        $client = new Client();

        // Kirim WA ke donatur
        if ($bukti->nomor_telepon) {
            $message = $this->buildDonorMessage($bukti, $infakMasuk, $urlKuitansi);
            $this->sendWhatsAppMessage($client, $bukti->nomor_telepon, $message);
        }

        // Kirim WA ke user yang input bukti transaksi
        if ($bukti->user && $bukti->user->nomor_telepon) {
            $msgUser = $this->buildUserMessage($bukti);
            $this->sendWhatsAppMessage($client, $bukti->user->nomor_telepon, $msgUser);
        }
    }

    /**
     * Build message untuk donatur
     */
    private function buildDonorMessage($bukti, $infakMasuk, $urlKuitansi)
    {
        $message = "*Terima kasih, transaksi infak Anda telah diverifikasi.*\n\n";
        $message .= "*Nama:* {$bukti->donatur}\n";
        $message .= "*Kategori:* {$bukti->kategori}\n";
        $message .= "*Alamat:* {$bukti->alamat}\n";
        $message .= "*Infak:* ";

        if (!empty($bukti->nominal)) {
            $message .= "Rp " . number_format($bukti->nominal, 0, ',', '.');
        } elseif (!empty($bukti->barang)) {
            $message .= $bukti->barang;
        } else {
            $message .= "-";
        }

        $message .= "\n";
        $message .= "*Tanggal Infak:* " . Carbon::parse($bukti->tanggal_infak)->format('d-m-Y') . "\n";
        $message .= "*Tanggal Konfirmasi:* " . Carbon::parse($infakMasuk->tanggal_konfirmasi)->format('d-m-Y') . "\n";
        $message .= "*Kuitansi:* " . $urlKuitansi . "\n\n";
        $message .= "Semoga Allah membalas kebaikan Anda. Jazakumullahu khairan.\n\n";
        $message .= "_Pesan ini dikirim secara resmi oleh Masjid Jami' Al Munawwarah._";

        return $message;
    }

    /**
     * Build message untuk user
     */
    private function buildUserMessage($bukti)
    {
        $msgUser = "*KONFIRMASI INFAK TERVERIFIKASI* \n\n";
        $msgUser .= "Halo {$bukti->user->nama},\n";
        $msgUser .= "Transaksi Infak yang Anda masukkan pada: \n";
        $msgUser .= "*Tanggal Infak:* " . Carbon::parse($bukti->tanggal_infak)->format('d-m-Y') . "\n";
        $msgUser .= "*Nama:* {$bukti->donatur}\n";
        $msgUser .= "*Kategori:* {$bukti->kategori}\n";
        $msgUser .= "*Alamat:* {$bukti->alamat}\n";
        $msgUser .= "*Infak:* ";

        if (!empty($bukti->nominal)) {
            $msgUser .= "Rp " . number_format($bukti->nominal, 0, ',', '.');
        } elseif (!empty($bukti->barang)) {
            $msgUser .= $bukti->barang;
        } else {
            $msgUser .= "-";
        }

        $msgUser .= "\n\n";
        $msgUser .= "Transaksi infak sudah *TERVERIFIKASI*\n";
        $msgUser .= "Cek pemasukan infak untuk melihat kuitansi.\n";

        return $msgUser;
    }

    /**
     * Kirim pesan WhatsApp
     */
    private function sendWhatsAppMessage($client, $phoneNumber, $message)
    {
        try {
            $client->post('https://api.fonnte.com/send', [
                'headers' => [
                    'Authorization' => env('FONNTE_TOKEN'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'target' => $phoneNumber,
                    'message' => $message,
                ],
            ]);
        } catch (Exception $e) {
            Log::error('WhatsApp sending error: ' . $e->getMessage());
        }
    }

    // menolak transaksi
    public function tolak($id)
    {
        try {
            $transaksi = BuktiTransaksiModel::with('user')->findOrFail($id);
            $transaksi->status = 'Ditolak';
            $transaksi->save();

            // Kirim notifikasi penolakan
            if ($transaksi->user && $transaksi->user->nomor_telepon) {
                $msgUser = $this->buildRejectionMessage($transaksi);
                $client = new Client();
                $this->sendWhatsAppMessage($client, $transaksi->user->nomor_telepon, $msgUser);
            }

            return redirect()->back()->with('success', 'Transaksi ditolak dan notifikasi WhatsApp telah dikirim.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Build message untuk penolakan
     */
    private function buildRejectionMessage($transaksi)
    {
        $msgUser = "*KONFIRMASI INFAK DITOLAK* \n\n";
        $msgUser .= "Halo {$transaksi->user->nama},\n";
        $msgUser .= "Transaksi Infak yang Anda masukkan pada: \n";
        $msgUser .= "*Tanggal Infak:* " . Carbon::parse($transaksi->tanggal_infak)->format('d-m-Y') . "\n";
        $msgUser .= "*Nama:* {$transaksi->donatur}\n";
        $msgUser .= "*Kategori:* {$transaksi->kategori}\n";
        $msgUser .= "*Alamat:* {$transaksi->alamat}\n";
        $msgUser .= "*Infak:* ";

        if (!empty($transaksi->nominal)) {
            $msgUser .= "Rp " . number_format($transaksi->nominal, 0, ',', '.');
        } elseif (!empty($transaksi->barang)) {
            $msgUser .= $transaksi->barang;
        } else {
            $msgUser .= "-";
        }

        $msgUser .= "\n\n";
        $msgUser .= "Transaksi infak *DITOLAK*\n";
        $msgUser .= "Cek kembali data atau hubungi bendahara.\n";

        return $msgUser;
    }

    /**
     * Bersihkan file temporary yang sudah lama
     */
    public function cleanTempFiles()
    {
        $tempFiles = Storage::disk('public')->files('temp_bukti');
        foreach ($tempFiles as $file) {
            if (Storage::disk('public')->lastModified($file) < now()->subHour()->timestamp) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
