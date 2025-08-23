<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LaporanTriwulanService;
use App\Models\LaporanTriwulan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateLaporanTriwulan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laporan:generate-triwulan {--year=} {--quarter=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate laporan triwulanan mahasiswa magang';

    protected $laporanService;

    public function __construct(LaporanTriwulanService $laporanService)
    {
        parent::__construct();
        $this->laporanService = $laporanService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?? date('Y');
        $quarter = $this->option('quarter') ?? $this->laporanService->getCurrentQuarter();
        $force = $this->option('force');

        $this->info("Generating laporan triwulan untuk Q{$quarter} {$year}...");

        try {
            // Check if laporan already exists
            if (!$force && $this->laporanService->laporanExists($year, $quarter)) {
                $this->warn("Laporan untuk Q{$quarter} {$year} sudah ada. Gunakan --force untuk menimpa.");
                return 1;
            }

            // Get quarter dates
            $quarterDates = LaporanTriwulan::getQuarterDates($year, $quarter);
            
            $this->info("Periode: {$quarterDates['start']->format('d M Y')} - {$quarterDates['end']->format('d M Y')}");

            // Generate laporan
            $laporan = $this->generateLaporan($year, $quarter, $quarterDates, $force);

            if ($laporan) {
                $this->info("✅ Laporan berhasil dibuat dengan ID: {$laporan->id}");
                $this->info("📊 Total mahasiswa: {$laporan->total_mahasiswa}");
                $this->info("📄 Status: {$laporan->status}");
                
                if ($laporan->file_pdf) {
                    $this->info("📁 File PDF: {$laporan->file_pdf}");
                }

                // Log success
                Log::info('Laporan triwulan generated successfully', [
                    'laporan_id' => $laporan->id,
                    'year' => $year,
                    'quarter' => $quarter,
                    'total_mahasiswa' => $laporan->total_mahasiswa
                ]);

                return 0;
            } else {
                $this->error("❌ Gagal membuat laporan triwulan.");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::error('Failed to generate laporan triwulan', [
                'year' => $year,
                'quarter' => $quarter,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    private function generateLaporan($year, $quarter, $quarterDates, $force = false)
    {
        $this->line("🔍 Mengumpulkan data mahasiswa...");
        
        // Get students data for the quarter
        $studentsData = $this->laporanService->getStudentsDataForQuarter($year, $quarter);
        
        if (empty($studentsData)) {
            $this->warn("⚠️  Tidak ada data mahasiswa untuk periode ini.");
            
            if (!$this->confirm('Tetap buat laporan kosong?')) {
                return null;
            }
        } else {
            $this->info("📋 Ditemukan {" . count($studentsData) . "} mahasiswa");
        }

        // Delete existing laporan if force
        if ($force) {
            $existing = LaporanTriwulan::where('tahun', $year)
                ->where('quarter', $quarter)
                ->first();
            
            if ($existing) {
                $this->line("🗑️  Menghapus laporan yang sudah ada...");
                $existing->delete();
            }
        }

        // Create laporan
        $this->line("📝 Membuat laporan...");
        
        $laporan = LaporanTriwulan::create([
            'tahun' => $year,
            'quarter' => $quarter,
            'periode' => LaporanTriwulan::generatePeriode($year, $quarter),
            'tanggal_mulai' => $quarterDates['start'],
            'tanggal_selesai' => $quarterDates['end'],
            'total_mahasiswa' => count($studentsData),
            'data_mahasiswa' => $studentsData,
            'ringkasan' => $this->generateAutoSummary($studentsData, $year, $quarter),
            'status' => 'published',
            'created_by' => 1 // System user
        ]);

        // Generate PDF
        $this->line("📄 Membuat file PDF...");
        
        $pdfPath = $this->laporanService->generatePdf($laporan);
        
        if ($pdfPath) {
            $laporan->update(['file_pdf' => $pdfPath]);
            $this->info("✅ PDF berhasil dibuat");
        } else {
            $this->warn("⚠️  PDF gagal dibuat, tapi laporan tetap tersimpan");
        }

        return $laporan;
    }

    private function generateAutoSummary($studentsData, $year, $quarter)
    {
        if (empty($studentsData)) {
            return "Laporan triwulan Q{$quarter} {$year} - Tidak ada mahasiswa magang pada periode ini.";
        }

        $totalStudents = count($studentsData);
        $evaluations = collect($studentsData)->pluck('evaluasi')->countBy();
        $avgAttendance = collect($studentsData)->avg('persentase_kehadiran');
        
        $quarterNames = [
            1 => 'Januari - Maret',
            2 => 'April - Juni', 
            3 => 'Juli - September',
            4 => 'Oktober - Desember'
        ];

        $summary = "Laporan Triwulan Q{$quarter} {$year} ({$quarterNames[$quarter]})\n\n";
        $summary .= "RINGKASAN EKSEKUTIF:\n";
        $summary .= "• Total mahasiswa magang: {$totalStudents} orang\n";
        $summary .= "• Rata-rata kehadiran: " . number_format($avgAttendance, 1) . "%\n";
        $summary .= "• Distribusi evaluasi:\n";
        
        foreach ($evaluations as $eval => $count) {
            $percentage = ($count / $totalStudents) * 100;
            $summary .= "  - {$eval}: {$count} orang (" . number_format($percentage, 1) . "%)
";
        }
        
        $summary .= "\nLaporan ini dibuat secara otomatis oleh sistem pada " . now()->format('d M Y H:i') . ".";
        
        return $summary;
    }
}
