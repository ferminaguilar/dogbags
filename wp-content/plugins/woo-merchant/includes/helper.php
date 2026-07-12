<?php
// Declare WordPress and WooCommerce functions for IDE
if (!function_exists('__')) {
    /**
     * Retrieve translated string.
     * @param string $text Text to translate
     * @param string $domain Text domain
     * @return string Translated text
     */
    function __($text, $domain = 'default') {}
}

if (!function_exists('wp_strip_all_tags')) {
    /**
     * Strip all HTML tags including script and style.
     * @param string $string String to strip tags from
     * @param bool $remove_breaks Whether to remove line breaks
     * @return string Stripped string
     */
    function wp_strip_all_tags($string, $remove_breaks = false) {}
}

if (!function_exists('esc_attr')) {
    /**
     * Escape for HTML attributes.
     * @param string $text Text to escape
     * @return string Escaped text
     */
    function esc_attr($text) {}
}

if (!function_exists('wp_send_json_error')) {
    /**
     * Send JSON error response.
     * @param mixed $data Error data
     * @param int $status_code HTTP status code
     */
    function wp_send_json_error($data = null, $status_code = null) {}
}

if (!function_exists('wp_send_json_success')) {
    /**
     * Send JSON success response.
     * @param mixed $data Response data
     * @param int $status_code HTTP status code
     */
    function wp_send_json_success($data = null, $status_code = null) {}
}

if (!function_exists('wc_get_product')) {
    /**
     * Get WooCommerce product object.
     * @param int $product_id Product ID
     * @return WC_Product|false Product object or false
     */
    function wc_get_product($product_id) {}
}

if (!function_exists('wc_price')) {
    /**
     * Format price with currency symbol.
     * @param float $price Price amount
     * @return string Formatted price
     */
    function wc_price($price) {}
}

if (!function_exists('is_page')) {
    /**
     * Check if current page matches given ID.
     * @param int|string $page Page ID, title, slug, or array
     * @return bool True if matches
     */
    function is_page($page) {}
}

if (!function_exists('absint')) {
    /**
     * Convert value to non-negative integer.
     * @param mixed $maybeint Value to convert
     * @return int Non-negative integer
     */
    function absint($maybeint) {}
}

if (!function_exists('WC')) {
    /**
     * Get WooCommerce instance.
     * @return WooCommerce Main instance
     */
    function WC() {}
}

if (!function_exists('wp_safe_redirect')) {
    /**
     * Safe redirect.
     * @param string $location Redirect target
     * @param int $status HTTP status code
     */
    function wp_safe_redirect($location, $status = 302) {}
}

if (!function_exists('wc_get_checkout_url')) {
    /**
     * Get checkout page URL.
     * @return string Checkout URL
     */
    function wc_get_checkout_url() {}
}

if (!function_exists('get_permalink')) {
    /**
     * Get permalink for post.
     * @param int|WP_Post $post Post ID or object
     * @return string|false Permalink or false
     */
    function get_permalink($post) {}
}

if (!function_exists('get_post_meta')) {
    /**
     * Get post meta value.
     * @param int $post_id Post ID
     * @param string $key Meta key
     * @param bool $single Return single value
     * @return mixed Meta value
     */
    function get_post_meta($post_id, $key, $single = false) {}
}


if (!function_exists('woo_merchant_locate_plugin_template')) {
    function woo_merchant_locate_plugin_template($template, $template_name, $template_path)
    {
        $plugin_path = WOO_MERCHANT_PLUGIN_DIR . 'woocommerce/';

        // Look in your plugin first
        if (file_exists($plugin_path . $template_name)) {

            return $plugin_path . $template_name;
        }

        return $template;
    }
    add_filter('woocommerce_locate_template', 'woo_merchant_locate_plugin_template', 10, 3);
}

