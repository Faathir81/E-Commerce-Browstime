<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('method', ['transfer','qris','cash'])->default('transfer');
            $table->unsignedInteger('amount');
            $table->enum('status', ['pending','verified','failed'])->default('pending');
            $table->string('proof_url', 255)->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->index(['order_id', 'status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
