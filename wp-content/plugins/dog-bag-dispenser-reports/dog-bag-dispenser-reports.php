<?php
/**
 * Plugin Name: Dog Bag Dispenser Reports
 * Description: QR-based reporting system for dog bag dispenser refills and maintenance.
 * Version: 1.0.0
 * Author: Fermin
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Constants
define( 'DBDR_PATH', plugin_dir_path( __FILE__ ) );
define( 'DBDR_URL', plugin_dir_url( __FILE__ ) );

// Include classes
require_once DBDR_PATH . 'includes/class-report-database.php';
require_once DBDR_PATH . 'includes/class-frontend-form.php';
require_once DBDR_PATH . 'includes/class-admin-dashboard.php';
require_once DBDR_PATH . 'includes/class-locations.php';

// Activation hook — create DB tables
register_activation_hook( __FILE__, function() {
    DBDR_Report_Database::create_table();           // reports table
    DBDR_Report_Database::create_location_table();  // locations table
});

// Init frontend
add_action( 'plugins_loaded', function() {
    new DBDR_Frontend_Form();
});

// --- Global instances ---
$locations_instance = new DBDR_Locations();
$reports_instance   = new DBDR_Admin_Dashboard();

// --- Admin menus ---
add_action('admin_menu', function() use ($locations_instance, $reports_instance) {

    // Locations top-level menu
    add_menu_page(
        'Dog Bag Locations',
        'Locations',
        'manage_options',
        'dbdr-locations',
        [$locations_instance, 'render_page'],  // Main page (Add/List)
        'dashicons-location-alt',
        25
    );

    // Dedicated Edit page slug
    add_submenu_page(
        null,  // Hidden from menu
        'Edit Location',
        'Edit Location',
        'manage_options',
        'dbdr-locations-edit',
        [$locations_instance, 'render_edit_page']
    );

    // Reports top-level menu
    add_menu_page(
        'Dog Bag Reports',
        'Reports',
        'manage_options',
        'dbdr-reports',
        [$reports_instance, 'render_reports_page'],
        'dashicons-feedback',
        26
    );
});
