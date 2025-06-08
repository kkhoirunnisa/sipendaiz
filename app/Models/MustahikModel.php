<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MustahikModel extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'mustahik';

    protected $fillable = [
        'nama', 'alamat', 'kategori'
    ];

    public function zakatKeluar()
    {
        return $this->hasMany(ZakatKeluarModel::class, 'id_mustahik');
    }
}
