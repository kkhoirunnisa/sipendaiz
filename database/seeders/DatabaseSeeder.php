<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserModel;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       UserModel::create([
            'nama' => 'Beni Hermanto',
            'username' => 'beni',
            'password' => bcrypt('beni123'),
            'role' => 'Bendahara',
            'nomor_telepon' => '082138174677'
        ]);
        UserModel::create([
            'nama' => 'Syarif Thoyib',
            'username' => 'syarif',
            'password' => bcrypt('syarif123'),
            'role' => 'Petugas',
            'nomor_telepon' => '0895379174235'
        ]);
         UserModel::create([
            'nama' => 'Agus Tisngadi',
            'username' => 'agus',
            'password' => bcrypt('agus123'),
            'role' => 'Ketua',
            'nomor_telepon' => '0895379174235'
        ]);
    }
}
