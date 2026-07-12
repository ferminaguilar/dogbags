<?php

/**
 * Product Short Description Widget for Value Pack
 * 
 * @package ValuePack
 * @since 1.0.0
 * 
 * Displays a product's short description with style controls.
 * Requires WooCommerce and Elementor to be active.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Namespace imports
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Value_Pack_Product_Short_Description extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_product_description';
    }

    public function get_title()
    {
        return __('Product Short Description', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-product-description vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    public function get_keywords()
    {
        return ['product', 'description', 'short description', 'woocommerce', 'value pack'];
    }

    protected function _register_controls()
    {
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
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => value_pack_get_any_one_product_id(),
                'description' => __('Add product id to the the specfic description of product', 'valuepack-addons'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('desc_style', [
            'label' => esc_html__('Description Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-description p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-description p',
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__('Text Align', 'valuepack-addons'),
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
                    'justify' => [
                        'title' => esc_html__('Justify', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-description p' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Get and validate product ID
      
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

        // Get and validate product
        $product = wc_get_product($product_id);
        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }

        // Get and sanitize description
        $description = $product->get_short_description();
        if (!empty($description)) { ?>
            <div class="vp-product-description-container">
                <div class="vp-product-description">
                    <p><?php echo wp_kses_post($description); ?></p>
                </div>
            </div>
<?php }
    }
}

?>