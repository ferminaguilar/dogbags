<?php
defined('ABSPATH') || exit;

/**
 * Get WooCommerce term IDs by slugs with caching
 */
if (! function_exists('cubewp_get_woocommerce_term_id_by_slug')) {
    function cubewp_get_woocommerce_term_id_by_slug($terms, $taxonomy = 'product_type') {
        static $term_cache = array();
        
        $cache_key = md5($terms . $taxonomy);
        if (isset($term_cache[$cache_key])) {
            return $term_cache[$cache_key];
        }

        $term_ids = array();
        $term_slugs = explode(',', sanitize_text_field($terms));

        if (!empty($term_slugs)) {
            foreach ($term_slugs as $term_slug) {
                $term_slug = sanitize_text_field($term_slug);
                $term = get_term_by('slug', $term_slug, $taxonomy);
                if ($term && !is_wp_error($term)) {
                    $term_ids[] = $term->term_id;
                }
            }
        }

        $result = implode(',', $term_ids);
        $term_cache[$cache_key] = $result;
        
        return $result;
    }
}

/**
 * Add WooCommerce settings section
 */
if (! function_exists('cubewp_woocommerce_settings')) {
    function cubewp_woocommerce_settings($sections) {
        $settings['cubewp_woocommerce'] = [
            'title'  => esc_html__('WooCommerce', 'cubewp-woocommerce'),
            'id'     => 'cubewp_woocommerce',
            'icon'   => 'dashicons-store',
            'fields' => [
                [
                    'id'      => 'cubewp_product_price_into_wallet',
                    'type'    => 'switch',
                    'title'   => esc_html__('Add Product Price Into Author Wallet', 'cubewp-woocommerce'),
                    'desc'    => esc_html__('If enabled, upon product sale, the system will add the product price to the author\'s wallet.', 'cubewp-woocommerce'),
                    'default' => '1',
                ],
            ]
        ];

        return array_merge($sections, $settings);
    }
    add_filter('cubewp/options/sections', 'cubewp_woocommerce_settings', 11);
}

/**
 * Get WooCommerce hook names and labels for theme builder with caching
 */
if (! function_exists('woo_hoooooks')) {
    function woo_hoooooks() {
        static $hooks = null;
        
        if ($hooks !== null) {
            return $hooks;
        }

        $hooks = [
            'woocommerce_before_single_product' => esc_html__('Before Single Product', 'cubewp-woocommerce'),
            'woocommerce_before_single_product_summary' => esc_html__('Before Single Product Summary', 'cubewp-woocommerce'),
            'woocommerce_single_product_summary' => esc_html__('Single Product Summary', 'cubewp-woocommerce'),
            'woocommerce_after_single_product_summary' => esc_html__('After Single Product Summary', 'cubewp-woocommerce'),
            'woocommerce_after_single_product' => esc_html__('After Single Product', 'cubewp-woocommerce'),
            'woocommerce_product_meta_start' => esc_html__('Product Meta Start', 'cubewp-woocommerce'),
            'woocommerce_product_meta_end' => esc_html__('Product Meta End', 'cubewp-woocommerce'),
            'woocommerce_share' => esc_html__('Share', 'cubewp-woocommerce'),
            'woocommerce_product_additional_information' => esc_html__('Product Additional Information', 'cubewp-woocommerce'),
            'woocommerce_product_tabs' => esc_html__('Product Tabs', 'cubewp-woocommerce'),
            'woocommerce_product_tab_panels' => esc_html__('Product Tab Panels', 'cubewp-woocommerce'),
            'woocommerce_review_before' => esc_html__('Review Before', 'cubewp-woocommerce'),
            'woocommerce_review_after' => esc_html__('Review After', 'cubewp-woocommerce'),
            'woocommerce_review_before_comment_meta' => esc_html__('Review Before Comment Meta', 'cubewp-woocommerce'),
            'woocommerce_review_after_comment_meta' => esc_html__('Review After Comment Meta', 'cubewp-woocommerce'),
            'woocommerce_review_before_comment_text' => esc_html__('Review Before Comment Text', 'cubewp-woocommerce'),
            'woocommerce_review_after_comment_text' => esc_html__('After Comment Text', 'cubewp-woocommerce'),
        ];

        return $hooks;
    }
    add_filter('cubewp/theme_builder/blocks', 'woo_hoooooks');
}

