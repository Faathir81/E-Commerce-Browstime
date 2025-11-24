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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan_bakus')->cascadeOnDelete();

            $table->enum('jenis_mutasi', [
                'pemakaian_produksi',
                'stok_masuk',
                'stok_rusak',
                'stok_expired',
                'penyesuaian',
            ]);

            $table->decimal('qty', 12, 2);
            $table->decimal('stok_awal', 12, 2);
            $table->decimal('stok_akhir', 12, 2);

            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
