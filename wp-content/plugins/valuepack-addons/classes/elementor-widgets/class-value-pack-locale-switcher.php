<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

class Value_Pack_Locale_Switcher extends Value_Pack_Widget_Base
{
    public function get_name()
    {
        return 'vp_locale_switcher';
    }

    public function get_title()
    {
        return __('Language & Currency Switcher', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-globe vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Language & Currency Options', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Only Repeater
        $repeater = new Repeater();

        // Country Name
        $repeater->add_control(
            'country_name',
            [
                'label' => __('Country Name', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('e.g. United States', 'valuepack-addons'),
            ]
        );

        // Currency Name
        $repeater->add_control(
            'currency_name',
            [
                'label' => __('Currency Name', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('e.g. USD ($)', 'valuepack-addons'),
            ]
        );

        // Flag Image
        $repeater->add_control(
            'flag_image',
            [
                'label' => __('Flag/Icon', 'valuepack-addons'),
                'type' => Controls_Manager::MEDIA,

            ]
        );

        $this->add_control(
            'locale_items',
            [
                'label' => __('Add Country & Country', 'valuepack-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'country_name' => 'United States',
                        'currency_name' => 'USD ($)',
                        'flag_image' => ['url' => Utils::get_placeholder_image_src()],
                    ],
                ],
                'title_field' => '{{{ country_name }}} - {{{ currency_name }}}',
            ]
        );

        $this->add_control(
            'dropdown_position',
            [
                'label' => __('Dropdown Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom-left',
                'options' => [
                    'top-left' => __('Top Left', 'valuepack-addons'),
                    'top-right' => __('Top Right', 'valuepack-addons'),
                    'bottom-left' => __('Bottom Left', 'valuepack-addons'),
                    'bottom-right' => __('Bottom Right', 'valuepack-addons'),
                ],
            ]
        );

        // bottom left
        $this->add_responsive_control(
            'dropdown_position_hidden_bottom_left',
            [
                'label' => __('Dropdown Hidden Bottom Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-bottom-left .vp-locale-dropdown' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['bottom-left'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_active_bottom_left',
            [
                'label' => __('Dropdown Active Bottom Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-bottom-left.vp-dropdown-toggled .vp-locale-dropdown' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['bottom-left'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_default_bottom_left',
            [
                'label' => __('Dropdown Bottom Left Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
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
                    '{{WRAPPER}} .vp-dropdown-bottom-left .vp-locale-dropdown' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['bottom-left'],
                ],
            ]
        );

        // bottom right
        $this->add_responsive_control(
            'dropdown_position_hidden_bottom_right',
            [
                'label' => __('Dropdown Hidden Bottom Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-bottom-right .vp-locale-dropdown' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['bottom-right'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_active_bottom_right',
            [
                'label' => __('Dropdown Active Bottom Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-bottom-right.vp-dropdown-toggled .vp-locale-dropdown' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['bottom-right'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_default_bottom_right',
            [
                'label' => __('Dropdown Bottom Right Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
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
                    '{{WRAPPER}} .vp-dropdown-bottom-right .vp-locale-dropdown' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['bottom-right'],
                ],
            ]
        );

        // top left
        $this->add_responsive_control(
            'dropdown_position_hidden_top_left',
            [
                'label' => __('Dropdown Hidden Top Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-top-left .vp-locale-dropdown' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['top-left'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_active_top_left',
            [
                'label' => __('Dropdown Active Top Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-top-left.vp-dropdown-toggled .vp-locale-dropdown' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['top-left'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_default_top_left',
            [
                'label' => __('Dropdown Top Left Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
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
                    '{{WRAPPER}} .vp-dropdown-top-left .vp-locale-dropdown' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['top-left'],
                ],
            ]
        );

        // top right
        $this->add_responsive_control(
            'dropdown_position_hidden_top_right',
            [
                'label' => __('Hidden Items Top Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-top-right .vp-locale-dropdown' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['top-right'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_active_top_right',
            [
                'label' => __('Active Item Top Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-dropdown-top-right.vp-dropdown-toggled .vp-locale-dropdown' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['top-right'],
                ],
            ]
        );
        $this->add_responsive_control(
            'dropdown_position_default_top_right',
            [
                'label' => __('Items Top Right Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
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
                    '{{WRAPPER}} .vp-dropdown-top-right .vp-locale-dropdown' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_position' => ['top-right'],
                ],
            ]
        );

        $this->add_control(
            'dropdown_trigger_transition',
            [
                'label' => __('Dropdown Trigger Transition Duration', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    's' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.3,
                    'unit' => 's',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown' => 'transition: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'default_style_section',
            [
                'label' => __('Default Field Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'default_selected_field_icon',
            [
                'label' => __('Default Field Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-down',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'default_option_typography',
                'label' => __('Default Option Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-locale-toggle .vp-locale-current-text, {{WRAPPER}} .vp-locale-toggle', 
            ]
        );

        $this->add_responsive_control(
            'default_selected_icon_size',
            [
                'label' => __('Default Field Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 3,
                    ],
                ],
                'default' => [
                    'size' => 7,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle .vp-locale-dropdown-arrow i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-locale-toggle .vp-locale-dropdown-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'default_selected_text_color',
            [
                'label' => __('Default Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'default_selected_icon_color',
            [
                'label' => __('Default Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle .vp-locale-dropdown-arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-locale-toggle .vp-locale-dropdown-arrow svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'default_selected_icon_text_color_hover',
            [
                'label' => __('Default Text & Icon Color Hover', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-locale-toggle:hover .vp-locale-dropdown-arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-locale-toggle:hover .vp-locale-dropdown-arrow svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'default_selected_field_image_size',
            [
                'label' => __('Default Field Image Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'default_selected_field_image_radius',
            [
                'label' => __('Default Image Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'default_selected_field_background',
            [
                'label' => __('Default Field Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'description' => __('Set the background color for the dropdown trigger element.', 'valuepack-addons'),
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle' => 'background: {{VALUE}}',
                ],
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'default_selected_field_background_hover',
            [
                'label' => __('Default Field Background (Hover)', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'description' => __('Set the background color when hovering the dropdown trigger.', 'valuepack-addons'),
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle:hover' => 'background: {{VALUE}}',
                ],
                'default' => '#f5f5f5',
            ]
        );

        $this->add_responsive_control(
            'default_selected_field_option_padding',
            [
                'label' => __('Default Field Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'default_selected_field_option_radius',
            [
                'label' => __('Default Field Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'default_selected_transition',
            [
                'label' => __('Transition Duration', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle' => 'transition: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_responsive_control(
            'default_selected_field_flex_direction',
            [
                'label' => __('Default Field Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'default_selected_field_justify_content',
            [
                'label' => __('Default Field Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('End', 'valuepack-addons'),
                    'space-between' => __('Space Between', 'valuepack-addons'),
                    'space-around' => __('Space Around', 'valuepack-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'default_selected_field_icon_margin',
            [
                'label' => __('Default Field Icon Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle .vp-locale-dropdown-arrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'default_selected_field_image_margin',
            [
                'label' => __('Default Field Image Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-toggle img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'dropdown_style_section',
            [
                'label' => __('Dropdown Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dropdown_background_color',
            [
                'label' => __('Dropdown Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_padding',
            [
                'label' => __('Dropdown Padding', 'valuepack-addons'),
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
                    '{{WRAPPER}} .vp-locale-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_border_radius',
            [
                'label' => __('Dropdown Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_height',
            [
                'label' => __('Dropdown Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'label' => __('Dropdown Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-locale-dropdown',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'label' => __('Dropdown Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-locale-dropdown',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'dropdown_items_style_section',
            [
                'label' => __('Dropdown Items Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dropdown_items_margin',
            [
                'label' => __('Items Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_items_text_color',
            [
                'label' => __('Items Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_items_hover_text_color',
            [
                'label' => __('Items Hover Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_items_background',
            [
                'label' => __('Items Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_items_hover_background',
            [
                'label' => __('Items Hover Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_active_items_color',
            [
                'label' => __('Active Item Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li.vp-locale-active-option' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_active_items_background',
            [
                'label' => __('Active Item Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li.vp-locale-active-option' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'items_background_transition',
            [
                'label' => __('Transition Duration', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li' => 'transition: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_items_padding',
            [
                'label' => __('Items Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_items_border_radius',
            [
                'label' => __('Items Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-option' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_items_typography',
                'label' => __('Items Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-locale-dropdown li',
            ]
        );

        $this->add_responsive_control(
            'dropdown_items_image_width_height',
            [
                'label' => __('Items Image Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dropdown_items_image_radius',
            [
                'label' => __('Items Image Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_items_flex_direction',
            [
                'label' => __('Item Field Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_items_image_margin',
            [
                'label' => __('Items Image Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-locale-dropdown li img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $items = $settings['locale_items'];
        $dropdown_position = $settings['dropdown_position'] ?? 'bottom-left';
        $first_item = reset($items);
        $default_text = $first_item['country_name'] . $first_item['currency_name'];
        $default_image = $first_item['flag_image'];
?>
        <div class="vp-locale-switcher-container vp-dropdown-<?php echo esc_attr($dropdown_position); ?>">
            <div class="vp-locale-toggle">
                <?php if (!empty($default_image['url'])) : ?>
                    <img src="<?php echo esc_url($default_image['url']); ?>" alt="<?php echo esc_attr($default_text); ?>" class="vp-locale-flag-icon" />
                <?php endif; ?>
                <span class="vp-locale-current-text"><?php echo esc_html($default_text); ?></span>
                <span class="vp-locale-dropdown-arrow">
                    <?php Icons_Manager::render_icon($settings['default_selected_field_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            </div>
            <ul class="vp-locale-dropdown" name="vp_site_language" tabindex="0">
                <?php foreach ($items as $index => $item) :
                    $text = $item['country_name'] . $item['currency_name'];
                    $is_active = $index === 0 ? 'vp-locale-active-option' : '';
                ?>
                    <li class="vp-locale-option <?php echo esc_attr($is_active); ?>"
                        value="<?php echo esc_attr(strtolower(str_replace(' ', '_', $text))); ?>"
                        data-country="<?php echo esc_attr($item['country_name']); ?>"
                        data-currency="<?php echo esc_attr($item['currency_name']); ?>">
                        <?php if (!empty($item['flag_image']['url'])) : ?>
                            <img src="<?php echo esc_url($item['flag_image']['url']); ?>" class="vp-locale-flag-icon" />
                        <?php endif; ?>
                        <?php echo esc_html($text); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
<?php
    }
}
