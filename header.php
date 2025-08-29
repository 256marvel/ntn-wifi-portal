<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NTENJERU WIFI - Affordable and reliable internet connectivity in Mukono, Uganda. 24/7 availability with mobile money payments.">
    <meta name="keywords" content="internet, wifi, mukono, uganda, affordable, reliable, mobile money, MTN, airtel">
    
    <!-- SEO Meta Tags -->
    <meta property="og:title" content="<?php echo get_option('site_title', 'NTENJERU WIFI'); ?> - Stay Connected">
    <meta property="og:description" content="Reliable Internet Anytime, Anywhere in Mukono. Fast, affordable, and always available connectivity.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo home_url(); ?>">
    <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/hero-bg.jpg">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo get_option('site_title', 'NTENJERU WIFI'); ?>">
    <meta name="twitter:description" content="Reliable Internet Anytime, Anywhere in Mukono">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo home_url(); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <title><?php echo get_option('site_title', 'NTENJERU WIFI'); ?> - Fast & Reliable Internet in Mukono</title>
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php if (!is_page_template('elementor_canvas')): ?>
<header class="header">
    <div class="container">
        <div class="header-content">
            <a href="<?php echo home_url(); ?>" class="logo">
                <div class="logo-icon">
                    📶
                </div>
                <div>
                    <h1><?php echo get_option('site_title', 'NTENJERU WIFI'); ?></h1>
                    <p><?php echo get_option('site_tagline', 'Fast & Reliable Internet'); ?></p>
                </div>
            </a>
            
            <!-- Optional navigation (can be managed via WordPress Customizer) -->
            <?php if (has_nav_menu('primary')): ?>
            <nav class="nav">
                <?php wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'items_wrap' => '%3$s'
                )); ?>
            </nav>
            <?php endif; ?>
            
            <div style="display: flex; align-items: center; gap: 16px;">
                <!-- WhatsApp Contact Button -->
                <?php if (get_option('whatsapp_number')): ?>
                <a href="https://wa.me/<?php echo get_option('whatsapp_number'); ?>" 
                   target="_blank" 
                   class="btn btn-outline"
                   style="font-size: 14px; padding: 8px 16px;">
                    💬 WhatsApp
                </a>
                <?php endif; ?>
                
                <!-- Quick Contact -->
                <a href="tel:<?php echo get_option('contact_phone', '+256123456789'); ?>" 
                   class="btn btn-primary"
                   style="font-size: 14px; padding: 8px 16px;">
                    📞 Call Now
                </a>
            </div>
        </div>
    </div>
</header>
<?php endif; ?>