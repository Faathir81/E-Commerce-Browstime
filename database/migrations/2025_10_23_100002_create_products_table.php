<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->unsignedInteger('price'); // rupiah
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