/**
 * Get product gallery HTML with caching
 */
if (!function_exists('wc_get_product_gallery')) {
    function wc_get_product_gallery($productID) {
        static $gallery_cache = array();
        
        if (isset($gallery_cache[$productID])) {
            return $gallery_cache[$productID];
        }

        if (!function_exists('wc_get_product')) {
            $gallery_cache[$productID] = '';
            return '';
        }

        $product = wc_get_product($productID);
        if (!$product) {
            $gallery_cache[$productID] = '';
            return '';
        }

        $attachment_ids = $product->get_gallery_image_ids();
        $product_name = $product->get_name();
        $product_url = get_permalink($productID);
        $html = '';

        if ($attachment_ids) {
            foreach ($attachment_ids as $attachment_id) {
                $image_url = wp_get_attachment_url($attachment_id);
                if ($image_url) {
                    $html .= '<a href="' . esc_url($product_url) . '"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($product_name) . '"></a>';
                }
            }
        } else {
            $thumbnail_url = get_the_post_thumbnail_url($productID);
            if ($thumbnail_url) {
                $html = '<a href="' . esc_url($product_url) . '"><img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($product_name) . '"></a>';
            }
        }

        $gallery_cache[$productID] = $html;
        return $html;
    }
}

/**
 * Add product attributes column to WooCommerce export
 */
if (!function_exists('add_product_attributes_column')) {
    function add_product_attributes_column($columns) {
        $columns['product_attributes'] = esc_html__('Product Attributes', 'cubewp-woocommerce');
        return $columns;
    }
    add_filter('woocommerce_product_export_columns', 'add_product_attributes_column');
}

/**
 * Populate product attributes column in WooCommerce export
 */
if (!function_exists('populate_product_attributes_column')) {
    function populate_product_attributes_column($value, $product) {
        static $attribute_cache = array();
        
        $product_id = $product->get_id();
        if (isset($attribute_cache[$product_id])) {
            return $attribute_cache[$product_id];
        }

        if (!function_exists('wc_get_product_terms')) {
            $attribute_cache[$product_id] = '';
            return '';
        }

        $attributes = $product->get_attributes();
        if (empty($attributes)) {
            $attribute_cache[$product_id] = '';
            return '';
        }

        $formatted_attributes = [];
        foreach ($attributes as $attribute) {
            $name = $attribute->get_name();
            $options = $attribute->is_taxonomy()
                ? wc_get_product_terms($product->get_id(), $name, ['fields' => 'names'])
                : $attribute->get_options();

            if (!empty($options)) {
                $formatted_attributes[] = esc_html($name) . ': ' . esc_html(implode(', ', (array)$options));
            }
        }

        $result = implode(' | ', $formatted_attributes);
        $attribute_cache[$product_id] = $result;
        
        return $result;
    }
    add_filter('woocommerce_product_export_product_column_product_attributes', 'populate_product_attributes_column', 10, 2);
}

/**
 * Video meta box functionality
 */
if (! function_exists('vp_add_video_meta_box')) {
    add_action('add_meta_boxes', 'vp_add_video_meta_box');
    function vp_add_video_meta_box() {
        add_meta_box(
            'vp_video_tabber',
            __('Product Video', 'cubewp-woocommerce'),
            'vp_render_video_meta_box',
            'product',
            'normal',
            'low'
        );
    }
}

