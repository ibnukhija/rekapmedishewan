@extends('layouts.app')

@section('title', 'Kelola Data Diagnosa - Klinik Hewan Satwa Sehat')
@section('page_title', 'Kelola Data Diagnosa')

@push('styles')
<style>
    .form-input-focus:focus { box-shadow: 0 0 0 2px rgba(64, 145, 108, 0.2); }
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
    <div id="alert-success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-4 flex justify-between items-center transition-opacity duration-500">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        <!-- Tombol Close Manual -->
        <button onclick="closeAlert('alert-success')"
        class="text-green-600 hover:text-green-800 focus:outline-none px-2">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div id="alert-error" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-4 flex justify-between items-center transition-opacity duration-500">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
        <button onclick="closeAlert('alert-error')" class="text-red-600 hover:text-red-800 focus:outline-none px-2">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        
    <!-- Search Form -->
        <form action="{{ route('diagnosa.index') }}" method="GET" class="relative w-full sm:w-96">

            <!-- Tombol Search -->
            <button type="submit"
                class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-brand-primary transition-colors">
                <i class="fa-solid fa-search"></i>
            </button>

            <!-- Input -->
            <input
                type="text"
                name="cari"
                value="{{ request('cari') }}"
                placeholder="Cari nama diagnosa..."
                class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm shadow-sm">
        </form>

        <!-- Add Button -->
        <button onclick="openModal('add')" class="w-full sm:w-auto bg-brand-primary hover:bg-brand-dark text-white font-medium py-2.5 px-5 rounded-xl shadow-lg shadow-brand-primary/20 transform hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Diagnosa</span>
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 font-semibold border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap w-24">No</th>
                        <th class="px-6 py-4 whitespace-nowrap">Nama Diagnosa</th>
                        <th class="px-6 py-4 whitespace-nowrap text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                    @forelse ($dataDiagnosa as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-md text-xs font-mono">
                                {{ $dataDiagnosa->firstItem() + $index }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $item->nama_diagnosa }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <!-- Tombol Edit -->
                                <button onclick="openModal('edit', {{ json_encode($item) }})" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 flex items-center justify-center transition-colors tooltip" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                
                                <!-- Form Hapus dengan SweetAlert -->
                                <form action="{{ route('diagnosa.destroy', $item->id_diagnosa) }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 flex items-center justify-center transition-colors tooltip" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-50"></i>
                            <p>Belum ada data diagnosa.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        <!-- Custom Pagination -->
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between text-xs text-gray-500 dark:text-gray-400 shrink-0">
            <span>
                Menampilkan {{ $dataDiagnosa->firstItem() ?? 0 }} sampai {{ $dataDiagnosa->lastItem() ?? 0 }} dari {{ $dataDiagnosa->total() }} entri
            </span>
            <div class="w-full sm:w-auto">
                {{ $dataDiagnosa->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Form (Tambah/Edit Diagnosa) -->
<div id="diagnosaModal" class="modal-hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="modal-overlay absolute inset-0 bg-gray-900/60" onclick="closeModal()"></div>
    
    <div class="modal-content relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 dark:border-gray-700">
        
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-user-nurse text-brand-primary"></i>
                <span id="modalTitleText">Tambah Data Diagnosa</span>
            </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="diagnosaForm" action="{{ route('diagnosa.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div id="method-container"></div>
            
            <!-- Input Nama Diagnosa -->
            <div class="space-y-1.5">
                <label for="nama_diagnosa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Diagnosa<span class="text-red-500">*</span></label>
                <input type="text" id="nama_diagnosa" name="nama_diagnosa" value="{{ old('nama_diagnosa') }}" placeholder="Contoh: Penyakit Jantung"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light form-input-focus transition-colors text-sm">
                @error('nama_diagnosa')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
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
    // --- MODAL & FORM LOGIC ---
    const alertSuccess = document.getElementById('alert-success');
    const alertError = document.getElementById('alert-error');
    const modal = document.getElementById('diagnosaModal');
    const form = document.getElementById('diagnosaForm');
    const modalTitleText = document.getElementById('modalTitleText');
    const methodContainer = document.getElementById('method-container');
    
    const storeUrl = "{{ route('diagnosa.store') }}";
    const updateUrlBase = "{{ url('diagnosa') }}";

    // Fungsi tunggal untuk menutup alert berdasarkan ID-nya
    function closeAlert(elementId) {
        const alertElement = document.getElementById(elementId);
        if (alertElement) {
            alertElement.style.opacity = '0';
            setTimeout(() => {
                alertElement.style.display = 'none';
            }, 500);
        }
    }

    if (document.getElementById('alert-success')) {
        setTimeout(() => {
            closeAlert('alert-success');
        }, 2000); 
    }

    if (document.getElementById('alert-error')) {
        setTimeout(() => {
            closeAlert('alert-error');
        }, 2000); 
    }

    // Fungsi untuk membuka modal
    function openModal(mode, data = null) {
        form.reset();
        methodContainer.innerHTML = ''; 
        
        if (mode === 'edit' && data) {
            modalTitleText.textContent = 'Edit Data Diagnosa';
            form.action = updateUrlBase + '/' + data.id_diagnosa;
            methodContainer.innerHTML = '@method("PUT")';
            
            document.getElementById('nama_diagnosa').value = data.nama_diagnosa;
        } else {
            modalTitleText.textContent = 'Tambah Data Diagnosa';
            form.action = storeUrl;
        }
        
        modal.classList.remove('modal-hidden');
        document.getElementById('nama_diagnosa').focus();
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        modal.classList.add('modal-hidden');
        setTimeout(() => form.reset(), 300);
    }

    // Fungsi untuk menampilkan loading
    function showLoading(btn) {
        if(form.checkValidity()) {
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Menyimpan...</span>';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        }
    }

    // Fungsi konfirmasi hapus
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data diagnosa yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fa-solid fa-trash mr-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fa-solid fa-xmark mr-1"></i> Batal',
            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827',
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                confirmButton: 'px-5 py-2.5 rounded-xl font-medium tracking-wide',
                cancelButton: 'px-5 py-2.5 rounded-xl font-medium tracking-wide'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                button.closest('form').submit();
            }
        });
    }

    // --- AUTO-OPEN MODAL ON ERROR ---
    const adaError = {{ $errors->any() ? 'true' : 'false' }};
    if (adaError) {
        document.addEventListener("DOMContentLoaded", function() {
            modal.classList.remove('modal-hidden');
        });
    }
</script>
@endpush