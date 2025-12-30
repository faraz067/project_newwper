<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->integer('extra_charge')->default(0); // Kolom biaya tambahan
        $table->string('note')->nullable();          // Kolom catatan (misal: "Beli Air")
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['extra_charge', 'note']);
    });
}
};
