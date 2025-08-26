<footer class="footer">
    <!-- Main Footer Content -->
    <div class="container">
        <div class="footer-grid">
            <!-- Company Info -->
            <div class="footer-section">
                <div class="logo" style="margin-bottom: 24px;">
                    <div class="logo-icon">
                        <span style="color: white; font-size: 24px;">📶</span>
                    </div>
                    <div>
                        <h3 style="color: white; margin-bottom: 4px;">NTENJERU WIFI</h3>
                        <p style="font-size: 14px; color: #d1d5db; margin: 0;">Stay Connected</p>
                    </div>
                </div>
                <p style="color: #d1d5db; line-height: 1.6; margin-bottom: 24px;">
                    Providing affordable and reliable internet connectivity throughout Mukono, Uganda. 
                    Keeping you connected for work, study, and entertainment.
                </p>
                
                <!-- Social Media -->
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <span style="font-size: 20px;">📘</span>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <span style="font-size: 20px;">🐦</span>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <span style="font-size: 20px;">📷</span>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home" onclick="scrollToSection('home')">Home</a></li>
                    <li><a href="#packages" onclick="scrollToSection('packages')">Packages</a></li>
                    <li><a href="#about" onclick="scrollToSection('about')">About</a></li>
                    <li><a href="#contact" onclick="scrollToSection('contact')">Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-section">
                <h4>Our Services</h4>
                <ul>
                    <li>High-Speed Internet</li>
                    <li>WiFi Installation</li>
                    <li>24/7 Technical Support</li>
                    <li>Network Solutions</li>
                    <li>Business Packages</li>
                    <li>Home Internet</li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h4>Contact Info</h4>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="color: var(--primary); font-size: 20px;">📞</span>
                        <div>
                            <p style="font-weight: 500; margin: 0;">+256 763 643724</p>
                            <p style="font-size: 14px; color: #d1d5db; margin: 0;">Call or WhatsApp</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="color: var(--primary); font-size: 20px;">📍</span>
                        <div>
                            <p style="font-weight: 500; margin: 0;">Ntenjeru, Mukono</p>
                            <p style="font-size: 14px; color: #d1d5db; margin: 0;">Uganda, East Africa</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="color: var(--primary); font-size: 20px;">💬</span>
                        <div>
                            <button onclick="window.open('https://wa.me/256763643724')" style="background: none; border: none; color: white; font-weight: 500; cursor: pointer; padding: 0; font-size: 16px;">
                                WhatsApp Support
                            </button>
                            <p style="font-size: 14px; color: #d1d5db; margin: 0;">24/7 Available</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div style="color: #d1d5db; font-size: 14px;">
                    © <?php echo date('Y'); ?> NTENJERU WIFI. All rights reserved. | Affordable Internet in Mukono.
                </div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Support</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/256763643724" class="whatsapp-float" aria-label="Chat on WhatsApp" target="_blank">
    <span style="font-size: 28px;">💬</span>
</a>

<!-- JavaScript -->
<script src="<?php echo get_template_directory_uri(); ?>/script.js"></script>

<?php wp_footer(); ?>
</body>
</html>