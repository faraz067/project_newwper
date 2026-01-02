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
    Schema::table('users', function (Blueprint $table) {
        // $table->string('avatar')->nullable()->after('email'); // <--- INI DI-KOMENTARI ATAU DIHAPUS
        
        // Cek dulu apakah kolom address sudah ada atau belum, biar aman
        if (!Schema::hasColumn('users', 'address')) {
            $table->text('address')->nullable()->after('email'); 
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
