@extends('layouts.app')

@section('title', 'Rekapitulasi - S-ALPUKAT')
@section('page_title', 'Rekapitulasi Setoran Retribusi')

@section('content')
<div class="flex-1 flex flex-col space-y-4 max-w-[1600px] mx-auto w-full">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
        <form method="GET" action="{{ route('rekap-laporan.rekapitulasi') }}">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penandatangan 1</label>
                    <button type="button" id="btnTtd1"
                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm text-left flex items-center gap-2 truncate">
                        <i class="fa-solid fa-signature text-brand-primary flex-shrink-0"></i>
                        <span id="btnTtd1Label" class="truncate">Belum diisi</span>
                    </button>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penandatangan 2</label>
                    <button type="button" id="btnTtd2"
                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm text-left flex items-center gap-2 truncate">
                        <i class="fa-solid fa-signature text-brand-primary flex-shrink-0"></i>
                        <span id="btnTtd2Label" class="truncate">Belum diisi</span>
                    </button>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Tahun</label>
                    <select name="year" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light transition-colors text-sm appearance-none cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @foreach($years ?? [] as $yearOption)
                            <option value="{{ $yearOption }}" {{ request('year') == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Hidden input, ke-submit bareng form GET -->
            <input type="hidden" name="ttd1_nama" id="ttd1_nama" value="{{ request('ttd1_nama') }}">
            <input type="hidden" name="ttd1_nip" id="ttd1_nip" value="{{ request('ttd1_nip') }}">
            <input type="hidden" name="ttd1_jabatan" id="ttd1_jabatan" value="{{ request('ttd1_jabatan') }}">

            <input type="hidden" name="ttd2_nama" id="ttd2_nama" value="{{ request('ttd2_nama') }}">
            <input type="hidden" name="ttd2_nip" id="ttd2_nip" value="{{ request('ttd2_nip') }}">
            <input type="hidden" name="ttd2_jabatan" id="ttd2_jabatan" value="{{ request('ttd2_jabatan') }}">

            <div class="flex flex-wrap items-center justify-end gap-3 mt-2">
                <button type="submit"
                    class="bg-brand-primary hover:bg-brand-dark text-white font-medium py-2 px-4 text-sm rounded-xl shadow-lg shadow-brand-primary/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Terapkan</span>
                </button>

                <a href="{{ route('rekap-laporan.rekapitulasi') }}"
                    class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-4 text-sm rounded-xl border border-gray-200 dark:border-gray-600 transition-all duration-200">
                    Reset
                </a>

                <a href="{{ route('rekap-laporan.export-view', request()->query()) }}"
                    class="js-export-link bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 text-sm rounded-xl shadow-lg shadow-green-600/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i>
                    Excel Rekapitulasi
                </a>

                <a href="{{ route('rekap-laporan.pdf2', request()->query()) }}"
                    class="js-export-link bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 text-sm rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i>
                    PDF Rekapitulasi
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex-1 flex flex-col relative">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Rekapitulasi Setoran Retribusi</h3>
            @forelse($filterInfo as $label => $value)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $label }}: {{ $value }}</p>
            @empty
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada filter (menampilkan seluruh data)</p>
            @endforelse
        </div>

        <div class="table-container overflow-x-auto w-full h-full pb-2">
            <table class="w-full text-center text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap min-w-max border-collapse">
                <thead class="bg-brand-primary text-white sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th rowspan="3" class="px-3 py-3 font-semibold border border-brand-dark/50 align-middle">No</th>
                        <th rowspan="3" class="px-3 py-3 font-semibold border border-brand-dark/50 align-middle">Bulan</th>
                        <th colspan="6" class="px-3 py-2 font-semibold border border-brand-dark/50">Non Operatif</th>
                        <th colspan="6" class="px-3 py-2 font-semibold border border-brand-dark/50">Operatif</th>
                        <th colspan="3" class="px-3 py-2 font-semibold border border-brand-dark/50">Lain-lain</th>
                        <th rowspan="3" class="px-3 py-3 font-semibold border border-brand-dark/50 align-middle">Total Pasien</th>
                        <th rowspan="3" class="px-3 py-3 font-semibold border border-brand-dark/50 align-middle">Total Retribusi (Rp)</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="px-3 py-2 font-semibold border border-brand-dark/50">Pemeriksaan Umum</th>
                        <th colspan="3" class="px-3 py-2 font-semibold border border-brand-dark/50">Vaksinasi</th>
                        <th colspan="3" class="px-3 py-2 font-semibold border border-brand-dark/50">Operasi Kecil</th>
                        <th colspan="3" class="px-3 py-2 font-semibold border border-brand-dark/50">Operasi Besar</th>
                        <th colspan="3" class="px-3 py-2 font-semibold border border-brand-dark/50"></th>
                    </tr>
                    <tr>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Kucing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Anjing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Unggas/Kelinci</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Kucing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Anjing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Unggas/Kelinci</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Kucing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Anjing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Unggas/Kelinci</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Kucing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Anjing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Unggas/Kelinci</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Kucing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Anjing</th>
                        <th class="px-3 py-2 font-medium border border-brand-dark/50">Unggas/Kelinci</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($summaryRows as $index => $row)
                        @php $isTotal = $row['month'] === 'TOTAL'; @endphp
                        <tr class="{{ $isTotal ? 'bg-gray-100 dark:bg-gray-700 font-semibold' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30' }} transition-colors">
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $isTotal ? '' : $index + 1 }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700 text-left font-medium text-gray-900 dark:text-white">{{ $row['month'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['pemeriksaan_umum']['kucing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['pemeriksaan_umum']['anjing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['pemeriksaan_umum']['unggas_kelinci'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['vaksinasi']['kucing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['vaksinasi']['anjing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['vaksinasi']['unggas_kelinci'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['operasi_kecil']['kucing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['operasi_kecil']['anjing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['operasi_kecil']['unggas_kelinci'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['operasi_besar']['kucing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['operasi_besar']['anjing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['operasi_besar']['unggas_kelinci'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['lain_lain']['kucing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['lain_lain']['anjing'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700">{{ $row['lain_lain']['unggas_kelinci'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700 font-medium text-gray-900 dark:text-white">{{ $row['total_pasien'] }}</td>
                            <td class="px-3 py-2.5 border border-gray-200 dark:border-gray-700 font-medium text-gray-900 dark:text-white">{{ number_format($row['total_retribusi'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3">
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Entri</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $totalEntri }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3">
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Hewan (Unik)</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $totalHewanUnik }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3">
                    <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Retribusi</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">Rp {{ number_format($totalRetribusi, 0, ',', '.') }}</div>
                </div>
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
                <input type="text" id="ttdForm1Nama" placeholder="Contoh: KH. Budi Santoso, M.Pd."
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
                <input type="text" id="ttdForm2Nama" placeholder="Contoh: drh. Siti Aminah"
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
    // ====== PENANDATANGAN (TTD) — localStorage, tanpa DB ======
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
        if (label) {
            label.textContent = 'Penandatangan ' + index + ': ' + (data.nama ? data.nama : 'Belum diisi');
        }

        const hiddenNama = document.getElementById('ttd' + index + '_nama');
        const hiddenNip = document.getElementById('ttd' + index + '_nip');
        const hiddenJabatan = document.getElementById('ttd' + index + '_jabatan');

        // isi otomatis teko localStorage lek hidden input jik kosong
        // (ben nilai query string e utowo hasil submit e form diutamakan)
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
            link.target = '_blank';
            link.rel = 'noopener';

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

    //tampilkan data 
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

        document.getElementById('btnTtd1Label').textContent = 'Penandatangan 1: ' + (data.nama || 'Belum diisi');
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

        document.getElementById('btnTtd2Label').textContent = 'Penandatangan 2: ' + (data.nama || 'Belum diisi');
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