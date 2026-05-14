<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manajemen Meja — Jay's Billiard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/css_layout/app_admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/meja.css') }}">
</head>
<body>
    <div class="adm-layout">
        @include('component.c_dashboard.sidebar.sidebar_admin')

        <main class="adm-main">
            {{-- Top Bar --}}
            @include('component.c_dashboard.topbar.topbar', [
                'topbar_title' => 'Meja',
                'topbar_sub' => "Kelola kebutuhan operasional jay's billiard"
            ])

            <div class="adm-content adm-meja-content">

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
                <div class="adm-meja-stats">
                    <div class="adm-mstat-card">
                        <div class="adm-mstat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="4" rx="1"/><path d="M4 11v7"/><path d="M20 11v7"/></svg>
                        </div>
                        <div class="adm-mstat-info">
                            <span class="adm-mstat-label">TOTAL MEJA</span>
                            <span class="adm-mstat-value">{{ count($tables ?? []) }} Meja</span>
                        </div>
                    </div>
                    <div class="adm-mstat-card">
                        <div class="adm-mstat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <div class="adm-mstat-info">
                            <span class="adm-mstat-label">TOTAL MEJA AKTIF</span>
                            <span class="adm-mstat-value">{{ ($tables ?? collect())->where('status', 'active')->count() }} Meja</span>
                        </div>
                    </div>
                    <div class="adm-mstat-card">
                        <div class="adm-mstat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/></svg>
                        </div>
                        <div class="adm-mstat-info">
                            <span class="adm-mstat-label">TOTAL MEJA MAINTENANCE</span>
                            <span class="adm-mstat-value">{{ ($tables ?? collect())->where('status', 'maintenance')->count() }} Meja</span>
                        </div>
                    </div>
                </div>

                {{-- ═══════ SEARCH & LEGEND ═══════ --}}
                <div class="adm-meja-actions">
                    <div class="adm-search-wrap">
                        <svg class="adm-search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" class="adm-search-input" id="tableSearch" placeholder="Cari Meja">
                    </div>
                    <div class="adm-meja-legend">
                        <div class="legend-item"><span class="legend-dot terisi"></span> TERISI</div>
                        <div class="legend-item"><span class="legend-dot dipesan"></span> DIPESAN</div>
                        <div class="legend-item"><span class="legend-dot tersedia"></span> TERSEDIA</div>
                    </div>
                </div>

                {{-- ═══════ MEJA GRID ═══════ --}}
                <div class="adm-meja-grid">
                    @forelse($tables ?? [] as $table)
                        <div class="adm-meja-card">
                            <div class="card-image-wrap">
                                <span class="price-badge">Rp {{ number_format($table->price_per_hour, 0, ',', '.') }} / JAM</span>
                                <img src="{{ $table->image ? asset('storage/' . $table->image) : asset('images/hero-bg.png') }}" alt="{{ $table->name }}" class="card-image">
                            </div>
                            <div class="card-body">
                                <div class="card-header-row">
                                    <h3 class="card-title">{{ strtoupper($table->name) }}</h3>
                                    <div class="card-status {{ $table->status }}">
                                        <span class="status-dot-sm"></span> 
                                        {{ strtoupper($table->status === 'active' ? 'Tersedia' : ($table->status === 'maintenance' ? 'Maintenance' : $table->status)) }}
                                    </div>
                                </div>
                                <div class="card-details">
                                    <span class="type-text">{{ strtoupper($table->type) }}</span>
                                    <span style="color: rgba(255,255,255,0.1);">|</span>
                                    <span class="cap-text">{{ $table->capacity }} orang</span>
                                </div>
                                <p class="card-desc">{{ $table->description ?: 'Adalah meja billiard tipe standar untuk permainan biasa.' }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin.meja.edit', $table->id) }}" class="btn-edit-meja">EDIT MEJA</a>
                                <form action="{{ route('admin.meja.destroy', $table->id) }}" method="POST" style="display: contents;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-meja" onclick="return confirm('Apakah Anda yakin ingin menghapus meja ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        {{-- No tables yet --}}
                    @endforelse

                    {{-- Tambah Meja Baru Card --}}
                    <a href="{{ route('admin.meja.create') }}" class="adm-meja-add-card">
                        <div class="add-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </div>
                        <span class="add-text">Tambah Meja Baru</span>
                    </a>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Simple and clean searching logic
        document.getElementById('tableSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.adm-meja-card');
            
            cards.forEach(card => {
                const tableName = card.querySelector('.card-title').textContent.toLowerCase();
                if (tableName.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>

    {{-- Logout Modal --}}
    @include('component.c_dashboard.modal.logout_modal')
    <script src="{{ asset('js/js_component/logout.js') }}"></script>
</body>
</html>
