@extends('layouts.app')
@section('title', 'Tarif — Jay\'s Billiard')

@section('content')
    {{-- ═══════════════════════════════ HEADER ═══════════════════════════════ --}}
    <section class="text-center pt-32 pb-8 px-6 max-w-3xl mx-auto">
        <h1 class="text-5xl md:text-6xl font-black uppercase text-white mb-4 tracking-wide">MEJA KAMI</h1>
        <p class="text-white/55 text-base leading-relaxed">
            Pilih arena Anda. Dari meja reguler yang kompetitif hingga suite VIP
            eksklusif, kami menyediakan pengalaman billiard terbaik di Tegal.
        </p>
    </section>

    {{-- ═══════════════════════════════ PRICING CARDS ═══════════════════════════════ --}}
    <section class="px-6 pb-16 max-w-[85rem] mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tables->take(4) as $table)
                <div class="bg-dark-card border border-white/5 rounded-2xl overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-2 hover:border-primary/15">
                    <div class="h-45 relative overflow-hidden">
                        <span class="absolute top-4 right-4 bg-primary text-black px-3.5 py-1.5 rounded-lg text-xs font-black shadow-neon z-10">
                            Rp {{ number_format($table->price_per_hour, 0, ',', '.') }} / JAM
                        </span>
                        <img src="{{ $table->image ? asset('storage/' . $table->image) : asset('images/hero-bg.png') }}"
                            alt="{{ $table->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 flex flex-col gap-3.5 flex-1">
                        <div class="flex justify-center items-center">
                            <h3 class="text-xl font-black text-white">{{ strtoupper($table->name) }}</h3>
                        </div>
                        <div class="flex items-center justify-center gap-1.5 text-xs font-bold">
                            <span class="text-white/35 uppercase">{{ strtoupper($table->type) }}</span>
                            <span class="text-white/10">|</span>
                            <span class="text-primary">{{ $table->capacity }} orang</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('login') }}" class="block w-full text-center bg-primary/5 border-2 border-primary/15 text-primary px-3 py-3 rounded-xl text-xs font-extrabold transition-all hover:bg-primary hover:text-black hover:shadow-neon">
                                BOOK NOW
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════ INFO SECTION ═══════════════════════════════ --}}
    <section class="py-12 pb-20 px-6 max-w-[85rem] mx-auto border-t border-white/5">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="flex gap-4 items-start">
                <div class="flex items-center justify-center w-10 h-10 min-w-10 bg-primary/10 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#00e5ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="16" x2="12" y2="12" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm mb-1.5">Kebijakan Pemesanan</h4>
                    <p class="text-white/45 text-xs leading-relaxed">Pemesanan ditahan selama 15 menit setelah waktu yang dijadwalkan. Pembatalan harus dilakukan setidaknya 2 jam sebelumnya.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start">
                <div class="flex items-center justify-center w-10 h-10 min-w-10 bg-primary/10 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#00e5ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm mb-1.5">Ukuran Grup</h4>
                    <p class="text-white/45 text-xs leading-relaxed">Meja reguler untuk maksimal 4 orang. Suite VIP dapat menampung hingga 10 tamu dengan nyaman.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start">
                <div class="flex items-center justify-center w-10 h-10 min-w-10 bg-primary/10 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="#00e5ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                        <line x1="6" y1="1" x2="6" y2="4" />
                        <line x1="10" y1="1" x2="10" y2="4" />
                        <line x1="14" y1="1" x2="14" y2="4" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm mb-1.5">Makanan dari Luar</h4>
                    <p class="text-white/45 text-xs leading-relaxed">Makanan dan minuman dari luar sangat dilarang. Silakan tanyakan pilihan menu yang tersedia kepada staff kami.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
