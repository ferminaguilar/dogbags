<?php

/**
 * User Registration Form Handler
 *
 * Handles user registration forms and submission processing.
 *
 * @package valuepack-addons/cube/classes
 * @version 1.0.0
 */

 // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

defined('ABSPATH') || exit;

/**
 * User registration form and processing class.
 */
class Value_Pack_User_Registration
{

    private $user_default_fields = array();
    private $user_custom_fields = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_shortcode('cwpRegisterForm', array($this, 'value_pack_render_registration_form'));
        add_shortcode('vpRegisterForm', array($this, 'value_pack_render_registration_form'));
        add_action('wp_ajax_nopriv_vp_submit_user_register', array($this, 'handle_registration_submission'));
    }

    /**
     * Initialize the class.
     */
    public static function init()
    {
        new self();
    }

    /**
     * Render the registration form shortcode.
     *
     * @param array $params Shortcode parameters.
     * @param string $content Shortcode content.
     * @return string HTML output of the registration form.
     */
    public function value_pack_render_registration_form($params = array(), $content = null)
    {
        // Check if user is already logged in
        if (is_user_logged_in()) {
            return cwp_alert_ui(esc_html__('You are already logged in.', 'valuepack-addons'), 'info');
        }

        // Check if registration is allowed
        if (!get_option('users_can_register')) {
            return cwp_alert_ui(esc_html__('User registration is currently disabled.', 'valuepack-addons'), 'warning');
        }

        // Parse shortcode attributes
        $params = shortcode_atts(array(
            'role' => 'subscriber',
        ), $params);

        // Enqueue required assets
        $this->enqueue_registration_assets();

        // Get form configuration
        $cwp_user_forms = CWP()->get_form('user_register');
        $this->user_custom_fields = CWP()->get_custom_fields('user');
        $this->user_default_fields = value_pack_user_default_fields();

        $form_fields = $cwp_user_forms[$params['role']] ?? array();

        // Build the form
        return $this->build_registration_form($form_fields, $params['role']);
    }

    /**
     * Enqueue registration form assets.
     */
    protected function enqueue_registration_assets()
    {
        if (class_exists('CubeWp_Enqueue')) {
            CubeWp_Enqueue::enqueue_style('select2');
            CubeWp_Enqueue::enqueue_script('select2');
            CubeWp_Enqueue::enqueue_style('frontend-fields');
            CubeWp_Enqueue::enqueue_script('cwp-frontend-fields');
            CubeWp_Enqueue::enqueue_script('cwp-form-validation');
            CubeWp_Enqueue::enqueue_script('cwp-user-register');
            CubeWp_Enqueue::enqueue_style('cwp-login-register');
            CubeWp_Enqueue::enqueue_style('valuepack-user-register');
            CubeWp_Enqueue::enqueue_script('valuepack-user-register');
        }
    }

    /**
     * Build the registration form HTML.
     *
     * @param array $form_fields Form configuration.
     * @param string $role User role.
     * @return string Form HTML.
     */
    protected function build_registration_form($form_fields, $role)
    {
        // Extract form settings with defaults
        //$form_settings = $form_fields['form'] ?? array();
        $form_container_class = esc_attr($form_settings['form_container_class'] ?? '');
        $form_class = esc_attr($form_settings['form_class'] ?? 'cwp-user-register cwp-from-' . $role);
        $form_id = esc_attr($form_settings['form_id'] ?? 'cwp-from-' . $role);
        $submit_button_title = esc_attr($form_settings['submit_button_title'] ?? __('Register', 'valuepack-addons'));
        $submit_button_class = esc_attr($form_settings['submit_button_class'] ?? 'submit-btn');
        $recaptcha = $form_settings['form_recaptcha'] ?? 'disabled';

        // Start building output
        $output = '<div class="cwp-frontend-form-container ' . $form_container_class . '">';
        $output .= '<form method="post" id="' . $form_id . '" class="' . $form_class . '" enctype="multipart/form-data">';
        $output .= '<input type="hidden" name="cwp_user_register[default_fields][role]" value="' . esc_attr($role) . '">';
        $output .= '<div class="tab-content">';

        // Add form sections
        $section_data = array();
        $output .= $this->render_form_section($section_data);

        $output .= '</div>';
        $output .= CubeWp_Frontend_Recaptcha::cubewp_captcha_form_attributes($recaptcha);
        $output .= '<input type="submit" class="' . $submit_button_class . '" value= "' . $submit_button_title . '" >';
        $output .= '</form>';
        $output .= '</div>';

        return apply_filters("cubewp/user/registration/form", $output, $form_fields);
    }

    /**
     * Render a form section.
     *
     * @param array $section_data Section configuration.
     * @return string Section HTML.
     */
    protected function render_form_section($section_data)
    {
        $section_data = wp_parse_args($section_data, array(
            'section_id' => '',
            'section_title' => '',
            'section_description' => '',
            'section_class' => '',
            'fields' => '',
        ));

        $section_id = $section_data['section_id'] ? ' id="' . esc_attr($section_data['section_id']) . '"' : '';
        $section_class = 'cwp-frontend-section-container ' . esc_attr($section_data['section_class']);

        $output = '<div' . $section_id . ' class="' . $section_class . '">';

        if ($section_data['section_title']) {
            $output .= '<h3 class="cwp-section-title">' . esc_html($section_data['section_title']) . '</h3>';
        }

        if ($section_data['section_description']) {
            $output .= '<div class="cwp-section-description">' . wp_kses_post($section_data['section_description']) . '</div>';
        }

        $output .= '<div class="cwp-frontend-section-content-container">';
        $output .= $this->render_fields($this->user_default_fields);
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Render form fields.
     *
     * @param array $fields Fields configuration.
     * @return string Fields HTML.
     */
    protected function render_fields($fields)
    {
        $output = '';

        foreach ($fields as $field_name => $field_options) {
            $field_options = wp_parse_args($field_options, array(
                'type' => 'text',
                'name' => $field_name,
                'id' => $field_name,
                'required' => ($field_name === 'user_pass') ? '1' : '0',
                'custom_name' => 'cwp_user_register[default_fields][' . $field_name . ']'
            ));

            $output .= apply_filters(
                "cubewp/user/registration/{$field_options['type']}/field",
                '',
                $field_options
            );
        }

        return $output;
    }

    /**
     * Handle registration form submission.
     */
    public function handle_registration_submission()
    {
        // Verify nonce
        $security_nonce = isset($_POST['security_nonce']) ? sanitize_text_field( wp_unslash( $_POST['security_nonce'] ) ) : '';
        if (!wp_verify_nonce($security_nonce, 'vp_user_register_nonce')) {
            wp_send_json_error(esc_html__('Security verification failed.', 'valuepack-addons'));
        }

        // Verify reCAPTCHA if enabled
        if (isset($_POST['g-recaptcha-response'])) {
            CubeWp_Frontend_Recaptcha::cubewp_captcha_verification(
                'cubewp_captcha_user_registration',
                sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) )
            );
        }

        // Sanitize input data
        $default_fields = CubeWp_Sanitize_Text_Array($_POST['cwp_user_register']['default_fields'] ?? array()); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $custom_fields = CubeWp_Sanitize_Fields_Array($_POST['cwp_user_register']['custom_fields'] ?? array(), 'user'); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        // Validate required fields
        $validation_errors = $this->validate_registration_data($default_fields);
        if (!empty($validation_errors)) {
            wp_send_json_error($validation_errors);
        }

        $submitted_role = isset($default_fields['role']) ? sanitize_text_field($default_fields['role']) : '';
        
        $restricted_roles = ['administrator','editor'];
        if(in_array($submitted_role, $restricted_roles)){
            $default_fields['role'] = get_option('default_role');
        }

        // Process registration
        $user_id = $this->register_new_user($default_fields);
 

        if (is_wp_error($user_id)) {
            wp_send_json_error($user_id->get_error_message());
        }

        // Handle post-registration actions
        $this->after_registration_actions($user_id, $default_fields);

        // Send success response
        wp_send_json(array(
            'type' => 'success',
            'msg' => $default_fields['user_pass']
                ? esc_html__('Registration successful. Redirecting...', 'valuepack-addons')
                : esc_html__('Registration successful. Check your email for login details.', 'valuepack-addons'),
            'redirectURL' => apply_filters('cubewp/after/user/registration/redirect-url', home_url())
        ));
    }

    /**
     * Validate registration data.
     *
     * @param array $data Registration data.
     * @return array Validation errors.
     */
    protected function validate_registration_data($data)
    {
        $errors = array();

        // Validate username
        if (empty($data['user_login'])) {
            $errors[] = esc_html__('Username is required.', 'valuepack-addons');
        } elseif (username_exists($data['user_login'])) {
            $errors[] = esc_html__('Username already exists.', 'valuepack-addons');
        }

        // Validate email
        if (empty($data['user_email'])) {
            $errors[] = esc_html__('Email is required.', 'valuepack-addons');
        } elseif (!is_email($data['user_email'])) {
            $errors[] = esc_html__('Invalid email address.', 'valuepack-addons');
        } elseif (email_exists($data['user_email'])) {
            $errors[] = esc_html__('Email already exists.', 'valuepack-addons');
        }

        // Validate password if provided
        if (isset($data['user_pass']) && isset($data['confirm_pass'])) {
            if ($data['user_pass'] !== $data['confirm_pass']) {
                $errors[] = esc_html__('Passwords do not match.', 'valuepack-addons');
            } elseif (strlen($data['user_pass']) < 8) {
                $errors[] = esc_html__('Password must be at least 8 characters.', 'valuepack-addons');
            }
        }

        return $errors;
    }

    /**
     * Register a new user.
     *
     * @param array $user_data User data.
     * @return int|WP_Error User ID or error.
     */
    protected function register_new_user($user_data)
    {
        // Generate password if not provided
        if (empty($user_data['user_pass'])) {
            $user_data['user_pass'] = wp_generate_password(12, false);
        }

        // Prepare user data
        $user_data = apply_filters('cubewp/before/user/registration', $user_data);
        $user_id = wp_insert_user($user_data);

        return $user_id;
    }

    /**
     * Handle actions after successful registration.
     *
     * @param int $user_id New user ID.
     * @param array $user_data User data.
     */
    protected function after_registration_actions($user_id, $user_data)
    {
        // Auto-login if password was provided
        if (!empty($user_data['user_pass'])) {
            wp_set_auth_cookie($user_id);
            wp_set_current_user($user_id);
        }

        // Trigger after registration hook
        do_action('cubewp/after/user/registration', $user_id);
    }
}
