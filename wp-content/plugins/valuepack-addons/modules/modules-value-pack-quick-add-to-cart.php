<?php
/**
 * Quick Add to Cart Module
 * 
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Add control to Button and Icon Box widgets
 */
if (!function_exists('value_pack_add_quick_add_to_cart_controls')) {
    function value_pack_add_quick_add_to_cart_controls($element, $args)
    {
        // Get current editing post ID
        $post_id = get_the_ID();

        // Make sure we're in Elementor editor
        if (function_exists('cubewp_is_elementor_editing') && cubewp_is_elementor_editing()) {

            $template_type = get_post_meta($post_id, 'template_type', true);

            // Only run for postcard templates
            if (get_post_type($post_id) === 'cubewp-tb' && $template_type === 'postcard') {

                $element->start_controls_section(
                    'vp_quick_add_to_cart_section',
                    [
                        'label' => __('Quick Add to Cart - Value Pack', 'valuepack-addons'),
                        'tab'   => Controls_Manager::TAB_CONTENT,
                    ]
                );

                $element->add_control(
                    'enable_quick_add_to_cart',
                    [
                        'label'        => __('Enable Quick Add to Cart', 'valuepack-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Yes', 'valuepack-addons'),
                        'label_off'    => __('No', 'valuepack-addons'),
                        'return_value' => 'yes',
                        'default'      => '',
                    ]
                );

                $element->add_control(
                    'quick_add_product_id',
                    [
                        'label'       => __('Product ID', 'valuepack-addons'),
                        'type'        => Controls_Manager::NUMBER,
                        'description' => __('Leave empty to use current product ID (on single product pages).', 'valuepack-addons'),
                        'condition'   => [
                            'enable_quick_add_to_cart' => 'yes',
                        ],
                    ]
                );

                $element->add_control(
                    'hover_visibility',
                    [
                        'label' => esc_html__('Hover Visibility', 'valuepack-addons'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'none',
                        'options' => [
                            'none' => esc_html__('Always Visible', 'valuepack-addons'),
                            'show_on_hover' => esc_html__('Show on Post Card Hover', 'valuepack-addons'),
                            'hide_on_hover' => esc_html__('Hide on Post Card Hover', 'valuepack-addons'),
                        ],
                        'condition'   => [
                            'enable_quick_add_to_cart' => 'yes',
                        ],
                    ]
                );

                $element->end_controls_section();
            }
        }
    }

    // Register controls for specific widgets
    add_action('elementor/element/button/section_button/after_section_end', 'value_pack_add_quick_add_to_cart_controls', 10, 2);
    add_action('elementor/element/icon/section_icon/after_section_end', 'value_pack_add_quick_add_to_cart_controls', 10, 2);
}
/**
 * Inject attributes dynamically before render
 */
if (!function_exists('value_pack_add_quick_add_to_cart_render_attributes')) {
	function value_pack_add_quick_add_to_cart_render_attributes($widget) {
		$settings = $widget->get_settings_for_display();

		if (empty($settings['enable_quick_add_to_cart']) || 'yes' !== $settings['enable_quick_add_to_cart']) {
			return;
		}

		$product_id = ! empty($settings['quick_add_product_id'])
			? absint($settings['quick_add_product_id'])
			: (function_exists('value_pack_get_tag_post_id') ? value_pack_get_tag_post_id() : get_the_ID());

		// Initialize class before using
		$class = '';

		$behavior = isset($settings['hover_visibility']) ? $settings['hover_visibility'] : 'none';

		if ($behavior === 'show_on_hover') {
			$class = 'vp-card-hover-show';
		} elseif ($behavior === 'hide_on_hover') {
			$class = 'vp-card-hover-hide';
		}

		$widget->add_render_attribute(
			'_wrapper',
			[
				'class'          => trim('women-wc-quick-checkout ' . $class),
				'data-productid' => esc_attr($product_id),
			]
		);
	}

	add_action('elementor/frontend/widget/before_render', 'value_pack_add_quick_add_to_cart_render_attributes', 10);
	add_action('elementor/frontend/container/before_render', 'value_pack_add_quick_add_to_cart_render_attributes', 10);
}

if (! function_exists('value_pack_get_tag_post_id')) {
    function value_pack_get_tag_post_id()
    {
        $post_id = get_the_ID();

        if (function_exists('cubewp_is_elementor_editing') && cubewp_is_elementor_editing()) {
            $template_type = get_post_meta($post_id, 'template_type', true);

            if (get_post_type($post_id) === 'cubewp-tb' && $template_type === 'postcard') {
                $preview_post_id = get_post_meta($post_id, 'preview_post_id', true);
                if ($preview_post_id) {
                    return (int) $preview_post_id;
                }

                $template_location   = get_post_meta($post_id, 'template_location', true);
                $associated_posttype = $template_location ? str_replace('postcard_', '', $template_location) : '';

                $args = [
                    'post_type'      => $associated_posttype,
                    'posts_per_page' => 1,
                    'fields'         => 'ids',
                    'post_status'    => 'publish',
                ];

                $products = get_posts($args);
                if (! empty($products)) {
                    return (int) $products[0];
                }
            }
        }

        return $post_id;
    }
}
