/**
 * Konfirmasi Logout
 */
(function () {
    let pendingLogoutForm = null;
    let initialized = false;

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');

        if (modal) {
            modal.classList.remove('active');
        }

        pendingLogoutForm = null;
    }

    window.confirmLogout = function (event, form) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        const modal = document.getElementById('logoutModal');

        if (!modal || !form) {
            return;
        }

        pendingLogoutForm = form;
        modal.classList.add('active');
    };

    function initLogoutModal() {
        if (initialized) {
            return;
        }

        const modal = document.getElementById('logoutModal');
        const confirmBtn = document.getElementById('confirmLogoutSubmit');
        const cancelBtn = document.getElementById('cancelLogout');

        if (!modal || !confirmBtn || !cancelBtn) {
            return;
        }

        initialized = true;

        confirmBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const form = pendingLogoutForm;
            closeLogoutModal();

            if (form) {
                form.submit();
            }
        });

        cancelBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            closeLogoutModal();
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeLogoutModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeLogoutModal();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initLogoutModal);
})();
