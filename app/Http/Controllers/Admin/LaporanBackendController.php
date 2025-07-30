<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanBackendController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('user')->latest()->get();
        return view('admin.laporan.index', compact('laporans'));
    }

    public function downloadFile($type, $id)
    {
        $laporan = Laporan::findOrFail($id);
        $path = $type === 'laporan' ? $laporan->file_laporan : $laporan->file_nilai_magang;

        return response()->download(storage_path("app/public/{$path}"));
    }
}
