<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Biodata extends Model
{
    protected $table = 'biodatas';
    
   public function absensis()
{
    return $this->hasMany(Absensi::class, 'biodata_id');
}

public function absensi()
{
    return $this->hasMany(Absensi::class, 'biodata_id');
}

public function logbook()
{
    return $this->hasMany(Logbook::class, 'user_id', 'user_id');
}

public function logbooks()
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

    public function laporanAkhir()
    {
        return $this->hasOne(LaporanAkhir::class, 'user_id', 'user_id');
    }
    public function dokumen()
    {
    return $this->hasOne(Dokumen::class, 'user_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsto(User::class);
    }

    public function dashboard()
{
    $peserta = auth()->user(); // jika peserta login
    return view('frontend.dashboard', compact('peserta'));
}
protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

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

    /**
     * Relasi many-to-many dengan pembimbing
     */
    public function pembimbings(): BelongsToMany
    {
        return $this->belongsToMany(Pembimbing::class, 'pembimbing_mahasiswa', 'biodata_id', 'pembimbing_id')
                    ->withPivot(['tanggal_mulai', 'tanggal_selesai', 'status_bimbingan'])
                    ->withTimestamps();
    }


}
