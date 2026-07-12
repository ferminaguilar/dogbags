<?php
/**
 * Sidebar Template
 *
 * Displays widget area content with fallback when empty
 *
 * @package Woomen
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( function_exists( 'is_woocommerce' ) && is_woocommerce() && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
    return;  // Exit early if WooCommerce is active and we're on a shop or product page
}
?>
<section id="woomen-sidebar" role="complementary" aria-label="<?php esc_attr_e('Primary Sidebar', 'woomen'); ?>">
    <?php
    if( ! dynamic_sidebar('default-sidebar')) {
        esc_html_e("There is no widget. You should add your widgets into", "woomen");
        ?>
        <strong>
            <?php esc_html_e("Default Sidebar.", "woomen"); ?>
        </strong>
        <?php
    }
    ?>
</section>
