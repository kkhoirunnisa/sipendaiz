<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfakMasukModel extends Model
{
    use HasFactory;

    protected $table = 'infak_masuk';

    protected $fillable = [
        'id_bukti_transaksi', 'tanggal_konfirmasi'
    ];

    public function buktiTransaksi()
    {
        return $this->belongsTo(BuktiTransaksiModel::class, 'id_bukti_transaksi');
    }
}
