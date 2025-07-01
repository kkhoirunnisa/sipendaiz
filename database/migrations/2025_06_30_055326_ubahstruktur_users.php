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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->change(); // dari 10 ke 50 karakter
            $table->string('password', 60)->change(); // dari 255 ke 60 karakter
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 10)->change(); // kembalikan ke 10
             $table->string('password', 255)->change(); // kembalikan ke 255
        });
    }
};
