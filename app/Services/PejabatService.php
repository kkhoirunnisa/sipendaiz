<?php

namespace App\Services;

use App\Models\PejabatMasjidModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PejabatService
{
    /**
     * Ambil pejabat pembangunan untuk kuitansi berdasarkan tanggal konfirmasi
     */
    public static function getPejabatUntukKuitansi($tanggalKonfirmasi)
    {
        $tanggal = Carbon::parse($tanggalKonfirmasi);

        return [
            'ketua' => PejabatMasjidModel::getPejabatPadaTanggal('ketua_pembangunan', $tanggal),
            'bendahara' => PejabatMasjidModel::getPejabatPadaTanggal('bendahara_pembangunan', $tanggal),
        ];
    }

    /**
     * Ambil pejabat berdasarkan kategori infak dan tanggal (untuk laporan)
     */
    public static function getPejabatUntukLaporanInfak($kategori, $tanggalUnduh)
    {
        // Parsing tanggal unduh laporan sebagai acuan pengambilan pejabat
        $tanggal = Carbon::parse($tanggalUnduh);

        // Untuk kategori Takmir
        if (strtolower($kategori) === 'takmir') {
            return [
                'ketua' => PejabatMasjidModel::getPejabatPadaTanggal('ketua_takmir', $tanggal),
                'bendahara' => PejabatMasjidModel::getPejabatPadaTanggal('bendahara_takmir', $tanggal),
            ];
        }
        // Untuk kategori Pembangunan
        elseif (strtolower($kategori) === 'pembangunan') {
            return [
                'ketua' => PejabatMasjidModel::getPejabatPadaTanggal('ketua_pembangunan', $tanggal),
                'bendahara' => PejabatMasjidModel::getPejabatPadaTanggal('bendahara_pembangunan', $tanggal),
            ];
        }

        // Jika kategori tidak dikenali
        return [
            'ketua' => '-',
            'bendahara' => '-',
        ];
    }

    public static function getPejabatUntukLaporanZakat($tanggalUnduh)
    {
        // Parsing tanggal unduh laporan sebagai acuan pengambilan pejabat
        $tanggal = Carbon::parse($tanggalUnduh);

        // Ketua dan Bendahara untuk zakat sama dengan Takmir
        return [
            'ketua' => PejabatMasjidModel::getPejabatPadaTanggal('ketua_takmir', $tanggal),
            'bendahara' => PejabatMasjidModel::getPejabatPadaTanggal('bendahara_takmir', $tanggal),
        ];
    }


    /**
     * Ganti pejabat pada jabatan tertentu.
     */
    public static function gantiPejabat($jabatan, $namaBaru, $fotoTtd, $tanggalMulai)
    {
        $tanggalMulai = Carbon::parse($tanggalMulai);

        // Nonaktifkan pejabat lama (jika ada)
        $pejabatLama = PejabatMasjidModel::getPejabatAktif($jabatan);
        if ($pejabatLama) {
            $pejabatLama->update([
                'aktif' => false,
                'tanggal_selesai' => $tanggalMulai->copy()->subDay(),
            ]);
        }

        // Tambahkan pejabat baru
        return PejabatMasjidModel::create([
            'jabatan' => $jabatan,
            'nama' => $namaBaru,
            'foto_ttd' => $fotoTtd,
            'tanggal_mulai' => $tanggalMulai,
            'aktif' => true,
            'id_users' => Auth::id(),
        ]);
    }
}