if (! function_exists('vp_render_video_meta_box')) {
    function vp_render_video_meta_box($post) {
        $video_type = get_post_meta($post->ID, '_vp_video_type', true) ?: 'youtube';
        $youtube_url = get_post_meta($post->ID, '_vp_youtube_url', true);
        $mp4_video = get_post_meta($post->ID, '_vp_mp4_video', true);
        wp_nonce_field('vp_save_video_meta_box', 'vp_video_nonce');
        ?>
        <div class="vp_video-tabs">
            <button type="button" class="vp_tab_button <?php echo esc_attr(($video_type == 'youtube') ? 'active' : ''); ?>" data-type="youtube"><?php echo esc_html__('YouTube', 'cubewp-woocommerce'); ?></button>
            <button type="button" class="vp_tab_button <?php echo esc_attr(($video_type == 'mp4') ? 'active' : ''); ?>" data-type="mp4"><?php echo esc_html__('MP4', 'cubewp-woocommerce'); ?></button>
        </div>

        <input type="hidden" name="_vp_video_type" id="vp_video_type" value="<?php echo esc_attr($video_type); ?>">

        <div class="vp_video-tab-content vp_youtube-tab <?php echo esc_attr(($video_type == 'youtube') ? 'active' : ''); ?>">
            <p class="form-field">
                <label for="vp_youtube_url">
                    <?php echo esc_html__('YouTube Video URL', 'cubewp-woocommerce'); ?>
                    <span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__('Paste the full YouTube video URL', 'cubewp-woocommerce'); ?>"></span>
                </label>
                <input type="text" name="_vp_youtube_url" id="vp_youtube_url" class="widefat" value="<?php echo esc_url($youtube_url); ?>">
            </p>
        </div>

        <div class="vp_video-tab-content vp_mp4-tab <?php echo esc_attr(($video_type == 'mp4') ? 'active' : ''); ?>">
            <div class="form-field">
                <label for="vp_mp4_video">
                    <?php echo esc_html__('MP4 Video Upload', 'cubewp-woocommerce'); ?>
                    <span class="woocommerce-help-tip" data-tip="<?php echo esc_attr__('Upload or paste the direct link to an MP4 video file.', 'cubewp-woocommerce'); ?>"></span>
                </label>
                <div class="form-field-inner">
                    <input type="text" name="_vp_mp4_video" id="vp_mp4_video" class="widefat" value="<?php echo esc_url($mp4_video); ?>">
                    <button class="button vp_upload_video_button"><?php echo esc_html__('Upload Video', 'cubewp-woocommerce'); ?></button>
                </div>
            </div>
        </div>
        <?php
    }
}

