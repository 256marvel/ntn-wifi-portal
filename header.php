<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NTENJERU WIFI - Affordable and reliable internet connectivity in Mukono, Uganda. 24/7 availability with mobile money payments.">
    <meta name="keywords" content="internet, wifi, mukono, uganda, affordable, reliable, mobile money, MTN, airtel">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    
    <!-- SEO Meta Tags -->
    <meta property="og:title" content="NTENJERU WIFI - Stay Connected">
    <meta property="og:description" content="Reliable Internet Anytime, Anywhere in Mukono. Fast, affordable, and always available connectivity.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo home_url(); ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo home_url(); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="header">
    <div class="container">
        <div class="header-content">
            <!-- Logo -->
            <div class="logo">
                <div class="logo-icon">
                    <span style="color: white; font-size: 24px;">📶</span>
                </div>
                <div>
                    <h1>NTENJERU WIFI</h1>
                    <p>Stay Connected</p>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav class="nav" id="mainNav">
                <a href="#home" onclick="scrollToSection('home')">Home</a>
                <a href="#packages" onclick="scrollToSection('packages')">Packages</a>
                <a href="#about" onclick="scrollToSection('about')">About</a>
                <a href="#contact" onclick="scrollToSection('contact')">Contact</a>
            </nav>

            <!-- Contact Info & Mobile Menu -->
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="display: none; align-items: center; gap: 8px; font-size: 14px;" class="contact-info">
                    <span style="color: var(--primary);">📞</span>
                    <span style="font-weight: 500;">+256 763 643724</span>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileMenu()">
                    <span id="menuIcon">☰</span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mobile-nav" id="mobileNav" style="display: none; padding: 24px 0; border-top: 1px solid var(--border);">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <a href="#home" onclick="scrollToSection('home'); toggleMobileMenu();" style="text-decoration: none; color: var(--foreground); font-weight: 500;">Home</a>
                <a href="#packages" onclick="scrollToSection('packages'); toggleMobileMenu();" style="text-decoration: none; color: var(--foreground); font-weight: 500;">Packages</a>
                <a href="#about" onclick="scrollToSection('about'); toggleMobileMenu();" style="text-decoration: none; color: var(--foreground); font-weight: 500;">About</a>
                <a href="#contact" onclick="scrollToSection('contact'); toggleMobileMenu();" style="text-decoration: none; color: var(--foreground); font-weight: 500;">Contact</a>
                <div style="display: flex; align-items: center; gap: 8px; padding-top: 16px; border-top: 1px solid var(--border); font-size: 14px;">
                    <span style="color: var(--primary);">📞</span>
                    <span style="font-weight: 500;">+256 763 643724</span>
                </div>
            </div>
        </nav>
    </div>
</header>