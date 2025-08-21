<?php

namespace App\Http\Controllers\User;

use App\Models\Biodata;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BiodataController extends Controller
{
    // Menampilkan form pengisian biodata pertama kali
    public function create()
    {
        return view('frontend.create_biodata');
    }

    // Menampilkan form edit biodata
    public function edit()
    {
        $biodata = Biodata::where('user_id', Auth::id())->first();
        
        if (!$biodata) {
            return redirect()->route('biodata.create')->with('error', 'Silakan lengkapi biodata terlebih dahulu.');
        }
        return view('frontend.edit_biodata', compact('biodata'));
    }

    // Menyimpan biodata baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis_nim' => 'required|string|max:100',
            'asal_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'matkul_pendukung' => 'required|string|max:255',
            'tujuan_magang' => 'required|string|max:255',
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
            'matkul_pendukung' => $request->matkul_pendukung,
            'tujuan_magang' => $request->tujuan_magang,
            'nama_pembimbing' => $request->nama_pembimbing,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('dokumen.create')->with('success', 'Biodata berhasil disimpan! Silakan lengkapi dokumen.');
    }

    // Mengupdate biodata yang sudah ada
    public function update(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis_nim' => 'required|string|max:100',
            'asal_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'matkul_pendukung' => 'required|string|max:255',
            'tujuan_magang' => 'required|string|max:255',
            'nama_pembimbing' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $biodata = Biodata::where('user_id', Auth::id())->firstOrFail();
        $biodata->update($request->all());

        return redirect()->route('biodata.edit')->with('success', 'Biodata berhasil diperbarui!');
    }
}
