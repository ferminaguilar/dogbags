<?php

/**
 * Term Image Dynamic Tag for Elementor
 * 
 * @package     ValuePack Addons
 * 
 * Provides a custom term image dynamic tag for Elementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Product_URL
 * 
 * Elementor dynamic tag that provides custom link functionality
 */
class Value_Pack_Product_Term_Image extends \Elementor\Core\DynamicTags\Data_Tag
{

    /**
     * Get the tag name
     * 
     * @return string
     */
    public function get_name()
    {
        return 'vp-product-term-image';
    }

    /**
     * Get the tag title
     * 
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Term Image', 'valuepack-addons');
    }

    /**
     * Get the tag group
     * 
     * @return array
     */
    public function get_group()
    {
        return ['vp-tags'];
    }

    /**
     * Get the tag categories
     * 
     * @return array
     */
    public function get_categories()
    {
        return [
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
        ];
    }

    /**
     * Get the tag value
     * 
     * @param array $options Optional options
     * @return array
     */
    public function get_value(array $options = [])
    {
        $category_id = 0;
        $preview_term_id = cubewp_get_preview_term_id();
        if ($preview_term_id) {
            $image_id = get_term_meta($preview_term_id, 'thumbnail_id', true);
        } else {
            
            if (is_product_category()) {
                $category_id = get_queried_object_id();
                $image_id = get_term_meta($category_id, 'thumbnail_id', true);
            } elseif (is_product()) {
                $product = wc_get_product();
                if ($product) {
                    $category_ids = $product->get_category_ids();
                    if (! empty($category_ids)) {
                        $image_id = get_term_meta($category_ids[0], 'thumbnail_id', true);
                    }
                }
            }
            global $cubewp_term;
            if (!empty($cubewp_term) && is_object($cubewp_term)) {
                $category_id = $cubewp_term->term_id;
            }
            if ($category_id) {
                $image_id = get_term_meta($category_id, 'thumbnail_id', true);
            }

        }

        if (empty($image_id)) {
            return [];
        }

        $src = wp_get_attachment_image_src($image_id, 'full');

        return [
            'id' => $image_id,
            'url' => $src[0],
        ];
    }
}

