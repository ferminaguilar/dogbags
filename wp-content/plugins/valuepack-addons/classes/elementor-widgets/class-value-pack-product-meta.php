<?php
/**
 * Value Pack Product Meta Widget
 * 
 * Displays product SKU, categories and tags with flexible styling options
 * 
 * @package ValuePack
 * @since 1.0.0
 * 
 * @requires Elementor
 * @requires WooCommerce
 */

defined('ABSPATH') || exit;

// Check for required plugins
if (!class_exists('Elementor\Widget_Base') || !function_exists('wc_get_product')) {
    return;
}

// Import dependencies
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Product Meta Widget
 */
class Value_Pack_Product_Meta extends Value_Pack_Widget_Base
{

    /**
     * Retrieve widget name
     *
     * @return string Widget name
     */
    public function get_name()
    {
        return 'vp_product_meta';
    }

    /**
     * Retrieve widget title
     *
     * @return string Widget title
     */
    public function get_title()
    {
        return esc_html__('Product Meta', 'valuepack-addons');
    }

    /**
     * Retrieve widget icon
     *
     * @return string Widget icon  
     */
    public function get_icon()
    {
        return 'eicon-product-meta vpack-icon';
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

    /**
     * Retrieve widget keywords
     * 
     * @return array Widget keywords
     */
    public function get_keywords()
    {
        return ['elementor', 'woocommerce', 'product', 'meta', 'sku', 'categories', 'tags'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Settings', 'valuepack-addons'),
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

        $this->add_control(
            'show_sku',
            [
                'label' => __('Show SKU', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label' => __('Show Categories', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_tags',
            [
                'label' => __('Show Tags', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('meta_style', [
            'label' => esc_html__('Meta Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'meta_row_styles',
            [
                'label' => esc_html__('Meta Row', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_row_flex_direction',
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
                    '{{WRAPPER}} .vp-product-meta' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_row_justify_content',
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
                    '{{WRAPPER}} .vp-product-meta' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_row_align_items',
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
                    '{{WRAPPER}} .vp-product-meta' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_row_gap',
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
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-product-meta' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_column_styles',
            [
                'label' => esc_html__('Meta Column', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_column_flex_direction',
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
                    '{{WRAPPER}} .meta-colom' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_column_justify_content',
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
                    '{{WRAPPER}} .meta-colom' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_column_align_items',
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
                    '{{WRAPPER}} .meta-colom' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_column_gap',
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
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meta-colom' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_title_styles',
            [
                'label' => esc_html__('Title Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_title_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .meta-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_title_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .meta-title',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 13,
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '600',
                    ],
                ],
            ]
        );

        $this->add_control(
            'meta_value_styles',
            [
                'label' => esc_html__('Value Styles', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_value_color',
            [
                'label' => esc_html__('Value Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meta-value, .meta-value span, .meta-value span a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_value_typography',
                'label' => esc_html__('Value Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .meta-value, .meta-value span, .meta-value span a',
            ]
        );


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
        if (!$product) {
            return;
        }

        // Gather meta data
        $sku = $product->get_sku();
        $categories = wc_get_product_category_list(
            $product_id,
            ', ',
            '<span class="posted_in">',
            '</span>'
        );
        $tags = wc_get_product_tag_list(
            $product_id,
            ', ',
            '<span class="tagged_as">',
            '</span>'
        );

        // Only render if at least one meta field is enabled
        if (
            'yes' === $settings['show_sku'] ||
            'yes' === $settings['show_categories'] ||
            'yes' === $settings['show_tags']
        ) {

            echo '<div class="vp-product-meta" style="display: flex;">';

            if ('yes' === $settings['show_sku']) {
                $this->render_meta_field(
                    esc_html__('SKU:', 'valuepack-addons'),
                    esc_html($sku)
                );
            }

            if ('yes' === $settings['show_categories']) {
                $this->render_meta_field(
                    esc_html__('Categories:', 'valuepack-addons'),
                    wp_kses_post($categories)
                );
            }

            if ('yes' === $settings['show_tags']) {
                $this->render_meta_field(
                    esc_html__('Tags:', 'valuepack-addons'),
                    wp_kses_post($tags)
                );
            }

            echo '</div>';
        }
    }

    /**
     * Render individual meta field
     *
     * @param string $title Field title
     * @param string $value Field value (already escaped)
     */
    protected function render_meta_field($title, $value)
    {
        echo '<div class="meta-colom" style="display: flex;">
            <h2 class="meta-title">' . esc_html($title) . '</h2>
            <p class="meta-value">' . wp_kses_post($value) . '</p>
          </div>';
    }
}
