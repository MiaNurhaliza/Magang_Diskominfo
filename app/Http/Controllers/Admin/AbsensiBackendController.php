<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiBackendController extends Controller
{
    public function index()
    {
        $absensis = Absensi::with('user')->latest('tanggal')->get();
        return view('admin.absensi.index', compact('absensis'));
    }
}
