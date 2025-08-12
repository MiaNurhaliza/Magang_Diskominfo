<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{

    public function index () 
    {
        $user = auth()->user();
        $dokumen = Dokumen::where('user_id', $user->id)->first();

        return view('frontend.dokumen.index', compact('dokumen'));
    }
    // Tampilkan form upload dokumen
    public function create()
    {
        $dokumen = \App\Models\Dokumen::where('user_id', auth()->id())->first(); // ganti ke Auth::id() nanti
        return view('frontend.create_dokumen',compact('dokumen'));
    }

    // Simpan dokumen


public function store(Request $request)
{
    $userId = auth()->id();
 if (!$userId) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    // Validasi file
    $request->validate([
        'surat_permohonan' => 'required|mimes:pdf|max:5120',
        'kartu_tanda_mahasiswa' => 'nullable|mimes:pdf|max:5120',
        'cv' => 'nullable|mimes:pdf|max:5120',
        'sertifikat' => 'nullable|mimes:pdf|max:5120',
    ]);

    // Simpan file
    $data = [];

    if ($request->hasFile('surat_permohonan')) {
        $data['surat_permohonan'] = $request->file('surat_permohonan')->store('dokumen/surat', 'public');
    }
    if ($request->hasFile('kartu_tanda_mahasiswa')) {
        $data['kartu_tanda_mahasiswa'] = $request->file('kartu_tanda_mahasiswa')->store('dokumen/kartu', 'public');
    }
    if ($request->hasFile('cv')) {
        $data['cv'] = $request->file('cv')->store('dokumen/cv', 'public');
    }
    if ($request->hasFile('sertifikat')) {
        $data['sertifikat'] = $request->file('sertifikat')->store('dokumen/sertifikat', 'public');
    }

    // Simpan atau update
    \App\Models\Dokumen::updateOrCreate(
        ['user_id' => $userId],
        $data
    );

    return redirect()->route('dokumen.create')->with('success', 'Dokumen berhasil diunggah!');
}



}
