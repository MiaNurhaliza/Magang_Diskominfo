<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biodata;
use App\Models\Pendaftar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPesertaAktifController extends Controller
{
    public function index()
    {
        $pesertas = biodata::where ('status', 'diterima')->paginate(10);
        
        return view('backend.peserta_aktif.index', compact('pesertas'));
    }
    public function destroy($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->delete();
        return redirect()->route('admin.pendaftar.index')->with('success', 'Data berhasil dihapus.');
    }

    public function edit ($id)
    {
        $peserta = biodata::with ('dokumen')->findOrFail($id);
        return view('backend.peserta_aktif.edit', compact('peserta'));
    }

    public function update(Request $request, $id)
    {
        $peserta = Biodata::findOrFail($id);
        
        // Debug: Log semua input yang diterima
        \Log::info('Update request data: ', $request->all());
        \Log::info('Password filled: ' . ($request->filled('password') ? 'Yes' : 'No'));
        
        // Validasi input
        $validationRules = [
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ];
        
        // Tambahkan validasi password hanya jika password diisi
        if ($request->filled('password')) {
            $validationRules['password'] = 'required|string|min:8|confirmed';
        }
        
        $request->validate($validationRules);
        
        // Update data biodata hanya jika field diisi
        $updateData = [];
        if ($request->filled('tanggal_mulai')) {
            $updateData['tanggal_mulai'] = $request->tanggal_mulai;
        }
        if ($request->filled('tanggal_selesai')) {
            $updateData['tanggal_selesai'] = $request->tanggal_selesai;
        }
        
        if (!empty($updateData)) {
            $peserta->update($updateData);
        }
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $user = User::find($peserta->user_id);
            if ($user) {
                // Force password update dengan hash baru
                $hashedPassword = Hash::make($request->password);
                
                // Update menggunakan query builder untuk memastikan update langsung ke database
                \DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => $hashedPassword, 'updated_at' => now()]);
                
                \Log::info('Password updated for user ID: ' . $user->id . ' with new hash');
            }
        }
        
        $message = 'Data peserta berhasil diperbarui.';
        if ($request->filled('password')) {
            $message .= ' Password juga berhasil diperbarui.';
        }
        
        return redirect()->route('admin.peserta-aktif')->with('success', $message);
    }
}
