<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::where('user_id', Auth::id())->first();
        return view('frontend.sertifikat.index', compact('sertifikat'));
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
