<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Izin extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','jenis','tanggal','keterangan','bukti_file'];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }
}
