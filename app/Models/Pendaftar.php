<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model{
    protected $fillable = [
        'user_id','nama_lengkap','nis_nim','sekolah','jurusan','matkul_pendukung',
        'tujuan_magang','alamat','no_hp','tanggal_mulai','tanggal_selesai',
        'surat_pengantar','kartu_pelajar','cv','sertifikat','status','alasan'
    ];

    

    public function user()
{
    return $this->belongsTo(User::class);
}



}

