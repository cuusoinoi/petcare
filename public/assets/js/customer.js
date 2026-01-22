// Customer Frontend JavaScript

// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.querySelector('.nav-menu');

    if (navToggle) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
            navMenu.classList.remove('active');
        }
    });

    // User dropdown menu - giữ menu mở khi di chuột
    const userDropdown = document.querySelector('.nav-user-dropdown');
    const userMenu = document.querySelector('.nav-user-menu');
    let hideTimeout;

    if (userDropdown && userMenu) {
        userDropdown.addEventListener('mouseenter', function() {
            clearTimeout(hideTimeout);
            userMenu.style.display = 'block';
        });

        userDropdown.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(function() {
                userMenu.style.display = 'none';
            }, 200); // Delay 200ms trước khi ẩn
        });
    }
});

// Form Validation
function validatePhone(phone) {
    const phoneRegex = /^[0-9]{10,11}$/;
    return phoneRegex.test(phone);
}

// OTP Input Auto-focus
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.focus();
    }
    
    // Debug form submission
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            console.log('Form submitting...');
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
        });
    }
});
