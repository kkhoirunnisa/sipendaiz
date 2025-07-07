<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class PejabatMasjidModel extends Model
{
    use HasFactory;

    protected $table = 'pejabat_masjid';

    protected $fillable = [
        'jabatan',
        'nama',
        'foto_ttd',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
        'id_users', // siapa yang melakukan CRUD
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
    ];

    // relasi ke user (yang input data)
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_users');
    }

    // menyaring data pejabat yg aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // menyaring jabatan berdasarkan jabatan
    public function scopeJabatan($query, $jabatan)
    {
        return $query->where('jabatan', $jabatan);
    }

    // pejabat yang menjabat pada tanggal tertentu
    public static function getPejabatPadaTanggal($jabatan, $tanggal)
    {
        return self::where('jabatan', $jabatan)
            ->where('tanggal_mulai', '<=', $tanggal)
            ->where(function ($query) use ($tanggal) {
                $query->whereNull('tanggal_selesai')
                      ->orWhere('tanggal_selesai', '>=', $tanggal);
            })
            ->first();
    }

    // pejabat aktif saat ini
    public static function getPejabatAktif($jabatan)
    {
        return self::where('jabatan', $jabatan)
            ->where('aktif', true)
            ->whereNull('tanggal_selesai')
            ->latest('tanggal_mulai')
            ->first();
    }

    // nama lengkap (jika suatu hari gelar ditambahkan)
    // public function getNamaLengkapAttribute()
    // {
    //     return $this->nama; // tambahkan gelar jika ada di masa depan
    // }

    // uRL TTD
    // public function getFotoTtdUrlAttribute()
    // {
    //     return $this->foto_ttd ? asset('storage/' . $this->foto_ttd) : null;
    // }
}
