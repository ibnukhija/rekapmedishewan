<?php

use App\Http\Controllers\Api\SurveilansApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['throttle:60,1', 'auth:sanctum'])->group(function () {
    Route::get('/surveilans', [SurveilansApiController::class, 'index']);
});