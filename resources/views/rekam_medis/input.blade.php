@extends('layouts.app')

@section('title', 'Input Rekam Medis - Klinik Hewan Satwa Sehat')
@section('page_title', 'Input Rekam Medis')

@push('styles')
<style>
    /* Custom form focus ring */
    .form-input-focus:focus {
        box-shadow: 0 0 0 2px rgba(64, 145, 108, 0.2);
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <form onsubmit="handleSimpan(event)" class="space-y-6">
        @csrf
        <input type="hidden" id="id_hewan" name="id_hewan" value="">
        <input type="hidden" id="id_pemilik" name="id_pemilik" value="">
        
        <!-- Section 1: Informasi Dasar -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-brand-primary/5 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <i class="fa-solid fa-calendar-days text-brand-primary dark:text-brand-light"></i>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Data Registrasi</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Tanggal -->
                    <div class="space-y-1.5 lg:col-span-1">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                    </div>

                    <!-- No. Karcis -->
                    <div class="space-y-1.5 lg:col-span-3">
                        <label for="no_karcis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Karcis</label>
                        <input type="text" id="no_karcis" name="no_karcis" placeholder="Nomor Karcis"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Cari Pasien -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-brand-primary/5 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <i class="fa-solid fa-magnifying-glass text-brand-primary dark:text-brand-light"></i>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Cari Pasien</h2>
            </div>
            <div class="p-6">

                <!-- Search Box -->
                <div id="searchStage" class="relative">
                    <label for="searchPasien" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Hewan / ID Hewan / Nama Pemilik / No. HP</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                        </div>
                        <input type="text" id="searchPasien" autocomplete="off" placeholder="Ketik minimal 2 huruf... contoh: Milo, 123, Andi, 08123456789"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                    </div>

                    <!-- Live Dropdown Results -->
                    <div id="searchResults" class="hidden mt-2 border border-gray-200 dark:border-gray-700 rounded-lg divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden"></div>

                    <div class="flex items-center justify-between mt-3">
                        <p class="text-xs text-gray-400 dark:text-gray-500">Data pasien akan otomatis muncul jika sudah pernah berobat.</p>
                        <button type="button" onclick="selectNewRegistration()" class="text-sm font-medium text-brand-primary dark:text-brand-light hover:underline whitespace-nowrap ml-4">
                            + Pasien Benar-benar Baru
                        </button>
                    </div>
                </div>

                <!-- Selected Patient Card -->
                <div id="patientCard" class="hidden">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-dashed border-gray-200 dark:border-gray-700">
                        <span id="patientCardStatus" class="inline-flex items-center gap-2 text-sm font-medium px-3 py-1 rounded-full"></span>
                        <button type="button" onclick="resetSearch()" class="text-sm text-gray-500 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                            <i class="fa-solid fa-rotate-left mr-1"></i>Ganti Pasien
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kiri: Data Pemilik -->
                        <div class="space-y-5">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Data Pemilik</h3>
                                <button type="button" id="btnEditPemilik" onclick="toggleEdit('pemilik')" class="hidden text-xs font-medium text-brand-primary dark:text-brand-light hover:underline">
                                    <i class="fa-solid fa-pen mr-1"></i>Edit
                                </button>
                            </div>

                            <div class="space-y-1.5">
                                <label for="nama_pemilik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pemilik <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-regular fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" id="nama_pemilik" name="nama_pemilik" placeholder="Masukkan nama pemilik" required
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label for="no_hp_pemilik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" id="no_hp_pemilik" name="no_hp_pemilik" placeholder="08xxxxxxxxxx"
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap pemilik"
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Kanan: Data Hewan -->
                        <div class="space-y-5">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Data Hewan</h3>
                                <button type="button" id="btnEditHewan" onclick="toggleEdit('hewan')" class="hidden text-xs font-medium text-brand-primary dark:text-brand-light hover:underline">
                                    <i class="fa-solid fa-pen mr-1"></i>Edit
                                </button>
                            </div>

                            <div class="space-y-1.5">
                                <label for="nama_hewan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Hewan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-cat text-gray-400"></i>
                                    </div>
                                    <input type="text" id="nama_hewan" name="nama_hewan" placeholder="Masukkan nama hewan" required
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="jenis_hewan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Hewan</label>
                                    <select id="jenis_hewan" name="jenis_hewan" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                                        <option value="" disabled selected>Pilih Jenis...</option>
                                        @foreach($jenisHewans as $jenis)
                                            <option value="{{ $jenis->id_jenis }}">{{ $jenis->nama_jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                                        <option value="" disabled selected>Pilih Kelamin...</option>
                                        <option value="Jantan">Jantan</option>
                                        <option value="Betina">Betina</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="umur_hewan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Umur</label>
                                    <input type="text" id="umur_hewan" name="umur_hewan" placeholder="cth. 2 tahun"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label for="warna_hewan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warna</label>
                                    <input type="text" id="warna_hewan" name="warna_hewan" placeholder="cth. Oren"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                                </div>
                            </div>

                            <div id="lastVisitNote" class="hidden text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2">
                                <i class="fa-regular fa-clock mr-1"></i><span id="lastVisitText"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Pemeriksaan Medis -->
        <div id="lockWrapMedis" class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div id="lockOverlayMedis" class="absolute inset-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-[1px] flex items-center justify-center">
                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2"><i class="fa-solid fa-lock"></i>Pilih atau daftarkan pasien terlebih dahulu</p>
            </div>
            <div class="bg-brand-primary/5 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <i class="fa-solid fa-stethoscope text-brand-primary dark:text-brand-light"></i>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Pemeriksaan Medis</h2>
            </div>
            <div class="p-6 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- 1. Anamnesa (Dropdown Search Multi + Tabel) -->
                    <div id="anamnesaMultiSelect" class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Anamnesa (Bisa &gt; 1)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" class="ms-search w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm"
                                placeholder="Cari anamnesa..." autocomplete="off">
                            <div class="ms-dropdown hidden absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                @forelse($anamnesas as $a)
                                    <button type="button" data-id="{{ $a->id_anamnesa }}" data-name="{{ $a->nama_anamnesa }}"
                                        class="ms-option w-full text-left px-4 py-2.5 hover:bg-brand-primary/5 dark:hover:bg-gray-700/50 text-sm text-gray-700 dark:text-gray-200 transition-colors">{{ $a->nama_anamnesa }}</button>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-400">Tidak ada data anamnesa</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="ms-hidden"></div>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mt-2">
                            <table class="w-full text-left text-sm">
                                <tbody class="ms-tbody divide-y divide-gray-100 dark:divide-gray-700">
                                    <tr class="ms-empty">
                                        <td class="p-3 text-center text-xs text-gray-400 dark:text-gray-500">Belum ada anamnesa dipilih</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 2. Diagnosa (Dropdown Tunggal) -->
                    <div class="space-y-1.5">
                        <label for="diagnosa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Diagnosa</label>
                        <select id="diagnosa" name="diagnosa"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Diagnosa...</option>
                            @foreach($diagnosas as $d)
                                <option value="{{ $d->id_diagnosa }}">{{ $d->nama_diagnosa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 3. Terapi / Obat (Dropdown Search Multi + Tabel) -->
                <div id="obatMultiSelect" class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Terapi / Obat (Bisa &gt; 1)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" class="ms-search w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm"
                            placeholder="Cari terapi / obat..." autocomplete="off">
                        <div class="ms-dropdown hidden absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            @forelse($obats as $o)
                                <button type="button" data-id="{{ $o->id_obat }}" data-name="{{ $o->nama_obat }}"
                                    class="ms-option w-full text-left px-4 py-2.5 hover:bg-brand-primary/5 dark:hover:bg-gray-700/50 text-sm text-gray-700 dark:text-gray-200 transition-colors">{{ $o->nama_obat }}</button>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-400">Tidak ada data obat</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="ms-hidden"></div>
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mt-2">
                        <table class="w-full text-left text-sm">
                            <tbody class="ms-tbody divide-y divide-gray-100 dark:divide-gray-700">
                                <tr class="ms-empty">
                                    <td class="p-3 text-center text-xs text-gray-400 dark:text-gray-500">Belum ada obat dipilih</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section 4: Tindakan & Biaya -->
        <div id="lockWrapBiaya" class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div id="lockOverlayBiaya" class="absolute inset-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-[1px] flex items-center justify-center">
                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2"><i class="fa-solid fa-lock"></i>Pilih atau daftarkan pasien terlebih dahulu</p>
            </div>
            <div class="bg-brand-primary/5 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <i class="fa-solid fa-file-invoice-dollar text-brand-primary dark:text-brand-light"></i>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Tindakan & Biaya</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Pelayanan -->
                    <div class="space-y-1.5 lg:col-span-2">
                        <label for="pelayanan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Pelayanan</label>
                        <select id="pelayanan" name="pelayanan" onchange="updateRetribusi()" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Pelayanan...</option>
                            @foreach($pelayanans as $pelayanan)
                                <option value="{{ $pelayanan->id_pelayanan }}" data-tarif="{{ $pelayanan->tarif }}" data-jenis="{{ $pelayanan->id_jenis }}" data-kelamin="{{ $pelayanan->jenis_kelamin }}">
                                    {{ $pelayanan->nama_pelayanan }}@if($pelayanan->id_jenis || $pelayanan->jenis_kelamin) ({{ $pelayanan->jenisHewan->nama_jenis ?? 'Semua Jenis' }}{{ $pelayanan->jenis_kelamin ? ' - '.$pelayanan->jenis_kelamin : '' }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dokter -->
                    <div class="space-y-1.5 lg:col-span-1">
                        <label for="dokter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dokter</label>
                        <select id="dokter" name="dokter" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>-- Pilih Dokter --</option>
                            @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id_dokter }}">{{ $dokter->nama_dokter }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Paramedis -->
                    <div class="space-y-1.5 lg:col-span-1">
                        <label for="paramedis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paramedik</label>
                        <select id="paramedis" name="paramedis" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                            <option value="" selected>-- Tidak Ada --</option>
                            @foreach($paramedis as $p)
                                <option value="{{ $p->id_paramedis }}">{{ $p->nama_paramedis }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Retribusi -->
                    <div class="space-y-1.5 lg:col-span-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-600 mt-2 flex justify-between items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Total Retribusi (Rp)</span>
                        <input type="text" id="retribusi" name="retribusi" value="0" readonly
                            class="bg-transparent text-right text-2xl font-bold text-brand-primary dark:text-brand-light focus:outline-none w-1/2">
                    </div>

                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 pb-10">
            <button type="submit" id="btnSubmit"
                class="w-full bg-brand-primary hover:bg-brand-dark text-white font-semibold py-4 rounded-xl shadow-lg shadow-brand-primary/20 transform hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2 text-lg">
                <i class="fa-solid fa-save"></i>
                <span>SIMPAN DATA REKAM MEDIS</span>
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('tanggal').valueAsDate = new Date();
    lockSections();

    const searchInput = document.getElementById('searchPasien');
    const searchResults = document.getElementById('searchResults');
    const searchStage = document.getElementById('searchStage');
    const patientCard = document.getElementById('patientCard');
    const patientCardStatus = document.getElementById('patientCardStatus');

    let currentHewanData = [];
    let currentPemilikData = [];

    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        const q = searchInput.value.trim();
        
        if (q.length < 2) { 
            searchResults.classList.add('hidden'); 
            searchResults.innerHTML = ''; 
            return; 
        }

        searchResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">Mencari...</div>';
        searchResults.classList.remove('hidden');

        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/rekam-medis/search?q=${encodeURIComponent(q)}`);
                const data = await response.json();
                
                currentHewanData = data.hewans;
                currentPemilikData = data.pemiliks;

                renderSearchResults(currentHewanData, currentPemilikData);
            } catch (error) {
                console.error("Error fetching search results:", error);
                searchResults.innerHTML = '<div class="px-4 py-3 text-sm text-red-500">Gagal mengambil data pencarian.</div>';
            }
        }, 300);
    });

    function renderSearchResults(hewans, pemiliks) {
        let html = '';

        hewans.forEach(a => {
            const owner = a.pemilik;
            html += `
            <button type="button" onclick="selectExistingPet('${a.id_hewan}')" class="w-full text-left px-4 py-3 hover:bg-brand-primary/5 dark:hover:bg-gray-700/50 transition-colors flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-brand-bg dark:bg-brand-primary/20 flex items-center justify-center text-brand-dark dark:text-brand-light flex-shrink-0">
                    <i class="fa-solid fa-paw"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${a.nama_hewan} <span class="text-gray-400 font-normal">· ID: ${a.id_hewan}</span></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Pemilik: ${owner.nama_pemilik} · ${owner.no_hp}</p>
                </div>
            </button>`;
        });

        pemiliks.forEach(o => {
            html += `
            <div class="px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300 flex-shrink-0">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${o.nama_pemilik} <span class="text-gray-400 font-normal">· ${o.no_hp}</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Punya ${o.hewans.length} hewan terdaftar</p>
                    </div>
                </div>
                <button type="button" onclick="selectOwnerForNewPet('${o.id_pemilik}')" class="text-xs font-medium text-brand-primary dark:text-brand-light hover:underline whitespace-nowrap flex-shrink-0">+ Hewan Baru</button>
            </div>`;
        });

        if (!hewans.length && !pemiliks.length) {
            html = `
            <div class="px-4 py-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2"><i class="fa-regular fa-circle-xmark mr-1"></i>Data tidak ditemukan</p>
                <button type="button" onclick="selectNewRegistration()" class="text-sm font-medium text-brand-primary dark:text-brand-light hover:underline">+ Daftar Pasien & Pemilik Baru</button>
            </div>`;
        }

        searchResults.innerHTML = html;
    }

    const pemilikFields = ['nama_pemilik', 'no_hp_pemilik', 'alamat'];
    const hewanFields = ['nama_hewan', 'jenis_hewan', 'jenis_kelamin', 'umur_hewan', 'warna_hewan'];

    function selectExistingPet(id_hewan) {
        const pet = currentHewanData.find(a => String(a.id_hewan) === String(id_hewan));
        const owner = pet.pemilik;

        document.getElementById('id_pemilik').value = owner.id_pemilik;
        document.getElementById('id_hewan').value = pet.id_hewan;

        document.getElementById('nama_pemilik').value = owner.nama_pemilik;
        document.getElementById('no_hp_pemilik').value = owner.no_hp;
        document.getElementById('alamat').value = owner.alamat;

        document.getElementById('nama_hewan').value = pet.nama_hewan;
        document.getElementById('jenis_hewan').value = pet.id_jenis;
        document.getElementById('jenis_kelamin').value = pet.jenis_kelamin;
        document.getElementById('umur_hewan').value = pet.umur;
        document.getElementById('warna_hewan').value = pet.warna;

        setFieldsState([...pemilikFields, ...hewanFields], true);
        
        patientCardStatus.innerHTML = '<i class="fa-solid fa-circle-check"></i> Pasien Lama — Data Ditemukan';
        showPatientCard();
        filterPelayanan();
    }

    function selectOwnerForNewPet(id_pemilik) {
        const owner = currentPemilikData.find(o => String(o.id_pemilik) === String(id_pemilik));

        document.getElementById('id_pemilik').value = owner.id_pemilik;
        document.getElementById('id_hewan').value = ""; 

        document.getElementById('nama_pemilik').value = owner.nama_pemilik;
        document.getElementById('no_hp_pemilik').value = owner.no_hp;
        document.getElementById('alamat').value = owner.alamat;

        clearFields(hewanFields);
        setFieldsState(pemilikFields, true);
        setFieldsState(hewanFields, false);

        patientCardStatus.innerHTML = '<i class="fa-solid fa-plus"></i> Pemilik Lama — Hewan Baru';
        showPatientCard();
        resetFilterPelayanan();
        document.getElementById('nama_hewan').focus();
    }

    function selectNewRegistration() {
        document.getElementById('id_pemilik').value = "";
        document.getElementById('id_hewan').value = "";

        clearFields(pemilikFields);
        clearFields(hewanFields);
        setFieldsState([...pemilikFields, ...hewanFields], false);

        patientCardStatus.innerHTML = '<i class="fa-solid fa-user-plus"></i> Pendaftaran Baru';
        showPatientCard();
        resetFilterPelayanan();
        document.getElementById('nama_pemilik').focus();
    }

    function toggleEdit(group) {
        const ids = group === 'pemilik' ? pemilikFields : hewanFields;
        const btn = group === 'pemilik' ? document.getElementById('btnEditPemilik') : document.getElementById('btnEditHewan');
        const isCurrentlyEditable = ids.some(id => {
            const el = document.getElementById(id);
            return el.tagName === 'SELECT' ? !el.disabled : !el.readOnly;
        });
        setFieldsState(ids, isCurrentlyEditable);
        btn.innerHTML = isCurrentlyEditable
            ? '<i class="fa-solid fa-pen mr-1"></i>Edit'
            : '<i class="fa-solid fa-check mr-1"></i>Selesai';
    }

    function clearFields(ids) { 
        ids.forEach(id => { 
            const el = document.getElementById(id); 
            if(el) { el.value = ''; }
        }); 
    }
    
    function setFieldsState(ids, readonly) {
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (el.tagName === 'SELECT') { el.disabled = readonly; } else { el.readOnly = readonly; }
            el.classList.toggle('bg-gray-100', readonly);
            el.classList.toggle('dark:bg-gray-800', readonly);
            el.classList.toggle('cursor-not-allowed', readonly);
            el.classList.toggle('bg-gray-50', !readonly);
            el.classList.toggle('dark:bg-gray-900', !readonly);
        });
    }
    
    function showPatientCard() {
        searchStage.classList.add('hidden');
        patientCard.classList.remove('hidden');
        document.getElementById('btnEditPemilik').classList.remove('hidden');
        document.getElementById('btnEditHewan').classList.remove('hidden');
        unlockSections();
    }
    
    function resetSearch() {
        patientCard.classList.add('hidden');
        searchStage.classList.remove('hidden');
        searchInput.value = '';
        searchResults.classList.add('hidden');
        document.getElementById('id_pemilik').value = "";
        document.getElementById('id_hewan').value = "";
        lockSections();
        resetFilterPelayanan();
    }

    function lockSections() {
        document.getElementById('lockOverlayMedis').classList.remove('hidden');
        document.getElementById('lockOverlayBiaya').classList.remove('hidden');
    }
    
    function unlockSections() {
        document.getElementById('lockOverlayMedis').classList.add('hidden');
        document.getElementById('lockOverlayBiaya').classList.add('hidden');
    }

    function updateRetribusi() {
        const select = document.getElementById('pelayanan');
        const retribusiInput = document.getElementById('retribusi');
        
        if(select.selectedIndex > 0) {
            const tarif = select.options[select.selectedIndex].getAttribute('data-tarif');
            retribusiInput.value = new Intl.NumberFormat('id-ID').format(tarif);
        } else {
            retribusiInput.value = '0';
        }
    }

    // ===== Filter Jenis Pelayanan sesuai Jenis & Kelamin Hewan =====
    const pelayananSelect = document.getElementById('pelayanan');
    const pelayananOptions = Array.from(pelayananSelect.options).filter(o => o.value !== "");
    const jenisHewanSelect = document.getElementById('jenis_hewan');
    const jenisKelaminSelect = document.getElementById('jenis_kelamin');

    function filterPelayanan() {
        const idJenisHewan = jenisHewanSelect.value;
        const kelaminHewan = jenisKelaminSelect.value;

        pelayananOptions.forEach(opt => {
            const optJenis = opt.getAttribute('data-jenis') || '';
            const optKelamin = opt.getAttribute('data-kelamin') || '';
            // Cocok kalau pelayanan berlaku "semua jenis" (kosong) ATAU jenisnya sama persis
            const jenisCocok = !optJenis || optJenis === idJenisHewan;
            // Cocok kalau pelayanan berlaku "semua kelamin" (kosong) ATAU kelaminnya sama persis
            const kelaminCocok = !optKelamin || optKelamin === kelaminHewan;
            opt.hidden = !(jenisCocok && kelaminCocok);
        });

        // Jika pilihan pelayanan yang sedang aktif jadi tidak sesuai lagi, reset ke placeholder
        const activeOption = pelayananSelect.options[pelayananSelect.selectedIndex];
        if (pelayananSelect.selectedIndex > 0 && activeOption && activeOption.hidden) {
            pelayananSelect.selectedIndex = 0;
            updateRetribusi();
        }
    }

    function resetFilterPelayanan() {
        pelayananOptions.forEach(opt => { opt.hidden = false; });
    }

    jenisHewanSelect.addEventListener('change', filterPelayanan);
    jenisKelaminSelect.addEventListener('change', filterPelayanan);

    // ===== Searchable Multi-select (Anamnesa & Terapi/Obat) =====
    function initMultiSelect(containerId, inputName) {
        const container = document.getElementById(containerId);
        const searchBox = container.querySelector('.ms-search');
        const dropdown = container.querySelector('.ms-dropdown');
        const options = Array.from(dropdown.querySelectorAll('.ms-option'));
        const hiddenWrap = container.querySelector('.ms-hidden');
        const tableBody = container.querySelector('.ms-tbody');
        const emptyRow = container.querySelector('.ms-empty');
        const selected = new Set();

        function updateEmptyRow() {
            emptyRow.classList.toggle('hidden', selected.size > 0);
        }

        function addItem(id, name) {
            if (selected.has(id)) return;
            selected.add(id);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = inputName;
            hidden.value = id;
            hidden.dataset.id = id;
            hiddenWrap.appendChild(hidden);

            const row = document.createElement('tr');
            row.dataset.id = id;
            row.innerHTML = `
                <td class="p-3 text-gray-800 dark:text-gray-200 text-sm">${name}</td>
                <td class="p-3 w-10 text-center">
                    <button type="button" class="ms-remove-btn text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </td>`;
            row.querySelector('.ms-remove-btn').addEventListener('click', () => removeItem(id));
            tableBody.appendChild(row);

            const optBtn = options.find(o => o.dataset.id === id);
            if (optBtn) optBtn.classList.add('hidden');

            updateEmptyRow();
        }

        function removeItem(id) {
            selected.delete(id);
            const hiddenInput = hiddenWrap.querySelector(`input[data-id="${id}"]`);
            if (hiddenInput) hiddenInput.remove();
            const row = tableBody.querySelector(`tr[data-id="${id}"]`);
            if (row) row.remove();
            const optBtn = options.find(o => o.dataset.id === id);
            if (optBtn) optBtn.classList.remove('hidden');
            updateEmptyRow();
        }

        function reset() {
            selected.clear();
            hiddenWrap.innerHTML = '';
            tableBody.querySelectorAll('tr:not(.ms-empty)').forEach(r => r.remove());
            options.forEach(o => o.classList.remove('hidden'));
            searchBox.value = '';
            dropdown.classList.add('hidden');
            updateEmptyRow();
        }

        options.forEach(btn => {
            btn.addEventListener('click', () => {
                addItem(btn.dataset.id, btn.dataset.name);
                searchBox.value = '';
                options.forEach(o => o.classList.remove('hidden'));
                selected.forEach(id => {
                    const b = options.find(o => o.dataset.id === id);
                    if (b) b.classList.add('hidden');
                });
            });
        });

        searchBox.addEventListener('input', () => {
            const q = searchBox.value.trim().toLowerCase();
            dropdown.classList.remove('hidden');
            options.forEach(btn => {
                const match = btn.dataset.name.toLowerCase().includes(q);
                const isSelected = selected.has(btn.dataset.id);
                btn.classList.toggle('hidden', !match || isSelected);
            });
        });

        searchBox.addEventListener('focus', () => {
            dropdown.classList.remove('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        updateEmptyRow();
        return { reset };
    }

    const anamnesaMS = initMultiSelect('anamnesaMultiSelect', 'anamnesa[]');
    const obatMS = initMultiSelect('obatMultiSelect', 'terapi[]');

    async function handleSimpan(e) {
        e.preventDefault();
        
        setFieldsState([...pemilikFields, ...hewanFields], false);

        const form = e.target;
        const btn = document.getElementById('btnSubmit');
        const originalContent = btn.innerHTML;
        const formData = new FormData(form);
        
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Menyimpan...</span>';
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');

        try {
            const response = await fetch("{{ route('rekam-medis.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if(response.ok && result.success) {
                btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>' + result.message + '</span>';
                btn.classList.replace('bg-brand-primary', 'bg-green-600');
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    btn.classList.replace('bg-green-600', 'bg-brand-primary');
                    btn.classList.remove('opacity-80', 'cursor-not-allowed');
                    
                    form.reset();

                    // Reset multi-select Anamnesa & Terapi/Obat
                    anamnesaMS.reset();
                    obatMS.reset();
                    
                    document.getElementById('tanggal').valueAsDate = new Date();
                    document.getElementById('retribusi').value = '0';
                    resetSearch();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 2000);
            } else {
                throw new Error(result.message || "Terjadi kesalahan di server.");
            }
        } catch (error) {
            console.error("Submit Error:", error);
            alert("Gagal menyimpan data: " + error.message);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-not-allowed');
        }
    }
</script>
@endpush