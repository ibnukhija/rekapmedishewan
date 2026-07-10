<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik Hewan Satwa Sehat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            dark: '#2d6a4f',
                            primary: '#40916c',
                            light: '#52b788',
                            bg: '#d8f3dc',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        #sidebarOverlay { transition: opacity 0.3s ease-in-out; }
        
        /* Mini Sidebar Styles */
        .sidebar-mini .menu-text, .sidebar-mini .logo-text, .sidebar-mini .menu-header, .sidebar-mini .profile-info { display: none; }
        .sidebar-mini .menu-link { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-mini .logo-container { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-mini .logo-container .flex { justify-content: center; width: 100%; }
        .sidebar-mini .profile-container { justify-content: center; padding-left: 0; padding-right: 0; }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 font-sans h-screen flex overflow-hidden transition-colors duration-200">

    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0 transition-all duration-300 ease-in-out">
        
        @include('layouts.navbar')

        <main class="flex-1 overflow-y-auto p-4 lg:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            } else {
                sidebar.classList.toggle('w-72');
                sidebar.classList.toggle('w-20');
                sidebar.classList.toggle('sidebar-mini');
            }
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('w-20', 'sidebar-mini');
                sidebar.classList.add('w-72');
                overlay.classList.add('hidden');
            }
        });

        function toggleTheme() {
            const htmlClass = document.documentElement.classList;
            const themeIcon = document.getElementById('theme-icon');
            
            if (htmlClass.contains('dark')) {
                htmlClass.remove('dark');
                localStorage.setItem('theme', 'light');
                themeIcon.classList.replace('fa-sun', 'fa-moon');
            } else {
                htmlClass.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            }
        }

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.getElementById('theme-icon')?.classList.replace('fa-moon', 'fa-sun');
        }
    </script>
    @stack('scripts')
</body>
</html>