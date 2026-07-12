<?php

/**
 * Main plugin loader class responsible for initializing the NextWP plugin.
 *
 * @package    NextWP
 * @subpackage Core
 * @since      1.0.0
 * @author     NextWP Team
 * @copyright  2024 NextWP
 * @license    GPL-2.0+
 */

defined('ABSPATH') || exit;

/**
 * Class NextWP_Load
 *
 * Main plugin class that handles:
 * - Plugin initialization
 * - Admin interface setup
 * - REST API endpoints
 * - Asset management
 */
final class NextWP_Load
{

    /**
     * The single instance of the class.
     *
     * @var NextWP_Load|null
     */
    protected static $instance = null;

    /**
     * Base API URL for the NextWP service.
     *
     * @var string
     */
    public static $api_base_url = 'https://nextwp.io/';

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Initialize plugin hooks.
     *
     * @since 1.0.0
     */
    private function init_hooks()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_nextwp_admin_style_and_script']);
        add_action('admin_menu', [$this, 'register_nextwp_templates_page']);
        add_action('admin_head', [$this, 'nextwp_hide_admin_ui_css']);

        add_action('init', array('NextWP_Sites_Updater', 'init'));
        
        // Add cron job for automatic rate limit clearing
        add_action('nextwp_clear_rate_limits_cron', [$this, 'clear_expired_rate_limits']);
        
