<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Models\Status;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\Laporan_Akhir;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ringkasan
        $totalPendaftar = Biodata::count();
        $diterima = Status::where('status', 'diterima')->count();
        $ditolak = Status::where('status', 'ditolak')->count();
        $pesertaAktif = Status::where('status', 'diterima')->count(); // anggap diterima = aktif
        //$laporanAkhir = LaporanAkhir::whereNotNull('laporan_akhir')->count();
        // $laporanAkhir = Biodata::whereNotNull('file_laporan')->count();
        // $sertifikat = Biodata::whereNotNull('sertifikat')->count();

         $today = now()->toDateString();

        // Aktivitas hari ini
        $aktivitasHariIni = Biodata::with([
    'absensi' => function ($query) use ($today) {
        $query->where('tanggal', $today);
    },
    'logbook' => function ($query) use ($today) {
        $query->where('tanggal', $today);
    }
])->get();
        return view('backend.dashboard', compact(
            'totalPendaftar',
            'diterima',
            'ditolak',
            'pesertaAktif',
            // 'laporanAkhir',
            // 'sertifikat',
            'aktivitasHariIni'
        ));
    }
}
