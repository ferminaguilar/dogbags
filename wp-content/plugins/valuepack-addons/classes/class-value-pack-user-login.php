<?php

/**
 * User Login and Password Reset Handler
 *
 * Handles user login forms, password reset functionality, and related AJAX calls.
 *
 * @package valuepack-addons/cube/classes
 * @version 1.0.0
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

defined('ABSPATH') || exit;

/**
 * User authentication handler class.
 */
class Value_Pack_User_Login
{

	/**
	 * Constructor.
	 * 
	 * Sets up shortcodes and AJAX handlers.
	 */
	public function __construct()
	{
		add_shortcode('cwpLoginForm', array($this, 'value_pack_render_login_form'));
		add_shortcode('vpLoginForm', array($this, 'value_pack_render_login_form'));
		add_shortcode('cwpResetPasswordForm', array($this, 'value_pack_render_reset_password_form'));

		add_action('wp_ajax_nopriv_vp_ajax_login', array($this, 'value_pack_handle_ajax_login'));
		add_action('wp_ajax_nopriv_cubewp_ajax_forget_password', array($this, 'value_pack_handle_ajax_forget_password'));
		add_action('wp_ajax_nopriv_cubewp_reset_password', array($this, 'value_pack_handle_ajax_reset_password'));
	}

	/**
	 * Initialize the class.
	 */
	public static function init()
	{
		new self();
	}

	/**
	 * Render the login form shortcode.
	 *
	 * @param array $params Shortcode parameters.
	 * @param string $content Shortcode content.
	 * @return string HTML output of the login form.
	 */
	public function value_pack_render_login_form($params = array(), $content = '')
	{
		if (is_user_logged_in()) {
			return $this->get_alert_ui(esc_html__('You are already logged in.', 'valuepack-addons'), 'info');
		}

		$params = shortcode_atts(
			array(
				'class' => 'cwp-user-login',
			),
			$params,
			'vpLoginForm'
		);

		// Enqueue required assets
		$this->enqueue_login_assets();

		$output = '<div class="cwp-frontend-form-container ' . esc_attr($params['class']) . '">';
		$output .= $this->build_login_form();
		$output .= $this->build_forget_password_form();
		$output .= '</div>';

		return $output;
	}