if (! function_exists('vp_save_video_meta_box')) {
    add_action('save_post', 'vp_save_video_meta_box');
    function vp_save_video_meta_box($post_id) {
        if (!isset($_POST['vp_video_nonce']) || !wp_verify_nonce($_POST['vp_video_nonce'], 'vp_save_video_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        update_post_meta($post_id, '_vp_video_type', sanitize_text_field($_POST['_vp_video_type'] ?? 'youtube'));
        update_post_meta($post_id, '_vp_youtube_url', esc_url_raw($_POST['_vp_youtube_url'] ?? ''));
        update_post_meta($post_id, '_vp_mp4_video', esc_url_raw($_POST['_vp_mp4_video'] ?? ''));
    }
}

if (! function_exists('vp_custom_video_shortcode')) {
    function vp_custom_video_shortcode($post_id) {
        static $video_cache = array();
        
        if (isset($video_cache[$post_id])) {
            return $video_cache[$post_id];
        }

        $video_type  = get_post_meta($post_id, '_vp_video_type', true);
        $youtube_url = get_post_meta($post_id, '_vp_youtube_url', true);
        $mp4_url     = get_post_meta($post_id, '_vp_mp4_video', true);
        $has_video = false;
        
        ob_start();
        ?>
        <div class="vp_video_wrapper">
            <?php if ($video_type === 'youtube' && !empty($youtube_url)) : ?>
                <div class="vp_iframe_wrapper">
                    <div class="iframe-layers"></div>
                    <?php
                    $parsed_url = wp_parse_url($youtube_url);
                    parse_str($parsed_url['query'] ?? '', $query_vars);
                    $video_id = $query_vars['v'] ?? '';

                    if ($video_id) {
                        $has_video = true;
                        echo '<iframe 
                            src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?enablejsapi=1&autoplay=1&mute=1&controls=0&modestbranding=1&rel=0&showinfo=0" 
                            frameborder="0" 
                            allow="autoplay; encrypted-media" 
                            allowfullscreen>
                        </iframe>';
                    }
                    ?>
                </div>
            <?php elseif ($video_type === 'mp4' && !empty($mp4_url)) : ?>
                <?php $has_video = true; ?>
                <div class="vp_custom_video_box">
                    <div class="iframe-layers"></div>
                    <video class="vp_custom_video" preload="metadata" playsinline muted autoplay loop>
                        <source src="<?php echo esc_url($mp4_url); ?>" type="video/mp4">
                        <?php esc_html_e('Your browser does not support the video tag.', 'cubewp-woocommerce'); ?>
                    </video>
                </div>
            <?php endif; ?>
        </div>
        <?php

        $video_html = ob_get_clean();
        $result = [
            'html' => $video_html,
            'status' => $has_video ? 'has_video' : 'empty',
        ];
        
        $video_cache[$post_id] = $result;
        return $result;
    }
}

/**
 * Get product stock status with caching
 */
if (! function_exists('vp_get_product_stock_status_percentage')) {
    function vp_get_product_stock_status_percentage($product_id) {
        static $stock_cache = array();
        
        if (isset($stock_cache[$product_id])) {
            return $stock_cache[$product_id];
        }

        $product = wc_get_product($product_id);
        if (!$product) {
            $stock_cache[$product_id] = [
                'stock_status' => 'Out of stock',
                'stock_quantity' => 0,
                'sold_quantity' => 0,
                'percentage_sold' => 0
            ];
            return $stock_cache[$product_id];
        }

        $total_stock = 0;
        $total_sold = 0;

        // Add main product's stock & sales
        $main_stock = $product->get_stock_quantity();
        $main_sold = $product->get_total_sales();

        $total_stock += ($main_stock !== null) ? $main_stock : 0;
        $total_sold += ($main_sold !== null) ? $main_sold : 0;

        // If variable, add all variations' stock & sales
        if ($product->is_type('variable')) {
            $variation_ids = $product->get_children();
            foreach ($variation_ids as $variation_id) {
                $variation = wc_get_product($variation_id);
                if ($variation) {
                    $var_stock = $variation->get_stock_quantity();
                    $var_sold = $variation->get_total_sales();

                    $total_stock += ($var_stock !== null) ? $var_stock : 0;
                    $total_sold += ($var_sold !== null) ? $var_sold : 0;
                }
            }
        }

        // Calculate percentage sold
        $total_items = $total_stock + $total_sold;
        $percentage_sold = ($total_items > 0) ? ($total_sold / $total_items) * 100 : 0;

        // Stock status: in stock if any stock > 0
        $stock_status = ($total_stock > 0) ? 'In stock' : 'Out of stock';

        $result = [
            'stock_status' => $stock_status,
            'stock_quantity' => $total_stock,
            'sold_quantity' => $total_sold,
            'percentage_sold' => round($percentage_sold, 2)
        ];
        
        $stock_cache[$product_id] = $result;
        return $result;
    }
}

/**
 * Define custom post card tags
 */
if (!function_exists('woomen_custom_post_card_tags')) {
    function woomen_custom_post_card_tags() {
        static $tags = null;
        
        if ($tags !== null) {
            return $tags;
        }

        $tags = array(
            'store_locator_get_direction' => 'Get Direction Link',
            'store_locator_opening_hours' => 'Opening Hours',
            'store_locator_social_shares' => 'Social Shares',
        );
        
        return $tags;
    }
    add_filter('cubewp/post/cards/store-locator/custom/tags', 'woomen_custom_post_card_tags', 10);
}

/**
 * Custom cubes for builder
 */
if (!function_exists('cubewp_woocommerce_builder_custom_cube')) {
    function cubewp_woocommerce_builder_custom_cube($default_custom_cubes, $post_type) {
        if ($post_type == 'product' && !class_exists('CubeWp_Frontend_Load')) {
            $custom_cubes = array(
                '_price' => array(
                    'label' => __("Price", "cubewp-woocommerce"),
                    'name'  => '_price',
                    'type'  => 'number',
                ),
            );
            $default_custom_cubes = array_merge($default_custom_cubes, $custom_cubes);
        }
        return $default_custom_cubes;
    }
    add_filter('cubewp/builder/search_filters/custom/cubes', 'cubewp_woocommerce_builder_custom_cube', 10, 3);
    add_filter('cubewp/builder/search_fields/custom/cubes', 'cubewp_woocommerce_builder_custom_cube', 10, 3);
}

/**
 * Plugin updater with caching and optimized requests
 */
if (!function_exists('cubewp_woocommerce_check_for_plugin_update')) {
    function cubewp_woocommerce_check_for_plugin_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $plugin_slug = 'cubewp-addon-woocommerce/cubewp-woocommerce.php';
        if (!isset($transient->checked[$plugin_slug])) {
            return $transient;
        }

        $update_data = get_transient('cubewp_woo_plugin_update_check');
        if (false === $update_data) {
            $response = wp_remote_get('https://cubewp.com/wp-content/uploads/cubewp-woocommerce/cubewp-woo-plugin-updates.json', [
                'timeout' => 10,
                'sslverify' => false
            ]);
            
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $update_data = json_decode(wp_remote_retrieve_body($response), true);
                set_transient('cubewp_woo_plugin_update_check', $update_data, 12 * HOUR_IN_SECONDS);
            }
        }

        if (!empty($update_data) && version_compare($update_data['version'], $transient->checked[$plugin_slug], '>')) {
            $obj = new stdClass();
            $obj->slug = $update_data['slug'];
            $obj->new_version = $update_data['version'];
            $obj->url = 'https://cubewp.com';
            $obj->package = $update_data['download_url'];
            $transient->response[$plugin_slug] = $obj;
        }

        return $transient;
    }
    add_filter('site_transient_update_plugins', 'cubewp_woocommerce_check_for_plugin_update');
}

