<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_recipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('qty_per_unit', 12, 3); // kebutuhan bahan per 1 unit produk
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->unique(['product_id', 'material_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('product_recipes');
    }
};
