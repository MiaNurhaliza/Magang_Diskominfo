<?php

namespace App\Http\Controllers\User;

use App\Models\Biodata;
use App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BiodataController extends Controller
{
    //crate biodata
    public function create ()
    {
        return view('frontend.create_biodata');
    }

    public function store (Request $request)
    {
        $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'nis_nim' => 'required|string|max:100',
        'asal_sekolah' => 'required|string|max:255',
        'jurusan' => 'required|string|max:255',
        'nama_pembimbing' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_hp' => 'required|string|max:20',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

         Biodata::create([
        'user_id' => Auth::id(),
        'nama_lengkap' => $request->nama_lengkap,
        'nis_nim' => $request->nis_nim,
        'asal_sekolah' => $request->asal_sekolah,
        'jurusan' => $request->jurusan,
        'nama_pembimbing' => $request->nama_pembimbing,
        'alamat' => $request->alamat,
        'no_hp' => $request->no_hp,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
    ]);

    return redirect()->route('dokumen.create')->with('success', 'Biodata berhasil disimpan!');

    }
}
