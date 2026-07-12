<?php

/**
 * Cubewp Repeater Widget
 *
 * @package valuepack-addons/cube/classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;

/**
 * Cubewp Repeater Widget
 */
class Value_Pack_CubeWp_Repeater extends Value_Pack_Widget_Base
{
    public function get_name()
    {
        return 'vp_cubewp_post_repeater';
    }

    public function get_title()
    {
        return __('CubeWP Repeater', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-post-list vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    /**
     * Get script dependencies
     */
    public function get_script_depends()
    {
        $scripts = parent::get_script_depends();
        // Add Cubewp scripts for single page rendering
        if (class_exists('CubeWp_Enqueue')) {
            $scripts[] = 'cubewp-pretty-photo';
            $scripts[] = 'cubewp-leaflet';
            $scripts[] = 'cubewp-map';
        }
        return $scripts;
    }

    /**
     * Get style dependencies
     */
    public function get_style_depends()
    {
        $styles = parent::get_style_depends();
        // Add Cubewp styles for single page rendering
        if (class_exists('CubeWp_Enqueue')) {
            $styles[] = 'cubewp-pretty-photo';
            $styles[] = 'cwp-map-cluster';
            $styles[] = 'cwp-leaflet-css';
        }
        return $styles;
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'valuepack-addons'),
            ]
        );


