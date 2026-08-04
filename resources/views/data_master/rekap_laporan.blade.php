@extends('layouts.app')

@section('title', 'Rekap Laporan - SALPUKAT')
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

            <!-- Hidden input, ikut ke-submit bareng form GET, otomatis nempel di query string -->
            <input type="hidden" name="ttd1_nama" id="ttd1_nama" value="{{ request('ttd1_nama') }}">
            <input type="hidden" name="ttd1_nip" id="ttd1_nip" value="{{ request('ttd1_nip') }}">
            <input type="hidden" name="ttd1_jabatan" id="ttd1_jabatan" value="{{ request('ttd1_jabatan') }}">

            <input type="hidden" name="ttd2_nama" id="ttd2_nama" value="{{ request('ttd2_nama') }}">
            <input type="hidden" name="ttd2_nip" id="ttd2_nip" value="{{ request('ttd2_nip') }}">
            <input type="hidden" name="ttd2_jabatan" id="ttd2_jabatan" value="{{ request('ttd2_jabatan') }}">

            {{-- ====== BARIS PENANDATANGAN ====== --}}
            <div class="flex flex-wrap items-center gap-2 mt-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider shrink-0">Penandatangan:</span>

                <button type="button" id="btnTtd1"
                    class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-3 text-sm rounded-xl shadow-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 flex items-center gap-2 w-44 shrink-0">
                    <i class="fa-solid fa-signature text-brand-primary shrink-0"></i>
                    <span id="btnTtd1Prefix" class="shrink-0 text-gray-400 dark:text-gray-500">TTD 1:</span>
                    <span id="btnTtd1Label" class="truncate">Belum diisi</span>
                </button>

                <button type="button" id="btnTtd2"
                    class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-3 text-sm rounded-xl shadow-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 flex items-center gap-2 w-44 shrink-0">
                    <i class="fa-solid fa-signature text-brand-primary shrink-0"></i>
                    <span id="btnTtd2Prefix" class="shrink-0 text-gray-400 dark:text-gray-500">TTD 2:</span>
                    <span id="btnTtd2Label" class="truncate">Belum diisi</span>
                </button>

                <button type="button"
                    id="btnFilterMore"
                    class="ml-auto bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-3 text-sm rounded-xl shadow-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 flex items-center gap-2">
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

                <!-- Excel: split button -->
                <div class="relative inline-flex" data-dropdown>
                    <div class="flex rounded-xl shadow-lg shadow-green-600/20 overflow-hidden">
                        <a href="{{ route('rekap-laporan.export', request()->query()) }}"
                            class="js-export-link bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 text-sm transition-all duration-200 flex items-center gap-2">
                            <i class="fa-solid fa-file-excel"></i>
                            Excel
                        </a>
                        <button type="button" data-dropdown-toggle
                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-2.5 border-l border-green-500/40 transition-all duration-200">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                    </div>

                    <div data-dropdown-menu
                        class="hidden absolute right-0 top-full mt-2 w-36 bg-white dark:bg-gray-700 rounded-xl shadow-lg border border-gray-100 dark:border-gray-600 py-1 z-50">
                        <a href="{{ route('rekap-laporan.export', request()->query()) }}"
                            class="js-export-link flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-file-excel text-green-600"></i>
                            Excel Rincian
                        </a>
                        <a href="{{ route('rekap-laporan.export-view', request()->query()) }}"
                            class="js-export-link flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-file-excel text-green-600"></i>
                            Excel Rekapitulasi
                        </a>
                    </div>
                </div>

                <!-- PDF: split button -->
                <div class="relative inline-flex" data-dropdown>
                    <div class="flex rounded-xl shadow-lg shadow-red-600/20 overflow-hidden">
                        <a href="{{ route('rekap-laporan.pdf', request()->query()) }}"
                            class="js-export-link bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 text-sm transition-all duration-200 flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf"></i>
                            PDF
                        </a>
                        <button type="button" data-dropdown-toggle
                            class="bg-red-600 hover:bg-red-700 text-white py-2 px-2.5 border-l border-red-500/40 transition-all duration-200">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                    </div>

                    <div data-dropdown-menu
                        class="hidden absolute right-0 top-full mt-2 w-36 bg-white dark:bg-gray-700 rounded-xl shadow-lg border border-gray-100 dark:border-gray-600 py-1 z-50">
                        <a href="{{ route('rekap-laporan.pdf', request()->query()) }}"
                            class="js-export-link flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-file-pdf text-red-600"></i>
                            PDF Rincian
                        </a>
                        <a href="{{ route('rekap-laporan.pdf2', request()->query()) }}"
                            class="js-export-link flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-file-pdf text-red-600"></i>
                            PDF Rekapitulasi
                        </a>
                    </div>
                </div>
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
                            <td class="px-5 py-3">{{ $tanggal->translatedFormat('Y/m/d H:i:s') }}</td>
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

        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3">
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Kunjungan</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $totalEntrySummary }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3">
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Retribusi</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($totalRetribusiSummary, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3">
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Hewan (Unik)</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $totalHewanUnikSummary }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3">
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Dokter Aktif</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $dokterAktifSummary }}</div>
                </div>
            </div>
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

