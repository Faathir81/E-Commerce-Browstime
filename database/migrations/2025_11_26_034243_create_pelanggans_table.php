<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();

            // relasi opsional ke users (karena bisa guest)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('nama');
            $table->string('email')->nullable(); // guest bisa tidak punya email?
            $table->string('no_hp')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
