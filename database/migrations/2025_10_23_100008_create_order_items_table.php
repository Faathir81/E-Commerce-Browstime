<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('qty');
            $table->unsignedInteger('price_frozen'); // harga copy saat checkout
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->index(['order_id', 'product_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
