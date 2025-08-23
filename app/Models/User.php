<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
    public function magang() {
    return $this->hasOne(MagangPendaftar::class);
}

public function dokumen() {
    return $this->hasOne(DokumenPeserta::class);
}

public function status() {
    return $this->hasOne(StatusPendaftaran::class);
}

public function biodata() {
    return $this->hasOne(Biodata::class);
}
public function logbooks() {
    return $this->hasMany(Logbook::class);
}

public function lognbook() {
    return $this->hasOne(Logbook::class);
}

public function absensis() {
        return $this->hasMany(Absensi::class);
}

public function absensi() {
        return $this->hasMany(Absensi::class);
}    
public function pendaftar() {
        return $this->hasMany(Pendaftar::class);
}  

public function sertifikat() {
    return $this->hasOne(Sertifikat::class);
}

public function laporanAkhir() {
    return $this->hasOne(LaporanAkhir::class);
}

public function pembimbing() {
    return $this->hasOne(Pembimbing::class, 'email', 'email');
}

// Helper methods untuk role
public function isAdmin() {
    return $this->role === 'admin';
}

public function isPeserta() {
    return $this->role === 'user' || $this->role === 'peserta';
}

public function isPembimbing() {
    return $this->role === 'pembimbing';
}

}
