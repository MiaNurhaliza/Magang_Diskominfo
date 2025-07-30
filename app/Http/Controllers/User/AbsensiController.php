<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index() 
    {
        $absensis = Absensi::where('user_id', Auth::id())->latest('tanggal')->get();
        return view('user.absensi.index', compact('absensis'));
    }

    public function store(Request $request)
    {
        $request -> validate ([
            'tanggal'=> 'required|date',
            'pagi'=> 'required|string',
            'siang'=> 'required|string',
            'sore'=> 'required|string',
            
        ]);

        Absensi::updateOrCreate (
            ['user_id' => Auth::id(), 'tanggal'=>$request->tanggal],
            [
                'pagi' => $request->pagi,
                'siang' => $request->siang,
                'sore' => $request->sore,
            ]
            );

            return bacl()->with('success', 'Absensi disimpan');
    }
    public function ajukanIzin (Request $request)
    {
        $request -> validate ([
            'jenis' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request -> hasFile('bukti_file')) {
            $filePath = $request->file('bukti_file')->store('izin','public');
        }

        Izin::create ([
            'user_id'=>Auth::id(),
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'bukti_file' => $filePath,
        ]);
        return back ()->with('success' , 'Izin berhasil diajukan');
    }
    public function isiKehadiran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'sesi' => 'required|in:pagi,siang,sore',
            'status' => 'required|string',
        ]);

        $absen = Absensi::firstOrNew([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
        ]);

        $absen -> fill([
            $request->sesi =>$request->status,
        ]);
        $absen->save();
        return back()->with('succes', 'Kehadiran berhasil disimpan');
    }
}
