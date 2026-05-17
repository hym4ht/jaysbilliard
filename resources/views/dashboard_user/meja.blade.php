@extends('layouts.dashboard')

@section('title', "Pilih Meja — Jay's Billiard")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/css_page/user_meja.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_page/css_interaksi component/chat.css') }}">
@endpush

@section('content')
    <div class="meja-wrapper">
        {{-- LEFT: SELECTION AREA --}}
        <div class="meja-selection-area">

            {{-- Status Filter & Header --}}
            <div class="meja-header-flex">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #fff;">Meja Tersedia</h2>
                <div class="meja-legend">
                    <div class="legend-item"><span class="legend-dot dot-occupied"></span> Terisi</div>
                    <div class="legend-item"><span class="legend-dot dot-booked"></span> Dipesan</div>
                    <div class="legend-item"><span class="legend-dot dot-maintenance"></span> Maintenance</div>
                    <div class="legend-item"><span class="legend-dot dot-available"></span> Tersedia</div>
                </div>
            </div>

            {{-- Map Visualization --}}
            <div class="relative bg-gradient-to-br from-[rgba(20,20,30,0.4)] to-[rgba(0,0,0,0.6)] rounded-[32px] min-h-[420px] border border-white/[0.08] flex justify-center items-center overflow-hidden z-10 shadow-[inset_0_0_30px_rgba(0,0,0,0.5)] aspect-[2/1] md:min-h-[350px] sm:min-h-[280px] sm:rounded-[20px]">
                <div class="meja-grid w-[90%] h-[90%] relative md:w-[90%] md:h-[90%] sm:w-[85%] sm:h-[85%]">
                    @foreach($tables as $index => $table)
                        @php
                            $activeBooking = $table->bookings->first();
                            $statusClass = 'available';
                            $statusText = 'TERSEDIA';

                            if ($table->status === 'maintenance') {
                                $statusClass = 'maintenance';
                                $statusText = 'MAINTENANCE';
                            } elseif ($activeBooking) {
                                if ($activeBooking->status === 'confirmed') {
                                    $statusClass = 'occupied';
                                    $statusText = 'TERISI';
                                } elseif ($activeBooking->status === 'pending' || $activeBooking->status === 'booked' || $activeBooking->status === 'dipesan') {
                                    $statusClass = 'booked';
                                    $statusText = 'DIPESAN';
                                }
                            }
                        @endphp
                        <div class="billiard-table status-{{ $statusClass }} {{ $index % 4 === 0 || $index % 4 === 3 ? 'vertical' : '' }}"
                            id="meja-{{ str_pad($table->id, 2, '0', STR_PAD_LEFT) }}" data-id="{{ $table->id }}"
                            data-name="{{ $table->name }}" data-type="{{ $table->type }}"
                            data-price="{{ $table->price_per_hour }}" data-capacity="{{ $table->capacity }}"
                            data-image="{{ $table->image }}" data-status="{{ $statusClass }}">
                            <div class="{{ $index % 4 === 0 || $index % 4 === 3 ? 'table-side-label' : 'table-number' }} color-{{ $statusClass }}"
                                @if($index % 4 === 3) style="left: auto; right: -25px;" @endif>
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="table-pockets">
                                <span class="p-tl"></span><span class="p-tr"></span>
                                <span class="p-bl"></span><span class="p-br"></span>
                                @if($index % 4 === 0 || $index % 4 === 3)
                                    <span class="p-ml"></span><span class="p-mr"></span>
                                @else
                                    <span class="p-tc"></span><span class="p-bc"></span>
                                @endif
                            </div>

                            {{-- PREMIUM HOVER CARD (Tooltip) --}}
                            <div class="table-tooltip premium-card">
                                <div class="tm-image-container">
                                    <img src="{{ $table->image ? asset('storage/' . $table->image) : asset('images/hero-bg.png') }}"
                                        alt="Table">
                                    <div class="tm-price-badge">Rp {{ number_format($table->price_per_hour, 0, ',', '.') }} /
                                        JAM</div>
                                </div>
                                <div class="tm-body">
                                    <div class="tm-header">
                                        <h2 class="tm-name">{{ strtoupper($table->name) }}</h2>
                                        <div class="tm-status">
                                            <span class="tm-status-dot dot-{{ $statusClass }}"></span>
                                            <span class="tm-status-text text-{{ $statusClass }}">{{ $statusText }}</span>
                                        </div>
                                    </div>
                                    <div class="tm-meta">
                                        <span
                                            class="tm-type">{{ stripos($table->name, 'VIP') !== false ? 'VIP AREA' : 'STANDAR' }}</span>
                                        <span class="tm-capacity">{{ $table->capacity }} Orang</span>
                                    </div>
                                    <p class="tm-description">
                                        adalah meja billiard tipe
                                        {{ stripos($table->name, 'VIP') !== false ? 'VIP' : 'standar' }} untuk permainan
                                        {{ stripos($table->name, 'VIP') !== false ? 'eksklusif' : 'biasa' }}, dirancang untuk
                                        kenyamanan maksimal.
                                    </p>
                                    <div class="tm-footer">
                                        @if($statusClass === 'available')
                                            <button class="tm-btn-add">TAMBAH</button>
                                        @else
                                            <button class="tm-btn-add" disabled
                                                style="opacity: 0.5; cursor: not-allowed; background: #333;">PENUH</button>
                                        @endif
                                        <button class="tm-btn-chat" style="position: relative;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path
                                                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.7 8.38 8.38 0 0 1 3.8.9L21 3z">
                                                </path>
                                            </svg>
                                            <span class="notif-badge"
                                                style="display: none; position: absolute; top: -5px; right: -5px; background: #ff3b3b; color: #fff; font-size: 0.6rem; font-weight: bold; width: 14px; height: 14px; border-radius: 50%; align-items: center; justify-content: center; pointer-events: none;">!</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Booking Controls Card --}}
            <div class="booking-section mt-6">
                {{-- Date Selector --}}
                <div class="section-header"
                    style="justify-content: space-between; align-items: center; display: flex; margin-bottom: 20px;">
                    <div class="section-title"
                        style="display: flex; align-items: center; gap: 10px; font-weight: 800; color: #fff; font-size: 1.1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="#00e5ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Select Date
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; position: relative;">
                        <button class="month-nav prev-month"
                            style="background:none;border:none;color:#fff;cursor:pointer;padding:0;display:flex;align-items:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <div id="current-month-year"
                            style="font-size: 0.95rem; font-weight: 800; color: #fff; min-width: 120px; text-align: center; cursor: pointer; padding: 6px 12px; border-radius: 8px; transition: background 0.2s;"
                            onmouseover="this.style.background='rgba(0, 229, 255, 0.1)'"
                            onmouseout="this.style.background='transparent'" title="Pilih Bulan">
                        </div>
                        <button class="month-nav next-month"
                            style="background:none;border:none;color:#fff;cursor:pointer;padding:0;display:flex;align-items:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>

                        {{-- Month Picker Popup --}}
                        <div id="month-picker-popup"
                            style="display: none; position: absolute; top: 120%; left: 50%; transform: translateX(-50%); background: #16191d; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 15px; z-index: 100; width: 260px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                                <button type="button" id="mp-prev-year"
                                    style="background: none; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; transition: background 0.2s;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                                    onmouseout="this.style.background='none'">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                </button>
                                <span id="mp-year-display"
                                    style="font-weight: 800; color: #00e5ff; font-size: 1.1rem;">2026</span>
                                <button type="button" id="mp-next-year"
                                    style="background: none; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 6px; transition: background 0.2s;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                                    onmouseout="this.style.background='none'">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;"
                                id="mp-months-grid">
                                {{-- JS will populate months --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="date-strip">
                    <div class="nav-arrow prev-date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </div>
                    <div class="date-cards-container" id="date-cards-container">
                        {{-- Populated by JS --}}
                    </div>
                    <div class="nav-arrow next-date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </div>

                {{-- Time Selector --}}
                <div class="section-header" style="margin-top: 30px;">
                    <div class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Start Time
                    </div>
                </div>

                <div class="time-grid">
                    <div class="time-slot">14:00</div>
                    <div class="time-slot">15:00</div>
                    <div class="time-slot">16:00</div>
                    <div class="time-slot">17:00</div>
                    <div class="time-slot">18:00</div>
                    <div class="time-slot">19:00</div>
                    <div class="time-slot">20:00</div>
                    <div class="time-slot">21:00</div>
                </div>

                {{-- Duration --}}
                <div class="duration-container">
                    <div class="duration-header">
                        <div class="section-title"
                            style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #fff; font-size: 1.1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="#00e5ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="14" r="8"></circle>
                                <polyline points="12 10 12 14"></polyline>
                                <line x1="10" y1="2" x2="14" y2="2"></line>
                                <line x1="12" y1="2" x2="12" y2="6"></line>
                            </svg>
                            Duration
                        </div>
                        <div class="duration-value">-</div>
                    </div>
                    <input type="range" min="1" max="5" value="1" class="range-slider">
                    <div class="slider-labels">
                        <span>1 jam</span>
                        <span>2 jam</span>
                        <span>3 jam</span>
                        <span>4 jam</span>
                        <span>5 jam</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: ORDER SUMMARY --}}
        <div class="order-summary-area">
            <div class="summary-card">
                <h3 class="summary-title" style="display: flex; align-items: center; justify-content: space-between;">
                    Pesanan Saat Ini
                </h3>

                <div id="selected-tables-list"
                    style="margin-bottom: 25px; display: flex; flex-direction: column; gap: 12px;">
                    {{-- Populated by JS --}}
                    <div class="empty-state"
                        style="text-align: center; padding: 20px; color: #8a8a98; font-size: 0.9rem; border: 1px dashed rgba(255,255,255,0.1); border-radius: 12px;">
                        Belum ada meja dipilih
                    </div>
                </div>

                <div class="summary-details">
                    <div class="detail-row">
                        <span class="detail-label">Date</span>
                        <span class="detail-value" id="summary-date">April 5, 2026</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Time</span>
                        <span class="detail-value" id="summary-time">19:00 - 21:00</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value" id="summary-duration">2 Hours</span>
                    </div>
                </div>

                <div class="payment-breakdown"
                    style="border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 30px; margin-top: auto;">
                    <div class="breakdown-row" style="margin-bottom: 15px;">
                        <span style="color: #8a8a98;">Subtotal</span>
                        <span id="summary-subtotal" style="color: #fff; font-weight: 700;">Rp200.000</span>
                    </div>
                    <div class="breakdown-row" style="margin-bottom: 25px;">
                        <span style="color: #8a8a98;">Pajak (10%)</span>
                        <span id="summary-tax" style="color: #fff; font-weight: 700;">Rp20.000</span>
                    </div>
                    <div class="breakdown-total"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                        <span class="total-label" style="font-size: 1.2rem; font-weight: 800; color: #fff;">Total</span>
                        <span class="total-value" id="summary-total"
                            style="font-size: 1.6rem; font-weight: 900; color: var(--primary-cyan);">Rp220.000</span>
                    </div>
                </div>

                <a href="#" class="confirm-btn" id="confirm-booking-btn"
                    style="display: block; text-align: center; text-decoration: none;">Konfirmasi Pemesanan</a>
            </div>
        </div>
    </div>

    {{-- Chat Popup Component --}}
    @include('component.c_dashboard.modal.chat_blade')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tables = document.querySelectorAll('.billiard-table');
            const dateCardsContainer = document.getElementById('date-cards-container');
            const timeSlots = document.querySelectorAll('.time-slot');
            const rangeSlider = document.querySelector('.range-slider');
            const durationValue = document.querySelector('.duration-value');


            // Date Selector elements
            const monthYearDisplay = document.getElementById('current-month-year');
            const prevDateBtn = document.querySelector('.prev-date');
            const nextDateBtn = document.querySelector('.next-date');
            const prevMonthBtn = document.querySelector('.prev-month');
            const nextMonthBtn = document.querySelector('.next-month');
            const summaryDate = document.getElementById('summary-date');

            // Month Picker Elements
            const monthPickerPopup = document.getElementById('month-picker-popup');
            const mpYearDisplay = document.getElementById('mp-year-display');
            const mpPrevYear = document.getElementById('mp-prev-year');
            const mpNextYear = document.getElementById('mp-next-year');
            const mpMonthsGrid = document.getElementById('mp-months-grid');

            let startDate = new Date(); // Start view from today
            let selectedDate = new Date(); // Match initial table statuses to today's bookings
            let durationSelected = false; // Flag for duration selection
            let pickerYear = startDate.getFullYear();
            const availabilityUrl = @json(route('user.meja.availability'));

            const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            function formatLocalDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function isPastDate(date) {
                const compareDate = new Date(date);
                compareDate.setHours(0, 0, 0, 0);
                return compareDate < today;
            }

            function isPastStartTime(timeText) {
                if (!selectedDate) return false;

                const selectedDay = new Date(selectedDate);
                selectedDay.setHours(0, 0, 0, 0);
                const now = new Date();
                const currentDay = new Date(now);
                currentDay.setHours(0, 0, 0, 0);

                if (selectedDay.getTime() !== currentDay.getTime()) return false;

                const [hour, minute] = timeText.split(':').map(Number);
                const slotDate = new Date(now);
                slotDate.setHours(hour, minute, 0, 0);

                return slotDate <= now;
            }

            function updateTimeSlotAvailability() {
                timeSlots.forEach(slot => {
                    const disabled = isPastStartTime(slot.innerText.trim());
                    slot.classList.toggle('disabled', disabled);
                    slot.style.opacity = disabled ? '0.35' : '';
                    slot.style.cursor = disabled ? 'not-allowed' : '';
                    slot.style.pointerEvents = disabled ? 'none' : '';

                    if (disabled && slot.classList.contains('active')) {
                        slot.classList.remove('active');
                    }
                });

                updateTimeSummary();
            }

            function updateTableVisualStatus(table, status, statusText) {
                const statuses = ['available', 'booked', 'occupied', 'maintenance'];
                statuses.forEach(item => {
                    table.classList.remove(`status-${item}`);
                });
                table.classList.add(`status-${status}`);
                table.dataset.status = status;

                const label = table.querySelector('.table-number, .table-side-label');
                if (label) {
                    statuses.forEach(item => label.classList.remove(`color-${item}`));
                    label.classList.add(`color-${status}`);
                }

                const dot = table.querySelector('.tm-status-dot');
                if (dot) {
                    statuses.forEach(item => dot.classList.remove(`dot-${item}`));
                    dot.classList.add(`dot-${status}`);
                }

                const text = table.querySelector('.tm-status-text');
                if (text) {
                    statuses.forEach(item => text.classList.remove(`text-${item}`));
                    text.classList.add(`text-${status}`);
                    text.innerText = statusText;
                }

                const addButton = table.querySelector('.tm-btn-add');
                if (addButton) {
                    const isAvailable = status === 'available';
                    addButton.disabled = !isAvailable;
                    addButton.innerText = isAvailable ? 'TAMBAH' : 'PENUH';
                    addButton.style.opacity = isAvailable ? '' : '0.5';
                    addButton.style.cursor = isAvailable ? '' : 'not-allowed';
                    addButton.style.background = isAvailable ? '' : '#333';
                }
            }

            async function refreshTableAvailability() {
                if (!selectedDate) return;

                try {
                    // Build URL with date and optional time range
                    let url = `${availabilityUrl}?date=${formatLocalDate(selectedDate)}`;
                    
                    // Add time range if both start time and duration are selected
                    const activeTimeSlot = document.querySelector('.time-slot.active');
                    if (activeTimeSlot && durationSelected) {
                        const startTime = activeTimeSlot.innerText.trim();
                        const duration = parseInt(rangeSlider.value);
                        const startHour = parseInt(startTime.split(':')[0]);
                        const endHour = startHour + duration;
                        const endTime = `${String(endHour).padStart(2, '0')}:00`;
                        
                        url += `&start_time=${startTime}&end_time=${endTime}`;
                    }
                    
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Gagal memuat status meja');

                    const data = await response.json();
                    const removedTableNames = [];

                    tables.forEach(table => {
                        const availability = data.statuses?.[table.dataset.id];
                        if (!availability) return;

                        updateTableVisualStatus(table, availability.status, availability.text);

                        if (availability.status !== 'available' && table.classList.contains('selected')) {
                            table.classList.remove('selected');
                            const selectedTable = selectedTables.find(item => item.id === table.dataset.id);
                            if (selectedTable) removedTableNames.push(selectedTable.name);
                            selectedTables = selectedTables.filter(item => item.id !== table.dataset.id);
                        }
                    });

                    if (removedTableNames.length > 0) {
                        updateSelectedTablesList();
                        Swal.fire({
                            title: 'Pilihan Meja Diperbarui',
                            text: `${removedTableNames.join(', ')} tidak tersedia pada waktu ini.`,
                            icon: 'info',
                            confirmButtonColor: '#00e5ff',
                            background: '#0f1115',
                            color: '#fff'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'Status Meja Gagal Dimuat',
                        text: 'Coba pilih tanggal lagi beberapa saat lagi.',
                        icon: 'error',
                        confirmButtonColor: '#ff3b3b',
                        background: '#0f1115',
                        color: '#fff'
                    });
                }
            }

            function renderMonthPicker() {
                mpYearDisplay.innerText = pickerYear;
                mpMonthsGrid.innerHTML = '';

                monthNames.forEach((month, index) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    const isActive = (pickerYear === startDate.getFullYear() && index === startDate.getMonth());

                    btn.style.cssText = `background: ${isActive ? 'var(--primary-cyan)' : 'rgba(255,255,255,0.05)'}; color: ${isActive ? '#000' : '#fff'}; border: none; padding: 10px 5px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s; font-size: 0.85rem;`;
                    btn.innerText = month;

                    btn.onmouseover = () => { if (!isActive) btn.style.background = 'rgba(255,255,255,0.1)' };
                    btn.onmouseout = () => { if (!isActive) btn.style.background = 'rgba(255,255,255,0.05)' };

                    btn.addEventListener('click', () => {
                        startDate.setFullYear(pickerYear);
                        startDate.setMonth(index);
                        renderDates();
                        monthPickerPopup.style.display = 'none';
                    });

                    mpMonthsGrid.appendChild(btn);
                });
            }

            monthYearDisplay.addEventListener('click', () => {
                pickerYear = startDate.getFullYear();
                renderMonthPicker();
                monthPickerPopup.style.display = monthPickerPopup.style.display === 'none' ? 'block' : 'none';
            });

            mpPrevYear.addEventListener('click', () => {
                pickerYear--;
                renderMonthPicker();
            });

            mpNextYear.addEventListener('click', () => {
                pickerYear++;
                renderMonthPicker();
            });

            document.addEventListener('click', (e) => {
                if (!monthYearDisplay.contains(e.target) && !monthPickerPopup.contains(e.target)) {
                    monthPickerPopup.style.display = 'none';
                }
            });

            function renderDates() {
                dateCardsContainer.innerHTML = '';

                // Update header
                const options = { month: 'long', year: 'numeric' };
                monthYearDisplay.innerText = startDate.toLocaleDateString('id-ID', options);

                for (let i = 0; i < 7; i++) {
                    const date = new Date(startDate);
                    date.setDate(startDate.getDate() + i);

                    const isSelected = selectedDate && date.toDateString() === selectedDate.toDateString();
                    const dayName = date.toLocaleDateString('id-ID', { weekday: 'short' }).toUpperCase();
                    const dayNum = date.getDate();
                    const fullDate = formatLocalDate(date);
                    const disabled = isPastDate(date);

                    const card = document.createElement('div');
                    card.className = `date-card ${isSelected ? 'active' : ''} ${disabled ? 'disabled' : ''}`;
                    card.dataset.date = fullDate;
                    card.style.opacity = disabled ? '0.35' : '';
                    card.style.cursor = disabled ? 'not-allowed' : '';
                    card.innerHTML = `
                                                                                                                    <span class="day-name">${dayName}</span>
                                                                                                                    <span class="day-num">${dayNum}</span>
                                                                                                                `;

                    card.addEventListener('click', () => {
                        if (disabled) return;
                        selectedDate = new Date(date);
                        renderDates();
                        updateSummaryDate();
                        updateTimeSlotAvailability();
                        refreshTableAvailability();
                    });

                    dateCardsContainer.appendChild(card);
                }
            }

            function updateSummaryDate() {
                if (selectedTables.length === 0 || !selectedDate) {
                    summaryDate.innerText = '-';
                    return;
                }
                const options = { month: 'long', day: 'numeric', year: 'numeric' };
                summaryDate.innerText = selectedDate.toLocaleDateString('id-ID', options);
            }

            let selectedTables = []; // Array to store multiple selected table objects

            function updateSelectedTablesList() {
                const listContainer = document.getElementById('selected-tables-list');
                const badge = document.getElementById('tables-count-badge');
                listContainer.innerHTML = '';
                if (badge) {
                    badge.innerText = selectedTables.length;
                }

                if (selectedTables.length === 0) {
                    listContainer.innerHTML = `
                                                    <div class="empty-state" style="text-align: center; padding: 20px; color: #8a8a98; font-size: 0.9rem; border: 1px dashed rgba(255,255,255,0.1); border-radius: 12px;">
                                                        Belum ada meja dipilih
                                                    </div>
                                                `;
                    updateCalculations();
                    updateSummaryDate();
                    updateTimeSummary();
                    return;
                }

                selectedTables.forEach(table => {
                    const priceFormatted = parseInt(table.price).toLocaleString('id-ID');
                    const itemHtml = `
                                                    <div class="summary-item-card update-flash" data-id="${table.id}" style="display: flex; justify-content: space-between; align-items: center; background: rgba(0, 242, 255, 0.05); padding: 12px; border-radius: 12px; border: 1px solid rgba(0, 242, 255, 0.1); animation: slideIn 0.3s ease-out;">
                                                        <div style="display: flex; gap: 12px; align-items: center;">
                                                            <img src="${table.image}" style="width: 45px; height: 45px; border-radius: 8px; object-fit: cover;">
                                                            <div class="item-info">
                                                                <div style="font-size: 0.9rem; font-weight: 800; color: #fff;">${table.name}</div>
                                                                <div style="font-size: 0.7rem; color: #8a8a98;">${table.type === 'vip' ? 'VIP' : 'Regular'} • ${table.capacity} Orang</div>
                                                            </div>
                                                        </div>
                                                        <div style="text-align: right;">
                                                            <div style="font-size: 0.9rem; font-weight: 800; color: #fff;">Rp ${priceFormatted}</div>
                                                            <button class="remove-table" style="background: none; border: none; color: #ff3b3b; font-size: 0.65rem; font-weight: 700; padding: 0; cursor: pointer;">Hapus</button>
                                                        </div>
                                                    </div>
                                                `;
                    listContainer.insertAdjacentHTML('beforeend', itemHtml);
                });

                // Pulse badge
                if (badge) {
                    badge.classList.remove('pulse-animation');
                    void badge.offsetWidth;
                    badge.classList.add('pulse-animation');
                }

                // Handle remove buttons
                document.querySelectorAll('.remove-table').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const card = btn.closest('.summary-item-card');
                        const id = card.dataset.id;
                        selectedTables = selectedTables.filter(t => t.id !== id);

                        // Update visual state on map
                        const mapTable = document.getElementById(`meja-${id.toString().padStart(2, '0')}`);
                        if (mapTable) mapTable.classList.remove('selected');

                        updateSelectedTablesList();
                    });
                });

                updateCalculations();
                updateSummaryDate();
                updateTimeSummary();
            }

            prevDateBtn.addEventListener('click', () => {
                startDate.setDate(startDate.getDate() - 7);
                renderDates();
            });

            nextDateBtn.addEventListener('click', () => {
                startDate.setDate(startDate.getDate() + 7);
                renderDates();
            });

            prevMonthBtn.addEventListener('click', () => {
                startDate.setMonth(startDate.getMonth() - 1);
                renderDates();
            });

            nextMonthBtn.addEventListener('click', () => {
                startDate.setMonth(startDate.getMonth() + 1);
                renderDates();
            });

            // Initial render
            renderDates();
            updateSummaryDate();
            updateTimeSlotAvailability();
            refreshTableAvailability();

            // Hover Tooltip Fallback JS
            tables.forEach(table => {
                const tooltip = table.querySelector('.table-tooltip');
                if (tooltip) {
                    table.addEventListener('mouseenter', () => {
                        tooltip.style.opacity = '1';
                        tooltip.style.visibility = 'visible';
                        tooltip.style.transform = 'translate(-50%, -50%) scale(1)';
                        tooltip.style.zIndex = '11000';
                    });
                    table.addEventListener('mouseleave', () => {
                        tooltip.style.opacity = '0';
                        tooltip.style.visibility = 'hidden';
                        tooltip.style.transform = 'translate(-50%, -50%) scale(0.95)';
                        tooltip.style.zIndex = '1000';
                    });
                }
            });

            // Table Selection
            tables.forEach(table => {
                table.addEventListener('click', () => {
                    const status = table.dataset.status;

                    // Validation for Real-time Status
                    if (status === 'occupied' || status === 'booked' || status === 'maintenance') {
                        Swal.fire({
                            title: 'Meja Tidak Tersedia',
                            text: status === 'maintenance'
                                ? 'Gagal! Meja ini sedang maintenance.'
                                : 'Gagal! Meja ini sedang digunakan atau sudah dipesan.',
                            icon: 'error',
                            confirmButtonColor: '#ff3b3b',
                            background: '#0f1115',
                            color: '#fff'
                        });
                        return;
                    }

                    // Current behavior: Hover opens popup, click selection toggle
                    const id = table.dataset.id;
                    const isAlreadySelected = selectedTables.find(t => t.id === id);

                    if (isAlreadySelected) {
                        // Deselect if already in list
                        selectedTables = selectedTables.filter(t => t.id !== id);
                        table.classList.remove('selected');
                    } else {
                        // Add to selection
                        const name = table.dataset.name;
                        const price = table.dataset.price;
                        const capacity = table.dataset.capacity || '4';
                        const type = table.dataset.type;
                        const image = table.dataset.image && table.dataset.image.trim() !== '' ? '/storage/' + table.dataset.image : '/images/hero-bg.png';

                        selectedTables.push({ id, name, price, capacity, type, image });
                        table.classList.add('selected');

                        // Flash animation for summary list
                        const listContainer = document.getElementById('selected-tables-list');
                        listContainer.classList.remove('update-flash');
                        void listContainer.offsetWidth;
                        listContainer.classList.add('update-flash');
                    }

                    updateSelectedTablesList();
                });
            });

            // Handle TAMBAH button click inside tooltip
            const addButtons = document.querySelectorAll('.tm-btn-add');
            addButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Avoid triggering parent click

                    const parentTable = btn.closest('.billiard-table');
                    if (parentTable) {
                        const id = parentTable.dataset.id;
                        const isAlreadySelected = selectedTables.find(t => t.id === id);

                        if (!isAlreadySelected) {
                            parentTable.click(); // Trigger the click logic above
                        }

                        // Feedback
                        Swal.fire({
                            title: 'Meja Ditambahkan!',
                            text: `${parentTable.dataset.name} ditambahkan ke pesanan.`,
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            background: '#0f1115',
                            color: '#fff',
                            iconColor: '#00f2ff'
                        });
                    }

                    document.querySelector('.booking-section').scrollIntoView({ behavior: 'smooth' });
                });
            });

            // Chat Logic for User Dashboard (Meja)
            const chatButtons = document.querySelectorAll('.tm-btn-chat');
            const chatOverlay = document.getElementById('chatOverlay');
            const chatPopup = document.getElementById('chatPopup');
            const chatCloseBtn = document.getElementById('chatClose');
            const chatBody = document.getElementById('chatBody');
            const chatInput = document.getElementById('chatInput');
            const chatSendBtn = document.getElementById('chatSend');

            let userChatData = JSON.parse(localStorage.getItem('billiard_chat_history'));
            if (!userChatData) {
                userChatData = {
                    1: { table: 'MEJA 01', status: 'TERISI', statusColor: '#00e5ff', user: 'Rian S.', messages: [] },
                    2: { table: 'MEJA 02', status: 'TERSEDIA', statusColor: '#00e5ff', user: 'System', messages: [] },
                    3: { table: 'MEJA 03', status: 'DIPESAN', statusColor: '#ffab00', user: 'Zaenal', messages: [] },
                    4: { table: 'MEJA 04', status: 'TERISI', statusColor: '#00e5ff', user: 'Haecan', messages: [] }
                };
                localStorage.setItem('billiard_chat_history', JSON.stringify(userChatData));
            }

            function updateUserBadges() {
                chatButtons.forEach(btn => {
                    const parentTable = btn.closest('.billiard-table');
                    if (parentTable) {
                        const id = parentTable.dataset.id;
                        let badge = btn.querySelector('.notif-badge');

                        if (userChatData[id] && userChatData[id].hasUnreadForUser) {
                            if (badge) badge.style.display = 'flex';
                        } else {
                            if (badge) badge.style.display = 'none';
                        }
                    }
                });
            }

            function renderUserMessages(id) {
                chatBody.innerHTML = '';
                const messages = userChatData[id] ? userChatData[id].messages : [];
                if (messages.length === 0) {
                    const welcomeWrapper = document.createElement('div');
                    welcomeWrapper.className = 'chat-msg chat-msg--customer'; // Left side (admin)
                    welcomeWrapper.innerHTML = `
                                                <div class="chat-bubble">Halo, ada yang bisa kami bantu mengenai meja ini?</div>
                                                <div class="chat-meta">System &bull; Now</div>
                                            `;
                    chatBody.appendChild(welcomeWrapper);
                } else {
                    messages.forEach(msg => {
                        const wrapper = document.createElement('div');
                        // from 'user' -> right (admin class), from 'admin' -> left (customer class)
                        wrapper.className = 'chat-msg ' + (msg.from === 'user' ? 'chat-msg--admin' : 'chat-msg--customer');
                        wrapper.innerHTML = `
                                                    <div class="chat-bubble">${msg.text}</div>
                                                    <div class="chat-meta">${msg.time}</div>
                                                `;
                        chatBody.appendChild(wrapper);
                    });
                }
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            chatButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const parentTable = btn.closest('.billiard-table');
                    if (parentTable) {
                        const id = parentTable.dataset.id;
                        const name = parentTable.dataset.name;
                        const statusClass = parentTable.dataset.status;

                        // Clear unread flag for user when opening chat
                        if (userChatData[id] && userChatData[id].hasUnreadForUser) {
                            userChatData[id].hasUnreadForUser = false;
                            localStorage.setItem('billiard_chat_history', JSON.stringify(userChatData));
                            updateUserBadges();
                        }

                        document.getElementById('chatTableName').textContent = name;
                        let statusText = 'TERSEDIA';
                        let statusColor = '#00e5ff';
                        if (statusClass === 'occupied') { statusText = 'TERISI'; statusColor = '#ff3b3b'; }
                        else if (statusClass === 'booked') { statusText = 'DIPESAN'; statusColor = '#ffab00'; }
                        else if (statusClass === 'maintenance') { statusText = 'MAINTENANCE'; statusColor = '#ff3b3b'; }

                        document.getElementById('chatStatus').textContent = statusText;
                        document.getElementById('chatStatus').style.color = statusColor;
                        document.querySelector('.chat-popup-dot').style.color = statusColor;
                        document.getElementById('chatUserName').textContent = "Admin";

                        renderUserMessages(id);
                        chatPopup.dataset.activeTableId = id;

                        chatOverlay.classList.add('active');
                        chatPopup.classList.add('active');
                    }
                });
            });

            function closeChatPopup() {
                chatOverlay.classList.remove('active');
                chatPopup.classList.remove('active');
                chatPopup.dataset.activeTableId = '';
            }

            if (chatCloseBtn) chatCloseBtn.addEventListener('click', closeChatPopup);
            if (chatOverlay) chatOverlay.addEventListener('click', closeChatPopup);

            if (chatSendBtn) {
                chatSendBtn.addEventListener('click', () => {
                    const text = chatInput.value.trim();
                    const tableId = chatPopup.dataset.activeTableId;
                    if (!text || !tableId) return;

                    const now = new Date();
                    const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

                    if (!userChatData[tableId]) userChatData[tableId] = { messages: [] };
                    userChatData[tableId].messages.push({ from: 'user', text: text, time: timeStr });

                    // Notify admin there is an unread message
                    userChatData[tableId].hasUnreadForAdmin = true;

                    localStorage.setItem('billiard_chat_history', JSON.stringify(userChatData));

                    renderUserMessages(tableId);
                    chatInput.value = '';
                });

                chatInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        chatSendBtn.click();
                    }
                });
            }

            window.addEventListener('storage', (e) => {
                if (e.key === 'billiard_chat_history') {
                    userChatData = JSON.parse(e.newValue);
                    updateUserBadges();

                    const activeId = chatPopup.dataset.activeTableId;
                    if (activeId && chatPopup.classList.contains('active')) {
                        if (userChatData[activeId] && userChatData[activeId].hasUnreadForUser) {
                            userChatData[activeId].hasUnreadForUser = false;
                            localStorage.setItem('billiard_chat_history', JSON.stringify(userChatData));
                            updateUserBadges();
                        }
                        renderUserMessages(activeId);
                    }
                }
            });

            // Initialize badges on load
            updateUserBadges();

            // Time Selection
            timeSlots.forEach(slot => {
                slot.addEventListener('click', () => {
                    if (slot.classList.contains('disabled') || isPastStartTime(slot.innerText.trim())) {
                        Swal.fire({ icon: 'warning', title: 'Jam Sudah Lewat', text: 'Silakan pilih jam mulai yang masih tersedia.', background: '#0f1115', color: '#fff' });
                        updateTimeSlotAvailability();
                        return;
                    }

                    timeSlots.forEach(s => s.classList.remove('active'));
                    slot.classList.add('active');
                    updateTimeSummary();
                    // Refresh table availability when time is selected
                    if (durationSelected) {
                        refreshTableAvailability();
                    }
                });
            });

            // Duration Slider
            rangeSlider.addEventListener('input', (e) => {
                const val = e.target.value;
                durationSelected = true;
                durationValue.innerText = val + ' Jam';
                document.getElementById('summary-duration').innerText = val + ' Jam';
                updateTimeSummary();
                updateCalculations();
                // Refresh table availability when duration changes
                const activeTimeSlot = document.querySelector('.time-slot.active');
                if (activeTimeSlot) {
                    refreshTableAvailability();
                }
            });

            function updateTimeSummary() {
                const activeTimeSlot = document.querySelector('.time-slot.active');
                if (selectedTables.length === 0 || !activeTimeSlot || !durationSelected) {
                    document.getElementById('summary-time').innerText = '-';
                    document.getElementById('summary-duration').innerText = '-';
                    return;
                }

                const activeTime = activeTimeSlot.innerText;
                const duration = parseInt(rangeSlider.value);
                const startHour = parseInt(activeTime.split(':')[0]);
                const endHour = startHour + duration;
                document.getElementById('summary-time').innerText = `${activeTime} - ${endHour}:00`;
                document.getElementById('summary-duration').innerText = duration + ' Jam';
            }

            function updateCalculations() {
                const duration = durationSelected ? parseInt(rangeSlider.value) : 0;
                let subtotal = 0;

                selectedTables.forEach(t => {
                    subtotal += (parseInt(t.price) || 0) * duration;
                });

                const tax = Math.round(subtotal * 0.1);
                const total = subtotal + tax;

                document.getElementById('summary-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
                document.getElementById('summary-tax').innerText = 'Rp ' + tax.toLocaleString('id-ID');
                document.getElementById('summary-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            }

            updateCalculations();
            updateTimeSummary();

            const confirmBtn = document.getElementById('confirm-booking-btn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    const orderData = {
                        tables: selectedTables,
                        date: document.getElementById('summary-date').innerText,
                        date_iso: selectedDate ? formatLocalDate(selectedDate) : null,
                        time: document.getElementById('summary-time').innerText,
                        duration: document.getElementById('summary-duration').innerText,
                        subtotal: document.getElementById('summary-subtotal').innerText,
                        tax: document.getElementById('summary-tax').innerText,
                        total: document.getElementById('summary-total').innerText
                    };

                    if (selectedTables.length === 0) {
                        Swal.fire({ icon: 'warning', title: 'Meja Kosong', text: 'Silakan pilih minimal satu meja dulu.', background: '#0f1115', color: '#fff' });
                        return;
                    }

                    if (!selectedDate) {
                        Swal.fire({ icon: 'warning', title: 'Tanggal Belum Dipilih', text: 'Silakan pilih tanggal main dulu.', background: '#0f1115', color: '#fff' });
                        return;
                    }

                    const activeTimeSlot = document.querySelector('.time-slot.active');
                    if (!activeTimeSlot) {
                        Swal.fire({ icon: 'warning', title: 'Jam Belum Dipilih', text: 'Silakan pilih jam mulai main dulu.', background: '#0f1115', color: '#fff' });
                        return;
                    }

                    if (isPastDate(selectedDate) || isPastStartTime(activeTimeSlot.innerText.trim())) {
                        Swal.fire({ icon: 'warning', title: 'Waktu Tidak Valid', text: 'Tidak bisa reservasi untuk tanggal atau jam yang sudah lewat.', background: '#0f1115', color: '#fff' });
                        updateTimeSlotAvailability();
                        return;
                    }

                    if (!durationSelected) {
                        Swal.fire({ icon: 'warning', title: 'Durasi Belum Dipilih', text: 'Silakan tentukan durasi main dulu.', background: '#0f1115', color: '#fff' });
                        return;
                    }

                    localStorage.setItem('meja_order', JSON.stringify(orderData));
                    window.location.href = "{{ route('user.meja.konfirmasi') }}";
                });
            }
        });
    </script>
@endpush
