<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function index()
    {
        return view('login.index_login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $infoLogin = [
                'username' => $request->username,
                'password' => $request->password,
            ];

            if (Auth::attempt($infoLogin)) {
                session()->flash('success', 'Anda berhasil login!');
                return redirect('/dashboard');
                exit();
            }
            // Jika gagal login
            return back()->withErrors(['login' => 'Username atau password salah'])->withInput();
        } catch (Exception $e) {
            // Tangkap semua error lainnya
            return back()->withErrors(['login' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function dashboard()
    {
        if (!session()->has('user')) {
            return redirect()->route('login.index_login');
        }

        return view('dashboard.index_dashboard');
    }

    public function logout()
    {
        session()->flush(); // Menghapus semua session
        session()->flash('success', 'Anda berhasil logout!');
        return redirect()->route('login.index_login');
    }


    // PENGATURAN PROFIL
    public function profil()
    {
        $user = Auth::user();

        return view('profil.index_profil', [
            'profil' => $user
        ]);
    }

    public function editProfil()
    {

        $user = Auth::user();
        return view('profil.edit_profil', [
            'profil' => $user
        ]);
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'current_password' => 'nullable',
            'new_password' => 'nullable|confirmed',
            'nomor_telepon' => 'nullable|numeric',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok dengan password baru.',
        ]);

        try {
            $user = UserModel::find(Auth::user()->id);

            $user->nama = $request->nama;
            $user->username = $request->username;
            $user->nomor_telepon = $request->nomor_telepon;

            if ($request->filled('new_password')) {
                // Validasi password lama (hash comparison)
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Password lama tidak sesuai'])->withInput();
                }

                // Simpan password baru (di-hash)
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            session(['user' => $user->username, 'nama' => $user->nama]);

            return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }
}
