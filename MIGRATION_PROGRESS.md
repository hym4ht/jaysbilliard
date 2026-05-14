# Tailwind v3 Migration - Progress & Templates

## ✅ Completed (9/40+ files)

### Website Pages (4/4) ✅
- ✅ [`home.blade.php`](resources/views/website/home.blade.php:1)
- ✅ [`tarif.blade.php`](resources/views/website/tarif.blade.php:1)
- ✅ [`f&b.blade.php`](resources/views/website/f&b.blade.php:1)
- ✅ [`lokasi.blade.php`](resources/views/website/lokasi.blade.php:1)

### Authentication (1/3)
- ✅ [`login.blade.php`](resources/views/autentikasi/login.blade.php:1)
- ⏳ `login_admin.blade.php`
- ⏳ `register.blade.php`

### Components (2/10+)
- ✅ [`feature-card.blade.php`](resources/views/component/c_website/feature-card.blade.php:1)
- ✅ [`promo-card.blade.php`](resources/views/component/c_website/promo-card.blade.php:1)

## 🚀 Quick Start

```bash
npm run dev
```

## 📋 Remaining Files (30+)

### Priority 1: Auth Pages (2 files)
- `resources/views/autentikasi/login_admin.blade.php`
- `resources/views/autentikasi/register.blade.php`

### Priority 2: Layouts (2 files)
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/dashboard.blade.php`

### Priority 3: User Dashboard (5 files)
- `resources/views/dashboard_user/dashboard.blade.php`
- `resources/views/dashboard_user/meja.blade.php`
- `resources/views/dashboard_user/fnb.blade.php`
- `resources/views/dashboard_user/fnb_konfirmasi.blade.php`
- `resources/views/dashboard_user/konfirmasi_pembayaran.blade.php`

### Priority 4: Admin Dashboard (6 files)
- `resources/views/dashboard_admin/dashboard.blade.php`
- `resources/views/dashboard_admin/meja.blade.php`
- `resources/views/dashboard_admin/menu.blade.php`
- `resources/views/dashboard_admin/pemesanan.blade.php`
- `resources/views/dashboard_admin/transaksi.blade.php`
- `resources/views/dashboard_admin/profile_settings.blade.php`

### Priority 5: Components (15+ files)
- `resources/views/component/c_website/navbar.blade.php`
- `resources/views/component/c_website/footer.blade.php`
- `resources/views/component/c_dashboard/sidebar/sidebar_admin.blade.php`
- `resources/views/component/c_dashboard/sidebar/sidebar_user.blade.php`
- `resources/views/component/c_dashboard/topbar/topbar.blade.php`
- All dropdown & modal components

## 🎨 Templates for Remaining Files

### Template 1: Auth Page (register.blade.php, login_admin.blade.php)
```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title — Jay's Billiard</title>
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

        {{-- Card --}}
        <div class="relative z-10 w-full max-w-md bg-[#0f141e]/85 backdrop-blur-custom border border-primary/15 rounded-2xl p-10 md:p-8 sm:p-7 shadow-2xl">
            {{-- Back Button --}}
            <a href="{{ url('/') }}" class="flex items-center justify-center w-10 h-10 bg-white/5 border border-primary/20 rounded-lg text-white/40 mb-6 transition-all hover:bg-primary/10 hover:text-primary hover:border-primary/40 hover:-translate-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>

            {{-- Title --}}
            <h1 class="text-3xl md:text-2xl font-extrabold text-white mb-2">Title</h1>
            <p class="text-white/50 text-sm leading-relaxed mb-8">Subtitle text here</p>

            {{-- Form --}}
            <form action="#" method="POST" class="flex flex-col gap-5">
                @csrf
                
                {{-- Input Field --}}
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-white">Label</label>
                    <div class="flex items-center bg-white/5 border border-primary/20 rounded-xl px-4 transition-all focus-within:border-primary focus-within:shadow-neon-sm">
                        <span class="flex items-center text-white/35 mr-3">
                            {{-- Icon SVG --}}
                        </span>
                        <input type="text" name="field" class="flex-1 bg-transparent border-none outline-none text-white text-sm py-3.5 placeholder:text-white/30" placeholder="Placeholder" required>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-primary to-primary-dark text-black font-bold text-base px-4 py-3.5 rounded-xl border-none cursor-pointer transition-all hover:shadow-neon hover:-translate-y-0.5">
                    Button Text
                </button>
            </form>

            {{-- Footer Link --}}
            <p class="text-center text-sm text-white/50 mt-6">
                Text <a href="#" class="text-primary font-semibold transition-opacity hover:opacity-80">Link</a>
            </p>
        </div>
    </div>
</body>
</html>
```

### Template 2: Dashboard Page
```blade
@extends('layouts.dashboard')
@section('title', 'Page Title')

