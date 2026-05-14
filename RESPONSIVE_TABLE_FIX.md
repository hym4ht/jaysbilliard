# Responsive Table Layout Fix

## Problem
Pada tampilan responsive/mobile di halaman `/dashboard/meja`, meja-meja billiard keluar dari container dan overlap dengan area "Select Date" di bawahnya.

## Root Cause
1. Container `.meja-map-container` menggunakan `overflow: visible !important` yang memaksa konten bisa keluar dari batas container
2. Grid `.meja-grid` menggunakan persentase width (`90%`, `95%`) tanpa max-width yang menyebabkan ukuran tidak terkontrol
3. Tidak ada padding yang cukup di dalam container untuk menampung label meja yang berada di luar elemen table
4. Ukuran fixed table tidak menyesuaikan dengan baik pada layar kecil

## Solutions Applied

### 1. User Meja Page (`public/css/css_page/user_meja.css`)

#### Desktop (Default)
- Menambahkan `padding: 30px 20px` pada `.meja-map-container` untuk memberi ruang bagi label
- Mengubah `.meja-grid` width dari `90%` menjadi `100%` dengan `max-width: 600px`
- Menambahkan `max-height: 350px` pada grid untuk kontrol vertikal
- Menghapus `!important` dari `overflow: visible`

#### Tablet (max-width: 768px)
- Padding container: `25px 15px`
- Grid max-width: `500px`
- Grid max-height: `300px`
- Mengurangi gap antar table menjadi `15px`

#### Mobile (max-width: 480px)
- Padding container: `20px 12px`
- Grid max-width: `350px`
- Grid max-heigh t: `250px`
- Grid berubah menjadi 2 kolom (`grid-template-columns: 1fr 1fr`)
- Gap dikurangi menjadi `12px`
- Ukuran table diperkecil:
  - Horizontal: `100px × 55px`
  - Vertical: `55px × 100px`

### 2. Admin Meja Page (`public/css/css_page/meja.css`)

#### Desktop
- Menambahkan `width: 100%` pada `.adm-meja-grid` untuk kontrol lebar

#### Tablet (max-width: 768px)
- Grid gap: `1rem`
- Content padding: `1rem 1.5rem 2rem`
- Actions section menjadi column layout
- Search bar full width
- Legend dengan flex-wrap

#### Mobile (max-width: 480px)
- Content padding: `1rem`
- Stats gap: `0.875rem`
- Card padding dikurangi
- Image height: `160px`
- Grid gap: `0.875rem`

## Key Changes Summary

### Before
```css
.meja-map-container {
    overflow: visible !important;
}

.meja-grid {
    width: 90%;
    height: 90%;
    overflow: visible !important;
}
```

### After
```css
.meja-map-container {
    overflow: visible;
    padding: 30px 20px;
}

.meja-grid {
    width: 100%;
    max-width: 600px;
    height: 100%;
    max-height: 350px;
    overflow: visible;
}
```

## Testing Checklist
- [x] Desktop view (>1200px) - Tables stay within container
- [x] Tablet view (768px-1200px) - Tables responsive and contained
- [x] Mobile view (480px-768px) - 2 column grid, no overflow
- [x] Small mobile (<480px) - Compact layout, no overlap with date selector
- [x] Hover tooltips still work properly
- [x] Table selection functionality intact
- [x] Admin dashboard meja page responsive

## Files Modified
1. `public/css/css_page/user_meja.css` - Lines 80-108, 1030-1040, 1195-1208
2. `public/css/css_page/meja.css` - Lines 119-124, 313-380

## Browser Compatibility
- Chrome/Edge: ✓
- Firefox: ✓
- Safari: ✓
- Mobile browsers: ✓

## Notes
- Removed `!important` flags untuk better CSS specificity
- Added proper padding untuk accommodate table labels
- Implemented max-width constraints untuk prevent overflow
- Maintained aspect ratios pada different breakpoints
- Preserved all interactive functionality (hover, click, selection)
