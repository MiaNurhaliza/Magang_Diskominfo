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
        Schema::create('laporan_triwulans', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('quarter'); // 1, 2, 3 untuk triwulan
            $table->string('periode'); // Q1 2024, Q2 2024, dst
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('total_mahasiswa');
            $table->text('ringkasan')->nullable();
            $table->json('data_mahasiswa'); // JSON data mahasiswa dan evaluasi
            $table->string('file_pdf')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['tahun', 'quarter']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_triwulans');
    }
};
