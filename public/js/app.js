// Peepit - Main JavaScript

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// CSRF Token Helper
function getCsrfToken() {
    return document.querySelector('input[name="csrf_token"]')?.value || '';
}

// Fetch API Helper with CSRF
async function fetchWithCsrf(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': getCsrfToken()
        }
    };
    
    return fetch(url, { ...defaultOptions, ...options });
}

// Form Validation Helper
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });
    
    return isValid;
}

// Image Preview
function previewImage(input, targetId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(targetId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Price Calculator
function calculatePrice(quantity, sizeId) {
    // Properly encode URL parameters
    const params = new URLSearchParams({
        quantity: quantity,
        size: sizeId
    });
    
    fetch(`/api/calculate-price?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('unit-price').textContent = data.unit_price;
            document.getElementById('total-price').textContent = data.total_price;
        })
        .catch(error => console.error('Error calculating price:', error));
}

// Color Picker Integration
function initColorPicker(elementId) {
    const colorInput = document.getElementById(elementId);
    if (!colorInput) return;
    
    colorInput.addEventListener('change', function(e) {
        const previewDiv = document.getElementById(elementId + '-preview');
        if (previewDiv) {
            previewDiv.style.backgroundColor = e.target.value;
        }
    });
}

// Confirmation Dialog
function confirmAction(message) {
    return confirm(message);
}

// Show Loading Spinner
function showLoading(elementId = 'loading-spinner') {
    const spinner = document.getElementById(elementId);
    if (spinner) {
        spinner.style.display = 'block';
    }
}

function hideLoading(elementId = 'loading-spinner') {
    const spinner = document.getElementById(elementId);
    if (spinner) {
        spinner.style.display = 'none';
    }
}

// Tooltip
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(el => {
        el.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
        });
        
        el.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) tooltip.remove();
        });
    });
}

// Mobile Menu Toggle
function toggleMobileMenu() {
    const menu = document.querySelector('.navbar-menu');
    if (menu) {
        menu.classList.toggle('active');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
});

// Export for use in other scripts
window.Peepit = {
    getCsrfToken,
    fetchWithCsrf,
    validateForm,
    previewImage,
    calculatePrice,
    initColorPicker,
    confirmAction,
    showLoading,
    hideLoading,
    toggleMobileMenu
};
