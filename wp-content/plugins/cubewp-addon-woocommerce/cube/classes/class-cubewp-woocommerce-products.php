<?php

defined('ABSPATH') || exit;

/**
 * CubeWp_Woocommerce_Products - Optimized version
 */
class CubeWp_Woocommerce_Products
{
    public static $wooCommerce_post_type = 'product';
    private static $initialized = false;
    private static $cached_fields = array();
    private static $cached_options = array();

    /**
     * Initialize product hooks and filters
     */
    public function __construct()
    {
        // Only initialize once
        if (self::$initialized) {
            return;
        }
        
        self::$initialized = true;

        // Add product type to CubeWP builder
        add_filter('cubewp/builder/post_types', [$this, 'cubewp_woocommerce_post_types_into_cubewp'], 10, 2);

        // Add custom product fields sections with optimized callback
        add_filter('cubewp/builder/post_type/custom/cubes/sections', [$this, 'handle_custom_cubes_section'], 10, 2);
        add_filter('cubewp/builder/search_filters/custom/cubes/sections', [$this, 'handle_custom_cubes_section'], 10, 2);
        add_filter('cubewp/builder/search_fields/custom/cubes/sections', [$this, 'handle_custom_cubes_section'], 10, 2);

        add_filter('cubewp/custom/cube/field/options', array(
            $this,
            'cubewp_woocommerce_custom_cubes_options',
        ));

        add_filter('cubewp/' . self::$wooCommerce_post_type . '/after/submit/actions', array(
            $this,
            'cubewp_woocommerce_after_product_submission',
        ), 11, 2);

        add_filter('cubewp/frontend/field/parametrs', array(
            $this,
            'cubewp_woocommerce_frontend_fields_arguments',
        ), 20);

        add_filter('cubewp/settings/excluded/external/post_types', array(
            $this,
            'cubewp_woocommerce_exclude_product_post_type',
        ), 20);

        // Only add WooCommerce-specific hooks if WooCommerce is active
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_payment_complete', array(
                $this,
                'cubewp_woocommerce_after_product_payment'
            ));

            add_action('woocommerce_after_main_content', array(
                $this,
                'cubewp_woocommerce_single_product'
            ));
        }

        add_filter('cubewp/builder/loop_builder/custom/cubes/sections', array(
            $this,
            'add_global_product_tags'
        ), 10, 2);

        add_filter('cubewp/post/card/tags/value', array(
            $this,
            'process_global_product_tags'
        ), 10, 4);

        add_filter('cubewp/builder/post_types', array(
            $this,
            'custom_postype_builder'
        ), 10, 2);

        // Only run update on admin init if we're in admin area
        if (is_admin()) {
            add_action('admin_init', array(
                $this,
                'cubewp_woocommerce_update_product_field_options'
            ));
        }
    }

    /**
     * Optimized handler for custom cubes sections
     */
    public function handle_custom_cubes_section($sections, $post_type)
    {
        if ($post_type == self::$wooCommerce_post_type) {
            $current_filter = current_filter();
            $form_type = str_replace(array(
                'cubewp/builder/', '/custom/cubes/sections'
            ), '', $current_filter);
            
            $sections[] = [
                'section_title'       => esc_html__('Woo Product Fields', 'cubewp-woocommerce'),
                'section_description' => '',
                'section_class'       => '',
                'open_close_class'    => 'close',
                'form_relation'       => $post_type,
                'form_type'           => $form_type,
                'fields'              => self::cubewp_woocommerce_custom_cubes($form_type),
                'section_type'        => 'group_fields',
            ];
        }
        return $sections;
    }

    /**
     * Get cached custom cubes fields
     */
    private static function cubewp_woocommerce_custom_cubes($form_type)
    {
        $cache_key = 'cubewp_wc_cubes_' . $form_type;
        
        if (isset(self::$cached_fields[$cache_key])) {
            return self::$cached_fields[$cache_key];
        }

        $cubes = [];

        // Gallery Field
        $cubes['_product_image_gallery'] = [
            'label' => esc_html__('Gallery', 'cubewp-woocommerce'),
            'name'  => '_product_image_gallery',
            'id'    => '_product_image_gallery',
            'type'  => 'gallery',
        ];
        
        // Types Fields
        $cubes['_virtual'] = [
            'label' => esc_html__('Virtual?', 'cubewp-woocommerce'),
            'name'  => '_virtual',
            'type'  => 'switch',
        ];
        
        $cubes['_downloadable'] = [
            'label' => esc_html__('Downloadable?', 'cubewp-woocommerce'),
            'name'  => '_downloadable',
            'id'    => '_downloadable',
            'type'  => 'switch',
        ];
        
        // Downloads Fields
        $cubes['cwp_wc_downloadable_files'] = [
            'label' => esc_html__('Downloadable Files', 'cubewp-woocommerce'),
            'name'  => 'cwp_wc_downloadable_files',
            'id'    => 'cwp_wc_downloadable_files',
            'type'  => 'repeating_field',
        ];
        
        $cubes['_download_limit'] = [
            'label' => esc_html__('Download Limit', 'cubewp-woocommerce'),
            'name'  => '_download_limit',
            'id'    => '_download_limit',
            'type'  => 'number',
        ];
        
        $cubes['_download_expiry'] = [
            'label' => esc_html__('Download Expiry', 'cubewp-woocommerce'),
            'name'  => '_download_expiry',
            'id'    => '_download_expiry',
            'type'  => 'number',
        ];
        
        // Price Fields
        $cubes['_price'] = [
            'label' => esc_html__('Price', 'cubewp-woocommerce'),
            'name'  => '_price',
            'id'    => '_price',
            'type'  => 'number',
        ];
        
        $cubes['_regular_price'] = [
            'label' => esc_html__('Regular Price', 'cubewp-woocommerce'),
            'name'  => '_regular_price',
            'id'    => '_regular_price',
            'type'  => 'number',
        ];
        
        $cubes['_sale_price'] = [
            'label' => esc_html__('Sale Price', 'cubewp-woocommerce'),
            'name'  => '_sale_price',
            'id'    => '_sale_price',
            'type'  => 'number',
        ];
        
        // Schedule Sale Fields
        $cubes['_schedule'] = [
            'label' => esc_html__('Schedule?', 'cubewp-woocommerce'),
            'name'  => '_schedule',
            'id'    => '_schedule',
            'type'  => 'switch',
        ];
        
        $cubes['_sale_price_dates_from'] = [
            'label' => esc_html__('From', 'cubewp-woocommerce'),
            'name'  => '_sale_price_dates_from',
            'id'    => '_sale_price_dates_from',
            'type'  => 'date_picker',
        ];
        
        $cubes['_sale_price_dates_to'] = [
            'label' => esc_html__('To', 'cubewp-woocommerce'),
            'name'  => '_sale_price_dates_to',
            'id'    => '_sale_price_dates_to',
            'type'  => 'date_picker',
        ];
        
        // SKU Field
        $cubes['_sku'] = [
            'label' => esc_html__('SKU', 'cubewp-woocommerce'),
            'name'  => '_sku',
            'id'    => '_sku',
            'type'  => 'text',
        ];
        
        // External Product Fields
        $cubes['_product_url'] = [
            'label' => esc_html__('Product URL', 'cubewp-woocommerce'),
            'name'  => '_product_url',
            'id'    => '_product_url',
            'type'  => 'url',
        ];
        
        $cubes['_button_text'] = [
            'label' => esc_html__('Button Text', 'cubewp-woocommerce'),
            'name'  => '_button_text',
            'id'    => '_button_text',
            'type'  => 'text',
        ];
        
        // Manage Stock Fields
        $cubes['_manage_stock'] = [
            'label' => esc_html__('Stock Management', 'cubewp-woocommerce'),
            'name'  => '_manage_stock',
            'id'    => '_manage_stock',
            'type'  => 'switch',
        ];
        
        $cubes['_stock'] = [
            'label' => esc_html__('Stock Quantity', 'cubewp-woocommerce'),
            'name'  => '_stock',
            'id'    => '_stock',
            'type'  => 'number',
        ];
        
        $cubes['_backorders'] = [
            'label' => esc_html__('Allow Backorders?', 'cubewp-woocommerce'),
            'name'  => '_backorders',
            'id'    => '_backorders',
            'type'  => 'radio',
        ];
        
        $cubes['_low_stock_amount'] = [
            'label' => esc_html__('Low Stock Threshold', 'cubewp-woocommerce'),
            'name'  => '_low_stock_amount',
            'id'    => '_low_stock_amount',
            'type'  => 'number',
        ];
        
        // Stock Status Field
        $cubes['_stock_status'] = [
            'label' => esc_html__('Stock Status', 'cubewp-woocommerce'),
            'name'  => '_stock_status',
            'id'    => '_stock_status',
            'type'  => 'radio',
        ];
        
        // Sold Individually Field
        $cubes['_sold_individually'] = [
            'label' => esc_html__('Sold Individually', 'cubewp-woocommerce'),
            'name'  => '_sold_individually',
            'id'    => '_sold_individually',
            'type'  => 'switch',
        ];
        
        // Weight Field
        $cubes['_weight'] = [
            'label' => esc_html__('Weight (KG)', 'cubewp-woocommerce'),
            'name'  => '_weight',
            'id'    => '_weight',
            'type'  => 'text',
        ];
        
        // Dimensions Fields
        $cubes['_length'] = [
            'label' => esc_html__('Length (CM)', 'cubewp-woocommerce'),
            'name'  => '_length',
            'id'    => '_length',
            'type'  => 'text',
        ];
        
        $cubes['_width'] = [
            'label' => esc_html__('Width (CM)', 'cubewp-woocommerce'),
            'name'  => '_width',
            'id'    => '_width',
            'type'  => 'text',
        ];
        
        $cubes['_height'] = [
            'label' => esc_html__('Height (CM)', 'cubewp-woocommerce'),
            'name'  => '_height',
            'id'    => '_height',
            'type'  => 'text',
        ];
        
        $cubes['_upsell_ids'] = [
            'label' => esc_html__('Upsells', 'cubewp-woocommerce'),
            'name'  => '_upsell_ids',
            'id'    => '_upsell_ids',
            'type'  => 'post',
        ];
        
        $cubes['_crosssell_ids'] = [
            'label' => esc_html__('Cross Sells', 'cubewp-woocommerce'),
            'name'  => '_crosssell_ids',
            'id'    => '_crosssell_ids',
            'type'  => 'post',
        ];
        
        $cubes['_children'] = [
            'label' => esc_html__('Grouped Products', 'cubewp-woocommerce'),
            'name'  => '_children',
            'id'    => '_children',
            'type'  => 'post',
        ];
        
        // Attributes Fields
        $cubes['cwp_wc_product_attributes'] = [
            'label' => esc_html__('Attributes', 'cubewp-woocommerce'),
            'name'  => 'cwp_wc_product_attributes',
            'id'    => 'cwp_wc_product_attributes',
            'type'  => 'repeating_field',
        ];

        if ($form_type == 'search_fields' || $form_type == 'search_filters') {
            $voided_search_fields = array(
                '_product_image_gallery',
                'cwp_wc_downloadable_files',
                '_download_limit',
                '_download_expiry',
                '_schedule',
                '_sale_price_dates_from',
                '_sale_price_dates_to',
                '_product_url',
                '_button_text',
                '_manage_stock',
                '_stock',
                '_backorders',
                '_low_stock_amount',
                '_upsell_ids',
                '_crosssell_ids',
                '_children',
                'cwp_wc_product_attributes',
            );
            
            $voided_search_fields = apply_filters('cubewp/builder/woocommerce/product/voided/search/fields', $voided_search_fields);
            
            foreach ($voided_search_fields as $voided_search_field) {
                if (isset($cubes[$voided_search_field])) {
                    unset($cubes[$voided_search_field]);
                }
            }
        }

        $cubes = apply_filters('cubewp/builder/woocommerce/product/fields', $cubes, $form_type);
        self::$cached_fields[$cache_key] = $cubes;
        
        return $cubes;
    }

    public static function cubewp_woocommerce_update_product_field_options()
    {
        // Check if we've already updated options in this request
        if (isset(self::$cached_options['updated'])) {
            return;
        }

        $price_field_options = get_field_options('_price');
        if (empty($price_field_options) || !is_array($price_field_options)) {
            $default_options = array(
                'label' => '',
                'name' => 'cwp_field_' . wp_rand(10000000, 1000000000000),
                'type' => '',
                'description' => '',
                'map_use' => '',
                'default_value' => '',
                'minimum_value' => 0,
                'maximum_value' => 100,
                'steps_count' => 1,
                'file_types' => '',
                'upload_size' => '',
                'max_upload_files' => '',
                'placeholder' => '',
                'editor_media' => 0,
                'filter_post_types' => '',
                'filter_taxonomy' => '',
                'filter_user_roles' => '',
                'appearance' => '',
                'rel_attr' => 'do-follow',
                'current_user_posts' => '',
                'options' => '',
                'char_limit' => '',
                'admin_size' => '1/1',
                'multiple' => 0,
                'select2_ui' => 0,
                'auto_complete' => 0,
                'required' => '',
                'relationship' => 0,
                'rest_api' => 0,
                'validation_msg' => '',
                'id' => 'cwp_field_' . wp_rand(10000000, 1000000000000),
                'class' => '',
                'container_class' => '',
                'conditional' => '',
                'conditional_field' => '',
                'conditional_operator' => '',
                'conditional_value' => '',
                'sub_fields' => '',
                'fields_type' => '',
                'files_save' => 'ids',
                'files_save_separator' => 'array',
            );

            $fields = self::cubewp_woocommerce_custom_cubes('post_types');
            if (!empty($fields) && is_array($fields)) {
                foreach ($fields as $key => $field) {
                    if (is_array($field)) {
                        $fields[$key] = wp_parse_args($field, $default_options);
                    }
                }
            }
            
            $prev_fieldOptions = CWP()->get_custom_fields('post_types');
            if (!empty($prev_fieldOptions) && is_array($prev_fieldOptions)) {
                $fields = array_merge($prev_fieldOptions, $fields);
            }
            
            CWP()->update_custom_fields('post_types', $fields);
        }
        
        self::$cached_options['updated'] = true;
    }

    public static function init()
    {
        if (!self::$initialized) {
            $CubeClass = __CLASS__;
            new $CubeClass();
        }
    }

    public function cubewp_woocommerce_single_product()
    {
        if (is_singular(self::$wooCommerce_post_type)) {
            CubeWp_Enqueue::enqueue_style('single-cpt-styles');
            CubeWp_Enqueue::enqueue_script('cwp-single');
            do_action('cubewp_post_confirmation', get_the_ID());
        }
    }

    public function cubewp_woocommerce_after_product_payment($order_id)
    {
        static $cwpOptions = null;
        
        if ($cwpOptions === null) {
            $cwpOptions = get_option('cwpOptions');
        }
        
        $add_into_wallet = $cwpOptions['cubewp_product_price_into_wallet'] ?? '1';
        if ($add_into_wallet) {
            $order = wc_get_order($order_id);
            $items = $order->get_items() ?? array();
            
            if (!empty($items)) {
                foreach ($items as $item_id => $item) {
                    $product = $item->get_product();
                    $cubewp_product = get_post_meta($product->get_id(), 'is_cubewp_submitted_product', true);
                    if ($cubewp_product) {
                        self::cubewp_add_product_price_into_wallet($item, $product, $order_id);
                    }
                }
            }
        }
    }

    private static function cubewp_add_product_price_into_wallet($item, $product, $order_id)
    {
        $price = $product->get_price();
        $quantity = $item->get_quantity();
        $item_discount = $item->get_subtotal() - $item->get_total();
        $item_price = $price - ($item_discount / $quantity);
        $amount = $item_price * $quantity;
        
        if ($amount > 0) {
            $parameters = array(
                'amount'   => $amount,
                'post_id'  => $product->get_id(),
                'order_id' => $order_id,
            );

            CubeWp_Wallet_Processor::cubewp_add_funds_to_wallet($parameters);
        }
    }

    public function cubewp_woocommerce_exclude_product_post_type($post_types)
    {
        $post_types[self::$wooCommerce_post_type] = self::$wooCommerce_post_type;
        return $post_types;
    }

    public function cubewp_woocommerce_frontend_fields_arguments($args)
    {
        $filter_taxonomy = $args['filter_taxonomy'] ?? '';
        
        if ($filter_taxonomy == 'product_type') {
            if (isset($args['options']) && !empty($args['options']) && is_array($args['options'])) {
                unset($args['options']['variable']);
                if (!empty($args['options']) && is_array($args['options'])) {
                    $wc_labels = wc_get_product_types();
                    foreach ($args['options'] as $slug => $option) {
                        if (isset($wc_labels[$slug])) {
                            $args['options'][$slug]['term_name'] = $wc_labels[$slug];
                        }
                    }
                }
            }
            $args['value'] = !empty($args['value']) ? $args['value'] : cubewp_get_woocommerce_term_id_by_slug('simple');
        }
        
        if (!empty($filter_taxonomy) && $args['filter_taxonomy'] == 'product_shipping_class') {
            $args['conditional'] = true;
            $args['conditional_field'] = '_virtual';
            $args['conditional_operator'] = '!=';
            $args['conditional_value'] = 'Yes';
        }

        return $args;
    }

    /**
     * Handle product submission - optimized
     */
    public function cubewp_woocommerce_after_product_submission($return, $product)
    {
        $return['msg'] = esc_html__('Congratulations! Your product submission was successful. Please wait a moment while we redirect you.', 'cubewp-woocommerce');

        if (isset($product['post_id']) && !empty($product['post_id'])) {
            $product_id = absint($product['post_id']);
            $return['redirectURL'] = esc_url(get_preview_post_link($product_id));
            
            // Batch process meta updates
            $this->process_product_meta_updates($product_id);
            
            update_post_meta($product_id, 'is_cubewp_submitted_product', true);
        }

        return $return;
    }

    /**
     * Batch process product meta updates
     */
    private function process_product_meta_updates($product_id)
    {
        self::cubewp_woocommerce_sync_product_metas($product_id);
        self::cubewp_woocommerce_attach_product_downloadable_files($product_id);
        self::cubewp_woocommerce_attach_product_attributes($product_id);
        self::cubewp_woocommerce_sync_author_capabilities($product_id);
    }

    private static function cubewp_woocommerce_sync_product_metas($product_id)
    {
        $product_type = get_post_meta($product_id, 'product_type', true);
        $product_type_term = get_term($product_type, 'product_type');
        
        if ($product_type_term) {
            $product_type = $product_type_term->slug;
            wp_set_object_terms($product_id, $product_type, 'product_type');
        }

        // Create appropriate product type
        switch ($product_type) {
            case 'external':
                $product = new WC_Product_External($product_id);
                break;
            case 'grouped':
                $product = new WC_Product_Grouped($product_id);
                break;
            default:
                $product = new WC_Product($product_id);
                break;
        }

        wc_delete_product_transients($product->get_id());
        wp_cache_delete('product-' . $product->get_id(), 'products');

        $product_meta = get_post_meta($product_id);
        $voided_meta_keys = array(
            'plan_id',
            'payment_status',
            '_thumbnail_id',
            'featured_image',
            'product_type',
            'product_cat',
            'is_cubewp_submitted_product',
            '_product_attributes',
            '_product_version',
            '_wc_review_count',
            '_wc_average_rating',
            'total_sales',
            '_price',
            'cwp_wc_product_attributes',
            '_children',
            'product_tag',
        );
        
        $voided_meta_keys = apply_filters('cubewp_woocommerce_product_synchronization_voided_meta_keys', $voided_meta_keys);
        
        $set_method_names = array(
            '_sale_price_dates_from' => 'set_date_on_sale_from',
            '_sale_price_dates_to'   => 'set_date_on_sale_to',
            '_stock'                 => 'set_stock_quantity',
            'product_shipping_class' => 'set_shipping_class_id',
        );

        $set_method_names = apply_filters('cubewp_woocommerce_product_synchronization_meta_methods', $set_method_names);
        
        // Process meta values in bulk
        foreach ($product_meta as $meta_key => $meta_value) {
            if (in_array($meta_key, $voided_meta_keys)) {
                continue;
            }
            
            $method_name = isset($set_method_names[$meta_key]) ? $set_method_names[$meta_key] : 'set' . $meta_key;
            
            if (method_exists($product, $method_name) && !empty($meta_value[0])) {
                $product->$method_name($meta_value[0]);
            }
        }

        // Process categories
        $product_categories = get_post_meta($product_id, 'product_cat', true);
        if ($product_categories) {
            wp_set_object_terms($product_id, $product_categories, 'product_cat');
            $product->set_category_ids((array) $product_categories);
        }

        // Process tags
        $product_tags = get_post_meta($product_id, 'product_tag', true);
        if ($product_tags) {
            wp_set_object_terms($product_id, $product_tags, 'product_tag');
            $product->set_tag_ids((array) $product_tags);
        }

        // Set price
        if ($product->is_on_sale('edit')) {
            update_post_meta($product->get_id(), '_price', $product->get_sale_price('edit'));
            $product->set_price($product->get_sale_price('edit'));
        } else {
            update_post_meta($product->get_id(), '_price', $product->get_regular_price('edit'));
            $product->set_price($product->get_regular_price('edit'));
        }

        self::cubewp_woocommerce_sync_attributes($product);
        $product->save();
    }

    private static function cubewp_woocommerce_sync_attributes($product)
    {
        $meta_attributes = get_post_meta($product->get_id(), '_product_attributes', true);
        if (!empty($meta_attributes) && is_array($meta_attributes)) {
            $attributes = array();
            foreach ($meta_attributes as $meta_attribute_key => $meta_attribute_value) {
                $meta_value = array_merge(
                    array(
                        'name'         => '',
                        'value'        => '',
                        'position'     => 0,
                        'is_visible'   => 0,
                        'is_variation' => 0,
                        'is_taxonomy'  => 0,
                    ),
                    (array) $meta_attribute_value
                );
                
                if ($meta_value['is_variation'] && strstr($meta_value['name'], '/') && sanitize_title($meta_value['name']) !== $meta_attribute_key) {
                    global $wpdb;
                    $old_slug      = 'attribute_' . $meta_attribute_key;
                    $new_slug      = 'attribute_' . sanitize_title($meta_value['name']);
                    $old_meta_rows = $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s;", $old_slug));

                    if ($old_meta_rows) {
                        foreach ($old_meta_rows as $old_meta_row) {
                            update_post_meta($old_meta_row->post_id, $new_slug, $old_meta_row->meta_value);
                        }
                    }
                }
                
                if (!empty($meta_value['is_taxonomy'])) {
                    if (!taxonomy_exists($meta_value['name'])) {
                        continue;
                    }
                    $id      = wc_attribute_taxonomy_id_by_name($meta_value['name']);
                    $options = wc_get_object_terms($product->get_id(), $meta_value['name'], 'term_id');
                } else {
                    $id      = 0;
                    $options = wc_get_text_attributes($meta_value['value']);
                }
                
                $attribute = new WC_Product_Attribute();
                $attribute->set_id($id);
                $attribute->set_name($meta_value['name']);
                $attribute->set_options($options);
                $attribute->set_position($meta_value['position']);
                $attribute->set_visible($meta_value['is_visible']);
                $attribute->set_variation($meta_value['is_variation']);
                $attributes[] = $attribute;
            }
            $product->set_attributes($attributes);
        }
    }

    private static function cubewp_woocommerce_attach_product_downloadable_files($product_id)
    {
        $downloadable_files = get_post_meta($product_id, 'cwp_wc_downloadable_files', true);
        if (!empty($downloadable_files) && is_array($downloadable_files)) {
            $_downloadable_files = array();
            foreach ($downloadable_files as $file) {
                if (!empty($file['_wc_file_url'])) {
                    $url                        = $file['_wc_file_url'];
                    $name                       = !empty($file['_wc_file_name']) ? $file['_wc_file_name'] : '';
                    $enabled                    = !empty($file['_wc_file_enabled']) ? $file['_wc_file_enabled'] : 1;
                    $id                         = !empty($file['_wc_file_hash']) ? $file['_wc_file_hash'] : wc_rand_hash();
                    $_downloadable_files[$id] = array(
                        'id'      => $id,
                        'name'    => $name,
                        'file'    => $url,
                        'enabled' => $enabled,
                    );
                }
            }
            update_post_meta($product_id, '_downloadable_files', $_downloadable_files);
        }
    }

    private static function cubewp_woocommerce_attach_product_attributes($product_id)
    {
        $attributes = get_post_meta($product_id, 'cwp_wc_product_attributes', true);
        if (!empty($attributes) && is_array($attributes)) {
            $_attributes = array();
            foreach ($attributes as $key => $attribute) {
                if (!empty($attribute['_wc_pa_name']) && !empty($attribute['_wc_pa_value'])) {
                    $name                 = $attribute['_wc_pa_name'];
                    $slug                 = preg_replace('/[^a-zA-Z0-9]+/', '_', $name);
                    $value                = $attribute['_wc_pa_value'];
                    $position             = !empty($attribute['_wc_pa_position']) ? $attribute['_wc_pa_position'] : $key;
                    $is_visible           = !empty($attribute['_wc_pa_is_visible']) ? $attribute['_wc_pa_is_visible'] : 1;
                    $is_variation         = !empty($attribute['_wc_pa_is_variation']) ? $attribute['_wc_pa_is_variation'] : 1;
                    $is_taxonomy          = !empty($attribute['_wc_pa_is_taxonomy']) ? $attribute['_wc_pa_is_taxonomy'] : 0;
                    $_attributes[$slug] = array(
                        'name'         => $name,
                        'value'        => preg_replace('/(?<!\s)\|(?!\s)/', ' | ', $value),
                        'position'     => $position,
                        'is_visible'   => $is_visible,
                        'is_variation' => $is_variation,
                        'is_taxonomy'  => $is_taxonomy,
                    );
                }
            }
            update_post_meta($product_id, '_product_attributes', $_attributes);
        }
    }

    /**
     * Sync author capabilities for product management
     */
    private static function cubewp_woocommerce_sync_author_capabilities($product_id)
    {
        $author_id = absint(get_post_field('post_author', $product_id));
        $user = get_user_by('ID', $author_id);

        if ($user) {
            $capabilities = [
                'edit_' . self::$wooCommerce_post_type,
                'read_' . self::$wooCommerce_post_type,
                'delete_' . self::$wooCommerce_post_type,
                'edit_' . self::$wooCommerce_post_type . 's',
                'delete_' . self::$wooCommerce_post_type . 's',
                'delete_published_' . self::$wooCommerce_post_type . 's',
                'edit_published_' . self::$wooCommerce_post_type . 's',
            ];

            // Only add capabilities if user doesn't already have them
            foreach ($capabilities as $capability) {
                if (!user_can($author_id, $capability)) {
                    $user->add_cap($capability);
                }
            }
        }
    }

    public function cubewp_woocommerce_custom_cubes_options($field)
    {
        if (empty($field) || !is_array($field)) {
            return [];
        }
        
        $field_name = $field['name'] ?? '';
        if (empty($field_name)) {
            return $field;
        }
        
        $field['custom_name'] = 'cwp_user_form[cwp_meta][' . $field_name . ']';
        $field['id']          = $field['id'] ?? $field_name;
        
        if (isset($_GET['pid']) && !empty($_GET['pid'])) {
            $post_id        = absint($_GET['pid']);
            $field['value'] = get_post_meta($post_id, $field_name, true);
        }

        return self::cubewp_woocommerce_add_custom_cubes_args($field_name, $field);
    }

    private static function cubewp_woocommerce_add_custom_cubes_args($field_name, $field)
    {
        $field['default_value'] = '';
        
        switch ($field_name) {
            case '_product_image_gallery':
                $field['type']                 = 'gallery';
                $field['files_save']           = 'ids';
                $field['files_save_separator'] = ',';
                break;
                
            case '_regular_price':
                $field['required']        = true;
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,external') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                $field['validation_msg']  = esc_html__('Price field is mandatory', 'cubewp-woocommerce');
                break;
                
            case '_sale_price':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,external') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                break;
                
            case '_schedule':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,external') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                $field['description']     = esc_html__('The sale will start at 00:00:00 of "From" date and end at 23:59:59 of "To" date.', 'cubewp-woocommerce');
                break;
                
            case '_sale_price_dates_from':
            case '_sale_price_dates_to':
                $field['container_attrs']      = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,external') . '" ';
                $field['container_class']      = ' cwp-conditional-by-term ';
                $field['conditional']          = true;
                $field['conditional_field']    = '_schedule';
                $field['conditional_operator'] = '==';
                $field['conditional_value']    = 'Yes';
                break;
                
            case '_sku':
                $field['description'] = esc_html__('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'cubewp-woocommerce');
                break;
                
            case '_product_url':
            case '_button_text':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('external') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                if ($field_name == '_product_url') {
                    $field['description'] = esc_html__('Enter the external URL to the product.', 'cubewp-woocommerce');
                } else {
                    $field['description'] = esc_html__('This text will be shown on the button linking to the external product.', 'cubewp-woocommerce');
                }
                break;
                
            case '_manage_stock':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,variable') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                $field['description']     = esc_html__('Track stock quantity for this product.', 'cubewp-woocommerce');
                break;
                
            case '_stock':
            case '_low_stock_amount':
            case '_backorders':
                $field['conditional']          = true;
                $field['conditional_field']    = '_manage_stock';
                $field['conditional_operator'] = '==';
                $field['conditional_value']    = 'Yes';
                $field['container_attrs']      = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,variable') . '" ';
                $field['container_class']      = ' cwp-conditional-by-term ';
                
                if ($field_name == '_stock') {
                    $field['description'] = esc_html__('Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'cubewp-woocommerce');
                } elseif ($field_name == '_backorders') {
                    $field['options']       = json_encode([
                        'label' => [
                            esc_html__('Do not allow', 'cubewp-woocommerce'),
                            esc_html__('Allow, but notify customer', 'cubewp-woocommerce'),
                            esc_html__('Allow', 'cubewp-woocommerce'),
                        ],
                        'value' => ['no', 'notify', 'yes'],
                    ]);
                    $field['default_value'] = 'no';
                    $field['description']   = esc_html__('If managing stock, this controls whether or not backorders are allowed. If enabled, stock quantity can go below 0.', 'cubewp-woocommerce');
                } elseif ($field_name == '_low_stock_amount') {
                    $field['description'] = esc_html__('When product stock reaches this amount you will be notified by email.', 'cubewp-woocommerce');
                }
                break;
                
            case '_stock_status':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,variable') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                $field['options']         = json_encode([
                    'label' => [
                        esc_html__('In stock', 'cubewp-woocommerce'),
                        esc_html__('Out of stock', 'cubewp-woocommerce'),
                        esc_html__('On backorder', 'cubewp-woocommerce'),
                    ],
                    'value' => ['instock', 'outofstock', 'onbackorder'],
                ]);
                $field['default_value']   = 'instock';
                $field['description']     = esc_html__('Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'cubewp-woocommerce');
                break;
                
            case '_sold_individually':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,variable') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                $field['description']     = esc_html__('Check to let customers to purchase only 1 item in a single order. This is particularly useful for items that have limited quantity, for example art or handmade goods.', 'cubewp-woocommerce');
                break;
                
            case '_weight':
            case '_length':
            case '_width':
            case '_height':
                $field['conditional']          = true;
                $field['conditional_field']    = '_virtual';
                $field['conditional_operator'] = 'empty';
                $field['container_attrs']      = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,variable') . '" ';
                $field['container_class']      = ' cwp-conditional-by-term ';
                
                if ($field_name == '_weight') {
                    $field['description'] = esc_html__('Weight in decimal form.', 'cubewp-woocommerce');
                } elseif ($field_name == '_length') {
                    $field['description'] = esc_html__('Length in decimal form.', 'cubewp-woocommerce');
                } elseif ($field_name == '_width') {
                    $field['description'] = esc_html__('Width in decimal form.', 'cubewp-woocommerce');
                } elseif ($field_name == '_height') {
                    $field['description'] = esc_html__('Height in decimal form.', 'cubewp-woocommerce');
                }
                break;
                
            case '_virtual':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                $field['description']     = esc_html__('Virtual products are intangible and are not shipped.', 'cubewp-woocommerce');
                break;
                
            case '_downloadable':
                $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple') . '" ';
                $field['container_class'] = ' cwp-conditional-by-term ';
                $field['description']     = esc_html__('Downloadable products give access to a file upon purchase.', 'cubewp-woocommerce');
                break;
                
            case '_download_limit':
            case '_download_expiry':
            case 'cwp_wc_downloadable_files':
                $field['conditional']          = true;
                $field['conditional_field']    = '_downloadable';
                $field['conditional_operator'] = '==';
                $field['conditional_value']    = 'Yes';
                $field['container_attrs']      = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple') . '" ';
                $field['container_class']      = ' cwp-conditional-by-term ';
                
                if ($field_name == 'cwp_wc_downloadable_files') {
                    $field['sub_fields'] = [
                        '_wc_file_hash' => [
                            'label'           => esc_html__('File Hashes', 'cubewp-woocommerce'),
                            'name'            => '_wc_file_hash',
                            'type'            => 'text',
                            'id'              => '_wc_file_hash_{{row-count-placeholder}}',
                            'container_class' => ' hide hidden ',
                            'container_attrs' => 'style="display: none;"',
                        ],
                        '_wc_file_url'  => [
                            'label'       => esc_html__('File', 'cubewp-woocommerce'),
                            'name'        => '_wc_file_url',
                            'type'        => 'file',
                            'files_save'  => 'urls',
                            'id'          => '_wc_file_url_{{row-count-placeholder}}',
                            'description' => esc_html__('The file which customers will get access to.', 'cubewp-woocommerce'),
                        ],
                        '_wc_file_name' => [
                            'label'       => esc_html__('File Name', 'cubewp-woocommerce'),
                            'name'        => '_wc_file_name',
                            'type'        => 'text',
                            'id'          => '_wc_file_name_{{row-count-placeholder}}',
                            'description' => esc_html__('This is the name of the download shown to the customer.', 'cubewp-woocommerce'),
                        ],
                    ];
                    $field['type']       = 'repeating_field';
                } elseif ($field_name == '_download_limit') {
                    $field['description'] = esc_html__('Leave blank for unlimited re-downloads.', 'cubewp-woocommerce');
                } elseif ($field_name == '_download_expiry') {
                    $field['description'] = esc_html__('Enter the number of days before a download link expires, or leave blank.', 'cubewp-woocommerce');
                }
                break;
                
            case '_children':
            case '_upsell_ids':
            case '_crosssell_ids':
                $field['filter_post_types'] = self::$wooCommerce_post_type;
                $field['appearance']        = 'multi_select';
                $field['select2_ui']        = 1;
                $field['relationship']      = 0;
                $field['auto_complete']     = 1;
                
                if ($field_name == '_children') {
                    $field['description']     = esc_html__('This lets you choose which products are part of this group.', 'cubewp-woocommerce');
                    $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('grouped') . '" ';
                    $field['container_class'] = ' cwp-conditional-by-term ';
                } elseif ($field_name == '_upsell_ids') {
                    $field['description'] = esc_html__('Upsells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'cubewp-woocommerce');
                } elseif ($field_name == '_crosssell_ids') {
                    $field['description']     = esc_html__('Cross-sells are products which you promote in the cart, based on the current product.', 'cubewp-woocommerce');
                    $field['container_attrs'] = ' data-terms="' . cubewp_get_woocommerce_term_id_by_slug('simple,variable') . '" ';
                    $field['container_class'] = ' cwp-conditional-by-term ';
                }
                break;
                
            case 'cwp_wc_product_attributes':
                $field['sub_fields'] = [
                    '_wc_pa_name'  => [
                        'label'       => esc_html__('Attribute Name', 'cubewp-woocommerce'),
                        'name'        => '_wc_pa_name',
                        'type'        => 'text',
                        'id'          => '_wc_pa_name_{{row-count-placeholder}}',
                        'placeholder' => esc_html__('f.e. size or color', 'cubewp-woocommerce'),
                    ],
                    '_wc_pa_value' => [
                        'label'       => esc_html__('Attribute Value(s)', 'cubewp-woocommerce'),
                        'name'        => '_wc_pa_value',
                        'type'        => 'textarea',
                        'id'          => '_wc_pa_value_{{row-count-placeholder}}',
                        'placeholder' => esc_html__('Enter options for customers to choose from, f.e. "Blue" or "Large". Use "|" to separate different options.', 'cubewp-woocommerce'),
                    ],
                ];
                $field['type']       = 'repeating_field';
                break;
        }
        
        $field['value'] = !empty($field['value']) ? $field['value'] : $field['default_value'];
        return $field;
    }

    public function cubewp_woocommerce_post_types_into_cubewp(array $post_types, $builder)
    {
        if ($builder != 'single_layout') {
            $post_types[self::$wooCommerce_post_type] = esc_html__('Product', 'cubewp-woocommerce');
        }
        return $post_types;
    }

    public function get_custom_product_attribute_html($specific_attribute_slug)
    {
        global $product;

        if (!$product || !$specific_attribute_slug) {
            return '';
        }

        $attributes = $product->get_attributes();
        $attribute = $attributes[$specific_attribute_slug] ?? null;

        if (!is_object($attribute) || !$attribute->is_taxonomy()) {
            return '';
        }

        $terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));

        if (empty($terms)) {
            return '';
        }

        ob_start();
        
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $current_obj = null;
        
        foreach ($attribute_taxonomies as $attr) {
            if ('pa_' . $attr->attribute_name === $attribute->get_name()) {
                $current_obj = $attr;
                break;
            }
        }

        if ($current_obj) {
            $attribute_type = $current_obj->attribute_type;
            $term_count = count($terms);
            
            switch ($attribute_type) {
                case 'color':
                    echo '<ul class="color-attributes">';
                    $display_count = min(3, $term_count);
                    
                    for ($i = 0; $i < $display_count; $i++) {
                        $term = $terms[$i];
                        $color_value = get_term_meta($term->term_id, 'woomen_attr_field', true);
                        echo '<li class="attribute-color-item attribute" data-attr-type="' . esc_attr($attribute_type) . '" data-term-slug="' . esc_attr($term->slug) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                        echo '<span class="color-swatch wm-variation-attr" style="background-color: ' . esc_attr($color_value) . ';"></span>';
                        echo '</li>';
                    }
                    
                    if ($term_count > 3) {
                        echo '<li class="attribute-more-count">' . esc_html(($term_count - 3) . '+') . '</li>';
                    }
                    
                    echo '</ul>';
                    break;

                case 'label':
                    echo '<ul class="label-attributes">';
                    $term_count = count($terms);
                    $display_count = min(4, $term_count);

                    for ($i = 0; $i < $display_count; $i++) {
                        $term = $terms[$i];
                        echo '<li class="attribute-label-item" data-attr-type="' . esc_attr($attribute_type) . '" data-term-slug="' . esc_attr($term->slug) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                        echo '<span class="wm-variation-attr">' . esc_html($term->name) . '</span>';
                        echo '</li>';
                    }

                    if ($term_count > 4) {
                        $additional_count = $term_count - 4;
                        echo '<li class="attribute-more-count">' . esc_html($additional_count . '+') . '</li>';
                    }

                    echo '</ul>';
                    break;

                case 'image':
                    echo '<ul class="image-attributes">';
                    $term_count = count($terms);
                    $display_count = min(3, $term_count);

                    for ($i = 0; $i < $display_count; $i++) {
                        $term = $terms[$i];
                        $image_id = get_term_meta($term->term_id, 'woomen_attr_field', true);
                        $image_url = wp_get_attachment_url($image_id);
                        echo '<li class="attribute-image-item" data-attr-type="' . esc_attr($attribute_type) . '" data-term-slug="' . esc_attr($term->slug) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                        echo '<img class="wm-variation-attr" src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '">';
                        echo '</li>';
                    }

                    if ($term_count > 3) {
                        $additional_count = $term_count - 3;
                        echo '<li class="attribute-more-count">' . esc_html($additional_count . '+') . '</li>';
                    }

                    echo '</ul>';
                    break;

                default:
                    echo '<select class="default-attribute-dropdown wm-variation-attr" data-attr-type="' . esc_attr($attribute_type) . '" data-attr-name="' . esc_attr($attribute->get_name()) . '">';
                    foreach ($terms as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                    }
                    echo '</select>';
                    break;
            }
        }

        return ob_get_clean();
    }

    public function process_global_product_tags($return, $field, $post_id, $attr)
    {
        // Ensure WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return $return;
        }

        // Get the product object
        $product = wc_get_product($post_id);

        if (!$product) {
            return $return;
        }

        // Process Post Class
        if ($field === 'woo_post_class') {
            $col_class = get_query_var('col_class', 'col-6 col-md-6 col-lg-3');
            ob_start();
            post_class($col_class);
            return ob_get_clean();
        }

        // Process price shortcode
        if ($field === 'woo_price') {
            return $product->get_price_html();
        }
        
        // Process Product ID
        if ($field === 'woo_product_id') {
            return $post_id;
        }
        
        // Process Product Gallery images by id
        if ($field === 'woo_product_gallery') {
            return wc_get_product_gallery($post_id);
        }

        // Check if the field is 'woo_video' and return the video shortcode HTML if available
        if ($field === 'woo_video') {
            $video_shortcode = vp_custom_video_shortcode($post_id);
            if ($video_shortcode['status'] == 'has_video') {
                return $video_shortcode['html'];
            }
        }

        // Return the CSS class for the play button if the field is 'play_class'
        if ($field === 'play_class') {
            return 'play-video';
        }

        // Return the CSS class for the pause button if the field is 'pouse_class'
        if ($field === 'pouse_class') {
            return 'pause-video';
        }

        // Check if the field is 'has_video' and return a class based on video availability
        if ($field === 'has_video') {
            $video_shortcode = vp_custom_video_shortcode($post_id);
            return $video_shortcode['status'] == 'empty' ? 'video-not-available' : 'video-available';
        }

        if ($field === 'stock_available') {
            $data_parts = explode('-', $attr);
            $data = vp_get_product_stock_status_percentage($post_id);
            $get_data = '';
            foreach ($data_parts as $value) {
                $get_data .= $data[$value] ?? '';
            }
            return $get_data;
        }

        // Process Product Cart action
        if ($field === 'woo_add_cart_action') {
            ob_start();
            do_action('woocommerce_' . $product->get_type() . '_add_to_cart');
            return ob_get_clean();
        }

        // Get global product attributes
        $attributes = wc_get_attribute_taxonomies();

        // Loop through each attribute
        foreach ($attributes as $attribute) {
            // Format attribute name to create shortcode
            $formatted_name = str_replace(' ', '', $attribute->attribute_label);
            $formatted_name = str_replace('-', '_', $formatted_name);
            $tag = 'woo_' . sanitize_title($formatted_name);

            // If the field matches the tag, get the terms for this attribute
            if ($field === $tag) {
                $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                $terms = wp_get_post_terms($post_id, $taxonomy, array('fields' => 'names'));

                if (!is_wp_error($terms) && !empty($terms)) {
                    return $this->get_custom_product_attribute_html($taxonomy);
                } else {
                    return '';
                }
            }
        }

        // Process the quick add to cart button
        if ($field === 'woo_add_to_cart') {
            $product_id = $product->get_id();
            $add_to_cart_url = esc_url(add_query_arg('add-to-cart', $product_id, wc_get_cart_url()));
            return '<a href="' . $add_to_cart_url . '" class="button add_to_cart_button">Add to Cart</a>';
        }

        // Check if the product is new (created within the last 30 days)
        if ($field === 'woo_new') {
            $newness_days = 30;
            $created_date = strtotime($product->get_date_created());
            if ((time() - $created_date) <= ($newness_days * 24 * 60 * 60)) {
                return 'New';
            }
            return '';
        }

        // Check if the product is on sale
        if ($field === 'woo_on_sale') {
            if ($product->is_on_sale()) {
                return 'On Sale';
            }
            return '';
        }

        // Get product weight
        if ($field === 'woo_product_weight') {
            if ($product->is_type('variable')) {
                $variation_ids = $product->get_children();
                if (!empty($variation_ids)) {
                    $first_variation = wc_get_product(reset($variation_ids));
                    if ($first_variation) {
                        return $first_variation->get_weight() ?: '';
                    }
                }
            } else {
                return $product->get_weight() ?: '';
            }
            return '';
        }

        // Check the discount percentage
        if ($field === 'woo_discount_percentage') {
            if ($product->is_type('variable')) {
                $variation_ids = $product->get_children();
                if (!empty($variation_ids)) {
                    $first_variation = wc_get_product($variation_ids[0]);
                    if ($first_variation && $first_variation->is_on_sale()) {
                        $regular_price = (float) $first_variation->get_regular_price();
                        $sale_price    = (float) $first_variation->get_sale_price();

                        if ($regular_price > 0) {
                            $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                            return $discount_percentage . '%';
                        }
                    }
                }
            } else {
                if ($product->is_on_sale()) {
                    $regular_price = floatval($product->get_regular_price());
                    $sale_price = floatval($product->get_sale_price());
                    if ($regular_price > 0) {
                        $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                        return $discount_percentage . '%';
                    }
                }
            }
            return '';
        }

        // Check if the product is available for pre-order
        if ($field === 'woo_pre_order') {
            if ($product->is_type('preorder')) {
                return 'Pre Order';
            }
            return '';
        }

        // Get product rating
        if ($field === 'woo_rating') {
            $average = $product->get_average_rating();
            return $average > 0 ? wc_get_rating_html($average) : '<div class="star-rating" role="img"></div>';
        }

        // Get number of reviews
        if ($field === 'woo_number_of_reviews') {
            return $product->get_review_count();
        }

        // Calculate amount saved if the product is on sale
        if ($field === 'woo_amount_saving') {
            if ($product->is_on_sale()) {
                if ($product->is_type('variable')) {
                    $prices = $product->get_variation_prices();
                    $regular_prices = $prices['regular_price'];
                    $sale_prices = $prices['sale_price'];

                    $total_regular = 0;
                    $total_sale = 0;
                    $count = 0;

                    foreach ($regular_prices as $variation_id => $regular_price) {
                        $sale_price = isset($sale_prices[$variation_id]) ? $sale_prices[$variation_id] : $regular_price;
                        if ($regular_price > 0 && $sale_price < $regular_price) {
                            $total_regular += floatval($regular_price);
                            $total_sale += floatval($sale_price);
                            $count++;
                        }
                    }

                    if ($count > 0) {
                        $average_regular = $total_regular / $count;
                        $average_sale = $total_sale / $count;
                        $amount_saving = $average_regular - $average_sale;
                        return '<span class="save-label">Save</span>' . wc_price($amount_saving);
                    }
                } else {
                    $regular_price = floatval($product->get_regular_price());
                    $sale_price = floatval($product->get_sale_price());
                    if ($regular_price > 0 && $sale_price < $regular_price) {
                        $amount_saving = $regular_price - $sale_price;
                        return '<span class="save-label">Save</span>' . wc_price($amount_saving);
                    }
                }
            }
            return '';
        }

        if ($field === 'woo_normal_price') {
            if ($product->is_type('variable')) {
                $regular_price = (float) $product->get_variation_regular_price('max');
            } else {
                $regular_price = $product->get_regular_price();
            }
            return wc_price($regular_price);
        }

        // Get discounted price if available
        if ($field === 'woo_discounted_price') {
            $sale_price = null;

            if ($product->is_type('variable')) {
                $sale_price = $product->get_variation_sale_price('min');
                if (!$sale_price) {
                    $sale_price = $product->get_variation_regular_price('min');
                }
            } else {
                if ($product->is_on_sale()) {
                    $sale_price = $product->get_sale_price();
                }
                if (!$sale_price) {
                    $sale_price = $product->get_regular_price();
                }
            }
            return wc_price($sale_price);
        }

        // Get color count
        if ($field === 'color_available') {
            return $this->get_product_color_count($product);
        }
        
        // Get size count
        if ($field === 'size_available') {
            return $this->get_product_size_count($product);
        }
        
        // Get gallery overlay images
        if ($field === 'gallery_overlay_image') {
            $gallery_image_ids = $product->get_gallery_image_ids();
            if (!empty($gallery_image_ids)) {
                $first_three_image_ids = array_slice($gallery_image_ids, 0, 3);
                $images_html = '';
                foreach ($first_three_image_ids as $index => $image_id) {
                    $image_url = wp_get_attachment_url($image_id);
                    $class = ($index === 0) ? 'woo-gallery-overlay-image first-overlay-image' : 'woo-gallery-overlay-image';
                    $images_html .= '<img class="' . $class . '" src="' . $image_url . '" alt="overlay">';
                }
                return $images_html;
            } else {
                return '';
            }
        }

        if ($field == 'store_locator_get_direction') {
            $address = get_post_meta(get_the_ID(), 'store-locator-address', true);
            $lat = get_post_meta(get_the_ID(), 'store-locator-address_lat', true);
            $lng = get_post_meta(get_the_ID(), 'store-locator-address_lng', true);

            if (!empty($lat) && !empty($lng)) {
                return "https://www.google.com/maps/dir/?api=1&destination={$lat},{$lng}";
            } elseif (!empty($address)) {
                return "https://www.google.com/maps/dir/?api=1&destination=" . urlencode($address);
            } else {
                return "#";
            }
        } elseif ($field == 'store_locator_opening_hours') {
            $post_id = get_the_ID();
            global $cubewp_frontend;

            if (!isset($cubewp_frontend) || !method_exists($cubewp_frontend, 'post_metas')) {
                return '';
            }
            
            $post_metas = $cubewp_frontend->post_metas($post_id);
            $opening_hours = !empty($post_metas['store-opening-hours']['meta_value'][0])
                ? $post_metas['store-opening-hours']['meta_value'][0]
                : [];

            if (function_exists('get_field_options')) {
                $field_options = get_field_options('store-opening-hours');
            } else {
                $field_options = array('label' => '');
            }
            
            $opening_hours_label = !empty($field_options['label']) ? $field_options['label'] : __('Opening Hours', 'woomen');
            
            if (is_array($opening_hours) && !empty($opening_hours)) {
                $return = "<div class='woocomerce-location-opening-hours'>
                <h2 class='content-heading'>" . esc_html($opening_hours_label) . "</h2>
                <ul>";
                
                foreach ($opening_hours as $key => $day) {
                    $title = !empty($day['label']) ? esc_html($day['label']) : ucfirst(str_replace('store-hours-', '', esc_html($key)));
                    $value = !empty($day['value']) ? esc_html($day['value']) : __('Closed', 'woomen');
                    $return .= "<li><p class='title'>$title:</p> <p class='time'>$value</p></li>";
                }
                
                $return .= "</ul></div>";
                return $return;
            } else {
                return "";
            }
        } elseif ($field == 'store_locator_social_shares') {
            return function_exists('vp_get_socials_share') ? vp_get_socials_share() : '';
        }

        return $return;
    }

    public function get_product_color_count($product)
    {
        $colors = array();

        if ($product->is_type('variable')) {
            $variation_ids = $product->get_children();

            foreach ($variation_ids as $variation_id) {
                $variation_obj = wc_get_product($variation_id);
                if ($variation_obj) {
                    $color = $variation_obj->get_attribute('pa_color');
                    if (!empty($color) && !in_array($color, $colors)) {
                        $colors[] = $color;
                    }
                }
            }
        }

        $color_count = count($colors);
        return $color_count > 0
            ? $color_count . ' Color' . ($color_count > 1 ? 's' : '') . ' <span class="color-available">Available</span>'
            : '';
    }

    public function get_product_size_count($product)
    {
        $sizes = array();

        if ($product->is_type('variable')) {
            $variation_ids = $product->get_children();

            foreach ($variation_ids as $variation_id) {
                $variation_obj = wc_get_product($variation_id);
                if ($variation_obj) {
                    $size = $variation_obj->get_attribute('pa_size');
                    if (!empty($size) && !in_array($size, $sizes)) {
                        $sizes[] = $size;
                    }
                }
            }
        }

        $size_count = count($sizes);
        return $size_count > 0
            ? $size_count . ' Size' . ($size_count > 1 ? 's' : '') . ' <span class="size-available">Available</span>'
            : '';
    }

    public function custom_postype_builder($types, $form)
    {
        $types['product'] = 'Product';
        return $types;
    }

    public function add_global_product_tags($cubes, $postype)
    {
        if ($postype != 'product') return $cubes;

        // Ensure WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return $cubes;
        }

        $tags['woo_price'] = 'product price';
        $tags['woo_normal_price'] = 'Normal Price';
        $tags['woo_discounted_price'] = 'Discounted Price';

        // Get global product attributes
        $attributes = wc_get_attribute_taxonomies();

        // Loop through each attribute
        foreach ($attributes as $attribute) {
            // Format attribute name to create shortcode
            $formatted_name = str_replace(' ', '', $attribute->attribute_label);
            $formatted_name = str_replace('-', '_', $formatted_name);
            $tag = 'woo_' . sanitize_title($formatted_name);
            $tags[$tag] = $attribute->attribute_label;
        }

        $tags['woo_add_to_cart'] = 'Quick Add to Cart';
        $tags['woo_new'] = 'New Product';
        $tags['woo_on_sale'] = 'On Sale';
        $tags['woo_product_weight'] = 'Product Weight';
        $tags['woo_discount_percentage'] = 'Discount Percentage';
        $tags['woo_pre_order'] = 'Pre Order';
        $tags['woo_rating'] = 'Rating';
        $tags['woo_number_of_reviews'] = 'Number of Reviews';
        $tags['woo_amount_saving'] = 'Amount Saving';
        $tags['woo_product_id'] = 'Product ID';
        $tags['woo_product_gallery'] = 'Product Gallery';
        $tags['woo_add_cart_action'] = 'Product Cart';
        $tags['woo_post_class'] = 'Post Class';
        $tags['color_available'] = 'Color Available';
        $tags['size_available'] = 'Size Available';
        $tags['gallery_overlay_image'] = 'Gallery overlay Image';

        $tags['woo_video'] = 'Product Video';
        $tags['has_video'] = 'Video indicator';
        $tags['play_class'] = 'Play Video Class';
        $tags['pouse_class'] = 'Pouse Video Class';

        $tags['stock_available{stock_status-stock_quantity-sold_quantity-percentage_sold}'] = ' stock availability';

        $fields = [];
        foreach ($tags as $name => $label) {
            $fields[$name] = array(
                'label' => $label,
                'name' => $name
            );
        }
        
        $cube['woocommerece'] = array(
            'section_title' => 'WooCommerece',
            'fields' => $fields
        );

        return $cube;
    }
}