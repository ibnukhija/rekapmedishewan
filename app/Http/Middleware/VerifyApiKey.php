<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-Api-Key');

        if (!$key || $key !== config('services.dashboard_kota.api_key')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized. API key tidak valid atau tidak dikirim.',
            ], 401);
        }

        return $next($request);
    }
}