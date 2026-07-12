<?php

defined('ABSPATH') || exit;

if (!function_exists('cwp_theme_required_plugins')) {
	function cwp_theme_required_plugins()
	{
		return array(
			array(
				'name'         => esc_html__('Value Pack Addons', 'woomen'),
				'slug'         => 'valuepack-addons',
				'base'         => 'valuepack-addons',
				'required'     => 'yes',
				'cwp-source'   => 'yes',
				'class_exists' => 'Value_Pack_Load',
				'min_version'  => false
			),
			array(
				'name'         => esc_html__('Woo Merchant', 'woomen'),
				'slug'         => 'woo-merchant',
				'base'         => 'woo-merchant',
				'required'     => 'yes',
				'cwp-source'   => 'yes',
				'class_exists' => 'Woo_Merchant_Load',
				'min_version'  => false
			),
			array(
				'name'         => esc_html__('CubeWP Addon Woocommerce', 'woomen'),
				'slug'         => 'cubewp-addon-woocommerce',
				'base'         => 'cubewp-woocommerce',
				'required'     => 'yes',
				'source' 	   => 'https://cubewp.com/wp-content/uploads/cubewp-woocommerce/cubewp-addon-woocommerce.zip',
				'class_exists' => 'CubeWp_Woocommerce_Load',
				'min_version'  => false
			),
			array(
				'name'         => esc_html__('NextWP Sites', 'woomen'),
				'slug'         => 'nextwp-sites',
				'base'         => 'nextwp',
				'required'     => 'yes',
				'cwp-source'   => 'yes',
				'class_exists' => 'NextWP_Load',
				'min_version'  => false
			),
			array(
				'name'         => esc_html__('CubeWP Framework', 'woomen'),
				'slug'         => 'cubewp-framework',
				'base'         => 'cube',
				'required'     => 'yes',
				'class_exists' => 'CubeWp_Load',
				'min_version'  => false
			),
			array(
				'name'         => esc_html__('CubeWP Forms', 'woomen'),
				'slug'         => 'cubewp-forms',
				'base'         => 'cubewp-forms',
				'required'     => 'yes',
				'class_exists' => 'CubeWp_Forms_Custom',
				'min_version'  => false
			),
			array(
				'name'         => esc_html__('WooCommerce', 'woomen'),
				'slug'         => 'woocommerce',
				'base'         => 'woocommerce',
				'required'     => 'yes',
				'class_exists' => 'WooCommerce',
				'min_version'  => false
			),
			array(
				'name'         => esc_html__('Elementor', 'woomen'),
				'slug'         => 'elementor',
				'base'         => 'elementor',
				'required'     => 'yes',
				'class_exists' => 'Elementor\Plugin',
				'min_version'  => false
			),

		);
	}
}

add_action('after_setup_theme', function () {
	require WOOMEN_PATH . '/include/sdk/controller.php';
});

/**
 * Add custom body classes
 *
 * @since 1.0.0
 * @param array $classes Existing body classes
 * @return array Modified body classes
 */
if (! function_exists('woomen_body_class')) {
	function woomen_body_class($classes)
	{
		if (! in_array('home', $classes)) {
			global $woomen_post_types;
			if (! empty($woomen_post_types)) {
				foreach ($woomen_post_types as $post_type) {
					$page = woomen_get_setting('header_top_bar_landing_pages_' . $post_type);
					if (is_page($page)) {
						$classes[] = 'home';
					}
				}
			}
		}

		return $classes;
	}

	add_filter('body_class', 'woomen_body_class');
}



/**
 * Insert array elements after specific key
 *
 * @since 1.0.0
 * @param array $main_array Original array
 * @param array $to_add Elements to insert
 * @param string $add_after Key to insert after
 * @return array Modified array
 */
if (!function_exists('woomen_add_into_array_after_key')) {
	function woomen_add_into_array_after_key($main_array, $to_add, $add_after)
	{
		$index = array_search($add_after, array_keys($main_array)) + 1;

		return array_merge(
			array_slice($main_array, 0, $index, true),
			$to_add,
			array_slice($main_array, $index, null, true)
		);
	}
}

/**
 * Custom number field filter for search
 *
 * @since 1.0.0
 * @param string $output HTML output
 * @param array $args Field arguments
 * @return string Filtered HTML output
 * @hook cubewp/search_filters/number/field
 */
