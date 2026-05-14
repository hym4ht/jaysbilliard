// Mobile Menu Toggle for Admin Dashboard
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.adm-sidebar');
    const mobileOverlay = document.createElement('div');
    mobileOverlay.className = 'mobile-overlay';
    document.body.appendChild(mobileOverlay);

    // Create mobile menu button if it doesn't exist
    const topbar = document.querySelector('.adm-topbar');
    if (topbar && window.innerWidth <= 768) {
        const topbarLeft = topbar.querySelector('.adm-topbar-left') || document.createElement('div');
        if (!topbar.querySelector('.adm-topbar-left')) {
            topbarLeft.className = 'adm-topbar-left';
            const topbarTitle = topbar.querySelector('div');
            topbar.insertBefore(topbarLeft, topbarTitle);
        }

        if (!topbar.querySelector('.mobile-menu-btn')) {
            const menuBtn = document.createElement('button');
            menuBtn.className = 'mobile-menu-btn';
            menuBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            `;
            topbarLeft.appendChild(menuBtn);

            // Toggle sidebar on mobile
            menuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
                mobileOverlay.classList.toggle('active');
            });
        }
    }

    // Close sidebar when clicking overlay
    mobileOverlay.addEventListener('click', function() {
        sidebar.classList.remove('mobile-open');
        mobileOverlay.classList.remove('active');
    });

    // Close sidebar when clicking a nav link on mobile
    const navLinks = document.querySelectorAll('.adm-nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            }
        });
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            }
        }, 250);
    });
});
