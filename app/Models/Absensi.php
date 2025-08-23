<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'biodata_id', 
        'tanggal', 
        'pagi', 
        'siang', 
        'sore',
        'waktu_pagi',
        'waktu_siang', 
        'waktu_sore',
        'keterangan',
        'file_keterangan',
        'izin_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'biodata_id');
    }

    public function izin()
    {
        return $this->belongsTo(Izin::class);
    }
}
