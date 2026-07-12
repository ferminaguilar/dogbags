<?php

/**
 * CubeWP Woocommerce initializer.
 *
 * @package cubewp-addon-woocommerce/cube/classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * CubeWp_Woocommerce_Load
 */
class CubeWp_Woocommerce_Load
{

	/**
	 * The single instance of the class.
	 *
	 * @var CubeWp_Woocommerce_Load
	 */
	protected static $Load = null;

	/**
	 * CubeWp_Load Constructor.
	 */
	public function __construct()
	{

		if (! class_exists('WooCommerce')) {
			add_action('admin_notices', array($this, 'woocommerce_required_notice'));
		} else {

			self::includes();
			if (CWP()->is_request('admin')) {
				self::admin_includes();
			}
			if (CWP()->is_request('frontend')) {
				self::frontend_includes();
			}
		}
	}

	/**
	 * Display admin notice when WooCommerce is not active.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_required_notice()
	{
		$message = sprintf(
			wp_kses(
				/* translators: %s: WooCommerce download link */
				__('CubeWP WooCommerce requires WooCommerce to be installed and active. You can download <a href="%s" target="_blank">WooCommerce</a> here.', 'cubewp-woocommerce'),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			'https://woocommerce.com/'
		);

		printf(
			'<div class="notice notice-error"><p><strong>%s</strong></p></div>',
			$message
		);
	}

	/**
	 * Include required files for admin and frontend.
	 * @since  1.0.0
	 */
	public function includes()
	{

		$files = array(
			'include/helper.php'
		);
		foreach ($files as $file) {
			$file = CUBEWP_WOOCOMMERCE_PLUGIN_DIR . 'cube/' . $file;
			if (file_exists($file)) {
				require_once $file;
			}
		}

		add_action('init', array('CubeWp_Woocommerce_Enqueue', 'init'));
		add_action('init', array('CubeWp_Woocommerce_Products', 'init'), 9);
		add_action('init', array('CubeWp_Woocommerce_Coupons', 'init'), 9);
	}

	/**
	 * Include required admin files.
	 * @since  1.0.0
	 */
	public function admin_includes() {}

	/**
	 * Include required frontend files.
	 * @since  1.0.0
	 */
	public function frontend_includes() {}

	/**
	 * Main instance of CubeWp_Woocommerce_Load.
	 *
	 * @since 1.0.0
	 * @return CubeWp_Woocommerce_Load|false
	 */
	public static function instance()
	{
		if (! class_exists('WooCommerce')) {
			if (is_admin()) {
				add_action('admin_notices', array(new self(), 'woocommerce_required_notice'));
			}
			return false;
		}

		if (is_null(self::$Load)) {
			self::$Load = new self();
		}

		return self::$Load;
	}
}
