# Tailwind CSS Migration Guide - Jay's Billiard

## ✅ Setup Complete

### 1. Tailwind Configuration
- **[`vite.config.js`](vite.config.js:1)** - Tailwind plugin configured
- **[`resources/css/app.css`](resources/css/app.css:1)** - Custom theme & utilities
- **[`package.json`](package.json:1)** - Tailwind v4.2.1 installed

### 2. Custom Theme Variables
```css
--color-primary: #00e5ff (Cyan Neon)
--color-dark: #080a0f
--color-card: #111418
```

### 3. Custom Utility Classes
- `.text-neon` - Cyan text with glow
- `.bg-neon` - Cyan background with shadow
- `.glow-neon` - Neon glow effect
- `.animate-pulse-custom` - Custom pulse animation

## 📝 Refactored Files

### Website Pages
- ✅ [`resources/views/website/home.blade.php`](resources/views/website/home.blade.php:1)
- ✅ [`resources/views/component/c_website/feature-card.blade.php`](resources/views/component/c_website/feature-card.blade.php:1)
- ✅ [`resources/views/component/c_website/promo-card.blade.php`](resources/views/component/c_website/promo-card.blade.php:1) (already using Tailwind)

### Remaining Files to Refactor
- ⏳ `resources/views/website/tarif.blade.php`
- ⏳ `resources/views/website/f&b.blade.php`
- ⏳ `resources/views/website/lokasi.blade.php`
- ⏳ `resources/views/autentikasi/login.blade.php`
- ⏳ `resources/views/autentikasi/register.blade.php`
- ⏳ `resources/views/dashboard_admin/*`
- ⏳ `resources/views/dashboard_user/*`
- ⏳ `resources/views/layouts/app.blade.php`
- ⏳ `resources/views/layouts/dashboard.blade.php`

## 🎨 Tailwind Class Mapping

### Layout & Spacing
| Old CSS Class | Tailwind Equivalent |
|--------------|---------------------|
| `.section-wrapper` | `py-24 px-6 max-w-7xl mx-auto` |
| `.hero-content` | `relative z-10 max-w-3xl mx-auto px-6` |
| `.container` | `max-w-7xl mx-auto px-6` |

### Typography
| Old CSS Class | Tailwind Equivalent |
|--------------|---------------------|
| `.main-title` | `text-5xl md:text-7xl font-black uppercase` |
| `.section-title` | `text-4xl font-bold text-white` |
| `.section-subtitle-neon` | `text-cyan-400 text-xs font-bold uppercase tracking-widest` |

### Buttons
| Old CSS Class | Tailwind Equivalent |
|--------------|---------------------|
| `.home-btn-primary` | `bg-cyan-400 text-black font-bold px-8 py-3.5 rounded-lg hover:shadow-neon` |
| `.home-btn-secondary` | `bg-transparent text-white border-2 border-white/30 px-8 py-3.5 rounded-lg` |

### Cards
| Old CSS Class | Tailwind Equivalent |
|--------------|---------------------|
| `.feature-card` | `bg-[#141419] border border-white/[0.06] rounded-2xl p-7` |
| `.adm-card` | `bg-[#111418] border border-white/5 rounded-2xl p-7` |

### Colors
| Old CSS Variable | Tailwind Class |
|-----------------|----------------|
| `#00e5ff` | `cyan-400` or `text-neon` |
| `#080a0f` | `bg-[#080a0f]` |
| `#111418` | `bg-[#111418]` |
| `#ffab00` | `yellow-500` |
| `#ff5252` | `red-500` |

### Responsive Breakpoints
| Tailwind | Screen Size |
|----------|-------------|
| `sm:` | 640px |
| `md:` | 768px |
| `lg:` | 1024px |
| `xl:` | 1280px |
| `2xl:` | 1536px |

## 🚀 How to Continue Migration

### Step 1: Build Assets
```bash
npm run dev
# or for production
npm run build
```

### Step 2: Refactor Pattern
For each blade file:

1. **Remove old CSS link**:
```blade
{{-- Remove this --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/css_website/home.css') }}">
@endpush
```

2. **Replace custom classes with Tailwind**:
```blade
{{-- Old --}}
<div class="hero-wrapper">
    <div class="hero-content">
        <h1 class="main-title">Title</h1>
    </div>
</div>

{{-- New --}}
<div class="relative min-h-screen flex items-center justify-center">
    <div class="relative z-10 max-w-3xl mx-auto px-6">
        <h1 class="text-5xl md:text-7xl font-black uppercase">Title</h1>
    </div>
</div>
```

### Step 3: Common Patterns

