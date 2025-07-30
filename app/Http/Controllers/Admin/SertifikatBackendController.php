<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatBackendController extends Controller
{
    public function index () {
        $sertifikats = Sertifikat::with('user.biodata')->get();
        return view('backend.sertifikat.index',compact('sertifikats'));
    }

    public function store(Request $request) {
        $request->validate([
            'user_id'=>'required|exists:user.id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai'=> 'required|date',
            'file_sertifikat' => 'required|mimes:pdf|max:5120'
        ]);

        $path = $request -> file('file_sertifikat')->store('sertifikat','public');

        Sertifikat::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'file_sertifikat' => $path,
            ]
        );
        return back()->with('succes','Sertifikat berhasil diunggah');
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
            'file_sertifikat' => 'required|mimes:pdf|max:5120'
        ]);

        $sertifikat = Sertifikat::findOrFail($id);

        if ($request->hasFile('file_sertifikat')) {
            $sertifikat->file_sertifikat = $request->file('file_sertifikat')->store('sertifikat', 'public');
            $sertifikat->file = $request;
            $sertifikat->save();
        }

       

        return redirect()->route('admin.sertifikat.index')->with('success', 'Sertifikat berhasil diperbarui.');
    }
}
