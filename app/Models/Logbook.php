<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'kegiatan', 'langkah_kerja','hasil_akhir'];

    public function user()
    {
        return $this->belongsTo(Userser::class);
    }
}
