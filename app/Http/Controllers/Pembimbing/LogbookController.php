<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $mahasiswaIds = $mahasiswas->pluck('id');

        $query = Logbook::whereIn('biodata_id', $mahasiswaIds)
            ->with('biodata');

        // Filter by mahasiswa
        if ($request->mahasiswa_id) {
            $query->where('biodata_id', $request->mahasiswa_id);
        }

        // Filter by status
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

        // Summary statistics
        $summary = [
            'total' => Logbook::whereIn('biodata_id', $mahasiswaIds)->count(),
            'pending' => Logbook::whereIn('biodata_id', $mahasiswaIds)->where('status', 'pending')->count(),
            'disetujui' => Logbook::whereIn('biodata_id', $mahasiswaIds)->where('status', 'disetujui')->count(),
            'ditolak' => Logbook::whereIn('biodata_id', $mahasiswaIds)->where('status', 'ditolak')->count(),
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
        if (!$this->pembimbing->mahasiswas()->where('biodata_id', $logbook->biodata_id)->exists()) {
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
        $filename = 'logbook_mahasiswa_' . date('Y-m-d_H-i-s');

        if ($type === 'excel') {
            return Excel::download(new LogbookExport($data), $filename . '.xlsx');
        } elseif ($type === 'pdf') {
            $pdf = Pdf::loadView('pembimbing.exports.logbook-pdf', compact('data'));
            return $pdf->download($filename . '.pdf');
        }

        return redirect()->back()->with('error', 'Format export tidak valid.');
    }
}

/**
 * Export class for Excel
 */
class LogbookExport implements \Maatwebsite\Excel\Concerns\FromCollection,
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
            'Tanggal',
            'Kegiatan',
            'Jam Mulai',
            'Jam Selesai',
            'Status',
            'Komentar Pembimbing'
        ];
    }

    public function map($logbook): array
    {
        return [
            $logbook->biodata->nama,
            $logbook->biodata->nim,
            $logbook->tanggal->format('d/m/Y'),
            $logbook->kegiatan,
            $logbook->jam_mulai ? $logbook->jam_mulai->format('H:i') : '-',
            $logbook->jam_selesai ? $logbook->jam_selesai->format('H:i') : '-',
            ucfirst($logbook->status),
            $logbook->komentar_pembimbing ?? '-'
        ];
    }
}