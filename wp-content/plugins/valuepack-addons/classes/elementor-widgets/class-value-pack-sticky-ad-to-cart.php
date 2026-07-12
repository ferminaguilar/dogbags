<?php
/**
 * Sticky Add to Cart Widget
 *
 * @package valuepack-addons/cube/classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;

class Value_Pack_Sticky_Ad_To_Cart extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_sticky_add_to_cart';
    }

    public function get_title()
    {
        return __('Sticky Add To Cart', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-custom vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    protected function register_controls()
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
                'type' => Controls_Manager::TEXT,
                'description' => __('Enter product ID. If you want to use this element on the product single page, leave this field empty.', 'valuepack-addons'),
                'label_block' => true,
                'default' => value_pack_get_any_one_product_id(),
                'placeholder' => __('e.g., 123', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'sticky_style',
            [
                'label' => __('Sticky Add to Cart Style', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style_1' => __('Style 1', 'valuepack-addons'),
                    'style_2' => __('Style 2', 'valuepack-addons'),
                    'style_3' => __('Style 3', 'valuepack-addons'),
                ],
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        global $product;
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



        $sticky_style = !empty($settings['sticky_style']) ? $settings['sticky_style'] : '';
        $product = wc_get_product($product_id); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
        $is_mobile = wp_is_mobile();
        if ($is_mobile) {
            $sticky_style = 'style_2';
        }
        if ($product) {
            $thumbnail = $product->get_image('thumbnail');
            $title = $product->get_name();
            $price = $product->get_price_html();
            $product_permalink = $product->get_permalink();
            $is_single_product_page = is_product();
            $style_inline = '';
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $style_inline = 'position: relative; opacity: 1;transform: unset !important;';
            }
?>
            <div class="woo-sticky-ad-to-cart-container">
                <?php
                if ($sticky_style === 'style_1') {
                ?>
                    <div class="woo-sticky-cart-<?php echo esc_attr($sticky_style); ?> woo-sticky-cart" style="<?php echo esc_attr($style_inline); ?>">
                        <div class="woo-sticky-cart-style1-content">
                            <div class="woo-sticky-cart-product-thumbnail">
                                <?php if (!$is_single_product_page) { ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                    </a>
                                <?php } else { ?>
                                    <?php echo wp_kses_post($thumbnail); ?>
                                <?php } ?>
                            </div>
                            <div class="woo-sticky-cart-product-details">
                                <h4 class="product-title">
                                    <?php if (!$is_single_product_page) { ?>
                                        <a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($title); ?></a>
                                    <?php } else { ?>
                                        <?php echo esc_html($title); ?>
                                    <?php } ?>
                                </h4>
                                <p class="product-price"><?php echo wp_kses_post($price); ?></p>
                            </div>
                        </div>
                        <div class="woo-sticky-cart-product-attributes">
                            <?php echo woocommerce_template_single_add_to_cart();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Html from woocommerce_template_single_add_to_cart is safe. ?>
                        </div>
                    </div>
                <?php
                } elseif ($sticky_style === 'style_2') {
                ?>
                    <div class="woo-sticky-cart-<?php echo esc_attr($sticky_style); ?> woo-sticky-cart" style="<?php echo esc_attr($style_inline); ?>">
                        <div class="woo-sticky-cart-style2-content">
                            <div class="woo-sticky-cart-product-thumbnail">
                                <?php if (!$is_single_product_page) { ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                    </a>
                                <?php } else { ?>
                                    <?php echo wp_kses_post($thumbnail); ?>
                                <?php } ?>
                            </div>
                            <div class="woo-sticky-cart-product-details">
                                <div class="woo-sticky-cart-product-price-title">
                                    <h4 class="product-title">
                                        <?php if (!$is_single_product_page) { ?>
                                            <a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($title); ?></a>
                                        <?php } else { ?>
                                            <?php echo esc_html($title); ?>
                                        <?php } ?>
                                    </h4>
                                    <p class="product-price"><?php echo wp_kses_post($price); ?></p>
                                </div>
                                <div class="woo-sticky-cart-attributes">
                                    <span class="cart-attributes">
                                        <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect y="5.4668" width="10" height="1" fill="#1D1D1D" />
                                            <rect x="4.5" y="10.9668" width="10" height="1" transform="rotate(-90 4.5 10.9668)" fill="#1D1D1D" />
                                        </svg>
                                    </span>
                                    <span class="cart-attributes-closed" style="display: none;">
                                        <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect y="0.466797" width="10" height="1" fill="#1D1D1D" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="woo-sticky-cart-product-attributes">
                            <?php echo woocommerce_template_single_add_to_cart();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Html from woocommerce_template_single_add_to_cart is safe. ?>
                        </div>
                    </div>
                <?php
                } elseif ($sticky_style === 'style_3') {
                ?>
                    <div class="woo-sticky-cart-<?php echo esc_attr($sticky_style); ?> woo-sticky-cart" style="<?php echo esc_attr($style_inline); ?>">
                        <div class="woo-sticky-cart-style3-content">
                            <div class="woo-sticky-cart-product-thumbnail">
                                <?php if (!$is_single_product_page) { ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                    </a>
                                <?php } else { ?>
                                    <?php echo wp_kses_post($thumbnail); ?>
                                <?php } ?>
                            </div>
                            <div class="woo-sticky-cart-product-details">
                                <h4 class="product-title">
                                    <?php if (!$is_single_product_page) { ?>
                                        <a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($title); ?></a>
                                    <?php } else { ?>
                                        <?php echo esc_html($title); ?>
                                    <?php } ?>
                                </h4>
                                <p class="product-price"><?php echo wp_kses_post($price); ?></p>
                            </div>
                        </div>
                        <div class="woo-sticky-cart-product-attributes">
                            <?php echo woocommerce_template_single_add_to_cart();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Html from woocommerce_template_single_add_to_cart is safe. ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
<?php
        } else {
            echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }
    }
}
