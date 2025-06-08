<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiTransaksiModel extends Model
{
    use HasFactory;

    protected $table = 'bukti_transaksi';

    protected $fillable = [
        'id_users',
        'donatur',
        'alamat',
        'nomor_telepon',
        'tanggal_infak',
        'kategori',
        'sumber',
        'jenis_infak',
        'nominal',
        'barang',
        'metode',
        'bukti_transaksi',
        'keterangan',
        'status'
    ];

    public function infakMasuk()
    {
        return $this->hasOne(InfakMasukModel::class, 'id_bukti_transaksi');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'id_users')->withTrashed();
    }

}
