<?php

class NextWP_WC_Import {

    public static function init() {
        add_filter('nwp_import_product_attributes', [__CLASS__, 'process_product_attributes_meta'], 10, 3);
    }

    /**
     * Process WooCommerce product attributes meta data during import
     */
    public static function process_product_attributes_meta($post) {
        
        // Only process product posts
        if ($post['post_type'] == 'product') {
            $postmeta = $post['postmeta'];

            // Process _product_attributes meta
            foreach ($postmeta as $index => $meta) {
                if ($meta['key'] === '_product_attributes') {
                    $attributes = maybe_unserialize($meta['value']);
                    
                    if (is_array($attributes)) {
                        foreach ($attributes as $attr_slug => $attribute) {
                            if ($attribute['is_taxonomy']) {
                                // Ensure the attribute taxonomy exists
                                if (!taxonomy_exists($attr_slug)) {
                                    // Create WC attribute if it doesn't exist
                                    $attribute_name = str_replace('pa_', '', $attr_slug);
                                    wc_create_attribute(array(
                                        'name' => $attribute_name,
                                        'slug' => $attribute_name,
                                        'type' => 'select',
                                        'order_by' => 'menu_order',
                                        'has_archives' => false
                                    ));
                                    
                                    // Register the taxonomy
                                    register_taxonomy(
                                        $attr_slug,
                                        array('product'),
                                        array(
                                            'hierarchical' => true,
                                            'show_ui' => false,
                                            'query_var' => true,
                                            'rewrite' => false,
                                        )
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

// Initialize only if WooCommerce is active
if (class_exists('WooCommerce')) {
    NextWP_WC_Import::init();
}
