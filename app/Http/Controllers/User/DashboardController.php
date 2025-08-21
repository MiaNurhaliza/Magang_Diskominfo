<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Biodata;

class DashboardController extends Controller
{
   public function index()
    {
        $biodata = Biodata::where('user_id', Auth::id())->first();

        if ($biodata && $biodata->status === 'Diterima') {

            $periode = null;
            if ($biodata->tanggal_mulai && $biodata->tanggal_selesai) {
                // karena sudah di-cast ke date, bisa langsung format()
                $periode = $biodata->tanggal_mulai->format('d M Y')
                         . ' s/d ' .
                           $biodata->tanggal_selesai->format('d M Y');
            }

            return view('landing.index', compact('biodata','periode'));
        }

        return redirect()
            ->route('pendaftaran.status')
            ->with('error', 'Status pendaftaran kamu belum Diterima.');
    }

}