if (!function_exists('woomen_search_filters_number_field')) {
	function woomen_search_filters_number_field($output = '', $args = array())
	{
		$args['container_class'] = isset($args['container_class']) ? $args['container_class'] . ' woomen-number-field-slider' : 'woomen-number-field-slider';
		$args = apply_filters('cubewp/frontend/field/parametrs', $args);
		$minval = $maxval = '';
		if (isset($_GET['min-' . $args['name']])) {
			$minval = sanitize_text_field($_GET['min-' . $args['name']]);
		}
		if (isset($_GET['max-' . $args['name']])) {
			$maxval = sanitize_text_field($_GET['max-' . $args['name']]);
		}
		if (empty($minval)) {
			$minval = 0;
		}
		if (empty($maxval)) {
			$maxval = 100000;
		}
		$currency_symbol = '';
		if (function_exists('get_woocommerce_currency_symbol')) {
			$currency_symbol = get_woocommerce_currency_symbol();
		}
		$output  = CubeWp_Frontend::cwp_frontend_search_field_container($args);
		$output .= CubeWp_Frontend::cwp_frontend_search_field_label($args);
		$output .= '<div class="woomen-number-field-slider-inner">';
		$output .= '<div class="woomen-slider-range"></div>';
		$output .= '<div class="cwp-range-number-fields">' . "\n";
		$output .= '<div class="cwp-range-number-field">' . "\n";
		$output .= '<span class="woomen-currrency-symbol">' . $currency_symbol . '</span><input type="number" class="form-control woomen-min-number ' . $args['class'] . '" id="min-' . esc_attr($args['name']) . '" placeholder="' . esc_html__('Min', 'woomen') . '" step="1" min="0" max="100000" name="min-' . esc_attr($args['name']) . '" value="' . $minval . '">' . "\n";
		$output .= '</div>' . "\n";
		$output .= '<div class="cwp-range-number-field">' . "\n";
		$output .= '<span class="woomen-currrency-symbol">' . $currency_symbol . '</span><input type="number" class="form-control woomen-max-number ' . $args['class'] . '" id="max-' . esc_attr($args['name']) . '" placeholder="' . esc_html__('Max', 'woomen') . '" step="1" min="0" max="100000" name="max-' . esc_attr($args['name']) . '" value="' . $maxval . '">' . "\n";
		$output .= '<input type="hidden" name="' . esc_attr($args['name']) . '" value="0">' . "\n";
		$output .= '</div>' . "\n";
		$output .= '</div>' . "\n";
		$output .= '</div>';
		$output .= '</div>';
		$output = apply_filters("cubewp/search_filters/{$args['name']}/field", $output, $args);
		return $output;
	}
	add_filter('cubewp/search_filters/number/field',  'woomen_search_filters_number_field', 10, 2);
}




