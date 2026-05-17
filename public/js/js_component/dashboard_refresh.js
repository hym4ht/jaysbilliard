/**
 * Simple Real-time Timer Updates
 * Update timers locally every second without server calls
 */

(function() {
    'use strict';

    let timerInterval = null;

    /**
     * Parse time string HH:MM:SS to seconds
     */
    function timeToSeconds(timeStr) {
        const parts = timeStr.split(':').map(Number);
        return parts[0] * 3600 + parts[1] * 60 + parts[2];
    }

    /**
     * Format seconds to HH:MM:SS
     */
    function secondsToTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    /**
     * Update all timers on the page
     */
    function updateTimers() {
        // Find all active table cards (terisi status)
        const activeCards = document.querySelectorAll('.adm-meja--terisi');
        
        activeCards.forEach(card => {
            const timerDisplays = card.querySelectorAll('.adm-timer-display');
            if (timerDisplays.length !== 2) return;

            const elapsedDisplay = timerDisplays[0];
            const remainingDisplay = timerDisplays[1];

            // Update elapsed time (increment)
            let elapsedSeconds = timeToSeconds(elapsedDisplay.textContent);
            elapsedSeconds++;
            elapsedDisplay.textContent = secondsToTime(elapsedSeconds);

            // Update remaining time (decrement)
            let remainingSeconds = timeToSeconds(remainingDisplay.textContent);
            if (remainingSeconds > 0) {
                remainingSeconds--;
                remainingDisplay.textContent = secondsToTime(remainingSeconds);

                // Add warning color if less than 5 minutes
                if (remainingSeconds <= 300) {
                    remainingDisplay.style.color = '#ff4444';
                    remainingDisplay.style.fontWeight = 'bold';
                } else {
                    remainingDisplay.style.color = '';
                    remainingDisplay.style.fontWeight = '';
                }

                // Flash when time is almost up (last minute)
                if (remainingSeconds <= 60 && remainingSeconds % 2 === 0) {
                    remainingDisplay.style.opacity = '0.5';
                    setTimeout(() => {
                        remainingDisplay.style.opacity = '1';
                    }, 500);
                }
            } else {
                remainingDisplay.textContent = '00:00:00';
                remainingDisplay.style.color = '#ff4444';
                remainingDisplay.style.fontWeight = 'bold';
            }
        });
    }

    /**
     * Refresh page data every 30 seconds to sync with server
     */
    function schedulePageRefresh() {
        setTimeout(() => {
            window.location.reload();
        }, 30000); // 30 seconds
    }

    /**
     * Initialize
     */
    function init() {
        // Only run on admin dashboard page
        if (!window.location.pathname.includes('/admin-dashboard')) {
            return;
        }

        // Start timer updates every second
        timerInterval = setInterval(updateTimers, 1000);

        // Schedule page refresh every 30 seconds to sync with server
        schedulePageRefresh();

        // Stop timers when page is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
            } else {
                if (!timerInterval) {
                    timerInterval = setInterval(updateTimers, 1000);
                }
            }
        });

        console.log('✅ Real-time timers active - Page refreshes every 30s');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