	/**
	 * Build the login form HTML.
	 *
	 * @return string Login form HTML.
	 */
	protected function build_login_form()
	{
		$output = '<div class="cwp-frontend-section-container">';
		$output .= '<form id="vp-login-form" method="post">';
		$output .= '<div class="cwp-frontend-section-heading-container">';
		$output .= '<h2>' . esc_html__('Login', 'valuepack-addons') . '</h2>';
		$output .= '</div>';
		$output .= '<div class="cwp-frontend-section-content-container">';

		$login_fields = cubewp_user_login_fields();
		foreach ($login_fields as $login_field) {
			$output .= apply_filters("cubewp/user/profile/{$login_field['type']}/field", '', $login_field);
		}

		$output .= $this->get_password_reset_link();
		$output .= wp_nonce_field('vp-login-nonce', 'security', true, false);
		$output .= '<input type="submit" value="' . esc_html__('Login', 'valuepack-addons') . '">';
		$output .= '</div>';
		$output .= '</form>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Build the forget password form HTML.
	 *
	 * @return string Forget password form HTML.
	 */
	protected function build_forget_password_form()
	{
		global $cwpOptions;
		$cwpOptions = empty($cwpOptions) ? get_option('cwpOptions') : $cwpOptions; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$password_reset_page = $cwpOptions['password_reset_page'] ?? '';

		if (empty($password_reset_page)) {
			return '';
		}

		$output = '<form id="vp-forget-password-form" method="post" style="display: none;">';
		$output .= '<div class="cwp-frontend-section-container">';
		$output .= '<div class="cwp-frontend-section-heading-container">';
		$output .= '<h2>' . esc_html__('Reset Password', 'valuepack-addons') . '</h2>';
		$output .= '</div>';
		$output .= '<div class="cwp-frontend-section-content-container">';
		$forget_fields = cubewp_forget_password_fields();
		foreach ($forget_fields as $forget_field) {
			$output .= apply_filters("cubewp/user/profile/{$forget_field['type']}/field", '', $forget_field);
		}
		$output .= '</div>';
		$output .= '<p class="cwp-field-container">' . esc_html__('Go Back To', 'valuepack-addons') . ' <a href="javascript:void(0);" class="vp-login-form-trigger">' . esc_html__('Login', 'valuepack-addons') . '</a></p>';
		$output .= wp_nonce_field('vp-forget-password-nonce', 'security', true, false);
		$output .= '<input type="submit" value="' . esc_html__('Reset Password', 'valuepack-addons') . '">';
		$output .= '</div>';
		$output .= '</form>';

		return $output;
	}

	/**
	 * Get password reset link HTML.
	 *
	 * @return string Password reset link HTML.
	 */
	protected function get_password_reset_link()
	{
		global $cwpOptions;
		$cwpOptions = empty($cwpOptions) ? get_option('cwpOptions') : $cwpOptions; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$password_reset_page = $cwpOptions['password_reset_page'] ?? '';

		if (!empty($password_reset_page)) {
			return '<p class="cwp-field-container">' .
				esc_html__('Forget Password?', 'valuepack-addons') .
				' <a href="javascript:void(0);" class="vp-forget-password-form-trigger">' .
				esc_html__('Reset', 'valuepack-addons') .
				'</a></p>';
		} else {
			$reset_link = esc_url(wp_lostpassword_url());
			return '<p class="cwp-field-container">' .
				esc_html__('Forget Password?', 'valuepack-addons') .
				' <a href="' . $reset_link . '">' .
				esc_html__('Reset', 'valuepack-addons') .
				'</a></p>';
		}
	}

	/**
	 * Render the reset password form shortcode.
	 */
	public function value_pack_render_reset_password_form()
	{
		if (is_user_logged_in()) {
			echo wp_kses_post($this->get_alert_ui(esc_html__('You are already logged in.', 'valuepack-addons'), 'info'));
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Viewing reset form via secure email link; validated with check_password_reset_key below.
		$action = sanitize_text_field( wp_unslash( $_GET['action'] ?? '' ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Viewing reset form via secure email link; validated with check_password_reset_key below.
		$key = sanitize_text_field( wp_unslash( $_GET['key'] ?? '' ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Viewing reset form via secure email link; validated with check_password_reset_key below.
		$login = sanitize_text_field( wp_unslash( $_GET['login'] ?? '' ) );

		// Validate reset password parameters
		if ($action !== 'cubewp-reset-password' || empty($key) || empty($login)) {
			echo wp_kses_post($this->get_alert_ui(esc_html__('Invalid reset password link.', 'valuepack-addons'), 'error'));
			return;
		}

		// Validate the reset key
		$user = check_password_reset_key($key, $login);
		if (is_wp_error($user)) {
			echo wp_kses_post($this->get_alert_ui(
				esc_html__('The token for the password reset link has expired. Please request another password reset link.', 'valuepack-addons'),
				'error'
			));
			return;
		}

		// Enqueue required assets
		$this->enqueue_login_assets();

		// Build the form
		$output = '<div class="cwp-frontend-form-container cubewp-reset-password-form">';
		$output .= '<div class="cwp-frontend-section-container">';
		$output .= '<form id="reset-password-form" method="post">';
		$output .= '<div class="cwp-frontend-section-heading-container">';
		$output .= '<h2>' . esc_html__('Reset Password', 'valuepack-addons') . '</h2>';
		$output .= '</div>';
		$output .= '<div class="cwp-frontend-section-content-container">';
		$reset_password_fields = $this->get_reset_password_fields();
		foreach ($reset_password_fields as $field) {
			$output .= apply_filters("cubewp/user/profile/{$field['type']}/field", '', $field);
		}

		$output .= '<input type="hidden" name="reset-password-key" value="' . esc_attr($key) . '">';
		$output .= '<input type="hidden" name="reset-password-login" value="' . esc_attr($login) . '">';
		$output .= wp_nonce_field('cubewp-reset-password-nonce', 'security', true, false);
		$output .= '<button type="submit" class="cwp-submit-button">' . esc_html__('Reset Password', 'valuepack-addons') . '</button>';
		$output .= '</div>';
		$output .= '</form>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Get reset-password fields with safe fallback.
	 *
	 * @return array
	 */
	protected function get_reset_password_fields()
	{
		if (function_exists('value_pack_reset_password_fields')) {
			return value_pack_reset_password_fields();
		}

		return array(
			array(
				'label'          => esc_html__('Enter New Password', 'valuepack-addons'),
				'name'           => 'user_pass',
				'type'           => 'password',
				'required'       => 1,
				'validation_msg' => esc_html__('Please Enter New Password', 'valuepack-addons'),
			),
			array(
				'label'          => esc_html__('Confirm Password', 'valuepack-addons'),
				'name'           => 'confirm_pass',
				'type'           => 'password',
				'required'       => 1,
				'validation_msg' => esc_html__('Please Enter New Password', 'valuepack-addons'),
			),
		);
	}

	/**
	 * Enqueue login form assets.
	 */
	protected function enqueue_login_assets()
	{
		CubeWp_Enqueue::enqueue_style('frontend-fields');
		CubeWp_Enqueue::enqueue_style('cwp-login-register');
		CubeWp_Enqueue::enqueue_script('cwp-form-validation');
		CubeWp_Enqueue::enqueue_script('cwp-user-login');
		CubeWp_Enqueue::enqueue_script('valuepack-user-login');
	}

	/**
	 * Handle AJAX login request.
	 */
	public function value_pack_handle_ajax_login()
	{
		if(!isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'vp_user_login_nonce')) {
			wp_send_json_error(esc_html__('Invalid nonce.', 'valuepack-addons'));
		}

		$user_login = sanitize_text_field( wp_unslash( $_POST['user_login'] ?? '' ) );
		$user_pass = sanitize_text_field( wp_unslash( $_POST['user_pass'] ?? '' ) );

		// Validate input
		if (empty($user_login)) {
			wp_send_json_error(esc_html__('The username field is empty.', 'valuepack-addons'));
		}
		if (empty($user_pass)) {
			wp_send_json_error(esc_html__('The password field is empty.', 'valuepack-addons'));
		}

		// Attempt login
		$credentials = array(
			'user_login'    => $user_login,
			'user_password' => $user_pass,
			'remember'      => true
		);

		$user = wp_signon($credentials, is_ssl());

		if (is_wp_error($user)) {
			wp_send_json_error(esc_html__('Wrong username or password.', 'valuepack-addons'));
		}

		wp_send_json(
			array(
				'type'         => 'success',
				'msg'          => esc_html__('Login successful, redirecting...', 'valuepack-addons'),
				'redirectURL'  => apply_filters('cubewp/after/login/redirect-url', home_url()),
			)
		);
	}

	/**
	 * Handle AJAX forget password request.
	 */
	public function value_pack_handle_ajax_forget_password()
	{
		check_ajax_referer('vp-forget-password-nonce', 'security');

		$user_login = sanitize_text_field( wp_unslash( $_POST['user_login'] ?? '' ) );

		if (empty($user_login)) {
			wp_send_json_error(esc_html__('The username field is empty.', 'valuepack-addons'));
		}

		if (!username_exists($user_login) && !email_exists($user_login)) {
			wp_send_json_error(esc_html__("This username or email doesn't exist.", 'valuepack-addons'));
		}

		if (value_pack_send_password_reset_email($user_login)) {
			wp_send_json(array(
				'type'        => 'success',
				'msg'         => esc_html__('Password reset mail sent successfully. Please check your email and also look in spam.', 'valuepack-addons'),
			));
		} else {
			wp_send_json_error(esc_html__('Something went wrong, try again later.', 'valuepack-addons'));
		}
	}

	/**
	 * Handle AJAX reset password request.
	 */
	public function value_pack_handle_ajax_reset_password()
	{
		check_ajax_referer('cubewp-reset-password-nonce', 'security');

		$user_pass = sanitize_text_field( wp_unslash( $_POST['user_pass'] ?? '' ) );
		$confirm_pass = sanitize_text_field( wp_unslash( $_POST['confirm_pass'] ?? '' ) );
		$key = sanitize_text_field( wp_unslash( $_POST['reset-password-key'] ?? '' ) );
		$login = sanitize_text_field( wp_unslash( $_POST['reset-password-login'] ?? '' ) );

		// Validate passwords
		if (empty($user_pass) || empty($confirm_pass)) {
			wp_send_json_error(esc_html__('Please enter and confirm your new password.', 'valuepack-addons'));
		}

		if ($user_pass !== $confirm_pass) {
			wp_send_json_error(esc_html__('Password mismatch. Please double-check.', 'valuepack-addons'));
		}

		if (strlen($user_pass) < 8) {
			wp_send_json_error(esc_html__('Your password is too weak. The minimum length required is 8 characters.', 'valuepack-addons'));
		}

		// Validate reset key
		$user = check_password_reset_key($key, $login);
		if (is_wp_error($user)) {
			wp_send_json_error(esc_html__('The token for the password reset link has expired. Please request another password reset link.', 'valuepack-addons'));
		}

		// Reset password
		wp_set_password($user_pass, $user->ID);

		// Auto-login the user after password reset
		$credentials = array(
			'user_login'    => $user->user_login,
			'user_password' => $user_pass,
			'remember'      => true
		);
		wp_signon($credentials, is_ssl());

		wp_send_json(array(
			'type'        => 'success',
			'msg'         => esc_html__('Password reset successful. You are now logged in.', 'valuepack-addons'),
			'redirectURL' => home_url(),
		));
	}

	/**
	 * Get styled alert UI.
	 *
	 * @param string $message The message to display.
	 * @param string $type The type of alert (error, success, info, warning).
	 * @return string HTML for the alert.
	 */
	protected function get_alert_ui($message, $type = 'info')
	{

		return cwp_alert_ui($message, $type);
	}
}
