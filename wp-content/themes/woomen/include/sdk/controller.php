<?php

use Elementor\Plugin;

class CWP_License_verification
{


	/**
	 * Redirect User to defined page after completion of setup.
	 *
	 * @var string
	 */
	public $setup_after_url;

	/**
	 * Root folder of the sdk.
	 *
	 * @var string
	 */
	public $root = __DIR__;

	/**
	 * Whether licensing process is enabled or not.
	 *
	 * @var bool
	 */
	public $licensing = true;

	/**
	 * id or class selector to append response of import process
	 *
	 * @var string
	 */
	public $selector = '.cwp-importer-success';

	/**
	 * URL Path for images and stles
	 *
	 * @var string
	 */
	public $PATH_URL;

	/**
	 * Class constructor, actions, filters and method allocation
	 *
	 */
	function __construct()
	{

		$this->load_resources();

		add_action('wp_ajax_cwp_license_verification', 'cwp_license_verification_callback');
		add_action('admin_menu', array($this, 'cwp_license_sdk_view'));
		add_action('admin_post_cwp_license_submit', 'cwp_license_form_response');
		add_action('wp_ajax_cwp_activate_license', 'cwp_activate_license_cb');
		add_action('wp_ajax_cwp_activate_required_addons', 'cwp_activate_required_addons_cb');
		add_filter('cubewp/import/content/path', array($this, 'cwp_path_for_import_cubewp_content'), 15);
		add_filter('cubewp/after/import/redirect', array($this, 'cwp_redirect_import_cubewp_content'), 15);
		add_filter('cubewp/after/import/success_message', array($this, 'cwp_import_cubewp_content_success'), 15);
		add_action('admin_init', array($this, 'cwp_redirect_after_theme_activation'));
		add_action('admin_init', array($this, 'cwp_woocommerce_no_redirect_on_activation'));
		if (function_exists('cwp_theme_required_plugins')) {
			$theme_required = cwp_theme_required_plugins();

			if (! empty($theme_required) && is_array($theme_required)) {
				add_action('admin_notices', array($this, 'cwp_admin_notice_required_plugins'));
			}
		}
		add_action('admin_init', function () {
			if (did_action('elementor/loaded')) {
				remove_action('admin_init', [
					Plugin::$instance->admin,
					'maybe_redirect_to_getting_started'
				]);
			}
		}, 1);

		add_action('admin_init', array($this, 'cwp_plugin_redirect_after_activation'));
	}

	public function cwp_plugin_redirect_after_activation()
	{
		if (get_option('woomen_plugin_do_activation_redirect')) {
			delete_option('woomen_plugin_do_activation_redirect');

			// Avoid redirect on multisite bulk activation
			if (!isset($_GET['activate-multi'])) {
				wp_safe_redirect(
					esc_url_raw(
						add_query_arg('page', 'cwp_license_manager', admin_url('admin.php'))
					)
				);
				exit;
			}
		}
	}

	/**
	 * Class method, including required files and calling script method
	 *
	 */
	public function load_resources()
    {
        $this->setup_after_url = home_url();
        $slash = '/';
        $include = $slash.'include';
        $this->PATH_URL        = WOOMEN_URL . $include;
        $this->cwp_license_scripts();
        locate_template('include/sdk/helpers.php', true, true);
        locate_template('include/sdk/manager.php', true, true);
        locate_template('include/sdk/views/cwp-view.php', true, true);
    }

	/**
	 * Class method, calling of another method based on respective screen
	 *
	 */
	public function cwp_license_scripts()
	{
		if (isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'cwp_license_manager') {
			add_action('admin_enqueue_scripts', array($this, 'cwp_admin_license_scripts'));
		}
	}

	/**
	 * Class method, define folder path for import files
	 */
	public function cwp_path_for_import_cubewp_content($path)
	{
		$style = '';
		$folder = '';
		if (isset($_COOKIE['demo_style'])) {
			$style = $_COOKIE['demo_style'];
			$demo = cwp_get_theme_demo_styles($style);
			$folder = $demo['folder'] ?? '';
		}

		return $this->root . '/import/' . $folder;
	}

	/**
	 * Class method, no redirection after import process success
	 *
	 */
	public function cwp_redirect_import_cubewp_content($path)
	{
		return null;
	}

	/**
	 * Class method, passing selector and message after import success.
	 *
	 */
	public function cwp_import_cubewp_content_success($path)
	{
		return array('selecter' => $this->selector, 'message' => 'Your Content has been been imported successfully!');
	}

