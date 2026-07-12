<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;


/**
 * Value Pack Icon Products Widget
 *
 * Elementor widget for displaying products with icon hover effects
 *
 * @since 1.0.0
 */
class Value_Pack_Icon_Products extends Value_Pack_Widget_Base
{
    protected $mega_menu_index = 1;

    public function get_name()
    {
        return 'vp_icon_product';
    }

    public function get_title()
    {
        return esc_html__('Iconic Product View', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-product-description vpack-icon';
    }

    public function get_categories()
    {
        return array('value_pack');
    }

    public function get_keywords()
    {
        return array(
            'icon',
            'product',
            'hover',
            'preview',
            'quick view',
            'details',
            'woocommerce',
            'ecommerce',
            'cart',
            'shop',
            'interactive',
            'image',
            'title',
            'price'
        );
    }

    protected function register_controls()
    {

        $this->start_controls_section('content_type', [
            'label' => esc_html__('Content Type', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'content_type_select',
            [
                'label'   => esc_html__('Select Content Type', 'valuepack-addons'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'iconic_product_view' => esc_html__('Iconic Product View', 'valuepack-addons'),
                    'get_the_look'        => esc_html__('Get The Look', 'valuepack-addons'),
                ],
                'default' => 'iconic_product_view',
            ]
        );

        $this->add_control(
            'iconic_product_view_title',
            [
                'label' => esc_html__('Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('SHOP THE LOOK', 'valuepack-addons'),
                'condition' => [
                    'content_type_select' => 'get_the_look',
                ],
            ]
        );

        $this->add_control(
            'get_the_look_product_ids',
            [
                'label' => esc_html__('Product IDs', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Enter the WooCommerce product IDs you want to display. Example: 125, 126, 127', 'valuepack-addons'),
                'condition' => [
                    'content_type_select' => 'get_the_look',
                ],
            ]
        );
        $default_products = value_pack_get_woocommerce_products();
        $default_list = '';
        $count = 0;
        foreach ($default_products as $id => $name) {
            if ($count >= 5) break; // limit to 5 default items
            $default_list .= '<li class="vp-multi-product-result-item" data-id="' . esc_attr($id) . '">' . esc_html($name) . '</li>';
            $count++;
        }
        $this->add_control(
            'product_ids_search_html',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
                    <div class="vp-multi-product-search-wrapper" style="display: none;">
                        <input type="text" class="vp-multi-product-search" placeholder="Start typing product name..." />
                        <ul class="vp-multi-product-results">' . $default_list . '</ul>
                        <div class="vp-selected-products"></div>
                    </div>
                ',
                'content_classes' => 'vp-multi-product-search-box',
            ]
        );
        $this->add_control(
            'marker_icon_look',
            [
                'label' => esc_html__('Marker Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'map-marker-alt',
                        'plus-circle',
                        'dot-circle',
                        'bullseye'
                    ],
                ],
                'condition' => [
                    'content_type_select' => 'get_the_look',
                ],
            ]

        );
        $this->add_control(
            'enable_icon_text',
            [
                'label'     => esc_html__('Enable Icon Text', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default'     => 'no',
                'condition' => [
                    'content_type_select' => 'get_the_look',
                ],
            ]
        );

        // Text field for icon text (only when "get_the_look" is selected AND switcher is "yes")
        $this->add_control(
            'icon_text_value',
            [
                'label'       => esc_html__('Icon Text', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__('Enter your icon text', 'valuepack-addons'),
                'condition'   => [
                    'content_type_select' => 'get_the_look',
                    'enable_icon_text'    => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Background Image Section
        $this->start_controls_section('background_section', [
            'label' => esc_html__('Background Image', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'background_image',
            [
                'label' => esc_html__('Upload Base Image', 'valuepack-addons'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'icon_box_style',
            [
                'label' => esc_html__('Type', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'valuepack-addons'),
                    'minimal' => esc_html__('Minimal', 'valuepack-addons'),
                ],
                'default' => 'default',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section('background_section_settings', [
            'label' => esc_html__('Background Image Setting', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);


        $this->add_responsive_control(
            'background_image_width',
            [
                'label' => esc_html__('Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-image-container img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'background_image_height',
            [
                'label' => esc_html__('Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 2000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-image-container img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'background_image_object_fit',
            [
                'label' => esc_html__('Object Fit', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'fill' => esc_html__('Fill', 'valuepack-addons'),
                    'cover' => esc_html__('Cover', 'valuepack-addons'),
                    'contain' => esc_html__('Contain', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-image-container img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'background_image_max_width',
            [
                'label' => esc_html__('Max Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-image-container img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-image-container img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'background_image_alignment',
            [
                'label' => esc_html__('Alignment', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-image-container' => 'display: flex;justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('markers_section', [
            'label' => esc_html__('Product Markers', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_CONTENT,
            'condition' => [
                'content_type_select' => 'iconic_product_view',
            ],
        ]);

        $repeater = new Repeater();

        // Select Type: Product or Custom Data
        $repeater->add_control(
            'marker_type',
            [
                'label' => esc_html__('Select Type', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'product',
                'options' => [
                    'product' => esc_html__('Product', 'valuepack-addons'),
                    'custom'  => esc_html__('Custom Data', 'valuepack-addons'),
                ],
            ]
        );

        // Marker Icon (for plus/add icon)
        $repeater->add_control(
            'marker_icon',
            [
                'label' => esc_html__('Marker Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'map-marker-alt',
                        'plus-circle',
                        'dot-circle',
                        'bullseye'
                    ],
                ],
            ]
        );

        // Product ID (only visible when type is Product)
        $repeater->add_control(
            'product_id',
            [
                'label' => esc_html__('Product', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__('Search product...', 'valuepack-addons'),
                'description' => esc_html__('Type to search WooCommerce products', 'valuepack-addons'),
                'default' => '',
                'condition' => ['marker_type' => 'product'],
            ]
        );
        $default_products = value_pack_get_woocommerce_products();
        $default_list = '';
        $count = 0;
        foreach ($default_products as $id => $name) {
            if ($count >= 5) break; // limit to 5 default items
            $default_list .= '<li class="vp-product-result-item" data-id="' . esc_attr($id) . '">' . esc_html($name) . '</li>';
            $count++;
        }
        $repeater->add_control(
            'product_search_html',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
                    <div class="vp-product-search-wrapper" style="display: none;">
                        <input type="text" class="vp-product-search" placeholder="Start typing product name..." />
                         <ul class="vp-product-results">' . $default_list . '</ul>
                    </div>
                ',
                'content_classes' => 'vp-product-search-box',
                'condition' => ['marker_type' => 'product'],
            ]
        );

        // Custom Data Fields (visible only when type is Custom)
        $repeater->add_control(
            'custom_card_icon',
            [
                'label' => esc_html__('Card Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'condition' => ['marker_type' => 'custom'],
            ]
        );

        $repeater->add_control(
            'custom_card_title',
            [
                'label' => esc_html__('Card Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter title...', 'valuepack-addons'),
                'condition' => ['marker_type' => 'custom'],
            ]
        );

        $repeater->add_control(
            'custom_card_description',
            [
                'label' => esc_html__('Card Description', 'valuepack-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'placeholder' => esc_html__('Enter description...', 'valuepack-addons'),
                'condition' => ['marker_type' => 'custom'],
            ]
        );

        // Horizontal Position
        $repeater->add_control(
            'position_left',
            [
                'label' => esc_html__('Horizontal Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => ['%' => ['min' => 0, 'max' => 100]],
                'default' => ['unit' => '%', 'size' => 50],
                'frontend_available' => true,
            ]
        );

        // Vertical Position
        $repeater->add_control(
            'position_top',
            [
                'label' => esc_html__('Vertical Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => ['%' => ['min' => 0, 'max' => 100]],
                'default' => ['unit' => '%', 'size' => 50],
                'frontend_available' => true,
            ]
        );

        // Product Position (Left/Right of the icon)
        $repeater->add_control(
            'product_position',
            [
                'label' => esc_html__('Card Position', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'valuepack-addons'),
                        'icon' => 'eicon-arrow-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'valuepack-addons'),
                        'icon' => 'eicon-arrow-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'product_markers',
            [
                'label' => esc_html__('Markers', 'valuepack-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ marker_type }}} - {{{ position_left.size }}}% x {{{ position_top.size }}}%',
                'default' => [],
            ]
        );

        $this->end_controls_section();

        // Global Icon Styling Section
        $this->start_controls_section('product_settings_look', [
            'label' => esc_html__('Icon Settings', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
                'content_type_select' => 'get_the_look',
            ],
        ]);
        $this->add_control(
            'enable_icon_colors',
            [
                'label' => esc_html__('Enable Icon Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'color_icons',
            [
                'label' => esc_html__('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon i' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'enable_icon_color' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'sizes',
            [
                'label' => esc_html__('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 12,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon svg' => 'height: auto; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'svg_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-main-container.product-look-view .wc-woo-icon-products .vp-elementor-icon svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        // Margin Control
        $this->add_responsive_control(
            'svg_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'vw', 'vh'],
                'default' => [
                    'top' => '0',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-main-container.product-look-view .wc-woo-icon-products .vp-elementor-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        // Global Icon Styling Section
        $this->start_controls_section('product_settings', [
            'label' => esc_html__('Icon Settings', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
                'content_type_select' => 'iconic_product_view',
            ],
        ]);
        $this->add_control(
            'enable_icon_color',
            [
                'label' => esc_html__('Enable Icon Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'color_icon',
            [
                'label' => esc_html__('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon path' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon i' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'enable_icon_color' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'size',
            [
                'label' => esc_html__('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 12,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wc-woo-icon-products .vp-elementor-icon svg' => 'height: auto; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section('card_product_settings', [
            'label' => esc_html__('Card Settings', 'valuepack-addons'),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [
                'content_type_select' => 'iconic_product_view',
            ],
        ]);

        //   Enable custom styling toggle
        $this->add_control('enable_custom_card_style', [
            'label' => esc_html__('Enable Custom Styling', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'valuepack-addons'),
            'label_off' => esc_html__('No', 'valuepack-addons'),
            'return_value' => 'yes',
            'default' => 'no',
        ]);

        //  Main Card Styling Heading
        $this->add_control('main_card_style_heading', [
            'label' => esc_html__('Card Styling', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'condition' => ['enable_custom_card_style' => 'yes'],
        ]);
        // Enable Positioning
        $this->add_control('enable_card_position', [
            'label' => esc_html__('Enable Position', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'valuepack-addons'),
            'label_off' => esc_html__('No', 'valuepack-addons'),
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => ['enable_custom_card_style' => 'yes'],
        ]);

        // Top Position
        $this->add_responsive_control('card_position_top', [
            'label' => esc_html__('Top Position', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em'],
            'range' => [
                'px' => ['min' => -500, 'max' => 500],
            ],
            'condition' => [
                'enable_custom_card_style' => 'yes',
                'enable_card_position' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => ' top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        // Left Position
        $this->add_responsive_control('card_position_left', [
            'label' => esc_html__('Left Position', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'em'],
            'range' => [
                'px' => ['min' => -500, 'max' => 500],
            ],
            'condition' => [
                'enable_custom_card_style' => 'yes',
                'enable_card_position' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'left: {{SIZE}}{{UNIT}}  !important;right: unset !important;',
            ],
        ]);
        $this->add_responsive_control('card_padding', [
            'label' => esc_html__('Card Padding', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_border_radius', [
            'label' => esc_html__('Card Border Radius', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'label' => esc_html__('Card Border', 'valuepack-addons'),
            'selector' => '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards',
            'condition' => ['enable_custom_card_style' => 'yes'],
        ]);

        $this->add_responsive_control('card_width', [
            'label' => esc_html__('Card Width', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('card_gap', [
            'label' => esc_html__('Gap Between Items', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        // Flex Direction
        $this->add_control('card_flex_direction', [
            'label' => esc_html__('Flex Direction', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'row' => esc_html__('Row', 'valuepack-addons'),
                'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                'column' => esc_html__('Column', 'valuepack-addons'),
                'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
            ],
            'default' => 'row',
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'flex-direction: {{VALUE}};',
            ],
        ]);

        // Justify Content
        $this->add_control('card_justify_content', [
            'label' => esc_html__('Justify Content', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
                'center' => esc_html__('Center', 'valuepack-addons'),
                'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
                'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
            ],
            'default' => 'flex-start',
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'justify-content: {{VALUE}};',
            ],
        ]);

        // Align Items
        $this->add_control('card_align_items', [
            'label' => esc_html__('Align Items', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
                'center' => esc_html__('Center', 'valuepack-addons'),
                'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
                'baseline' => esc_html__('Baseline', 'valuepack-addons'),
            ],
            'default' => 'stretch',
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards' => 'align-items: {{VALUE}};',
            ],
        ]);


        $this->end_controls_section();

        $this->start_controls_section('elements_product_settings', [
            'label' => esc_html__('Card Options Settings', 'valuepack-addons'),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [
                'enable_custom_card_style' => 'yes',
            ],
        ]);

        //  Image Styling Heading
        $this->add_control('image_style_heading', [
            'label' => esc_html__('Image Styling', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'condition' => ['enable_custom_card_style' => 'yes'],
        ]);


        $this->add_control('image_display', [
            'label' => esc_html__('Image Display', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'flex' => esc_html__('Show Image', 'valuepack-addons'),
                'none'  => esc_html__('Hide Image', 'valuepack-addons'),
            ],
            'default' => 'block',
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                // Hide/show image
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-image' => 'display: {{VALUE}};',
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards img' => 'display: {{VALUE}};',
                // Set content width to 100% when image hidden
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content' => 'margin: 0;',
            ],
        ]);


        $this->add_responsive_control('image_width', [
            'label' => esc_html__('Image Width', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-image, {{WRAPPER}} .wc-woo-icon-products .product-hover-cards img,{{WRAPPER}} .wc-woo-icon-products .product-hover-cards svg,{{WRAPPER}} .wc-woo-icon-products .product-hover-cards i' => 'width: {{SIZE}}{{UNIT}};font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('image_height', [
            'label' => esc_html__('Image Height', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-image, {{WRAPPER}} .wc-woo-icon-products .product-hover-cards img,{{WRAPPER}} .wc-woo-icon-products .product-hover-cards svg,{{WRAPPER}} .wc-woo-icon-products .product-hover-cards i' => 'height: {{SIZE}}{{UNIT}};font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('image_border_radius', [
            'label' => esc_html__('Image Border Radius', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-image, {{WRAPPER}} .wc-woo-icon-products .product-hover-cards img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'image_border',
            'label' => esc_html__('Image Border', 'valuepack-addons'),
            'selector' => '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards img',
            'condition' => ['enable_custom_card_style' => 'yes'],
        ]);

        //  Text Styling (P, A, Span) Heading
        $this->add_control('text_style_heading', [
            'label' => esc_html__('Text Stylings', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'condition' => ['enable_custom_card_style' => 'yes'],
        ]);
        $this->add_responsive_control('content_width', [
            'label' => esc_html__('Content Width', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'condition' => ['enable_custom_card_style' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content' => 'width:calc(100% - {{SIZE}}{{UNIT}}) !important;',
            ],
        ]);
        $elements = [
            'p' => 'Short Heading',
            'a' => 'Title',
            'span' => 'Description',
        ];

        foreach ($elements as $tag => $label) {
            $display_control_id = "{$tag}_display";

            //  Show/Hide Dropdown
            $this->add_control($display_control_id, [
                // translators: %s: element label
                'label' => sprintf(esc_html__('%s Display', 'valuepack-addons'), esc_html($label)),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'flex' => esc_html__('Show', 'valuepack-addons'),
                    'none'  => esc_html__('Hide', 'valuepack-addons'),
                ],
                'default' => 'flex',
                'condition' => ['enable_custom_card_style' => 'yes'],
                'selectors' => [
                    "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content {$tag}" => 'display: {{VALUE}};',
                    "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content a" => 'white-space: nowrap;overflow: hidden;text-overflow: ellipsis;',
                ],
            ]);

            //  Typography
            $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
                'name' => "{$tag}_typography",
                // translators: %s: element label
                'label' => sprintf(esc_html__('%s Typography', 'valuepack-addons'), esc_html($label)),
                'selector' => "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content {$tag}",
                'condition' => [
                    'enable_custom_card_style' => 'yes',
                    $display_control_id => 'flex',
                ],
            ]);

            //  Text Color
            $this->add_control("{$tag}_color", [
                // translators: %s: element label
                'label' => sprintf(esc_html__('%s Color', 'valuepack-addons'), esc_html($label)),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content {$tag}" => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_custom_card_style' => 'yes',
                    $display_control_id => 'flex',
                ],
            ]);
            //  Hover Color
            $this->add_control("{$tag}_hover_color", [
                // translators: %s: element label
                'label' => sprintf(esc_html__('%s Hover Color', 'valuepack-addons'), esc_html($label)),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content {$tag}:hover" => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_custom_card_style' => 'yes',
                    $display_control_id => 'flex',
                ],
            ]);

            //  Margin
            $this->add_responsive_control("{$tag}_margin", [
                // translators: %s: element label
                'label' => sprintf(esc_html__('%s Margin', 'valuepack-addons'), esc_html($label)),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content {$tag}" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content h4" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    "{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content span" => '0',
                ],
                'condition' => [
                    'enable_custom_card_style' => 'yes',
                    $display_control_id => 'flex',
                ],
            ]);
        }
        $this->end_controls_section();

        $this->start_controls_section('card_arrow_product_settings', [
            'label' => esc_html__('Card Arrow Settings', 'valuepack-addons'),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [
                'content_type_select' => 'iconic_product_view',
            ],
        ]);

        // Enable Arrow Toggle
        $this->add_control('enable_card_arrow', [
            'label' => esc_html__('Enable Arrow', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'valuepack-addons'),
            'label_off' => esc_html__('No', 'valuepack-addons'),
            'return_value' => 'yes',
            'default' => 'no',
        ]);

        // Arrow Width
        $this->add_responsive_control('card_arrow_width', [
            'label' => esc_html__('Arrow Width', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'default' => ['size' => 10],
            'condition' => ['enable_card_arrow' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards::after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        // Arrow Left Offset
        // Arrow Left Offset
        $this->add_responsive_control('card_arrow_left', [
            'label' => esc_html__('Arrow Left Offset', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => ['min' => -100, 'max' => 100],
                '%'  => ['min' => -100, 'max' => 100],
            ],
            'default' => ['size' => 50, 'unit' => '%'],
            'condition' => ['enable_card_arrow' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards::after' => 'left: {{SIZE}}{{UNIT}};',
            ],
        ]);

        // Arrow Top Offset
        $this->add_responsive_control('card_arrow_top', [
            'label' => esc_html__('Arrow Top Offset', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => ['min' => -100, 'max' => 100],
                '%'  => ['min' => -100, 'max' => 100],
            ],
            'default' => ['size' => 0, 'unit' => '%'],
            'condition' => ['enable_card_arrow' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards::after' => 'top: {{SIZE}}{{UNIT}};',
            ],
        ]);


        // Arrow Background Color
        $this->add_control('card_arrow_bg_color', [
            'label' => esc_html__('Arrow Background Color', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'condition' => ['enable_card_arrow' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards::after' => 'background-color: {{VALUE}};content: "";position: absolute;transform: translate(-50%, -50%) rotate(45deg);;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section(
            'popover_settings',
            [
                'label' => __('Popover Settings', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'content_type_select' => 'get_the_look',
                ],
            ]
        );

        // Main Container
        $this->add_control(
            'popover_container_heading',
            [
                'label' => __('Main Container', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'popover_bg',
                'label' => __('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .product-hover-cards-look-data',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'popover_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .product-hover-cards-look-data',
            ]
        );

        $this->add_control(
            'popover_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-look-data' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'popover_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .product-hover-cards-look-data',
            ]
        );

        $this->add_responsive_control(
            'popover_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '30',
                    'right' => '30',
                    'bottom' => '30',
                    'left' => '30',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-look-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Header Section
        $this->add_control(
            'header_heading',
            [
                'label' => __('Header', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-iconic-product-title',
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-product-title' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->add_responsive_control(
            'header_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .data-loop-popover-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Close Button
        $this->add_control(
            'close_button_heading',
            [
                'label' => __('Close Button', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'close_button_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-product-close svg path' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'close_button_hover_color',
            [
                'label' => __('Hover Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-product-close:hover svg path' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'close_button_size',
            [
                'label' => __('Size', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-iconic-product-close svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Product Cards
        $this->add_control(
            'cards_heading',
            [
                'label' => __('Product Cards', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cards_spacing',
            [
                'label' => __('Spacing Between Cards', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        // Image Settings
        $this->add_control(
            'image_heading',
            [
                'label' => __('Product Image', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_width_card',
            [
                'label' => __('Width', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 60,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-image,{{WRAPPER}} .product-hover-cards-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_width_h',
            [
                'label' => __('Height', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 75,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-image , {{WRAPPER}} .product-hover-cards-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius_card',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'label' => __('Image Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .product-hover-cards-image img',
            ]
        );

        // Content Settings
        $this->add_control(
            'content_heading',
            [
                'label' => __('Content Area', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Product Title
        $this->add_control(
            'title_heading',
            [
                'label' => __('Product Title', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .product-hover-cards-content a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-content a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Hover Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-hover-cards-content a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        // Price
        $this->add_control(
            'price_heading',
            [
                'label' => __('Price', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content span',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-woo-icon-products .product-hover-cards .product-hover-cards-content span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'icon_text_style_section',
            [
                'label'     => esc_html__('Icon Text Style', 'valuepack-addons'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'content_type_select' => 'get_the_look',
                    'enable_icon_text'    => 'yes',
                ],
            ]
        );
        $this->add_control(
            'icon_text_color',
            [
                'label'     => esc_html__('Text Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        // 12. Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'icon_text_typography',
                'selector' => '{{WRAPPER}} .vp-elementor-icon',
            ]
        );

        // 13. Width
        $this->add_responsive_control(
            'icon_text_width',
            [
                'label'      => esc_html__('Width', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 1000],
                    '%'  => ['min' => 0, 'max' => 100],
                    'vw' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        // 4. Background color
        $this->add_responsive_control(
            'icon_text_bg_color',
            [
                'label'     => esc_html__('Background Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // 5. Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'icon_text_border',
                'selector' => '{{WRAPPER}} .vp-elementor-icon',
            ]
        );

        // 6. Border Radius
        $this->add_responsive_control(
            'icon_text_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // 7. Padding
        $this->add_responsive_control(
            'icon_text_padding',
            [
                'label'      => esc_html__('Padding', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_text_margin',
            [
                'label'      => esc_html__('Margin', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // 8. Display type: Flex or Block
        $this->add_control(
            'icon_text_display',
            [
                'label'   => esc_html__('Display Type', 'valuepack-addons'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'block' => esc_html__('Block', 'valuepack-addons'),
                    'flex'  => esc_html__('Flex', 'valuepack-addons'),
                ],
                'default' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'display: {{VALUE}};',
                ],
            ]
        );

        // 9. Justify Content (only if flex)
        $this->add_control(
            'icon_text_justify_content',
            [
                'label'     => esc_html__('Justify Content', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'center'     => esc_html__('Center', 'valuepack-addons'),
                    'flex-end'   => esc_html__('End', 'valuepack-addons'),
                    'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                    'space-around'  => esc_html__('Space Around', 'valuepack-addons'),
                ],
                'default'   => 'center',
                'condition' => [
                    'icon_text_display' => 'flex',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        // 10. Align Items (only if flex)
        $this->add_control(
            'icon_text_align_items',
            [
                'label'     => esc_html__('Align Items', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'stretch'    => esc_html__('Stretch', 'valuepack-addons'),
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'center'     => esc_html__('Center', 'valuepack-addons'),
                    'flex-end'   => esc_html__('End', 'valuepack-addons'),
                    'baseline'   => esc_html__('Baseline', 'valuepack-addons'),
                ],
                'default'   => 'center',
                'condition' => [
                    'icon_text_display' => 'flex',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_text_flex_direction',
            [
                'label'   => esc_html__('Flex Direction', 'valuepack-addons'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row'            => esc_html__('Row', 'valuepack-addons'),
                    'row-reverse'    => esc_html__('Row Reverse', 'valuepack-addons'),
                    'column'         => esc_html__('Column', 'valuepack-addons'),
                    'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                ],
                'default'   => 'row',
                'condition' => [
                    'icon_text_display' => 'flex',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-elementor-icon' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $main_image = !empty($settings['background_image']['url']) ? $settings['background_image']['url'] : '';
        $icon_box_style = !empty($settings['icon_box_style']) ? $settings['icon_box_style'] : 'default';

        $enable_icon_text = !empty($settings['enable_icon_text']) ? $settings['enable_icon_text'] : '';
        $icon_text_value = !empty($settings['icon_text_value']) ? $settings['icon_text_value'] : '';



        $content_type_select = !empty($settings['content_type_select']) ? $settings['content_type_select'] : 'iconic_product_view';
        if ($content_type_select !== 'iconic_product_view') {
            if (empty($main_image)) {
                return;
            }
            $get_the_look_product_ids = !empty($settings['get_the_look_product_ids']) ? $settings['get_the_look_product_ids'] : '';
            $marker_icon_look = !empty($settings['marker_icon_look']) ? $settings['marker_icon_look'] : '';
            $iconic_product_view_title = !empty($settings['iconic_product_view_title']) ? $settings['iconic_product_view_title'] : '';





?>
            <div class="vp-iconic-main-container  product-look-view">
                <div class="vp-iconic-image-container">
                    <?php
                    if (!empty($main_image)) {
                    ?>
                        <img src="<?php echo esc_url($main_image); ?>" alt="image" class="vp-iconic-image">
                    <?php
                    }
                    ?>
                </div>
                <div class="wc-woo-icon-products <?php echo esc_attr($icon_box_style); ?>">
                    <div class="vp-elementor-icon">

                        <?php
                        if ($enable_icon_text == 'yes') {
                            echo wp_kses_post($icon_text_value);
                        }
                        Icons_Manager::render_icon($marker_icon_look, ['aria-hidden' => 'true']); ?>
                    </div>
                    <div class="product-hover-cards-look-data">
                        <div class="data-loop-popover-header">
                            <h3 class="vp-iconic-product-title"><?php echo esc_html($iconic_product_view_title); ?></h3>
                            <span class="vp-iconic-product-close"><svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.488364 10.4999C0.391779 10.5 0.297359 10.4713 0.217046 10.4177C0.136734 10.364 0.0741365 10.2878 0.0371728 10.1985C0.00020916 10.1093 -0.00946041 10.0111 0.00938722 9.91639C0.0282348 9.82166 0.0747529 9.73465 0.143057 9.66636L9.16633 0.643092C9.25791 0.551511 9.38212 0.500061 9.51164 0.500061C9.64115 0.500061 9.76536 0.551511 9.85694 0.643092C9.94852 0.734672 9.99997 0.858883 9.99997 0.988398C9.99997 1.11791 9.94852 1.24212 9.85694 1.3337L0.83367 10.357C0.788365 10.4024 0.734538 10.4384 0.67528 10.4629C0.616022 10.4874 0.5525 10.5 0.488364 10.4999Z" fill="#1D1D1D" />
                                    <path d="M9.51161 10.4999C9.44747 10.5 9.38395 10.4874 9.32469 10.4629C9.26543 10.4384 9.21161 10.4024 9.1663 10.357L0.143031 1.3337C0.0514496 1.24212 0 1.11791 0 0.988398C0 0.858883 0.0514496 0.734672 0.143031 0.643092C0.234611 0.551511 0.358822 0.500061 0.488337 0.500061C0.617852 0.500061 0.742062 0.551511 0.833643 0.643092L9.85691 9.66636C9.92522 9.73465 9.97174 9.82166 9.99059 9.91639C10.0094 10.0111 9.99976 10.1093 9.9628 10.1985C9.92584 10.2878 9.86324 10.364 9.78293 10.4177C9.70261 10.4713 9.60819 10.5 9.51161 10.4999Z" fill="#1D1D1D" />
                                </svg>
                            </span>
                        </div>
                        <?php
                        // Get all available product IDs
                        $all_product_ids = array_keys(value_pack_get_woocommerce_products());

                        if (empty($get_the_look_product_ids)) {
                            $get_the_look_product_ids = implode(',', array_slice($all_product_ids, 0, 1));
                        }

                        if (!empty($get_the_look_product_ids)) {
                            $product_ids = array_map('trim', explode(',', $get_the_look_product_ids));
                            $valid_product_ids = array();
                            $available_product_ids = $all_product_ids;
                            $available_index = 0;

                            // Validate each product ID and replace invalid ones
                            foreach ($product_ids as $product_id) {
                                $product_id = absint($product_id);
                                if (empty($product_id)) {
                                    continue;
                                }

                                $product = wc_get_product($product_id);

                                // Check if product exists and is published
                                if ($product && is_a($product, 'WC_Product') && $product->get_status() === 'publish') {
                                    $valid_product_ids[] = $product_id;
                                } else {
                                    // Replace invalid ID with a valid one from available products
                                    if (!empty($available_product_ids) && $available_index < count($available_product_ids)) {
                                        // Skip if the replacement ID is already in valid list
                                        while ($available_index < count($available_product_ids)) {
                                            $replacement_id = absint($available_product_ids[$available_index]);
                                            if (!in_array($replacement_id, $valid_product_ids, true)) {
                                                $replacement_product = wc_get_product($replacement_id);
                                                if ($replacement_product && is_a($replacement_product, 'WC_Product') && $replacement_product->get_status() === 'publish') {
                                                    $valid_product_ids[] = $replacement_id;
                                                    $available_index++;
                                                    break;
                                                }
                                            }
                                            $available_index++;
                                        }
                                    }
                                }
                            }

                            $product_ids = $valid_product_ids;
                            foreach ($product_ids as $product_id) {
                                $marker_product_id = absint($product_id);
                                if (empty($marker_product_id)) {
                                    continue;
                                }

                                $product_id = absint($product_id);

                                $product = wc_get_product($marker_product_id);
                                if (!$product || !is_a($product, 'WC_Product')) {
                                    continue;
                                }

                                $product_title = get_the_title($product_id);
                                $product_url = get_permalink($product_id);
                                $product_price = wc_price($product->get_price());
                                $product_type = $product->get_type();
                                $product_thumbnail = $this->get_product_image($product_id);

                        ?>

                                <div class="product-hover-cards">
                                    <?php if (!empty($product_thumbnail)) { ?>
                                        <div class="product-hover-cards-image">
                                            <a href="<?php echo esc_url($product_url); ?>">
                                                <img src="<?php echo esc_url($product_thumbnail); ?>" alt="<?php echo esc_attr($product_title); ?>">
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="product-hover-cards-content">
                                        <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_title); ?></a>
                                        <h4><?php echo wp_kses_post($product_price); ?></h4>
                                    </div>
                                </div>

                        <?php
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                <?php
                return;
            }

                ?>
                <div class="vp-iconic-main-container">
                    <div class="vp-iconic-image-container">
                        <?php
                        if (!empty($main_image)) {
                        ?>
                            <img src="<?php echo esc_url($main_image); ?>" alt="image" class="vp-iconic-image">
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                    if (!empty($settings['product_markers'])) {
                        $all_product_ids = array_keys(value_pack_get_woocommerce_products());
                        // cwp_pre($all_product_ids)
                        foreach ($settings['product_markers'] as $_K =>  $marker) {
                            $marker_product_id = absint($marker['product_id']);
                            $marker_type = $marker['marker_type'];
                            if ($marker_type == 'product') {
                                if (empty($marker_product_id) && !empty($all_product_ids)) {
                                    $marker_product_id = $all_product_ids[array_rand($all_product_ids)]; // Assign a random product ID
                                }
                                if (empty($marker_product_id)) {
                                    continue;
                                }
                                $marker_position_left = $marker['position_left']['size'];
                                $marker_position_top = $marker['position_top']['size'];
                                $product_id = absint($marker_product_id);
                                $product_position = $marker['product_position'];
                                $product = wc_get_product($marker_product_id);
                                if (!$product || !is_a($product, 'WC_Product')) {
                                    continue;
                                }

                                $product_title = get_the_title($product_id);
                                $product_url = get_permalink($product_id);
                                $product_price = wc_price($product->get_price());
                                $product_type = $product->get_type();
                                $product_thumbnail = $this->get_product_image($product_id);
                                $product_position = isset($product_position) ? $product_position : 'left';
                                if (wp_is_mobile()) {
                                    $position_style = ($product_position === 'left')
                                        ? 'left: -12px; right: unset;  transform-origin: left center;'
                                        : 'right: -12px; left: unset; transform-origin: right center;';
                                } else {
                                    $position_style = ($product_position === 'left')
                                        ? 'left: 50px; right: unset;  transform-origin: left center;'
                                        : 'right: 50px; left: unset; transform-origin: right center;';
                                }

                    ?>

                                <div class="wc-woo-icon-products <?php echo esc_attr($icon_box_style); ?>" value-pack-data-slide-depends="<?php echo esc_attr($product_id); ?>" style="position: absolute;left: <?php echo esc_attr($marker_position_left); ?>%; top: <?php echo esc_attr($marker_position_top); ?>%;">
                                    <div class="vp-elementor-icon">
                                        <?php Icons_Manager::render_icon($marker['marker_icon'], ['aria-hidden' => 'true']); ?>
                                    </div>
                                    <div class="product-hover-cards" style="<?php echo esc_attr($position_style); ?>">
                                        <div class="product-hover-cards-image">
                                            <a href="<?php echo esc_url($product_url); ?>">
                                                <img src="<?php echo esc_url($product_thumbnail); ?>" alt="<?php echo esc_attr($product_title); ?>">
                                            </a>
                                        </div>
                                        <div class="product-hover-cards-content">
                                            <p><?php echo esc_html($product_type); ?></p>
                                            <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_title); ?></a>
                                            <h4><?php echo wp_kses_post($product_price); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {


                                $marker_position_left = $marker['position_left']['size'];
                                $marker_position_top = $marker['position_top']['size'];
                                $product_position = $marker['product_position'];
                                $custom_card_icon = $marker['custom_card_icon'];
                                $custom_card_description = $marker['custom_card_description'];
                                $custom_card_title = $marker['custom_card_title'];


                                $product_position = isset($product_position) ? $product_position : 'left';
                                if (wp_is_mobile()) {
                                    $position_style = ($product_position === 'left')
                                        ? 'left: -12px; right: unset;  transform-origin: left center;'
                                        : 'right: -12px; left: unset; transform-origin: right center;';
                                } else {
                                    $position_style = ($product_position === 'left')
                                        ? 'left: 50px; right: unset;  transform-origin: left center;'
                                        : 'right: 50px; left: unset; transform-origin: right center;';
                                }

                            ?>

                                <div class="wc-woo-icon-products <?php echo esc_attr($icon_box_style); ?>" style="position: absolute;left: <?php echo esc_attr($marker_position_left); ?>%; top: <?php echo esc_attr($marker_position_top); ?>%;">
                                    <div class="vp-elementor-icon">
                                        <?php Icons_Manager::render_icon($marker['marker_icon'], ['aria-hidden' => 'true']); ?>
                                    </div>
                                    <div class="product-hover-cards" style="<?php echo esc_attr($position_style); ?>">
                                        <div class="product-hover-cards-image">
                                            <?php Icons_Manager::render_icon($custom_card_icon, ['aria-hidden' => 'true']); ?>
                                        </div>
                                        <div class="product-hover-cards-content">
                                            <?php
                                            if (!empty($custom_card_title)) {
                                            ?>
                                                <a><?php echo esc_attr($custom_card_title); ?></a>
                                            <?php
                                            }
                                            if (!empty($custom_card_description)) {
                                            ?>
                                                <h4><span><?php echo esc_attr($custom_card_description); ?></span></h4>
                                            <?php
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>


        <?php
    }


    /**
     * Get product image URL
     *
     * @param int $product_id Product ID
     * @return string Image URL
     * @since 1.0.0
     * @access private
     */
    private function get_product_image($product_id)
    {
        if (has_post_thumbnail($product_id) && get_the_post_thumbnail_url($product_id)) {
            return esc_url(get_the_post_thumbnail_url($product_id));
        }
        return esc_url('https://placehold.co/800?text=Placeholder');
    }
}
