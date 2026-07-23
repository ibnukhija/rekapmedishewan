@extends('layouts.app')

@section('title', 'Rekap Laporan - Klinik Hewan Satwa Sehat')
@section('page_title', 'Rekapitulasi Laporan')

@section('content')
<div class="flex-1 flex flex-col space-y-4 max-w-[1600px] mx-auto w-full">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
        <form method="GET" action="{{ route('rekap-laporan.index') }}">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 w-full">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pencarian Umum</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pemilik, hewan, diagnosa..."
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mulai Dari</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" title="Tanggal Mulai"
                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sampai Kapan</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" title="Tanggal Akhir"
                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Tahun</label>
                    <select name="year" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $yearOption)
                            <option value="{{ $yearOption }}" {{ request('year') == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

                <div class="flex flex-wrap items-center justify-end gap-3">

                    <button type="button"
                        id="btnFilterMore"
                        class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-3 text-sm rounded-xl shadow-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter Lebih</span>
                    </button>

                    <button type="submit"
                        class="bg-brand-primary hover:bg-brand-dark text-white font-medium py-2 px-4 text-sm rounded-xl shadow-lg shadow-brand-primary/20 transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Cari</span>
                    </button>

                    <a href="{{ route('rekap-laporan.index') }}"
                        class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-4 text-sm rounded-xl border border-gray-200 dark:border-gray-600 transition-all duration-200">
                        Reset
                    </a>

                    <a href="{{ route('rekap-laporan.export', request()->query()) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 text-sm rounded-xl shadow-lg shadow-green-600/20 transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-file-excel"></i>
                        <span>Excel</span>
                    </a>

                </div>

            <div id="filter-more-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6">
                <div class="w-full max-w-2xl bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Filter Lebih</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Atur filter dokter, jenis hewan, pelayanan, diagnosa, dan kelamin.</p>
                        </div>
                        <button type="button" id="closeFilterMore" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 px-6 py-6">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Dokter</label>
                            <select name="dokter" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                                <option value="">Semua Dokter</option>
                                @foreach($dokters as $dokter)
                                    <option value="{{ $dokter->id_dokter }}" {{ request('dokter') == $dokter->id_dokter ? 'selected' : '' }}>{{ $dokter->nama_dokter }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Jenis Hewan</label>
                            <select name="jenis_hewan" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisHewans as $jenis)
                                    <option value="{{ $jenis->id_jenis }}" {{ request('jenis_hewan') == $jenis->id_jenis ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Pelayanan</label>
                            <select name="pelayanan" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                                <option value="">Semua Pelayanan</option>
                                @foreach($pelayanans as $pelayanan)
                                    @php
                                        $pelayananLabel = $pelayanan->nama_pelayanan;
                                        $details = [];
                                        if ($pelayanan->jenisHewan) {
                                            $details[] = $pelayanan->jenisHewan->nama_jenis;
                                        }
                                        if (!empty($pelayanan->jenis_kelamin)) {
                                            $details[] = $pelayanan->jenis_kelamin;
                                        }
                                        if (!empty($details)) {
                                            $pelayananLabel .= ' (' . implode(', ', $details) . ')';
                                        }
                                    @endphp
                                    <option value="{{ $pelayanan->id_pelayanan }}" {{ request('pelayanan') == $pelayanan->id_pelayanan ? 'selected' : '' }}>{{ $pelayananLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Diagnosa</label>
                            <select name="diagnosa" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                                <option value="">Semua Diagnosa</option>
                                @foreach($diagnosas as $diagnosa)
                                    <option value="{{ $diagnosa->id_diagnosa }}" {{ request('diagnosa') == $diagnosa->id_diagnosa ? 'selected' : '' }}>{{ $diagnosa->nama_diagnosa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5 lg:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                                <option value="">Semua Kelamin</option>
                                <option value="Jantan" {{ request('jenis_kelamin') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                                <option value="Betina" {{ request('jenis_kelamin') == 'Betina' ? 'selected' : '' }}>Betina</option>
                            </select>
                        </div>
                        <div class="space-y-1.5 lg:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Anamnesa</label>
                            <select name="anamnesa" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                                <option value="">Semua Anamnesa</option>
                                @foreach($anamnesas as $anamnesa)
                                    <option value="{{ $anamnesa->id_anamnesa }}" {{ request('anamnesa') == $anamnesa->id_anamnesa ? 'selected' : '' }}>{{ $anamnesa->nama_anamnesa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col gap-3 sm:flex-row sm:justify-between">
                        <button type="button" id="closeFilterMoreBottom" class="w-full sm:w-auto bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-3 px-5 rounded-xl transition-all duration-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                            Tutup
                        </button>
                        <button type="submit" class="w-full sm:w-auto bg-brand-primary hover:bg-brand-dark text-white font-medium py-3 px-5 rounded-xl transition-all duration-200">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex-1 flex flex-col relative">
        <div class="table-container overflow-x-auto w-full h-full pb-2">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap min-w-max">
                <thead class="bg-brand-primary text-white sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Tanggal</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Nama Pemilik</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50 min-w-[150px]">Alamat</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Nama Hewan</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Jenis Hewan</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Kelamin</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50 min-w-[200px]">Anamnesa</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50 min-w-[150px]">Diagnosa</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Pelayanan</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Dokter</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">Paramedik</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50 min-w-[150px]">Terapi</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50">No. Karcis</th>
                        <th class="px-5 py-4 font-semibold border-b border-brand-dark/50 text-right">Retribusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($rekapData as $item)
                        @php
                            $anamnesaList = $item->anamnesas->pluck('nama_anamnesa')->implode(', ');
                            $obatList = $item->obats->pluck('nama_obat')->implode(', ');
                            $tanggal = \Carbon\Carbon::parse($item->tanggal);
                            if ($tanggal->format('H:i:s') === '00:00:00' && $item->created_at) {
                                $tanggal = $tanggal->setTime($item->created_at->hour, $item->created_at->minute, $item->created_at->second);
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-3">{{ $tanggal->translatedFormat('d/m/Y H:i:s') }}</td>
                            <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $item->hewan?->pemilik?->nama_pemilik ?? '-' }}</td>
                            <td class="px-5 py-3 truncate max-w-[200px]" title="{{ $item->hewan?->pemilik?->alamat ?? '-' }}">{{ $item->hewan?->pemilik?->alamat ?? '-' }}</td>
                            <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $item->hewan?->nama_hewan ?? '-' }}</td>
                            <td class="px-5 py-3">{{ $item->hewan?->jenisHewan?->nama_jenis ?? '-' }}</td>
                            <td class="px-5 py-3">{{ $item->hewan?->jenis_kelamin ?? '-' }}</td>
                            <td class="px-5 py-3 truncate max-w-[250px]" title="{{ $anamnesaList ?: '-' }}">{{ $anamnesaList ?: '-' }}</td>
                            <td class="px-5 py-3 truncate max-w-[200px]" title="{{ $item->diagnosa?->nama_diagnosa ?? '-' }}">{{ $item->diagnosa?->nama_diagnosa ?? '-' }}</td>
                            <td class="px-5 py-3 text-brand-primary dark:text-brand-light font-medium">{{ $item->pelayanan?->nama_pelayanan ?? '-' }}</td>
                            <td class="px-5 py-3">{{ $item->dokter?->nama_dokter ?? '-' }}</td>
                            <td class="px-5 py-3">{{ $item->paramedis?->nama_paramedis ?? '-' }}</td>
                            <td class="px-5 py-3 truncate max-w-[200px]" title="{{ $obatList ?: '-' }}">{{ $obatList ?: '-' }}</td>
                            <td class="px-5 py-3 font-mono text-xs">{{ $item->no_karcis ?? '-' }}</td>
                            <td class="px-5 py-3 text-right font-medium text-gray-900 dark:text-white">{{ number_format($item->pelayanan?->tarif ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data rekam medis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between text-xs text-gray-500 dark:text-gray-400 shrink-0">
            <span>
                Menampilkan {{ $rekapData->firstItem() ?? 0 }} sampai {{ $rekapData->lastItem() ?? 0 }} dari {{ $rekapData->total() }} entri
            </span>
            <div class="w-full sm:w-auto">
                {{ $rekapData->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-container::-webkit-scrollbar { height: 8px; }
    .table-container::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
    .dark .table-container::-webkit-scrollbar-thumb { background: #64748b; }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('btnExport')?.addEventListener('click', function () {
        const button = this;
        const originalHtml = button.innerHTML;

        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Memproses...</span>';
        button.disabled = true;
        button.classList.add('opacity-80', 'cursor-not-allowed');

        setTimeout(() => {
            button.innerHTML = '<i class="fa-solid fa-check"></i><span>Berhasil Diunduh</span>';
            button.classList.replace('bg-green-600', 'bg-brand-dark');
            button.classList.replace('hover:bg-green-700', 'hover:bg-brand-dark');

            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.disabled = false;
                button.classList.remove('opacity-80', 'cursor-not-allowed');
                button.classList.replace('bg-brand-dark', 'bg-green-600');
                button.classList.replace('hover:bg-brand-dark', 'hover:bg-green-700');
            }, 3000);
        }, 1500);
    });

    const filterMoreBtn = document.getElementById('btnFilterMore');
    const closeFilterMore = document.getElementById('closeFilterMore');
    const closeFilterMoreBottom = document.getElementById('closeFilterMoreBottom');
    const filterMoreModal = document.getElementById('filter-more-modal');

    const toggleFilterModal = (show) => {
        if (!filterMoreModal) return;
        filterMoreModal.classList.toggle('hidden', !show);
    };

    filterMoreBtn?.addEventListener('click', () => toggleFilterModal(true));
    closeFilterMore?.addEventListener('click', () => toggleFilterModal(false));
    closeFilterMoreBottom?.addEventListener('click', () => toggleFilterModal(false));

    filterMoreModal?.addEventListener('click', function (event) {
        if (event.target === filterMoreModal) {
            toggleFilterModal(false);
        }
    });
</script>
@endpush
