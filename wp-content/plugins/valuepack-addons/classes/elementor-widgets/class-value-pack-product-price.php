<?php
/**
 * Value Pack Product Price Widget
 * 
 * @package ValuePack
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * WooCommerce Product Price Widget for Elementor
 *
 * @since 1.0.0
 */
class Value_Pack_Product_Price extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_product_price';
    }

    public function get_title()
    {
        return __('Product Price', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-product-price vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    public function get_keywords()
    {
        return ['product', 'price', 'sale', 'discount', 'woocommerce'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Product Price Settings', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Product ID', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
               'default' => value_pack_get_any_one_product_id(),
                'description' => __('If this is not single page then write product ID', 'valuepack-addons'),
            ]
        );

        $this->add_control('show_regular_price', [
            'label'        => esc_html__('Show Regular Price', 'valuepack-addons'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
        ]);

        $this->add_control('show_sale_price', [
            'label'        => esc_html__('Show Sale Price', 'valuepack-addons'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
        ]);

        $this->add_control('show_discount', [
            'label'        => esc_html__('Show Discount', 'valuepack-addons'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
        ]);

        $this->add_control('discount_type', [
            'label'       => esc_html__('Discount Type', 'valuepack-addons'),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'percentage',
            'options'     => [
                'percentage' => esc_html__('Percentage', 'valuepack-addons'),
                'fixed'      => esc_html__('Fixed Value', 'valuepack-addons'),
            ],
            'condition'   => ['show_discount' => 'yes'],
        ]);

        $this->add_control('before_discount_text', [
            'label'       => esc_html__('Before Discount Text', 'valuepack-addons'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Save', 'valuepack-addons'),
            'condition'   => ['show_discount' => 'yes'],
        ]);

        $this->add_control('after_discount_text', [
            'label'       => esc_html__('After Discount Text', 'valuepack-addons'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('%', 'valuepack-addons'),
            'condition'   => ['show_discount' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('layout_style', [
            'label' => esc_html__('Layout Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'flex_directions',
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
                    '{{WRAPPER}} .vp-product-price' => 'display:flex;flex-direction: {{VALUE}};',
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
                    '{{WRAPPER}} .vp-product-price' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gap_between_items',
            [
                'label' => esc_html__('Gap Between Items', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                 'default' => [
                    'size' => 10,
                    'unit' => 'px',
                 ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-product-price' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('regular_price_style', [
            'label' => esc_html__('Regular Price Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'regular_price_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-regular-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'r_price_bg_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-product-regular-price' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'regular_price_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-regular-price',  
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 13,
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '500',
                    ],
                    'line_height' => [
                        'default' => [
                            'size' => 1.5,
                            'unit' => 'em',
                        ],
                    ], 
                    'text_decoration' => [
                        'default' => 'line-through',
                    ], 
                    'font-family' => [
                        'default' => 'plus jakarta sans',
                    ],
                ],
            ]
        );
      

        $this->add_responsive_control('r_price_border_radius', [
            'label'      => esc_html__('Border Radius', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-regular-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('regular_price_padding', [
            'label'      => esc_html__('Padding', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-regular-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('regular_price_margin', [
            'label'      => esc_html__('Margin', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-regular-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('sale_price_style', [
            'label' => esc_html__('Sale Price Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            's_price_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-sale-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            's_price_bg_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-product-sale-price' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 's_price_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-sale-price',
            ]
        );

        $this->add_responsive_control('s_price_border_radius', [
            'label'      => esc_html__('Border Radius', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-sale-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('sale_price_padding', [
            'label'      => esc_html__('Padding', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-sale-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('sale_price_margin', [
            'label'      => esc_html__('Margin', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-sale-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('discount_style', [
            'label' => esc_html__('Discount Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'discount_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-discount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'discount_text_bg_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-product-discount' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'discount_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-discount',
            ]
        );

        $this->add_responsive_control('discount_border_radius', [
            'label'      => esc_html__('Border Radius', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-discount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('discount_padding', [
            'label'      => esc_html__('Padding', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-discount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('discount_margin', [
            'label'      => esc_html__('Margin', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'selectors'  => ['{{WRAPPER}} .vp-product-discount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend
     */
    protected function render()  
    {
        $settings = $this->get_settings_for_display();
        
        // Get product ID with validation
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
        
        $product = wc_get_product($product_id);

        if(!$product){
            return;
        }

        // Check if the product is variable
        if ($product->is_type('variable')) {
            $available_variations = $product->get_available_variations();
            $prices               = [];

            foreach ($available_variations as $variation) {
                $variation_product = wc_get_product($variation['variation_id']);
                if ($variation_product) {
                    $prices[] = [
                        'regular' => $variation_product->get_regular_price(),
                        'sale'    => $variation_product->get_sale_price(),
                    ];
                }
            }

            // Extract min/max regular and sale prices
            // Extract min/max regular and sale prices
            $regular_prices = array_column($prices, 'regular');
            $sale_prices    = array_column($prices, 'sale');

            if (!empty($regular_prices) && is_array($regular_prices)) {
                $filtered_regular_prices = array_filter($regular_prices); // Remove empty or invalid values
                if (!empty($filtered_regular_prices)) {
                    $min_regular_price = min($filtered_regular_prices);
                    $max_regular_price = max($filtered_regular_prices);
                } else {
                    $min_regular_price = $max_regular_price = null; // Default if no valid regular prices
                }
            } else {
                $min_regular_price = $max_regular_price = null; // Default if no regular prices array
            }

            if (!empty($sale_prices) && is_array($sale_prices)) {
                $filtered_sale_prices = array_filter($sale_prices); // Remove empty or invalid values
                if (!empty($filtered_sale_prices)) {
                    $min_sale_price = min($filtered_sale_prices);
                    $max_sale_price = max($filtered_sale_prices);
                } else {
                    $min_sale_price = $max_sale_price = null; // Default if no valid sale prices
                }
            } else {
                $min_sale_price = $max_sale_price = null; // Default if no sale prices array
            }

        } else {
            // For simple products
            $min_regular_price   = $product->get_regular_price();
            $min_sale_price      = $product->get_sale_price();
        }


        // Calculate Discount
        $discount_value = 0;
        if ($min_regular_price && $min_sale_price) {
            if ($settings['discount_type'] === 'percentage') {
                $discount_value = round((($min_regular_price - $min_sale_price) / $min_regular_price) * 100);
            } else {
                $discount_value = wc_price($min_regular_price - $min_sale_price);
            }
        }

        if ($product && ($settings['show_regular_price'] === 'yes' || $settings['show_sale_price'] === 'yes' || $settings['show_discount'] === 'yes')) {

        echo '<div class="vp-product-price-wrapper" data-nonce="'.esc_attr(wp_create_nonce('vp_product_price_nonce')).'">';
        if ($product && ($settings['show_regular_price'] === 'yes' || $settings['show_sale_price'] === 'yes')) {
            echo '<div class="vp-product-price" style="display: flex;align-items: center;">';

            // Regular Price
            if ('yes' === $settings['show_regular_price'] && $min_regular_price) {
                $regular_html = '<span class="vp-product-regular-price">' . wp_kses_post(wc_price($min_regular_price));
                
                if ($product->is_type('variable') && $max_regular_price > $min_regular_price) {
                    $regular_html .= ' - ' . wp_kses_post(wc_price($max_regular_price));
                }
                
                $regular_html .= '</span> ';
                echo wp_kses_post($regular_html);
            }

            // Sale Price
            if ('yes' === $settings['show_sale_price']) {
                $sale_html = '';
                
                if ($min_sale_price) {
                    $sale_html = '<span class="vp-product-sale-price">' . wp_kses_post(wc_price($min_sale_price));
                    
                    if ($product->is_type('variable') && $max_sale_price > $min_sale_price) {
                        $sale_html .= ' - ' . wp_kses_post(wc_price($max_sale_price));
                    }
                    
                    $sale_html .= '</span> ';
                } elseif ($min_regular_price) {
                    $sale_html = '<span class="vp-product-sale-price">' . wp_kses_post(wc_price($min_regular_price));
                    
                    if ($product->is_type('variable') && $max_regular_price > $min_regular_price) {
                        $sale_html .= ' - ' . wp_kses_post(wc_price($max_regular_price));
                    }
                    
                    $sale_html .= '</span> ';
                }
                
                if ($sale_html) {
                    echo wp_kses_post($sale_html);
                }
            }

            // Discount Percentage
            if ('yes' === $settings['show_discount'] && $discount_value > 0) {
                printf(
                    '<span class="vp-product-discount">%s %s%s</span>',
                    esc_html($settings['before_discount_text']),
                    esc_html($discount_value),
                    esc_html($settings['after_discount_text'])
                );
            }

            echo '</div>';
        }
        echo '</div>';
        }
    }

    public function get_price($product_id)
    {
        // Retrieve the product object
        $product = wc_get_product($product_id);

        // Check if the product exists
        if (!$product) {
            return false; // Return false if the product is invalid
        }

        ob_start();

        // Check if the product is a variable product
        if ($product->is_type('variable')) {
            $available_variations = $product->get_available_variations();

            // Loop through variations and return the first sale price found
            foreach ($available_variations as $variation) {
                $variation_id = $variation['variation_id'];
                $variation_product = new WC_Product_Variation($variation_id);
                $sale_price = $variation_product->get_sale_price();

                if ($sale_price) {
                    return wc_price($sale_price);
                }
            }
        } else {
            // For simple products, return the sale price
            $sale_price = $product->get_regular_price();
            if ($sale_price) {
                return wc_price($sale_price);
            }
        }

        return false; // Return false if no sale price is found
    }
}
