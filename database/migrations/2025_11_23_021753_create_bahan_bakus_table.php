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
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');

            // Relasi satuan
            $table->foreignId('satuan_id')->constrained('satuans')->restrictOnDelete();

            // Stok awal dan minimum
            $table->decimal('stok_awal', 10, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(0);

            // Optional deskripsi
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
