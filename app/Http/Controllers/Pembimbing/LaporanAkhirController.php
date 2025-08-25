<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\LaporanAkhir;
use App\Models\Sertifikat;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanAkhirController extends Controller
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
     * Display laporan akhir mahasiswa
     */
    public function index(Request $request)
    {
        $pembimbing = $this->pembimbing;
        $mahasiswas = $pembimbing->mahasiswas;
        $mahasiswaUserIds = $mahasiswas->pluck('user_id');

        $query = LaporanAkhir::whereIn('user_id', $mahasiswaUserIds)
            ->with('user');

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

        // Filter by year
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Handle export
        if ($request->export) {
            return $this->export($request->export, $query->get());
        }

        $laporans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Summary statistics
        $summary = [
            'total' => LaporanAkhir::whereIn('user_id', $mahasiswaUserIds)->count(),
            'menunggu_review' => LaporanAkhir::whereIn('user_id', $mahasiswaUserIds)->where('status', 'pending')->count(),
            'disetujui' => LaporanAkhir::whereIn('user_id', $mahasiswaUserIds)->where('status', 'approved')->count(),
            'ditolak' => LaporanAkhir::whereIn('user_id', $mahasiswaUserIds)->where('status', 'revision')->count(),
        ];

        return view('pembimbing.laporan-akhir', compact(
            'laporans',
            'mahasiswas',
            'summary'
        ))->with('laporanAkhirs', $laporans);
    }

    /**
     * Review laporan akhir (legacy method for compatibility)
     */
    public function review(Request $request, LaporanAkhir $laporan)
    {
        // Redirect to appropriate action based on status
        if ($request->status === 'approved') {
            return $this->approve($laporan);
        } elseif ($request->status === 'revision') {
            return $this->revise($request, $laporan);
        }
        
        return redirect()->back()->with('error', 'Status tidak valid.');
    }

    /**
     * Approve laporan akhir
     */
    public function approve(LaporanAkhir $laporan)
    {
        // Check if laporan belongs to pembimbing's mahasiswa
        if (!$this->pembimbing->mahasiswas()->where('user_id', $laporan->user_id)->exists()) {
            abort(403, 'Unauthorized access');
        }

        $laporan->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'revision_note' => null
        ]);

        // Auto-create sertifikat when laporan is approved
        $this->createSertifikat($laporan);

        return redirect()->back()
            ->with('success', 'Laporan akhir berhasil disetujui. Sertifikat otomatis tersedia di admin.');
    }

    /**
     * Request revision for laporan akhir
     */
    public function revise(Request $request, LaporanAkhir $laporan)
    {
        \Log::info("Revise method called for laporan ID: " . $laporan->id);
        
        // Check if laporan belongs to pembimbing's mahasiswa
        if (!$this->pembimbing->mahasiswas()->where('user_id', $laporan->user_id)->exists()) {
            \Log::error("Unauthorized access for laporan ID: " . $laporan->id);
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'revision_note' => 'required|string|max:1000'
        ]);

        \Log::info("Updating laporan status to revision with note: " . $request->revision_note);

        $laporan->update([
            'status' => 'revision',
            'revision_note' => $request->revision_note,
            'approved_at' => null,
            'approved_by' => null
        ]);

        \Log::info("Laporan updated successfully");

        return redirect()->back()
            ->with('success', 'Laporan akhir dikembalikan untuk revisi.');
    }

    /**
     * Delete laporan akhir
     */
    public function destroy(LaporanAkhir $laporan)
    {
        // Check if laporan belongs to pembimbing's mahasiswa
        if (!$this->pembimbing->mahasiswas()->where('user_id', $laporan->user_id)->exists()) {
            abort(403, 'Unauthorized access');
        }

        $laporan->delete();

        return redirect()->back()
            ->with('success', 'Laporan akhir berhasil dihapus.');
    }

    /**
     * Create sertifikat when laporan is approved
     */
    private function createSertifikat(LaporanAkhir $laporan)
    {
        // Check if sertifikat already exists
        $existingSertifikat = Sertifikat::where('user_id', $laporan->user_id)->first();
        
        if (!$existingSertifikat) {
            // Get biodata for dates
            $biodata = Biodata::where('user_id', $laporan->user_id)->first();
            
            // Generate nomor sertifikat
            $year = date('Y');
            $month = date('m');
            $lastSertifikat = Sertifikat::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $nomorUrut = str_pad($lastSertifikat + 1, 3, '0', STR_PAD_LEFT);
            $nomorSertifikat = "SERT/{$year}/{$month}/{$nomorUrut}";
            
            Sertifikat::create([
                'user_id' => $laporan->user_id,
                'nomor_sertifikat' => $nomorSertifikat,
                'tanggal_mulai' => $biodata->tanggal_mulai ?? now()->subMonths(3),
                'tanggal_selesai' => $biodata->tanggal_selesai ?? now(),
                'file_sertifikat' => null, // Will be generated by admin
            ]);
        }
    }

    /**
     * Export laporan akhir data
     */
    private function export($type, $data)
    {
        return redirect()->back()->with('error', 'Fitur export sementara dinonaktifkan.');
    }
}