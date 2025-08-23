<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;

class IzinBackendController extends Controller
{
    public function index()
    {
        $izins = Izin::with('user')->latest('tanggal')->paginate(10);
        return view('admin.izin.index', compact('izins'));
    }

    public function download($id)
    {
        $izin = Izin::findOrFail($id);
        return response()->download(storage_path("app/public/{$izin->file}"));
    }
}
