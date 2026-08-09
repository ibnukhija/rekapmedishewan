<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthTokenController extends Controller
{
    /**
     * Generate token baru berdasarkan username + password.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'token_name' => 'nullable|string|max:100',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya admin yang berhak membuat token API.',
            ], 403);
        }

        $tokenName = $request->input('token_name', 'api-token-'.now()->timestamp);

        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Token berhasil dibuat.',
            'data' => [
                'token' => $token,
                'token_name' => $tokenName,
                'user' => $user->nama,
                'role' => $user->role,
            ],
        ]);
    }

    public function revoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Token berhasil dicabut.',
        ]);
    }

    public function listTokens(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()->tokens()->select('id', 'name', 'last_used_at', 'created_at')->get(),
        ]);
    }
}