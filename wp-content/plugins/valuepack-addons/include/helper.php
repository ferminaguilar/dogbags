<?php

/**
 * Helper functions for Value Pack Addons
 *
 * @package valuepack-addons/cube/include
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

if (!function_exists('value_pack_add_woocommerce_settings_sections')) {
    /**
     * Add URL settings section to CubeWP options
     *
     * @param array $sections Existing sections
     * @return array Modified sections
     */
    function value_pack_add_woocommerce_settings_sections($sections)
    {
        // Adding Wocommerce Section
        $settings['wocommerce_section'] = array(
            'title'  => __('WooCommerce', 'valuepack-addons'),
            'id'     => 'wocommerce_section',
            'icon'   => 'dashicons dashicons-archive',
            'fields' => array(
                array(
                    'id'      => 'wc_quick_checkout_style',
                    'title'   => __('WooCommerce Quick Checkout Style', 'valuepack-addons'),
                    'desc'    => __('Please select  Quick Checkout Style for cart Page checkout', 'valuepack-addons'),
                    'type'    => 'select',
                    'options' => array(
                        'style-1' => esc_html__('Style 1', 'valuepack-addons'),
                        'style-2' => esc_html__('Style 2', 'valuepack-addons'),
                        'style-3' => esc_html__('Style 3', 'valuepack-addons'),
                    ),
                    'default' => 'style-1',
                ),

            ),
        );

        $single_position = array_search('map', array_keys($sections)) + 1;
        return array_merge(
            array_slice($sections, 0, $single_position),
            $settings,
            array_slice($sections, $single_position)
        );
    }
    add_filter('cubewp/options/sections', 'value_pack_add_woocommerce_settings_sections', 9, 1);
}

/**
 * @function value_pack_get_setting
 *
 * Return settings from CubeWP Settings.
 */
/**
 * Get theme setting from CubeWP options
 *
 * @param string $setting Setting name
 * @param string $handle_as How to handle the setting (default|page_url|media_url)
 * @param string $find_array Array key to find if setting is array
 * @return mixed Setting value
 */
if (! function_exists('value_pack_get_setting')) {
    function value_pack_get_setting($setting, $handle_as = 'default', $find_array = '')
    {
        static $valuepack_cwpOptions = null;

        if ($valuepack_cwpOptions === null || ! is_array($valuepack_cwpOptions)) {
            $valuepack_cwpOptions = get_option('cwpOptions');
        }

        $return = '';
        $setting = sanitize_text_field($setting);
        $handle_as = sanitize_text_field($handle_as);
        $find_array = sanitize_text_field($find_array);

        if ($handle_as == 'default') {
            $return = isset($valuepack_cwpOptions[$setting]) ? $valuepack_cwpOptions[$setting] : '';
        } elseif ($handle_as == 'page_url') {
            $return = isset($valuepack_cwpOptions[$setting]) ? $valuepack_cwpOptions[$setting] : false;
            if (is_array($return)) {
                $return = isset($return[$find_array]) ? $return[$find_array] : false;
            }
            if (is_numeric($return)) {
                $return = get_permalink(absint($return));
            }
        } elseif ($handle_as == 'media_url') {
            $return = isset($valuepack_cwpOptions[$setting]) ? $valuepack_cwpOptions[$setting] : '';
            if (is_numeric($return)) {
                $return = wp_get_attachment_url(absint($return));
            }
        }

        return apply_filters('value_pack_get_setting', $return, $setting, $handle_as, $find_array);
    }
}

if (! function_exists('value_pack_get_woocommerce_term_id_by_slug')) {
    function value_pack_get_woocommerce_term_id_by_slug($terms, $taxonomy = 'product_type')
    {
        $terms = explode(',', $terms);
        $term_ids = '';
        if (! empty($terms) && is_array($terms)) {
            foreach ($terms as $counter => $term) {
                if ($counter != 0) {
                    $term_ids .= ',';
                }
                $term    = get_term_by('slug', $term, $taxonomy);
                if (! empty($term) && ! is_wp_error($term)) {
                    $term_ids .= $term->term_id;
                }
            }
        }

        return $term_ids;
    }
}

if (!function_exists('value_pack_add_product_attributes_column')) {
    /**
     * Add product attributes column to WooCommerce export
     *
     * @param array $columns Existing export columns
     * @return array Modified columns
     */
    function value_pack_add_product_attributes_column($columns)
    {
        $columns['product_attributes'] = esc_html__('Product Attributes', 'valuepack-addons');
        return $columns;
    }
    add_filter('woocommerce_product_export_columns', 'value_pack_add_product_attributes_column');
}

if (!function_exists('value_pack_populate_product_attributes_column')) {
    /**
     * Populate product attributes column in WooCommerce export
     *
     * @param mixed $value Default value
     * @param WC_Product $product Product object
     * @return string Formatted attributes
     */
    function value_pack_populate_product_attributes_column($value, $product)
    {
        $attributes = $product->get_attributes();
        if (empty($attributes)) {
            return '';
        }

        $formatted_attributes = [];

        foreach ($attributes as $attribute) {
            $name = $attribute->get_name();
            $options = $attribute->is_taxonomy()
                ? wc_get_product_terms($product->get_id(), $name, ['fields' => 'names'])
                : $attribute->get_options();

            $formatted_attributes[] = esc_html($name) . ': ' . esc_html(implode(', ', (array)$options));
        }

        return implode(' | ', $formatted_attributes);
    }
    add_filter('woocommerce_product_export_product_column_product_attributes', 'value_pack_populate_product_attributes_column', 10, 2);
}

/**
 * Add display type field to attribute forms
 */
function value_pack_add_or_edit_display_type_field()
{
    $default_display_type = 'select';

    if (isset($_GET['edit']) && !empty($_GET['edit'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
        $edit_id = absint($_GET['edit']); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended

        if (class_exists('WooCommerce') && function_exists('wc_get_attribute_taxonomies')) {
            $taxonomies = wc_get_attribute_taxonomies();
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    if ($taxonomy->attribute_id == $edit_id) {
                        $default_display_type = $taxonomy->attribute_type;
                        break;
                    }
                }
            }
        }

        echo '<tr class="form-field">';
        echo '<th valign="top" scope="row"><label for="wm_attr_display_type">' . esc_html__('Display Type', 'valuepack-addons') . '</label></th>';
        echo '<td><select id="wm_attr_display_type" name="wm_attr_display_type">';
        echo '<option value="select" ' . selected($default_display_type, 'select', false) . '>' . esc_html__('Select', 'valuepack-addons') . '</option>';
        echo '<option value="label" ' . selected($default_display_type, 'label', false) . '>' . esc_html__('Label', 'valuepack-addons') . '</option>';
        echo '<option value="color" ' . selected($default_display_type, 'color', false) . '>' . esc_html__('Color', 'valuepack-addons') . '</option>';
        echo '<option value="image" ' . selected($default_display_type, 'image', false) . '>' . esc_html__('Image', 'valuepack-addons') . '</option>';
        echo '</select></td></tr>';
    } else {
        echo '<div class="form-field">';
        echo '<label for="wm_attr_display_type">' . esc_html__('Display Type', 'valuepack-addons') . '</label>';
        echo '<select id="wm_attr_display_type" name="wm_attr_display_type">';
        echo '<option value="select">' . esc_html__('Select', 'valuepack-addons') . '</option>';
        echo '<option value="label">' . esc_html__('Label', 'valuepack-addons') . '</option>';
        echo '<option value="color">' . esc_html__('Color', 'valuepack-addons') . '</option>';
        echo '<option value="image">' . esc_html__('Image', 'valuepack-addons') . '</option>';
        echo '</select></div>';
    }
}
add_action('woocommerce_after_add_attribute_fields', 'value_pack_add_or_edit_display_type_field');
add_action('woocommerce_after_edit_attribute_fields', 'value_pack_add_or_edit_display_type_field');

/**
 * Save display type field
 */
function value_pack_save_display_type_field($attribute_id, $attribute_data)
{
    if (!isset($_POST['wm_attr_display_type'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
        return;
    }

    global $wpdb;
    $display_type = sanitize_text_field(wp_unslash($_POST['wm_attr_display_type'])); // phpcs:ignore WordPress.Security.NonceVerification.Missing
    $wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->prefix . 'woocommerce_attribute_taxonomies',
        ['attribute_type' => $display_type],
        ['attribute_id' => absint($attribute_id)],
        ['%s'],
        ['%d']
    );
}
add_action('woocommerce_attribute_added', 'value_pack_save_display_type_field', 10, 2);
add_action('woocommerce_attribute_updated', 'value_pack_save_display_type_field', 10, 2);

/**
 * Add custom meta fields for product attributes
 */
function value_pack_customize_product_taxonomy_meta_fields()
{
    if (!class_exists('WooCommerce') || !function_exists('wc_get_attribute_taxonomies')) {
        return;
    }

    $attributes = wc_get_attribute_taxonomies();
    foreach ($attributes as $attribute) {
        if (in_array($attribute->attribute_type, array('color', 'image'))) {
            add_action('pa_' . $attribute->attribute_name . '_edit_form_fields', 'value_pack_render_taxonomy_fields');
            add_action('pa_' . $attribute->attribute_name . '_add_form_fields', 'value_pack_render_taxonomy_fields');
            add_action('edited_pa_' . $attribute->attribute_name, 'value_pack_save_taxonomy_meta');
            add_action('create_pa_' . $attribute->attribute_name, 'value_pack_save_taxonomy_meta');
        }
    }
}
add_action('admin_init', 'value_pack_customize_product_taxonomy_meta_fields');

/**
 * Render taxonomy fields
 */
function value_pack_render_taxonomy_fields($term)
{
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    $current_value = '';
    if (isset($term->taxonomy)) {
        $current_value = get_term_meta($term->term_id, 'woomen_attr_field', true);
        $current = $term->taxonomy;
    } else {
        $current = $term;
    }

    $placeholder_url = esc_url(get_theme_file_uri('/muffin-options/svg/placeholders/image.svg'));
    $attribute_name = str_replace('pa_', '', $current);
    $attribute = null;

    if (class_exists('WooCommerce') && function_exists('wc_get_attribute_taxonomies')) {
        $attributes = wc_get_attribute_taxonomies();
        foreach ($attributes as $attr) {
            if ($attr->attribute_name === $attribute_name) {
                $attribute = $attr;
                break;
            }
        }
    }

    $display_type = $attribute ? $attribute->attribute_type : 'select';
    /* translators: %s: display type */
    $field_label = sprintf(esc_html__('Select %s', 'valuepack-addons'), ucfirst($display_type));
    $field_name = 'woomen_attr_field_' . $display_type;

    if (isset($term->taxonomy)) {
        echo '<tr class="form-field vp-field">';
        echo '<th valign="top" scope="row"><label for="woomen_attr_field">' . esc_html($field_label) . '</label></th>';
        echo '<td>';

        if ($display_type === 'color') {
            echo '<input type="text" id="woomen_attr_field" value="' . esc_attr($current_value) . '" name="woomen_attr_field" class="woomen_attr_field_color" required>';
        } else {
            echo '<input type="hidden" id="woomen_attr_field" value="' . esc_attr($current_value) . '" name="woomen_attr_field" class="' . esc_attr($field_name) . '" required>';
            $current_value = wp_get_attachment_url(absint($current_value));
            echo '<div class="vp-image-container">';
            echo '<img id="woomen_image_preview" data-src="' . esc_url($placeholder_url) . '" src="' . esc_url($current_value ?: $placeholder_url) . '" alt="" style="max-width:100%;" />';
            echo '<a class="upload-image button" id="woomen_upload_image" href="#">' . esc_html__('Set custom image', 'valuepack-addons') . '</a>';
            echo '<a class="remove-image button ' . (!$current_value ? 'hidden' : '') . '" id="woomen_remove_image" href="#">' . esc_html__('Remove image', 'valuepack-addons') . '</a>';
            echo '</div>';
        }

        echo '</td></tr>';
    } else {
        echo '<div class="form-field vp-field">';
        echo '<label for="woomen_attr_field">' . esc_html($field_label) . '</label>';

        if ($display_type === 'color') {
            echo '<input type="text" id="woomen_attr_field" value="' . esc_attr($current_value) . '" name="woomen_attr_field" class="woomen_attr_field_color" required>';
        } else {
            echo '<input type="hidden" id="woomen_attr_field" value="' . esc_attr($current_value) . '" name="woomen_attr_field" class="' . esc_attr($field_name) . '" required>';
            $current_value = wp_get_attachment_url(absint($current_value));
            echo '<div class="vp-image-container">';
            echo '<img id="woomen_image_preview" data-src="' . esc_url($placeholder_url) . '" src="' . esc_url($current_value ?: $placeholder_url) . '" alt="" style="max-width:100%;" />';
            echo '<a id="woomen_upload_image" class="upload-image button ' . ($current_value ? 'hidden' : '') . '" href="#">' . esc_html__('Set custom image', 'valuepack-addons') . '</a>';
            echo '<a id="woomen_remove_image" class="remove-image button ' . (!$current_value ? 'hidden' : '') . '" href="#">' . esc_html__('Remove image', 'valuepack-addons') . '</a>';
            echo '</div>';
        }

        echo '</div>';
    }

    // JavaScript for media uploader
?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.woomen_attr_field_color').wpColorPicker();

            var frame;
            $('#woomen_upload_image').on('click', function(e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: '<?php esc_html_e('Select or Upload Image', 'valuepack-addons'); ?>',
                    button: {
                        text: '<?php esc_html_e('Use this image', 'valuepack-addons'); ?>'
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#woomen_image_preview').attr('src', attachment.url);
                    $('#woomen_attr_field').val(attachment.id);
                    $('#woomen_remove_image').removeClass('hidden');
                    $('#woomen_upload_image').addClass('hidden');
                });

                frame.open();
            });

            $('#woomen_remove_image').on('click', function(e) {
                e.preventDefault();
                $('#woomen_image_preview').attr('src', $('#woomen_image_preview').attr('data-src'));
                $('#woomen_attr_field').val('');
                $(this).addClass('hidden');
                $('#woomen_upload_image').removeClass('hidden');
            });
        });
    </script>
    <?php
}

