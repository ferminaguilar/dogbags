<?php
/**
 * NextWP License Manager
 *
 * Handles license activation, deactivation and validation for the NextWP plugin.
 * Integrates with Easy Digital Downloads (EDD) software licensing system.
 *
 * @package    NextWP
 * @subpackage License
 * @since      1.0.0
 * @author     NextWP Team
 * @copyright  2024 NextWP
 * @license    GPL-2.0+
 */

defined('ABSPATH') || exit;

/**
 * Class NextWP_License_Manager
 *
 * Manages all license-related functionality including:
 * - License activation/deactivation
 * - License status checks
 * - Admin notifications
 * - Rate limiting
 */
final class NextWP_License_Manager {

    /**
     * The store URL where licenses are managed.
     *
     * @var string
     */
    private $store_url = 'https://nextwp.io/index.php';

    /**
     * The ID of the item being licensed.
     *
     * @var int
     */
    private $item_id = 20324;

    /**
     * Transient key for rate limiting.
     *
     * @var string
     */
    private $rate_limit_key = 'nextwp_license_rate_limit';

    /**
     * Maximum allowed activation attempts per hour.
     *
     * @var int
     */
    private $max_attempts = 5;

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize license manager hooks.
     *
     * @since 1.0.0
     */
    private function init_hooks(): void {
        add_action('admin_init', [$this, 'handle_license_actions']);
        //add_action('admin_notices', [$this, 'show_license_notification']);
    }

    /**
     * Handle license activation/deactivation requests.
     *
     * @since 1.0.0
     */
    public function handle_license_actions(): void {
        if (isset($_POST['edd_activate_license'])) {
            $this->activate_license();
        } elseif (isset($_POST['edd_deactivate_license'])) {
            $this->deactivate_license();
        }
    }

    /**
     * Check if license activation attempts should be rate limited.
     *
     * @since 1.0.0
     * @return bool Whether the request should be rate limited
     */
    private function should_rate_limit(): bool {
        $rate_limit = get_transient($this->rate_limit_key);
        return $rate_limit && $rate_limit >= $this->max_attempts;
    }

    /**
     * Track license activation attempt for rate limiting.
     *
     * @since 1.0.0
     */
    private function track_attempt(): void {
        $attempts = get_transient($this->rate_limit_key) ?: 0;
        set_transient($this->rate_limit_key, $attempts + 1, HOUR_IN_SECONDS);
    }

    /**
     * Activate the license key.
     *
     * @since 1.0.0
     * @return bool Whether activation was successful
     */
    public function activate_license(): bool {
        if (!$this->verify_request('edd_activate_license')) {
            return false;
        }

        if ($this->should_rate_limit()) {
            add_settings_error(
                'edd_license',
                'edd_license_error',
                esc_html__('Too many activation attempts. Please try again in an hour.', 'nextwp'),
                'error'
            );
            return false;
        }

        $this->track_attempt();

        $license_key = $this->sanitize_license_key($_POST['edd_license_key']);
        if (empty($license_key)) {
            add_settings_error(
                'edd_license',
                'edd_license_error',
                esc_html__('Invalid license key format.', 'nextwp'),
                'error'
            );
            return false;
        }

        $response = $this->call_license_api('activate_license', $license_key);
        if (is_wp_error($response)) {
            add_settings_error(
                'edd_license',
                'edd_license_error',
                esc_html($response->get_error_message()),
                'error'
            );
            return false;
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));
        if (empty($license_data) || !$license_data->success) {
            $error = $license_data->error ?? 'unknown_error';
            add_settings_error(
                'edd_license',
                'edd_license_error',
                $this->get_license_error_message($error),
                'error'
            );
            return false;
        }

        update_option('edd_license_key', $license_key);
        update_option('edd_license_status', $license_data->license);
        add_settings_error(
            'edd_license',
            'edd_license_success',
            esc_html__('License activated successfully.', 'nextwp'),
            'updated'
        );

