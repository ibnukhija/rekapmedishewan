@extends('layouts.app')

@section('title', 'Kelola Data Pelayanan - Klinik Hewan Satwa Sehat')
@section('page_title', 'Kelola Data Pelayanan')

@push('styles')
<style>
    .form-input-focus:focus {
        box-shadow: 0 0 0 2px rgba(64, 145, 108, 0.2);
    }
    /* Modal Animation */
    .modal-overlay { backdrop-filter: blur(4px); transition: opacity 0.3s ease, visibility 0.3s ease; }
    .modal-content { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease; }
    .modal-hidden { opacity: 0; visibility: hidden; }
    .modal-hidden .modal-content { transform: scale(0.95) translateY(10px); opacity: 0; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-4">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-4">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Search Form -->
        <form action="{{ route('pelayanan.index') }}" method="GET" class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-gray-400"></i>
            </div>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama pelayanan/tindakan..."
                class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm shadow-sm" onchange="this.form.submit()">
        </form>
        
        <!-- Add Button -->
        <button onclick="openModal('add')" class="w-full sm:w-auto bg-brand-primary hover:bg-brand-dark text-white font-medium py-2.5 px-5 rounded-xl shadow-lg shadow-brand-primary/20 transform hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Pelayanan</span>
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 font-semibold border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap w-24">ID</th>
                        <th class="px-6 py-4 whitespace-nowrap">Nama Pelayanan / Tindakan</th>
                        <th class="px-6 py-4 whitespace-nowrap">Berlaku Untuk</th>
                        <th class="px-6 py-4 whitespace-nowrap">Tarif (Rp)</th>
                        <th class="px-6 py-4 min-w-[220px]">Keterangan</th>
                        <th class="px-6 py-4 whitespace-nowrap text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                    @forelse ($pelayanans as $index => $pelayanan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-md text-xs font-mono">
                                {{ $pelayanans->firstItem() + $index }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-stethoscope text-sm"></i>
                                </div>
                                {{ $pelayanan->nama_pelayanan }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                @if($pelayanan->id_jenis)
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full text-xs font-medium">
                                        {{ $pelayanan->jenisHewan->nama_jenis ?? '-' }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 rounded-full text-xs font-medium">
                                        Semua Jenis
                                    </span>
                                @endif
                                @if($pelayanan->jenis_kelamin)
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 rounded-full text-xs font-medium">
                                        {{ $pelayanan->jenis_kelamin }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            @if($pelayanan->tarif == 0)
                                <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full text-xs">Gratis</span>
                            @else
                                {{ number_format($pelayanan->tarif, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 line-clamp-2 mt-1 border-0 text-gray-500 dark:text-gray-400">
                            {{ $pelayanan->keterangan }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <!-- Tombol Edit -->
                                <button onclick="openModal('edit', {{ $pelayanan }})" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 flex items-center justify-center transition-colors tooltip" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                
                                <!-- Form Hapus -->
                                <form action="{{ route('pelayanan.destroy', $pelayanan->id_pelayanan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelayanan ini?');" class="m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 flex items-center justify-center transition-colors tooltip" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-briefcase-medical text-4xl mb-3 opacity-50"></i>
                            <p>Belum ada data pelayanan.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        <!-- Pagination Component Laravel -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
            {{ $pelayanans->links() }}
        </div>
    </div>

</div>

<!-- Modal Form (Tambah/Edit Pelayanan) -->
<div id="pelayananModal" class="modal-hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Overlay -->
    <div class="modal-overlay absolute inset-0 bg-gray-900/60" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="modal-content relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 dark:border-gray-700">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-briefcase-medical text-brand-primary"></i>
                <span id="modalTitleText">Tambah Data Pelayanan</span>
            </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form id="pelayananForm" action="{{ route('pelayanan.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <!-- Method field untuk update akan diinjeksi via JS saat mode Edit -->
            <div id="method-container"></div>
            
            <!-- Nama Pelayanan -->
            <div class="space-y-1.5">
                <label for="nama_pelayanan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pelayanan / Tindakan <span class="text-red-500">*</span></label>
                <input type="text" id="nama_pelayanan" name="nama_pelayanan" required placeholder="Contoh: Vaksinasi, Operasi, dll"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Boleh membuat nama yang sama lebih dari sekali jika tarifnya berbeda per jenis hewan / kelamin (mis. "Steril" untuk Jantan & Betina dibuat 2 baris terpisah).</p>
            </div>

            <!-- Jenis Hewan & Jenis Kelamin -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="id_jenis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Berlaku untuk Jenis</label>
                    <select id="id_jenis" name="id_jenis" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                        <option value="">-- Semua Jenis Hewan --</option>
                        @foreach($jenisHewans as $jenis)
                            <option value="{{ $jenis->id_jenis }}">{{ $jenis->nama_jenis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Berlaku untuk Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm appearance-none cursor-pointer">
                        <option value="">-- Semua / Tidak Berlaku --</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
            </div>

            <!-- Tarif -->
            <div class="space-y-1.5">
                <label for="tarif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tarif (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                    </div>
                    <input type="number" id="tarif" name="tarif" required placeholder="0" min="0" step="1000"
                        class="w-full pl-12 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Isi angka 0 jika pelayanan bersifat gratis.</p>
            </div>

            <!-- Keterangan -->
            <div class="space-y-1.5">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan Tambahan</label>
                <textarea id="keterangan" name="keterangan" rows="3" placeholder="Deskripsi singkat mengenai pelayanan ini..."
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm resize-none"></textarea>
            </div>

            <!-- Footer Actions -->
            <div class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium">
                    Batal
                </button>
                <button type="submit" id="btnSubmitModal" onclick="showLoading(this)" class="px-5 py-2.5 bg-brand-primary hover:bg-brand-dark text-white rounded-xl shadow-md shadow-brand-primary/20 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-save"></i>
                    <span>Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('pelayananModal');
    const form = document.getElementById('pelayananForm');
    const modalTitleText = document.getElementById('modalTitleText');
    const methodContainer = document.getElementById('method-container');

    // Konfigurasi route Laravel untuk dipanggil di Javascript
    const storeUrl = "{{ route('pelayanan.store') }}";
    const updateUrlBase = "{{ url('pelayanan') }}"; // Base URL untuk update (/pelayanan/{id})

    function openModal(mode, data = null) {
        form.reset();
        methodContainer.innerHTML = '';
        
        if (mode === 'edit' && data) {
            modalTitleText.textContent = 'Edit Data Pelayanan';
            // Set action form ke URL Update menggunakan primary key
            form.action = updateUrlBase + '/' + data.id_pelayanan;
            // Inject method PUT untuk Laravel
            methodContainer.innerHTML = '@method("PUT")';
            
            // Isi form dengan data yang dipilih
            document.getElementById('nama_pelayanan').value = data.nama_pelayanan;
            document.getElementById('id_jenis').value = data.id_jenis ?? '';
            document.getElementById('jenis_kelamin').value = data.jenis_kelamin ?? '';
            document.getElementById('tarif').value = data.tarif;
            document.getElementById('keterangan').value = data.keterangan;
        } else {
            modalTitleText.textContent = 'Tambah Data Pelayanan';
            // Set action form ke URL Store
            form.action = storeUrl;
        }
        
        modal.classList.remove('modal-hidden');
        document.getElementById('nama_pelayanan').focus();
    }

    function closeModal() {
        modal.classList.add('modal-hidden');
        setTimeout(() => form.reset(), 300);
    }

    // Efek loading saat tombol submit ditekan
    function showLoading(btn) {
        if(form.checkValidity()) {
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Menyimpan...</span>';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        }
    }
</script>
@endpush