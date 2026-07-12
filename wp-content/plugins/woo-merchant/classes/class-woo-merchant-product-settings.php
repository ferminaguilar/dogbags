<?php

/**
 * Woo_Merchant_Product_Settings
 *
 * A class to handle custom WooCommerce product settings for the Woo Merchant plugin.
 * This includes managing custom fields for discounts, low stock notifications,
 * free gifts, size guide images, and cross-sell options.
 *
 * @since 1.0.0
 */
class Woo_Merchant_Product_Settings
{
    public function __construct()
    {

        // Hook into WooCommerce initialization
        add_action('init', [$this, 'registerPreOrderStatus']);
        // Add Pre-Order status to WooCommerce order statuses
        add_filter('wc_order_statuses', [$this, 'addPreOrderStatus']);

        add_filter('woocommerce_product_data_tabs', array($this, 'custom_product_meta_group_tab'));
        add_action('woocommerce_product_data_panels', array($this, 'custom_product_meta_group_content'));
        //add_action('woocommerce_process_product_meta', 'custom_save_product_meta_group');

        add_action('woocommerce_process_product_meta', array($this, 'save_discount_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_low_stock_quantity_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_free_gift_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_size_guide_image_upload_field'));

        add_action('woocommerce_product_options_related', array($this, 'add_cross_sell_options_linked_products_tab'));
        add_action('woocommerce_process_product_meta', array($this, 'save_custom_linked_products_fields'));

        //Woo Merchant Pre Order
        //Simple Product
        add_action('woocommerce_product_options_stock_status', array($this, 'wm_add_simple_product_pre_order'));
        add_action('woocommerce_process_product_meta', array($this, 'wm_save_simple_product_pre_order'), 10, 2);

        //Variable Product
        add_action('woocommerce_product_after_variable_attributes', [$this, 'wm_add_variable_product_pre_order'], 10, 3);
        add_action('woocommerce_save_product_variation', [$this, 'wm_save_variable_product_pre_order'], 10, 2);
    }

    /**
     * Initialize the Woo_Merchant_Product_Settings class
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

    // Register Pre-Order Status
    public function registerPreOrderStatus()
    {
        register_post_status('wm-pre-ordered', [
            'label'                     => _x('WM Pre Ordered', 'Order status', 'woo-merchant'),
            'public'                    => true,
            'show_in_admin_status_list'  => true,
            'show_in_admin_all_list'     => true,
            'exclude_from_search'        => false,
            'label_count'                => _n_noop(
                'Pre Ordered <span class="count">(%s)</span>',
                'Pre Ordered <span class="count">(%s)</span>',
                'woo-merchant'
            ),
        ]);
    }

    public function addPreOrderStatus($statuses)
    {
        $statuses['wm-pre-ordered'] = __('WM Pre Ordered', 'woo-merchant');
        return $statuses;
    }

    /**
     * Add a custom tab to the WooCommerce product data tabs.
     *
     * @param array $tabs Existing WooCommerce product tabs.
     * @return array Modified product tabs.
     */
    public function custom_product_meta_group_tab($tabs)
    {
        $tabs['custom_meta_group'] = array(
            'label'    => __('Woo Merchant', 'woo-merchant'),
            'target'   => 'wm-woo-merchant',
            'class'    => array('show_if_simple', 'show_if_variable'),
            'priority' => 100,
        );
        return $tabs;
    }

    /**
     * Add content to the custom product data tab.
     */
    public function custom_product_meta_group_content()
    {
        global $post;
        $options = get_option('WM_woocommerce_features_options');
?>
        <div id="wm-woo-merchant" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php echo $this->add_free_gift_fields(); ?>
                <?php
                if (!empty($options['low_stock_notification'])) {
                    echo $this->add_low_stock_quantity_threshold();
                }
                if (!empty($options['size_guide_button'])) {
                    echo $this->add_size_guide_image_upload_field_html();
                }
                echo $this->add_discount_field();
                ?>
            </div>
        </div>
    <?php
    }

