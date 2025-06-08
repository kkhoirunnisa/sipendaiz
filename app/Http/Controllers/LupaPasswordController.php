<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LupaPasswordController extends Controller
{
    public function indexTelepon()
    {
        return view('login.index_nomor_telepon');
    }

    public function indexOtp()
    {
        return view('login.index_kodeotp');
    }

    public function kirimOtp(Request $request)
    {
        $nomor_telepon = $request->telepon;
        $kode_otp = mt_rand(100000, 999999);

        $user = UserModel::where("nomor_telepon", $nomor_telepon)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['telepon' => 'Nomor telepon tidak terdaftar.']);
        }
        $user->kode_otp = $kode_otp;
        $user->save();

        $message = "*Reset Password SIPENDAIZ*\n";
        $message .= "Kode OTP Anda: {$kode_otp}\n";
        $message .= "Jangan bagikan kode ini kepada siapa pun.";

        $client = new Client();

        $response = $client->post('https://api.fonnte.com/send', [
            'headers' => [
                'Authorization' => env('FONNTE_TOKEN'),
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'target' => $nomor_telepon,
                'message' => $message,
            ],
        ]);

        return redirect('/otp')->with(['id_user' => $user->id, 'telepon' => $nomor_telepon]);
    }

    public function verifikasiOtp(Request $request)
    {
        $kodeOtp = $request->otp;
        $user = UserModel::find($request->id_user);
        if ($kodeOtp == $user->kode_otp) {
            return redirect('/password')->with(['id_user' => $user->id]);
        } else {
            abort(401);
        }
    }

    public function indexGantiPassword(Request $request)
    {
        $id_user = $request->session()->get('id_user'); // ambil dari session
        if (!$id_user) {
            // Kalau tidak ada id_user di session, redirect ke form telepon lagi (atau halaman error)
            return redirect('/lupa-password');
        }
        return view('login.index_ganti_password', compact('id_user'));
    }


    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Password dan konfirmasi harus sama.',
        ]);

        $user = UserModel::find($request->id_user);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/login')->with('success', 'Password berhasil diubah.');
    }


    public function kirimUlangOtp(Request $request)
    {
        try {
            if (!$request->has('id_user') || !$request->id_user) {
                return back()->withErrors(['error' => 'ID user tidak tersedia']);
            }

            $user = UserModel::find($request->id_user);

            if (!$user) {
                return back()->withErrors(['error' => 'Pengguna tidak ditemukan']);
            }

            $kode_otp = mt_rand(100000, 999999);
            $user->kode_otp = $kode_otp;
            $user->save();

            $message = "*Reset Password SIPENDAIZ*\n";
            $message .= "Kode OTP Anda: {$kode_otp}\n";
            $message .= "Jangan bagikan kode ini kepada siapa pun.";

            $client = new Client();
            $client->post('https://api.fonnte.com/send', [
                'headers' => [
                    'Authorization' => env('FONNTE_TOKEN'),
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'target' => $user->nomor_telepon,
                    'message' => $message,
                ],
            ]);

            return back()->with(['id_user' => $user->id, 'telepon' => $user->nomor_telepon]);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim ulang OTP: ' . $e->getMessage()]);
        }
    }
}
