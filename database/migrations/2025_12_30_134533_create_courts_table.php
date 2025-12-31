<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // nama lapangan
            $table->string('type');                 // jenis (futsal, badminton, dll)
            $table->integer('price_per_hour');
            $table->enum('status', ['tersedia', 'maintenance'])->default('tersedia');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
