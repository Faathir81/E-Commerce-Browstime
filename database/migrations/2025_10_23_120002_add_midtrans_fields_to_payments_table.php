<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Identitas gateway
            $table->enum('gateway', ['manual','midtrans'])->default('manual')->after('order_id');
            $table->char('currency', 3)->default('IDR')->after('amount');

            // Midtrans metadata umum
            $table->string('gateway_order_id', 64)->nullable()->after('gateway'); // order_id yang dikirim ke Midtrans
            $table->string('transaction_id', 64)->nullable()->after('gateway_order_id'); // transaction_id Midtrans
            $table->string('channel', 50)->nullable()->after('status'); // e.g. bank/QRIS
            $table->dateTime('paid_at')->nullable()->after('channel');

            // Detail tambahan (opsional)
            $table->string('va_number', 50)->nullable()->after('paid_at');
            $table->text('qr_string')->nullable()->after('va_number'); // jika QRIS

            // Simpan payload callback/charge agar mudah debug
            $table->json('payload')->nullable()->after('qr_string');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'gateway',
                'currency',
                'gateway_order_id',
                'transaction_id',
                'channel',
                'paid_at',
                'va_number',
                'qr_string',
                'payload',
            ]);
        });
    }
};
