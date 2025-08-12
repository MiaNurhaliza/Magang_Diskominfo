<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    
    use HasFactory;
    protected $fillable = ['user_id', 'tanggal', 'pagi', 'siang', 'sore'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function biodata()
    {
    return $this->belongsTo(Biodata::class, 'biodata_id');
    }
    
}
