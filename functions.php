<?php
/**
 * NTENJERU WIFI Pro Theme Functions
 * Enhanced for Elementor compatibility and WordPress standards
 */

// Theme setup
function ntenjeru_wifi_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add Elementor support
    add_theme_support('elementor');
    add_theme_support('post-formats', array('aside', 'gallery', 'video', 'quote', 'link'));
    
    // Register navigation menus (kept for flexibility)
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu',
    ));
}
add_action('after_setup_theme', 'ntenjeru_wifi_setup');

// Enqueue styles and scripts
function ntenjeru_wifi_enqueue_assets() {
    wp_enqueue_style('ntenjeru-wifi-style', get_stylesheet_uri(), array(), '2.0.0');
    wp_enqueue_script('ntenjeru-wifi-script', get_template_directory_uri() . '/script.js', array('jquery'), '2.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('ntenjeru-wifi-script', 'ntenjeru_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ntenjeru_nonce'),
        'mtn_configured' => get_option('mtn_api_key') ? 'yes' : 'no',
        'airtel_configured' => get_option('airtel_client_id') ? 'yes' : 'no'
    ));
}
add_action('wp_enqueue_scripts', 'ntenjeru_wifi_enqueue_assets');

// Elementor compatibility
function ntenjeru_wifi_elementor_support() {
    add_post_type_support('page', 'elementor');
    add_post_type_support('post', 'elementor');
}
add_action('init', 'ntenjeru_wifi_elementor_support');

// Handle contact form submission
function handle_contact_form_submission() {
    if (!wp_verify_nonce($_POST['nonce'], 'ntenjeru_nonce')) {
        wp_die('Security check failed');
    }
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);
    
    if (empty($name) || empty($phone) || empty($message)) {
        wp_send_json_error('Please fill in all required fields.');
    }
    
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
    
    $sent = wp_mail($to, $subject, $body, $headers);
    
    if ($sent) {
        wp_send_json_success('Thank you for contacting us. We\'ll get back to you within 24 hours.');
    } else {
        wp_send_json_error('Sorry, there was an error sending your message. Please try again.');
    }
}
add_action('wp_ajax_handle_contact_form', 'handle_contact_form_submission');
add_action('wp_ajax_nopriv_handle_contact_form', 'handle_contact_form_submission');

