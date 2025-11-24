// assets/js/script.js

// Global JavaScript untuk LET Lab Website
document.addEventListener('DOMContentLoaded', function() {
    // Initialize semua fungsi
    initNavbar();
    initSmoothScroll();
    initCardAnimations();
    initFormValidations();
    initPageViewsCounter();
    initAdminFunctions();
    initMobileMenu();
    initCharts(); // Pindah ke dalam DOMContentLoaded
});

// Navbar scroll effect dengan throttle untuk performa
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        const handleScroll = Utils.throttle(function() {
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        }, 100);
        
        window.addEventListener('scroll', handleScroll);
    }
}

// Smooth scroll untuk anchor links dengan improvement
function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            // Skip jika target adalah "#" saja
            if (targetId === '#' || targetId === '#!') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                
                const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 80;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Update URL tanpa reload page
                if (history.pushState) {
                    history.pushState(null, null, targetId);
                }
                
                // Focus untuk accessibility
                targetElement.setAttribute('tabindex', '-1');
                targetElement.focus();
            }
        });
    });
}

// Animasi cards dengan Intersection Observer improvement
function initCardAnimations() {
    const cards = document.querySelectorAll('.card, .stats-card, .feature-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                // Stop observing setelah animasi selesai
                observer.unobserve(entry.target);
            }
        });
    }, { 
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    cards.forEach(card => {
        card.classList.add('animate-on-scroll');
        observer.observe(card);
    });
}

// Form validations dengan feedback yang lebih baik
function initFormValidations() {
    const forms = document.querySelectorAll('form[needs-validation]');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                
                // Scroll ke field pertama yang error
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    firstInvalid.focus();
                }
                
                showNotification('Please correct the errors in the form', 'warning');
            }
            
            form.classList.add('was-validated');
        });
    });
}

// Helper function untuk validasi field
function validateField(field) {
    const isValid = field.checkValidity();
    
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        
        // Remove error message jika ada
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        
        // Show custom error message
        showFieldError(field);
    }
    
    return isValid;
}

function showFieldError(field) {
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = field.validationMessage || 'This field is required';
    
    field.parentNode.appendChild(errorDiv);
}

// Page views counter dengan improvement
function initPageViewsCounter() {
    const pageViewsElement = document.querySelector('.page-views');
    if (pageViewsElement) {
        try {
            let views = parseInt(localStorage.getItem('pageViews')) || 0;
            views++;
            localStorage.setItem('pageViews', views.toString());
            
            // Format number dengan thousand separator
            pageViewsElement.textContent = `© Page Views: ${views.toLocaleString()}`;
        } catch (error) {
            console.warn('Could not update page views:', error);
        }
    }
}

// Admin specific functions dengan improvement
function initAdminFunctions() {
    // Cek jika di halaman admin
    if (!document.body.classList.contains('admin-page')) return;
    
    // Modal handlers
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            console.log('Modal opened:', this.id);
        });
        
        // Escape key to close modal
        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const bsModal = bootstrap.Modal.getInstance(this);
                if (bsModal) bsModal.hide();
            }
        });
    });
    
    // Confirm delete actions dengan sweet alert alternative
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    
    // Table row actions dengan improvement
    const tableRows = document.querySelectorAll('table tbody tr[data-clickable]');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('button') && !e.target.closest('a')) {
                this.classList.toggle('table-active');
            }
        });
        
        // Keyboard navigation
        row.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
    
    // Auto-save forms dengan improvement
    const autoSaveForms = document.querySelectorAll('form[auto-save]');
    autoSaveForms.forEach(form => {
        let saveTimeout;
        const saveIndicator = createSaveIndicator();
        form.appendChild(saveIndicator);
        
        form.addEventListener('input', Utils.debounce(function() {
            saveFormData(form);
            showSaveIndicator(saveIndicator);
        }, 1000));
    });
}

