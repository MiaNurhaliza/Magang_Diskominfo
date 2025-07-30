<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LaporanAkhir extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'judul-laporan',
        'pembimbing_industri',
        'file_laporan',
        'file_nilai_magang',

    ];
    public function user()
    {
        return $this -> belongsTo(User::class);
    }
}
