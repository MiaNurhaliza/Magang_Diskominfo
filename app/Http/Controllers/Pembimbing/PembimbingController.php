<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\LaporanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembimbingController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get pembimbing data
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Get mahasiswa yang dibimbing
        $mahasiswas = $pembimbing->mahasiswas()->with(['user'])->get();
        
        // Statistics with real data
        $totalMahasiswa = $mahasiswas->count();
        $mahasiswaAktif = $mahasiswas->where('status', 'Diterima')->count();
        
        // Calculate total absensi from all mahasiswa bimbingan
        $totalAbsensi = 0;
        foreach ($mahasiswas as $mahasiswa) {
            $totalAbsensi += Absensi::where('biodata_id', $mahasiswa->id)->count();
        }
        
        // Calculate total logbook from all mahasiswa bimbingan (using user_id)
        $totalLogbook = 0;
        foreach ($mahasiswas as $mahasiswa) {
            $totalLogbook += Logbook::where('user_id', $mahasiswa->user_id)->count();
        }
        
        // Calculate laporan selesai from all mahasiswa bimbingan (using user_id)
        $laporanSelesai = 0;
        foreach ($mahasiswas as $mahasiswa) {
            if (LaporanAkhir::where('user_id', $mahasiswa->user_id)->exists()) {
                $laporanSelesai++;
            }
        }
        
        return view('pembimbing.dashboard', compact(
            'mahasiswas', 
            'totalMahasiswa', 
            'mahasiswaAktif',
            'totalAbsensi', 
            'totalLogbook', 
            'laporanSelesai'
        ));
    }
    
    public function absensi()
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Get all absensi from mahasiswa yang dibimbing
        $absensis = collect();
        $mahasiswas = $pembimbing->mahasiswas()->with(['absensis' => function($query) {
            $query->with('biodata', 'izin')->orderBy('tanggal', 'desc');
        }])->get();
        
        // Debug: Log the data
        \Log::info('Pembimbing: ' . $pembimbing->nama);
        \Log::info('Mahasiswa count: ' . $mahasiswas->count());
        
        // Flatten all absensi records
        foreach ($mahasiswas as $mahasiswa) {
            \Log::info('Mahasiswa: ' . $mahasiswa->nama_lengkap . ' - Absensi count: ' . $mahasiswa->absensis->count());
            if ($mahasiswa->absensis) {
                foreach ($mahasiswa->absensis as $absensi) {
                    $absensis->push($absensi);
                }
            }
        }
        
        // Sort by date descending
        $absensis = $absensis->sortByDesc('tanggal');
        
        \Log::info('Total absensi records: ' . $absensis->count());
        
        return view('pembimbing.absensi', compact('mahasiswas', 'absensis'));
    }
    
    public function destroyAbsensi($id)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Find the absensi record
        $absensi = Absensi::findOrFail($id);
        
        // Check if this absensi belongs to one of pembimbing's mahasiswa
        $mahasiswaIds = $pembimbing->mahasiswas()->pluck('id');
        if (!$mahasiswaIds->contains($absensi->biodata_id)) {
            return redirect()->route('pembimbing.absensi')->with('error', 'Anda tidak memiliki akses untuk menghapus absensi ini.');
        }
        
        // Delete the absensi record
        $absensi->delete();
        
        return redirect()->route('pembimbing.absensi')->with('success', 'Data absensi berhasil dihapus.');
    }
    
    public function logbook()
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Get all logbooks from mahasiswa yang dibimbing
        $logbooks = collect();
        $mahasiswas = $pembimbing->mahasiswas()->with(['logbooks' => function($query) {
            $query->with('biodata')->orderBy('tanggal', 'desc');
        }])->get();
        
        // Flatten all logbook records
        foreach ($mahasiswas as $mahasiswa) {
            foreach ($mahasiswa->logbooks as $logbook) {
                $logbooks->push($logbook);
            }
        }
        
        // Sort by date descending
        $logbooks = $logbooks->sortByDesc('tanggal');
        
        return view('pembimbing.logbook', compact('mahasiswas', 'logbooks'));
    }
    
    public function exportAbsensi(Request $request)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        $format = $request->get('format', 'excel');
        
        // Get mahasiswa yang dibimbing dengan absensi mereka
        $mahasiswas = $pembimbing->mahasiswas()->with(['absensis' => function($query) {
            $query->orderBy('tanggal', 'desc');
        }])->get();
        
        if ($format === 'pdf') {
            // Return PDF export (you can implement this later)
            return response()->json(['message' => 'PDF export will be implemented']);
        } else {
            // Return Excel export (you can implement this later)
            return response()->json(['message' => 'Excel export will be implemented']);
        }
    }
    
    public function exportLogbook(Request $request)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        $format = $request->get('format', 'excel');
        
        // Get mahasiswa yang dibimbing dengan logbook mereka
        $mahasiswas = $pembimbing->mahasiswas()->with(['logbooks' => function($query) {
            $query->orderBy('tanggal', 'desc');
        }])->get();
        
        if ($format === 'pdf') {
            // Return PDF export (you can implement this later)
            return response()->json(['message' => 'PDF export will be implemented']);
        } else {
            // Return Excel export (you can implement this later)
            return response()->json(['message' => 'Excel export will be implemented']);
        }
    }
    
    public function reviewLogbook($id)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Get logbook with biodata
        $logbook = Logbook::with('biodata')->findOrFail($id);
        
        // Check if this logbook belongs to one of pembimbing's mahasiswa
        $mahasiswaIds = $pembimbing->mahasiswas()->pluck('user_id');
        if (!$mahasiswaIds->contains($logbook->user_id)) {
            return redirect()->route('pembimbing.logbook')->with('error', 'Anda tidak memiliki akses untuk mereview logbook ini.');
        }
        
        return response()->json([
            'logbook' => $logbook,
            'message' => 'Logbook review functionality will be implemented'
        ]);
    }
    
    public function laporan()
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Get all laporan akhir from mahasiswa yang dibimbing with biodata
        $laporanAkhirs = collect();
        $mahasiswas = $pembimbing->mahasiswas()->with('laporanAkhir.biodata')->get();
        
        // Flatten all laporan akhir records
        foreach ($mahasiswas as $mahasiswa) {
            if ($mahasiswa->laporanAkhir) {
                $laporanAkhirs->push($mahasiswa->laporanAkhir);
            }
        }
        
        return view('pembimbing.laporan-akhir', compact('mahasiswas', 'laporanAkhirs'));
    }
    
    public function destroyLaporan($id)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Find the laporan akhir record
        $laporan = LaporanAkhir::findOrFail($id);
        
        // Check if this laporan belongs to one of pembimbing's mahasiswa
        $mahasiswaIds = $pembimbing->mahasiswas()->pluck('user_id');
        if (!$mahasiswaIds->contains($laporan->user_id)) {
            return redirect()->route('pembimbing.laporan-akhir')->with('error', 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }
        
        // Delete the laporan record
        $laporan->delete();
        
        return redirect()->route('pembimbing.laporan-akhir')->with('success', 'Data laporan akhir berhasil dihapus.');
    }
    
    public function exportLaporan(Request $request)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        $format = $request->get('format', 'excel');
        
        // Get mahasiswa yang dibimbing dengan laporan akhir mereka
        $mahasiswas = $pembimbing->mahasiswas()->with('laporanAkhir')->get();
        
        if ($format === 'pdf') {
            // Return PDF export (you can implement this later)
            return response()->json(['message' => 'PDF export will be implemented']);
        } else {
            // Return Excel export (you can implement this later)
            return response()->json(['message' => 'Excel export will be implemented']);
        }
    }
    
    public function reviewLaporan(Request $request, $id)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbing;
        
        if (!$pembimbing) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai pembimbing.');
        }
        
        // Get laporan akhir with biodata
        $laporan = LaporanAkhir::with('biodata')->findOrFail($id);
        
        // Check if this laporan belongs to one of pembimbing's mahasiswa
        $mahasiswaIds = $pembimbing->mahasiswas()->pluck('user_id');
        if (!$mahasiswaIds->contains($laporan->user_id)) {
            return redirect()->route('pembimbing.laporan-akhir')->with('error', 'Anda tidak memiliki akses untuk mereview laporan ini.');
        }
        
        return response()->json([
            'laporan' => $laporan,
            'message' => 'Laporan review functionality will be implemented'
        ]);
    }
}
