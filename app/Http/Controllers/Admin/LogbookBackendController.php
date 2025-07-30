<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\logbook;
use Illuminate\Http\Request;

class LogbookBackendController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::with('user')->latest('tanggal')->get();
        return view('admin.logbook.index', compact('logbooks'));
    }
}
