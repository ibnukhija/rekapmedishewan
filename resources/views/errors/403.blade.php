@extends('layouts.app')

@section('title', '403 - S-ALPUKAT')
@section('page_title', '403')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-7xl font-bold text-red-600">403</h1>
        <p class="mt-4 text-xl">Forbidden</p>
        <p class="text-gray-500 mt-2">
            Anda tidak memiliki hak akses untuk membuka halaman ini.
        </p>

        <a href="{{ route('dashboard') }}"
            class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection