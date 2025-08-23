<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use App\Models\Biodata;
use App\Services\SertifikatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatBackendController extends Controller
{
    protected $sertifikatService;

    public function __construct(SertifikatService $sertifikatService)
    {
        $this->sertifikatService = $sertifikatService;
    }

    public function index() {
        // Ambil semua user yang memiliki biodata (peserta magang) dengan status Diterima
        $users = \App\Models\User::whereHas('biodata', function($query) {
                $query->where('status', 'Diterima');
            })
            ->with(['biodata', 'sertifikat', 'laporanAkhir'])
            ->paginate(10);
        return view('backend.sertifikat.index', compact('users'));
    }

    public function generate($userId) {
        try {
            // Cek apakah biodata sudah lengkap dan status diterima
            $biodata = Biodata::where('user_id', $userId)->first();
            
            if (!$biodata) {
                return back()->with('error', 'Biodata peserta tidak ditemukan.');
            }
            
            if ($biodata->status !== 'Diterima') {
                return back()->with('error', 'Sertifikat hanya dapat dibuat untuk peserta dengan status Diterima.');
            }
            
            // Cek apakah sertifikat sudah pernah dibuat
            $existingSertifikat = Sertifikat::where('user_id', $userId)->first();
            
            if ($existingSertifikat) {
                return back()->with('info', 'Sertifikat untuk peserta ini sudah pernah dibuat.');
            }
            
            // Generate sertifikat baru
            $result = $this->sertifikatService->generateSertifikat($userId);
            
            // Simpan record sertifikat ke database
            Sertifikat::create([
                'user_id' => $userId,
                'nomor_sertifikat' => $result['nomor_sertifikat'],
                'tanggal_mulai' => $biodata->tanggal_mulai,
                'tanggal_selesai' => $biodata->tanggal_selesai,
                'file_sertifikat' => $result['file_path']
            ]);
            
            return back()->with('success', 'Sertifikat berhasil dibuat dan siap diunduh.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat sertifikat: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $sertifikat = Sertifikat::findOrFail($id);
        return response()->download(storage_path("app/public/{$sertifikat->file_sertifikat}"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'file_sertifikat' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        try {
            // Cek apakah sertifikat sudah ada
            $existingSertifikat = Sertifikat::where('user_id', $request->user_id)->first();
            if ($existingSertifikat) {
                return back()->with('error', 'Sertifikat untuk peserta ini sudah ada.');
            }

            // Upload file
            $file = $request->file('file_sertifikat');
            $fileName = 'sertifikat_' . $request->user_id . '_' . time() . '.pdf';
            $filePath = $file->storeAs('sertifikat', $fileName, 'public');

            // Generate nomor sertifikat
            $tahun = date('Y');
            $bulan = date('m');
            $count = Sertifikat::whereYear('created_at', $tahun)
                              ->whereMonth('created_at', $bulan)
                              ->count();
            $nomorUrut = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            $nomorSertifikat = $nomorUrut . '/' . $bulan . '/' . $tahun;

            // Simpan ke database
            Sertifikat::create([
                'user_id' => $request->user_id,
                'nomor_sertifikat' => $nomorSertifikat,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'file_sertifikat' => $filePath
            ]);

            return back()->with('success', 'Sertifikat berhasil diupload.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload sertifikat: ' . $e->getMessage());
        }
    }

}
