<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pejabat_masjid', function (Blueprint $table) {
            $table->id();
            $table->enum('jabatan', [
                'ketua_takmir',
                'bendahara_takmir',
                'ketua_pembangunan',
                'bendahara_pembangunan',
            ]);

            $table->string('nama');
            $table->string('foto_ttd')->nullable(); // path file ttd (misal: storage/ttd_...)
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            $table->boolean('aktif')->default(true); // true = masih menjabat

            // Foreign key ke users (yang melakukan CRUD)
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');

            $table->timestamps();

            // Index untuk performa pencarian
            $table->index(['jabatan', 'aktif']);
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_masjid');
    }
};
