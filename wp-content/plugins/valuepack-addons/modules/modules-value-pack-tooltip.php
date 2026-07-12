<?php
/**
 * Tooltip Module
 * 
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;


if (!function_exists('value_pack_add_tooltips_controls')) {
    function value_pack_add_tooltips_controls($widget, $section_id, $args)
    {
        if ('section_custom_css_pro' !== $section_id) {
            return;
        }

        $widget->start_controls_section(
            'ep_tooltip_section',
            [
                'label' => __('Tooltip - Value Pack', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Tooltip Enable/Disable Control
        $widget->add_control(
            'ep_tooltip_enable',
            [
                'label' => __('Enable Tooltip', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        // Tooltip Text Control
        $widget->add_control(
            'ep_tooltip_text',
            [
                'label' => __('Tooltip Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter tooltip text', 'valuepack-addons'),
                'condition' => [
                    'ep_tooltip_enable' => 'yes',
                ],
            ]
        );

        // Tooltip Position Control
        $widget->add_control(
            'ep_tooltip_position',
            [
                'label' => __('Tooltip Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'up' => __('Top', 'valuepack-addons'),
                    'right' => __('Right', 'valuepack-addons'),
                    'down' => __('Bottom', 'valuepack-addons'),
                    'left' => __('Left', 'valuepack-addons'),
                ],
                'condition' => [
                    'ep_tooltip_enable' => 'yes',
                ],
            ]
        );

        // Tooltip Padding Control
        $widget->add_responsive_control(
            'ep_tooltip_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}}[data-tooltip]::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'ep_tooltip_enable' => 'yes',
                ],
            ]
        );

        // Tooltip Background Color Control
        $widget->add_control(
            'ep_tooltip_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}[data-tooltip]::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}[data-tooltip][data-flow^="down"]::before ' => 'border-bottom-color: {{VALUE}} !important;',
                    '{{WRAPPER}}[data-tooltip][data-flow^="left"]::before ' => 'border-left-color: {{VALUE}} !important;',
                    '{{WRAPPER}}[data-tooltip][data-flow^="right"]::before' => 'border-right-color: {{VALUE}} !important;',
                    '{{WRAPPER}}[data-tooltip]:not([data-flow])::before, {{WRAPPER}}[data-tooltip][data-flow^="up"]::before' => 'border-top-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'ep_tooltip_enable' => 'yes',
                ],
            ]
        );
        // new issue fixes
        // Tooltip Text Color Control
        $widget->add_control(
            'ep_tooltip_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
               'selectors' => [
                    '{{WRAPPER}}[data-tooltip]::after' => 'color: {{VALUE}};',
                    '{{WRAPPER}}[data-tooltip][data-flow^="down"]::before ' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}}[data-tooltip][data-flow^="left"]::before ' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}}[data-tooltip][data-flow^="right"]::before' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}}[data-tooltip]:not([data-flow])::before, {{WRAPPER}}[data-tooltip][data-flow^="up"]::before' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'ep_tooltip_enable' => 'yes',
                ],
            ]
        );

        // Tooltip Typography Control
        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ep_tooltip_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}}[data-tooltip]::after',
                'condition' => [
                    'ep_tooltip_enable' => 'yes',
                ],
            ]
        );

        $widget->end_controls_section();
    }
    add_action('elementor/element/after_section_end', 'value_pack_add_tooltips_controls', 25, 3);
}


// Rendering function to display the tooltip on the main wrapper
if (!function_exists('value_pack_render_tooltip_on_element')) {
    function value_pack_render_tooltip_on_element($widget)
    {
        // Get the widget settings
        $settings = $widget->get_settings_for_display();

        // Check if the tooltip is enabled and text is set
        if (!empty($settings['ep_tooltip_enable']) && $settings['ep_tooltip_enable'] === 'yes' && !empty($settings['ep_tooltip_text'])) {
            $tooltip_text = esc_attr($settings['ep_tooltip_text']);
            $tooltip_position = esc_attr($settings['ep_tooltip_position']);
            // Add tooltip attributes to the main wrapper
            $widget->add_render_attribute('_wrapper', 'data-tooltip', $tooltip_text);
            $widget->add_render_attribute('_wrapper', 'data-flow', $tooltip_position);
        }
    }

    add_action('elementor/frontend/before_render', 'value_pack_render_tooltip_on_element', 1);
}
