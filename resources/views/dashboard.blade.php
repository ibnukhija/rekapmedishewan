@extends('layouts.app')

@section('title', 'Dashboard - Klinik Hewan Satwa Sehat')
@section('page_title', 'Dashboard')

@section('content')
<div class="bg-gradient-to-r from-brand-primary to-brand-light rounded-2xl p-6 sm:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama ?? 'Admin' }}! 👋</h2>
            <p class="text-white/90 max-w-2xl text-sm sm:text-base leading-relaxed">
                Sistem Informasi Rekam Medis Klinik Hewan <strong>Satwa Sehat Kota Kediri</strong>.
            </p>
        </div>
    </div>
    <i class="fa-solid fa-paw absolute -bottom-10 -right-10 text-9xl text-white opacity-10 rotate-[-12deg]"></i>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">
        <img src="{{ asset('img/foto puskes.png') }}" class="w-full h-full object-cover hover:scale-105 transition duration-500" alt="Klinik Hewan">
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 flex flex-col justify-center">
        <span class="text-brand-primary font-semibold uppercase tracking-wider text-sm">Tentang Klinik</span>
        <h2 class="text-3xl font-bold mt-2 mb-4">Klinik Hewan Satwa Sehat Kota Kediri</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-8">
            Klinik Hewan Satwa Sehat merupakan UPT Pusat Kesehatan Hewan Kota Kediri yang menyediakan pelayanan pemeriksaan, pengobatan, vaksinasi, konsultasi, hingga rekam medis untuk berbagai jenis hewan peliharaan.
        </p>
        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-location-dot text-brand-primary text-xl mt-1"></i>
                <span class="text-sm">Jl. Brigadir Jenderal Polisi Imam Bachri No. 98A, Bangsal, Kec. Pesantren, Kab. Kediri.</span>
            </div>
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-clock text-brand-primary text-xl mt-1"></i>
                <span class="text-sm">Senin - Jum'at <br>07.30 - 14.00 WIB</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-phone text-brand-primary text-xl"></i>
                <span class="text-sm">085755238492</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-paw text-brand-primary text-xl"></i>
                <span class="text-sm">Melayani Berbagai Jenis Hewan</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center gap-3 transition-transform hover:-translate-y-1">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-address-book"></i>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-tight">Registrasi Satwa</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white pl-1">{{ $totalRegistrasiSatwa }}</h3>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center gap-3 transition-transform hover:-translate-y-1">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-notes-medical"></i>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-tight">Total Kunjungan</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white pl-1">{{ $totalKunjungan }}</h3>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center gap-3 transition-transform hover:-translate-y-1">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-tight">Kunjungan Hari Ini</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white pl-1">{{ $kunjunganHariIni }}</h3>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center gap-3 transition-transform hover:-translate-y-1">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-tight">Total Retribusi Hari Ini</p>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pl-1">Rp {{ number_format($totalRetribusiHariIni, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-center gap-3 transition-transform hover:-translate-y-1">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-tight">Dokter Jaga</p>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white pl-1 truncate">{{ $totalDokter }}</h3>
    </div>

</div>
@endsection