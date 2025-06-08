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
        Schema::create('infak_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('kategori', 17);
            $table->decimal('nominal', 15, 2);
            $table->string('barang', 20);
            $table->string('keterangan', 50);
            $table->string('bukti_infak_keluar', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infak_keluar');
    }
};
