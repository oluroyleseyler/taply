// main.js - Ana JavaScript dosyası

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobil menü toggle
    initMobileMenu();
    
    // Form validasyonları
    initFormValidations();
    
    // Smooth scroll
    initSmoothScroll();
    
    // Alert auto-hide
    initAlertAutoHide();
    
    // Copy to clipboard functionality
    initCopyToClipboard();
    
    // Image lazy loading
    initLazyLoading();
    
    // Tooltip system
    initTooltips();
});

// Mobil menü işlevselliği
function initMobileMenu() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
            
            // Animasyon için hamburger çizgilerini döndür
            const spans = navToggle.querySelectorAll('span');
            spans.forEach(span => span.classList.toggle('rotated'));
        });
        
        // Menü dışına tıklandığında kapat
        document.addEventListener('click', function(e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
            }
        });
        
        // Escape tuşu ile kapat
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
            }
        });
    }
}

// Form validasyonları
function initFormValidations() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Form submit validation
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                return false;
            }
        });
    });
}

// Tek alan validasyonu
function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute('required');
    let isValid = true;
    let errorMessage = '';
    
    // Required check
    if (required && !value) {
        isValid = false;
        errorMessage = 'Bu alan zorunludur.';
    }
    
    // Type-specific validations
    if (value && isValid) {
        switch (type) {
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Geçerli bir e-mail adresi giriniz.';
                }
                break;
                
            case 'url':
                try {
                    new URL(value);
                } catch {
                    isValid = false;
                    errorMessage = 'Geçerli bir URL giriniz.';
                }
                break;
                
            case 'password':
                if (value.length < 6) {
                    isValid = false;
                    errorMessage = 'Şifre en az 6 karakter olmalıdır.';
                }
                break;
        }
        
        // Username validation
        if (field.name === 'username') {
            const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
            if (!usernameRegex.test(value)) {
                isValid = false;
                errorMessage = 'Kullanıcı adı 3-20 karakter arası, sadece harf, rakam ve alt çizgi içerebilir.';
            }
        }
        
        // Pattern validation
        if (field.hasAttribute('pattern')) {
            const pattern = new RegExp(field.getAttribute('pattern'));
            if (!pattern.test(value)) {
                isValid = false;
                errorMessage = field.getAttribute('title') || 'Geçersiz format.';
            }
        }
        
        // Min/max length
        if (field.hasAttribute('minlength')) {
            const minLength = parseInt(field.getAttribute('minlength'));
            if (value.length < minLength) {
                isValid = false;
                errorMessage = `En az ${minLength} karakter olmalıdır.`;
            }
        }
        
        if (field.hasAttribute('maxlength')) {
            const maxLength = parseInt(field.getAttribute('maxlength'));
            if (value.length > maxLength) {
                isValid = false;
                errorMessage = `En fazla ${maxLength} karakter olabilir.`;
            }
        }
    }
    
    // Show/hide error
    if (isValid) {
        clearFieldError(field);
    } else {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

// Form validasyonu
function validateForm(form) {
    const fields = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    
    fields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Password confirmation check
    const passwordField = form.querySelector('input[name="password"]');
    const confirmField = form.querySelector('input[name="confirm_password"]');
    
    if (passwordField && confirmField) {
        if (passwordField.value !== confirmField.value) {
            showFieldError(confirmField, 'Şifreler eşleşmiyor.');
            isValid = false;
        }
    }
    
    return isValid;
}

// Hata gösterme
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

// Hata temizleme
function clearFieldError(field) {
    field.classList.remove('error');
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Smooth scroll
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Alert auto-hide
function initAlertAutoHide() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Auto-hide after 5 seconds
        setTimeout(() => {
            fadeOut(alert);
        }, 5000);
        
        // Manual close button
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '×';
        closeBtn.className = 'alert-close';
        closeBtn.addEventListener('click', () => fadeOut(alert));
        
        alert.appendChild(closeBtn);
    });
}

// Fade out animation
function fadeOut(element) {
    element.style.opacity = '0';
    element.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
        if (element.parentNode) {
            element.parentNode.removeChild(element);
        }
    }, 300);
}

// Copy to clipboard
function initCopyToClipboard() {
    const copyButtons = document.querySelectorAll('[data-copy]');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            
            navigator.clipboard.writeText(textToCopy).then(() => {
                showToast('Panoya kopyalandı!', 'success');
            }).catch(() => {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                
                showToast('Panoya kopyalandı!', 'success');
            });
        });
    });
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Show animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto-hide
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Lazy loading for images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.getAttribute('data-src');
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => observer.observe(img));
    } else {
        // Fallback
        images.forEach(img => {
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
        });
    }
}

// Tooltip system
function initTooltips() {
    const elements = document.querySelectorAll('[title]');
    
    elements.forEach(element => {
        let tooltip;
        
        element.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            this.removeAttribute('title');
            this.setAttribute('data-original-title', title);
            
            tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = title;
            document.body.appendChild(tooltip);
            
            positionTooltip(this, tooltip);
        });
        
        element.addEventListener('mouseleave', function() {
            const originalTitle = this.getAttribute('data-original-title');
            this.setAttribute('title', originalTitle);
            this.removeAttribute('data-original-title');
            
            if (tooltip && tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        });
        
        element.addEventListener('mousemove', function(e) {
            if (tooltip) {
                positionTooltip(this, tooltip, e);
            }
        });
    });
}

function positionTooltip(element, tooltip, event) {
    const rect = element.getBoundingClientRect();
    let x, y;
    
    if (event) {
        x = event.clientX;
        y = event.clientY;
    } else {
        x = rect.left + rect.width / 2;
        y = rect.top;
    }
    
    tooltip.style.left = (x - tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = (y - tooltip.offsetHeight - 10) + 'px';
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// AJAX helper
function ajaxRequest(url, options = {}) {
    const defaults = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    const config = { ...defaults, ...options };
    
    return fetch(url, config)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
}

// Global error handler
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    // Burada hata raporlama servisi entegrasyonu yapılabilir
});

// Page visibility API - sayfa görünürlüğü
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Sayfa gizlendi
        console.log('Page hidden');
    } else {
        // Sayfa görünür hale geldi
        console.log('Page visible');
    }
});

// Export for external use
window.TaplyJS = {
    showToast,
    validateField,
    validateForm,
    ajaxRequest,
    debounce,
    throttle,
    fadeOut
};
