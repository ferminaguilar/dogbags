<?php

/**
 * Woo_Merchant_Product_Discount class
 *
 * handle feature of product discount
 *
 * @since 1.0.0
 */
class Woo_Merchant_Product_Discount extends Woo_Merchant_Features_Callback
{
    /**
     * Constructor to initialize the settings
     *
     * Sets the options from the database and includes necessary actions.
     * 
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('woocommerce_before_single_product', [$this, 'setup_product_discount_hook']);
        add_action('woocommerce_cart_calculate_fees', [$this, 'bulk_purchase_discount'], 10, 1);
        add_action('woocommerce_cart_calculate_fees', [$this, 'free_gift_discount'], 10, 1);
    }

    /**
     * Set up cross-sell hooks for the product
     */
    public function setup_product_discount_hook()
    {
        if (is_product()) {
            $product_id = get_the_ID();

            if ($product_id) {
                $_enable_discount = get_post_meta($product_id, '_enable_discount', true);
                $_discount_display_position = get_post_meta($product_id, '_discount_display_position', true);
                // Bulk Purchase Display
                if (isset($_enable_discount) && $_enable_discount === 'yes') {

                    add_action($_discount_display_position ?? 'woocommerce_single_product_summary', [$this, 'bulk_purchase_offer_html'], 25);
                }

                $_WM_free_gift_yes_no = get_post_meta($product_id, '_WM_free_gift_yes_no', true);
                $_free_gift_display_position = get_post_meta($product_id, '_free_gift_display_position', true);
                // Bulk Purchase Display
                if (isset($_WM_free_gift_yes_no) && $_WM_free_gift_yes_no === 'yes') {

                    add_action($_free_gift_display_position ?? 'woocommerce_single_product_summary', [$this, 'free_gift_discount_html'], 25);
                }
            }
        }
    }

    public function bulk_purchase_discount($cart)
    {
        // Exit if we're in the admin area or an AJAX request
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        // Initialize variables
        $product_id = '';
        $discount_type = '';
        $discount_value = '';
        $discount_threshold = 0;
        $total_quantity = 0;
        $total_cart_value = 0;
        $eligible_for_discount = false;

        // Loop through cart items to calculate the total quantity and value for the specific product
        foreach ($cart->get_cart() as $cart_item) {
            $product = wc_get_product($cart_item['product_id']);

            // Set the product_id to the first product if not already set
            if (empty($product_id)) {
                $product_id = $product->get_id();
            }

            // Only consider the same product for the discount calculation
            if ($product->get_id() === $product_id) {
                $total_quantity += $cart_item['quantity'];
                $total_cart_value += $cart_item['quantity'] * $product->get_price();
            }
            $_enable_discount = $product->get_meta('_enable_discount');
            if ($_enable_discount == 'yes') {
                $discount_type = $product->get_meta('_discount_type') ?? 'fixed';
                $discount_value = $product->get_meta('_discount_value');
                $discount_threshold = $product->get_meta('_discount_threshold');
            }
        }
        // Check if discount values and threshold are valid
        if ($discount_value <= 0 || $discount_threshold <= 0) {
            return; // Exit if no valid discount or threshold set
        }
        // Check if total quantity exceeds the discount threshold
        if ($total_quantity >= $discount_threshold) {
            $discounted_price = 0;
            // Apply fixed or percentage discount
            if ($discount_type === 'fixed') {
                $cart->add_fee(__('Bulk Purchase Discount', 'woo-merchant'), -$discount_value);
                $discounted_price = number_format($cart->get_subtotal() - $discount_value, 2);
            } else if ($discount_type === 'percentage') {
                $discounted_price = $total_cart_value * ($discount_value / 100);
                $cart->add_fee(__('Bulk Purchase Discount', 'woo-merchant'), -$discounted_price);
            }

            $eligible_for_discount = true;
        }
        // Show a notice if the discount was applied
        if ($eligible_for_discount) {
            $new_total_cart = $total_cart_value - $discounted_price;

            // Format numbers for proper display
            $formatted_discounted_price = number_format($discounted_price, 2);
            $formatted_new_total_cart = number_format($new_total_cart, 2);

            // Add WooCommerce notice
            // wc_add_notice(sprintf(
            //     __('Congratulations! You qualify for a bulk purchase discount. You saved $%s. The new total is $%s.', 'woo-merchant'),
            //     $formatted_new_total_cart,$formatted_discounted_price

            // ), 'notice');
        }
    }

