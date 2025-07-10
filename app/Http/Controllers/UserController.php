<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $user = UserModel::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        })
            ->orderBy('updated_at', 'desc')
            ->paginate(10); //menampilkan data 10 pada tabel

        return view('user.index_user', compact('user'));
    }

    public function create()
    {
        return view('user.tambah_user');
    }

    public function store(Request $request)
    {
        try {
            // Validasi data input dari form
            $validator = Validator::make($request->all(), [
                'role' => 'required|string|max:10',
                'nama' => 'required|string|max:50',
                'username' => 'required|max:10|unique:users,username',
                'password' => 'required|string',
                'nomor_telepon' => 'required|numeric|digits_between:10,14',
            ]);

            // Jika validasi gagal, redirect dengan session error
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorMessage = implode(', ', $errors);

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }

            // Simpan data pengelola baru ke database
            UserModel::create([
                'role' => $request->role,
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => Hash::make($request->password), // bcrypt secara default
                'nomor_telepon' => $request->nomor_telepon,
            ]);

            // Redirect ke halaman daftar pengelola dengan pesan sukses
            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    // Menampilkan form edit pengelola
    public function edit($id)
    {
        $user = UserModel::findOrFail($id);
        return view('user.edit_user', compact('user'));
    }

    // Menyimpan perubahan pengelola yang diedit
    public function update(Request $request, $id)
    {
        try {
            // Manual validation dengan custom error handling
            $validator = Validator::make($request->all(), [
                'role' => 'required|string|max:10',
                'nama' => 'required|string|max:50',
                // 'username' => 'required|max:10' . $id,
                'username' => 'required|max:10|unique:users,username',
                'nomor_telepon' => 'required|numeric|digits_between:10,14',
                'password' => 'nullable', // optional password
            ]);

            // Jika validasi gagal, redirect dengan session error
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorMessage = implode(', ', $errors);

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }

            $user = UserModel::findOrFail($id);

            $password = $request->filled('password')
                ? Hash::make($request->password)
                : $user->password;

            $user->update([
                'role' => $request->role,
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => $password,
                'nomor_telepon' => $request->nomor_telepon,
            ]);

            return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Menghapus data pengelola
    public function destroy($id)
    {
        try {
            // Mencari data pengelola yang akan dihapus
            $user = UserModel::findOrFail($id);

            // Hapus data pengelola
            $user->delete();

            // Redirect ke halaman daftar pengelola dengan pesan sukses
            return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->route('user.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
