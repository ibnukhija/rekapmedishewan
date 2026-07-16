<aside id="sidebar" class="bg-white dark:bg-gray-800 w-72 flex-shrink-0 border-r border-gray-200 dark:border-gray-700 flex flex-col fixed inset-y-0 left-0 transform -translate-x-full lg:relative lg:translate-x-0 z-30 shadow-lg lg:shadow-none transition-all duration-300 ease-in-out overflow-hidden">
    
    <div class="logo-container h-16 flex items-center px-6 border-b border-gray-200 dark:border-gray-700 transition-all">
        <div class="flex items-center gap-3 text-brand-primary dark:text-brand-light w-full overflow-hidden">
            <img class="h-12 w-auto object-contain" src="{{ asset('img/logo kota kediri.png') }}" alt="Logo">
        </div>
        <button onclick="toggleSidebar()" class="ml-auto lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex-shrink-0">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

<!-- ... (Bagian atas sidebar / logo tetap sama) ... -->

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 overflow-x-hidden">
        
        <p class="menu-header px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4 whitespace-nowrap">Menu Utama</p>
        
        <!-- MENU PUBLIK: Bisa diakses Admin & Operator -->
        <a href="{{ route('dashboard') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 bg-brand-primary/10 dark:bg-brand-primary/20 text-brand-primary dark:text-brand-light rounded-lg transition-colors group font-medium" title="Dashboard">
            <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0"></i>
            <span class="menu-text whitespace-nowrap">Dashboard</span>
        </a>

        <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors group" title="Input Rekam Medis">
            <i class="fa-solid fa-laptop-medical w-5 text-center flex-shrink-0 group-hover:text-brand-primary dark:group-hover:text-brand-light transition-colors"></i>
            <span class="menu-text whitespace-nowrap">Input Rekam Medis</span>
        </a>

        <!-- MENU PRIVAT: HANYA TAMPIL JIKA LOGIN SEBAGAI ADMIN -->
        @if (Auth::user()->role === 'admin')
            <p class="menu-header px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 whitespace-nowrap">Data Master</p>

            <a href="{{ route('dokter.index') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-user-doctor w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Kelola Dokter</span>
            </a>
            
            <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-user-nurse w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Kelola Paramedis</span>
            </a>

            <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-cat w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Kelola Jenis Hewan</span>
            </a>

            <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-stethoscope w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Kelola Pelayanan</span>
            </a>
            
            <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-heart-pulse w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Kelola Diagnosa</span>
            </a>
            
            <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-file-waveform w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Kelola Anamnesa</span>
            </a>

            <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-pills w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Kelola Obat</span>
            </a>

            <p class="menu-header px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 whitespace-nowrap">Laporan</p>

            <a href="#" class="menu-link flex items-center gap-3 px-3 py-2.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                <i class="fa-solid fa-file-contract w-5 text-center flex-shrink-0 group-hover:text-brand-primary"></i>
                <span class="menu-text whitespace-nowrap">Rekap Laporan</span>
            </a>
        @endif
        <!-- AKHIR AREA ADMIN -->

        <div class="menu-header mt-8 mx-2 p-4 bg-brand-primary/5 dark:bg-gray-700/30 rounded-xl border border-brand-primary/10 dark:border-gray-600/50 text-center relative overflow-hidden group transition-all hover:bg-brand-primary/10">
            <i class="fa-solid fa-wheat-awn absolute -right-3 -bottom-3 text-5xl text-brand-primary/10 dark:text-white/5 -rotate-12 transition-transform group-hover:scale-110"></i>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 bg-white dark:bg-gray-800 rounded-full shadow-sm flex items-center justify-center mb-2 text-brand-primary dark:text-brand-light">
                    <i class="fa-solid fa-building-columns text-sm"></i>
                </div>
                <p class="text-[10px] font-bold text-brand-dark dark:text-gray-300 uppercase tracking-widest leading-relaxed">
                    Dinas Ketahanan Pangan<br>dan Pertanian
                </p>
                <div class="w-8 h-[1px] bg-brand-primary/20 dark:bg-gray-500 my-2"></div>
                <p class="text-[9px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    Kota Kediri
                </p>
            </div>
        </div>

    </nav>
    
    <div class="profile-container p-4 border-t border-gray-200 dark:border-gray-700 flex items-center gap-3 transition-all">
        <div class="w-10 h-10 rounded-full bg-brand-primary text-white flex items-center justify-center font-bold flex-shrink-0">
            {{ substr(Auth::user()->nama ?? 'A', 0, 1) }}
        </div>
        <div class="profile-info flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->nama ?? 'Admin Klinik' }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->role ?? 'Administrator' }}</p>
        </div>
    </div>
</aside>