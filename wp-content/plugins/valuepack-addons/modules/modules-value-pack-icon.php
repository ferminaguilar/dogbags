<?php
/**
 * Icon Module
 *
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;
/**
 * @param \Elementor\Controls_Stack $element The element type.
 * @param array   $args    Section arguments.
 */

if (!function_exists('value_pack_inject_vp_icon_controls')) {
    function value_pack_inject_vp_icon_controls($element, $args)
    {

        $element->start_controls_section(
            'header_animation_section',
            [
                'label' => __('Animate with Header', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Enable Animation Switcher
        $element->add_control(
            'enable_header_animation',
            [
                'label' => __('Enable Animation', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => '<span style="display: block; background: #fff3cd; color: #856404; padding: 8px; border: 1px solid #ffeeba; border-radius: 4px; font-weight: 500;">⚠️ ' . esc_html__('Use this only on header sections. Animation is intended to work within header elements.', 'valuepack-addons') . '</span>',
            ]
        );
        $element->add_control(
            'enable_header_animation_Sticky',
            [
                'label' => __('Enable Sticky', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        // Tabs: Normal and After Sticky
        $element->start_controls_tabs('header_animation_tabs');

        // 🔹 Normal State Tab
        $element->start_controls_tab(
            'header_animation_normal_tab',
            [
                'label' => __('Normal', 'valuepack-addons'),
                'condition' => [
                    'enable_header_animation' => 'yes',
                ],
            ]
        );

        // Normal - Scale
        $element->add_responsive_control(
            'header_scale_normal',
            [
                'label' => __('Scale', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [''],
                'range' => [
                    '' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],

                'selectors' => [
                    '{{WRAPPER}}' =>  '--vp-header-scale: {{SIZE}};',
                ],
                'condition' => [
                    'enable_header_animation' => 'yes',
                ],
            ]
        );

        // Normal - Translate Y
        $element->add_responsive_control(
            'header_translate_normal',
            [
                'label' => __('Translate Y (px)', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => -300,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'transform: translateY({{SIZE}}{{UNIT}}) scale(var(--vp-header-scale));',
                ],
                'condition' => [
                    'enable_header_animation' => 'yes',
                ],
            ]
        );

        // Normal - Color
        $element->add_control(
            'header_color_normal',
            [
                'label' => __('Text/Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} svg path,{{WRAPPER}}.sticky-enable-icon-control svg path' => 'fill: {{VALUE}} !important;',
                ],
                'condition' => [
                    'enable_header_animation' => 'yes',
                ],
            ]
        );

        $element->end_controls_tab();

        //  Sticky State Tab
        $element->start_controls_tab(
            'header_animation_sticky_tab',
            [
                'label' => __('After Sticky', 'valuepack-addons'),
                'condition' => [
                    'enable_header_animation' => 'yes',
                ],
            ]
        );

        // Sticky - Scale
        $element->add_responsive_control(
            'header_scale_sticky',
            [
                'label' => __('Scale', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [''],
                'range' => [
                    '' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}}' =>  '--vp-header-sticky-scale: {{SIZE}};',
                ],
                'condition' => [
                    'enable_header_animation' => 'yes',
                ],
            ]
        );

        // Sticky - Translate Y
        $element->add_responsive_control(
            'header_translate_sticky',
            [
                'label' => __('Translate Y (px)', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => -300,
                        'max' => 300,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    ' body .vp-sticky-header.enable_sticky_bottom:not(.scroll-top){{WRAPPER}}' => 'transform: translateY({{SIZE}}{{UNIT}}) scale(var(--vp-header-sticky-scale));',
                ],
                'condition' => [
                    'enable_header_animation' => 'yes',
                ],
            ]
        );

        // Sticky - Color
        $element->add_control(
            'header_color_sticky',
            [
                'label' => __('Text/Icon Color (Sticky)', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    ' body .vp-sticky-header.enable_sticky_bottom:not(.scroll-top){{WRAPPER}}:not(.vp-animation-complete) svg path ,
                     body .vp-sticky-header.enable_sticky_bottom:not(.scroll-top){{WRAPPER}}:not(.vp-animation-complete) svg g ' => 'fill: {{VALUE}} !important;',
                ],
                'condition' => [
                    'enable_header_animation' => 'yes',
                    'enable_header_animation_Sticky!' => 'yes', // Correct syntax for 'not equal to'
                ],
            ]
        );
        $element->add_control(
            'header_color_stickys',
            [
                'label' => __('Text/Icon Color (Sticky)', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-sticky-header.enable_sticky_bottom:not(.scroll-top){{WRAPPER}}.sticky-enable-icon-control.vp-animation-complete svg g,{{WRAPPER}}.sticky-enable-icon-control.vp-animation-complete svg path' => 'fill: {{VALUE}} !important;',
                ],
                'condition' => [
                    'enable_header_animation' => 'yes',
                    'enable_header_animation_Sticky' => 'yes',
                ],
            ]
        );
        $element->end_controls_tab();

        $element->end_controls_tabs();
        $element->end_controls_section();
    }
    add_action('elementor/element/icon/section_icon/after_section_end', 'value_pack_inject_vp_icon_controls', 10, 2);
}

if (!function_exists('value_pack_render_icon_on_element')) {
    function value_pack_render_icon_on_element($widget)
    {
        if ('icon' !== $widget->get_name()) {
            return;
        }

        $settings = $widget->get_settings_for_display();
        if (!empty($settings['enable_header_animation_Sticky']) && $settings['enable_header_animation_Sticky'] === 'yes') {
            $scale_data =  isset($settings['header_scale_normal']) ? $settings['header_scale_normal'] : '';
            $transform_data =  isset($settings['header_translate_normal']) ? $settings['header_translate_normal'] : '';
            $widget->add_render_attribute('_wrapper', 'class', 'sticky-enable-icon-control');
            $widget->add_render_attribute('_wrapper', 'data-scale', $scale_data['size']);
            $widget->add_render_attribute('_wrapper', 'data-transform',  $transform_data['size']);
        }
    }

    add_action('elementor/frontend/before_render', 'value_pack_render_icon_on_element', 1);
}
