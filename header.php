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

    <nav class="bg-white/10 backdrop-blur-md border-b border-white/20 relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-20">
                <div class="text-center">
                    <h1 class="text-2xl md:text-3xl font-bold text-white rainbow-text"><?php echo get_option('site_title', 'NTENJERU WIFI'); ?></h1>
                    <p class="text-white/80 text-sm mt-1"><?php echo get_option('site_tagline', 'Fast & Reliable Internet'); ?></p>
                </div>
            </div>
        </div>
    </nav>