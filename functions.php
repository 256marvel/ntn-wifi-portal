<?php
/**
 * NTENJERU WIFI Theme Functions
 */

// Enqueue styles and scripts
function ntenjeru_wifi_enqueue_assets() {
    // Enqueue main stylesheet
    wp_enqueue_style('ntenjeru-wifi-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue JavaScript
    wp_enqueue_script('ntenjeru-wifi-script', get_template_directory_uri() . '/script.js', array(), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('ntenjeru-wifi-script', 'ntenjeru_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ntenjeru_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'ntenjeru_wifi_enqueue_assets');

// Theme setup
function ntenjeru_wifi_setup() {
    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add theme support for title tag
    add_theme_support('title-tag');
    
    // Add theme support for HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu',
    ));
}
add_action('after_setup_theme', 'ntenjeru_wifi_setup');

// Handle contact form submission
function handle_contact_form_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'ntenjeru_nonce')) {
        wp_die('Security check failed');
    }
    
    // Sanitize form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Validate required fields
    if (empty($name) || empty($phone) || empty($message)) {
        wp_send_json_error('Please fill in all required fields.');
    }
    
    // Prepare email
    $to = get_option('admin_email');
    $subject = 'New Contact Form Submission - NTENJERU WIFI';
    $body = "New contact form submission:\n\n";
    $body .= "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Message:\n$message\n";
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    if (!empty($email)) {
        $headers[] = "Reply-To: $email";
    }
    
    // Send email
    $sent = wp_mail($to, $subject, $body, $headers);
    
    if ($sent) {
        wp_send_json_success('Thank you for contacting us. We\'ll get back to you within 24 hours.');
    } else {
        wp_send_json_error('Sorry, there was an error sending your message. Please try again.');
    }
}
add_action('wp_ajax_handle_contact_form', 'handle_contact_form_submission');
add_action('wp_ajax_nopriv_handle_contact_form', 'handle_contact_form_submission');

// Handle MTN Mobile Money payment processing
function handle_payment_processing() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'ntenjeru_nonce')) {
        wp_die('Security check failed');
    }
    
    // Sanitize payment data
    $plan = sanitize_text_field($_POST['plan']);
    $amount = sanitize_text_field($_POST['amount']);
    $provider = sanitize_text_field($_POST['provider']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    
    // Validate required fields
    if (empty($plan) || empty($amount) || empty($provider) || empty($phone_number)) {
        wp_send_json_error('Missing payment information.');
    }
    
    // Handle MTN Mobile Money payments
    if ($provider === 'MTN') {
        // Prepare data for MTN API
        $payment_data = array(
            'phone' => $phone_number,
            'amount' => $amount,
            'plan' => $plan
        );
        
        // Call MTN payment API using the new handler
        $response = wp_remote_post(get_template_directory_uri() . '/momo_request.php', array(
            'body' => $payment_data,
            'timeout' => 45,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            )
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error('Payment service temporarily unavailable. Please try again.');
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['success']) && $data['success']) {
            // Log successful payment attempt
            error_log("MTN Payment initiated: Phone: $phone_number, Amount: $amount, Plan: $plan, Reference: " . $data['reference_id']);
            
            wp_send_json_success(array(
                'message' => 'Payment request sent! Please check your phone for the MTN Mobile Money prompt.',
                'reference_id' => $data['reference_id'],
                'plan' => $plan,
                'amount' => $amount,
                'provider' => $provider
            ));
        } else {
            $error_message = isset($data['error']) ? $data['error'] : 'Payment failed. Please try again.';
            wp_send_json_error($error_message);
        }
    } 
    // Handle Airtel Money payments
    else if ($provider === 'Airtel') {
        // Check if Airtel is configured
        if (!get_option('airtel_client_id') || !get_option('airtel_client_secret')) {
            wp_send_json_error('Airtel Money is not configured yet. Please use MTN Mobile Money or contact us.');
        }
        
        // Prepare data for Airtel API
        $payment_data = array(
            'phone' => $phone_number,
            'amount' => $amount,
            'plan' => $plan
        );
        
        // Call Airtel payment API
        $response = wp_remote_post(get_template_directory_uri() . '/airtel_request.php', array(
            'body' => $payment_data,
            'timeout' => 45,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            )
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error('Airtel payment service temporarily unavailable. Please try again.');
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['success']) && $data['success']) {
            // Log successful payment attempt
            error_log("Airtel Payment initiated: Phone: $phone_number, Amount: $amount, Plan: $plan, Reference: " . $data['reference_id']);
            
            wp_send_json_success(array(
                'message' => 'Payment request sent! Please check your phone for the Airtel Money prompt.',
                'reference_id' => $data['reference_id'],
                'plan' => $plan,
                'amount' => $amount,
                'provider' => $provider
            ));
        } else {
            $error_message = isset($data['error']) ? $data['error'] : 'Airtel payment failed. Please try again.';
            wp_send_json_error($error_message);
        }
    }
    else {
        wp_send_json_error('Unsupported payment provider.');
    }
}
add_action('wp_ajax_handle_payment', 'handle_payment_processing');
add_action('wp_ajax_nopriv_handle_payment', 'handle_payment_processing');

