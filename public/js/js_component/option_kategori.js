document.addEventListener('DOMContentLoaded', function() {
    const dropdownWraps = document.querySelectorAll('.adm-kategori-dropdown-wrap');

    dropdownWraps.forEach(wrap => {
        const box = wrap.querySelector('.adm-kategori-select-box');
        const list = wrap.querySelector('.adm-kategori-dropdown-list');
        const input = wrap.querySelector('input[type="hidden"]');
        const displayText = wrap.querySelector('.selected-text');
        const items = wrap.querySelectorAll('.adm-kategori-item');

        if (!box) return;

        if (input && !input.value && displayText) {
            input.value = displayText.textContent.trim();
        }

        // Toggle open state
        box.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Close other dropdowns first (if any)
            dropdownWraps.forEach(other => {
                if (other !== wrap) other.classList.remove('open');
            });
            
            wrap.classList.toggle('open');
        });

        // Select item interaction
        items.forEach(item => {
            item.addEventListener('click', () => {
                const val = item.getAttribute('data-value');
                const text = item.textContent;

                // Update active state in UI
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                // Update display and hidden input value
                if (displayText) displayText.textContent = text;
                if (input) input.value = val;

                // Close dropdown after selection
                wrap.classList.remove('open');
            });
        });
    });

    // Close all custom dropdowns when clicking outside anywhere
    window.addEventListener('click', () => {
        dropdownWraps.forEach(wrap => wrap.classList.remove('open'));
    });
});
