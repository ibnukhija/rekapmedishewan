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
use App\Models\Dokter;

// Route untuk Halaman Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route untuk Halaman yang Membutuhkan Login (Auth Umum)
Route::middleware('auth')->group(function () {
    
    // DIAKSES ADMIN & OPERATOR
    Route::get('/dashboard', function () {
        $totalDokter = Dokter::count();
        return view('dashboard', compact('totalDokter'));
    })->name('dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Modul Transaksi (Akses Operator & Admin)
    Route::resource('rekam-medis', RekamMedisController::class);

    // HANYA BISA DIAKSES ADMIN ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('dokter', DokterController::class);
        Route::resource('paramedis', ParamedisController::class);
        Route::resource('pelayanan', PelayananController::class);
        Route::resource('jenis-hewan', JenisHewanController::class);
        Route::resource('diagnosa', DiagnosaController::class);
        Route::resource('anamnesa', AnamnesaController::class);
        Route::resource('obat', ObatController::class);
    });
});