// Add custom admin menu for comprehensive theme settings
function ntenjeru_wifi_admin_menu() {
    add_menu_page(
        'NTENJERU WIFI Pro Settings',
        'NTENJERU WIFI Pro',
        'manage_options',
        'ntenjeru-wifi-settings',
        'ntenjeru_wifi_settings_page',
        'dashicons-wifi',
        30
    );
    
    // Add sub-pages
    add_submenu_page(
        'ntenjeru-wifi-settings',
        'Payment Settings',
        'Payment Settings',
        'manage_options',
        'ntenjeru-payment-settings',
        'ntenjeru_payment_settings_page'
    );
    
    add_submenu_page(
        'ntenjeru-wifi-settings',
        'Site Customization',
        'Site Customization',
        'manage_options',
        'ntenjeru-customization',
        'ntenjeru_customization_settings_page'
    );
}
add_action('admin_menu', 'ntenjeru_wifi_admin_menu');

// Main Settings page callback
function ntenjeru_wifi_settings_page() {
    ?>
    <div class="wrap">
        <h1>NTENJERU WIFI Pro - Dashboard</h1>
        
        <div class="notice notice-success">
            <p><strong>Welcome to NTENJERU WIFI Pro!</strong> Professional WordPress theme for ISP businesses.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <div class="postbox">
                <div class="postbox-header">
                    <h2>Payment Integration Status</h2>
                </div>
                <div class="inside">
                    <p><strong>MTN Mobile Money:</strong> 
                        <?php echo get_option('mtn_api_key') ? '<span style="color: green;">✓ Configured</span>' : '<span style="color: red;">✗ Not Configured</span>'; ?>
                    </p>
                    <p><strong>Airtel Money:</strong> 
                        <?php echo get_option('airtel_client_id') ? '<span style="color: green;">✓ Configured</span>' : '<span style="color: red;">✗ Not Configured</span>'; ?>
                    </p>
                    <a href="<?php echo admin_url('admin.php?page=ntenjeru-payment-settings'); ?>" class="button button-primary">Configure Payments</a>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header">
                    <h2>Site Customization</h2>
                </div>
                <div class="inside">
                    <p>Customize your site appearance, colors, and content without touching code.</p>
                    <a href="<?php echo admin_url('admin.php?page=ntenjeru-customization'); ?>" class="button button-primary">Customize Site</a>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header">
                    <h2>Quick Actions</h2>
                </div>
                <div class="inside">
                    <p><a href="<?php echo home_url(); ?>" class="button" target="_blank">View Site</a></p>
                    <p><a href="<?php echo admin_url('admin.php?page=ntenjeru-payment-settings'); ?>" class="button">Payment Settings</a></p>
                    <p><a href="<?php echo admin_url('admin.php?page=ntenjeru-customization'); ?>" class="button">Site Settings</a></p>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Payment Settings page
function ntenjeru_payment_settings_page() {
    if (isset($_POST['submit'])) {
        // Save MTN settings
        update_option('mtn_api_user_id', sanitize_text_field($_POST['mtn_api_user_id']));
        update_option('mtn_api_key', sanitize_text_field($_POST['mtn_api_key']));
        update_option('mtn_subscription_key', sanitize_text_field($_POST['mtn_subscription_key']));
        update_option('mtn_environment', sanitize_text_field($_POST['mtn_environment']));
        update_option('mtn_callback_url', esc_url_raw($_POST['mtn_callback_url']));
        
        // Save Airtel settings
        update_option('airtel_client_id', sanitize_text_field($_POST['airtel_client_id']));
        update_option('airtel_client_secret', sanitize_text_field($_POST['airtel_client_secret']));
        update_option('airtel_api_key', sanitize_text_field($_POST['airtel_api_key']));
        update_option('airtel_environment', sanitize_text_field($_POST['airtel_environment']));
        update_option('airtel_callback_url', esc_url_raw($_POST['airtel_callback_url']));
        
        echo '<div class="notice notice-success"><p>Payment settings saved successfully!</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Payment Settings</h1>
        
        <form method="post">
            <div class="postbox">
                <div class="postbox-header">
                    <h2>MTN Mobile Money Configuration</h2>
                </div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">API User ID</th>
                            <td>
                                <input type="text" name="mtn_api_user_id" value="<?php echo esc_attr(get_option('mtn_api_user_id', 'b118d864-b932-41fa-bc53-17d2841772ee')); ?>" class="regular-text" required />
                                <p class="description">Your MTN Mobile Money API User ID</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">API Key</th>
                            <td>
                                <input type="text" name="mtn_api_key" value="<?php echo esc_attr(get_option('mtn_api_key', '127fb39cbddc47dc8220c3ebd4244cc2')); ?>" class="regular-text" required />
                                <p class="description">Your MTN Mobile Money API Key</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Subscription Key</th>
                            <td>
                                <input type="text" name="mtn_subscription_key" value="<?php echo esc_attr(get_option('mtn_subscription_key', '09d303b8c9e94eb1a530d68418848f6a')); ?>" class="regular-text" required />
                                <p class="description">Your MTN Mobile Money Subscription Key</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Environment</th>
                            <td>
                                <select name="mtn_environment">
                                    <option value="mtnuganda" <?php selected(get_option('mtn_environment', 'mtnuganda'), 'mtnuganda'); ?>>MTN Uganda</option>
                                    <option value="sandbox" <?php selected(get_option('mtn_environment'), 'sandbox'); ?>>Sandbox</option>
                                </select>
                                <p class="description">Select your MTN environment</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Callback URL</th>
                            <td>
                                <input type="url" name="mtn_callback_url" value="<?php echo esc_attr(get_option('mtn_callback_url', home_url('/momo-callback/'))); ?>" class="regular-text" />
                                <p class="description">Callback URL for payment notifications</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header">
                    <h2>Airtel Money Configuration</h2>
                </div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Client ID</th>
                            <td>
                                <input type="text" name="airtel_client_id" value="<?php echo esc_attr(get_option('airtel_client_id')); ?>" class="regular-text" />
                                <p class="description">Your Airtel Money Client ID</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Client Secret</th>
                            <td>
                                <input type="password" name="airtel_client_secret" value="<?php echo esc_attr(get_option('airtel_client_secret')); ?>" class="regular-text" />
                                <p class="description">Your Airtel Money Client Secret</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">API Key</th>
                            <td>
                                <input type="text" name="airtel_api_key" value="<?php echo esc_attr(get_option('airtel_api_key')); ?>" class="regular-text" />
                                <p class="description">Your Airtel Money API Key</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Environment</th>
                            <td>
                                <select name="airtel_environment">
                                    <option value="sandbox" <?php selected(get_option('airtel_environment', 'sandbox'), 'sandbox'); ?>>Sandbox</option>
                                    <option value="production" <?php selected(get_option('airtel_environment'), 'production'); ?>>Production</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Callback URL</th>
                            <td>
                                <input type="url" name="airtel_callback_url" value="<?php echo esc_attr(get_option('airtel_callback_url', home_url('/airtel-callback/'))); ?>" class="regular-text" />
                                <p class="description">Callback URL for payment notifications</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php submit_button('Save Payment Settings', 'primary', 'submit', false); ?>
        </form>
    </div>
    <?php
}

// Site Customization page
function ntenjeru_customization_settings_page() {
    if (isset($_POST['submit'])) {
        update_option('site_title', sanitize_text_field($_POST['site_title']));
        update_option('site_tagline', sanitize_text_field($_POST['site_tagline']));
        update_option('hero_title', sanitize_text_field($_POST['hero_title']));
        update_option('hero_subtitle', sanitize_textarea_field($_POST['hero_subtitle']));
        update_option('primary_color', sanitize_hex_color($_POST['primary_color']));
        update_option('secondary_color', sanitize_hex_color($_POST['secondary_color']));
        update_option('contact_phone', sanitize_text_field($_POST['contact_phone']));
        update_option('contact_email', sanitize_email($_POST['contact_email']));
        update_option('business_address', sanitize_textarea_field($_POST['business_address']));
        update_option('enable_floating_graphics', sanitize_text_field($_POST['enable_floating_graphics']));
        
        echo '<div class="notice notice-success"><p>Customization settings saved successfully!</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Site Customization</h1>
        
        <form method="post">
            <div class="postbox">
                <div class="postbox-header">
                    <h2>Site Identity</h2>
                </div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Site Title</th>
                            <td>
                                <input type="text" name="site_title" value="<?php echo esc_attr(get_option('site_title', 'NTENJERU WIFI')); ?>" class="regular-text" />
                                <p class="description">Your business name</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Site Tagline</th>
                            <td>
                                <input type="text" name="site_tagline" value="<?php echo esc_attr(get_option('site_tagline', 'Fast & Reliable Internet')); ?>" class="regular-text" />
                                <p class="description">Short description under your business name</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Hero Title</th>
                            <td>
                                <input type="text" name="hero_title" value="<?php echo esc_attr(get_option('hero_title', 'Stay Connected with Lightning-Fast WiFi')); ?>" class="large-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Hero Subtitle</th>
                            <td>
                                <textarea name="hero_subtitle" rows="3" class="large-text"><?php echo esc_textarea(get_option('hero_subtitle', 'Experience blazing-fast internet speeds with our reliable WiFi packages. Perfect for streaming, gaming, and working from home.')); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header">
                    <h2>Contact Information</h2>
                </div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Phone Number</th>
                            <td>
                                <input type="tel" name="contact_phone" value="<?php echo esc_attr(get_option('contact_phone', '+256 763 643724')); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Email Address</th>
                            <td>
                                <input type="email" name="contact_email" value="<?php echo esc_attr(get_option('contact_email', 'info@ntenjeruwifi.com')); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Business Address</th>
                            <td>
                                <textarea name="business_address" rows="3" class="large-text"><?php echo esc_textarea(get_option('business_address', 'Ntenjeru, Mukono District, Uganda')); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header">
                    <h2>Visual Settings</h2>
                </div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Primary Color</th>
                            <td>
                                <input type="color" name="primary_color" value="<?php echo esc_attr(get_option('primary_color', '#8b5cf6')); ?>" />
                                <p class="description">Main brand color</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Secondary Color</th>
                            <td>
                                <input type="color" name="secondary_color" value="<?php echo esc_attr(get_option('secondary_color', '#06b6d4')); ?>" />
                                <p class="description">Accent color</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Enable Floating Graphics</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="enable_floating_graphics" value="1" <?php checked(get_option('enable_floating_graphics', '1'), '1'); ?> />
                                    Enable floating tech graphics animation
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php submit_button('Save Customization Settings', 'primary', 'submit', false); ?>
        </form>
    </div>
    <?php
}

// Register all settings
function ntenjeru_wifi_register_settings() {
    // Payment settings
    register_setting('ntenjeru_payment_settings', 'mtn_api_user_id');
    register_setting('ntenjeru_payment_settings', 'mtn_api_key');
    register_setting('ntenjeru_payment_settings', 'mtn_subscription_key');
    register_setting('ntenjeru_payment_settings', 'mtn_environment');
    register_setting('ntenjeru_payment_settings', 'mtn_callback_url');
    register_setting('ntenjeru_payment_settings', 'airtel_client_id');
    register_setting('ntenjeru_payment_settings', 'airtel_client_secret');
    register_setting('ntenjeru_payment_settings', 'airtel_api_key');
    register_setting('ntenjeru_payment_settings', 'airtel_environment');
    register_setting('ntenjeru_payment_settings', 'airtel_callback_url');
    
    // Customization settings
    register_setting('ntenjeru_customization_settings', 'site_title');
    register_setting('ntenjeru_customization_settings', 'site_tagline');
    register_setting('ntenjeru_customization_settings', 'hero_title');
    register_setting('ntenjeru_customization_settings', 'hero_subtitle');
    register_setting('ntenjeru_customization_settings', 'primary_color');
    register_setting('ntenjeru_customization_settings', 'secondary_color');
    register_setting('ntenjeru_customization_settings', 'contact_phone');
    register_setting('ntenjeru_customization_settings', 'contact_email');
    register_setting('ntenjeru_customization_settings', 'business_address');
    register_setting('ntenjeru_customization_settings', 'enable_floating_graphics');
}
add_action('admin_init', 'ntenjeru_wifi_register_settings');

// Add structured data for SEO
function ntenjeru_wifi_structured_data() {
    $structured_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => 'NTENJERU WIFI',
        'description' => 'Affordable and reliable internet connectivity in Mukono, Uganda',
        'url' => home_url(),
        'telephone' => '+256763643724',
        'address' => array(
            '@type' => 'PostalAddress',
            'addressLocality' => 'Ntenjeru',
            'addressRegion' => 'Mukono',
            'addressCountry' => 'Uganda'
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => '0.3476',
            'longitude' => '32.6204'
        ),
        'openingHours' => 'Mo,Tu,We,Th,Fr,Sa,Su 00:00-23:59',
        'priceRange' => '1000-25000 UGX',
        'serviceArea' => array(
            '@type' => 'City',
            'name' => 'Mukono'
        )
    );
    
    echo '<script type="application/ld+json">' . json_encode($structured_data) . '</script>';
}
add_action('wp_head', 'ntenjeru_wifi_structured_data');

// Clean up WordPress head
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

// Security headers
function ntenjeru_wifi_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
}
add_action('send_headers', 'ntenjeru_wifi_security_headers');
?>