<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    protected $table = 'biodatas';
    
   public function absensi()
{
    return $this->hasMany(Absensi::class, 'user_id', 'user_id');
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
    protected $fillable = [
    'nama_lengkap',
    'asal_sekolah',
    'jurusan',
    'tanggal_mulai',
    'tanggal_selesai',
    // tambahkan kolom lain jika ada yang ikut di-update
];


}
