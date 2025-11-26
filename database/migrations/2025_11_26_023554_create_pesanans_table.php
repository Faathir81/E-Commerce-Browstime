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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();                 // kode pesanan
            $table->foreignId('user_id')->nullable();         // customer login
            $table->string('guest_email')->nullable();        // kalau guest
            $table->foreignId('wilayah_pengiriman_id');       // atau supported_area_id
            $table->decimal('subtotal', 15, 2);
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->enum('status', [
                'pending',       // menunggu pembayaran
                'paid',          // terbayar
                'produksi',      // sedang diproduksi
                'dikirim',       // dikirim
                'selesai',       // selesai
                'batal',         // optional
            ]);
            $table->string('no_resi')->nullable();
            $table->dateTime('eta')->nullable();             // estimasi sampai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
