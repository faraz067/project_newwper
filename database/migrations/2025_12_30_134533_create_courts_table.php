<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courts', function (Blueprint $table) {
            // id: Unsigned, Auto Increment
            $table->id(); 

            // name: VARCHAR(255), Not Null
            $table->string('name', 255);

            // type: VARCHAR(255), Not Null
            $table->string('type', 255);

            // price_per_hour: Integer/Decimal (DataType=3 biasanya INT atau DECIMAL)
            // Menggunakan integer sesuai metadata LengthSet=10
            $table->integer('price_per_hour');

            // status: ENUM('tersedia', 'maintenance'), Default: tersedia
            $table->enum('status', ['tersedia', 'maintenance'])->default('tersedia');

            // photo: VARCHAR(255), Allow Null
            $table->string('photo', 255)->nullable();

            // created_at & updated_at: Allow Null
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};