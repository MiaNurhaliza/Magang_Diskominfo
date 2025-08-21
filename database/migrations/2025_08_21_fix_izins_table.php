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
        Schema::table('izins', function (Blueprint $table) {
            // Change enum to string to allow more flexible values
            $table->string('jenis', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izins', function (Blueprint $table) {
            $table->enum('jenis', ['Sakit', 'Izin Pribadi', 'Lainnya'])->change();
        });
    }
};
