<?php
defined('ABSPATH') || exit;
/**
 * Woo_Merchant_Buy_Now_Button class
 *
 * handle feature of buy now button
 *
 * @since 1.0.0
 */
class Woo_Merchant_Buy_Now_Button extends Woo_Merchant_Features_Callback
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
        if (!empty($wm_options['buy_now_button'])) {
            add_action('woocommerce_after_add_to_cart_button', [$this, 'add_buy_now_button']);
        }
    }

    /**
     * Add Buy Now button to product page.
     *
     * @since 1.0.0
     */
    public function add_buy_now_button()
    {
        global $product;

        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }

        if ($product->is_purchasable()) {
            $output = sprintf(
                '<div class="WM-buy-now-button-container" data-checkout-url="%s">',
                esc_url(wc_get_checkout_url())
            );

            $button_classes = 'buy-now-button button alt';
            $button_attrs = [
                'type' => 'button',
                'class' => $button_classes,
                'data-product-id' => $product->get_id(),
                'data-nonce' => wp_create_nonce('woo_merchant_buy_now_' . $product->get_id())
            ];

            if ($product->is_type('variable')) {
                $button_attrs['class'] .= ' wm-buy-now-variable disabled wc-variation-selection-needed';
                $button_attrs['data-variation-id'] = '';
            }

            $output .= '<button ';
            foreach ($button_attrs as $attr => $value) {
                $output .= sprintf('%s="%s" ', esc_attr($attr), esc_attr($value));
            }
            $output .= '>';
            $output .= esc_html__('Buy It Now', 'woo-merchant');
            $output .= '</button>';
            $output .= '</div>';

            // Allow filtering the output
            $output = apply_filters('woo/merchant/buy/now/button', $output);

            // Safely output the HTML
            echo wp_kses_post($output);
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
