<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Status; 
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function status()
    {
        // Ambil data pendaftaran berdasarkan user yang login
        $status = Status::where('user_id', auth()->id())->get();

        return view('frontend.status_pendaftaran', compact('status'));
    }

    public function konfirmasiKetersediaan(Request $request, $id)
    {
        $status = Status::findOrFail($id);
        $status->ketersediaan = $request->ketersediaan;
        $status->save();

        return back()->with('success', 'Konfirmasi berhasil disimpan.');
    }

    public function unduhSurat($id)
    {
        $status = Status::findOrFail($id);

        return response()->download(storage_path('app/public/surat_balasan/' . $status->surat_balasan));
    }
}
