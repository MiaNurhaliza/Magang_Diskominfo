<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\BiodataController;
use App\Http\Controllers\User\DokumenController;
use App\Http\Controllers\User\Statuscontroller;
use App\Http\Controllers\User\Logincontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPendaftarController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/landing', function () {
    return view('landing.index');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    //biodata lengkapi
    Route::get('/biodata', [BiodataController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [BiodataController::class, 'store'])->name('biodata.store');
    Route::get('/dokumen', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');

    //status pendaftaran

        Route::get('/status-pendaftaran',[StatusController::class, 'status'])->name('pendaftaran.status');
        Route::post('/kofirmasi-ketersediaan/{id}', [StatusController::class, 'konfirmasiKetersediaan'])->name('status.konfirmasi');
        Route::get('/unduh-surat/{id}',[StatusController::class,'unduhSurat'])->name('status.unduh_surat');
    
    //Login
        // Route::get('/login',[LoginController::class, 'showLoginForm']) ->name('login');
        // Route::post('/login',[LoginController::class, 'login'])->name('login.custom');

// Route::get('/dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');

    
//Route::get('/biodata', [BiodataController::class, 'create']);

});

//Root Admin

// Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
//     Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])->name('admin.pendaftaran.index');
// });


Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('pendaftar', App\Http\Controllers\Admin\AdminPendaftarController::class);
});


Route::get('/tes-login', function () {
    return view('auth.login');
});



 require __DIR__.'/auth.php';
