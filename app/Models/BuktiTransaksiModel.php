<?php

namespace App\Models;
// hasOne digunakan di model induk Model ini memiliki satu data dari model lain
// belongsTo digunakan di model anak Model ini dimiliki oleh satu data dari model lain
// hasMany digunakan di model induk Model ini memiliki banyak data dari model lain
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
