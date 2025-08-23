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
        
        return view('pembimbing.dashboard', compact('mahasiswas', 'pembimbing'));
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
