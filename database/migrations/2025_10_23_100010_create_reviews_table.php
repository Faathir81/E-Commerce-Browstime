<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('rating'); // 1..5
            $table->string('comment', 255)->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->unique(['user_id','product_id','order_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};
