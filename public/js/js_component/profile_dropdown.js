document.addEventListener('DOMContentLoaded', () => {
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
            profileBtn.classList.toggle('active');
        });

        // Close on outside click
        window.addEventListener('click', () => {
            profileDropdown.classList.remove('show');
            profileBtn.classList.remove('active');
        });

        // Prevent dropdown click from closing itself
        profileDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
});
