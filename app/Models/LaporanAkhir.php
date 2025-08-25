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
        'judul_laporan',
        'pembimbing_industri',
        'file_laporan',
        'file_nilai_magang',
        'status',
        'revision_note',
        'approved_at',
        'approved_by',
    ];
    public function user()
    {
        return $this -> belongsTo(User::class);
    }

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'user_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected $casts = [
        'approved_at' => 'datetime',
    ];
}
