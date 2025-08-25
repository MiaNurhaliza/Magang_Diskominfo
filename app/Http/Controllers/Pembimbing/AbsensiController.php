<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    protected $pembimbing;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('pembimbing');
        
        $this->middleware(function ($request, $next) {
            $this->pembimbing = Pembimbing::where('user_id', Auth::id())->first();
            if (!$this->pembimbing) {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    /**
     * Display absensi mahasiswa
     */
    public function index(Request $request)
    {
        $pembimbing = $this->pembimbing;
        $mahasiswas = $pembimbing->mahasiswas;
        $mahasiswaIds = $mahasiswas->pluck('id');

        $query = Absensi::whereIn('biodata_id', $mahasiswaIds)
            ->with('biodata');

        // Filter by mahasiswa
        if ($request->mahasiswa_id) {
            $query->where('biodata_id', $request->mahasiswa_id);
        }

        // Filter by month and year
        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        } elseif ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // Handle export
        if ($request->export) {
            return $this->export($request->export, $query->get());
        }

        $absensis = $query->orderBy('tanggal', 'desc')->paginate(15);

        // Summary statistics
        $summary = [
            'hadir' => Absensi::whereIn('biodata_id', $mahasiswaIds)->where('status', 'hadir')->count(),
            'izin' => Absensi::whereIn('biodata_id', $mahasiswaIds)->where('status', 'izin')->count(),
            'sakit' => Absensi::whereIn('biodata_id', $mahasiswaIds)->where('status', 'sakit')->count(),
            'alpha' => Absensi::whereIn('biodata_id', $mahasiswaIds)->where('status', 'alpha')->count(),
        ];

        return view('pembimbing.absensi', compact(
            'absensis',
            'mahasiswas',
            'summary'
        ));
    }

    /**
     * Delete absensi record
     */
    public function destroy(Absensi $absensi)
    {
        $pembimbing = $this->pembimbing;
        $mahasiswaIds = $pembimbing->mahasiswas->pluck('id');

        // Check if absensi belongs to pembimbing's mahasiswa
        if (!$mahasiswaIds->contains($absensi->biodata_id)) {
            abort(403, 'Unauthorized access');
        }

        $absensi->delete();

        return redirect()->back()
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Export absensi data
     */
    private function export($type, $data)
    {
        return redirect()->back()->with('error', 'Fitur export sementara dinonaktifkan.');
    }
}

/**
 * Export class for Excel
 */