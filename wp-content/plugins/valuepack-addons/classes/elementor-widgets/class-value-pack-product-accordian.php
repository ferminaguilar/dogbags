<?php
/**
 * Product Accordion Widget
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
 * Product Accordion Widget
 */
class Value_Pack_Product_Accordian extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_product_accordion';
    }

    public function get_title()
    {
        return __('VP Accordion', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-accordion vpack-icon';
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

        // Target Control (Accordion or Tabs)
        $this->add_control(
            'menu_target',
            [
                'label' => __('Target Display', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'accordion' => __('Accordion', 'valuepack-addons'),
                    'tabs' => __('Tabs', 'valuepack-addons'),
                    'popup' => __('Popup', 'valuepack-addons'),
                ],
                'default' => 'accordion',
            ]
        );

        $this->add_control(
            'show_in_sidebar',
            [
                'label' => __('Show in Sidebar', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'menu_target!' => 'popup',
                ],
            ]
        );

        $this->add_control(
            'sidebar_button_text',
            [
                'label' => __('Button Text', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Show Details', 'valuepack-addons'),
                'placeholder' => __('Enter your text here', 'valuepack-addons'),
                'condition' => [
                    'show_in_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'sidebar_button_icon',
            [
                'label' => __('Button Icon', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'show_in_sidebar' => 'yes',
                ],
            ]
        );

        $cubewp_megaID = class_exists('CubeWp_Theme_Builder') ? CubeWp_Theme_Builder::cwp_elementor_builder_options('blocks') : [];
        $repeater = new Repeater();

        $repeater->add_control(
            'item_menu_title',
            [
                'label' => esc_html__('Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Block',
            ]
        );

        $repeater->add_control(
            'item_menu_icon',
            [
                'label' => esc_html__('Icon For Title', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'item_menu_icon_active',
            [
                'label' => esc_html__('Icon For Active', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $content_type_options = [
            'block' => __('Block', 'valuepack-addons'),
            'shortcode' => __('Shortcode', 'valuepack-addons'),
        ];
        if (class_exists('WooCommerce')) {
            $content_type_options['woocommerce_product_tab'] = __('Woocommerce Product Tabs', 'valuepack-addons');
        }
        $repeater->add_control(
            'item_content_type',
            [
                'label' => __('Content Type', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $content_type_options,
                'default' => 'block',
            ]
        );

        $repeater->add_control(
            'select_tab_content',
            [
                'label' => esc_html__('Select Tab Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'woo_description'               => esc_html__('Description', 'valuepack-addons'),
                    'woo_additional_information'    => esc_html__('Additional Information', 'valuepack-addons'),
                    'woo_reviews'                   => esc_html__('Reviews', 'valuepack-addons'),
                ],
                'condition' => [
                    'item_content_type' => 'woocommerce_product_tab',
                ],
            ]
        );

        $repeater->add_control(
            'item_select_mega_menu',
            [
                'label' => esc_html__('Select Mega Menu', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $cubewp_megaID,
                'condition' => [
                    'item_content_type' => 'block',
                ],
            ]
        );

        $repeater->add_control(
            'item_shortcode_content',
            [
                'label' => __('Shortcode Content', 'valuepack-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '',
                'condition' => [
                    'item_content_type' => 'shortcode',
                ],
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label' => esc_html__('Menu Items', 'valuepack-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ item_menu_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('show_in_sidebar_style', [
            'label' => esc_html__('Button Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_in_sidebar' => 'yes',
            ],
        ]);

        // Text Color Control
        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Typography Control
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-tabs-show',
            ]
        );

        // Background Color Control
        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Padding Control
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border Control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => esc_html__('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-tabs-show',
                'default' => [
                    'border' => 'solid',
                    'width' => '1px',
                    'color' => '#000',
                ],
            ]
        );

        // Border Radius Control
        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_styles',
            [
                'label' => esc_html__('Hover Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Hover Background Color Control
        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => esc_html__('Hover Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Hover Text Color Control
        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__('Hover Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Hover Border Color Control
        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__('Hover Border Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        // Hover Box Shadow Control
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'label' => esc_html__('Hover Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-tabs-show:hover',
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__('Button Icon', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        // Icon Color
        $this->add_control(
            'sidebar_button_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show .vp-product-tabs-icon svg , {{WRAPPER}} .vp-product-tabs-show .vp-product-tabs-icon i' => 'color: {{VALUE}};path: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );

        // Icon Font Size
        $this->add_control(
            'sidebar_button_icon_font_size',
            [
                'label' => __('Icon Font Size', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show .vp-product-tabs-icon svg , {{WRAPPER}} .vp-product-tabs-show .vp-product-tabs-icon i' => 'font-size: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        // Icon Spacing
        $this->add_control(
            'sidebar_button_icon_spacing',
            [
                'label' => __('Icon Spacing', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-product-tabs-show .vp-product-tabs-icon svg , {{WRAPPER}} .vp-product-tabs-show .vp-product-tabs-icon i' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_in_sidebar' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Accordion Style Section
        $this->start_controls_section('accordion_style_section', [
            'label' => esc_html__('Accordion Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'menu_target' => ['accordion', 'popup'],
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'accordion_title_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-accordion .accordion-header button',
            ]
        );

        // Accordion Title Styles
        $this->add_control(
            'accordion_title_color',
            [
                'label' => esc_html__('Title Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_flex_direction',
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
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_justify_content',
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
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_align_items',
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
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_menu_item_style'); // Corrected: Start tabs here

        // Start "Normal" tab
        $this->start_controls_tab(
            'tab_menu_item_normal',
            [
                'label' => esc_html__('Normal', 'valuepack-addons'),
            ]
        );


        // Accordion Content Styles
        $this->add_responsive_control(
            'accordion_content_padding1',
            [
                'label' => esc_html__('Header Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'accordion_icon_bg_color',
            [
                'label' => esc_html__('Accordion Header BG Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .cubewp-accordion .accordion-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'accordion_icon_border',
                'label' => esc_html__('Accordion Header Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-accordion .accordion-header, {{WRAPPER}} .cubewp-accordion .accordion-button:focus',
            ]
        );

        $this->add_control(
            'accordion_header_border_radius',
            [
                'label' => esc_html__('Header Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .cubewp-tabber .tab-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'accordion_divider_color',
            [
                'label' => esc_html__('Divider Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header' => 'border-bottom: 1px solid {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'accordion_icon_styles',
            [
                'label' => esc_html__('Icon Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'accordion_icon_size',
            [
                'label' => esc_html__('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header i, {{WRAPPER}} .cubewp-accordion .accordion-header svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_icon_color',
            [
                'label' => esc_html__('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header i, {{WRAPPER}} .cubewp-accordion .accordion-header svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_header_spacing',
            [
                'label' => esc_html__('Spacing Between Icon & Title', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button .elementor-icon-list-icon, {{WRAPPER}} .cubewp-accordion .accordion-header button span' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Accordion Active Styles

        $this->add_responsive_control(
            'accordion_active_styles',
            [
                'label' => esc_html__('Active Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'accordion_active_bg_color',
            [
                'label' => esc_html__('Active Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button:not(.collapsed)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_active_border_color',
            [
                'label' => esc_html__('Active Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button:not(.collapsed)' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        // Accordion Content Styles

        $this->add_responsive_control(
            'accordion_content_styles',
            [
                'label' => esc_html__('Content Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'accordion_content_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-accordion .accordion-body *',
            ]
        );

        $this->add_responsive_control(
            'accordion_content_padding',
            [
                'label' => esc_html__('Content Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_content_margin',
            [
                'label' => esc_html__('Content Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_content_bg_color',
            [
                'label' => esc_html__('Content Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_content_border_color',
            [
                'label' => esc_html__('Content Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-body' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        // Start "Hover" tab
        $this->start_controls_tab(
            'tab_menu_item_hover',
            [
                'label' => esc_html__('Hover', 'valuepack-addons'),
            ]
        );


        $this->add_responsive_control(
            'accordion_header_hover_background_color',
            [
                'label' => esc_html__('Header Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'accordion_header_hover_text_color',
            [
                'label' => esc_html__('Header Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'accordion_header_hover_text_decoration',
            [
                'label' => esc_html__('Header Text Decoration', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'valuepack-addons'),
                    'underline' => esc_html__('Underline', 'valuepack-addons'),
                    'overline' => esc_html__('Overline', 'valuepack-addons'),
                    'line-through' => esc_html__('Line Through', 'valuepack-addons'),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .cubewp-accordion .accordion-header button:hover' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs(); // Corrected: End tabs here

        $this->end_controls_section();

        // Tabber Style Section
        $this->start_controls_section('tabber_style_section', [
            'label' => esc_html__('Tabber Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'menu_target' => 'tabs',
            ],
        ]);

        $this->add_control(
            'tabber_normal_styles',
            [
                'label' => esc_html__('Normal Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Tabber Title Styles
        $this->add_control(
            'tabber_title_color',
            [
                'label' => esc_html__('Title Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tabber_title_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-tabber .nav-link',
            ]
        );

        $this->add_control(
            'tabber_icon_bg_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_flex_direction',
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
                    '{{WRAPPER}} .cubewp-tabber .nav-tabs' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gap_between_tabs',
            [
                'label' => esc_html__('Gap Between Tabs', 'valuepack-addons'),
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
                    '{{WRAPPER}} .cubewp-tabber .nav-tabs' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_justify_content',
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
                    '{{WRAPPER}} .cubewp-tabber .nav-tabs' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_align_items',
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
                    '{{WRAPPER}} .cubewp-tabber .nav-tabs' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        // Navbar Padding
        $this->add_responsive_control(
            'navbar_padding',
            [
                'label' => esc_html__('Tab Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Navbar Border Settings
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'navbar_border',
                'label' => esc_html__('Tab Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-tabber .nav-tabs',
            ]
        );

        $this->add_control(
            'navbar_border_radius',
            [
                'label' => esc_html__('Tab Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        // Tabber Button Padding
        $this->add_responsive_control(
            'tabber_button_padding',
            [
                'label' => esc_html__('Tab Button Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Tabber Button Margin
        $this->add_responsive_control(
            'tabber_button_margin',
            [
                'label' => esc_html__('Tab Button Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Tabber Border Settings
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tabber_border',
                'label' => esc_html__('Tab Button Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-tabber .nav-link',
            ]
        );

        $this->add_control(
            'tabber_border_radius',
            [
                'label' => esc_html__('Tab Button Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_active_styles',
            [
                'label' => esc_html__('Active Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Tabber Active Styles
        $this->add_control(
            'tabber_active_bg_color',
            [
                'label' => esc_html__('Active Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link.active' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'tabber_active_border_color',
            [
                'label' => esc_html__('Active Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link.active' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'tabber_active_text_color',
            [
                'label' => esc_html__('Active Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link.active' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'tabber_active_box_shadow',
            [
                'label' => esc_html__('Active Box Shadow', 'valuepack-addons'),
                'type' => Controls_Manager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link.active' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_hover_styles',
            [
                'label' => esc_html__('Hover Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tabber_hover_bg_color',
            [
                'label' => esc_html__('Hover Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_hover_border_color',
            [
                'label' => esc_html__('Hover Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_hover_text_color',
            [
                'label' => esc_html__('Hover Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .nav-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabber_hover_box_shadow',
                'label' => esc_html__('Hover Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-tabber .nav-link:hover',
            ]
        );

        $this->add_control(
            'tabber_content_styles',
            [
                'label' => esc_html__('Content Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Tabber Content Styles

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tabber_content_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cubewp-tabber .tab-content *',
            ]
        );

        $this->add_responsive_control(
            'tabber_content_padding',
            [
                'label' => esc_html__('Content Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabber_content_margin',
            [
                'label' => esc_html__('Content Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_content_bg_color',
            [
                'label' => esc_html__('Content Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .tab-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_content_border_color',
            [
                'label' => esc_html__('Content Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .tab-content' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabber_content_border_radius',
            [
                'label' => esc_html__('Content Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cubewp-tabber .tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'reviews_form_style_accordion',
            [
                'label' => __('Reviews Form Button', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Start Tabs
        $this->start_controls_tabs('reviews_form_button_tabs');

        // Normal Tab
        $this->start_controls_tab(
            'reviews_form_button_tab_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'reviews_form_button_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a, 
            {{WRAPPER}} .vp-overall-rating .write-review-btn a svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'reviews_form_button_background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'reviews_form_button_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit, 
        {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a',

            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab(); // End Normal Tab

        // Hover Tab
        $this->start_controls_tab(
            'reviews_form_button_tab_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'reviews_form_button_hover_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'reviews_form_button_hover_background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'reviews_form_button_hover_border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover' => 'border-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_hover_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover, 
            {{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab(); // End Hover Tab

        $this->end_controls_tabs(); // End Tabs

        $this->end_controls_section(); // End Section

    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $button_text = !empty($settings['sidebar_button_text']) ? $settings['sidebar_button_text'] : 'Show Details';
        $icon_spacing = !empty($settings['sidebar_button_icon_spacing']['size']) ? $settings['sidebar_button_icon_spacing']['size'] : 10;
        $icon_color = !empty($settings['sidebar_button_icon_color']) ? $settings['sidebar_button_icon_color'] : '#000000';
        $icon_font_size = !empty($settings['sidebar_button_icon_font_size']['size']) ? $settings['sidebar_button_icon_font_size']['size'] : 16;

        if ('yes' === $settings['show_in_sidebar']) {
            $sidebar = 'sidebar';

            ob_start();
            if (!empty($settings['sidebar_button_icon']['value'])) {
                echo '<span class="vp-product-tabs-icon" style="margin-right:' . esc_attr($icon_spacing) . 'px; color:' . esc_attr($icon_color) . '; font-size:' . esc_attr($icon_font_size) . 'px;">';
                \Elementor\Icons_Manager::render_icon($settings['sidebar_button_icon'], ['aria-hidden' => 'true']);
                echo '</span>';
            }
            $icon_html = ob_get_clean();



            $close_sidebar = '<span class="vp-product-tabs-close">' . value_pack_get_close_icon_svg() . '</span>';
            $show_details = '<div class="vp-product-tabs-button">
            <button class="vp-product-tabs-show">
               
                ' . $icon_html . ' ' . esc_html($button_text) . '
            </button>
        </div>';
        } else {
            $sidebar = '';
            $close_sidebar = '';
            $show_details = '';
        }

        global $product;
        if ($product) {
            $product_tabs = apply_filters('woocommerce_product_tabs', []); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        } else {
            echo 'Product is not available.';
        }

        $popup_class = $popup_close_btn = '';
        if ($settings['menu_target'] === 'popup') {
            $popup_class = 'popup';
            $popup_close_btn = '<div class="popup-close-btn">' . value_pack_get_close_icon_svg() . '</div>';
        }

        // Render Accordion
        if ($settings['menu_target'] === 'accordion' || $settings['menu_target'] === 'popup') {
            echo $show_details; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '<div class="cubewp-accordion-main ' . esc_attr($sidebar) . '">';
            echo '<div class="cubewp-accordion">';
            echo $close_sidebar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '<div class="cubewp-accordion-inner accordion ' . esc_attr($popup_class) . '" id="cubewpAccordion">';
            foreach ($settings['menu_items'] as $index => $item) {
                echo '<div class="accordion-item">';
                echo '<h2 class="accordion-header" id="heading' . esc_attr($index) . '">';
                echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . esc_attr($index) . '" aria-expanded="false" aria-controls="collapse' . esc_attr($index) . '">';
                echo '<span class="normal-itern-icons">';
                \Elementor\Icons_Manager::render_icon($item['item_menu_icon'], ['aria-hidden' => 'true']);
                echo '</span>';
                echo '<span class="active-itern-icons">';
                \Elementor\Icons_Manager::render_icon($item['item_menu_icon_active'], ['aria-hidden' => 'true']);
                echo '</span>';
                echo esc_html($item['item_menu_title']);
                echo '</button>';
                echo '<h2>';
                echo '<div id="collapse' . esc_attr($index) . '" class="accordion-collapse collapse" aria-labelledby="heading' . esc_attr($index) . '">';
                echo '<div class="accordion-body">';
                echo $popup_close_btn; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                if ($item['item_content_type'] === 'block') {
                    echo do_shortcode('[elementor-template id="' . esc_attr(absint($item['item_select_mega_menu'])) . '"]');
                } else if ($item['item_content_type'] === 'woocommerce_product_tab') {
                    if (isset($product_tabs['description']) && $item['select_tab_content'] === 'woo_description') {
                        $allowed_html = array(
                            'p'      => array(),
                            'br'     => array(),
                            'ul'     => array(),
                            'ol'     => array(),
                            'li'     => array(),
                            'strong' => array(),
                            'em'     => array(),
                            'a'      => array(
                                'href'  => array(),
                                'title' => array()
                            ),
                        );
                        $content = $product_tabs['description']['callback']('description', $product_tabs['description']);
                        if (!empty($content)) {
                            echo wp_kses((string) $content, $allowed_html);
                        }
                    } elseif (isset($product_tabs['additional_information']) && $item['select_tab_content'] === 'woo_additional_information') {
                        call_user_func($product_tabs['additional_information']['callback'], 'additional_information', $product_tabs['additional_information']);
                    } elseif (isset($product_tabs['reviews']) && $item['select_tab_content'] === 'woo_reviews') {
                        call_user_func($product_tabs['reviews']['callback'], 'reviews', $product_tabs['reviews']);
                    }
                } else {
                    echo wp_kses_post($item['item_shortcode_content']);
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        // Render Tabs
        if ($settings['menu_target'] === 'tabs') {
            echo $show_details; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '<div class="cubewp-tabber-main ' . esc_attr($sidebar) . '">';
            echo '<div class="cubewp-tabber">';
            echo $close_sidebar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '<ul class="nav nav-tabs" id="cubewpTabs" role="tablist">';
            foreach ($settings['menu_items'] as $index => $item) {
                $active_class = ($index === 0) ? ' active' : '';
                $aria_selected = ($index === 0) ? 'true' : 'false';
                echo '<li class="nav-item" role="presentation">';
                echo '<button class="nav-link' . esc_attr($active_class) . '" id="tab-' . esc_attr($index) . '-tab" data-bs-toggle="tab" data-bs-target="#tab-' . esc_attr($index) . '" type="button" role="tab" aria-controls="tab-' . esc_attr($index) . '" aria-selected="' . esc_attr($aria_selected) . '">';
                echo esc_html($item['item_menu_title']);
                echo '</button>';
                echo '</li>';
            }
            echo '</ul>';

            echo '<div class="tab-content" id="cubewpTabsContent">';
            foreach ($settings['menu_items'] as $index => $item) {
                $active_class = ($index === 0) ? ' show active' : '';
                echo '<div class="tab-pane fade' . esc_attr($active_class) . '" id="tab-' . esc_attr($index) . '" role="tabpanel" aria-labelledby="tab-' . esc_attr($index) . '-tab">';
                if ($item['item_content_type'] === 'block') {
                    echo do_shortcode('[elementor-template id="' . $item['item_select_mega_menu'] . '"]');
                } else if ($item['item_content_type'] === 'woocommerce_product_tab') {
                    if (isset($product_tabs['description']) && $item['select_tab_content'] === 'woo_description') {
                        call_user_func($product_tabs['description']['callback'], 'description', $product_tabs['description']);
                    } elseif (isset($product_tabs['additional_information']) && $item['select_tab_content'] === 'woo_additional_information') {
                        call_user_func($product_tabs['additional_information']['callback'], 'additional_information', $product_tabs['additional_information']);
                    } elseif (isset($product_tabs['reviews']) && $item['select_tab_content'] === 'woo_reviews') {

                        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                            echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            return;
                        } else {
                            call_user_func($product_tabs['reviews']['callback'], 'reviews', $product_tabs['reviews']);
                        }
                    }
                } else {
                    echo $item['item_shortcode_content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

?>

<?php

    }
}
