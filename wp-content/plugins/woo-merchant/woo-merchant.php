<?php
/**
 * Plugin Name: Woo Merchant
 * Plugin URI: https://cubewp.com/
 * Description: Enhance your WooCommerce store and boost sales effortlessly.
 * Version: 1.0.2
 * Author: CubeWP
 * Author URI: https://cubewp.com
 * Text Domain: woo-merchant
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.3
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package WooMerchant
 * @category Core
 * @author CubeWP
 */

/**
 * The absolute path to the WordPress directory.
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
if (!defined('WOO_MERCHANT_PLUGIN_VERSION')) {
    define('WOO_MERCHANT_PLUGIN_VERSION', '1.0.2');
}
if (!defined('WOO_MERCHANT_PLUGIN_DIR')) {
    define('WOO_MERCHANT_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('WOO_MERCHANT_PLUGIN_URL')) {
    define('WOO_MERCHANT_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('WOO_MERCHANT_PLUGIN_BASENAME')) {
    define('WOO_MERCHANT_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

spl_autoload_register('woo_merchant_autoload_classes');

/**
 * Autoload Woo Merchant classes.
 *
 * @since 1.0.0
 * @param string $className Class name to load.
 * @return string|null Class name if loaded, null otherwise.
 */
function woo_merchant_autoload_classes($className)
{
    // Validate class name input
    if (!is_string($className) || empty($className)) {
        return null;
    }

    // Only load our plugin classes
    if (false === strpos($className, 'Woo_Merchant')) {
        return null;
    }

    // Sanitize class name for file path with fallback
    $sanitized = str_replace('_', '-', strtolower($className));
    $file_name = function_exists('sanitize_file_name') 
        ? sanitize_file_name($sanitized)
        : preg_replace('/[^a-z0-9\-]/', '', $sanitized);

    // Define possible file locations
    $files = array(
        WOO_MERCHANT_PLUGIN_DIR . 'classes/class-' . $file_name . '.php',
        WOO_MERCHANT_PLUGIN_DIR . 'classes/elementor-widgets/class-' . $file_name . '.php',
        WOO_MERCHANT_PLUGIN_DIR . 'classes/features/class-' . $file_name . '.php'
    );

    // Safely include the file if it exists
    foreach ($files as $file) {
        if (file_exists($file) && is_readable($file)) {
            require_once $file;
            break;
        }
    }

    return $className;
}


/**
 * Initialize the Woo Merchant plugin.
 *
 * @since 1.0.0
 * @return Woo_Merchant_Load Plugin instance.
 */
function woo_merchant_init() {
    return Woo_Merchant_Load::instance();
}
add_action('plugins_loaded', 'woo_merchant_init');