/**
 * Save taxonomy meta
 */
function value_pack_save_taxonomy_meta($term_id)
{
    if (isset($_POST['woomen_attr_field'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
        update_term_meta(
            absint($term_id),
            'woomen_attr_field',
            sanitize_text_field(wp_unslash($_POST['woomen_attr_field'])) // phpcs:ignore WordPress.Security.NonceVerification.Missing
        );
    }
}

/**
 * Display custom product attributes
 */
function value_pack_display_custom_product_attributes()
{
    global $product;

    if (!$product) {
        return;
    }

    $attributes = $product->get_attributes();
    if (!$attributes) {
        return;
    }

    $default_attributes = $product->get_default_attributes();

    echo '<div class="wm-product-attributes">';

    foreach ($attributes as $attribute) {

        // Skip if not used for variation
        if (! $attribute->get_variation()) {
            continue;
        }

        if ($attribute->is_taxonomy()) {
            $terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));

            if (!empty($terms)) {
                $selected = isset($_GET['attribute_' . $attribute->get_name()]) ? sanitize_text_field(wp_unslash($_GET['attribute_' . $attribute->get_name()])) : (isset($default_attributes[$attribute->get_name()]) ? $default_attributes[$attribute->get_name()] : ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                echo '<div class="attribute-container">';
                echo '<div class="attribute-heading attribute-heading-' . esc_attr(sanitize_title(wc_attribute_label($attribute->get_name()))) . '">';
                echo '<h3 class="' . esc_attr(sanitize_title(wc_attribute_label($attribute->get_name()))) . '">
                ' . esc_html(wc_attribute_label($attribute->get_name()));
                if (!empty($selected)) {
                    echo ': <span class="slug-display">' . esc_html($selected) . '</span>';
                }
                echo '</h3>';
                echo '</div>';

                $attribute_taxonomies = array();
                if (class_exists('WooCommerce') && function_exists('wc_get_attribute_taxonomies')) {
                    $attribute_taxonomies = wc_get_attribute_taxonomies();
                }

                $current_obj = null;
                foreach ($attribute_taxonomies as $attr) {
                    if ('pa_' . $attr->attribute_name === $attribute->get_name()) {
                        $current_obj = $attr;
                        break;
                    }
                }

                if ($current_obj) {
                    $attribute_type = $current_obj->attribute_type;

                    switch ($attribute_type) {
                        case 'color':
                            $selected = isset($_GET['attribute_' . $attribute->get_name()]) ? sanitize_text_field(wp_unslash($_GET['attribute_' . $attribute->get_name()])) : (isset($default_attributes[$attribute->get_name()]) ? $default_attributes[$attribute->get_name()] : ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                            echo '<ul class="color-attributes">';
                            foreach ($terms as $term) {
                                $color_value = get_term_meta($term->term_id, 'woomen_attr_field', true);
                                $is_selected = ($term->slug === $selected) ? ' active' : '';
                                echo '<li class="attribute-color-item attribute' . esc_attr($is_selected) . '" data-attr-type="' . esc_attr($attribute_type) . '" data-term-slug="' . esc_attr($term->slug) . '" data-term-name="' . esc_attr($term->name) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                                echo '<span class="color-swatch wm-variation-attr" style="background-color: ' . esc_attr($color_value) . ';"></span>';
                                echo '</li>';
                            }
                            echo '</ul>';
                            break;

                        case 'label':
                            $selected = isset($_GET['attribute_' . $attribute->get_name()]) ? sanitize_text_field(wp_unslash($_GET['attribute_' . $attribute->get_name()])) : (isset($default_attributes[$attribute->get_name()]) ? $default_attributes[$attribute->get_name()] : ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                            echo '<ul class="label-attributes">';
                            foreach ($terms as $term) {
                                $is_selected = ($term->slug === $selected) ? ' active' : '';
                                echo '<li class="attribute-label-item' . esc_attr($is_selected) . '" data-attr-type="' . esc_attr($attribute_type) . '" data-term-slug="' . esc_attr($term->slug) . '" data-term-name="' . esc_attr($term->name) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                                echo '<span class="wm-variation-attr">' . esc_html($term->name) . '</span>';
                                echo '</li>';
                            }
                            echo '</ul>';
                            break;

                        case 'image':
                            $selected = isset($_GET['attribute_' . $attribute->get_name()]) ? sanitize_text_field(wp_unslash($_GET['attribute_' . $attribute->get_name()])) : (isset($default_attributes[$attribute->get_name()]) ? $default_attributes[$attribute->get_name()] : ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
                            echo '<ul class="image-attributes">';
                            foreach ($terms as $term) {
                                $image_id = get_term_meta($term->term_id, 'woomen_attr_field', true);
                                $image_url = wp_get_attachment_url(absint($image_id));
                                $is_selected = ($term->slug === $selected) ? ' active' : '';
                                echo '<li class="attribute-image-item' . esc_attr($is_selected) . '" data-attr-type="' . esc_attr($attribute_type) . '" data-term-slug="' . esc_attr($term->slug) . '" data-term-name="' . esc_attr($term->name) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                                echo '<img class="wm-variation-attr" src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '">';
                                echo '</li>';
                            }
                            echo '</ul>';
                            break;

                        default:
                            echo '<select class="default-attribute-dropdown wm-variation-attr" data-attr-type="' . esc_attr($attribute_type) . '" data-term-name="' . esc_attr($term->name) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                            foreach ($terms as $term) {
                                echo '<option value="' . esc_attr($term->slug) . '" ' . selected($term->slug, $selected, false) . '>';
                                echo esc_html($term->name);
                                echo '</option>';
                            }
                            echo '</select>';
                            break;
                    }
                }

                echo '</div>';
            }
        } else {
            $value = $attribute->get_options();
            echo '<div class="custom-attribute">';
            echo '<h3>' . esc_html(wc_attribute_label($attribute->get_name())) . '</h3>';
            echo '<span class="custom-value">' . esc_html(implode(', ', (array)$value)) . '</span>';
            echo '</div>';
        }
    }

    echo '</div>';
}
add_action('woocommerce_before_add_to_cart_form', 'value_pack_display_custom_product_attributes', 25);

// ajax fucntion for mini cart data
add_action('wp_ajax_get_cart_content', 'value_pack_get_cart_content');
add_action('wp_ajax_nopriv_get_cart_content', 'value_pack_get_cart_content');

if (!function_exists('value_pack_get_cart_content')) {
    function value_pack_get_cart_content()
    {
        $items_html = [];
        $item_prices = [];
        $total_price = 0;
        $cart_items = array();
        $free_shipping_html = '';
        if (class_exists('WooCommerce')) {
            $cart_items = WC()->cart->get_cart();
        }
        if (empty($cart_items)) {
            $items_html[] = '<div class="vp-cart-empty">
                <p>' . esc_html__('Your cart is empty', 'valuepack-addons') . '</p>
                <a href="' . esc_url(wc_get_cart_url()) . '" class="vp-cart-view-button">
                    ' . esc_html__('View Cart', 'valuepack-addons') . '
                </a>
            </div>';
            $item_prices[] = 0;
            $cart_items_data = false;
        } else {
            $cart_items_data = true;
            foreach ($cart_items as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                $product_id = $product->get_id();
                $product_name = $product->get_name();
                $product_price = (float) $product->get_price();
                $product_permalink = get_permalink($product_id);
                $product_image = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0];
                $quantity = $cart_item['quantity'];

                // Get dynamic attributes (for variable products)
                $attributes_html = '';
                if ($product->is_type('variation')) {
                    $variation_attributes = $product->get_variation_attributes();

                    foreach ($variation_attributes as $attribute_name => $attribute_value) {
                        $taxonomy = str_replace('attribute_', '', $attribute_name);
                        $label = wc_attribute_label($taxonomy);
                        $attributes_html .= esc_html($label) . ': ' . esc_html($attribute_value) . '<br>';
                    }
                }

                $item_html = '
						<div class="row">
							<div class="vp-cart-item col-12">
								<div class="vp-cart-item-image">
									<a href="' . esc_url($product_permalink) . '">
										<img src="' . esc_url($product_image) . '" alt="' . esc_attr($product_name) . '" class="vp-cart-item-img">
									</a>
								</div>
								<div class="vp-cart-item-content">
									<a href="' . esc_url($product_permalink) . '">
										<p class="vp-cart-item-title">' .  $product_name . '</p>
									</a>
									<p class="vp-cart-item-price">' . wc_price($product_price) . '</p>

									<p class="vp-cart-item-attributes">
										' . $attributes_html . '
									</p>

									<div class="vp-cart-item-quantity">
										<div class="vp-cart-item-quantity-selection" data-cart-item-key="' . esc_attr($cart_item_key) . '">
											<i class="fa-solid fa-minus vp-cart-item-minus-icon"></i>
											<p class="vp-cart-item-action-count">' . esc_html($quantity) . '</p>
											<i class="fa-solid fa-plus vp-cart-item-plus-icon"></i>
										</div>
										<div class="vp-cart-item-remove-button">
											<p class="vp-cart-item-shopping-remove" data-cart-item-key="' . esc_attr($cart_item_key) . '">Remove</p>
											<div class="remove-spinner"></div>
										</div>
									</div>
									<div class="quantity-spinner"></div>
								</div>
							</div>
						</div>';

                $items_html[] = $item_html;
                $item_prices[] = $product_price * $quantity;
                $total_price += $product_price * $quantity;
            }
            $free_shipping_html = value_pack_get_free_shipping_html();
        }
        wp_send_json([
            'items_html' => $items_html,
            'free_shipping_html' => $free_shipping_html,
            'item_prices' => $item_prices,
            'total_price' => wc_price($total_price),
            'cart_items_data' => $cart_items_data,
        ]);
        wp_die();
    }
}

/**
 * Handle quick checkout AJAX request
 *
 * @since 1.0.0
 * @hook wp_ajax_value_pack_card_quick_checkout
 * @hook wp_ajax_nopriv_value_pack_card_quick_checkout
 * @return void Outputs JSON response
 */
if (!function_exists('value_pack_card_quick_checkout')) {
    function value_pack_card_quick_checkout()
    {
        $productID = absint($_POST['pID'] ?? 0); // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if (empty($productID)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => esc_html__('Invalid product ID.', 'valuepack-addons'),
            ));
            return;
        }

        $product = wc_get_product($productID);

        if (!$product) {
            wp_send_json(array(
                'type' => 'error',
                'message' => esc_html__('Product not found.', 'valuepack-addons'),
            ));
            return;
        }

        global $post;
        $post = get_post($productID);
        setup_postdata($post);

        ob_start();
        $attachment_ids = $product->get_gallery_image_ids();
        $checkoutStyle  = value_pack_get_setting('wc_quick_checkout_style');
        $checkoutStyle = (isset($checkoutStyle) && !empty($checkoutStyle)) ? $checkoutStyle : 'style-1';
        if ($checkoutStyle == 'style-1') {
    ?>
            <div class="wc-woomen-card-quick-checkout-popup style1">
                <div class="wc-woomen-card-popup-details">
                    <div class="wc-woomen-card-popup-details-top">
                        <h4><?php echo esc_html__('Select Options',  'valuepack-addons'); ?></h4>
                        <button class="wc-woomen-card-popup-close"><svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="35" height="35" rx="17.5" fill="white" />
                                <path d="M12.9884 22.4999C12.8918 22.5 12.7974 22.4713 12.717 22.4177C12.6367 22.364 12.5741 22.2878 12.5372 22.1985C12.5002 22.1093 12.4905 22.0111 12.5094 21.9164C12.5282 21.8217 12.5748 21.7346 12.6431 21.6664L21.6663 12.6431C21.7579 12.5515 21.8821 12.5001 22.0116 12.5001C22.1412 12.5001 22.2654 12.5515 22.3569 12.6431C22.4485 12.7347 22.5 12.8589 22.5 12.9884C22.5 13.1179 22.4485 13.2421 22.3569 13.3337L13.3337 22.357C13.2884 22.4024 13.2345 22.4384 13.1753 22.4629C13.116 22.4874 13.0525 22.5 12.9884 22.4999Z" fill="#1D1D1D" />
                                <path d="M22.0117 22.4999C21.9475 22.5 21.884 22.4874 21.8248 22.4629C21.7655 22.4384 21.7117 22.4024 21.6664 22.357L12.6431 13.3337C12.5515 13.2421 12.5001 13.1179 12.5001 12.9884C12.5001 12.8589 12.5515 12.7347 12.6431 12.6431C12.7347 12.5515 12.8589 12.5001 12.9884 12.5001C13.1179 12.5001 13.2421 12.5515 13.3337 12.6431L22.357 21.6664C22.4253 21.7346 22.4718 21.8217 22.4906 21.9164C22.5095 22.0111 22.4998 22.1093 22.4629 22.1985C22.4259 22.2878 22.3633 22.364 22.283 22.4177C22.2027 22.4713 22.1083 22.5 22.0117 22.4999Z" fill="#1D1D1D" />
                            </svg>
                        </button>
                    </div>
                    <div class="wc-woomen-card-popup-details-main">
                        <?php
                        $attachment_ids = $product->get_gallery_image_ids();

                        if ($attachment_ids) {
                            echo '<div class="product-gallery">';
                            if ($attachment_ids) {
                                foreach ($attachment_ids as $attachment_id) {
                                    $image_url = wp_get_attachment_url($attachment_id);
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
                                }
                            }

                            echo '</div>';
                        }
                        echo '<h2>' . esc_html($product->get_name()) . '</h2>';
                        echo '<div class="product-price">' . wp_kses_post($product->get_price_html()) . '</div>';
                        woocommerce_template_single_add_to_cart();

                        ?>
                        <a class="wc-women-quick-view-all" href="<?php echo esc_url(get_permalink($productID)) ?>"><?php echo esc_html__('VIEW ALL DETAILS',  'valuepack-addons'); ?> <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        <?php
        } elseif ($checkoutStyle == 'style-2') {
        ?>
            <div class="wc-woomen-card-quick-checkout-popup style1 style2-combine">
                <div class="wc-woomen-card-popup-details">
                    <div class="wc-women-style2-checkout">
                        <?php
                        if ($attachment_ids) {
                            if ($attachment_ids) {
                                foreach ($attachment_ids as $attachment_id) {
                                    $image_url = wp_get_attachment_url($attachment_id);
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="wc-woomen-card-popup-details-setup">
                        <div class="wc-woomen-card-popup-details-top">
                            <h4><?php echo esc_html__('Select Options',  'valuepack-addons'); ?></h4>
                            <button class="wc-woomen-card-popup-close"><svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="35" height="35" rx="17.5" fill="white" />
                                    <path d="M12.9884 22.4999C12.8918 22.5 12.7974 22.4713 12.717 22.4177C12.6367 22.364 12.5741 22.2878 12.5372 22.1985C12.5002 22.1093 12.4905 22.0111 12.5094 21.9164C12.5282 21.8217 12.5748 21.7346 12.6431 21.6664L21.6663 12.6431C21.7579 12.5515 21.8821 12.5001 22.0116 12.5001C22.1412 12.5001 22.2654 12.5515 22.3569 12.6431C22.4485 12.7347 22.5 12.8589 22.5 12.9884C22.5 13.1179 22.4485 13.2421 22.3569 13.3337L13.3337 22.357C13.2884 22.4024 13.2345 22.4384 13.1753 22.4629C13.116 22.4874 13.0525 22.5 12.9884 22.4999Z" fill="#1D1D1D" />
                                    <path d="M22.0117 22.4999C21.9475 22.5 21.884 22.4874 21.8248 22.4629C21.7655 22.4384 21.7117 22.4024 21.6664 22.357L12.6431 13.3337C12.5515 13.2421 12.5001 13.1179 12.5001 12.9884C12.5001 12.8589 12.5515 12.7347 12.6431 12.6431C12.7347 12.5515 12.8589 12.5001 12.9884 12.5001C13.1179 12.5001 13.2421 12.5515 13.3337 12.6431L22.357 21.6664C22.4253 21.7346 22.4718 21.8217 22.4906 21.9164C22.5095 22.0111 22.4998 22.1093 22.4629 22.1985C22.4259 22.2878 22.3633 22.364 22.283 22.4177C22.2027 22.4713 22.1083 22.5 22.0117 22.4999Z" fill="#1D1D1D" />
                                </svg>
                            </button>
                        </div>
                        <div class="wc-woomen-card-popup-details-main">
                            <?php
                            $attachment_ids = $product->get_gallery_image_ids();
                            echo '<h2>' . esc_html($product->get_name()) . '</h2>';
                            echo '<div class="product-price">' . wp_kses_post($product->get_price_html()) . '</div>';
                            woocommerce_template_single_add_to_cart();

                            ?>
                            <?php if ($attachment_ids) {
                                // Get product tabs
                                $product_tabs = apply_filters('woocommerce_product_tabs', []); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

                                // Check if description and additional information tabs exist
                                if (isset($product_tabs['description']) && isset($product_tabs['additional_information'])) {
                            ?>
                                    <div class="wc-quick-checkout-accordion" id="productAccordion">

                                        <!-- Description Tab -->
                                        <div class="wc-accordion-item">
                                            <h2 class="wc-accordion-header" id="headingDescription">
                                                <button class="wc-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDescription" aria-expanded="false" aria-controls="collapseDescription">
                                                    <span><?php echo esc_html__('Description',  'valuepack-addons'); ?></span>

                                                    <svg class="close-accordion" width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect y="0.5" width="10" height="1" fill="#1D1D1D" />
                                                    </svg>
                                                    <svg class="open-accordion" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect y="4.5" width="10" height="1" fill="#1D1D1D" />
                                                        <rect x="4.5" y="10" width="10" height="1" transform="rotate(-90 4.5 10)" fill="#1D1D1D" />
                                                    </svg>
                                                </button>
                                            </h2>
                                            <div id="collapseDescription" class="wc-accordion-collapse collapse" aria-labelledby="headingDescription" data-bs-parent="#productAccordion">
                                                <div class="wc-accordion-body">
                                                    <?php call_user_func($product_tabs['description']['callback'], 'description', $product_tabs['description']); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Information Tab -->
                                        <div class="wc-accordion-item">
                                            <h2 class="wc-accordion-header" id="headingAdditionalInfo">
                                                <button class="wc-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdditionalInfo" aria-expanded="false" aria-controls="collapseAdditionalInfo">
                                                    <span><?php echo esc_html__('Details',  'valuepack-addons'); ?></span>
                                                    <svg class="close-accordion" width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect y="0.5" width="10" height="1" fill="#1D1D1D" />
                                                    </svg>
                                                    <svg class="open-accordion" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect y="4.5" width="10" height="1" fill="#1D1D1D" />
                                                        <rect x="4.5" y="10" width="10" height="1" transform="rotate(-90 4.5 10)" fill="#1D1D1D" />
                                                    </svg>
                                                </button>
                                            </h2>
                                            <div id="collapseAdditionalInfo" class="wc-accordion-collapse collapse" aria-labelledby="headingAdditionalInfo" data-bs-parent="#productAccordion">
                                                <div class="wc-accordion-body">
                                                    <?php call_user_func($product_tabs['additional_information']['callback'], 'additional_information', $product_tabs['additional_information']); ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                            <?php }
                            }
                            ?>

                            <a class="wc-women-quick-view-all" href="<?php echo esc_url(get_permalink($productID)) ?>"><?php echo esc_html__('VIEW ALL DETAILS',  'valuepack-addons'); ?> <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } elseif ($checkoutStyle == 'style-3') {

        ?>
            <div class="wc-woomen-card-quick-checkout-popup style1 style3-combine">
                <div class="wc-woomen-card-popup-details row">
                    <div class="wc-women-style2-checkout col-lg-6 col-12">
                        <?php
                        if ($attachment_ids) {
                            if ($attachment_ids) {
                                foreach ($attachment_ids as $attachment_id) {
                                    $image_url = wp_get_attachment_url($attachment_id);
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="wc-woomen-card-popup-details-setup col-lg-6 col-12">
                        <div class="wc-woomen-card-popup-details-top">
                            <button class="wc-woomen-card-popup-close"><svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="35" height="35" rx="17.5" fill="white" />
                                    <path d="M12.9884 22.4999C12.8918 22.5 12.7974 22.4713 12.717 22.4177C12.6367 22.364 12.5741 22.2878 12.5372 22.1985C12.5002 22.1093 12.4905 22.0111 12.5094 21.9164C12.5282 21.8217 12.5748 21.7346 12.6431 21.6664L21.6663 12.6431C21.7579 12.5515 21.8821 12.5001 22.0116 12.5001C22.1412 12.5001 22.2654 12.5515 22.3569 12.6431C22.4485 12.7347 22.5 12.8589 22.5 12.9884C22.5 13.1179 22.4485 13.2421 22.3569 13.3337L13.3337 22.357C13.2884 22.4024 13.2345 22.4384 13.1753 22.4629C13.116 22.4874 13.0525 22.5 12.9884 22.4999Z" fill="#1D1D1D" />
                                    <path d="M22.0117 22.4999C21.9475 22.5 21.884 22.4874 21.8248 22.4629C21.7655 22.4384 21.7117 22.4024 21.6664 22.357L12.6431 13.3337C12.5515 13.2421 12.5001 13.1179 12.5001 12.9884C12.5001 12.8589 12.5515 12.7347 12.6431 12.6431C12.7347 12.5515 12.8589 12.5001 12.9884 12.5001C13.1179 12.5001 13.2421 12.5515 13.3337 12.6431L22.357 21.6664C22.4253 21.7346 22.4718 21.8217 22.4906 21.9164C22.5095 22.0111 22.4998 22.1093 22.4629 22.1985C22.4259 22.2878 22.3633 22.364 22.283 22.4177C22.2027 22.4713 22.1083 22.5 22.0117 22.4999Z" fill="#1D1D1D" />
                                </svg>
                            </button>
                        </div>
                        <div class="wc-woomen-card-popup-details-main">
                            <?php
                            $attachment_ids = $product->get_gallery_image_ids();
                            $product_description = $product->get_description();
                            $product_description_text = wp_strip_all_tags($product_description);
                            echo '<h2>' . esc_html($product->get_name()) . '</h2>';
                            echo '<div class="product-price">' . wp_kses_post($product->get_price_html()) . '</div>';
                            echo '<p>' . wp_kses_post($product_description_text) . '</p>';
                            woocommerce_template_single_add_to_cart();

                            ?>
                            <a class="wc-women-quick-view-all" href="<?php echo esc_url(get_permalink($productID)) ?>"><?php echo esc_html__('VIEW ALL DETAILS',  'valuepack-addons'); ?> <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }

        wp_reset_postdata();
        $ui = ob_get_clean();
        wp_send_json(array(
            'type'         => 'success',
            'html'         => $ui,
        ));
    }

    add_action('wp_ajax_value_pack_card_quick_checkout', 'value_pack_card_quick_checkout');
    add_action('wp_ajax_nopriv_value_pack_card_quick_checkout', 'value_pack_card_quick_checkout');
}

if (class_exists('CubeWp_Load') && !class_exists('CubeWp_Frontend_Load')) {
    if (!function_exists('value_pack_add_url_settings_sections')) {
        /**
         * Add URL settings section to CubeWP options
         *
         * @param array $sections Existing sections
         * @return array Modified sections
         */
        function value_pack_add_url_settings_sections($sections)
        {
            $settings['url-settings'] = array(
                'title'  => esc_html__('URL Config', 'valuepack-addons'),
                'id'     => 'url-settings',
                'icon'   => 'dashicons-admin-links',
                'fields' => array(
                    array(
                        'id'       => 'password_reset_page',
                        'type'     => 'pages',
                        'title'    => esc_html__('Password Reset Page', 'valuepack-addons'),
                        'subtitle' => esc_html__('This must be an URL.', 'valuepack-addons'),
                        'validate' => 'url',
                        'desc'     => esc_html__('Select the page used for the Reset Password (Page must include the Reset Password Shortcode)', 'valuepack-addons'),
                        'default'  => ''
                    ),
                )
            );

            $single_position = array_search('map', array_keys($sections)) + 1;
            return array_merge(
                array_slice($sections, 0, $single_position),
                $settings,
                array_slice($sections, $single_position)
            );
        }
        add_filter('cubewp/options/sections', 'value_pack_add_url_settings_sections', 9, 1);
    }

    if (!function_exists("value_pack_user_default_fields")) {
        /**
         * Get default user registration fields
         *
         * @return array User fields configuration
         */
        function value_pack_user_default_fields()
        {
            return array(
                'user_login'   => array(
                    'label'    => esc_html__('Username', 'valuepack-addons'),
                    'name'     => 'user_login',
                    'type'     => 'text',
                    'required' => 1,
                    'validation_msg' => '',
                ),
                'first_name'   => array(
                    'label' => esc_html__('First Name', 'valuepack-addons'),
                    'name'  => 'first_name',
                    'type'  => 'text',
                    'required' => 1,
                    'validation_msg' => '',
                ),
                'last_name'    => array(
                    'label' => esc_html__('Last Name', 'valuepack-addons'),
                    'name'  => 'last_name',
                    'type'  => 'text',
                    'required' => 1,
                    'validation_msg' => '',
                ),
                'user_email'   => array(
                    'label'    => esc_html__('Email', 'valuepack-addons'),
                    'name'     => 'user_email',
                    'type'     => 'email',
                    'required' => 1,
                    'validation_msg' => '',
                ),
                'user_pass'    => array(
                    'label'    => esc_html__('Password', 'valuepack-addons'),
                    'name'     => 'user_pass',
                    'type'     => 'password',
                    'required' => 0,
                    'validation_msg' => '',
                ),
            );
        }
    }

    if (!function_exists("value_pack_login_redirect_url")) {
        /**
         * Filter login/registration redirect URL
         *
         * @param string $redirect_url Original redirect URL
         * @return string Modified redirect URL
         */
        function value_pack_login_redirect_url($redirect_url)
        {
            return 'self';
        }
        add_filter('cubewp/after/login/redirect-url', 'value_pack_login_redirect_url');
        add_filter('cubewp/after/user/registration/redirect-url', 'value_pack_login_redirect_url');
    }

    if (!function_exists('value_pack_send_password_reset_email')) {
        /**
         * Send password reset email to user
         *
         * @param mixed $user User ID, login or email
         * @return bool True on success, false on failure
         */
        function value_pack_send_password_reset_email($user)
        {
            $user_data = false;

            if (is_numeric($user)) {
                $user_data = get_user_by('id', absint($user));
            } elseif (username_exists($user)) {
                $user_data = get_user_by('login', sanitize_user($user));
            } elseif (email_exists($user)) {
                $user_data = get_user_by('email', sanitize_email($user));
            }

            if (!$user_data) {
                return false;
            }

            return value_pack_send_password_reset_email($user_data->ID);
        }
    }

    if (!function_exists('value_pack_send_reset_password_email')) {
        /**
         * Send password reset email
         *
         * @param int $user_id User ID
         * @return bool True on success, false on failure
         */
        function value_pack_send_reset_password_email($user_id)
        {
            $user_obj = get_userdata(absint($user_id));
            if (empty($user_obj) || !$user_obj) {
                return false;
            }

            $reset_password_link = value_pack_get_password_reset_link($user_id);
            if (!$reset_password_link) {
                return false;
            }

            $email_subject = esc_html__('Password Reset!', 'valuepack-addons');
            $email_content = esc_html__('A password reset request has been received for your profile:', 'valuepack-addons');
            $email_content .= '<br><br>';
            $email_content .= sprintf(
                /* translators: %s: site name */
                esc_html__('Site Name: %1$s%2$s%3$s', 'valuepack-addons'),
                '<a href="' . esc_url(home_url()) . '">',
                esc_html(get_bloginfo('name')),
                '</a>'
            );
            $email_content .= '<br>';
            $email_content .= sprintf(
                /* translators: %s: username */
                esc_html__('Username: %1$s%2$s%3$s', 'valuepack-addons'),
                '<a href="' . esc_url(get_author_posts_url($user_obj->ID)) . '">',
                esc_html($user_obj->display_name),
                '</a>'
            );
            $email_content .= '<br>';
            $email_content .= esc_html__('If you didn\'t initiate this request or if it was made in error, you can disregard this email, and no further action will be taken.', 'valuepack-addons');
            $email_content .= '<br>';
            $email_content .= esc_html__('To reset your password, visit the following address:', 'valuepack-addons');
            $email_content .= '<br>';
            $email_content .= '<a href="' . esc_url($reset_password_link) . '">' . esc_html($reset_password_link) . '</a>';
            $email_content .= '<br>';
            $email_content .= esc_html__('This link will expire within one day.', 'valuepack-addons');

            $email_to = sanitize_email($user_obj->user_email);
            $headers = array();

            $cwpOptions = get_option('cwpOptions');
            $website_name = get_bloginfo('name');
            $admin_email  = apply_filters("cubewp_emails_from_mail", get_option('admin_email')); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
            $email_from = isset($cwpOptions['email_from']) ? $cwpOptions['email_from'] : $website_name;
            $from_email_address = isset($cwpOptions['from_email_address']) ? sanitize_email($cwpOptions['from_email_address']) : $admin_email;

            if (!empty($from_email_address)) {
                $headers[] = 'From: ' . esc_html($email_from) . ' <' . esc_html($from_email_address) . '>';
            }

            return cubewp_send_mail($email_to, $email_subject, $email_content, $headers);
        }
    }

    if (!function_exists('value_pack_get_password_reset_link')) {
        /**
         * Get password reset link for user
         *
         * @param int $user_id User ID
         * @return string|bool Reset link or false on failure
         */
        function value_pack_get_password_reset_link($user_id)
        {
            $user_obj = get_userdata(absint($user_id));
            if (empty($user_obj) || !$user_obj) {
                return false;
            }

            $valuepack_cwp_options = get_option('cwpOptions');

            $password_reset_page = isset($valuepack_cwp_options['password_reset_page']) ? absint($valuepack_cwp_options['password_reset_page']) : 0;
            if (empty($password_reset_page)) {
                return false;
            }

            $password_reset_url = get_permalink($password_reset_page);
            if (empty($password_reset_url)) {
                return false;
            }

            $key = get_password_reset_key($user_obj);
            if (is_wp_error($key)) {
                return false;
            }

            return add_query_arg(array(
                'action' => 'cubewp-reset-password',
                'key'    => sanitize_text_field($key),
                'login'  => rawurlencode($user_obj->user_login),
            ), esc_url($password_reset_url));
        }
    }

    if (!function_exists("value_pack_reset_password_fields")) {
        /**
         * Get password reset form fields
         *
         * @return array Field configuration
         */
        function value_pack_reset_password_fields()
        {
            return array(
                'user_pass'    => array(
                    'label'    => esc_html__('Enter New Password', 'valuepack-addons'),
                    'name'     => 'user_pass',
                    'type'     => 'password',
                    'required' => 1,
                    'validation_msg' => esc_html__('Please Enter New Password', 'valuepack-addons')
                ),
                'confirm_pass' => array(
                    'label'    => esc_html__('Confirm Password', 'valuepack-addons'),
                    'name'     => 'confirm_pass',
                    'type'     => 'password',
                    'required' => 1,
                    'validation_msg' => esc_html__('Please Enter New Password', 'valuepack-addons')
                ),
            );
        }
    }
}

if (!function_exists('value_pack_get_free_shipping_html')) {
    function value_pack_get_free_shipping_html()
    {
        // Ensure WooCommerce is active and cart is available
        if (!function_exists('WC') || !WC()->cart) {
            return;
        }
        // Get free shipping threshold, ensure it's valid
        $free_shipping_threshold = value_pack_get_free_shipping_threshold();
        if (!$free_shipping_threshold || !is_numeric($free_shipping_threshold) || $free_shipping_threshold <= 0) {
            return;
        }
        // Determine if cart prices include tax
        $include_tax = WC()->cart->display_prices_including_tax();
        // Get cart subtotal safely
        $_cart_subtotal = WC()->cart->get_subtotal();
        $discount_total = WC()->cart->get_discount_total();
        $discount_tax = WC()->cart->get_discount_tax();
        // Ensure numeric values and prevent errors
        $_cart_subtotal = is_numeric($_cart_subtotal) ? (float) $_cart_subtotal : 0;
        $discount_total = is_numeric($discount_total) ? (float) $discount_total : 0;
        $discount_tax = is_numeric($discount_tax) ? (float) $discount_tax : 0;
        // Get cart total after applying discounts
        if ($include_tax) {
            $_cart_subtotal = round($_cart_subtotal - ($discount_total + $discount_tax), wc_get_price_decimals());
        } else {
            $_cart_subtotal = round($_cart_subtotal - $discount_total, wc_get_price_decimals());
        }
        // Ensure subtotal is non-negative
        $_cart_subtotal = max(0, $_cart_subtotal);
        // Initialize variables
        $progress_percent = 0;
        $free_shipping_text = '';

        // Calculate remaining amount and progress
        if ($_cart_subtotal < $free_shipping_threshold) {
            $remaining_amount = $free_shipping_threshold - $_cart_subtotal;
            $progress_percent = ($_cart_subtotal / $free_shipping_threshold) * 100;
            $progress_percent = min($progress_percent, 100); // Ensure it doesn't exceed 100%

            $free_shipping_text = sprintf(
                /* translators: %s: remaining amount */
                __('Spend %1$s more and get free shipping!', 'valuepack-addons'),
                wc_price($remaining_amount)
            );
        } else {
            $progress_percent = 100;
            $free_shipping_text = __('Congratulations! You have unlocked free shipping!', 'valuepack-addons');
        }

        // Output the progress bar HTML
        ob_start();
        ?>
        <p><?php echo wp_kses_post($free_shipping_text); ?></p>
        <div class="vp-free-shipping-progress">
            <div class="vp-free-shipping-bar" style="width: <?php echo esc_attr($progress_percent); ?>%;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}



if (!function_exists('value_pack_get_related_products_by_cart_ids')) {
    function value_pack_get_related_products_by_cart_ids($count = -1)
    {
        if (!class_exists('WooCommerce') || !WC()->cart) {
            return [];
        }

        $cart_product_ids = [];
        foreach (WC()->cart->get_cart() as $cart_item) {
            $cart_product_ids[] = $cart_item['product_id'];
        }
        if (empty($cart_product_ids)) {
            return [];
        }
        $related_product_ids = [];
        foreach ($cart_product_ids as $product_id) {
            $related_ids = wc_get_related_products($product_id, $count);
            $related_product_ids = array_merge($related_product_ids, $related_ids);
        }

        $related_product_ids = array_unique($related_product_ids);
        $related_product_ids = array_diff($related_product_ids, $cart_product_ids);
        if ($count > 0) {
            $related_product_ids = array_slice($related_product_ids, 0, $count);
        }

        return $related_product_ids;
    }
}

if (!function_exists('value_pack_get_cart_ajax_related_items')) {
    function value_pack_get_cart_ajax_related_items()
    {
        $related_products = value_pack_get_related_products_by_cart_ids();
        ob_start();
        if (!empty($related_products)) { ?>
            <div class="vp-cart-may-also-like-container">
                <h2 class="vp-cart-may-also-like-heading pt-40"><?php echo esc_html__('YOU MAY ALSO LIKE', 'valuepack-addons'); ?></h2>
                <div class="vp-cart-may-also-like-items">
                    <?php foreach ($related_products as $product_id) :
                        $product = wc_get_product($product_id);
                        if (!$product) continue; ?>
                        <div class="vp-cart-may-also-like-item">
                            <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                <?php echo wp_kses_post($product->get_image()); ?>
                            </a>
                            <div class="wc-vp-vpack-like-title">
                                <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                    <h3 class="vp-cart-may-also-like-item-heading"><?php echo esc_html($product->get_name()); ?></h3>
                                </a>
                                <p class="vp-cart-may-also-like-item-value"><?php echo wp_kses_post($product->get_price_html()); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php
            $have_items = true;
        } else {
            $have_items = false;
        };
        $items_html = ob_get_clean();
        wp_send_json([
            'items_ html' => $items_html,
            'have_items' =>  $have_items,
        ]);
        wp_die();
    }
    add_action('wp_ajax_value_pack_get_cart_ajax_related_items', 'value_pack_get_cart_ajax_related_items');
    add_action('wp_ajax_nopriv_value_pack_get_cart_ajax_related_items', 'value_pack_get_cart_ajax_related_items');
}

if (!function_exists('value_pack_get_free_shipping_threshold')) {
    function value_pack_get_free_shipping_threshold()
    {
        if (!class_exists('WC_Shipping_Zones')) {
            return false;
        }

        $shipping_zones = WC_Shipping_Zones::get_zones();

        foreach ($shipping_zones as $zone) {
            foreach ($zone['shipping_methods'] as $method) {
                if ($method->id === 'free_shipping' && !empty($method->min_amount)) {
                    return floatval($method->min_amount);
                }
            }
        }

        // Check for "Free Shipping" in the default zone
        $default_zone = new WC_Shipping_Zone(0);
        $default_methods = $default_zone->get_shipping_methods();

        foreach ($default_methods as $method) {
            if ($method->id === 'free_shipping' && !empty($method->min_amount)) {
                return floatval($method->min_amount);
            }
        }

        return false;
    }
}

if (!function_exists('value_pack_generate_mini_cart_html')) {
    /**
     * Generates the HTML for the cart popup based on the selected style.
     *
     * @param string $cart_style The style of the cart ('style_1', 'style_2', or 'style_3').
     * @return string The generated HTML for the cart popup.
     */
    function value_pack_generate_mini_cart_html($cart_style)
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        // Get cart subtotal and item count
        $cart_subtotal = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_subtotal() : wc_price(0);
        $cart_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;

        $free_shipping_text = '';


        ob_start();
        $cart_items = WC()->cart->get_cart();
        $related_products = value_pack_get_related_products_by_cart_ids();
        if ($cart_style === 'style_1') {

        ?>
            <div class="vp-cart-popup" style="opacity: 0; visibility: hidden;">
                <div class="vp-cart-container vp-cart-popup-content">
                    <?php if (!empty($related_products)) { ?>
                        <div class="vp-cart-may-also-like-container">
                            <h2 class="vp-cart-may-also-like-heading pt-40"><?php echo esc_html__('YOU MAY ALSO LIKE', 'valuepack-addons'); ?></h2>
                            <div class="vp-cart-may-also-like-items">
                                <?php foreach ($related_products as $product_id) :
                                    $product = wc_get_product($product_id);
                                    if (!$product) continue; ?>
                                    <div class="vp-cart-may-also-like-item">
                                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                            <?php echo wp_kses_post($product->get_image()); ?>
                                        </a>
                                        <div class="wc-vp-vpack-like-title">
                                            <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                                <h3 class="vp-cart-may-also-like-item-heading"><?php echo esc_html($product->get_name()); ?></h3>
                                            </a>
                                            <p class="vp-cart-may-also-like-item-value"><?php echo wp_kses_post($product->get_price_html()); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="vp-cart-items-container">
                        <div class="vp-cart-header">
                            <div class="vp-cart-items-heading-and-icon">
                                <h1 class="vp-cart-items-heading"><?php echo esc_html__('SHOPPING BAG', 'valuepack-addons'); ?></h1>
                                <button class="vp-cart-items-close">
                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D" />
                                        <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Dynamic Cart Content -->
                        <div id="vp-cart-content">
                            <?php
                            if (is_array($cart_items) && !empty($cart_items)) {
                                foreach ($cart_items as $cart_item_key => $cart_item) {
                                    $product = $cart_item['data'];
                                    $product_id = $product->get_id();
                                    $product_name = $product->get_name();
                                    $product_price = (float) $product->get_price(); // Get price as float
                                    $product_permalink = get_permalink($product_id);
                                    $product_image = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0];
                                    $quantity = $cart_item['quantity'];


                                    $attributes_html = '';
                                    if ($product->is_type('variation')) {
                                        $variation_attributes = $product->get_variation_attributes();

                                        foreach ($variation_attributes as $attribute_name => $attribute_value) {
                                            $taxonomy = str_replace('attribute_', '', $attribute_name);
                                            $label = wc_attribute_label($taxonomy);
                                            $attributes_html .= esc_html($label) . ': ' . esc_html($attribute_value) . '<br>';
                                        }
                                    }

                                    $color = $product->get_attribute('pa_color');
                                    // Get size attribute
                                    $size = $product->get_attribute('pa_size');
                                    // Generate HTML for this cart item
                                    $item_html = '
                                    <div class="row">
                                        <div class="vp-cart-item col-12">
                                            <div class="vp-cart-item-image">
                                                <a href="' . esc_url($product_permalink) . '">
                                                    <img src="' . esc_url($product_image) . '" alt="' . esc_attr($product_name) . '" class="vp-cart-item-img">
                                                </a>
                                            </div>
                                            <div class="vp-cart-item-content">
                                                <a href="' . esc_url($product_permalink) . '">
                                                    <p class="vp-cart-item-title">' . $product_name . '</p>
                                                </a>
                                                <p class="vp-cart-item-price">' . wc_price($product_price) . '</p> <!-- Use wc_price for formatting -->
                                                <p class="vp-cart-item-attributes">
														' . $attributes_html . '
													</p>
                                                <div class="vp-cart-item-quantity">
                                                    <div class="vp-cart-item-quantity-selection" data-cart-item-key="' . esc_attr($cart_item_key) . '">
                                                        <i class="fa-solid fa-minus vp-cart-item-minus-icon"></i>
                                                        <p class="vp-cart-item-action-count">' . esc_html($quantity) . '</p>
                                                        <i class="fa-solid fa-plus vp-cart-item-plus-icon"></i>
                                                    </div>
                                                    <div class="vp-cart-item-remove-button">
                                                        <p class="vp-cart-item-shopping-remove" data-cart-item-key="' . esc_attr($cart_item_key) . '">Remove</p>
                                                        <div class="remove-spinner"></div>
                                                    </div>
                                                </div>
                                                <div class="quantity-spinner"></div>
                                            </div>
                                        </div>
                                    </div>';
                                    echo $item_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            } else {
                            }
                            ?>
                        </div>
                        <?php if ($cart_count > 0) : ?>
                            <div class="vp-cart-footer">
                                <div class="vp-cart-subtotal-text">
                                    <h6 class="vp-cart-subtotal-heading"><?php echo esc_html__('SUBTOTAL', 'valuepack-addons'); ?></h6>
                                    <p class="vp-cart-subtotal-value"><?php echo wp_kses_post($cart_subtotal); ?></p> <!-- Standardized subtotal display -->
                                </div>
                                <div class="vp-cart-checkout-buttons">
                                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="vp-cart-view-button"><?php echo esc_html__('View Cart', 'valuepack-addons'); ?></a>
                                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="vp-cart-checkout-button"><?php echo esc_html__('Checkout', 'valuepack-addons'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } elseif ($cart_style === 'style_2') { ?>
            <div class="vp-cart-popup" style="opacity: 0; visibility: hidden;">
                <div class="vp-cart-style-two-popup-container vp-cart-popup-content">
                    <div class="vp-cart-style-two-container">
                        <div class="vp-cart-style-two-header-and-content-wrapper">
                            <div class="vp-cart-style-two-header">
                                <div class="vp-cart-style-two-heading-and-icon">
                                    <h3 class="vp-cart-style-two-heading"><?php echo esc_html__('SHOPPING BAG', 'valuepack-addons'); ?></h3>
                                    <span class="vp-cart-items-close">
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D" />
                                            <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D" />
                                        </svg>
                                    </span>
                                </div>
                                <?php if ($cart_count > 0): ?>
                                    <div class="vp-cart-style-two-free-shipping-heading vp-free-shipping-container">
                                        <?php echo value_pack_get_free_shipping_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Dynamic Cart Content -->
                            <div class="vp-cart-content-container">
                                <div id="vp-cart-content">
                                    <?php foreach ($cart_items as $cart_item_key => $cart_item) {
                                        $product = $cart_item['data'];
                                        $product_id = $product->get_id();
                                        $product_name = $product->get_name();
                                        $product_price = (float) $product->get_price(); // Get price as float
                                        $product_permalink = get_permalink($product_id);
                                        $product_image = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0];
                                        $quantity = $cart_item['quantity'];

                                        $color = $product->get_attribute('pa_color');
                                        // Get size attribute
                                        $size = $product->get_attribute('pa_size');

                                        $attributes_html = '';
                                        if ($product->is_type('variation')) {
                                            $variation_attributes = $product->get_variation_attributes();

                                            foreach ($variation_attributes as $attribute_name => $attribute_value) {
                                                $taxonomy = str_replace('attribute_', '', $attribute_name);
                                                $label = wc_attribute_label($taxonomy);
                                                $attributes_html .= esc_html($label) . ': ' . esc_html($attribute_value) . '<br>';
                                            }
                                        }




                                        // Generate HTML for this cart item
                                        $item_html = '
                                    <div class="row">
                                        <div class="vp-cart-item col-12">
                                            <div class="vp-cart-item-image">
                                                <a href="' . esc_url($product_permalink) . '">
                                                    <img src="' . esc_url($product_image) . '" alt="' . esc_attr($product_name) . '" class="vp-cart-item-img">
                                                </a>
                                            </div>
                                            <div class="vp-cart-item-content">
                                                <a href="' . esc_url($product_permalink) . '">
                                                    <p class="vp-cart-item-title">' . $product_name . '</p>
                                                </a>
                                                <p class="vp-cart-item-price">' . wc_price($product_price) . '</p> <!-- Use wc_price for formatting -->
                                               <p class="vp-cart-item-attributes">
													' . $attributes_html . '
												</p>

                                                <div class="vp-cart-item-quantity">
                                                    <div class="vp-cart-item-quantity-selection" data-cart-item-key="' . esc_attr($cart_item_key) . '">
                                                        <i class="fa-solid fa-minus vp-cart-item-minus-icon"></i>
                                                        <p class="vp-cart-item-action-count">' . esc_html($quantity) . '</p>
                                                        <i class="fa-solid fa-plus vp-cart-item-plus-icon"></i>
                                                    </div>
                                                    <div class="vp-cart-item-remove-button">
                                                        <p class="vp-cart-item-shopping-remove" data-cart-item-key="' . esc_attr($cart_item_key) . '">Remove</p>
                                                        <div class="remove-spinner"></div>
                                                    </div>
                                                </div>
                                                <div class="quantity-spinner"></div>
                                            </div>
                                        </div>
                                    </div>';
                                        echo $item_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    }
                                    ?>
                                </div>
                                <?php if (!empty($related_products)) { ?>
                                    <div class="vp-cart-may-also-like-container">
                                        <h2 class="vp-cart-may-also-like-heading pt-40"><?php echo esc_html__('YOU MAY ALSO LIKE', 'valuepack-addons'); ?></h2>
                                        <div class="vp-cart-may-also-like-items">
                                            <?php foreach ($related_products as $product_id) :
                                                $product = wc_get_product($product_id);
                                                if (!$product) continue; ?>
                                                <div class="vp-cart-may-also-like-item">
                                                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                                        <?php echo wp_kses_post($product->get_image()); ?>
                                                    </a>
                                                    <div class="wc-vp-vpack-like-title">
                                                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                                            <h3 class="vp-cart-may-also-like-item-heading"><?php echo esc_html($product->get_name()); ?></h3>
                                                        </a>
                                                        <p class="vp-cart-may-also-like-item-value"><?php echo wp_kses_post($product->get_price_html()); ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($cart_count > 0) : ?>
                            <div class="vp-cart-style-two-footer">
                                <div class="vp-cart-style-two-subtotal-text">
                                    <h6><?php echo esc_html__('SUBTOTAL:', 'valuepack-addons'); ?></h6>
                                    <p class="vp-cart-subtotal-value"><?php echo wp_kses_post($cart_subtotal); ?></p> <!-- Standardized subtotal display -->
                                </div>
                                <div class="vp-cart-style-two-checkout-buttons">
                                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="vp-cart-style-two-view-button"><?php echo esc_html__('View Cart', 'valuepack-addons'); ?></a>
                                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="vp-cart-style-two-checkout-button"><?php echo esc_html__('Checkout', 'valuepack-addons'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } elseif ($cart_style === 'style_3') { ?>
            <div class="vp-cart-popup cart-style3" style="opacity: 0; visibility: hidden;">
                <div class="vp-cart-style-two-popup-container vp-cart-popup-content">
                    <div class="vp-cart-style-two-container">
                        <div class="vp-cart-style-three-header-and-content-wrapper">
                            <div class="vp-cart-style-three-header">
                                <div class="vp-cart-style-two-heading-and-icon">
                                    <h3 class="vp-cart-style-two-heading"> <?php echo esc_html__('SHOPPING BAG', 'valuepack-addons') ?> </h3>
                                    <span class="vp-cart-items-close">
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D" />
                                            <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D" />
                                        </svg>
                                    </span>
                                </div>
                                <?php if ($cart_count > 0): ?>
                                    <div class="vp-cart-style-two-free-shipping-heading vp-free-shipping-container">
                                        <?php echo value_pack_get_free_shipping_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Dynamic Cart Content -->
                            <div id="vp-cart-content">
                                <?php foreach ($cart_items as $cart_item_key => $cart_item) {

                                    $product = $cart_item['data'];
                                    $product_id = $product->get_id();
                                    $product_name = $product->get_name();
                                    $product_price = (float) $product->get_price(); // Get price as float
                                    $product_permalink = get_permalink($product_id);
                                    $product_image = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0];
                                    $quantity = $cart_item['quantity'];

                                    $color = $product->get_attribute('pa_color');
                                    // Get size attribute
                                    $size = $product->get_attribute('pa_size');


                                    $attributes_html = '';
                                    if ($product->is_type('variation')) {
                                        $variation_attributes = $product->get_variation_attributes();

                                        foreach ($variation_attributes as $attribute_name => $attribute_value) {
                                            $taxonomy = str_replace('attribute_', '', $attribute_name);
                                            $label = wc_attribute_label($taxonomy);
                                            $attributes_html .= esc_html($label) . ': ' . esc_html($attribute_value) . '<br>';
                                        }
                                    }



                                    // Generate HTML for this cart item
                                    $item_html = '
                                    <div class="row">
                                        <div class="vp-cart-item col-12">
                                            <div class="vp-cart-item-image">
                                                <a href="' . esc_url($product_permalink) . '">
                                                    <img src="' . esc_url($product_image) . '" alt="' . esc_attr($product_name) . '" class="vp-cart-item-img">
                                                </a>
                                            </div>
                                            <div class="vp-cart-item-content">
                                                <a href="' . esc_url($product_permalink) . '">
                                                    <p class="vp-cart-item-title">' . $product_name . '</p>
                                                </a>
                                                <p class="vp-cart-item-price">' . wc_price($product_price) . '</p> <!-- Use wc_price for formatting -->
												  <p class="vp-cart-item-attributes">
														' . $attributes_html . '
													</p>

												<div class="vp-cart-item-quantity">
                                                    <div class="vp-cart-item-quantity-selection" data-cart-item-key="' . esc_attr($cart_item_key) . '">
                                                        <i class="fa-solid fa-minus vp-cart-item-minus-icon"></i>
                                                        <p class="vp-cart-item-action-count">' . esc_html($quantity) . '</p>
                                                        <i class="fa-solid fa-plus vp-cart-item-plus-icon"></i>
                                                    </div>
                                                    <div class="vp-cart-item-remove-button">
                                                        <p class="vp-cart-item-shopping-remove" data-cart-item-key="' . esc_attr($cart_item_key) . '">Remove</p>
                                                        <div class="remove-spinner"></div>
                                                    </div>
                                                </div>
                                                <div class="quantity-spinner"></div>
                                            </div>
                                        </div>
                                    </div>';
                                    echo $item_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                                ?>
                            </div>
                        </div>
                        <?php if ($cart_count > 0): ?>
                            <div class="vp-cart-style-three-footer">
                                <div class="vp-cart-style-two-subtotal-text">
                                    <h6> <?php echo esc_html__('SUBTOTAL:', 'valuepack-addons') ?></h6>
                                    <p class="vp-cart-subtotal-value"><?php echo wp_kses_post(wc_price($cart_subtotal)) ?></p>
                                </div>
                                <div class="vp-cart-style-two-checkout-buttons">
                                    <a href="<?php echo esc_url(wc_get_cart_url()) ?>" class="vp-cart-style-two-view-button"><?php echo esc_html__('View Cart', 'valuepack-addons') ?></a>
                                    <a href="<?php echo esc_url(wc_get_checkout_url()) ?>" class="vp-cart-style-two-checkout-button"><?php echo esc_html__('Checkout', 'valuepack-addons') ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }

        return ob_get_clean();
    }
}

if (!function_exists('value_pack_get_cart_ajax_data')) {
    /**
     * Handles AJAX request for updating the mini cart.
     */
    function value_pack_get_cart_ajax_data()
    {
        // Check if doing AJAX and if required parameters are present
        if (!wp_doing_ajax() || !isset($_POST['cart_style'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            wp_send_json_error(['error' => 'Invalid request']);
            wp_die();
        }

        // Sanitize POST data
        $cart_style = sanitize_text_field(wp_unslash($_POST['cart_style'])); // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Generate cart HTML
        $items_html = value_pack_generate_mini_cart_html($cart_style);

        // Send the JSON response
        wp_send_json_success(['items_html' => $items_html]);
    }
    add_action('wp_ajax_get_cart_ajax_data', 'value_pack_get_cart_ajax_data');
    add_action('wp_ajax_nopriv_get_cart_ajax_data', 'value_pack_get_cart_ajax_data');
}




if (! function_exists('value_pack_update_cart_item_quantity')) {
    function value_pack_update_cart_item_quantity()
    {
        $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field(wp_unslash($_POST['cart_item_key'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $quantity = isset($_POST['quantity']) ? intval(sanitize_text_field(wp_unslash($_POST['quantity']))) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        if ($quantity > 0 && !empty($cart_item_key)) {
            WC()->cart->set_quantity($cart_item_key, $quantity);
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    add_action('wp_ajax_update_cart_item_quantity', 'value_pack_update_cart_item_quantity');
    add_action('wp_ajax_nopriv_update_cart_item_quantity', 'value_pack_update_cart_item_quantity');
}

if (! function_exists('value_pack_remove_cart_item')) {
    function value_pack_remove_cart_item()
    {
        $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field(wp_unslash($_POST['cart_item_key'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        if (!empty($cart_item_key)) {
            WC()->cart->remove_cart_item($cart_item_key);
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
    add_action('wp_ajax_remove_cart_item', 'value_pack_remove_cart_item');
    add_action('wp_ajax_nopriv_remove_cart_item', 'value_pack_remove_cart_item');
}



if (!function_exists('value_pack_woocommerce_live_search')) {
    function value_pack_woocommerce_live_search()
    {
        // Check if 'searchStyle' is set in $_POST
        $searchStyle = isset($_POST['searchStyle']) ? sanitize_text_field(wp_unslash($_POST['searchStyle'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $keyword = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        $cache_key = 'woocommerce_live_search_' . md5($keyword);

        if (!empty($keyword)) {
            $results = get_transient($cache_key);
            if ($results === false) {
                $results = value_pack_search_products($keyword, $searchStyle);
                set_transient($cache_key, $results, 5 * MINUTE_IN_SECONDS);
            }
        } else {
            $results = value_pack_get_initial_suggestions($searchStyle);
        }

        wp_send_json($results);
    }
    // Add AJAX actions
    add_action('wp_ajax_woocommerce_live_search', 'value_pack_woocommerce_live_search');
    add_action('wp_ajax_nopriv_woocommerce_live_search', 'value_pack_woocommerce_live_search');
}



if (!function_exists('value_pack_search_products')) {
    function value_pack_search_products($keyword, $searchStyle)
    {
        $products = [];
        $taxonomies = [];  // Store relevant taxonomies

        // Search products by keyword
        $args = [
            'post_type' => 'product',
            'posts_per_page' => 10,
            's' => $keyword,
            'no_found_rows' => true,
            'fields' => 'ids',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                // Collect product data
                $products[] = [
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'price' => $product->get_price_html(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
                ];

                // Collect relevant taxonomies for the matched products
                $product_categories = wp_get_post_terms(get_the_ID(), 'product_cat');
                foreach ($product_categories as $category) {
                    $taxonomies[] = $category->name;
                }
            }
        }

        wp_reset_postdata();

        // Additionally, search directly for taxonomies that match the keyword
        $taxonomy_query = [
            'taxonomy' => 'product_cat',
            'name__like' => $keyword,
            'fields' => 'names',
        ];

        $matched_taxonomies = get_terms($taxonomy_query);
        if (!is_wp_error($matched_taxonomies)) {
            foreach ($matched_taxonomies as $taxonomy_name) {
                $taxonomies[] = $taxonomy_name;
            }
        }

        // Ensure uniqueness and sort taxonomies
        $taxonomies = array_unique($taxonomies);
        shuffle($taxonomies);

        // If no products match, but taxonomies do, show taxonomies in suggestions
        if (empty($products) && !empty($taxonomies)) {
            $suggestions = $taxonomies;
        } elseif (empty($products)) {
            $suggestions = [];
        } else {
            // If products match, suggest both products and matching taxonomies
            $suggestions = $taxonomies;
        }

        return [
            'products' => value_pack_generate_products_html($products, $searchStyle),
            'suggestions' => value_pack_generate_suggestions_html($suggestions),
            'total' => count($products),
        ];
    }
}

if (!function_exists('value_pack_get_initial_suggestions')) {
    function value_pack_get_initial_suggestions($searchStyle = 'style_1')
    {
        $products = [];
        $taxonomies = [];

        $args = [
            'post_type' => 'product',
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'fields' => 'ids',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                // Collect product data
                $products[] = [
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'price' => $product->get_price_html(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
                ];

                // Collect relevant taxonomies for the products
                $product_categories = wp_get_post_terms(get_the_ID(), 'product_cat');
                foreach ($product_categories as $category) {
                    $taxonomies[] = $category->name;
                }
            }
        }

        wp_reset_postdata();

        // Ensure uniqueness of taxonomies and shuffle them
        $taxonomies = array_unique($taxonomies);
        shuffle($taxonomies);

        return [
            'products' => value_pack_generate_products_html($products, $searchStyle),
            'suggestions' => value_pack_generate_suggestions_html($taxonomies),  // Display taxonomies
            'total' => $query->post_count,
        ];
    }
}

if (!function_exists('value_pack_generate_products_html')) {
    function value_pack_generate_products_html($products, $searchStyle)
    {
        ob_start();

        foreach ($products as $product) {
            $product_link = $product['link'];
            $product_image = $product['image'];
            $product_title = $product['title'];
            $product_price = $product['price'];

            if ($searchStyle === 'style_1') {
            ?>
                <div class="content-item">
                    <a href="<?php echo esc_url($product_link); ?>">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                    </a>
                    <div class="content-item-text">
                        <a href="<?php echo esc_url($product_link); ?>">
                            <h3 class="content-heading"><?php echo esc_html($product['title']); ?></h3>
                        </a>
                        <p class="content-value"><?php echo wp_kses_post($product_price); ?></p>
                    </div>
                </div>
            <?php } elseif ($searchStyle === 'style_2') { ?>
                <div class="result-post">
                    <div class="result-post-thumb">
                        <a href="<?php echo esc_url($product_link); ?>">
                            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        </a>
                    </div>
                    <div class="result-post-content">
                        <a href="<?php echo esc_url($product_link); ?>">
                            <h4><?php echo esc_html($product['title']); ?></h4>
                        </a>
                        <?php echo wp_kses_post($product_price); ?>
                    </div>
                </div>
            <?php } elseif ($searchStyle === 'style_3') { ?>
                <div class="col-lg-3 col-md-6 col-sm-12 m-0 p-0 search-results-style-3-card">
                    <div class="main-content-image">
                        <a href="<?php echo esc_url($product_link); ?>">
                            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        </a>
                        <div class="main-content-svg">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_3126_4137)">
                                    <path d="M2.95889 11.1054C2.83639 11.1054 2.71474 11.0686 2.61092 10.9962C2.41753 10.8612 2.32719 10.629 2.37982 10.405L3.09914 7.35171L0.659669 5.29018C0.480351 5.13934 0.411849 4.90045 0.48547 4.68187C0.55909 4.4637 0.759394 4.30923 0.99655 4.28811L4.22427 4.00573L5.50039 1.128C5.59448 0.916571 5.80878 0.780029 6.04687 0.780029C6.28497 0.780029 6.49926 0.916571 6.59336 1.12751L7.86948 4.00573L11.0967 4.28811C11.3343 4.30874 11.5347 4.4637 11.6083 4.68187C11.6819 4.90004 11.6138 5.13934 11.4345 5.29018L8.99503 7.35129L9.71435 10.4045C9.76707 10.629 9.67664 10.8612 9.48334 10.9958C9.29045 11.1305 9.03325 11.1408 8.83013 11.0232L6.04687 9.42046L3.26361 11.0241C3.16952 11.078 3.06467 11.1054 2.95889 11.1054ZM6.04687 8.73003C6.15265 8.73003 6.25741 8.7574 6.35159 8.81125L8.97831 10.3251L8.29943 7.44328C8.25098 7.23818 8.32323 7.02404 8.48771 6.88528L10.7911 4.93867L7.74365 4.672C7.52424 4.65268 7.33554 4.51976 7.2498 4.32493L6.04687 1.60923L4.84249 4.32535C4.7577 4.51886 4.56899 4.65178 4.35009 4.6711L1.30221 4.93777L3.60553 6.88437C3.77051 7.02355 3.84268 7.23728 3.7938 7.44287L3.11543 10.3246L5.74215 8.81125C5.83625 8.7574 5.94109 8.73003 6.04687 8.73003ZM4.19961 4.06139C4.19961 4.06139 4.19961 4.06188 4.1991 4.06229L4.19961 4.06139ZM7.89319 4.06007L7.89371 4.06098C7.89371 4.06048 7.89371 4.06048 7.89319 4.06007Z" fill="#1D1D1D" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_3126_4137">
                                        <rect width="11.1812" height="10.7744" fill="white" transform="translate(0.456299 0.548828)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <div class="main-content-text">
                        <p class="product-categories">NEW ARRIVAL</p>
                        <a href="<?php echo esc_url($product_link); ?>">
                            <h4><?php echo esc_html($product['title']); ?></h4>
                        </a>
                        <p><?php echo wp_kses_post($product_price); ?></p>
                    </div>
                </div>

            <?php } elseif ($searchStyle === 'style_4') { ?>
                <div class="vp-search-product-item-style-4">
                    <a href="<?php echo esc_url($product_link); ?>">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                    </a>
                    <div class="vp-search-style-4-product-content">
                        <a href="<?php echo esc_url($product_link); ?>">
                            <h4><?php echo esc_html($product['title']); ?></h4>
                        </a>
                        <p><?php echo wp_kses_post($product_price); ?></p>
                    </div>
                </div>

            <?php } elseif ($searchStyle === 'style_5') { ?>
                <div class="col-lg-3 col-md-6 col-sm-12 vp-search-style-5-product-item">
                    <div class="vp-search-style-5-product-content">
                        <a href="<?php echo esc_url($product_link); ?>">
                            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        </a>
                        <a href="<?php echo esc_url($product_link); ?>">
                            <h4><?php echo esc_html($product['title']); ?></h4>
                        </a>
                        <p><?php echo wp_kses_post($product_price); ?></p>
                    </div>
                </div>
                <?php
            }
        }

        return ob_get_clean();
    }
}

if (!function_exists('value_pack_generate_suggestions_html')) {
    function value_pack_generate_suggestions_html($suggestions)
    {
        ob_start();

        if (!empty($suggestions)) {
            foreach ($suggestions as $suggestion) {
                $term = get_term_by('name', $suggestion, 'product_cat');
                if ($term && !is_wp_error($term)) {
                    $term_link = get_term_link($term);
                ?>
                    <li>
                        <a href="<?php echo esc_url($term_link); ?>">
                            <?php echo esc_html($suggestion); ?>
                        </a>
                    </li>
            <?php
                }
            }
        } else {
            ?>
            <li>No Keyword Match</li>
        <?php
        }

        return ob_get_clean();
    }
}


if (! function_exists('value_pack_template_single_add_to_cart')) {
    function value_pack_template_single_add_to_cart($product)
    {
        do_action('woocommerce_' . $product->get_type() . '_add_to_cart'); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
    }
}



if (! function_exists('value_pack_add_to_cart_cb')) {
    function value_pack_add_to_cart_cb()
    {
        $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $quantity = isset($_POST['quantity']) ? wc_stock_amount(sanitize_text_field(wp_unslash($_POST['quantity']))) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        $cart_style = isset($_POST['cart_style']) ? sanitize_text_field(wp_unslash($_POST['cart_style'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Attempt to add the product to the cart
        if ($variation_id) {
            $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
        } else {
            $added = WC()->cart->add_to_cart($product_id, $quantity);
        }

        // Prepare the AJAX response
        if ($added) {
            ob_start(); // Start buffering output
            echo value_pack_generate_mini_cart_html($cart_style); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            $items_html = ob_get_clean();

            $response = array(
                'success' => true,
                'message' => __('Product added to cart successfully!', 'valuepack-addons'),
                'items_html' => $items_html,
            );
        } else {
            $response = array(
                'success' => false,
                'message' => __('Failed to add product to cart.', 'valuepack-addons'),
            );
        }

        wp_send_json($response);
        wp_die();
    }
    add_action('wp_ajax_value_pack_add_to_cart', 'value_pack_add_to_cart_cb');
    add_action('wp_ajax_nopriv_value_pack_add_to_cart', 'value_pack_add_to_cart_cb');
}



if (! function_exists('value_pack_get_post_featured_image')) {
    function value_pack_get_post_featured_image($post_id = 0, $id_only = false, $size = 'medium')
    {
        $return = '';
        if (! $post_id) {
            $post_id = get_the_ID();
        }
        if (has_post_thumbnail($post_id)) {
            if ($id_only) {
                $return = get_post_thumbnail_id($post_id);
            } else {
                $return = get_the_post_thumbnail_url($post_id, $size);
            }
        }
        if (empty($return)) {
            $return = VALUE_PACK_PLUGIN_URL . 'assets/frontend/images/placeholder.png';
        }
        return $return;
    }
}

if (! function_exists('value_pack_get_socials_share')) {
    function value_pack_get_socials_share($ID = 0)
    {
        $url       = get_site_url();
        $title     = get_bloginfo();
        $thumbnail = get_site_icon_url();
        if ($ID != 0) {
            $url       = get_post_permalink($ID);
            $title     = get_the_title($ID);
            $thumbnail = value_pack_get_post_featured_image($ID);
        }
        $title       = str_replace(' ', '%20', $title);
        $twitterURL  = 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url;
        $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
        $pinterest   = 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title;
        $linkedin    = 'http://www.linkedin.com/shareArticle?mini=true&url=' . $url;
        $reddit      = 'https://www.reddit.com/login?dest=https%3A%2F%2Fwww.reddit.com%2Fsubmit%3Ftitle%3D' . $title . '%26url%3D' . $url;
        ob_start();
        ?>
        <div class="vp-social-shares-list d-flex justify-content-center align-items-center p-3">
            <a href="<?php echo esc_url($twitterURL); ?>" class="d-block mx-2"
                target="_blank">
                <i class="fa-brands fa-twitter m-0" aria-hidden="true"></i>
            </a>
            <a href="<?php echo esc_url($facebookURL); ?>"
                class="d-block mx-2"
                target="_blank">
                <i class="fa-brands fa-facebook m-0" aria-hidden="true"></i>
            </a>
            <a href="<?php echo esc_url($pinterest); ?>" class="d-block mx-2"
                target="_blank">
                <i class="fa-brands fa-pinterest m-0" aria-hidden="true"></i>
            </a>
            <a href="<?php echo esc_url($linkedin); ?>" class="d-block mx-2"
                target="_blank">
                <i class="fa-brands fa-linkedin m-0" aria-hidden="true"></i>
            </a>
            <a href="<?php echo esc_url($reddit); ?>" class="d-block mx-2"
                target="_blank">
                <i class="fa-brands fa-reddit m-0" aria-hidden="true"></i>
            </a>
        </div>
    <?php
        $output = ob_get_clean();

        return apply_filters('value_pack_item_social_share', $output, $ID,);
    }
}

if (!function_exists('value_pack_get_woocommerce_product_hooks')) {
    function value_pack_get_woocommerce_product_hooks()
    {
        // Array to store WooCommerce product detail page hooks and their titles
        $hooks = array(
            'woocommerce_before_single_product'             => 'Before Single Product',
            'woocommerce_before_single_product_summary'     => 'Before Single Product Summary',
            'woocommerce_single_product_summary'            => 'Single Product Summary',
            'woocommerce_after_single_product_summary'      => 'After Single Product Summary',
            'woocommerce_after_single_product'              => 'After Single Product',
            'woocommerce_product_thumbnails'                => 'Product Thumbnails',
            'woocommerce_product_gallery_thumbnail'         => 'Product Gallery Thumbnail',
            'woocommerce_share'                             => 'Product Sharing',
            'woocommerce_template_single_title'             => 'Single Product Title',
            'woocommerce_template_single_rating'            => 'Single Product Rating',
            'woocommerce_template_single_price'             => 'Single Product Price',
            'woocommerce_template_single_excerpt'           => 'Single Product Excerpt',
            'woocommerce_template_single_add_to_cart'       => 'Single Product Add to Cart',
            'woocommerce_template_single_meta'              => 'Single Product Meta',
            'woocommerce_template_single_sharing'           => 'Single Product Sharing',
            'woocommerce_product_meta_start'                => 'Product Meta Start',
            'woocommerce_product_meta_end'                  => 'Product Meta End',
            'woocommerce_review_before_comment_meta'        => 'Review Before Comment Meta',
            'woocommerce_review_meta'                       => 'Review Meta',
            'woocommerce_review_after_comment_meta'         => 'Review After Comment Meta',
            'woocommerce_review_before'                     => 'Review Before',
            'woocommerce_review_before_comment_text'        => 'Review Before Comment Text',
            'woocommerce_review_comment_text'               => 'Review Comment Text',
            'woocommerce_review_after_comment_text'         => 'Review After Comment Text',
            'woocommerce_product_tabs'                      => 'Product Tabs',
            'woocommerce_product_tab_panels'                => 'Product Tab Panels',
            'woocommerce_after_single_product_tabs'         => 'After Product Tabs',
            'woocommerce_upsell_display'                    => 'Upsell Products',
            'woocommerce_output_related_products'           => 'Related Products',
        );

        return $hooks;
    }
}

/**
 * Track product views.
 */
if (!function_exists('value_pack_track_product_views')) {
    function value_pack_track_product_views()
    {
        if (!is_singular('product')) {
            return;
        }

        global $post;

        $viewed_products = array();

        if (!empty($_COOKIE['value_pack_recently_viewed'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            // Convert comma-separated string to array
            $cookie_ids = explode(',', sanitize_text_field(wp_unslash($_COOKIE['value_pack_recently_viewed']))); // phpcs:ignore WordPress.Security.NonceVerification.Missing

            // Ensure integers, then use IDs as keys and values
            foreach ($cookie_ids as $id) {
                $id = intval($id);
                if ($id) {
                    $viewed_products[$id] = $id;
                }
            }
        }

        // Remove current ID if exists
        unset($viewed_products[$post->ID]);

        // Add current ID at the end (as key and value)
        $viewed_products[$post->ID] = $post->ID;

        // Optional: limit to last 10 — to keep only last 10 keys
        if (count($viewed_products) > 10) {
            // Keep only the last 10 keys
            $viewed_products = array_slice($viewed_products, -10, null, true);
        }
        // Save the keys back as comma-separated string
        setcookie(
            'value_pack_recently_viewed',
            implode(',', array_keys($viewed_products)),
            0,
            '/'
        );
    }

    add_action('template_redirect', 'value_pack_track_product_views', 20);
}


if (!function_exists('value_pack_add_size_guide_options_advanced')) {
    function value_pack_add_size_guide_options_advanced()
    {
        global $post;
        $size_guide_image = get_post_meta($post->ID, 'vp_size_guide_image', true);
    ?>
        <div class="options_group">
            <p class="form-field">
                <label for="vp_size_guide_image"><?php esc_html_e('Size Guide Image', 'valuepack-addons'); ?></label>
                <input type="hidden" id="vp_size_guide_image" name="vp_size_guide_image" value="<?php echo esc_attr($size_guide_image); ?>" />
                <button type="button" class="vp_upload_image button"><?php esc_html_e('Upload/Add image', 'valuepack-addons'); ?></button>
                <img id="vp_size_guide_image_preview" src="<?php echo esc_url($size_guide_image); ?>" style="max-width: 150px; display: <?php $size_guide_image ? 'block' : 'none'; ?>;" />
                <button type="button" class="vp_remove_image button" style="display: <?php $size_guide_image ? 'inline-block' : 'none'; ?>;"><?php esc_html_e('Remove image', 'valuepack-addons'); ?></button>
            </p>
        </div>
<?php
    }
    add_action('woocommerce_product_options_advanced', 'value_pack_add_size_guide_options_advanced', 12);
}

if (!function_exists('value_pack_save_size_guide_image_upload_field')) {
    function value_pack_save_size_guide_image_upload_field($post_id)
    {
        if (isset($_POST['vp_size_guide_image'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            update_post_meta($post_id, 'vp_size_guide_image', esc_url_raw($_POST['vp_size_guide_image'])); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.EscapeOutput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        }
    }
    add_action('woocommerce_process_product_meta', 'value_pack_save_size_guide_image_upload_field');
}

if (!function_exists('value_pack_get_close_icon_svg')) {
    function value_pack_get_close_icon_svg()
    {
        return '<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D"/>
        <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D"/>
    </svg>
    ';
    }
}
if (! function_exists('value_pack_add_multiple_products_to_cart')) {
    function value_pack_add_multiple_products_to_cart()
    {

        if (! isset($_POST['product_ids'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            wp_send_json(array(
                'success' => false,
                'text'    => esc_html__('No product IDs provided.', 'valuepack-addons'),
            ));
        }

        $raw_ids = sanitize_text_field(wp_unslash($_POST['product_ids'])); // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $product_entries = explode(',', $raw_ids);

        if (empty($product_entries)) {
            wp_send_json(array(
                'success' => false,
                'text'    => esc_html__('Invalid product data.', 'valuepack-addons'),
            ));
        }

        $products_added = 0;

        foreach ($product_entries as $entry) {
            $entry = trim($entry);

            // Check for variation format (productID|variationID)
            if (strpos($entry, '|') !== false) {
                list($product_id, $variation_id) = array_map('absint', explode('|', $entry));

                if (! $product_id || ! $variation_id) continue;

                $variation = wc_get_product($variation_id);
                $product   = wc_get_product($product_id);

                if ($product && $variation && $variation->is_type('variation') && $variation->is_in_stock() && $variation->get_price() > 0) {
                    $variation_obj = new WC_Product_Variation($variation_id);
                    $attributes = $variation_obj->get_attributes();

                    WC()->cart->add_to_cart($product_id, 1, $variation_id, $attributes);
                    $products_added++;
                }
            } else {
                // Simple product or fallback to first variation
                $product_id = absint($entry);
                if (! $product_id) continue;

                $product = wc_get_product($product_id);
                if (! $product) continue;

                if ($product->is_type('simple') && $product->is_in_stock() && $product->get_price() > 0) {
                    WC()->cart->add_to_cart($product_id, 1);
                    $products_added++;
                } elseif ($product->is_type('variable')) {
                    $available_variations = $product->get_available_variations();
                    if (! empty($available_variations)) {
                        $first_variation = $available_variations[0];
                        $variation_id = $first_variation['variation_id'];
                        $variation = wc_get_product($variation_id);

                        if ($variation && $variation->is_in_stock() && $variation->get_price() > 0) {
                            WC()->cart->add_to_cart($product_id, 1, $variation_id, array());
                            $products_added++;
                        }
                    }
                }
            }
        }

        if ($products_added > 0) {
            wp_send_json(array(
                'success' => true,
                'url'     => wc_get_cart_url(),
            ));
        } else {
            wp_send_json(array(
                'success' => false,
                'text'    => esc_html__('No products were added to the cart.', 'valuepack-addons'),
            ));
        }
    }

    add_action('wp_ajax_add_multiple_products_to_cart', 'value_pack_add_multiple_products_to_cart');
    add_action('wp_ajax_nopriv_add_multiple_products_to_cart', 'value_pack_add_multiple_products_to_cart');
}



if (! function_exists('value_pack_get_woocommerce_category_ids')) {
    function value_pack_get_woocommerce_category_ids()
    {
        $taxonomy = 'product_cat';

        // Get all terms for the specified taxonomy
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'fields'     => 'all', // Ensures full term objects are returned
        ]);

        if (empty($terms) || is_wp_error($terms)) {
            return [];
        }

        $options = [];

        foreach ($terms as $term) {
            if (is_object($term)) {
                $options[$term->term_id] = $term->name;
            }
        }

        return $options;
    }
    add_action('init', 'value_pack_get_woocommerce_category_ids');
}

if (! function_exists('value_pack_get_woocommerce_category_slugs')) {
    function value_pack_get_woocommerce_category_slugs()
    {
        $taxonomy = 'product_cat';

        // Get all terms for the specified taxonomy
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'fields'     => 'all', // Ensures full term objects are returned
        ]);

        if (empty($terms) || is_wp_error($terms)) {
            return [];
        }

        $options = [];

        foreach ($terms as $term) {
            if (is_object($term)) {
                $options[$term->slug] = $term->name;
            }
        }

        return $options;
    }
    add_action('init', 'value_pack_get_woocommerce_category_slugs');
}


if (!function_exists('value_pack_get_svg_content')) {
    function value_pack_get_svg_content($icon)
    {
        // If icon is array with 'url', fetch the content
        if (is_array($icon)) {
            if (isset($icon['url'])) {
                $response = wp_safe_remote_get($icon['url']);
                if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
                    return (string) wp_remote_retrieve_body($response);
                }
                return '';
            } elseif (isset($icon['value'])) {
                // In case value is itself an array
                return is_string($icon['value']) ? $icon['value'] : '';
            }
            return ''; // fallback for unexpected array shapes
        }

        // If icon is string, return it
        if (is_string($icon)) {
            return $icon;
        }

        return ''; // fallback
    }
}


/**
 * Get any one WooCommerce product ID dynamically.
 *
 * @return int|false Returns product ID if found, false if WooCommerce inactive or no products.
 */
if (! function_exists('value_pack_get_any_one_product_id')) {
    function value_pack_get_any_one_product_id()
    {
        // Check if WooCommerce is active
        if (! class_exists('WooCommerce')) {
            return false; // WooCommerce not installed or active
        }

        // Query to get one product ID
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'post_status'    => 'publish',
        );

        $products = get_posts($args);

        if (! empty($products)) {
            return $products[0]; // Return the first product ID
        }

        return false; // No products found
    }
}

if (! function_exists('value_pack_get_any_one_post_id')) {
    function value_pack_get_any_one_post_id($post_type)
    {
        // Query to get one post ID
        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'post_status'    => 'publish',
        );

        $posts = get_posts($args);

        if (! empty($posts)) {
            return $posts[0]; // Return the first post ID
        }

        return false; // No posts found
    }
}

if (! function_exists('value_pack_get_quoute_text_elementor_preview')) {
    function value_pack_get_quoute_text_elementor_preview()
    {
        // Check if Elementor is in edit mode
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return '<div style="text-align:center;margin:10px 0;font-weight:bold;">' . esc_html__('Real output depends on live user interaction. Preview the page for actual content.', 'valuepack-addons') . '</div><div style="width:100%;height:2px;background:#000;margin:10px 0;"></div>';
        }
        return;
    }
}


if (!function_exists('value_pack_switch_locale')) {
    function value_pack_switch_locale()
    {
        // Optional security check
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'value_pack_ajax')) {
            wp_send_json_error(['message' => 'Invalid security token.']);
        }

        $selected_value = isset($_POST['selected_value']) ? sanitize_text_field(wp_unslash($_POST['selected_value'])) : '';
        $switch_type    = isset($_POST['switch_type']) ? sanitize_text_field(wp_unslash($_POST['switch_type'])) : '';

        if ($switch_type === 'language') {
            // Set Polylang cookie
            if (function_exists('pll_set_language')) {
                setcookie('pll_language', $selected_value, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
            }

            // WPML redirect
            if (function_exists('icl_get_current_language')) {
                $url = apply_filters('wpml_permalink', home_url(), $selected_value);// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                wp_send_json_success([
                    'reload' => true,
                    'redirect_url' => $url
                ]);
            }
        }

        if ($switch_type === 'currency') {
            // Set WooCommerce Currency (Works with WOOCS)
            setcookie('woocommerce_currency', $selected_value, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        }

        wp_send_json_success(['reload' => true]);
    }

    add_action('wp_ajax_vpack_switch_locale', 'value_pack_switch_locale');
    add_action('wp_ajax_nopriv_vpack_switch_locale', 'value_pack_switch_locale');
}


if (!function_exists('value_pack_add_custom_template_options')) {
    function value_pack_add_custom_template_options($options)
    {
        $options['popup'] = 'Popup';
        return $options;
    }
    add_filter('cubewp/theme_builder/options/register', 'value_pack_add_custom_template_options');
}

if (!function_exists('value_pack_product_search_callback')) {
    function value_pack_product_search_callback()
    {
        check_ajax_referer('vp_product_search_nonce', 'nonce');
        $search = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
        $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 20,
            's'              => $search,
        ];
        if ($product_id) {
            $args['p'] = $product_id;
            unset($args['s']);
        }
        $query = new WP_Query($args);
        $results = [];
        foreach ($query->posts as $post) {
            $product = wc_get_product($post->ID);
            if ($product) {
                $results[] = [
                    'id'   => $post->ID,
                    'text' => $product->get_name(),
                ];
            }
        }
        wp_send_json_success(['results' => $results]);
    }
    add_action('wp_ajax_vp_product_search', 'value_pack_product_search_callback');
    add_action('wp_ajax_nopriv_vp_product_search', 'value_pack_product_search_callback');
}

/**
 * Get a list of WooCommerce products (ID => Title).
 *
 * @return array
 */
if (!function_exists('value_pack_get_woocommerce_products')) {
    function value_pack_get_woocommerce_products()
    {
        if (!class_exists('WooCommerce')) {
            return [];
        }
        $products = [];
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 15,
            'fields'         => 'ids', // Get only post IDs
        );
        $product_ids = get_posts($args);
        if ($product_ids) {
            foreach ($product_ids as $product_id) {
                $product = wc_get_product($product_id);
                if ($product) {
                    $products[$product_id] = $product->get_name();
                }
            }
        }
        return $products;
    }
}

