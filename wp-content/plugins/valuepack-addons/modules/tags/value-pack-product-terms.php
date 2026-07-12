<?php

/**
 * Custom Product Terms Dynamic Tag for Elementor
 * 
 * @package     ValuePackAddons
 * 
 * Provides a dynamic tag for Elementor to fetch WooCommerce product terms.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Product_Terms_Tag
 * 
 * Elementor dynamic tag that fetches WooCommerce product terms.
 */
class Value_Pack_Product_Terms extends \Elementor\Core\DynamicTags\Data_Tag
{

    /**
     * Get the tag name
     * 
     * @return string
     */
    public function get_name()
    {
        return 'vp-product-terms-tag';
    }

    /**
     * Get the tag title
     * 
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Product Terms', 'valuepack-addons');
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
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
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
     * @return string The term names
     */
    public function get_value(array $options = [])
    {
        $preview_term_id = function_exists( 'cubewp_get_preview_term_id' ) ? cubewp_get_preview_term_id() : null;
        if ( $preview_term_id ) {
            $term = get_term( (int) $preview_term_id );
            if ( $term && ! is_wp_error( $term ) ) {
                return $term->name;
            }
        }

        global $cubewp_term;
        if (!empty($cubewp_term) && is_object($cubewp_term)) {
            return $cubewp_term->name;
        }


        $get_on_archive = $this->get_settings('get_on_archive');
        $taxonomy = $this->get_settings('taxonomy_field');
        $get_by_product = $this->get_settings('get_by_product');
        $taxonomy_field_id = absint($this->get_settings('taxonomy_field_id'));

        if ($get_on_archive && is_tax($taxonomy)) {
            $term = get_queried_object();
            if ($term && !is_wp_error($term)) {
                return $term->name;
            }
        }


        if (empty($taxonomy)) {
            return esc_html__('No taxonomy selected', 'valuepack-addons');
        }
        $product_post = null;

        if ('yes' === $get_by_product && !empty($taxonomy_field_id)) {
            $product_post = get_post($taxonomy_field_id);

            if (!$product_post || 'product' !== $product_post->post_type || 'publish' !== $product_post->post_status) {
                $product_post = null;
            }
        }

        if (null === $product_post && is_singular('product')) {
            global $post;

            if ($post instanceof \WP_Post && 'product' === $post->post_type && 'publish' === $post->post_status) {
                $product_post = $post;
            }
        }

        if (null === $product_post) {
            $fallback_product_id = $this->get_first_published_product_id();

            if (!$fallback_product_id) {
                return esc_html__('No published products available', 'valuepack-addons');
            }

            $product_post = get_post($fallback_product_id);
        }

        if (!$product_post || 'product' !== $product_post->post_type || 'publish' !== $product_post->post_status) {
            return esc_html__('No published products available', 'valuepack-addons');
        }

        $terms = get_the_terms($product_post->ID, $taxonomy);

        if (is_wp_error($terms) || empty($terms)) {
            return '';
        }

        $term_names = wp_list_pluck($terms, 'name');

        return !empty($term_names) ? $term_names[0] : '';
    }

    /**
     * Register controls for the tag
     */
    protected function register_controls()
    {
        $this->add_control(
            'taxonomy_field',
            [
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => esc_html__('Select Taxonomy', 'valuepack-addons'),
                'options' => $this->get_taxonomy_options(),
                'default' => 'product_cat',
            ]
        );
        $this->add_control(
            'get_by_product',
            [
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'label'   => esc_html__('Get by Product ID', 'valuepack-addons'),
                'description' => esc_html__('Enable to fetch terms by specific Product ID.', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'taxonomy_field_id',
            [
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'label'   => esc_html__('Product ID', 'valuepack-addons'),
                'default' => '',
                'condition' => [
                    'get_by_product' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'get_on_archive',
            [
                'type'    => \Elementor\Controls_Manager::SWITCHER,
                'label'   => esc_html__('Get on Archive Page', 'valuepack-addons'),
                'description' => esc_html__('Enable to fetch terms on archive pages.', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
    }

    /**
     * Get available taxonomy options
     * 
     * @return array
     */
    private function get_taxonomy_options()
    {
        $taxonomies = get_object_taxonomies('product', 'objects');
        $options = [];

        foreach ($taxonomies as $taxonomy) {
            $options[$taxonomy->name] = $taxonomy->label;
        }

        return $options;
    }

    /**
     * Get the first published product ID.
     *
     * @return int|null The product ID or null if none found.
     */
    private function get_first_published_product_id()
    {
        $products = get_posts(
            [
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'fields'         => 'ids',
                'no_found_rows'  => true,
            ]
        );

        if (empty($products) || !isset($products[0])) {
            return null;
        }

        return (int) $products[0];
    }
}

