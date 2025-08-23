<?php

namespace App\Services;

use App\Models\LaporanTriwulan;
use App\Models\Biodata;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Logbook;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class LaporanTriwulanService
{
    /**
     * Generate PDF laporan triwulanan
     */
    public function generatePdf(LaporanTriwulan $laporan)
    {
        $data = [
            'laporan' => $laporan,
            'generated_at' => Carbon::now()->format('d F Y H:i:s'),
            'summary' => $this->generateSummary($laporan)
        ];

        $pdf = Pdf::loadView('admin.laporan-triwulan.pdf-template', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true
            ]);

        return $pdf;
    }

    /**
     * Save PDF to storage and update laporan
     */
    public function savePdf(LaporanTriwulan $laporan)
    {
        $pdf = $this->generatePdf($laporan);
        
        $filename = "laporan_triwulan_{$laporan->tahun}_q{$laporan->quarter}.pdf";
        $directory = 'laporan-triwulan';
        $path = "{$directory}/{$filename}";
        
        // Create directory if not exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // Save PDF
        Storage::disk('public')->put($path, $pdf->output());
        
        // Update laporan with file path
        $laporan->update(['file_pdf' => $path]);
        
        return $path;
    }

    /**
     * Generate summary statistics
     */
    private function generateSummary(LaporanTriwulan $laporan)
    {
        $data = $laporan->data_mahasiswa;
        
        if (empty($data)) {
            return [
                'total_mahasiswa' => 0,
                'rata_rata_kehadiran' => 0,
                'evaluasi_counts' => [],
                'sekolah_terbanyak' => '-',
                'jurusan_terbanyak' => '-'
            ];
        }

        // Hitung rata-rata kehadiran
        $totalKehadiran = array_sum(array_column($data, 'persentase_kehadiran'));
        $rataRataKehadiran = count($data) > 0 ? round($totalKehadiran / count($data), 2) : 0;

        // Hitung distribusi evaluasi
        $evaluasiCounts = array_count_values(array_column($data, 'evaluasi'));

        // Sekolah terbanyak
        $sekolahCounts = array_count_values(array_column($data, 'asal_sekolah'));
        $sekolahTerbanyak = !empty($sekolahCounts) ? array_keys($sekolahCounts, max($sekolahCounts))[0] : '-';

        // Jurusan terbanyak
        $jurusanCounts = array_count_values(array_column($data, 'jurusan'));
        $jurusanTerbanyak = !empty($jurusanCounts) ? array_keys($jurusanCounts, max($jurusanCounts))[0] : '-';

        return [
            'total_mahasiswa' => count($data),
            'rata_rata_kehadiran' => $rataRataKehadiran,
            'evaluasi_counts' => $evaluasiCounts,
            'sekolah_terbanyak' => $sekolahTerbanyak,
            'jurusan_terbanyak' => $jurusanTerbanyak
        ];
    }

    /**
     * Get laporan by period
     */
    public function getLaporanByPeriod($year, $quarter)
    {
        return LaporanTriwulan::where('tahun', $year)
            ->where('quarter', $quarter)
            ->first();
    }

    /**
     * Check if laporan exists for period
     */
    public function laporanExists($year, $quarter)
    {
        return LaporanTriwulan::where('tahun', $year)
            ->where('quarter', $quarter)
            ->exists();
    }

    /**
     * Get current quarter
     */
    public function getCurrentQuarter()
    {
        return ceil(date('n') / 3);
    }

    /**
     * Get available years for laporan
     */
    public function getAvailableYears()
    {
        $currentYear = date('Y');
        $startYear = 2020; // Adjust based on your needs
        
        $years = [];
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $years[] = $year;
        }
        
        return $years;
    }

    /**
     * Get quarter name in Indonesian
     */
    public function getQuarterName($quarter)
    {
        $names = [
            1 => 'Triwulan I (Januari - Maret)',
            2 => 'Triwulan II (April - Juni)', 
            3 => 'Triwulan III (Juli - September)',
            4 => 'Triwulan IV (Oktober - Desember)'
        ];
        
        return $names[$quarter] ?? 'Tidak Diketahui';
    }

    /**
     * Get students data for a specific quarter (used by command)
     */
    public function getStudentsDataForQuarter($year, $quarter)
    {
        $quarterDates = LaporanTriwulan::getQuarterDates($year, $quarter);
        
        // Get all students who were active during this quarter
        $students = User::with(['biodata', 'pendaftar'])
            ->where('role', 'user')
            ->whereHas('pendaftar', function($query) use ($quarterDates) {
                $query->where('status', 'diterima')
                    ->where(function($q) use ($quarterDates) {
                        $q->where('tanggal_mulai', '<=', $quarterDates['end'])
                          ->where('tanggal_selesai', '>=', $quarterDates['start']);
                    });
            })
            ->get();

        $studentsData = [];
        
        foreach ($students as $student) {
            // Calculate attendance for this quarter
            $attendanceData = $this->calculateAttendanceForPeriod(
                $student->id, 
                $quarterDates['start'], 
                $quarterDates['end']
            );
            
            // Count logbook entries for this quarter
            $logbookCount = Logbook::where('user_id', $student->id)
                ->whereBetween('tanggal', [$quarterDates['start'], $quarterDates['end']])
                ->count();
            
            // Generate evaluation based on attendance and logbook
            $evaluation = $this->generateEvaluation(
                $attendanceData['persentase_kehadiran'],
                $logbookCount
            );
            
            $studentsData[] = [
                'user_id' => $student->id,
                'nama' => $student->name,
                'nim' => $student->biodata->nim ?? '-',
                'universitas' => $student->biodata->universitas ?? '-',
                'jurusan' => $student->biodata->jurusan ?? '-',
                'total_hadir' => $attendanceData['total_hadir'],
                'total_hari_kerja' => $attendanceData['total_hari_kerja'],
                'persentase_kehadiran' => $attendanceData['persentase_kehadiran'],
                'total_logbook' => $logbookCount,
                'evaluasi' => $evaluation
            ];
        }
        
        return $studentsData;
    }

    /**
     * Calculate attendance for a specific period
     */
    private function calculateAttendanceForPeriod($userId, $startDate, $endDate)
    {
        // Count working days (Monday to Friday) in the period
        $totalWorkingDays = 0;
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            if ($current->isWeekday()) {
                $totalWorkingDays++;
            }
            $current->addDay();
        }
        
        // Count actual attendance
        $attendanceCount = Absensi::where('user_id', $userId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereNotNull('jam_masuk')
            ->count();
        
        $percentage = $totalWorkingDays > 0 ? ($attendanceCount / $totalWorkingDays) * 100 : 0;
        
        return [
            'total_hadir' => $attendanceCount,
            'total_hari_kerja' => $totalWorkingDays,
            'persentase_kehadiran' => round($percentage, 2)
        ];
    }
}