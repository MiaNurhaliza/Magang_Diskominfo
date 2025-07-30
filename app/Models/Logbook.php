<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
      protected $fillable = ['user_id', 'tanggal', 'aktivitas', 'deskripsi'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