@section('content')
<div class="p-10 md:p-6 sm:p-4">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white mb-2">Page Title</h1>
        <p class="text-white/40 text-sm">Page description</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-dark-card border border-white/5 rounded-2xl p-7 transition-all hover:border-primary/30">
            <div class="flex items-center justify-between mb-7">
                <div class="w-11 h-11 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    {{-- Icon --}}
                </div>
                <span class="text-xs font-bold px-3.5 py-1.5 rounded-full bg-primary/15 text-primary border border-primary/15">
                    +12%
                </span>
            </div>
            <span class="block text-[0.72rem] font-bold text-white/30 uppercase tracking-wider mb-2">Label</span>
            <span class="block text-3xl font-extrabold text-white">123</span>
        </div>
    </div>

    {{-- Card --}}
    <div class="bg-dark-card border border-white/5 rounded-2xl p-7">
        <h2 class="text-xl font-extrabold text-white mb-6">Section Title</h2>
        {{-- Content --}}
    </div>
</div>
@endsection
```

### Template 3: Component (Sidebar, Navbar, etc)
```blade
<div class="bg-dark-lighter border-r border-white/5 flex flex-col">
    {{-- Brand --}}
    <div class="flex items-center gap-2.5 px-4 py-5 relative">
        <div class="w-10 h-10 bg-primary/10 border border-primary/20 rounded-xl flex items-center justify-center">
            {{-- Logo --}}
        </div>
        <div class="flex flex-col">
            <span class="text-base font-bold text-white leading-tight">Jay's Billiard</span>
            <span class="text-[0.58rem] font-bold tracking-widest text-primary mt-0.5">DASHBOARD</span>
        </div>
    </div>

    {{-- Nav Links --}}
    <nav class="flex flex-col gap-0.5 px-2.5">
        <a href="#" class="flex items-center gap-2.5 text-white/40 text-sm font-medium px-3.5 py-3 rounded-lg transition-all hover:text-white/75 hover:bg-white/5 active:text-white active:bg-primary/12 active:border-l-4 active:border-primary active:pl-3 active:font-bold">
            {{-- Icon --}}
            <span>Link Text</span>
        </a>
    </nav>
</div>
```

## 🔄 Step-by-Step Process

### For Each File:

1. **Open the file**
2. **Remove old CSS**:
   ```blade
   {{-- DELETE THIS --}}
   @push('styles')
   <link rel="stylesheet" href="{{ asset('css/...') }}">
   @endpush
   ```

3. **Replace classes**:
   - Use templates above as reference
   - Copy patterns from completed files
   - Test responsive (mobile, tablet, desktop)

4. **Common replacements**:
   ```blade
   {{-- Old → New --}}
   class="section-wrapper" → class="py-24 px-6 max-w-7xl mx-auto"
   class="main-title" → class="text-5xl md:text-7xl font-black uppercase text-white"
   class="btn-primary" → class="bg-primary text-black font-bold px-8 py-3.5 rounded-lg hover:shadow-neon"
   class="card" → class="bg-dark-card border border-white/5 rounded-2xl p-7"
   ```

## 💡 Quick Tips

### Colors
```blade
bg-primary          → #00e5ff (cyan)
bg-dark             → #080a0f
bg-dark-card        → #111418
text-neon-yellow    → #ffab00
text-neon-red       → #ff5252
```

### Spacing
```blade
p-4   → 1rem (16px)
p-6   → 1.5rem (24px)
p-8   → 2rem (32px)
p-10  → 2.5rem (40px)
```

### Responsive
```blade
{{-- Mobile first --}}
text-sm md:text-base lg:text-lg
grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
p-4 md:p-6 lg:p-8
```

### Effects
```blade
hover:shadow-neon           → Neon glow on hover
hover:-translate-y-1        → Lift on hover
transition-all              → Smooth transitions
backdrop-blur-sm            → Blur effect
```

## 📚 Resources

- **Completed Files**: Check home.blade.php, tarif.blade.php, f&b.blade.php, lokasi.blade.php, login.blade.php
- **Tailwind Docs**: https://v3.tailwindcss.com/
- **Custom Config**: [`tailwind.config.js`](tailwind.config.js:1)
- **Custom Utilities**: [`resources/css/app.css`](resources/css/app.css:1)

## 🎯 Estimated Time

- **Auth pages**: 30 min (2 files)
- **Layouts**: 1 hour (2 files)
- **User Dashboard**: 2 hours (5 files)
- **Admin Dashboard**: 2.5 hours (6 files)
- **Components**: 2 hours (15+ files)

**Total**: ~8 hours

## ✅ Testing Checklist

After refactoring each file:
- [ ] Page loads without errors
- [ ] All elements visible
- [ ] Responsive on mobile (375px)
- [ ] Responsive on tablet (768px)
- [ ] Responsive on desktop (1920px)
- [ ] Hover states work
- [ ] Forms submit correctly
- [ ] Links navigate correctly

---

**Current Progress**: 9/40+ files (22%)
**Next Priority**: Auth pages (register, login_admin)
