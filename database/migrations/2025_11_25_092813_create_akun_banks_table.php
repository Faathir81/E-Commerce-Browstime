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
        Schema::create('akun_banks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');       // BCA, BNI, Mandiri
            $table->string('nama_pemilik');    // Nama Rekening
            $table->string('nomor_rekening');  // Nomor Rekening
            $table->boolean('aktif')->default(true);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_banks');
    }
};
