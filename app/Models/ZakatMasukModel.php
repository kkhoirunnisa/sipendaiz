<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZakatMasukModel extends Model
{
    use HasFactory;

    protected $table = 'zakat_masuk';

    protected $fillable = [
        'id_users',
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

    
}
