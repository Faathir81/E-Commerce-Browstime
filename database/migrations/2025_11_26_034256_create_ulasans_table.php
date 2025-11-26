<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasans', function (Blueprint $table) {
            $table->id();

            // siapa yang memberi ulasan
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();

            // ulasan hanya untuk item yang pernah dibeli
            $table->foreignId('detail_pesanan_id')->constrained('detail_pesanans')->cascadeOnDelete();

            $table->tinyInteger('rating'); // 1–5
            $table->text('komentar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasans');
    }
};
