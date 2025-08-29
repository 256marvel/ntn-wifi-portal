<?php if (!is_page_template('elementor_canvas')): ?>
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php echo get_option('site_title', 'NTENJERU WIFI'); ?></h3>
                <p>Providing fast, reliable, and affordable internet connectivity to homes and businesses in Mukono, Uganda.</p>
                <p><strong>📞 <?php echo get_option('contact_phone', '+256 123 456 789'); ?></strong></p>
                <p><strong>📧 <?php echo get_option('contact_email', 'info@ntenjeruwifi.com'); ?></strong></p>
            </div>
            
            <div class="footer-section">
                <h3>Our Services</h3>
                <p>24-Hour Internet Access</p>
                <p>Weekly Internet Packages</p>
                <p>Monthly Unlimited Plans</p>
                <p>Business Internet Solutions</p>
                <p>Technical Support 24/7</p>
            </div>
            
            <div class="footer-section">
                <h3>Payment Methods</h3>
                <p>📱 MTN Mobile Money</p>
                <p>📱 Airtel Money</p>
                <p>💳 Bank Transfer</p>
                <p>💰 Cash Payment</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="#packages">View Packages</a></p>
                <p><a href="#about">About Us</a></p>
                <p><a href="#contact">Contact Support</a></p>
                <?php if (get_option('whatsapp_number')): ?>
                <p><a href="https://wa.me/<?php echo get_option('whatsapp_number'); ?>" target="_blank">WhatsApp Us</a></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo get_option('site_title', 'NTENJERU WIFI'); ?>. All rights reserved.</p>
            <p>Designed for modern internet connectivity in Uganda 🇺🇬</p>
        </div>
    </div>
</footer>

<?php if (get_option('whatsapp_number')): ?>
<a href="https://wa.me/<?php echo get_option('whatsapp_number'); ?>" class="whatsapp-float" target="_blank">
    💬
</a>
<style>
.whatsapp-float {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #25D366;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    z-index: 1000;
    transition: all 0.3s ease;
}
.whatsapp-float:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
}
</style>
<?php endif; ?>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>