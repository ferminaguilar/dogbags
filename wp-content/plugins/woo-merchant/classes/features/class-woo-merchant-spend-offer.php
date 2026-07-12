<?php

/**
 * Woo_Merchant_Product_Discount class
 *
 * handle feature of product discount
 *
 * @since 1.0.0
 */
class Woo_Merchant_Spend_Offer extends Woo_Merchant_Features_Callback
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
        $wm_options = get_option('WM_woocommerce_features_options');
        if (! empty($wm_options['WM_spend_save_discount'])) {
            add_action('woocommerce_cart_calculate_fees', [$this, 'apply_spend_save_discount'], 20);
            add_action('woocommerce_before_single_product', array($this, 'woo_merchant_spend_save_discount_html'), 25);
        }

        if (! empty($wm_options['WM_spend_gift_offer'])) {
            add_action('woocommerce_cart_calculate_fees', [$this, 'apply_spend_gift_offer'], 20, 1);
            add_action('woocommerce_before_single_product', array($this, 'woo_merchant_spend_gift_offer_html'), 25);
        }
    }

    public function apply_spend_save_discount($cart)
    {
        $wm_options = get_option('WM_woocommerce_features_options');
        if (!$cart) {
            return;
        }
        $total_cart_value = $cart->get_subtotal();

        if (!empty($wm_options['WM_spend_save_discount'])) {
            $discount_type = $wm_options['WM_spend_save_type'] ?? 'fixed';
            $discount_value = isset($wm_options['WM_spend_save_value']) ? floatval($wm_options['WM_spend_save_value']) : 0;
            $threshold = isset($wm_options['WM_spend_save_threshhold']) ? floatval($wm_options['WM_spend_save_threshhold']) : 0;

            // Check if total cart value exceeds the discount threshold
            if ($discount_value > 0 && $threshold > 0 && $total_cart_value >= $threshold) {
                if ($discount_type === 'fixed') {
                    $cart->add_fee(__('Spend & Save Discount', 'woo-merchant'), -$discount_value);
                } elseif ($discount_type === 'percentage') {
                    $discounted_price = $total_cart_value * ($discount_value / 100);
                    $cart->add_fee(__('Spend & Save Discount', 'woo-merchant'), -$discounted_price);
                }
            }
        }
    }

    /**
     * Display spend & save discount offer.
     *
     * @since 1.0.0
     */
    public function woo_merchant_spend_save_discount_html()
    {
        if (!function_exists('WC') || !WC()->cart) {
            return;
        }

        $wm_options = get_option('WM_woocommerce_features_options');
        if (empty($wm_options['WM_spend_save_discount'])) {
            return;
        }

        $discount_type = $wm_options['WM_spend_save_type'] ?? 'fixed';
        $discount_value = isset($wm_options['WM_spend_save_value']) ? floatval($wm_options['WM_spend_save_value']) : 0;
        $threshold = isset($wm_options['WM_spend_save_threshhold']) ? floatval($wm_options['WM_spend_save_threshhold']) : 0;

        $cart_subtotal = WC()->cart->get_subtotal();
        $remaining_spend = max(0, $threshold - $cart_subtotal);
        $milestone_percentage = ($cart_subtotal >= $threshold) ? 100 : round(($cart_subtotal / $threshold) * 100, 2);

        $formatted_discount_value = ($discount_type === 'percentage') ? $discount_value . '%' : strip_tags(wc_price($discount_value));
        ob_start();
?>
        <div class="woo-merchant-spend-save">
            <div class="wm-spend-save-sidebar">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0)">
                        <path d="M13.0086 0C13.0055 0 13.0025 0 12.9994 0C5.83644 0.00502734 0.00492879 5.83659 3.00872e-06 12.9995C-0.00477043 19.9509 5.67095 26 12.9756 26H13.0015C16.8597 25.9924 20.5025 24.2518 22.9532 21.3447V22.9023H24.4766V18.332H19.9063V19.855H22.1925C20.0365 22.728 16.625 24.4694 12.9986 24.4766C12.9908 24.4766 12.9834 24.4766 12.9757 24.4766C6.53672 24.4766 1.51933 19.1293 1.52344 13.0005C1.52776 6.67647 6.67642 1.52786 13.0006 1.52344H13.0086C19.1818 1.52344 24.4766 6.57749 24.4766 13C24.4766 13.9679 24.3555 14.9292 24.1166 15.857L25.592 16.2368C25.8627 15.185 26 14.096 26 13C26 5.7198 20.0054 0 13.0086 0Z" fill="white" />
                        <path d="M16.8086 10.535V6.09375H18.332V4.57031H7.66797V6.09375H9.19141V10.535C9.19141 11.4062 9.67545 12.1894 10.4546 12.579L11.2967 13L10.4546 13.4211C9.6755 13.8106 9.19141 14.5938 9.19141 15.465V19.8555H7.66797V21.3789H18.332V19.8555H16.8086V15.465C16.8086 14.5938 16.3245 13.8106 15.5454 13.421L14.7033 13L15.5454 12.5789C16.3245 12.1894 16.8086 11.4062 16.8086 10.535ZM14.8641 14.7837C15.1238 14.9135 15.2852 15.1746 15.2852 15.465V19.8555H10.7148V15.465C10.7148 15.1746 10.8762 14.9135 11.1359 14.7836L13 13.8517L14.8641 14.7837ZM15.2852 10.535C15.2852 10.8254 15.1238 11.0865 14.8641 11.2164L13 12.1483L11.1359 11.2163C10.8762 11.0865 10.7148 10.8254 10.7148 10.535V6.09375H15.2852V10.535Z" fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0">
                            <rect width="26" height="26" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
                <p><?php echo esc_html($milestone_percentage); ?>%</p>
            </div>
            <div class="wm-spend-save-content">
                <div class="wm-spend-save-content-text">
                    <?php if ($milestone_percentage >= 100) { ?>
                        <p><?php echo sprintf(__('Congraturaltions! You get %s discount on this order.', 'woo-merchant'), $formatted_discount_value); ?></p>
                    <?php } elseif ($milestone_percentage == 0) { ?>
                        <p><?php echo sprintf(__('Spend %s for buying product to get a', 'woo-merchant'), wc_price($remaining_spend)); ?>
                            <span class="highlighted-text"> <?php echo esc_html($formatted_discount_value . ' ' . __('Discount', 'woo-merchant')); ?></span>
                        </p>
                    <?php } else { ?>
                        <p><?php echo sprintf(__('Spend %s more for buying product to get a', 'woo-merchant'), wc_price($remaining_spend)); ?>
                            <span class="highlighted-text"> <?php echo esc_html($formatted_discount_value . ' ' . __('Discount', 'woo-merchant')); ?></span>
                        </p>

                    <?php } ?>
                </div>
                <?php
                $red_degree = ($milestone_percentage / 100) * 360;
                $grey_degree = 360 - $red_degree;
                ?>
                <div class="wm-spend-save-progress-bar" style="background:conic-gradient(#C68566 <?php echo $red_degree; ?>deg, #D9D9D9 <?php echo $grey_degree; ?>deg);">
                    <div class="wm-spend-save-progress-bar-inner">
                    </div>
                    <p class="wm-spend-save-progress-bar-text"><?php echo $milestone_percentage; ?>%</p>
                </div>

            </div>
        </div>
    <?php
        $html = ob_get_clean();
        echo $html;
    }


    public function apply_spend_gift_offer($cart)
    {
        // Avoid running in admin or non-AJAX requests
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        // Get WooCommerce Merchant options
        $wm_options = get_option('WM_woocommerce_features_options');
        $total_cart_value = $cart->get_subtotal();

        // Check if Spend & Get Gift offer is enabled
        if (!empty($wm_options['WM_spend_gift_offer'])) {
            $free_gift_product_id = intval($wm_options['WM_spend_gift_product'] ?? 0);
            $free_gift_threshold = floatval($wm_options['WM_spend_gift_threshhold'] ?? 0);

            // Validate threshold and product ID
            if ($total_cart_value >= $free_gift_threshold && $free_gift_product_id) {
                $free_gift = wc_get_product($free_gift_product_id);

                // Check if the product exists and is in stock
                if ($free_gift && $free_gift->is_in_stock()) {
                    if (!$this->is_product_in_cart($free_gift_product_id)) {
                        // Add free gift to the cart
                        $cart_item_key = $cart->add_to_cart($free_gift_product_id, 1);
                    } else {
                        $cart_item_key = true;
                    }
                    // Apply the discount only if the product is successfully added to the cart
                    if ($cart_item_key) {
                        $cart->add_fee(__('Spend and Get a Gift', 'woo-merchant'), -$free_gift->get_price());
                    }
                } else {
                    wc_add_notice(
                        __('The free gift is currently out of stock and cannot be added to your cart.', 'woo-merchant'),
                        'notice'
                    );
                }
            }
        }
    }

    // Helper to check if a product is in the cart
    private function is_product_in_cart($product_id)
    {
        foreach (WC()->cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] === $product_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Display spend & get gift offer.
     *
     * @since 1.0.0
     */
    public function woo_merchant_spend_gift_offer_html()
    {
        $wm_options = get_option('WM_woocommerce_features_options');
        $free_product_id = isset($wm_options['WM_spend_gift_product']) ? floatval($wm_options['WM_spend_gift_product']) : 0;
        $threshold = isset($wm_options['WM_spend_gift_threshhold']) ? floatval($wm_options['WM_spend_gift_threshhold']) : 0;
        // Check if the Spend & Get a Gift Offer feature is enabled
        if (empty($wm_options['WM_spend_gift_offer']) || !$free_product_id || !$threshold) {
            return;
        }

        $free_product = wc_get_product($free_product_id);
        if (!$free_product) {
            return;
        }

        ob_start();
    ?>
        <div class="woo-merchant-spend-gift">
            <div class="wm-spend-gift-sidebar">
                <svg width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.8314 9.0288L19.2827 8.10117C20.2042 7.33976 20.5977 6.13203 20.3156 4.98437C19.7612 2.72953 17.012 1.87356 15.275 3.40904L13.5154 4.96457L13.1673 2.63168C12.8231 0.334893 10.164 -0.770108 8.29338 0.598447C7.35804 1.28272 6.86049 2.46232 7.08113 3.66005L4.67826 2.78549C3.49608 2.35512 2.18394 2.96694 1.75357 4.14928L0.713062 7.00811C0.569402 7.40283 0.772933 7.83935 1.16771 7.98295C1.32782 8.0412 12.8851 12.2477 13.0548 12.3095C12.5738 12.3095 2.04018 12.3095 1.57675 12.3095C1.15669 12.3095 0.816148 12.65 0.816148 13.0701V23.7183C0.816148 24.9764 1.83975 26 3.0979 26H9.18261H12.225H18.3097C19.5678 26 20.5914 24.9764 20.5914 23.7183V15.0526L21.1798 15.2668C21.5727 15.4099 22.0104 15.2086 22.1547 14.8122L23.1952 11.9534C23.6255 10.771 23.0137 9.45912 21.8314 9.0288ZM8.42196 24.4789H3.09785C2.67845 24.4789 2.33725 24.1377 2.33725 23.7183V13.8307H8.42196V24.4789ZM11.4643 24.4789H9.94316V13.8307H11.4643V24.4789ZM16.2825 4.54877C17.1648 3.76872 18.5577 4.2063 18.8384 5.34761C19.1376 6.56448 17.9614 7.61962 16.7842 7.19183C15.7829 6.82738 15.3137 6.6566 14.311 6.29164L16.2825 4.54877ZM9.1915 1.82624C10.1421 1.13084 11.4887 1.69512 11.6629 2.85725L12.0529 5.46969C11.7722 5.36752 9.85602 4.67004 9.57992 4.56959C8.40261 4.14085 8.18024 2.56592 9.1915 1.82624ZM9.54981 9.41499L2.40266 6.81362L3.18306 4.66953C3.32652 4.27542 3.7639 4.07158 4.15796 4.21494C4.95776 4.50601 10.3437 6.46632 10.5903 6.5561L9.54981 9.41499ZM12.4087 10.4556L10.9793 9.93529L12.0198 7.07646L13.4493 7.59676L12.4087 10.4556ZM19.0702 23.7183C19.0702 24.1377 18.729 24.4789 18.3096 24.4789H12.9855V13.8307H17.2342L19.0702 14.499V23.7183H19.0702ZM21.7656 11.433L20.9853 13.5772L13.8381 10.9758L14.8787 8.11697L21.3111 10.4581C21.7052 10.6016 21.9091 11.039 21.7656 11.433Z" fill="white" />
                </svg>
            </div>
            <div class="wm-spend-gift-content">
                <div class="wm-spend-save-content-text">
                    <p><?php echo sprintf(__('Spend %s and get a free gift from us!.', 'woo-merchant'), wc_price($threshold)); ?></p>
                </div>
                <?php
                $free_product = wc_get_product($free_product_id);
                ?>
                <div class="wm-spend-gift-product">
                    <div class="wm-spend-gift-product-img">
                        <?php echo $free_product->get_image(); ?>
                    </div>
                    <div class="wm-spend-gift-product-details">
                        <h6><?php echo esc_html($free_product->get_name()); ?></h6>
                        <p><del><?php echo wc_price($free_product->get_price()); ?></del> <span><?php echo esc_html__('Free', 'woo-merchant'); ?></span></p>
                    </div>
                </div>

            </div>
        </div>
<?php
        $html = ob_get_clean();
        echo $html;
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
