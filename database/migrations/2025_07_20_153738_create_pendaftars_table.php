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
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nis_nim')->nullable();
            $table->string('sekolah');
            $table->string('jurusan');
            $table->string('matkul_pendukung');
            $table->string('tujuan_magang');
            $table->text('alamat');
            $table->string('no_hp');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('surat_pengantar')->nullable();
            $table->string('kartu_pelajar')->nullable();
            $table->string('cv')->nullable();
            $table->string('sertifikat')->nullable();
            $table->enum('status',['diproses','diterima','ditolak'])->default('diproses');
            $table->text('alasan_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
