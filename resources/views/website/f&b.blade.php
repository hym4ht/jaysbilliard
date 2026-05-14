@extends('layouts.app')
@section('title', 'F&B — Jay\'s Billiard')

@section('content')
    {{-- ═══════════════════════════════ HEADER ═══════════════════════════════ --}}
    <section class="text-center pt-32 pb-8 px-6 max-w-2xl mx-auto">
        <h1 class="text-5xl md:text-6xl font-black text-white mb-4 italic">Menu Menu Kami</h1>
        <p class="text-white/50 text-base leading-relaxed">
            Tingkatkan permainan Anda dengan pilihan camilan gourmet,
            kopi artisan, dan minuman khas yang dirancang untuk pecinta malam.
        </p>
    </section>

    {{-- ═══════════════════════════════ CATEGORY TABS ═══════════════════════════════ --}}
    <section class="max-w-6xl mx-auto px-6 pb-16">
        <div class="flex justify-center flex-wrap gap-2 mb-12">
            <button class="fnb-tab bg-primary text-black border-primary px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all hover:bg-primary-dark" data-category="all">Semua Koleksi</button>
            <button class="fnb-tab bg-transparent text-white/60 border border-white/10 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all hover:border-primary/40 hover:text-white" data-category="camilan">Camilan</button>
            <button class="fnb-tab bg-transparent text-white/60 border border-white/10 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all hover:border-primary/40 hover:text-white" data-category="minuman">Minuman</button>
        </div>

        {{-- ═══════════════════════════════ MENU GRID ═══════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="fnb-grid">

            {{-- Chitato Party --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="camilan">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Chitato Party.jpg') }}" alt="Chitato Party" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Chitato Party</h3>
                        <span class="text-primary font-extrabold text-sm">35k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Paket porsi besar Chitato dengan rasa sapi panggang otentik, cocok untuk dinikmati bersama geng billiard Anda.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Camilan</span>
                    </div>
                </div>
            </div>

            {{-- Chiki Balls --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="camilan">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Chiki Balls.jpg') }}" alt="Chiki Balls" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Chiki Balls</h3>
                        <span class="text-primary font-extrabold text-sm">15k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Bola-bola jagung renyah dengan rasa keju yang legendaris, camilan ringan yang tidak pernah membosankan.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Camilan</span>
                    </div>
                </div>
            </div>

            {{-- Chitato Lite --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="camilan">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Chitato Lite.jpg') }}" alt="Chitato Lite" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Chitato Lite</h3>
                        <span class="text-primary font-extrabold text-sm">25k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Keripik kentang tipis yang sangat renyah dengan rasa rumput laut pilihan yang ringan di lidah.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Camilan</span>
                    </div>
                </div>
            </div>

            {{-- Beng Beng --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="camilan">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Beng Beng Jajan.jpg') }}" alt="Beng Beng" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Beng Beng</h3>
                        <span class="text-primary font-extrabold text-sm">25k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Beng Beng Jajan yang sangat renyah dengan rasa rumput laut pilihan yang ringan di lidah.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Camilan</span>
                    </div>
                </div>
            </div>

            {{-- Taro --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="camilan">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Taro.jpg') }}" alt="Taro" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Taro</h3>
                        <span class="text-primary font-extrabold text-sm">25k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Taro yang sangat renyah dengan rasa rumput laut pilihan yang ringan di lidah.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Camilan</span>
                    </div>
                </div>
            </div>

            {{-- Pocky --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="camilan">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Pocky.jpg') }}" alt="Pocky" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Pocky</h3>
                        <span class="text-primary font-extrabold text-sm">25k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Pocky yang sangat manis dengan rasa stawbery pilihan yang ringan di lidah.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Camilan</span>
                    </div>
                </div>
            </div>

            {{-- Nescafe Latte --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="minuman">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Nescafe Latte.jpg') }}" alt="Nescafe Latte" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Nescafe Latte</h3>
                        <span class="text-primary font-extrabold text-sm">12k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Kesegaran kopi latte pilihan dengan perasan kopi asli yang memberikan sensasi melek dan segar.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Minuman</span>
                    </div>
                </div>
            </div>

            {{-- Good day --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="minuman">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Good day.jpg') }}" alt="Good day" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Good day</h3>
                        <span class="text-primary font-extrabold text-sm">22k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Good day premium yang dipadukan dengan susu segar yang creamy dan lembut.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Minuman</span>
                    </div>
                </div>
            </div>

            {{-- Le Minerale --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="minuman">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Le Minerale.jpg') }}" alt="Le Minerale" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Mineral Water</h3>
                        <span class="text-primary font-extrabold text-sm">15k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Le Minerale dengan , dibuat dari biji kopi pilihan yang.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Minuman</span>
                    </div>
                </div>
            </div>

            {{-- Teh Kotak --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="minuman">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Teh Kotak.jpg') }}" alt="Teh Kotak" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Teh Kotak</h3>
                        <span class="text-primary font-extrabold text-sm">25k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Perpaduan Teh yang menyegarkan tanpa alkohol.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Minuman</span>
                    </div>
                </div>
            </div>

            {{-- Soft Drink --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="minuman">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/cola.jpg') }}" alt="Coca Cola" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Coca Cola / Sprite</h3>
                        <span class="text-primary font-extrabold text-sm">10k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Minuman bersoda dingin yang sangat pas menemani waktu santai Anda saat bermain billiard.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Minuman</span>
                    </div>
                </div>
            </div>

            {{-- Pocari Sweat --}}
            <div class="fnb-card bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/10 rounded-2xl overflow-hidden transition-all hover:border-primary/35 hover:-translate-y-1 flex flex-col" data-tags="minuman">
                <div class="p-4 pt-4">
                    <img src="{{ asset('images/fnb/Pocari Sweat.jpg') }}" alt="Pocari Sweat" class="w-full h-40 object-cover rounded-xl" />
                </div>
                <div class="px-5 pb-5 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-bold text-base">Pocari Sweat</h3>
                        <span class="text-primary font-extrabold text-sm">5k</span>
                    </div>
                    <p class="text-white/40 text-xs leading-relaxed mb-3 flex-1">Pocari Sweat dalam kemasan botol dingin yang jernih dan menyegarkan dahaga.</p>
                    <div class="flex flex-wrap gap-1.5 mt-auto">
                        <span class="inline-flex items-center text-[0.6rem] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/25 rounded-full px-2.5 py-1">Minuman</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════ CTA SECTION ═══════════════════════════════ --}}
    <section class="max-w-6xl mx-auto px-6 pb-20">
        <div class="bg-gradient-to-b from-[#0f1923] to-[#111118] border border-primary/12 rounded-2xl p-10">
            <h2 class="text-3xl font-black text-white uppercase mb-4"><span class="italic">BILLIARD</span> SAMBIL MAKAN</h2>
            <p class="text-white/45 text-sm leading-relaxed max-w-2xl mb-8">
                Perpaduan sempurna antara gaming santai, meja yang enak dimainkan, dan
                pelayanan yang ramah bikin setiap momen jadi lebih asik. Mau datang bareng
                teman, pasangan, atau satu geng besar? Semua tetap terasa nyaman dan
                menyenangkan.
            </p>
            <div class="flex flex-wrap items-center gap-8 pt-6 border-t border-white/[0.06]">
                <div>
                    <span class="text-primary text-[0.65rem] font-bold uppercase tracking-widest block mb-1">JAM DAPUR</span>
                    <p class="text-white text-base font-bold">Setiap Hari 10:00 — 02:00</p>
                </div>
                <div>
                    <span class="text-primary text-[0.65rem] font-bold uppercase tracking-widest block mb-1">KONTAK CEPAT</span>
                    <p class="text-white text-base font-bold">+62 283 1234 567</p>
                </div>
                <a href="{{ route('login') }}" class="ml-auto inline-flex items-center gap-2 bg-primary text-black font-bold text-sm uppercase tracking-wider px-8 py-3.5 rounded-lg transition-all hover:bg-primary-dark hover:shadow-neon">
                    BOOK MAKANAN →
                </a>
            </div>
        </div>
    </section>

    {{-- JavaScript for category filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.fnb-tab');
            const cards = document.querySelectorAll('.fnb-card');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    // Update active state
                    tabs.forEach(t => {
                        t.classList.remove('bg-primary', 'text-black', 'border-primary');
                        t.classList.add('bg-transparent', 'text-white/60', 'border-white/10');
                    });
                    this.classList.remove('bg-transparent', 'text-white/60', 'border-white/10');
                    this.classList.add('bg-primary', 'text-black', 'border-primary');

                    const category = this.dataset.category;

                    cards.forEach(card => {
                        if (category === 'all') {
                            card.style.display = '';
                        } else {
                            const tags = card.dataset.tags || '';
                            card.style.display = tags.includes(category) ? '' : 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection
