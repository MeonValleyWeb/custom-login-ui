<?php
/**
 * Plugin Name: Custom Login UI - Split Screen
 * Description: Split-screen login layout with logo on left, form on right
 * Version: 1.0
 * 
 * MU-Plugin for WordPress admin login customization
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class CustomLoginUI {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Only run on login page
        if (!$this->is_login_page()) {
            return;
        }
        
        // Remove default WordPress branding
        add_filter('login_headerurl', array($this, 'login_header_url'));
        add_filter('login_headertext', array($this, 'login_header_text'));
        
        // Wrap the login markup
        add_action('login_header', array($this, 'login_header_wrapper'));
        add_action('login_footer', array($this, 'login_footer_wrapper'));
        
        // Add custom styles
        add_action('login_enqueue_scripts', array($this, 'login_styles'));
    }
    
    private function is_login_page() {
        return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
    }
    
    public function login_header_url() {
        return home_url('/');
    }
    
    public function login_header_text() {
        return get_bloginfo('name');
    }
    
    public function login_header_wrapper() {
        echo '<div class="wba-split-container">';
        echo '<div class="wba-left-panel">';
        echo '<div class="wba-left-content">';
        echo '<div class="wba-logo"></div>';
        echo '<h1 class="wba-site-title">Southampton Disabled Supporters\' Association - CMS Login</h1>';
        echo '<p class="wba-welcome-text">Join our community and start your journey to success</p>';
        echo '</div>';
        echo '</div>';
        echo '<div class="wba-right-panel">';
        echo '<div class="wba-form-container">';
        echo '<div class="wba-form-header">';
        echo '<h2>Login to the SDSA CMS</h2>';
        echo '<p>Enter your email and password to login</p>';
        echo '</div>';
    }
    
    public function login_footer_wrapper() {
        echo '</div>'; // .wba-form-container
        echo '</div>'; // .wba-right-panel
        echo '</div>'; // .wba-split-container
        
        // Hide default elements
        ?>
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Hide default WordPress elements
            var elementsToHide = [
                '#nav',
                '#backtoblog',
                '.privacy-policy-page-link',
                '.login h1'
            ];
            
            elementsToHide.forEach(function(selector) {
                var element = document.querySelector(selector);
                if (element) {
                    element.style.display = 'none';
                }
            });
            
            // Hide success messages (but keep error messages)
            var messages = document.querySelectorAll('.message, .success');
            messages.forEach(function(msg) {
                if (!msg.classList.contains('error')) {
                    msg.style.display = 'none';
                }
            });
        });
        </script>
        <?php
    }
    
    private function get_logo_url() {
        // Try multiple sources for the logo
        $logo_sources = array(
            wp_get_attachment_url(16752),
            get_stylesheet_directory_uri() . '/assets/images/sdsa-login-logo.svg',
            content_url('uploads/2025/08/sdsa-login-logo.svg'),
            WPMU_PLUGIN_URL . '/assets/sdsa-login-logo.svg',
        );
        
        // Return the first valid URL
        foreach ($logo_sources as $url) {
            if ($url && !empty($url)) {
                return esc_url($url);
            }
        }
        
        // SVG fallback with SDSA branding
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg width="200" height="120" viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg">
                <rect width="200" height="120" fill="none"/>
                <circle cx="100" cy="40" r="25" fill="#ffffff" opacity="0.9"/>
                <text x="100" y="48" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" fill="#1c2430">DSA</text>
                <text x="100" y="70" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#ffffff" opacity="0.8">SOUTHAMPTON</text>
                <text x="100" y="85" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#ffffff" opacity="0.8">DISABLED SUPPORTERS</text>
                <text x="100" y="100" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#ffffff" opacity="0.8">ASSOCIATION</text>
            </svg>
        ');
    }
    
    public function login_styles() {
        $logo = $this->get_logo_url();
        
        ?>
        <style type="text/css">
        /* Reset and base styles */
        body.login *, body.login *::before, body.login *::after {
            box-sizing: border-box;
        }
        
        :root {
            --sdsa-primary: #d32f2f;      /* Southampton red */
            --sdsa-secondary: #1976d2;    /* Blue accent */
            --sdsa-dark: #1c2430;         /* Dark text */
            --sdsa-light: #f5f7fb;        /* Light background */
            --sdsa-white: #ffffff;
            --sdsa-gray-100: #f8f9fa;
            --sdsa-gray-200: #e9ecef;
            --sdsa-gray-300: #dee2e6;
            --sdsa-gray-600: #6c757d;
            --sdsa-gray-800: #343a40;
            --sdsa-border: #e5e9f2;
            --sdsa-focus: #5b9cff;
            --sdsa-error: #dc3545;
        }

        /* Page reset */
        body.login {
            background: var(--sdsa-light) !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        #login {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            position: static !important;
        }
        
        /* Hide default WordPress elements */
        .login h1,
        .login h1 a,
        #nav,
        #backtoblog,
        .privacy-policy-page-link {
            display: none !important;
        }

        /* Split container */
        .wba-split-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Left panel - Logo and branding */
        .wba-left-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--sdsa-primary) 0%, #b71c1c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }
        
        .wba-left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .wba-left-content {
            text-align: center;
            color: white;
            position: relative;
            z-index: 1;
            max-width: 400px;
        }

        .wba-logo {
            width: 400px;
            height: 240px;
            margin: 0 auto 48px auto;
            background: url('<?php echo $logo; ?>') no-repeat center center;
            background-size: contain;
        }

        .wba-site-title {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 16px 0;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .wba-welcome-text {
            font-size: 18px;
            line-height: 1.5;
            opacity: 0.9;
            margin: 0;
            font-weight: 400;
        }

        /* Right panel - Form */
        .wba-right-panel {
            flex: 1;
            background: var(--sdsa-white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .wba-form-container {
            width: 100%;
            max-width: 400px;
        }

        .wba-form-header {
            text-align: center;
            margin-bottom: 20px !important;
            padding-bottom: 20px !important;
        }

        /* Force spacing between form header and first form element */
        .wba-form-container .login form,
        .login form {
            margin-top: 20px !important;
            padding-top: 10px !important;
        }
        
        /* Additional spacing for the first form field */
        .login form p:first-child,
        .login form label:first-child {
            margin-top: 20px !important;
            padding-top: 20px !important;
        }

        .wba-form-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--sdsa-dark);
            margin: 0 0 8px 0;
            line-height: 1.2;
            text-align: center;
        }

        .wba-form-header p {
            font-size: 16px;
            color: var(--sdsa-gray-600);
            margin: 0;
            line-height: 1.4;
            text-align: center;
        }

        /* Form styles */
        .login form {
            width: 100% !important;
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .login label {
            display: block !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            color: var(--sdsa-dark) !important;
            margin: 0 0 8px 0 !important;
            line-height: 1.4 !important;
        }
        
        /* Form inputs */
        .login .input,
        .login input[type="text"],
        .login input[type="password"],
        .login input[type="email"] {
            width: 100% !important;
            padding: 14px 16px !important;
            font-size: 16px !important;
            line-height: 1.5 !important;
            color: var(--sdsa-dark) !important;
            background: var(--sdsa-white) !important;
            border: 1px solid var(--sdsa-border) !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
            margin: 0 0 20px 0 !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
            -webkit-appearance: none !important;
        }
        
        .login .input::placeholder,
        .login input[type="text"]::placeholder,
        .login input[type="password"]::placeholder,
        .login input[type="email"]::placeholder {
            color: var(--sdsa-gray-600) !important;
            opacity: 0.7 !important;
        }
        
        .login .input:focus,
        .login input[type="text"]:focus,
        .login input[type="password"]:focus,
        .login input[type="email"]:focus {
            border-color: var(--sdsa-focus) !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(91, 156, 255, 0.1) !important;
        }

        /* Remember me */
        .forgetmenot {
            margin: 0 0 24px 0 !important;
        }
        
        .forgetmenot label {
            display: flex !important;
            align-items: center !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            color: var(--sdsa-gray-600) !important;
        }
        
        .forgetmenot input[type="checkbox"] {
            width: auto !important;
            margin: 0 8px 0 0 !important;
        }

        /* Submit button */
        .wp-core-ui .button-primary {
            width: 100% !important;
            padding: 16px 24px !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            line-height: 1.5 !important;
            color: white !important;
            background: var(--sdsa-primary) !important;
            border: 1px solid var(--sdsa-primary) !important;
            border-radius: 8px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-shadow: none !important;
            box-shadow: 0 2px 4px rgba(211, 47, 47, 0.2) !important;
            text-align: center !important;
        }
        
        .wp-core-ui .button-primary:hover,
        .wp-core-ui .button-primary:focus {
            background: #b71c1c !important;
            border-color: #b71c1c !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(211, 47, 47, 0.3) !important;
        }

        /* Links */
        .wp-core-ui .button-link {
            color: var(--sdsa-primary) !important;
            text-decoration: none !important;
            font-size: 14px !important;
            font-weight: 500 !important;
        }
        
        .wp-core-ui .button-link:hover {
            color: #b71c1c !important;
            text-decoration: underline !important;
        }

        /* Form spacing */
        .login .submit {
            margin: 8px 0 0 0 !important;
            text-align: center !important;
        }
        
        .login .submit + p {
            margin: 20px 0 0 0 !important;
            text-align: center !important;
        }

        /* Error messages */
        #login_error {
            background: #fff5f5 !important;
            border: 1px solid #fecaca !important;
            color: var(--sdsa-error) !important;
            border-radius: 8px !important;
            padding: 16px !important;
            margin: 0 0 24px 0 !important;
            font-size: 14px !important;
        }

        /* Mobile responsive */
        @media (max-width: 992px) {
            .wba-split-container {
                flex-direction: column;
            }
            
            .wba-left-panel,
            .wba-right-panel {
                flex: none;
                min-height: 50vh;
            }
            
            .wba-left-panel {
                padding: 32px 24px;
            }
            
            .wba-right-panel {
                padding: 32px 24px;
            }
            
            .wba-site-title {
                font-size: 28px;
            }
            
            .wba-form-header h2 {
                font-size: 24px;
            }
        }
        
        @media (max-width: 576px) {
            .wba-left-panel,
            .wba-right-panel {
                padding: 24px 20px;
            }
            
            .wba-site-title {
                font-size: 24px;
            }
            
            .wba-welcome-text {
                font-size: 16px;
            }
            
            .wba-form-header h2 {
                font-size: 22px;
            }
            
            .wba-logo {
                width: 320px;
                height: 192px;
            }
        }
        </style>
        <?php
    }
}

// Initialize the plugin
new CustomLoginUI();