{{-- ====== MODAL PENANDATANGAN 1 ====== --}}
<div id="ttd-modal-1" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Penandatangan 1</h3>
            <button type="button" class="ttd-modal-close text-gray-500 hover:text-gray-700 dark:hover:text-gray-300" data-target="1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="px-6 py-5 space-y-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Nama</label>
                <input type="text" id="ttdForm1Nama" placeholder="Contoh: Budi Santoso"
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">NIP <span class="normal-case text-gray-400">(opsional)</span></label>
                <input type="text" id="ttdForm1Nip" placeholder="19xxxxxxxxxxxxxxxx"
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Jabatan <span class="normal-case text-gray-400">(opsional)</span></label>
                <input type="text" id="ttdForm1Jabatan" placeholder="Contoh: Kepala Dinas Ketahanan Pangan dan Pertanian"
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary">
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-end gap-3">
            <button type="button" class="ttd-modal-close bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-2.5 px-5 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600" data-target="1">
                Batal
            </button>
            <button type="button" id="ttdSave1" class="bg-brand-primary hover:bg-brand-dark text-white font-medium py-2.5 px-5 rounded-xl">
                Simpan
            </button>
        </div>
    </div>
</div>

{{-- ====== MODAL PENANDATANGAN 2 ====== --}}
<div id="ttd-modal-2" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Penandatangan 2</h3>
            <button type="button" class="ttd-modal-close text-gray-500 hover:text-gray-700 dark:hover:text-gray-300" data-target="2">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="px-6 py-5 space-y-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Nama</label>
                <input type="text" id="ttdForm2Nama" placeholder="Contoh: Siti Aminah"
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">NIP <span class="normal-case text-gray-400">(opsional)</span></label>
                <input type="text" id="ttdForm2Nip" placeholder="19xxxxxxxxxxxxxxxx"
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Jabatan <span class="normal-case text-gray-400">(opsional)</span></label>
                <input type="text" id="ttdForm2Jabatan" placeholder="Contoh: Kepala UPT Pusat Kesehatan Hewan"
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary">
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-end gap-3">
            <button type="button" class="ttd-modal-close bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-2.5 px-5 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600" data-target="2">
                Batal
            </button>
            <button type="button" id="ttdSave2" class="bg-brand-primary hover:bg-brand-dark text-white font-medium py-2.5 px-5 rounded-xl">
                Simpan
            </button>
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
    document.querySelectorAll('[data-dropdown]').forEach(function (wrapper) {
        const toggleBtn = wrapper.querySelector('[data-dropdown-toggle]');
        const menu = wrapper.querySelector('[data-dropdown-menu]');

        toggleBtn?.addEventListener('click', function (event) {
            event.stopPropagation();

            // tutup dropdown lain
            document.querySelectorAll('[data-dropdown-menu]').forEach(function (otherMenu) {
                if (otherMenu !== menu) otherMenu.classList.add('hidden');
            });

            menu.classList.toggle('hidden');
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('[data-dropdown-menu]').forEach(function (menu) {
            menu.classList.add('hidden');
        });
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

    // ====== PENANDATANGAN — localStorage ======
    function loadTtd(index) {
        try {
            return JSON.parse(localStorage.getItem('ttd' + index) || 'null') || { nama: '', nip: '', jabatan: '' };
        } catch (e) {
            return { nama: '', nip: '', jabatan: '' };
        }
    }

    function saveTtd(index, data) {
        localStorage.setItem('ttd' + index, JSON.stringify(data));
    }

    function updateTtdButton(index) {
        const data = loadTtd(index);
        const label = document.getElementById('btnTtd' + index + 'Label');
        const prefix = document.getElementById('btnTtd' + index + 'Prefix');
        if (label) {
            label.textContent = (data.nama ? data.nama : 'Belum diisi');
            label.title = data.nama || '';
        }
        if (prefix) {
            prefix.classList.toggle('hidden', !!data.nama);
        }

        const hiddenNama = document.getElementById('ttd' + index + '_nama');
        const hiddenNip = document.getElementById('ttd' + index + '_nip');
        const hiddenJabatan = document.getElementById('ttd' + index + '_jabatan');

        // hanya isi otomatis dari localStorage kalau hidden input masih kosong
        // (biar nilai dari query string / hasil submit form tetap diutamakan)
        if (hiddenNama && !hiddenNama.value) hiddenNama.value = data.nama || '';
        if (hiddenNip && !hiddenNip.value) hiddenNip.value = data.nip || '';
        if (hiddenJabatan && !hiddenJabatan.value) hiddenJabatan.value = data.jabatan || '';
    }

    function openTtdModal(index) {
        const data = loadTtd(index);
        document.getElementById('ttdForm' + index + 'Nama').value = data.nama || '';
        document.getElementById('ttdForm' + index + 'Nip').value = data.nip || '';
        document.getElementById('ttdForm' + index + 'Jabatan').value = data.jabatan || '';

        const modal = document.getElementById('ttd-modal-' + index);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTtdModal(index) {
        const modal = document.getElementById('ttd-modal-' + index);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Update href semua tombol Excel/PDF biar bawa data TTD terbaru, tanpa reload halaman
    function refreshExportLinks() {
        const ttd1 = loadTtd(1);
        const ttd2 = loadTtd(2);

        const params = {
            ttd1_nama: ttd1.nama || '',
            ttd1_nip: ttd1.nip || '',
            ttd1_jabatan: ttd1.jabatan || '',
            ttd2_nama: ttd2.nama || '',
            ttd2_nip: ttd2.nip || '',
            ttd2_jabatan: ttd2.jabatan || '',
        };

        document.querySelectorAll('.js-export-link').forEach(function (link) {
            const url = new URL(link.href, window.location.origin);
            Object.keys(params).forEach(function (key) {
                if (params[key]) {
                    url.searchParams.set(key, params[key]);
                } else {
                    url.searchParams.delete(key);
                }
            });
            link.href = url.toString();
        });
    }

    // Init: tampilkan data tersimpan pas halaman dibuka
    updateTtdButton(1);
    updateTtdButton(2);
    refreshExportLinks();

    document.getElementById('btnTtd1')?.addEventListener('click', () => openTtdModal(1));
    document.getElementById('btnTtd2')?.addEventListener('click', () => openTtdModal(2));

    document.querySelectorAll('.ttd-modal-close').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeTtdModal(this.dataset.target);
        });
    });

    document.getElementById('ttdSave1')?.addEventListener('click', function () {
        const data = {
            nama: document.getElementById('ttdForm1Nama').value.trim(),
            nip: document.getElementById('ttdForm1Nip').value.trim(),
            jabatan: document.getElementById('ttdForm1Jabatan').value.trim(),
        };
        saveTtd(1, data);

        document.getElementById('ttd1_nama').value = data.nama;
        document.getElementById('ttd1_nip').value = data.nip;
        document.getElementById('ttd1_jabatan').value = data.jabatan;

        document.getElementById('btnTtd1Label').textContent = (data.nama || 'Belum diisi');
        document.getElementById('btnTtd1Label').title = data.nama || '';
        document.getElementById('btnTtd1Prefix').classList.toggle('hidden', !!data.nama);
        refreshExportLinks();
        closeTtdModal(1);
    });

    document.getElementById('ttdSave2')?.addEventListener('click', function () {
        const data = {
            nama: document.getElementById('ttdForm2Nama').value.trim(),
            nip: document.getElementById('ttdForm2Nip').value.trim(),
            jabatan: document.getElementById('ttdForm2Jabatan').value.trim(),
        };
        saveTtd(2, data);

        document.getElementById('ttd2_nama').value = data.nama;
        document.getElementById('ttd2_nip').value = data.nip;
        document.getElementById('ttd2_jabatan').value = data.jabatan;

        document.getElementById('btnTtd2Label').textContent = (data.nama || 'Belum diisi');
        document.getElementById('btnTtd2Label').title = data.nama || '';
        document.getElementById('btnTtd2Prefix').classList.toggle('hidden', !!data.nama);
        refreshExportLinks();
        closeTtdModal(2);
    });

    document.getElementById('ttd-modal-1')?.addEventListener('click', function (e) {
        if (e.target === this) closeTtdModal(1);
    });
    document.getElementById('ttd-modal-2')?.addEventListener('click', function (e) {
        if (e.target === this) closeTtdModal(2);
    });
</script>
@endpush