	/**
	 * Class method, registration, localization and enqueing of scripts,styles.
	 *
	 */
	public function cwp_admin_license_scripts()
	{
		wp_enqueue_style('cwp_admin_license_style', WOOMEN_URL . '/include/sdk/assets/css/cwp_license.css'); //enqueue style
		wp_register_script('cwp_admin_license_script', WOOMEN_URL . '/include/sdk/assets/js/cwp_license.js'); //register script
		wp_localize_script('cwp_admin_license_script', 'cwp_admin_license_params', [
			'ajax_url'       => admin_url('admin-ajax.php'),
			'security_nonce' => wp_create_nonce("cubewp-admin-nonce")
		]); //localize script
		wp_enqueue_script('cwp_admin_license_script');
		wp_enqueue_script('cwp_vars');
	}

	/**
	 * Class method, add new theme page for license management screen
	 *
	 */
	public function cwp_license_sdk_view()
	{
		// Add main menu page
		add_menu_page(
			'Woomen',                          // Page title
			'Woomen',                          // Menu title
			'manage_options',                  // Capability
			'cwp_license_manager',             // Menu slug
			'cwp_license_manager_cb',          // Callback function
			'dashicons-art',           // Icon
			52                                 // Position
		);

		// Add Setup Wizard as default submenu (same slug as parent)
		add_submenu_page(
			'cwp_license_manager',             // Parent slug
			'Setup Wizard',                    // Page title
			'Setup Wizard',                    // Menu title
			'manage_options',                  // Capability
			'cwp_license_manager',             // Same slug makes it default
			'cwp_license_manager_cb'           // Callback function
		);
	}

	/**
	 * Class method, redirect to setup page after theme activation
	 *
	 */
	public function cwp_redirect_after_theme_activation()
	{
		global $pagenow;
		if ("themes.php" == $pagenow && is_admin() && isset($_GET['activated'])) {
			wp_redirect(esc_url_raw(add_query_arg('page', 'cwp_license_manager', admin_url('admin.php'))));
		}
	}

	/**
	 * Class method, stop redirection after woo commerce activation
	 *
	 */

	public function cwp_woocommerce_no_redirect_on_activation()
	{
		delete_transient('_wc_activation_redirect');
	}

	/**
	 * Class method, admin notice of required plugins
	 *
	 */
	public function cwp_admin_notice_required_plugins()
	{
		$theme_required  = cwp_theme_required_plugins();
		$counter         = 1;
		$first_occurance = true;
		$last_occurance  = false;
		foreach ($theme_required as $theme_req) {
			if (isset($theme_req['slug']) && isset($theme_req['base']) && isset($theme_req['name'])) {
				if ((isset($theme_req['class_exists']) && ! class_exists($theme_req['class_exists'])) && (isset($theme_req['required']) && $theme_req['required'] == 'yes')) {
					if ($first_occurance) {
?>
						<div class="notice notice-warning is-dismissible">
							<p>
								<strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">This theme requires the following plugins:
									<?php
									$first_occurance = false;
								}
								if ($counter != 1) {
									echo ' and ';
								}
									?>
									<em><a href="<?php if (empty($theme_req['source'])) {
														echo admin_url('plugin-install.php?tab=plugin-information&plugin=' . $theme_req['slug']);
													} else {
														echo 'https://zemowp.com/';
													} ?>" target="_blank"><?php echo esc_html($theme_req['name']); ?></a>
									</em>
									<?php
									$last_occurance = true;
								}
								$counter++;
							}

							if (isset($theme_req['min_version']) && ! empty($theme_req['min_version'])) {
								$plugin      = $theme_req['slug'] . '/' . $theme_req['base'] . '.php';
								$min_version = $theme_req['min_version'];
								$plugin_name = $theme_req['name'];
								$plugin_path = WP_PLUGIN_DIR . '/' . $plugin;
								if (isset($theme_req['class_exists']) && class_exists($theme_req['class_exists'])) {
									$plugin_data    = get_plugin_data($plugin_path);
									$plugin_version = $plugin_data['Version'];
									if (version_compare($plugin_version, $min_version, '<')) {
									?>
										<div class="notice notice-warning">
											<h3><?php esc_html_e('Woomen', 'woomen'); ?></h3>
											<p><?php echo sprintf(esc_html__('Please update your %s%s%s plugin to %s%s%s version or you may face issues in functionality.', 'woomen'), '<strong>', $plugin_name, '</strong>', '<strong>', $min_version, '</strong>') ?></p>
										</div>
							<?php
									}
								}
							}
						}
						if (($counter == count($theme_required) + 1) && $last_occurance) { ?>
									</span>
									<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><a
											href="<?php echo admin_url('themes.php?page=cwp_license_manager'); ?>">Begin Setup</a></span>
								</strong>
							</p>
						</div>
			<?php
						}
					}
				}

				$CWP_License_verification = new CWP_License_verification();
