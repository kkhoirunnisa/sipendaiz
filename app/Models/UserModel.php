<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable,SoftDeletes;

    protected $table = "users";

    protected $fillable = [
        'role',
        'nama',
        'username',
        'password',
        'nomor_telepon',
        'kode_otp',
    ];

    public function infakKeluar()
    {
        return $this->hasMany(InfakKeluarModel::class, 'id_users');
    }

    public function zakatMasuk()
    {
        return $this->hasMany(ZakatMasukModel::class, 'id_users');
    }

    public function zakatKeluar()
    {
        return $this->hasMany(ZakatKeluarModel::class, 'id_users');
    }

    public function buktiTransaksi()
    {
        return $this->hasMany(BuktiTransaksiModel::class, 'id_users');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
