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
        Schema::table('absensis', function (Blueprint $table) {
        $table->unsignedBigInteger('biodata_id')->nullable();

        $table->foreign('biodata_id')->references('id')->on('biodatas')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('absensis', function (Blueprint $table) {
        $table->dropForeign(['biodata_id']);
        $table->dropColumn('biodata_id');
    });

    }
};
