<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route untuk Halaman Auth (Guest / Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route untuk Halaman yang Membutuhkan Login (Auth)
Route::middleware('auth')->group(function () {
    // Contoh Dashboard (Sesuaikan dengan controller Anda)
    Route::get('/dashboard', function () {
        return view('dashboard'); // Ganti dengan view/controller dashboard Anda
    })->name('dashboard');
    
    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});