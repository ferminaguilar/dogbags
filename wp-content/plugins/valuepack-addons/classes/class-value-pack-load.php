<?php

/**
 * Value Pack initializer.
 *
 * @package valuep/cube/classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Value_Pack_Load
 */
class Value_Pack_Load
{

    /**
     * The single instance of the class.
     *
     * @var Value_Pack_Load
     */
    protected static $Load = null;

    public static function instance()
    {
        $proceed = true;

        if (! did_action('elementor/loaded')) {
            //self::admin_notice_elementor_required();
            $proceed = false; // Abort if WooCommerce isn't active
        }

        if (! $proceed) {
            return false;
        }

        if (is_null(self::$Load)) {
            self::$Load = new self();
        }

        return self::$Load;
    }

    /**
     * Value_Pack_Load Constructor.
     */
    public function __construct()
    {
        self::init_hooks();
        self::includes();
    }

    /**
     * Method init_hooks
     *
     * @since  1.0.0
     */
    private function init_hooks()
    {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'), -1);
        add_action('widgets_init', array($this, 'register_widgets'));
    }

    /**
     * Include required files for admin and frontend.
     * @since  1.0.0
     */
    public function includes()
    {
        $files = array(
            'include/helper.php',
            'include/blocks/blocks.php',
        );
        foreach ($files as $file) {
            $file = VALUE_PACK_PLUGIN_DIR . $file;
            if (file_exists($file) && is_readable($file)) {
                require_once $file;
            }
        }
        add_action('init', array('Value_Pack_Enqueue', 'init'));
        add_action('value_pack_loaded', array('Value_Pack_Elementor', 'init'));
        if (is_admin()) {
            add_action('init', array('Value_Pack_Updater', 'init'));
        }
        add_action('plugins_loaded', array('Value_Pack_Post_Reactions', 'init'));

        if (class_exists('CubeWp_Load')) {
            add_action('init', array('Value_Pack_User_Login', 'init'), 10);
            add_action('init', array('Value_Pack_User_Registration', 'init'), 10);
        }
    }

    /**
     * Register custom widgets.
     */
    public function register_widgets()
    {
        if (class_exists('Value_Pack_Recent_Posts_Widget')) {
            register_widget('Value_Pack_Recent_Posts_Widget');
        }
    }

    /**
     * Display an admin notice when WooCommerce is not active.
     */
    public static function admin_notice_elementor_required()
    {
        $elementor_plugin = 'elementor/elementor.php';
        if (self::check_if_plugin_installed($elementor_plugin)) {
            $action_url = wp_nonce_url(
                self_admin_url('plugins.php?action=activate&plugin=' . esc_attr($elementor_plugin) . '&plugin_status=all'),
                'activate-plugin_' . esc_attr($elementor_plugin)
            );
            $active_plugin = '<a href="' . esc_url($action_url) . '">Elementor</a>';
            $message = sprintf(
                /* translators: %s: Elementor plugin name */
                esc_html__('Value Pack Addons requires Elementor to be activated. You can activate %s here.', 'valuepack-addons'),
                $active_plugin
            );
        } else {
            $action_url = wp_nonce_url(
                self_admin_url('update.php?action=install-plugin&plugin=elementor'),
                'install-plugin_elementor'
            );
            $active_plugin = '<a href="' . esc_url($action_url) . '">Elementor</a>';
            $message = sprintf(
                /* translators: %s: Elementor plugin name */
                esc_html__('Value Pack Addons requires Elementor to be installed. You can install %s here.', 'valuepack-addons'),
                $active_plugin
            );
        }

        // Display the admin notice
        if (is_admin() && ! defined('DOING_AJAX')) {
            add_action('admin_notices', function () use ($message) {
                echo '<div class="notice notice-error"><p><strong>' . wp_kses_post($message) . '</strong></p></div>';
            });
        }
    }

    /**
     * Initialize plugin localization.
     *
     * @since 1.0.0
     */
    public function init()
    {
        $this->value_pack_load_plugin_textdomain();
    }

    /**
     * value_pack_loaded action hook to load something on plugin_loaded action.
     * Method on_plugins_loaded
     *
     * @since  1.0.0
     */
    public function on_plugins_loaded()
    {
        do_action('value_pack_loaded');
    }

    public static function check_if_plugin_installed($plugin_path)
    {
        // Ensure the required functions are available
        if (! function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();
        return isset($installed_plugins[$plugin_path]);
    }

    /**
     * Method value_pack_load_plugin_textdomain
     * 
     * Load plugin textdomain for localization.
     *
     * @since 1.0.0
     */
    public function value_pack_load_plugin_textdomain() {

        $domain = 'valuepack-addons';
    
        $locale = function_exists('determine_locale')
            ? determine_locale()
            : ( is_admin() ? get_user_locale() : get_locale() );
    
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core 'plugin_locale' filter
        $locale = apply_filters( 'plugin_locale', $locale, $domain );
    
        unload_textdomain( $domain );
    
        // Load from wp-content/languages/plugins
        load_textdomain(
            $domain,
            WP_LANG_DIR . '/plugins/' . $domain . '-' . $locale . '.mo'
        );
    
        // Load from plugin's languages directory
        load_plugin_textdomain($domain,false,dirname( plugin_basename( VALUE_PACK_PLUGIN_FILE ) ) . '/languages/'); // phpcs:ignore PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound	
    }
    
}
