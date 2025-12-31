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
            $table->string('name');
            $table->integer('price'); // sesuai dengan model Court terbaru
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
