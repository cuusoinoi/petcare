// Sidebar Elements
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const toggleBtn = document.getElementById("toggleSidebar");

const isMobileTablet = () => window.innerWidth <= 1023;

// Close all submenus
function closeAllSubmenus(except = null) {
    document.querySelectorAll(".sidebar__item--open").forEach((el) => {
        if (el !== except) el.classList.remove("sidebar__item--open");
    });
    document.querySelectorAll(".sidebar__submenu-popup").forEach((p) => (p.style.display = "none"));
}

// Toggle Sidebar
if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
        if (isMobileTablet()) {
            sidebar.classList.toggle("sidebar--open");
            overlay.classList.toggle("overlay--active");
        } else {
            sidebar.classList.toggle("sidebar--collapsed");
        }
    });
}

// Overlay click to close sidebar on mobile
if (overlay) {
    overlay.addEventListener("click", () => {
        sidebar.classList.remove("sidebar--open");
        overlay.classList.remove("overlay--active");
    });
}

// Submenu toggle
document.querySelectorAll(".sidebar__item--has-submenu").forEach((item) => {
    const link = item.querySelector(".sidebar__link");
    const submenu = item.querySelector(".sidebar__submenu");
    const popup = item.querySelector(".sidebar__submenu-popup");

    // Click handler for accordion (expanded mode & mobile)
    if (link) {
        link.addEventListener("click", (e) => {
            e.stopPropagation();
            const collapsed = sidebar.classList.contains("sidebar--collapsed");

            if (isMobileTablet()) {
                // Accordion behavior on mobile
                if (item.classList.contains("sidebar__item--open")) {
                    item.classList.remove("sidebar__item--open");
                } else {
                    closeAllSubmenus(item);
                    item.classList.add("sidebar__item--open");
                }
            } else if (!collapsed) {
                // Normal accordion desktop (only when expanded)
                if (item.classList.contains("sidebar__item--open")) {
                    item.classList.remove("sidebar__item--open");
                } else {
                    closeAllSubmenus(item);
                    item.classList.add("sidebar__item--open");
                }
            }
            // When collapsed, hover handles the popup - click does nothing
        });
    }

    // Hover handler for popup submenu (collapsed mode only)
    item.addEventListener("mouseenter", () => {
        const collapsed = sidebar.classList.contains("sidebar--collapsed");
        if (collapsed && popup && !isMobileTablet()) {
            closeAllSubmenus(item);
            const rect = item.getBoundingClientRect();
            popup.innerHTML = submenu.innerHTML;
            popup.style.top = `${rect.top}px`;
            popup.style.display = "flex";
        }
    });

    item.addEventListener("mouseleave", (e) => {
        const collapsed = sidebar.classList.contains("sidebar--collapsed");
        if (collapsed && popup && !isMobileTablet()) {
            // Check if mouse moved to popup
            const relatedTarget = e.relatedTarget;
            if (!popup.contains(relatedTarget)) {
                popup.style.display = "none";
            }
        }
    });

    // Keep popup open when hovering over it
    if (popup) {
        popup.addEventListener("mouseenter", () => {
            popup.style.display = "flex";
        });

        popup.addEventListener("mouseleave", () => {
            popup.style.display = "none";
        });
    }
});

// Close submenus when clicking outside
document.addEventListener("click", (e) => {
    if (!e.target.closest('.sidebar__item--has-submenu')) {
        closeAllSubmenus();
    }
});

// Theme Toggle
document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    const icon = themeToggle?.querySelector('i');
    const themeText = document.getElementById('themeToggleText');
    const savedTheme = localStorage.getItem('theme');

    // Function to update theme text and icon
    function updateThemeDisplay(isDark) {
        if (icon) {
            icon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
        }
        if (themeText) {
            themeText.textContent = isDark ? 'Giao diện sáng' : 'Giao diện tối';
        }
    }

    // Load saved theme
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        updateThemeDisplay(true);
    } else {
        updateThemeDisplay(false);
    }

    // Update chart theme on load
    window.addEventListener('load', () => {
        if (typeof updateChartTheme === 'function') updateChartTheme();
    });

    // Theme toggle click
    themeToggle?.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');

        updateThemeDisplay(isDark);

        if (typeof updateChartTheme === 'function') {
            updateChartTheme();
        }
    });
});

// Toast notifications from URL params
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');
    const success = params.get('success');
    const error = params.get('error');

    if (msg && (success || error)) {
        const toast = document.createElement('div');
        toast.className = 'toast ' + (success ? 'success' : 'error');
        toast.innerHTML = success ?
            `<i class="fas fa-check-circle"></i> ${msg}` :
            `<i class="fas fa-exclamation-circle"></i> ${msg}`;
        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);

        // Auto hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
