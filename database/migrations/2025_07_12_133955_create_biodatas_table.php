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
      
    Schema::create('biodatas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('nama_lengkap');
        $table->string('nis_nim');
        $table->string('asal_sekolah');
        $table->string('jurusan');
        $table->string('matkul_pendukung')->nullable();
        $table->string('tujuan_magang')->nullable();
        $table->string('nama_pembimbing');
        $table->text('alamat');
        $table->string('no_hp');
        $table->date('tanggal_mulai');
        $table->date('tanggal_selesai');
        $table->string('status')->nullable();
        $table->text('alasan')->nullable();
        $table->string('surat_balasan')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodatas');
    }
};
