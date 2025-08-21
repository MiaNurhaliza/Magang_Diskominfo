<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Biodata;

class PesertaController extends Controller
{
    public function index()
    {
        // Ambil status pendaftaran dari biodata user
        $biodata = Biodata::where('user_id', Auth::id())->first();

        // Cek apakah status Diterima
        if ($biodata && $biodata->status === 'Diterima') {
            return view('frontend.dashboard', [
                'biodata' => $biodata
            ]);
        }

        // Kalau belum diterima, arahkan balik ke halaman status
        return redirect()->route('pendaftaran.status')
                         ->with('error', 'Status pendaftaran kamu belum Diterima.');
    }
}
