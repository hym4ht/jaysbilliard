document.addEventListener('DOMContentLoaded', () => {
    const chartFilterBtn = document.getElementById('chartFilterBtn');
    const chartFilterDropdown = document.getElementById('chartFilterDropdown');

    if (chartFilterBtn && chartFilterDropdown) {
        chartFilterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            chartFilterBtn.classList.toggle('active');
            chartFilterDropdown.classList.toggle('show');
        });

        // Close on outside click
        window.addEventListener('click', () => {
            chartFilterDropdown.classList.remove('show');
            chartFilterBtn.classList.remove('active');
        });

        // Prevent clicking inside the dropdown from closing it
        chartFilterDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Item selection (aesthetic only for now)
        const items = chartFilterDropdown.querySelectorAll('.adm-chart-dropdown-item');
        items.forEach(item => {
            item.addEventListener('click', () => {
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                
                // Update button text
                const btnText = chartFilterBtn.querySelector('span');
                if (btnText) btnText.textContent = item.textContent;
                
                // Close dropdown
                chartFilterDropdown.classList.remove('show');
                chartFilterBtn.classList.remove('active');
            });
        });
    }
});