add_filter('cubewp/search_filters/checkbox/taxonomy/field', 'search_filter_checkbox', 11, 2);
function search_filter_checkbox($output = '', $args = array())
{
	$args    = apply_filters('cubewp/frontend/field/parametrs', $args);

	$options = $args['options'];
	//if ($args['name'] != 'pa_color') {
	$values         =  !empty($args['value']) ? explode(',', $args['value']) : $args['value'];
	if (!empty($options) && is_array($options)) {
		$name   = '';
		$output = CubeWp_Frontend::cwp_frontend_post_field_container($args);
		$args['custom_name']  =  !empty($args['name']) ? '_ST_' . $args['name'] : $args['name'];
		$output .= '<div class="cwp-search-field cwp-search-field-checkbox ' . $args['container_class'] . '">';
		$output .= CubeWp_Frontend::cwp_frontend_field_label($args);
		if (count($options) > 4 || ($args['name'] != 'pa_color' || $args['name'] != 'pa_color_image')) {
			$output .= '<div class="cwp-field-checkbox-container woomen-category-card-have-collapse">';
		} else {
			$output .= '<div class="cwp-field-checkbox-container ';
			if ($args['name'] == 'pa_color' || $args['name'] == 'pa_color_image') {
				$output .= 'woomen-color-checkbox-container';
			}
			$output .= '">';
		}
		$counter = 1;
		foreach ($options as $value => $label) {
			$output_class  = '';
			if ($counter > 4) {
				$output_class = 'd-none';
			}


			$output      .= '<ul class="woomen-term-container ' . $output_class . '">
                <li ' . $args['class'] . '>
                <div class="cwp-field-checkbox">';
			$input_attrs = array(
				'type'  => 'checkbox',
				'id'    => esc_attr($args['id'] . ' ' . $label['term_name']),
				'name'  => $name,
				'value' => $label['term_id'],
				'class' => 'custom-control-input ' . $args['class'],
			);
			if (isset($args['value']) && is_array($args['value']) && in_array($label['term_id'], $values)) {
				$input_attrs['extra_attrs'] = ' checked="checked"';
			} else if (isset($args['value']) && $args['value'] == $label['term_id']) {
				$input_attrs['extra_attrs'] = ' checked="checked"';
			}
			if (is_tax() && !is_search() && !is_page()) {
				$queried_object = get_queried_object();
				if (is_object($queried_object) && !empty($queried_object) && !is_wp_error($queried_object)) {
					$CurrentSlug = $queried_object->slug;
					if (isset($CurrentSlug) && $CurrentSlug == $label['term_id']) {
						$input_attrs['extra_attrs'] = ' checked="checked"';
						$currentVal = $CurrentSlug;
					}
				}
			}
			$output .= cwp_render_text_input($input_attrs);
			if ($args['name'] == 'pa_color' || $args['name'] == 'pa_color_image') {
				$color = get_term_meta($label['term_id'], 'woomen_attr_field', true);
				if (is_numeric($color)) {
					$color = wp_get_attachment_url($color);
					$output .= '<label class="colored-label color-image" for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '" ><span style="background-image:url(' . $color . ')"></span>' . esc_html($label['term_name']) . '</label>';
				} else {
					$output .= '<label class="colored-label" for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '" style="background-color:' . $color . '"><span style="background-color:' . $color . '"></span>' . esc_html($label['term_name']) . '</label>';
				}
			} else {
				$output .= '<label for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '">' . esc_html($label['term_name']) . '</label>';
			}
			$output .= '</div>
                </li>';
			if (!empty($label['childern']) && is_array($label['childern'])) {
				$icon_class = '';
				$ul_style   = '';
				if ($counter == 2) {
					$icon_class = 'expanded';
					$ul_style   = 'style="display: block;"';
				}
				$counter++;
				$output .= '
                    <i class="fa-solid fa-chevron-down woomen-expand-more-terms ' . $icon_class . '" aria-hidden="true"></i>
                    <ul ' . $ul_style . '>';
				foreach ($label['childern'] as $c_value => $c_label) {
					$output      .= '<li ' . $args['class'] . '>';
					$output      .= '<div class="cwp-field-checkbox">';
					$input_attrs = array(
						'type'  => 'checkbox',
						'id'    => esc_attr($args['id'] . ' ' . $c_label['term_name']),
						'name'  => $name,
						'value' => $c_label['term_id'],
						'class' => 'custom-control-input ' . $args['class'],
					);
					if (isset($args['value']) && is_array($args['value']) && in_array($c_label['term_id'], $args['value'])) {
						$input_attrs['extra_attrs'] = ' checked="checked"';
					} else if (isset($args['value']) && $args['value'] == $c_label['term_id']) {
						$input_attrs['extra_attrs'] = ' checked="checked"';
					}
					if (is_tax() && !is_search() && !is_page()) {
						$queried_object = get_queried_object();
						if (is_object($queried_object) && !empty($queried_object) && !is_wp_error($queried_object)) {
							$CurrentSlug = $queried_object->slug;
							if (isset($CurrentSlug) && $CurrentSlug == $label['term_id']) {
								$input_attrs['extra_attrs'] = ' checked="checked"';
								$currentVal = $CurrentSlug;
							}
						}
					}
					$output .= cwp_render_text_input($input_attrs);
					if ($args['name'] == 'pa_color' || $args['name'] == 'pa_color_image') {
						$color = get_term_meta($label['term_id'], 'woomen_attr_field', true);
						if (is_numeric($color)) {
							$color = wp_get_attachment_url($color);
							$output .= '<label class="colored-label color-image" for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '" ><span style="background-image:url(' . $color . ')"></span>' . esc_html($label['term_name']) . '</label>';
						} else {
							$output .= '<label class="colored-label" for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '" style="background-color:' . $color . '"><span style="background-color:' . $color . '"></span>' . esc_html($label['term_name']) . '</label>';
						}
					} else {
						$output .= '<label for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '">' . esc_html($label['term_name']) . '</label>';
					}
					$output .= '</div>';
					$output .= '</li>';
					if (!empty($c_label['childern']) && is_array($c_label['childern'])) {
						$output .= '<ul>';
						foreach ($c_label['childern'] as $cc_value => $cc_label) {
							$output      .= '<li ' . $args['class'] . '>';
							$output      .= '<div class="cwp-field-checkbox">';
							$input_attrs = array(
								'type'  => 'checkbox',
								'id'    => esc_attr($args['id'] . ' ' . $cc_label['term_name']),
								'name'  => $name,
								'value' => $cc_label['term_id'],
								'class' => 'custom-control-input ' . $args['class'],
							);
							if (isset($args['value']) && is_array($args['value']) && in_array($cc_label['term_id'], $args['value'])) {
								$input_attrs['extra_attrs'] = ' checked="checked"';
							} else if (isset($args['value']) && $args['value'] == $cc_label['term_id']) {
								$input_attrs['extra_attrs'] = ' checked="checked"';
							}
							if (is_tax() && !is_search() && !is_page()) {
								$queried_object = get_queried_object();
								if (is_object($queried_object) && !empty($queried_object) && !is_wp_error($queried_object)) {
									$CurrentSlug = $queried_object->slug;
									if (isset($CurrentSlug) && $CurrentSlug == $label['term_id']) {
										$input_attrs['extra_attrs'] = ' checked="checked"';
										$currentVal = $CurrentSlug;
									}
								}
							}
							$output .= cwp_render_text_input($input_attrs);
							if ($args['name'] == 'pa_color' || $args['name'] == 'pa_color_image') {
								$color = get_term_meta($label['term_id'], 'woomen_attr_field', true);
								if (is_numeric($color)) {
									$color = wp_get_attachment_url($color);
									$output .= '<label class="colored-label color-image" for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '" ><span style="background-image:url(' . $color . ')"></span>' . esc_html($label['term_name']) . '</label>';
								} else {
									$output .= '<label class="colored-label" for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '" style="background-color:' . $color . '"><span style="background-color:' . $color . '"></span>' . esc_html($label['term_name']) . '</label>';
								}
							} else {
								$output .= '<label for="' . esc_attr($args['id'] . ' ' . $label['term_name']) . '">' . esc_html($label['term_name']) . '</label>';
							}
							$output .= '</div>';
							$output .= '</li>';
						}
						$output .= '</ul>';
					}
				}
				$output .= '</ul>';
			}
			$counter++;
			$output .= '</ul>';
		}
		$currentVal = isset($currentVal) ? $currentVal : '';
		$input_attrs = array(
			'name'         => !empty($args['custom_name']) ? $args['custom_name'] : $args['name'],
			'class'        => !empty($currentVal) ? 'is_tax' : '',
			'value'        => !empty($currentVal) ? $currentVal : $args['value'],
			'extra_attrs'  => 'data-current-tax="' . $currentVal . '"',
		);
		$output      .= cwp_render_hidden_input($input_attrs);
		$output .= '</div>';
		if (count($options) > 4) {
			$output .= '<p class="woomen-see-more-category collapsed" data-more="' . esc_attr__("SHOW MORE", "woomen") . '"data-less="' . esc_attr__("SHOW LESS", "woomen") . '">';
			$output .= esc_html__("SHOW MORE", "woomen") . '</p>';
		}
		$output .= '</div>
            </div>';
	}
	//}
	return apply_filters("cubewp/frontend/{$args['name']}/field", $output, $args);
}
/**
 * Hide uncategorized product category
 *
 * @since 1.0.0
 * @param array $terms Array of term objects
 * @param string|array $taxonomy Taxonomy name(s)
 * @return array Filtered terms
 * @hook get_terms
 * @hook get_the_terms
 */
