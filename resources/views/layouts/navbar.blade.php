<header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 lg:px-8 z-10 transition-colors">
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-brand-primary dark:text-gray-400 dark:hover:text-brand-light focus:outline-none transition-colors p-1">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white hidden sm:block">@yield('page_title', 'Dashboard')</h1>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <button onclick="toggleTheme()" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors focus:outline-none shadow-inner">
            <i id="theme-icon" class="fa-solid fa-moon"></i>
        </button>

        <div class="h-6 w-px bg-gray-300 dark:bg-gray-600"></div>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:text-red-400 rounded-lg transition-colors font-medium text-sm border border-red-100 dark:border-red-900/50">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </form>
    </div>
</header>