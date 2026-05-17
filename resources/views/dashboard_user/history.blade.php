@extends('layouts.dashboard')

@section('title', 'Riwayat Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/css_page/user_history.css') }}?v={{ filemtime(public_path('css/css_page/user_history.css')) }}">
@endpush

@section('content')
    <div class="user-history-page">
        <section class="history-stats">
            <div class="history-stat">
                <span class="history-stat__label">TOTAL RIWAYAT</span>
                <span class="history-stat__value">{{ $stats['total'] }}</span>
            </div>
            <div class="history-stat">
                <span class="history-stat__label">BOOKING MEJA</span>
                <span class="history-stat__value">{{ $stats['booking'] }}</span>
            </div>
            <div class="history-stat">
                <span class="history-stat__label">PESANAN F&B</span>
                <span class="history-stat__value">{{ $stats['fnb'] }}</span>
            </div>
        </section>

        <section class="history-panel">
            <div class="history-toolbar">
                <div class="history-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="search" id="history-search-input" placeholder="Cari riwayat">
                </div>
                <div class="history-tabs" aria-label="Filter riwayat">
                    <button type="button" class="history-tab active" data-filter="all">Semua</button>
                    <button type="button" class="history-tab" data-filter="booking">Meja</button>
                    <button type="button" class="history-tab" data-filter="fnb">F&B</button>
                </div>
            </div>

            <div class="history-list" id="history-list">
                @forelse($histories as $history)
                    <article class="history-card" data-type="{{ $history['type'] }}" data-search="{{ e(strtolower($history['title'] . ' ' . $history['subtitle'] . ' ' . $history['description'] . ' ' . $history['payment_method'] . ' ' . $history['status_label'] . ' ' . $history['transaction_id'])) }}">
                        <div class="history-card__header">
                            <div class="history-card__badge {{ $history['dot_class'] }}">
                                {{ $history['type'] === 'booking' ? 'Booking Meja' : 'Pesanan F&B' }}
                            </div>
                            <span class="history-status {{ $history['status_class'] }}">{{ $history['status_label'] }}</span>
                        </div>
                        
                        <div class="history-card__id">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span>ID: {{ $history['transaction_id'] }}</span>
                        </div>
                        
                        <h3 class="history-card__title">{{ $history['title'] }}</h3>
                        
                        <div class="history-card__details">
                            <div class="history-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>{{ $history['subtitle'] }}</span>
                            </div>
                            <div class="history-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>{{ $history['description'] }}</span>
                            </div>
                            <div class="history-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span>{{ $history['payment_method'] }}</span>
                            </div>
                        </div>
                        
                        <div class="history-card__footer">
                            <span class="history-card__amount">{{ $history['amount'] }}</span>
                        </div>
                    </article>
                @empty
                    <div class="history-empty">Belum ada riwayat pesanan.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('history-search-input');
            const tabs = document.querySelectorAll('.history-tab');
            const items = document.querySelectorAll('.history-card');
            let activeFilter = 'all';

            function applyFilter() {
                const query = (searchInput?.value || '').trim().toLowerCase();

                items.forEach(item => {
                    const matchesType = activeFilter === 'all' || item.dataset.type === activeFilter;
                    const matchesSearch = !query || item.dataset.search.includes(query);
                    item.hidden = !(matchesType && matchesSearch);
                });
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(button => button.classList.remove('active'));
                    tab.classList.add('active');
                    activeFilter = tab.dataset.filter || 'all';
                    applyFilter();
                });
            });

            searchInput?.addEventListener('input', applyFilter);
        });
    </script>
@endpush