if (! function_exists('hide_uncategorized_category')) {
	function hide_uncategorized_category($terms, $taxonomy)
	{
		if (is_array($terms) && !empty($taxonomy) && in_array('product_cat', (array) $taxonomy)) {
			foreach ($terms as $key => $term) {
				if (is_object($term) && isset($term->slug) && $term->slug === 'uncategorized') {
					unset($terms[$key]);
				}
			}
		}
		return $terms;
	}
	add_filter('get_terms', 'hide_uncategorized_category', 10, 2);
	add_filter('get_the_terms', 'hide_uncategorized_category', 10, 2);
}

/**
 * Enqueue WooCommerce variation script
 *
 * @since 1.0.0
 * @hook wp_enqueue_scripts
 * @return void
 */
if (!function_exists('woomen_enqueue_variation_script')) {
	function woomen_enqueue_variation_script()
	{
		wp_enqueue_script('wc-add-to-cart-variation');
	}
	add_action('wp_enqueue_scripts', 'woomen_enqueue_variation_script');
}

if (!function_exists('woomen_enqueue_elementor_iframe_styles')) {
	function woomen_enqueue_elementor_iframe_styles()
	{
		if (isset($_GET['elementor-preview']) && $_GET['elementor-preview']) {
			$post_id = get_the_ID();
			$template_type = get_post_meta($post_id, 'template_type', true);
			if ($template_type === 'archive' || $template_type === 'shop') {
				wp_enqueue_style('woomen-styles-archive');
			}
		}
	}
	add_action('elementor/frontend/after_enqueue_styles', 'woomen_enqueue_elementor_iframe_styles');
}