    public function bulk_purchase_offer_html()
    {
        global $product;
        if (!$product) {
            return;
        }
        $options = get_option('WM_woocommerce_features_options');
        $discount_type = $discount_value = $discount_threshold = '';

        $_enable_discount = $product->get_meta('_enable_discount');
        if ($_enable_discount == 'yes') {
            $discount_type = $product->get_meta('_discount_type');
            $discount_value = $product->get_meta('_discount_value');
            $discount_threshold = $product->get_meta('_discount_threshold');
        }

        // Check and generate notice
        if ($discount_threshold > 0 && $discount_value > 0) {
            if ($discount_type === 'fixed') {
                $discount__value = wc_price($discount_value);
                $discount_message = sprintf(
                    __('Buy %d, get %s off', 'woo-merchant'),
                    $discount_threshold,
                    $discount__value
                );
            } elseif ($discount_type === 'percentage') {
                $discount_message = sprintf(
                    __('Buy %d, get %d%% off', 'woo-merchant'),
                    $discount_threshold,
                    $discount_value
                );
            }
            $save = wc_price($discount_value);
            $html = '<div class="wm-bulk-discount">';
            $html .= '<div class="wm-bulk-discount-inner">';
            $html .= '<div class="wm-bulk-discount-top">
                <h5>' . $discount_message . '</h5>
                <span>' . esc_html__('Save', 'woo-merchant') . ' ' . $save . '</span>
            </div>';
            $product_id = get_the_ID();
            if (is_product($product_id)) {
                $product_price = $product->get_price();
                $total_price = $product_price * $discount_threshold;
                if ($discount_type === 'percentage') {
                    $discount_value = $total_price * ($discount_value / 100);
                }
                $discount_price = $total_price - $discount_value;
                $html .= '<div class="wm-bulk-discount-bottom">
                <ul>
                    <li>
                        ' . esc_html__('Total Price x 1') . ':
                        <span>' . wc_price($total_price) . '</span>
                    </li>
                    <li>
                        ' . esc_html__('Discounted Price x 1') . ':
                        <span>' . wc_price($discount_price) . '</span>
                    </li>
                </ul>
            </div>';
            }
            $html .= '</div>';
            $html .= '</div>';
            $html = apply_filters('woo/merchant/bulk/discount', $html);
            return $html;
        }
    }

    function free_gift_discount($cart)
    {
        // Avoid running this function in admin area or non-AJAX requests
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        // Array to store free gifts and their quantities, keyed by the free gift product ID
        $free_gifts = array();
        $total_free_gift_value = 0;

        // Loop through cart items to check for free gift configuration
        foreach ($cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $product = wc_get_product($product_id);
            $_WM_free_gift_yes_no = $product->get_meta('_WM_free_gift_yes_no');

            $free_gift_product_id = $_WM_free_gift_yes_no == 'yes' ? $product->get_meta('_free_gift_product_id') : false;
            $promotion_type = $_WM_free_gift_yes_no == 'yes' ? $product->get_meta('_free_gift_promotion_type') : false;

            if ($free_gift_product_id && $promotion_type) {
                $quantity = $cart_item['quantity'];
                $free_gift_quantity = 0;

                // Determine free gift quantity based on promotion type
                if ($promotion_type === 'buy_one_get_one' && $quantity >= 1) {
                    $free_gift_quantity = floor($quantity / 1); // 1 free for every 1 bought
                } elseif ($promotion_type === 'buy_two_get_one' && $quantity >= 2) {
                    $free_gift_quantity = floor($quantity / 2); // 1 free for every 2 bought
                }

                if ($free_gift_quantity > 0) {
                    // Add to the free gifts array if not already present
                    if (!isset($free_gifts[$free_gift_product_id])) {
                        $free_gifts[$free_gift_product_id] = 0;
                    }
                    $free_gifts[$free_gift_product_id] += $free_gift_quantity;

                    // Calculate the total value of free gifts
                    $free_gift_product = wc_get_product($free_gift_product_id);
                    $total_free_gift_value += $free_gift_quantity * $free_gift_product->get_price();
                }
            }
        }

        // Add free products to the cart if applicable
        if (is_array($free_gifts) && !empty($free_gifts)) {
            $free_gifts = self::merge_free_products($free_gifts);
            self::add_free_product_to_cart($free_gifts);
        }

        // Apply discount equal to the total value of free gifts
        if ($total_free_gift_value > 0) {
            $cart->add_fee(__('Discount for Free Gifts', 'woo-merchant'), -$total_free_gift_value, false);
        }
    }