if (! function_exists('woo_merchant_load_checkout_functions')) {
    function woo_merchant_load_checkout_functions()
    {
        if (class_exists('WooCommerce')) {
            $wm_options = get_option('WM_woocommerce_features_options');
            if (! empty($wm_options['woo_merchant_checkout'])) {
                $checkoutStyles = isset($wm_options['woo_merchant_checkout_style'])
                    ? $wm_options['woo_merchant_checkout_style']
                    : 'style-2';

                if ($checkoutStyles === 'style-2') {
                    require_once WOO_MERCHANT_PLUGIN_DIR . 'includes/checkout-functions.php';
                }
            }
        }
    }

    // Hook it into plugins_loaded so WooCommerce is available
    add_action('init', 'woo_merchant_load_checkout_functions');
}


/**
 * Get WooCommerce product detail page hooks with their display names.
 *
 * @since 1.0.0
 * @return array Associative array of hook names and their display titles
 */
function get_woocommerce_product_detail_hooks()
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
        'woocommerce_before_add_to_cart_button'         => 'Before Add to Cart Button (Single Only)',
        'woocommerce_template_single_add_to_cart'       => 'Single Product Add to Cart',
        'woocommerce_after_add_to_cart_button'          => 'After Add to Cart Button (Single Only)',
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



/**
 * Render custom Woo Merchant shortcodes in content.
 *
 * @since 1.0.0
 * @param string $content Content containing shortcodes
 * @return string Content with rendered shortcodes
 */
function WM_render_custom_shortcode($content)
{
    // Define a regex pattern to match shortcodes starting with "WM_"
    $pattern = '/\[(WM_[^\]]+)\]/';

    $content = preg_replace_callback($pattern, function ($matches) {
        $shortcode = $matches[1];

        if ($shortcode === 'WM_time_countdown') {

            return '<span id="countdown-timer"></span>';
        } else if ($shortcode === 'WM_saved_price_of_offer') {

            return '<strong>$5.00</strong>';
        } else if ($shortcode === 'WM_item_price_of_offer') {

            return '<strong>$42.00</strong>';
        } else if ($shortcode === 'WM_total_price_of_offer') {

            return '<strong>$231.00</strong>';
        }

        return $matches[0];
    }, $content);

    return $content;
}

if (!function_exists('woo_merchant_format_sale_price')) {
    /**
     * Format sale price display for a product.
     *
     * @since 1.0.0
     * @param int $product_id Product ID
     * @return string Formatted price HTML
     */
    function woo_merchant_format_sale_price($product_id)
    {
        // Ensure the product ID is valid.
        if (empty($product_id) || !is_numeric($product_id)) {
            return ''; // Return empty string for invalid product ID.
        }

        // Get the product object.
        $product = wc_get_product($product_id);

        // Check if the product object is valid.
        if (!$product || !is_a($product, 'WC_Product')) {
            return ''; // Return empty string if the product is not valid.
        }

        // Initialize the price variable.
        $price = '';

        // Check if the product is on sale.
        if ($product->is_on_sale()) {
            // Get regular and sale prices.
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();

            // Ensure both prices are valid.
            $formatted_regular_price = is_numeric($regular_price) ? wc_price($regular_price) : false;
            $formatted_sale_price    = is_numeric($sale_price) ? wc_price($sale_price) : false;

            // Build the HTML for the price display if both prices are valid.
            if ($formatted_regular_price && $formatted_sale_price) {
                $price .= '<ins aria-hidden="true">' . $formatted_sale_price . '</ins>';
                $price .= '<del aria-hidden="true">' . $formatted_regular_price . '</del>';

                // Add accessibility (a11y) text for screen readers.
                $price .= '<span class="screen-reader-text">';
                $price .= esc_html(sprintf(__('Original price was: %s.', 'woo-merchant'), wp_strip_all_tags($formatted_regular_price)));
                $price .= '</span>';

                $price .= '<span class="screen-reader-text">';
                $price .= esc_html(sprintf(__('Current price is: %s.', 'woo-merchant'), wp_strip_all_tags($formatted_sale_price)));
                $price .= '</span>';
            } else {
                $current_price = $product->get_price();
                $price = is_numeric($current_price) ? wc_price($current_price) : '<span class="screen-reader-text">' . esc_html__('Price information is not available.', 'woo-merchant') . '</span>';
            }
        } else {
            // Handle the case when the product is not on sale.
            $current_price = $product->get_price();
            $price = is_numeric($current_price) ? wc_price($current_price) : '<span class="screen-reader-text">' . esc_html__('Price information is not available.', 'woo-merchant') . '</span>';
        }

        // Apply a filter to allow customization of the price format.
        return apply_filters('woo_merchant_format_sale_price', $price, $product_id);
    }
}



