<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 120);
            $table->string('unit', 20); // gram, ml, pcs
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->unique(['name', 'unit']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('materials');
    }
};