    public static function add_free_product_to_cart($products)
    {
        // Avoid running this function in admin area or non-AJAX requests
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        // Get the current cart object
        $cart = WC()->cart;

        // Loop through the array of products
        foreach ($products as $product_id => $quantity) {
            $found_in_cart = false;

            // Check if the product is already in the cart
            foreach ($cart->get_cart() as $cart_item) {
                if ($cart_item['product_id'] === $product_id) {
                    $cart_item_quantity = $cart_item['quantity'];
                    if($quantity <= $cart_item_quantity){
                        $found_in_cart = true;
                    } else{
                        $quantity -= $cart_item_quantity;
                        $found_in_cart = false;
                    }
                    break;
                }
            }

            // Add the product to the cart if not already present
            if (!$found_in_cart) {
                $cart->add_to_cart($product_id, $quantity);
            }
        }
    }

    public static function merge_free_products($products)
    {
        $merged_products = [];
        foreach ($products as $product_id => $quantity) {
            if (!isset($merged_products[$product_id])) {
                $merged_products[$product_id] = 0;
            }
            $merged_products[$product_id] += $quantity;
        }
        return $merged_products;
    }

    /**
     * Display free gift discount offer.
     *
     * @since 1.0.0
     */
    public static function free_gift_discount_html()
    {
        global $product;
        
        if (!$product) {
            return;
        }

        $_WM_free_gift_yes_no = $product->get_meta('_WM_free_gift_yes_no');
        if ($_WM_free_gift_yes_no !== 'yes') {
            return;
        }

        $free_gift_product_id = absint($product->get_meta('_free_gift_product_id'));
        $promotion_type = sanitize_text_field($product->get_meta('_free_gift_promotion_type'));

        if (!$free_gift_product_id || !in_array($promotion_type, ['buy_one_get_one', 'buy_two_get_one'])) {
            return;
        }

        $free_product = wc_get_product($free_gift_product_id);
        if (!$free_product || !$free_product->is_in_stock()) {
            return;
        }

        // Prepare promotion text
        $promotion_text = '';
        if ($promotion_type === 'buy_one_get_one') {
            $promotion_text = esc_html__('Buy 1 get 1 free', 'woo-merchant');
        } else {
            $promotion_text = esc_html__('Buy 2 get 1 free', 'woo-merchant');
        }

        // Safe SVG icons
        $plus_icon = '<svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.5006 6.4834H8.16736V1.15002C8.16736 0.782104 7.86865 0.483398 7.50061 0.483398C7.13269 0.483398 6.83398 0.782104 6.83398 1.15002V6.4834H1.50061C1.13269 6.4834 0.833984 6.7821 0.833984 7.15002C0.833984 7.51807 1.13269 7.81677 1.50061 7.81677H6.83398V13.15C6.83398 13.5181 7.13269 13.8168 7.50061 13.8168C7.86865 13.8168 8.16736 13.5181 8.16736 13.15V7.81677H13.5006C13.8687 7.81677 14.1674 7.51807 14.1674 7.15002C14.1674 6.7821 13.8687 6.4834 13.5006 6.4834Z" fill="white"/>
        </svg>';

        $check_icon = '<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0.5" y="1.3999" width="15" height="15" fill="white" stroke="#1D1D1D"/>
            <path d="M11.8141 6.20756C11.5666 5.95971 11.1647 5.95986 10.9168 6.20756L6.8783 10.2462L5.08335 8.4513C4.8355 8.20344 4.43374 8.20344 4.18589 8.4513C3.93804 8.69915 3.93804 9.10091 4.18589 9.34876L6.42947 11.5923C6.55332 11.7162 6.71571 11.7783 6.87812 11.7783C7.04053 11.7783 7.20309 11.7163 7.32693 11.5923L11.8141 7.10501C12.062 6.85733 12.062 6.45539 11.8141 6.20756Z" fill="#1D1D1D"/>
        </svg>';

        // Build HTML with escaped data
        $html = sprintf(
            '<div class="wm-free-product-discount">
                <h3>%s</h3>
                <div class="wm-free-product">
                    <span class="wm-plus-free-product">
                        <span class="wm-plus-free">%s</span>
                    </span>
                    %s
                    <div class="wm-free-product-img">%s</div>
                    <div class="wm-free-product-content">
                        <h6>%s</h6>
                        <p>%s</p>
                    </div>
                </div>
            </div>',
            esc_html($promotion_text),
            $plus_icon,
            $check_icon,
            wp_kses_post($free_product->get_image()),
            esc_html($free_product->get_name()),
            wp_kses_post(wc_price($free_product->get_price()))
        );

        $html = apply_filters('woo/merchant/free/gift/discount', $html);
        echo wp_kses_post($html);
    }

    /**
     * Initialize class
     *
     * Instantiates the class and triggers the necessary actions.
     * 
     * @since 1.0.0
     */
    public static function init()
    {
        $WooMerchantClass = __CLASS__;
        new $WooMerchantClass;
    }
}
