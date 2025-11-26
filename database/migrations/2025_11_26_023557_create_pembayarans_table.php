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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            $table->enum('metode', ['transfer','qris','midtrans']);   // lihat Bab 5 & modul pembayaran
            $table->foreignId('akun_bank_id')->nullable()->constrained('akun_banks');
            $table->foreignId('qris_setting_id')->nullable()->constrained('qris_settings');
            $table->decimal('jumlah', 15, 2);
            $table->enum('status', ['pending','menunggu_verifikasi','valid','invalid'])
                ->default('pending');
            $table->string('bukti_bayar')->nullable();               // path file upload
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
