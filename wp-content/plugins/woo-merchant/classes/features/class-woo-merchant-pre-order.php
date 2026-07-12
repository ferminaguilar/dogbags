<?php

/**
 * Woo_Merchant_Pre_Order class
 *
 * handle pre order functionality
 *
 * @since 1.0.0
 */
class Woo_Merchant_Pre_Order extends Woo_Merchant_Features_Callback
{
    /**
     * @var Woo_Merchant_Cart
     */
    protected $cart;

    protected $emailIds = [];

    /**
     * Constructor to initialize the settings
     *
     * Sets the options from the database and includes necessary actions.
     * 
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->cart = new Woo_Merchant_Cart();
        add_filter('woocommerce_product_add_to_cart_text', [$this, 'wm_change_add_to_cart_text'], 10, 1);
        add_filter('woocommerce_product_single_add_to_cart_text', [$this, 'wm_change_add_to_cart_text'], 10, 1);
        add_filter('woocommerce_available_variation', [$this, 'wm_change_variable_add_to_cart_text'], 10, 3);
        add_action('woocommerce_before_add_to_cart_form', [$this, 'wm_before_add_to_cart_button'], 10);
        //add_filter('woocommerce_get_item_data', [$this, 'wm_add_pre_order_info_to_cart'], 10, 2);
        add_action('woocommerce_email_order_details', [$this, 'wm_add_pre_order_note_to_email'], 10, 1);
        add_action('woocommerce_checkout_update_order_meta', [$this, 'wm_manage_pre_orders'], 10, 2);
    }

    /**
     * Change "Add to Cart" button to "Pre-Order Now".
     */
    public function wm_change_add_to_cart_text($text)
    {
        global $product;
        if (!$product instanceof WC_Product) {
            return $text;
        }

        $product_id = $product->get_id();

        $wm_pre_order = get_post_meta($product_id, 'wm_pre_order', true);
        $preorder_date = get_post_meta($product_id, 'wm_pre_order_date', true);
        if ($wm_pre_order === 'yes' && strtotime($preorder_date) > time()) {
            return __('Pre-Order Now', 'woo-merchant');
        }

        return $text;
    }

    public function wm_change_variable_add_to_cart_text($data, $product, $variation)
    {
        if (!$variation instanceof WC_Product_Variation) {
            return $data;
        }

        $variation_id   = $variation->get_id();
        $wm_pre_order    = get_post_meta($variation_id, 'wm_pre_order', true);
        $preorder_date  = get_post_meta($variation_id, 'wm_pre_order_date', true);

        if ($wm_pre_order === 'yes' && strtotime($preorder_date) > time()) {
            $data['wm_pre_order'] = true;
        }

        return $data;
    }

    public function wm_before_add_to_cart_button()
    {
        global $post;
        $wm_pre_order   = get_post_meta($post->ID, 'wm_pre_order', true) === 'yes';
        $preorder_date = get_post_meta($post->ID, 'wm_pre_order_date', true);

        if (!$wm_pre_order || strtotime($preorder_date) <= time()) {
            return;
        }
        $timeFormat = date_i18n(get_option('date_format'), strtotime($preorder_date));
        $text       = '<p class="wm-pre-order-availability-text">' . sprintf(__('Available on %s', 'woo-merchant'), $timeFormat) . '</p>';

        echo apply_filters('preorder_avaiable_date_text', $text);
    }



    /**
     * Show Pre-Order info in cart.
     */
    public function wm_add_pre_order_info_to_cart($item_data, $cart_item)
    {
        $product_id = $cart_item['data']->get_id();
        $is_pre_order = get_post_meta($product_id, 'wm_pre_order', true);
        $pre_order_date = get_post_meta($product_id, 'wm_pre_order_date', true);

        if ($is_pre_order === 'yes') {
            $item_data[] = [
                'name'  => __('Pre-Order', 'woo-merchant'),
                'value' => !empty($pre_order_date) ? esc_html($pre_order_date) : __('No specific date', 'woo-merchant')
            ];
        }
        return $item_data;
    }

    /**
     * Prevent Pre-Order products from being marked as "in stock".
     */
    public function wm_mark_pre_order_as_out_of_stock($in_stock, $product)
    {
        return (get_post_meta($product->get_id(), 'wm_pre_order', true) === 'yes') ? false : $in_stock;
    }

    /**
     * Add Pre-Order note in customer email.
     */
    public function wm_add_pre_order_note_to_email($order)
    {
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $is_pre_order = get_post_meta($product_id, 'wm_pre_order', true);
            $pre_order_date = get_post_meta($product_id, 'wm_pre_order_date', true);

            if ($is_pre_order === 'yes') {
                echo '<p><strong>' . __('Pre-Order Notice:', 'woo-merchant') . '</strong> ' . __('This item is a pre-order and will be available on', 'woo-merchant') . ' ' . esc_html($pre_order_date) . '</p>';
            }
        }
    }

    /**
     * Force pre-order products to be "In Stock" so the button appears.
     *
     * @param string $stock_status Current stock status.
     * @param WC_Product $product The product object.
     * @return string Modified stock status.
     */
    public function wm_pre_order_force_in_stock($stock_status, $product)
    {
        if ($product && $product->get_meta('wm_pre_order') === 'yes') {
            return 'instock'; // Ensure pre-order products are always "in stock"
        }
        return $stock_status;
    }

    /**
     * Handle pre-order status when order is placed.
     *
     * @since 1.0.0
     * @param int $order_id Order ID
     * @param array $data Order data
     */
    public function wm_manage_pre_orders($order_id, $data)
    {

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        if (isset($data['preorder_date'])) {
            $order->update_meta_data(
                '_preorder_date', 
                sanitize_text_field($data['preorder_date'])
            );
        } else {
            global $woocommerce;
            $cart = $woocommerce->cart->get_cart();
            $this->cart->checkPreOrderProducts($cart);
            
            if (count($this->cart->getPreOrderProducts()) > 0) {
                $oldestDate = str_replace(
                    [' 00:00:00'], 
                    [''], 
                    sanitize_text_field($this->cart->getOldestDate())
                );
                $order->update_meta_data('_preorder_date', $oldestDate);
            }
        }
        
        $order->save();
        do_action('preorder_email', $this->emailIds);
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
