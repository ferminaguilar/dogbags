<?php
/**
 * Scroll Module
 * 
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

defined('ABSPATH') || exit;
if (!function_exists('value_pack_add_custom_animation_controls')) {
    function value_pack_add_custom_animation_controls($widget, $section_id, $args)
    {


        if ('container' == $widget->get_name() && 'section_custom_css_pro' === $section_id) {

            $widget->start_controls_section(
                'scroll_controls_section',
                [
                    'label' => __('Custom Scroll - Value Pack', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );

            $widget->add_control(
                'enable_scroll',
                [
                    'label' => esc_html__('Enable Scroll', 'valuepack-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'valuepack-addons'),
                    'label_off' => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'frontend_available' => true,
                ]
            );

            $widget->add_responsive_control(
                'scroll_bg_color',
                [
                    'label' => esc_html__('Scroll Background Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#f0f0f0',
                    'selectors' => [
                        '{{WRAPPER}} .custom-scrollbar' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_thumb_color',
                [
                    'label' => esc_html__('Scroll Thumb Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#c0c0c0',
                    'selectors' => [
                        '{{WRAPPER}} .custom-scrollbar .scroll-thumb' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_thumb_border_radius',
                [
                    'label' => esc_html__('Scroll Thumb Border Radius', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .custom-scrollbar .scroll-thumb' => 'border-radius: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .custom-scrollbar' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_height',
                [
                    'label' => esc_html__('Scroll Height', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 50,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .custom-scrollbar' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .custom-scrollbar .scroll-thumb' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'scroll_buttons',
                [
                    'label' => esc_html__('Enable Scroll Buttons', 'valuepack-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'valuepack-addons'),
                    'label_off' => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_button_width',
                [
                    'label' => esc_html__('Button Width', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 40,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 20,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll, {{WRAPPER}} .vp_prevBtn_scroll' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_button_height',
                [
                    'label' => esc_html__('Button Height', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 40,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 20,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll, {{WRAPPER}} .vp_prevBtn_scroll' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'scroll_button_z_index',
                [
                    'label' => esc_html__('Buttons Z-Index', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => -99999,
                    'max' => 99999999,
                    'step' => 1,
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll, {{WRAPPER}} .vp_prevBtn_scroll' => 'z-index: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'scroll_icon_size',
                [
                    'label' => esc_html__('Icon Size', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 20,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 50,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll i, {{WRAPPER}} .vp_prevBtn_scroll i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .vp_nextBtn_scroll img, {{WRAPPER}} .vp_prevBtn_scroll img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'scroll_button_icon',
                [
                    'label' => esc_html__('Prev Button Icon', 'valuepack-addons'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-chevron-left',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'scroll_button_icon_next',
                [
                    'label' => esc_html__('Next Button Icon', 'valuepack-addons'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-chevron-right',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'scroll_button_border',
                [
                    'label' => esc_html__('Button Border', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );

            $widget->start_controls_tabs('scroll_button_border_tabs', [
                'condition' => [
                    'enable_scroll' => 'yes',
                    'scroll_buttons' => 'yes',
                ],
            ]);

            // Normal Tab
            $widget->start_controls_tab(
                'scroll_button_border_normal',
                [
                    'label' => esc_html__('Normal', 'valuepack-addons'),
                ]
            );

            $widget->add_responsive_control(
                'scroll_button_color',
                [
                    'label' => esc_html__('Button Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll, {{WRAPPER}} .vp_prevBtn_scroll, {{WRAPPER}} .vp_nextBtn_scroll i, {{WRAPPER}} .vp_prevBtn_scroll i, {{WRAPPER}} .vp_nextBtn_scroll svg, {{WRAPPER}} .vp_prevBtn_scroll svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_button_bg_color',
                [
                    'label' => esc_html__('Button Background Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll, {{WRAPPER}} .vp_prevBtn_scroll' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );

            // Border Control
            $widget->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'button_border',
                    'label' => esc_html__('Border', 'valuepack-addons'),
                    'selector' => '{{WRAPPER}} .vp_nextBtn_scroll, {{WRAPPER}} .vp_prevBtn_scroll',
                    'default' => [
                        'border' => 'solid',
                        'width' => '1px',
                        'color' => '#000',
                    ],
                ]
            );



            $widget->add_responsive_control(
                'scroll_button_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll, {{WRAPPER}} .vp_prevBtn_scroll' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $widget->end_controls_tab();

            // Hover Tab
            $widget->start_controls_tab(
                'scroll_button_border_hover',
                [
                    'label' => esc_html__('Hover', 'valuepack-addons'),
                ]
            );

            $widget->add_responsive_control(
                'scroll_button_color:hover',
                [
                    'label' => esc_html__('Button Color ', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll:hover i, {{WRAPPER}} .vp_prevBtn_scroll:hover  i' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_button_bg_color:hover',
                [
                    'label' => esc_html__('Button Background Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll:hover, {{WRAPPER}} .vp_prevBtn_scroll:hover' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                        'scroll_buttons' => 'yes',
                    ],
                ]
            );


            // Border Control
            $widget->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'button_border_hover',
                    'label' => esc_html__('Border', 'valuepack-addons'),
                    'selector' => '{{WRAPPER}} .vp_nextBtn_scroll:hover, {{WRAPPER}} .vp_prevBtn_scroll:hover',
                    'default' => [
                        'border' => 'solid',
                        'width' => '1px',
                        'color' => '#000',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scroll_button_border_hover_radius',
                [
                    'label' => esc_html__('Border Hover Radius', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll:hover, {{WRAPPER}} .vp_prevBtn_scroll:hover' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $widget->end_controls_tab();

            $widget->end_controls_tabs();


            $widget->add_control(
                'choose_style_heading',
                [
                    'label' => esc_html__('Choose Style Options', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'choose_style',
                [
                    'label' => esc_html__('Select Style', 'valuepack-addons'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'style1',
                    'options' => [
                        'style1' => esc_html__('Style 1', 'valuepack-addons'),
                        'style2' => esc_html__('Style 2', 'valuepack-addons'),
                    ],
                    'condition' => [
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'max_width_scrollbar',
                [
                    'label' => esc_html__('Max Width', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 500,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 2000,
                            'step' => 10,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'vw' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrape' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style1',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'gap_between_items',
                [
                    'label' => esc_html__('Gap Between Items', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                        'em' => [
                            'min' => 0,
                            'max' => 5,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrape' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style1',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'scroll_items_order',
                [
                    'label' => esc_html__('Scroll Items Order', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'choose_style' => 'style1',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'scrollbar_order',
                [
                    'label' => esc_html__('Scrollbar Order', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 1,
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrape .custom-scrollbar' => 'order: {{VALUE}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style1',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'prev_button_order',
                [
                    'label' => esc_html__('Previous Button Order', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0,
                    'selectors' => [
                        '{{WRAPPER}} .vp_prevBtn_scroll' => 'order: {{VALUE}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style1',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'next_button_order',
                [
                    'label' => esc_html__('Next Button Order', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 2,
                    'selectors' => [
                        '{{WRAPPER}} .vp_nextBtn_scroll' => 'order: {{VALUE}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style1',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'custom_scrollbar_max_width',
                [
                    'label' => esc_html__('Max Width for Scrollbar', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 500,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 2000,
                            'step' => 10,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'vw' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .custom-scrollbar' => 'max-width: {{SIZE}}{{UNIT}};    margin-left: auto;margin-right: auto;    width: 100%;',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );



            $widget->add_responsive_control(
                'vp_scrollbar_gap',
                [
                    'label' => esc_html__('Button Gap', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                        'em' => [
                            'min' => 0,
                            'max' => 5,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrapes' => 'gap: {{SIZE}}{{UNIT}};position: absolute;',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );


            $widget->add_responsive_control(
                'icon_direction_position',
                [
                    'label' => esc_html__('horizontal Direction', 'valuepack-addons'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__('Left', 'valuepack-addons'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => esc_html__('Right', 'valuepack-addons'),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'right',
                    'condition' => [
                        'choose_style' => 'style2',
                        'enable_scroll' => 'yes',
                    ],

                ]
            );

            $widget->add_responsive_control(
                'vp_scrollbar_right_position',
                [
                    'label' => esc_html__('Right Position', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'em' => [
                            'min' => -10,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrapes' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'icon_direction_position' => 'right',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'vp_scrollbar_left_position',
                [
                    'label' => esc_html__('Left Position', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'em' => [
                            'min' => -10,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrapes' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'icon_direction_position' => 'left',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );


            $widget->add_responsive_control(
                'icon_direction_position_vertical',
                [
                    'label' => esc_html__('Vertical Direction', 'valuepack-addons'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => esc_html__('Top', 'valuepack-addons'),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => esc_html__('Bottom', 'valuepack-addons'),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'bottom',
                    'condition' => [
                        'choose_style' => 'style2',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'vp_scrollbar_Top_position',
                [
                    'label' => esc_html__('Top Position', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'em' => [
                            'min' => -10,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrapes' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'icon_direction_position_vertical' => 'top',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'vp_scrollbar_bottom_position',
                [
                    'label' => esc_html__('Bottom Position', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'em' => [
                            'min' => -10,
                            'max' => 10,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrapes' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'icon_direction_position_vertical' => 'bottom',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'vp_scrollbar_justify_content',
                [
                    'label' => esc_html__('Justify Content', 'valuepack-addons'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'flex-start',
                    'options' => [
                        'flex-start' => esc_html__('Start', 'valuepack-addons'),
                        'flex-end' => esc_html__('End', 'valuepack-addons'),
                        'center' => esc_html__('Center', 'valuepack-addons'),
                        'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                        'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                        'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrapes' => 'justify-content: {{VALUE}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'vp_scrollbar_padding',
                [
                    'label' => esc_html__('Padding', 'valuepack-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'top' => '0',
                        'right' => '0',
                        'bottom' => '0',
                        'left' => '0',
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .vp-scrollbar-button-wrapes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'choose_style' => 'style2',
                        'enable_scroll' => 'yes',
                    ],
                ]
            );



            $widget->add_control(
                'choose_style_headings',
                [
                    'label' => esc_html__('Container Heading', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $widget->add_responsive_control(
                'min_width_wrapper',
                [
                    'label' => esc_html__('Min Width', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', 'em', '%', 'vw'],
                    'range' => [
                        'px' => [
                            'min' => 50,
                            'max' => 2000,
                            'step' => 10,
                        ],
                        'em' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 0.1,
                        ],
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 1,
                        ],
                        'vw' => [
                            'min' => 1,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'min-width: {{SIZE}}{{UNIT}};',
                    ],

                ]
            );

            $widget->end_controls_section();
        }
    }
    add_action('elementor/element/after_section_end', 'value_pack_add_custom_animation_controls', 25, 3);
}

if (!function_exists('value_pack_render_swiper_scroll')) {
    function value_pack_render_swiper_scroll($widget)
    {
        $settings = $widget->get_settings_for_display();
        $enable_scroll = isset($settings['enable_scroll']) ? $settings['enable_scroll'] : 'no';
        $scroll_buttons = isset($settings['scroll_buttons']) ? $settings['scroll_buttons'] : 'no';
        $choose_style = isset($settings['choose_style']) ? $settings['choose_style'] : 'style1';
        $button_icon = isset($settings['scroll_button_icon']) ? $settings['scroll_button_icon'] : '';
        $scroll_button_icon_next = isset($settings['scroll_button_icon_next']) ? $settings['scroll_button_icon_next'] : '';
        $icon_html = '';
        if (!empty($button_icon['value'])) {
            if ($button_icon['library'] === 'svg') {
                $icon_html = '<img src="' . esc_url($button_icon['value']['url']) . '" alt="Scroll Button Icon">';
            } else {
                $icon_html = '<i class="' . esc_attr($button_icon['value']) . '"></i>';
            }
        }
        $icon_htmls = '';
        if (!empty($scroll_button_icon_next['value'])) {
            if ($scroll_button_icon_next['library'] === 'svg') {
                $icon_htmls = '<img src="' . esc_url($scroll_button_icon_next['value']['url']) . '" alt="Scroll Button Icon">';
            } else {
                $icon_htmls = '<i class="' . esc_attr($scroll_button_icon_next['value']) . '"></i>';
            }
        }

        if ('yes' === $enable_scroll) {
            $widget->add_render_attribute('_wrapper', 'class', 'custom-scroll-enabled');
            $widget->add_render_attribute('_wrapper', 'vp-data-scrollbar', 'true');
            $widget->add_render_attribute('_wrapper', 'data-style',  $choose_style);
            $widget->add_render_attribute('_wrapper', 'data-buttons',  $scroll_buttons);
            $widget->add_render_attribute('_wrapper', 'data-scroll-button-icon-next', $icon_html);
            $widget->add_render_attribute('_wrapper', 'data-scroll-button-icon-prev', $icon_htmls);
        }
    }
    add_action('elementor/frontend/before_render', 'value_pack_render_swiper_scroll');
}
