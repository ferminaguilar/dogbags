<?php

/**
 * Cubewp Business Hours Widget
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

/**
 * Cubewp Business Hours Widget
 */
class Value_Pack_Cubewp_Business_Hours extends Value_Pack_Widget_Base
{
    /**
     * Convert mixed time string formats to seconds.
     * Supports: 24h (17:00, 17:00:00) and 12h (06:00 PM, 06:00:00 AM).
     */
    private function time_to_seconds($time)
    {
        if (empty($time) || !is_string($time)) {
            return null;
        }

        $raw = trim($time);
        if ($raw === '' || $raw === '24-hours-open') {
            return null;
        }

        if (preg_match('/^(\d{1,2}):(\d{2})(?::(\d{2}))?\s*([AaPp][Mm])?$/', $raw, $m)) {
            $hours = (int) $m[1];
            $mins = (int) $m[2];
            $secs = isset($m[3]) && $m[3] !== '' ? (int) $m[3] : 0;
            $ampm = isset($m[4]) ? strtolower($m[4]) : '';

            if ($ampm !== '') {
                if ($hours === 12) {
                    $hours = 0;
                }
                if ($ampm === 'pm') {
                    $hours += 12;
                }
            }

            return ($hours * 3600) + ($mins * 60) + $secs;
        }

        $timestamp = strtotime($raw);
        if ($timestamp === false) {
            return null;
        }

        return ((int) date('H', $timestamp) * 3600) + ((int) date('i', $timestamp) * 60) + (int) date('s', $timestamp);
    }

    /**
     * Format business hour time for display (always AM/PM).
     */
    private function format_business_time_display($time)
    {
        if (empty($time) || $time === '24-hours-open') {
            return '';
        }

        $timestamp = strtotime($time);
        if ($timestamp === false) {
            return '';
        }

        return date_i18n('h:i A', $timestamp);
    }

    /**
     * Get script dependencies
     */
    public function get_script_depends()
    {
        $scripts = parent::get_script_depends();
        // Add Cubewp scripts for business hours rendering
        if (class_exists('CubeWp_Enqueue')) {
            $scripts[] = 'cwp-business-hours-fields';
        }
        return $scripts;
    }

    /**
     * Get style dependencies
     */
    public function get_style_depends()
    {
        $styles = parent::get_style_depends();
        // Add Cubewp styles for business hours rendering
        if (class_exists('CubeWp_Enqueue')) {
            $styles[] = 'cwp-business-hours-fields';
        }
        return $styles;
    }

    public function get_name()
    {
        return 'vp_cubewp_business_hours';
    }

