// Website navbar mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('[data-website-menu-toggle]');
    const mobileMenu = document.querySelector('[data-website-mobile-menu]');
    const menuIconOpen = document.querySelector('[data-menu-icon-open]');
    const menuIconClose = document.querySelector('[data-menu-icon-close]');

    if (!menuToggle || !mobileMenu) {
        return;
    }

    // Toggle mobile menu
    menuToggle.addEventListener('click', function() {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        
        // Toggle menu visibility
        mobileMenu.classList.toggle('hidden');
        
        // Toggle icons
        if (menuIconOpen && menuIconClose) {
            menuIconOpen.classList.toggle('hidden');
            menuIconClose.classList.toggle('hidden');
        }
        
        // Update aria-expanded
        menuToggle.setAttribute('aria-expanded', !isExpanded);
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = menuToggle.contains(event.target) || mobileMenu.contains(event.target);
        
        if (!isClickInside && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            if (menuIconOpen && menuIconClose) {
                menuIconOpen.classList.remove('hidden');
                menuIconClose.classList.add('hidden');
            }
            menuToggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Close menu on window resize to desktop size
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.add('hidden');
                if (menuIconOpen && menuIconClose) {
                    menuIconOpen.classList.remove('hidden');
                    menuIconClose.classList.add('hidden');
                }
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        }, 250);
    });
});
