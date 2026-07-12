<?php
/**
 * Sticky Section Module
 * 
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;


if (!function_exists('value_pack_add_sticky_section_controls')) {
    function value_pack_add_sticky_section_controls($widget, $section_id, $args)
    {
        if ('section_custom_css_pro' !== $section_id) {
            return;
        }

        $widget->start_controls_section(
            'ep_sticky_section',
            [
                'label' => __('Sticky Section - Value Pack', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Tooltip Enable/Disable Control
        $widget->add_control(
            'ep_sticky_enable',
            [
                'label' => __('Enable Sticky Section', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $widget->add_control(
            'ep_parallax_sticky_enable',
            [
                'label' => __('Enable Parallax Sticky', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'ep_sticky_enable' => 'yes', // Show only if Sticky Section is enabled
                ],
            ]
        );

        // Divider
        $widget->add_control(
            'ep_sticky_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'solid'
            ]
        );

        $widget->add_control(
            'ep_sticky_header_headings',
            [
                'label' => __('Advanced Sticky Settings', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $widget->add_control(
            'ep_sticky_paragraph',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '
                <div class="elementor-control-alert elementor-panel-alert elementor-panel-alert-warning">
                    <div class="elementor-control-alert-heading">' . __('Instructions!', 'valuepack-addons') . '</div>
                    <div class="elementor-control-alert-content">' . __('This element should be a container and must have a main container with a minimum height of <b>100vh</b>. Then enable this setting to use the animated section.', 'valuepack-addons') . '</div>
                </div>',
                'content_classes' => 'valuepack-addons',
            ]
        );

        // New Control under the new header
        $widget->add_control(
            'ep_sticky_animation',
            [
                'label' => __('Enable Sticky Animation For Sections', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $widget->add_control(
            'ep_sticky_bg_color',
            [
                'label' => __('Sticky Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.value-pack-sticky-section>.vp-sticky' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'ep_sticky_enable' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'ep_sticky_e_color',
            [
                'label' => __('Sticky Element Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.value-pack-sticky-section>.vp-sticky div:not(.cubewp-mega-menu-item-dropdown div),
                    {{WRAPPER}}.value-pack-sticky-section>.vp-sticky span:not(.cubewp-mega-menu-item-dropdown span), 
                    {{WRAPPER}}.value-pack-sticky-section>.vp-sticky i:not(.cubewp-mega-menu-item-dropdown i),
                    {{WRAPPER}}.value-pack-sticky-section>.vp-sticky path:not(.cubewp-mega-menu-item-dropdown path),
                    {{WRAPPER}}.value-pack-sticky-section>.vp-sticky select:not(.cubewp-mega-menu-item-dropdown path),
                    {{WRAPPER}}.value-pack-sticky-section>.vp-sticky p:not(.cubewp-mega-menu-item-dropdown p),
                    {{WRAPPER}}.value-pack-sticky-section>.vp-sticky .cubewp-mega-menu-item:not(.cubewp-mega-menu-item-dropdown)' => 'color: {{VALUE}}!important;  fill: {{VALUE}} !important; ',
                ],
                'condition' => [
                    'ep_sticky_enable' => 'yes',
                ],
            ]
        );

        $widget->end_controls_section();
    }
    add_action('elementor/element/after_section_end', 'value_pack_add_sticky_section_controls', 25, 3);
}


if (!function_exists('value_pack_render_sticky_on_element')) {
    function value_pack_render_sticky_on_element($widget)
    {
            $settings = $widget->get_settings_for_display();
          
            if (!empty($settings['ep_sticky_animation']) && $settings['ep_sticky_animation'] === 'yes') {
                $widget->add_render_attribute('_wrapper', 'class', 'vp-sticky-section');
            }
         
            if (!empty($settings['ep_sticky_enable']) && $settings['ep_sticky_enable'] === 'yes') {
                $widget->add_render_attribute('_wrapper', 'class', 'value-pack-sticky-section');

                $z_index = '9999';
                $visibility = 'visible';
                if (!empty($settings['ep_parallax_sticky_enable']) && $settings['ep_parallax_sticky_enable'] === 'yes') {
                    $z_index = '-1';
                    $visibility = 'hidden';
                }



                $z_index = !empty($settings['ep_parallax_sticky_enable']) && $settings['ep_parallax_sticky_enable'] === 'yes' ? '0' : '999999';
                $visibility = !empty($settings['ep_parallax_sticky_enable']) && $settings['ep_parallax_sticky_enable'] === 'yes' ? 'hidden' : 'visible';

                $widget->add_render_attribute('_wrapper', 'data-sticky-z-index', $z_index);
                $widget->add_render_attribute('_wrapper', 'data-sticky-visibility', $visibility);
            }
    }
  
        add_action('elementor/frontend/before_render', 'value_pack_render_sticky_on_element', 1);
  
}
