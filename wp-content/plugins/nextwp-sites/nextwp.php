<?php
/**
 * @wordpress-plugin
 * Plugin Name: NextWP Sites
 * Plugin URI:  https://nextwp.io/
 * Description: Premium starter templates for your next WordPress website or web application.
 * Version:     1.0.1
 * Author:      NextWP
 * Author URI:  https://nextwp.io
 * Text Domain: nextwp
 * Domain Path: /languages/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires PHP: 7.4
 */

// Security check - prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Prevent direct file access
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('NEXTWP_VERSION', '1.0.1');
define('NEXTWP_DIR', plugin_dir_path(__FILE__));
define('NEXTWP_URL', plugin_dir_url(__FILE__));

/**
 * Initialize the NextWP plugin.
 */
function nextwp_init() {
    try {
        // Security check - verify the file exists before requiring
        $load_file = NEXTWP_DIR . 'classes/class-nextwp-load.php';
        if (!file_exists($load_file)) {
            error_log('NextWP: Core plugin file missing: ' . $load_file);
            wp_die(
                esc_html__('Unable to load plugin core files. Please reinstall the plugin.', 'nextwp'),
                esc_html__('Plugin Error', 'nextwp'),
                ['response' => 500, 'back_link' => true]
            );
        }
        require_once $load_file;
        if (!class_exists('NextWP_Load')) {
            throw new Exception('NextWP_Load class not found after including file.');
        }
        return NextWP_Load::instance();
    } catch (Exception $e) {
        error_log('NextWP: Plugin initialization failed: ' . $e->getMessage());
        wp_die(
            esc_html__('Plugin initialization failed. Please check the error logs or reinstall the plugin.', 'nextwp'),
            esc_html__('Plugin Error', 'nextwp'),
            ['response' => 500, 'back_link' => true]
        );
    }
}

/**
 * Clear rate limits when plugin is activated.
 */
function nextwp_activate() {
    // Clear any existing rate limits
    $transients_to_clear = [
        'nextwp_import_rate_limit_content_import',
        'nextwp_import_rate_limit_theme_install',
        'nextwp_import_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_theme_install',
        'nextwp_setup_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_content_import'
    ];
    
    foreach ($transients_to_clear as $transient) {
        delete_transient($transient);
        delete_transient($transient . '_last_activity');
    }
    
    error_log('NextWP: Plugin activated - cleared all rate limits');
}

/**
 * Clean up when plugin is deactivated.
 */
function nextwp_deactivate() {
    // Clear scheduled cron job
    wp_clear_scheduled_hook('nextwp_clear_rate_limits_cron');
    
    // Clear rate limit transients
    $transients_to_clear = [
        'nextwp_import_rate_limit_content_import',
        'nextwp_import_rate_limit_theme_install',
        'nextwp_import_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_theme_install',
        'nextwp_setup_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_content_import'
    ];
    
    foreach ($transients_to_clear as $transient) {
        delete_transient($transient);
        delete_transient($transient . '_last_activity');
    }
    
    error_log('NextWP: Plugin deactivated - cleaned up rate limits and cron jobs');
}

// Hook into WordPress lifecycle
add_action('plugins_loaded', 'nextwp_init');

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'nextwp_activate');
register_deactivation_hook(__FILE__, 'nextwp_deactivate');

// Add security headers for NextWP admin pages
add_action('admin_init', function() {
    if (isset($_GET['page']) && $_GET['page'] === 'nextwp-sites') {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
});
