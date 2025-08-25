<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\BiodataController;
use App\Http\Controllers\User\DokumenController;
use App\Http\Controllers\User\Statuscontroller;
use App\Http\Controllers\User\AbsensiController;
use App\Http\Controllers\User\Logincontroller;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\LogbookController;
use App\Http\Controllers\User\LaporanAkhirController;
use App\Http\Controllers\User\SertifikatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPendaftarController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPesertaAktifController;
use App\Http\Controllers\Admin\AdminAbsensiController;
use App\Http\Controllers\Admin\LogbookBackendController;
use App\Http\Controllers\Admin\LaporanBackendController;
use App\Http\Controllers\Admin\SertifikatBackendController;
use App\Http\Controllers\Admin\LaporanTriwulanController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\PembimbingController;
use App\Http\Controllers\Pembimbing\DashboardController as PembimbingDashboardController;
use App\Http\Controllers\Pembimbing\AbsensiController as PembimbingAbsensiController;
use App\Http\Controllers\Pembimbing\LogbookController as PembimbingLogbookController;
use App\Http\Controllers\Pembimbing\LaporanAkhirController as PembimbingLaporanAkhirController;



Route::get('/', function () {
    return view('landing.index');
});

Route::get('/test-mail', function () {
    Mail::raw('Tes kirim email dari SIMADIS', function ($m) {
        $m->to('nurhalizamia5@gmail.com')->subject('Tes SIMADIS');
    });
    return 'OK';
});

