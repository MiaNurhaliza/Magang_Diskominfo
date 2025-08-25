<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PembimbingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembimbings = Pembimbing::with('user')->paginate(10);
        return view('admin.pembimbing.index', compact('pembimbings'));
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:pembimbings,nip',
            'jabatan' => 'required|string|max:255',
            'bidang_keahlian' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembimbing',
        ]);

        // Create pembimbing profile
        Pembimbing::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'bidang_keahlian' => $request->bidang_keahlian,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('admin.pembimbing.index')
            ->with('success', 'Pembimbing berhasil ditambahkan.');
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
     * Show the form for editing the specified resource.
     */
    public function edit(Pembimbing $pembimbing)
    {
        return view('admin.pembimbing.edit', compact('pembimbing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembimbing $pembimbing)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($pembimbing->user_id)],
            'nip' => ['required', 'string', Rule::unique('pembimbings', 'nip')->ignore($pembimbing->id)],
            'jabatan' => 'required|string|max:255',
            'bidang_keahlian' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user account
        $userData = [
            'name' => $request->nama,
            'email' => $request->email,
        ];
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $pembimbing->user->update($userData);

        // Update pembimbing profile
        $pembimbing->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'bidang_keahlian' => $request->bidang_keahlian,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('admin.pembimbing.index')
            ->with('success', 'Data pembimbing berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembimbing $pembimbing)
    {
        // Delete user account and pembimbing profile
        $pembimbing->user->delete();
        $pembimbing->delete();

        return redirect()->route('admin.pembimbing.index')
            ->with('success', 'Pembimbing berhasil dihapus.');
    }

    /**
     * Assign mahasiswa to pembimbing
     */
    public function assignMahasiswa(Request $request, Pembimbing $pembimbing)
    {
        $request->validate([
            'mahasiswa_ids' => 'required|array',
            'mahasiswa_ids.*' => 'exists:biodatas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        foreach ($request->mahasiswa_ids as $mahasiswaId) {
            $pembimbing->mahasiswas()->attach($mahasiswaId, [
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_bimbingan' => 'aktif',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Mahasiswa berhasil ditugaskan ke pembimbing.');
    }
}
