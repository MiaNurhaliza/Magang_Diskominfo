<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanAkhirController extends Controller
{
    public function index () {
        $laporan = LaporanAkhir::where('user_id',Auth::id())->latest()->first();
        return view('user.laporan.index',compact('laporan'));
    }

    public function store(Request $request)
    {
        $request ->validete ([
            'nama_lengkap'=>'required|string|max:255',
            'judul_laporan'=>'required|string|max:255',
            'pembimbing_industri'=>'required|string|max:255',
            'file_laporan'=>'required|file|mimes:pdf|max:5120',
            'file_nilai_magang'=>'required|file|mimes:pdf|max:5120',
        ]);

        $laporanPath = $request -> file ('file_laporan')->store('laporan-akhir','public');
        $nilaiPath = $request -> file ('file_nilai_magang')->store('nilai-magang','public');

        LaporanAkhir::create([
            'user_id'=>Auth::id(),
            'nama_lengkap'=>$request->nama_lengkap,
            'judul_laporan'=>$request->judul_laporan,
            'pembimbing_industri'=>$request->pembimbing_industri,
            'file_laporan'=>$laporanPath,
            'file_nilai_magang'=>$nilaiPath,
        ]);
        return back()->with('succes','Laporan akhir berhasil diunggah');
    }
}
