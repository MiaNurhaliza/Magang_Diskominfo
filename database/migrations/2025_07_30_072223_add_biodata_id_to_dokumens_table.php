<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom biodata_id ke tabel dokumens.
     */
    public function up(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->foreignId('biodata_id')->nullable()->constrained('biodatas')->onDelete('cascade');
        });
    }

    /**
     * Hapus kolom biodata_id jika rollback.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropForeign(['biodata_id']);
            $table->dropColumn('biodata_id');
        });
    }
};
