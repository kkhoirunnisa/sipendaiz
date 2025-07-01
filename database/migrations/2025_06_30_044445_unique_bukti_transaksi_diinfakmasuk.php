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
        // Tambahkan constraint UNIQUE di kolom id_bukti_transaksi
        Schema::table('infak_masuk', function (Blueprint $table) {
            $table->unique('id_bukti_transaksi', 'unique_id_bukti_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus constraint UNIQUE saat rollback
        Schema::table('infak_masuk', function (Blueprint $table) {
            $table->dropUnique('unique_id_bukti_transaksi');
        });
    }
};
