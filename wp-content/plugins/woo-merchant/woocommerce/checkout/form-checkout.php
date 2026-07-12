<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version  69.4.0
 * 
 * Security Considerations:
 * - All output is properly escaped
 * - Nonce verification handled by WooCommerce
 * - Form submissions properly validated
 * - Sensitive data handled securely
 */

if (! defined('ABSPATH')) {
	exit;
}

//$checkoutStyles  = sanitize_text_field(woomen_get_setting('woomen_checkout_style'));
$wm_options = get_option('WM_woocommerce_features_options');
$checkoutStyles = isset($wm_options['woo_merchant_checkout_style']) ? $wm_options['woo_merchant_checkout_style'] : 'style-2';
if ($checkoutStyles == 'style-1') {
	do_action('woocommerce_before_checkout_form', $checkout);
}
?>
<div class="container woomen-checkout-page-container <?php echo esc_attr($checkoutStyles); ?>">
	<?php
	if ($checkoutStyles == 'style-1') {
		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
			echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woomen')));
			return;
		}
	}
	if ($checkoutStyles == 'style-1'):
	?>
		<div class="woocommerce-orders-breadcrumbs">
			<?php
			$woocommerce_pages_class = '';
			if (is_cart()) {
				$woocommerce_pages_class = 'woocommerce-cart';
			} elseif (is_checkout()) {
				$woocommerce_pages_class = 'woocommerce-checkout';
			} elseif (is_order_received_page()) {
				$woocommerce_pages_class = 'woocommerce-order-complete';
			}
			?>
			<ul class="<?php echo esc_attr($woocommerce_pages_class); ?>">
				<?php if (! wp_is_mobile()) { ?>
					<li class="cart"><?php esc_html_e('SHOPPING CART', 'woomen'); ?></li>
					<li class="woocommerce-breadcrumbs-saprator"><i class="fa-solid fa-chevron-right"></i></li>
				<?php } ?>
				<li class="checkout"><?php esc_html_e('CHECKOUT', 'woomen'); ?></li>
				<?php if (! wp_is_mobile()) { ?>
					<li class="woocommerce-breadcrumbs-saprator"><i class="fa-solid fa-chevron-right"></i></li>
					<li class="order"><?php esc_html_e('ORDER COMPLETE', 'woomen'); ?></li>
				<?php } ?>
			</ul>
		</div>
	<?php
	endif;
	$checkout_mainClass = 'row woomen-checkout style-1';
	$checkoutleftClass = 'col-1 col-lg-7';
	$checkout_rightClass = 'col-2 col-lg-5 ';
	if ($checkoutStyles == 'style-2') {
		$checkout_mainClass = 'woomen-checkout woomen-checkout-classic';
		$checkoutleftClass = 'woomen-checkout-fields-content';
		$checkout_rightClass = 'woomen-checkout-sidebar-conetnt';
	}
	?>
	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

		<?php if ($checkout->get_checkout_fields()) : ?>

			<?php
			if ($checkoutStyles == 'style-1') {
				do_action('woocommerce_checkout_before_customer_details');
			} ?>
			<!-- col2-set -->
			<div class="<?php echo esc_attr($checkout_mainClass); ?>" id="customer_details">
				<div class="<?php echo esc_attr($checkoutleftClass); ?>">
					<?php
					if ($checkoutStyles == 'style-2') {
						do_action('woocommerce_checkout_before_customer_details');
						do_action('woocommerce_before_checkout_form', $checkout);
						// If checkout registration is disabled and not logged in, the user cannot checkout.
						if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
							echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woomen')));
							return;
						}
					}
					?>
					<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
					<?php do_action('woocommerce_checkout_billing'); ?>
					<?php
					if ($checkoutStyles == 'style-1') {
					?>
						<p class="additional-information"><?php esc_html_e('Additional Information', 'woomen'); ?></p>
						<?php do_action('woocommerce_checkout_shipping'); ?>

					<?php
					}

					?>
				</div>

				<div class="<?php echo esc_attr($checkout_rightClass); ?>">
					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php
						if ($checkoutStyles == 'style-1') {
						?>
							<h3 id="order_review_heading"><?php esc_html_e('Order Summary', 'woomen'); ?></h3>
							<div class="woocommerce-edit-order-button">
								<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="edit-order-button">
									<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1.32749 0.691772H9.42343C9.53946 0.691772 9.65074 0.737866 9.73279 0.819913C9.81484 0.90196 9.86093 1.01324 9.86093 1.12927C9.86093 1.2453 9.81484 1.35658 9.73279 1.43863C9.65074 1.52068 9.53946 1.56677 9.42343 1.56677H2.18543L11.1625 10.5443C11.2059 10.5842 11.2408 10.6325 11.265 10.6863C11.2893 10.74 11.3024 10.7982 11.3036 10.8571C11.3048 10.9161 11.2941 10.9747 11.2721 11.0294C11.2501 11.0841 11.2173 11.1338 11.1756 11.1755C11.1339 11.2172 11.0842 11.2501 11.0294 11.2721C10.9747 11.2941 10.9161 11.3048 10.8571 11.3036C10.7982 11.3024 10.7401 11.2892 10.6863 11.265C10.6325 11.2407 10.5842 11.2059 10.5443 11.1625L1.5668 2.18496V9.4234C1.5668 9.53943 1.52071 9.65071 1.43866 9.73276C1.35662 9.8148 1.24534 9.8609 1.1293 9.8609C1.01327 9.8609 0.901992 9.8148 0.819944 9.73276C0.737898 9.65071 0.691804 9.53943 0.691804 9.4234V1.3279C0.692036 1.15933 0.759069 0.997733 0.878222 0.878499C0.997373 0.759265 1.15893 0.69212 1.32749 0.691772Z" fill="#1D1D1D" />
									</svg>
									<?php esc_html_e('Edit Order', 'woomen'); ?>
								</a>
							</div>
						<?php
						}
						?>
						<?php do_action('woocommerce_checkout_order_review');
						?>
					</div>
				</div>
			</div>

			<?php do_action('woocommerce_checkout_after_customer_details'); ?>

		<?php endif; ?>


		<?php do_action('woocommerce_checkout_after_order_review'); ?>

	</form>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout);



?>
