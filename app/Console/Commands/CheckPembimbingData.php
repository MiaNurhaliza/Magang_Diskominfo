<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembimbing;
use App\Models\Biodata;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckPembimbingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:pembimbing-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check pembimbing data relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking pembimbing data relationships...');
        
        // Check pembimbing records
        $pembimbingCount = Pembimbing::count();
        $this->info("Total pembimbings: {$pembimbingCount}");
        
        // Check pivot table
        $pivotCount = DB::table('pembimbing_mahasiswa')->count();
        $this->info("Total pivot records: {$pivotCount}");
        
        // Check absensi records
        $absensiCount = Absensi::count();
        $this->info("Total absensi records: {$absensiCount}");
        
        // Check biodata records
        $biodataCount = Biodata::count();
        $this->info("Total biodata records: {$biodataCount}");
        
        // Check specific pembimbing relationships
        $pembimbings = Pembimbing::with('mahasiswas')->get();
        foreach ($pembimbings as $pembimbing) {
            $this->info("Pembimbing: {$pembimbing->nama}");
            $this->info("  Mahasiswa count: {$pembimbing->mahasiswas->count()}");
            
            foreach ($pembimbing->mahasiswas as $mahasiswa) {
                $absensiCount = $mahasiswa->absensis()->count();
                $this->info("    - {$mahasiswa->nama_lengkap}: {$absensiCount} absensi records");
            }
        }
        
        return 0;
    }
}
