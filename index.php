<?php get_header(); ?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero" id="home">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/hero-bg.jpg" alt="WiFi Background" class="hero-bg">
        <div class="hero-overlay"></div>
        
        <!-- Enhanced Floating Graphics Background -->
        <?php if (get_option('enable_floating_graphics', '1') === '1'): ?>
        <div class="floating-graphics">
            <div class="tech-shape"></div>
            <div class="tech-shape"></div>
            <div class="tech-shape"></div>
            <div class="tech-shape"></div>
            <div class="tech-shape"></div>
            <div class="tech-shape"></div>
        </div>
        <?php endif; ?>
        
        <div class="container">
            <div class="hero-content scroll-animate">
                <h1 class="rainbow-text">
                    <?php echo get_option('hero_title', 'Stay Connected with Lightning-Fast WiFi ⚡'); ?>
                </h1>
                <p>
                    <?php echo get_option('hero_subtitle', 'Experience blazing-fast internet speeds with our reliable WiFi packages. Perfect for streaming, gaming, and working from home.'); ?>
                </p>
                
                <div class="hero-features">
                    <div class="feature-badge">
                        <span>⚡</span>
                        <span>Lightning Fast</span>
                    </div>
                    <div class="feature-badge">
                        <span>🛡️</span>
                        <span>24/7 Available</span>
                    </div>
                    <div class="feature-badge">
                        <span>📶</span>
                        <span>Reliable Connection</span>
                    </div>
                </div>
                
                <div class="hero-buttons">
                    <a href="#packages" class="btn btn-primary btn-lg">
                        Get Started
                        <span style="margin-left: 8px;">→</span>
                    </a>
                    <a href="#packages" class="btn btn-outline btn-lg">
                        View Packages
                    </a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime Guarantee</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Technical Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="packages" class="pricing section">
        <div class="container">
            <div class="section-header">
                <h2>Choose Your Perfect Plan</h2>
                <p>Affordable internet packages designed for every need. Pay securely with MTN or Airtel Mobile Money.</p>
            </div>
            
            <div class="pricing-grid">
                <?php 
                $plans = get_option('ntenjeru_pricing_plans', array(
                    'plan_24h' => array('name' => '24 Hours', 'price' => '1,000', 'duration' => 'for 24 hours', 'features' => "High-speed internet access\n24-hour unlimited browsing\nConnect multiple devices\nBasic customer support\nSocial media access\nVideo streaming capability"),
                    'plan_1w' => array('name' => '1 Week', 'price' => '7,000', 'duration' => 'for 7 days', 'features' => "High-speed internet access\nFull week unlimited browsing\nConnect unlimited devices\nPriority customer support\nHD video streaming\nFile downloads & uploads\nEmail and work applications\nOnline gaming support"),
                    'plan_1m' => array('name' => '1 Month', 'price' => '25,000', 'duration' => 'for 30 days', 'features' => "Maximum speed internet access\nFull month unlimited browsing\nConnect unlimited devices\n24/7 premium support\n4K video streaming\nLarge file transfers\nBusiness applications\nOnline gaming & streaming\nTechnical support priority\nSpeed guarantee")
                ));
                
                $plan_icons = array('🕐', '📅', '🏆');
                $plan_classes = array('', 'popular', '');
                $i = 0;
                
                foreach ($plans as $plan_key => $plan): 
                ?>
                <div class="pricing-card <?php echo $plan_classes[$i]; ?>">
                    <?php if ($plan_classes[$i] === 'popular'): ?>
                    <div class="popular-badge">Most Popular</div>
                    <?php endif; ?>
                    
                    <div class="plan-icon">
                        <span style="font-size: 32px;"><?php echo $plan_icons[$i]; ?></span>
                    </div>
                    <h3><?php echo esc_html($plan['name']); ?></h3>
                    <p style="color: hsl(var(--muted-foreground)); margin-bottom: 24px;">Perfect for <?php echo esc_html(strtolower($plan['name'])); ?> usage</p>
                    
                    <div style="margin-bottom: 32px;">
                        <span class="plan-price"><?php echo esc_html($plan['price']); ?></span>
                        <span class="plan-currency">UGX</span>
                        <p style="font-size: 14px; color: hsl(var(--muted-foreground)); margin-top: 8px;"><?php echo esc_html($plan['duration']); ?></p>
                    </div>
                    
                    <ul class="plan-features">
                        <?php foreach (explode("\n", $plan['features']) as $feature): ?>
                        <li><span class="check">✓</span> <?php echo esc_html(trim($feature)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="payment-buttons">
                        <button class="btn btn-mtn" onclick="handlePayment('<?php echo esc_js($plan['name']); ?>', '<?php echo esc_js($plan['price']); ?>', 'MTN')">
                            📱 Pay with MTN
                        </button>
                        <button class="btn btn-airtel" onclick="handlePayment('<?php echo esc_js($plan['name']); ?>', '<?php echo esc_js($plan['price']); ?>', 'Airtel')">
                            📱 Pay with Airtel
                        </button>
                    </div>
                    
                    <p style="font-size: 12px; color: hsl(var(--muted-foreground)); text-align: center; margin-top: 16px;">
                        Secure mobile money payment
                    </p>
                </div>
                <?php 
                $i++;
                endforeach; 
                ?>
            </div>
            
            <div style="text-align: center; margin-top: 48px;">
                <p style="color: hsl(var(--muted-foreground));">
                    All plans include unlimited data usage • No hidden fees • Instant activation after payment
                </p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us section">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose NTENJERU WIFI?</h2>
                <p>Experience the difference with our reliable, affordable, and customer-focused internet service.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon blue">
                        <span style="font-size: 40px; color: white;">🕐</span>
                    </div>
                    <h3>24/7 Availability</h3>
                    <p>Round-the-clock internet access with 99.9% uptime guarantee. Stay connected whenever you need it.</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon green">
                        <span style="font-size: 40px; color: white;">💰</span>
                    </div>
                    <h3>Affordable Prices</h3>
                    <p>Competitive pricing that fits your budget. Get premium internet without breaking the bank.</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon purple">
                        <span style="font-size: 40px; color: white;">📶</span>
                    </div>
                    <h3>Reliable & Fast Connectivity</h3>
                    <p>High-speed internet with consistent performance. Stream, work, and browse without interruptions.</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon orange">
                        <span style="font-size: 40px; color: white;">🎧</span>
                    </div>
                    <h3>Friendly Customer Support</h3>
                    <p>Dedicated support team ready to help. Quick response times and technical assistance when you need it.</p>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 64px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; background: hsl(var(--primary) / 0.1); color: hsl(var(--primary)); padding: 12px 24px; border-radius: 50px; font-weight: 500;">
                    <span>📶</span>
                    <span>Trusted by 1000+ customers in Mukono</span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about section">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2>About NTENJERU WIFI</h2>
                    
                    <p>NTENJERU WIFI provides affordable and reliable internet connectivity throughout Mukono, Uganda. We believe that everyone deserves access to fast, dependable internet for work, education, and entertainment.</p>
                    
                    <p>Our mission is simple: keep you connected with quality service at prices that make sense. We focus on simplicity, speed, and customer satisfaction, ensuring that your internet experience is seamless and hassle-free.</p>
                    
                    <p>Whether you're a student, professional, or family looking to stay connected, we have the perfect package for your needs. Join thousands of satisfied customers who trust NTENJERU WIFI for their internet connectivity.</p>
                    
                    <a href="#contact" class="btn btn-primary btn-lg">
                        Get in Touch
                        <span style="margin-left: 8px;">→</span>
                    </a>
                </div>
                
                <div class="stats-card">
                    <h3 style="text-align: center; margin-bottom: 32px;">Our Impact</h3>
                    
                    <div class="stats-list">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <span style="color: #2563eb;">👥</span>
                            </div>
                            <div>
                                <div class="stat-number">1000+</div>
                                <div class="stat-label">Happy Customers</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <span style="color: #059669;">🏆</span>
                            </div>
                            <div>
                                <div class="stat-number">99.9%</div>
                                <div class="stat-label">Uptime Guarantee</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <span style="color: #7c3aed;">🎯</span>
                            </div>
                            <div>
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Technical Support</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid hsl(var(--border)); text-align: center;">
                        <p style="font-size: 14px; color: hsl(var(--muted-foreground)); margin-bottom: 8px;">Serving Mukono since 2020</p>
                        <div style="display: flex; justify-content: center; gap: 4px; margin-bottom: 4px;">
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                        </div>
                        <p style="font-size: 12px; color: hsl(var(--muted-foreground));">4.9/5 Customer Rating</p>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 80px; text-align: center;">
                <div style="max-width: 800px; margin: 0 auto; background: hsl(var(--primary) / 0.05); border-radius: 24px; padding: 48px; border: 1px solid hsl(var(--primary) / 0.1);">
                    <h3 style="margin-bottom: 16px;">Our Mission</h3>
                    <p style="font-size: 1.25rem; color: hsl(var(--muted-foreground));">
                        "To bridge the digital divide by providing affordable, reliable internet access to every home and business in Mukono, empowering our community through connectivity."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
        <div class="container">
            <div class="section-header">
                <h2>Get in Touch</h2>
                <p>Ready to get connected? Contact us today and let's set up your perfect internet package.</p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-form">
                    <h3 style="margin-bottom: 8px;">Send us a Message</h3>
                    <p style="color: hsl(var(--muted-foreground)); margin-bottom: 32px;">Fill out the form below and we'll get back to you within 24 hours.</p>
                    
                    <form id="contactForm" onsubmit="handleContactForm(event)">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required placeholder="+256 700 000 000">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message *</label>
                            <textarea id="message" name="message" rows="5" required placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            <span class="btn-text">Send Message</span>
                            <span class="btn-loading loading" style="display: none;"></span>
                        </button>
                    </form>
                    
                    <div id="form-message" style="margin-top: 16px;"></div>
                </div>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div>
                            <h4>Call Us</h4>
                            <p><?php echo get_option('contact_phone', '+256 123 456 789'); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📧</div>
                        <div>
                            <h4>Email Us</h4>
                            <p><?php echo get_option('contact_email', 'info@ntenjeruwifi.com'); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div>
                            <h4>Visit Us</h4>
                            <p><?php echo get_option('contact_address', 'Mukono, Uganda'); ?></p>
                        </div>
                    </div>
                    
                    <?php if (get_option('whatsapp_number')): ?>
                    <div class="contact-item">
                        <div class="contact-icon">💬</div>
                        <div>
                            <h4>WhatsApp</h4>
                            <p>
                                <a href="https://wa.me/<?php echo get_option('whatsapp_number'); ?>" 
                                   target="_blank" 
                                   style="color: inherit; text-decoration: none;">
                                    Chat with us on WhatsApp
                                </a>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Payment Modal -->
<div id="paymentModal" class="payment-modal">
    <div class="payment-modal-content">
        <button class="close-modal" onclick="closePaymentModal()">&times;</button>
        
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 80px; height: 80px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 32px;">
                📱
            </div>
            <h3 id="modal-title">Complete Payment</h3>
            <p id="modal-subtitle" style="color: hsl(var(--muted-foreground));">Enter your phone number to proceed</p>
        </div>
        
        <form id="paymentForm" onsubmit="processPayment(event)">
            <div class="form-group">
                <label for="modal-phone">Mobile Money Number</label>
                <input type="tel" id="modal-phone" name="phone_number" required 
                       placeholder="256700000000" 
                       pattern="[0-9]{12}" 
                       title="Please enter a valid 12-digit phone number">
                <small style="color: hsl(var(--muted-foreground));">Enter number without spaces (e.g., 256700000000)</small>
            </div>
            
            <div id="payment-summary" style="background: hsl(var(--muted)); padding: 20px; border-radius: var(--radius); margin: 20px 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Plan:</span>
                    <span id="summary-plan" style="font-weight: 600;"></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Amount:</span>
                    <span id="summary-amount" style="font-weight: 600; color: hsl(var(--primary));"></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Provider:</span>
                    <span id="summary-provider" style="font-weight: 600;"></span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                <span class="btn-text">Send Payment Request</span>
                <span class="btn-loading loading" style="display: none;"></span>
            </button>
        </form>
        
        <div id="payment-message" style="margin-top: 16px;"></div>
    </div>
</div>

<?php get_footer(); ?>