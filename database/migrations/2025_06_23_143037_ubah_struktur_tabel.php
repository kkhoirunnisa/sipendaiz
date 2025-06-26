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
         Schema::table('mustahik', function (Blueprint $table) {
            $table->string('alamat', 100)->change(); // dari 50 ke 100 karakter
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mustahik', function (Blueprint $table) {
            $table->string('alamat', 50)->change(); // kembalikan ke 50
        });
    }
};
