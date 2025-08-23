<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
      protected $fillable = ['user_id', 'tanggal', 'kegiatan', 'langkah_kerja', 'hasil_akhir', 'file_gambar'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'user_id', 'user_id');
    }
}
