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
 * Class Value_Pack_Custom_Link_Tag
 * 
 * Elementor dynamic tag that provides custom link functionality
 */
class Value_Pack_Custom_Link_Tag extends \Elementor\Core\DynamicTags\Data_Tag
{

    /**
     * Get the tag name
     * 
     * @return string
     */
    public function get_name()
    {
        return 'vp-custom-link-tag';
    }

    /**
     * Get the tag title
     * 
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Custom Link', 'valuepack-addons');
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
        $selected_option = $this->get_settings('user_selected_link_field');
        $extra_url = $this->get_settings('extra_url_field');

        // Validate and sanitize the selected option
        if ($selected_option === 'vp-homepage-link') {
            $home_url = home_url('/');

            // Sanitize and append extra URL path if provided
            if (!empty($extra_url)) {
                $extra_url = wp_sanitize_redirect($extra_url);
                $extra_url = ltrim($extra_url, '/');
                $extra_url = preg_replace('/[^a-zA-Z0-9\-_\/?=&]/', '', $extra_url);

                return esc_url_raw(trailingslashit($home_url) . $extra_url);
            }

            return esc_url_raw($home_url);
        }

        return '#';
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
                'options' => [
                    'vp-homepage-link' => esc_html__('Homepage Link', 'valuepack-addons'),
                ],
                'default' => 'vp-homepage-link',
            ]
        );

        $this->add_control(
            'extra_url_field',
            [
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label'       => esc_html__('Extra URL', 'valuepack-addons'),
                'description' => esc_html__('Add additional path for the URL, e.g., /shop', 'valuepack-addons'),
                'condition'   => [
                    'user_selected_link_field' => 'vp-homepage-link',
                ],
                'input_type' => 'url',
            ]
        );
    }
}

