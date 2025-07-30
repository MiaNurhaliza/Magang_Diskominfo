<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class DokumenController extends Controller
{
    // Tampilkan form upload dokumen
    public function create()
    {
        return view('frontend.create_dokumen');
    }

    // Simpan dokumen
    public function store(Request $request)
    {
        // Validasi file upload
        $request->validate([
            'surat_permohonan' => 'required|mimes:pdf|max:5120',
            'kartu_tanda_mahasiswa' => 'required|mimes:pdf|max:5120',
            'cv' => 'required|mimes:pdf|max:5120',
            'sertifikat' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Simpan masing-masing file ke storage/app/public/dokumen/
        $data = [];

        if ($request->hasFile('surat_permohonan')) {
            $data['surat_permohonan'] = $request->file('surat_permohonan')->store('dokumen', 'public');
        }

        if ($request->hasFile('kartu_pelajar')) {
            $data['kartu_pelajar'] = $request->file('kartu_pelajar')->store('dokumen', 'public');
        }

        if ($request->hasFile('cv')) {
            $data['cv'] = $request->file('cv')->store('dokumen', 'public');
        }

        if ($request->hasFile('sertifikat_kompetensi')) {
            $data['sertifikat_kompetensi'] = $request->file('sertifikat_kompetensi')->store('dokumen', 'public');
        }

        // Simpan data ke database kalau kamu sudah punya model (opsional)
        // Misal: Auth::user()->dokumen()->create($data);

        return redirect()->back()->with('success', 'Dokumen berhasil diunggah!');
    }
}
