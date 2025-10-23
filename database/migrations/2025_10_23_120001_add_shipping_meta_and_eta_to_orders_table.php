<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Binderbyte metadata (optional tapi berguna untuk rekam jejak biaya)
            $table->string('shipping_courier', 20)->nullable()->after('shipping_cost');   // jne/jnt/pos
            $table->string('shipping_service', 50)->nullable()->after('shipping_courier'); // REG/YES/OKE/dst
            $table->string('shipping_etd', 50)->nullable()->after('shipping_service');     // "2-3 Hari"
            $table->unsignedInteger('weight_gram')->default(0)->after('shipping_etd');     // total berat kirim (gram)
            $table->unsignedInteger('origin_city_id')->nullable()->after('weight_gram');   // sesuai kode Binderbyte
            $table->unsignedInteger('destination_city_id')->nullable()->after('origin_city_id');

            // ETA produksi & pengantaran (untuk transparansi ke buyer)
            $table->dateTime('estimated_ready_at')->nullable()->after('updated_at');
            $table->dateTime('estimated_delivery_at')->nullable()->after('estimated_ready_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_courier',
                'shipping_service',
                'shipping_etd',
                'weight_gram',
                'origin_city_id',
                'destination_city_id',
                'estimated_ready_at',
                'estimated_delivery_at',
            ]);
        });
    }
};
