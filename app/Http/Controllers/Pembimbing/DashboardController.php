<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\LaporanAkhir;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isPembimbing()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $pembimbing = Auth::user()->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Data pembimbing tidak ditemukan');
        }

        $mahasiswas = $pembimbing->mahasiswas()->with(['user', 'absensi', 'logbook'])->get();
        
        // Calculate statistics for dashboard
        $totalMahasiswa = $mahasiswas->count();
        
        $totalAbsensi = 0;
        $totalLogbook = 0;
        $laporanSelesai = 0;
        
        foreach ($mahasiswas as $mahasiswa) {
            // Count total attendance records
            $totalAbsensi += Absensi::where('biodata_id', $mahasiswa->id)->count();
            
            // Count total logbook entries
            $totalLogbook += Logbook::where('user_id', $mahasiswa->user_id)->count();
            
            // Count completed reports
            $laporan = LaporanAkhir::where('user_id', $mahasiswa->user_id)->first();
            if ($laporan && $laporan->laporan_akhir && $laporan->nilai) {
                $laporanSelesai++;
            }
        }
        
        return view('pembimbing.dashboard', compact(
            'mahasiswas', 
            'pembimbing', 
            'totalMahasiswa', 
            'totalAbsensi', 
            'totalLogbook', 
            'laporanSelesai'
        ));
    }

    public function absensi()
    {
        $pembimbing = Auth::user()->pembimbing;
        $mahasiswas = $pembimbing->mahasiswas;
        
        $absensiData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $absensiData[$mahasiswa->id] = Absensi::where('biodata_id', $mahasiswa->id)
                ->orderBy('tanggal', 'desc')
                ->get();
        }
        
        return view('pembimbing.absensi', compact('mahasiswas', 'absensiData'));
    }

    public function logbook()
    {
        $pembimbing = Auth::user()->pembimbing;
        $mahasiswas = $pembimbing->mahasiswas;
        
        $logbookData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $logbookData[$mahasiswa->id] = Logbook::where('user_id', $mahasiswa->user_id)
                ->orderBy('tanggal', 'desc')
                ->get();
        }
        
        return view('pembimbing.logbook', compact('mahasiswas', 'logbookData'));
    }

    public function laporanAkhir()
    {
        $pembimbing = Auth::user()->pembimbing;
        $mahasiswas = $pembimbing->mahasiswas;
        
        $laporanData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $laporanData[$mahasiswa->id] = LaporanAkhir::where('user_id', $mahasiswa->user_id)->first();
        }
        
        return view('pembimbing.laporan-akhir', compact('mahasiswas', 'laporanData'));
    }
}
