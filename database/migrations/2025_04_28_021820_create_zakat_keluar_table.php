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
        Schema::create('zakat_keluar', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_mustahik')->constrained('mustahik')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jenis_zakat', 10);     
            $table->string('bentuk_zakat', 10);    
            $table->decimal('nominal', 15, 2)->nullable();
            $table->decimal('jumlah', 5, 2)->nullable();
            $table->string('keterangan', 255); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_keluar');
    }
};
