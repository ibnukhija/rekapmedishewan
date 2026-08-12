<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\SurveilansApiController;
use Illuminate\Support\Facades\Route;

// Login tidak diproteksi sanctum (di sinilah token didapat), tapi tetap dibatasi rate limit
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
});

// Semua route di bawah ini WAJIB kirim header: Authorization: Bearer <token>
Route::middleware(['throttle:60,1', 'auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    Route::prefix('v1')->group(function () {
        Route::get('/surveilans', [SurveilansApiController::class, 'index']);
    });
});