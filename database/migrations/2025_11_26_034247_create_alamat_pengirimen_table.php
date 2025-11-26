<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alamat_pengirimans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();

            $table->string('nama_penerima');
            $table->string('no_hp');
            $table->string('alamat_lengkap');
            $table->string('kode_pos')->nullable();

            // bisa pakai tabel wilayah_pengiriman kamu
            $table->foreignId('wilayah_pengiriman_id')
                  ->nullable()
                  ->constrained('wilayah_pengiriman')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alamat_pengirimans');
    }
};
