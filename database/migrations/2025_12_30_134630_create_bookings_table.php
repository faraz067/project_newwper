<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_bookings_table.php
public function up()
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke User
        $table->foreignId('court_id')->constrained()->onDelete('cascade'); // Relasi ke Lapangan
        $table->dateTime('start_time'); // Misal: 2023-10-25 14:00:00
        $table->dateTime('end_time');   // Misal: 2023-10-25 16:00:00
        $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
        $table->integer('total_price');
        $table->string('payment_proof')->nullable(); // Bukti transfer
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
