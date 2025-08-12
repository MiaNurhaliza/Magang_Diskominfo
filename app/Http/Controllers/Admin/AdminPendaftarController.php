<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biodata;

class AdminPendaftarController extends Controller
{
    public function index()
    {
        $pendaftars = Biodata::with(['user', 'dokumen'])->latest()->get();
        return view('backend.pendaftar.index', compact('pendaftars'));
    }

    public function show($id)
    {
        $pendaftar = Biodata::findOrFail($id);
        return view('backend.pendaftar.show', compact('pendaftar'));
    }

    public function destroy($id)
    {
        $pendaftar = Biodata::findOrFail($id);
        $pendaftar->delete();

        return redirect()->route('pendaftar.index')
                         ->with('success', 'Data berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string', // diproses, diterima, ditolak, jadwal_dialihkan
            'alasan' => 'nullable|string',
            'surat_balasan' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $pendaftar = Biodata::findOrFail($id);

        // Simpan status
        $pendaftar->status = $request->status;

        // Kalau status jadwal_dialihkan → wajib alasan & reset ketersediaan
        if ($request->status === 'jadwal_dialihkan') {
            $pendaftar->alasan = $request->alasan;
            $pendaftar->ketersediaan = null; // reset biar user bisa konfirmasi
        } else {
            $pendaftar->alasan = $request->alasan;
        }

        // Upload surat balasan jika ada
        if ($request->hasFile('surat_balasan')) {
            $file = $request->file('surat_balasan')->store('surat_balasan', 'public');
            $pendaftar->surat_balasan = $file;
        }

        $pendaftar->save();

        return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    public function updateJadwal(Request $request, $id)
{
    $request->validate([
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    ]);

    $pendaftar = Biodata::findOrFail($id);
    $pendaftar->tanggal_mulai = $request->tanggal_mulai;
    $pendaftar->tanggal_selesai = $request->tanggal_selesai;
    $pendaftar->status = 'Jadwal Dirubah';
    $pendaftar->save();

    return redirect()->back()->with('success', 'Jadwal magang berhasil diperbarui.');
}

}
