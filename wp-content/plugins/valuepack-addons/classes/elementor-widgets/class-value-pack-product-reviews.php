<?php

/**
 * Product Reviews Widget for Value Pack
 * 
 * @package ValuePack
 * @since 1.0.0
 **/

defined('ABSPATH') || exit;

// Check requirements
if (
    !class_exists('Elementor\Widget_Base') ||
    !function_exists('wc_get_product') ||
    !function_exists('comments_template')
) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class Value_Pack_Product_Reviews extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_product_reviews';
    }

    public function get_title()
    {
        return __('Product Reviews', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-product-rating vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Reviews Content', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'reviews_section_title',
            [
                'label' => __('Reviews Section Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Product ID', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => value_pack_get_any_one_product_id(),
                'description' => __('Enter the product ID if not on a single product page.', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'review_style',
            [
                'label'   => __('Select Review Style', 'valuepack-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'style_1' => __('Style 1', 'valuepack-addons'),
                    'style_2' => __('Style 2', 'valuepack-addons'),
                ],
                'default' => 'style_1',
            ]
        );

        $this->add_control(
            'show_reviews',
            [
                'label' => __('Product Reviews', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'valuepack-addons'),
                'label_off' => __('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_overall_rating',
            [
                'label' => __('Overall Product Rating', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'valuepack-addons'),
                'label_off' => __('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_product_review_form',
            [
                'label' => __('Product Review Form', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'valuepack-addons'),
                'label_off' => __('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'product_review_form_title',
            [
                'label' => __('Reviews Form Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'product_review_form_desc',
            [
                'label' => __('Reviews Form Description', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'rows' => 4,
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'reviews_title_style',
            [
                'label' => __('Reviews Title', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'reviews_title_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-reviews-section-title h4' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviews_title_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-reviews-section-title h4',
            ]
        );

        $this->add_control(
            'reviews_title_alignment',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-reviews-section-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reviews_title_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-reviews-section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reviews_title_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-reviews-section-title h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'reviews_form_style',
            [
                'label' => __('Reviews Form', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_text_alignment',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-reviews-form-heading' => 'text-align: {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_title',
            [
                'label' => __('Reviews Form Title', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_title_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-review-form-title' => 'color: {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviews_form_title_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .product-review-form-title',
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_title_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .product-review-form-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_title_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .product-review-form-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_desc',
            [
                'label' => __('Reviews Form Description', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_desc_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-review-form-desc' => 'color: {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviews_form_desc_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .product-review-form-desc',
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_desc_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .product-review-form-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_desc_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .product-review-form-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        // New controls for the submit button
        $this->add_control(
            'reviews_form_button_heading',
            [
                'label' => __('Reviews Form Button', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->start_controls_tabs(
            'reviews_form_button_tabs',
            [
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

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
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a,{{WRAPPER}} .vp-overall-rating .write-review-btn a svg path' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviews_form_button_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit ,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a',
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_button_background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'background-color: {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'reviews_form_button_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a',
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->end_controls_tab();

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
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover  svg path' => 'color: {{VALUE}};fill:  {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_button_hover_background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_control(
            'reviews_form_button_hover_border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'reviews_form_button_hover_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-Reviews #review_form #respond form .form-submit .submit:hover,{{WRAPPER}} .vp-product-reviews-container .vp-overall-rating .write-review-btn a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_product_review_form' => 'yes'],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

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
        $style_class = $settings['review_style'] === 'style_1' ? 'style-1' : 'style-2';
        $show_product_review_form = $settings['show_product_review_form'] === 'yes';


        if (!$product_id || !$product || $product->get_status() !== 'publish') {
            return;
        }

        $reviews = get_comments([
            'post_id' => $product_id,
            'status'  => 'approve',
            'type'    => 'review',
        ]);
        $has_reviews = !empty($reviews);
        $show_overall_rating = $settings['show_overall_rating'] === 'yes';
        $show_reviews = $settings['show_reviews'] === 'yes';

        if ($has_reviews || $show_product_review_form) { ?>
            <div class="vp-product-reviews-main-container-<?php echo esc_attr($style_class); ?>">
                <?php if ($has_reviews && ($show_overall_rating || $show_reviews)) { ?>
                    <div class="vp-product-reviews-container <?php echo esc_attr($style_class); ?>">
                        <?php if (!empty($settings['reviews_section_title'])) { ?>
                            <div class="vp-reviews-section-title">
                                <h4><?php echo esc_html($settings['reviews_section_title']); ?></h4>
                            </div>
                        <?php }

                        if ($show_overall_rating) { ?>
                            <div class="vp-overall-rating">
                                <?php
                                $ratings_count = array_fill(1, 5, 0);
                                $total_reviews = $product->get_rating_count();

                                $comments = get_comments([
                                    'post_id' => $product_id,
                                    'status'  => 'approve',
                                    'type'    => 'review',
                                ]);

                                foreach ($comments as $comment) {
                                    $rating = (int) get_comment_meta($comment->comment_ID, 'rating', true);
                                    if ($rating >= 1 && $rating <= 5) {
                                        $ratings_count[$rating]++;
                                    }
                                }
                                ?>
                                <div class="vp-overall-rating-col-1">
                                    <?php for ($i = 5; $i >= 1; $i--):
                                        $percentage = ($total_reviews > 0) ? ($ratings_count[$i] / $total_reviews) * 100 : 0;
                                    ?>
                                        <div class="vp-overall-rating-row">
                                            <span class="vp-overall-star-label"><?php echo esc_html($i); ?> Star</span>
                                            <div class="vp-overall-progress-bar">
                                                <div class="vp-overall-progress-fill" style="width: <?php echo esc_attr($percentage); ?>%;"></div>
                                            </div>
                                            <span class="vp-overall-rating-count"><?php echo esc_html($ratings_count[$i]); ?></span>
                                        </div>
                                    <?php endfor; ?>
                                </div>

                                <div class="vp-overall-rating-col-2">
                                    <h6 class="vp-overall-rating-text">
                                        <?php
                                        $average_rating = $product->get_average_rating();
                                        echo esc_html(number_format($average_rating, 1));
                                        ?>
                                        <span> out of 5 stars </span>
                                    </h6>

                                    <div class="vp-overall-rating-stars">
                                        <?php
                                        $rating = round($average_rating);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<div class="star filled"><i class="fa-solid fa-star"></i></div>';
                                            } else {
                                                echo '<div class="star empty"><i class="fa-regular fa-star"></i></div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="write-review-btn">
                                        <a href="#vp-product-reviews">WRITE A REIVEW
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                                                <path d="M10.1715 0.330811H2.07559C1.95956 0.330811 1.84828 0.376904 1.76624 0.458951C1.68419 0.540998 1.63809 0.652278 1.63809 0.768311C1.63809 0.884343 1.68419 0.995623 1.76624 1.07767C1.84828 1.15972 1.95956 1.20581 2.07559 1.20581H9.31359L0.336532 10.1833C0.293136 10.2233 0.258264 10.2716 0.234012 10.3253C0.20976 10.3791 0.196628 10.4372 0.195406 10.4962C0.194185 10.5551 0.204898 10.6137 0.226903 10.6684C0.248907 10.7232 0.281749 10.7729 0.323453 10.8146C0.365158 10.8563 0.414864 10.8891 0.469584 10.9111C0.524304 10.9331 0.582909 10.9438 0.641875 10.9426C0.700841 10.9414 0.758951 10.9283 0.812713 10.904C0.866475 10.8798 0.914778 10.8449 0.954719 10.8015L9.93222 1.824V9.06244C9.93222 9.17847 9.97831 9.28975 10.0604 9.37179C10.1424 9.45384 10.2537 9.49994 10.3697 9.49994C10.4858 9.49994 10.597 9.45384 10.6791 9.37179C10.7611 9.28975 10.8072 9.17847 10.8072 9.06244V0.966936C10.807 0.798371 10.74 0.636771 10.6208 0.517537C10.5017 0.398303 10.3401 0.331158 10.1715 0.330811Z" fill="#1D1D1D" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        if ($show_reviews) { ?>
                            <div class="vp-product-reviews">
                                <?php foreach ($reviews as $review) { ?>
                                    <div class="vp-review">
                                        <div class="vp-review-admin">
                                            <div class="vp-review-rating">
                                                <?php
                                                $rating = get_comment_meta($review->comment_ID, 'rating', true);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $rating) {
                                                        echo '<div class="star filled"><i class="fa-solid fa-star"></i></div>';
                                                    } else {
                                                        echo '<div class="star empty"><i class="fa-regular fa-star"></i></div>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <p class="vp-review-date"><?php echo esc_html(get_comment_date('M d, Y', $review)); ?></p>
                                            <h4 class="vp-review-author-name"><?php echo esc_html($review->comment_author); ?></h4>
                                        </div>
                                        <div class="vp-review-content">
                                            <p class="vp-review-text"><?php echo esc_html($review->comment_content); ?></p>
                                            <button class="vp-review-show-more"><span class="btn-text">More Detail</span> <i class="fa-solid fa-chevron-down"></i></button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php }

                if ($show_product_review_form) { ?>
                    <div id="vp-product-reviews" class="vp-product-reviews-form-container <?php echo esc_attr($style_class); ?>">
                        <div class="vp-product-reviews-form-heading">
                            <?php if (!empty($settings['product_review_form_title'])) { ?>
                                <h4 class="product-review-form-title"><?php echo esc_html($settings['product_review_form_title']); ?></h4>
                            <?php }
                            if (!empty($settings['product_review_form_desc'])) { ?>
                                <p class="product-review-form-desc"><?php echo esc_html($settings['product_review_form_desc']); ?></p>
                            <?php } ?>
                        </div>
                        <?php if (comments_open()) { ?>
                            <div id="reviews" class="woocommerce-Reviews vp-product-reviews-form">
                                <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
                                    <div id="review_form_wrapper">
                                        <div id="review_form">
                                            <?php
                                            $commenter    = wp_get_current_commenter();
                                            $name_email_required = (bool) get_option('require_name_email', 1);

                                            $comment_form = array(
                                                'title_reply'         => esc_html__('Add a review', 'valuepack-addons'),
                                                /* translators: %s: reply author name */
                                                'title_reply_to'      => esc_html__('Leave a Reply to %s', 'valuepack-addons'),
                                                'title_reply_before'  => '<span id="reply-title" class="comment-reply-title" role="heading" aria-level="3">',
                                                'title_reply_after'   => '</span>',
                                                'label_submit'        => esc_html__('Submit', 'valuepack-addons'),
                                                'logged_in_as'        => '',
                                                'comment_notes_after' => '',
                                                'fields'              => array(
                                                    'author' => '<p class="comment-form-author">
                                                <label for="author">' . __('Name', 'valuepack-addons') . '&nbsp;<span class="required">*</span></label>
                                                <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" required />
                                            </p>',
                                                    'email' => '<p class="comment-form-email">
                                                <label for="email">' . __('Email', 'valuepack-addons') . '&nbsp;<span class="required">*</span></label>
                                                <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" required />
                                            </p>',
                                                ),
                                                'comment_field'       => '<div class="comment-form-rating">
                                            <label for="rating">' . esc_html__('Overall rating', 'valuepack-addons') . '&nbsp;<span class="required">*</span></label>
                                            <select name="rating" id="rating" required>
                                                <option value="">' . esc_html__('Rate&hellip;', 'valuepack-addons') . '</option>
                                                <option value="5">' . esc_html__('Perfect', 'valuepack-addons') . '</option>
                                                <option value="4">' . esc_html__('Good', 'valuepack-addons') . '</option>
                                                <option value="3">' . esc_html__('Average', 'valuepack-addons') . '</option>
                                                <option value="2">' . esc_html__('Not that bad', 'valuepack-addons') . '</option>
                                                <option value="1">' . esc_html__('Very poor', 'valuepack-addons') . '</option>
                                            </select>
                                        </div>
                                        <p class="comment-form-comment">
                                            <label for="comment">' . esc_html__('Review', 'valuepack-addons') . '&nbsp;<span class="required">*</span></label>
                                            <textarea id="comment" name="comment" cols="45" rows="6" required></textarea>
                                        </p>',
                                            );

                                            comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
                                            ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <p class="woocommerce-verification-required"><?php esc_html_e('Only logged in customers who have purchased this product may leave a review.', 'valuepack-addons'); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php }
                        ?>
                    </div>
                <?php } ?>
            </div>
<?php }
    }
}
