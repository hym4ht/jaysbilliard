@php
    $navLinks = [
        ['label' => 'Beranda', 'route' => 'home'],
        ['label' => 'Meja', 'route' => 'rates'],
        ['label' => 'Lokasi', 'route' => 'location'],
    ];
@endphp

<nav class="fixed top-0 w-full z-50 bg-[#0d0d0d]/95 backdrop-blur border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 md:py-4 flex items-center justify-between gap-4">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2 text-white font-bold text-base sm:text-lg">
            <img src="{{ asset('images/logo-jb.png') }}" alt="Jay's Billiard Logo" class="w-10 h-10 object-contain shrink-0">
            <span class="truncate">Jay's Billiard</span>
        </a>

        <!-- Menu Desktop -->
        <div class="hidden md:flex items-center gap-8 text-sm font-bold text-gray-300">
            @foreach ($navLinks as $item)
                <a href="{{ route($item['route']) }}"
                    class="{{ request()->routeIs($item['route']) ? 'text-primary' : 'hover:text-white' }} transition">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Auth Buttons -->
        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('login') }}"
                class="border border-[#00e5ff] text-[#00e5ff] text-sm font-bold px-5 py-2 rounded-md hover:bg-[#00e5ff] hover:text-black transition">
                Login
            </a>
            <a href="{{ route('register') }}"
                class="bg-[#00e5ff] text-black text-sm font-bold px-5 py-2 rounded-md hover:bg-[#00cce6] shadow-md hover:shadow-lg transition">
                Register
            </a>
        </div>

        <!-- Mobile Toggle -->
        <button type="button"
            class="md:hidden inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-white transition hover:border-primary/40 hover:text-primary"
            aria-label="Buka menu navigasi"
            aria-controls="website-mobile-menu"
            aria-expanded="false"
            data-website-menu-toggle>
            <svg data-menu-icon-open xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="4" x2="20" y1="6" y2="6"></line>
                <line x1="4" x2="20" y1="12" y2="12"></line>
                <line x1="4" x2="20" y1="18" y2="18"></line>
            </svg>
            <svg data-menu-icon-close class="hidden" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="website-mobile-menu" class="hidden md:hidden border-t border-white/5 px-4 pb-4" data-website-mobile-menu>
        <div class="rounded-xl border border-white/10 bg-[#111418]/95 p-3 shadow-2xl">
            <div class="grid gap-1">
                @foreach ($navLinks as $item)
                    <a href="{{ route($item['route']) }}"
                        class="rounded-lg px-4 py-3 text-sm font-bold transition {{ request()->routeIs($item['route']) ? 'bg-primary/10 text-primary' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="mt-3 grid grid-cols-2 gap-2 border-t border-white/5 pt-3">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-primary/40 px-4 py-3 text-sm font-bold text-primary transition hover:bg-primary/10">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-bold text-black transition hover:bg-primary-dark">
                    Register
                </a>
            </div>
        </div>
    </div>
</nav>
