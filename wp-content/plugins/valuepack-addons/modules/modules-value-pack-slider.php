<?php

use \Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

defined('ABSPATH') || exit;

if (!function_exists('value_pack_add_swiper_controls')) {
    function value_pack_add_swiper_controls($widget, $section_id, $args)
    {
        if ('container' === $widget->get_name() && 'section_layout_additional_options' === $section_id) {
            $widget->start_controls_section(
                'swiper_controls_sections',
                [
                    'label' => __('Container Title', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                ]
            );
            $widget->add_control(
                'container_title_options',
                [
                    'type' => Controls_Manager::TEXT,
                    'label' => esc_html__('Title', 'valuepack-addons'),
                    'default' => '',
                ]
            );

            $widget->end_controls_section();
        }
        if ('container' === $widget->get_name() && 'section_custom_css_pro' === $section_id) {

            $widget->start_controls_section(
                'swiper_controls_section',
                [
                    'label' => __('Slider - Value Pack', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );

            $widget->add_control(
                'enable_slider',
                [
                    'label'        => esc_html__('Enable Slider', 'valuepack-addons'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                    'label_off'    => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default'      => '',
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'slider_device_option',
                [
                    'label'   => esc_html__('Use Slider for', 'valuepack-addons'),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'all_devices'  => esc_html__('All Devices', 'valuepack-addons'),
                        'mobile_only'  => esc_html__('Mobile Only', 'valuepack-addons'),
                    ],
                    'default' => 'all_devices',
                    'condition' => [
                        'enable_slider' => 'yes',  // Show this option only if the slider is enabled
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'vertical',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('Vertical', 'valuepack-addons'),
                    'default' => '',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'scroll_swipe',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('Swipe On Scroll', 'valuepack-addons'),
                    'default' => '',
                    'condition' => [
                        'vertical' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'variableWidth',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('variable Width', 'valuepack-addons'),
                    'default' => '',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control('slides_to_show', array(
                'type'    => Controls_Manager::NUMBER,
                'label'   => esc_html__('Slides To Show', 'valuepack-addons'),
                'default' => 3,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'description' => esc_html__('Number of slides to show at once in the slider.', 'valuepack-addons'),
                'condition'   => [
                    'enable_slider' => 'yes',
                ],
                'frontend_available' => true,
            ));

            $widget->add_control(
                'slides_to_scroll',
                [
                    'type'    => Controls_Manager::NUMBER,
                    'label'   => esc_html__('Slides To Scroll', 'valuepack-addons'),
                    'default' => 1,
                    'min'     => 1,
                    'max'     => 10,
                    'step'    => 1,
                    'description' => esc_html__('Number of slides to scroll at once in the slider.', 'valuepack-addons'),
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $widget->add_control(
                'autoplay',
                [
                    'type'    => Controls_Manager::SWITCHER,
                    'label'   => esc_html__('Autoplay', 'valuepack-addons'),
                    'default' => 'yes',
                    'description' => esc_html__('Enable or disable autoplay for the slider.', 'valuepack-addons'),
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $widget->add_control(
                'autoplay_speed',
                [
                    'type'    => Controls_Manager::NUMBER,
                    'label'   => esc_html__('Autoplay Speed (ms)', 'valuepack-addons'),
                    'default' => 2000,
                    'min'     => 0,
                    'max'     => 100000,
                    'step'    => 1,
                    'description' => esc_html__('Set the speed for autoplay in milliseconds.', 'valuepack-addons'),
                    'condition'   => [
                        'enable_slider' => 'yes',
                        'autoplay'      => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'custom_speed',
                [
                    'type'    => Controls_Manager::NUMBER,
                    'label'   => esc_html__('Speed (ms)', 'valuepack-addons'),
                    'default' => 2000,
                    'min'     => 0,
                    'max'     => 100000,
                    'step'    => 0,
                    'description' => esc_html__('Set the speed for Speed in milliseconds.', 'valuepack-addons'),
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'infinite',
                [
                    'type'    => Controls_Manager::SWITCHER,
                    'label'   => esc_html__('Infinite Loop', 'valuepack-addons'),
                    'default' => 'yes',
                    'description' => esc_html__('Enable or disable infinite loop for the slider.', 'valuepack-addons'),
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'fade_effect',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('Fade Effect', 'valuepack-addons'),
                    'default' => '',
                    'description' => esc_html__('Enable fade effect for slides transition.', 'valuepack-addons'),
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'overflow_hidden_toggle',
                [
                    'label' => esc_html__('Overflow Setting', 'valuepack-addons'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'hidden',  // Default is 'visible'
                    'options' => [
                        'hidden' => esc_html__('Hidden', 'valuepack-addons'),
                        'visible' => esc_html__('Visible', 'valuepack-addons'),
                    ],
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-list.draggable' => 'overflow: {{VALUE}}; max-width: 100%;',
                    ],
                    'frontend_available' => true,
                ]
            );

            // Draggable
            $widget->add_control(
                'draggable',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('Draggable', 'valuepack-addons'),
                    'default' => 'yes',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            // Easing Function
            $widget->add_control(
                'easing',
                [
                    'type' => Controls_Manager::SELECT,
                    'label' => esc_html__('Easing Function', 'valuepack-addons'),
                    'options' => [
                        'linear' => esc_html__('Linear', 'valuepack-addons'),
                        'ease' => esc_html__('Ease', 'valuepack-addons'),
                        'ease-in' => esc_html__('Ease In', 'valuepack-addons'),
                        'ease-out' => esc_html__('Ease Out', 'valuepack-addons'),
                    ],
                    'default' => 'linear',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'custom_arrows',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('Custom Arrows', 'valuepack-addons'),
                    'default' => 'yes',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_control(
                'prev_icon',
                [
                    'label' => __('Previous Icon', 'valuepack-addons'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-chevron-left',
                        'library' => 'fa-solid',
                    ],
                    'label_block' => true,
                    'condition'   => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );

            $widget->add_control(
                'next_icon',
                [
                    'label' => __('Next Icon', 'valuepack-addons'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-chevron-right',
                        'library' => 'fa-solid',
                    ],
                    'label_block' => true,
                    'condition'   => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                    'frontend_available' => true,
                ]
            );
            $widget->add_responsive_control(
                'icon_size',
                [
                    'label' => esc_html__('Icon Size (px)', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow svg' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'icon_color',
                [
                    'label' => esc_html__('Icon Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow i' => 'color: {{VALUE}};',
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow svg' => 'fill: {{VALUE}};stroke: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'icon_background_color',
                [
                    'label' => esc_html__('Icon Background Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'icon_hover_color',
                [
                    'label' => esc_html__('Icon Hover Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow:hover i' => 'color: {{VALUE}};',
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow:hover svg' =>  'fill: {{VALUE}};stroke: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'icon_hover_background_color',
                [
                    'label' => esc_html__('Icon Hover Background Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider  .slick-arrow:hover' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'svg_width',
                [
                    'label' => esc_html__('Arrow Width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 24,
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        'em' => [
                            'min' => 1,
                            'max' => 10,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-arrow' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'arrow_border_style',
                [
                    'label' => __('Arrow Border Style', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'None',
                    'options' => [
                        'none' => __('None', 'valuepack-addons'),
                        'solid' => __('Solid', 'valuepack-addons'),
                        'dotted' => __('Dotted', 'valuepack-addons'),
                        'dashed' => __('Dashed', 'valuepack-addons'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-style: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'arrow_border_width',
                [
                    'label' => __('Arrow Border Width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'default' => [
                        'top' => '1',
                        'right' => '1',
                        'bottom' => '1',
                        'left' => '1',
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'arrow_border_color',
                [
                    'label' => __('Arrow Border Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'arrow_border_radius',
                [
                    'label' => __('Arrow Border Radius', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 0,
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                    'selectors' => [
                        '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-radius: {{VALUE}}px;',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'icon_padding',
                [
                    'label' => esc_html__('Arrow Padding', 'valuepack-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-prev, {{WRAPPER}}.vpack-product-slider .slick-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'icon_top_position',
                [
                    'label' => esc_html__('Arrow Margin', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 50,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-prev, {{WRAPPER}}.vpack-product-slider .slick-next' => 'top: {{SIZE}}{{UNIT}} !important;',
                    ],
                    'condition'    => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'icon_prev_left_position',
                [
                    'label' => esc_html__('Prev Icon Left Position', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-prev' => 'left: {{SIZE}}{{UNIT}} !important;',
                    ],
                    'condition' => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );


            $widget->add_responsive_control(
                'icon_next_right_position',
                [
                    'label' => esc_html__('Next Icon Right Position', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.vpack-product-slider .slick-next' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'    => [
                        'custom_arrows' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'custom_dots',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('Custom Dots', 'valuepack-addons'),
                    'default' => 'yes',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],

                ]
            );
            $widget->add_responsive_control(
                'dots_bg_color',
                [
                    'label' => __('Dots Background Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ddd',
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li button' => 'background-color: {{VALUE}};',
                    ],
                    'condition'    => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'dots_bg_color_hover',
                [
                    'label' => __('Dots Background Color Hover', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ddd',
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
                    ],
                    'condition'    => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'dots_width',
                [
                    'label' => __('Dots Width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'    => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );


            $widget->add_responsive_control(
                'dots_height',
                [
                    'label' => __('Dots Height', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li button' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'   => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'active_dot_width',
                [
                    'label' => __('Active Dot Width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li.slick-active button' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'    => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );


            $widget->add_responsive_control(
                'active_dot_height',
                [
                    'label' => __('Active Dot Height', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li.slick-active button' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'   => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'dot_margin',
                [
                    'label' => esc_html__('Icon Margin', 'valuepack-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}}   .slick-dots li button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'dot_padding',
                [
                    'label' => esc_html__('Icon Padding', 'valuepack-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}}  .slick-dots li button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'dots_gap',
                [
                    'label' => __('Dots Gap', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 5,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots' => 'gap:{{SIZE}}{{UNIT}};',
                    ],
                    'condition'   => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'dot_border_style',
                [
                    'label' => __('Dots Border Style', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'None',
                    'options' => [
                        'none' => __('None', 'valuepack-addons'),
                        'solid' => __('Solid', 'valuepack-addons'),
                        'dotted' => __('Dotted', 'valuepack-addons'),
                        'dashed' => __('Dashed', 'valuepack-addons'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li' => 'border-style: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'dot_border_width',
                [
                    'label' => __('Dots Border Width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'default' => [
                        'top' => '1',
                        'right' => '1',
                        'bottom' => '1',
                        'left' => '1',
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'dot_border_color',
                [
                    'label' => __('Dots Border Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'dot_border_color_hover',
                [
                    'label' => __('Arrow Border Color Hover', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li.slick-active' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'dot_border_radius',
                [
                    'label' => __('Dots Border Radius', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 0,
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li ,{{WRAPPER}} .slick-dots li button' => 'border-radius: {{VALUE}}px;',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'sdisplay_flex',
                [
                    'label' => esc_html__('Display', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'block' => esc_html__('Block', 'valuepack-addons'),
                        'flex' => esc_html__('Flex', 'valuepack-addons'),
                    ],
                    'default' => 'flex', // Default to flex
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots' => 'display: {{VALUE}};',
                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'sflex_direction',
                [
                    'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'row' => esc_html__('Row', 'valuepack-addons'),
                        'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'column' => esc_html__('Column', 'valuepack-addons'),
                        'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots' => 'flex-direction: {{VALUE}};',

                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            // Add Position Select Control
            $widget->add_responsive_control(
                'position_select',
                [
                    'label' => esc_html__('Position', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'static' => esc_html__('Static', 'valuepack-addons'),
                        'absolute' => esc_html__('Absolute', 'valuepack-addons'),
                        'relative' => esc_html__('Relative', 'valuepack-addons'),
                        'fixed' => esc_html__('Fixed', 'valuepack-addons'),
                    ],
                    'default' => 'static',
                    'selectors' => [
                        '{{WRAPPER}}  .slick-dots' => 'position: {{VALUE}};',

                    ],
                    'condition' => [
                        'custom_dots' => 'yes',
                        'enable_slider' => 'yes',
                    ],

                ]
            );

            // Add Top Control (visible if position is absolute)
            $widget->add_responsive_control(
                'position_top',
                [
                    'label' => esc_html__('Top', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}  .slick-dots' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            // Add Bottom Control (visible if position is absolute)
            $widget->add_responsive_control(
                'position_bottom',
                [
                    'label' => esc_html__('Bottom', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots' => 'bottom: {{SIZE}}{{UNIT}}  !important;',
                    ],
                ]
            );

            // Add Left Control (visible if position is absolute)
            $widget->add_responsive_control(
                'position_left',
                [
                    'label' => esc_html__('Left', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max'  => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots' => 'left: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );

            // Add Right Control (visible if position is absolute)
            $widget->add_responsive_control(
                'position_right',
                [
                    'label' => esc_html__('Right', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}  .slick-dots' => 'right: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );

            // Add Z-Index Control (visible if position is absolute)
            $widget->add_responsive_control(
                'position_z_index',
                [
                    'label' => esc_html__('Z-Index', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => -9999,
                    'max' => 9999,
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}  .slick-dots' => 'z-index: {{VALUE}} !important;',
                    ],
                ]
            );
            $widget->add_control(
                'slider_responsive_settings_heading',
                [
                    'label' => esc_html__('Responsive Settings For Slides To Show And Scroll', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slides_to_show_tablet',
                [
                    'label' => esc_html__('Slides To Show On (Tablet)', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 3,
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slides_to_show_tablet_portrait',
                [
                    'label' => esc_html__('Slides To Show On (Tablet Portrait)', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 2,
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slides_to_show_mobile',
                [
                    'label' => esc_html__('Slides To Show On (Mobile)', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slider_responsive_settings_divider',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slides_to_scroll_tablet',
                [
                    'label' => esc_html__('Slides To Scroll On (Tablet)', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slides_to_scroll_tablet_portrait',
                [
                    'label' => esc_html__('Slides To Scroll On (Tablet Portrait)', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slides_to_scroll_mobile',
                [
                    'label' => esc_html__('Slides To Scroll On (Mobile)', 'valuepack-addons'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 1,
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'slider_hover_icon_settings_heading',
                [
                    'label' => esc_html__('Show Icons On Hover', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'show_hover_icons_slick',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('show Next Prev Icons On Section Hover', 'valuepack-addons'),
                    'default' => 'no',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'slider_progress_settings_heading',
                [
                    'label' => esc_html__('Use Slider Progress Bar', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'progress_bar_slick',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label' => esc_html__('Progreess Bar', 'valuepack-addons'),
                    'default' => '',
                    'condition' => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'progress_bar_slick_h',
                [
                    'label' => __('Progreess Bar Height', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 20,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'condition'   => [
                        'progress_bar_slick' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'prog-bar-bg',
                [
                    'label' => esc_html__('Progress Bar Background Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ddd',
                    'selectors' => [
                        '{{WRAPPER}} .slick-progress' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'progress_bar_slick' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'prog-bar-color',
                [
                    'label' => esc_html__('Progress Bar Fill Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .slick-progress-bar' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'progress_bar_slick' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slider_dots_wrap_settings_heading',
                [
                    'label' => esc_html__('Wrap Dots With Arrows', 'valuepack-addons'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'   => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'enable_wrap_dots_arrowss',
                [
                    'label'        => esc_html__('Enable Wrap Dots With Arrows', 'valuepack-addons'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                    'label_off'    => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default'      => '',
                    'condition'    => [
                        'enable_slider' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'wrap_dots_arrows_padding',
                [
                    'label' => esc_html__('Padding', 'valuepack-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-arrows-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'icon_direction_position_verticals',
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
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'vp_scrollbar_Top_positions',
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
                        '{{WRAPPER}} .slick-arrows-wrapper' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_wrap_dots_arrowss' => 'yes',
                        'icon_direction_position_verticals' => 'top',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'vp_scrollbar_bottom_positions',
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
                        '{{WRAPPER}} .slick-arrows-wrapper' =>  'bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_wrap_dots_arrowss' => 'yes',
                        'icon_direction_position_verticals' => 'bottom',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'icon_direction_positions',
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
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],

                ]
            );

            $widget->add_responsive_control(
                'vp_scrollbar_right_positions',
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
                        '{{WRAPPER}} .slick-arrows-wrapper' =>  'right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_wrap_dots_arrowss' => 'yes',
                        'icon_direction_positions' => 'right',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'vp_scrollbar_left_positions',
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
                        '{{WRAPPER}} .slick-arrows-wrapper' =>  'left: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_wrap_dots_arrowss' => 'yes',
                        'icon_direction_positions' => 'left',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'gap_between_itemsss',
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
                        '{{WRAPPER}} .slick-arrows-wrapper' =>  'gap: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'wrap_justify_contentss',
                [
                    'label' => esc_html__('Justify Content', 'valuepack-addons'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
                        'center' => esc_html__('Center', 'valuepack-addons'),
                        'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
                        'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                        'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                        'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
                    ],
                    'default' => 'center',
                    'condition' => [
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-arrows-wrapper' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );

            // Add control to enable counts instead of dots
            $widget->add_control(
                'enable_slider_counts',
                [
                    'label'        => esc_html__('Enable Slide Counts Instead of Dots', 'valuepack-addons'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                    'label_off'    => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default'      => '',
                    'condition'    => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],
                    'description'  => esc_html__('Show slide counts (e.g. 1/5) instead of dots navigation.', 'valuepack-addons'),
                ]
            );

            // Show background, border, and border radius controls when .slick-arrows-wrapper.slider-counts is enabled
            $widget->add_responsive_control(
                'slider_counts_bg_color',
                [
                    'label' => esc_html__('Counts Background Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#f5f5f5',
                    'selectors' => [
                        '{{WRAPPER}} .slick-arrows-wrapper.slider-counts' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                    ],
                ]
            );

            $widget->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'slider_counts_border',
                    'label' => esc_html__('Counts Border', 'valuepack-addons'),
                    'selector' => '{{WRAPPER}} .slick-arrows-wrapper.slider-counts ,{{WRAPPER}}  .slick-arrows-wrapper.slider-counts .slick-arrow,{{WRAPPER}}  .slick-arrows-wrapper.slider-counts .slick-counter-text',
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                    ],
                ]
            );

            $widget->add_responsive_control(
                'slider_counts_border_radius',
                [
                    'label' => esc_html__('Counts Border Radius', 'valuepack-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-arrows-wrapper.slider-counts' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                    ],
                ]
            );
            // Default typography for counts (when custom text is not enabled)
            $widget->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'slider_counts_typography',
                    'selector' => '{{WRAPPER}} .slick-arrows-counter',
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                    ],
                ]
            );

            // Text color for .slick-arrows-counter
            $widget->add_responsive_control(
                'slider_counts_text_color',
                [
                    'label' => esc_html__('Counts Text Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#222',
                    'selectors' => [
                        '{{WRAPPER}} .slick-arrows-counter' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                    ],
                ]
            );

            // Padding for .slick-arrows-counter
            $widget->add_responsive_control(
                'slider_counts_padding',
                [
                    'label' => esc_html__('Counts Padding', 'valuepack-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .slick-arrows-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                    ],
                ]
            );

            // Typography for .slick-arrows-counter
            // Switcher to enable custom text
            $widget->add_control(
                'enable_slider_counts_text',
                [
                    'label'        => esc_html__('Enable Custom Counts Text', 'valuepack-addons'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                    'label_off'    => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default'      => '',
                    'condition'    => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                    ],
                ]
            );

            // Text control for custom counts text
            $widget->add_control(
                'slider_counts_custom_text',
                [
                    'label' => esc_html__('Counts Custom Text', 'valuepack-addons'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__('e.g. Slide {current} of {total}', 'valuepack-addons'),
                    'description' => esc_html__('Use {current} for current slide and {total} for total slides.', 'valuepack-addons'),
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                        'enable_slider_counts_text' => 'yes',
                    ],
                ]
            );

            // Text color for custom counts text
            $widget->add_responsive_control(
                'slider_counts_custom_text_color',
                [
                    'label' => esc_html__('Text Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#222',
                    'selectors' => [
                        '{{WRAPPER}} .slick-counter-text' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                        'enable_slider_counts_text' => 'yes',
                    ],
                ]
            );

            // Typography for custom counts text
            $widget->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'slider_counts_custom_text_typography',
                    'selector' => '{{WRAPPER}} .slick-counter-text',
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                        'enable_slider_counts_text' => 'yes',
                    ],
                ]
            );

            // Padding for custom counts text
            $widget->add_responsive_control(
                'slider_counts_custom_text_padding',
                [
                    'label' => esc_html__('Text Padding', 'valuepack-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .slick-counter-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                        'enable_slider_counts' => 'yes',
                        'enable_slider_counts_text' => 'yes',
                    ],
                ]
            );



            // 1. Enable Animated Dots
            $widget->add_control(
                'enable_animated_dots',
                [
                    'label' => __('Enable Animated Dots', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],
                ]
            );

            // 2. Animation Background Color (visible only when enabled)
            $widget->add_control(
                'dots_animation_color',
                [
                    'label' => __('Animation Bar Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li button::after' => 'background-color: {{VALUE}};
                         content: "";
                            position: absolute;
                            bottom: 0;
                            left: 0;
                            height: 100%; 
                            width: 0%; ',
                        '{{WRAPPER}} .slick-dots li.slick-active button::after' => ' transition: {{autoplay_speed.VALUE}}ms; width: 100%;',
                        '{{WRAPPER}} .slick-dots li button' => 'position:relative;',
                    ],
                    'condition' => [
                        'enable_animated_dots' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'enable_dot_label_text',
                [
                    'label' => esc_html__('Enable Dot Label Text', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'valuepack-addons'),
                    'label_off' => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'enable_slider' => 'yes',
                        'enable_wrap_dots_arrowss' => 'yes',
                    ],
                ]
            );

            $widget->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'dot_label_typography',
                    'label' => esc_html__('Dot Label Typography', 'valuepack-addons'),
                    'selector' => '{{WRAPPER}} .ep-swiper-slider .slick-dots button span',
                    'condition' => [
                        'enable_dot_label_text' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'dots_title_color',
                [
                    'label' => __('Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots button span' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_dot_label_text' => 'yes',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'dot_label_bottom_spacing',
                [
                    'label' => esc_html__('Dot Label Bottom Spacing', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -100, 'max' => 100],
                        '%'  => ['min' => -50, 'max' => 50],
                        'em' => ['min' => -10, 'max' => 10],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots button span' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_dot_label_text' => 'yes',
                    ],
                ]
            );


            $widget->end_controls_section();
        }
    }

    add_action('elementor/element/after_section_end', 'value_pack_add_swiper_controls', 25, 3);
}
if (!function_exists('value_pack_render_swiper_slider')) {
    function value_pack_render_swiper_slider($widget)
    {
        $settings = $widget->get_settings_for_display();

        $container_title_options = isset($settings['container_title_options']) ? $settings['container_title_options'] : '';


        if (!empty($container_title_options)) {
            $widget->add_render_attribute(
                '_wrapper',
                [
                    'data-container-title' => $container_title_options,

                ]
            );
        }



        if (isset($settings['enable_slider']) && $settings['enable_slider'] == 'yes') {


            $slider_device_option = $settings['slider_device_option'] ?? 'all_devices';

            $is_mobile = wp_is_mobile();

            $enable_slider = !empty($settings['enable_slider']) ? 'vpack-product-slider' : '';
            $rendomid =   value_pack_slider_random_string(8);

            if (isset($settings['progress_bar_slick']) && $settings['progress_bar_slick'] == 'yes') {
?>
                <style>
                    <?php echo '.' . esc_attr($rendomid); ?>+.slick-progress {
                        background-color: <?php echo esc_attr($settings['prog-bar-bg']); ?>;
                    }

                    <?php echo '.' . esc_attr($rendomid); ?>+.slick-progress .slick-progress-bar {
                        background-color: <?php echo esc_attr($settings['prog-bar-color']); ?>;
                        height: <?php echo esc_attr($settings['progress_bar_slick_h']['size']) . esc_attr($settings['progress_bar_slick_h']['unit']); ?>;
                    }
                </style>
<?php
            }
            $prev_icon = '';
            if (! empty($settings['prev_icon']['value'])) {
                if (
                    'svg' === $settings['prev_icon']['library']
                    && ! empty($settings['prev_icon']['value']['url'])
                ) {
                    $prev_icon_url = esc_url_raw($settings['prev_icon']['value']['url']);
                    $response = wp_remote_get($prev_icon_url, ['timeout' => 5]);
                    if (
                        ! is_wp_error($response)
                        && wp_remote_retrieve_response_code($response) === 200
                    ) {
                        $prev_icon = wp_remote_retrieve_body($response);
                    }
                } else {
                    $prev_icon = '<i class="' . esc_attr($settings['prev_icon']['value']) . '"></i>';
                }
            }


            $next_icon = '';
            if (! empty($settings['next_icon']['value'])) {
                if (
                    'svg' === $settings['next_icon']['library']
                    && ! empty($settings['next_icon']['value']['url'])
                ) {
                    $next_icon_url = esc_url_raw($settings['next_icon']['value']['url']);
                    $response = wp_remote_get($next_icon_url, ['timeout' => 5]);
                    if (
                        ! is_wp_error($response)
                        && wp_remote_retrieve_response_code($response) === 200
                    ) {
                        $next_icon = wp_remote_retrieve_body($response);
                    }
                } else {
                    $next_icon = '<i class="' . esc_attr($settings['next_icon']['value']) . '"></i>';
                }
            }


            $hoverIcon_class = '';
            if (isset($settings['show_hover_icons_slick']) && $settings['show_hover_icons_slick'] == 'yes') {
                $hoverIcon_class =  'icon-on-hover';
            }

            // Define the slider settings
            $slides_to_show = $settings['slides_to_show'];
            $slides_to_scroll = $settings['slides_to_scroll'];
            $easing = $settings['easing'];
            $autoplay = $settings['autoplay'] === 'yes' ? true : false;
            $autoplay_speed = $settings['autoplay_speed'];
            $custom_speed = $settings['custom_speed'];
            $infinite = $settings['infinite'] === 'yes' ? true : false;
            $variableWidth = $settings['variableWidth'] === 'yes' ? true : false;
            $fade_effect = $settings['fade_effect'] === 'yes' ? true : false;
            $draggable = $settings['draggable'] === 'yes' ? true : false;
            $custom_dots = $settings['custom_dots'] === 'yes' ? true : false;
            $custom_arrows = $settings['custom_arrows'] === 'yes' ? true : false;
            $vertical = $settings['vertical'] === 'yes' ? true : false;
            $scroll_swipe = $settings['scroll_swipe'] === 'yes' ? true : false;
            $enable_wrap_dots_arrows = $settings['enable_wrap_dots_arrowss'] === 'yes' ? true : false;
            $enable_slider_counts = $settings['enable_slider_counts'] === 'yes' ? true : false;
            $progress_bar_slick = $settings['progress_bar_slick'] === 'yes' ? true : false;
            $slider_counts_custom_text = $settings['slider_counts_custom_text'] ? $settings['slider_counts_custom_text'] : '';

            // Responsive settings for tablets and mobile
            $slides_to_show_tablet = $settings['slides_to_show_tablet'];
            $slides_to_show_tablet_portrait = $settings['slides_to_show_tablet_portrait'];
            $slides_to_show_mobile = $settings['slides_to_show_mobile'];
            $slides_to_scroll_tablet = $settings['slides_to_scroll_tablet'];
            $slides_to_scroll_tablet_portrait = $settings['slides_to_scroll_tablet_portrait'];
            $slides_to_scroll_mobile = $settings['slides_to_scroll_mobile'];
            $enable_dot_label_text = isset($settings['enable_dot_label_text']) ? $settings['enable_dot_label_text'] : 'no';
            // Add the necessary attributes for Swiper
            $widget->add_render_attribute(
                '_wrapper',
                [
                    'class' => 'ep-swiper-slider ' . $enable_slider . ' ' . $rendomid . ' ' . $hoverIcon_class,
                    'data-prev-arrow' => $prev_icon,
                    'data-next-arrow' => $next_icon,
                    'data-enable-title' => $enable_dot_label_text,
                    'data-slides-to-show' => $slides_to_show,
                    'data-slides-to-scrol' => $slides_to_scroll,
                    'data-autoplay' =>   esc_attr($autoplay ? 'true' : 'false'),
                    'data-autoplay-speed' => esc_attr($autoplay_speed),
                    'data-custom_speed' => esc_attr($custom_speed),
                    'data-infinite' => esc_attr($infinite ? 'true' : 'false'),
                    'data-variableWidth' => esc_attr($variableWidth ? 'true' : 'false'),
                    'data-fade_effect' => esc_attr($fade_effect ? 'true' : 'false'),
                    'data-draggable' => esc_attr($draggable ? 'true' : 'false'),
                    'data-progressbar' => esc_attr($progress_bar_slick ? 'true' : 'false'),
                    'data-scroll_swipe' => esc_attr($scroll_swipe ? 'true' : 'false'),
                    'data-custom_dots' => esc_attr($custom_dots ? 'true' : 'false'),
                    'data-custom_arrows' => esc_attr($custom_arrows ? 'true' : 'false'),
                    'data-vertical' => esc_attr($vertical ? 'true' : 'false'),
                    'data-wrap_dots_arrows' => esc_attr($enable_wrap_dots_arrows ? 'true' : 'false'),
                    'data-enable_slider_counts' => esc_attr($enable_slider_counts ? 'true' : 'false'),
                    'data-easing' => $easing,
                    'data-slides_to_show_tablet' => $slides_to_show_tablet,
                    'data-slider_device_option' => $slider_device_option,
                    'data-slides_to_show_tablet_portrait' => $slides_to_show_tablet_portrait,
                    'data-slides_to_show_mobile' => $slides_to_show_mobile,
                    'data-slides_to_scroll_tablet' => $slides_to_scroll_tablet,
                    'data-slides_to_scroll_tablet_portrait' => $slides_to_scroll_tablet_portrait,
                    'data-slides_to_scroll_mobile' => $slides_to_scroll_mobile,
                    'data-slider_counts_custom_text' => $slider_counts_custom_text,
                ]
            );
        }
    }

    add_action('elementor/frontend/before_render', 'value_pack_render_swiper_slider');
}


if (!function_exists('value_pack_add_slider_animation_controls')) {
    function value_pack_add_slider_animation_controls($widget, $section_id, $args)
    {
        if ('container' != $widget->get_name() && 'section_custom_css_pro' === $section_id) {


            $widget->start_controls_section(
                'swiper_controls_section',
                [
                    'label' => __('Slider Item - Value Pack', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );

            // Add the switcher to enable slider item
            $widget->add_control(
                'enable_slider_item',
                [
                    'label'        => esc_html__('Slider Item', 'valuepack-addons'),
                    'description'  => esc_html__('Is this an item of the slider? Enable if you want to animate your item in slick active.', 'valuepack-addons'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                    'label_off'    => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default'      => '',
                ]
            );

            // Add the select control for animation styles
            $widget->add_control(
                'slider_animation_style',
                [
                    'label'       => esc_html__('Animation Style', 'valuepack-addons'),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'fadeIn',
                    'options'     => [
                        'fadeIn'      => esc_html__('Fade In', 'valuepack-addons'),
                        'fadeOut'     => esc_html__('Fade Out', 'valuepack-addons'),
                        'fadeInUp'     => esc_html__('Fade in Up', 'valuepack-addons'),
                        'fadeInDown'     => esc_html__('Fade in Down', 'valuepack-addons'),
                        'slideInLeft' => esc_html__('Slide In Left', 'valuepack-addons'),
                        'slideInRight' => esc_html__('Slide In Right', 'valuepack-addons'),
                        'zoomIn'      => esc_html__('Zoom In', 'valuepack-addons'),
                        'zoomOut'     => esc_html__('Zoom Out', 'valuepack-addons'),
                        // Add more animation styles as per slickAnimation's available options
                    ],
                    'condition' => [
                        'enable_slider_item' => 'yes',
                    ],
                ]
            );
            $widget->add_control(
                'slider_animation_out_style',
                [
                    'label'       => esc_html__('Animation Out Style', 'valuepack-addons'),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'fadeIn',
                    'options'     => [
                        'fadeIn'      => esc_html__('Fade In', 'valuepack-addons'),
                        'fadeOut'     => esc_html__('Fade Out', 'valuepack-addons'),
                        'fadeInUp'     => esc_html__('Fade in Up', 'valuepack-addons'),
                        'fadeInDown'     => esc_html__('Fade in Down', 'valuepack-addons'),
                        'slideInLeft' => esc_html__('Slide In Left', 'valuepack-addons'),
                        'slideInRight' => esc_html__('Slide In Right', 'valuepack-addons'),
                        'zoomIn'      => esc_html__('Zoom In', 'valuepack-addons'),
                        'zoomOut'     => esc_html__('Zoom Out', 'valuepack-addons'),
                        // Add more animation styles as per slickAnimation's available options
                    ],
                    'condition' => [
                        'enable_slider_item' => 'yes',
                    ],
                ]
            );


            // Add controls for animation delay, duration, etc. based on the selected animation style
            $widget->add_control(
                'slider_animation_delay_in',
                [
                    'label'       => esc_html__('Animation In Delay (seconds)', 'valuepack-addons'),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => 2,
                    'min'         => 0,
                    'max'         => 10,
                    'step'        => 0.1,
                    'condition' => [
                        'enable_slider_item' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slider_animation_duration_in',
                [
                    'label'       => esc_html__('Animation In Duration (seconds)', 'valuepack-addons'),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => 2,
                    'min'         => 0,
                    'max'         => 10,
                    'step'        => 0.1,
                    'condition' => [
                        'enable_slider_item' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slider_animation_delay_out',
                [
                    'label'       => esc_html__('Animation Out Delay (seconds)', 'valuepack-addons'),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => 2,
                    'min'         => 0,
                    'max'         => 10,
                    'step'        => 0.1,
                    'condition' => [
                        'enable_slider_item' => 'yes',
                    ],
                ]
            );

            $widget->add_control(
                'slider_animation_duration_out',
                [
                    'label'       => esc_html__('Animation Out Duration (seconds)', 'valuepack-addons'),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => 2,
                    'min'         => 0,
                    'max'         => 10,
                    'step'        => 0.1,
                    'condition' => [
                        'enable_slider_item' => 'yes',
                    ],
                ]
            );

            $widget->end_controls_section();
        }
    }

    add_action('elementor/element/after_section_end', 'value_pack_add_slider_animation_controls', 25, 3);
}

// Rendering function to display the tooltip on the main wrapper
if (!function_exists('value_pack_render_swiper_sliders')) {
    function value_pack_render_swiper_sliders($widget)
    {
        // Get the widget settings
        $settings = $widget->get_settings_for_display();

        // Check if the tooltip is enabled and text is set
        if (!empty($settings['enable_slider_item']) && $settings['enable_slider_item'] === 'yes') {


            $widget->add_render_attribute(
                '_wrapper',
                [
                    'class' => 'animated',
                    'data-animation-in' => 'animate__' . $settings['slider_animation_style'],
                    'data-delay-in' => $settings['slider_animation_delay_in'],
                    'data-duration-in' => $settings['slider_animation_duration_in'],
                    'data-animation-out' => $settings['slider_animation_out_style'],
                    'data-delay-out' => $settings['slider_animation_delay_out'],
                    'data-duration-out' => $settings['slider_animation_duration_out'],
                ]
            );
        }
    }

    add_action('elementor/frontend/before_render', 'value_pack_render_swiper_sliders', 1);
}

/**
 * Generate a random string of specified length
 *
 * @param int $length Length of the random string
 * @return string Random string
 */
if (!function_exists('value_pack_slider_random_string')) {

    function value_pack_slider_random_string($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Generate the random string
        for ($i = 0; $i < $length; $i++) {
            // Use rand() to get a random index
            $randomIndex = wp_rand(0, $charactersLength - 1);
            $randomString .= $characters[$randomIndex];
        }

        return $randomString;
    }
}
