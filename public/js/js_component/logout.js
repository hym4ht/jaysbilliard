/**
 * Konfirmasi Logout Premium
 */
window.confirmLogout = function(event, form) {
    if (event) event.preventDefault();
    
    const modal = document.getElementById('logoutModal');
    const confirmBtn = document.getElementById('confirmLogoutSubmit');
    const cancelBtn = document.getElementById('cancelLogout');
    
    if (!modal) return;

    // Show modal
    modal.classList.add('active');

    // Handle Confirm
    const handleConfirm = () => {
        if (form) form.submit();
        modal.classList.remove('active');
        cleanup();
    };

    // Handle Cancel
    const handleCancel = () => {
        modal.classList.remove('active');
        cleanup();
    };

    // Cleanup listeners
    const cleanup = () => {
        confirmBtn.removeEventListener('click', handleConfirm);
        cancelBtn.removeEventListener('click', handleCancel);
        modal.removeEventListener('click', handleOutsideClick);
    };

    // Handle Outside Click
    const handleOutsideClick = (e) => {
        if (e.target === modal) handleCancel();
    };

    confirmBtn.addEventListener('click', handleConfirm);
    cancelBtn.addEventListener('click', handleCancel);
    modal.addEventListener('click', handleOutsideClick);
}
