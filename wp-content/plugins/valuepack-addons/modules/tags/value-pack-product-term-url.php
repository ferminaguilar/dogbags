<?php

/**
 * Custom Link Dynamic Tag for Elementor
 * 
 * @package     ValuePackAddons
 * 
 * Provides a custom link dynamic tag for Elementor that allows selecting
 * predefined URLs with optional path additions.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Value_Pack_Product_Term_URL
 * 
 * Elementor dynamic tag that provides custom link functionality
 */
class Value_Pack_Product_Term_URL extends \Elementor\Core\DynamicTags\Data_Tag
{

    /**
     * Get the tag name
     * 
     * @return string
     */
    public function get_name()
    {
        return 'vp-product-term-url';
    }

    /**
     * Get the tag title
     * 
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Term Link', 'valuepack-addons');
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
        ];
    }

    /**
     * Determine if settings are required
     * 
     * @return bool
     */
    public function is_settings_required()
    {
        return true;
    }

    /**
     * Get the tag value
     * 
     * @param array $options Optional options
     * @return string The generated URL
     */
    public function get_value(array $options = [])
    {
        $preview_term_id = function_exists('cubewp_get_preview_term_id') ? cubewp_get_preview_term_id() : null;
        if ($preview_term_id) {
            $term = get_term((int) $preview_term_id);
            if ($term && ! is_wp_error($term)) {
                $term_link = get_term_link($term);
                if (! is_wp_error($term_link)) {
                    return $term_link;
                }
            }
        }

        global $cubewp_term;
        if (!empty($cubewp_term) && is_object($cubewp_term)) {
            $term_id = $cubewp_term->term_id;
            $term_link = get_term_link($term_id);
            if (!is_wp_error($term_link)) {
                return $term_link;
            }
        }

        $product_id = $this->get_settings('product_id_field');
        $term_slug = $this->get_settings('user_selected_link_field');

        // If product ID is provided, get the terms attached to the product
        if (!empty($product_id)) {
            $terms = wp_get_post_terms((int)$product_id, 'product_cat');
            if (!empty($terms) && !is_wp_error($terms)) {
                $term_link = get_term_link($terms[0]); // Get the first term's link
                if (!is_wp_error($term_link)) {
                    return $term_link;
                }
            }
        }

        // If no product ID or terms are found, check if it's a single product page
        if (is_product() && $this->get_settings('use_single_product_switch')) {
            global $post;
            $terms = wp_get_post_terms($post->ID, 'product_cat');
            if (!empty($terms) && !is_wp_error($terms)) {
                $term_link = get_term_link($terms[0]); // Get the first term's link
                if (!is_wp_error($term_link)) {
                    return $term_link;
                }
            }
        }
        if (!empty($term_slug)) {
            // Get the term object by slug for 'product_cat' taxonomy
            $term = get_term_by('slug', $term_slug, 'product_cat');

            if (!is_wp_error($term) && $term) {
                $term_link = get_term_link($term);
                if (!is_wp_error($term_link)) {
                    return $term_link;
                }
            }
        }

        return '';
    }

    /**
     * Register controls for the tag
     */
    protected function register_controls()
    {
        $this->add_control(
            'product_id_field',
            [
                'type'    => \Elementor\Controls_Manager::TEXT,
                'label'   => esc_html__('Product ID', 'valuepack-addons'),
                'description' => esc_html__('Enter the product ID to get the term URL associated with it. Leave empty to use the current product on single product pages.', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'use_single_product_switch',
            [
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'label'   => esc_html__('Use Term on Single', 'valuepack-addons'),
                'description' => esc_html__('Enable this to dynamically get the term URL for the current Category on single product pages.', 'valuepack-addons'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'user_selected_link_field',
            [
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => esc_html__('Select Term', 'valuepack-addons'),
                'options' => value_pack_get_woocommerce_category_slugs(),
                'description' => esc_html__('Select a term manually if no product ID is provided or if not on a single product page.', 'valuepack-addons'),
                'default' => '',
            ]
        );
    }
}

