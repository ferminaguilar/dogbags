<?php

/**
 * Plugin Name: CubeWP WooCommerce
 * Plugin URI: https://cubewp.com/
 * Description: Enhance your online store's capabilities with the powerful CubeWP WooCommerce Plugin
 * Version: 1.0.2
 * Author: CubeWP
 * Author URI: https://cubewp.com
 * Text Domain: cubewp-woocommerce
 * Domain Path: /languages/
 *
 * @package cubewp-woocommerce
 */
defined('ABSPATH') || exit;

/* CUBEWP_WOOCOMMERCE_PLUGIN_URL Defines current plugin version */
if (! defined('CUBEWP_WOOCOMMERCE_VERSION')) {
	define('CUBEWP_WOOCOMMERCE_VERSION', '1.0.2');
}

/* CUBEWP_WOOCOMMERCE_PLUGIN_DIR Defines for load Php files */
if (! defined('CUBEWP_WOOCOMMERCE_PLUGIN_DIR')) {
	define('CUBEWP_WOOCOMMERCE_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

/* CUBEWP_WOOCOMMERCE_PLUGIN_URL Defines for load JS and CSS files */
if (! defined('CUBEWP_WOOCOMMERCE_PLUGIN_URL')) {
	define('CUBEWP_WOOCOMMERCE_PLUGIN_URL', plugin_dir_url(__FILE__));
}

/* CUBEWP_WOOCOMMERCE_PLUGIN_FILE Defines for file access */
if (! defined('CUBEWP_WOOCOMMERCE_PLUGIN_FILE')) {
	define('CUBEWP_WOOCOMMERCE_PLUGIN_FILE', __FILE__);
}

/**
 * Autoload CubeWP WooCommerce classes.
 *
 * Automatically loads classes when they are referenced. Only loads classes with
 * the CubeWp prefix from the cube/classes directory.
 *
 * @since 1.0.0
 * @param string $className The fully-qualified class name
 * @return string|null The class name if loaded, null otherwise
 */
spl_autoload_register('cubewp_woocommerce_autoload_classes');
function cubewp_woocommerce_autoload_classes($className)
{

	// If class does not start with our prefix (CubeWp), nothing will return.
	if (false === strpos($className, 'CubeWp')) {
		return null;
	}
	// Replace _ with - to match the file name.
	$file_name = str_replace('_', '-', strtolower($className));

	// Calling class file.
	$files = array(
		CUBEWP_WOOCOMMERCE_PLUGIN_DIR . 'cube/classes/class-' . $file_name . '.php'
	);

	// Checking if exists then include.
	foreach ($files as $file) {
		if (file_exists($file)) {
			require $file;
		}
	}

	return $className;
}

/**
 * Method cubewp_framework_required_notice_for_forms
 *
 * @return void
 * @since  1.0.0
 */
if (! function_exists('cubewp_framework_required_notice_for_forms')) {
	function cubewp_framework_required_notice_for_forms()
	{
		if (! function_exists('CWP')) {
			$message = sprintf(
				wp_kses(
					__('CubeWP WooCommerce requires CubeWP Framework to be installed and active. You can download <a href="%s" target="_blank">CubeWP Framework</a> here.', 'cubewp-woocommerce'),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				),
				'https://cubewp.com/'
			);

			printf(
				'<div class="notice notice-error"><p><strong>%s</strong></p></div>',
				$message
			);
		}
	}
	add_action('admin_notices', 'cubewp_framework_required_notice_for_forms');
}

add_action('cubewp_loaded', 'cubewp_woocommerce');
function cubewp_woocommerce()
{
	return CubeWp_Woocommerce_Load::instance();
}
