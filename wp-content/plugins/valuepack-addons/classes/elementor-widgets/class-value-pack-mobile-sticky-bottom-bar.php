<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

class Value_Pack_Mobile_Sticky_Bottom_Bar extends Value_Pack_Widget_Base
{


    public function get_name()
    {
        return 'value-pack-mobile-sticky-bottom-bar';
    }

    public function get_title()
    {
        return __('VP Mobile Sticky Bottom Bar', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-navigator vpack-icon';
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
                'label' => __('Menu Items', 'valuepack-addons'),
            ]
        );

        // Home Item
        $this->add_control(
            'home_text',
            [
                'label' => __('Home Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Home', 'valuepack-addons'),
            ]
        );
        $this->add_control(
            'home_icon',
            [
                'label' => __('Home Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'solid',
                ],
            ]
        );

        // Cart Item
        $this->add_control(
            'cart_text',
            [
                'label' => __('Cart Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Cart', 'valuepack-addons'),
            ]
        );
        $this->add_control(
            'cart_icon',
            [
                'label' => __('Cart Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shopping-cart',
                    'library' => 'solid',
                ],
            ]
        );

        // Shop Item
        $this->add_control(
            'shop_text',
            [
                'label' => __('Shop Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Shop', 'valuepack-addons'),
            ]
        );
        $this->add_control(
            'shop_icon',
            [
                'label' => __('Shop Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-store',
                    'library' => 'solid',
                ],
            ]
        );

