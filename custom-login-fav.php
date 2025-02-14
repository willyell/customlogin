<?php
/**
 * Plugin Name: Custom Login Page
 * Description: Customizes the WordPress login page with the homepage featured image as background and replaces the WordPress logo.
 * Version: 1.0.0
 * Author: William Yell
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue custom login styles
function custom_login_styles() {
    $homepage_id = get_option('page_on_front');
    $background_image = '';

    if ($homepage_id) {
        $background_image = get_the_post_thumbnail_url($homepage_id, 'full');
    }

    if (!$background_image) {
        $background_image = includes_url('images/w-logo-blue-white.png'); // Fallback image
    }

    $favicon_url = get_site_icon_url(512); // Get the site's favicon (512x512 size)
    $site_name = get_bloginfo('name'); // Get the site name
    ?>
    <style>
        body.login {
            background: url('<?php echo esc_url($background_image); ?>') no-repeat center center fixed;
            background-size: cover;
        }
        body.login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: 1; /* Ensure it's behind the login form */
        }
        .login h1 a {
            background-image: url('<?php echo esc_url($favicon_url); ?>') !important;
            background-size: contain;
            background-position: center center;
            background-repeat: no-repeat;
            width: 160px; /* Adjusted size for favicon */
            height: 160px; /* Adjusted size for favicon */
            display: block;
            filter: none; /* Ensure no blur is applied */
            z-index: 10; /* Ensure it's above the overlay */
            position: relative;
            border-radius: 20px; /* Rounded corners */
        }
        .login h1 {
            text-align: center; /* Center the logo and heading */
            margin-bottom: 20px; /* Add some space below the logo */
        }
        .login h1::after {
            content: '<?php echo esc_html($site_name); ?>'; /* Add the site name as a heading */
            display: block;
            font-size: 24px;
            font-weight: bold;
            color: #fff; /* White text color */
            margin-top: 10px; /* Space between logo and heading */
            z-index: 10; /* Ensure it's above the overlay */
            position: relative;
        }
        .login form {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
        }
        .login #nav, .login #backtoblog, .login .language-switcher {
            display: block !important; /* Ensure they remain visible */
            position: relative;
            z-index: 10; /* Ensure they are above the overlay */
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'custom_login_styles');

// Change the login logo URL
function custom_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'custom_login_url');

// Change the login logo title
function custom_login_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'custom_login_title');