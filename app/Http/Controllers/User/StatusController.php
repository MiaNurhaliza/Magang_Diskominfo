<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Biodata;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{

public function index()
{
    // Ambil data biodata berdasarkan user yang login
    $status = Biodata::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            // Normalisasi status supaya konsisten di view
            if (strtolower($item->status) === 'jadwal dirubah' || strtolower($item->status) === 'jadwal_dirubah') {
                $item->status = 'jadwal_dialihkan';
            }
            return $item;
        });

    return view('frontend.status_pendaftaran', compact('status'));
}


    // public function status()
    // {
    //     // Ambil data pendaftaran user yang login
    //     $status = Biodata::where('user_id', auth()->id())->first();

    //     if (!$status) {
    //         return redirect()->route('biodata.create')
    //                          ->with('error', 'Silakan lengkapi biodata terlebih dahulu.');
    //     }

    //     return view('frontend.status_pendaftaran', compact('status'));
    // }

    public function konfirmasiKetersediaan(Request $request, $id)
{
    $request->validate([
        'ketersediaan' => 'required|in:ya,tidak'
    ]);

    $pendaftar = Biodata::where('user_id', auth()->id())
                        ->where('id', $id)
                        ->firstOrFail();

    $pendaftar->ketersediaan = $request->ketersediaan;
    $pendaftar->save();

    return back()->with('success', 'Konfirmasi berhasil disimpan.');
}

    public function unduhSurat($id)
    {
        $pendaftar = Biodata::where('user_id', auth()->id())
                            ->where('id', $id)
                            ->firstOrFail();

        if (!$pendaftar->surat_balasan) {
            return back()->with('error', 'Surat balasan belum tersedia.');
        }

        $path = storage_path('app/public/' . $pendaftar->surat_balasan);

        if (!file_exists($path)) {
            return back()->with('error', 'File surat balasan tidak ditemukan.');
        }

        return response()->download($path);
    }
}
