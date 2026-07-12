<?php

defined('ABSPATH') || exit;

/**
 * Woo Merchant EDD Plugin Updater
 * Requires EDD Software Licensing on your store.
 *
 * Place in: wp-content/plugins/woo-merchant/classes/class-woo-merchant-updater.php
 * and load from your main plugin file.
 */

// Include the EDD SL Plugin Updater class
if (!class_exists('Woo_Merchant_EDD_SL_Plugin_Updater')) {
    require_once plugin_dir_path(__FILE__) . 'Woo_Merchant_EDD_SL_Plugin_Updater.php';
}

class Woo_Merchant_Updater
{

    const STORE_URL       = 'https://vpaddons.com';
    const ITEM_ID         = 46; // 🔹 Replace with your actual EDD Download ID for Woo Merchant
    const ITEM_NAME       = 'Woo Merchant'; // Fallback name
    const PLUGIN_SLUG     = 'woo-merchant/woo-merchant.php'; // main plugin file path
    const LICENSE_OPTION  = 'woo-merchant_key';
    const AUTHOR          = 'ZemoWP';

    /** @var string */
    private $current_version;

    public function __construct()
    {
        if (function_exists('get_plugin_data')) {
            $plugin_data         = get_plugin_data(WP_PLUGIN_DIR . '/' . self::PLUGIN_SLUG, false, false);
            $this->current_version = isset($plugin_data['Version']) ? $plugin_data['Version'] : '';
        }

        // Boot the EDD updater on admin.
        add_action('admin_init', array($this, 'init_updater'), 0);
    }

    /**
     * Instantiate EDD SL Plugin Updater.
     */
    public function init_updater()
    {
        if (! function_exists('get_option')) {
            return;
        }

        // Retrieve license key from options
        $license = get_option(self::LICENSE_OPTION);

        // Debugging: Force update check by clearing transient
        
        // $transient_key = 'edd_sl_check_for_update_' . self::PLUGIN_SLUG;
        // delete_transient($transient_key);

        // Abort if license is not set
        if (empty($license)) {
            return;
        }

        // Preferred: Woo_Merchant_EDD_SL_Plugin_Updater
        if (class_exists('Alledia\\Woo_Merchant_EDD_SL_Plugin_Updater')) {
            new \Alledia\Woo_Merchant_EDD_SL_Plugin_Updater(
                self::STORE_URL,
                self::PLUGIN_SLUG,
                array(
                    'version'   => $this->current_version,
                    'license'   => $license,
                    'item_id'   => self::ITEM_ID,
                    'item_name' => self::ITEM_NAME,
                    'author'    => self::AUTHOR,
                    'url'       => home_url(),
                    'beta'      => false,
                )
            );
            return;
        }

        // Legacy class (older setups)
        if (class_exists('EDD_Plugin_Updater')) {
            new EDD_Plugin_Updater(
                self::STORE_URL,
                self::PLUGIN_SLUG,
                array(
                    'version'   => $this->current_version,
                    'license'   => $license,
                    'item_id'   => self::ITEM_ID,
                    'item_name' => self::ITEM_NAME,
                    'author'    => self::AUTHOR,
                    'url'       => home_url(),
                    'beta'      => false,
                )
            );
        }
    }

    public static function init()
    {
        $CubeClass = __CLASS__;
        new $CubeClass();
    }
}
