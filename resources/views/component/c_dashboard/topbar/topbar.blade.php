{{-- Top Bar --}}
<header class="adm-topbar">
    <div class="adm-topbar-left">
        <button type="button" class="mobile-menu-btn" id="mobileMenuBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <div>
            <h1 class="adm-topbar-title">{{ $topbar_title ?? 'Dashboard' }}</h1>
            <p class="adm-topbar-sub">{{ $topbar_sub ?? "Kelola kebutuhan operasional jay's billiard" }}</p>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/css_page/css_interaksi component/profile_dropdown.css') }}">
    <div class="adm-topbar-right">
        @if(isset($topbar_right))
            {!! $topbar_right !!}
        @else
            <div class="adm-user-profile-wrap">
                <div class="adm-user-badge" id="profileBtn">
                    <div class="adm-user-avatar">A</div>
                    <span class="adm-user-name">{{ (Request::is('admin*') || Request::is('admin-dashboard*')) ? 'Admin' : (auth()->user()->name ?? 'User') }}</span>
                    <svg class="badge-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                @include('component.c_dashboard.dropdown.profile_acount')
            </div>
        @endif
    </div>
</header>

<script src="{{ asset('js/js_component/profile_dropdown.js') }}"></script>
<script src="{{ asset('js/js_component/mobile_menu.js') }}"></script>