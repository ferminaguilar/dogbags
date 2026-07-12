<?php

/**
 * Product Rating Widget for Value Pack
 * 
 * @package ValuePack
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Value_Pack_Product_Rating extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_product_rating';
    }

    public function get_title()
    {
        return __('Product Rating', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-product-rating vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    public function get_keywords()
    {
        return ['product', 'rating', 'star', 'review', 'reviews', 'feedback', 'WooCommerce', 'stars'];
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
                'default' => '',
                'default' => value_pack_get_any_one_product_id(),
                'description' => __('If this is not single page then write product ID', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'show_review_count',
            [
                'label' => __('Show Review Count', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'valuepack-addons'),
                'label_off' => __('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_stars',
            [
                'label' => __('Stars', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'stars_size',
            array(
                'label'      => __('Size', 'valuepack-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em'),
                'default'    => array(
                    'size' => 12,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .vp-product-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            'stars_letter_spacing',
            [
                'label' => __('Letter Spacing', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em'),
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .star-rating::before, {{WRAPPER}} .star-rating span::before' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'stars_color',
            array(
                'label'     => __('Color', 'valuepack-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1d1d1d',
                'selectors' => array(
                    '{{WRAPPER}} .star-rating span:before' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'stars_background_color',
            array(
                'label'     => __('Background Color', 'valuepack-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1d1d1d',
                'selectors' => array(
                    '{{WRAPPER}} .star-rating:before' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_text',
            [
                'label' => __('Text', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'review_text_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-product-review-count',


            ]
        );

        $this->add_control(
            'review_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-review-count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control('review_text_padding', [
            'label'      => esc_html__('Padding', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'default'    => [
                'top'    => 2,
                'right'  => 5,
                'bottom' => 0,
                'left'   => 5,
            ],
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .vp-product-review-count' =>  'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display(); 
        $show_review_count = isset($settings['show_review_count']) ? $settings['show_review_count'] : 'yes';

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
            echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }

        $product = wc_get_product($product_id);

        if (!$product) {
            echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }

        $rating = $product->get_average_rating();
        $review_count = $product->get_review_count();

        if (!wc_review_ratings_enabled()) {
            echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }

        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average_rating = $product->get_average_rating();

        if (!$rating_count || !$review_count || !$average_rating) {
            echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }
?>
        <div class="vp-product-rating-container">
            <div class="vp-product-rating">
                <?php if ($rating_count > 0) {
                ?>
                    <div class="star-rating">
                        <span style="width:<?php echo esc_attr((($average_rating / 5) * 100)); ?>%">
                            <span class="rating"></span>
                        </span>
                    </div>
                <?php
                }  ?>
            </div>
            <?php if ($show_review_count === 'yes' && $review_count > 0): ?>
                <p class="vp-product-review-count">
                    <?php
                    if ($review_count === 1) {
                        echo esc_html__('1 review', 'valuepack-addons');
                    } else {
                        /* translators: %d: review count */
                        echo sprintf(esc_html__('%d reviews', 'valuepack-addons'), esc_html($review_count));
                    }
                    ?>
                </p>
            <?php endif; ?>
        </div>
<?php
    }
}
