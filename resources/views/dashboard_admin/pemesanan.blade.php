<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>History Pemesanan — Jay's Billiard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/css_layout/app_admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/pemesanan.css') }}">
</head>
<body>
    <div class="adm-layout">
        @include('component.c_dashboard.sidebar.sidebar_admin')

        <main class="adm-main">
            {{-- Top Bar --}}
            @include('component.c_dashboard.topbar.topbar', [
                'topbar_title' => 'History Pemesanan',
                'topbar_sub' => 'Lorem ipsum dolor sit amet'
            ])

            <div class="adm-content adm-history-content">
                
                {{-- ═══════ TOP STATS ═══════ --}}
                <div class="adm-history-stats">
                    <div class="adm-hstat-card">
                        <div class="adm-hstat-icon" style="color: #00ffaa; background: rgba(0,255,170,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="adm-hstat-info">
                            <span class="adm-hstat-label">TOTAL PEMESANAN</span>
                            <span class="adm-hstat-value" id="statTotalBookings">{{ $totalBookings ?? 0 }}X</span>
                        </div>
                    </div>
 
                    <div class="adm-hstat-card">
                        <div class="adm-hstat-icon" style="color: #00e5ff; background: rgba(0,229,255,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/>
                            </svg>
                        </div>
                        <div class="adm-hstat-info">
                            <span class="adm-hstat-label">TOTAL F&B ORDER</span>
                            <span class="adm-hstat-value" id="statTotalOrders">{{ $totalOrders ?? 0 }} Pesanan</span>
                        </div>
                    </div>

                    <div class="adm-hstat-card">
                        <div class="adm-hstat-icon" style="color: #ffb300; background: rgba(255,179,0,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div class="adm-hstat-info">
                            <span class="adm-hstat-label">STATUS</span>
                            <span class="adm-hstat-value">Aktif</span>
                        </div>
                    </div>
                </div>

                {{-- ═══════ SEARCH & ACTIONS ═══════ --}}
                <div class="adm-history-actions">
                    <div class="adm-search-wrap">
                        <svg class="adm-search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" class="adm-search-input" placeholder="Cari Meja">
                    </div>
                </div>

                {{-- ═══════ DATA TABLE ═══════ --}}
                <div class="adm-table-container">
                    <div class="adm-table-scroller">
                        <table class="adm-table">
                            <thead>
                                <tr>
                                    <th class="col-id">ID</th>
                                    <th class="col-customer">NAMA PELANGGAN</th>
                                    <th class="col-meja">MEJA</th>
                                    <th class="col-menu">MAKANAN & MINUMAN</th>
                                    <th class="col-datetime">TANGGAL/WAKTU</th>
                                    <th class="col-duration">DURASI</th>
                                    <th class="col-status">STATUS</th>
                                    <th class="col-action">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $index => $booking)
                                    @php
                                        $initial = strtoupper(substr($booking->customer_name, 0, 1));
                                        $colors = ['#ff5252', '#00acc1', '#26c6da', '#ffb300', '#ab47bc'];
                                        $color = $colors[$index % count($colors)];
                                        
                                        $statusPill = '';
                                        if (in_array(strtolower($booking->status), ['completed', 'selesai'])) {
                                            $statusPill = '<span class="status-pill completed" style="background: rgba(255,82,82,0.1); color: #ff5252;">Selesai</span>';
                                        } else {
                                            $statusPill = '<span class="status-pill paid">Lunas</span>';
                                        }
                                        
                                        // Calculate duration
                                        try {
                                            $start = \Carbon\Carbon::parse($booking->start_time);
                                            $end = \Carbon\Carbon::parse($booking->end_time);
                                            $duration = $start->diffInHours($end) . ' Jam';
                                        } catch (\Exception $e) {
                                            $duration = '2 Jam';
                                        }
                                        
                                        $fnbItems = $booking->orders; // assuming relation exists
                                    @endphp
                                    <tr>
                                        <td class="col-id">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="col-customer">
                                                <div class="cust-avatar" style="background: {{ $color }};">{{ $initial }}</div>
                                                <span class="cust-name">{{ $booking->customer_name }}</span>
                                            </div>
                                        </td>
                                        <td><span class="meja-badge">{{ $booking->table->name ?? 'Meja' }}</span></td>
                                        <td>
                                            @if($fnbItems && $fnbItems->count() > 0)
                                                <div class="menu-list-inline" style="font-size: 0.75rem; color: var(--primary-cyan); font-weight: 700; max-width: 150px; line-height: 1.4; word-wrap: break-word;">
                                                    {{ $fnbItems->pluck('menu_name')->implode(', ') }}
                                                </div>
                                            @else
                                                <span class="menu-empty">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="date-primary">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                            <span class="time-secondary">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</span>
                                        </td>
                                        <td><span class="col-duration">{{ $duration }}</span></td>
                                        <td>{!! $statusPill !!}</td>
                                        <td class="col-action">
                                            <div class="adm-action-buttons">
                                                <button type="button" class="btn-action view" style="background: transparent; color: #ffb300;" onclick='showOrderDetails("{{ htmlspecialchars($booking->customer_name, ENT_QUOTES) }}", "{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}", "{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}", "{{ $booking->table->name ?? 'Meja' }}", "{{ $duration }}", "{{ $booking->payment_method ?? 'QRIS' }}", {{ json_encode($fnbItems->map(function($o) { return ["name" => $o->menu_name, "quantity" => $o->quantity, "price" => $o->price ?? 0]; })) }}, "Rp {{ number_format($booking->total_price, 0, ',', '.') }}")' title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </button>
                                                @if(strtolower($booking->status) !== 'completed')
                                                <form action="{{ route('admin.booking.complete', $booking->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-action edit" style="background: transparent; color: #00e5ff;" onclick="return confirm('Selesaikan pesanan {{ htmlspecialchars($booking->customer_name, ENT_QUOTES) }}?')" title="Selesaikan Pesanan">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('admin.booking.delete', $booking->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action delete" style="background: transparent; color: #ff5252;" onclick="return confirm('Hapus riwayat pesanan {{ htmlspecialchars($booking->customer_name, ENT_QUOTES) }}?')" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" style="text-align: center; padding: 60px; color: rgba(255,255,255,0.1);">Belum ada riwayat pemesanan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Table Footer --}}
                    <div class="adm-table-footer">
                        <div class="adm-pagination-info">
                            {{-- Populated via JS --}}
                        </div>
                        <div class="adm-pagination-links">
                            {{-- Populated via JS --}}
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════ ORDER DETAIL MODAL ═══════════════════════════════ --}}
                <div class="adm-detail-modal-overlay" id="orderDetailModal">
                    <div class="adm-detail-modal">
                        <div class="modal-details-header">
                            <button type="button" class="btn-close-modal" onclick="closeOrderDetailModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                            <span class="detail-cust-name" id="detCustName">Ayu Alda Fu</span>
                            <div class="detail-datetime-row">
                                <span class="detail-dt-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <span id="detDate">24 Oct 2023</span>
                                </span>
                                <span class="detail-dt-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <span id="detTime">19:30</span>
                                </span>
                            </div>
                        </div>

                        <div class="modal-details-body">
                            <div class="modal-separator"></div>
                            
                            <span class="section-label">MEJA & PEMBAYARAN</span>
                            <div class="panel-meja-info">
                                <div class="meja-info-row">
                                    <span class="meja-info-label">MEJA</span>
                                    <span class="meja-info-val" id="detMejaNum">04 [VIP]</span>
                                </div>
                                <div class="meja-info-row">
                                    <span class="meja-info-label">LAMA WAKTU</span>
                                    <span class="meja-info-val" id="detDuration">01:30:00</span>
                                </div>
                                <div class="meja-info-row" style="margin-top: 8px; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 8px;">
                                    <span class="meja-info-label">METODE</span>
                                    <span class="meja-info-val" id="detPaymentMethod" style="color: #00e5ff; font-weight: 700;">QRIS</span>
                                </div>
                            </div>

                            <span class="section-label">MAKANAN & MINUMAN</span>
                            <div class="fnb-list">
                                {{-- Populated via JS --}}
                            </div>

                            <div class="detail-summary">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span id="detSubtotal">Rp 0</span>
                                </div>
                                <div class="summary-row grand-total">
                                    <span class="gt-label">GRAND TOTAL</span>
                                    <span class="gt-val">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════ DELETE CONFIRMATION MODAL ═══════════════════════════════ --}}
                <div class="adm-delete-modal-overlay" id="deleteOrderModal">
                    <div class="adm-delete-modal-content">
                        <div class="modal-delete-header">
                            <div class="modal-delete-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <h3 class="modal-delete-title">Hapus Riwayat</h3>
                        </div>
                        <div class="modal-delete-msg">
                            Apakah Anda yakin ingin menghapus data riwayat pemesanan <strong id="delCustNameText">Nama Pelanggan</strong>? 
                            jika anda menghapusnya nanti data riwayat ini tidak ada lagi
                        </div>
                        <div class="modal-delete-footer">
                            <button type="button" class="btn-delete-cancel" onclick="closeDeleteHistoryModal()">BATAL</button>
                            <form id="deleteOrderForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" id="confirmDeleteBtn" class="btn-delete-confirm">HAPUS</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════ COMPLETE CONFIRMATION MODAL ═══════════════════════════════ --}}
                <div class="adm-delete-modal-overlay" id="completeOrderModal">
                    <div class="adm-delete-modal-content">
                        <div class="modal-delete-header">
                            <div class="modal-delete-icon-wrap" style="background: rgba(0, 229, 255, 0.1); color: #00e5ff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <h3 class="modal-delete-title">Selesaikan Pesanan</h3>
                        </div>
                        <div class="modal-delete-msg">
                            Apakah Anda yakin ingin menyelesaikan pesanan <strong id="completeCustNameText">Nama Pelanggan</strong>? 
                            Status meja akan kembali normal dan riwayat akan ditandai Selesai.
                        </div>
                        <div class="modal-delete-footer">
                            <button type="button" class="btn-delete-cancel" onclick="closeCompleteHistoryModal()">BATAL</button>
                            <form id="completeOrderForm" method="POST">
                                @csrf
                                <button type="submit" id="confirmCompleteBtn" class="btn-delete-confirm" style="background: #00e5ff; color: #000;">SELESAI</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showOrderDetails(name, date, time, meja, duration, paymentMethod = 'QRIS', fnbItems = [], total = 'Rp 0') {
            document.getElementById('detCustName').innerText = name;
            document.getElementById('detDate').innerText = date;
            document.getElementById('detTime').innerText = time;
            document.getElementById('detMejaNum').innerText = meja;
            document.getElementById('detDuration').innerText = duration;
            document.getElementById('detPaymentMethod').innerText = paymentMethod;

            const fnbContainer = document.querySelector('.fnb-list');
            if (fnbContainer) {
                fnbContainer.innerHTML = '';
                if (fnbItems && fnbItems.length > 0) {
                    fnbItems.forEach(item => {
                        const price = (item.price * item.quantity).toLocaleString('id-ID');
                        fnbContainer.insertAdjacentHTML('beforeend', `<div class="fnb-item"><div class="fnb-info-main">${item.name} <span class="fnb-qty">${item.quantity}x</span></div><div class="fnb-price">Rp ${price}</div></div>`);
                    });
                } else {
                    fnbContainer.innerHTML = '<div style="color: var(--text-muted); font-size: 0.8rem;">Tidak ada pesanan F&B</div>';
                }
            }
            document.querySelector('.grand-total .gt-val').innerText = total;
            document.getElementById('detSubtotal').innerText = total;
            document.getElementById('orderDetailModal').classList.add('open');
        }

        function closeOrderDetailModal() { document.getElementById('orderDetailModal').classList.remove('open'); }

        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('adm-detail-modal-overlay')) closeOrderDetailModal();
        });
    </script>

    {{-- Logout Modal --}}
    @include('component.c_dashboard.modal.logout_modal')
    <script src="{{ asset('js/js_component/logout.js') }}"></script>
</body>
</html>
