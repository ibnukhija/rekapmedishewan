<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    // POST /api/login  -> body: { "username": "...", "password": "..." }
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username atau password salah.',
            ], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak. Hanya akun admin yang boleh login melalui API ini.',
            ], 403);
        }

        $user->tokens()->delete();

        // Token hanya berlaku 5 menit sejak dibuat
        $token = $user->createToken(
            'postman-token',
            ['*'],
            now()->addMinutes(5)
        )->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil.',
            'user'    => [
                'id_user'  => $user->id_user,
                'nama'     => $user->nama,
                'username' => $user->username,
                'role'     => $user->role,
            ],
            'token'      => $token,
            'expires_at' => now()->addMinutes(5)->toDateTimeString(),
        ]);
    }

    // POST /api/logout -> perlu header Authorization: Bearer <token>
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil, token sudah dicabut.',
        ]);
    }

    // GET /api/me -> cek token masih valid & lihat data user login
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user'   => $request->user(),
        ]);
    }
}