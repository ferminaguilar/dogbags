<?php

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * User Visibility Controls for All Elementor Elements
 * 
 * Adds advanced settings to control element visibility based on user login status
 */

// Add User Visibility controls to Advanced tab for all elements
if (!function_exists('vp_add_user_visibility_controls')) {
    function vp_add_user_visibility_controls($widget, $section_id, $args)
    {
        // Only add to Advanced tab after custom CSS section
        if ('section_custom_css_pro' !== $section_id) {
            return;
        }

        $widget->start_controls_section(
            'vp_user_visibility_section',
            [
                'label' => __('User Visibility - Value Pack', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $widget->add_control(
            'vp_user_visibility',
            [
                'label' => __('User Visibility', 'value-pack'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'all' => __('Show for Everyone', 'value-pack'),
                    'logged_out' => __('Show for Non-Logged In Users Only', 'value-pack'),
                    'logged_in' => __('Show for Logged In Users Only', 'value-pack'),
                ],
                'default' => 'all',
                'description' => __('Control who can see this element. When set to logged-in or logged-out only, the wrapper gets class vp-user-visibility-logged-in or vp-user-visibility-logged-out for custom CSS/JS.', 'value-pack'),
            ]
        );

        $widget->end_controls_section();
    }

    add_action('elementor/element/after_section_end', 'vp_add_user_visibility_controls', 25, 3);
}
 


// Render visibility logic - apply server-side to avoid FOUC
if (!function_exists('vp_render_user_visibility')) {
    function vp_render_user_visibility($widget)
    {
        // Get the widget settings
        $settings = $widget->get_settings_for_display();
        
        // Get user visibility setting
        $user_visibility = !empty($settings['vp_user_visibility']) ? $settings['vp_user_visibility'] : 'all';
        
        // If set to show for everyone, no need to do anything
        if ($user_visibility === 'all') {
            return;
        }

        // Check if we're in Elementor editor mode (always show in editor for preview)
        $is_editor_mode = false;
        if (class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::$instance->editor) && method_exists(\Elementor\Plugin::$instance->editor, 'is_edit_mode')) {
            $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        }

        // Always show in editor mode for preview
        if ($is_editor_mode) {
            return;
        }

        // Check user login status server-side
        $is_logged_in = is_user_logged_in();

        // Add visibility-mode class so you can target with CSS/JS (e.g. .vp-user-visibility-logged-in, .vp-user-visibility-logged-out)
        $widget->add_render_attribute('_wrapper', 'class', 'user-set-visibility vp-user-visibility-' . $user_visibility);
        $widget->add_render_attribute('_wrapper', 'style', 'display: none;');
        $widget->add_render_attribute('_wrapper', 'data-vp-user-visibility', esc_attr($user_visibility));

   
    }

    add_action('elementor/frontend/before_render', 'vp_render_user_visibility', 1);
}
 