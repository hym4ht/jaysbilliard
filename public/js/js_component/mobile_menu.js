// Mobile menu toggle for dashboard layouts.
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.adm-sidebar');
    const topbar = document.querySelector('.adm-topbar');

    if (!sidebar || !topbar) {
        return;
    }

    const mobileOverlay = document.createElement('div');
    mobileOverlay.className = 'mobile-overlay';
    document.body.appendChild(mobileOverlay);

    const topbarLeft = topbar.querySelector('.adm-topbar-left') || document.createElement('div');
    if (!topbar.querySelector('.adm-topbar-left')) {
        topbarLeft.className = 'adm-topbar-left';
        topbar.prepend(topbarLeft);
    }

    let menuBtn = topbar.querySelector('.mobile-menu-btn');
    if (!menuBtn) {
        menuBtn = document.createElement('button');
        menuBtn.type = 'button';
        menuBtn.className = 'mobile-menu-btn';
        menuBtn.setAttribute('aria-label', 'Buka menu');
        menuBtn.setAttribute('aria-expanded', 'false');
        menuBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        `;
        topbarLeft.insertBefore(menuBtn, topbarLeft.firstChild);
    }

    function closeMobileMenu() {
        sidebar.classList.remove('mobile-open');
        mobileOverlay.classList.remove('active');
        menuBtn.setAttribute('aria-expanded', 'false');
    }

    menuBtn.addEventListener('click', function() {
        const isOpen = sidebar.classList.toggle('mobile-open');
        mobileOverlay.classList.toggle('active', isOpen);
        menuBtn.setAttribute('aria-expanded', String(isOpen));
    });

    mobileOverlay.addEventListener('click', closeMobileMenu);

    document.querySelectorAll('.adm-nav-link').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeMobileMenu();
            }
        });
    });

    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768) {
                closeMobileMenu();
            }
        }, 250);
    });
});