if (!function_exists('woo_merchant_prevent_duplicate_cart_addition')) {
    /**
     * Prevent duplicate cart additions during checkout.
     *
     * @since 1.0.0
     */
    function woo_merchant_prevent_duplicate_cart_addition()
    {
        if (function_exists('is_checkout') && is_checkout() && isset($_GET['add-to-cart'])) {
            $product_id = absint($_GET['add-to-cart']);
            $quantity = isset($_GET['quantity']) ? absint($_GET['quantity']) : 1;
            $variation_id = isset($_GET['variation_id']) ? absint($_GET['variation_id']) : 0;

            // Add the product to the cart
            //WC()->cart->add_to_cart($product_id, $quantity, $variation_id);

            // Redirect to the clean checkout page
            wp_safe_redirect(wc_get_checkout_url());
            exit;
        }
    }
    add_action('template_redirect', 'woo_merchant_prevent_duplicate_cart_addition');
}


// Prevent function re-declaration
if (!function_exists('woo_merchant_display_frequently_bought_together')) {
    /**
     * Display frequently bought together products.
     *
     * @since 1.0.0
     * @param string $style Display style (style1|style2)
     */
    function woo_merchant_display_frequently_bought_together($style)
    {
        global $product;

        if (! $product) {
            return;
        }

        $cross_sells = $product->get_cross_sell_ids();
        $style = isset($style) ? $style : 'style1';
        if (! empty($cross_sells)) {
            $html = '<div class="woocommerce-cross-sells woo-merchant-cross-sells ' . $style . '">';
            $html .= '<div class="woo-merchant-cross-sells-products">';
            $total_price   = 0;
            foreach ($cross_sells as $cross_sell_id) {
                $random_id = rand(0, 9999);
                $cross_sell_product = wc_get_product($cross_sell_id);
                $html .= '<div class="woo-cross-sell-product">';
                if ($style == 'style2') {
                    $html .= '<input type="checkbox" id="cross-sell-' . esc_attr($cross_sell_id) . '-' . $random_id . '" checked="checked" class="woo-cross-sell-checkbox" value="' . esc_attr($cross_sell_id) . '" data-qty="1" data-price="' . esc_attr($cross_sell_product->get_price()) . '">
                    <label for="cross-sell-' . esc_attr($cross_sell_id) . '-' . $random_id . '"></label>';
                }
                $html .= '<a href="' . get_permalink($cross_sell_id) . '">
                            ' . $cross_sell_product->get_image() . '
                            <h6>' . $cross_sell_product->get_name() . '</h6>
                        </a>';
                $html .= '<p>' . woo_merchant_format_sale_price($cross_sell_id) . '</p>';
                $html .= '</div>';
                $total_price += $cross_sell_product->get_price();
            }

            $html .= '</div>';
            if (isset($style) && $style == 'style1') {
                $html .= '<div class="woo-merchant-cross-sells-selections"><ul>';

                $total_discount = 0;

                foreach ($cross_sells as $cross_sell_id) {
                    $random_id = rand(0, 9999);
                    $cross_sell_product = wc_get_product($cross_sell_id);
                    $extra_atts        = '';

                    if ($cross_sell_product->is_on_sale()) {
                        $regular_price  = floatval($cross_sell_product->get_regular_price());
                        $sale_price     = floatval($cross_sell_product->get_sale_price());
                        if ($regular_price > 0 && $sale_price > 0) {
                            $discount_value = $regular_price - $sale_price;
                            $total_discount += $discount_value;

                            $extra_atts = 'data-discount-value="' . esc_attr($discount_value) . '"';
                        }
                    }

                    $html .= '<li class="woo-cross-sell-selection">';
                    $html .= '<input type="checkbox" id="cross-sell-' . esc_attr($cross_sell_id) . '-' . $random_id . '" checked="checked" class="woo-cross-sell-checkbox" value="' . esc_attr($cross_sell_id) . '" data-qty="1" data-price="' . esc_attr($cross_sell_product->get_price()) . '" ' . $extra_atts . '>
                        <label for="cross-sell-' . esc_attr($cross_sell_id) . '-' . $random_id . '">' . $cross_sell_product->get_name() . ' - ' . wc_price($cross_sell_product->get_price()) . '</label>';
                    $html .= '</li>';
                }

                $html .= '</ul></div>';
            }
            $html .= '<div class="woo-merchant-cross-sells-bottom">';
            $html .= '<div class="woo-merchant-cross-sells-pricing">
            <p>' . esc_html__('For', 'woo-merchant') . ' <span class="selected-items-count">' . count($cross_sells) . '</span> ' . esc_html__('items(s)', 'woo-merchant') . '</p>
            <h2 class="selected-items-price">' . wc_price($total_price) . '</h2>';

            if (! empty($total_discount)) {
                $html .= '<del class="items-discount-value">' . wc_price($total_discount) . '</del>';
            }

            $html .= '</div>';
            $html .= '<button class="button add-all-cross-sells-to-cart">' . __('Add All to Cart', 'woo-merchant') . '</button>';
            $html .= '</div></div>';
            $html .= do_action('woocommerce_upsell_display');
            $output = apply_filters('woo/merchant/cross/sells', $html);
            return $output;
        }
    }
}

