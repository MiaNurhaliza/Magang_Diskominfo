<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate laporan triwulanan otomatis setiap 3 bulan
        // Akan berjalan pada tanggal 1 di bulan April, Juli, Oktober, dan Januari
        $schedule->command('laporan:generate-triwulan')
                 ->cron('0 2 1 1,4,7,10 *') // Jam 02:00 pada tanggal 1 bulan Januari, April, Juli, Oktober
                 ->description('Generate laporan triwulanan mahasiswa magang')
                 ->emailOutputOnFailure('admin@diskominfo.bukittinggi.go.id');
        
        // Alternative: Generate laporan untuk quarter yang baru saja berakhir
        // $schedule->command('laporan:generate-triwulan --quarter=1')->cron('0 2 1 4 *'); // Q1 report in April
        // $schedule->command('laporan:generate-triwulan --quarter=2')->cron('0 2 1 7 *'); // Q2 report in July  
        // $schedule->command('laporan:generate-triwulan --quarter=3')->cron('0 2 1 10 *'); // Q3 report in October
        // $schedule->command('laporan:generate-triwulan --quarter=4')->cron('0 2 1 1 *'); // Q4 report in January
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}