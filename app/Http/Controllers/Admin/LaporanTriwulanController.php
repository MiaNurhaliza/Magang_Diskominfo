<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanTriwulan;
use App\Models\Biodata;
use App\Models\Absensi;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanTriwulanController extends Controller
{
    public function __construct()
    {
        // Middleware handled by routes
    }

    public function index()
    {
        $laporans = LaporanTriwulan::with('creator')
            ->orderBy('tahun', 'desc')
            ->orderBy('quarter', 'desc')
            ->paginate(10);

        return view('admin.laporan-triwulan.index', compact('laporans'));
    }

    public function create()
    {
        $currentYear = date('Y');
        $currentQuarter = ceil(date('n') / 3);
        
        return view('admin.laporan-triwulan.create', compact('currentYear', 'currentQuarter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'quarter' => 'required|integer|min:1|max:4',
            'ringkasan' => 'nullable|string|max:1000'
        ]);

        // Check if laporan already exists
        $exists = LaporanTriwulan::where('tahun', $request->tahun)
            ->where('quarter', $request->quarter)
            ->exists();

        if ($exists) {
            return back()->withErrors(['quarter' => 'Laporan untuk periode ini sudah ada.']);
        }

        $dates = LaporanTriwulan::getQuarterDates($request->tahun, $request->quarter);
        $periode = LaporanTriwulan::generatePeriode($request->tahun, $request->quarter);

        // Get mahasiswa data for the quarter
        $mahasiswaData = $this->getMahasiswaDataForQuarter($request->tahun, $request->quarter, $dates);

        $laporan = LaporanTriwulan::create([
            'tahun' => $request->tahun,
            'quarter' => $request->quarter,
            'periode' => $periode,
            'tanggal_mulai' => $dates['start'],
            'tanggal_selesai' => $dates['end'],
            'total_mahasiswa' => count($mahasiswaData),
            'ringkasan' => $request->ringkasan,
            'data_mahasiswa' => $mahasiswaData,
            'status' => 'draft',
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.laporan-triwulan.index')
            ->with('success', 'Laporan triwulan berhasil dibuat.');
    }

    public function show(LaporanTriwulan $laporanTriwulan)
    {
        return view('admin.laporan-triwulan.show', compact('laporanTriwulan'));
    }

    public function edit(LaporanTriwulan $laporanTriwulan)
    {
        return view('admin.laporan-triwulan.edit', compact('laporanTriwulan'));
    }

    public function update(Request $request, LaporanTriwulan $laporanTriwulan)
    {
        $request->validate([
            'ringkasan' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,final'
        ]);

        $laporanTriwulan->update([
            'ringkasan' => $request->ringkasan,
            'status' => $request->status
        ]);

        return redirect()->route('admin.laporan-triwulan.show', $laporanTriwulan)
            ->with('success', 'Laporan triwulan berhasil diperbarui.');
    }

    public function destroy(LaporanTriwulan $laporanTriwulan)
    {
        // Delete PDF file if exists
        if ($laporanTriwulan->file_pdf && file_exists(storage_path('app/public/' . $laporanTriwulan->file_pdf))) {
            unlink(storage_path('app/public/' . $laporanTriwulan->file_pdf));
        }

        $laporanTriwulan->delete();

        return redirect()->route('admin.laporan-triwulan.index')
            ->with('success', 'Laporan triwulan berhasil dihapus.');
    }

    public function downloadPdf(LaporanTriwulan $laporanTriwulan)
    {
        $pdf = Pdf::loadView('admin.laporan-triwulan.pdf', compact('laporanTriwulan'));
        $pdf->setPaper('A4', 'portrait');
        
        $filename = "Laporan_Triwulan_{$laporanTriwulan->periode}.pdf";
        
        return $pdf->download($filename);
    }

    public function generatePdf(LaporanTriwulan $laporanTriwulan)
    {
        $pdf = Pdf::loadView('admin.laporan-triwulan.pdf', compact('laporanTriwulan'));
        
        $filename = "laporan_triwulan_{$laporanTriwulan->tahun}_q{$laporanTriwulan->quarter}.pdf";
        $path = "laporan-triwulan/{$filename}";
        
        // Save PDF to storage
        $pdf->save(storage_path('app/public/' . $path));
        
        // Update laporan with file path
        $laporanTriwulan->update(['file_pdf' => $path]);
        
        return redirect()->back()->with('success', 'PDF berhasil di-generate.');
    }

    public function regenerateData(LaporanTriwulan $laporanTriwulan)
    {
        $dates = LaporanTriwulan::getQuarterDates($laporanTriwulan->tahun, $laporanTriwulan->quarter);
        $mahasiswaData = $this->getMahasiswaDataForQuarter($laporanTriwulan->tahun, $laporanTriwulan->quarter, $dates);
        
        $laporanTriwulan->update([
            'data_mahasiswa' => $mahasiswaData,
            'total_mahasiswa' => count($mahasiswaData)
        ]);
        
        return redirect()->back()->with('success', 'Data mahasiswa berhasil di-regenerate.');
    }

    private function getMahasiswaDataForQuarter($year, $quarter, $dates)
    {
        $startDate = Carbon::parse($dates['start']);
        $endDate = Carbon::parse($dates['end']);

        // Get mahasiswa yang magang dalam periode tersebut
        $mahasiswas = Biodata::with(['user', 'absensi', 'logbook'])
            ->where('status', 'diterima')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                      ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('tanggal_mulai', '<=', $startDate)
                            ->where('tanggal_selesai', '>=', $endDate);
                      });
            })
            ->get();

        $data = [];
        foreach ($mahasiswas as $mahasiswa) {
            // Hitung statistik absensi
            $totalAbsensi = $mahasiswa->absensi()
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count();
            
            $hadirCount = $mahasiswa->absensi()
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where(function($query) {
                    $query->where('pagi', 'hadir')
                          ->orWhere('siang', 'hadir')
                          ->orWhere('sore', 'hadir');
                })
                ->count();

            // Hitung total logbook
            $totalLogbook = $mahasiswa->logbook()
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count();

            $data[] = [
                'nama' => $mahasiswa->nama_lengkap,
                'nis_nim' => $mahasiswa->nis_nim,
                'asal_sekolah' => $mahasiswa->asal_sekolah,
                'jurusan' => $mahasiswa->jurusan,
                'tanggal_mulai' => $mahasiswa->tanggal_mulai->format('d/m/Y'),
                'tanggal_selesai' => $mahasiswa->tanggal_selesai->format('d/m/Y'),
                'total_absensi' => $totalAbsensi,
                'hadir_count' => $hadirCount,
                'persentase_kehadiran' => $totalAbsensi > 0 ? round(($hadirCount / $totalAbsensi) * 100, 2) : 0,
                'total_logbook' => $totalLogbook,
                'evaluasi' => $this->generateEvaluasi($hadirCount, $totalAbsensi, $totalLogbook)
            ];
        }

        return $data;
    }

    private function generateEvaluasi($hadirCount, $totalAbsensi, $totalLogbook)
    {
        $kehadiran = $totalAbsensi > 0 ? ($hadirCount / $totalAbsensi) * 100 : 0;
        
        if ($kehadiran >= 90 && $totalLogbook >= 20) {
            return 'Sangat Baik';
        } elseif ($kehadiran >= 80 && $totalLogbook >= 15) {
            return 'Baik';
        } elseif ($kehadiran >= 70 && $totalLogbook >= 10) {
            return 'Cukup';
        } else {
            return 'Perlu Perbaikan';
        }
    }
}
