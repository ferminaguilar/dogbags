<?php
/**
 * NextWP Setup Helper
 *
 * Handles theme and plugin installation/activation for template setups.
 *
 * @package    NextWP
 * @subpackage Setup
 * @since      1.0.0
 * @author     NextWP Team
 * @copyright  2024 NextWP
 * @license    GPL-2.0+
 */

defined('ABSPATH') || exit;

/**
 * Class NextWP_Setup_Helper
 *
 * Provides functionality for:
 * - Theme installation and activation
 * - Plugin installation and activation
 * - Content import coordination
 */
final class NextWP_Setup_Helper {

    /**
     * Maximum allowed file size for downloads (10MB)
     */
    const MAX_FILE_SIZE = 15485760;

    /**
     * Allowed file types for theme/plugin packages
     */
    const ALLOWED_TYPES = ['zip'];

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->include_required_files();
    }

    /**
     * Include required WordPress files.
     *
     * @since 1.0.0
     * @throws Exception If required files are missing
     */
    private function include_required_files(): void {
        $required_files = [
            ABSPATH . 'wp-admin/includes/file.php',
            ABSPATH . 'wp-admin/includes/upgrade.php'
        ];

        foreach ($required_files as $file) {
            if (!file_exists($file)) {
                throw new Exception(
                    sprintf(esc_html__('Required WordPress file missing: %s', 'nextwp'), $file)
                );
            }
            require_once $file;
        }
    }

    /**
     * Initialize the setup helper.
     *
     * @since 1.0.0
     * @return NextWP_Setup_Helper
     */
    public static function init(): self {
        return new self();
    }

    /**
     * Install and activate a theme from a template.
     *
     * @param int $template_id The template ID containing theme data
     * @return array|WP_Error Result array or WP_Error on failure
     */
    public function install_and_activate_theme(int $template_id) {
        try {
            // Validate template ID
            if ($template_id <= 0) {
                throw new Exception(esc_html__('Invalid template ID provided.', 'nextwp'));
            }
            
            // Check user capabilities
            if (!current_user_can('install_themes')) {
                error_log('NextWP: Unauthorized theme installation attempt by user: ' . get_current_user_id());
                throw new Exception(esc_html__('Insufficient permissions to install themes.', 'nextwp'));
            }
            
            // Rate limiting check
            if ($this->should_rate_limit_operation('theme_install')) {
                throw new Exception(esc_html__('Too many theme installation attempts. Please try again in an hour.', 'nextwp'));
            }
            
            // Track this attempt
            $this->track_operation_attempt('theme_install');
            
            $template = nextwp_single_resources($template_id);
            if (!is_array($template)) {
                error_log('NextWP: Failed to get template resources for theme installation: ' . $template_id);
                throw new Exception(esc_html__('Failed to retrieve template information.', 'nextwp'));
            }

            $theme_data = $template['theme'] ?? [];
            $theme = sanitize_text_field($theme_data['slug'] ?? '');
            $theme_url = esc_url_raw($theme_data['source'] ?? '');

            if (empty($theme)) {
                return [
                    'status' => 'error',
                    'message' => esc_html__('No theme is associated with this template.', 'nextwp')
                ];
            }
    
            // Check if the theme is already installed
            $installed_themes = wp_get_themes();
            if (array_key_exists($theme, $installed_themes)) {
                if (get_option('stylesheet') === $theme) {
                    return [
                        'status' => 'success',
                        'message' => esc_html__('The theme is already installed and activated.', 'nextwp'),
                        'slug' => $theme
                    ];
                }

                switch_theme($theme);
                return [
                    'status' => 'success',
                    'message' => esc_html__('The theme activated successfully.', 'nextwp'),
                    'slug' => $theme
                ];
            }
        
            // Determine if the theme should be installed from a URL or the repository
            if (!empty($theme_url) && filter_var($theme_url, FILTER_VALIDATE_URL)) {
                if (!$this->validate_download_url($theme_url)) {
                    error_log('NextWP: Invalid theme download URL: ' . $theme_url);
                    throw new Exception(esc_html__('Invalid theme download URL', 'nextwp'));
                }
                // Manual URL installation
                include_once ABSPATH . '/wp-admin/includes/admin.php';
                include_once ABSPATH . '/wp-admin/includes/theme-install.php';
                include_once ABSPATH . '/wp-admin/includes/class-wp-upgrader.php';
                include_once ABSPATH . '/wp-admin/includes/class-theme-upgrader.php';
        
                $upgrader = new ThemeUpgrader(new ThemeUpgraderSkin());
                $result = $upgrader->install($theme_url);
        
                if (is_wp_error($result) || is_null($result)) {
                    return new \WP_Error(
                        'nextwp_rest_theme_install',
                        sprintf(__('Theme not found in WordPress repository.', 'nextwp'), $theme),
                        500
                    );
                }
            } else {
                // Install from WordPress repository
                include_once ABSPATH . '/wp-admin/includes/theme-install.php';
        
                $api = themes_api('theme_information', array(
                    'slug' => $theme,
                    'fields' => array('sections' => false),
                ));
        
                if (is_wp_error($api)) {
                    return new \WP_Error(
                        'nextwp_rest_invalid_theme_slug',
                        __('Theme not found in WordPress repository.', 'nextwp'),
                        400
                    );
                }
        
                $theme_url = $api->download_link;
        
                include_once ABSPATH . '/wp-admin/includes/class-wp-upgrader.php';
                include_once ABSPATH . '/wp-admin/includes/class-theme-upgrader.php';
        
                $upgrader = new ThemeUpgrader(new ThemeUpgraderSkin());
                $result = $upgrader->install($theme_url);
        
                if (is_wp_error($result) || is_null($result)) {
                    return new \WP_Error(
                        'nextwp_rest_theme_install',
                        sprintf(__('Theme `%s` could not be installed.', 'nextwp'), $theme),
                        500
                    );
                }
            }
        
            // Activate the theme after successful installation
            switch_theme($theme);
        
            return array(
                'status' => 'success',
                'message' => 'Theme installed and activated successfully.',
                'slug'   => $theme,
            );
            
        } catch (Exception $e) {
            error_log('NextWP: Theme installation error: ' . $e->getMessage());
            return new WP_Error(
                'theme_install_error',
                $e->getMessage(),
                ['status' => 500]
            );
        }
    }

    /**
     * Validate a download URL meets security requirements.
     *
     * @param string $url The URL to validate
     * @return bool Whether URL is valid
     */
    private function validate_download_url(string $url): bool {
        $parsed = wp_parse_url($url);
        if (!$parsed || !isset($parsed['scheme'], $parsed['host'])) {
            return false;
        }

        // Check file type
        $path = $parsed['path'] ?? '';
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), self::ALLOWED_TYPES, true)) {
            return false;
        }

        // Check file size via HEAD request
        $response = wp_remote_head($url, [
            'timeout' => 5,
            'sslverify' => true
        ]);

        if (!is_wp_error($response)) {
            $size = $response['headers']['content-length'] ?? 0;
            if ($size > self::MAX_FILE_SIZE) {
                return false;
            }
        }

        return true;
    }
    
    
    

    public function install_and_activate_plugins($template_id = '', $nextcall = '') {
        $results = [];
        $template_id = intval($template_id);
        $template = nextwp_single_resources($template_id);
        if (!is_array($template)) {
            return new WP_Error('invalid_data', 'Invalid template data returned.', ['status' => 500]);
        }        
        $plugin_data = isset($template['plugins']) ? $template['plugins'] : [];
        if(!empty($plugin_data)){
            $nextcall = !is_null($nextcall) && !empty($nextcall) ? $nextcall: array_key_first($plugin_data);
            if(isset($plugin_data[$nextcall])){
                $index = $nextcall;
                $plugin = $plugin_data[$nextcall];
                $plugin_slug = $plugin['slug'];
                $main_file = $plugin['main_file'];
                $plugin_exec_file = $plugin_slug . '.php';
                
                if (!empty($main_file)) {
                    $plugin_exec_file = $main_file . '.php';
                }
                $plugin['main_file'] = $plugin_exec_file;

                $result = $this->nextwp_install_plugins($plugin);
    
                if (isset($plugin_data[$index + 1])) {
                    // Return the response after installing the current plugin
                    return rest_ensure_response([
                        'status' => 'continue', 
                        'message' => $result, 
                        'nextcall' => $index + 1
                    ]);
                }else{
                    return rest_ensure_response([
                        'status' => 'success', 
                        'message' => $result,
                        'nextcall' => null
                    ]);
                }
            }
                
            return rest_ensure_response([
                'status' => 'success', 
                'message' => 'All plugins installed successfully', 
            ]);
        }
        
    }
    
    public function nextwp_install_plugins($plugin) {
        $plugin_name = $plugin['name'];
        $plugin_slug = $plugin['slug'];
        $plugin_url = $plugin['source'];
        $plugin_exec_file = $plugin['main_file'];

        if(empty($plugin_exec_file) || empty($plugin_slug)) return 'Plugin slug or execution file missing';
    
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    
        $plugin_main_file = WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_exec_file;
    
        // Check if the plugin is already installed
        if (file_exists($plugin_main_file)) {
            if (is_plugin_active($plugin_slug . '/' . $plugin_exec_file)) {
                return $plugin_name . ' is already active.';
            } else {
                $plugin_path = $plugin_slug . '/' . $plugin_exec_file;
                $activation_result = activate_plugin($plugin_path);
    
                if (is_wp_error($activation_result)) {
                    return $plugin_name . ' activation failed: ' . $activation_result->get_error_message();
                } else {
                    return $plugin_name . ' activated successfully.';
                }
            }
        }
    
        // If a custom source is provided, use it; otherwise, fetch from WordPress repository
        if (empty($plugin_url)) {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    
            $api = plugins_api('plugin_information', ['slug' => $plugin_slug, 'fields' => ['sections' => false]]);
            if (is_wp_error($api)) {
                return $plugin_name . ' not found in WordPress repository: ' . $api->get_error_message();
            }
    
            $plugin_url = $api->download_link;
        }
    
        // Install the plugin
        $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
        $installed = $upgrader->install($plugin_url);
    
        if (is_wp_error($installed)) {
            return $plugin_name . ' installation failed: ' . $installed->get_error_message();
        } else {
            if (file_exists($plugin_main_file)) {
                $plugin_path = $plugin_slug . '/' . $plugin_exec_file;
                $activation_result = activate_plugin($plugin_path);
    
                if (is_wp_error($activation_result)) {
                    return $plugin_name . ' activation failed: ' . $activation_result->get_error_message();
                } else {
                    return $plugin_name . ' installed and activated successfully.';
                }
            } else {
                return 'Main plugin file of ' . $plugin_name . ' does not exist after installation.';
            }
        }
    }
    

    public function import_content($template_id = '', $nextcall = '', $media = 0) {

        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_REST_Response( array(
                'status' => 'failed',
                'msg'     => esc_html__( "You do not have permission to perform this action.", 'cubewp-framework' )
            ), 403 );
        }

        $response = ( new NextWP_Import )->import_template_content($template_id, $nextcall);

        if(!empty($response) && isset($response['status'])){
            if($response['status'] == 'success' || $response['status'] == 'continue'){
                $status = 200;
            }else{
                $status = 403;
            }
            return new WP_REST_Response( $response, $status );
        }
    }

    /**
     * Check if operations should be rate limited.
     *
     * @param string $operation The operation being performed
     * @return bool Whether the operation should be rate limited
     */
    private function should_rate_limit_operation($operation) {
        // Skip rate limiting for administrators in development mode
        if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
            return false;
        }
        
        $rate_limit_key = 'nextwp_setup_rate_limit_' . $operation;
        $max_attempts = 20; // Allow 20 attempts per hour (more reasonable for development)
        $rate_limit = get_transient($rate_limit_key);
        
        // Check if this is an old rate limit (more than 2 hours)
        $last_activity_key = $rate_limit_key . '_last_activity';
        $last_activity = get_transient($last_activity_key);
        $current_time = current_time('timestamp');
        
        if ($last_activity && ($current_time - $last_activity) > (2 * HOUR_IN_SECONDS)) {
            // Clear old rate limit
            delete_transient($rate_limit_key);
            delete_transient($last_activity_key);
            $rate_limit = 0;
        }
        
        if ($rate_limit && $rate_limit >= $max_attempts) {
            error_log('NextWP: Setup rate limit exceeded for operation: ' . $operation);
            return true;
        }
        
        return false;
    }

    /**
     * Track operation attempt for rate limiting.
     *
     * @param string $operation The operation being performed
     */
    private function track_operation_attempt($operation) {
        $rate_limit_key = 'nextwp_setup_rate_limit_' . $operation;
        $last_activity_key = $rate_limit_key . '_last_activity';
        
        $attempts = get_transient($rate_limit_key) ?: 0;
        $current_time = current_time('timestamp');
        
        set_transient($rate_limit_key, $attempts + 1, HOUR_IN_SECONDS);
        set_transient($last_activity_key, $current_time, DAY_IN_SECONDS);
    }

    /**
     * Reset rate limiting for a specific operation (admin only).
     *
     * @param string $operation The operation to reset rate limiting for
     * @return bool Whether the reset was successful
     */
    public function reset_rate_limit($operation = '') {
        if (!current_user_can('manage_options')) {
            return false;
        }
        
        if (empty($operation)) {
            // Reset all rate limits
            $operations = ['theme_install', 'plugin_install', 'content_import'];
            foreach ($operations as $op) {
                $rate_limit_key = 'nextwp_setup_rate_limit_' . $op;
                delete_transient($rate_limit_key);
            }
        } else {
            // Reset specific operation
            $rate_limit_key = 'nextwp_setup_rate_limit_' . $operation;
            delete_transient($rate_limit_key);
        }
        
        return true;
    }

}
