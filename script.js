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

// Handle payment processing
function handlePayment(plan, amount, provider) {
    // Create a simple modal/prompt for phone number
    const phoneNumber = prompt(`Enter your ${provider} phone number to pay ${amount} UGX for ${plan}:`);
    
    if (!phoneNumber) {
        return;
    }
    
    // Validate phone number format (basic validation)
    const ugandanPhoneRegex = /^(\+256|0)(7[0-9]{8}|3[0-9]{8})$/;
    if (!ugandanPhoneRegex.test(phoneNumber)) {
        alert('Please enter a valid Ugandan phone number (e.g., +256712345678 or 0712345678)');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '⏳ Processing...';
    button.disabled = true;
    
    // Prepare payment data
    const paymentData = {
        action: 'handle_payment',
        plan: plan,
        amount: amount,
        provider: provider,
        phone_number: phoneNumber,
        nonce: ntenjeru_ajax ? ntenjeru_ajax.nonce : ''
    };
    
    // Send AJAX request (if WordPress AJAX is available)
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
                alert(`Payment initiated! Check your phone for the ${provider} payment prompt.`);
                // In a real implementation, you would redirect to a success page or update the UI
            } else {
                alert('Payment failed: ' + data.data);
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            alert('Payment processing error. Please try again or contact support.');
        })
        .finally(() => {
            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
        });
    } else {
        // Fallback for non-WordPress environments
        setTimeout(() => {
            alert(`Payment integration required. Contact NTENJERU WIFI at +256 763 643724 to complete payment for ${plan} (${amount} UGX) via ${provider}.`);
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
    }
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