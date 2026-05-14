{{-- Logout Confirmation Modal --}}
<div id="logoutModal" class="adm-modal-overlay">
    <div class="adm-modal-container">
        <div class="adm-modal-content">
            <div class="adm-logout-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="adm-modal-body">
                <h3 class="adm-modal-title">Konfirmasi Logout</h3>
                <p class="adm-modal-text">Apakah Anda yakin ingin keluar dari sistem? Anda perlu login kembali untuk mengakses dashboard.</p>
            </div>
        </div>
        <div class="adm-modal-footer">
            <button type="button" class="adm-modal-btn-cancel" id="cancelLogout">BATAL</button>
            <button type="button" class="adm-modal-btn-confirm" id="confirmLogoutSubmit">LOGOUT</button>
        </div>
    </div>
</div>