if (!function_exists('woomen_enqueue_elementor_iframe_scripts')) {
	function woomen_enqueue_elementor_iframe_scripts()
	{
		if (isset($_GET['elementor-preview']) && $_GET['elementor-preview']) {
			$post_id = get_the_ID();
			$template_type = get_post_meta($post_id, 'template_type', true);
			if ($template_type === 'archive' || $template_type === 'shop') {
				wp_enqueue_script('woomen-scripts-archive');
			}
		}
	}
	add_action('elementor/frontend/after_enqueue_scripts', 'woomen_enqueue_elementor_iframe_scripts');
}

if (!function_exists('nextwp_after_import_action_callbak')) {
	/**
	 * Set the buy now button and set the checkout page to "Checkout Classic"
	 *
	 * @return void
	 */
	function nextwp_after_import_action_callbak()
	{
		$data_already_imported = get_option('woomen_import_completed_once');
		if (!$data_already_imported) {
			update_option('woomen_import_completed_once', true);
		}

		// Step 1: Update the buy_now_button key
		$options = get_option('wm_woocommerce_features_options', []);
		$options['buy_now_button'] = 1;
		$options['woo_merchant_checkout'] = 1;
		$options['woo_merchant_checkout_style'] = 'style-2';
		update_option('wm_woocommerce_features_options', $options);

		woomen_update_woo_variation_term_meta_once();

		// Step 2: Set the Checkout Page
		$checkout_page = get_posts([
			'post_type'      => 'page',
			'title'          => 'Checkout Classic',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		]);

		if (empty($checkout_page)) {
			$checkout_page = get_posts([
				'post_type'      => 'page',
				'title'          => 'Classic Checkout',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
			]);
		}

		if (! empty($checkout_page)) {
			update_option('woocommerce_checkout_page_id', $checkout_page[0]->ID);
		}

		// Step 3: Set the Cart Page (titled "Woomen Cart")
		$cart_page = get_posts([
			'post_type'      => 'page',
			'title'          => 'Woomen Cart',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		]);

		if (! empty($cart_page)) {
			update_option('woocommerce_cart_page_id', $cart_page[0]->ID);
		}

		// Step 4: Update theme Options
		$cwpOptions = get_option('cwpOptions');
		update_option('cwpOptions', $cwpOptions);
		if (!empty($cwpOptions)) {
			woomen_dynamic_css();
		}

		// Step 5: Clear Elementor cache
		if (class_exists('\Elementor\Plugin')) {
			$elementor = \Elementor\Plugin::instance();
			if (method_exists($elementor->files_manager, 'clear_cache')) {
				$elementor->files_manager->clear_cache();
			}
		}
	}
	add_action('nextwp_after_import_action', 'nextwp_after_import_action_callbak');
}

