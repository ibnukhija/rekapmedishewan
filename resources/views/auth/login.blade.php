<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Hewan Satwa Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
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
        .shape-blob {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
            clip-path: polygon(0 0, 100% 0, 75% 100%, 0% 100%);
            z-index: 0;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-brand-bg min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden font-sans">

    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-brand-light rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-brand-primary rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <!-- Main Container -->
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row relative z-10 min-h-[600px]">
        
        <!-- Left Side - Branding -->
        <div class="relative w-full md:w-5/12 min-h-[300px] md:min-h-full flex items-center justify-center p-8 text-white overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-dark to-brand-primary"></div>
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

            <div class="relative z-10 text-center flex flex-col items-center">
                <div class="bg-white/20 p-4 rounded-full backdrop-blur-sm mb-6 inline-block shadow-lg">
                    <i class="fa-solid fa-paw text-5xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold tracking-wide mb-2 leading-tight">
                    Klinik Hewan<br>Satwa Sehat
                </h1>
                <p class="text-brand-bg text-sm font-medium tracking-widest uppercase mb-6">
                    Kota Kediri
                </p>
                <div class="w-16 h-1 bg-white/50 rounded-full mb-6 mx-auto"></div>
                <p class="text-white/80 text-sm max-w-[250px] font-semibold leading-relaxed">
                    UPT Pusat Kesehatan Hewan<br>Dinas Ketahanan Pangan Dan Pertanian Kota Kediri.
                </p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-7/12 p-8 md:p-12 lg:p-16 flex items-center justify-center bg-gray-50/50">
            <div class="w-full max-w-md glass-panel p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                
                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <!-- Pastikan path logo sesuai dengan folder public Anda -->
                    <div class="w-28 h-28 mx-auto mb-4 relative">
                        <img src="{{ asset('img/logo kota kediri.png') }}" alt="Logo Kota Kediri" class="w-full h-full object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang</h2>
                    <p class="text-gray-500 text-sm">Silakan login ke akun Anda</p>
                </div>

                <!-- Alert Error Laravel -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                        <p class="text-sm font-medium">{{ $errors->first() }}</p>
                    </div>
                @endif

                <!-- Form Login -->
                <!-- Arahkan action ke route proses login, gunakan method POST, dan tambahkan @csrf -->
                <form action="{{ route('login.post') }}" method="POST" onsubmit="showLoading(event)" class="space-y-6">
                    @csrf
                    
                    <!-- Username Input -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-brand-primary transition-colors">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl text-gray-700 text-sm focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all outline-none shadow-sm" 
                            placeholder="Username" required autocomplete="username" autofocus>
                    </div>

                    <!-- Password Input -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-brand-primary transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" 
                            class="block w-full pl-11 pr-12 py-3.5 bg-white border border-gray-200 rounded-xl text-gray-700 text-sm focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all outline-none shadow-sm" 
                            placeholder="Password" required>
                        <!-- Toggle Password Visibility -->
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" 
                        class="w-full bg-brand-primary hover:bg-brand-dark text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-brand-primary/30 transform hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2 mt-4">
                        <span>Login</span>
                        <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                    </button>
                </form>
                
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Script untuk menampilkan loading saat form di-submit sungguhan ke server
        function showLoading(e) {
            const btn = e.target.querySelector('button[type="submit"]');
            // Ganti teks tombol menjadi proses loading
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';
            // Hindari klik berulang
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            // Catatan: Tidak ada e.preventDefault() di sini agar form tetap terkirim ke Laravel
        }
    </script>
</body>
</html>