        // Schedule cron job if not already scheduled
        if (!wp_next_scheduled('nextwp_clear_rate_limits_cron')) {
            wp_schedule_event(time(), 'hourly', 'nextwp_clear_rate_limits_cron');
        }
    }

    /**
     * Clear expired rate limits via cron job.
     *
     * @since 1.0.0
     */
    public function clear_expired_rate_limits() {
        if (!function_exists('nextwp_smart_clear_rate_limits')) {
            return;
        }
        
        $cleared_count = nextwp_smart_clear_rate_limits();
        if ($cleared_count > 0) {
            error_log("NextWP: Cron job cleared {$cleared_count} expired rate limits");
        }
    }

    /**
     * Include required plugin files.
     *
     * @since  1.0.0
     * @throws Exception If a required file is missing.
     */
    public function includes()
    {
        $files = [
            'classes/class-nextwp-security.php',
            'src/admin/helper.php',
            'classes/class-nextwp-license.php',
            'classes/class-nextwp-sites.php',
            'classes/class-nextwp-setup-helper.php',
            'classes/class-nextwp-sites-updater.php',
            'classes/class-nextwp-import.php',
            'src/admin/overrides/theme-upgrader.php',
            'src/admin/overrides/theme-upgrader-skin.php',
        ];

        foreach ($files as $file) {
            $file_path = NEXTWP_DIR . $file;
            if (!file_exists($file_path)) {
                error_log(sprintf('NextWP: Required file missing: %s', $file_path));
                throw new Exception(
                    sprintf(esc_html__('Required file missing: %s. Please reinstall the plugin.', 'nextwp'), esc_html($file))
                );
            }
            require_once $file_path;
        }
    }

    /**
     * Register the admin menu pages.
     *
     * @since 1.0.0
     */
    public function register_nextwp_templates_page()
    {
        add_menu_page(
            esc_html__('NextWP Sites', 'nextwp'),
            esc_html__('NextWP Sites', 'nextwp'),
            'manage_options',
            'nextwp-sites',
            [$this, 'display_templates'],
            'dashicons-admin-generic',
            500
        );

        add_submenu_page(
            'nextwp-sites',
            esc_html__('License', 'nextwp'),
            esc_html__('License', 'nextwp'),
            'manage_options',
            'nextwp-license',
            ['NextWP_License_Manager', 'render_settings_page']
        );

        remove_submenu_page('nextwp-sites', 'nextwp-license');

    }

    public function nextwp_hide_admin_ui_css()
    {
        $screen = get_current_screen();

        if (!is_object($screen) || $screen->id !== 'toplevel_page_nextwp-sites') {
            return;
        }

        // CSS is now handled by enqueue_admin_ui_css method
        // This method is kept for backward compatibility but no longer outputs CSS
    }

    /**
     * Display the templates page.
     *
     * @since 1.0.0
     * @return string Rendered templates page HTML
     */
    public function display_templates(): string
    {

        
        wp_enqueue_style('all-templates');
        wp_enqueue_script('nextwp-admin-script');

        wp_enqueue_style('nextwp-swiper-style');
        wp_enqueue_script('nextwp-swiper-script');

        $output = (new NextWP_Sites())->render_nextwp_templates_page();
        if (empty($output) || !is_string($output)) {
            return esc_html__('Error loading template page', 'nextwp');
        }
        return $output;
    }

    /**
     * Register and enqueue admin styles and scripts.
     *
     * @since 1.0.0
     */
    public function enqueue_nextwp_admin_style_and_script()
    {
        // Register the CSS file
        wp_register_style(
            'all-templates',
            esc_url(NEXTWP_URL . 'assets/css/all-templates.css'),
            [],
            NEXTWP_VERSION,
            'all'
        );

        wp_register_style(
            'nextwp-swiper-style',
            esc_url(NEXTWP_URL . 'assets/css/swiper.min.css'),
            [],
            NEXTWP_VERSION,
            'all'
        );

        // Register the JavaScript file
        wp_register_script(
            'nextwp-admin-script',
            esc_url(NEXTWP_URL . 'assets/js/nextwp-admin.js'),
            ['jquery'],
            NEXTWP_VERSION,
            true
        );

        wp_register_script(
            'nextwp-swiper-script',
            esc_url(NEXTWP_URL . 'assets/js/swiper.min.js'),
            ['jquery'],
            NEXTWP_VERSION,
            true
        );

        // Localize script with nonce and other data
        wp_localize_script('nextwp-admin-script', 'nextwp_params', [
            'nonce' => wp_create_nonce('wp_rest'),
            'template_nonce' => wp_create_nonce('template_nonce'),
            'template_operations_nonce' => wp_create_nonce('nextwp_template_operations'),
            'apiUrl' => esc_url_raw(rest_url()),
            'ajax_url' => esc_url(admin_url('admin-ajax.php')),
            'admin_url' => esc_url(admin_url()),
            'site_url' => esc_url(get_site_url()),
        ]);
        
        // Enqueue the admin UI hiding CSS
        $this->enqueue_admin_ui_css();
    }

    /**
     * Enqueue CSS for hiding admin UI elements.
     *
     * @since 1.0.0
     */
    private function enqueue_admin_ui_css()
    {
        $custom_css = "
            /* Early hide admin UI before main CSS loads */
            .notice,
            .hide,
            .update-nag,
            #adminmenu,
            #adminmenuback,
            #adminmenuwrap,
            #wpadminbar,
            #wpfooter {
                display: none !important;
            }

            #wpcontent,
            #wpbody,
            #wpbody-content,
            html.wp-toolbar {
                margin-left: 0 !important;
                padding-left: 0 !important;
                padding-bottom: 0 !important;
                padding-top: 0 !important;
            }
            
            body {
                background: #202227;
            }
            
            .nwp-template-loader {
                position: relative;
                width: 100%;
                height: calc(100vh - 64px);
                background: #202227;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999;
                top: 0;
                left: 0;
            }

            .nwp-loader-text h6 {
                font-size: 18px;
                color: #fff;
                font-weight: 500;
                text-align: center;
                margin: 0 10px 0 0;
            }

            .nwp-loader-text img {
                height: 120px;
                margin: 16px auto 0 auto;
                display: block;
            }
        ";

        wp_add_inline_style('all-templates', $custom_css);
    }

    /**
     * Register REST API routes.
     *
     * @since 1.0.0
     */
    public function register_routes()
    {
        register_rest_route('setup-helper/v1', '/theme-install', [
            'methods' => 'POST',
            'callback' => [$this, 'nextwp_api_theme_install_callback'],
            'permission_callback' => [$this, 'manage_options_permission'],
            'args' => [
                'template_id' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_template_id']
                ]
            ]
        ]);

        register_rest_route('setup-helper/v1', '/plugin-install', [
            'methods' => 'POST',
            'callback' => [$this, 'nextwp_api_plugin_install_callback'],
            'permission_callback' => [$this, 'manage_options_permission'],
            'args' => [
                'template_id' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_template_id']
                ],
                'nextcall' => [
                    'required' => false,
                    'sanitize_callback' => 'sanitize_text_field'
                ]
            ]
        ]);

        register_rest_route('setup-helper/v1', '/content-import', [
            'methods' => 'POST',
            'callback' => [$this, 'nextwp_api_content_import_callback'],
            'permission_callback' => [$this, 'manage_options_permission'],
            'args' => [
                'template_id' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_template_id']
                ],
                'nextcall' => [
                    'required' => false,
                    'sanitize_callback' => 'sanitize_text_field'
                ]
            ]
        ]);

        // Add rate limit reset endpoint for administrators
        register_rest_route('setup-helper/v1', '/reset-rate-limits', [
            'methods' => 'POST',
            'callback' => [$this, 'nextwp_api_reset_rate_limits_callback'],
            'permission_callback' => [$this, 'manage_options_permission'],
            'args' => [
                'operation' => [
                    'required' => false,
                    'sanitize_callback' => 'sanitize_text_field'
                ]
            ]
        ]);
    }

    /**
     * Validate template ID parameter.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate_template_id($value): bool
    {
        return is_numeric($value) && $value > 0;
    }

    /**
     * REST API callback for theme installation.
     *
     * @param WP_REST_Request $request
     * @return array|WP_Error
     */
    public function nextwp_api_theme_install_callback(WP_REST_Request $request)
    {
        $template_id = absint($request->get_param('template_id'));
        return (new NextWP_Setup_Helper())->install_and_activate_theme($template_id);
    }

    /**
     * REST API callback for plugin installation.
     *
     * @param WP_REST_Request $request
     * @return array|WP_Error
     */
    public function nextwp_api_plugin_install_callback(WP_REST_Request $request)
    {
        $template_id = absint($request->get_param('template_id'));
        $nextcall = sanitize_text_field($request->get_param('nextcall'));
        return (new NextWP_Setup_Helper())->install_and_activate_plugins($template_id, $nextcall);
    }

    /**
     * REST API callback for content import.
     *
     * @param WP_REST_Request $request
     * @return array|WP_Error
     */
    public function nextwp_api_content_import_callback(WP_REST_Request $request)
    {
        $template_id = absint($request->get_param('template_id'));
        $nextcall = sanitize_text_field($request->get_param('nextcall'));
        return (new NextWP_Setup_Helper())->import_content($template_id, $nextcall);
    }

    /**
     * REST API callback for rate limit reset.
     *
     * @param WP_REST_Request $request
     * @return array|WP_Error
     */
    public function nextwp_api_reset_rate_limits_callback(WP_REST_Request $request)
    {
        $operation = sanitize_text_field($request->get_param('operation'));
        
        // Reset rate limits in both setup helper and import classes
        $setup_helper = new NextWP_Setup_Helper();
        $import_class = new NextWP_Import();
        
        $setup_result = $setup_helper->reset_rate_limit($operation);
        $import_result = $import_class->reset_rate_limit($operation);
        
        if ($setup_result && $import_result) {
            return [
                'status' => 'success',
                'message' => esc_html__('Rate limits reset successfully.', 'nextwp')
            ];
        } else {
            return new WP_Error(
                'rate_limit_reset_failed',
                esc_html__('Failed to reset rate limits.', 'nextwp'),
                ['status' => 500]
            );
        }
    }

    /**
     * Check current rate limit status for debugging.
     *
     * @param string $operation The operation to check
     * @return array Rate limit status information
     */
    public function check_rate_limit_status($operation = '') {
        if (!current_user_can('manage_options')) {
            return ['error' => 'Insufficient permissions'];
        }
        
        $status = [];
        
        if (empty($operation) || $operation === 'content_import') {
            $rate_limit_key = 'nextwp_import_rate_limit_content_import';
            $attempts = get_transient($rate_limit_key) ?: 0;
            $status['content_import'] = [
                'attempts' => $attempts,
                'limit' => 50,
                'remaining' => max(0, 50 - $attempts)
            ];
        }
        
        if (empty($operation) || $operation === 'theme_install') {
            $rate_limit_key = 'nextwp_setup_rate_limit_theme_install';
            $attempts = get_transient($rate_limit_key) ?: 0;
            $status['theme_install'] = [
                'attempts' => $attempts,
                'limit' => 20,
                'remaining' => max(0, 20 - $attempts)
            ];
        }
        
        if (empty($operation) || $operation === 'plugin_install') {
            $rate_limit_key = 'nextwp_setup_rate_limit_plugin_install';
            $attempts = get_transient($rate_limit_key) ?: 0;
            $status['plugin_install'] = [
                'attempts' => $attempts,
                'limit' => 20,
                'remaining' => max(0, 20 - $attempts)
            ];
        }
        
        return $status;
    }

    /**
     * Check if current user has manage_options capability.
     *
     * @return bool
     */
    public function manage_options_permission(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * Get the singleton instance.
     *
     * @return NextWP_Load
     */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
