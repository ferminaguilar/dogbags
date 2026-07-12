<?php
/**
 * Custom CSS Module
 *
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager; 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Value_Pack_Custom_Css {

	private static $vp_instance = null;

	public function __construct() {
		// Add new controls to advanced tab globally
		add_action('elementor/element/after_section_end', [$this, 'vp_add_section_custom_css_controls'], 25, 3);

		// Render the Custom CSS
		add_action('elementor/element/parse_css', [$this, 'vp_add_post_css'], 10, 2);
	}

	public function vp_add_section_custom_css_controls($vp_widget, $vp_section_id, $vp_args) {
		if ( 'section_custom_css_pro' !== $vp_section_id ) {
			return;
		}

		// Translators: %s is replaced with the plugin name
		$vp_widget->start_controls_section(
			'vp_section_custom_css',
			[
				'label' => esc_html__('Custom CSS - Value Pack', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_ADVANCED
			]
		);

		$vp_widget->add_control(
			'vp_custom_css',
			[
				'type' => Controls_Manager::CODE,
				'label' => esc_html__('Custom CSS', 'valuepack-addons'),
				'language' => 'css',
				'render_type' => 'ui',
				'label_block' => true,
			]
		);

		$vp_widget->add_control(
			'vp_custom_css_description',
			[
				'raw' => esc_html__('Add your own custom CSS here. Use "selector" to target the element itself, e.g., selector { color: red; }', 'valuepack-addons'),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'separator' => 'none'
			]
		);

		$vp_widget->end_controls_section();
	}



	public function vp_add_post_css($vp_post_css, $vp_element) {
		$vp_element_settings = $vp_element->get_settings();

		if ( empty($vp_element_settings['vp_custom_css']) ) {
			return;
		}

		$vp_css = trim($vp_element_settings['vp_custom_css']);

		if ( empty($vp_css) ) {
			return;
		}

		$vp_css = str_replace('selector', $vp_post_css->get_element_unique_selector($vp_element), $vp_css);

		$vp_css = sprintf('/* Start custom CSS for %s, class: %s */', $vp_element->get_name(), $vp_element->get_unique_selector()) . $vp_css . '/* End custom CSS */';

		$vp_post_css->get_stylesheet()->add_raw_css($vp_css);
	}
} 

new Value_Pack_Custom_Css();
