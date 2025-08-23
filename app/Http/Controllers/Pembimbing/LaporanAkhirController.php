<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\LaporanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $mahasiswaIds = $mahasiswas->pluck('id');

        $query = LaporanAkhir::whereIn('biodata_id', $mahasiswaIds)
            ->with('biodata');

        // Filter by mahasiswa
        if ($request->mahasiswa_id) {
            $query->where('biodata_id', $request->mahasiswa_id);
        }

        // Filter by status
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
            'total' => LaporanAkhir::whereIn('biodata_id', $mahasiswaIds)->count(),
            'menunggu_review' => LaporanAkhir::whereIn('biodata_id', $mahasiswaIds)->where('status', 'menunggu_review')->count(),
            'disetujui' => LaporanAkhir::whereIn('biodata_id', $mahasiswaIds)->where('status', 'disetujui')->count(),
            'ditolak' => LaporanAkhir::whereIn('biodata_id', $mahasiswaIds)->where('status', 'ditolak')->count(),
        ];

        return view('pembimbing.laporan-akhir', compact(
            'laporans',
            'mahasiswas',
            'summary'
        ));
    }

    /**
     * Review laporan akhir
     */
    public function review(Request $request, LaporanAkhir $laporan)
    {
        // Check if laporan belongs to pembimbing's mahasiswa
        if (!$this->pembimbing->mahasiswas()->where('biodata_id', $laporan->biodata_id)->exists()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'nilai' => 'nullable|numeric|min:0|max:100',
            'komentar_pembimbing' => 'nullable|string|max:1000'
        ]);

        $laporan->update([
            'status' => $request->status,
            'nilai' => $request->nilai,
            'komentar_pembimbing' => $request->komentar_pembimbing,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        $statusText = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return redirect()->back()
            ->with('success', "Laporan akhir berhasil {$statusText}.");
    }

    /**
     * Export laporan akhir data
     */
    private function export($type, $data)
    {
        $filename = 'laporan_akhir_mahasiswa_' . date('Y-m-d_H-i-s');

        if ($type === 'excel') {
            return Excel::download(new LaporanAkhirExport($data), $filename . '.xlsx');
        } elseif ($type === 'pdf') {
            $pdf = Pdf::loadView('pembimbing.exports.laporan-akhir-pdf', compact('data'));
            return $pdf->download($filename . '.pdf');
        }

        return redirect()->back()->with('error', 'Format export tidak valid.');
    }
}

/**
 * Export class for Excel
 */
class LaporanAkhirExport implements \Maatwebsite\Excel\Concerns\FromCollection,
                                   \Maatwebsite\Excel\Concerns\WithHeadings,
                                   \Maatwebsite\Excel\Concerns\WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Nama Mahasiswa',
            'NIM',
            'Judul Laporan',
            'Tanggal Submit',
            'Status',
            'Nilai',
            'Komentar Pembimbing'
        ];
    }

    public function map($laporan): array
    {
        return [
            $laporan->biodata->nama,
            $laporan->biodata->nim,
            $laporan->judul,
            $laporan->created_at->format('d/m/Y H:i'),
            ucfirst(str_replace('_', ' ', $laporan->status)),
            $laporan->nilai ?? '-',
            $laporan->komentar_pembimbing ?? '-'
        ];
    }
}