/**
 * NTENJERU WIFI Theme JavaScript
 */

// Smooth scrolling function
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Mobile menu toggle
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const menuIcon = document.getElementById('menuIcon');
    
    if (mobileNav.style.display === 'none' || mobileNav.style.display === '') {
        mobileNav.style.display = 'block';
        menuIcon.textContent = '✕';
    } else {
        mobileNav.style.display = 'none';
        menuIcon.textContent = '☰';
    }
}

// Handle payment processing with modern UI
function handlePayment(plan, amount, provider) {
    // Create modern payment modal
    createPaymentModal(plan, amount, provider);
}

function createPaymentModal(plan, amount, provider) {
    // Remove existing modal if any
    const existingModal = document.getElementById('paymentModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Create modal HTML
    const modalHTML = `
        <div id="paymentModal" class="payment-modal-overlay">
            <div class="payment-modal">
                <div class="payment-modal-header">
                    <h3>💳 Complete Payment</h3>
                    <button class="modal-close" onclick="closePaymentModal()">&times;</button>
                </div>
                <div class="payment-modal-body">
                    <div class="payment-info">
                        <div class="plan-info">
                            <h4>📶 ${plan}</h4>
                            <p class="amount">${amount} UGX</p>
                            <p class="provider">${provider === 'MTN' ? '🟡' : '🔴'} ${provider} Mobile Money</p>
                        </div>
                    </div>
                    <form id="paymentForm" class="payment-form">
                        <div class="form-group">
                            <label for="phoneInput">📱 Phone Number</label>
                            <input type="tel" id="phoneInput" placeholder="Enter ${provider} number (07xxxxxxxx)" required>
                            <small>Format: 0712345678 or +256712345678</small>
                        </div>
                        <div class="payment-buttons">
                            <button type="button" class="btn-cancel" onclick="closePaymentModal()">Cancel</button>
                            <button type="submit" class="btn-pay">
                                <span class="pay-text">Pay ${amount} UGX</span>
                                <span class="pay-loading" style="display: none;">⏳ Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Add modal styles
    addPaymentModalStyles();
    
    // Handle form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        processPayment(plan, amount, provider);
    });
    
    // Focus on phone input
    setTimeout(() => {
        document.getElementById('phoneInput').focus();
    }, 100);
}

function processPayment(plan, amount, provider) {
    const phoneInput = document.getElementById('phoneInput');
    const phoneNumber = phoneInput.value.trim();
    const payButton = document.querySelector('.btn-pay');
    const payText = document.querySelector('.pay-text');
    const payLoading = document.querySelector('.pay-loading');
    
    // Validate phone number
    const ugandanPhoneRegex = /^(\+256|256|0)?(7[0-9]{8}|3[0-9]{8})$/;
    if (!ugandanPhoneRegex.test(phoneNumber)) {
        showPaymentError('Please enter a valid Ugandan phone number (e.g., 0712345678)');
        phoneInput.focus();
        return;
    }
    
    // Show loading state
    payButton.disabled = true;
    payText.style.display = 'none';
    payLoading.style.display = 'inline';
    
    // Prepare payment data
    const paymentData = {
        action: 'handle_payment',
        plan: plan,
        amount: amount,
        provider: provider,
        phone_number: phoneNumber,
        nonce: ntenjeru_ajax ? ntenjeru_ajax.nonce : ''
    };
    
    // Send payment request
    if (typeof ntenjeru_ajax !== 'undefined') {
        fetch(ntenjeru_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(paymentData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPaymentSuccess(data.data.message);
                setTimeout(() => {
                    closePaymentModal();
                }, 3000);
            } else {
                showPaymentError(data.data || 'Payment failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            showPaymentError('Network error. Please check your connection and try again.');
        })
        .finally(() => {
            // Reset button
            payButton.disabled = false;
            payText.style.display = 'inline';
            payLoading.style.display = 'none';
        });
    } else {
        // Fallback for non-WordPress environments
        setTimeout(() => {
            showPaymentError(`Payment integration required. Contact NTENJERU WIFI at +256 763 643724 to complete payment.`);
            payButton.disabled = false;
            payText.style.display = 'inline';
            payLoading.style.display = 'none';
        }, 2000);
    }
}

function showPaymentSuccess(message) {
    const modalBody = document.querySelector('.payment-modal-body');
    modalBody.innerHTML = `
        <div class="payment-result success">
            <div class="result-icon">✅</div>
            <h4>Payment Initiated!</h4>
            <p>${message}</p>
            <small>Check your phone for the MTN Mobile Money prompt</small>
        </div>
    `;
}

function showPaymentError(message) {
    const modalBody = document.querySelector('.payment-modal-body');
    const errorDiv = modalBody.querySelector('.payment-error');
    
    if (errorDiv) {
        errorDiv.remove();
    }
    
    const errorHTML = `
        <div class="payment-error">
            ⚠️ ${message}
        </div>
    `;
    
    modalBody.insertAdjacentHTML('afterbegin', errorHTML);
    
    // Auto-hide error after 5 seconds
    setTimeout(() => {
        const errorDiv = modalBody.querySelector('.payment-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }, 5000);
}

function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.classList.add('closing');
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

function addPaymentModalStyles() {
    const existingStyles = document.getElementById('paymentModalStyles');
    if (existingStyles) return;
    
    const styles = document.createElement('style');
    styles.id = 'paymentModalStyles';
    styles.textContent = `
        .payment-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        
        .payment-modal {
            background: white;
            border-radius: 24px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }
        
        .payment-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .payment-modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .modal-close:hover {
            background: #f0f0f0;
            color: #333;
        }
        
        .payment-modal-body {
            padding: 24px;
        }
        
        .plan-info {
            text-align: center;
            margin-bottom: 24px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            color: white;
        }
        
        .plan-info h4 {
            margin: 0 0 8px 0;
            font-size: 1.2rem;
        }
        
        .plan-info .amount {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }
        
        .plan-info .provider {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 8px 0 0 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        
        .form-group small {
            color: #666;
            font-size: 0.85rem;
            margin-top: 4px;
            display: block;
        }
        
        .payment-buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        
        .btn-cancel {
            flex: 1;
            padding: 12px 24px;
            border: 2px solid #ddd;
            background: white;
            color: #666;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-cancel:hover {
            border-color: #bbb;
            color: #333;
        }
        
        .btn-pay {
            flex: 2;
            padding: 12px 24px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-pay:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(251, 191, 36, 0.3);
        }
        
        .btn-pay:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .payment-error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        
        .payment-result {
            text-align: center;
            padding: 40px 20px;
        }
        
        .payment-result.success {
            color: #059669;
        }
        
        .result-icon {
            font-size: 3rem;
            margin-bottom: 16px;
        }
        
        .payment-result h4 {
            margin: 0 0 12px 0;
            font-size: 1.3rem;
        }
        
        .payment-result p {
            margin: 0 0 8px 0;
        }
        
        .payment-result small {
            color: #666;
            font-size: 0.9rem;
        }
        
        .closing {
            animation: fadeOut 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        @media (max-width: 640px) {
            .payment-modal {
                width: 95%;
                margin: 20px;
            }
            
            .payment-buttons {
                flex-direction: column;
            }
            
            .btn-cancel, .btn-pay {
                flex: none;
            }
        }
    `;
    document.head.appendChild(styles);
}

// Handle contact form submission
function handleContactForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '⏳ Sending...';
    submitButton.disabled = true;
    
    // Prepare form data
    const contactData = {
        action: 'handle_contact_form',
        name: formData.get('name'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        message: formData.get('message'),
        nonce: ntenjeru_ajax ? ntenjeru_ajax.nonce : ''
    };
    
    // Send AJAX request (if WordPress AJAX is available)
    if (typeof ntenjeru_ajax !== 'undefined') {
        fetch(ntenjeru_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(contactData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.data);
                form.reset();
            } else {
                alert('Error: ' + data.data);
            }
        })
        .catch(error => {
            console.error('Contact form error:', error);
            alert('Error sending message. Please try again or contact us directly at +256 763 643724.');
        })
        .finally(() => {
            // Reset button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    } else {
        // Fallback for non-WordPress environments
        setTimeout(() => {
            alert('Message received! Thank you for contacting NTENJERU WIFI. We\'ll get back to you within 24 hours.');
            form.reset();
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 1500);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            scrollToSection(targetId);
        });
    });
    
    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe elements with animation classes
    document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Header scroll effect
    let lastScrollTop = 0;
    const header = document.querySelector('.header');
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileNav = document.getElementById('mobileNav');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        
        if (mobileNav && mobileNav.style.display === 'block') {
            if (!mobileNav.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                toggleMobileMenu();
            }
        }
    });
    
    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function() {
            // Add a subtle loading effect
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Contact form validation
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        const inputs = contactForm.querySelectorAll('input[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.style.borderColor = '#ef4444';
                } else {
                    this.style.borderColor = '';
                }
            });
            
            input.addEventListener('input', function() {
                if (this.style.borderColor === 'rgb(239, 68, 68)') {
                    this.style.borderColor = '';
                }
            });
        });
    }
});

// Handle browser back/forward navigation
window.addEventListener('popstate', function(event) {
    if (event.state && event.state.section) {
        scrollToSection(event.state.section);
    }
});

// Add keyboard navigation support
document.addEventListener('keydown', function(event) {
    // ESC key closes mobile menu
    if (event.key === 'Escape') {
        const mobileNav = document.getElementById('mobileNav');
        if (mobileNav && mobileNav.style.display === 'block') {
            toggleMobileMenu();
        }
    }
});

// Performance optimization: Lazy load images
function lazyLoadImages() {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading
lazyLoadImages();

// WhatsApp floating button pulse animation
const whatsappFloat = document.querySelector('.whatsapp-float');
if (whatsappFloat) {
    setInterval(() => {
        whatsappFloat.style.animation = 'none';
        setTimeout(() => {
            whatsappFloat.style.animation = 'pulse 2s infinite';
        }, 10);
    }, 10000); // Pulse every 10 seconds
}

// Add CSS for pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);

// Enhanced scroll animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.scroll-animate').forEach(el => {
        observer.observe(el);
    });
}

// Initialize scroll animations when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
});