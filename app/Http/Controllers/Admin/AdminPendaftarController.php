<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\Dokumen;
use App\Models\Biodata;


class AdminPendaftarController extends Controller
{
    public function index()
    {
        $pendaftars = Biodata::with(['user','dokumen'])->latest()->get();
        return view('backend.pendaftar.index', compact('pendaftars'));
        // $pendaftars = Pendaftar::with('dokumen')->get();

        // $pendaftars = Pendaftar::latest()->get();
        // return view('backend.pendaftar.index', compact('pendaftars'));
    }

    public function show($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        return view('backend.pendaftar.show', compact('pendaftar'));
    }

    public function destroy($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->delete();
        return redirect()->route('pendaftar.index')->with('success', 'Data berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $pendaftar = Biodata::findOrFail($id);
        $pendaftar->status = $request->status;
        $pendaftar->alasan = $request->alasan;

        if ($request->hasFile('surat_balasan')) {
            $file = $request->file('surat_balasan')->store('surat_balasan', 'public');
            //$pendaftar->surat_balasan = $file;
        }

        $pendaftar->save();

        return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }
}