if (!function_exists('woomen_update_woo_variation_term_meta_once')) {
	/**
	 * Update WooCommerce variation term meta once
	 *
	 * @return void
	 */
	function woomen_update_woo_variation_term_meta_once()
	{

		if (get_option('woomen_variation_term_meta_updated')) {
			return; // Already done
		}
		// 1. Update attribute taxonomy meta
		update_wm_attr_display_type();

		// Mark as done
		update_option('woomen_variation_term_meta_updated', 1);
	}
}

if (!function_exists('update_wm_attr_display_type')) {
	/**
	 * Update WooCommerce attribute display type
	 *
	 * @param string $attribute_name Attribute name
	 * @param string $display_type Display type
	 * @return void
	 */
	function update_wm_attr_display_type()
	{
		global $wpdb;
		$attributes = wc_get_attribute_taxonomies();
		if (is_array($attributes) && !empty($attributes)) {
			foreach ($attributes as $attribute) {
				$attribute_name = $attribute->attribute_name;
				$attribute_slug = wc_attribute_taxonomy_slug($attribute_name);

				// Check and set 'color' for pa_color, 'label' for pa_size
				if ($attribute_name === 'color_image' && $attribute_slug === 'color_image') {
					$display_type = 'image';

					$wpdb->update(
						$wpdb->prefix . 'woocommerce_attribute_taxonomies',
						[
							'attribute_label' => 'color',
							'attribute_type' => $display_type,
						],
						['attribute_id' => absint($attribute->attribute_id)],
						['%s'],
						['%d']
					);
					continue;
				} elseif ($attribute_name === 'color') {
					$display_type = 'color';
				} elseif ($attribute_name === 'size') {
					$display_type = 'label';
				} else {
					continue;
				}

				// Update the attribute type in DB

				$wpdb->update(
					$wpdb->prefix . 'woocommerce_attribute_taxonomies',
					['attribute_type' => $display_type],
					['attribute_id' => absint($attribute->attribute_id)],
					['%s'],
					['%d']
				);
			}
		}
		// Clear attribute cache so WooCommerce reflects the update
		delete_transient('wc_attribute_taxonomies');
		wc_delete_product_transients();
	}
}

if (!function_exists('disable_elementor_fonts_temporarily')) {
	/**
	 * Disable Elementor fonts temporarily for 1 hour
	 *
	 * @return void
	 */
	function disable_elementor_fonts_temporarily()
	{
		$data_already_imported = get_option('woomen_import_completed_once');
		if ($data_already_imported) {
			// Check if start time is already recorded
			$start_time = get_option('wm_elementor_fonts_start_time');

			// If not set, set it now (first run)
			if (! $start_time) {
				$start_time = time();
				update_option('wm_elementor_fonts_start_time', $start_time);
			}

			// Check if 1 hour has passed since first run
			if ((time() - $start_time) > HOUR_IN_SECONDS) {
				return; // More than 1 hour has passed — do not run
			}

			// Within 1 hour — disable Elementor fonts
			add_filter('elementor/frontend/print_google_fonts', '__return_false');
		}
	}
	add_action('template_redirect', 'disable_elementor_fonts_temporarily');
}

/*---------- Addon Code ----------*/

if (!function_exists('woomen_file_force_contents')) {
	/**
	 * Force file contents with proper directory creation
	 *
	 * @param string $file_path File path
	 * @param string $file_content File content
	 * @param int $flags File permissions
	 * @return bool True on success, false on failure
	 */
	function woomen_file_force_contents($file_path, $file_content, $flags = 0644)
	{
		global $wp_filesystem;

		if (!function_exists('WP_Filesystem')) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if (!isset($wp_filesystem)) {
			WP_Filesystem();
		}

		$parts = explode('/', $file_path);
		array_pop($parts);
		$dir = implode('/', $parts);

		if (!is_dir($dir)) {
			wp_mkdir_p($dir);
		}

		return $wp_filesystem->put_contents(
			sanitize_text_field($file_path),
			$file_content,
			$flags
		);
	}
}

