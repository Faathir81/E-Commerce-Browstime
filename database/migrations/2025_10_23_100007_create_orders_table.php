<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('code', 32)->unique();
            $table->enum('status', ['pending','awaiting_payment','paid','processing','shipped','delivered','cancelled'])->default('pending');

            // buyer & shipping (guest allowed)
            $table->string('buyer_name', 120);
            $table->string('buyer_phone', 32);
            $table->string('ship_address', 255);
            $table->string('ship_city', 120)->nullable();
            $table->string('ship_postal', 10)->nullable();

            $table->unsignedInteger('shipping_cost')->default(0);
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('grand_total');

            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->index(['status', 'user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
