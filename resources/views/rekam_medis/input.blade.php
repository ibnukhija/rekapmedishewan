@extends('layouts.app')

@section('title', 'Input Rekam Medis - S-ALPUKAT')
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
    <form id="formRekamMedis" action="{{ route('rekam-medis.store') }}" onsubmit="handleSimpan(event)" class="space-y-6">
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
                            + Tambah Data Pasien Baru
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

                            <!-- ALAMAT: Kota Kediri (dropdown kelurahan) atau Luar Kota Kediri (manual) -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>

                                <div class="flex items-center gap-5 mb-1">
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" id="lokasi_dalam_kota" name="lokasi_alamat" value="dalam" checked onchange="toggleAlamatMode()">
                                        Kota Kediri
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" id="lokasi_luar_kota" name="lokasi_alamat" value="luar" onchange="toggleAlamatMode()">
                                        Luar Kota Kediri
                                    </label>
                                </div>

                                <div id="alamatDalamKotaWrap">
                                    <select id="alamat_kelurahan" name="alamat"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                                        <option value="" disabled selected>Pilih Kelurahan...</option>
                                        <optgroup label="Kecamatan Kota">
                                            <option value="Kelurahan Semampir, Kec. Kota, Kota Kediri">Semampir</option>
                                            <option value="Kelurahan Dandangan, Kec. Kota, Kota Kediri">Dandangan</option>
                                            <option value="Kelurahan Ngadirejo, Kec. Kota, Kota Kediri">Ngadirejo</option>
                                            <option value="Kelurahan Pakelan, Kec. Kota, Kota Kediri">Pakelan</option>
                                            <option value="Kelurahan Pocanan, Kec. Kota, Kota Kediri">Pocanan</option>
                                            <option value="Kelurahan Banjaran, Kec. Kota, Kota Kediri">Banjaran</option>
                                            <option value="Kelurahan Jagalan, Kec. Kota, Kota Kediri">Jagalan</option>
                                            <option value="Kelurahan Kemasan, Kec. Kota, Kota Kediri">Kemasan</option>
                                            <option value="Kelurahan Kaliombo, Kec. Kota, Kota Kediri">Kaliombo</option>
                                            <option value="Kelurahan Kampung Dalem, Kec. Kota, Kota Kediri">Kampung Dalem</option>
                                            <option value="Kelurahan Ngronggo, Kec. Kota, Kota Kediri">Ngronggo</option>
                                            <option value="Kelurahan Manisrenggo, Kec. Kota, Kota Kediri">Manisrenggo</option>
                                            <option value="Kelurahan Balowerti, Kec. Kota, Kota Kediri">Balowerti</option>
                                            <option value="Kelurahan Rejomulyo, Kec. Kota, Kota Kediri">Rejomulyo</option>
                                            <option value="Kelurahan Ringin Anom, Kec. Kota, Kota Kediri">Ringin Anom</option>
                                            <option value="Kelurahan Setono Gedong, Kec. Kota, Kota Kediri">Setono Gedong</option>
                                            <option value="Kelurahan Setono Pande, Kec. Kota, Kota Kediri">Setono Pande</option>
                                        </optgroup>
                                        <optgroup label="Kecamatan Mojoroto">
                                            <option value="Kelurahan Lirboyo, Kec. Mojoroto, Kota Kediri">Lirboyo</option>
                                            <option value="Kelurahan Campurejo, Kec. Mojoroto, Kota Kediri">Campurejo</option>
                                            <option value="Kelurahan Bandar Lor, Kec. Mojoroto, Kota Kediri">Bandar Lor</option>
                                            <option value="Kelurahan Dermo, Kec. Mojoroto, Kota Kediri">Dermo</option>
                                            <option value="Kelurahan Mrican, Kec. Mojoroto, Kota Kediri">Mrican</option>
                                            <option value="Kelurahan Mojoroto, Kec. Mojoroto, Kota Kediri">Mojoroto</option>
                                            <option value="Kelurahan Ngampel, Kec. Mojoroto, Kota Kediri">Ngampel</option>
                                            <option value="Kelurahan Gayam, Kec. Mojoroto, Kota Kediri">Gayam</option>
                                            <option value="Kelurahan Sukorame, Kec. Mojoroto, Kota Kediri">Sukorame</option>
                                            <option value="Kelurahan Pojok, Kec. Mojoroto, Kota Kediri">Pojok</option>
                                            <option value="Kelurahan Tamanan, Kec. Mojoroto, Kota Kediri">Tamanan</option>
                                            <option value="Kelurahan Bandar Kidul, Kec. Mojoroto, Kota Kediri">Bandar Kidul</option>
                                            <option value="Kelurahan Banjarmelati, Kec. Mojoroto, Kota Kediri">Banjarmelati</option>
                                            <option value="Kelurahan Bujel, Kec. Mojoroto, Kota Kediri">Bujel</option>
                                        </optgroup>
                                        <optgroup label="Kecamatan Pesantren">
                                            <option value="Kelurahan Jamsaren, Kec. Pesantren, Kota Kediri">Jamsaren</option>
                                            <option value="Kelurahan Bangsal, Kec. Pesantren, Kota Kediri">Bangsal</option>
                                            <option value="Kelurahan Burengan, Kec. Pesantren, Kota Kediri">Burengan</option>
                                            <option value="Kelurahan Pesantren, Kec. Pesantren, Kota Kediri">Pesantren</option>
                                            <option value="Kelurahan Pakunden, Kec. Pesantren, Kota Kediri">Pakunden</option>
                                            <option value="Kelurahan Singonegaran, Kec. Pesantren, Kota Kediri">Singonegaran</option>
                                            <option value="Kelurahan Tinalan, Kec. Pesantren, Kota Kediri">Tinalan</option>
                                            <option value="Kelurahan Banaran, Kec. Pesantren, Kota Kediri">Banaran</option>
                                            <option value="Kelurahan Tosaren, Kec. Pesantren, Kota Kediri">Tosaren</option>
                                            <option value="Kelurahan Betet, Kec. Pesantren, Kota Kediri">Betet</option>
                                            <option value="Kelurahan Blabak, Kec. Pesantren, Kota Kediri">Blabak</option>
                                            <option value="Kelurahan Bawang, Kec. Pesantren, Kota Kediri">Bawang</option>
                                            <option value="Kelurahan Ngletih, Kec. Pesantren, Kota Kediri">Ngletih</option>
                                            <option value="Kelurahan Tempurejo, Kec. Pesantren, Kota Kediri">Tempurejo</option>
                                            <option value="Kelurahan Ketami, Kec. Pesantren, Kota Kediri">Ketami</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div id="alamatLuarKotaWrap" class="hidden">
                                    <input type="text" id="alamat_manual" name="alamat" disabled placeholder="cth. Kandat, Kabupaten Kediri / Kota Blitar"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Cukup isi desa/kecamatan, dan nama kota/kabupaten saja.</p>
                                </div>
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

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- UMUR: Tahun & Bulan terpisah, digabung ke hidden umur_hewan sebelum submit -->
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Umur</label>
                                    <div class="flex items-center gap-1">
                                        <input type="number" min="0" id="umur_tahun" placeholder="0"
                                            class="w-full px-2 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm text-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Thn</span>
                                        <input type="number" min="0" max="11" id="umur_bulan" placeholder="0"
                                            class="w-full px-2 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm text-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Bln</span>
                                    </div>
                                    <input type="hidden" id="umur_hewan" name="umur_hewan" value="">
                                </div>
                                <div class="space-y-1.5">
                                    <label for="warna_hewan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warna</label>
                                    <input type="text" id="warna_hewan" name="warna_hewan" placeholder="cth. Oren"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label for="berat_badan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Berat Badan (kg)</label>
                                    <input type="number" step="0.01" min="0" id="berat_badan" name="berat_badan" placeholder="cth. 3.5"
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
        <div id="lockWrapMedis" class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div id="lockOverlayMedis" class="absolute inset-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-[1px] flex items-center justify-center rounded-2xl">
                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2"><i class="fa-solid fa-lock"></i>Pilih atau daftarkan pasien terlebih dahulu</p>
            </div>
            <div class="bg-brand-primary/5 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3 rounded-t-2xl">
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
                            <option value="" disabled selected>-- Pilih Paramedis --</option>
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
<script src="{{ asset('js/rekam-medis.js') }}"></script>
@endpush