if (!function_exists('woomen_download_google_fonts')) {
	/**
	 * Download and cache Google Fonts locally
	 *
	 * @param array $families Font families to download
	 * @return bool True on success, false on failure
	 */
	function woomen_download_google_fonts($families)
	{
		// Initialize the Filesystem
		if (!function_exists('WP_Filesystem')) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		global $wp_filesystem;
		WP_Filesystem();

		if (!$wp_filesystem) {
			return false;
		}

		$upload_dir = wp_upload_dir();
		$font_dir = trailingslashit($upload_dir['basedir']) . 'woomen-google-fonts/';
		$font_url_base = trailingslashit($upload_dir['baseurl']) . 'woomen-google-fonts/';

		if (!file_exists($font_dir)) {
			wp_mkdir_p($font_dir);
		}

		$fonts_url = woomen_google_fonts(); // Assumes this returns a proper Google Fonts CSS URL
		$css = wp_remote_get(esc_url_raw($fonts_url));

		if (is_wp_error($css)) {
			return false;
		}

		$css_body = wp_remote_retrieve_body($css);

		preg_match_all('/https:\/\/fonts.gstatic.com\/s\/[^)]+/', $css_body, $matches);
		$font_files = $matches[0];

		if (is_array($font_files) && !empty($font_files)) {
			foreach ($font_files as $font_url) {
				$parsed_url = wp_parse_url($font_url);
				$path_parts = pathinfo($parsed_url['path']);

				$sub_dir = $font_dir . ltrim($path_parts['dirname'], '/');
				if (!file_exists($sub_dir)) {
					wp_mkdir_p($sub_dir);
				}

				$font_file_path = $sub_dir . '/' . $path_parts['basename'];

				if (!file_exists($font_file_path)) {
					$font_data = wp_remote_get(esc_url_raw($font_url));
					if (!is_wp_error($font_data)) {
						$font_contents = wp_remote_retrieve_body($font_data);
						$wp_filesystem->put_contents($font_file_path, $font_contents, FS_CHMOD_FILE);
					}
				}
			}
		}

		// Replace external URLs with local URLs in CSS
		$css_body = str_replace(
			'https://fonts.gstatic.com',
			untrailingslashit($font_url_base),
			$css_body
		);

		// Save the local CSS file
		$css_file_path = $font_dir . 'fonts.css';
		$wp_filesystem->put_contents($css_file_path, $css_body, FS_CHMOD_FILE);

		return true;
	}
}

/**
 * Handle AJAX request for product variation data
 */
if (!function_exists('woocommerce_get_variation')) {
	function woocommerce_get_variation()
	{
		if (!isset($_POST['product_id']) || !isset($_POST['variation'])) {
			wp_send_json_error(esc_html__('Invalid request', 'woomen'));
		}

		$product_id = absint($_POST['product_id']);
		$variation_data = array_map('sanitize_text_field', $_POST['variation']);

		$product = wc_get_product($product_id);
		if (!$product || !$product->is_type('variable')) {
			wp_send_json_error(esc_html__('Invalid product', 'woomen'));
		}

		$variation = new WC_Product_Variation(absint($variation_data['variation_id']));
		if (!$variation) {
			wp_send_json_error(esc_html__('Invalid variation', 'woomen'));
		}

		wp_send_json_success(array(
			'variation_id' => $variation->get_id(),
			'price' => $variation->get_price(),
			'availability' => $variation->get_availability(),
		));
	}
	add_action('wp_ajax_woocommerce_get_variation', 'woocommerce_get_variation');
	add_action('wp_ajax_nopriv_woocommerce_get_variation', 'woocommerce_get_variation');
}

if (!function_exists('woomen_post_loop_tags_output')) {
	/**
	 * Filter post loop tags output
	 *
	 * @param string $return Current output
	 * @param string $field Field name
	 * @param int $post_id Post ID
	 * @return string Modified output
	 */
	function woomen_post_loop_tags_output($return, $field, $post_id)
	{
		$post_type = get_post_type(absint($post_id));
		if (($field === 'featured_image' || $field === 'post_loop_image') && $post_type == 'post') {
			ob_start();
			echo esc_url(woomen_get_post_featured_image($post_id, false, 'woomen'));
			$return = ob_get_clean();
		}
		return $return;
	}
	add_filter('cubewp/post/card/tags/value', 'woomen_post_loop_tags_output', 10, 3);
}
