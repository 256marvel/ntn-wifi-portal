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
        
        <div class="text-center z-10 scroll-animate">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 rainbow-text">
                <?php echo get_option('hero_title', 'Stay Connected with Lightning-Fast WiFi ⚡'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto">
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
                    <a href="#packages" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
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
    <section id="packages" class="pricing">
        <div class="container">
            <div class="section-header">
                <h2>Choose Your Perfect Plan</h2>
                <p>Affordable internet packages designed for every need. Pay securely with MTN or Airtel Mobile Money.</p>
            </div>
            
            <div class="pricing-grid">
                <!-- 24 Hours Plan -->
                <div class="pricing-card">
                    <div class="plan-icon">
                        <span style="font-size: 32px;">🕐</span>
                    </div>
                    <h3>24 Hours</h3>
                    <p style="color: var(--muted-foreground); margin-bottom: 24px;">Perfect for 24 hours usage</p>
                    
                    <div style="margin-bottom: 32px;">
                        <span class="plan-price">1,000</span>
                        <span class="plan-currency">UGX</span>
                        <p style="font-size: 14px; color: var(--muted-foreground); margin-top: 8px;">for 24 hours</p>
                    </div>
                    
                    <ul class="plan-features">
                        <li><span class="check">✓</span> High-speed internet access</li>
                        <li><span class="check">✓</span> 24-hour unlimited browsing</li>
                        <li><span class="check">✓</span> Connect multiple devices</li>
                        <li><span class="check">✓</span> Basic customer support</li>
                        <li><span class="check">✓</span> Social media access</li>
                        <li><span class="check">✓</span> Video streaming capability</li>
                    </ul>
                    
                    <div class="payment-buttons">
                        <button class="btn btn-mtn" onclick="handlePayment('24 Hours', '1,000', 'MTN')">
                            📱 Pay with MTN
                        </button>
                        <button class="btn btn-airtel" onclick="handlePayment('24 Hours', '1,000', 'Airtel')">
                            📱 Pay with Airtel
                        </button>
                    </div>
                    
                    <p style="font-size: 12px; color: var(--muted-foreground); text-align: center; margin-top: 16px;">
                        Secure mobile money payment
                    </p>
                </div>
                
                <!-- 1 Week Plan -->
                <div class="pricing-card popular">
                    <div class="popular-badge">Most Popular</div>
                    <div class="plan-icon">
                        <span style="font-size: 32px;">📅</span>
                    </div>
                    <h3>1 Week</h3>
                    <p style="color: var(--muted-foreground); margin-bottom: 24px;">Perfect for 7 days usage</p>
                    
                    <div style="margin-bottom: 32px;">
                        <span class="plan-price">7,000</span>
                        <span class="plan-currency">UGX</span>
                        <p style="font-size: 14px; color: var(--muted-foreground); margin-top: 8px;">for 7 days</p>
                    </div>
                    
                    <ul class="plan-features">
                        <li><span class="check">✓</span> High-speed internet access</li>
                        <li><span class="check">✓</span> Full week unlimited browsing</li>
                        <li><span class="check">✓</span> Connect unlimited devices</li>
                        <li><span class="check">✓</span> Priority customer support</li>
                        <li><span class="check">✓</span> HD video streaming</li>
                        <li><span class="check">✓</span> File downloads & uploads</li>
                        <li><span class="check">✓</span> Email and work applications</li>
                        <li><span class="check">✓</span> Online gaming support</li>
                    </ul>
                    
                    <div class="payment-buttons">
                        <button class="btn btn-mtn" onclick="handlePayment('1 Week', '7,000', 'MTN')">
                            📱 Pay with MTN
                        </button>
                        <button class="btn btn-airtel" onclick="handlePayment('1 Week', '7,000', 'Airtel')">
                            📱 Pay with Airtel
                        </button>
                    </div>
                    
                    <p style="font-size: 12px; color: var(--muted-foreground); text-align: center; margin-top: 16px;">
                        Secure mobile money payment
                    </p>
                </div>
                
                <!-- 1 Month Plan -->
                <div class="pricing-card">
                    <div class="plan-icon">
                        <span style="font-size: 32px;">🏆</span>
                    </div>
                    <h3>1 Month</h3>
                    <p style="color: var(--muted-foreground); margin-bottom: 24px;">Perfect for 30 days usage</p>
                    
                    <div style="margin-bottom: 32px;">
                        <span class="plan-price">25,000</span>
                        <span class="plan-currency">UGX</span>
                        <p style="font-size: 14px; color: var(--muted-foreground); margin-top: 8px;">for 30 days</p>
                    </div>
                    
                    <ul class="plan-features">
                        <li><span class="check">✓</span> Maximum speed internet access</li>
                        <li><span class="check">✓</span> Full month unlimited browsing</li>
                        <li><span class="check">✓</span> Connect unlimited devices</li>
                        <li><span class="check">✓</span> 24/7 premium support</li>
                        <li><span class="check">✓</span> 4K video streaming</li>
                        <li><span class="check">✓</span> Large file transfers</li>
                        <li><span class="check">✓</span> Business applications</li>
                        <li><span class="check">✓</span> Online gaming & streaming</li>
                        <li><span class="check">✓</span> Technical support priority</li>
                        <li><span class="check">✓</span> Speed guarantee</li>
                    </ul>
                    
                    <div class="payment-buttons">
                        <button class="btn btn-mtn" onclick="handlePayment('1 Month', '25,000', 'MTN')">
                            📱 Pay with MTN
                        </button>
                        <button class="btn btn-airtel" onclick="handlePayment('1 Month', '25,000', 'Airtel')">
                            📱 Pay with Airtel
                        </button>
                    </div>
                    
                    <p style="font-size: 12px; color: var(--muted-foreground); text-align: center; margin-top: 16px;">
                        Secure mobile money payment
                    </p>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 48px;">
                <p style="color: var(--muted-foreground);">
                    All plans include unlimited data usage • No hidden fees • Instant activation after payment
                </p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us">
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
                    <p style="color: var(--muted-foreground);">Round-the-clock internet access with 99.9% uptime guarantee. Stay connected whenever you need it.</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon green">
                        <span style="font-size: 40px; color: white;">💰</span>
                    </div>
                    <h3>Affordable Prices</h3>
                    <p style="color: var(--muted-foreground);">Competitive pricing that fits your budget. Get premium internet without breaking the bank.</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon purple">
                        <span style="font-size: 40px; color: white;">📶</span>
                    </div>
                    <h3>Reliable & Fast Connectivity</h3>
                    <p style="color: var(--muted-foreground);">High-speed internet with consistent performance. Stream, work, and browse without interruptions.</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon orange">
                        <span style="font-size: 40px; color: white;">🎧</span>
                    </div>
                    <h3>Friendly Customer Support</h3>
                    <p style="color: var(--muted-foreground);">Dedicated support team ready to help. Quick response times and technical assistance when you need it.</p>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 64px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(0, 123, 255, 0.1); color: var(--primary); padding: 12px 24px; border-radius: 50px; font-weight: 500;">
                    <span>📶</span>
                    <span>Trusted by 1000+ customers in Mukono</span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
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
                    
                    <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--border); text-align: center;">
                        <p style="font-size: 14px; color: var(--muted-foreground); margin-bottom: 8px;">Serving Mukono since 2020</p>
                        <div style="display: flex; justify-content: center; gap: 4px; margin-bottom: 4px;">
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                            <span style="color: #fbbf24;">★</span>
                        </div>
                        <p style="font-size: 12px; color: var(--muted-foreground);">4.9/5 Customer Rating</p>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 80px; text-align: center;">
                <div style="max-width: 800px; margin: 0 auto; background: linear-gradient(to right, rgba(0, 123, 255, 0.05), rgba(0, 123, 255, 0.1)); border-radius: 24px; padding: 48px;">
                    <h3 style="margin-bottom: 16px;">Our Mission</h3>
                    <p style="font-size: 1.25rem; color: var(--muted-foreground);">
                        "To bridge the digital divide by providing affordable, reliable internet access to every home and business in Mukono, empowering our community through connectivity."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2>Get in Touch</h2>
                <p>Ready to get connected? Contact us today and let's set up your perfect internet package.</p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-form">
                    <h3 style="margin-bottom: 8px;">Send us a Message</h3>
                    <p style="color: var(--muted-foreground); margin-bottom: 32px;">Fill out the form below and we'll get back to you within 24 hours.</p>
                    
                    <form id="contactForm" onsubmit="handleContactForm(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Your full name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="+256 XXX XXXXXX" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="your.email@example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" class="form-control" rows="5" placeholder="Tell us about your internet needs or ask any questions..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            <span style="margin-right: 8px;">📧</span>
                            Send Message
                        </button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <div class="contact-item" onclick="window.open('tel:+256763643724')">
                        <div class="contact-item-content">
                            <div class="contact-icon green">
                                <span style="font-size: 24px;">📞</span>
                            </div>
                            <div>
                                <h4>Phone & WhatsApp</h4>
                                <p style="color: var(--primary); font-weight: 500; margin: 4px 0;">+256 763 643724</p>
                                <p style="font-size: 14px; color: var(--muted-foreground); margin: 0;">Call or text us anytime</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item" onclick="window.open('https://wa.me/256763643724')">
                        <div class="contact-item-content">
                            <div class="contact-icon blue">
                                <span style="font-size: 24px;">💬</span>
                            </div>
                            <div>
                                <h4>WhatsApp Chat</h4>
                                <p style="color: var(--primary); font-weight: 500; margin: 4px 0;">Chat with Us</p>
                                <p style="font-size: 14px; color: var(--muted-foreground); margin: 0;">Get instant support</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item" onclick="window.open('https://maps.google.com/?q=Ntenjeru,Uganda')">
                        <div class="contact-item-content">
                            <div class="contact-icon purple">
                                <span style="font-size: 24px;">📍</span>
                            </div>
                            <div>
                                <h4>Location</h4>
                                <p style="color: var(--primary); font-weight: 500; margin: 4px 0;">Ntenjeru, Mukono</p>
                                <p style="font-size: 14px; color: var(--muted-foreground); margin: 0;">Uganda, East Africa</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-item-content">
                            <div class="contact-icon orange">
                                <span style="font-size: 24px;">🕐</span>
                            </div>
                            <div>
                                <h4>Service Hours</h4>
                                <p style="color: var(--primary); font-weight: 500; margin: 4px 0;">24/7 Available</p>
                                <p style="font-size: 14px; color: var(--muted-foreground); margin: 0;">Always here for you</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="whatsapp-cta">
                        <div style="font-size: 48px; margin-bottom: 16px;">💬</div>
                        <h3 style="margin-bottom: 8px;">Need Instant Help?</h3>
                        <p style="margin-bottom: 24px; opacity: 0.9;">
                            Chat with us on WhatsApp for immediate assistance with your internet connection.
                        </p>
                        <button class="btn" style="background: white; color: #059669; font-weight: 600;" onclick="window.open('https://wa.me/256763643724')">
                            <span style="margin-right: 8px;">💬</span>
                            Chat on WhatsApp
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="map-placeholder">
                <div>
                    <div style="font-size: 64px; margin-bottom: 16px;">📍</div>
                    <h3 style="margin-bottom: 8px;">Ntenjeru, Mukono</h3>
                    <p style="color: var(--muted-foreground); margin-bottom: 16px;">Uganda, East Africa</p>
                    <button class="btn btn-outline" onclick="window.open('https://maps.google.com/?q=Ntenjeru,Uganda')">
                        View on Google Maps
                    </button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>