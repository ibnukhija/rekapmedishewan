@extends('layouts.app')

@section('title', 'Kelola Token API - S-ALPUKAT')
@section('page_title', 'Kelola Token API')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Alert Success -->
    @if(session('success'))
    <div id="alert-success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-4 flex justify-between items-center transition-opacity duration-500">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        <button onclick="closeAlert('alert-success')" class="text-green-600 hover:text-green-800 focus:outline-none px-2">
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

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Token Akses API</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola token untuk sistem luar yang mengakses data surveilans.</p>
        </div>
        <button type="button" onclick="openAddModal()"
            class="bg-brand-primary hover:bg-brand-dark text-white font-medium py-2.5 px-4 rounded-xl shadow-lg shadow-brand-primary/20 transition-all duration-200 flex items-center gap-2 text-sm">
            <i class="fa-solid fa-key"></i>
            <span>Generate Token</span>
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 font-semibold border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Nama Token</th>
                        <th class="px-5 py-4 font-semibold">Terakhir Dipakai</th>
                        <th class="px-5 py-4 font-semibold">Dibuat</th>
                        <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tokens as $token)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">
                                <i class="fa-solid fa-key text-gray-400 mr-1.5"></i>{{ $token->name }}
                            </td>
                            <td class="px-5 py-3">
                                @if($token->last_used_at)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-brand-bg text-brand-dark dark:bg-brand-primary/20 dark:text-brand-light">
                                        <i class="fa-solid fa-circle-check"></i> {{ $token->last_used_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                        <i class="fa-regular fa-circle"></i> Belum pernah
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">{{ $token->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form action="{{ route('admin.api-tokens.destroy', $token->id) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmRevoke(this, '{{ addslashes($token->name) }}')"
                                            class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 flex items-center justify-center transition-colors tooltip" title="Cabut Token">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada token yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Generate Token -->
<div id="tokenModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form id="tokenForm" method="POST" action="{{ route('admin.api-tokens.store') }}">
            @csrf

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Generate Token Baru</h3>
                <button type="button" onclick="closeModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="px-6 py-6 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Token</label>
                    <input type="text" name="token_name" id="input_token_name" required placeholder="mis. dashboard-kota"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:outline-none focus:border-brand-primary dark:focus:border-brand-light text-sm">
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal()"
                    class="w-full sm:w-auto bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-2.5 px-5 rounded-xl transition-all duration-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                    Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-auto bg-brand-primary hover:bg-brand-dark text-white font-medium py-2.5 px-5 rounded-xl transition-all duration-200">
                    Generate
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // --- FITUR AUTO-HIDE ALERT ---
    function closeAlert(elementId) {
        const alertElement = document.getElementById(elementId);
        if (alertElement) {
            alertElement.style.opacity = '0';
            setTimeout(() => { alertElement.style.display = 'none'; }, 500);
        }
    }

    if (document.getElementById('alert-success')) {
        setTimeout(() => { closeAlert('alert-success'); }, 2000);
    }
    if (document.getElementById('alert-error')) {
        setTimeout(() => { closeAlert('alert-error'); }, 2000);
    }

    // --- MODAL GENERATE TOKEN ---
    const tokenModal = document.getElementById('tokenModal');
    const tokenForm = document.getElementById('tokenForm');

    function openAddModal() {
        tokenForm.reset();
        tokenModal.classList.remove('hidden');
        tokenModal.classList.add('flex');
    }

    function closeModal() {
        tokenModal.classList.add('hidden');
        tokenModal.classList.remove('flex');
    }

    tokenModal.addEventListener('click', (e) => {
        if (e.target === tokenModal) closeModal();
    });

    // --- KONFIRMASI CABUT TOKEN ---
    function confirmRevoke(button, namaToken) {
        Swal.fire({
            title: 'Cabut Token Ini?',
            text: `Token "${namaToken}" akan langsung berhenti berfungsi. Sistem yang masih pakai token ini akan ditolak akses.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fa-solid fa-ban mr-1"></i> Ya, Cabut!',
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
                    title: 'Mencabut...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                button.closest('form').submit();
            }
        });
    }

    @if(session('new_token'))
        Swal.fire({
            title: 'Token Berhasil Dibuat!',
            html: `
                <p class="text-sm text-gray-500 mb-3">Nama: <b>{{ session('new_token_name') }}</b></p>
                <p class="text-xs text-red-500 mb-2">Copy sekarang — token ini tidak akan ditampilkan lagi setelah ini.</p>
                <div class="flex items-center gap-2">
                    <input type="text" id="newTokenValue" value="{{ session('new_token') }}" readonly
                        style="color: #111827; background-color: #f9fafb;"
                        class="w-full text-xs font-mono px-3 py-2 border border-gray-300 rounded-lg select-all">
                    <button onclick="copyNewToken()" class="bg-brand-primary text-white px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap">
                        <i class="fa-solid fa-copy"></i> Copy
                    </button>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Selesai',
            confirmButtonColor: '#40916c',
            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827',
            customClass: { popup: 'rounded-2xl shadow-2xl' }
        });
    @endif

    function copyNewToken() {
        const input = document.getElementById('newTokenValue');
        input.select();
        navigator.clipboard.writeText(input.value);
    }

    @if($errors->any())
        openAddModal();
        Swal.fire({
            icon: 'error',
            title: 'Periksa kembali isian',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    @endif
</script>
@endpush
@endsection