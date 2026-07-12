<?php

/**
 * Value Pack - Product Variations Widget
 * 
 * Displays product variation selection forms for WooCommerce products
 * 
 * Features:
 * - Color, image and label variation types
 * - Flexible layout controls
 * - Responsive styling options
 * - Secure form handling
 * 
 * @package ValuePack 
 * @since 1.0.0
 */
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

/**
 * Product Variations Widget
 *
 * Displays WooCommerce product variation selection forms with extensive styling options
 */
class Value_Pack_Product_Variations extends Value_Pack_Widget_Base
{

    /**
     * Retrieve widget name
     *
     * @return string Widget name
     */
    public function get_name()
    {
        return 'vp_product_variations';
    }

    /**
     * Retrieve widget title
     *
     * @return string Widget title
     */
    public function get_title()
    {
        return esc_html__('Product Variations', 'valuepack-addons');
    }

    /**
     * Retrieve widget icon
     *
     * @return string Widget icon
     */
    public function get_icon()
    {
        return 'eicon-woocommerce vpack-icon';
    }

    /**
     * Retrieve widget categories
     *
     * @return array Widget categories
     */
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

        $this->add_control(
            'product_id',
            [
                'label' => __('Product ID', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Enter the WooCommerce product ID. If you want to use this element on the product single page, leave this field empty.', 'valuepack-addons'),
                'label_block' => true,
                'default' => value_pack_get_any_one_product_id(),
                'placeholder' => __('e.g., 123', 'valuepack-addons'),
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'heading_section_style',
            [
                'label' => __('Heading Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'h_flex_directions_container',
            [
                'label' => esc_html__('Heading Container Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-product-attributes .attribute-heading' => 'display:flex;flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'h_justify_contents_container',
            [
                'label' => esc_html__('Heading Container Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-product-attributes .attribute-heading' => 'display:flex;justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'h_align_itemss_container',
            [
                'label' => esc_html__('Heading Container Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-product-attributes .attribute-heading' => 'display:flex;align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes h3' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typographies',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .wc-women-single-attributes h3',
            ]
        );
        $this->add_control(
            'margin_heading',
            [
                'label' => __('Heading Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'flex_directions',
            [
                'label' => esc_html__('Attributes Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'column',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-product-attributes' => 'display:flex;flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => __('Text Alignment', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'justify_contents',
            [
                'label' => esc_html__('Attributes Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-product-attributes' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'align_itemss',
            [
                'label' => esc_html__('Attributes Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-product-attributes' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'attributes_margin2',
            [
                'label' => __('Attributes Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wm-product-attributes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'attribute_section_style',
            [
                'label' => __('Attribute Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'flex_directions_container',
            [
                'label' => esc_html__('Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'column',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .attribute-container' => 'display:flex;flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'justify_contents_container',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .attribute-container' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'align_itemss_container',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .attribute-container' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'attribute_container_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .attribute-container',
            ]
        );

        $this->add_responsive_control(
            'attribute_container_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .attribute-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'attribute_container_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .attribute-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'in_stock_heading',
            [
                'label' => __('In stoke Space', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'in_stoke_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-variation-availability .stock' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Styles Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Color Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'attribute_label_color',
            [
                'label' => __('Show in Dropdown', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'valuepack-addons'),
                'label_off' => __('Off', 'valuepack-addons'),
                'return_value' => 'on',
                'default' => 'off',
            ]
        );

        // Active Controls
        $this->add_control(
            'active_color:after',
            [
                'label' => __('Check Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes .active span::after' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Normal and Active Tabs
        $this->start_controls_tabs('tabs_color_states');

        // Normal Tab
        $this->start_controls_tab(
            'tab_color_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'color_heading1',
            [
                'label' => __('Color Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes, .wc-women-single-attributes .image-attributes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'row',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes, .wc-women-single-attributes .image-attributes' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes, .wc-women-single-attributes .image-attributes' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'stretch',
                'options' => [
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes, .wc-women-single-attributes .image-attributes' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gap_between_items',
            [
                'label' => esc_html__('Gap Between Items', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes, .wc-women-single-attributes .image-attributes' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        // Normal Controls
        $this->add_control(
            'color_width',
            [
                'label' => __('Color Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 36,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes span, .wc-women-single-attributes .image-attributes img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color_height',
            [
                'label' => __('Color Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 36,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes span, .wc-women-single-attributes .image-attributes img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'color_border_main',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes .color-attributes span, .wc-women-single-attributes .image-attributes img',
                'separator' => 'before',
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 5,
                        'right' => 5,
                        'bottom' => 5,
                        'left' => 5,
                    ],
                    'color' => '#fff',
                ],
            ]
        );

        $this->add_control(
            'color_border_radius',
            [
                'label' => __('Color Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes span' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wc-women-single-attributes .image-attributes img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'color_box_shadow',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes .color-attributes span, .wc-women-single-attributes .image-attributes img',
                'default' => [
                    'box_shadow_type' => 'outline',
                    'horizontal' => 0,
                    'vertical' => 0,
                    'blur' => 1,
                    'spread' => 1,
                    'color' => '#ddd',
                ],
            ]
        );

        $this->end_controls_tab(); // End Normal Tab

        // Active Tab
        $this->start_controls_tab(
            'tab_color_active',
            [
                'label' => __('Active', 'valuepack-addons'),
            ]
        );

        // Active Controls
        $this->add_control(
            'active_color',
            [
                'label' => __('Active Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes .active span, .wc-women-single-attributes .image-attributes .active img' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_border_color',
            [
                'label' => __('Active Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .color-attributes .active span, .wc-women-single-attributes .image-attributes .active img' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'active_box_shadow',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes .color-attributes .active span, .wc-women-single-attributes .image-attributes .active img',
                'default' => [
                    'box_shadow_type' => 'outline',
                    'horizontal' => 0,
                    'vertical' => 0,
                    'blur' => 1,
                    'spread' => 1,
                    'color' => '#000',
                ],
            ]
        );
        $this->end_controls_tab(); // End Active Tab

        $this->end_controls_tabs(); // End Normal and Active Tabs

        $this->end_controls_section();

        // Styles Section

        // Styles Section
        $this->start_controls_section(
            'section_style_label',
            [
                'label' => __('Label Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'attribute_label_dropdown',
            [
                'label' => __('Show in Dropdown', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('On', 'valuepack-addons'),
                'label_off' => __('Off', 'valuepack-addons'),
                'return_value' => 'on',
                'default' => 'off',
            ]
        );

        // Normal and Active Tabs
        $this->start_controls_tabs('tabs_label_states');

        // Normal Tab
        $this->start_controls_tab(
            'tab_label_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => __('Label Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_bg_color',
            [
                'label'     => __('Label Background Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_margin1',
            [
                'label' => __('Label Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Labels Styling
        $this->add_control(
            'label_padding',
            [
                'label' => __('Label Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .label-attributes li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typographies',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .label-attributes li span',
            ]
        );

        $this->add_control(
            'label_flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'row',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'stretch',
                'options' => [
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'label_gap_between_items',
            [
                'label' => esc_html__('Gap Between Items', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        // Normal Controls
        $this->add_control(
            'lable_width',
            [
                'label' => __('label Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 36,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes span' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'lable_height',
            [
                'label' => __('Label Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 36,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes span' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'lable_border_main',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes .label-attributes span',
                'separator' => 'before',
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 5,
                        'right' => 5,
                        'bottom' => 5,
                        'left' => 5,
                    ],
                    'color' => '#fff',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'lable_border_main_last',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes  .label-attributes li:last-child span',
                'separator' => 'before',
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 5,
                        'right' => 5,
                        'bottom' => 5,
                        'left' => 5,
                    ],
                    'color' => '#fff',
                ],
            ]
        );

        $this->add_control(
            'Label_border_radius',
            [
                'label' => __('Label Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes span' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'Label_box_shadow',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes .label-attributes span',
                'default' => [
                    'box_shadow_type' => 'outline',
                    'horizontal' => 0,
                    'vertical' => 0,
                    'blur' => 1,
                    'spread' => 1,
                    'color' => '#ddd',
                ],
            ]
        );

        $this->end_controls_tab(); // End Normal Tab

        // Active Tab
        $this->start_controls_tab(
            'tab_lable_active1',
            [
                'label' => __('Active', 'valuepack-addons'),
            ]
        );

        // Active Controls
        $this->add_control(
            'active_lable1_color',
            [
                'label' => __('Active Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes .active span' => 'color: {{VALUE}}  !important;',
                ],
            ]
        );

        $this->add_control(
            'active_lable1',
            [
                'label' => __('Active Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes .active span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_border_lable1',
            [
                'label' => __('Active Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .label-attributes .active span' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'active_border_lable1',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes li.attribute-label-item.active span',
                'separator' => 'before',
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 5,
                        'right' => 5,
                        'bottom' => 5,
                        'left' => 5,
                    ],
                    'color' => '#fff',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'lable_active_box_shadow',
                'selector' => '{{WRAPPER}} .wc-women-single-attributes .label-attributes .active span',
                'default' => [
                    'box_shadow_type' => 'outline',
                    'horizontal' => 0,
                    'vertical' => 0,
                    'blur' => 1,
                    'spread' => 1,
                    'color' => '#000',
                ],
            ]
        );
        $this->end_controls_tab(); // End Active Tab

        $this->end_controls_tabs(); // End Normal and Active Tabs



        $this->end_controls_section();

        // Styles Section
        $this->start_controls_section(
            'section_style_Buttons',
            [
                'label' => __('Buttons Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Normal and Active Tabs
        $this->start_controls_tabs('tabs_label_states1');

        // Normal Tab
        $this->start_controls_tab(
            '1tab_label_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'cart_text_bg_color',
            [
                'label' => __('Cart BG Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' =>  'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cart_text_color',
            [
                'label' => __('Cart TEXT Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',

                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' =>  'color: {{VALUE}} !important;',
                ],
            ]
        );

        // Labels Styling
        $this->add_control(
            'input_q_buttons',
            [
                'label' => __('Input Button BG Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity button' =>  'background-color: {{VALUE}};',
                    '{{WRAPPER}} .quantity input' =>  'background-color: {{VALUE}};',
                ],
            ]
        );
        // Labels Styling
        /* // new issue fixes */
        $this->add_control(
            'input_q_buttons_color',
            [
                'label' => __('Input Button Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' =>   '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .quantity button' =>  'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .quantity input' =>  'color: {{VALUE}} !important;',
                ],
            ]
        );
        /* //Close new issue fixes */
        $this->add_control(
            'button_margin1',
            [
                'label' => __('Section Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-variation-add-to-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Labels Styling
        $this->add_control(
            'Button_label_padding',
            [
                'label' => __('Input Button Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 10,
                    'right' => 13,
                    'bottom' => 10,
                    'left' => 13,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // Labels Styling
        $this->add_control(
            'button_padding',
            [
                'label' => __('Cart Button Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 15,
                    'right' => 13,
                    'bottom' => 15,
                    'left' => 13,
                    'unit' => 'px', // Default unit for padding
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typographies',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .single_add_to_cart_button',
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button',
                ],
            ]
        );

        $this->add_control(
            'cart_flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'row',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-variation-add-to-cart' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-variation-add-to-cart' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'stretch',
                'options' => [
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-variation-add-to-cart' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cart_gap_between_items',
            [
                'label' => esc_html__('Gap Between Items', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-variation-add-to-cart' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        // Normal Controls
        $this->add_responsive_control(
            'Input_width',
            [
                'label' => __('Quantity Input Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 112,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Add Dropdown Control
        $this->add_responsive_control(
            'layout_option',
            [
                'label' => __('Layout Option', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => __('Show in One Line', 'valuepack-addons'),
                    'other'  => __('Show in Full Width', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'quantity_container_margin',
            [
                'label' => __('Quantity Container Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'button_width',
            [
                'label' => __('Button Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'em'], // Remove 'px' as an option
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_option' => 'other',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_widths',
            [
                'label' => __('Button Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'em'], // Remove 'px' as an option
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}}  .single_add_to_cart_button' => 'width: calc({{SIZE}}{{UNIT}} - {{Input_width.SIZE}}{{Input_width.UNIT}} - {{cart_gap_between_items.SIZE}}{{cart_gap_between_items.UNIT}});',
                ],
                'condition' => [
                    'layout_option' => 'inline',
                ],
            ]
        );
        // Heading for Input Border
        $this->add_control(
            'input_border_heading',
            [
                'label' => __('Input Border', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'input_button_widths',
            [
                'label' => __('Input Button Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'em', 'px'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity .input-text' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'input_border',
                'label' => __('Input Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .quantity input',
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 1,
                        'right' => 0,
                        'bottom' => 1,
                        'left' => 0,
                    ],
                    'color' => '#ddd',
                ],
            ]
        );

        // Heading for Minus Button Border
        $this->add_control(
            'minus_button_border_heading',
            [
                'label' => __('Minus Button Border', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'minus_button_widths',
            [
                'label' => __('Minus Button Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'em', 'px'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity .minus-variations' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'minus_button_border',
                'label' => __('Minus Button Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .quantity .minus-variations',
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 1,
                        'right' => 0,
                        'bottom' => 1,
                        'left' => 1,
                    ],
                    'color' => '#ddd',
                ],
            ]
        );

        // Heading for Plus Button Border
        $this->add_control(
            'plus_button_border_heading',
            [
                'label' => __('Plus Button Border', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'plus_button_widths',
            [
                'label' => __('Plus Button Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'em', 'px'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity .plus-variations' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'plus_button_border',
                'label' => __('Plus Button Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .quantity .plus-variations',
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 1,
                        'right' => 1,
                        'bottom' => 1,
                        'left' => 0,
                    ],
                    'color' => '#ddd',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border_main',
                'selector' => '{{WRAPPER}}  .single_add_to_cart_button',
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'border' => 'solid',
                    'width' => [
                        'top' => 5,
                        'right' => 5,
                        'bottom' => 5,
                        'left' => 5,
                    ],
                    'color' => '#fff',
                ],
            ]
        );

        $this->add_control(
            'input_border_radius',
            [
                'label' => __('Input Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-women-single-attributes .minus-variations' => 'border-radius: {{SIZE}}{{UNIT}} 0px 0px {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wc-women-single-attributes .plus-variations' => 'border-radius: 0px {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0px;',
                ],
            ]
        );
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 2,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        // Heading for Styling Section
        $this->add_control(
            'buy_now_button_styling_heading',
            [
                'label' => __('Buy Now Button', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Divider
        $this->add_control(
            'buy_now_button_styling_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        // Button Width
        $this->add_responsive_control(
            'buy_now_button_width',
            [
                'label' => __('Button Width', 'valuepack-addons'),
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
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .WM-buy-now-button-container' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .buy-now-button' => 'width: 100%;',
                ],
            ]
        );

        // Button Background Color
        $this->add_control(
            'buy_now_button_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1d1d1d', // Default color
                'selectors' => [
                    '{{WRAPPER}} .buy-now-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Button Text Color
        $this->add_control(
            'buy_now_button_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff', // Default text color
                'selectors' => [
                    '{{WRAPPER}} .buy-now-button' => 'color: {{VALUE}} !important;',
                ],
            ]
        );


        // Border Radius
        $this->add_control(
            'buy_now_button_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
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
                'default' => [
                    'size' => 3,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .buy-now-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'buy_now_button_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .buy-now-button',
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'buy_now_button_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .buy-now-button',
            ]
        );

        // Padding
        $this->add_control(
            'buy_now_button_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 13,
                    'right' => 20,
                    'bottom' => 13,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .buy-now-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );


        $this->end_controls_tab(); // End Normal Tab

        // Active Tab
        $this->start_controls_tab(
            'tab_lable_active10',
            [
                'label' => __('Active', 'valuepack-addons'),
            ]
        );


        $this->add_control(
            'cart_text_bg_color_hover',
            [
                'label' => __('Cart BG Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover,{{WRAPPER}} .single_add_to_cart_button .woo-ajax-loader' =>  'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cart_text_color_hover',
            [
                'label' => __('Cart TEXT Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover,{{WRAPPER}} .single_add_to_cart_button.woo-ajax-loader-svg circle' =>  'stroke:{{VALUE}} !important;color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_control(
            'button_border_main_hover',
            [
                'label' => __('Cart Border color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'input_border_radius_hover',
            [
                'label' => __('Input Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 2,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'buy_now_button_heading',
            [
                'label' => __('Buy Now Button Hover Options', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before', // Adds space before the heading
            ]
        );

        $this->add_control(
            'buy_now_button_divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        // Hover Background Color
        $this->add_control(
            'buy_now_button_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000', // Default color
                'selectors' => [
                    '{{WRAPPER}} .buy-now-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Hover Text Color
        $this->add_control(
            'buy_now_button_hover_text_color',
            [
                'label' => __('Hover Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .buy-now-button:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        // Hover Border Color
        $this->add_control(
            'buy_now_button_hover_border_color',
            [
                'label' => __('Hover Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#005bb5',
                'selectors' => [
                    '{{WRAPPER}} .buy-now-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab(); // End Active Tab

        $this->end_controls_tabs(); // End Normal and Active Tabs 

        $this->end_controls_section();



        // Styles Section
        $this->start_controls_section(
            'section_stock_manager',
            [
                'label' => __('Custom Stock Manager', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'enable_custom_stock_styles',
            [
                'label' => __('Enable Custom Stock Styles', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'yes' => __('Enable', 'valuepack-addons'),
                    'no' => __('Disable', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'instock_custom_text',
            [
                'label' => __('In Stock Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('In Stock', 'valuepack-addons'),
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'outofstock_custom_text',
            [
                'label' => __('Out of Stock Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Out of Stock', 'valuepack-addons'),
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );
        // Typography Control
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stock_status_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-stock-status',
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );

        // Text Color Control
        $this->add_control(
            'stock_status_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );

        // Background Color Control
        $this->add_control(
            'stock_status_background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );


        // Padding Control
        $this->add_responsive_control(
            'stock_status_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );

        // Margin Control
        $this->add_responsive_control(
            'stock_status_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );

        // Border Controls
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'stock_status_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-stock-status',
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );

        // Border Radius Control
        $this->add_responsive_control(
            'stock_status_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );

        // Box Shadow Control
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'stock_status_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-stock-status',
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );

        // Alignment Control
        $this->add_control(
            'stock_status_alignment',
            [
                'label' => __('Alignment', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .vp-stock-status' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'enable_custom_stock_styles_indicator',
            [
                'label' => __('Enable Stock Indicator', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'block' => __('Enable', 'valuepack-addons'),
                    'none' => __('Disable', 'valuepack-addons'),
                ],
                'condition' => [
                    'enable_custom_stock_styles' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'stock_indicator_width',
            [
                'label' => __('Indicator Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'default' => ['size' => 20],
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status::before' => 'content: "";position: absolute;width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles_indicator' => 'block',

                ],
            ]
        );

        $this->add_responsive_control(
            'stock_indicator_height',
            [
                'label' => __('Indicator Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'default' => ['size' => 20],
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles_indicator' => 'block',
                ],
            ]
        );

        $this->add_control(
            'stock_indicator_bg_color',
            [
                'label' => __('Indicator Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status::before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles_indicator' => 'block',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'stock_indicator_border',
                'label' => __('Indicator Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-stock-status::before',
                'condition' => [
                    'enable_custom_stock_styles_indicator' => 'block',
                ],
            ]
        );
        $this->add_responsive_control(
            'stock_indicator_border_radius',
            [
                'label' => __('Indicator Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles_indicator' => 'block',
                ],
            ]
        );

        // Optional: position offsets (top, left, etc.)
        $this->add_responsive_control(
            'stock_indicator_offset',
            [
                'label' => __('Offset (Top/Left)', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-stock-status::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_stock_styles_indicator' => 'block',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
       
        if (is_singular('product')) {
            $product_id = get_the_ID();
        } else {
            $product_id = isset($settings['product_id']) ? absint($settings['product_id']) : 0;
            if ($product_id <= 0 || get_post_type($product_id) !== 'product' || get_post_status($product_id) !== 'publish') {
                $default_products = value_pack_get_woocommerce_products();
                if (!empty($default_products)) {
                    $first_default_id = array_key_first($default_products);
                    $product_id = absint($first_default_id);
                } else {
                    $product_id = 0;
                }
            }
        }

        if (!$product_id) {
            return;
        }

        $product = wc_get_product($product_id);
        if (!$product) {
            return;
        }

        global $product;
        setup_postdata(get_post($product_id));

        // Ensure global $post is available
        global $post;
        $post = get_post($product_id);

        // Sanitize classes
        $attribute_label_dropdown = ($settings['attribute_label_dropdown'] === 'on') ? 'label-dropdown' : '';
        $enable_custom_stock_styles = ($settings['enable_custom_stock_styles'] === 'yes') ? 'v-stock-enabled' : '';
        $attribute_label_color = ($settings['attribute_label_color'] === 'on') ? 'color-dropdown' : '';

        echo '
        <div class="wc-women-single-attributes ' . esc_attr($attribute_label_dropdown) . ' ' . esc_attr($enable_custom_stock_styles) . ' ' . esc_attr($attribute_label_color) . '">
        ';
        if (!empty($settings['enable_custom_stock_styles']) && $settings['enable_custom_stock_styles'] === 'yes') {
            $instock_text = !empty($settings['instock_custom_text']) ? $settings['instock_custom_text'] : __('In Stock', 'valuepack-addons');
            $outofstock_text = !empty($settings['outofstock_custom_text']) ? $settings['outofstock_custom_text'] : __('Out of Stock', 'valuepack-addons');
            $products = wc_get_product($product_id);
            if ($products && $products->exists()) {
                $stock_status = $products->get_stock_status();
                if ($stock_status === 'instock') {
                    echo '<div class="vp-stock-status" data-instock="' . esc_attr($instock_text) . '"  data-outofstock="' . esc_attr($outofstock_text) . '" >' . esc_html($instock_text) . '</div>';
                } elseif ($stock_status === 'outofstock') {
                    echo '<div class="vp-stock-status"  data-instock="' . esc_attr($instock_text) . '"  data-outofstock="' . esc_attr($outofstock_text) . '" >' . esc_html($outofstock_text) . '</div>';
                }
            }
        }
        // Render add to cart form
        woocommerce_template_single_add_to_cart();

        echo '</div>';

        wp_reset_postdata();
    }
}
