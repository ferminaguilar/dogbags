<?php

/**
 * Woo_Merchant_Features_Callback class
 *
 * Handles callback for woo merchant features.
 *
 * @since 1.0.0
 */
class Woo_Merchant_Features_Callback
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
        $this->initialize_features_callback();
    }

    private function initialize_features_callback()
    {
        $features = array(
            'Woo_Merchant_Product_Discount',
            'Woo_Merchant_Frequently_Bought_Together', 
            'Woo_Merchant_Spend_Offer',
            'Woo_Merchant_Sale_End_Countdown',
            'Woo_Merchant_Low_Stock_Notification',
            'Woo_Merchant_Buy_Now_Button',
            'Woo_Merchant_Size_Guide_Button',
            'Woo_Merchant_Pre_Order',
        );

        foreach ($features as $feature_class) {
            if (class_exists($feature_class)) {
                new $feature_class();
            }
        }
    }

    /**
     * Initialize the Woo_Merchant_Features_Callback class
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
