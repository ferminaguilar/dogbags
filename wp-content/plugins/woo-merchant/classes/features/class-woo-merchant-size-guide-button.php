<?php

/**
 * Woo_Merchant_Size_Guide_Button class
 *
 * handle feature of buy now button
 *
 * @since 1.0.0
 */
class Woo_Merchant_Size_Guide_Button extends Woo_Merchant_Features_Callback
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
        if (isset($wm_options['manage_size_guide_button']) && ! empty($wm_options['size_guide_button']) && ($wm_options['manage_size_guide_button'] == 'hooks')) {
            add_action($wm_options['size_guide_button_position'] ?? 'woocommerce_after_add_to_cart_button', [$this, 'add_size_guide_button']);
        }
    }

    /**
     * Add size guide button and modal to product page.
     *
     * @since 1.0.0
     */
    public function add_size_guide_button()
    {

        global $product;
        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }

        $size_guide_image = get_post_meta($product->get_id(), '_size_guide_image', true);
        if (!empty($size_guide_image) && filter_var($size_guide_image, FILTER_VALIDATE_URL)) {
            $output = sprintf(
                '<div class="size-guide-button-container">
                    <button type="button" class="size-guide-button button alt" data-toggle="modal" data-target="#sizeGuideModal">%s</button>
                </div>
                <div id="sizeGuideModal" class="size-guide-modal">
                    <div class="size-guide-modal-content">
                        <span class="close">&times;</span>
                        <h2>%s</h2>
                        <img src="%s" alt="%s" />
                    </div>
                </div>',
                esc_html__('Size Guide', 'woo-merchant'),
                esc_html__('Size Guide', 'woo-merchant'),
                esc_url($size_guide_image),
                esc_attr__('Size Guide', 'woo-merchant')
            );
            
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
