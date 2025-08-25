<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Models\Status;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\LaporanAkhir;
use App\Models\Sertifikat;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ringkasan
        $totalPendaftar = Biodata::count();
        $diterima = Biodata::where('status', 'diterima')->count();
        $ditolak = Biodata::where('status', 'ditolak')->count();
        $pesertaAktif = Biodata::where('status', 'diterima')->count(); // anggap diterima = aktif
        $laporanAkhir = LaporanAkhir::whereNotNull('file_laporan')->count();
        $sertifikat = Sertifikat::count();

         $today = now()->toDateString();

        // Aktivitas hari ini - hanya peserta yang diterima
        $aktivitasHariIni = Biodata::whereHas('status', function($query) {
            $query->where('status', 'diterima');
        })
        ->with([
            'user',
            'absensi' => function ($query) use ($today) {
                $query->where('tanggal', $today);
            },
            'logbook' => function ($query) use ($today) {
                $query->where('tanggal', $today);
            }
        ])
        ->paginate(5);
        return view('backend.dashboard', compact(
            'totalPendaftar',
            'diterima',
            'ditolak',
            'pesertaAktif',
            'laporanAkhir',
            'sertifikat',
            'aktivitasHariIni'
        ));
    }
}
