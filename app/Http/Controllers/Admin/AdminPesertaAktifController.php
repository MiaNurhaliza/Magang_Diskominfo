<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Models\Pendaftar;

class AdminPesertaAktifController extends Controller
{
    public function index()
    {
        $pesertas = biodata::where ('status', 'diterima')->get();
        
        return view('backend.peserta_aktif.index', compact('pesertas'));
    }
    public function destroy($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->delete();
        return redirect()->route('admin.pendaftar.index')->with('success', 'Data berhasil dihapus.');
    }

    public function edit ($id)
    {
        $peserta = biodata::with ('dokumen')->findOrFail($id);
        return view('backend.peserta_aktif.edit', compact('peserta'));
    }

    public function update(Request $request, $id)
{
    $peserta = Biodata::findOrFail($id);
    $peserta->update($request->all());
        // tambahkan lainnya sesuai kebutuhan
    
        return redirect()->route('admin.peserta-aktif')->with('success', 'Data peserta berhasil diperbarui.');
    }
}
