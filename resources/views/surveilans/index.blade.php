@extends('layouts.app')

@section('title', 'Surveilans  - Klinik Hewan Satwa Sehat')
@section('page_title', 'Surveilans')

@section('content')

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Daerah</label>
            <select name="daerah" onchange="this.form.submit()"
                class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                <option value="semua" {{ $daerah === 'semua' ? 'selected' : '' }}>Semua Daerah</option>
                @foreach($daftarDaerah as $d)
                    <option value="{{ $d }}" {{ $daerah === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jenis Hewan</label>
            <select name="jenis" onchange="this.form.submit()"
                class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                <option value="semua" {{ $jenis === 'semua' ? 'selected' : '' }}>Semua Jenis</option>
                @foreach($daftarJenis as $j)
                    <option value="{{ $j }}" {{ $jenis === $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Periode</label>
            <select name="periode" onchange="this.form.submit()"
                class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                <option value="3" {{ $periode == 3 ? 'selected' : '' }}>3 Bulan Terakhir</option>
                <option value="6" {{ $periode == 6 ? 'selected' : '' }}>6 Bulan Terakhir</option>
                <option value="12" {{ $periode == 12 ? 'selected' : '' }}>12 Bulan Terakhir</option>
            </select>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Kasus</p>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $ringkasan['total'] }}</h3>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kombinasi Prioritas Tinggi</p>
        <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $ringkasan['kombinasi_tinggi'] }}</h3>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Daerah Terdampak</p>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $ringkasan['daerah_terdampak'] }}</h3>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-8 overflow-x-auto">
    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Matriks Jenis Hewan &times; Diagnosa</h3>
    <table class="min-w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-400 uppercase border-b border-gray-100 dark:border-gray-700">
                <th class="py-2 pr-4">Jenis Hewan</th>
                <th class="py-2 pr-4">Diagnosa</th>
                <th class="py-2 pr-4">Jumlah Kasus</th>
                <th class="py-2 pr-4">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matrix->sortByDesc('count') as $m)
                @php
                    $tier = $m['count'] >= 10 ? 'tinggi' : ($m['count'] >= 5 ? 'sedang' : 'rendah');
                    $badge = [
                        'tinggi' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'sedang' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'rendah' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    ][$tier];
                @endphp
                <tr class="border-b border-gray-50 dark:border-gray-700/50">
                    <td class="py-2 pr-4">{{ $m['jenis'] }}</td>
                    <td class="py-2 pr-4">{{ $m['diagnosa'] }}</td>
                    <td class="py-2 pr-4 font-mono font-semibold">{{ $m['count'] }}</td>
                    <td class="py-2 pr-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge }}">{{ ucfirst($tier) }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-6 text-center text-gray-400">Belum ada rekam medis pada filter ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')

@endpush
@endsection