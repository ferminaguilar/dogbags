<?php

/**
 * Buttons Module
 *
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

if (!function_exists('value_pack_inject_custom_button_control')) {
    function value_pack_inject_custom_button_control($element, $args)
    {
        // Check if the element is a button
        if ('button' === $element->get_name()) {
            // Add Button Style Select Control to the Style Tab
            $element->add_control(
                'button_style_select',
                [
                    'label' => esc_html__('Select Button Style', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'default' => esc_html__('Default', 'valuepack-addons'),
                        'vpack-buttons-1' => esc_html__('Style 1', 'valuepack-addons'),
                        'vpack-buttons-2' => esc_html__('Style 2', 'valuepack-addons'),
                        'vpack-buttons-3' => esc_html__('Style 3', 'valuepack-addons'),
                        'vpack-buttons-4' => esc_html__('Style 4', 'valuepack-addons'),
                        'vpack-buttons-5' => esc_html__('Style 5', 'valuepack-addons'),
                        'vpack-buttons-6' => esc_html__('Style 6', 'valuepack-addons'),
                    ],
                    'default' => 'default',
                ]
            );

            // Icon Size
            $element->add_control(
                'button_icon_size',
                [
                    'label' => esc_html__('Icon Size', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'em', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                        'em' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $element->add_control(
                'button_align_items',
                [
                    'label' => esc_html__('Align Items', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'flex-start' => esc_html__('Start', 'valuepack-addons'),
                        'center' => esc_html__('Center', 'valuepack-addons'),
                        'flex-end' => esc_html__('End', 'valuepack-addons'),
                        'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                        'baseline' => esc_html__('Baseline', 'valuepack-addons'),
                    ],
                    'default' => 'center',
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button-content-wrapper' => 'align-items: {{VALUE}};',
                    ],
                ]
            );

            // Add Blur Filter
            $element->add_control(
                'blur_filter',
                [
                    'label' => esc_html__('Blur Filter', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-ep-buttons' => 'backdrop-filter: blur({{SIZE}}{{UNIT}});',
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0,
                    ],
                ]
            );

            // Add Border Color Hover Control to the Style Tab
            $element->add_control(
                'border_color_hover',
                [
                    'label' => esc_html__('Border Hover Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-ep-buttons::after, {{WRAPPER}} .elementor-ep-buttons::before, {{WRAPPER}} .elementor-ep-buttons:hover::after, {{WRAPPER}} .elementor-ep-buttons:hover::before' => 'border-color: {{VALUE}} !IMPORTANT;',
                    ],
                    'default' => '#FF0000',
                    'condition' => [
                        'button_style_select' => ['vpack-buttons-1', 'vpack-buttons-2', 'vpack-buttons-3', 'vpack-buttons-4', 'vpack-buttons-5',],
                    ],
                ]
            );
            // Add Icon Hover Color Control to the Style Tab
            $element->add_control(
                'icon_hover_color',
                [
                    'label' => esc_html__('Icon Hover Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} svg path' => 'transition: 1.2s;',
                        '{{WRAPPER}}:hover svg path' => 'fill: {{VALUE}} !IMPORTANT;',
                        '{{WRAPPER}}:hover i' => 'color: {{VALUE}} !IMPORTANT;',
                    ],
                    'default' => '#FF0000',
                ]
            );

            $element->add_control(
                'svg_hover_stroke',
                [
                    'label' => esc_html__('SVG Hover Stroke', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} svg path' => 'transition: 1.2s;',
                        '{{WRAPPER}}:hover svg path' => 'stroke: {{VALUE}} !IMPORTANT;',
                    ],
                    'default' => '',
                ]
            );
        }
    }

    add_action('elementor/element/button/section_style/before_section_end', 'value_pack_inject_custom_button_control', 10, 2);
}


if (!function_exists('valuepack_inject_popup_section')) {
    function valuepack_inject_popup_section($element, $args)
    {
        global $valuepack_popups;  

        if (empty($valuepack_popups)) {
            $valuepack_popups = value_pack_get_popup_lists('popup'); 
        }

        $popup_options = $popup_options_close = [];

        if (!empty($valuepack_popups)) {
            foreach ($valuepack_popups as $popup_id => $popup_data) {
                $post_popup_close = get_post_meta($popup_id, 'post_popup_close', true);
                $post_popup_open  = get_post_meta($popup_id, 'post_popup_open', true);

                if (!empty($post_popup_open)) {
                    $popup_options[$post_popup_open] = $popup_data['title'];
                }
                if (!empty($post_popup_close)) {
                    $popup_options_close[$post_popup_close] = $popup_data['title'];
                }
            }
        }

        $popup_options['custom_popup']       = esc_html__('Custom Popup (Manual ID)', 'valuepack-addons');
        $popup_options_close['custom_popup'] = esc_html__('Custom Popup (Manual ID)', 'valuepack-addons');

         
        $element->start_controls_section(
            'valuepack_popup_section',
            [
                'label' => esc_html__('Value Pack Popup', 'valuepack-addons'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

         
        $element->add_control(
            'valuepack_popup_action_type',
            [
                'label' => esc_html__('Popup Action Type', 'valuepack-addons'),
                'type'  => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'open'  => esc_html__('Open Popup', 'valuepack-addons'),
                    'close' => esc_html__('Close Popup', 'valuepack-addons'),
                ],
                'default' => 'open',
            ]
        );

         
        $element->add_control(
            'valuepack_popup_select_open',
            [
                'label' => esc_html__('Select Popup to Open', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $popup_options,
                'default' => '',
                'condition' => [
                    'valuepack_popup_action_type' => 'open',
                ],
            ]
        );

         
        $element->add_control(
            'valuepack_popup_select_close',
            [
                'label' => esc_html__('Select Popup to Close', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $popup_options_close,
                'default' => '',
                'condition' => [
                    'valuepack_popup_action_type' => 'close',
                ],
            ]
        );

         
        $element->add_control(
            'valuepack_custom_popup_id',
            [
                'label' => esc_html__('Custom Popup ID', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter popup ID or selector...', 'valuepack-addons'),
                'condition' => [
                    'valuepack_popup_select_open' => 'custom_popup',
                ],
            ]
        );

        $element->end_controls_section();
    }

    add_action('elementor/element/button/section_button/after_section_end', 'valuepack_inject_popup_section', 10, 2);
}

if (!function_exists('value_pack_custom_button_render_content')) {
    function value_pack_custom_button_render_content($widget)
    {
        if ('button' === $widget->get_name()) {
            $settings = $widget->get_settings_for_display();

            $button_style = !empty($settings['button_style_select']) ? $settings['button_style_select'] : 'default';
            $popup_open   = !empty($settings['valuepack_popup_select_open']) ? $settings['valuepack_popup_select_open'] : '';
            $popup_close  = !empty($settings['valuepack_popup_select_close']) ? $settings['valuepack_popup_select_close'] : '';
            $custom_id    = !empty($settings['valuepack_custom_popup_id']) ? $settings['valuepack_custom_popup_id'] : '';

            // Base button class
            $widget->add_render_attribute('button', 'class', 'elementor-ep-buttons ' . esc_attr($button_style));

            // ✅ Add ID for open or close (based on selection)
            if ($popup_open && $popup_open !== 'custom_popup') {
                $widget->add_render_attribute('button', 'id', esc_attr($popup_open));
            } elseif ($popup_close && $popup_close !== 'custom_popup') {
                $widget->add_render_attribute('button', 'id', esc_attr($popup_close));
            } elseif ($custom_id) {
                $widget->add_render_attribute('button', 'id', esc_attr($custom_id));
            }

            // ✅ Add data attributes for popup open
            if ($popup_open && $popup_open !== 'custom_popup') {
                $widget->add_render_attribute('button', 'data-vp-popup-open', esc_attr($popup_open));
            }

            // ✅ Add data attributes for popup close
            if ($popup_close && $popup_close !== 'custom_popup') {
                $widget->add_render_attribute('button', 'data-vp-popup-close', esc_attr($popup_close));
            }

            // ✅ Handle custom popup ID (manual)
            if ($popup_open === 'custom_popup' && $custom_id) {
                $widget->add_render_attribute('button', 'data-vp-popup-open', esc_attr($custom_id));
            }

            if ($popup_close === 'custom_popup' && $custom_id) {
                $widget->add_render_attribute('button', 'data-vp-popup-close', esc_attr($custom_id));
            }
        }
    }

    add_action('elementor/frontend/before_render', 'value_pack_custom_button_render_content');
}