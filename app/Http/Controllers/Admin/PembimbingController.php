<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\User;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PembimbingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $query = Pembimbing::with(['user', 'mahasiswas'])
            ->withCount('mahasiswas');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                  
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Departemen filter
        // if ($request->has('departemen') && $request->departemen) {
        //     $query->where('departemen', $request->departemen);
        // }

        // Handle export
        if ($request->has('export')) {
            return $this->export($request->export, $query->get());
        }

        $pembimbings = $query->orderBy('nama')->paginate(10);

        // Statistics
        $totalPembimbing = Pembimbing::count();
        $pembimbingAktif = Pembimbing::where('status', 'aktif')->count();
        $totalMahasiswa = Biodata::count();
        $mahasiswaBelumDibimbing = Biodata::whereDoesntHave('pembimbings')->count();

        // Get unique departments for filter
        //$departemens = Pembimbing::distinct()->pluck('departemen')->filter()->sort()->values();

        // Get available mahasiswa for assignment (only those with status 'Diterima' and no pembimbing)
        $mahasiswasAvailable = Biodata::where('status', 'Diterima')
            ->whereDoesntHave('pembimbings')
            ->get();

        return view('admin.pembimbing.index', compact(
            'pembimbings',
            'totalPembimbing',
            'pembimbingAktif', 
            'totalMahasiswa',
            'mahasiswaBelumDibimbing',
            // 'departemens',
            'mahasiswasAvailable'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pembimbing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Form submitted with data:', $request->all());
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        
        \Log::info('Validation passed');

        DB::beginTransaction();
        try {
            // Buat user account terlebih dahulu
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pembimbing',
            ]);

            // Buat record pembimbing
            $pembimbing = Pembimbing::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'email' => $request->email,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('admin.pembimbing.index')
                           ->with('success', 'Pembimbing berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan pembimbing: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembimbing $pembimbing)
    {
        $pembimbing->load(['user', 'mahasiswas']);
        return view('admin.pembimbing.show', compact('pembimbing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembimbing $pembimbing)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pembimbing->user_id)],
            // 'telepon' => 'nullable|string|max:20',
            // 'departemen' => 'required|string|max:255',
            // 'jabatan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        try {
            DB::beginTransaction();

            // Update user account
            $pembimbing->user->update([
                'name' => $request->nama,
                'email' => $request->email
            ]);

            // Update pembimbing record
            $pembimbing->update([
                'nama' => $request->nama,
                'email' => $request->email,
                // 'telepon' => $request->telepon,
                // 'departemen' => $request->departemen,
                // 'jabatan' => $request->jabatan,
                'status' => $request->status
            ]);

            DB::commit();

            return redirect()->route('admin.pembimbing.index')
                ->with('success', 'Data pembimbing berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data pembimbing: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembimbing $pembimbing)
    {
        try {
            DB::beginTransaction();

            // Check if pembimbing has active mahasiswa
            if ($pembimbing->mahasiswas()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pembimbing yang masih memiliki mahasiswa bimbingan.');
            }

            // Delete pembimbing record
            $pembimbing->delete();

            // Delete user account
            $pembimbing->user->delete();

            DB::commit();

            return redirect()->route('admin.pembimbing.index')
                ->with('success', 'Pembimbing berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pembimbing: ' . $e->getMessage());
        }
    }

    /**
     * Assign mahasiswa to pembimbing
     */
    public function assignMahasiswa(Request $request, Pembimbing $pembimbing)
    {
        $request->validate([
            'mahasiswa_ids' => 'required|array',
            'mahasiswa_ids.*' => 'exists:biodatas,id'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->mahasiswa_ids as $mahasiswaId) {
                // Check if mahasiswa is already assigned to this pembimbing
                if (!$pembimbing->mahasiswas()->where('biodata_id', $mahasiswaId)->exists()) {
                    $pembimbing->mahasiswas()->attach($mahasiswaId, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();

            $count = count($request->mahasiswa_ids);
            return redirect()->route('admin.pembimbing.index')
                ->with('success', "Berhasil meng-assign {$count} mahasiswa ke pembimbing {$pembimbing->nama}.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat meng-assign mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Remove mahasiswa from pembimbing
     */
    public function removeMahasiswa(Request $request, Pembimbing $pembimbing)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:biodatas,id'
        ]);

        try {
            $pembimbing->mahasiswas()->detach($request->mahasiswa_id);

            return redirect()->back()
                ->with('success', 'Mahasiswa berhasil dihapus dari bimbingan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus mahasiswa dari bimbingan: ' . $e->getMessage());
        }
    }

    /**
     * Export data
     */
    private function export($type, $data)
    {
        // Export functionality disabled - Excel/PDF packages not installed
        return redirect()->back()->with('error', 'Export functionality is currently disabled.');
    }

    /**
     * Get pembimbing statistics for dashboard
     */
    public function getStatistics()
    {
        $stats = [
            'total_pembimbing' => Pembimbing::count(),
            'pembimbing_aktif' => Pembimbing::where('status', 'aktif')->count(),
            'total_mahasiswa_bimbingan' => DB::table('biodata_pembimbing')->count(),
            'rata_rata_mahasiswa_per_pembimbing' => round(
                DB::table('biodata_pembimbing')->count() / max(Pembimbing::count(), 1), 2
            )
        ];

        return response()->json($stats);
    }

    /**
     * Search pembimbing for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $pembimbings = Pembimbing::where('nama', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nama', 'email']);

        return response()->json($pembimbings);
    }

    /**
     * Get pembimbing mahasiswa for specific pembimbing
     */
    public function getMahasiswa(Pembimbing $pembimbing)
    {
        $mahasiswas = $pembimbing->mahasiswas()->with(['user'])->get();
        return response()->json($mahasiswas);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'pembimbing_ids' => 'required|array',
            'pembimbing_ids.*' => 'exists:pembimbings,id'
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($request->pembimbing_ids as $id) {
                $pembimbing = Pembimbing::find($id);
                if (!$pembimbing) continue;

                switch ($request->action) {
                    case 'activate':
                        $pembimbing->update(['status' => 'aktif']);
                        $count++;
                        break;
                    case 'deactivate':
                        $pembimbing->update(['status' => 'nonaktif']);
                        $count++;
                        break;
                    case 'delete':
                        if ($pembimbing->mahasiswas()->count() === 0) {
                            $pembimbing->user->delete();
                            $pembimbing->delete();
                            $count++;
                        }
                        break;
                }
            }

            DB::commit();

            $actionText = [
                'activate' => 'diaktifkan',
                'deactivate' => 'dinonaktifkan', 
                'delete' => 'dihapus'
            ];

            return redirect()->back()
                ->with('success', "{$count} pembimbing berhasil {$actionText[$request->action]}.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat melakukan bulk action: ' . $e->getMessage());
        }
    }
}