<?php

defined('ABSPATH') || exit;

/**
 * CubeWp_Woocommerce_Products
 */
class CubeWp_Woocommerce_Enqueue
{
	/**
	 * Initialize product hooks and filters
	 */
	public function __construct()
	{
		add_action('admin_enqueue_scripts', array(__CLASS__, 'load_admin_script'));
		add_action('wp_enqueue_scripts', array(__CLASS__, 'load_frontend_scripts'));
	}

	public static function load_frontend_scripts()
	{
		global $post;
		self::register_frontend_scripts();
		self::register_frontend_styles();
	}


	/**
	 * Initialize the class
	 */

	public static function load_admin_script()
	{
		self::register_admin_script();
	}



	private static function register_frontend_scripts()
	{
		global $cwpOptions;
		$register_scripts = array(
			'cwp-woo-script' => array(
				'src'     => CUBEWP_WOOCOMMERCE_PLUGIN_URL . 'assets/frontend/js/script.js',
				'deps'    => array('jquery'),
				'version' => CUBEWP_WOOCOMMERCE_VERSION,
				'footer'  => true
			),

		);

		$register_scripts = apply_filters('cubewp/woo/frontend/script/register', $register_scripts);
		foreach ($register_scripts as $name => $props) {
			$deps   = isset($props['deps']) ? $props['deps'] : array();
			$footer = isset($props['footer']) ? (bool) $props['footer'] : true;
			wp_register_script($name, $props['src'], $deps, $props['version'], $footer);
			wp_enqueue_script($name);
		}
	}


	private static function register_frontend_styles()
	{
		$register_styles = array(
			'cubewp-woo-styles' => array(
				'src'     => CUBEWP_WOOCOMMERCE_PLUGIN_URL . 'assets/frontend/css/style.css',
				'deps'    => array(),
				'version' => CUBEWP_WOOCOMMERCE_VERSION,
				'has_rtl' => false,
			),
		);

		$register_styles = apply_filters('cubewp/woo/frontend/style/register', $register_styles);
		foreach ($register_styles as $name => $props) {
			wp_register_style($name, $props['src'], $props['deps'], $props['version'], 'all', $props['has_rtl']);
		}

		wp_enqueue_style('cubewp-woo-styles');
	}

	private static function register_admin_script()
	{
		global $post;
		$register_admin_scripts = array(
			'cubewp-woo-admin-scripts' => array(
				'src'     => CUBEWP_WOOCOMMERCE_PLUGIN_URL . 'assets/admin/js/admin-script.js',
				'version' => CUBEWP_WOOCOMMERCE_VERSION,
				'footer'  => true,
			),
		);

		$register_admin_scripts = apply_filters('value/pack/admin/script/register', $register_admin_scripts);
		foreach ($register_admin_scripts as $name => $props) {
			wp_register_script($name, $props['src'], '', $props['version']);
		}

		wp_enqueue_script('cubewp-woo-admin-scripts');


		// Register admin stylesheet
		$register_admin_styles = array(
			'cubewp-woo-admin-style' => array(
				'src'     => CUBEWP_WOOCOMMERCE_PLUGIN_URL . 'assets/admin/css/admin-styles.css',
				'version' => CUBEWP_WOOCOMMERCE_VERSION,
			),
		);
		$register_admin_styles  = apply_filters('cwp-woo/admin/style/register', $register_admin_styles);
		foreach ($register_admin_styles as $name => $props) {
			wp_register_style($name, $props['src'], array(), $props['version']);
		}
		wp_enqueue_style('cubewp-woo-admin-style');
	}

	public static function init()
	{
		$CubeClass = __CLASS__;
		new $CubeClass;
	}
}
