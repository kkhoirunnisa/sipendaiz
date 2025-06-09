<?php

use App\Http\Controllers\BuktiTransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfakKeluarController;
use App\Http\Controllers\InfakMasukController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LaporanInfakController;
use App\Http\Controllers\LaporanZakatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LupaPasswordController;
use App\Http\Controllers\MustahikController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZakatKeluarController;
use App\Http\Controllers\ZakatMasukController;
use Illuminate\Support\Facades\Route;

// LOGIN
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.index_login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// LANDING PAGE
Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');

//KODE OTP
Route::get('/lupa-password', [LupaPasswordController::class, 'indexTelepon'])->name('password.telepon');
Route::get('/otp', [LupaPasswordController::class, 'indexOtp'])->name('password.index_otp');
Route::post('/verifikasi-otp', [LupaPasswordController::class, 'verifikasiOtp'])->name('password.verifikasi_otp');
Route::post('/kirim-otp', [LupaPasswordController::class, 'kirimOtp'])->name('password.kirim_otp');
Route::post('/password/kirim-ulang-otp', [LupaPasswordController::class, 'kirimUlangOtp'])->name('password.kirim_ulang_otp');
Route::get('/password', [LupaPasswordController::class, 'indexGantiPassword'])->name('password.index_ganti_password');
Route::post('/password', [LupaPasswordController::class, 'GantiPassword'])->name('password.ganti_password');

//untuk percobaan
// Route::get('/preview-otp', function () {
//     return view('verifikasi-otp-preview');
// });

