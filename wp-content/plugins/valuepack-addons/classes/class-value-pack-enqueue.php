<?php

/**
 * Value Pack Enqueue - Handles script and style loading
 *
 * @version 1.0
 * @package value-pack/classes
 */

if (! defined('ABSPATH')) {
	exit;
}

class Value_Pack_Enqueue
{
	/**
	 * Hook in methods.
	 */
	public function __construct()
	{
		add_action('admin_enqueue_scripts', array(__CLASS__, 'load_admin_scripts'));

		add_action('wp_enqueue_scripts', array(__CLASS__, 'load_frontend_scripts'));
		add_action('elementor/editor/before_enqueue_styles', array($this, 'vp_editor_enqueue_styles'));
		add_action('elementor/editor/after_enqueue_scripts', array($this, 'vp_editor_enqueue_script'));
	}

	/**
	 * Initialize the class
	 */
	public static function init()
	{
		$CubeClass = __CLASS__;
		new $CubeClass;
	}

	/**
	 * Register/queue admin scripts.
	 */
	public static function load_admin_scripts()
	{
		self::register_admin_scripts();
	}

	/**
	 * Register/queue frontend scripts and styles.
	 */
	public static function load_frontend_scripts()
	{
		self::register_frontend_scripts();
		self::register_frontend_styles();
	}

	/**
	 * Register admin scripts.
	 */
	private static function register_admin_scripts()
	{
		global $post;
		$register_admin_scripts = array(
			'value-pack-admin-scripts' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/admin/js/value-pack-admin-scripts.js',
				'version' => VALUE_PACK_VERSION,
			),
		);

		$register_admin_scripts = apply_filters('valuepack-addons/admin/script/register', $register_admin_scripts);
		foreach ($register_admin_scripts as $name => $props) {
			$deps = isset($props['deps']) ? (array) $props['deps'] : array();
			wp_register_script($name, $props['src'], $deps, $props['version'], true);
		}

