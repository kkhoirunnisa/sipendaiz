<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZakatKeluarModel extends Model
{
    use HasFactory;

    protected $table = 'zakat_keluar';

    protected $fillable = [
        'id_users',
        'id_mustahik',
        'tanggal',
        'jenis_zakat',
        'bentuk_zakat',
        'nominal',
        'jumlah',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_users')->withTrashed();
    }

    public function mustahik()
    {
        return $this->belongsTo(MustahikModel::class, 'id_mustahik')->withTrashed();
    }



}
