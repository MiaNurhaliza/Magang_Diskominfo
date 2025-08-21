<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatBackendController extends Controller
{
    public function index() {
        // Ambil semua user yang memiliki biodata (peserta magang) dan sudah mengupload laporan akhir
        $users = \App\Models\User::whereHas('biodata')
            ->with(['biodata', 'sertifikat', 'laporanAkhir'])
            ->get();
        return view('backend.sertifikat.index', compact('users'));
    }

    public function store(Request $request) {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai'=> 'required|date',
            'file_sertifikat' => 'required|mimes:pdf|max:5120'
        ]);

        $path = $request->file('file_sertifikat')->store('sertifikat','public');

        Sertifikat::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'tanggal_mulai' => \Carbon\Carbon::parse($request->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => \Carbon\Carbon::parse($request->tanggal_selesai)->format('Y-m-d'),
                'file_sertifikat' => $path,
            ]
        );
        return back()->with('success','Sertifikat berhasil diunggah!');
    }

    public function download($id)
    {
        $sertifikat = Sertifikat::findOrFail($id);
        return response()->download(storage_path("app/public/{$sertifikat->file_sertifikat}"));
    }

    public function edit ($id)
    {
        $sertifikat = Sertifikat::with('user.biodata')->findOrFail($id);
        return view('backend.sertifikat.edit', compact('sertifikat'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'file' => 'required|mimes:pdf|max:2048'
    ]);

    $sertifikat = Sertifikat::findOrFail($id);

    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('sertifikats', 'public');
        $sertifikat->file = $filePath;
        $sertifikat->save();
    }

    return redirect()->route('admin.sertifikat')->with('success', 'Sertifikat berhasil diunggah.');
}

}
