<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;

class AdminPendaftarController extends Controller
{
    public function index()
    {
        $pendaftars = Pendaftar::latest()->get();
        return view('backend.pendaftar.index', compact('pendaftars'));
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
}
