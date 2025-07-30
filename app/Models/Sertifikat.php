<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','tanggal_mulai','tanggal_selesai','file_sertifikat'];

    public function user () {
        return $this ->belongsTo(User::class);
    }
}
