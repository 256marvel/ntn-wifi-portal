/**
 * NTENJERU WIFI Professional Theme JavaScript
 * Compatible with WordPress & Elementor
 */

// Theme Configuration - Auto-loaded from WordPress admin settings
let themeConfig = {
    primary_color: '#3b82f6',
    secondary_color: '#f59e0b', 
    accent_color: '#10b981',
    gradient_enabled: true,
    animations_enabled: true,
    floating_graphics: true,
    payment_enabled: true
};

// Load theme settings from WordPress
function loadThemeSettings() {
    if (typeof ntenjeru_theme_settings !== 'undefined') {
        themeConfig = { ...themeConfig, ...ntenjeru_theme_settings };
        applyThemeSettings();
    }
}

// Apply theme settings dynamically
function applyThemeSettings() {
    const root = document.documentElement;
    root.style.setProperty('--primary-color', themeConfig.primary_color);
    root.style.setProperty('--secondary-color', themeConfig.secondary_color);
    root.style.setProperty('--accent-color', themeConfig.accent_color);
    
    // Apply gradient settings
    if (themeConfig.gradient_enabled) {
        document.body.classList.add('gradients-enabled');
    }
    
    // Apply animation settings
    if (!themeConfig.animations_enabled) {
        document.body.classList.add('animations-disabled');
    }
}

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

// Floating Tech Graphics System
class FloatingTechGraphics {
    constructor() {
        this.graphics = [];
        this.isActive = themeConfig.floating_graphics;
        this.init();
    }
    
    init() {
        if (!this.isActive) return;
        
        this.createGraphics();
        this.startAnimation();
        this.bindScrollEvents();
    }
    
    createGraphics() {
        const graphicsContainer = document.createElement('div');
        graphicsContainer.className = 'floating-tech-graphics';
        graphicsContainer.innerHTML = `
            <div class="tech-graphic tech-circuit">
                <svg viewBox="0 0 100 100" class="circuit-svg">
                    <path d="M20,20 L80,20 L80,40 L60,40 L60,60 L80,60 L80,80 L20,80 Z" stroke="currentColor" fill="none" stroke-width="2"/>
                    <circle cx="20" cy="20" r="3" fill="currentColor"/>
                    <circle cx="80" cy="40" r="3" fill="currentColor"/>
                    <circle cx="60" cy="60" r="3" fill="currentColor"/>
                </svg>
            </div>
            <div class="tech-graphic tech-chip">
                <svg viewBox="0 0 100 100" class="chip-svg">
                    <rect x="30" y="30" width="40" height="40" stroke="currentColor" fill="none" stroke-width="2"/>
                    <rect x="35" y="35" width="30" height="30" fill="currentColor" opacity="0.3"/>
                    <line x1="30" y1="40" x2="10" y2="40" stroke="currentColor" stroke-width="2"/>
                    <line x1="30" y1="50" x2="10" y2="50" stroke="currentColor" stroke-width="2"/>
                    <line x1="30" y1="60" x2="10" y2="60" stroke="currentColor" stroke-width="2"/>
                    <line x1="70" y1="40" x2="90" y2="40" stroke="currentColor" stroke-width="2"/>
                    <line x1="70" y1="50" x2="90" y2="50" stroke="currentColor" stroke-width="2"/>
                    <line x1="70" y1="60" x2="90" y2="60" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
            <div class="tech-graphic tech-wave">
                <svg viewBox="0 0 200 100" class="wave-svg">
                    <path d="M0,50 Q50,10 100,50 T200,50" stroke="currentColor" fill="none" stroke-width="3"/>
                    <path d="M0,60 Q50,20 100,60 T200,60" stroke="currentColor" fill="none" stroke-width="2" opacity="0.6"/>
                </svg>
            </div>
            <div class="tech-graphic tech-grid">
                <svg viewBox="0 0 100 100" class="grid-svg">
                    <defs>
                        <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" opacity="0.4"/>
                </svg>
            </div>
        `;
        
        document.body.appendChild(graphicsContainer);
        this.graphics = Array.from(document.querySelectorAll('.tech-graphic'));
    }
    
    startAnimation() {
        this.graphics.forEach((graphic, index) => {
            const delay = index * 0.5;
            graphic.style.animationDelay = `${delay}s`;
            
            // Random positioning
            const randomX = Math.random() * 80;
            const randomY = Math.random() * 80;
            graphic.style.left = `${randomX}%`;
            graphic.style.top = `${randomY}%`;
        });
    }
    
    bindScrollEvents() {
        let ticking = false;
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    this.updateGraphicsOnScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }
    
