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

        $tanggalMulai = Carbon::parse($biodata->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($biodata->tanggal_selesai);
        $today = Carbon::today();

        // Cek apakah hari ini dalam periode magang dan bukan weekend
        $bolehAbsen = $today->between($tanggalMulai, $tanggalSelesai) && !$today->isWeekend();

        // Ambil semua riwayat absensi yang sudah ada
        $riwayatAbsensi = Absensi::where('biodata_id', $biodataId)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Cek absensi hari ini
        $absensiHariIni = Absensi::where('biodata_id', $biodataId)
            ->whereDate('tanggal', $today)
            ->first();

        // Jika belum ada absensi hari ini dan boleh absen, tambahkan ke list
        $absensis = collect();
        
        if ($bolehAbsen) {
            if ($absensiHariIni) {
                $absensiHariIni->is_today = true;
                $absensis->push($absensiHariIni);
            } else {
                $absensis->push((object) [
                    'id' => null,
                    'biodata_id' => $biodataId,
                    'tanggal' => $today->format('Y-m-d'),
                    'pagi' => null,
                    'siang' => null,
                    'sore' => null,
                    'is_today' => true
                ]);
            }
        }

        // Tambahkan riwayat absensi sebelumnya
        foreach ($riwayatAbsensi as $absensi) {
            if (!$today->isSameDay(Carbon::parse($absensi->tanggal))) {
                $absensi->is_today = false;
                $absensis->push($absensi);
            }
        }

        return view('frontend.absensi.index', compact(
            'absensis',
            'bolehAbsen',
            'biodata'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'sesi' => 'required|in:pagi,siang,sore',
            'status' => 'required|string',
        ]);

        $biodataId = Auth::user()->biodata->id;
        $sesi = $request->sesi;
        $status = $request->status;

        // Update atau create absensi
        $absensi = Absensi::firstOrNew([
            'biodata_id' => $biodataId,
            'tanggal' => $request->tanggal
        ]);

        // Set user_id dan status untuk sesi yang dipilih
        $absensi->user_id = Auth::id();
        $absensi->$sesi = $status;
        $absensi->save();

        return back()->with('success', 'Absensi berhasil disimpan');
    }

    public function storeIzin(Request $request)
    {
        // Validation rules based on izin type
        $rules = [
            'jenis_izin' => 'required|string',
            'tanggal_izin' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        // Only require file upload for 'Sakit' type
        if ($request->jenis_izin === 'Sakit') {
            $rules['surat_keterangan'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        } else {
            $rules['surat_keterangan'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        $filePath = null;
        if ($request->hasFile('surat_keterangan')) {
            $filePath = $request->file('surat_keterangan')->store('izin', 'public');
        }

        Izin::create([
            'user_id' => Auth::id(),
            'jenis' => $request->jenis_izin,
            'tanggal' => $request->tanggal_izin,
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
