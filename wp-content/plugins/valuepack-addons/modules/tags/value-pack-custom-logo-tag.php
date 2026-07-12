<?php
/**
 * Custom Logo Dynamic Tag for Elementor
 * 
 * @package     ValuePackAddons
 * 
 * Provides a dynamic tag for Elementor that allows selecting between different site logos.
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

/**
 * Class Value_Pack_Custom_Logo_Tag
 * 
 * Elementor dynamic tag that provides access to custom logo settings.
 */
class Value_Pack_Custom_Logo_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

    /**
     * Get the name of the dynamic tag
     * 
     * @return string
     */
    public function get_name() {
        return 'vp-custom-logo';
    }

    /**
     * Get the title of the dynamic tag
     * 
     * @return string
     */
    public function get_title() {
        return esc_html__('Site Logo', 'valuepack-addons');
    }

    /**
     * Get the group this tag belongs to
     * 
     * @return array
     */
    public function get_group() {
        return ['vp-tags'];
    }

    /**
     * Get the categories this tag belongs to
     * 
     * @return array
     */
    public function get_categories() {
        return [ 
            \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
        ];
    }

    /**
     * Determine if settings are required for this tag
     * 
     * @return bool
     */
    public function is_settings_required() {
        return true;
    }

    /**
     * Get the value of the dynamic tag
     * 
     * @param array $options Optional options array
     * @return array|string Returns either an array with image data or empty string
     */
    public function get_value(array $options = []) {
        $field = $this->get_settings('user_selected_image_field');

        // Return early if no field is selected
        if (empty($field)) {
            return '';
        }

        // Sanitize the field name
        $field = sanitize_key($field);

        // Get the image ID from settings
        $image_id = absint(value_pack_get_setting($field));

        // Return if no valid image ID
        if (!$image_id) {
            return '';
        }

        // Get image URL and metadata
        $image_data = wp_get_attachment_image_src($image_id, 'full');

        // Validate image data
        if (empty($image_data) || !is_array($image_data)) {
            return '';
        }

        // Return structured data for Elementor
        return [
            'id'  => $image_id,
            'url' => esc_url_raw($image_data[0]),
        ];
    }

    /**
     * Register controls for the dynamic tag
     */
    protected function register_controls() {
        $this->add_control(
            'user_selected_image_field',
            [
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => esc_html__('Select Logo Type', 'valuepack-addons'),
                'options' => [
                    'value-pack-sitelogo'       => esc_html__('Home Logo', 'valuepack-addons'),
                    'value-pack-sitelogo-pages' => esc_html__('Pages Logo', 'valuepack-addons'),
                    'value-pack-sitelogo-footer' => esc_html__('Footer Logo', 'valuepack-addons'),
                ],
                'default' => 'value-pack-sitelogo',
            ]
        );
    }
}

