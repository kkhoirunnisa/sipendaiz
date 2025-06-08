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
        Schema::create('bukti_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->string('donatur', 50);
            $table->string('alamat', 30)->nullable();
            $table->string('nomor_telepon', 14)->nullable();
            $table->date('tanggal_infak');
            $table->string('kategori', 12); 
            $table->string('sumber', 11);   
            $table->string('jenis_infak', 6); 
            $table->decimal('nominal', 15, 2)->nullable();
            $table->string('barang', 20)->nullable();
            $table->string('metode', 17); 
            $table->string('bukti_transaksi', 255);
            $table->string('keterangan', 50);
            $table->string('status', 13)->default('Pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_transaksi');
    }
};