        // Wishlist Item
        $this->add_control(
            'wishlist_text',
            [
                'label' => __('Wishlist Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Wishlist', 'valuepack-addons'),
            ]
        );
        $this->add_control(
            'wishlist_icon',
            [
                'label' => __('Wishlist Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-heart',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'wishlist_link',
            [
                'label' => __('Wishlist Link', 'valuepack-addons'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'valuepack-addons'),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        // Login Item
        $this->add_control(
            'login_text',
            [
                'label' => __('Login Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Login', 'valuepack-addons'),
            ]
        );
        $this->add_control(
            'login_icon',
            [
                'label' => __('Login Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'login_link',
            [
                'label' => __('Login Page Link', 'valuepack-addons'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-login-page.com', 'valuepack-addons'),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'show_external' => true,
            ]
        );

        $this->end_controls_section();

        // Style Controls
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Bottom Bar Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'main_padding',
            [
                'label' => __('Main Padding', 'valuepack-addons'),
                'type'  => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '6',
                    'bottom' => '6',
                    'left' => '6',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-sticky-bottom-bar-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'bottom_bar_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'bottom_bar_padding',
            [
                'label'      => __('Padding', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '6',
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bottom_bar_border_radius',
            [
                'label'      => __('Border Radius', 'valuepack-addons'),

                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'bottom_bar_box_shadow',
                'label'    => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-mobile-sticky-bottom-bar',
                'fields_options' => [
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 10,
                            'vertical'   => -7,
                            'blur'       => 36,
                            'spread'     => -6,
                            'color'      => 'rgba(0, 0, 0, 0.22)',
                        ],
                    ],
                ],
            ]
        );
        $this->add_responsive_control(
            'bottom_bar_icon_size',
            [
                'label'      => __('Icon Size', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => ['min' => 10, 'max' => 100],
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'item_color',
            [
                'label' => __('Item Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-item a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-item i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'item_counter_text_color',
            [
                'label' => __('Item Counter Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-item .cart-item-icon .vp-cart-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_counter_bg_color',
            [
                'label' => __('Item Counter Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-item .cart-item-icon .vp-cart-count' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_typography',
                'label' => __('Item Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-item span',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_center_icon_style',
            [
                'label' => __('Center Icon Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'center_icon_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'center_icon_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '4',
                    'right' => '4',
                    'bottom' => '4',
                    'left' => '4',
                    'unit' => 'px',
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'center_item_typography',
                'label' => __('Center Item Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item span',
            ]
        );
        $this->add_responsive_control(
            'center_icon_size',
            [
                'label'      => __('Center Icon Size', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => ['min' => 10, 'max' => 150],
                ],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'center_icon_width',
            [
                'label'      => __('Center Icon Container Width', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 300],
                ],
                'default' => [
                    'size' => 70,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item' => 'width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'center_icon_height',
            [
                'label'      => __('Center Icon Container Height', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 300],
                ],
                'default' => [
                    'size' => 55,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item' => 'height: {{SIZE}}{{UNIT}};max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_color_center',
            [
                'label' => __('Item Color', 'valuepack-addons'),
                'default' => '#000000',
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'center_icon_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F0EAFF',
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item a' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'center_icon_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item a',
            ]
        );


        $this->add_responsive_control(
            'center_icon_translate_x',
            [
                'label' => __('Translate X', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 100, 'step' => 1],
                    '%' => ['min' => -100, 'max' => 100, 'step' => 1],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'center_icon_translate_y',
            [
                'label' => __('Translate Y', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 100, 'step' => 1],
                    '%' => ['min' => -100, 'max' => 100, 'step' => 1],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .vp-mobile-sticky-bottom-bar-center-item' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        if (!wp_is_mobile() && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        $settings = $this->get_settings_for_display();

        $home_text      = !empty($settings['home_text']) ? $settings['home_text'] : '';
        $home_icon      = !empty($settings['home_icon']) ? $settings['home_icon'] : [];
        $cart_text      = !empty($settings['cart_text']) ? $settings['cart_text'] : '';
        $cart_icon      = !empty($settings['cart_icon']) ? $settings['cart_icon'] : [];
        $shop_text      = !empty($settings['shop_text']) ? $settings['shop_text'] : '';
        $shop_icon      = !empty($settings['shop_icon']) ? $settings['shop_icon'] : [];
        $wishlist_text  = !empty($settings['wishlist_text']) ? $settings['wishlist_text'] : '';
        $wishlist_icon  = !empty($settings['wishlist_icon']) ? $settings['wishlist_icon'] : [];
        $wishlist_link  = !empty($settings['wishlist_link']['url']) ? $settings['wishlist_link']['url'] : '';
        $login_text     = !empty($settings['login_text']) ? $settings['login_text'] : '';
        $login_icon     = !empty($settings['login_icon']) ? $settings['login_icon'] : [];
        $login_link     = !empty($settings['login_link']['url']) ? $settings['login_link']['url'] : '';

        $home_url = home_url();
        $shop_url = get_permalink(wc_get_page_id('shop'));

        $cart_url = '#';
        $cart_count = 0;

        if (class_exists('WooCommerce')) {
            $cart_url = wc_get_cart_url();
            $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        } else {
            $cart_url = $shop_url;
        }

        // CubeWP saved posts count
        if (class_exists('CubeWp_Saved')) {
            $saved_posts = CubeWp_Saved::cubewp_get_saved_posts();
            $saved_posts = count($saved_posts);
        } else {
            $saved_posts = 0;
        }

        if (is_user_logged_in() && class_exists('WooCommerce')) {
            $login_text = __('My Account', 'valuepack-addons');
            $login_link = wc_get_page_permalink('myaccount');
        }
?>

        <div class="vp-sticky-bottom-bar-main">
            <div class="vp-mobile-sticky-bottom-bar">

                <!-- Home -->
                <div class="vp-mobile-sticky-bottom-bar-item">
                    <a href="<?php echo esc_url($home_url); ?>">
                        <?php Icons_Manager::render_icon($home_icon, ['aria-hidden' => 'true']); ?>
                        <span><?php echo esc_html($home_text); ?></span>
                    </a>
                </div>

                <!-- Cart -->
                <div class="vp-mobile-sticky-bottom-bar-item">
                    <a href="<?php echo esc_url($cart_url); ?>">
                        <div class="cart-item-icon">
                            <?php if ($cart_count > 0): ?>
                                <span class="vp-cart-count"><?php echo esc_html($cart_count); ?></span>
                            <?php endif; ?>
                            <?php Icons_Manager::render_icon($cart_icon, ['aria-hidden' => 'true']); ?>
                        </div>
                        <span><?php echo esc_html($cart_text); ?></span>
                    </a>
                </div>

                <!-- Center Shop -->
                <div class="vp-mobile-sticky-bottom-bar-item vp-mobile-sticky-bottom-bar-center-item">
                    <a href="<?php echo esc_url($shop_url); ?>">
                        <?php Icons_Manager::render_icon($shop_icon, ['aria-hidden' => 'true']); ?>
                        <span><?php echo esc_html($shop_text); ?></span>
                    </a>
                </div>

                <!-- Wishlist -->
                <div class="vp-mobile-sticky-bottom-bar-item">
                    <a href="<?php echo esc_url($wishlist_link); ?>">
                        <div class="cart-item-icon">
                            <?php if ($saved_posts > 0): ?>
                                <span class="vp-cart-count"><?php echo esc_html($saved_posts); ?></span>
                            <?php endif; ?>
                            <?php Icons_Manager::render_icon($wishlist_icon, ['aria-hidden' => 'true']); ?>
                        </div>
                        <span><?php echo esc_html($wishlist_text); ?></span>
                    </a>
                </div>

                <!-- Login / My Account -->
                <div class="vp-mobile-sticky-bottom-bar-item">
                    <a href="<?php echo esc_url($login_link); ?>">
                        <?php Icons_Manager::render_icon($login_icon, ['aria-hidden' => 'true']); ?>
                        <span><?php echo esc_html($login_text); ?></span>
                    </a>
                </div>

            </div>
        </div>
<?php

    }
}