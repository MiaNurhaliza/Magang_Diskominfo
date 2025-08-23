<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pembimbing extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Relasi many-to-many dengan mahasiswa (biodata)
     */
    public function mahasiswas(): BelongsToMany
    {
        return $this->belongsToMany(Biodata::class, 'pembimbing_mahasiswa', 'pembimbing_id', 'biodata_id')
                    ->withTimestamps();
    }

    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
