# Tailwind v3 Migration - Complete Guide

## ✅ Setup Complete

### Installed & Configured
- ✅ Tailwind CSS v3.4.0
- ✅ PostCSS & Autoprefixer
- ✅ Custom theme in [`tailwind.config.js`](tailwind.config.js:1)
- ✅ Base styles in [`resources/css/app.css`](resources/css/app.css:1)

## 🎨 Custom Theme

### Colors
```js
primary: '#00e5ff'        // Neon cyan
primary-dark: '#00bcd4'   // Darker cyan
neon-yellow: '#ffab00'    // Yellow accent
neon-red: '#ff5252'       // Red accent
dark: '#080a0f'           // Main background
dark-card: '#111418'      // Card background
```

### Shadows
```js
shadow-neon: '0 0 20px rgba(0, 229, 255, 0.4)'
shadow-neon-strong: '0 0 40px rgba(0, 229, 255, 0.7)'
```

### Animations
```js
animate-fade-in
animate-slide-in
animate-pulse-custom
```

## 📋 Refactored Files

### Website Pages
- ✅ [`resources/views/website/home.blade.php`](resources/views/website/home.blade.php:1)
- ✅ [`resources/views/website/tarif.blade.php`](resources/views/website/tarif.blade.php:1)
- ⏳ `resources/views/website/f&b.blade.php`
- ⏳ `resources/views/website/lokasi.blade.php`

### Components
- ✅ [`resources/views/component/c_website/feature-card.blade.php`](resources/views/component/c_website/feature-card.blade.php:1)
- ✅ [`resources/views/component/c_website/promo-card.blade.php`](resources/views/component/c_website/promo-card.blade.php:1)

## 🚀 Quick Start

### 1. Build Assets
```bash
npm run dev
```

### 2. Test Pages
Visit:
- http://localhost/
- http://localhost/tarif

## 📖 Common Patterns

### Hero Section
```blade
<section class="relative min-h-screen flex items-center justify-center text-center overflow-hidden">
    <div class="absolute inset-0 bg-[#0d0d0d]" style="background-image: url('...')">
        <div class="absolute inset-0 bg-black/55 backdrop-blur-sm"></div>
    </div>
    <div class="relative z-10 max-w-3xl mx-auto px-6 pt-24 md:pt-0">
        <h1 class="text-5xl md:text-7xl font-black uppercase text-white">
            Title <span class="text-neon">Highlight</span>
        </h1>
    </div>
</section>
```

### Section Container
```blade
<section class="py-24 px-6 max-w-7xl mx-auto">
    <!-- Content -->
</section>
```

### Card with Hover
```blade
<div class="bg-dark-card border border-white/5 rounded-2xl p-7 transition-all hover:border-primary/30 hover:-translate-y-1">
    <!-- Content -->
</div>
```

### Button Primary
```blade
<a href="#" class="inline-flex items-center gap-2 bg-primary text-black font-bold text-sm uppercase tracking-wider px-8 py-3.5 rounded-lg border-2 border-primary transition-all hover:bg-primary-dark hover:shadow-neon hover:-translate-y-0.5">
    Button Text
</a>
```

### Button Secondary
```blade
<a href="#" class="inline-flex items-center gap-2 bg-transparent text-white font-semibold text-sm uppercase tracking-wider px-8 py-3.5 rounded-lg border-2 border-white/30 transition-all hover:border-white/60 hover:bg-white/5">
    Button Text
</a>
```

### Grid Responsive
```blade
{{-- 1 col mobile, 2 tablet, 3 desktop, 4 xl --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <!-- Items -->
</div>
```

### Icon with Background
```blade
<div class="flex items-center justify-center w-10 h-10 min-w-10 bg-primary/10 rounded-xl">
    <svg>...</svg>
</div>
```

### Badge/Tag
```blade
<span class="inline-flex items-center gap-2 text-xs bg-primary/10 text-primary border border-primary/40 rounded-full px-4 py-1.5 uppercase tracking-wider">
    <span class="w-2 h-2 rounded-full bg-primary animate-pulse-custom"></span>
    Badge Text
</span>
```

### Neon Text
```blade
<h1 class="text-neon">Glowing Text</h1>
<!-- or -->
<h1 class="text-primary" style="text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);">Glowing Text</h1>
```

## 🔄 Class Mapping Reference

### Layout
| Old CSS | Tailwind v3 |
|---------|-------------|
| `.section-wrapper` | `py-24 px-6 max-w-7xl mx-auto` |
| `.hero-wrapper` | `relative min-h-screen flex items-center justify-center` |
| `.hero-content` | `relative z-10 max-w-3xl mx-auto px-6` |
| `.hero-overlay` | `absolute inset-0 bg-black/55 backdrop-blur-sm` |

### Typography
| Old CSS | Tailwind v3 |
|---------|-------------|
| `.main-title` | `text-5xl md:text-7xl font-black uppercase` |
| `.section-title` | `text-4xl font-bold text-white` |
| `.section-subtitle-neon` | `text-primary text-xs font-bold uppercase tracking-widest` |
| `.text-cyan` | `text-primary` or `text-neon` |

