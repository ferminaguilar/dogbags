<?php

/**
 * Enqueues scripts and styles for the Woo Merchant plugin.
 *
 * Handles both frontend and admin script/style registration and localization.
 *
 * @package Woo_Merchant
 * @subpackage Enqueue
 * @version 1.0.1
 */

defined('ABSPATH') || exit;

/**
 * Class Woo_Merchant_Enqueue
 */
class Woo_Merchant_Enqueue
{

	/**
	 * Constructor.
	 *
	 * Hooks the enqueue methods into WordPress actions.
	 */
	public function __construct()
	{
		add_action('wp_enqueue_scripts', array(__CLASS__, 'load_frontend_assets'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'load_admin_assets'));
	}

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public static function init()
	{
		new self();
	}

	/**
	 * Load frontend assets.
	 *
	 * @return void
	 */
	public static function load_frontend_assets()
	{
		self::register_frontend_scripts();
		self::register_frontend_styles();
	}

	/**
	 * Register and enqueue frontend scripts.
	 *
	 * @return void
	 */
	private static function register_frontend_scripts()
	{
		$scripts = apply_filters('woo/merchant/frontend/script/register', array(
			'woo-merchant-frontend-scripts' => array(
				'src'     => WOO_MERCHANT_PLUGIN_URL . 'assets/frontend/woo-merchant-frontend-scripts.js',
				'deps'    => array('jquery'),
				'version' => WOO_MERCHANT_PLUGIN_VERSION,
			),
		));

		foreach ($scripts as $handle => $script) {
			if (! wp_script_is($handle, 'registered')) {
				wp_register_script(
					$handle,
					esc_url_raw($script['src']),
					$script['deps'] ?? array(),
					$script['version'] ?? WOO_MERCHANT_PLUGIN_VERSION,
					true
				);
			}
			wp_enqueue_script($handle);
		}

		// Localize frontend data
		wp_localize_script(
			'woo-merchant-frontend-scripts',
			'wooMerchantParams',
			array(
				'ajax_url'   => esc_url_raw(admin_url('admin-ajax.php')),
				'nonce'      => wp_create_nonce('woo_merchant_nonce'),
				'success'    => esc_html__('Products added to cart successfully!', 'woo-merchant'),
				'addToCart'  => esc_html__('Add to cart', 'woo-merchant'),
				'preOrder'   => esc_html__('Pre-Order Now', 'woo-merchant'),
			)
		);
	}

	/**
	 * Register and enqueue frontend styles.
	 *
	 * @return void
	 */
	private static function register_frontend_styles()
	{
		$styles = apply_filters('woo/merchant/frontend/style/register', array(
			'woo-merchant-frontend-styles' => array(
				'src'     => WOO_MERCHANT_PLUGIN_URL . 'assets/frontend/woo-merchant-frontend-styles.css',
				'deps'    => array(),
				'version' => WOO_MERCHANT_PLUGIN_VERSION,
				'media'   => 'all',
				'has_rtl' => false,
			),
		));

		// Conditionally add WooCommerce-specific styles
		if (class_exists('WooCommerce')) {
			$styles['woo-merchant-woocommerce'] = array(
				'src'     => WOO_MERCHANT_PLUGIN_URL . 'assets/frontend/woo-merchant-woocommerce.css',
				'deps'    => array(),
				'version' => WOO_MERCHANT_PLUGIN_VERSION,
				'media'   => 'all',
			);
		}

		foreach ($styles as $handle => $style) {
			if (! wp_style_is($handle, 'registered')) {
				wp_register_style(
					$handle,
					esc_url_raw($style['src']),
					$style['deps'] ?? array(),
					$style['version'] ?? WOO_MERCHANT_PLUGIN_VERSION,
					$style['media'] ?? 'all'
				);
			}
			wp_enqueue_style($handle);
		}
	}

	/**
	 * Load admin assets.
	 *
	 * @return void
	 */
	public static function load_admin_assets()
	{
		self::register_admin_scripts();
		self::register_admin_styles();
	}

	/**
	 * Register and enqueue admin scripts.
	 *
	 * @return void
	 */
	private static function register_admin_scripts()
	{
		$scripts = apply_filters('woo/merchant/admin/script/register', array(
			'woo-merchant-admin-scripts' => array(
				'src'     => WOO_MERCHANT_PLUGIN_URL . 'assets/admin/woo-merchant-admin-scripts.js',
				'deps'    => array('jquery'),
				'version' => WOO_MERCHANT_PLUGIN_VERSION,
			),
		));

		foreach ($scripts as $handle => $script) {
			if (! wp_script_is($handle, 'registered')) {
				wp_register_script(
					$handle,
					esc_url_raw($script['src']),
					$script['deps'] ?? array(),
					$script['version'] ?? WOO_MERCHANT_PLUGIN_VERSION,
					true
				);
			}
			wp_enqueue_script($handle);
		}

		// Localize admin script
		wp_localize_script('woo-merchant-admin-scripts', 'admin_ajax_params', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('woo_merchant_admin_nonce'),
		));
	}

	/**
	 * Register and enqueue admin styles.
	 *
	 * Only enqueue on Woo Merchant admin pages.
	 *
	 * @return void
	 */
	private static function register_admin_styles()
	{
		// Only load styles on Woo Merchant admin menu pages
		$screen = get_current_screen();
		if (empty($screen) || $screen->id !== 'toplevel_page_WM-woocommerce-features') {
			return;
		}

		$styles = apply_filters('woo/merchant/admin/style/register', array(
			'woo-merchant-admin-styles' => array(
				'src'     => WOO_MERCHANT_PLUGIN_URL . 'assets/admin/woo-merchant-admin-styles.css',
				'deps'    => array(),
				'version' => WOO_MERCHANT_PLUGIN_VERSION,
				'media'   => 'all',
			),
		));

		foreach ($styles as $handle => $style) {
			if (! wp_style_is($handle, 'registered')) {
				wp_register_style(
					$handle,
					esc_url_raw($style['src']),
					$style['deps'] ?? array(),
					$style['version'] ?? WOO_MERCHANT_PLUGIN_VERSION,
					$style['media'] ?? 'all'
				);
			}
			wp_enqueue_style($handle);
		}
	}
}