Route::middleware('auth')->group(function () {
    Route::middleware('role:Bendahara')->group(function () {
        // USER
        Route::get('user', [UserController::class, 'index'])->name('user.index'); //menampilkan
        Route::get('user/create', [UserController::class, 'create'])->name('user.create'); //form tambah
        Route::post('user', [UserController::class, 'store'])->name('user.store'); //menyimpan
        Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('user.edit'); //form edit
        Route::put('user/{id}', [UserController::class, 'update'])->name('user.update'); //update
        Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy'); //hapus

        // KONFIRMASI TRANSAKSI
        Route::get('/bukti-transaksi/konfirmasi', [BuktiTransaksiController::class, 'konfirmasiIndex'])->name('bukti_transaksi.konfirmasi');
        Route::post('/bukti-transaksi/konfirmasi/{id}/verifikasi', [BuktiTransaksiController::class, 'verifikasi'])->name('bukti_transaksi.verifikasi');
        Route::post('/bukti-transaksi/konfirmasi/{id}/tolak', [BuktiTransaksiController::class, 'tolak'])->name('bukti_transaksi.tolak');
    });

    Route::middleware('role:Petugas')->group(function () {
        // MUSTAHIK
        Route::get('mustahik', [MustahikController::class, 'index'])->name('mustahik.index'); //menampilkan
        Route::get('mustahik/create', [MustahikController::class, 'create'])->name('mustahik.create'); //form tambah
        Route::post('mustahik', [MustahikController::class, 'store'])->name('mustahik.store'); // menyimpan
        Route::get('mustahik/{id}/edit', [MustahikController::class, 'edit'])->name('mustahik.edit'); //form edit
        Route::put('mustahik/{id}', [MustahikController::class, 'update'])->name('mustahik.update'); //update
        Route::delete('mustahik/{id}', [MustahikController::class, 'destroy'])->name('mustahik.destroy'); //hapus
        Route::get('/mustahik/pdf', [MustahikController::class, 'exportPdf'])->name('mustahik.pdf'); //cetak
    });

    Route::middleware('role:Petugas,Bendahara')->group(function () {

        // ZAKAT MASUK
        Route::get('zakat-masuk', [ZakatMasukController::class, 'index'])->name('zakat_masuk.index'); //menampilkan
        Route::get('zakat-masuk/create', [ZakatMasukController::class, 'create'])->name('zakat_masuk.create'); //form tambah
        Route::post('zakat-masuk', [ZakatMasukController::class, 'store'])->name('zakat_masuk.store'); // menyimpan
        Route::get('zakat-masuk/{id}/edit', [ZakatMasukController::class, 'edit'])->name('zakat_masuk.edit'); //form edit
        Route::put('zakat-masuk/{id}', [ZakatMasukController::class, 'update'])->name('zakat_masuk.update'); //update
        Route::delete('zakat-masuk/{id}', [ZakatMasukController::class, 'destroy'])->name('zakat_masuk.destroy'); //hapus

        // ZAKAT KELUAR
        Route::get('zakat-keluar', [ZakatKeluarController::class, 'index'])->name('zakat_keluar.index'); // menampilkan
        Route::get('zakat-keluar/create', [ZakatKeluarController::class, 'create'])->name('zakat_keluar.create'); // form tambah
        Route::post('zakat-keluar', [ZakatKeluarController::class, 'store'])->name('zakat_keluar.store'); // menyimpan
        Route::get('zakat-keluar/{id}/edit', [ZakatKeluarController::class, 'edit'])->name('zakat_keluar.edit'); // form edit
        Route::put('zakat-keluar/{id}', [ZakatKeluarController::class, 'update'])->name('zakat_keluar.update'); // update
        Route::delete('zakat-keluar/{id}', [ZakatKeluarController::class, 'destroy'])->name('zakat_keluar.destroy'); // hapus

        // INFAK MASUK
        Route::get('/infak_masuk', [InfakMasukController::class, 'index'])->name('infak_masuk.index');
        // Route::post('/bukti-transaksi/verifikasi/{id}', [BuktiTransaksiController::class, 'verifikasi'])->name('bukti_transaksi.verifikasi');

        // INFAK KELUAR
        Route::get('/infak-keluar/{kategori}', [InfakKeluarController::class, 'index'])->name('infak_keluar.index'); //menampilkan
        // Route::get('infak-keluar', [InfakKeluarController::class, 'index'])->name('infak_keluar.index'); // menampilkan
        Route::get('infak-keluar/create/{kategori}', [InfakKeluarController::class, 'create'])->name('infak_keluar.create'); // form tambah
        Route::post('infak-keluar', [InfakKeluarController::class, 'store'])->name('infak_keluar.store'); // menyimpan
        Route::get('infak-keluar/{id}/edit', [InfakKeluarController::class, 'edit'])->name('infak_keluar.edit'); // form edit
        Route::put('infak-keluar/{id}', [InfakKeluarController::class, 'update'])->name('infak_keluar.update'); // update
        Route::delete('infak-keluar/{id}', [InfakKeluarController::class, 'destroy'])->name('infak_keluar.destroy'); // hapus

        // BUKTI TRANSAKSI
        Route::get('bukti-transaksi', [BuktiTransaksiController::class, 'index'])->name('bukti_transaksi.index'); // menampilkan semua bukti
        Route::get('bukti-transaksi/create', [BuktiTransaksiController::class, 'create'])->name('bukti_transaksi.create'); // form tambah
        Route::post('bukti-transaksi', [BuktiTransaksiController::class, 'store'])->name('bukti_transaksi.store'); // simpan data
        Route::get('bukti-transaksi/{id}/edit', [BuktiTransaksiController::class, 'edit'])->name('bukti_transaksi.edit'); // edit
        Route::put('bukti-transaksi/{id}', [BuktiTransaksiController::class, 'update'])->name('bukti_transaksi.update'); // update
        Route::delete('bukti-transaksi/{id}', [BuktiTransaksiController::class, 'destroy'])->name('bukti_transaksi.destroy'); // hapus

        // KUITANSI
        Route::get('/infak-masuk/kuitansi/{id}', [InfakMasukController::class, 'kuitansi'])->name('infak.kuitansi');
        Route::get('/infak-masuk/kuitansi/{id}/pdf', [InfakMasukController::class, 'cetakKuitansiPdf'])->name('infak.kuitansi.pdf');
    });

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // PROFIL
    // Route::get('profil', [ProfilController::class, 'index'])->name('profil.index'); 
    Route::get('/profil', [LoginController::class, 'profil'])->name('profil.index');
    Route::get('/profil/edit', [LoginController::class, 'editProfil'])->name('profil.edit');
    Route::put('/profil/update', [LoginController::class, 'updateProfil'])->name('profil.update');

    // LAPORAN ZAKAT
    Route::get('/zakat/laporan', [LaporanZakatController::class, 'index'])->name('laporan_zakat.index'); //menampilkan
    Route::get('/zakat/laporan/generate', [LaporanZakatController::class, 'generateReport'])->name('laporan_zakat.generate'); //menampilkan data
    Route::get('/zakat/laporan/download', [LaporanZakatController::class, 'downloadReport'])->name('laporan_zakat.download'); //unduh

    // LAPORAN INFAK
    Route::get('/infak/laporan', [LaporanInfakController::class, 'index'])->name('laporan_infak.index');
    Route::get('/infak/laporan/generate', [LaporanInfakController::class, 'generateReport'])->name('laporan_infak.generate');
    Route::get('/infak/laporan/download', [LaporanInfakController::class, 'downloadReport'])->name('laporan_infak.download');
});
