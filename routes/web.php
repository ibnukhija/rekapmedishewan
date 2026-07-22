<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ParamedisController;
use App\Http\Controllers\PelayananController;
use App\Http\Controllers\JenisHewanController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\AnamnesaController;
use App\Http\Controllers\ObatController;


// Route untuk Halaman Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route untuk Halaman yang Membutuhkan Login (Auth Umum)
Route::middleware('auth')->group(function () {
    
    // DIAKSES ADMIN & OPERATOR
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Modul Transaksi (Akses Operator & Admin)
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
    });
});

