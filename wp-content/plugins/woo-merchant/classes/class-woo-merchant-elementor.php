<?php
defined('ABSPATH') || exit;

use Elementor\Widgets_Manager;

/**
 * Woo Merchant load Elementor Widgets.
 *
 * @class Woo_Merchant_Elementor
 */
final class Woo_Merchant_Elementor
{
	/**
	 * Instance
	 *
	 * @since  1.0.0
	 * @access private
	 * @static
	 * @var Woo_Merchant_Elementor The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct()
	{
		add_action('elementor/init', array($this, 'init_elementor_widgets'));
	}


	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return   Woo_Merchant_Elementor
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function init()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initialize
	 *
	 * Load the addons functionality only after Elementor is initialized.
	 *
	 * Fired by `elementor/init` action hook.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init_elementor_widgets()
	{
		add_action('elementor/elements/categories_registered', array($this, 'elementor_widget_category'));
		add_action('elementor/widgets/register', array($this, 'register_widgets'));
		spl_autoload_register(array($this, 'require_widgets_files'));
	}

	/**
	 * Register Elementor widget category.
	 *
	 * @since 1.0.0
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function elementor_widget_category($elements_manager)
	{

		$elements_manager->add_category(
			'woo_merchant',
			[
				'title' => esc_html__('Woo Merchant', 'woo-merchant'),
				'icon' => '',
			]
		);
	}

	/**
	 * Register Elementor widgets.
	 *
	 * @since 1.0.0
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets($widgets_manager)
	{

		$classes = array();
		
		if (class_exists('WooCommerce')) {
			$woocommerce_dependent_classes = array(
				'Size_Guide_Widget',
				'Frequently_Bought_Together_Widget', 
				'Sale_End_Countdown_Widget',
				'Pre_order_Notice_Widget'
			);
			
			foreach ($woocommerce_dependent_classes as $class) {
				$classes[] = sanitize_text_field($class);
			}
		}
		$classes = apply_filters("woo/merchant/widgets/classes", $classes);
		if (!empty($classes) && is_array($classes)) {
			foreach ($classes as $class) {
				
				$class = 'Woo_Merchant_' . sanitize_text_field($class);
				if (class_exists($class)) {
					
					$widgets_manager->register(new $class());
				}
			}
		}
	}

	/**
	 * Autoload widget class files.
	 *
	 * @since 1.0.0
	 * @param string $className Class name to load.
	 * @return string|null Class name if loaded, null otherwise.
	 */
	private static function require_widgets_files($className)
	{
		if (!is_string($className) || false === strpos($className, 'Woo_Merchant')) {
			return null;
		}

		$file_name = sanitize_file_name(str_replace('_', '-', strtolower($className)));
		$file_path = trailingslashit(WOO_MERCHANT_PLUGIN_DIR) . 'classes/elementor-widgets/class-' . $file_name . '.php';
		
		$files = apply_filters(
			"woo/merchant/widgets/files", 
			array($file_path),
			$file_name
		);

		if (!empty($files) && is_array($files)) {
			foreach ($files as $file) {
				$file = realpath($file);
				if ($file && file_exists($file) && is_readable($file) && 
					strpos($file, WOO_MERCHANT_PLUGIN_DIR) === 0) {
					require_once $file;
				}
			}
		}

		return $className;
	}
}
