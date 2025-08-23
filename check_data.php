<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking database data...\n";

// Check if there are any pembimbing records
$pembimbingCount = App\Models\Pembimbing::count();
echo "Total pembimbings: $pembimbingCount\n";

// Check if there are any pivot records
$pivotCount = Illuminate\Support\Facades\DB::table('pembimbing_mahasiswa')->count();
echo "Total pivot records: $pivotCount\n";

// Check if there are any absensi records
$absensiCount = App\Models\Absensi::count();
echo "Total absensi records: $absensiCount\n";

// Check if there are any biodata records
$biodataCount = App\Models\Biodata::count();
echo "Total biodata records: $biodataCount\n";

// Check user with pembimbing role
$pembimbingUsers = App\Models\User::where('role', 'pembimbing')->count();
echo "Users with pembimbing role: $pembimbingUsers\n";

// Check specific relationships
if ($pembimbingCount > 0) {
    $pembimbing = App\Models\Pembimbing::first();
    echo "First pembimbing: {$pembimbing->nama}\n";
    
    $mahasiswas = $pembimbing->mahasiswas;
    echo "Mahasiswa count for first pembimbing: {$mahasiswas->count()}\n";
    
    foreach ($mahasiswas as $mahasiswa) {
        $absensiCount = $mahasiswa->absensis()->count();
        echo "  - {$mahasiswa->nama_lengkap}: $absensiCount absensi records\n";
    }
}

// Check if there are absensi records with biodata_id
$absensiWithBiodata = App\Models\Absensi::whereNotNull('biodata_id')->count();
echo "Absensi records with biodata_id: $absensiWithBiodata\n";

// Sample absensi data
$sampleAbsensi = App\Models\Absensi::with('biodata')->take(3)->get();
echo "Sample absensi data:\n";
foreach ($sampleAbsensi as $absensi) {
    echo "  ID: {$absensi->id}, User ID: {$absensi->user_id}, Biodata ID: {$absensi->biodata_id}, Date: {$absensi->tanggal}\n";
    if ($absensi->biodata) {
        echo "    Biodata: {$absensi->biodata->nama_lengkap}\n";
    } else {
        echo "    No biodata found\n";
    }
}
