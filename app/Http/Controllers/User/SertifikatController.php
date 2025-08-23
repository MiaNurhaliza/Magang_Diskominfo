<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use App\Models\Biodata;
use App\Services\SertifikatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    protected $sertifikatService;

    public function __construct(SertifikatService $sertifikatService)
    {
        $this->sertifikatService = $sertifikatService;
    }

    public function index()
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())->first();
        $biodata = Biodata::where('user_id', Auth::id())->first();
        
        return view('frontend.sertifikat.index', compact('sertifikat', 'biodata'));
    }

    public function generate()
    {
        try {
            // Cek apakah biodata sudah lengkap dan status diterima
            $biodata = Biodata::where('user_id', Auth::id())->first();
            
            if (!$biodata) {
                return back()->with('error', 'Biodata belum lengkap. Silakan lengkapi biodata terlebih dahulu.');
            }
            
            if ($biodata->status !== 'Diterima') {
                return back()->with('error', 'Sertifikat hanya dapat diunduh setelah status pendaftaran diterima.');
            }
            
            // Cek apakah sertifikat sudah pernah dibuat
            $existingSertifikat = Sertifikat::where('user_id', Auth::id())->first();
            
            if ($existingSertifikat) {
                return $this->download();
            }
            
            // Generate sertifikat baru
            $result = $this->sertifikatService->generateSertifikat(Auth::id());
            
            // Simpan record sertifikat ke database
            Sertifikat::create([
                'user_id' => Auth::id(),
                'nomor_sertifikat' => $result['nomor_sertifikat'],
                'tanggal_mulai' => $biodata->tanggal_mulai,
                'tanggal_selesai' => $biodata->tanggal_selesai,
                'file_sertifikat' => $result['file_path']
            ]);
            
            return back()->with('success', 'Sertifikat berhasil dibuat dan siap diunduh.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat sertifikat: ' . $e->getMessage());
        }
    }

    public function download()
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())->first();
        
        if (!$sertifikat || !$sertifikat->file_sertifikat) {
            return back()->with('error', 'Sertifikat tidak ditemukan atau belum tersedia.');
        }
        
        $filePath = $sertifikat->file_sertifikat;
        $fileName = 'Sertifikat_Magang_' . Auth::user()->name . '.pdf';
        
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }
        
        return back()->with('error', 'File sertifikat tidak ditemukan.');
    }
}
