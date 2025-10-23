<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('material_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('material_id')->constrained('materials')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['in','out','adjust']);
            $table->decimal('qty', 12, 3);
            $table->string('note', 191)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->engine = 'InnoDB';
            $table->index(['material_id', 'type']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('material_stocks');
    }
};
