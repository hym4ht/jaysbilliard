@extends('layouts.app')
@section('title', "Promo - Jay's Billiard")

@section('content')
{{-- ═══════════════════════════════ HERO SECTION ═══════════════════════════════ --}}
<section class="relative min-h-[40vh] flex items-center justify-center text-center overflow-hidden">
    <div class="absolute inset-0 bg-[#0d0d0d]" style="background-image: url('/images/billiard bg.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    </div>

    <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 pt-28 pb-12">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black uppercase leading-tight mb-4 text-white tracking-wide">
            Promo <span class="text-neon">Spesial</span>
        </h1>
        <p class="text-white/65 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
            Jangan lewatkan penawaran terbaik kami untuk pengalaman billiard yang lebih hemat
        </p>
    </div>
</section>

{{-- ═══════════════════════════════ PROMO LIST ═══════════════════════════════ --}}
<section class="py-16 sm:py-24 px-4 sm:px-6 max-w-7xl mx-auto">
    <div class="grid md:grid-cols-2 gap-6">
        {{-- Promo 1: Malam Pelajar --}}
        <div class="relative rounded-2xl overflow-hidden group cursor-pointer">
            <img src="{{ asset('images/hero-bg.png') }}"
                 alt="Malam Pelajar"
                 class="w-full aspect-video object-cover object-center group-hover:scale-105 transition duration-500"/>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

            <div class="absolute inset-0 p-6 flex flex-col justify-between">
                <span class="self-start bg-[#00e5ff] text-black text-[10px] font-black uppercase
                             tracking-widest rounded-full px-3 py-1 shadow-[0_0_10px_rgba(0,229,255,0.5)]">
                    WAKTU TERBATAS
                </span>

                <div>
                    <h3 class="text-white font-black text-3xl uppercase leading-tight mb-2">
                        MALAM PELAJAR
                    </h3>
                    <p class="text-gray-300 text-sm mb-4">Dapatkan diskon 20% untuk setiap meja sebelum pukul 6 sore pada hari kerja.</p>
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1 text-[#00e5ff] text-xs font-semibold
                                  hover:text-white transition">
                            Book Sekarang <span class="text-lg">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Promo 2: Tangga Mingguan --}}
        <div class="relative rounded-2xl overflow-hidden group cursor-pointer">
            <img src="{{ asset('images/billiard bg.png') }}"
                 alt="Tangga Mingguan"
                 class="w-full aspect-video object-cover object-center group-hover:scale-105 transition duration-500"/>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

            <div class="absolute inset-0 p-6 flex flex-col justify-between">
                <span class="self-start bg-[#00e5ff] text-black text-[10px] font-black uppercase
                             tracking-widest rounded-full px-3 py-1 shadow-[0_0_10px_rgba(0,229,255,0.5)]">
                    TURNAMEN
                </span>

                <div>
                    <h3 class="text-white font-black text-3xl uppercase leading-tight mb-2">
                        TANGGA MINGGUAN
                    </h3>
                    <p class="text-gray-300 text-sm mb-4">Bergabunglah dalam kompetisi setiap hari Jumat. Hadiah hingga Rp 5.000.000.</p>
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1 text-[#00e5ff] text-xs font-semibold
                                  hover:text-white transition">
                            Daftar Sekarang <span class="text-lg">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Promo 3: Happy Hour Monday --}}
        <div class="relative rounded-2xl overflow-hidden group cursor-pointer">
            <img src="{{ asset('images/hero-bg.png') }}"
                 alt="Happy Hour Monday"
                 class="w-full aspect-video object-cover object-center group-hover:scale-105 transition duration-500"/>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

            <div class="absolute inset-0 p-6 flex flex-col justify-between">
                <span class="self-start bg-[#00e5ff] text-black text-[10px] font-black uppercase
                             tracking-widest rounded-full px-3 py-1 shadow-[0_0_10px_rgba(0,229,255,0.5)]">
                    HOT DEAL
                </span>

                <div>
                    <h3 class="text-white font-black text-3xl uppercase leading-tight mb-2">
                        HAPPY HOUR MONDAY
                    </h3>
                    <p class="text-gray-300 text-sm mb-4">Dapatkan diskon 30% untuk pemesanan meja di jam 10:00 - 15:00. Khusus hari Senin!</p>
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1 text-[#00e5ff] text-xs font-semibold
                                  hover:text-white transition">
                            Book Sekarang <span class="text-lg">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Promo 4: Weekend Special --}}
        <div class="relative rounded-2xl overflow-hidden group cursor-pointer">
            <img src="{{ asset('images/billiard bg.png') }}"
                 alt="Weekend Special"
                 class="w-full aspect-video object-cover object-center group-hover:scale-105 transition duration-500"/>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

            <div class="absolute inset-0 p-6 flex flex-col justify-between">
                <span class="self-start bg-[#00e5ff] text-black text-[10px] font-black uppercase
                             tracking-widest rounded-full px-3 py-1 shadow-[0_0_10px_rgba(0,229,255,0.5)]">
                    WEEKEND ONLY
                </span>

                <div>
                    <h3 class="text-white font-black text-3xl uppercase leading-tight mb-2">
                        WEEKEND SPECIAL
                    </h3>
                    <p class="text-gray-300 text-sm mb-4">Booking 3 jam dapat bonus 1 jam gratis. Berlaku Sabtu & Minggu.</p>
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1 text-[#00e5ff] text-xs font-semibold
                                  hover:text-white transition">
                            Book Sekarang <span class="text-lg">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════ CTA SECTION ═══════════════════════════════ --}}
<section class="py-16 px-4 sm:px-6 max-w-7xl mx-auto">
    <div class="bg-gradient-to-r from-cyan-500/10 to-blue-500/10 border border-cyan-400/20 rounded-2xl p-8 sm:p-12 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Siap Bermain?</h2>
        <p class="text-gray-300 text-sm mb-6 max-w-xl mx-auto">
            Manfaatkan promo spesial kami dan booking meja favoritmu sekarang
        </p>
        <a href="{{ route('login') }}" 
           class="inline-flex items-center gap-2 bg-cyan-400 text-black font-bold text-sm uppercase tracking-wider px-8 py-3.5 rounded-lg border-2 border-cyan-400 transition-all hover:bg-cyan-500 hover:border-cyan-500 hover:shadow-neon hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Book a Table
        </a>
    </div>
</section>
@endsection
