<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ParamedisController;
use App\Http\Controllers\PelayananController;
use App\Http\Controllers\JenisHewanController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\AnamnesaController;
use App\Http\Controllers\SurveilansController;
use App\Http\Controllers\ObatController;

Route::redirect('/', '/login');
// Route untuk Halaman Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route untuk Halaman yang Membutuhkan Login (Auth Umum)
Route::middleware('auth')->group(function () {
    
    // DIAKSES ADMIN & OPERATOR
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Modul Transaksi (Akses Operator & Admin)
    Route::get('/rekam-medis/search', [RekamMedisController::class, 'search'])->name('rekam-medis.search');
    Route::get('/surveilans', [SurveilansController::class, 'index'])->name('surveilans.index');
    // Standar resource controller
    Route::resource('rekam-medis', RekamMedisController::class);

    // HANYA BISA DIAKSES ADMIN ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('dokter', DokterController::class);
        Route::resource('paramedis', ParamedisController::class);
        Route::resource('pelayanan', PelayananController::class)
            ->parameters(['pelayanan' => 'id_pelayanan'])
            ->except(['show', 'create', 'edit']);
        Route::resource('jenis-hewan', JenisHewanController::class)
            ->parameters(['jenis-hewan' => 'id_jenis'])
            ->names('jenis_hewan')
            ->except(['show', 'create', 'edit']);
        Route::resource('diagnosa', DiagnosaController::class);
        Route::resource('anamnesa', AnamnesaController::class);
        Route::resource('obat', ObatController::class);

        Route::get('/rekap-laporan', [RekamMedisController::class, 'rekapLaporan'])->name('rekap-laporan.index');
        Route::get('/rekap-laporan/export', [RekamMedisController::class, 'exportRekapLaporan'])->name('rekap-laporan.export');
        Route::get('/rekap-laporan/pdf', [RekamMedisController::class, 'cetakRekapLaporan'])->name('rekap-laporan.pdf');
    });
});

