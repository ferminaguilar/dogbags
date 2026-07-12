<?php
defined('ABSPATH') || exit;
/**
 * Woo_Merchant_Frequently_Bought_Together class
 *
 * handle feature of frequently bought together
 *
 * @since 1.0.0
 */
class Woo_Merchant_Frequently_Bought_Together extends Woo_Merchant_Features_Callback
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
        add_action('woocommerce_before_single_product', [$this, 'setup_cross_sell_hooks']);
    }

    /**
     * Set up cross-sell hooks for the product
     */
    public function setup_cross_sell_hooks()
    {
        if (is_product()) {
            $product_id = get_the_ID();

            if ($product_id) {
                $_WM_cross_sells_yes_no = get_post_meta($product_id, '_WM_cross_sells_yes_no', true);
                $_WM_cross_sells_display_position = get_post_meta($product_id, '_WM_cross_sells_display_position', true);

                // Frequently Bought Together
                if (isset($_WM_cross_sells_yes_no) && $_WM_cross_sells_yes_no === 'yes') {
                    add_action($_WM_cross_sells_display_position ?? 'woocommerce_single_product_summary', [$this, 'display_frequently_bought_together'], 25);
                }
            }
        }
    }

    /**
     * Display frequently bought together products.
     *
     * @since 1.0.0
     */
    public function display_frequently_bought_together()
    {
        global $product;
        
        if (!$product || !is_a($product, 'WC_Product') ) {
            return;
        }

        // Get cross-sell products
        $cross_sells = $product->get_cross_sell_ids();

        if (!empty($cross_sells) && is_array($cross_sells)) {
            echo woo_merchant_display_frequently_bought_together('style1');
        }
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
