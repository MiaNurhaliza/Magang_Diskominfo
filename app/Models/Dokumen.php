<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    public function biodata()
    {
    return $this->belongsTo(Biodata::class);
    }

    use HasFactory;

    protected $fillable = [
        'user_id',
        'surat_permohonan',
        'kartu_tanda_mahasiswa',
        'cv',
        'sertifikat',
    ];
}
