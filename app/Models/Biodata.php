<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    protected $table = 'biodatas';
    
   public function absensis()
{
    return $this->hasMany(Absensi::class, 'biodata_id');
}

public function logbook()
{
    return $this->hasMany(Logbook::class, 'user_id', 'user_id');
}

    public function status()
    {
        return $this->hasOne(Status::class);
    }
    public function laporan_akhir()
    {
        return $this->hasOne(Laporan_akhir::class, 'user_id', 'user_id');
    }
    public function dokumen()
    {
    return $this->hasOne(Dokumen::class, 'user_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsto(User::class);
    }

    public function pendaftaran()
{
    return $this->hasOne(Pendaftaran::class, 'user_id', 'user_id');
}

    protected $fillable = [
    'user_id',
    'nama_lengkap',
    'nis_nim',
    'asal_sekolah',
    'jurusan',
    'matkul_pendukung',
    'tujuan_magang',
    'nama_pembimbing',
    'alamat',
    'no_hp',
    'tanggal_mulai',
    'tanggal_selesai',
    'surat_pengantar',
        'cv',
        'kartu_pelajar',
        'sertifikat',
        'status',
        'alasan_status',
];


}
