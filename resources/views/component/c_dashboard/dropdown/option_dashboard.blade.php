{{-- ═══════════════════════════════ CHART FILTER DROPDOWN ═══════════════════════════════ --}}
@php
    $period = $period ?? request('period', 'today');
    $periodOptions = $periodOptions ?? [
        'today' => 'HARI INI',
        'month' => 'BULAN INI',
        'year' => 'TAHUN INI',
    ];
@endphp

<div class="adm-chart-filter-wrap">
    <div class="adm-chart-filter" id="chartFilterBtn">
        <span>{{ $periodOptions[$period] ?? 'HARI INI' }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </div>
    <div class="adm-chart-dropdown" id="chartFilterDropdown">
        @foreach($periodOptions as $value => $label)
            <div
                class="adm-chart-dropdown-item {{ $period === $value ? 'active' : '' }}"
                data-url="{{ route('admin.dashboard', ['period' => $value]) }}"
            >{{ $label }}</div>
        @endforeach
    </div>
</div>
