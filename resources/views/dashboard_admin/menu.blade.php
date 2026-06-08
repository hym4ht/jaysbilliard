<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manajemen Menu — Jay's Billiard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/css_layout/app_admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/menu.css') }}">
</head>
<body>
    <div class="adm-layout">
        @include('component.c_dashboard.sidebar.sidebar_admin')

        <main class="adm-main">
            {{-- Top Bar --}}
            @include('component.c_dashboard.topbar.topbar', [
                'topbar_title' => 'Makanan & Minuman',
                'topbar_sub' => "Kelola kebutuhan amunisi pemain billiar"
            ])
            <div class="adm-content adm-menu-content">

                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="adm-alert adm-alert--success">
                        <div class="adm-alert-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                        </div>
                        <div class="adm-alert-content">
                            <span class="adm-alert-title">Berhasil!</span>
                            <span class="adm-alert-msg">{{ session('success') }}</span>
                        </div>
                        <button class="adm-alert-close" onclick="this.parentElement.style.display='none'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                {{-- ═══════ TOP STATS ═══════ --}}
                <div class="adm-menu-stats">
                    <div class="adm-mstat-card">
                        <div class="adm-mstat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        </div>
                        <div class="adm-mstat-info">
                            <span class="adm-mstat-label">TOTAL MENU</span>
                            <span class="adm-mstat-value">{{ count($menus ?? []) }} Menu</span>
                        </div>
                    </div>
                    <div class="adm-mstat-card">
                        <div class="adm-mstat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>
                        </div>
                        <div class="adm-mstat-info">
                            <span class="adm-mstat-label">TOTAL MAKANAN</span>
                            <span class="adm-mstat-value">{{ ($menus ?? collect())->filter(fn($m) => $m && in_array(strtolower($m->category ?? ''), ['hidangan utama', 'camilan', 'main', 'snack']))->count() }} Menu</span>
                        </div>
                    </div>
                    <div class="adm-mstat-card">
                        <div class="adm-mstat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"></path><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path><line x1="6" y1="2" x2="6" y2="4"></line><line x1="10" y1="2" x2="10" y2="4"></line><line x1="14" y1="2" x2="14" y2="4"></line></svg>
                        </div>
                        <div class="adm-mstat-info">
                            <span class="adm-mstat-label">TOTAL MINUMAN</span>
                            <span class="adm-mstat-value">{{ ($menus ?? collect())->filter(fn($m) => $m && in_array(strtolower($m->category ?? ''), ['kopi', 'minuman', 'drink', 'mocktail', 'jus', 'teh']))->count() }} Menu</span>
                        </div>
                    </div>
                </div>

                {{-- ═══════ SEARCH & FILTERS ═══════ --}}
                <div class="adm-menu-actions">
                    <div class="adm-search-wrap">
                        <svg class="adm-search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" class="adm-search-input" placeholder="Cari Menu atau Kategori...">
                    </div>

                    <div class="adm-menu-legend">
                        <div class="legend-item"><span class="legend-dot tersedia"></span> TERSEDIA</div>
                        <div class="legend-item"><span class="legend-dot kosong"></span> KOSONG</div>
                    </div>
                </div>

                {{-- ═══════ MENU GRID ═══════ --}}
                <div class="adm-menu-grid">
                    @forelse($menus ?? [] as $menu)
                        <div class="adm-menu-card {{ $menu->status }}">
                            <div class="card-image-wrap">
                                <span class="price-badge">Rp {{ number_format($menu->price ?? 0, 0, ',', '.') }}</span>
                                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://images.unsplash.com/photo-1544145945-f904253db0ad?auto=format&fit=crop&q=80&w=400' }}" alt="{{ $menu->name }}" class="card-image">
                            </div>
                            <div class="card-body">
                                <div class="card-header-row">
                                    <h3 class="card-title">{{ $menu->name }}</h3>
                                    <div class="card-status {{ $menu->status }}">
                                        <span class="status-dot-sm"></span> {{ strtoupper($menu->status === 'available' ? 'Tersedia' : 'Kosong') }}
                                     </div>
                                </div>
                                <div class="card-details">
                                    <span>{{ strtoupper($menu->category) }}</span>
                                </div>

                                <p class="card-desc">{{ $menu->description ?: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn-edit-menu">EDIT MENU</a>
                                <button type="button" class="btn-delete-menu" onclick="confirmDelete('{{ $menu->id }}', '{{ addslashes($menu->name) }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    {{-- Tambah Menu Baru Card --}}
                    <a href="{{ route('admin.menu.create') }}" class="adm-menu-add-card">
                        <div class="add-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </div>
                        <span class="add-text">Tambah Menu Baru</span>
                    </a>
                </div>

                {{-- ═══════════════════════════════ DELETE CONFIRMATION MODAL ═══════════════════════════════ --}}
                <div class="adm-delete-modal-overlay" id="deleteModal" style="z-index: 9999;">
                    <div class="adm-delete-modal-content">
                        <div class="modal-header">
                            <div class="modal-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <h3 class="modal-title">Hapus Menu</h3>
                        </div>
                        <div class="modal-msg">
                            Apakah Anda yakin ingin menghapus menu <strong id="deleteMenuName">Menu Name</strong>? 
                            jika anda mengahpusnya nanti data menu ini di halaman user tidak ada lagi , sampai anda inputkan menunya lagi
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">BATAL</button>
                            <form id="deleteConfirmForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-modal-confirm">HAPUS</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        {{-- Pencarian Menu --}}
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.adm-search-input');
            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const cards = document.querySelectorAll('.adm-menu-card');
                    
                    cards.forEach(card => {
                        const menuName = card.querySelector('.card-title').textContent.toLowerCase();
                        const menuCat = card.querySelector('.card-details').textContent.toLowerCase();
                        if (menuName.includes(searchTerm) || menuCat.includes(searchTerm)) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
        });

        {{-- Fungsi Modal Hapus --}}
        function confirmDelete(id, name) {
            document.getElementById('deleteMenuName').innerText = name;
            const form = document.getElementById('deleteConfirmForm');
            form.action = `/admin/menu/${id}`; {{-- Sesuaikan endpoint --}}
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
        }

        {{-- Close modal on outside click --}}
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    </script>

    {{-- Logout Modal --}}
    @include('component.c_dashboard.modal.logout_modal')
    <script src="{{ asset('js/js_component/logout.js') }}"></script>
</body>
</html>


