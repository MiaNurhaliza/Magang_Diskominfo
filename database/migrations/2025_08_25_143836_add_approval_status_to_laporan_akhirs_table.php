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
        Schema::table('laporan_akhirs', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'revision'])->default('pending');
            $table->text('revision_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_akhirs', function (Blueprint $table) {
            $table->dropColumn(['status', 'revision_note', 'approved_at', 'approved_by']);
        });
    }
};
