<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'ketersediaan',
        'surat_balasan',
        // tambahkan kolom lain sesuai tabel kamu
    ];

    public function biodata()
    {
        return $this->belongsTo(Biodata::class, 'user_id', 'user_id');
    }
}
