<?php

/**
 * Custom Link Dynamic Tag for Elementor
 * 
 * @package     ValuePack Addons
 * 
 * Provides a custom link dynamic tag for Elementor that allows selecting
 * predefined URLs with optional path additions.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Product_URL
 * 
 * Elementor dynamic tag that provides custom link functionality
 */
class Value_Pack_Product_Term_Count extends \Elementor\Core\DynamicTags\Tag
{

    /**
     * Get the tag name
     * 
     * @return string
     */
    public function get_name()
    {
        return 'vp-product-term-count';
    }

    /**
     * Get the tag title
     * 
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Term Count', 'valuepack-addons');
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
     * @return int|string|null Term count for the resolved term or empty when unavailable
     */
    public function get_value(array $options = [])
    {
        $preview_term_id = function_exists('cubewp_get_preview_term_id') ? cubewp_get_preview_term_id() : null;
        if ($preview_term_id) {
            $term = get_term((int) $preview_term_id);
            if ($term && ! is_wp_error($term)) {
                $term_count = $term->count;
                return $term_count ?? 0;
            }
        }

        global $cubewp_term;
        if (!empty($cubewp_term) && is_object($cubewp_term)) {
            return $cubewp_term->count ?? 0;
        }

        $term_slug = $this->get_settings('user_selected_link_field');

        if (empty($term_slug)) {
            return '';
        }

        // Get the term object by slug for 'product_cat' taxonomy
        $term = get_term_by('slug', $term_slug, 'product_cat');

        if (!is_wp_error($term) && $term) {
            return $term->count;
        } else {
            return 0;
        }
    }

    /**
     * Render the output with before/after/fallback support
     */
    public function render()
    {
        $value = $this->get_value();
        $fallback = $this->get_settings('fallback');

        if ($value === null || $value === '') {
            if ($fallback !== '' && $fallback !== null) {
                echo wp_kses_post($fallback);
            }
            return;
        }

        $before = $this->get_settings('before') ?: '';
        $after = $this->get_settings('after') ?: '';

        echo wp_kses_post($before) . esc_html($value) . wp_kses_post($after);
    }


    /**
     * Register controls for the tag
     */
    protected function register_controls()
    {
        $this->add_control(
            'user_selected_link_field',
            [
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => esc_html__('Select Link Field', 'valuepack-addons'),
                'options' =>   value_pack_get_woocommerce_category_slugs(),
                'default' => '',
                'description' => esc_html__('Leave empty to use the current product on single product pages or cubewp taxonomy loop.', 'valuepack-addons'),
            ]
        );
    }
}