        // Post Type Selector
        $post_types = get_post_types(['public' => true], 'objects');
        $post_type_options = array('' => __('Select Post Type', 'valuepack-addons'));
        foreach ($post_types as $post_type) {
            $post_type_options[$post_type->name] = $post_type->label;
        }

        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_type_options,
                'default' => '',
            ]
        );

        // Display Style
        $this->add_control(
            'display_style',
            [
                'label' => __('Display Style', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'accordion' => __('Accordion', 'valuepack-addons'),
                    'static' => __('Static', 'valuepack-addons'),
                    'tabber' => __('Tabber', 'valuepack-addons'),
                ],
                'default' => 'accordion',
            ]
        );


        // Get all repeater fields for all post types

        // Get all repeater fields for all post types
        $repeater_field_options = array('' => __('Select Repeater Field', 'valuepack-addons'));
        $repeater_field_sub_fields = array();

        if (function_exists('get_fields_by_type') && function_exists('get_field_options')) {
            $all_repeater_fields = get_fields_by_type(array('repeating_field'));
            if (!empty($all_repeater_fields) && is_array($all_repeater_fields)) {
                foreach ($all_repeater_fields as $field_key => $field_label) {
                    // Get field options to access sub-fields
                    $field_options = get_field_options($field_key);
                    $sub_field_labels = array();
                    $sub_fields = array();
                    if (!empty($field_options) && isset($field_options['type']) && $field_options['type'] === 'repeating_field') {
                        if (isset($field_options['sub_fields']) && !empty($field_options['sub_fields'])) {
                            $sub_field_names = explode(',', $field_options['sub_fields']);
                            foreach ($sub_field_names as $sub_field_name) {
                                $sub_field_name = trim($sub_field_name);
                                if (empty($sub_field_name)) {
                                    continue;
                                }
                                // Get sub-field options
                                $sub_field_options = get_field_options($sub_field_name);
                                if (!empty($sub_field_options)) {
                                    $sub_field_label = isset($sub_field_options['label']) ? $sub_field_options['label'] : $sub_field_name;
                                    $sub_field_labels[$sub_field_name] = $sub_field_label;
                                } else {
                                    $sub_field_labels[$sub_field_name] = $sub_field_name;
                                }
                            }
                        }
                    }
                    // Each repeater field option is the field_label, sub-fields are stored as value for parent key
                    $repeater_field_options[$field_key] = $field_label;
                    $repeater_field_sub_fields[$field_key] = $sub_field_labels;
                }
            }
        }


        // Repeater Field Selector
        $this->add_control(
            'repeater_field',
            [
                'label' => __('Repeater Field', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $repeater_field_options,
                'default' => '',
                'condition' => [
                    'post_type!' => '',
                ],
            ]
        );

        // Title Field Selector (for tabs/accordion titles) - will be populated dynamically
        foreach ($repeater_field_sub_fields as $field_key => $field_sub_fields) {
            $this->add_control(
                'title_field_' . $field_key,
                [
                    'label' => __('Title Field (for Tabs/Accordion)', 'valuepack-addons'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $field_sub_fields,
                    'default' => '',
                    'description' => __('Select which sub-field should be used as the title for tabs or accordion items. All other sub-fields will display inside.', 'valuepack-addons'),
                    'condition' => [
                        'post_type!' => '',
                        'repeater_field' => $field_key,
                    ],
                ]
            );
        }
        $this->end_controls_section();
        // Static Prefix Section (Content)
        $this->start_controls_section(
            'static_prefix_section',
            [
                'label' => __('Static Prefix', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'display_style' => 'static',
                ],
            ]
        );

        $this->add_control(
            'static_title_prefix',
            [
                'label' => __('Title Prefix', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('e.g. • or Title: ', 'valuepack-addons'),
                'description' => __('Text or character shown before each item title.', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'static_content_prefix',
            [
                'label' => __('Content Prefix', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('e.g. — or Details: ', 'valuepack-addons'),
                'description' => __('Text or character shown before each item content.', 'valuepack-addons'),
            ]
        );

        $this->end_controls_section();
        // Icon Section for Accordion
        $this->start_controls_section(
            'accordion_icon_section',
            [
                'label' => __('Accordion Icons', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'display_style' => 'accordion',
                ],
            ]
        );

        // Accordion Arrow Icon (Expand/Collapse)
        $this->add_control(
            'accordion_arrow_icon',
            [
                'label' => __('Accordion Arrow Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-down',
                    'library' => 'fa-solid',
                ],
                'description' => __('Icon shown when accordion is collapsed (closed).', 'valuepack-addons'),
            ]
        );

        // Accordion Arrow Icon Active (Expanded)
        $this->add_control(
            'accordion_arrow_icon_active',
            [
                'label' => __('Accordion Arrow Icon (Active)', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-up',
                    'library' => 'fa-solid',
                ],
                'description' => __('Icon shown when accordion is expanded (open).', 'valuepack-addons'),
            ]
        );

        // Extra Icon Switcher
        $this->add_control(
            'show_extra_icon',
            [
                'label' => __('Show Extra Icon', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Enable to add an extra icon in the accordion header.', 'valuepack-addons'),
            ]
        );

        // Extra Icon
        $this->add_control(
            'accordion_extra_icon',
            [
                'label' => __('Extra Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_extra_icon' => 'yes',
                ],
                'description' => __('Additional icon displayed in the accordion header.', 'valuepack-addons'),
            ]
        );

        $this->end_controls_section();

        // Icon Section for Tabber
        $this->start_controls_section(
            'tabber_icon_section',
            [
                'label' => __('Tabber Icons', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'display_style' => 'tabber',
                ],
            ]
        );

        // Tabber Icon (Normal State)
        $this->add_control(
            'tabber_icon',
            [
                'label' => __('Tab Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'description' => __('Icon shown when tab is not active.', 'valuepack-addons'),
            ]
        );

        // Tabber Icon Active
        $this->add_control(
            'tabber_icon_active',
            [
                'label' => __('Tab Icon (Active)', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'description' => __('Icon shown when tab is active.', 'valuepack-addons'),
            ]
        );

        $this->end_controls_section();

        // Style Section for Tabber
        $this->start_controls_section(
            'tabber_style_section',
            [
                'label' => __('Tabber Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'tabber',
                ],
            ]
        );

        $this->add_control(
            'tabber_title_color',
            [
                'label' => __('Tab Title Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_active_title_color',
            [
                'label' => __('Active Tab Title Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_tab_bg',
            [
                'label' => __('Tab Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_active_tab_bg',
            [
                'label' => __('Active Tab Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabber_tab_padding',
            [
                'label' => __('Tab Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_content_bg',
            [
                'label' => __('Content Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Gap and Justify Content for the tab list as a dropdown
        $this->add_responsive_control(
            'tabber_tabs_justify',
            [
                'label' => __('Tabs Justify Content', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Left', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Right', 'valuepack-addons'),
                    'space-between' => __('Space Between', 'valuepack-addons'),
                    'space-around' => __('Space Around', 'valuepack-addons'),
                    'space-evenly' => __('Space Evenly', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} #cubewpRepeaterTabs' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'display_style' => 'tabber',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabber_tabs_gap',
            [
                'label' => __('Tabs Gap', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 80],
                    'em' => ['min' => 0, 'max' => 5],
                    'rem' => ['min' => 0, 'max' => 5],
                ],
                'selectors' => [
                    '{{WRAPPER}} #cubewpRepeaterTabs' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'display_style' => 'tabber',
                ],
            ]
        );

        // Container Display Type (Block/Flex)
        $this->add_control(
            'tabber_container_display',
            [
                'label' => __('Container Display', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'block' => __('Block', 'valuepack-addons'),
                    'flex' => __('Flex', 'valuepack-addons'),
                ],
                'default' => 'block',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber' => 'display: {{VALUE}};',
                ],
            ]
        );

        // Flex Direction (only shown when display is flex)
        $this->add_responsive_control(
            'tabber_container_flex_direction',
            [
                'label' => __('Flex Direction (For Main Container)', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber' => 'flex-direction: {{VALUE}};',
                ],
                'default' => 'row',
                'condition' => [
                    'tabber_container_display' => 'flex',
                ],
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control(
            'tabber_container_items_flex_direction',
            [
                'label' => __('Flex Direction (For Items Container)', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} #cubewpRepeaterTabs' => 'flex-direction: {{VALUE}};',
                ],
                'default' => 'column',
                'condition' => [
                    'tabber_container_display' => 'flex',
                ],
                'separator' => 'before'
            ]
        );

        // Align Items (only shown when display is flex)
        $this->add_responsive_control(
            'tabber_container_align_items',
            [
                'label' => __('Align Items', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'stretch' => __('Stretch', 'valuepack-addons'),
                    'flex-start' => __('Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('End', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber' => 'align-items: {{VALUE}};',
                ],
                'default' => 'stretch',
                'condition' => [
                    'tabber_container_display' => 'flex',
                    'tabber_container_items_flex_direction' => ['column', 'column-reverse'],
                ],
            ]
        );


        // Gap (only shown when display is flex)
        $this->add_responsive_control(
            'tabber_container_gap',
            [
                'label' => __('Gap', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 80],
                    'em' => ['min' => 0, 'max' => 5],
                    'rem' => ['min' => 0, 'max' => 5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tabber_container_display' => 'flex',
                ],
            ]
        );

        // Padding for tab content
        $this->add_responsive_control(
            'tabber_tabs_width',
            [
                'label' => __('Tabs Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'px'  => ['min' => 0, 'max' => 1600],
                    '%'   => ['min' => 0, 'max' => 100],
                    'em'  => ['min' => 0, 'max' => 80],
                    'rem' => ['min' => 0, 'max' => 80],
                ],
                'selectors' => [
                    '{{WRAPPER}} #cubewpRepeaterTabs' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabber_content_padding',
            [
                'label' => __('Content Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Section for Tabber Button (Tab Header)
        $this->start_controls_section(
            'tabber_button_style_section',
            [
                'label' => __('Tabber Button', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'tabber',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabber_button_align',
            [
                'label' => __('Alignment', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Add width options for .nav-item
        $this->add_responsive_control(
            'tabber_nav_item_width',
            [
                'label' => __('Tab Width', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'px'  => ['min' => 0, 'max' => 1000],
                    '%'   => ['min' => 0, 'max' => 100],
                    'em'  => ['min' => 0, 'max' => 50],
                    'rem' => ['min' => 0, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-item' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-item .nav-link' => 'width: 100%;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tabber_button_typography',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .nav-link',
            ]
        );

        $this->start_controls_tabs('tabber_button_tabs');

        // Normal State
        $this->start_controls_tab('tabber_button_normal', ['label' => __('Normal', 'valuepack-addons')]);

        $this->add_control('tabber_button_color', [
            'label' => __('Text Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .nav-link:not(.active)' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('tabber_button_bg_color', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .nav-link:not(.active)' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tabber_button_border',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:not(.active)',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabber_button_box_shadow',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:not(.active)',
            ]
        );

        $this->add_responsive_control(
            'tabber_button_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:not(.active)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab('tabber_button_hover', ['label' => __('Hover', 'valuepack-addons')]);

        $this->add_control('tabber_button_color_hover', [
            'label' => __('Text Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .nav-link:hover:not(.active)' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('tabber_button_bg_color_hover', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .nav-link:hover:not(.active)' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tabber_button_border_hover',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:hover:not(.active)',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabber_button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:hover:not(.active)',
            ]
        );

        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab('tabber_button_active', ['label' => __('Active', 'valuepack-addons')]);

        $this->add_control('tabber_button_color_active', [
            'label' => __('Text Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('tabber_button_bg_color_active', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tabber_button_border_active',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabber_button_box_shadow_active',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active',
            ]
        );

        $this->add_responsive_control(
            'tabber_button_border_radius_active',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tabber_button_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        // Section for Tabber Icon Styles
        $this->start_controls_section(
            'tabber_icon_style_section',
            [
                'label' => __('Tabber Icon', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'tabber',
                ],
            ]
        );

        // Icon Position
        $this->add_control(
            'tabber_icon_position',
            [
                'label' => __('Icon Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => __('Left', 'valuepack-addons'),
                    'right' => __('Right', 'valuepack-addons'),
                ],
                'default' => 'left',
            ]
        );

        // Icon Size
        $this->add_responsive_control(
            'tabber_icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Icon Spacing
        $this->add_responsive_control(
            'tabber_icon_spacing',
            [
                'label' => __('Icon Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-icon.tab-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-icon.tab-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Icon Color Tabs
        $this->start_controls_tabs('tabber_icon_tabs');

        // Normal State
        $this->start_controls_tab(
            'tabber_icon_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'tabber_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:not(.active) .tab-icon-normal' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:not(.active) .tab-icon-normal svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'tabber_icon_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'tabber_icon_color_hover',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:hover:not(.active) .tab-icon-normal' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link:hover:not(.active) .tab-icon-normal svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab(
            'tabber_icon_actives',
            [
                'label' => __('Active', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'tabber_icon_color_active',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active .tab-icon-active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cubewp-repeater-tabber .nav-link.active .tab-icon-active svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Section for Tabber Body
        $this->start_controls_section(
            'tabber_body_style_section',
            [
                'label' => __('Tabber Body', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'tabber',
                ],
            ]
        );

        $this->add_control('tabber_body_bg_color', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .tab-content' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control(
            'tabber_body_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => ['{{WRAPPER}} .cubewp-repeater-tabber .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tabber_body_border',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .tab-content',
            ]
        );

        $this->add_responsive_control(
            'tabber_body_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabber_body_box_shadow',
                'selector' => '{{WRAPPER}} .cubewp-repeater-tabber .tab-content',
            ]
        );

        $this->end_controls_section();

        // Style Section for Repeater Fields (Common for all display styles)
        $this->start_controls_section(
            'repeater_fields_style_section',
            [
                'label' => __('Repeater Fields Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Gap between fields
        $this->add_responsive_control(
            'field_gap',
            [
                'label' => __('Gap Between Fields', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-accordion .accordion-body .cwp-cpt-single-field-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cubewp-repeater-tabber .tab-content .cwp-cpt-single-field-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cubewp-repeater-static .cubewp-repeater-item .cwp-cpt-single-field-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Field Container Styles
        $this->add_control(
            'field_container_heading',
            [
                'label' => __('Field Container', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => __('Field Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-field-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_margin',
            [
                'label' => __('Field Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-field-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label' => __('Field Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-field-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'label' => __('Field Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cwp-cpt-single-field-container',
            ]
        );

        $this->add_responsive_control(
            'field_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-field-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_box_shadow',
                'label' => __('Field Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cwp-cpt-single-field-container',
            ]
        );

        // Label Styles
        $this->add_control(
            'field_label_heading',
            [
                'label' => __('Field Label', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'field_label_typography',
                'label' => __('Label Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cwp-cpt-single-field-container h4',
            ]
        );

        $this->add_control(
            'field_label_color',
            [
                'label' => __('Label Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-field-container h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_label_margin',
            [
                'label' => __('Label Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-field-container h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Value Styles
        $this->add_control(
            'field_value_heading',
            [
                'label' => __('Field Value', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'field_value_typography',
                'label' => __('Value Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-text, {{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-number, {{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-textarea, {{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-textarea p, {{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-switch p, {{WRAPPER}} .cwp-cpt-single-field-container ul li, {{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-email, {{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-url, {{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-file',
            ]
        );

        $this->add_control(
            'field_value_color',
            [
                'label' => __('Value Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-number' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-textarea' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-textarea p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-switch p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container ul li' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-email a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-url a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-file a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-date_picker p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-time_picker p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-email a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-url a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-file a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-date_picker p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cwp-cpt-single-field-container .cwp-cpt-single-time_picker p' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Image/Gallery Styles
        $this->add_control(
            'field_image_heading',
            [
                'label' => __('Image & Gallery', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'field_image_width',
            [
                'label' => __('Image Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-image img' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
                    '{{WRAPPER}} .cwp-cpt-single-gallery img' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_image_height',
            [
                'label' => __('Image Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                    '{{WRAPPER}} .cwp-cpt-single-gallery img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_gallery_gap',
            [
                'label' => __('Gallery Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-gallery .cwp-cpt-single-gallery-item' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Map Styles
        $this->add_control(
            'field_map_heading',
            [
                'label' => __('Google Map', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'field_map_width',
            [
                'label' => __('Map Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cpt-single-map' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_map_height',
            [
                'label' => __('Map Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cpt-single-map' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'static_title_style_section',
            [
                'label' => __('Static Title Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'static',
                ],
            ]
        );
        
        // Typography control for .cubewp-repeater-field-title
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'static_field_title_typography',
                'label'    => __('Title Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-repeater-field-title',
            ]
        );

        // Color control for .cubewp-repeater-field-title
        $this->add_control(
            'static_field_title_color',
            [
                'label'     => __('Title Color', 'valuepack-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-field-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Margin control for .cubewp-repeater-field-title
        $this->add_responsive_control(
            'static_field_title_margin',
            [
                'label'      => __('Title Margin', 'valuepack-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .cubewp-repeater-field-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Display toggle control (hide/show)
        $this->add_control(
            'static_field_title_display',
            [
                'label'        => __('Show Title', 'valuepack-addons'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'block' => __('Show', 'valuepack-addons'),
                    'none' => __('Hide', 'valuepack-addons'),
                ],
                'default'      => 'block',
                'selectors'    => [
                    '{{WRAPPER}} .cubewp-repeater-field-title' => 'display: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        // Static Prefix Style Section
        $this->start_controls_section(
            'static_prefix_style_section',
            [
                'label' => __('Static Prefix Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'static',
                ],
            ]
        );

        $this->add_control(
            'static_title_prefix_heading',
            [
                'label' => __('Title Prefix', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'static_title_prefix_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-repeater-title-prefix',
            ]
        );

        $this->add_control(
            'static_title_prefix_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'static_title_prefix_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-title-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'static_content_prefix_heading',
            [
                'label' => __('Content Prefix', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'static_content_prefix_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-repeater-content-prefix',
            ]
        );

        $this->add_control(
            'static_content_prefix_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-content-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'static_content_prefix_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-content-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'static_style_section',
            [
                'label' => __('Static Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'static',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'static_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-repeater-static,{{WRAPPER}} .cubewp-repeater-item-title,{{WRAPPER}} .cubewp-repeater-title-value',
            ]
        );

        $this->add_control(
            'static_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-static,{{WRAPPER}} .cubewp-repeater-item-title,{{WRAPPER}} .cubewp-repeater-title-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'static_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-repeater-static,{{WRAPPER}} .cubewp-repeater-item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_accordion_style_controls();
    }

    private function register_accordion_style_controls()
    {
        // Section for Main Accordion Item (The Container)
        $this->start_controls_section(
            'accordion_item_style_section',
            [
                'label' => __('Accordion Item Container', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'accordion',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .accordion-item',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .accordion-item',
            ]
        );

        $this->end_controls_section();

        // Section for Accordion Header (Title)
        $this->start_controls_section(
            'accordion_header_style_section',
            [
                'label' => __('Accordion Header', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'accordion',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_align',
            [
                'label' => __('Alignment', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-button' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'selector' => '{{WRAPPER}} .accordion-header, {{WRAPPER}} .accordion-button',
            ]
        );


        $this->start_controls_tabs('header_tabs');

        // Normal State
        $this->start_controls_tab('header_normal', ['label' => __('Normal', 'valuepack-addons')]);

        $this->add_control('header_color', [
            'label' => __('Text Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .accordion-button' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('header_bg_color', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .accordion-button' => 'background-color: {{VALUE}};'],
        ]);

        // Border control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'header_border',
                'selector' => '{{WRAPPER}} .accordion-button',
            ]
        );

        // Box Shadow control
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'header_box_shadow',
                'selector' => '{{WRAPPER}} .accordion-button',
            ]
        );

        // Border Radius control
        $this->add_responsive_control(
            'header_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab('header_hover', ['label' => __('Hover', 'valuepack-addons')]);

        $this->add_control('header_color_hover', [
            'label' => __('Text Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .accordion-button:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('header_bg_color_hover', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .accordion-button:hover' => 'background-color: {{VALUE}};'],
        ]);
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'header_border_hover',
                'selector' => '{{WRAPPER}} .accordion-button:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'header_box_shadow_hover',
                'selector' => '{{WRAPPER}} .accordion-button:hover',
            ]
        );

        // Border Radius control
        $this->add_responsive_control(
            'header_border_radius_hover',
            [
                'label' => __('Hover Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab('header_active', ['label' => __('Active', 'valuepack-addons')]);

        $this->add_control('header_color_active', [
            'label' => __('Text Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .accordion-button:not(.collapsed)' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('header_bg_color_active', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .accordion-button:not(.collapsed)' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'header_border_active',
                'selector' => '{{WRAPPER}} .accordion-button:not(.collapsed)',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'header_box_shadow_active',
                'selector' => '{{WRAPPER}} .accordion-button:not(.collapsed)',
            ]
        );
        $this->add_responsive_control(
            'header_border_radius_active',
            [
                'label' => __('Active Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-button:not(.collapsed)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Padding/Border for Header
        $this->add_responsive_control(
            'header_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => ['{{WRAPPER}} .accordion-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();


        // Section for Accordion Body
        $this->start_controls_section(
            'accordion_body_style_section',
            [
                'label' => __('Accordion Body', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'accordion',
                ],
            ]
        );

        $this->add_control('body_bg_color', [
            'label' => __('Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .accordion-body' => 'background-color: {{VALUE}};'],
        ]);
        $this->add_responsive_control(
            'body_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => ['{{WRAPPER}} .accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_responsive_control(
            'body_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => ['{{WRAPPER}} .accordion-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'body_border',
                'selector' => '{{WRAPPER}} .accordion-body',
            ]
        );
        $this->add_responsive_control(
            'body_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .accordion-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Section for Accordion Arrow Icon Styles
        $this->start_controls_section(
            'accordion_arrow_icon_style_section',
            [
                'label' => __('Accordion Arrow Icon', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'accordion',
                ],
            ]
        );


        // Absolute positioning controls for arrow icon
        $this->add_responsive_control(
            'accordion_arrow_icon_top',
            [
                'label' => __('Icon Top', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 200],
                    '%'  => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0,  'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-arrow-icon' => 'position: absolute; top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_arrow_icon_left',
            [
                'label' => __('Icon Left', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 200],
                    '%'  => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0,  'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-arrow-icon' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_arrow_icon_right',
            [
                'label' => __('Icon Right', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 200],
                    '%'  => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0,  'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-arrow-icon' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Icon Size
        $this->add_responsive_control(
            'accordion_arrow_icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-arrow-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .accordion-arrow-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        // Icon Color Tabs
        $this->start_controls_tabs('accordion_arrow_icon_tabs');

        // Normal State
        $this->start_controls_tab(
            'accordion_arrow_icon_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'accordion_arrow_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion-button.collapsed .accordion-arrow-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .accordion-button.collapsed .accordion-arrow-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'accordion_arrow_icon_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'accordion_arrow_icon_color_hover',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion-button:hover .accordion-arrow-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .accordion-button:hover .accordion-arrow-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab(
            'accordion_arrow_icon_actives',
            [
                'label' => __('Active', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'accordion_arrow_icon_color_active',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion-button:not(.collapsed) .accordion-arrow-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .accordion-button:not(.collapsed) .accordion-arrow-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Section for Accordion Extra Icon Styles
        $this->start_controls_section(
            'accordion_extra_icon_style_section',
            [
                'label' => __('Accordion Extra Icon', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style' => 'accordion',
                    'show_extra_icon' => 'yes',
                ],
            ]
        );

        // Extra Icon Position
        // Use positioning controls (top, left, right, bottom) for extra icon
        $this->add_responsive_control(
            'accordion_extra_icon_top',
            [
                'label' => __('Icon Top', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 200],
                    '%'  => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0,  'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-extra-icon' => 'top: {{SIZE}}{{UNIT}}; position: absolute;',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_extra_icon_left',
            [
                'label' => __('Icon Left', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 200],
                    '%'  => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0,  'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-extra-icon' => 'left: {{SIZE}}{{UNIT}}; position: absolute;',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_extra_icon_right',
            [
                'label' => __('Icon Right', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 200],
                    '%'  => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0,  'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-extra-icon' => 'right: {{SIZE}}{{UNIT}}; position: absolute;',
                ],
            ]
        );
        $this->add_responsive_control(
            'accordion_extra_icon_bottom',
            [
                'label' => __('Icon Bottom', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 200],
                    '%'  => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0,  'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-extra-icon' => 'bottom: {{SIZE}}{{UNIT}}; position: absolute;',
                ],
            ]
        );

        // Extra Icon Size
        $this->add_responsive_control(
            'accordion_extra_icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-extra-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .accordion-extra-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Extra Icon Spacing
        $this->add_responsive_control(
            'accordion_extra_icon_spacing',
            [
                'label' => __('Icon Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion-extra-icon.accordion-extra-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .accordion-extra-icon.accordion-extra-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Extra Icon Color Tabs
        $this->start_controls_tabs('accordion_extra_icon_tabs');

        // Normal State
        $this->start_controls_tab(
            'accordion_extra_icon_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'accordion_extra_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion-button .accordion-extra-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .accordion-button .accordion-extra-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'accordion_extra_icon_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'accordion_extra_icon_color_hover',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion-button:hover .accordion-extra-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .accordion-button:hover .accordion-extra-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active State
        $this->start_controls_tab(
            'accordion_extra_icon_active',
            [
                'label' => __('Active', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'accordion_extra_icon_color_active',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion-button:not(.collapsed) .accordion-extra-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .accordion-button:not(.collapsed) .accordion-extra-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();


        // Enqueue Cubewp required scripts and styles for single page rendering
        if (class_exists('CubeWp_Enqueue')) {
            // Enqueue scripts
            if (class_exists('CubeWp_Enqueue')) {
                CubeWp_Enqueue::enqueue_script('cubewp-pretty-photo');
                CubeWp_Enqueue::enqueue_script('cubewp-leaflet');
                CubeWp_Enqueue::enqueue_script('cubewp-leaflet-cluster');
                CubeWp_Enqueue::enqueue_script('cubewp-map');
            }
            // Enqueue styles
            if (class_exists('CubeWp_Enqueue')) {
                CubeWp_Enqueue::enqueue_style('cubewp-pretty-photo');
                CubeWp_Enqueue::enqueue_style('cwp-map-cluster');
                CubeWp_Enqueue::enqueue_style('cwp-leaflet-css');
            }
        }

        // Add inline CSS for icon visibility and layout
        echo '<style>
            .elementor-element-' . esc_attr($this->get_id()) . ' .accordion-button::after {
                display: none;
            }
            .elementor-element-' . esc_attr($this->get_id()) . ' .accordion-button.collapsed .accordion-arrow-icon .accordion-arrow-icon-active {
                display: none !important;
            }
            .elementor-element-' . esc_attr($this->get_id()) . ' .accordion-button.collapsed .accordion-arrow-icon .accordion-arrow-icon-normal {
                display: block !important;
            }
            .elementor-element-' . esc_attr($this->get_id()) . ' .accordion-button:not(.collapsed) .accordion-arrow-icon .accordion-arrow-icon-normal {
                display: none !important;
            }
            .elementor-element-' . esc_attr($this->get_id()) . ' .accordion-button:not(.collapsed) .accordion-arrow-icon .accordion-arrow-icon-active {
                display: block !important;
            }
            .elementor-element-' . esc_attr($this->get_id()) . ' .cubewp-repeater-tabber .nav-link:not(.active) .tab-icon-active { display: none !important; }
            .elementor-element-' . esc_attr($this->get_id()) . ' .cubewp-repeater-tabber .nav-link.active .tab-icon-normal { display: none !important; }
        </style>';




        $post_type = $settings['post_type'];
        $display_style = $settings['display_style'];
        $repeater_field = $settings['repeater_field'];


        $title_field = '';
        if (!empty($repeater_field) && isset($settings['title_field_' . $repeater_field])) {
            $title_field = $settings['title_field_' . $repeater_field];
        }


        if (empty($post_type) || empty($repeater_field)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . esc_html__('Please select a post type and repeater field.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        // Get current post ID
        $post_id = get_the_ID();
        if(cubewp_is_elementor_editing()){
            $post_id = cubewp_get_elementor_preview_post_id();
        }

        // In Elementor edit mode, get first post of selected post type for preview
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            if (empty($post_id) || get_post_type($post_id) !== $post_type) {
                $first_post = get_posts(array(
                    'post_type' => $post_type,
                    'posts_per_page' => 1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                if (!empty($first_post)) {
                    $post_id = $first_post[0]->ID;
                }
            }
        }

        if (empty($post_id)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__('No posts found for the selected post type.', 'valuepack-addons') . '</div>';
            }
            return;
        }


        // Get field value using Cubewp function (same as Cubewp tag)
        $field_options = function_exists('get_field_options') ? get_field_options($repeater_field) : array();
        if (empty($field_options) || !isset($field_options['type']) || $field_options['type'] !== 'repeating_field') {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__('Selected field is not a repeater field.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        // // Set post ID for Cubewp (required for proper field rendering)
        if (class_exists('CubeWp_Single_Cpt')) {
            CubeWp_Single_Cpt::$post_id = $post_id;
        }

        // Use Cubewp's get_field_value function (same as class-cubewp-tag-repeating-field.php)
        // get_field_value($field, $render = false, $postID = 0)
        $field_value = get_post_meta($post_id, $repeater_field, true);

        if (empty($field_value) || !is_array($field_value)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . esc_html__('No repeater data found for the current post.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        // Get sub-fields
        $sub_fields = array();
        if (isset($field_options['sub_fields']) && !empty($field_options['sub_fields'])) {
            $sub_fields = explode(',', $field_options['sub_fields']);
        }

        // Process repeater data - filter to only numeric indices to avoid Cubewp warnings
        $processed_data = $this->process_repeater_data($field_value, $sub_fields, $title_field, $settings);

        if (empty($processed_data)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . esc_html__('No repeater data to display.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        $repeater_data = array(
            'field_name' => $repeater_field,
            'field_label' => isset($field_options['label']) ? $field_options['label'] : $repeater_field,
            'title_field' => $title_field,
            'data' => $processed_data,
        );

        // Render based on display style
        switch ($display_style) {
            case 'accordion':
                $this->render_accordion($repeater_data);
                break;
            case 'tabber':
                $this->render_tabber($repeater_data);
                break;
            case 'static':
            default:
                $this->render_static_style($repeater_data);
                break;
        }
    }

    /**
     * Process repeater data - same way as Cubewp field_repeating_field
     */
    private function process_repeater_data($values, $sub_fields, $title_field = '', $settings = array())
    {
        $processed = array();
        if (!is_array($values) || empty($values)) {
            return $processed;
        }
        $filtered_values = array();
        foreach ($values as $key => $value) {
            $is_numeric = is_numeric($key);

            if ($is_numeric && is_string($key)) {
                // Skip if numeric string is longer than 3 digits
                if (strlen($key) > 3) {
                    continue;
                }
            }

            // Process if numeric (int or short numeric string) and value is array
            if ($is_numeric && is_array($value) && !empty($value)) {
                $filtered_values[] = $value; // Use [] to re-index sequentially (0, 1, 2, ...)
            }
        }

        if (empty($filtered_values)) {
            return $processed;
        }

        // Check if data is in Cubewp format (with 'type' and 'value' keys)
        $is_cubewp_format = false;
        if (!empty($filtered_values[0]) && is_array($filtered_values[0])) {
            $first_row = $filtered_values[0];
            $first_field = reset($first_row);
            if (is_array($first_field) && isset($first_field['type']) && isset($first_field['value'])) {
                $is_cubewp_format = true;
            }
        }

        foreach ($filtered_values as $row_index => $row) {
            if (!is_array($row) || empty($row)) {
                continue;
            }

            $row_data = array(
                'title' => '',
                'title_value' => '',
                'content_fields' => array(),
            );

            foreach ($row as $field_key => $field_data) {
                // Skip lat/lng for google_address
                if (strpos($field_key, '_lat') !== false || strpos($field_key, '_lng') !== false) {
                    continue;
                }

                // Skip if field_data is null
                if ($field_data === null) {
                    continue;
                }

                // Get field options
                $field_options = function_exists('get_field_options') ? get_field_options($field_key) : array();
                if (!is_array($field_options)) {
                    $field_options = array();
                }
                $field_label = isset($field_options['label']) ? $field_options['label'] : $field_key;
                $field_type = isset($field_options['type']) ? $field_options['type'] : 'text';

                // Process based on data format
                if ($is_cubewp_format) {
                    // Cubewp format: $field_data = array('type' => 'text', 'value' => '...', 'label' => '...')
                    if (is_array($field_data)) {
                        $field_type = isset($field_data['type']) ? $field_data['type'] : $field_type;
                        $field_value = isset($field_data['value']) ? $field_data['value'] : '';
                        $field_label = isset($field_data['label']) ? $field_data['label'] : $field_label;
                    } else {
                        $field_value = $field_data;
                    }

                    // Merge with field options
                    $render_args = $field_options;
                    $render_args['value'] = $field_value;
                    $render_args['label'] = $field_label;
                    $render_args['name'] = $field_key;
                    $render_args['class'] = isset($render_args['class']) ? $render_args['class'] : '';
                    $render_args['container_class'] = isset($render_args['container_class']) ? $render_args['container_class'] : '';
                    $render_args = apply_filters('cubewp/custom/cube/field/options', $render_args);

                    // Use Cubewp's field rendering method (same as field_repeating_field)
                    $field_html = '';
                    // CubeWp_Single_Cpt uses CubeWp_Single_Page_Trait which has all field_* methods
                    if (class_exists('CubeWp_Single_Cpt') && method_exists('CubeWp_Single_Cpt', 'field_' . $field_type)) {
                        $field_html = call_user_func(array('CubeWp_Single_Cpt', 'field_' . $field_type), $render_args);
                    }

                    // Fallback if Cubewp method not available
                    if (empty($field_html)) {
                        $processed_value = is_array($field_value) ? implode(', ', array_map('esc_html', $field_value)) : wp_kses_post($field_value);
                        $field_html = '<div class="cubewp-repeater-field"><strong>' . esc_html($field_label) . ':</strong> ' . $processed_value . '</div>';
                    }

                    $processed_value = $field_value;
                } else {
                    // Simple format: $field_data = 'value' (direct value)
                    $field_value = $field_data;
                    $processed_value = is_array($field_value) ? implode(', ', array_map('esc_html', $field_value)) : wp_kses_post($field_value);

                    // Try to use Cubewp rendering if field type is known
                    $field_html = '';
                    if (!empty($field_options) && isset($field_options['type'])) {
                        $render_args = $field_options;
                        $render_args['value'] = $field_value;
                        $render_args['label'] = $field_label;
                        $render_args['name'] = $field_key;
                        $render_args['class'] = isset($render_args['class']) ? $render_args['class'] : '';
                        $render_args['container_class'] = isset($render_args['container_class']) ? $render_args['container_class'] : '';
                        $render_args = apply_filters('cubewp/custom/cube/field/options', $render_args);

                        // Use Cubewp's field rendering method
                        if (class_exists('CubeWp_Single_Cpt') && method_exists('CubeWp_Single_Cpt', 'field_' . $field_type)) {
                            $field_html = call_user_func(array('CubeWp_Single_Cpt', 'field_' . $field_type), $render_args);
                        }
                    }

                    // Fallback to simple HTML
                    if (empty($field_html)) {
                        $field_html = '<div class="cubewp-repeater-field"><strong>' . esc_html($field_label) . ':</strong> ' . $processed_value . '</div>';
                    }
                }

                // Check if this is the title field
                if (!empty($title_field) && $field_key === $title_field) {
                    $row_data['title'] = $field_label;
                    if (empty($row_data['title_value'])) {
                        $row_data['title_value'] = is_array($processed_value) ? implode(', ', array_map('esc_html', $processed_value)) : esc_html($processed_value);
                    }
                } else {
                    // Add to content fields
                    $row_data['content_fields'][] = array(
                        'field_name' => $field_key,
                        'field_label' => $field_label,
                        'field_type' => $field_type,
                        'value' => $processed_value,
                        'processed_value' => $processed_value,
                        'html' => $field_html,
                    );
                }
            }

            // If no title field selected, use first field or index
            if (empty($row_data['title']) && !empty($row_data['content_fields'])) {
                $first_field = $row_data['content_fields'][0];
                $row_data['title'] = $first_field['field_label'];
                $row_data['title_value'] = wp_strip_all_tags($first_field['html']);
                if (empty($row_data['title_value'])) {
                    $row_data['title_value'] = is_array($first_field['processed_value']) ? implode(', ', array_map('esc_html', $first_field['processed_value'])) : esc_html($first_field['processed_value']);
                }
            }

            if (!empty($row_data['content_fields']) || !empty($row_data['title'])) {
                $processed[] = $row_data;
            }
        }

        return $processed;
    }

    /**
     * Render accordion style
     */
    protected function render_accordion($repeater_data)
    {
        $settings = $this->get_settings_for_display();
        $accordion_arrow_icon = isset($settings['accordion_arrow_icon']) ? $settings['accordion_arrow_icon'] : '';
        $accordion_arrow_icon_active = isset($settings['accordion_arrow_icon_active']) ? $settings['accordion_arrow_icon_active'] : '';
        $show_extra_icon = isset($settings['show_extra_icon']) && $settings['show_extra_icon'] === 'yes';
        $accordion_extra_icon = isset($settings['accordion_extra_icon']) ? $settings['accordion_extra_icon'] : '';
        $arrow_icon_position = isset($settings['accordion_arrow_icon_position']) ? $settings['accordion_arrow_icon_position'] : 'right';
        $extra_icon_position = isset($settings['accordion_extra_icon_position']) ? $settings['accordion_extra_icon_position'] : 'left';

        echo '<div class="cubewp-repeater-accordion">';
        echo '<div class="accordion" id="cubewpRepeaterAccordion">';

        $items = $repeater_data['data'];
        foreach ($items as $item_index => $item) {
            $unique_id = 'accordion-' . $item_index;
            $is_first = ($item_index === 0);
            $title = !empty($item['title_value']) ? $item['title_value'] : ('Item #' . ($item_index + 1));

            echo '<div class="accordion-item" style="overflow: hidden;">';
            echo '<h2 class="accordion-header" id="heading' . esc_attr($unique_id) . '">';
            echo '<button class="accordion-button' . ($is_first ? '' : ' collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . esc_attr($unique_id) . '" aria-expanded="' . ($is_first ? 'true' : 'false') . '" aria-controls="collapse' . esc_attr($unique_id) . '">';

            // Render extra icon (left side)
            if ($show_extra_icon && !empty($accordion_extra_icon) && $extra_icon_position === 'left') {
                echo '<span class="accordion-extra-icon accordion-extra-icon-left">';
                Icons_Manager::render_icon($accordion_extra_icon, ['aria-hidden' => 'true']);
                echo '</span>';
            }

            // Render arrow icon (left side if position is left)
            if (!empty($accordion_arrow_icon) && $arrow_icon_position === 'left') {
                echo '<span class="accordion-arrow-icon accordion-arrow-icon-left">';
                // Render both icons, CSS will show/hide based on state
                if (!empty($accordion_arrow_icon)) {
                    echo '<span class="accordion-arrow-icon-normal">';
                    Icons_Manager::render_icon($accordion_arrow_icon, ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                if (!empty($accordion_arrow_icon_active)) {
                    echo '<span class="accordion-arrow-icon-active">';
                    Icons_Manager::render_icon($accordion_arrow_icon_active, ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                echo '</span>';
            }

            echo '<span class="accordion-title" style="flex: 1;">' . wp_kses_post($title) . '</span>';

            // Render extra icon (right side)
            if ($show_extra_icon && !empty($accordion_extra_icon) && $extra_icon_position === 'right') {
                echo '<span class="accordion-extra-icon accordion-extra-icon-right">';
                Icons_Manager::render_icon($accordion_extra_icon, ['aria-hidden' => 'true']);
                echo '</span>';
            }

            // Render arrow icon (right side if position is right)
            if (!empty($accordion_arrow_icon) && $arrow_icon_position === 'right') {
                echo '<span class="accordion-arrow-icon accordion-arrow-icon-right">';
                // Render both icons, CSS will show/hide based on state
                if (!empty($accordion_arrow_icon)) {
                    echo '<span class="accordion-arrow-icon-normal">';
                    Icons_Manager::render_icon($accordion_arrow_icon, ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                if (!empty($accordion_arrow_icon_active)) {
                    echo '<span class="accordion-arrow-icon-active">';
                    Icons_Manager::render_icon($accordion_arrow_icon_active, ['aria-hidden' => 'true']);
                    echo '</span>';
                }
                echo '</span>';
            }

            echo '</button>';
            echo '</h2>';
            echo '<div id="collapse' . esc_attr($unique_id) . '" class="accordion-collapse collapse' . ($is_first ? ' show' : '') . '" aria-labelledby="heading' . esc_attr($unique_id) . '">';
            echo '<div class="accordion-body">';

            foreach ($item['content_fields'] as $sub_field) {
                echo $sub_field['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }

    /**
     * Render tabber style
     */
    protected function render_tabber($repeater_data)
    {
        $settings = $this->get_settings_for_display();
        $tabber_icon = isset($settings['tabber_icon']) ? $settings['tabber_icon'] : '';
        $tabber_icon_active = isset($settings['tabber_icon_active']) ? $settings['tabber_icon_active'] : '';

        echo '<div class="cubewp-repeater-tabber">';
        echo '<ul class="nav nav-tabs" id="cubewpRepeaterTabs" role="tablist">';

        $items = $repeater_data['data'];


        // Generate tabs
        foreach ($items as $item_index => $item) {
            $unique_id = 'tab-' . $item_index;
            $is_first = ($item_index === 0);
            $title = !empty($item['title_value']) ? $item['title_value'] : ('Item #' . ($item_index + 1));

            echo '<li class="nav-item" role="presentation">';
            echo '<button class="nav-link' . ($is_first ? ' active' : '') . '" id="' . esc_attr($unique_id) . '-tab" data-bs-toggle="tab" data-bs-target="#' . esc_attr($unique_id) . '" type="button" role="tab" aria-controls="' . esc_attr($unique_id) . '" aria-selected="' . ($is_first ? 'true' : 'false') . '">';

            // Render icon (normal state - shown when not active)
            if (!empty($tabber_icon)) {
                echo '<span class="tab-icon tab-icon-normal">';
                Icons_Manager::render_icon($tabber_icon, ['aria-hidden' => 'true']);
                echo '</span>';
            }

            // Render icon (active state - shown when active)
            if (!empty($tabber_icon_active)) {
                echo '<span class="tab-icon tab-icon-active">';
                Icons_Manager::render_icon($tabber_icon_active, ['aria-hidden' => 'true']);
                echo '</span>';
            }

            echo '<span class="tab-title">' . wp_kses_post($title) . '</span>';
            echo '</button>';
            echo '</li>';
        }

        echo '</ul>';
        echo '<div class="tab-content" id="cubewpRepeaterTabsContent">';

        // Generate tab content
        foreach ($items as $item_index => $item) {
            $unique_id = 'tab-' . $item_index;
            $is_first = ($item_index === 0);

            echo '<div class="tab-pane fade' . ($is_first ? ' show active' : '') . '" id="' . esc_attr($unique_id) . '" role="tabpanel" aria-labelledby="' . esc_attr($unique_id) . '-tab">';

            foreach ($item['content_fields'] as $sub_field) {
                echo $sub_field['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }

    /**
     * Render static style
     */
    protected function render_static_style($repeater_data)
    {
        $settings = $this->get_settings_for_display();
        $title_prefix = isset($settings['static_title_prefix']) ? trim($settings['static_title_prefix']) : '';
        $content_prefix = isset($settings['static_content_prefix']) ? trim($settings['static_content_prefix']) : '';

        echo '<div class="cubewp-repeater-static">';

        $field_label = $repeater_data['field_label'];
        $items = $repeater_data['data'];

        echo '<div class="cubewp-repeater-field-group">';
        echo '<h3 class="cubewp-repeater-field-title">' . esc_html($field_label) . '</h3>';

        foreach ($items as $item) {
            echo '<div class="cubewp-repeater-item">';

            // Title with optional prefix
            if (!empty($item['title_value'])) { 
                echo '<div class="cubewp-repeater-item-title" style="display: flex;">' 
                    . ($title_prefix !== '' ? '<span class="cubewp-repeater-title-prefix">' . esc_html($title_prefix) . '</span>' : '') 
                    . '<h4 class="cubewp-repeater-title-value" style="margin: 0;">' . wp_kses_post($item['title_value']) . '</h4>' 
                    . '</div>';
            }
            // Content with optional prefix
           
            foreach ($item['content_fields'] as $sub_field) {
                echo '<div class="cubewp-repeater-item-content" style="display: flex;">';
                if ($content_prefix !== '') {
                    echo '<span class="cubewp-repeater-content-prefix">' . esc_html($content_prefix) . '</span>';
                }
                echo $sub_field['html']; 
                echo '</div>';
            }

            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }
}