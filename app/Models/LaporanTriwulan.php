<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanTriwulan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'quarter',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_mahasiswa',
        'ringkasan',
        'data_mahasiswa',
        'file_pdf',
        'status',
        'created_by'
    ];

    protected $casts = [
        'data_mahasiswa' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('tahun', $year);
    }

    public function scopeByQuarter($query, $quarter)
    {
        return $query->where('quarter', $quarter);
    }

    public static function generatePeriode($year, $quarter)
    {
        return "Q{$quarter} {$year}";
    }

    public static function getQuarterDates($year, $quarter)
    {
        switch ($quarter) {
            case 1:
                return [
                    'start' => "{$year}-01-01",
                    'end' => "{$year}-03-31"
                ];
            case 2:
                return [
                    'start' => "{$year}-04-01",
                    'end' => "{$year}-06-30"
                ];
            case 3:
                return [
                    'start' => "{$year}-07-01",
                    'end' => "{$year}-09-30"
                ];
            case 4:
                return [
                    'start' => "{$year}-10-01",
                    'end' => "{$year}-12-31"
                ];
            default:
                throw new \InvalidArgumentException('Quarter must be between 1 and 4');
        }
    }
}