    public function get_title()
    {
        return __('Cubewp Business Hours', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-clock vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
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

        // Get all business hours fields for all post types
        $business_hours_field_options = array('' => __('Select Business Hours Field', 'valuepack-addons'));

        if (function_exists('get_fields_by_type')) {
            $all_business_hours_fields = get_fields_by_type(array('business_hours'));
            if (!empty($all_business_hours_fields) && is_array($all_business_hours_fields)) {
                foreach ($all_business_hours_fields as $field_key => $field_label) {
                    $business_hours_field_options[$field_key] = $field_label;
                }
            }
        }

        // Business Hours Field Selector
        $this->add_control(
            'business_hours_field',
            [
                'label' => __('Business Hours Field', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $business_hours_field_options,
                'default' => '',
                'condition' => [
                    'post_type!' => '',
                ],
            ]
        );


        // Open Now Text
        $this->add_control(
            'open_now_text',
            [
                'label' => __('Open Now Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Open Now', 'valuepack-addons'),
            ]
        );

        // Closed Now Text
        $this->add_control(
            'closed_now_text',
            [
                'label' => __('Closed Now Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Closed Now', 'valuepack-addons'),
            ]
        );

        // Day Off Text
        $this->add_control(
            'day_off_text',
            [
                'label' => __('Day Off Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Day Off', 'valuepack-addons'),
            ]
        );


        // Enable Today Option
        $this->add_control(
            'show_today',
            [
                'label' => __('Show Today', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Display "Today" row with Open Now/Closed Now status at the top.', 'valuepack-addons'),
            ]
        );
        $this->add_control(
            'enable_toggle',
            [
                'label' => __('Enable Toggle', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'enable_toggle_open',
            [
                'label' => __('Default Status', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'open' => __('Open', 'valuepack-addons'),
                    'close' => __('Close', 'valuepack-addons'),
                ],
                'default' => 'close',
                'condition' => [
                    'enable_toggle' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'toggle_open_text',
            [
                'label' => __('Open Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Open', 'valuepack-addons'),
                'placeholder' => __('Open', 'valuepack-addons'),
                'condition' => [
                    'enable_toggle' => 'yes',
                ],
            ]
        );

        /* Close Text */
        $this->add_control(
            'toggle_close_text',
            [
                'label' => __('Close Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Close', 'valuepack-addons'),
                'placeholder' => __('Close', 'valuepack-addons'),
                'condition' => [
                    'enable_toggle' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'business_hours_style_section',
            [
                'label' => __('Business Hours Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'business_hours_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget',
            ]
        );

        $this->add_control(
            'business_hours_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'business_hours_text_color_background',
            [
                'label' => __('Text Color Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ddd',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'business_hours_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '18',
                    'right' => '25',
                    'bottom' => '0',
                    'left' => '20',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'business_hours_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'business_hours_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget',
            ]
        );

        $this->add_responsive_control(
            'business_hours_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'business_hours_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget',
            ]
        );

        $this->end_controls_section();

        // Today Row Style Section
        $this->start_controls_section(
            'today_row_style_section',
            [
                'label' => __('Today Row Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Today Icon
        $this->add_control(
            'today_icon',
            [
                'label' => __('Today Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-clock',
                    'library' => 'fa-solid',
                ],
            ]
        );

        // Today Icon Size
        $this->add_responsive_control(
            'today_icon_size',
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
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Today Icon Color
        $this->add_control(
            'today_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        // Today Icon Spacing
        $this->add_responsive_control(
            'today_icon_spacing',
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
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Today Row Padding
        $this->add_responsive_control(
            'today_row_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '14',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Today Row Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'today_row_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-today',
            ]
        );

        // Today Row Border Radius
        $this->add_responsive_control(
            'today_row_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // Day Row Flex Direction
        $this->add_control(
            'day_row_flex_direction_today',
            [
                'label' => __('Row Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today' => 'display: flex; flex-direction: {{VALUE}};',
                ],
            ]
        );

        // Day Row Justify Content
        $this->add_control(
            'day_row_justify_content_todays',
            [
                'label' => __('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'space-between' => __('Space Between', 'valuepack-addons'),
                    'space-around' => __('Space Around', 'valuepack-addons'),
                    'space-evenly' => __('Space Evenly', 'valuepack-addons'),
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'open_now_style_today',
            [
                'label' => __('Today Styling', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'open_now_typography_today',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-title',
            ]
        );

        $this->add_control(
            'open_now_color_today',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'open_now_margin_today',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // Open Now Style
        $this->add_control(
            'open_now_style_heading',
            [
                'label' => __('Open Now Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'open_now_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-open',
            ]
        );

        $this->add_control(
            'open_now_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-open' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'open_now_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-open' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'open_now_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-open' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Closed Now Style
        $this->add_control(
            'closed_now_style_heading',
            [
                'label' => __('Closed Now Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'closed_now_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-closed',
            ]
        );

        $this->add_control(
            'closed_now_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-closed' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'closed_now_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-closed' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'closed_now_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-status-closed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Today Hours Style
        $this->add_control(
            'today_hours_style_heading',
            [
                'label' => __('Today Hours Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'today_hours_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-hours',
            ]
        );

        $this->add_control(
            'today_hours_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-hours' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'today_hours_color_day_off',
            [
                'label' => __('Day Off Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-today .cubewp-today-hours.day-off' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Day Row Style Section
        $this->start_controls_section(
            'day_row_style_section',
            [
                'label' => __('Day Row Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Day Row Padding
        $this->add_responsive_control(
            'day_row_padding',
            [
                'label' => __('Row Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Day Row Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'day_row_border',
                'label' => __('Row Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-item',
            ]
        );
        $this->add_responsive_control(
            'business_hours_border_radius_row',
            [
                'label' => __('Border width (Last Child)', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-item:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'business_hours_padding_row_last',
            [
                'label' => __('Padding (Last Child)', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 10,
                    'bottom' => 18,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-item:last-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // Day Row Flex Direction
        $this->add_control(
            'day_row_flex_direction',
            [
                'label' => __('Row Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-item' => 'display: flex; flex-direction: {{VALUE}};',
                ],
            ]
        );

        // Day Row Justify Content
        $this->add_control(
            'day_row_justify_content',
            [
                'label' => __('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'space-between' => __('Space Between', 'valuepack-addons'),
                    'space-around' => __('Space Around', 'valuepack-addons'),
                    'space-evenly' => __('Space Evenly', 'valuepack-addons'),
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-item' => 'justify-content: {{VALUE}};',
                ],
            ]
        );


        // Day Name Style
        $this->add_control(
            'day_name_style_heading',
            [
                'label' => __('Day Name Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'day_name_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-day',
            ]
        );

        $this->add_control(
            'day_name_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-day' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Timing Style
        $this->add_control(
            'timing_style_heading',
            [
                'label' => __('Timing Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'timing_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-time',
            ]
        );

        $this->add_control(
            'timing_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-time' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Day Off Style
        $this->add_control(
            'day_off_style_heading',
            [
                'label' => __('Day Off Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'day_off_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-time.cubewp-day-off',
            ]
        );

        $this->add_control(
            'day_off_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-time.cubewp-day-off' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'day_off_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-time.cubewp-day-off' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'day_off_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .cubewp-business-hours-time.cubewp-day-off' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // Style Section
        // Style Section
        $this->start_controls_section(
            'business_hours_style_toggle',
            [
                'label' => __('Toggle Button', 'valuepack-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_toggle' => 'yes',
                ],
            ]
        );

        /* Text Color */
        $this->add_control(
            'bh_days_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle .hours-toggle-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bh_days_text_hover_color',
            [
                'label' => __('Text Hover Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle:hover .hours-toggle-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        /* Typography */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bh_days_typography',
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle .hours-toggle-text',
            ]
        );

        /* Background Color */
        $this->add_control(
            'bh_days_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bh_days_bg_hover_color',
            [
                'label' => __('Background Hover Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        /* Border */
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bh_days_border',
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle',
            ]
        );
        $this->add_control(
            'bh_days_border_hover_color',
            [
                'label' => __('Border Hover Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        /* Box Shadow */
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bh_days_shadow',
                'selector' => '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle',
            ]
        );

        /* Border Radius */
        $this->add_control(
            'bh_days_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' =>
                    'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'bh_days_padding',
            [
                'label'      => __('Padding', 'valuepack-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '4',
                    'right' => '10',
                    'bottom' => '4',
                    'left' => '10',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' =>
                    'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        /* Position */
        $this->add_control(
            'bh_days_position',
            [
                'label' => __('Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,

                'options' => [
                    'static'   => __('Static', 'valuepack-addons'),
                    'absolute' => __('Absolute', 'valuepack-addons'),
                ],
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' =>
                    'position: {{VALUE}};',
                ],
            ]
        );

        /* Top */
        $this->add_responsive_control(
            'bh_days_position_top',
            [
                'label' => __('Top', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'condition' => [
                    'bh_days_position' => 'absolute',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' =>
                    'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /* Right */
        $this->add_responsive_control(
            'bh_days_position_right',
            [
                'label' => __('Right', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'condition' => [
                    'bh_days_position' => 'absolute',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' =>
                    'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /* Bottom */
        $this->add_responsive_control(
            'bh_days_position_bottom',
            [
                'label' => __('Bottom', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'condition' => [
                    'bh_days_position' => 'absolute',
                ],
                'default' => [
                    'size' => -30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' =>
                    'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /* Left */
        $this->add_responsive_control(
            'bh_days_position_left',
            [
                'label' => __('Left', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'condition' => [
                    'bh_days_position' => 'absolute',
                ],
                'default' => [
                    'size' => 40,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' =>
                    'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'business_hours_style_toggle_icon',
            [
                'label' => __('Toggle Button Icon', 'valuepack-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_toggle' => 'yes',
                ],
            ]
        );

        // Open Icon
        $this->add_control(
            'toggle_icon_open',
            [
                'label' => __('Open Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-down',
                    'library' => 'fa-solid',
                ],
            ]
        );

        // Close Icon
        $this->add_control(
            'toggle_icon_close',
            [
                'label' => __('Close Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-up',
                    'library' => 'fa-regular',
                ],
            ]
        );

        // Add Color control for icon
        $this->add_control(
            'toggle_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'toggle_icon_color_hover',
            [
                'label' => __('Icon Hover Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        // Add Font Size control for icon
        $this->add_responsive_control(
            'toggle_icon_font_size',
            [
                'label' => __('Icon Font Size', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 8, 'max' => 80],
                    'em' => ['min' => 0.5, 'max' => 5, 'step' => 0.01],
                    'rem' => ['min' => 0.5, 'max' => 5, 'step' => 0.01],
                ],
                'default' => [
                    'size' => 13,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        // Flex display direction
        $this->add_control(
            'toggle_icon_flex_direction',
            [
                'label' => __('Flex Direction', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' => 'display: flex; flex-direction: {{VALUE}};',
                ],
            ]
        );
        // Flex gap control
        $this->add_responsive_control(
            'toggle_icon_gap',
            [
                'label' => __('Gap Between Icon and Label', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0, 'max' => 5, 'step' => 0.1],
                    'rem' => ['min' => 0, 'max' => 5, 'step' => 0.1],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'toggle_icon_z_index',
            [
                'label' => __('Z Index', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'default' => [
                    'size' => 99,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-business-hours-widget .hours-toggle' => 'z-index: {{SIZE}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Enqueue Cubewp required scripts and styles for business hours rendering
        if (class_exists('CubeWp_Enqueue')) {
            CubeWp_Enqueue::enqueue_script('cwp-business-hours-fields');
            CubeWp_Enqueue::enqueue_style('cwp-business-hours-fields');
        }

        $post_type = $settings['post_type'];
        $business_hours_field = $settings['business_hours_field'];
        $enable_toggle = $settings['enable_toggle'];
        $enable_toggle_open = $settings['enable_toggle_open'];

        if (empty($post_type) || empty($business_hours_field)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . esc_html__('Please select a post type and business hours field.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        $post_id = get_the_ID();
        if (cubewp_is_elementor_editing()) {
            $post_id = cubewp_get_elementor_preview_post_id($post_type);
        }

        if (empty($post_id)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__('No posts found for the selected post type.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        // Get field options
        $field_options = function_exists('get_field_options') ? get_field_options($business_hours_field) : array();
        if (empty($field_options) || !isset($field_options['type']) || $field_options['type'] !== 'business_hours') {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__('Selected field is not a business hours field.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        // Set post ID for Cubewp (required for proper field rendering)
        if (class_exists('CubeWp_Single_Cpt')) {
            CubeWp_Single_Cpt::$post_id = $post_id;
        }
        // Get business hours field value using Cubewp's function
        $field_value = get_post_meta($post_id, $business_hours_field, true);

        if (empty($field_value) || !is_array($field_value)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . esc_html__('No business hours data found for the current post.', 'valuepack-addons') . '</div>';
            }
            return;
        }

        // Prepare field arguments for rendering 
        $show_today = isset($settings['show_today']) && $settings['show_today'] === 'yes';
        $open_now_text = isset($settings['open_now_text']) ? $settings['open_now_text'] : __('Open Now', 'valuepack-addons');
        $closed_now_text = isset($settings['closed_now_text']) ? $settings['closed_now_text'] : __('Closed Now', 'valuepack-addons');
        $day_off_text = isset($settings['day_off_text']) ? $settings['day_off_text'] : __('Day Off', 'valuepack-addons');


        $open_text  = ! empty($settings['toggle_open_text'])  ? $settings['toggle_open_text']  : __('Open', 'valuepack-addons');
        $close_text = ! empty($settings['toggle_close_text'])  ? $settings['toggle_close_text']  : __('Close', 'valuepack-addons');



        // Render business hours with custom layout
        $wrapper_classes = ['cubewp-business-hours-widget'];

        if ($enable_toggle === 'yes') {
            $wrapper_classes[] = 'active-toggle';
        }

        if ($enable_toggle_open === 'close') {
            $wrapper_classes[] = 'is-closed';
        }

        echo '<div class="' . esc_attr(implode(' ', $wrapper_classes)) . '">';


        $this->render_today_row($field_value, $settings, $open_now_text, $closed_now_text, $show_today);


        // Render all days

        $this->render_business_hours_days($field_value, $settings, $day_off_text);
        if ($enable_toggle === 'yes') {
            $btn_text = ($enable_toggle_open === 'open') ? $close_text : $open_text;
            $btn_class = ($enable_toggle_open === 'close') ? 'is-closed' : '';
            $open_icon = isset($settings['toggle_icon_open']) ? $settings['toggle_icon_open'] : [];
            $close_icon = isset($settings['toggle_icon_close']) ? $settings['toggle_icon_close'] : [];
            echo '<button class="hours-toggle ' . esc_attr($btn_class) . '"  data-open="' . $open_text . '"  data-close="' . $close_text . '">' .
                '<span class="hours-toggle-icon">';
            Icons_Manager::render_icon($open_icon, ['aria-hidden' => 'true']);
            echo '</span>';
            echo '<span class="hours-toggle-icon-close">';
            Icons_Manager::render_icon($close_icon, ['aria-hidden' => 'true']);
            echo '</span>';
            echo '<span class="hours-toggle-text">';
            echo esc_html($btn_text);
            echo '</span>';
            echo '</button>';
        }
        echo '</div>';
    }

    /**
     * Render Today row with status and hours
     */
    private function render_today_row($field_value, $settings, $open_now_text, $closed_now_text, $show_today)
    {
        if (empty($field_value) || !is_array($field_value)) {
            return;
        }

        // Get current day and status
        $timezone = wp_timezone_string();
        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        $currentDateTime = new DateTime('now', new DateTimeZone($timezone));
        $currentDay = strtolower($currentDateTime->format('l'));
        $currentTime = $currentDateTime->format('H:i:s');
        $currentSeconds = $this->time_to_seconds($currentTime);

        $status = 'closed';
        $today_hours = '';
        $is_24_hours = false;

        // Get Day Off text if set in settings, otherwise use default
        $day_off_text = isset($settings['day_off_text']) && !empty($settings['day_off_text']) ? $settings['day_off_text'] : esc_html__('Day Off', 'valuepack-addons');

        if (isset($field_value[$currentDay]) && !empty($field_value[$currentDay])) {
            $times = $field_value[$currentDay];

            if (!is_array($times) && is_string($times) && $times == '24-hours-open') {
                $status = 'open';
                $is_24_hours = true;
                $today_hours = __('24 Hours Open', 'valuepack-addons');
            } elseif (is_array($times) && isset($times['open']) && isset($times['close'])) {
                $openTimes = $times['open'];
                $closeTimes = $times['close'];
                $isOpen = false;

                // Check if current time falls within any open period (including overnight ranges).
                for ($i = 0; $i < count($openTimes); $i++) {
                    $openTime = $openTimes[$i];
                    $closeTime = $closeTimes[$i];
                    $openSeconds = $this->time_to_seconds($openTime);
                    $closeSeconds = $this->time_to_seconds($closeTime);

                    if ($openSeconds === null || $closeSeconds === null || $currentSeconds === null) {
                        continue;
                    }

                    if ($closeSeconds >= $openSeconds) {
                        // Same day window.
                        if ($currentSeconds >= $openSeconds && $currentSeconds <= $closeSeconds) {
                            $isOpen = true;
                            break;
                        }
                    } else {
                        // Overnight window (e.g. 06:00 PM - 02:00 AM).
                        if ($currentSeconds >= $openSeconds || $currentSeconds <= $closeSeconds) {
                            $isOpen = true;
                            break;
                        }
                    }
                }

                $status = $isOpen ? 'open' : 'closed';

                // Format today's hours
                $formatted_times = array();
                for ($i = 0; $i < count($openTimes); $i++) {
                    $otime = $this->format_business_time_display($openTimes[$i]);
                    $ctime = $this->format_business_time_display($closeTimes[$i]);
                    if ($otime !== '' && $ctime !== '') {
                        $formatted_times[] = $otime . ' - ' . $ctime;
                    }
                }
                $today_hours = implode(', ', $formatted_times);
            }
        } else {
            // Day is not available in the data, so use Day Off
            $today_hours = $day_off_text;
            $status = 'closed';
        }

        $today_icon = isset($settings['today_icon']) ? $settings['today_icon'] : '';

        echo '<div class="cubewp-business-hours-today">';
        echo '<div class="today-heading">';
        // Icon
        if (!empty($today_icon)) {
            echo '<span class="cubewp-today-icon">';
            Icons_Manager::render_icon($today_icon, ['aria-hidden' => 'true']);
            echo '</span>';
        }
        // Today's label
        $today_label = '<span class="cubewp-today-title">' . esc_html__('Today', 'valuepack-addons') . '</span>';
        if ($show_today === 'yes') {
            echo $today_label;
        }
        // Status (Open Now / Closed Now)
        if ($status === 'open') {
            $status_class = 'cubewp-status-open';
            $status_text = $open_now_text;
            echo '<span class="cubewp-status ' . esc_attr($status_class) . '">' . esc_html($status_text) . '</span>';
        } elseif ($status === 'closed' && $today_hours === $day_off_text) {
            // Day off
            echo $today_label;
        } else {
            $status_class = 'cubewp-status-closed';
            $status_text = $closed_now_text;
            echo '<span class="cubewp-status ' . esc_attr($status_class) . '">' . esc_html($status_text) . '</span>';
        }


        echo '</div>';
        // Today's hours
        if (!empty($today_hours)) {
            // Replace spaces and special characters with dashes, make lowercase
            $today_hours_class = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $today_hours));
            $today_hours_class = trim($today_hours_class, '-');

            echo '<div class="today-time"><span class="cubewp-today-hours ' . esc_attr($today_hours_class) . '">' . esc_html($today_hours) . '</span></div>';
        }

        echo '</div>';
    }

    /**
     * Render all business hours days
     */
    private function render_business_hours_days($field_value, $settings, $day_off_text = '')
    {
        // Always show all 7 days, even if field_value is empty
        if (!is_array($field_value)) {
            $field_value = array();
        }

        $days = array(
            'monday' => __('Monday', 'valuepack-addons'),
            'tuesday' => __('Tuesday', 'valuepack-addons'),
            'wednesday' => __('Wednesday', 'valuepack-addons'),
            'thursday' => __('Thursday', 'valuepack-addons'),
            'friday' => __('Friday', 'valuepack-addons'),
            'saturday' => __('Saturday', 'valuepack-addons'),
            'sunday' => __('Sunday', 'valuepack-addons'),
        );

        if (empty($day_off_text)) {
            $day_off_text = __('Day Off', 'valuepack-addons');
        }

        echo '<div class="cubewp-business-hours-days">';
        echo '<ul class="cubewp-business-hours-list" style="padding: 0;margin: 0;">';

        // Always loop through all 7 days
        foreach ($days as $day_key => $day_label) {
            echo '<li class="cubewp-business-hours-item cubewp-business-hours-' . esc_attr($day_key) . '">';
            echo '<span class="cubewp-business-hours-day">' . esc_html($day_label) . '</span>';

            // Check if day exists in field_value
            if (isset($field_value[$day_key])) {
                $day_data = $field_value[$day_key];

                if (!is_array($day_data) && is_string($day_data) && $day_data == '24-hours-open') {
                    echo '<span class="cubewp-business-hours-time">' . esc_html__('24 Hours Open', 'valuepack-addons') . '</span>';
                } elseif (is_array($day_data) && isset($day_data['open']) && isset($day_data['close'])) {
                    $openTimes = $day_data['open'];
                    $closeTimes = $day_data['close'];
                    $formatted_times = array();

                    if (is_array($openTimes) && is_array($closeTimes) && count($openTimes) > 0) {
                        for ($i = 0; $i < count($openTimes); $i++) {
                            if (isset($openTimes[$i]) && isset($closeTimes[$i])) {
                                $otime = $this->format_business_time_display($openTimes[$i]);
                                $ctime = $this->format_business_time_display($closeTimes[$i]);
                                if ($otime !== '' && $ctime !== '') {
                                    $formatted_times[] = $otime . ' - ' . $ctime;
                                }
                            }
                        }
                        if (!empty($formatted_times)) {
                            echo '<span class="cubewp-business-hours-time">' . esc_html(implode(', ', $formatted_times)) . '</span>';
                        } else {
                            echo '<span class="cubewp-business-hours-time cubewp-day-off">' . esc_html($day_off_text) . '</span>';
                        }
                    } else {
                        echo '<span class="cubewp-business-hours-time cubewp-day-off">' . esc_html($day_off_text) . '</span>';
                    }
                } elseif (is_array($day_data) && isset($day_data['status']) && $day_data['status'] === 'closed') {
                    echo '<span class="cubewp-business-hours-time cubewp-day-off">' . esc_html($day_off_text) . '</span>';
                } else {
                    // If day exists but has invalid data, show Day Off
                    echo '<span class="cubewp-business-hours-time cubewp-day-off">' . esc_html($day_off_text) . '</span>';
                }
            } else {
                // Day doesn't exist in field_value, show Day Off
                echo '<span class="cubewp-business-hours-time cubewp-day-off">' . esc_html($day_off_text) . '</span>';
            }

            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';
    }

    /**
     * Fallback method to render business hours if Cubewp method is not available
     */
    private function render_business_hours_fallback($field_value, $field_label)
    {
        if (empty($field_value) || !is_array($field_value)) {
            return;
        }

        $days = array(
            'monday' => __('Monday', 'valuepack-addons'),
            'tuesday' => __('Tuesday', 'valuepack-addons'),
            'wednesday' => __('Wednesday', 'valuepack-addons'),
            'thursday' => __('Thursday', 'valuepack-addons'),
            'friday' => __('Friday', 'valuepack-addons'),
            'saturday' => __('Saturday', 'valuepack-addons'),
            'sunday' => __('Sunday', 'valuepack-addons'),
        );

        echo '<div class="cwp-cpt-single-field-container">';
        echo '<div class="cwp-cpt-single-business-hours">';
        echo '<ul class="cwp-business-hours-list">';

        foreach ($days as $day_key => $day_label) {
            if (isset($field_value[$day_key])) {
                $day_data = $field_value[$day_key];
                echo '<li class="cwp-business-hours-item cwp-business-hours-' . esc_attr($day_key) . '">';
                echo '<span class="cwp-business-hours-day">' . esc_html($day_label) . '</span>';
                if (isset($day_data['status']) && $day_data['status'] === 'closed') {
                    echo '<span class="cwp-business-hours-time">' . esc_html__('Closed', 'valuepack-addons') . '</span>';
                } elseif (isset($day_data['status']) && $day_data['status'] === '24_hours') {
                    echo '<span class="cwp-business-hours-time">' . esc_html__('24 Hours Open', 'valuepack-addons') . '</span>';
                } elseif (isset($day_data['open']) && isset($day_data['close'])) {
                    $open_time = isset($day_data['open']) ? $day_data['open'] : '';
                    $close_time = isset($day_data['close']) ? $day_data['close'] : '';
                    echo '<span class="cwp-business-hours-time">' . esc_html($open_time) . ' - ' . esc_html($close_time) . '</span>';
                }
                echo '</li>';
            }
        }

        echo '</ul>';
        echo '</div>';
        echo '</div>';
    }
}
