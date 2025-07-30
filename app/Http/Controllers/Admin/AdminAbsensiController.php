<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;

class AdminAbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::with('biodata')->get();
        return view('backend.absensi.index', compact('absensis'));
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();
        return redirect()->route('admin.absensi.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
