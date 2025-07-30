<?php

namespace App\Models;
use illuminate\Database\Eloquent\Model;

class Pendaftar extends Model{
    protected $fillale = [
        'nama_lengkap','nis_nim','sekolah','jurusan','matkul_pendukung',
        'tujuan_magang','alamat','no_hp','tanggal_mulai','tanggal_selesai',
        'surat_pengantar','kartu_pelajar','cv','sertifikat','status','alasan_status'
    ];
}

