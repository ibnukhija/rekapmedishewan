<?php

use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\SurveilansApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Endpoint generate token 
    Route::post('/token', [AuthTokenController::class, 'generate'])->middleware('throttle:5,1');

    // Endpoint butuh token 
    Route::middleware(['throttle:60,1', 'auth:sanctum'])->group(function () {
        Route::get('/surveilans', [SurveilansApiController::class, 'index']);
        Route::post('/token/revoke', [AuthTokenController::class, 'revoke']);
        Route::get('/token/list', [AuthTokenController::class, 'listTokens']);
    });
});