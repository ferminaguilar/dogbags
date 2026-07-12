<?php
/**
 * Main plugin loader class.
 *
 * @package WooMerchant
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Woo_Merchant_Load class.
 *
 * Handles plugin initialization, dependencies, and core functionality.
 */
final class Woo_Merchant_Load
{

    /**
     * The single instance of the class.
     *
     * @var Woo_Merchant_Load
     * @since 1.0.0
     */
    protected static $instance = null;

    /**
     * Check if WooCommerce is active and initialize the class.
     *
     * @since 1.0.0
     * @return Woo_Merchant_Load|false Plugin instance or false if WooCommerce not active
     */
    public static function instance()
    {
        if (!class_exists('WooCommerce')) {
            self::admin_notice_woocommerce_required();
            return false;
        }

        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->init_hooks();
        $this->includes();
    }

    /**
     * Initialize plugin hooks.
     *
     * @since 1.0.0
     */
    private function init_hooks()
    {
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'), 90);
        add_action('init', array($this, 'init'));
    }

    /**
     * Include required core files.
     *
     * @since 1.0.0
     */
    public function includes()
    {
        // Initialize plugin components
        add_action('init', array('Woo_Merchant_Updater', 'init'));
        add_action('init', array('Woo_Merchant_Enqueue', 'init'));
        add_action('init', array('Woo_Merchant_Cart', 'init'), 10);
        add_action('init', array('Woo_Merchant_Feature_Settings', 'init'), 10);
        add_action('init', array('Woo_Merchant_Product_Settings', 'init'), 10);
        add_action('init', array('Woo_Merchant_Features_Callback', 'init'), 20);
        add_action('woo_merchant_loaded', array('Woo_Merchant_Elementor', 'init'),10);

        $files = array(
            'includes/helper.php',
        );

        // Conditionally include WooCommerce checkout functions
        

        foreach ($files as $file) {
            $file_path = trailingslashit(WOO_MERCHANT_PLUGIN_DIR) . $file;

            if (file_exists($file_path) && is_readable($file_path)) {
                require_once $file_path;
            } else {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    trigger_error(
                        sprintf('Required file %s is missing or unreadable', esc_html($file_path)),
                        E_USER_WARNING
                    );
                }
            }
        }
    }

    /**
     * Initialize plugin localization.
     *
     * @since 1.0.0
     */
    public function init()
    {
        $this->load_plugin_textdomain();
    }

    /**
     * Handle plugins_loaded action.
     *
     * @since 1.0.0
     */
    public function on_plugins_loaded()
    {
        do_action('woo_merchant_loaded');
    }

    /**
     * Display admin notice when WooCommerce is not active.
     *
     * @since 1.0.0
     */
    public static function admin_notice_woocommerce_required()
    {
        $message = sprintf(
            esc_html__('Woo Merchant requires WooCommerce to be installed and active. You can download %s here.', 'woo-merchant'),
            '<a href="https://woocommerce.com/" target="_blank" rel="noopener noreferrer">WooCommerce</a>'
        );

        if (is_admin() && !defined('DOING_AJAX')) {
            add_action('admin_notices', function () use ($message) {
                printf(
                    '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                    wp_kses_post($message)
                );
            });
        }
    }

    /**
     * Load plugin textdomain for localization.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {
        $locale = function_exists('determine_locale') 
            ? determine_locale() 
            : (is_admin() ? get_user_locale() : get_locale());

        $locale = apply_filters('plugin_locale', $locale, 'woo-merchant');

        unload_textdomain('woo-merchant');
        load_textdomain(
            'woo-merchant',
            WP_LANG_DIR . '/woo-merchant/woo-merchant-' . $locale . '.mo'
        );
        load_plugin_textdomain(
            'woo-merchant',
            false,
            dirname(plugin_basename(WOO_MERCHANT_PLUGIN_DIR)) . '/languages'
        );
    }
}
