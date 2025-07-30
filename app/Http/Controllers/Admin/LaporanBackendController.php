<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanAkhir;

class LaporanBackendController extends Controller
{
    public function index()
    {
        $laporans = LaporanAkhir::with('user')->latest()->get();
        return view('backend.laporan_akhir.index', compact('laporans'));
    }

    public function downloadFile($type, $id)
    {
        $laporans = LaporanAkhir::findOrFail($id);
        $path = $type === 'laporan' ? $laporans->file_laporan : $laporans->file_nilai_magang;

        return response()->download(storage_path("app/public/{$path}"));
    }
}
