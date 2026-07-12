<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
class Value_Pack_Mini_Cart extends Value_Pack_Widget_Base
{
    protected $value_pack_style_depends = ['value-pack-mini-cart-styles'];

    public function get_name()
    {
        return 'vp_mini_cart';
    }

    public function get_title()
    {
        return __('Mini Cart', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-cart vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    protected function register_controls()
    {

        // Content controls
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'valuepack-addons'),
            ]
        );
        $this->add_control(
            'cart_style',
            [
                'label' => __('Select Cart Style', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'style_1',
                'options' => [
                    'style_1' => __('Style 1', 'valuepack-addons'),
                    'style_2' => __('Style 2', 'valuepack-addons'),
                    'style_3' => __('Style 3', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shopping-cart',
                    'library' => 'fa-solid',
                ],
                'label_block' => true,
                'required' => true,
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mini-cart-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', // For SVG icons
                ],
            ]
        );

        // Text display control
        $this->add_control(
            'show_text',
            [
                'label' => __('Show Text', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        // Title control
        $this->add_control(
            'title',
            [
                'label' => __('Cart Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Cart',
                'placeholder' => __('Enter text here', 'valuepack-addons'),
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );
        // Add a new switcher for showing count
        $this->add_control(
            'show_count',
            [
                'label' => __('Show Count', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        // Add an input field for count format
        $this->add_control(
            'count_format',
            [
                'label' => __('Count Format', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '(%d)',
                'placeholder' => __('Enter format here', 'valuepack-addons'),
                /* translators: %d: cart count */
                'description' => __('Use "%d" in the text to dynamically display the cart count.', 'valuepack-addons'),
                'condition' => [
                    'show_count' => 'yes',
                    'show_text' => 'yes',
                ],
            ]
        );

        // Add a switcher for showing total price
        $this->add_control(
            'show_total_price',
            [
                'label' => __('Show Total Price', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        // Add a raw HTML input for total price format
        $this->add_control(
            'total_price_format',
            [
                'label' => __('Use "{prc}" to display the total price.', 'valuepack-addons'),
                'type' => Controls_Manager::RAW_HTML,
                'condition' => [
                    'show_total_price' => 'yes',
                    'show_text' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();

        // Style controls (typography, colors, etc.)
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .mini-cart-icon i' => 'color: {{VALUE}};',

                ],
            ]
        );

        // Icon Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'mini_cart_icon_border',
                'selector' => '{{WRAPPER}} .mini-cart-icon',
            ]
        );

        // Icon Border Radius
        $this->add_responsive_control(
            'mini_cart_icon_border_radius',
            [
                'label' => __('Icon Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Icon Padding
        $this->add_responsive_control(
            'mini_cart_icon_padding',
            [
                'label' => __('Icon Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Icon Background Color
        $this->add_control(
            'mini_cart_icon_bg_color',
            [
                'label' => __('Icon Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Title Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .mini-cart-main-title',
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        // Title Color
        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-main-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        // Flex direction (reverse icon/text)
        $this->add_control(
            'flex_direction',
            [
                'label' => __('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget' => 'display: flex; flex-direction: {{VALUE}};',
                ],
            ]
        );

        // Align items control
        $this->add_control(
            'align_items',
            [
                'label' => __('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        // Justify content control
        $this->add_control(
            'justify_content',
            [
                'label' => __('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        // Space Between Control (Text and Icon)
        $this->add_responsive_control(
            'text_icon_spacing',
            [
                'label' => __('Space Between', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget.cursor-pointer' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );
        // Background Color
        $this->add_control(
            'mini_cart_widget_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'mini_cart_widget_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            'mini_cart_widget_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'mini_cart_widget_border',
                'selector' => '{{WRAPPER}} .mini-cart-widget',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'mini_cart_widget_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'text_floating_section',
            [
                'label' => __('Text Floating', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        // Switcher: Enable Floating
        $this->add_control(
            'enable_text_floating',
            [
                'label' => __('Enable Floating Text', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_responsive_control(
            'floating_transform_xy',
            [
                'label' => __('Offset (Transform)', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'allowed_dimensions' => ['top', 'left'], // Top = Y, Left = X
                'default' => [
                    'top' => '0',
                    'left' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-main-title' => 'top: {{TOP}}{{UNIT}} ; left:{{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_text_floating' => 'yes',
                ],
            ]
        );
        // Border Radius
        $this->add_responsive_control(
            'floating_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-main-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_text_floating' => 'yes',
                ],
            ]
        );
        // Background Color
        $this->add_control(
            'floating_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-main-title' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_text_floating' => 'yes',
                ],
            ]
        );
        // Padding (used as Width and Height)
        $this->add_responsive_control(
            'floating_padding',
            [
                'label' => __('Width & Height', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => -500, 'max' => 500],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart-main-title' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}} ;display: flex;align-items: center;justify-content: center;',
                ],
                'condition' => [
                    'enable_text_floating' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_cart_buttons_style',
            [
                'label' => __('Cart Buttons Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_cart_buttons_style');

        // Normal
        $this->start_controls_tab(
            'tab_cart_buttons_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'cart_buttons_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-popup.cart-style3 .vp-cart-style-two-view-button,
            body .vp-cart-checkout-buttons .vp-cart-view-button,
            body .vp-cart-style-two-container .vp-cart-style-two-view-button' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'cart_buttons_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-popup.cart-style3 .vp-cart-style-two-view-button,
            body .vp-cart-checkout-buttons .vp-cart-view-button,
            body .vp-cart-style-two-container .vp-cart-style-two-view-button' => 'background-color: {{VALUE}} !important;background-image: unset;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cart_buttons_border',
                'selector' => 'body .vp-cart-popup.cart-style3 .vp-cart-style-two-view-button,
                      body .vp-cart-checkout-buttons .vp-cart-view-button,
                      body .vp-cart-style-two-container .vp-cart-style-two-view-button',
            ]
        );

        $this->add_control(
            'cart_buttons_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    'body .vp-cart-popup.cart-style3 .vp-cart-style-two-view-button,
            body .vp-cart-checkout-buttons .vp-cart-view-button,
            body .vp-cart-style-two-container .vp-cart-style-two-view-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_cart_buttons_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'cart_buttons_hover_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-popup.cart-style3 .vp-cart-style-two-view-button:hover,
            body .vp-cart-checkout-buttons .vp-cart-view-button:hover,
            body .vp-cart-style-two-container .vp-cart-style-two-view-button:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'cart_buttons_hover_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-popup.cart-style3 .vp-cart-style-two-view-button:hover,
            body .vp-cart-checkout-buttons .vp-cart-view-button:hover,
            body .vp-cart-style-two-container .vp-cart-style-two-view-button:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'cart_buttons_hover_border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-popup.cart-style3 .vp-cart-style-two-view-button:hover,
            body .vp-cart-checkout-buttons .vp-cart-view-button:hover,
            body .vp-cart-style-two-container .vp-cart-style-two-view-button:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        $this->start_controls_section(
            'section_checkout_buttons_style',
            [
                'label' => __('Checkout Buttons Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_checkout_buttons_style');

        // Normal
        $this->start_controls_tab(
            'tab_checkout_buttons_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'checkout_buttons_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button,
            body .vp-cart-checkout-buttons .vp-cart-checkout-button,
            body .vp-cart-style-two-container .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'checkout_buttons_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button,
            body .vp-cart-checkout-buttons .vp-cart-checkout-button,
            body .vp-cart-style-two-container .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button' => 'background-color: {{VALUE}} !important;background-image: unset;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'checkout_buttons_border',
                'selector' => 'body .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button,
                      body .vp-cart-checkout-buttons .vp-cart-checkout-button,
                      body .vp-cart-style-two-container .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button',
            ]
        );

        $this->add_control(
            'checkout_buttons_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    'body .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button,
            body .vp-cart-checkout-buttons .vp-cart-checkout-button,
            body .vp-cart-style-two-container .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_checkout_buttons_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'checkout_buttons_hover_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button:hover,
            body .vp-cart-checkout-buttons .vp-cart-checkout-button:hover,
            body .vp-cart-style-two-container .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'checkout_buttons_hover_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button:hover,
            body .vp-cart-checkout-buttons .vp-cart-checkout-button:hover,
            body .vp-cart-style-two-container .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'checkout_buttons_hover_border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button:hover,
            body .vp-cart-checkout-buttons .vp-cart-checkout-button:hover,
            body .vp-cart-style-two-container .vp-cart-style-two-checkout-buttons .vp-cart-style-two-checkout-button:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_cart_view_button_style',
            [
                'label' => __('Empty Cart Button Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_cart_button_style');

        // Normal tab
        $this->start_controls_tab(
            'tab_cart_button_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'cart_button_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-empty .vp-cart-view-button' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'cart_button_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-empty .vp-cart-view-button' => 'background-color: {{VALUE}} !important;background-image: unset;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cart_button_border',
                'selector' => 'body .vp-cart-empty .vp-cart-view-button',
            ]
        );

        $this->add_control(
            'cart_button_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    'body .vp-cart-empty .vp-cart-view-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover tab
        $this->start_controls_tab(
            'tab_cart_button_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'cart_button_hover_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-empty .vp-cart-view-button:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'cart_button_hover_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-empty .vp-cart-view-button:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'cart_button_hover_border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-cart-empty .vp-cart-view-button:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    /**
     * Render the widget output on the frontend with security enhancements.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $title = sanitize_text_field($settings['title']);
        $icon = $settings['icon'];
        $show_count = $settings['show_count'];
        $count_format = $settings['count_format'];
        $enable_text_floating = ($settings['enable_text_floating'] === 'yes') ? 'position: absolute; white-space: nowrap;' : 'position: relative;';
        $show_text = $settings['show_text'] === 'yes';
        $cart_style = sanitize_key($settings['cart_style']);

        // Get WooCommerce cart count with nonce verification
        $cart_count =  '';
        if (function_exists('WC') && WC()->cart) {
            $cart_items = WC()->cart->get_cart();
            $cart_count = absint(count($cart_items)); // Ensure count is positive integer
        }
        if ($cart_count > 0) {
            $cart_count = $cart_count;
        } else {
            $cart_count = '';
        }


        // Output the widget HTML with nonce and ARIA attributes
        echo '<div class="mini-cart-widget cursor-pointer" 
            data-cart_style="' . esc_attr($cart_style) . '"
            aria-label="' . esc_attr__('Shopping Cart', 'valuepack-addons') . '">';

        // Render icon with aria attributes
        if (!empty($icon['value'])) {
            echo '<span class="mini-cart-icon" aria-hidden="true">';
            \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            echo '</span>';
        }

        // Show text with cart item count
        if ($show_text) {
            if (!empty($title) || ($settings['show_count'] === 'yes' && $cart_count > 0) || ($settings['show_total_price'] === 'yes')) {
                $output = $title;

                // Handle count
                if ($settings['show_count'] === 'yes' && $cart_count > 0 && !empty($settings['count_format'])) {
                    $output  = $title . ' ' . sprintf($settings['count_format'], $cart_count);
                }

                // Handle total price
                if ($settings['show_total_price'] === 'yes') {
                    $total_price = '';
                    if (function_exists('WC') && WC()->cart) {
                        $total_price = WC()->cart->get_cart_total();
                    }
                    // Insert total price using {prc} placeholder
                    // Replace any previous price in $output with {prc}

                    if ($settings['show_count'] === 'yes' && $cart_count > 0 && !empty($settings['count_format'])) {
                        $output  = $title . ' ' . sprintf($settings['count_format'], $cart_count);
                    }
                    $output = preg_replace('/<span[^>]*class="woocommerce-Price-amount[^"]*"[^>]*>.*?<\/span>/', '{prc}', $output);
                    // Insert total price using {prc} placeholder
                    $output = ' ' . str_replace('{prc}', $total_price,  $output);
                }

                echo '<p class="mini-cart-main-title" style="' . esc_attr($enable_text_floating) . '">';
                echo wp_kses_post($output);
                echo '</p>';
            }
        }
        echo '</div>';

        // Output mini cart HTML if not in editor mode
        //if (!defined('ELEMENTOR_VERSION') || !\Elementor\Plugin::instance()->editor->is_edit_mode()) {
        $mini_cart_html = value_pack_generate_mini_cart_html($cart_style);
        if ($mini_cart_html) {
            echo $mini_cart_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        //}
    }
}
