<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logbook;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::where('user_id', Auth::id())->latest('tanggal')->get();
        return view('frontend.logbook.index', compact('logbooks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'langkah_kerja' => 'required|string',
            'hasil_akhir' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('hasil_akhir')) {
            $filePath = $request->file('hasil_akhir')->store('logbook', 'public');
        }

        Logbook::create([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
            'langkah_kerja' => $request->langkah_kerja,
            'hasil_akhir' => $filePath,
        ]);

        return back()->with('success', 'Logbook berhasil disimpan');
    }

    public function edit($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($logbook);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'langkah_kerja' => 'required|string',
            'hasil_akhir' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $logbook = Logbook::where('user_id', Auth::id())->findOrFail($id);

        $filePath = $logbook->hasil_akhir;
        if ($request->hasFile('hasil_akhir')) {
            $filePath = $request->file('hasil_akhir')->store('logbook', 'public');
        }

        $logbook->update([
            'tanggal' => $request->tanggal,
            'kegiatan' => $request->kegiatan,
            'langkah_kerja' => $request->langkah_kerja,
            'hasil_akhir' => $filePath,
        ]);

        return back()->with('success', 'Logbook berhasil diperbarui');
    }

    public function destroy($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())->findOrFail($id);
        $logbook->delete();
        return back()->with('success', 'Logbook berhasil dihapus');
    }
}
