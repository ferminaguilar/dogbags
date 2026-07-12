<?php
!defined('ABSPATH') && exit; // Exit if accessed directly
/**
 * Woomen_Theme_Maintenance
 */

// Include the EDD SL Plugin Updater class
if (!class_exists('Woomen_Theme_Services')) {
    require_once get_template_directory() . '/classes/Woomen_Theme_Services.php';
}

class Woomen_Theme_Maintenance
{
    const STORE_URL       = 'https://zemowp.com/index.php';
    const ITEM_ID         = 17;              // EDD Download ID
    const ITEM_NAME       = 'Woomen';        // EDD Download Name (fallback)
    const THEME_SLUG      = 'woomen';        // Theme directory slug
    const LICENSE_OPTION  = 'woomen_key';
	const STATUS_OPTION   = 'woomen-status';
    const AUTHOR          = 'ZemoWP';

    /** @var string */
    private $current_version;

    public function __construct()
    {
        // Get theme version correctly
        $theme = wp_get_theme(self::THEME_SLUG);
        $this->current_version = $theme->get('Version');

        // Boot the EDD updater on admin
        add_action('admin_init', array($this, 'init_updater'), 0);
        
    }

    /**
     * Instantiate EDD SL Theme Updater
     */
    public function init_updater()
    {
        if (!function_exists('get_option')) {
            return;
        }
		
        // Retrieve license key from options
        $license = trim(get_option(self::LICENSE_OPTION));
		$status = trim(get_option(self::STATUS_OPTION));
        // Abort if license is not set
        if (empty($license) && $status != 'valid' ) {
            return;
        }
		
		
		$transient_key = 'edd_sl_check_for_update_' . self::THEME_SLUG;
		delete_transient($transient_key);

      
        if (class_exists('Woomen_Theme_Services')) {
			
			new Woomen_Theme_Services(
				array(
					'remote_api_url' 	=> self::STORE_URL,
					'version' 			=> $this->current_version,
					'license' 			=> $license,
					'item_name' 		=> self::ITEM_NAME,
					'theme_slug' 		=> self::THEME_SLUG,
					'author'			=> self::AUTHOR
				)
			);
            return;
        }
    }

    public static function init()
    {
        $instance = __Class__;
        new $instance;
    }
}
