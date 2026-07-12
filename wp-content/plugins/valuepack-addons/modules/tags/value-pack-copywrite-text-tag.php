<?php
/**
 * Copywrite Text Tag for Elementor
 * 
 * @package     ValuePackAddons
 * 
 * Provides a dynamic tag for Elementor that allows selecting dynamic text for copywrite.
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

/**
 * Class Value_Pack_Copywrite_Text_Tag
 * 
 * Elementor dynamic tag that provides copywrite text from cubewp settings.
 */
class Value_Pack_Copywrite_Text_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

    /**
     * Get the name of the dynamic tag
     * 
     * @return string
     */
    public function get_name() {
        return 'vp-copywrite-text';
    }

    /**
     * Get the title of the dynamic tag
     * 
     * @return string
     */
    public function get_title() {
        return esc_html__('Copywrite Text', 'valuepack-addons');
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
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    /**
     * Get the value of the dynamic tag
     * 
     * @param array $options Optional options array
     * @return string Returns Text string
     */
    public function get_value(array $options = []) {

        $copywrite_text = wp_kses_post(value_pack_get_setting('vp-copywrite-text'));
        if (!empty($copywrite_text)) {
            return $copywrite_text;
        }
    }

    /**
     * Register controls for the dynamic tag
     */
    protected function register_controls() {
    }
}

