<?php

/**
 * Woo_Merchant_Feature_Settings class
 *
 * Handles the admin settings for WooCommerce merchant features.
 *
 * @since 1.0.0
 */
class Woo_Merchant_Feature_Settings
{

    private $options;

    /**
     * Constructor to initialize the settings
     *
     * Sets the options from the database and includes necessary actions.
     * 
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->options = get_option('WM_woocommerce_features_options');
        $this->includes();
    }

    /**
     * Initialize the Woo_Merchant_Feature_Settings class
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

    /**
     * Includes the necessary hooks and actions for the settings page
     *
     * Registers the settings page and the settings fields.
     * 
     * @since 1.0.0
     */
    public function includes()
    {
        add_action('admin_menu', array($this, 'add_settings_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Registers settings for WooCommerce merchant features
     *
     * Registers the settings and sanitization function for the options.
     * 
     * @since 1.0.0
     */
    public function register_settings()
    {
        register_setting('WM_woocommerce_features', 'WM_woocommerce_features_options', [
            'sanitize_options' => function ($input) {
                // Fix: Merge with old options to avoid losing hidden/unsubmitted data
                $old_options = get_option('WM_woocommerce_features_options', []);
                return array_merge($old_options, $input);
            }
        ]);
    }

    /**
     * Adds the settings menu to the WordPress admin
     *
     * Creates a menu item for the settings page.
     * 
     * @since 1.0.0
     */
    public function add_settings_menu()
    {
        add_menu_page(
            __('WooCommerce Merchant Features', 'woo-merchant'), // Translatable page title
            __('Woo Merchant', 'woo-merchant'), // Translatable menu title
            'manage_options', // Capability
            'WM-woocommerce-features', // Menu slug
            array($this, 'render_settings_page'), // Function to render the page
            WOO_MERCHANT_PLUGIN_URL . 'assets/images/woo-merchant-icon.svg'
        );
    }

    /**
     * Renders the settings page for WooCommerce merchant features
     *
     * Displays the form with all the options for enabling and configuring features.
     * 
     * @since 1.0.0
     */
    public function render_settings_page()
    {
?>
        <div class="wrap woo-merchant-setting-page">
            <h1><?php echo esc_html(__('WooCommerce Merchant Settings', 'woo-merchant')); ?></h1> <!-- Translatable Heading -->
            <form method="post" action="options.php">
                <?php settings_fields('WM_woocommerce_features'); ?>
                <table class="form-table">
                    <?php
                    // Woocommerce Checkout
                    $this->settings_field_container_start(__('WooCommerce Checkout ', 'woo-merchant')); // Translatable Section Title
                    $this->render_field(
                        array(
                            'id' => 'woo_merchant_checkout',
                            'title' => __('Enable WooCommerce Checkout Styles', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );
                    $this->render_field(
                        array(
                            'id' => 'woo_merchant_checkout_style',
                            'title' => __('Select Checkout Style ', 'woo-merchant'),
                            'type' => 'select',
                            'options' => array(
                                'style-1' => 'Style 1',
                                'style-2' => 'Style 2'
                            ),
                            'depends' => 'woo_merchant_checkout'
                        )
                    );
                    $this->settings_field_container_end();
                    // Starting CROSS SELL
                    $this->settings_field_container_start(__('Woo Merchant Cross Sell', 'woo-merchant')); // Translatable Section Title
                    $this->render_field(
                        array(
                            'id' => 'frequently_bought_together',
                            'title' => __('Enable Frequently Bought Together', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );
                    $this->settings_field_container_end();

                    // Starting Discount Settings
                    $this->settings_field_container_start(__('Woo Merchant Spend & Save Discounts', 'woo-merchant'));
                    $this->render_field(
                        array(
                            'id' => 'WM_spend_save_discount',
                            'title' => __('Enable Spend & Save Discount', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );
                    $this->render_field(
                        array(
                            'id' => 'WM_spend_save_type',
                            'title' => __('Discount Offer', 'woo-merchant'),
                            'type' => 'select',
                            'options' => array('fixed' => __('Fixed Amount', 'woo-merchant'), 'percentage' => __('Percentage', 'woo-merchant')),
                            'depends' => 'WM_spend_save_discount',
                        )
                    ); // Translatable Field Options
                    $this->render_field(
                        array(
                            'id' => 'WM_spend_save_value',
                            'title' => __('Discount value', 'woo-merchant'),
                            'type' => 'number',
                            'depends' => 'WM_spend_save_discount',
                        )
                    );
                    $this->render_field(
                        array(
                            'id' => 'WM_spend_save_threshhold',
                            'title' => __('Spent Amount Threshold', 'woo-merchant'),
                            'type' => 'number',
                            'depends' => 'WM_spend_save_discount',
                        )
                    );
                    $this->settings_field_container_end();
                    // Starting Discount Settings
                    $this->settings_field_container_start(__('Woo Merchant Spend & Get a Gift offer', 'woo-merchant'));
                    $this->render_field(
                        array(
                            'id' => 'WM_spend_gift_offer',
                            'title' => __('Enable Spend & Get a Gift offer', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );
                    wp_enqueue_script('woocommerce_admin');
                    wp_enqueue_script('wc-enhanced-select');
                    wp_enqueue_style('woocommerce_admin_styles');
                    $selected_free_gift = $this->options['WM_spend_gift_product'] ?? '';
                    echo '<tr class="WM_spend_gift_product conditional-with-WM_spend_gift_offer" style="display: ' . (empty($this->options['WM_spend_gift_offer']) ? 'none' : 'table-row') . ';">
                        <th scope="row">' . __('Select Free Product', 'woo-merchant') . '</th>
                        <td>
                            <select id="product_search" 
                                    name="WM_woocommerce_features_options[WM_spend_gift_product]" 
                                    class="wc-product-search" 
                                    data-placeholder="' . esc_attr__('Select Free Gift Product', 'woo-merchant') . '" 
                                    data-action="woocommerce_json_search_products">';

                    if ($selected_free_gift) {
                        $product = wc_get_product($selected_free_gift);
                        echo '<option value="' . esc_attr($selected_free_gift) . '" selected="selected">' . esc_html($product->get_name()) . '</option>';
                    }
                    echo ' </select>
                        </td>
                    </tr>';
                    $this->render_field(
                        array(
                            'id' => 'WM_spend_gift_threshhold',
                            'title' => __('Spent Amount Threshold', 'woo-merchant'),
                            'type' => 'number',
                            'depends' => 'WM_spend_gift_offer',
                        )
                    );
                    $this->settings_field_container_end();

                    // Sale End Countdown
                    $this->settings_field_container_start(__('Woo Merchant Sale End CountDown', 'woo-merchant'));
                    $this->render_field(
                        array(
                            'id' => 'sale_end_countdown',
                            'title' => __('Enable Sale End Countdown', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );
                    $sale_end_hooks['woo_merchant_sale_end_countdown'] = 'Woo Merchant Sale End Countdown';
                    $this->render_field(
                        array(
                            'id' => 'sale_end_countdown_position',
                            'title' => __('Sale End Countdown Display Position', 'woo-merchant'),
                            'type' => 'select',
                            'options' => $sale_end_hooks,
                            'depends' => 'sale_end_countdown'
                        )
                    );

                    $this->settings_field_container_end();

                    // Low Stock Notification
                    $this->settings_field_container_start(__('Woo Merchant Low Stock Notification', 'woo-merchant'));
                    $this->render_field(
                        array(
                            'id' => 'low_stock_notification',
                            'title' => __('Enable Low Stock Notification', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );
                    $low_stock_hooks['woo_merchant_low_stock_notification'] = 'Woo Merchant Low Stock Notification';
                    $this->render_field(
                        array(
                            'id' => 'low_stock_notification_position',
                            'title' => __('Low Stock Notification Display Position', 'woo-merchant'),
                            'type' => 'select',
                            'options' => $low_stock_hooks,
                            'depends' => 'low_stock_notification'
                        )
                    );

                    $this->settings_field_container_end();

                    // Buy Now Button
                    $this->settings_field_container_start(__('Woo Merchant Buy Now Button', 'woo-merchant'));
                    $this->render_field(
                        array(
                            'id' => 'buy_now_button',
                            'title' => __('Enable Buy Now Button', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );

                    $this->settings_field_container_end();

                    // Size Guide Button
                    $this->settings_field_container_start(__('Woo Merchant Size Guide Button', 'woo-merchant'));
                    $this->render_field(
                        array(
                            'id' => 'size_guide_button',
                            'title' => __('Enable Size Guide Button', 'woo-merchant'),
                            'type' => 'switch'
                        )
                    );
                    $this->render_field(
                        array(
                            'id' => 'manage_size_guide_button',
                            'title' => __('Manage Size Guide Button', 'woo-merchant'),
                            'type' => 'select',
                            'options' => array('widget' => __('By Elementor Widget', 'woo-merchant'), 'hooks' => __('By Product Page Hook', 'woo-merchant')),
                            'depends' => 'size_guide_button'
                        )
                    );
                    $size_guide_hooks['woo_merchant_sale_end_countdown'] = 'Woo Merchant Sale End Countdown';
                    $this->render_field(
                        array(
                            'id' => 'size_guide_button_position',
                            'title' => __('Size Guide Button Display Position', 'woo-merchant'),
                            'type' => 'select',
                            'options' => $size_guide_hooks,
                            'depends' => 'size_guide_button',
                            'conditional'  => array(
                                'manage_size_guide_button' => 'hooks'
                            )
                        )
                    );
                    $this->settings_field_container_end();
                    ?>
                </table>
                <?php submit_button(__('Save Settings', 'woo-merchant')); ?> <!-- Translatable Save Button -->
            </form>
        </div>
<?php
    }

    /**
     * settings_field_container_start
     *
     * @param string $title
     * 
     * @since 1.0.0
     */
    private function settings_field_container_start($title)
    {
        echo '<tr class="wm-settings-section">';
        echo '<th colspan="2"><h2>' . esc_html($title) . '</h2></th>';
    }

    /**
     * Ends the current field container in the settings table
     *
     * @since 1.0.0
     */
    private function settings_field_container_end()
    {
        echo '</tr>';
    }

    /**
     * Renders a form field in the settings table
     *
     * @param array $args
     * 
     * @since 1.0.0
     */
    private function render_field($args)
    {
        $value = $this->options[$args['id']] ?? '';
        $depends = $args['depends'] ?? '';
        $conditional = $args['conditional'] ?? [];
        $atrr = $conditional_class = '';

        if (is_array($conditional) && !empty($conditional)) {
            foreach ($conditional as $field => $val) {
                $conditional_class = 'WM-conditional-field';
                $atrr .= 'data-conditional-field=' . $field;
                $atrr .= ' data-conditional-value=' . $val;
            }
        }

        echo '<tr ' . $atrr . ' class="' . esc_attr($args['id'] . '_container') . ' ' . esc_attr('conditional-with-' . $depends) . ' ' . $conditional_class . '" style="display: ' . ($depends && empty($this->options[$depends]) ? 'none' : 'table-row') . ';">';
        echo '<th scope="row">' . esc_html($args['title']) . '</th>';
        echo '<td>';

        switch ($args['type']) {
            case 'switch':
                echo '<label class="switch">';
                echo '<input data-name="' . esc_attr($args['id']) . '"  type="checkbox" name="WM_woocommerce_features_options[' . esc_attr($args['id']) . ']" value="1" ' . checked(1, $value, false) . ' />';
                echo '<span class="slider round"></span>';
                echo '</label>';
                break;

            case 'select':
                echo '<select name="WM_woocommerce_features_options[' . esc_attr($args['id']) . ']" data-name=' . esc_attr($args['id']) . '>';
                foreach ($args['options'] as $key => $label) {
                    echo '<option value="' . esc_attr($key) . '" ' . selected($value, $key, false) . '>' . esc_html($label) . '</option>';
                }
                echo '</select>';
                break;

            case 'number':
                echo '<input type="number" name="WM_woocommerce_features_options[' . esc_attr($args['id']) . ']" value="' . esc_attr($value) . '" />';
                break;

            case 'text':
                echo '<input type="text" name="WM_woocommerce_features_options[' . esc_attr($args['id']) . ']" value="' . esc_attr($value) . '" />';
                break;

            case 'email':
                echo '<input type="email" name="WM_woocommerce_features_options[' . esc_attr($args['id']) . ']" value="' . esc_attr($value) . '" />';
                break;

                // Add more field types as needed
        }

        echo '</td>';
        echo '</tr>';
    }

    private function render_custom_content_field()
    {
        $custom_contents = $this->options['custom_contents'] ?? [];

        echo '<tr><th colspan="2"><h2>Custom Contents</h2></th></tr>';
        foreach ($custom_contents as $index => $content) {
            $this->render_content_editor($index, $content);
        }

        // Empty template for new entries
        $this->render_content_editor('new_template', ['content' => '', 'position' => ''], true);

        echo '<tr><td colspan="2"><button type="button" id="add-new-content" class="button">Add New</button></td></tr>';
    }

    private function render_content_editor($index, $content, $hidden = false)
    {
        if ($index === 'new_template' && !$hidden) {
            return; // Skip rendering the actual template row when not hidden
        }

        $editor_id = 'custom_content_' . $index;
        $position_id = 'custom_content_position_' . $index;
        $hidden_class = $hidden ? 'hidden' : '';

        echo '<tr class="custom-content-row ' . $hidden_class . '" data-index="' . $index . '">';
        echo '<th scope="row">Content ' . (is_numeric($index) ? $index + 1 : '') . '</th>';
        echo '<td>';


        $name = 'WM_woocommerce_features_options[custom_contents][' . $index . '][content]';

        wp_editor($content['content'], $editor_id, [
            'textarea_name' => $name,
            'editor_height' => 200
        ]);

        echo '<select name="WM_woocommerce_features_options[custom_contents][' . $index . '][position]" id="' . $position_id . '">';
        $hooks = get_woocommerce_product_detail_hooks();
        foreach ($hooks as $hook => $label) {
            echo '<option value="' . esc_attr($hook) . '" ' . selected($content['position'], $hook, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';

        // Add delete button for all but the template row
        if ($index !== 'new_template') {
            echo '<button type="button" class="button remove-content">Delete</button>';
        }

        echo '</td>';
        echo '</tr>';
    }

    /**
     * Sanitizes the input from the settings page
     *
     * @param array $input The raw input data from the form
     * 
     * @return array The sanitized input data
     * 
     * @since 1.0.0
     */
    public function sanitize_options($input)
    {
        if (!current_user_can('manage_options')) {
            return $this->options;
        }

        $sanitized = array();

        // Sanitize switch fields
        $switch_fields = array(
            'woo_merchant_checkout',
            'frequently_bought_together',
            'WM_spend_save_discount',
            'WM_spend_gift_offer',
            'sale_end_countdown',
            'low_stock_notification',
            'buy_now_button',
            'size_guide_button'
        );

        foreach ($switch_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? 1 : 0;
        }

        // Sanitize select fields
        foreach ($input as $key => $value) {
            if (strpos($key, '_position') !== false || $key === 'WM_spend_save_type') {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }

        // Sanitize numeric fields  
        $numeric_fields = array(
            'WM_spend_save_value',
            'WM_spend_save_threshhold',
            'WM_spend_gift_threshhold'
        );

        foreach ($numeric_fields as $field) {
            if (isset($input[$field])) {
                $sanitized[$field] = floatval($input[$field]);
            }
        }

        // Sanitize product ID field
        if (isset($input['WM_spend_gift_product'])) {
            $sanitized['WM_spend_gift_product'] = absint($input['WM_spend_gift_product']);
        }

        return $sanitized;
    }
}
