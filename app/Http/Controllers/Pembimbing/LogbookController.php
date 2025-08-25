<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
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
     * Display logbook mahasiswa
     */
    public function index(Request $request)
    {
        $pembimbing = $this->pembimbing;
        $mahasiswas = $pembimbing->mahasiswas;
        $mahasiswaUserIds = $mahasiswas->pluck('user_id');

        $query = Logbook::whereIn('user_id', $mahasiswaUserIds)
            ->with('user', 'biodata');

        // Filter by mahasiswa
        if ($request->mahasiswa_id) {
            // Find the user_id for the selected mahasiswa
            $selectedMahasiswa = $mahasiswas->find($request->mahasiswa_id);
            if ($selectedMahasiswa) {
                $query->where('user_id', $selectedMahasiswa->user_id);
            }
        }

        // Filter by status (if status column exists)
        if ($request->status) {
            $query->where('status', $request->status);
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

        $logbooks = $query->orderBy('tanggal', 'desc')->paginate(15);

        // Summary statistics - simplified since status column may not exist
        $summary = [
            'total' => Logbook::whereIn('user_id', $mahasiswaUserIds)->count(),
            'pending' => 0,
            'disetujui' => 0,
            'ditolak' => 0,
        ];

        return view('pembimbing.logbook', compact(
            'logbooks',
            'mahasiswas',
            'summary'
        ));
    }

    /**
     * Review logbook
     */
    public function review(Request $request, Logbook $logbook)
    {
        // Check if logbook belongs to pembimbing's mahasiswa
        if (!$this->pembimbing->mahasiswas()->where('user_id', $logbook->user_id)->exists()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'komentar_pembimbing' => 'nullable|string|max:1000'
        ]);

        $logbook->update([
            'status' => $request->status,
            'komentar_pembimbing' => $request->komentar_pembimbing,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        $statusText = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return redirect()->back()
            ->with('success', "Logbook berhasil {$statusText}.");
    }

    /**
     * Export logbook data
     */
    private function export($type, $data)
    {
        return redirect()->back()->with('error', 'Fitur export sementara dinonaktifkan.');
    }
}