if (!function_exists('cubewp_woocommerce_plugin_update_info')) {
    function cubewp_woocommerce_plugin_update_info($res, $action, $args) {
        if ($action !== 'plugin_information' || $args->slug !== 'cubewp-addon-woocommerce') {
            return $res;
        }

        static $plugin_info = null;
        
        if ($plugin_info === null) {
            $response = wp_remote_get('https://cubewp.com/wp-content/uploads/cubewp-woocommerce/cubewp-woo-plugin-updates.json', [
                'timeout' => 10,
                'sslverify' => false
            ]);
            
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $plugin_info = json_decode(wp_remote_retrieve_body($response), true);
            } else {
                $plugin_info = false;
            }
        }

        if ($plugin_info) {
            $res = new stdClass();
            $res->name = !empty($plugin_info['name']) ? sanitize_text_field($plugin_info['name']) : 'CubeWP WooCommerce';
            $res->slug = !empty($plugin_info['slug']) ? sanitize_text_field($plugin_info['slug']) : 'cubewp-addon-woocommerce';
            $res->version = !empty($plugin_info['version']) ? sanitize_text_field($plugin_info['version']) : '1.0.0';
            $res->download_link = !empty($plugin_info['download_url']) ? esc_url($plugin_info['download_url']) : '';
            $res->tested = !empty($plugin_info['tested']) ? sanitize_text_field($plugin_info['tested']) : '6.0';
            $res->requires = !empty($plugin_info['requires']) ? sanitize_text_field($plugin_info['requires']) : '5.5';
            $res->last_updated = !empty($plugin_info['last_updated']) ? sanitize_text_field($plugin_info['last_updated']) : '';
            
            $res->sections = array(
                'changelog' => !empty($plugin_info['changelog']) 
                    ? '<strong>Changelog</strong><br>' . cubewp_woocommerce_format_changelog($plugin_info['changelog'])
                    : '<strong>Changelog</strong><br>No updates available.'
            );
        }
        
        return $res;
    }
    add_filter('plugins_api', 'cubewp_woocommerce_plugin_update_info', 20, 3);
}

/**
 * Format changelog for display
 */
if (!function_exists('cubewp_woocommerce_format_changelog')) {
    function cubewp_woocommerce_format_changelog($changelog) {
        $output = '';
        foreach ($changelog as $version => $changes) {
            $output .= '<h4>Version ' . esc_html($version) . '</h4><ul>';
            foreach ($changes as $change) {
                $output .= '<li>' . esc_html($change) . '</li>';
            }
            $output .= '</ul>';
        }
        return $output;
    }
}