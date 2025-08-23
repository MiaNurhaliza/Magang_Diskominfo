<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;

class LogbookBackendController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::with('user')->latest('tanggal')->paginate(10);
        return view('backend.logbook.index', compact('logbooks'));
    }
    public function destroy($id)
    {
        $logbooks = Logbook::findOrFail($id);
        $logbooks->delete();
        return redirect()->route('admin.logbook.index')->with('success', 'Data logbook berhasil dihapus.');
    }
}
