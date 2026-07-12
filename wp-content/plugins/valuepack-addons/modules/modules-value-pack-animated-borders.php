<?php

/**
 * Animated Borders Module
 *
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

defined('ABSPATH') || exit;

if (!function_exists('value_pack_add_hover_border_controls_to_section')) {
    function value_pack_add_hover_border_controls_to_section($widget, $type)
    {
        $widget->add_control(
            'enable_hover_border_' . $type,
            [
                'label'        => esc_html__('Enable Hover Border', 'valuepack-addons'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                'label_off'    => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        // Hover Border Style
        $widget->add_control(
            'hover_border_style_' . $type,
            [
                'label'   => esc_html__('Border Animation Style', 'valuepack-addons'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'slide-left'   => esc_html__('Slide from Left', 'valuepack-addons'),
                    'slide-right'  => esc_html__('Slide from Right', 'valuepack-addons'),
                    'grow-center'  => esc_html__('Grow', 'valuepack-addons'),
                    'slide-fade'         => esc_html__('Fade', 'valuepack-addons'),
                ],
                'default' => 'slide-left',
                'condition' => [
                    'enable_hover_border_' . $type => 'yes',
                ],
            ]
        ); 
         
        $widget->add_control(
            'hover_border_color_' . $type,
            [
                'label'     => esc_html__('Hover Border Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#056CFF',
                'selectors' => [
                    '{{WRAPPER}}>.elementor-widget-container::after , {{WRAPPER}} li a::before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_hover_border_' . $type => 'yes',
                ],
            ]
        ); 
        $widget->add_control(
            'hover_border_height_' . $type,
            [
                'label'       => esc_html__('Hover Border height (px)', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'size_units'  => ['px'],
                'range'       => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}}>.elementor-widget-container::after , {{WRAPPER}} li a::before' => 'height:  {{SIZE}}{{UNIT}}  !important;',
                ],
                'condition' => [
                    'enable_hover_border_' . $type => 'yes',
                ],
            ]
        );
        $widget->add_control(
            'hover_border_space_' . $type,
            [
                'label'       => esc_html__('Space From Top (px)', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'size_units'  => ['px'],
                'range'       => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}}>.elementor-widget-container::after , {{WRAPPER}} li a::before' => 'bottom:  -{{SIZE}}{{UNIT}}  !important;',
                ],
                'condition' => [
                    'enable_hover_border_' . $type => 'yes',
                ],
            ]
        ); 
        $widget->add_control(
            'hover_border_left_space_' . $type,
            [
                'label'       => esc_html__('Left Space (px)', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'size_units'  => ['px'],
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors'   => [
                    '{{WRAPPER}}>.elementor-widget-container::after , {{WRAPPER}} li a::before' => 'left:  {{SIZE}}{{UNIT}}  !important;',  
                ],
                'condition'   => [
                    'enable_hover_border_' . $type => 'yes',
                    'hover_border_style_' . $type => [
                        'slide-left',
                        'grow-center',
                        'slide-fade',
                    ],
                ],
            ]
        );
        $widget->add_control(
            'hover_border_right_space_' . $type,
            [
                'label'       => esc_html__('Right Space (px)', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'size_units'  => ['px'],
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors'   => [
                    '{{WRAPPER}}>.elementor-widget-container::after , {{WRAPPER}} li a::before' => 'right:  {{SIZE}}{{UNIT}}  !important;',  
                ],
                'condition'   => [
                    'enable_hover_border_' . $type => 'yes',
                    'hover_border_style_' . $type  => 'slide-right',
                ],
            ]
        ); 
        $widget->add_control(
            'hover_border_right_gap_space_' . $type,
            [
                'label'       => esc_html__('Remove Empty Space', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'size_units'  => ['px'],
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default'     => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'   => [
                    '{{WRAPPER}}>.elementor-widget-container:hover::after , {{WRAPPER}} li:hover::before' => 'width:  calc(100% - {{SIZE}}{{UNIT}})  !important;',  
                ],
                'condition'   => [
                    'enable_hover_border_' . $type => 'yes', 
                ],
            ]
        ); 
    }
}


if (!function_exists('value_pack_add_icon_list_hover_border_controls')) {
    function value_pack_add_icon_list_hover_border_controls($widget, $section_id)
    {
        $widget->start_controls_section(
            'hover_border_controls_section_list',
            [
                'label'       => __('Border Effects', 'valuepack-addons'),
                'tab'         => \Elementor\Controls_Manager::TAB_STYLE,
                'description' => __('Configure hover border styles for the Icon List items.', 'valuepack-addons'),
            ]
        );

        value_pack_add_hover_border_controls_to_section($widget, 'list');

        $widget->end_controls_section();
    }
    add_action('elementor/element/icon-list/section_icon_list/after_section_end', 'value_pack_add_icon_list_hover_border_controls', 10, 2);
}

if (!function_exists('value_pack_add_custom_widget_hover_border_controls')) {
    function value_pack_add_custom_widget_hover_border_controls($widgets, $section_id, $args)
    {
        if ('container' != $widgets->get_name() &&  'icon-list' != $widgets->get_name() &&  'section_custom_css_pro' === $section_id) {
            $widgets->start_controls_section(
                'hover_border_controls_section',
                [
                    'label'       => __('Hover Border Effects - Value Pack', 'valuepack-addons'),
                    'tab'         => \Elementor\Controls_Manager::TAB_ADVANCED,
                    'description' => __('Configure hover border styles.', 'valuepack-addons'),
                ]
            );
            value_pack_add_hover_border_controls_to_section($widgets, 'container');
            $widgets->end_controls_section();
        }
    }

    add_action('elementor/element/after_section_end', 'value_pack_add_custom_widget_hover_border_controls', 25, 3);
}

if (!function_exists('value_pack_render_animated_border_controls')) {
    function value_pack_render_animated_border_controls($widget)
    {
        $settings = $widget->get_settings_for_display();
        if (!empty($settings['enable_hover_border_container']) && $settings['enable_hover_border_container'] === 'yes') {

            $hover_class = $settings['hover_border_style_container'];
            $widget->add_render_attribute(
                '_wrapper',
                [
                    'class' => 'hover-border-style  ' . $hover_class,
                ]
            );
        }


        if ($widget->get_name()  === 'icon-list') {
            if (!empty($settings['enable_hover_border_list']) && $settings['enable_hover_border_list'] === 'yes') {

                $hover_class = $settings['hover_border_style_list'];
                $widget->add_render_attribute(
                    '_wrapper',
                    [
                        'class' => 'hover-border-style_list  ' . $hover_class,
                    ]
                );
            }
        }
    }

    add_action('elementor/frontend/before_render', 'value_pack_render_animated_border_controls', 1);
}
