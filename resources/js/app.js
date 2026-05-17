import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('[data-website-menu-toggle]');
    const mobileMenu = document.querySelector('[data-website-mobile-menu]');

    if (!menuToggle || !mobileMenu) {
        return;
    }

    const openIcon = menuToggle.querySelector('[data-menu-icon-open]');
    const closeIcon = menuToggle.querySelector('[data-menu-icon-close]');
    const desktopMedia = window.matchMedia('(min-width: 768px)');

    const setMobileMenuOpen = (isOpen) => {
        mobileMenu.classList.toggle('hidden', !isOpen);
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        document.body.classList.toggle('mobile-menu-open', isOpen);
        openIcon?.classList.toggle('hidden', isOpen);
        closeIcon?.classList.toggle('hidden', !isOpen);
    };

    menuToggle.addEventListener('click', () => {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
        setMobileMenuOpen(!isOpen);
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setMobileMenuOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMobileMenuOpen(false);
        }
    });

    const closeOnDesktop = (event) => {
        if (event.matches) {
            setMobileMenuOpen(false);
        }
    };

    if (desktopMedia.addEventListener) {
        desktopMedia.addEventListener('change', closeOnDesktop);
    } else {
        desktopMedia.addListener(closeOnDesktop);
    }
});