if (!function_exists('woo_merchant_add_multiple_to_cart')) {
    /**
     * AJAX handler for adding multiple products to cart.
     *
     * @since 1.0.0
     */
    function woo_merchant_add_multiple_to_cart()
    {
        // Get the products from AJAX request
        if (! isset($_POST['products']) || ! is_array($_POST['products'])) {
            wp_send_json_error('No products found.');
        }

        $products = $_POST['products']; // include data in array, unable to use sanitize text field
        $error    = false;

        foreach ($products as $product) {
            $product_id = intval($product['id']);
            $quantity   = isset($product['quantity']) ? intval($product['quantity']) : 1;

            // Add the product to the cart
            $added = WC()->cart->add_to_cart($product_id, $quantity);

            if (! $added) {
                $error = true; // Flag if any product fails to be added
            }
        }

        if ($error) {
            wp_send_json_error('Some products could not be added to the cart.');
        } else {
            wp_send_json_success();
        }
    }
    add_action('wp_ajax_add_multiple_to_cart', 'woo_merchant_add_multiple_to_cart');
    add_action('wp_ajax_nopriv_add_multiple_to_cart', 'woo_merchant_add_multiple_to_cart');
}

if (!function_exists('woo_merchant_get_sale_end_countdown')) {
    /**
     * Get sale end countdown HTML for a product.
     *
     * @since 1.0.0
     * @param int $product_id Product ID
     * @param string $title Countdown title
     * @param string $style Display style
     * @return string Countdown HTML
     */
    function woo_merchant_get_sale_end_countdown($product_id, $title, $style = 'style_1')
    {
        $product = wc_get_product($product_id);
        if (!$product) {
            return;
        }
        $html = '';
        if ($product->is_on_sale()) {
            $sale_end_date = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
            $title = !empty($title) ? $title : __('HURRY UP! SALE ENDS IN:', 'woo-merchant');
            if ($sale_end_date) {
                $sale_end_date = date('Y-m-d H:i:s', $sale_end_date);
                $html = '<div class="wm-sale-end-countdown ' . $style . '" data-sale-end-time="' . esc_attr($sale_end_date) . '">';
                $html .= '<h6>
                <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.7911 17.6775L2.81281 19.1828C2.62345 19.4763 2.70551 19.8676 2.999 20.057C3.1063 20.1264 3.22309 20.158 3.34298 20.158C3.55125 20.158 3.75326 20.057 3.87315 19.8708L4.82936 18.4002C6.19895 19.2144 7.79579 19.6846 9.49992 19.6846C11.2072 19.6846 12.804 19.2144 14.1705 18.3971L15.1235 19.8677C15.2434 20.0539 15.4486 20.1549 15.6537 20.1549C15.7705 20.1549 15.8904 20.1233 15.9977 20.0539C16.2912 19.8645 16.3732 19.4732 16.1839 19.1797L15.2088 17.6744C17.3042 15.9987 18.6517 13.4204 18.6517 10.5329C18.6517 5.48677 14.546 1.3811 9.49992 1.3811C4.45381 1.3811 0.348145 5.48672 0.348145 10.5328C0.348145 13.4204 1.69565 15.9987 3.7911 17.6775ZM9.49992 2.64334C13.8518 2.64334 17.3894 6.181 17.3894 10.5328C17.3894 14.8847 13.8518 18.4223 9.49992 18.4223C5.14809 18.4223 1.61044 14.8847 1.61044 10.5328C1.61044 6.181 5.14809 2.64334 9.49992 2.64334Z" fill="#1D1D1D"/>
                    <path d="M11.6079 13.2184C11.7278 13.3226 11.8761 13.3731 12.0244 13.3731C12.2011 13.3731 12.3747 13.3005 12.501 13.1585C12.7313 12.8965 12.7029 12.4989 12.441 12.2686L10.1309 10.2457V5.16801C10.1309 4.82087 9.84694 4.53687 9.4998 4.53687C9.15266 4.53687 8.86865 4.82087 8.86865 5.16801V10.5329C8.86865 10.7159 8.94755 10.8895 9.08325 11.0094L11.6079 13.2184Z" fill="#1D1D1D"/>
                    <path d="M1.61037 3.44177C1.77448 3.44177 1.93859 3.37863 2.06165 3.25241C2.81275 2.48556 3.66481 1.84493 4.58945 1.34632C4.89554 1.18221 5.01232 0.800396 4.84821 0.491094C4.6841 0.185007 4.30228 0.0682222 3.99298 0.232333C2.95161 0.79091 1.99857 1.50727 1.15599 2.36883C0.912982 2.61816 0.916144 3.01889 1.16547 3.2619C1.29164 3.38185 1.45259 3.44177 1.61037 3.44177Z" fill="#1D1D1D"/>
                    <path d="M14.4105 1.34627C15.3383 1.84171 16.1872 2.48551 16.9383 3.25236C17.0613 3.37858 17.2255 3.44171 17.3896 3.44171C17.5474 3.44171 17.7083 3.38174 17.8314 3.26185C18.0807 3.01884 18.0838 2.61805 17.8408 2.36878C17.0014 1.51038 16.0452 0.790857 15.0038 0.232333C14.6946 0.0682222 14.3127 0.185007 14.1486 0.491094C13.9876 0.800343 14.1012 1.18216 14.4105 1.34627Z" fill="#1D1D1D"/>
                </svg>
                ' . $title . ' </h6>';
                $html .= '<div class="wm-sale-end-timer">
                                <div id="countdown-days" class="wm-timer-wrapper">
                                    <span class="time">00</span>
                                    <span class="label">Days</span>
                                </div>
                                <div id="countdown-hours" class="wm-timer-wrapper">
                                    <span class="time">00</span>
                                    <span class="label">Hours</span>
                                </div>
                                <div id="countdown-minutes" class="wm-timer-wrapper">
                                    <span class="time">00</span>
                                    <span class="label">Minutes</span>
                                </div>
                                <div id="countdown-seconds" class="wm-timer-wrapper">
                                    <span class="time">00</span>
                                    <span class="label">Seconds</span>
                                </div>
                            </div>';
                $html .= '</div>';
                $html = apply_filters('woo/merchant/sale/end/countdown', $html, $sale_end_date);
            }
        }
        return $html;
    }
}
