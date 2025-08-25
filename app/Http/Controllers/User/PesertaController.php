<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Biodata;

class PesertaController extends Controller
{
    public function index()
    {
        // Jika user adalah pembimbing, redirect ke dashboard pembimbing
        if (Auth::user()->role === 'pembimbing') {
            return redirect()->route('pembimbing.dashboard');
        }

        // Jika user adalah admin, redirect ke dashboard admin
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

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
