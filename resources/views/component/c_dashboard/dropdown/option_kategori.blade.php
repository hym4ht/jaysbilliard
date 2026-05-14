{{-- option_kategori.blade.php — Category Selector Component --}}
@php
    $selectedValue = $value ?? $placeholder ?? 'Hidangan Utama';
    $categories = ['Hidangan Utama', 'Camilan', 'Minuman', 'Kopi'];
@endphp

<div class="adm-kategori-dropdown-wrap" id="{{ $id ?? 'categoryDropdown' }}">
    {{-- Display value --}}
    <div class="adm-kategori-select-box" id="dropdownDisplay">
        <span class="selected-text">{{ $selectedValue }}</span>
        <svg class="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </div>

    {{-- Hidden input for form submission --}}
    <input type="hidden" name="{{ $name ?? 'category' }}" id="dropdownInput" value="{{ $selectedValue }}">

    {{-- Dropdown list --}}
    <div class="adm-kategori-dropdown-list" id="dropdownList">
        @foreach ($categories as $category)
            <div class="adm-kategori-item {{ $selectedValue === $category ? 'active' : '' }}" data-value="{{ $category }}">{{ $category }}</div>
        @endforeach
    </div>
</div>
