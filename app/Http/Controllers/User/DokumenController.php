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

    // Check if dokumen already exists
    $existingDokumen = \App\Models\Dokumen::where('user_id', $userId)->first();

    $rules = [];
    $messages = [
        'surat_permohonan.required' => 'Surat permohonan magang wajib diupload.',
        'surat_permohonan.mimes' => 'Surat permohonan harus berformat PDF.',
        'surat_permohonan.max' => 'Ukuran surat permohonan maksimal 5MB.',
        'kartu_tanda_mahasiswa.mimes' => 'Kartu tanda mahasiswa harus berformat PDF.',
        'kartu_tanda_mahasiswa.max' => 'Ukuran kartu tanda mahasiswa maksimal 5MB.',
        'cv.mimes' => 'CV harus berformat PDF.',
        'cv.max' => 'Ukuran CV maksimal 5MB.',
        'sertifikat.mimes' => 'Sertifikat harus berformat PDF.',
        'sertifikat.max' => 'Ukuran sertifikat maksimal 5MB.',
    ];

    if (!$existingDokumen || !$existingDokumen->surat_permohonan) {
        $rules['surat_permohonan'] = 'required|mimes:pdf|max:5120';
    } else {
        $rules['surat_permohonan'] = 'nullable|mimes:pdf|max:5120';
    }
    
    // Kartu tanda mahasiswa tidak wajib
    $rules['kartu_tanda_mahasiswa'] = 'nullable|mimes:pdf|max:5120';
    $rules['cv'] = 'nullable|mimes:pdf|max:5120';
    $rules['sertifikat'] = 'nullable|mimes:pdf|max:5120';

    // Debug: Log request data
    \Log::info('Upload request data:', [
        'has_surat_permohonan' => $request->hasFile('surat_permohonan'),
        'has_kartu_tanda_mahasiswa' => $request->hasFile('kartu_tanda_mahasiswa'),
        'has_cv' => $request->hasFile('cv'),
        'has_sertifikat' => $request->hasFile('sertifikat'),
        'files_count' => count($request->allFiles()),
        'content_type' => $request->header('Content-Type')
    ]);

    // Validasi file
    $request->validate($rules, $messages);

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
