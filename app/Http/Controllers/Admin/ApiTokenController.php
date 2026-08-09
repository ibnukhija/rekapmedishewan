<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenController extends Controller
{
    public function index()
    {
        $tokens = Auth::user()->tokens()
            ->select('id', 'name', 'last_used_at', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return view('pengaturan.api_index', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:100',
        ], [
            'token_name.required' => 'Nama token wajib diisi.',
        ]);

        $plainToken = Auth::user()->createToken($request->token_name)->plainTextToken;

        return back()
            ->with('new_token', $plainToken)
            ->with('new_token_name', $request->token_name)
            ->with('success', 'Token berhasil dibuat.');
    }

    public function destroy($id)
    {
        $deleted = Auth::user()->tokens()->where('id', $id)->delete();

        if (!$deleted) {
            return back()->with('error', 'Token tidak ditemukan.');
        }

        return back()->with('success', 'Token berhasil dicabut.');
    }
}