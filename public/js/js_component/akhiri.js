// Modal confirmation logic for "Akhiri Sesi"
const confirmOverlay = document.getElementById('confirmOverlay'); 
const confirmModal = document.getElementById('confirmModal');
const confirmTable = document.getElementById('confirmTable'); 
const confirmCancel = document.getElementById('confirmCancel');
const confirmSubmit = document.getElementById('confirmSubmit'); 
let currentBookingId = null;

// Use event delegation for more reliable clicks (especially if cards refresh)
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.trigger-end-session');
    if (btn) {
        e.preventDefault();
        
        currentBookingId = btn.dataset.id;
        const tableName = btn.dataset.table;
        const duration = btn.dataset.duration;
        const elapsed = btn.dataset.elapsed;

        // Populate Modal
        if (confirmTable) confirmTable.textContent = tableName;
        
        // Match selectors for values based on the HTML structure
        const values = confirmModal.querySelectorAll('.confirm-value--cyan');
        if (values.length >= 2) {
            values[0].textContent = elapsed;  // First cyan value is LAMA WAKTU
            values[1].textContent = duration; // Second cyan value is TOTAL DURASI MAIN
        }

        confirmOverlay.classList.add('active'); 
        confirmModal.classList.add('active');
    }
});

function closeConfirm() { 
    confirmOverlay.classList.remove('active'); 
    confirmModal.classList.remove('active'); 
    currentBookingId = null; 
}

if (confirmCancel) confirmCancel.addEventListener('click', closeConfirm); 
if (confirmOverlay) confirmOverlay.addEventListener('click', closeConfirm);

if (confirmSubmit) {
    confirmSubmit.addEventListener('click', () => {
        if (!currentBookingId) return;
        
        // Submit the form associated with this booking
        const form = document.getElementById(`end-session-form-${currentBookingId}`);
        if (form) {
            form.submit();
        }
        
        closeConfirm();
    });
}
