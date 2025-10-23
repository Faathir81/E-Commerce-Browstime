<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('url', 255);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }
    public function down(): void {
        Schema::dropIfExists('product_images');
    }
};
