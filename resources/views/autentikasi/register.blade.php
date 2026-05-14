<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up — Jay's Billiard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="relative flex items-center justify-center min-h-screen p-8 md:p-6 sm:p-4">
        {{-- Background --}}
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/login-bg.png') }}" alt="Background" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black/65 backdrop-blur-sm"></div>
        </div>

        {{-- Sign Up Card --}}
        <div class="relative z-10 w-full max-w-md bg-[#0f141e]/85 backdrop-blur-custom border border-primary/15 rounded-2xl p-10 md:p-8 sm:p-7 shadow-2xl">
            <a href="{{ url('/') }}" class="flex items-center justify-center w-10 h-10 bg-white/5 border border-primary/20 rounded-lg text-white/40 mb-6 transition-all hover:bg-primary/10 hover:text-primary hover:border-primary/40 hover:-translate-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <h1 class="text-3xl md:text-2xl font-extrabold text-white mb-2">Sign Up</h1>
            <p class="text-white/50 text-sm leading-relaxed mb-8">Dengan membuat akun pemesanan di jay's billiard tegal, anda dapat mulai bisa memesan</p>

            @if($errors->any())
                <div class="bg-red-500/15 border border-red-500/30 rounded-xl px-4 py-3 mb-4">
                    @foreach($errors->all() as $error)
                        <p class="text-red-400 text-sm m-0 leading-relaxed">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-5">
                @csrf

                {{-- Nama Lengkap --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-white">Nama Lengkap</label>
                    <div class="flex items-center bg-white/5 border border-primary/20 rounded-xl px-4 transition-all focus-within:border-primary focus-within:shadow-neon-sm">
                        <span class="flex items-center text-white/35 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input type="text" name="name" class="flex-1 bg-transparent border-none outline-none text-white text-sm py-3.5 placeholder:text-white/30" placeholder="Masukkan nama lengkap anda" value="{{ old('name') }}" required>
                    </div>
                </div>

                {{-- Username --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-white">Username</label>
                    <div class="flex items-center bg-white/5 border border-primary/20 rounded-xl px-4 transition-all focus-within:border-primary focus-within:shadow-neon-sm">
                        <span class="flex items-center text-white/35 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input type="text" name="username" class="flex-1 bg-transparent border-none outline-none text-white text-sm py-3.5 placeholder:text-white/30" placeholder="Masukkan username anda" value="{{ old('username') }}" required>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-white">Email</label>
                    <div class="flex items-center bg-white/5 border border-primary/20 rounded-xl px-4 transition-all focus-within:border-primary focus-within:shadow-neon-sm">
                        <span class="flex items-center text-white/35 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <input type="email" name="email" class="flex-1 bg-transparent border-none outline-none text-white text-sm py-3.5 placeholder:text-white/30" placeholder="Masukkan email anda" value="{{ old('email') }}" required>
                    </div>
                </div>

                {{-- Nomor HP --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-white">Nomor HP</label>
                    <div class="flex items-center bg-white/5 border border-primary/20 rounded-xl px-4 transition-all focus-within:border-primary focus-within:shadow-neon-sm">
                        <span class="flex items-center text-white/35 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <input type="text" name="phone" class="flex-1 bg-transparent border-none outline-none text-white text-sm py-3.5 placeholder:text-white/30" placeholder="Masukkan nomor HP anda" value="{{ old('phone') }}" required>
                    </div>
                </div>

                {{-- Kata Sandi --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-white">Kata Sandi</label>
                    <div class="flex items-center bg-white/5 border border-primary/20 rounded-xl px-4 transition-all focus-within:border-primary focus-within:shadow-neon-sm">
                        <span class="flex items-center text-white/35 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="M8 11h8"/></svg>
                        </span>
                        <input type="password" name="password" id="passwordInput" class="flex-1 bg-transparent border-none outline-none text-white text-sm py-3.5 placeholder:text-white/30" placeholder="Masukkan kata sandi yang kuat" required>
                        <button type="button" class="flex items-center bg-transparent border-none text-white/40 cursor-pointer p-1 transition-colors hover:text-primary" onclick="togglePassword()">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mt-2">
                    <label class="flex items-center gap-2 text-sm text-white/60 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 accent-primary cursor-pointer">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm font-semibold text-primary transition-opacity hover:opacity-80">Lupa Kata Sandi?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-primary to-primary-dark text-black font-bold text-base px-4 py-3.5 rounded-xl border-none cursor-pointer transition-all hover:shadow-neon hover:-translate-y-0.5">
                    <span>Register</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                </button>
            </form>

            <p class="text-center text-sm text-white/50 mt-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-semibold transition-opacity hover:opacity-80">Log in</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
</body>
</html>
