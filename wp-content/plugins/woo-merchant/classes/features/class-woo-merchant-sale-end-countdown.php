<?php

/**
 * Woo_Merchant_Sale_End_Countdown class
 *
 * handle feature of sale end countdown
 *
 * @since 1.0.0
 */
class Woo_Merchant_Sale_End_Countdown extends Woo_Merchant_Features_Callback
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
        if (! empty($wm_options['sale_end_countdown'])) {
            add_action($wm_options['sale_end_countdown_position'] ?? 'woocommerce_single_product_summary', [$this, 'add_custom_sale_end_countdown'], 20);
        }
    }

    /**
     * Display sale end countdown timer.
     *
     * @since 1.0.0
     */
    public function add_custom_sale_end_countdown()
    {

        global $product;
        $style = 'style_1';
        
        if ($product && is_product()) {
            $product_id = absint(get_the_ID());
            if (!$product_id) {
                return;
            }
            
            $title = esc_html__('HURRY UP! SALE ENDS IN:', 'woo-merchant');
            $countdown_html = woo_merchant_get_sale_end_countdown(
                $product_id,
                $title, 
                $style
            );
            
            if ($countdown_html) {
                echo wp_kses_post($countdown_html);
            }
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