		wp_enqueue_script('value-pack-admin-scripts');
	}

	/**
	 * Register all frontend JavaScript files.
	 */
	private static function register_frontend_scripts()
	{
		global $cwpOptions;
		$register_scripts = array(
			'value-pack-photoswipe' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/photoswipe/photoswipe.min.js',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-component-viewer' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/component-viewer/component-viewer.min.js',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-photoswipe-lightbox' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/photoswipe/photoswipe-lightbox.esm.js',
				'deps'    => array('value-pack-photoswipe'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-slick' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/slick/slick.js',
				'deps'    => array('jquery'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-slick-animation' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/slick-animation.min.js',
				'deps'    => array('jquery', 'value-pack-slick'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-gsap' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/gsap/gsap-min.js',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-locomotive' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/gsap/locomotive.js',
				'deps'    => array('value-pack-gsap'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-scrolltrigger' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/gsap/ScrollTrigger.js',
				'deps'    => array('value-pack-gsap'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-countdown' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/value-pack-countdown.js',
				'deps'    => array('jquery', 'elementor-frontend'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-frontend-scripts' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/value-pack-core.js',
				'deps'    => array(
					'jquery',
					'elementor-frontend',
					'value-pack-gsap',
					'value-pack-scrolltrigger',
					'value-pack-locomotive',
					'value-pack-slick',
					'value-pack-slick-animation',
					'value-pack-photoswipe',
					'value-pack-component-viewer',
					'value-pack-photoswipe-lightbox'
				),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-bootstrap-scripts' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/bootstrap/js/bootstrap.bundle.min.js',
				'deps'    => array('jquery'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true,
			),
			'valuepack-user-login' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/valuepack-user-login.js',
				'deps'    => array('jquery'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'valuepack-user-register' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/valuepack-user-register.js',
				'deps'    => array('jquery'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'value-pack-popup-js' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/value-pack-popup.js',
				'deps'    => array('jquery', 'elementor-frontend'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			),
			'valuepack-header-script' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/valuepack-header.js',
				'deps'    => array('jquery'),
				'version' => VALUE_PACK_VERSION,
				'footer'  => true
			), 
		);

		$register_scripts = apply_filters('valuepack-addons/frontend/script/register', $register_scripts);
		$default_enqueue_scripts = apply_filters(
			'valuepack-addons/frontend/script/default',
			array('value-pack-frontend-scripts', 'value-pack-bootstrap-scripts', 'value-pack-countdown')
		);
		$excluded_scripts = array('valuepack-user-login', 'valuepack-user-register');
		foreach ($register_scripts as $name => $props) {
			$deps   = isset($props['deps']) ? (array) $props['deps'] : array();
			$footer = isset($props['footer']) ? (bool) $props['footer'] : true;

			wp_register_script($name, $props['src'], $deps, $props['version'], $footer);
			if (!in_array($name, $excluded_scripts, true) && in_array($name, $default_enqueue_scripts, true)) {
				wp_enqueue_script($name);
			}
		}

		//cubewp dependent scripts
		if (class_exists('CubeWp_Load')) {
			$cubewp_scripts = array(
				'value-pack-map' => array(
					'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/value-pack-map.js',
					'deps'    => array('jquery', 'elementor-frontend'),
					'version' => VALUE_PACK_VERSION,
					'footer'  => true
				),
			);

			foreach ($cubewp_scripts as $name => $props) {
				$deps   = isset($props['deps']) ? (array) $props['deps'] : array();
				$footer = isset($props['footer']) ? (bool) $props['footer'] : true;
				wp_register_script($name, $props['src'], $deps, $props['version'], $footer);
			}
			
			// Get map settings - use CubeWP's function if available, otherwise use fallback
			$map_settings = array();
			if (function_exists('_get_map_settings')) {
				$map_settings = _get_map_settings();
			} else {
				// Fallback map settings
				global $cwpOptions;
				if ($cwpOptions) {
					if (isset($cwpOptions['map_option']) && !empty($cwpOptions['map_option'])) {
						$map_settings['map_option'] = $cwpOptions['map_option'];
					}
					if (isset($cwpOptions['map_zoom']) && !empty($cwpOptions['map_zoom'])) {
						$map_settings['map_zoom'] = $cwpOptions['map_zoom'];
					}
					if (isset($cwpOptions['map_latitude']) && !empty($cwpOptions['map_latitude'])) {
						$map_settings['map_latitude'] = $cwpOptions['map_latitude'];
					}
					if (isset($cwpOptions['map_longitude']) && !empty($cwpOptions['map_longitude'])) {
						$map_settings['map_longitude'] = $cwpOptions['map_longitude'];
					}
					if ($cwpOptions['map_option'] == 'mapbox' && isset($cwpOptions['mapbox_token']) && !empty($cwpOptions['mapbox_token'])) {
						$map_settings['mapbox_token'] = $cwpOptions['mapbox_token'];
					}
					if ($cwpOptions['map_option'] == 'mapbox' && isset($cwpOptions['map_style']) && !empty($cwpOptions['map_style'])) {
						$map_settings['map_style'] = $cwpOptions['map_style'];
					}
				}
				// Default values if nothing is set
				if (empty($map_settings)) {
					$map_settings = array(
						'map_option' => 'openstreet',
						'map_zoom' => 12,
						'map_latitude' => 51.5072,
						'map_longitude' => -0.128
					);
				}
			}
			wp_enqueue_script('value-pack-map');
			wp_localize_script('value-pack-map', 'value_pack_map_params', $map_settings);
		}

		wp_localize_script('value-pack-frontend-scripts', 'value_pack_ajax_params', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('value_pack_ajax')
		));

		wp_localize_script('valuepack-user-login', 'cwp_user_login_params', array(
			'ajax_url'  => esc_url(admin_url('admin-ajax.php')),
			'admin_url' => esc_url(admin_url()),
			'error_msg' => esc_html__('Something Went Wrong. Try Again Later.', 'valuepack-addons'),
			'nonce'    => wp_create_nonce('vp_user_login_nonce'),
		));

		wp_localize_script('valuepack-user-register', 'cwp_user_register_params', array(
			'ajax_url'      => esc_url(admin_url('admin-ajax.php')),
			'admin_url'     => esc_url(admin_url()),
			'security_nonce' => wp_create_nonce('vp_user_register_nonce'),
			'password_strength_error' => esc_html__('Please make your password stronger.', 'valuepack-addons'),
		));

		 

		// Use CubeWP's submit form script and params if available
		// CubeWP's script handles form submission via cubewp_submit_post_form action
		if (function_exists('CubeWp_Enqueue') && method_exists('CubeWp_Enqueue', 'enqueue_script')) {
			// Try to enqueue CubeWP's submit post script
			CubeWp_Enqueue::enqueue_script('cwp-submit-post');
		} elseif (wp_script_is('cwp-submit-post', 'registered')) {
			wp_enqueue_script('cwp-submit-post');
		}

		wp_dequeue_style('photoswipe');
		wp_dequeue_style('photoswipe-default-skin-css');
		wp_dequeue_script('photoswipe-ui-default');
	}

	/**
	 * Register all frontend CSS styles.
	 */
	private static function register_frontend_styles()
	{
		$register_styles = array(
			'value-pack-styles' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/css/value-pack-core.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-search-styles' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/css/value-pack-search.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-login-styles' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/css/value-pack-login.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-mini-cart-styles' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/css/value-pack-mini-cart.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-newsletter-styles' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/css/value-pack-newsletter.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-animate' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/css/value-pack-animate.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-photoswipe-css' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/photoswipe/photoswipe.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-component-viewer-css' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/component-viewer/component-viewer.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-slick' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/slick/slick.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-fa' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/fontawesome/css/fontawesome.min.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-fa-solid' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/fontawesome/css/solid.min.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-fa-regular' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/fontawesome/css/regular.min.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-fa-brands' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/fontawesome/css/brands.min.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-bootstrap-styles' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/bootstrap/css/bootstrap.min.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			'value-pack-locomotive-scroll' => array(
				'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/gsap/locomotive-scroll.css',
				'deps'    => array(),
				'version' => VALUE_PACK_VERSION,
				'has_rtl' => false,
			),
			 
		);

		$register_styles = apply_filters('valuepack-addons/frontend/style/register', $register_styles);
		foreach ($register_styles as $name => $props) {
			$deps  = isset($props['deps']) ? (array) $props['deps'] : array();
			$media = isset($props['media']) ? $props['media'] : 'all';

			wp_register_style($name, $props['src'], $deps, $props['version'], $media);

			if (!empty($props['has_rtl'])) {
				wp_style_add_data($name, 'rtl', 'replace');
			}
		}

		wp_enqueue_style('value-pack-styles');
		wp_enqueue_style('value-pack-animate');
		wp_enqueue_style('value-pack-photoswipe-css');
		wp_enqueue_style('value-pack-component-viewer-css');
		wp_enqueue_style('value-pack-fa');
		wp_enqueue_style('value-pack-fa-solid');
		wp_enqueue_style('value-pack-fa-regular');
		wp_enqueue_style('value-pack-fa-brands');
		wp_enqueue_style('value-pack-bootstrap-styles');
		wp_enqueue_style('value-pack-locomotive-scroll');
		wp_enqueue_style('value-pack-search-styles');
		wp_enqueue_style('value-pack-mini-cart-styles');
		wp_enqueue_style('value-pack-slick');
		wp_enqueue_style('value-pack-login-styles');

		add_action('wp_enqueue_scripts', function () {
			global $wp_scripts;
			foreach ($wp_scripts->queue as $script) {
				if (strpos($wp_scripts->registered[$script]->src, 'bootstrap') !== false) {
					if ($script != 'value-pack-bootstrap-scripts') {
						wp_dequeue_script($script);
						wp_deregister_script($script);
					}
				}
			}
		}, 100);

		//cubewp dependent styles
		if (class_exists('CubeWp_Load')) {
			$cubewp_styles = array(
				'value-pack-leaflet-css'        => array(
					'src'     => VALUE_PACK_PLUGIN_URL . 'assets/frontend/lib/leaflet/leaflet.css',
					'deps'    => array(),
					'version' => VALUE_PACK_VERSION,
					'has_rtl' => false,
				),
			);

			
			foreach ($cubewp_styles as $name => $props) {
				$deps  = isset($props['deps']) ? (array) $props['deps'] : array();
				$media = isset($props['media']) ? $props['media'] : 'all';
				wp_register_style($name, $props['src'], $deps, $props['version'], $media);
			}
		}
	}

	/**
	 * Enqueue styles for Elementor editor.
	 *
	 * @since 1.0.0
	 */
	public function vp_editor_enqueue_styles()
	{
		wp_enqueue_style(
			'vp-elementor-editor-styles',
			VALUE_PACK_PLUGIN_URL . 'assets/frontend/css/vp-elementor-editor.css',
			null,
			VALUE_PACK_VERSION
		);
	}

	/**
	 * Enqueue Script for Elementor editor.
	 *
	 * @since 1.0.0
	 */
	public function vp_editor_enqueue_script()
	{
		wp_enqueue_script(
			'vp-elementor-editor-script',
			VALUE_PACK_PLUGIN_URL . 'assets/frontend/js/vp-elementor-editor.js',
			['jquery', 'elementor-editor'],
			VALUE_PACK_VERSION,
			true
		);
		wp_localize_script('vp-elementor-editor-script', 'vp_product_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('vp_product_search_nonce'),
			'plugin_url' => VALUE_PACK_PLUGIN_URL,
			'message'  => esc_html__('Elementor editor script loaded successfully', 'valuepack-addons'),
		]);

		// Localize script for CubeWP fields AJAX
		wp_localize_script('vp-elementor-editor-script', 'vp_cubewp_fields_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('valuepack_elementor_nonce'),
		]);
	}
}