Route::get('/', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'pembimbing') {
        return redirect()->route('pembimbing.dashboard');
    }
    return redirect()->route('peserta.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes - Remove duplicate dashboard route

// Pembimbing Routes
Route::middleware(['auth', 'pembimbing'])->prefix('pembimbing')->name('pembimbing.')->group(function () {
    Route::get('/dashboard', [PembimbingDashboardController::class, 'index'])->name('dashboard');
    Route::get('/absensi', [PembimbingAbsensiController::class, 'index'])->name('absensi');
    Route::get('/logbook', [PembimbingLogbookController::class, 'index'])->name('logbook');
    Route::get('/laporan-akhir', [PembimbingLaporanAkhirController::class, 'index'])->name('laporan-akhir');
    
    // Export routes
    Route::get('/absensi/export/{type}', [PembimbingAbsensiController::class, 'export'])->name('absensi.export');
    Route::get('/logbook/export/{type}', [PembimbingLogbookController::class, 'export'])->name('logbook.export');
    Route::get('/laporan-akhir/export/{type}', [PembimbingLaporanAkhirController::class, 'export'])->name('laporan-akhir.export');
    
    // Review routes
    Route::post('/logbook/{logbook}/review', [PembimbingLogbookController::class, 'review'])->name('logbook.review');
    Route::post('/laporan-akhir/{laporan}/review', [PembimbingLaporanAkhirController::class, 'review'])->name('laporan-akhir.review');
    Route::post('/laporan-akhir/{laporan}/approve', [PembimbingLaporanAkhirController::class, 'approve'])->name('laporan-akhir.approve');
    Route::post('/laporan-akhir/{laporan}/revise', [PembimbingLaporanAkhirController::class, 'revise'])->name('laporan-akhir.revise');
    
    // Delete routes
    Route::delete('/absensi/{absensi}', [PembimbingAbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::delete('/laporan-akhir/{laporan}', [PembimbingLaporanAkhirController::class, 'destroy'])->name('laporan-akhir.destroy');
    
    // AJAX routes
    Route::get('/mahasiswa/{mahasiswa}/stats', [PembimbingDashboardController::class, 'getMahasiswaStats'])->name('mahasiswa.stats');
});

// Admin - Pembimbing Management Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('pembimbing', PembimbingController::class);
    Route::post('/pembimbing/{pembimbing}/assign-mahasiswa', [PembimbingController::class, 'assignMahasiswa'])->name('pembimbing.assign-mahasiswa');
});

//Register routes (accessible to guests)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dokumen', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/biodata', [BiodataController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [BiodataController::class, 'store'])->name('biodata.store');
    //Route::get('/biodata/edit', [\App\Http\Controllers\User\BiodataController::class, 'edit'])->name('biodata.edit');
    Route::get('/biodata/edit', [BiodataController::class, 'edit'])->name('biodata.edit');
    Route::put('/biodata/{biodatum}', [BiodataController::class, 'update'])->name('biodata.update');

    //Status pendaftaran
    Route::get('/status', [StatusController::class, 'index'])->name('pendaftaran.status');
    Route::post('/status/konfirmasi/{id}', [\App\Http\Controllers\User\StatusController::class, 'konfirmasiKetersediaan'])
        ->name('pendaftaran.konfirmasi');
    Route::get('/pendaftaran/unduh-surat/{id}', [StatusController::class, 'unduhSurat'])->name('pendaftaran.unduh_surat');
    
    Route::get('/peserta/dashboard', [\App\Http\Controllers\User\PesertaController::class, 'index'])
        ->name('peserta.dashboard');


//PESERTA MAGANG (yang telah diterima)
 Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

//   Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('index');
    Route::post('/masuk', [AbsensiController::class, 'masuk'])->name('absensi.masuk');
    Route::post('/keluar', [AbsensiController::class, 'keluar'])->name('absensi.keluar');
});
Route::post('/absensi/masuk', [AbsensiController::class, 'masuk'])->name('absensi.masuk');
Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
Route::post('/absensi/pulang', [AbsensiController::class, 'pulang'])->name('absensi.pulang');
Route::post('/izin/store', [AbsensiController::class, 'storeIzin'])->name('izin.store');

    Route::get('/logbook', [LogbookController::class, 'index'])->name('upload_logbook_harian');
    Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store');
    Route::get('/logbook/{id}/edit', [LogbookController::class, 'edit'])->name('logbook.edit');
    Route::put('/logbook/{id}', [LogbookController::class, 'update'])->name('logbook.update');
    Route::delete('/logbook/{id}', [LogbookController::class, 'destroy'])->name('logbook.destroy');

    Route::get('/laporan-akhir', [LaporanAkhirController::class, 'index'])->name('upload_laporan_akhir');
    Route::post('/laporan-akhir', [LaporanAkhirController::class, 'store'])->name('laporan-akhir.store');
    Route::get('/laporan-akhir/download/{type}', [LaporanAkhirController::class, 'download'])->name('laporan-akhir.download');
    Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('unduh_sertifikat');
       //Route::get('/sertifikat', [SertifikatController::class, 'index'])->name('unduh_sertifikat');
    Route::get('/sertifikat/generate', [SertifikatController::class, 'generate'])->name('sertifikat.generate');
    Route::get('/sertifikat/download', [SertifikatController::class, 'download'])->name('sertifikat.download');

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


Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
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
  Route::post('/sertifikat', [SertifikatBackendController::class, 'store'])->name('admin.sertifikat.store');
    Route::post('/sertifikat/generate/{userId}', [SertifikatBackendController::class, 'generate'])->name('admin.sertifikat.generate');
    Route::get('/sertifikat/{id}/download', [SertifikatBackendController::class, 'download'])->name('admin.sertifikat.download');

    // Laporan Triwulanan Routes
    Route::prefix('laporan-triwulan')->name('admin.laporan-triwulan.')->group(function () {
        Route::get('/', [LaporanTriwulanController::class, 'index'])->name('index');
        Route::get('/create', [LaporanTriwulanController::class, 'create'])->name('create');
        Route::post('/', [LaporanTriwulanController::class, 'store'])->name('store');
        Route::get('/{laporanTriwulan}', [LaporanTriwulanController::class, 'show'])->name('show');
        Route::get('/{laporanTriwulan}/edit', [LaporanTriwulanController::class, 'edit'])->name('edit');
        Route::put('/{laporanTriwulan}', [LaporanTriwulanController::class, 'update'])->name('update');
        Route::delete('/{laporanTriwulan}', [LaporanTriwulanController::class, 'destroy'])->name('destroy');
        Route::get('/{laporanTriwulan}/download', [LaporanTriwulanController::class, 'downloadPdf'])->name('download');
        Route::post('/{laporanTriwulan}/regenerate', [LaporanTriwulanController::class, 'regenerateData'])->name('regenerate');

    });

    
    });


Route::get('/tes-login', function () {
    return view('auth.login');
});



 require __DIR__.'/auth.php';
