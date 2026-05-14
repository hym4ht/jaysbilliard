{{-- ═══════════ SIDEBAR ═══════════ --}}
<aside class="adm-sidebar">
    <div class="adm-sidebar-top">
        {{-- Brand --}}
        <div class="adm-brand">
            <div class="adm-brand-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="4" />
                    <path d="M7 8h10" />
                    <path d="M7 12h10" />
                    <path d="M7 16h6" />
                </svg>
            </div>
            <div class="adm-brand-text">
                <span class="adm-brand-name">Jay's Billiard</span>
                <span class="adm-brand-sub">ADMIN DASHBOARD</span>
            </div>
            <button class="adm-collapse-btn" id="sidebarToggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="adm-nav">
            <a href="{{ url('/admin-dashboard') }}" class="adm-nav-link {{ Request::is('admin-dashboard*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.history') }}" class="adm-nav-link {{ Request::is('admin/history*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="15" y2="16"/>
                </svg>
                <span>History Pemesanan</span>
            </a>
            <a href="{{ route('admin.meja.index') }}" class="adm-nav-link {{ Request::is('admin/meja*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="4" rx="1"/>
                    <path d="M4 11v7"/>
                    <path d="M20 11v7"/>
                    <path d="M9 11v3"/>
                    <path d="M15 11v3"/>
                </svg>
                <span>Meja</span>
            </a>
            <a href="{{ route('admin.menu') }}" class="adm-nav-link {{ Request::is('admin/menu*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/>
                </svg>
                <span>Makanan dan Minuman</span>
            </a>
        </nav>
    </div>

    {{-- Logout --}}
    <div class="adm-sidebar-bottom">
        <form action="{{ route('admin.logout') }}" method="POST" id="logout-sidebar-form">
            @csrf
            <button type="button" class="adm-logout-btn adm-logout-trigger" onclick="window.confirmLogout(event, this.form)" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</aside>
<script>
    const sidebar = document.querySelector('.adm-sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    
    // Check initial state
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
    });
</script>
