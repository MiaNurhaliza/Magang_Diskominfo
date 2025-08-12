<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index() 
    {
        $user = Auth::user();

        // Pastikan user punya biodata
        if (!$user->biodata) {
            return back()->with('error', 'Biodata tidak ditemukan.');
        }

        $biodata = $user->biodata;
        $biodataId = $biodata->id;

        // Ambil semua absensi peserta
        $absensis = Absensi::where('biodata_id', $biodataId)->get();

        // Cek tanggal hari ini
        $today = Carbon::today();
        $bolehAbsen = $today->between(
            Carbon::parse($biodata->tanggal_mulai),
            Carbon::parse($biodata->tanggal_selesai)
        );

        // Cek apakah sudah absen hari ini
        $sudahAbsen = Absensi::where('biodata_id', $biodataId)
            ->whereDate('tanggal', $today)
            ->exists();

        return view('frontend.absensi.index', compact(
            'absensis',
            'bolehAbsen',
            'sudahAbsen',
            'biodata'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'=> 'required|date',
            'pagi'=> 'nullable|string',
            'siang'=> 'nullable|string',
            'sore'=> 'nullable|string',
        ]);

        $biodataId = Auth::user()->biodata->id;

        Absensi::updateOrCreate(
            ['biodata_id' => $biodataId, 'tanggal' => $request->tanggal],
            [
                'pagi' => $request->pagi,
                'siang' => $request->siang,
                'sore' => $request->sore,
            ]
        );

        return back()->with('success', 'Absensi disimpan');
    }

    public function ajukanIzin(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $filePath = $request->file('bukti_file')->store('izin', 'public');
        }

        $biodataId = Auth::user()->biodata->id;

        Izin::create([
            'biodata_id' => $biodataId,
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'bukti_file' => $filePath,
        ]);

        return back()->with('success', 'Izin berhasil diajukan');
    }

    public function isiKehadiran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'sesi' => 'required|in:pagi,siang,sore',
            'status' => 'required|string',
        ]);

        $biodataId = Auth::user()->biodata->id;

        $absen = Absensi::firstOrNew([
            'biodata_id' => $biodataId,
            'tanggal' => $request->tanggal,
        ]);

        $absen->fill([
            $request->sesi => $request->status,
        ]);

        $absen->save();

        return back()->with('success', 'Kehadiran berhasil disimpan');
    }
}