### Buttons
| Old CSS | Tailwind v3 |
|---------|-------------|
| `.home-btn-primary` | `bg-primary text-black font-bold px-8 py-3.5 rounded-lg hover:shadow-neon` |
| `.home-btn-secondary` | `bg-transparent text-white border-2 border-white/30 px-8 py-3.5 rounded-lg` |
| `.btn-neon` | `bg-primary text-black font-black px-9 py-4 rounded-2xl shadow-neon` |

### Cards
| Old CSS | Tailwind v3 |
|---------|-------------|
| `.feature-card` | `bg-dark-card border border-white/[0.06] rounded-2xl p-7` |
| `.adm-card` | `bg-dark-card border border-white/5 rounded-2xl p-7` |
| `.user-card` | `bg-dark-card/50 border border-white/5 rounded-2xl p-7` |

### Colors
| Old CSS | Tailwind v3 |
|---------|-------------|
| `#00e5ff` | `primary` or `neon-cyan` |
| `#080a0f` | `dark` |
| `#111418` | `dark-card` |
| `#ffab00` | `neon-yellow` |
| `#ff5252` | `neon-red` |

### Spacing
| Old CSS | Tailwind v3 |
|---------|-------------|
| `padding: 2.5rem` | `p-10` |
| `padding: 1.5rem` | `p-6` |
| `gap: 1.5rem` | `gap-6` |
| `margin-bottom: 2rem` | `mb-8` |

### Borders
| Old CSS | Tailwind v3 |
|---------|-------------|
| `border: 1px solid rgba(255,255,255,0.05)` | `border border-white/5` |
| `border: 1px solid rgba(0,229,255,0.3)` | `border border-primary/30` |
| `border-radius: 1rem` | `rounded-2xl` |
| `border-radius: 0.5rem` | `rounded-lg` |

## 📝 Step-by-Step Refactoring

### For Each Page:

1. **Remove old CSS**
```blade
{{-- Remove this --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/...') }}">
@endpush
```

2. **Replace classes systematically**
   - Start with layout (sections, containers)
   - Then typography (headings, paragraphs)
   - Then components (cards, buttons)
   - Finally interactions (hovers, transitions)

3. **Test responsive**
   - Mobile (375px, 390px)
   - Tablet (768px, 1024px)
   - Desktop (1280px, 1920px)

4. **Verify interactions**
   - Hover states
   - Active states
   - Transitions
   - Animations

## 🎯 Priority Order

### High Priority (User-facing)
1. ✅ Home
2. ✅ Tarif
3. ⏳ F&B
4. ⏳ Lokasi
5. ⏳ Login/Register

### Medium Priority (Dashboard)
6. ⏳ User Dashboard
7. ⏳ User Meja
8. ⏳ Admin Dashboard
9. ⏳ Admin Meja

### Low Priority (Components)
10. ⏳ Navbar
11. ⏳ Footer
12. ⏳ Modals
13. ⏳ Sidebar

## 💡 Pro Tips

### 1. Use @apply for repeated patterns
In `resources/css/app.css`:
```css
@layer components {
    .card-base {
        @apply bg-dark-card border border-white/5 rounded-2xl p-7;
    }
}
```

### 2. Custom utilities for neon effects
```blade
<h1 class="text-neon">Glowing Text</h1>
```

### 3. Responsive with mobile-first
```blade
<div class="text-sm md:text-base lg:text-lg">
```

### 4. Opacity for dark mode
```blade
<div class="bg-white/5 border-white/10 text-white/80">
```

### 5. Group hover
```blade
<div class="group">
    <img class="group-hover:scale-105 transition">
</div>
```

## 🐛 Troubleshooting

### Styles not applying?
```bash
# Clear cache and rebuild
npm run build
php artisan view:clear
php artisan cache:clear
```

### Colors not working?
Check `tailwind.config.js` - custom colors must be in `theme.extend.colors`

### Responsive not working?
Ensure viewport meta tag in layout:
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

### Hover not smooth?
Add transition:
```blade
<div class="transition-all duration-300">
```

## 📚 Resources

- [Tailwind v3 Docs](https://v3.tailwindcss.com/)
- [Tailwind Play](https://play.tailwindcss.com/)
- [Tailwind Cheat Sheet](https://nerdcave.com/tailwind-cheat-sheet)

## 🎉 Benefits

✅ **Responsive by default** - Mobile-first utilities
✅ **Smaller bundle** - Only used classes
✅ **Faster development** - No context switching
✅ **Consistent design** - Design system built-in
✅ **Easy maintenance** - All styles in HTML
✅ **Better performance** - Optimized CSS

## 📦 Next Steps

1. Run `npm run dev`
2. Refactor remaining pages using patterns above
3. Test each page after refactoring
4. Remove old CSS files when done
5. Update documentation

---

**Current Progress**: 2/30+ files refactored
**Estimated Time**: 4-6 hours for all pages
**Difficulty**: Medium (repetitive but straightforward)
