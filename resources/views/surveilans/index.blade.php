@extends('layouts.app')

@section('title', 'Surveilans  - S-ALPUKAT')
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

{{-- Tabel ringkasan kasus tertinggi per jenis hewan --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-8 overflow-x-auto">
    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Kasus Tertinggi per Jenis Hewan</h3>
    <table class="min-w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-400 uppercase">
                <th rowspan="2" class="py-2 pr-4 align-bottom border-b border-gray-100 dark:border-gray-700">No</th>
                <th rowspan="2" class="py-2 pr-4 align-bottom border-b border-gray-100 dark:border-gray-700">Jenis</th>
                <th rowspan="2" class="py-2 pr-4 align-bottom border-b border-gray-100 dark:border-gray-700">Total</th>
                <th colspan="5" class="py-2 pr-4 text-center border-b border-gray-100 dark:border-gray-700">Kasus</th>
            </tr>
            <tr class="text-left text-xs text-gray-400 uppercase border-b border-gray-100 dark:border-gray-700">
                <th class="py-2 pr-4">Tertinggi</th>
                <th class="py-2 pr-4">Jumlah</th>
                <th class="py-2 pr-4">Asal Kota Kediri</th>
                <th class="py-2 pr-4">Jml Kelurahan Terdampak</th>
                <th class="py-2 pr-4">Asal Luar Kota Kediri</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jenisBreakdown as $i => $jb)
                <tr class="border-b border-gray-50 dark:border-gray-700/50">
                    <td class="py-2 pr-4">{{ $i + 1 }}</td>
                    <td class="py-2 pr-4">{{ $jb['jenis'] }}</td>
                    <td class="py-2 pr-4 font-mono font-semibold">{{ $jb['total'] }}</td>
                    <td class="py-2 pr-4">{{ $jb['diagnosa_tertinggi'] ?? '-' }}</td>
                    <td class="py-2 pr-4 font-mono">{{ $jb['jumlah_tertinggi'] }}</td>
                    <td class="py-2 pr-4 font-mono">{{ $jb['asal_kota'] }}</td>
                    <td class="py-2 pr-4 font-mono">{{ $jb['kelurahan_terdampak'] }}</td>
                    <td class="py-2 pr-4 font-mono">{{ $jb['asal_luar'] }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="py-6 text-center text-gray-400">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Diagram batang top 5 kasus per jenis hewan --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-8">
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <h3 class="font-semibold text-gray-900 dark:text-white">Top 5 Kasus per Jenis Hewan</h3>
        <select id="chartJenisSelect"
            class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
            @foreach($chartData->keys() as $jn)
                <option value="{{ $jn }}">{{ $jn }}</option>
            @endforeach
        </select>
    </div>
    <div style="height:350px;">
        <canvas id="kasusChart"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    const chartData = @json($chartData);
    const jenisSelect = document.getElementById('chartJenisSelect');
    const ctx = document.getElementById('kasusChart')?.getContext('2d');

    const warna = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#94a3b8'];

    let kasusChart;

    function renderChart(jenis) {
        const dataset = chartData[jenis] || { labels: [], data: [] };

        if (kasusChart) {
            kasusChart.data.labels = dataset.labels;
            kasusChart.data.datasets[0].data = dataset.data;
            kasusChart.update();
            return;
        }

        kasusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataset.labels,
                datasets: [{
                    label: 'Jumlah Kasus',
                    data: dataset.data,
                    backgroundColor: warna,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }

    if (ctx && jenisSelect && jenisSelect.options.length > 0) {
        renderChart(jenisSelect.value);
        jenisSelect.addEventListener('change', (e) => renderChart(e.target.value));
    }
</script>
@endpush
@endsection