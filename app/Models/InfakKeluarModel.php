<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfakKeluarModel extends Model
{
    use HasFactory;

    protected $table = 'infak_keluar';

    protected $fillable = [
        'id_users', 'tanggal', 'kategori', 'nominal', 'barang', 'keterangan', 'bukti_infak_keluar'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_users')->withTrashed();
    }
}
