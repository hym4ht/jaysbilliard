@extends('layouts.dashboard')

@section('title', "Konfirmasi Pembayaran — Jay's Billiard")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/css_page/konfirmasi_pembayaran.css') }}">
@endpush

@section('content')
    <div class="konfirmasi-wrapper">
        {{-- LEFT COLUMN: ORDER SUMMARY --}}
        <div class="summary-container">
            <h3 class="summary-title">Ringkasan Pemesanan</h3>

            <div id="konfirmasi-tables-container" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px;">
                {{-- Populated by JS --}}
                <div class="table-info-card">
                    <img src="{{ asset('images/hero-bg.png') }}" alt="Meja" class="table-img" id="konfirmasi-img">
                    <div class="table-text">
                        <div class="table-name" id="konfirmasi-name">Pilih Meja <span class="table-capacity" id="konfirmasi-ppl">0 Orang</span></div>
                        <div class="table-type" id="konfirmasi-type">Regular</div>
                    </div>
                </div>
            </div>

            <div class="booking-details">
                <div class="detail-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span class="detail-label">Date</span>
                    <span class="detail-value" id="konfirmasi-date">Oct 24, 2023</span>
                </div>
                <div class="detail-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span class="detail-label">Time</span>
                    <span class="detail-value" id="konfirmasi-time">19:00 - 21:00</span>
                </div>
                <div class="detail-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span class="detail-label">Duration</span>
                    <span class="detail-value" id="konfirmasi-duration">2 Hours</span>
                </div>
            </div>

            <div class="price-breakdown">
                <div class="breakdown-row">
                    <span>Subtotal</span>
                    <span id="konfirmasi-subtotal">Rp 200.000</span>
                </div>
                <div class="breakdown-row">
                    <span>Service & Tax (10%)</span>
                    <span id="konfirmasi-tax">Rp 5.000</span>
                </div>
                <div class="total-row">
                    <span style="font-size: 1.1rem; font-weight: 800; color: #fff;">Total Amount</span>
                </div>
                <div class="total-row" style="margin-top: 5px;">
                    <span class="total-value" id="konfirmasi-total">Rp 205.000</span>
                </div>
            </div>

            <div class="payment-details-section">
                <div class="detail-section-title" id="payment-method-title">QRIS via Midtrans</div>
                <div class="detail-section-content" id="payment-method-content">
                    <div class="payment-method-preview">
                        <div class="payment-badge payment-badge--qris">QR</div>
                        <div>
                            <div class="payment-method-preview__title">QRIS Dinamis</div>
                            <p class="payment-method-preview__text">
                                Snap Midtrans akan membuka kode QRIS yang bisa dibayar lewat aplikasi bank atau e-wallet pendukung QRIS.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="no-method-selected" style="display: none; padding: 40px; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                    Tidak ada metode pembayaran yang dipilih
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: PAYMENT METHODS --}}
        <div class="payment-main-area">
            {{-- Countdown Timer --}}
            <div class="timer-card">
                <div class="timer-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="M20 12h2"></path><path d="M2 12h2"></path></svg>
                </div>
                <div class="timer-info" id="timer-status-wrapper">
                    <div class="timer-label" id="payment-status-text">Batas Waktu Pembayaran</div>
                    <div class="timer-sub" id="payment-status-sub">Selesaikan pembayaran melalui Midtrans Snap</div>
                </div>
                <div class="countdown-display">
                    <div class="time-unit">
                        <div class="time-box">0</div>
                        <div class="time-label">HRS</div>
                    </div>
                    <div class="time-dots">:</div>
                    <div class="time-unit">
                        <div class="time-box">22</div>
                        <div class="time-label">MIN</div>
                    </div>
                    <div class="time-dots">:</div>
                    <div class="time-unit">
                        <div class="time-box">30</div>
                        <div class="time-label">SEC</div>
                    </div>
                </div>
            </div>

            {{-- Midtrans Payment --}}
            <div class="methods-card">
                <div class="method-category">
                    <div class="category-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                        Pilih Metode Pembayaran
                    </div>
                    <div class="method-grid method-grid--wallet">
                        <button type="button" class="method-option selected" data-key="qris" data-name="QRIS" data-title="QRIS via Midtrans" data-badge="QR" data-badge-class="payment-badge--qris" data-preview-title="QRIS Dinamis" data-description="Snap Midtrans akan membuka kode QRIS yang bisa dibayar lewat aplikasi bank atau e-wallet pendukung QRIS." data-button="Bayar QRIS via Midtrans" data-ui-mode="qr">
                            <div class="option-left">
                                <span class="payment-badge payment-badge--qris">QR</span>
                                <span class="option-copy">
                                    <span class="option-name">QRIS</span>
                                    <span class="option-note">Scan dari bank atau e-wallet</span>
                                </span>
                            </div>
                            <div class="radio-circle"></div>
                        </button>
                        <button type="button" class="method-option" data-key="dana" data-name="DANA" data-title="DANA via Midtrans" data-badge="DANA" data-badge-class="payment-badge--dana" data-preview-title="DANA Direct Debit" data-description="Midtrans akan mengarahkan pelanggan ke halaman pembayaran DANA jika credential Snap-BI sudah dikonfigurasi." data-button="Bayar DANA via Midtrans" data-ui-mode="auto">
                            <div class="option-left">
                                <span class="payment-badge payment-badge--dana">DANA</span>
                                <span class="option-copy">
                                    <span class="option-name">DANA</span>
                                    <span class="option-note">Redirect ke pembayaran DANA</span>
                                </span>
                            </div>
                            <div class="radio-circle"></div>
                        </button>
                        <button type="button" class="method-option" data-key="gopay" data-name="GoPay" data-title="GoPay via Midtrans" data-badge="GP" data-badge-class="payment-badge--gopay" data-preview-title="GoPay Deeplink" data-description="Snap Midtrans akan mencoba membuka aplikasi GoPay/Gojek langsung di perangkat mobile." data-button="Bayar GoPay via Midtrans" data-ui-mode="deeplink">
                            <div class="option-left">
                                <span class="payment-badge payment-badge--gopay">GP</span>
                                <span class="option-copy">
                                    <span class="option-name">GoPay</span>
                                    <span class="option-note">Deeplink atau scan QR</span>
                                </span>
                            </div>
                            <div class="radio-circle"></div>
                        </button>
                        <div class="method-helper">
                            QRIS dan GoPay diproses lewat Midtrans Snap. DANA redirect memakai Snap-BI Direct Debit jika credential tersedia.
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="konfirmasi-footer">
                    <a href="{{ route('user.meja') }}" class="cancel-link" id="cancel-btn">Cancel</a>
                    <button class="pay-btn" id="main-pay-btn">Bayar QRIS via Midtrans</button>
                </div>
            </div>
        </div>
    </div>

    {{-- PAYMENT SUCCESS MODAL --}}
    <div class="success-overlay" id="success-overlay">
        <div class="success-modal">
            <div class="success-icon-wrap">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
            </div>

            <h2 class="success-title">Pembayaran Berhasil!</h2>
            <p class="success-sub">Transaksi Anda telah berhasil diproses oleh Midtrans</p>

            <div class="receipt-card">
                <div class="receipt-row">
                    <span class="receipt-label">TOTAL PEMBAYARAN</span>
                    <span class="receipt-value cyan" id="modal-total-value">Rp 205.000</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">METODE PEMBAYARAN</span>
                    <span class="receipt-value white" id="final-method-name">QRIS</span>
                </div>
            </div>

            <p class="success-note">
                Terima kasih! Sesi meja Anda telah dikonfirmasi. Silakan tunjukkan struk digital ini ke kasir saat tiba di lokasi.
            </p>

            <div class="success-actions">
                <a href="{{ route('user.meja') }}" class="btn-kembali">Kembali</a>
                <button class="btn-download">Download Struk</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const methodOptions = document.querySelectorAll('.method-option');
            const methodTitle = document.getElementById('payment-method-title');
            const methodContent = document.getElementById('payment-method-content');

            // Dynamic Data Binding from localStorage
            const orderDataRaw = localStorage.getItem('meja_order');
            let currentOrderData = null;
            if(orderDataRaw) {
                const orderData = JSON.parse(orderDataRaw);
                currentOrderData = orderData;

                // Multi Table Info Render
                const tablesContainer = document.getElementById('konfirmasi-tables-container');
                if (orderData.tables && orderData.tables.length > 0) {
                    tablesContainer.innerHTML = '';
                    orderData.tables.forEach(table => {
                        const tableHtml = `
                            <div class="table-info-card">
                                <img src="${table.image}" class="table-img">
                                <div class="table-text">
                                    <div class="table-name">${table.name} <span class="table-capacity">${table.capacity} Orang</span></div>
                                    <div class="table-type">${table.type === 'vip' ? 'VIP' : 'Regular'}</div>
                                </div>
                            </div>
                        `;
                        tablesContainer.insertAdjacentHTML('beforeend', tableHtml);
                    });
                }

                // Dates & times
                document.getElementById('konfirmasi-date').innerText = orderData.date;
                document.getElementById('konfirmasi-time').innerText = orderData.time;
                document.getElementById('konfirmasi-duration').innerText = orderData.duration;

                // Pricing
                document.getElementById('konfirmasi-subtotal').innerText = orderData.subtotal;
                document.getElementById('konfirmasi-tax').innerText = orderData.tax;
                document.getElementById('konfirmasi-total').innerText = orderData.total;
                document.getElementById('modal-total-value').innerText = orderData.total;
            }

            const mainPayBtn = document.getElementById('main-pay-btn');
            const paymentStatusText = document.getElementById('payment-status-text');
            const paymentStatusSub = document.getElementById('payment-status-sub');
            const noMethodText = document.getElementById('no-method-selected');
            const cancelBtn = document.getElementById('cancel-btn');
            const bookingPaymentStatusUrlTemplate = '{{ route("booking.payment-status", ["orderId" => "__ORDER_ID__"]) }}';
            let bookingStatusPollInterval = null;
            let bookingStatusPollTimeout = null;

            methodOptions.forEach(option => {
                option.addEventListener('click', () => {
                    methodOptions.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                    updatePaymentView(option);
                    setPayButtonIdle();
                });
            });

            function getSelectedMethodOption() {
                return document.querySelector('.method-option.selected') || methodOptions[0];
            }

            function updatePaymentView(option) {
                methodTitle.innerText = option.dataset.title || 'Pembayaran Midtrans';
                methodContent.innerHTML = `
                    <div class="payment-method-preview">
                        <div class="payment-badge ${option.dataset.badgeClass || 'payment-badge--qris'}">${option.dataset.badge || 'QR'}</div>
                        <div>
                            <div class="payment-method-preview__title">${option.dataset.previewTitle || option.dataset.name || 'Midtrans'}</div>
                            <p class="payment-method-preview__text">${option.dataset.description || 'Pembayaran akan diproses lewat Midtrans Snap.'}</p>
                        </div>
                    </div>
                `;
            }

            function setPayButtonIdle() {
                const selectedOption = getSelectedMethodOption();
                mainPayBtn.innerText = selectedOption?.dataset.button || 'Bayar dengan Midtrans';
                mainPayBtn.style.pointerEvents = 'auto';
            }

            function formatPaymentMethod(paymentType, fallbackName) {
                const normalized = String(paymentType || '').toLowerCase();
                const labels = {
                    qris: 'QRIS',
                    dana: 'DANA',
                    gopay: 'GoPay',
                    shopeepay: 'ShopeePay',
                    bank_transfer: 'Bank Transfer',
                    credit_card: 'Kartu Kredit'
                };

                return labels[normalized] || fallbackName || 'Midtrans';
            }

            function saveBookingHistory(orderData, paymentMethod, status, orderId = null) {
                const historyData = JSON.parse(localStorage.getItem('billiard_history') || '[]');
                const entryId = orderId || 'NEW-' + Math.floor(Math.random() * 1000);
                const existingIndex = historyData.findIndex(item => item.id === entryId);
                const newEntry = {
                    id: entryId,
                    customer_name: '{{ Auth::user()->name }}',
                    tables: orderData.tables.map(t => t.name).join(', '),
                    date: orderData.date,
                    time: orderData.time.split(' - ')[0],
                    duration: orderData.duration,
                    total: orderData.total,
                    status,
                    payment_method: paymentMethod,
                    timestamp: new Date().getTime()
                };

                if (existingIndex >= 0) {
                    historyData[existingIndex] = newEntry;
                } else {
                    historyData.unshift(newEntry);
                }

                localStorage.setItem('billiard_history', JSON.stringify(historyData));
            }

            function clearBookingPaymentPolling() {
                if (bookingStatusPollInterval) {
                    clearInterval(bookingStatusPollInterval);
                    bookingStatusPollInterval = null;
                }

                if (bookingStatusPollTimeout) {
                    clearTimeout(bookingStatusPollTimeout);
                    bookingStatusPollTimeout = null;
                }
            }

            function pollBookingPaymentStatus(orderId, latestOrderData, fallbackMethod = 'DANA') {
                if (!orderId || !latestOrderData) return;

                localStorage.setItem('meja_pending_order_id', orderId);
                clearBookingPaymentPolling();

                const checkStatus = function () {
                    const statusUrl = bookingPaymentStatusUrlTemplate.replace('__ORDER_ID__', encodeURIComponent(orderId));

                    fetch(statusUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Gagal memeriksa status pembayaran.');
                        }

                        return res.json();
                    })
                    .then(data => {
                        if (data.status === 'paid') {
                            const paymentMethod = data.payment_method || fallbackMethod || 'DANA';
                            document.getElementById('final-method-name').innerText = paymentMethod;
                            saveBookingHistory(latestOrderData, paymentMethod, 'paid', orderId);
                            localStorage.removeItem('meja_pending_order_id');
                            clearBookingPaymentPolling();
                            showSuccessModal();
                        } else if (['cancelled', 'failed', 'expired'].includes(data.status)) {
                            localStorage.removeItem('meja_pending_order_id');
                            clearBookingPaymentPolling();
                            setPayButtonIdle();
                            alert('Pembayaran tidak berhasil atau sudah berakhir.');
                        } else {
                            paymentStatusText.innerText = 'Status Pembayaran : MENUNGGU';
                            paymentStatusSub.innerText = 'Menunggu konfirmasi pembayaran dari Midtrans';
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
                };

                checkStatus();
                bookingStatusPollInterval = setInterval(checkStatus, 3000);
                bookingStatusPollTimeout = setTimeout(clearBookingPaymentPolling, 25 * 60 * 1000);
            }

            function showSuccessModal() {
                mainPayBtn.innerText = 'Pembayaran Selesai';
                mainPayBtn.style.background = '#00f2ff';
                mainPayBtn.style.pointerEvents = 'none';

                paymentStatusText.innerHTML = 'Status Pembayaran : <span style="color: #00f2ff;">BERHASIL</span>';
                paymentStatusSub.innerText = 'Pesanan terkonfirmasi';

                hours = 0; minutes = 0; seconds = 0;
                updateTimer();

                document.querySelectorAll('.detail-value, .total-value').forEach(el => el.innerText = '-');
                document.querySelector('.total-value').innerText = 'Rp 0';
                document.querySelectorAll('.table-name').forEach(el => el.innerHTML = '- <span class="table-capacity">-</span>');
                document.querySelectorAll('.table-type').forEach(el => el.innerText = '-');

                methodContent.style.display = 'none';
                noMethodText.style.display = 'block';
                cancelBtn.style.display = 'none';

                setTimeout(() => {
                    document.getElementById('success-overlay').classList.add('active');
                }, 800);
            }

            // Simulate Failed/Cancelled Transaction for testing
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const orderDataRaw = localStorage.getItem('meja_order');
                if (orderDataRaw) {
                    const orderData = JSON.parse(orderDataRaw);
                    const historyData = JSON.parse(localStorage.getItem('billiard_history') || '[]');
                    const selectedMethod = document.querySelector('.method-option.selected')?.dataset.name || 'QRIS';

                    const failedEntry = {
                        id: 'NEW-' + Math.floor(Math.random() * 1000),
                        customer_name: '{{ Auth::user()->name }}',
                        tables: orderData.tables.map(t => t.name).join(', '),
                        date: orderData.date,
                        time: orderData.time.split(' - ')[0],
                        duration: orderData.duration,
                        total: orderData.total,
                        status: 'failed', // Mark as failed
                        payment_method: selectedMethod,
                        timestamp: new Date().getTime()
                    };

                    historyData.unshift(failedEntry);
                    localStorage.setItem('billiard_history', JSON.stringify(historyData));
                }

                window.location.href = "{{ route('user.meja') }}";
            });

            mainPayBtn.addEventListener('click', function() {
                const selectedOption = getSelectedMethodOption();
                const selectedMethod = selectedOption?.dataset.name || 'QRIS';
                const selectedMethodKey = selectedOption?.dataset.key || 'qris';
                const selectedUiMode = selectedOption?.dataset.uiMode || 'auto';
                document.getElementById('final-method-name').innerText = selectedMethod;

                if (selectedMethodKey !== 'dana' && !window.snap) {
                    alert("Midtrans Snap belum berhasil dimuat. Cek koneksi internet atau client key Midtrans.");
                    return;
                }

                // Save to server via AJAX
                const orderDataRaw = localStorage.getItem('meja_order');
                if (orderDataRaw) {
                    const orderData = JSON.parse(orderDataRaw);
                    
                    const formattedDate = orderData.date_iso;
                    if (!formattedDate) {
                        alert("Data tanggal tidak valid. Silakan pilih ulang tanggal reservasi.");
                        window.location.href = "{{ route('user.meja') }}";
                        return;
                    }

                    // Parse times
                    const timeParts = orderData.time.split(' - ');
                    const startTime = timeParts[0];
                    const endTime = timeParts[1];
                    if (!startTime || !endTime || orderData.time === '-') {
                        alert("Data jam tidak valid. Silakan pilih ulang jam reservasi.");
                        window.location.href = "{{ route('user.meja') }}";
                        return;
                    }

                    // Clean total price (remove Rp and dots)
                    const cleanTotal = parseInt(orderData.total.replace(/[^0-9]/g, ''));

                    const payload = {
                        table_ids: orderData.tables.map(t => t.id),
                        customer_name: '{{ Auth::user()->name }}',
                        phone: '{{ Auth::user()->phone ?? "" }}',
                        booking_date: formattedDate,
                        start_time: startTime,
                        end_time: endTime,
                        total_price: cleanTotal,
                        payment_method: selectedMethodKey,
                        _token: '{{ csrf_token() }}'
                    };

                    mainPayBtn.innerText = 'Memproses...';
                    mainPayBtn.style.pointerEvents = 'none';

                    fetch('{{ route("booking.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json().then(data => ({ ok: response.ok, data })))
                    .then(({ ok, data }) => {
                        if (!ok) {
                            const errors = data.errors ? Object.values(data.errors).flat().join('\n') : null;
                            throw new Error(errors || data.message || 'Reservasi tidak valid.');
                        }

                        if (data.redirect_url) {
                            localStorage.setItem('meja_pending_order_id', data.order_id);
                            saveBookingHistory(orderData, selectedMethod, 'pending', data.order_id);
                            paymentStatusText.innerText = 'Mengalihkan ke DANA';
                            paymentStatusSub.innerText = 'Selesaikan pembayaran di halaman DANA';
                            window.location.href = data.redirect_url;
                            return;
                        }

                        if (!data.snap_token) {
                            throw new Error(data.message || 'Snap token Midtrans tidak tersedia.');
                        }

                        window.snap.pay(data.snap_token, {
                            uiMode: selectedUiMode,
                            onSuccess: function(result){
                                const paymentMethod = formatPaymentMethod(result.payment_type, selectedMethod);
                                document.getElementById('final-method-name').innerText = paymentMethod;

                                saveBookingHistory(orderData, paymentMethod, 'paid', result.order_id);

                                showSuccessModal();
                            },
                            onPending: function(result){
                                document.getElementById('final-method-name').innerText = formatPaymentMethod(result.payment_type, selectedMethod);
                                setPayButtonIdle();
                                alert("Menunggu pembayaran Anda!"); console.log(result);
                            },
                            onError: function(result){
                                setPayButtonIdle();
                                alert("Pembayaran gagal!"); console.log(result);
                            },
                            onClose: function(){
                                setPayButtonIdle();
                                alert("Anda menutup popup pembayaran sebelum menyelesaikan pembayaran");
                            }
                        });
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        setPayButtonIdle();
                        alert(error.message || "Terjadi kesalahan sistem saat memproses pesanan.");
                    });
                }
            });

            // DOWNLOAD STRUK LOGIC
            const downloadBtn = document.querySelector('.btn-download');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', function() {
                    const modal = document.querySelector('.success-modal');
                    
                    // Style adjustments for capture (optional, e.g. hiding buttons)
                    const actions = document.querySelector('.success-actions');
                    actions.style.display = 'none';

                    html2canvas(modal, {
                        backgroundColor: '#111317',
                        scale: 2, // Higher quality
                        logging: false,
                        useCORS: true
                    }).then(canvas => {
                        // Restore buttons
                        actions.style.display = 'flex';

                        // Trigger Download
                        const link = document.createElement('a');
                        link.download = `Struk_Billiard_${new Date().getTime()}.png`;
                        link.href = canvas.toDataURL('image/png');
                        link.click();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Struk berhasil diunduh ke perangkat Anda.',
                            background: '#141418',
                            color: '#fff',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                });
            }

            // Demo Countdown Logic
            let hours = 0;
            let minutes = 22;
            let seconds = 30;

            const timeBoxes = document.querySelectorAll('.time-box');
            let timerInterval = null;

            function updateTimer() {
                hours = Math.max(0, hours);
                minutes = Math.max(0, minutes);
                seconds = Math.max(0, seconds);

                if (hours === 0 && minutes === 0 && seconds === 0) {
                    if (timerInterval) clearInterval(timerInterval);
                    renderTimerBoxes();
                    return;
                }

                if (seconds > 0) {
                    seconds--;
                } else if (minutes > 0) {
                    minutes--;
                    seconds = 59;
                } else if (hours > 0) {
                    hours--;
                    minutes = 59;
                    seconds = 59;
                }

                renderTimerBoxes();
            }

            function renderTimerBoxes() {
                if (timeBoxes.length >= 3) {
                    timeBoxes[0].innerText = hours;
                    timeBoxes[1].innerText = minutes;
                    timeBoxes[2].innerText = seconds;
                }
            }

            timerInterval = setInterval(updateTimer, 1000);

            const pendingBookingOrderId = localStorage.getItem('meja_pending_order_id');
            if (pendingBookingOrderId && currentOrderData) {
                paymentStatusText.innerText = 'Memeriksa pembayaran DANA';
                paymentStatusSub.innerText = 'Menunggu konfirmasi pembayaran dari Midtrans';
                pollBookingPaymentStatus(pendingBookingOrderId, currentOrderData, 'DANA');
            }
        });
    </script>
@endpush
