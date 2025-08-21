<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LaporanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanAkhirController extends Controller
{
    public function index()
    {
        $laporan = LaporanAkhir::where('user_id', Auth::id())->latest()->first();
        return view('frontend.laporan-akhir.index', compact('laporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'pembimbing_industri' => 'required|string|max:255',
            'file_laporan' => 'required|file|mimes:pdf|max:5120',
            'file_nilai_magang' => 'required|file|mimes:pdf|max:5120',
        ]);

        // Cek apakah user sudah pernah upload laporan
        $existingLaporan = LaporanAkhir::where('user_id', Auth::id())->first();
        
        if ($existingLaporan) {
            return back()->with('error', 'Anda sudah pernah mengupload laporan akhir. Laporan hanya dapat diupload sekali.');
        }
        
        // Buat data baru
        $laporanPath = $request->file('file_laporan')->store('laporan-akhir', 'public');
        $nilaiPath = $request->file('file_nilai_magang')->store('nilai-magang', 'public');
        
        LaporanAkhir::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => Auth::user()->name,
            'judul_laporan' => $request->judul_laporan,
            'pembimbing_industri' => $request->pembimbing_industri,
            'file_laporan' => $laporanPath,
            'file_nilai_magang' => $nilaiPath,
        ]);
        
        return back()->with('success', 'Laporan akhir berhasil diunggah!');
    }

    public function download($type)
    {
        $laporan = LaporanAkhir::where('user_id', Auth::id())->first();
        
        if (!$laporan) {
            return back()->with('error', 'Laporan tidak ditemukan.');
        }
        
        $filePath = null;
        $fileName = null;
        
        if ($type === 'laporan' && $laporan->file_laporan) {
            $filePath = $laporan->file_laporan;
            $fileName = 'Laporan_Akhir_' . $laporan->nama_lengkap . '.pdf';
        } elseif ($type === 'nilai' && $laporan->file_nilai_magang) {
            $filePath = $laporan->file_nilai_magang;
            $fileName = 'Nilai_Magang_' . $laporan->nama_lengkap . '.pdf';
        }
        
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }
        
        return back()->with('error', 'File tidak ditemukan.');
    }
}
