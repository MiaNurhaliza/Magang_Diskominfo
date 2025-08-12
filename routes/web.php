<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\BiodataController;
use App\Http\Controllers\User\DokumenController;
use App\Http\Controllers\User\Statuscontroller;
use App\Http\Controllers\User\AbsensiController;
use App\Http\Controllers\User\Logincontroller;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPendaftarController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPesertaAktifController;
use App\Http\Controllers\Admin\AdminAbsensiController;
use App\Http\Controllers\Admin\LogbookBackendController;
use App\Http\Controllers\Admin\LaporanBackendController;
use App\Http\Controllers\Admin\SertifikatBackendController;
use App\Http\Controllers\Auth\RegisterController;



Route::get('/landing', function () {
    return view('landing.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dokumen', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/biodata', [BiodataController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [BiodataController::class, 'store'])->name('biodata.store');
    Route::get('/biodata/edit', [\App\Http\Controllers\User\BiodataController::class, 'edit'])->name('biodata.edit');
    Route::put('/biodata/{biodatum}', [BiodataController::class, 'update'])->name('biodata.update');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/status-pendaftaran', [StatusController::class, 'index'])
    ->name('pendaftaran.status');
Route::post('/status/konfirmasi/{id}', [\App\Http\Controllers\User\StatusController::class, 'konfirmasiKetersediaan'])
    ->name('pendaftaran.konfirmasi');
    
    Route::get('/peserta/dashboard', [\App\Http\Controllers\User\PesertaController::class, 'index'])
    ->name('peserta.dashboard');


//Route::get('/status-pendaftaran', [StatusController::class, 'status'])->name('pendaftaran.status');
Route::post('/pendaftaran/konfirmasi/{id}', [StatusController::class, 'konfirmasiKetersediaan'])->name('pendaftaran.konfirmasi');
Route::get('/pendaftaran/unduh-surat/{id}', [StatusController::class, 'unduhSurat'])->name('pendaftaran.unduh_surat');


//PESERTA MAGANG (yang telah diterima)
 Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

//   Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('index');
    Route::post('/masuk', [AbsensiController::class, 'masuk'])->name('masuk');
    Route::post('/keluar', [AbsensiController::class, 'keluar'])->name('keluar');
});
Route::post('/absensi/masuk', [AbsensiController::class, 'masuk'])->name('absensi.masuk');

// Absensi pulang
Route::post('/absensi/pulang', [AbsensiController::class, 'pulang'])->name('absensi.pulang');
    Route::get('/logbook', [LogbookController::class, 'index'])->name('upload_logbook_harian');
    Route::get('/laporan-akhir', [LaporanAkhirController::class, 'index'])->name('upload_laporan_akhir');
    Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('unduh_sertifikat');

});
Route::prefix('user')->name('user.')->group(function () {
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
});


//  Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    //biodata lengkapi
    
    // Route::get('/biodata/edit', [BiodataController::class, 'edit'])->name('biodata.edit');
    // Route::put('/biodata/update', [BiodataController::class, 'update'])->name('biodata.update');


    // Route::get('/dokumen', [DokumenController::class, 'create'])->name('dokumen.create');
    // Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');


    //status pendaftaran

        // Route::get('/status-pendaftaran',[StatusController::class, 'status'])->name('pendaftaran.status');
        // Route::post('/kofirmasi-ketersediaan/{id}', [StatusController::class, 'konfirmasiKetersediaan'])->name('status.konfirmasi');
        // Route::get('/unduh-surat/{id}',[StatusController::class,'unduhSurat'])->name('status.unduh_surat');
    
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

// });


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


    //admin
    Route::patch('/admin/pendaftar/{id}/update-status', [AdminPendaftarController::class, 'updateStatus'])->name('admin.pendaftar.updateStatus');
    Route::delete('/admin/pendaftar/{id}', [AdminPendaftarController::class, 'destroy'])->name('admin.pendaftar.delete');
    Route::patch('/admin/pendaftar/{id}/update-jadwal', [AdminPendaftarController::class, 'updateJadwal'])->name('admin.pendaftar.updateJadwal');


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

    // Tampilkan tabel sertifikat
 Route::get('/sertifikat', [SertifikatBackendController::class, 'index'])->name('admin.sertifikat');
    Route::get('/sertifikat/{id}/edit', [SertifikatBackendController::class, 'edit'])->name('admin.sertifikat.edit');
    Route::put('/sertifikat/{id}', [SertifikatBackendController::class, 'update'])->name('admin.sertifikat.update');
    Route::get('/sertifikat/{id}/download', [SertifikatBackendController::class, 'download'])->name('admin.sertifikat.download');
    
    
    });


Route::get('/tes-login', function () {
    return view('auth.login');
});



 require __DIR__.'/auth.php';
