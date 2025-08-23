<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanAkhir;

class LaporanBackendController extends Controller
{
    public function index()
    {
        $laporans = LaporanAkhir::with('user')->latest()->paginate(10);
        return view('backend.laporan_akhir.index', compact('laporans'));
    }

    public function downloadFile($type, $id)
    {
        $laporans = LaporanAkhir::findOrFail($id);
        $path = $type === 'laporan' ? $laporans->file_laporan : $laporans->file_nilai_magang;

        return response()->download(storage_path("app/public/{$path}"));
    }
    public function destroy($id)
    {
        $laporan = LaporanAkhir::findOrFail($id);
        $laporan->delete();
        return redirect()->route('backend.laporan.index')->with('success', 'Data laporan berhasil dihapus.');
    }
}
