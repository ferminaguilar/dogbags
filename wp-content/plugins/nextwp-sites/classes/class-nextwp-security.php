<?php
/**
 * NextWP Security Manager
 *
 * Handles security configurations and provides security utilities for the NextWP plugin.
 *
 * @package    NextWP
 * @subpackage Security
 * @since      1.0.0
 * @author     NextWP Team
 * @copyright  2024 NextWP
 * @license    GPL-2.0+
 */

defined('ABSPATH') || exit;

/**
 * Class NextWP_Security
 *
 * Provides security functionality including:
 * - Security headers
 * - Rate limiting
 * - Input validation
 * - Security logging
 */
final class NextWP_Security {

    /**
     * Maximum allowed file size for downloads (300MB)
     */
    const MAX_FILE_SIZE = 314572800;

    /**
     * Allowed file types for theme/plugin packages
     */
    const ALLOWED_TYPES = ['zip'];

    /**
     * Maximum allowed URL length
     */
    const MAX_URL_LENGTH = 2048;

    /**
     * Rate limiting configuration
     */
    const RATE_LIMIT_CONFIG = [
        'theme_install' => ['max_attempts' => 5, 'timeout' => HOUR_IN_SECONDS],
        'plugin_install' => ['max_attempts' => 10, 'timeout' => HOUR_IN_SECONDS],
        'content_import' => ['max_attempts' => 3, 'timeout' => HOUR_IN_SECONDS],
        'api_request' => ['max_attempts' => 100, 'timeout' => HOUR_IN_SECONDS],
    ];

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize security hooks.
     *
     * @since 1.0.0
     */
    private function init_hooks(): void {
        add_action('admin_init', [$this, 'add_security_headers']);
        add_action('wp_ajax_nextwp_', [$this, 'verify_ajax_nonce']);
        add_filter('upload_mimes', [$this, 'restrict_upload_mimes'], 10, 1);
    }

    /**
     * Add security headers for admin pages.
     *
     * @since 1.0.0
     */
    public function add_security_headers(): void {
        if (!$this->is_nextwp_admin_page()) {
            return;
        }

        // Security headers
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }

    /**
     * Check if current page is a NextWP admin page.
     *
     * @since 1.0.0
     * @return bool True if on NextWP admin page
     */
    private function is_nextwp_admin_page(): bool {
        $screen = get_current_screen();
        return $screen && (
            strpos($screen->id, 'nextwp') !== false ||
            (isset($_GET['page']) && $_GET['page'] === 'nextwp-sites')
        );
    }

    /**
     * Verify AJAX nonce for NextWP operations.
     *
     * @since 1.0.0
     */
    public function verify_ajax_nonce(): void {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nextwp_ajax_nonce')) {
            wp_die(esc_html__('Security check failed.', 'nextwp'), 403);
        }
    }

    /**
     * Restrict upload mime types for security.
     *
     * @since 1.0.0
     * @param array $mimes Current allowed mime types
     * @return array Modified mime types
     */
    public function restrict_upload_mimes($mimes): array {
        // Only allow administrators to upload potentially dangerous file types
        if (!current_user_can('manage_options')) {
            unset($mimes['svg']);
            unset($mimes['swf']);
            unset($mimes['exe']);
        }
        
        return $mimes;
    }

    /**
     * Validate and sanitize a URL.
     *
     * @since 1.0.0
     * @param string $url The URL to validate
     * @return string|false Sanitized URL or false if invalid
     */
    public static function validate_url($url) {
        if (empty($url) || strlen($url) > self::MAX_URL_LENGTH) {
            return false;
        }

        $url = esc_url_raw(trim($url));
        if (empty($url)) {
            return false;
        }

        $parsed = parse_url($url);
        if (!$parsed || !isset($parsed['scheme'], $parsed['host'])) {
            return false;
        }

        // Only allow http/https protocols
        if (!in_array($parsed['scheme'], ['http', 'https'], true)) {
            return false;
        }

        return $url;
    }

    /**
     * Validate file type for security.
     *
     * @since 1.0.0
     * @param string $filename The filename to validate
     * @return bool True if file type is allowed
     */
    public static function validate_file_type($filename): bool {
        if (empty($filename)) {
            return false;
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, self::ALLOWED_TYPES, true);
    }

    /**
     * Check if operation should be rate limited.
     *
     * @since 1.0.0
     * @param string $operation The operation being performed
     * @param int $user_id Optional user ID (defaults to current user)
     * @return bool True if operation should be rate limited
     */
    public static function should_rate_limit($operation, $user_id = null): bool {
        if (!isset(self::RATE_LIMIT_CONFIG[$operation])) {
            return false;
        }

        if ($user_id === null) {
            $user_id = get_current_user_id();
        }

        $config = self::RATE_LIMIT_CONFIG[$operation];
        $rate_limit_key = 'nextwp_rate_limit_' . $operation . '_' . $user_id;
        
        $attempts = get_transient($rate_limit_key) ?: 0;
        
        if ($attempts >= $config['max_attempts']) {
            error_log(sprintf('NextWP: Rate limit exceeded for operation %s by user %d', $operation, $user_id));
            return true;
        }
        
        return false;
    }

    /**
     * Track operation attempt for rate limiting.
     *
     * @since 1.0.0
     * @param string $operation The operation being performed
     * @param int $user_id Optional user ID (defaults to current user)
     */
    public static function track_operation_attempt($operation, $user_id = null): void {
        if (!isset(self::RATE_LIMIT_CONFIG[$operation])) {
            return;
        }

        if ($user_id === null) {
            $user_id = get_current_user_id();
        }

        $config = self::RATE_LIMIT_CONFIG[$operation];
        $rate_limit_key = 'nextwp_rate_limit_' . $operation . '_' . $user_id;
        
        $attempts = get_transient($rate_limit_key) ?: 0;
        set_transient($rate_limit_key, $attempts + 1, $config['timeout']);
    }

    /**
     * Log security event.
     *
     * @since 1.0.0
     * @param string $event The security event
     * @param array $context Additional context information
     */
    public static function log_security_event($event, $context = []): void {
        $log_entry = [
            'timestamp' => current_time('mysql'),
            'event' => sanitize_text_field($event),
            'user_id' => get_current_user_id(),
            'ip_address' => $this->get_client_ip(),
            'user_agent' => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? ''),
            'context' => $context
        ];

        error_log('NextWP Security Event: ' . json_encode($log_entry));
    }

    /**
     * Get client IP address.
     *
     * @since 1.0.0
     * @return string Client IP address
     */
    private function get_client_ip(): string {
        $ip_keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Sanitize and validate template ID.
     *
     * @since 1.0.0
     * @param mixed $template_id The template ID to validate
     * @return int|false Valid template ID or false if invalid
     */
    public static function validate_template_id($template_id) {
        if (empty($template_id)) {
            return false;
        }

        $template_id = absint($template_id);
        return $template_id > 0 ? $template_id : false;
    }

    /**
     * Verify nonce for template operations.
     *
     * @since 1.0.0
     * @param string $nonce The nonce to verify
     * @param string $action The nonce action
     * @return bool True if nonce is valid
     */
    public static function verify_template_nonce($nonce, $action = 'nextwp_template_operations') {
        if (empty($nonce)) {
            return false;
        }

        return wp_verify_nonce($nonce, $action);
    }
}

// Initialize the security manager
new NextWP_Security();

