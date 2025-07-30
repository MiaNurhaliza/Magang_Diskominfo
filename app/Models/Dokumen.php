<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    public function biodata()
    {
    return $this->belongsTo(Biodata::class);
    }

}
