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

        // Generate attendance records up to today only (not future days)
        $absensis = collect();
        $currentDate = $tanggalMulai->copy();
        
        // Get existing attendance records
        $existingAbsensi = Absensi::where('biodata_id', $biodataId)
            ->with('izin')
            ->get()
            ->keyBy('tanggal');
        
        // Only show dates from start date up to today (or end date if today is past end date)
        $endDate = $today->lt($tanggalSelesai) ? $today : $tanggalSelesai;
        
        while ($currentDate->lte($endDate)) {
            // Skip weekends
            if (!$currentDate->isWeekend()) {
                $dateString = $currentDate->format('Y-m-d');
                
                // Check if attendance record exists for this date
                if (isset($existingAbsensi[$dateString])) {
                    $absensi = $existingAbsensi[$dateString];
                    $absensi->is_today = $currentDate->isSameDay($today);
                    $absensi->is_past = $currentDate->lt($today);
                    $absensi->is_future = false; // No future dates shown
                } else {
                    // Create empty attendance record for display (only for today and past)
                    $absensi = (object) [
                        'id' => null,
                        'biodata_id' => $biodataId,
                        'tanggal' => $dateString,
                        'pagi' => null,
                        'siang' => null,
                        'sore' => null,
                        'waktu_pagi' => null,
                        'waktu_siang' => null,
                        'waktu_sore' => null,
                        'keterangan' => null,
                        'izin' => null,
                        'is_today' => $currentDate->isSameDay($today),
                        'is_past' => $currentDate->lt($today),
                        'is_future' => false
                    ];
                }
                
                $absensis->push($absensi);
            }
            
            $currentDate->addDay();
        }
        
        // Sort by date descending (newest first)
        $absensis = $absensis->sortByDesc('tanggal');

        // Convert to paginator with 10 items per page
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $currentItems = $absensis->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedAbsensis = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $absensis->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('frontend.absensi.index', compact(
            'paginatedAbsensis',
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

        // Create izin record
        $izin = Izin::create([
            'user_id' => Auth::id(),
            'jenis' => $request->jenis_izin,
            'tanggal' => $request->tanggal_izin,
            'keterangan' => $request->keterangan,
            'bukti_file' => $filePath,
        ]);

        // Update or create absensi record
        $absensi = Absensi::firstOrNew([
            'biodata_id' => Auth::user()->biodata->id,
            'tanggal' => $request->tanggal_izin
        ]);

        // Set status izin untuk semua sesi
        $absensi->user_id = Auth::id();
        $absensi->pagi = $request->jenis_izin;
        $absensi->siang = $request->jenis_izin;
        $absensi->sore = $request->jenis_izin;
        $absensi->keterangan = $request->keterangan;
        $absensi->file_keterangan = $filePath;
        $absensi->izin_id = $izin->id;
        $absensi->save();

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