// Handle payment processing
function handle_payment_processing() {
    if (!wp_verify_nonce($_POST['nonce'], 'ntenjeru_nonce')) {
        wp_die('Security check failed');
    }
    
    $plan = sanitize_text_field($_POST['plan']);
    $amount = sanitize_text_field($_POST['amount']);
    $provider = sanitize_text_field($_POST['provider']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    
    if (empty($plan) || empty($amount) || empty($provider) || empty($phone_number)) {
        wp_send_json_error('Missing payment information.');
    }
    
    // Prepare payment data
    $payment_data = array(
        'phone' => $phone_number,
        'amount' => str_replace(',', '', $amount), // Remove commas
        'plan' => $plan
    );
    
    if ($provider === 'MTN') {
        // Direct file inclusion for MTN processing
        $mtn_file = get_template_directory() . '/momo_request.php';
        if (file_exists($mtn_file)) {
            $_POST = array_merge($_POST, $payment_data);
            ob_start();
            include $mtn_file;
            $response = ob_get_clean();
            
            $data = json_decode($response, true);
            if ($data && isset($data['success']) && $data['success']) {
                wp_send_json_success(array(
                    'message' => 'Payment request sent! Check your phone for MTN Mobile Money prompt.',
                    'reference_id' => $data['reference_id'],
                    'plan' => $plan,
                    'amount' => $amount,
                    'provider' => $provider
                ));
            } else {
                $error_message = isset($data['error']) ? $data['error'] : 'Payment failed. Please try again.';
                wp_send_json_error($error_message);
            }
        } else {
            wp_send_json_error('MTN payment service not available.');
        }
    } 
    else if ($provider === 'Airtel') {
        if (!get_option('airtel_client_id')) {
            wp_send_json_error('Airtel Money is not configured yet. Please use MTN Mobile Money.');
        }
        
        $airtel_file = get_template_directory() . '/airtel_request.php';
        if (file_exists($airtel_file)) {
            $_POST = array_merge($_POST, $payment_data);
            ob_start();
            include $airtel_file;
            $response = ob_get_clean();
            
            $data = json_decode($response, true);
            if ($data && isset($data['success']) && $data['success']) {
                wp_send_json_success(array(
                    'message' => 'Payment request sent! Check your phone for Airtel Money prompt.',
                    'reference_id' => $data['reference_id'],
                    'plan' => $plan,
                    'amount' => $amount,
                    'provider' => $provider
                ));
            } else {
                $error_message = isset($data['error']) ? $data['error'] : 'Airtel payment failed. Please try again.';
                wp_send_json_error($error_message);
            }
        } else {
            wp_send_json_error('Airtel payment service not available.');
        }
    } else {
        wp_send_json_error('Unsupported payment provider.');
    }
}
add_action('wp_ajax_handle_payment', 'handle_payment_processing');
add_action('wp_ajax_nopriv_handle_payment', 'handle_payment_processing');

// Admin menu
function ntenjeru_wifi_admin_menu() {
    add_menu_page(
        'NTENJERU WIFI Pro',
        'NTENJERU WIFI Pro',
        'manage_options',
        'ntenjeru-wifi-settings',
        'ntenjeru_wifi_settings_page',
        'dashicons-wifi',
        30
    );
    
    add_submenu_page(
        'ntenjeru-wifi-settings',
        'Payment Settings',
        'Payment APIs',
        'manage_options',
        'ntenjeru-payment-settings',
        'ntenjeru_payment_settings_page'
    );
    
    add_submenu_page(
        'ntenjeru-wifi-settings',
        'Site Customization',
        'Customize Site',
        'manage_options',
        'ntenjeru-customization',
        'ntenjeru_customization_settings_page'
    );
    
    add_submenu_page(
        'ntenjeru-wifi-settings',
        'Pricing Plans',
        'Pricing Plans',
        'manage_options',
        'ntenjeru-pricing',
        'ntenjeru_pricing_settings_page'
    );
}
add_action('admin_menu', 'ntenjeru_wifi_admin_menu');

// Main Settings page
function ntenjeru_wifi_settings_page() {
    ?>
    <div class="wrap">
        <h1>🌐 NTENJERU WIFI Pro - Dashboard</h1>
        
        <div class="notice notice-success">
            <p><strong>Welcome to NTENJERU WIFI Pro!</strong> Professional WordPress theme with Elementor compatibility.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <div class="postbox">
                <div class="postbox-header"><h2>💳 Payment Status</h2></div>
                <div class="inside">
                    <p><strong>MTN Mobile Money:</strong> 
                        <?php echo get_option('mtn_api_key') ? '<span style="color: green;">✅ Active</span>' : '<span style="color: red;">❌ Configure API</span>'; ?>
                    </p>
                    <p><strong>Airtel Money:</strong> 
                        <?php echo get_option('airtel_client_id') ? '<span style="color: green;">✅ Active</span>' : '<span style="color: orange;">⏳ Configure API</span>'; ?>
                    </p>
                    <a href="<?php echo admin_url('admin.php?page=ntenjeru-payment-settings'); ?>" class="button button-primary">Setup Payment APIs</a>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header"><h2>🎨 Site Customization</h2></div>
                <div class="inside">
                    <p>Control all site elements without touching code.</p>
                    <a href="<?php echo admin_url('admin.php?page=ntenjeru-customization'); ?>" class="button button-primary">Customize Site</a>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header"><h2>💰 Pricing Plans</h2></div>
                <div class="inside">
                    <p>Manage your internet packages and pricing.</p>
                    <a href="<?php echo admin_url('admin.php?page=ntenjeru-pricing'); ?>" class="button button-primary">Edit Plans</a>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <h3>🚀 Quick Actions</h3>
            <p><a href="<?php echo home_url(); ?>" class="button" target="_blank">🌐 View Live Site</a></p>
            <p><a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="button">📄 Edit with Elementor</a></p>
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
        
        echo '<div class="notice notice-success"><p>✅ Payment settings saved successfully!</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>💳 Payment API Configuration</h1>
        <p>Configure your mobile money payment providers to start accepting payments.</p>
        
        <form method="post">
            <div class="postbox">
                <div class="postbox-header"><h2>📱 MTN Mobile Money API</h2></div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">API User ID</th>
                            <td>
                                <input type="text" name="mtn_api_user_id" value="<?php echo esc_attr(get_option('mtn_api_user_id', 'b118d864-b932-41fa-bc53-17d2841772ee')); ?>" class="regular-text" required />
                                <p class="description">Your MTN MoMo API User ID from MTN Developer Portal</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">API Key</th>
                            <td>
                                <input type="text" name="mtn_api_key" value="<?php echo esc_attr(get_option('mtn_api_key', '127fb39cbddc47dc8220c3ebd4244cc2')); ?>" class="regular-text" required />
                                <p class="description">Your MTN MoMo API Key</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Subscription Key</th>
                            <td>
                                <input type="text" name="mtn_subscription_key" value="<?php echo esc_attr(get_option('mtn_subscription_key', '09d303b8c9e94eb1a530d68418848f6a')); ?>" class="regular-text" required />
                                <p class="description">Your MTN MoMo Subscription Key (Ocp-Apim-Subscription-Key)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Environment</th>
                            <td>
                                <select name="mtn_environment">
                                    <option value="mtnuganda" <?php selected(get_option('mtn_environment', 'mtnuganda'), 'mtnuganda'); ?>>MTN Uganda (Production)</option>
                                    <option value="sandbox" <?php selected(get_option('mtn_environment'), 'sandbox'); ?>>Sandbox (Testing)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Callback URL</th>
                            <td>
                                <input type="url" name="mtn_callback_url" value="<?php echo esc_attr(get_option('mtn_callback_url', home_url('/momo-callback/'))); ?>" class="regular-text" />
                                <p class="description">URL for payment status notifications</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header"><h2>📱 Airtel Money API</h2></div>
                <div class="inside">
                    <div class="notice notice-info inline">
                        <p><strong>Note:</strong> Airtel Money API configuration is optional. MTN Mobile Money will work without it.</p>
                    </div>
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
                                    <option value="sandbox" <?php selected(get_option('airtel_environment', 'sandbox'), 'sandbox'); ?>>Sandbox (Testing)</option>
                                    <option value="production" <?php selected(get_option('airtel_environment'), 'production'); ?>>Production</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Callback URL</th>
                            <td>
                                <input type="url" name="airtel_callback_url" value="<?php echo esc_attr(get_option('airtel_callback_url', home_url('/airtel-callback/'))); ?>" class="regular-text" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php submit_button('💾 Save Payment Settings', 'primary', 'submit', false); ?>
        </form>
    </div>
    <?php
}

// Site Customization page
function ntenjeru_customization_settings_page() {
    if (isset($_POST['submit'])) {
        // Save site branding
        update_option('site_title', sanitize_text_field($_POST['site_title']));
        update_option('site_tagline', sanitize_text_field($_POST['site_tagline']));
        update_option('hero_title', sanitize_text_field($_POST['hero_title']));
        update_option('hero_subtitle', sanitize_textarea_field($_POST['hero_subtitle']));
        
        // Save visual settings
        update_option('primary_color', sanitize_hex_color($_POST['primary_color']));
        update_option('enable_floating_graphics', sanitize_text_field($_POST['enable_floating_graphics']));
        update_option('enable_animations', sanitize_text_field($_POST['enable_animations']));
        
        // Save contact info
        update_option('contact_phone', sanitize_text_field($_POST['contact_phone']));
        update_option('contact_email', sanitize_email($_POST['contact_email']));
        update_option('contact_address', sanitize_textarea_field($_POST['contact_address']));
        update_option('whatsapp_number', sanitize_text_field($_POST['whatsapp_number']));
        
        echo '<div class="notice notice-success"><p>✅ Site settings saved successfully!</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>🎨 Site Customization</h1>
        
        <form method="post">
            <div class="postbox">
                <div class="postbox-header"><h2>🏷️ Site Branding</h2></div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Site Title</th>
                            <td>
                                <input type="text" name="site_title" value="<?php echo esc_attr(get_option('site_title', 'NTENJERU WIFI')); ?>" class="regular-text" />
                                <p class="description">Main site title displayed in header</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Site Tagline</th>
                            <td>
                                <input type="text" name="site_tagline" value="<?php echo esc_attr(get_option('site_tagline', 'Fast & Reliable Internet')); ?>" class="regular-text" />
                                <p class="description">Subtitle below main title</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Hero Title</th>
                            <td>
                                <input type="text" name="hero_title" value="<?php echo esc_attr(get_option('hero_title', 'Stay Connected with Lightning-Fast WiFi ⚡')); ?>" class="large-text" />
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
                <div class="postbox-header"><h2>🎨 Visual Settings</h2></div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Primary Color</th>
                            <td>
                                <input type="color" name="primary_color" value="<?php echo esc_attr(get_option('primary_color', '#007BFF')); ?>" />
                                <p class="description">Main brand color for buttons and accents</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Floating Graphics</th>
                            <td>
                                <label><input type="radio" name="enable_floating_graphics" value="1" <?php checked(get_option('enable_floating_graphics', '1'), '1'); ?> /> Enable</label>
                                <label><input type="radio" name="enable_floating_graphics" value="0" <?php checked(get_option('enable_floating_graphics', '1'), '0'); ?> /> Disable</label>
                                <p class="description">Animated background elements</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Animations</th>
                            <td>
                                <label><input type="radio" name="enable_animations" value="1" <?php checked(get_option('enable_animations', '1'), '1'); ?> /> Enable</label>
                                <label><input type="radio" name="enable_animations" value="0" <?php checked(get_option('enable_animations', '1'), '0'); ?> /> Disable</label>
                                <p class="description">Scroll animations and transitions</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header"><h2>📞 Contact Information</h2></div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Phone Number</th>
                            <td>
                                <input type="text" name="contact_phone" value="<?php echo esc_attr(get_option('contact_phone', '+256 123 456 789')); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Email Address</th>
                            <td>
                                <input type="email" name="contact_email" value="<?php echo esc_attr(get_option('contact_email', 'info@ntenjeruwifi.com')); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">WhatsApp Number</th>
                            <td>
                                <input type="text" name="whatsapp_number" value="<?php echo esc_attr(get_option('whatsapp_number', '+256123456789')); ?>" class="regular-text" />
                                <p class="description">Include country code without + (e.g., 256123456789)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Address</th>
                            <td>
                                <textarea name="contact_address" rows="3" class="large-text"><?php echo esc_textarea(get_option('contact_address', 'Mukono, Uganda')); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php submit_button('💾 Save Customization', 'primary', 'submit', false); ?>
        </form>
    </div>
    <?php
}

// Pricing Settings page
function ntenjeru_pricing_settings_page() {
    if (isset($_POST['submit'])) {
        // Save pricing plans
        $plans = array(
            'plan_24h' => array(
                'name' => sanitize_text_field($_POST['plan_24h_name']),
                'price' => sanitize_text_field($_POST['plan_24h_price']),
                'duration' => sanitize_text_field($_POST['plan_24h_duration']),
                'features' => sanitize_textarea_field($_POST['plan_24h_features'])
            ),
            'plan_1w' => array(
                'name' => sanitize_text_field($_POST['plan_1w_name']),
                'price' => sanitize_text_field($_POST['plan_1w_price']),
                'duration' => sanitize_text_field($_POST['plan_1w_duration']),
                'features' => sanitize_textarea_field($_POST['plan_1w_features'])
            ),
            'plan_1m' => array(
                'name' => sanitize_text_field($_POST['plan_1m_name']),
                'price' => sanitize_text_field($_POST['plan_1m_price']),
                'duration' => sanitize_text_field($_POST['plan_1m_duration']),
                'features' => sanitize_textarea_field($_POST['plan_1m_features'])
            )
        );
        
        update_option('ntenjeru_pricing_plans', $plans);
        echo '<div class="notice notice-success"><p>✅ Pricing plans updated!</p></div>';
    }
    
    $plans = get_option('ntenjeru_pricing_plans', array(
        'plan_24h' => array('name' => '24 Hours', 'price' => '1,000', 'duration' => 'for 24 hours', 'features' => "High-speed internet access\n24-hour unlimited browsing\nConnect multiple devices\nBasic customer support\nSocial media access\nVideo streaming capability"),
        'plan_1w' => array('name' => '1 Week', 'price' => '7,000', 'duration' => 'for 7 days', 'features' => "High-speed internet access\nFull week unlimited browsing\nConnect unlimited devices\nPriority customer support\nHD video streaming\nFile downloads & uploads\nEmail and work applications\nOnline gaming support"),
        'plan_1m' => array('name' => '1 Month', 'price' => '25,000', 'duration' => 'for 30 days', 'features' => "Maximum speed internet access\nFull month unlimited browsing\nConnect unlimited devices\n24/7 premium support\n4K video streaming\nLarge file transfers\nBusiness applications\nOnline gaming & streaming\nTechnical support priority\nSpeed guarantee")
    ));
    ?>
    <div class="wrap">
        <h1>💰 Pricing Plans Management</h1>
        <p>Customize your internet packages and pricing to match your services.</p>
        
        <form method="post">
            <?php foreach(['plan_24h' => '24 Hour Plan', 'plan_1w' => '1 Week Plan', 'plan_1m' => '1 Month Plan'] as $key => $title): ?>
            <div class="postbox">
                <div class="postbox-header"><h2><?php echo $title; ?></h2></div>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row">Plan Name</th>
                            <td><input type="text" name="<?php echo $key; ?>_name" value="<?php echo esc_attr($plans[$key]['name']); ?>" class="regular-text" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Price (UGX)</th>
                            <td><input type="text" name="<?php echo $key; ?>_price" value="<?php echo esc_attr($plans[$key]['price']); ?>" class="regular-text" placeholder="1,000" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Duration Text</th>
                            <td><input type="text" name="<?php echo $key; ?>_duration" value="<?php echo esc_attr($plans[$key]['duration']); ?>" class="regular-text" placeholder="for 24 hours" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Features (One per line)</th>
                            <td><textarea name="<?php echo $key; ?>_features" rows="6" class="large-text"><?php echo esc_textarea($plans[$key]['features']); ?></textarea></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php submit_button('💾 Update Pricing Plans', 'primary', 'submit', false); ?>
        </form>
    </div>
    <?php
}

// Custom body classes for Elementor
function ntenjeru_wifi_body_classes($classes) {
    if (is_page_template('elementor_canvas')) {
        $classes[] = 'elementor-page';
    }
    return $classes;
}
add_filter('body_class', 'ntenjeru_wifi_body_classes');

// Custom CSS output
function ntenjeru_wifi_custom_css() {
    $primary_color = get_option('primary_color', '#007BFF');
    if ($primary_color !== '#007BFF') {
        echo "<style>
        :root {
            --primary: " . $primary_color . ";
            --primary-dark: " . adjustBrightness($primary_color, -20) . ";
            --primary-light: " . adjustBrightness($primary_color, 80) . ";
        }
        </style>";
    }
}
add_action('wp_head', 'ntenjeru_wifi_custom_css');

// Helper function to adjust color brightness
function adjustBrightness($hex, $steps) {
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }
    
    $color_parts = array_map('hexdec', array(substr($hex, 0, 2), substr($hex, 2, 2), substr($hex, 4, 2)));
    
    foreach ($color_parts as &$color) {
        $color = max(0, min(255, $color + $steps));
    }
    
    return '#' . implode('', array_map('dechex', $color_parts));
}
?>