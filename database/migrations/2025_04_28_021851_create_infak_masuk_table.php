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
        Schema::create('infak_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bukti_transaksi')->constrained('bukti_transaksi')->onDelete('cascade');
            $table->date('tanggal_konfirmasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infak_masuk');
    }
};
