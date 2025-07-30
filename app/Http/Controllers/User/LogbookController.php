<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::where('user_id',Auth::id())->latest('tanggal')->get();
        return view('user.logbook.index',compact('logbooks'));
        
    }

    public function store(Request $request)
    {
        $request->validate ([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'langkah_kerja' => 'nullable|date',
            'hasil_akhir' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $filePath = null;
        if ($request -> hasFile ('hasil_akhir')){
            $filePath = $request -> file('hasil_akhir')->store('logbook','public');
        }

        Logbook::create ([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'Kegiatan' =>$request->kegiatan,
            'langkah_kerja' =>$request->langkah_kerja,
            'hasil_akhir' =>$request->filePath,
        ]);
        return back()->with('succes', 'Logbook berhasil disimpan');
    }
}
