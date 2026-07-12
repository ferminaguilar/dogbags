<?php

defined('ABSPATH') || exit;

/**
 * Woo_Merchant_Cart class
 *
 * Handles cart functionality for pre-order products.
 *
 * @since 1.0.0
 */

class Woo_Merchant_Cart
{

    protected $preorder_products = [];

    /**
     * Constructor to initialize the cart functionality.
     *
     * Sets up the necessary actions and filters for handling pre-order products in the cart.
     */
    public function checkPreOrderProducts($cart_items)
    {
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $is_preorder = get_post_meta($product_id, 'wm_pre_order', true);
            $preorder_date = get_post_meta($product_id, 'wm_pre_order_date', true);

            if ($is_preorder === 'yes' && strtotime($preorder_date) > time()) {
                $this->preorder_products[] = [
                    'product_id' => $product_id,
                    'preorder_date' => $preorder_date
                ];
            }
        }
    }
    /**
     * Get the list of pre-order products in the cart.
     *
     * @return array
     */
    public function getPreOrderProducts()
    {
        return $this->preorder_products;
    }

    /**
     * Get the earliest preorder date from the cart.
     *
     * @return string
     */
    public function getOldestDate()
    {
        if (empty($this->preorder_products)) {
            return '';
        }

        $dates = array_column($this->preorder_products, 'preorder_date');
        sort($dates);
        return $dates[0]; // return the earliest preorder date
    }

    /**
     * Initialize the class.
     *
     * @return void
     */
    public static function init()
    {
        new self();
    }
}