        return true;
    }

    /**
     * Deactivate the license key.
     *
     * @since 1.0.0
     * @return bool Whether deactivation was successful
     */
    public function deactivate_license(): bool {
        if (!$this->verify_request('edd_deactivate_license')) {
            return false;
        }

        $license_key = get_option('edd_license_key');
        if (empty($license_key)) {
            add_settings_error(
                'edd_license',
                'edd_license_error',
                esc_html__('No active license found.', 'nextwp'),
                'error'
            );
            return false;
        }

        $response = $this->call_license_api('deactivate_license', $license_key);
        if (is_wp_error($response)) {
            add_settings_error(
                'edd_license',
                'edd_license_error',
                esc_html($response->get_error_message()),
                'error'
            );
            return false;
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));
        if (empty($license_data) || !$license_data->success) {
            $error = $license_data->error ?? 'unknown_error';
            add_settings_error(
                'edd_license',
                'edd_license_error',
                $this->get_license_error_message($error),
                'error'
            );
            return false;
        }

        delete_option('edd_license_key');
        delete_option('edd_license_status');
        add_settings_error(
            'edd_license',
            'edd_license_success',
            esc_html__('License deactivated successfully.', 'nextwp'),
            'updated'
        );

        return true;
    }

    /**
     * Call the EDD license API.
     *
     * @param string $action The API action to perform
     * @param string $license_key The license key
     * @return array|WP_Error The API response or error
     */
    private function call_license_api(string $action, string $license_key) {
        return wp_remote_post($this->store_url, [
            'timeout' => 15,
            'sslverify' => true,
            'body' => [
                'edd_action' => $action,
                'license' => $license_key,
                'item_id' => $this->item_id,
                'url' => esc_url(home_url('/'))
            ]
        ]);
    }

    /**
     * Verify the license management request is valid.
     *
     * @param string $action The action being performed
     * @return bool Whether the request is valid
     */
    private function verify_request(string $action): bool {
        if (!isset($_POST[$action])) {
            return false;
        }

        if (!current_user_can('manage_options')) {
            add_settings_error(
                'edd_license',
                'edd_license_error',
                esc_html__('You do not have permission to manage licenses.', 'nextwp'),
                'error'
            );
            return false;
        }

        if (!check_admin_referer('edd_license_nonce', 'edd_license_nonce_field')) {
            add_settings_error(
                'edd_license',
                'edd_license_error',
                esc_html__('Security check failed. Please try again.', 'nextwp'),
                'error'
            );
            return false;
        }

        return true;
    }

    /**
     * Sanitize and validate a license key.
     *
     * @param string $key The license key to sanitize
     * @return string The sanitized key or empty string if invalid
     */
    private function sanitize_license_key(string $key): string {
        $key = trim(sanitize_text_field($key));
        return preg_match('/^[a-zA-Z0-9]{32}$/', $key) ? $key : '';
    }

    /**
     * Get a user-friendly error message for a license error.
     *
     * @param string $error_code The error code
     * @return string The error message
     */
    private function get_license_error_message(string $error_code): string {
        $messages = [
            'missing' => __('Invalid license key.', 'nextwp'),
            'no_activations_left' => __('No activations left for this license.', 'nextwp'),
            'expired' => __('Your license has expired.', 'nextwp'),
            'revoked' => __('This license has been revoked.', 'nextwp'),
            'disabled' => __('This license has been disabled.', 'nextwp'),
            'invalid' => __('Invalid license key.', 'nextwp'),
            'site_inactive' => __('License is not active for this site.', 'nextwp'),
            'item_name_mismatch' => __('This license is not valid for this product.', 'nextwp'),
            'default' => __('An error occurred while processing your license.', 'nextwp')
        ];

        return esc_html($messages[$error_code] ?? $messages['default']);
    }

    /**
     * Render the license settings page.
     *
     * @since 1.0.0
     */
    public static function render_settings_page(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'nextwp'));
        }

        settings_errors('edd_license');

        $license_key = get_option('edd_license_key');
        $license_status = get_option('edd_license_status');
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('License Settings', 'nextwp'); ?></h1>
            <form method="post" action="">
                <?php wp_nonce_field('edd_license_nonce', 'edd_license_nonce_field'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="edd_license_key"><?php esc_html_e('License Key', 'nextwp'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="edd_license_key" id="edd_license_key" 
                                   value="<?php echo esc_attr($license_key); ?>" class="regular-text">
                        </td>
                    </tr>
                    <?php if ($license_status === 'valid') : ?>
                        <tr>
                            <td colspan="2">
                                <p class="description"><?php esc_html_e('Your license is active.', 'nextwp'); ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>

                <?php if ($license_status !== 'valid') : ?>
                    <p>
                        <input type="submit" name="edd_activate_license" class="button-primary" 
                               value="<?php esc_attr_e('Activate License', 'nextwp'); ?>">
                    </p>
                <?php else : ?>
                    <p>
                        <input type="submit" name="edd_deactivate_license" class="button-secondary" 
                               value="<?php esc_attr_e('Deactivate License', 'nextwp'); ?>">
                    </p>
                <?php endif; ?>
            </form>
        </div>
        <?php
    }

    /**
     * Show admin notice if license is not active.
     *
     * @since 1.0.0
     */
    public function show_license_notification(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        $license_status = get_option('edd_license_status');
        if ($license_status !== 'valid') {
            $message = esc_html__('Your license is not activated. Please activate your license to ensure full functionality.', 'nextwp');
            printf(
                '<div class="notice notice-warning is-dismissible"><p><strong>%s</strong> %s</p></div>',
                esc_html__('License Notice:', 'nextwp'),
                esc_html($message)
            );
        }
    }
}

// Initialize the license manager.
new NextWP_License_Manager();
