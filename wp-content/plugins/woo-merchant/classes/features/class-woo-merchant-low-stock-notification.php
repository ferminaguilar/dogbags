<?php

/**
 * Woo_Merchant_Low_Stock_Notification class
 *
 * handle feature of low stock notification
 *
 * @since 1.0.0
 */
class Woo_Merchant_Low_Stock_Notification extends Woo_Merchant_Features_Callback
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
        if (! empty($wm_options['low_stock_notification'])) {
            add_action($wm_options['low_stock_notification_position'] ?? 'woocommerce_single_product_summary', [$this, 'add_low_stock_notification'], 20);
        }
    }

    /**
     * Display low stock notification.
     *
     * @since 1.0.0
     */
    public function add_low_stock_notification()
    {
        global $product;
        
        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }

        $low_stock_threshold = absint($product->get_meta('low_stock_quantity'));
        $stock_quantity = absint($product->get_stock_quantity());

        if ($product->managing_stock() && $low_stock_threshold > 0 && $stock_quantity < $low_stock_threshold) {
            // Safe SVG markup
            $svg_icon = '<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">';
            $svg_icon .= '<path d="M3.86184 15.9256L2.98138 17.2804C2.81096 17.5445 2.88481 17.8967 3.14895 18.0671C3.24552 18.1296 3.35063 18.158 3.45854 18.158C3.64598 18.158 3.82778 18.0671 3.93569 17.8995L4.79627 16.576C6.02891 17.3087 7.46606 17.7319 8.99978 17.7319C10.5364 17.7319 11.9735 17.3088 13.2033 16.5732L14.061 17.8967C14.1689 18.0643 14.3536 18.1552 14.5382 18.1552C14.6433 18.1552 14.7512 18.1268 14.8478 18.0643C15.1119 17.8939 15.1858 17.5417 15.0153 17.2776L14.1377 15.9228C16.0236 14.4146 17.2364 12.0942 17.2364 9.49539C17.2364 4.95389 13.5413 1.25879 8.99978 1.25879C4.45828 1.25879 0.763184 4.95384 0.763184 9.49534C0.763184 12.0941 1.97594 14.4146 3.86184 15.9256ZM8.99978 2.3948C12.9164 2.3948 16.1003 5.57869 16.1003 9.49534C16.1003 13.412 12.9164 16.5959 8.99978 16.5959C5.08314 16.5959 1.89925 13.412 1.89925 9.49534C1.89925 5.57869 5.08314 2.3948 8.99978 2.3948Z" fill="#1D1D1D"></path>';
            $svg_icon .= '<path d="M10.897 11.9123C11.0049 12.006 11.1384 12.0515 11.2718 12.0515C11.4309 12.0515 11.5871 11.9861 11.7007 11.8583C11.908 11.6226 11.8825 11.2647 11.6467 11.0574L9.5677 9.23682V4.66691C9.5677 4.35448 9.3121 4.09888 8.99967 4.09888C8.68725 4.09888 8.43164 4.35448 8.43164 4.66691V9.49527C8.43164 9.66 8.50264 9.81624 8.62478 9.92414L10.897 11.9123Z" fill="#1D1D1D"></path>';
            $svg_icon .= '<path d="M1.89948 3.11339C2.04718 3.11339 2.19488 3.05656 2.30563 2.94297C2.98162 2.2528 3.74848 1.67623 4.58065 1.22749C4.85613 1.07979 4.96124 0.736152 4.81354 0.457781C4.66584 0.182303 4.3222 0.0771959 4.04383 0.224895C3.1066 0.727615 2.24886 1.37234 1.49053 2.14774C1.27183 2.37214 1.27468 2.7328 1.49907 2.9515C1.61262 3.05946 1.75747 3.11339 1.89948 3.11339Z" fill="#1D1D1D"></path>';
            $svg_icon .= '<path d="M13.419 1.22744C14.254 1.67334 15.018 2.25275 15.694 2.94292C15.8048 3.05652 15.9525 3.11334 16.1002 3.11334C16.2422 3.11334 16.387 3.05936 16.4978 2.95146C16.7222 2.73275 16.725 2.37204 16.5063 2.14769C15.7508 1.37514 14.8903 0.727567 13.953 0.224895C13.6747 0.0771959 13.331 0.182303 13.1833 0.457781C13.0384 0.736105 13.1407 1.07974 13.419 1.22744Z" fill="#1D1D1D"></path>';
            $svg_icon .= '</svg>';

            $notification = sprintf(
                '<div class="wm-low-stock-notification">
                    <div class="wm-low-stock-text">
                        <span>%s</span>
                        <p>%s</p>
                    </div>
                    <div class="wm-stock-total">
                        <div class="wm-stock-average" style="width: %d%%;"></div>
                    </div>
                </div>',
                $svg_icon,
                esc_html(sprintf(__('Hurry Up! Only %d units left in stock!', 'woo-merchant'), $stock_quantity)),
                min(100, ($stock_quantity / $low_stock_threshold) * 100)
            );

            $notification = apply_filters('woo/merchant/low/stock/notification', $notification, $stock_quantity);
            echo wp_kses_post($notification);
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
