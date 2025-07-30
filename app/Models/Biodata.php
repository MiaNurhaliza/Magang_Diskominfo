<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    protected $table = 'biodatas';

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'biodata_id');
    }

    public function logbook()
    {
        return $this->hasMany(Logbook::class, 'biodata_id');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'biodata_id');
    }
    public function laporan_akhir()
    {
        return $this->hasOne(Laporan_akhir::class, 'biodata_id');
    }
}
