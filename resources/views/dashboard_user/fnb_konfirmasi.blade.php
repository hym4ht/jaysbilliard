@extends('layouts.dashboard')

@section('title', 'Konfirmasi Pembayaran Makanan & Minuman')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/css_page/konfirmasi_pembayaran.css') }}">
    <style>
        /* Specific Styles for FnB Items in Summary */
        .antarkan-title {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .antarkan-meja {
            color: #fff;
            font-weight: 800;
        }

        .items-list-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .fnb-item-preview {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .fnb-item-img {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
        }

        .fnb-item-info {
            flex: 1;
        }

        .fnb-item-name-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2px;
        }

        .fnb-item-name {
            font-size: 0.95rem;
            font-weight: 800;
            color: #fff;
        }

        .fnb-item-qty {
            font-size: 0.9rem;
            color: #fff;
            font-weight: 800;
        }

        .fnb-item-meta {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .fnb-item-price {
            font-size: 0.9rem;
            font-weight: 800;
            color: #fff;
        }

        .total-amount-block {
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .total-amount-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .total-amount-value {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--primary-cyan);
        }

        .payment-qr-title {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
    </style>
@endpush

@section('content')
<div class="konfirmasi-wrapper">
    {{-- Left Column: Order Summary --}}
    <div class="summary-container">
        <h3 class="summary-title">Ringkasan Pemesanan</h3>
        
        <div class="antarkan-title">
            Antarkan ke Meja <span class="antarkan-meja">Meja 01</span>
        </div>

        {{-- Items List --}}
        <div class="items-list-container" id="konfirmasi-items-list">
            {{-- Injeksi Dinamis dari JS --}}
        </div>

        <div class="price-breakdown">
            <div class="breakdown-row">
                <span>Subtotal</span>
                <span class="detail-value" id="konfirmasi-subtotal">Rp 0</span>
            </div>
            <div class="breakdown-row">
                <span>Service & Tax (10%)</span>
                <span class="detail-value" id="konfirmasi-tax">Rp 0</span>
            </div>
            
            <div class="total-amount-block">
                <div class="total-amount-label">Total Amount</div>
                <div class="total-amount-value" id="konfirmasi-total">Rp 0</div>
            </div>
        </div>

        <div class="payment-details-section" style="margin-top: 50px; padding-top: 30px; border-top: 1px dashed rgba(255,255,255,0.1);">
            <div class="detail-section-title" id="payment-method-title">Pembayaran</div>
            <div class="detail-section-content" id="payment-method-content">
                <div class="qr-container">
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=JaysBilliardFnbPayment" alt="QRIS" class="qr-image">
                    </div>
                </div>
            </div>
            <div id="no-method-selected" style="display: none; padding: 40px; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                Tidak ada metode pembayaran yang dipilih
            </div>
        </div>
    </div>

    {{-- Right Column: Timer & Payment Methods --}}
    <div class="payment-main-area">
        {{-- Timer Card --}}
        <div class="timer-card">
            <div class="timer-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="M20 12h2"></path><path d="M2 12h2"></path></svg>
            </div>
            <div class="timer-info" id="timer-status-wrapper">
                <div class="timer-label" id="payment-status-text">Batas Waktu Pembayaran</div>
                <div class="timer-sub" id="payment-status-sub">Selesaikan pembayaran sebelum waktu habis</div>
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

        {{-- Payment Methods Card --}}
        <div class="methods-card">
            <h3 class="methods-title">Metode Pembayaran</h3>

            <div class="payment-categories">
                {{-- Group: Instant --}}
                <div class="method-category">
                    <div class="category-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg>
                        Pembayaran Instan (QRIS)
                    </div>
                    <div class="method-grid">
                        <div class="method-option selected qris-option" data-type="qris" data-name="QRIS">
                            <div class="option-left">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="bank-logo" style="height: 25px; filter: brightness(0) invert(1);">
                                <span class="option-name">QRIS</span>
                            </div>
                            <div class="radio-circle"></div>
                        </div>
                    </div>
                </div>

                {{-- Group: VA --}}
                <div class="method-category">
                    <div class="category-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Rekening Virtual
                    </div>
                    <div class="method-grid">
                        <div class="method-option" data-type="va" data-name="BCA" data-number="88301{{ rand(1000000,9999999) }}">
                            <div class="option-left">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="bank-logo">
                                <span class="option-name">BCA (Bank Central Asia)</span>
                            </div>
                            <div class="radio-circle"></div>
                        </div>
                        <div class="method-option" data-type="va" data-name="Mandiri" data-number="90123{{ rand(1000000,9999999) }}">
                            <div class="option-left">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri" class="bank-logo">
                                <span class="option-name">VA Mandiri</span>
                            </div>
                            <div class="radio-circle"></div>
                        </div>
                        <div class="method-option" data-type="va" data-name="BRI" data-number="10293{{ rand(1000000,9999999) }}">
                            <div class="option-left">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI" class="bank-logo">
                                <span class="option-name">BRI (Bank Rakyat Indonesia)</span>
                            </div>
                            <div class="radio-circle"></div>
                        </div>
                        <div class="method-option" data-type="va" data-name="BNI" data-number="00987{{ rand(1000000,9999999) }}">
                            <div class="option-left">
                                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 55 30' width='55' height='30'><text x='2' y='22' font-family='Arial Black, sans-serif' font-weight='900' font-size='24' fill='%23006e62'>BNI</text></svg>" alt="BNI" class="bank-logo">
                                <span class="option-name">BNI (Bank Negara Indonesia)</span>
                            </div>
                            <div class="radio-circle"></div>
                        </div>
                    </div>
                </div>

                {{-- Group: Wallet --}}
                <div class="method-category">
                    <div class="category-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        Dompet Digital
                    </div>
                    <div class="method-grid">
                        <div class="method-option" data-type="wallet" data-name="Dana" data-number="08{{ rand(10,99) }} {{ rand(1000,9999) }} {{ rand(1000,9999) }}">
                            <div class="option-left">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" alt="Dana" class="bank-logo">
                                <span class="option-name">Dana</span>
                            </div>
                            <div class="radio-circle"></div>
                        </div>
                        <div class="method-option" data-type="wallet" data-name="OVO" data-number="08{{ rand(10,99) }} {{ rand(1000,9999) }} {{ rand(1000,9999) }}">
                            <div class="option-left">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" alt="OVO" class="bank-logo">
                                <span class="option-name">OVO</span>
                            </div>
                            <div class="radio-circle"></div>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="konfirmasi-footer">
                    <a href="{{ route('user.fnb') }}" class="cancel-link" id="cancel-btn">Cancel</a>
                    <button class="pay-btn" id="main-pay-btn">Konfirmasi Pembayaran</button>
                </div>
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
        <p class="success-sub">Transaksi Anda telah berhasil diproses</p>

        <div class="receipt-card">
            <div class="receipt-row">
                <span class="receipt-label">TOTAL PEMBAYARAN</span>
                <span class="receipt-value cyan" id="modal-total-value">Rp 0</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label">METODE PEMBAYARAN</span>
                <span class="receipt-value white" id="final-method-name">QRIS</span>
            </div>
        </div>

        <p class="success-note">
            Terima kasih! Pesanan Anda segera disiapkan dan akan diantar ke meja dalam kurun waktu 15 - 20 menit.
        </p>

        <div class="success-actions">
            <a href="{{ route('user.fnb') }}" class="btn-kembali">Kembali</a>
            <button class="btn-download">Download Struk</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const methodOptions = document.querySelectorAll('.method-option');
        const methodTitle = document.getElementById('payment-method-title');
        const methodContent = document.getElementById('payment-method-content');
        const mainPayBtn = document.getElementById('main-pay-btn');
        const paymentStatusText = document.getElementById('payment-status-text');
        const paymentStatusSub = document.getElementById('payment-status-sub');
        const noMethodText = document.getElementById('no-method-selected');
        const cancelBtn = document.getElementById('cancel-btn');

        // Dynamic Loading from localStorage
        const orderDataRaw = localStorage.getItem('fnb_order');
        if(orderDataRaw) {
            const orderData = JSON.parse(orderDataRaw);
            
            // Meja target
            document.querySelector('.antarkan-meja').innerText = orderData.tableName || '-';

            // Menus rendering
            const itemsList = document.getElementById('konfirmasi-items-list');
            itemsList.innerHTML = '';
            
            if(orderData.items && orderData.items.length > 0) {
                orderData.items.forEach(item => {
                    const priceFormatted = (item.price * item.quantity).toLocaleString('id-ID');
                    
                    const html = `
                        <div class="fnb-item-preview">
                            <img src="${item.image}" alt="${item.name}" class="fnb-item-img" onerror="this.src='/images/hero-bg.png'">
                            <div class="fnb-item-info">
                                <div class="fnb-item-name-wrap">
                                    <span class="fnb-item-name">${item.name}</span>
                                    <span class="fnb-item-qty">${item.quantity}x</span>
                                </div>
                                <div class="fnb-item-meta">${item.category}</div>
                            </div>
                            <div class="fnb-item-price">Rp ${priceFormatted}</div>
                        </div>
                    `;
                    itemsList.insertAdjacentHTML('beforeend', html);
                });
            }

            // Calculations
            document.getElementById('konfirmasi-subtotal').innerText = 'Rp ' + (orderData.subtotal || 0).toLocaleString('id-ID');
            document.getElementById('konfirmasi-tax').innerText = 'Rp ' + (orderData.tax || 0).toLocaleString('id-ID');
            document.getElementById('konfirmasi-total').innerText = 'Rp ' + (orderData.total || 0).toLocaleString('id-ID');
            document.getElementById('modal-total-value').innerText = 'Rp ' + (orderData.total || 0).toLocaleString('id-ID');
        }

        methodOptions.forEach(option => {
            option.addEventListener('click', () => {
                methodOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                const type = option.dataset.type;
                const name = option.dataset.name;
                const number = option.dataset.number;

                updatePaymentView(type, name, number);
            });
        });

        function updatePaymentView(type, name, number) {
            if (type === 'qris') {
                methodTitle.innerText = 'PEMBAYARAN QRIS';
                methodContent.innerHTML = `
                    <div class="qr-container">
                        <div class="qr-code">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=JaysBilliardFnbPayment" alt="QRIS" class="qr-image">
                        </div>
                    </div>
                `;
            } else if (type === 'va') {
                methodTitle.innerText = `VIRTUAL ACCOUNT ${name.toUpperCase()}`;
                methodContent.innerHTML = `
                    <div class="va-details" style="text-align: left; background: #1a1a20; padding: 25px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="font-size: 0.95rem; color: #8a8a98; margin-bottom: 8px;">Nomor Virtual Account</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div style="font-size: 1.8rem; font-weight: 800; color: #00f2ff; letter-spacing: 1px;">${number}</div>
                            <button onclick="copyToClipboard('${number}')" style="background: transparent; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; cursor: pointer; padding: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.2)';" onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255,255,255,0.1)';">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                        </div>
                        <div>
                            <div style="font-size: 0.95rem; color: #8a8a98; margin-bottom: 15px;">Petunjuk Transfer:</div>
                            <ul style="font-size: 0.95rem; font-weight: 500; color: #eaeaef; padding-left: 20px; margin: 0; line-height: 2.2;">
                                <li>Pilih Transfer > Virtual Account</li>
                                <li>Masukkan nomor di atas</li>
                                <li>Konfirmasi detail pembayaran</li>
                                <li>Selesaikan transaksi</li>
                            </ul>
                        </div>
                    </div>
                `;
            } else if (type === 'wallet') {
                methodTitle.innerText = `DOMPET DIGITAL ${name.toUpperCase()}`;
                methodContent.innerHTML = `
                    <div class="wallet-details" style="text-align: left; background: rgba(255,255,255,0.03); padding: 20px; border-radius: 15px; border: 1px solid var(--card-border);">
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 5px;">Nomor Telepon</div>
                        <div style="font-size: 1.2rem; font-weight: 800; color: var(--primary-cyan);">${number}</div>
                    </div>
                `;
            }
        }

        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersalin!',
                    text: 'Nomor VA berhasil disalin.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    background: '#141418',
                    color: '#fff'
                });
            });
        }

        mainPayBtn.addEventListener('click', function() {
            const selectedMethod = document.querySelector('.method-option.selected').dataset.name || 'QRIS';
            document.getElementById('final-method-name').innerText = selectedMethod;

            const orderDataRaw = localStorage.getItem('fnb_order');
            if (!orderDataRaw) {
                alert("Data pesanan tidak ditemukan.");
                return;
            }
            const orderData = JSON.parse(orderDataRaw);
            const cleanTotal = parseInt(orderData.total.replace(/[^0-9]/g, ''));

            fetch('{{ route("user.fnb.checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    total_price: cleanTotal,
                    items: orderData.items || []
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            saveFnbHistory(orderData, result.payment_type || selectedMethod);
                            showFnbSuccessModal();
                        },
                        onPending: function(result){
                            alert("Menunggu pembayaran Anda!");
                        },
                        onError: function(result){
                            alert("Pembayaran gagal!");
                        },
                        onClose: function(){
                            alert("Anda menutup popup pembayaran sebelum menyelesaikan pembayaran");
                        }
                    });
                } else {
                    saveFnbHistory(orderData, selectedMethod);
                    showFnbSuccessModal();
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan sistem.");
            });
        });

        function saveFnbHistory(orderData, paymentMethod) {
            const historyData = JSON.parse(localStorage.getItem('billiard_history') || '[]');
            const newEntry = {
                id: 'FNB-' + Math.floor(Math.random() * 1000),
                customer_name: '{{ Auth::user()->name }}',
                tables: orderData.tableName || 'Takeaway',
                date: new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }),
                time: new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }),
                duration: '-',
                total: 'Rp ' + (orderData.total || 0).toLocaleString('id-ID'),
                status: 'paid',
                payment_method: paymentMethod,
                fnb: orderData.items || [],
                timestamp: new Date().getTime()
            };
            historyData.unshift(newEntry);
            localStorage.setItem('billiard_history', JSON.stringify(historyData));
        }

        function showFnbSuccessModal() {

            mainPayBtn.innerText = 'Pembayaran Selesai';
            mainPayBtn.style.background = '#00f2ff';
            mainPayBtn.style.pointerEvents = 'none';
            
            paymentStatusText.innerHTML = 'Status Pembayaran : <span style="color: #00f2ff;">BERHASIL</span>';
            paymentStatusSub.innerText = 'tunggu 5 detik untuk merefresh';
            
            hours = 0; minutes = 0; seconds = 0;
            updateTimerBoxes();

            // Clear summary to match image
            document.querySelector('.antarkan-meja').innerText = '-';
            
            // For items, replace content with placeholders
            const items = document.querySelectorAll('.fnb-item-preview');
            items.forEach(item => {
                item.querySelector('.fnb-item-name').innerText = 'Nama Menu';
                item.querySelector('.fnb-item-qty').innerText = '-';
                item.querySelector('.fnb-item-meta').innerText = 'Kategori';
                item.querySelector('.fnb-item-price').innerText = '-';
            });

            document.querySelectorAll('.detail-value').forEach(el => el.innerText = '-');
            document.querySelector('.total-amount-value').innerText = '-';
            
            // Hide payment detail content and show "No method"
            methodContent.style.display = 'none';
            noMethodText.style.display = 'block';
            cancelBtn.style.display = 'none';

            setTimeout(() => {
                document.getElementById('success-overlay').classList.add('active');
            }, 800);
        }

        // DOWNLOAD STRUK LOGIC
        const downloadBtn = document.querySelector('.btn-download');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const modal = document.querySelector('.success-modal');
                
                // Sembunyikan tombol saat capture agar tidak ikut terfoto
                const actions = document.querySelector('.success-actions');
                actions.style.display = 'none';

                html2canvas(modal, {
                    backgroundColor: '#111317',
                    scale: 2, // Kualitas lebih tinggi
                    logging: false,
                    useCORS: true
                }).then(canvas => {
                    // Kembalikan tombol
                    actions.style.display = 'flex';

                    // Trigger Download
                    const link = document.createElement('a');
                    link.download = `Struk_FnB_${new Date().getTime()}.png`;
                    link.href = canvas.toDataURL('image/png');
                    link.click();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Struk berhasil diunduh.',
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

        function updateTimer() {
            if (seconds > 0) seconds--;
            else {
                if (minutes > 0) { minutes--; seconds = 59; }
                else if (hours > 0) { hours--; minutes = 59; seconds = 59; }
            }
            updateTimerBoxes();
        }

        function updateTimerBoxes() {
            if (timeBoxes.length >= 3) {
                timeBoxes[0].innerText = hours;
                timeBoxes[1].innerText = minutes;
                timeBoxes[2].innerText = seconds;
            }
        }

        setInterval(updateTimer, 1000);
    });
</script>
@endpush