    updateGraphicsOnScroll() {
        const scrollY = window.pageYOffset;
        const windowHeight = window.innerHeight;
        
        this.graphics.forEach((graphic, index) => {
            const speed = 0.1 + (index * 0.05);
            const yPos = -(scrollY * speed);
            const rotation = scrollY * 0.1;
            
            graphic.style.transform = `translateY(${yPos}px) rotate(${rotation}deg)`;
        });
    }
}

// Professional Payment Modal System
class PaymentModal {
    constructor() {
        this.modal = null;
        this.isOpen = false;
    }
    
    open(plan, amount, provider) {
        if (this.isOpen) return;
        
        this.create(plan, amount, provider);
        this.show();
    }
    
    create(plan, amount, provider) {
        this.remove(); // Remove existing modal
        
        const modalHTML = `
            <div id="paymentModal" class="payment-modal-overlay">
                <div class="payment-modal">
                    <div class="payment-modal-header">
                        <div class="modal-title">
                            <h3>💳 Complete Payment</h3>
                            <p>Secure ${provider} Mobile Money Payment</p>
                        </div>
                        <button class="modal-close" onclick="paymentModal.close()">&times;</button>
                    </div>
                    <div class="payment-modal-body">
                        <div class="payment-info">
                            <div class="plan-card">
                                <div class="plan-icon">${provider === 'MTN' ? '🟡' : '🔴'}</div>
                                <div class="plan-details">
                                    <h4>${plan}</h4>
                                    <div class="amount">${amount} UGX</div>
                                    <div class="provider">${provider} Mobile Money</div>
                                </div>
                            </div>
                        </div>
                        <form id="paymentForm" class="payment-form">
                            <div class="form-group">
                                <label for="phoneInput">📱 Phone Number</label>
                                <input type="tel" id="phoneInput" placeholder="Enter ${provider} number (07xxxxxxxx)" required>
                                <small>Format: 0712345678 or +256712345678</small>
                            </div>
                            <div class="security-notice">
                                🔒 Your payment is processed securely through ${provider} Mobile Money
                            </div>
                            <div class="payment-buttons">
                                <button type="button" class="btn-cancel" onclick="paymentModal.close()">Cancel</button>
                                <button type="submit" class="btn-pay">
                                    <span class="pay-text">Pay ${amount} UGX</span>
                                    <span class="pay-loading" style="display: none;">⏳ Processing...</span>
                                </button>
                            </div>
                        </form>
                        <div class="payment-steps">
                            <div class="step">
                                <span class="step-number">1</span>
                                <span>Enter your phone number</span>
                            </div>
                            <div class="step">
                                <span class="step-number">2</span>
                                <span>Check your phone for prompt</span>
                            </div>
                            <div class="step">
                                <span class="step-number">3</span>
                                <span>Enter your Mobile Money PIN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.modal = document.getElementById('paymentModal');
        
        // Bind form submission
        document.getElementById('paymentForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.processPayment(plan, amount, provider);
        });
        
        // Focus phone input
        setTimeout(() => {
            document.getElementById('phoneInput').focus();
        }, 300);
    }
    
    show() {
        if (this.modal) {
            this.isOpen = true;
            this.modal.classList.add('active');
            document.body.classList.add('modal-open');
        }
    }
    
    close() {
        if (this.modal) {
            this.isOpen = false;
            this.modal.classList.add('closing');
            document.body.classList.remove('modal-open');
            
            setTimeout(() => {
                this.remove();
            }, 300);
        }
    }
    
    remove() {
        const existing = document.getElementById('paymentModal');
        if (existing) {
            existing.remove();
        }
        this.modal = null;
        this.isOpen = false;
    }
    
    processPayment(plan, amount, provider) {
        const phoneInput = document.getElementById('phoneInput');
        const phoneNumber = phoneInput.value.trim();
        const payButton = document.querySelector('.btn-pay');
        const payText = document.querySelector('.pay-text');
        const payLoading = document.querySelector('.pay-loading');
        
        // Validate phone number
        const ugandanPhoneRegex = /^(\+256|256|0)?(7[0-9]{8}|3[0-9]{8})$/;
        if (!ugandanPhoneRegex.test(phoneNumber)) {
            this.showError('Please enter a valid Ugandan phone number (e.g., 0712345678)');
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
            nonce: typeof ntenjeru_ajax !== 'undefined' ? ntenjeru_ajax.nonce : ''
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
                    this.showSuccess(data.data.message || 'Payment initiated successfully!');
                } else {
                    this.showError(data.data || 'Payment failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                this.showError('Network error. Please check your connection and try again.');
            })
            .finally(() => {
                payButton.disabled = false;
                payText.style.display = 'inline';
                payLoading.style.display = 'none';
            });
        } else {
            // Fallback for non-WordPress environments
            setTimeout(() => {
                this.showError(`Payment integration required. Contact NTENJERU WIFI at +256 763 643724 to complete payment.`);
                payButton.disabled = false;
                payText.style.display = 'inline';
                payLoading.style.display = 'none';
            }, 2000);
        }
    }
    
    showSuccess(message) {
        const modalBody = document.querySelector('.payment-modal-body');
        modalBody.innerHTML = `
            <div class="payment-result success">
                <div class="result-icon">✅</div>
                <h4>Payment Initiated Successfully!</h4>
                <p>${message}</p>
                <div class="next-steps">
                    <p><strong>Next Steps:</strong></p>
                    <ol>
                        <li>Check your phone for the Mobile Money prompt</li>
                        <li>Enter your Mobile Money PIN to complete payment</li>
                        <li>You'll receive a confirmation SMS</li>
                    </ol>
                </div>
                <button class="btn-done" onclick="paymentModal.close()">Done</button>
            </div>
        `;
        
        setTimeout(() => {
            this.close();
        }, 10000); // Auto-close after 10 seconds
    }
    
    showError(message) {
        const modalBody = document.querySelector('.payment-modal-body');
        const errorDiv = modalBody.querySelector('.payment-error');
        
        if (errorDiv) {
            errorDiv.remove();
        }
        
        const errorHTML = `
            <div class="payment-error">
                <div class="error-icon">⚠️</div>
                <div class="error-message">${message}</div>
            </div>
        `;
        
        modalBody.insertAdjacentHTML('afterbegin', errorHTML);
        
        setTimeout(() => {
            const errorDiv = modalBody.querySelector('.payment-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }, 8000);
    }
}

// Initialize payment modal
const paymentModal = new PaymentModal();

// Global payment handler
function handlePayment(plan, amount, provider) {
    if (!themeConfig.payment_enabled) {
        alert('Payment system is currently disabled. Please contact support.');
        return;
    }
    
    paymentModal.open(plan, amount, provider);
}

// Professional Contact Form Handler
class ContactFormHandler {
    constructor() {
        this.init();
    }
    
    init() {
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => this.handleSubmit(e));
            this.addValidation(contactForm);
        }
    }
    
    handleSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.innerHTML = '⏳ Sending Message...';
        submitButton.disabled = true;
        
        // Prepare form data
        const contactData = {
            action: 'handle_contact_form',
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            message: formData.get('message'),
            nonce: typeof ntenjeru_ajax !== 'undefined' ? ntenjeru_ajax.nonce : ''
        };
        
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
                    this.showNotification('Message sent successfully! We\'ll get back to you soon.', 'success');
                    form.reset();
                } else {
                    this.showNotification('Error sending message: ' + data.data, 'error');
                }
            })
            .catch(error => {
                console.error('Contact form error:', error);
                this.showNotification('Error sending message. Please try again or contact us directly.', 'error');
            })
            .finally(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        } else {
            setTimeout(() => {
                this.showNotification('Message received! We\'ll get back to you within 24 hours.', 'success');
                form.reset();
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 1500);
        }
    }
    
    addValidation(form) {
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                this.validateField();
            });
            
            input.addEventListener('input', function() {
                this.clearValidation();
            });
        });
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
        
        // Click to remove
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });
    }
}

// Initialize contact form
const contactFormHandler = new ContactFormHandler();

// Enhanced Animation System
class AnimationSystem {
    constructor() {
        this.observers = [];
        this.init();
    }
    
    init() {
        if (!themeConfig.animations_enabled) return;
        
        this.initScrollAnimations();
        this.initParallaxEffects();
        this.initCounterAnimations();
        this.initTypewriterEffects();
    }
    
    initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);
        
        // Observe elements with animation classes
        document.querySelectorAll('.animate-fade-in, .animate-slide-up, .animate-slide-left, .animate-slide-right, .scroll-animate').forEach(el => {
            observer.observe(el);
        });
        
        this.observers.push(observer);
    }
    
    initParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.parallax');
        
        if (parallaxElements.length > 0) {
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                
                parallaxElements.forEach(element => {
                    const rate = scrolled * -0.5;
                    element.style.transform = `translateY(${rate}px)`;
                });
            });
        }
    }
    
    initCounterAnimations() {
        const counters = document.querySelectorAll('.counter');
        
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    this.animateCounter(entry.target);
                    entry.target.classList.add('counted');
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
        
        this.observers.push(counterObserver);
    }
    
    animateCounter(element) {
        const target = parseInt(element.dataset.target) || 0;
        const duration = parseInt(element.dataset.duration) || 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };
        
        updateCounter();
    }
    
    initTypewriterEffects() {
        const typewriters = document.querySelectorAll('.typewriter');
        
        typewriters.forEach(element => {
            const text = element.textContent;
            element.textContent = '';
            element.style.borderRight = '2px solid';
            element.style.animation = 'blink 1s infinite';
            
            let i = 0;
            const typeInterval = setInterval(() => {
                element.textContent += text.charAt(i);
                i++;
                
                if (i >= text.length) {
                    clearInterval(typeInterval);
                    setTimeout(() => {
                        element.style.borderRight = 'none';
                    }, 1000);
                }
            }, 100);
        });
    }
}

// Elementor Compatibility Layer
class ElementorCompatibility {
    constructor() {
        this.init();
    }
    
    init() {
        // Wait for Elementor to load
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', this.handleElementorElements.bind(this));
        }
        
        // Handle dynamic content loading
        this.observeContentChanges();
    }
    
    handleElementorElements($scope) {
        // Re-initialize animations for new Elementor elements
        const animationSystem = new AnimationSystem();
        
        // Re-bind payment buttons in Elementor widgets
        const paymentButtons = $scope.find('[data-payment-plan]');
        paymentButtons.each((index, button) => {
            button.addEventListener('click', () => {
                const plan = button.dataset.paymentPlan;
                const amount = button.dataset.paymentAmount;
                const provider = button.dataset.paymentProvider || 'MTN';
                handlePayment(plan, amount, provider);
            });
        });
    }
    
    observeContentChanges() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Re-initialize features for dynamically added content
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) { // Element node
                            this.initializeNodeFeatures(node);
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    initializeNodeFeatures(node) {
        // Initialize animations
        const animatableElements = node.querySelectorAll?.('.animate-fade-in, .animate-slide-up, .scroll-animate') || [];
        animatableElements.forEach(el => {
            if (!el.dataset.initialized) {
                // Re-initialize animations
                el.dataset.initialized = 'true';
            }
        });
        
        // Initialize payment buttons
        const paymentButtons = node.querySelectorAll?.('[data-payment-plan]') || [];
        paymentButtons.forEach(button => {
            if (!button.dataset.initialized) {
                button.addEventListener('click', () => {
                    const plan = button.dataset.paymentPlan;
                    const amount = button.dataset.paymentAmount;
                    const provider = button.dataset.paymentProvider || 'MTN';
                    handlePayment(plan, amount, provider);
                });
                button.dataset.initialized = 'true';
            }
        });
    }
}

// Performance Monitor
class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.init();
    }
    
    init() {
        // Monitor page load performance
        window.addEventListener('load', () => {
            this.recordMetric('pageLoad', performance.now());
        });
        
        // Monitor scroll performance
        let scrollStartTime = null;
        window.addEventListener('scroll', () => {
            if (!scrollStartTime) {
                scrollStartTime = performance.now();
                requestAnimationFrame(() => {
                    this.recordMetric('scrollPerformance', performance.now() - scrollStartTime);
                    scrollStartTime = null;
                });
            }
        });
    }
    
    recordMetric(name, value) {
        this.metrics[name] = value;
        
        // Report to console in development
        if (window.location.hostname === 'localhost') {
            console.log(`Performance: ${name} = ${value.toFixed(2)}ms`);
        }
    }
}

// Main Theme Initialization
class NtenjeruTheme {
    constructor() {
        this.components = {};
        this.init();
    }
    
    init() {
        // Load theme settings
        loadThemeSettings();
        
        // Initialize core components
        this.components.floatingGraphics = new FloatingTechGraphics();
        this.components.animationSystem = new AnimationSystem();
        this.components.elementorCompat = new ElementorCompatibility();
        this.components.performanceMonitor = new PerformanceMonitor();
        
        // Bind global events
        this.bindGlobalEvents();
        
        // Initialize utilities
        this.initializeUtilities();
    }
    
    bindGlobalEvents() {
        // Smooth scrolling for anchor links
        document.addEventListener('click', (e) => {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const targetId = e.target.getAttribute('href').substring(1);
                scrollToSection(targetId);
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && paymentModal.isOpen) {
                paymentModal.close();
            }
        });
        
        // Window resize handling
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));
    }
    
    initializeUtilities() {
        // Add button click effects
        document.querySelectorAll('.btn, .button, [class*="btn-"]').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.classList.contains('no-effect')) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                }
            });
        });
        
        // Initialize lazy loading
        this.initLazyLoading();
    }
    
    initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    handleResize() {
        // Handle responsive adjustments
        const vw = window.innerWidth;
        
        if (vw < 768) {
            document.body.classList.add('mobile-view');
        } else {
            document.body.classList.remove('mobile-view');
        }
    }
    
    debounce(func, wait) {
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
}

// Initialize theme when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.ntenjeruTheme = new NtenjeruTheme();
});

// WordPress compatibility
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(() => {
        // Additional jQuery-based initializations if needed
    });
}