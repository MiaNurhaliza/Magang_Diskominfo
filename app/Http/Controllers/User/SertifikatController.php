<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function index () {
        $sertifikat = Sertifikat::where ('user_id',Auth::id())->first();
        return view('user.sertifikat.index',compact('sertifikat'));
    }

    public function download () {
        $sertifikat = Sertifikat::where('user_id',Auth::id())->firstOrFail();
        return response()->download(storage_path('app/pulic/' . $sertifikat->file_sertifikat));
    }
}
