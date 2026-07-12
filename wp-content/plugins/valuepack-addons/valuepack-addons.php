<?php
/**
 * Plugin Name: Value Pack Addons
 * Plugin URI: https://cubewp.com/
 * Description: Enhance your online store's capabilities with the powerful Value Pack Plugin
 * Version: 1.0.3
 * Author: CubeWP
 * Author URI: https://cubewp.com
 * License: GPLv2 or later
 * Text Domain: valuepack-addons
 * Domain Path: /languages/
 * @package valuepack-addons
 */
defined( 'ABSPATH' ) || exit;

/* VALUE_PACK_PLUGIN_URL Defines current plugin version */
if ( ! defined( 'VALUE_PACK_VERSION' ) ) {
    define( 'VALUE_PACK_VERSION', '1.0.3' );
}



/* VALUE_PACK_PLUGIN_DIR Defines for load Php files */
if ( ! defined( 'VALUE_PACK_PLUGIN_DIR' ) ) {
    define( 'VALUE_PACK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/* VALUE_PACK_PLUGIN_URL Defines for load JS and CSS files */
if ( ! defined( 'VALUE_PACK_PLUGIN_URL' ) ) {
    define( 'VALUE_PACK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* VALUE_PACK_PLUGIN_FILE Defines for file access */
if ( ! defined('VALUE_PACK_PLUGIN_FILE')) {
    define('VALUE_PACK_PLUGIN_FILE', __FILE__);
}

 

/**
 * All Elementor classes files to be loaded automatically.
 *
 * @param string $className Class name.
 */
spl_autoload_register( 'value_pack_autoload_classes' );
function value_pack_autoload_classes( $className ) {
    // If class does not start with our prefix (Value_Pack), nothing will return.
    if ( false === strpos( $className, 'Value_Pack' ) ) {
        return null;
    }
    // Replace _ with - to match the file name.
    $file_name = str_replace( '_', '-', strtolower( $className ) );

    // Calling class file.
    $files = array(
        VALUE_PACK_PLUGIN_DIR . 'classes/class-' . $file_name . '.php',
        VALUE_PACK_PLUGIN_DIR . 'modules/modules-value-pack.php'
    );
    
    // Only load widget files if Elementor is loaded (to prevent fatal errors)
    if ( class_exists( '\Elementor\Widget_Base' ) ) {
        $files[] = VALUE_PACK_PLUGIN_DIR . 'classes/elementor-widgets/class-' . $file_name . '.php';
    }
    
    // Checking if exists and is readable then include.
    foreach ( $files as $file ) {
        if ( file_exists( $file ) && is_readable( $file ) ) {
            require $file;
            // Stop after first match to prevent loading widget files when looking for core classes
            if ( strpos( $file, 'classes/class-' ) !== false && strpos( $file, 'modules/modules-value-pack.php' ) !== false ) {
                break;
            }
        }
    }

    return $className;
}




/**
 * Main plugin instance accessor.
 *
 * @return Value_Pack_Load The plugin instance
 */
function value_pack() {
    return Value_Pack_Load::instance();
}
value_pack();



