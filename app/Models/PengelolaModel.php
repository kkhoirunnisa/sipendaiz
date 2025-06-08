<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengelolaModel extends Model
{
    use HasFactory;
    protected $table = 'pengelola';

    protected $fillable = [
        'role',
        'nama',
        'username',
        'password',
        'nomor_telepon',
    ];

    public function infakKeluar()
    {
        return $this->hasMany(InfakKeluarModel::class, 'id_pengelola');
    }

    public function zakatMasuk()
    {
        return $this->hasMany(ZakatMasukModel::class, 'id_pengelola');
    }

    public function zakatKeluar()
    {
        return $this->hasMany(ZakatKeluarModel::class, 'id_pengelola');
    }

    public function buktiTransaksi()
    {
        return $this->hasMany(BuktiTransaksiModel::class, 'id_pengelola');
    }
}