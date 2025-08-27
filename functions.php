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
        
        // Call MTN payment API
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
    // Handle Airtel Money payments (placeholder for future implementation)
    else if ($provider === 'Airtel') {
        wp_send_json_error('Airtel Money integration coming soon! Please use MTN Mobile Money or contact us at +256 763 643724.');
    } 
    else {
        wp_send_json_error('Unsupported payment provider.');
    }
}
add_action('wp_ajax_handle_payment', 'handle_payment_processing');
add_action('wp_ajax_nopriv_handle_payment', 'handle_payment_processing');

// Add custom admin menu for payment settings
function ntenjeru_wifi_admin_menu() {
    add_options_page(
        'NTENJERU WIFI Settings',
        'NTENJERU WIFI',
        'manage_options',
        'ntenjeru-wifi-settings',
        'ntenjeru_wifi_settings_page'
    );
}
add_action('admin_menu', 'ntenjeru_wifi_admin_menu');

// Settings page callback
function ntenjeru_wifi_settings_page() {
    ?>
    <div class="wrap">
        <h1>NTENJERU WIFI Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ntenjeru_wifi_settings');
            do_settings_sections('ntenjeru_wifi_settings');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">MTN Mobile Money API Key</th>
                    <td>
                        <input type="text" name="mtn_api_key" value="<?php echo esc_attr(get_option('mtn_api_key')); ?>" class="regular-text" />
                        <p class="description">Enter your MTN Mobile Money API key for payment processing.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Airtel Money API Key</th>
                    <td>
                        <input type="text" name="airtel_api_key" value="<?php echo esc_attr(get_option('airtel_api_key')); ?>" class="regular-text" />
                        <p class="description">Enter your Airtel Money API key for payment processing.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Mikrotik API Endpoint</th>
                    <td>
                        <input type="url" name="mikrotik_api_endpoint" value="<?php echo esc_attr(get_option('mikrotik_api_endpoint')); ?>" class="regular-text" />
                        <p class="description">Enter your Mikrotik RouterOS API endpoint for user access management.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Mikrotik API Username</th>
                    <td>
                        <input type="text" name="mikrotik_username" value="<?php echo esc_attr(get_option('mikrotik_username')); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Mikrotik API Password</th>
                    <td>
                        <input type="password" name="mikrotik_password" value="<?php echo esc_attr(get_option('mikrotik_password')); ?>" class="regular-text" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        
        <hr>
        
        <h2>Integration Instructions</h2>
        <div class="notice notice-info">
            <p><strong>To complete the payment integration:</strong></p>
            <ol>
                <li>Sign up for MTN Mobile Money Sandbox/Production API access</li>
                <li>Sign up for Airtel Money API access</li>
                <li>Configure your Mikrotik router for API access</li>
                <li>Update the payment processing functions in functions.php</li>
                <li>Test the integration thoroughly before going live</li>
            </ol>
        </div>
    </div>
    <?php
}

// Register settings
function ntenjeru_wifi_register_settings() {
    register_setting('ntenjeru_wifi_settings', 'mtn_api_key');
    register_setting('ntenjeru_wifi_settings', 'airtel_api_key');
    register_setting('ntenjeru_wifi_settings', 'mikrotik_api_endpoint');
    register_setting('ntenjeru_wifi_settings', 'mikrotik_username');
    register_setting('ntenjeru_wifi_settings', 'mikrotik_password');
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