<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ Request::is('admin*') ? 'Admin Dashboard' : 'Dashboard' }} — Jay's Billiard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/css_layout/app_admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/css_interaksi component/option_dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/css_interaksi component/akhiri.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/css_interaksi component/chat.css') }}">
</head>

<body>
    <div class="adm-layout">

        @if(Request::is('admin*'))
            @include('component.c_dashboard.sidebar.sidebar_admin')
        @else
            @include('component.c_dashboard.sidebar.sidebar_user')
        @endif

        {{-- ═══════════ MAIN ═══════════ --}}
        <main class="adm-main">

            {{-- Top Bar --}}
            @include('component.c_dashboard.topbar.topbar', [
                'topbar_title' => 'Dashboard',
                'topbar_sub' => "Kelola kebutuhan operasional jay's billiard"
            ])

            {{-- Content --}}
            <div class="adm-content">

                {{-- ═══════ STAT CARDS ═══════ --}}
                <div class="adm-stats">
                    {{-- PENDAPATAN HARI INI --}}
                    <div class="adm-stat-card">
                                                                      

                        <div class="adm-stat-header">
                             <div class="adm-stat-icon">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="22" height ="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/>
                                    <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/>
                                    <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"/>
                                </svg>

                                   
                                     
                                                            </div>

                        </div>
                        <span class="adm-stat-label">PENDAPATAN {{ $activePeriodLabel ?? 'HARI INI' }}</span>
                        <span class="adm-stat-value">IDR {{ number_format($pendapatanHariIni, 0, ',', '.') }}</span>
                    </div>

                    {{-- TOTAL PEMESANAN --}}
                    <div class="adm-stat-card">

                                   
                                                           <div class="adm-s
    t                                   at-header">
                             <div class="adm-stat-icon">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                    <path d="M9 2h6v4H9z"/>
                                </svg>
                                   
                                     
                                
                            </div>

                        </div>
                        <span class="adm-stat-label">TOTAL PEMESANAN</span>
                        <span class="adm-stat-value">{{ $totalPemesanan }} X</span>
                    </div>

                    {{-- TOTAL MEJA --}}
                    <div class="adm-stat-card">

                                   
                                                           <div class="adm-stat-header">
                             <div class="adm-stat-icon ">
                                <svg xmlns="http://www .w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="4" rx="1"/>
                                    <path d="M4 11v7"/>
                                    <path d="M20 11v7"/>
                                </svg>
                            </div>
                        </div>
                        <span class="adm-stat-label">TOTAL MEJA</span>
                        <span class="adm-stat-value">{{ $tables->count() }}</span>
                    </div>
                </div>

               {{-- ═══════ TREN PENDAPATAN (BAR CHART) ═══════ --}}
                <section class="adm-chart-section">
                    <div class="adm-chart-header">
                         <div class="adm-chart-titles">
                            <h2 class="adm-chart-title">Tren Pendapatan</h2>
                            <p class="adm-chart-sub">{{ $chartSubtitle ?? 'Metrik pendapatan per jam' }}</p>
                        </div>
                        @include('component.c_dashboard.dropdown.option_dashboard')
                    </div>
                    <div class="adm-chart-area">
                        <div class="adm-chart-bars">
                            @foreach($chartData as $hour => $data)
                                @php
                                    $colorClass = 'adm-bar--gray';
                                    if ($data['percentage'] >= 80) {
                                        $colorClass = 'adm-bar--cyan';
                                    } elseif ($data['percentage'] >= 60) {
                                        $colorClass = 'adm-bar--cyan-alt';
                                    } elseif ($data['percentage'] >= 40) {
                                        $colorClass = 'adm-bar--teal';
                                    }
                                @endphp
                                <div class="adm-bar-group" title="Rp {{ number_format($data['revenue'], 0, ',', '.') }}">
                                    <div class="adm-bar-v {{ $colorClass }}" style="height: {{ $data['percentage'] }}%;"></div>
                                @if($loop->iteration % 2 === 0 || ($period ?? 'today') === 'year')
                                        <span class="adm-bar-label">{{ $data['label'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- ═══════ STATUS MEJA (PREMIUM MOCKUP) ═══════════ --}}
                <section class="adm-meja-section">
                    <div class="adm-meja-header">

                                   
                                                           <div class="adm-meja-title-group">

                                                                <div class="adm-stat-icon" style="background: rgba(0, 2
                                    09, 255, 0.1); color: #00d1ff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect>
                                </svg>
                            </div>
                            <h2 class="adm-meja-title">Status Meja</h2>

                                                       </div>

                                                       <div class="adm-meja-legend">

                                                           <div class="adm-legend-item"><span class="adm-legend-dot adm-legend-dot--terisi"></span> TERISI</div>
                            <div class="adm-legend-item"><span class="adm-legend-dot adm-legend-dot--dipesan"></span> DIPESAN</div>
                            <div class="adm-legend-item"><span class="adm-legend-dot adm-legend-dot--tersedia"></span> TERSEDIA</div>
                        </div>
                    </div>

                    <div class="adm-meja-grid">
                        @foreach($tables as $table)
                            @php
                                // Logic to determine status based on active booking
                                $activeBooking = $table->bookings->first();
                                $statusClass = 'tersedia';
                                $statusLabel = 'TERSEDIA';

                                if ($activeBooking) {
                                    $bookingStartAt = \Carbon\Carbon::parse($activeBooking->booking_date . ' ' . $activeBooking->start_time);
                                    $bookingEndAt = \Carbon\Carbon::parse($activeBooking->booking_date . ' ' . $activeBooking->end_time);
                                    $nowAt = \Carbon\Carbon::now();

                                    if ($activeBooking->status === 'confirmed' && $nowAt->betweenIncluded($bookingStartAt, $bookingEndAt)) {
                                        $statusClass = 'terisi';
                                        $statusLabel = 'TERISI';
                                    } elseif (in_array($activeBooking->status, ['pending', 'confirmed', 'booked', 'dipesan'])) {
                                        $statusClass = 'dipesan';
                                        $statusLabel = 'DIPESAN';
                                    }
                                }

                                // Dynamic Time Calculation for all tables
                                $elapsedTime = '00:00:00';
                                $remainingTime = '00:00:00';
                                if ($activeBooking && $activeBooking->status === 'confirmed') {
                                    try {
                                        $start = \Carbon\Carbon::parse($activeBooking->booking_date . ' ' . $activeBooking->start_time);
                                        $end = \Carbon\Carbon::parse($activeBooking->booking_date . ' ' . $activeBooking->end_time);
                                        $now = \Carbon\Carbon::now();
                                        
                                        if ($now->greaterThan($start) && $now->lessThanOrEqualTo($end)) {
                                            $diff = $start->diff($now);
                                            $elapsedTime = sprintf('%02d:%02d:%02d', ($diff->days * 24) + $diff->h, $diff->i, $diff->s);
                                        }
                                        
                                        if ($end->greaterThan($now)) {
                                            $diffRem = $now->diff($end);
                                            $remainingTime = sprintf('%02d:%02d:%02d', ($diffRem->days * 24) + $diffRem->h, $diffRem->i, $diffRem->s);
                                        } else {
                                            $remainingTime = '00:00:00';
                                        }
                                    } catch (\Exception $e) {
                                        // Silent fallback
                                    }
                                }
                            @endphp

                            <div class="adm-meja-card adm-meja--{{ $statusClass }}">
                                <div class="adm-meja-card-top">
                                    <div class="adm-meja-name-wrap">
                                        <h3 class="adm-meja-name">{{ strtoupper($table->name) }}</h3>
                                        <div class="adm-meja-status">
                                            <span class="status-dot-sm"></span> {{ $statusLabel }}
                                        </div>
                                    </div>
                                    <div class="adm-ball-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="currentColor" fill-opacity="0.1"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="adm-meja-card-body">
                                    <div class="adm-info-box">
                                        @if($activeBooking)
                                            @if($statusClass === 'terisi')
                                                <div class="adm-info-row adm-info-row--border-bottom">
                                                    <span class="adm-label">PEMAIN</span>
                                                    <span class="adm-value">{{ $activeBooking->customer_name }}</span>
                                                </div>
                                                <div class="adm-timer-container">
                                                    <div class="adm-timer-group">
                                                        <span class="adm-timer-label">WAKTU BERLALU</span>
                                                        <div class="adm-timer-display">{{ $elapsedTime }}</div>
                                                    </div>
                                                    <div class="adm-timer-group">
                                                        <span class="adm-timer-label">SISA WAKTU</span>
                                                        <div class="adm-timer-display">{{ $remainingTime }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="adm-info-row"><span class="adm-label">DIPESAN OLEH</span><span class="adm-value">{{ $activeBooking->customer_name }}</span></div>
                                                <div class="adm-info-row"><span class="adm-label">DURASI</span><span class="adm-value">{{ $activeBooking->duration ?? '2 Jam' }}</span></div>
                                                <div class="adm-info-row"><span class="adm-label">MAIN JAM</span><span class="adm-value">{{ $activeBooking->start_time }}</span></div>
                                                <div class="adm-info-row adm-info-row--highlight"><span class="adm-label">DIPESAN JAM</span><span class="adm-value">{{ $activeBooking->created_at->format('H:i') }}</span></div>
                                            @endif
                                        @else
                                            <div class="adm-info-empty">TIDAK ADA PEMAIN</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="adm-meja-card-footer">
                                    @if($statusClass === 'terisi')
                                        <button type="button" class="btn-akhiri trigger-end-session" 
                                                data-id="{{ $activeBooking->id }}" 
                                                data-table="{{ $table->name }}"
                                                data-duration="{{ $activeBooking->duration ?? '2 Jam' }}"
                                                data-elapsed="{{ $elapsedTime }}">AKHIRI SESI</button>
                                        <form id="end-session-form-{{ $activeBooking->id }}" action="{{ route('admin.booking.end', $activeBooking->id) }}" method="POST" style="display:none">
                                            @csrf
                                        </form>
                                    @elseif($statusClass === 'dipesan')
                                        <form action="{{ route('admin.booking.confirm', $activeBooking->id) }}" method="POST" style="flex:1">
                                            @csrf
                                            <button type="submit" class="btn-akhiri" style="background: rgba(0, 209, 255, 0.1); color: #00d1ff; border-color: rgba(0, 209, 255, 0.2);">KONFIRMASI</button>
                                        </form>
                                    @else
                                        <div style="flex:1"></div>
                                    @endif
                                    <div class="btn-chat-icon adm-btn-chat" data-meja="{{ $table->id }}">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                        @if($activeBooking && $activeBooking->status === 'pending')
                                            <span class="notif-badge">!</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </main>

    </div>

    {{-- Modals & Popups --}}
    @include('component.c_dashboard.modal.akhiri_sesi')
    @include('component.c_dashboard.modal.chat_blade')
    @include('component.c_dashboard.modal.logout_modal')

    <script src="{{ asset('js/js_component/logout.js') }}"></script>
<script src="{{ asset('js/js_component/chat.js') }}"></script>
    <script src="{{ asset('js/js_component/akhiri.js') }}"></script>
    <script src="{{ asset('js/js_component/option_dashboard.js') }}"></script>
    
    <script>
        // Auto refresh page every 30 seconds to simulate real-time updates
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
