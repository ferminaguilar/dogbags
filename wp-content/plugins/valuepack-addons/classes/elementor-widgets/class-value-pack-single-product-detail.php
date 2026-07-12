<?php

/**
 * Single Product Details Widget for Elementor
 *
 * This Elementor widget displays WooCommerce single product information.
 *
 * @package     Value_Pack
 * @subpackage  Elementor Widgets
 * @author      Your Name
 * @since       1.0.0
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Elementor Widget: Value_Pack_Single_Product_Detail
 */
class Value_Pack_Single_Product_Detail extends Value_Pack_Widget_Base
{

    /**
     * Get widget name.
     */
    public function get_name()
    {
        return 'vp_single_product_detail';
    }

    /**
     * Get widget title.
     */
    public function get_title()
    {
        return esc_html__('Single Product Details', 'valuepack-addons');
    }

    /**
     * Get widget icon.
     */
    public function get_icon()
    {
        return 'eicon-product-info vpack-icon';
    }

    /**
     * Get widget categories.
     */
    public function get_categories()
    {
        return array('value_pack');
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords()
    {
        return array('product', 'woocommerce', 'shop', 'ecommerce', 'single product', 'product details', 'buy now');
    }

    /**
     * Register controls.
     */
    protected function register_controls()
    {

        $this->start_controls_section('product_settings', [
            'label' => esc_html__('Product Settings', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('product_id', [
            'type'        => Controls_Manager::NUMBER,
            'label'       => esc_html__('Product ID', 'valuepack-addons'),
            'description' => esc_html__('Enter the Product ID to display details.', 'valuepack-addons'),
            'placeholder' => '123',
            'default' => value_pack_get_any_one_product_id(),
        ]);

        $default_products = value_pack_get_woocommerce_products();
        $default_list = '';
        $count = 0;
        foreach ($default_products as $id => $name) {
            if ($count >= 5) break; // limit to 5 default items
            $default_list .= '<li class="vp-product-result-item" data-id="' . esc_attr($id) . '">' . esc_html($name) . '</li>';
            $count++;
        }
        $this->add_control(
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

        $this->add_control('show_product_type', [
            'label'   => esc_html__('Show Product Type', 'valuepack-addons'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_product_title', [
            'label'   => esc_html__('Show Product Title', 'valuepack-addons'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_product_price', [
            'label'   => esc_html__('Show Product Price', 'valuepack-addons'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_product_description', [
            'label'   => esc_html__('Show Product Short Description', 'valuepack-addons'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_section', [
            'label' => esc_html__('Style Settings', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_color', [
            'label'     => esc_html__('Title Color', 'valuepack-addons'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .vp-single-product-details .vp-product-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'label'    => __('Title Typography', 'valuepack-addons'),
            'selector' => '{{WRAPPER}} .vp-single-product-details .vp-product-title',
        ]);

        $this->add_control('price_color', [
            'label'     => esc_html__('Price Color', 'valuepack-addons'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .vp-single-product-details .vp-product-price .woocommerce-Price-amount' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'price_typography',
            'label'    => __('Price Typography', 'valuepack-addons'),
            'selector' => '{{WRAPPER}} .vp-single-product-details .vp-product-price .woocommerce-Price-amount',
        ]);

        $this->add_control('description_color', [
            'label'     => esc_html__('Short Description Color', 'valuepack-addons'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .vp-single-product-details .vp-product-short-description' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'description_typography',
            'label'    => __('Short Description Typography', 'valuepack-addons'),
            'selector' => '{{WRAPPER}} .vp-single-product-details .vp-product-short-description',
        ]);

        $this->end_controls_section();
        // Image Styling Section
        $this->start_controls_section('vp_product_image_style_section', [
            'label' => esc_html__('Product Image Styling', 'valuepack-addons'),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        // Enable Styling Toggle
        $this->add_control('enable_image_styling', [
            'label' => esc_html__('Enable Styling', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'valuepack-addons'),
            'label_off' => esc_html__('No', 'valuepack-addons'),
            'return_value' => 'yes',
            'default' => '',
        ]);

        // Border for Gallery List Images
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'gallery_list_image_border',
            'label' => esc_html__('Gallery Image Border', 'valuepack-addons'),
            'selector' => '{{WRAPPER}} .vp-product-gallery-main .vp-product-gallery .vp-product-gallery-list img',
            'condition' => ['enable_image_styling' => 'yes'],
        ]);

        $this->add_responsive_control('gallery_list_image_border_radius', [
            'label' => esc_html__('Gallery Image Border Radius', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .vp-product-gallery-main .vp-product-gallery .vp-product-gallery-list img' =>
                'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => ['enable_image_styling' => 'yes'],
        ]);
        $this->add_responsive_control('gallery_list_image_height', [
            'label' => esc_html__('Gallery Image Height', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vh'],
            'range' => [
                'px' => ['min' => 10, 'max' => 1000],
                '%'  => ['min' => 1, 'max' => 100],
                'vh' => ['min' => 1, 'max' => 100],
            ],
            'selectors' => [
                '{{WRAPPER}} .vp-product-gallery-main .vp-product-gallery .vp-product-gallery-list img' =>
                'height: {{SIZE}}{{UNIT}}  !important; object-fit: cover;',
            ],
            'condition' => ['enable_image_styling' => 'yes'],
        ]);

        // Border for Main Product Image
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'main_image_border',
            'label' => esc_html__('Main Image Border', 'valuepack-addons'),
            'selector' => '{{WRAPPER}} .vp-product-gallery-main .vp-product-image .vp-product-img',
            'condition' => ['enable_image_styling' => 'yes'],
        ]);

        $this->add_responsive_control('main_image_border_radius', [
            'label' => esc_html__('Main Image Border Radius', 'valuepack-addons'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .vp-product-gallery-main .vp-product-image .vp-product-img' =>
                'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => ['enable_image_styling' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    /**
     * Render widget output.
     */
    protected function render()
    {
        global $product;
       
        $settings = $this->get_settings_for_display();

        // new changes aded 
        $product_id = isset($settings['product_id']) ? absint($settings['product_id']) : 0;
        if ($product_id <= 0 || get_post_type($product_id) !== 'product' || get_post_status($product_id) !== 'publish') {
            $default_products = value_pack_get_woocommerce_products();
            if (!empty($default_products)) {
                $first_default_id = array_key_first($default_products);
                $product_id = absint($first_default_id);
            } else {
                return ;
            }
        }

        $product = wc_get_product($product_id); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
        if (!$product) { 
             value_pack_get_quoute_text_elementor_preview();
              return ;  
        }

        $postTitle = esc_html(get_the_title($product_id));
        $posturl = esc_url(get_permalink($product_id));
        $product_price = $product->get_price_html();
        $categories = wp_get_post_terms($product_id, 'product_cat');
        $product_type = esc_html(join(', ', wp_list_pluck($categories, 'name')));
        $short_description = esc_html(wp_trim_words($product->get_short_description(), 20));
        $checkout_url = esc_url(wc_get_checkout_url() . '?add-to-cart=' . $product_id);

        $gallery_ids = $product->get_gallery_image_ids();
        $main_image_id = $product->get_image_id();
        $main_image_url = $main_image_id ? wp_get_attachment_url($main_image_id) : '';

        echo '<div class="row vp-single-product-details-container">';

        echo '<div class="col-12 col-md-12 col-lg-7">';
        echo '<div class="vp-product-gallery-main">';
        echo '<div class="vp-product-gallery list-page">';
        if (!empty($gallery_ids)) {
            echo '<div class="vp-product-gallery-list">';
            foreach ($gallery_ids as $gallery_id) {
                $gallery_link = wp_get_attachment_url($gallery_id);
                echo '<img src="' . esc_url($gallery_link) . '" class="vp-product-img" alt="" />';
            }
            echo '</div>';
        }
        echo '</div>';

        echo '<div class="vp-product-image">';
        if (!empty($gallery_ids)) {
            echo '<div class="vp-product-image-list">';
            foreach ($gallery_ids as $image_id) {
                $image_link = wp_get_attachment_url($image_id);
                echo '<img src="' . esc_url($image_link) . '" class="vp-product-img" alt="" />';
            }
            echo '</div>';
        } elseif (!empty($main_image_url)) {
            echo '<div class="vp-product-single-image">';
            echo '<img src="' . esc_url($main_image_url) . '" class="" alt="" />';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="col-12 col-md-12 col-lg-5 col-xl-5 vp-single-product-details">';
        echo '<div class="vp-product-summary">';

        if ('yes' === $settings['show_product_type']) {
            echo '<p class="vp-product-type">' . esc_html($product_type) . '</p>';
        }

        if ('yes' === $settings['show_product_title']) {
            echo '<h2><a href="' . esc_url($posturl) . '" class="vp-product-title">' . esc_html($postTitle) . '</a></h2>';
        }

        if ('yes' === $settings['show_product_price']) {
            echo '<h5 class="vp-product-price">' . wp_kses_post($product_price) . '</h5>';
        }

        if ('yes' === $settings['show_product_description']) {
            echo '<p class="vp-product-short-description">' . wp_kses_post($short_description) . '</p>';
        }

        echo '<div class="vp-product-summary-head">';
        if (function_exists('value_pack_template_single_add_to_cart')) {
            value_pack_template_single_add_to_cart($product);
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