#### Hero Section
```blade
<section class="relative min-h-screen flex items-center justify-center text-center overflow-hidden">
    <div class="absolute inset-0 bg-[#0d0d0d]" style="background-image: url('...')">
        <div class="absolute inset-0 bg-black/55 backdrop-blur-sm"></div>
    </div>
    <div class="relative z-10 max-w-3xl mx-auto px-6">
        <!-- Content -->
    </div>
</section>
```

#### Card with Glow
```blade
<div class="relative bg-[#141419] border border-white/[0.06] rounded-2xl p-7 overflow-hidden hover:border-cyan-400/30 transition-all">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/5 h-0.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent"></div>
    <!-- Content -->
</div>
```

#### Button Primary
```blade
<a href="#" class="inline-flex items-center gap-2 bg-cyan-400 text-black font-bold text-sm uppercase tracking-wider px-8 py-3.5 rounded-lg border-2 border-cyan-400 transition-all hover:bg-cyan-500 hover:shadow-neon hover:-translate-y-0.5">
    Button Text
</a>
```

#### Button Secondary
```blade
<a href="#" class="inline-flex items-center gap-2 bg-transparent text-white font-semibold text-sm uppercase tracking-wider px-8 py-3.5 rounded-lg border-2 border-white/30 transition-all hover:border-white/60 hover:bg-white/5">
    Button Text
</a>
```

#### Grid Layouts
```blade
{{-- 3 columns on desktop, 1 on mobile --}}
<div class="grid md:grid-cols-3 gap-6">
    <!-- Items -->
</div>

{{-- 4 columns responsive --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <!-- Items -->
</div>
```

## 📋 Checklist for Each Page

- [ ] Remove `@push('styles')` CSS link
- [ ] Replace all custom classes with Tailwind
- [ ] Test responsive breakpoints (mobile, tablet, desktop)
- [ ] Verify hover states and transitions
- [ ] Check dark mode compatibility
- [ ] Test on different browsers

## 🎯 Priority Order

### High Priority (User-facing)
1. ✅ Home page
2. ⏳ Login/Register pages
3. ⏳ Tarif (Rates) page
4. ⏳ F&B page
5. ⏳ Lokasi (Location) page

### Medium Priority (Dashboard)
6. ⏳ User Dashboard
7. ⏳ User Meja (Table booking)
8. ⏳ Admin Dashboard
9. ⏳ Admin Meja Management

### Low Priority (Components)
10. ⏳ Navbar
11. ⏳ Footer
12. ⏳ Modals
13. ⏳ Forms

## 💡 Tips

### 1. Use Arbitrary Values
For exact colors from design:
```blade
<div class="bg-[#141419] border-[#00e5ff]/20">
```

### 2. Group Hover States
```blade
<div class="group">
    <div class="group-hover:scale-105 transition">
```

### 3. Custom Animations
Already defined in `app.css`:
- `animate-fade-in`
- `animate-slide-in`
- `animate-pulse-custom`

### 4. Responsive Design
Mobile-first approach:
```blade
<div class="text-sm md:text-base lg:text-lg">
```

### 5. Dark Mode Ready
All colors use opacity for better dark mode:
```blade
<div class="bg-white/5 border-white/10">
```

## 🔧 Troubleshooting

### Issue: Styles not applying
**Solution**: Run `npm run dev` to rebuild assets

### Issue: Custom colors not working
**Solution**: Check `resources/css/app.css` for theme variables

### Issue: Responsive not working
**Solution**: Ensure viewport meta tag in layout:
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

### Issue: Hover effects not smooth
**Solution**: Add transition classes:
```blade
<div class="transition-all duration-300">
```

## 📚 Resources

- [Tailwind CSS v4 Docs](https://tailwindcss.com/docs)
- [Tailwind Play](https://play.tailwindcss.com/) - Test classes online
- [Tailwind Cheat Sheet](https://nerdcave.com/tailwind-cheat-sheet)

## 🎉 Benefits of Tailwind

✅ **Responsive by default** - Mobile-first utilities
✅ **Smaller bundle size** - Only used classes included
✅ **Faster development** - No context switching
✅ **Consistent design** - Design system built-in
✅ **Easy maintenance** - All styles in HTML
✅ **Better performance** - Optimized CSS output

## Next Steps

1. Run `npm run dev` to start development server
2. Refactor remaining pages one by one
3. Test each page after refactoring
4. Remove old CSS files after migration complete
5. Update documentation

---

**Note**: Keep `resources/css/app.css` for custom utilities and theme. Only remove page-specific CSS files after migration.