    /**
     * Save custom product meta data.
     *
     * @param int $post_id Post ID of the product.
     */
    public function custom_save_product_meta_group($post_id)
    {
        if (isset($_POST['on_off_option'])) {
            $on_off_value = sanitize_text_field($_POST['on_off_option']);
            update_post_meta($post_id, '_on_off_option', $on_off_value);
        }
    }

    /**
     * Add discount-related fields to the product data tab.
     *
     * @return string Discount fields HTML.
     */
    private function add_discount_field()
    {
        global $post;
        ob_start();
    ?>
        <div class="options_group">
            <?php
            woocommerce_wp_select(array(
                'id'          => '_enable_discount',
                'label'       => __('Enable discount', 'woo-merchant'),
                'description' => __('Enable discount for this product', 'woo-merchant'),
                'desc_tip'    => 'true',
                'options'     => array(
                    'no'  => __('No', 'woo-merchant'),
                    'yes' => __('Yes', 'woo-merchant'),
                ),
                'value'       => get_post_meta($post->ID, '_enable_discount', true),
            ));

            woocommerce_wp_select(array(
                'id'          => '_discount_type',
                'label'       => __('Discount Type', 'woo-merchant'),
                'description' => __('Select discount type', 'woo-merchant'),
                'desc_tip'    => 'true',
                'wrapper_class' => '_discount_fields',
                'options'     => array(
                    'fixed'  => __('Discount Fixed Price', 'woo-merchant'),
                    'percentage' => __('Discount Percentage', 'woo-merchant'),
                ),
                'value'       => get_post_meta($post->ID, '_discount_type', true),
            ));

            woocommerce_wp_text_input(array(
                'id'          => '_discount_value',
                'label'       => __('Discount Value', 'woo-merchant'),
                'description' => __('Enter discount value.', 'woo-merchant'),
                'desc_tip'    => 'true',
                'wrapper_class' => '_discount_fields',
                'type'        => 'number',
                'custom_attributes' => array('step' => '0.01', 'min' => '0'),
                'value'       => get_post_meta($post->ID, '_discount_value', true),
            ));

            woocommerce_wp_text_input(array(
                'id'          => '_discount_threshold',
                'label'       => __('Discount Threshold', 'woo-merchant'),
                'description' => __('Threshold amount for discount.', 'woo-merchant'),
                'desc_tip'    => 'true',
                'wrapper_class' => '_discount_fields',
                'type'        => 'number',
                'custom_attributes' => array('step' => '0.01', 'min' => '0'),
                'value'       => get_post_meta($post->ID, '_discount_threshold', true),
            ));
            $product_page_hooks = array(
                'woocommerce_before_single_product' => 'Before Single Product',
                'woocommerce_before_single_product_summary' => 'Before Product Summary',
                'woocommerce_single_product_summary' => 'Product Summary',
                'woocommerce_after_single_product_summary' => 'After Product Summary',
                'woocommerce_after_single_product' => 'After Single Product',
            );
            woocommerce_wp_select(array(
                'id'          => '_discount_display_position',
                'label'       => __('Select Discount Display Position', 'woo-merchant'),
                'options'     => $product_page_hooks,
                'desc_tip'    => 'true',
                'wrapper_class' => '_discount_fields',
                'description' => __('Select the WooCommerce hook to apply.', 'woo-merchant'),
            ));
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Save discount-related fields.
     *
     * @param int $post_id Post ID of the product.
     */
    public function save_discount_fields($post_id)
    {
        // Verify nonce and user capabilities
        if (!current_user_can('edit_product', $post_id)) {
            return;
        }

        $product = wc_get_product($post_id);
        if (!$product) {
            return;
        }

        // Define allowed values for validation
        $allowed_discount_types = array('fixed', 'percentage');
        $allowed_hooks = array(
            'woocommerce_before_single_product',
            'woocommerce_before_single_product_summary',
            'woocommerce_single_product_summary',
            'woocommerce_after_single_product_summary',
            'woocommerce_after_single_product'
        );

        // Save Discount Enable/Disable
        $enable_discount = isset($_POST['_enable_discount']) && in_array($_POST['_enable_discount'], array('yes', 'no'))
            ? sanitize_text_field($_POST['_enable_discount'])
            : 'no';
        $product->update_meta_data('_enable_discount', $enable_discount);

        // Save Discount Type with validation
        $discount_type = isset($_POST['_discount_type']) && in_array($_POST['_discount_type'], $allowed_discount_types)
            ? sanitize_text_field($_POST['_discount_type'])
            : '';
        $product->update_meta_data('_discount_type', $discount_type);

        // Save Discount Value with validation
        $discount_value = isset($_POST['_discount_value'])
            ? floatval(sanitize_text_field($_POST['_discount_value']))
            : 0;
        $product->update_meta_data('_discount_value', $discount_value);

        // Save Discount Threshold with validation
        $discount_threshold = isset($_POST['_discount_threshold'])
            ? floatval(sanitize_text_field($_POST['_discount_threshold']))
            : 0;
        $product->update_meta_data('_discount_threshold', $discount_threshold);

        // Save Discount Display Position with validation
        $discount_display_position = isset($_POST['_discount_display_position']) && in_array($_POST['_discount_display_position'], $allowed_hooks)
            ? sanitize_text_field($_POST['_discount_display_position'])
            : '';
        $product->update_meta_data('_discount_display_position', $discount_display_position);

        $product->save();
    }

    /**
     * Add low stock quantity threshold field to the product data tab.
     *
     * @return string
     */
    private function add_low_stock_quantity_threshold()
    {
        global $post;
        ob_start();
        woocommerce_wp_text_input(array(
            'id'          => 'low_stock_quantity',
            'label'       => __('Low Stock Quantity Threshold', 'woo-merchant'),
            'description' => __('Enter the quantity threshold.', 'woo-merchant'),
            'desc_tip'    => 'true',
            'type'        => 'number',
            'custom_attributes' => array('step' => '1', 'min' => '0'),
            'value'       => get_post_meta($post->ID, 'low_stock_quantity', true),
        ));
        return ob_get_clean();
    }

    /**
     * Save low stock quantity field.
     *
     * @param int $post_id Post ID of the product.
     */
    public function save_low_stock_quantity_field($post_id)
    {
        $product = wc_get_product($post_id);
        $low_stock_quantity = isset($_POST['low_stock_quantity']) ? sanitize_text_field($_POST['low_stock_quantity']) : '';
        $product->update_meta_data('low_stock_quantity', $low_stock_quantity);
        $product->save();
    }

    /**
     * Add free gift fields to the product data tab.
     */
    private function add_free_gift_fields()
    {
        global $post;

        // Only for product post type
        if ($post->post_type === 'product') {
            ob_start();
        ?>
            <div class="options_group">
                <?php
                // First dropdown (Yes/No)
                woocommerce_wp_select(array(
                    'id'      => '_WM_free_gift_yes_no',
                    'label'   => __('Enable Free Gift with Woo Merchant', 'woo-merchant'),
                    'options' => array(
                        'no'  => __('No', 'woo-merchant'),
                        'yes' => __('Yes', 'woo-merchant'),
                    ),
                ));

                // Free gift product ID field (initially hidden)
                ?>
                <p class="form-field _free_gift_fields" style="display: none;">
                    <label for="product_search"><?php _e('Free Gift Product:', 'woo-merchant'); ?></label>
                    <select id="product_search" name="_free_gift_product_id" class="wc-product-search" style="width: 50%;"
                        data-placeholder="<?php esc_attr_e('Free Gift Product...', 'woo-merchant'); ?>"
                        data-action="woocommerce_json_search_products"
                        data-exclude="<?php echo absint($post->ID); ?>">
                        <?php
                        // Retrieve the selected product
                        $selected_product_id = get_post_meta($post->ID, '_free_gift_product_id', true);
                        if ($selected_product_id) {
                            $product = wc_get_product($selected_product_id);
                            echo '<option value="' . esc_attr($selected_product_id) . '" selected="selected">' . esc_html($product->get_name()) . '</option>';
                        }
                        ?>
                    </select>
                </p>
                <?php

                // Promotion type field (initially hidden)
                woocommerce_wp_select(array(
                    'id' => '_free_gift_promotion_type',
                    'label' => __('Free Gift Promotion Type', 'woo-merchant'),
                    'description' => __('Select the type of free gift promotion.', 'woo-merchant'),
                    'desc_tip' => true,
                    'class' => 'select short', // Add a class for JavaScript targeting
                    'wrapper_class' => '_free_gift_fields', // Add a class for JavaScript targeting
                    'options' => array(
                        '' => __('Select Promotion Type', 'woo-merchant'),
                        'buy_one_get_one' => __('Buy One Get One Free', 'woo-merchant'),
                        'buy_two_get_one' => __('Buy Two Get One Free', 'woo-merchant'),
                    ),
                    'value' => get_post_meta($post->ID, '_free_gift_promotion_type', true),
                ));

                $product_page_hooks = array(
                    'woocommerce_before_single_product' => 'Before Single Product',
                    'woocommerce_before_single_product_summary' => 'Before Product Summary',
                    'woocommerce_single_product_summary' => 'Product Summary',
                    'woocommerce_after_single_product_summary' => 'After Product Summary',
                    'woocommerce_after_single_product' => 'After Single Product',
                );
                woocommerce_wp_select(array(
                    'id'          => '_free_gift_display_position',
                    'label'       => __('Select Free Gift Display Position', 'woo-merchant'),
                    'options'     => $product_page_hooks,
                    'desc_tip'    => 'true',
                    'wrapper_class' => '_free_gift_fields',
                    'description' => __('Select the WooCommerce hook to apply.', 'woo-merchant'),
                ));
                ?>
            </div>
        <?php
            return ob_get_clean();
        } else {
            return;
        }
    }

    /**
     * Save free gift fields.
     * 
     * @param int $post_id Post ID of the product.
     * 
     */
    public function save_free_gift_fields($post_id)
    {
        // Verify nonce and user capabilities
        if (!current_user_can('edit_product', $post_id)) {
            return;
        }

        $product = wc_get_product($post_id);
        if (!$product) {
            return;
        }

        // Define allowed values for validation
        $allowed_promo_types = array('', 'buy_one_get_one', 'buy_two_get_one');
        $allowed_hooks = array(
            'woocommerce_before_single_product',
            'woocommerce_before_single_product_summary',
            'woocommerce_single_product_summary',
            'woocommerce_after_single_product_summary',
            'woocommerce_after_single_product'
        );

        // Save Free Gift Enable/Disable
        $free_gift_enabled = isset($_POST['_WM_free_gift_yes_no']) && in_array($_POST['_WM_free_gift_yes_no'], array('yes', 'no'))
            ? sanitize_text_field($_POST['_WM_free_gift_yes_no'])
            : 'no';
        $product->update_meta_data('_WM_free_gift_yes_no', $free_gift_enabled);

        // Save Free Gift Product ID with validation
        $free_gift_product_id = isset($_POST['_free_gift_product_id'])
            ? absint($_POST['_free_gift_product_id'])
            : 0;
        if ($free_gift_product_id && get_post_type($free_gift_product_id) === 'product') {
            $product->update_meta_data('_free_gift_product_id', $free_gift_product_id);
        } else {
            $product->delete_meta_data('_free_gift_product_id');
        }

        // Save Promotion Type with validation
        $promotion_type = isset($_POST['_free_gift_promotion_type']) && in_array($_POST['_free_gift_promotion_type'], $allowed_promo_types)
            ? sanitize_text_field($_POST['_free_gift_promotion_type'])
            : '';
        $product->update_meta_data('_free_gift_promotion_type', $promotion_type);

        // Save Free Gift Display Position with validation
        $free_gift_display_position = isset($_POST['_free_gift_display_position']) && in_array($_POST['_free_gift_display_position'], $allowed_hooks)
            ? sanitize_text_field($_POST['_free_gift_display_position'])
            : '';
        $product->update_meta_data('_free_gift_display_position', $free_gift_display_position);

        $product->save();
    }

    /**
     * Add size guide image upload field.
     */
    private function add_size_guide_image_upload_field_html()
    {
        global $post;
        ob_start();
        $size_guide_image = get_post_meta($post->ID, '_size_guide_image', true);
        ?>
        <div class="options_group">
            <p class="form-field">
                <label for="size_guide_image"><?php _e('Size Guide Image', 'woo-merchant'); ?></label>
                <input type="hidden" id="size_guide_image" name="size_guide_image" value="<?php echo esc_attr($size_guide_image); ?>" />
                <button type="button" class="upload_image_button button"><?php _e('Upload/Add image', 'woo-merchant'); ?></button>
                <img id="size_guide_image_preview" src="<?php echo esc_url($size_guide_image); ?>" style="max-width: 150px; display: <?php echo $size_guide_image ? 'block' : 'none'; ?>;" />
                <button type="button" class="remove_image_button button" style="display: <?php echo $size_guide_image ? 'inline-block' : 'none'; ?>;"><?php _e('Remove image', 'woo-merchant'); ?></button>
            </p>
        </div>
<?php
        return ob_get_clean();
    }

    /**
     * Save size guide image upload field.
     * 
     * @param int $post_id Post ID of the product.
     * 
     */
    public function save_size_guide_image_upload_field($post_id)
    {
        if (isset($_POST['size_guide_image'])) {
            update_post_meta($post_id, '_size_guide_image', esc_url_raw($_POST['size_guide_image']));
        }
    }

    /**
     * Add cross-sell options to the product linked products tab.
     */
    public function add_cross_sell_options_linked_products_tab()
    {
        global $post;

        // Define WooCommerce hooks for the second dropdown
        $product_page_hooks = array(
            'woocommerce_before_single_product' => 'Before Single Product',
            'woocommerce_before_single_product_summary' => 'Before Product Summary',
            'woocommerce_single_product_summary' => 'Product Summary',
            'woocommerce_after_single_product_summary' => 'After Product Summary',
            'woocommerce_after_single_product' => 'After Single Product',
        );

        echo '<div class="options_group">';

        // First dropdown (Yes/No)
        woocommerce_wp_select(array(
            'id'      => '_WM_cross_sells_yes_no',
            'label'   => __('Enable Cross-sells with Woo Merchant', 'woo-merchant'),
            'options' => array(
                'no'  => __('No', 'woo-merchant'),
                'yes' => __('Yes', 'woo-merchant'),
            ),
        ));

        // Second dropdown (WooCommerce Product Page Hooks) - Initially hidden
        woocommerce_wp_select(array(
            'id'          => '_WM_cross_sells_display_position',
            'label'       => __('Select Display Position', 'woo-merchant'),
            'options'     => $product_page_hooks,
            'desc_tip'    => 'true',
            'wrapper_class' => '_cross_sell_field',
            'description' => __('Select the WooCommerce hook to apply.', 'woo-merchant'),
            //'class'       => 'custom_product_hook_field',
            //'style'       => 'display: none;', // Initially hidden
        ));

        echo '</div>';
    }

    /**
     * Save cross-sell options fields.
     * 
     * @param int $post_id Post ID of the product.
     * 
     */
    public function save_custom_linked_products_fields($post_id)
    {
        $_WM_cross_sells_yes_no = isset($_POST['_WM_cross_sells_yes_no']) ? sanitize_text_field($_POST['_WM_cross_sells_yes_no']) : 'no';
        update_post_meta($post_id, '_WM_cross_sells_yes_no', $_WM_cross_sells_yes_no);

        $_WM_cross_sells_display_position = isset($_POST['_WM_cross_sells_display_position']) ? sanitize_text_field($_POST['_WM_cross_sells_display_position']) : '';
        update_post_meta($post_id, '_WM_cross_sells_display_position', $_WM_cross_sells_display_position);
    }

    /**
     * Add pre-order option to the product Inventory tab with stock .
     */
    public function wm_add_simple_product_pre_order()
    {
        $post_id = get_the_ID(); // Store post ID to avoid redundant function calls

        echo '<div class="options_group form-row form-row-full hide_if_variable">';

        // Pre-Order Checkbox
        woocommerce_wp_checkbox([
            'id'          => 'wm_pre_order',
            'label'       => __('Enable Pre-Order', 'woo-merchant'),
            'description' => __('Check this box to allow customers to pre-order this product before it becomes available.', 'woo-merchant'),
            'value'       => get_post_meta($post_id, 'wm_pre_order', true),
        ]);

        // Pre-Order Date Input
        woocommerce_wp_text_input([
            'id'          => 'wm_pre_order_date',
            'label'       => __('Pre-Order Availability Date', 'woo-merchant'),
            'placeholder' => date('Y-m-d H:i:s'), // 24-hour format
            'class'       => 'datepicker',
            'desc_tip'    => true,
            'description' => __('Specify the date and time when this pre-order product will be available for purchase.', 'woo-merchant'),
            'value'       => esc_attr(get_post_meta($post_id, 'wm_pre_order_date', true)), // Ensure proper escaping
        ]);

        echo '</div>';
    }


    /**
     * Save pre-order options fields.
     * 
     * @param int $post_id Post ID of the product.
     * 
     */
    public function wm_save_simple_product_pre_order($post_id)
    {

        $product = wc_get_product($post_id);
        if (! $product) {
            return;
        }

        // Save pre-order status
        $wm_pre_order = isset($_POST['wm_pre_order']) ? 'yes' : 'no';
        $product->update_meta_data('wm_pre_order', $wm_pre_order);

        // Save pre-order date only if pre-order is enabled
        if ($wm_pre_order === 'yes' && ! empty($_POST['wm_pre_order_date'])) {
            $wm_pre_order_date = sanitize_text_field($_POST['wm_pre_order_date']);
            $product->update_meta_data('wm_pre_order_date', $wm_pre_order_date);
        } else {
            $product->delete_meta_data('wm_pre_order_date'); // Remove field if pre-order is disabled
        }

        // Save product meta
        $product->save();
    }



    /**
     * Add Pre-Order options to variable product variations.
     *
     * @param int $loop    
     * @param array $variation_data
     * @param WP_Post $variation
     */
    public function wm_add_variable_product_pre_order($loop, $variation_data, $variation)
    {
        echo '<div class="options_group form-row form-row-full">';

        // Pre-Order Checkbox
        woocommerce_wp_checkbox([
            'id'          => 'wm_pre_order_' . $variation->ID,
            'label'       => __('Enable Pre-Order', 'woo-merchant'),
            'description' => __('Check this box to allow customers to pre-order this product variation before it becomes available.', 'woo-merchant'),
            'value'       => get_post_meta($variation->ID, 'wm_pre_order', true),
        ]);

        // Pre-Order Date Input
        woocommerce_wp_text_input([
            'id'          => 'wm_pre_order_date_' . $variation->ID,
            'label'       => __('Pre-Order Availability Date', 'woo-merchant'),
            'placeholder' => date('Y-m-d H:i:s'), // 24-hour format
            'class'       => 'datepicker',
            'desc_tip'    => true,
            'description' => __('Specify the date and time when this pre-order variation will be available for purchase.', 'woo-merchant'),
            'value'       => esc_attr(get_post_meta($variation->ID, 'wm_pre_order_date', true)), // Ensure proper escaping
        ]);

        echo '</div>';
    }

    /**
     * Save Pre-Order options for variable product variations.
     *
     * @param int $post_id The variation ID.
     */
    public function wm_save_variable_product_pre_order($post_id)
    {
        $product = wc_get_product($post_id);
        if (!$product) {
            return;
        }

        // Save pre-order status
        $wm_pre_order = isset($_POST['wm_pre_order_' . $post_id]) ? 'yes' : 'no';
        $product->update_meta_data('wm_pre_order', $wm_pre_order);

        // Save pre-order date only if pre-order is enabled
        if ($wm_pre_order === 'yes' && !empty($_POST['wm_pre_order_date_' . $post_id])) {
            $wm_pre_order_date = sanitize_text_field($_POST['wm_pre_order_date_' . $post_id]);
            $product->update_meta_data('wm_pre_order_date', $wm_pre_order_date);
        } else {
            $product->delete_meta_data('wm_pre_order_date'); // Remove field if pre-order is disabled
        }

        // Save product meta
        $product->save();
    }
}