function createSaveIndicator() {
    const indicator = document.createElement('div');
    indicator.className = 'save-indicator';
    indicator.innerHTML = '<i class="fas fa-check-circle"></i> Saved';
    indicator.style.cssText = `
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 12px;
        color: #28a745;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    return indicator;
}

function showSaveIndicator(indicator) {
    indicator.style.opacity = '1';
    setTimeout(() => {
        indicator.style.opacity = '0';
    }, 2000);
}

// Mobile menu handling dengan improvement
function initMobileMenu() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        // Close mobile menu ketika klik di luar
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && 
                !navbarCollapse.contains(e.target) && 
                !navbarToggler.contains(e.target) &&
                navbarCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) bsCollapse.hide();
            }
        });
        
        // Close mobile menu ketika klik link
        const navLinks = navbarCollapse.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if (bsCollapse) bsCollapse.hide();
                }
            });
        });
        
        // Keyboard navigation untuk mobile menu
        navbarToggler.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    }
}

// Notification system dengan improvement
function showNotification(message, type = 'info', duration = 5000) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notif => {
        if (notif.parentNode) {
            notif.remove();
        }
    });
    
    const icon = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    }[type] || 'fa-info-circle';
    
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show notification-toast`;
    notification.setAttribute('role', 'alert');
    notification.innerHTML = `
        <i class="fas ${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: '9999',
        minWidth: '300px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
        animation: 'slideInRight 0.3s ease'
    });
    
    document.body.appendChild(notification);
    
    // Auto remove setelah duration
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, duration);
    
    return notification;
}

// Loading spinner dengan improvement
function showLoading(message = 'Loading...') {
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    spinner.innerHTML = `
        <div class="spinner-container">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            ${message ? `<div class="loading-text mt-2">${message}</div>` : ''}
        </div>
    `;
    
    Object.assign(spinner.style, {
        position: 'fixed',
        top: '0',
        left: '0',
        width: '100%',
        height: '100%',
        background: 'rgba(255,255,255,0.9)',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        zIndex: '9999',
        backdropFilter: 'blur(2px)'
    });
    
    document.body.appendChild(spinner);
    document.body.style.overflow = 'hidden'; // Prevent scrolling
    
    return spinner;
}

function hideLoading(spinner) {
    if (spinner && spinner.parentNode) {
        spinner.style.opacity = '0';
        spinner.style.transition = 'opacity 0.3s ease';
        
        setTimeout(() => {
            if (spinner.parentNode) {
                spinner.remove();
            }
            document.body.style.overflow = ''; // Restore scrolling
        }, 300);
    }
}

// AJAX helper functions dengan improvement
const AjaxHelper = {
    get: function(url, callback, errorCallback) {
        const spinner = showLoading();
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                hideLoading(spinner);
                callback(data);
            })
            .catch(error => {
                hideLoading(spinner);
                console.error('GET Error:', error);
                showNotification('Error fetching data', 'error');
                if (errorCallback) errorCallback(error);
            });
    },
    
    post: function(url, data, callback, errorCallback) {
        const spinner = showLoading();
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            hideLoading(spinner);
            callback(data);
        })
        .catch(error => {
            hideLoading(spinner);
            console.error('POST Error:', error);
            showNotification('Error submitting data', 'error');
            if (errorCallback) errorCallback(error);
        });
    },
    
    // Tambahan method untuk file upload
    upload: function(url, formData, callback, progressCallback, errorCallback) {
        const spinner = showLoading('Uploading...');
        
        const xhr = new XMLHttpRequest();
        
        // Progress tracking
        if (progressCallback) {
            xhr.upload.addEventListener('progress', progressCallback);
        }
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                hideLoading(spinner);
                if (xhr.status === 200) {
                    callback(JSON.parse(xhr.responseText));
                } else {
                    const error = new Error(`Upload failed: ${xhr.status}`);
                    showNotification('Upload failed', 'error');
                    if (errorCallback) errorCallback(error);
                }
            }
        };
        
        xhr.open('POST', url);
        xhr.send(formData);
    }
};

// Utility functions dengan tambahan
const Utils = {
    // Format date
    formatDate: function(dateString, options = {}) {
        const defaultOptions = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        
        const mergedOptions = { ...defaultOptions, ...options };
        return new Date(dateString).toLocaleDateString('id-ID', mergedOptions);
    },
    
    // Debounce function
    debounce: function(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    },
    
    // Throttle function
    throttle: function(func, limit) {
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
    },
    
    // Generate unique ID
    generateId: function() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    },
    
    // Copy to clipboard
    copyToClipboard: function(text) {
        return navigator.clipboard.writeText(text).then(() => {
            showNotification('Copied to clipboard', 'success');
        }).catch(err => {
            console.error('Copy failed:', err);
            showNotification('Copy failed', 'error');
        });
    },
    
    // Detect mobile device
    isMobile: function() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
};

// Chart initialization
function initCharts() {
    const chartElements = document.querySelectorAll('[data-chart]');
    
    if (chartElements.length === 0) return;
    
    // Load Chart.js dynamically jika diperlukan
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js is not loaded. Charts will not be initialized.');
        return;
    }
    
    chartElements.forEach(element => {
        const chartType = element.getAttribute('data-chart-type') || 'bar';
        const chartData = JSON.parse(element.getAttribute('data-chart-data') || '{}');
        const chartOptions = JSON.parse(element.getAttribute('data-chart-options') || '{}');
        
        try {
            new Chart(element, {
                type: chartType,
                data: chartData,
                options: chartOptions
            });
        } catch (error) {
            console.error('Chart initialization error:', error);
        }
    });
}

// Error handling global
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
    // showNotification('An unexpected error occurred', 'error');
});

// Export untuk penggunaan di module lain
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showNotification,
        showLoading,
        hideLoading,
        AjaxHelper,
        Utils,
        initCharts
    };
}