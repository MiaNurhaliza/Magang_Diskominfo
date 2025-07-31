<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\BiodataController;
use App\Http\Controllers\User\DokumenController;
use App\Http\Controllers\User\Statuscontroller;
use App\Http\Controllers\User\Logincontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPendaftarController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPesertaAktifController;
use App\Http\Controllers\Admin\AdminAbsensiController;
use App\Http\Controllers\Admin\LogbookBackendController;
use App\Http\Controllers\Admin\LaporanBackendController;
use App\Http\Controllers\Admin\SertifikatBackendController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


Route::get('/landing', function () {
    return view('landing.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



 Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    //biodata lengkapi
    Route::get('/biodata', [BiodataController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [BiodataController::class, 'store'])->name('biodata.store');
    Route::get('/dokumen', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');

    //status pendaftaran

        Route::get('/status-pendaftaran',[StatusController::class, 'status'])->name('pendaftaran.status');
        Route::post('/kofirmasi-ketersediaan/{id}', [StatusController::class, 'konfirmasiKetersediaan'])->name('status.konfirmasi');
        Route::get('/unduh-surat/{id}',[StatusController::class,'unduhSurat'])->name('status.unduh_surat');
    
    //peserta
        //Route::get('/absensi', [AbsensiController::class, 'index'])->name('user.absensi');
        //Route::post('/absensi', [AbsensiController::class, 'store']);

        // Route::get('/izin', [IzinController::class, 'index'])->name('user.izin');
        // Route::post('/izin', [IzinController::class, 'store']);

        //Route::resource('/logbook', LogbookController::class)->names('user.logbook');
        //Route::resource('/laporan', LaporanAkhirController::class)->names('user.laporan');
        //Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('user.sertifikat');
        //Route::get('/sertifikat/download', [SertifikatController::class, 'download'])->name('user.sertifikat.download');


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


//Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


    //admin
    Route::patch('/admin/pendaftar/{id}/update-status', [AdminPendaftarController::class, 'updateStatus'])->name('admin.pendaftar.updateStatus');
    Route::delete('/admin/pendaftar/{id}', [AdminPendaftarController::class, 'destroy'])->name('admin.pendaftar.delete');

    Route::get('/peserta-aktif', [AdminPesertaAktifController::class, 'index'])->name('admin.peserta-aktif');
    Route::patch('/admin/peserta-aktif/{id}/update-status', [AdminPesertaAktifController::class, 'updateStatus'])->name('admin.peserta-aktif.updateStatus');
    //Route::get('/peserta-aktif', [AdminPesertaAktifController::class, 'index'])->name('peserta-aktif.index');
    Route::get('/peserta-aktif/{id}/edit', [AdminPesertaAktifController::class, 'edit'])->name('admin.peserta-aktif.edit');
    Route::patch('/peserta-aktif/{id}', [AdminPesertaAktifController::class, 'update'])->name('admin.peserta-aktif.update');


    Route::get('/pendaftar', [AdminPendaftarController::class, 'index'])->name('admin.pendaftar');
    Route::get('/absensi', [AdminAbsensiController::class, 'index'])->name('admin.absensi');
    Route::get('/absensi/{id}', [AdminAbsensiController::class, 'destroy'])->name('admin.absensi.destroy');
    // Route::get('/izin', [Admin\IzinBackendController::class, 'index'])->name('admin.izin');
    Route::get('/logbook', [LogbookBackendController::class, 'index'])->name('admin.logbook.index');
    Route::delete('/logbook/{id}', [LogbookBackendController::class, 'destroy'])->name('admin.logbook.destroy');
   
    Route::get('/laporan', [LaporanBackendController::class, 'index'])->name('admin.laporan');
    //Route::get('/laporan/download/{type}/{id}', [LaporanBackendController::class, 'downloadFile'])->name('admin.laporan.download');
    Route::get('/admin/laporan/{id}/edit', [LaporanBackendController::class, 'edit'])->name('admin.laporan.edit');
    Route::delete('admin/laporan/{laporan}', [LaporanBackendController::class, 'destroy'])->name('admin.laporan.destroy');

    Route::get('/sertifikat', [SertifikatBackendController::class, 'index'])->name('admin.sertifikat');
    Route::get('/admin/sertifikat/{id}/edit', [SertifikatBackendController::class, 'edit'])->name('admin.sertifikat.edit');
    Route::get('/laporan/{id}', [SertifikatBackendController::class, 'destroy'])->name('admin.sertifikat.destroy');
    
    // Route::get('/laporan', [Admin\LaporanBackendController::class, 'index'])->name('admin.laporan');
   
    // Route::post('/sertifikat/upload/{id}', [Admin\SertifikatBackendController::class, 'upload'])->name('admin.sertifikat.upload');
    
    });


Route::get('/tes-login', function () {
    return view('auth.login');
});



 require __DIR__.'/auth.php';
