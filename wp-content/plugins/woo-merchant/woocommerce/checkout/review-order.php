<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 65.2.0
 */

defined('ABSPATH') || exit;
//$checkoutStyles  = woomen_get_setting('woomen_checkout_style');
$wm_options = get_option('WM_woocommerce_features_options');
$checkoutStyles = isset($wm_options['woo_merchant_checkout_style']) ? $wm_options['woo_merchant_checkout_style'] : 'style-2';
?>

<table class="shop_table woocommerce-checkout-review-order-table">
	<tbody>
		<?php
		do_action('woocommerce_review_order_before_cart_contents');

		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
				$product_image_id = $_product->get_image_id();
				$product_image_url = wp_get_attachment_image_src($product_image_id, 'thumbnail');
				$product_image_url = $product_image_url ? $product_image_url[0] : '';
				//$parent_data = $_product->get_parent_data();
				//$title = isset($parent_data['title']) ? $parent_data['title'] : '';
				if ($_product->is_type('variation')) {
					$parent_id = $_product->get_parent_id();
					$parent_product = wc_get_product($parent_id);
					$title = $parent_product ? $parent_product->get_name() : $_product->get_name();
				} else {
					$title = $_product->get_name();
				}
				$attribute_summary = isset($_product->get_data()['attribute_summary']) ? $_product->get_data()['attribute_summary'] : '';
				$color = '';
				$size = '';
				if (!empty($attribute_summary)) {
					$attributes = explode(', ', $attribute_summary);
					foreach ($attributes as $attribute) {
						list($key, $value) = explode(': ', $attribute);
						if (trim($key) === 'color') {
							$color = trim($value);
						} elseif (trim($key) === 'size') {
							$size = trim($value);
						}
					}
				}

				if (wp_is_mobile()) { ?>
					<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item mobile', $cart_item, $cart_item_key)); ?>">
						<td class="product-name">
							<?php if ($product_image_url) : ?>
								<img class="product-image" src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($_product->get_name()); ?>" width="50" height="50" />
							<?php endif; ?>
							<div class="product-content">
								<?php
								echo wp_kses_post(apply_filters(
									'woocommerce_cart_item_name',
									'<h3 class="title">' . esc_html($title) . '</h3>' .
										'<p class="price">' . wp_kses_post(apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key)) . '</p>' .
										'<p class="quantity">' . wp_kses_post(apply_filters('woocommerce_checkout_cart_item_quantity', sprintf('Quantity: %s', $cart_item['quantity']), $cart_item, $cart_item_key)) . '</p>' .
										'<p class="size">Size: ' . esc_html($size) . '</p>' .
										'<p class="color">Color: ' . esc_html($color) . '</p>',
									$cart_item,
									$cart_item_key
								));
								?>
								<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>
							</div>
						</td>
					</tr>
				<?php } else { ?>
					<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
						<td class="product-name">
							<?php if ($product_image_url) : ?>
								<img class="product-image" src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($_product->get_name()); ?>" width="50" height="50" />
							<?php endif; ?>
							<div class="product-content">
								<?php
								if ($checkoutStyles == 'style-2') {
									echo wp_kses_post(apply_filters(
										'woocommerce_cart_item_name',
										'<h3 class="title">' . esc_html($title) . '</h3>' .
											'<p class="quantity">' . esc_html($attribute_summary) . '/' . wp_kses_post(apply_filters('woocommerce_checkout_cart_item_quantity', sprintf('- %s', $cart_item['quantity']), $cart_item, $cart_item_key)) . '</p>',
									));
								} else {
									echo wp_kses_post(apply_filters(
										'woocommerce_cart_item_name',
										'<h3 class="title">' . esc_html($title) . '</h3>' .
											'<p class="quantity">' . wp_kses_post(apply_filters('woocommerce_checkout_cart_item_quantity', sprintf('Quantity: %s', $cart_item['quantity']), $cart_item, $cart_item_key)) . '</p>' .
											'<p class="size">Size: ' . esc_html($size) . '</p>' .
											'<p class="color">Color: ' . esc_html($color) . '</p>',
										$cart_item,
										$cart_item_key
									));
								}

								?>
								<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>

								<?php //echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; 
								?>
								<?php //echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>
								<?php //echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>
							</div>
						</td>
						<td class="product-total">
							<?php
							$_product = $cart_item['data'];
							$quantity = $cart_item['quantity'];
							$regular_price = $_product->get_regular_price(); // Get regular (original) price
							$subtotal_before_discount = $regular_price * $quantity; // Calculate original total
							if ($checkoutStyles == 'style-2') {
								echo '<del>' . wc_price($subtotal_before_discount) . '</del> '; // Show original subtotal
							}
							echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
							?>
						</td>

					</tr>
		<?php }
			}
		}
		do_action('woocommerce_review_order_after_cart_contents');

		?>

	</tbody>

	<tfoot>
		<?php if ($checkoutStyles == 'style-2') { ?>
			<tr class="cart-coupens">
				<th>
					<div class="woomen-custom-coupen"><?php woocommerce_checkout_coupon_form();  ?></div>
				</th>
			</tr>
		<?php	} ?>
		<tr class="cart-subtotal">
			<th><?php esc_html_e('Subtotal', 'woomen'); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>
		<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
				<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
				<td><?php wc_cart_totals_coupon_html($coupon); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if ($checkoutStyles == 'style-1') { ?>
			<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

				<?php do_action('woocommerce_review_order_before_shipping'); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action('woocommerce_review_order_after_shipping'); ?>

			<?php endif; ?>
			<?php } else {
			if (WC()->cart->needs_shipping()) : ?>
				<?php $packages = WC()->shipping->get_packages(); ?>
				<?php foreach ($packages as $i => $package) : ?>
					<?php $available_methods = $package['rates']; ?>
					<?php if (! empty($available_methods)) : ?>
						<?php foreach ($available_methods as $method_id => $method) : ?>
							<tr class="cart-shipping-method" data-ship-available="<?php echo esc_attr($method_id); ?>">
								<th><?php echo esc_html($method->label); ?></th>
								<td><?php echo wc_price($method->cost); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
		<?php endif;
		} ?>

		<?php foreach (WC()->cart->get_fees() as $fee) : ?>
			<tr class="fee">
				<th><?php echo esc_html($fee->name); ?></th>
				<td><?php wc_cart_totals_fee_html($fee); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) : ?>
			<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
				<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited 
				?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<th><?php echo esc_html($tax->label); ?></th>
						<td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action('woocommerce_review_order_before_order_total');

		$cart_items = WC()->cart->get_cart();
		$related_products = [];
		if ($checkoutStyles == 'style-2') {
			foreach ($cart_items as $cart_item) {
				$product_id = $cart_item['product_id'];
				$related = wc_get_related_products($product_id, 5);

				foreach ($related as $rel_id) {
					$related_product = wc_get_product($rel_id);

					if ($related_product->is_in_stock() && $related_product->get_price() > 0) {
						$related_products[$rel_id] = $related_product;
					}
				}
			}
		}
		?>

		<tr class="order-total">
			<th><?php esc_html_e('Total', 'woomen'); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php
		$related_products = array_slice($related_products, 0, 3);
		if ($checkoutStyles == 'style-2') {
			if (!empty($related_products) && is_array($related_products)): ?>
				<tr>
					<th class="woomen-checkout-releated-items-title">
						<h3><?php esc_html_e('You May Also Like', 'woomen'); ?></h3>
					</th>
				</tr>
				<?php foreach ($related_products as $rel_product):
					$product_id = $rel_product->get_id();
					$product_link = get_permalink($product_id);
					$product_price = $rel_product->get_price_html();
					$product_image = $rel_product->get_image();
					$is_variable = $rel_product->is_type('variable');
					$variation_id = '';

					if ($is_variable) {
						$available_variations = $rel_product->get_available_variations();
						if (!empty($available_variations)) {
							$first_variation = reset($available_variations);
							$variation_id = $first_variation['variation_id'];
							$product_price = wc_price($first_variation['display_price']);
							$product_image = $first_variation['image']['src'] ? '<img src="' . esc_url($first_variation['image']['src']) . '" width="50">' : $product_image;
						}
					}
				?>
					<tr class="related-product-row" data-product-id="<?php echo esc_attr($product_id); ?>" data-variation-id="<?php echo esc_attr($variation_id); ?>">
						<td class="product-name"><?php echo wp_kses_post($product_image); ?>
							<div class="product-content">
								<p class="product-title"><a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html($rel_product->get_name()); ?></a></p>
								<?php if ($is_variable && !empty($available_variations)):
									$attributes_summary = [];
									foreach ($first_variation['attributes'] as $attr_name => $attr_value) {
										if (!empty($attr_value)) {
											$attr_label = wc_attribute_label(str_replace('attribute_', '', $attr_name));
											$attributes_summary[] = "<strong>{$attr_label}:</strong> " . esc_html($attr_value);
										}
									}

								?>
									<p class="product-attr"><?php echo wp_kses_post(implode(' | ', $attributes_summary)); ?></p>
								<?php endif; ?>
								<p class="product-price"><?php echo wp_kses_post($product_price); ?></p>
							</div>
						</td>
						<td class="product-total">
							<p class="add-related-product button" data-product-id="<?php echo esc_attr($product_id); ?>" data-variation-id="<?php echo esc_attr($variation_id); ?>"><?php esc_html_e('ADD', 'woomen'); ?></p>
						</td>

					</tr>
				<?php endforeach; ?>
		<?php endif;
		} ?>

		<tr class="order-total d-none">
			<th> </th>
			<td class="get-delevery-options-ajax">
				<h3><?php esc_html_e('Delivery', 'woomen'); ?></h3>
				<div class="woomen-checkout-delivery-options-wrape">
					<?php if ($checkoutStyles == 'style-2') { ?>
						<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
							<?php do_action('woocommerce_review_order_before_shipping'); ?>
							<ul id="shipping_method" class="woocommerce-shipping-methods">
								<?php
								$packages = WC()->shipping->get_packages();
								foreach ($packages as $package) :
									$available_methods = $package['rates'];
									foreach ($available_methods as $method_id => $method) :
										$method_class = '';
										$method_icon = '<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_682_12176)"><path d="M16.9603 8.26031H16.5523L15.891 5.49429C15.7982 5.09329 15.4464 4.81334 15.0342 4.81334H12.3459V4.28575C12.3459 3.60692 11.7937 3.05469 11.1148 3.05469H1.23107C0.552234 3.05465 0 3.60689 0 4.28572V12.4811C0 13.1596 0.552234 13.7122 1.23107 13.7122H2.23879C2.47373 14.6214 3.30103 15.295 4.28235 15.295C5.2637 15.295 6.09096 14.6214 6.32591 13.7122H11.9554C12.1904 14.6214 13.0177 15.295 13.999 15.295C14.9803 15.295 15.8076 14.6214 16.0425 13.7122H16.7689C17.4477 13.7122 17.9999 13.16 17.9999 12.4811V9.30006C18 8.72673 17.5336 8.26031 16.9603 8.26031ZM14.8952 5.86852L15.4672 8.26031H12.3459V5.86852H14.8952ZM1.05521 12.4811V10.9377H2.17638C2.46776 10.9377 2.70397 10.7015 2.70397 10.4101C2.70397 10.1188 2.46776 9.88256 2.17638 9.88256H1.05521V4.28572C1.05521 4.18865 1.134 4.10987 1.23107 4.10987H11.1148C11.2119 4.10987 11.2906 4.18865 11.2906 4.28572V12.657H6.32595C6.091 11.7477 5.2637 11.0742 4.28238 11.0742C3.30103 11.0742 2.47377 11.7477 2.23882 12.657H1.23107C1.134 12.657 1.05521 12.5778 1.05521 12.4811ZM4.28235 14.2398C3.70048 14.2398 3.22713 13.7659 3.22713 13.1846C3.22713 12.1087 4.65754 11.7277 5.19578 12.657C5.38383 12.9797 5.38594 13.3846 5.19578 13.7122C5.01293 14.0273 4.67209 14.2398 4.28235 14.2398ZM13.999 14.2398C13.1869 14.2398 12.6804 13.3549 13.0855 12.657C13.6259 11.7242 15.0542 12.1114 15.0542 13.1846C15.0542 13.7635 14.5828 14.2398 13.999 14.2398ZM16.9448 12.4811C16.9448 12.5782 16.866 12.657 16.7689 12.657H16.0426C15.8077 11.7477 14.9804 11.0742 13.999 11.0742C13.3304 11.0742 12.7331 11.3869 12.3459 11.874V9.31549H16.9448V12.4811H16.9448Z" fill="#1D1D1D" /><path d="M5.08209 9.83641C5.27868 10.0612 5.62251 10.0781 5.84027 9.87378L8.1969 7.66207C8.40935 7.46266 8.41997 7.12878 8.22056 6.9163C8.02119 6.70385 7.68731 6.69323 7.47479 6.89264L5.51659 8.7304L4.90909 8.03585C4.71724 7.81651 4.38392 7.79426 4.16462 7.98607C3.94531 8.17792 3.92302 8.51123 4.11484 8.73054L5.08209 9.83641Z" fill="#1D1D1D" /></g><defs><clipPath id="clip0_682_12176"><rect width="18" height="18" fill="white" transform="translate(0 0.174805)" /></clipPath></defs></svg>';
										if (strpos(strtolower($method->label), 'free') !== false) {
											$method_icon = '<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_682_12176)"><path d="M16.9603 8.26031H16.5523L15.891 5.49429C15.7982 5.09329 15.4464 4.81334 15.0342 4.81334H12.3459V4.28575C12.3459 3.60692 11.7937 3.05469 11.1148 3.05469H1.23107C0.552234 3.05465 0 3.60689 0 4.28572V12.4811C0 13.1596 0.552234 13.7122 1.23107 13.7122H2.23879C2.47373 14.6214 3.30103 15.295 4.28235 15.295C5.2637 15.295 6.09096 14.6214 6.32591 13.7122H11.9554C12.1904 14.6214 13.0177 15.295 13.999 15.295C14.9803 15.295 15.8076 14.6214 16.0425 13.7122H16.7689C17.4477 13.7122 17.9999 13.16 17.9999 12.4811V9.30006C18 8.72673 17.5336 8.26031 16.9603 8.26031ZM14.8952 5.86852L15.4672 8.26031H12.3459V5.86852H14.8952ZM1.05521 12.4811V10.9377H2.17638C2.46776 10.9377 2.70397 10.7015 2.70397 10.4101C2.70397 10.1188 2.46776 9.88256 2.17638 9.88256H1.05521V4.28572C1.05521 4.18865 1.134 4.10987 1.23107 4.10987H11.1148C11.2119 4.10987 11.2906 4.18865 11.2906 4.28572V12.657H6.32595C6.091 11.7477 5.2637 11.0742 4.28238 11.0742C3.30103 11.0742 2.47377 11.7477 2.23882 12.657H1.23107C1.134 12.657 1.05521 12.5778 1.05521 12.4811ZM4.28235 14.2398C3.70048 14.2398 3.22713 13.7659 3.22713 13.1846C3.22713 12.1087 4.65754 11.7277 5.19578 12.657C5.38383 12.9797 5.38594 13.3846 5.19578 13.7122C5.01293 14.0273 4.67209 14.2398 4.28235 14.2398ZM13.999 14.2398C13.1869 14.2398 12.6804 13.3549 13.0855 12.657C13.6259 11.7242 15.0542 12.1114 15.0542 13.1846C15.0542 13.7635 14.5828 14.2398 13.999 14.2398ZM16.9448 12.4811C16.9448 12.5782 16.866 12.657 16.7689 12.657H16.0426C15.8077 11.7477 14.9804 11.0742 13.999 11.0742C13.3304 11.0742 12.7331 11.3869 12.3459 11.874V9.31549H16.9448V12.4811H16.9448Z" fill="#1D1D1D" /><path d="M5.08209 9.83641C5.27868 10.0612 5.62251 10.0781 5.84027 9.87378L8.1969 7.66207C8.40935 7.46266 8.41997 7.12878 8.22056 6.9163C8.02119 6.70385 7.68731 6.69323 7.47479 6.89264L5.51659 8.7304L4.90909 8.03585C4.71724 7.81651 4.38392 7.79426 4.16462 7.98607C3.94531 8.17792 3.92302 8.51123 4.11484 8.73054L5.08209 9.83641Z" fill="#1D1D1D" /></g><defs><clipPath id="clip0_682_12176"><rect width="18" height="18" fill="white" transform="translate(0 0.174805)" /></clipPath></defs></svg>';
											$method_class = 'free-shipping';
										} elseif (strpos(strtolower($method->label), 'pickup') !== false) {
											$method_icon = '<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8752 9.7373C10.5184 9.741 10.1652 9.66557 9.84095 9.51644C9.51675 9.3673 9.22962 9.14817 9.00023 8.8748C8.76915 9.14618 8.48169 9.36393 8.15787 9.51289C7.83406 9.66185 7.48166 9.73843 7.12523 9.7373C6.76839 9.741 6.41516 9.66557 6.09095 9.51644C5.76675 9.3673 5.47962 9.14817 5.25023 8.8748C5.18108 8.95777 5.10588 9.03548 5.02523 9.1073C4.77274 9.33704 4.47441 9.51064 4.14992 9.61665C3.82543 9.72265 3.48215 9.75867 3.14273 9.7223C2.5284 9.64661 1.96363 9.3468 1.55672 8.88037C1.1498 8.41394 0.929387 7.81373 0.937728 7.1948V6.6623C0.937118 6.28009 0.997892 5.90025 1.11773 5.5373L2.29523 2.0123C2.3819 1.75052 2.54882 1.52268 2.7723 1.36113C2.99578 1.19957 3.26447 1.11251 3.54023 1.1123H14.4602C14.736 1.11251 15.0047 1.19957 15.2282 1.36113C15.4516 1.52268 15.6186 1.75052 15.7052 2.0123L16.8827 5.5373C17.0026 5.90025 17.0633 6.28009 17.0627 6.6623V7.1948C17.0715 7.81144 16.8532 8.40977 16.4493 8.87585C16.0455 9.34194 15.4843 9.64321 14.8727 9.7223C14.5333 9.75964 14.1897 9.72409 13.8651 9.61803C13.5405 9.51197 13.2422 9.33785 12.9902 9.1073C12.9077 9.0323 12.8252 8.9423 12.7502 8.8598C12.5202 9.13353 12.2333 9.35384 11.9095 9.50539C11.5857 9.65694 11.2328 9.73608 10.8752 9.7373ZM3.54023 2.2373C3.50086 2.2379 3.4626 2.2504 3.43047 2.27316C3.39834 2.29592 3.37385 2.32787 3.36023 2.3648L2.19023 5.8898C2.10898 6.13165 2.06596 6.38469 2.06273 6.6398V7.1723C2.05172 7.51088 2.16564 7.84168 2.38278 8.1017C2.59992 8.36171 2.90511 8.53277 3.24023 8.5823C3.42109 8.60103 3.60386 8.58198 3.77697 8.52636C3.95007 8.47073 4.10974 8.37974 4.24583 8.25916C4.38192 8.13859 4.49147 7.99105 4.56754 7.8259C4.64361 7.66075 4.68454 7.4816 4.68773 7.2998C4.68773 7.15062 4.74699 7.00755 4.85248 6.90206C4.95797 6.79657 5.10104 6.7373 5.25023 6.7373C5.39941 6.7373 5.54249 6.79657 5.64798 6.90206C5.75347 7.00755 5.81273 7.15062 5.81273 7.2998C5.80414 7.47444 5.83221 7.64893 5.89513 7.81206C5.95806 7.97519 6.05443 8.12333 6.17807 8.24697C6.3017 8.3706 6.44985 8.46698 6.61297 8.5299C6.7761 8.59282 6.9506 8.62089 7.12523 8.6123C7.47272 8.61034 7.80541 8.47142 8.05113 8.22571C8.29685 7.97999 8.43576 7.64729 8.43773 7.2998C8.43773 7.15062 8.49699 7.00755 8.60248 6.90206C8.70797 6.79657 8.85104 6.7373 9.00023 6.7373C9.14941 6.7373 9.29249 6.79657 9.39798 6.90206C9.50347 7.00755 9.56273 7.15062 9.56273 7.2998C9.55414 7.47444 9.58221 7.64893 9.64513 7.81206C9.70806 7.97519 9.80443 8.12333 9.92807 8.24697C10.0517 8.3706 10.1998 8.46698 10.363 8.5299C10.5261 8.59282 10.7006 8.62089 10.8752 8.6123C11.2227 8.61034 11.5554 8.47142 11.8011 8.22571C12.0468 7.97999 12.1858 7.64729 12.1877 7.2998C12.1877 7.15062 12.247 7.00755 12.3525 6.90206C12.458 6.79657 12.601 6.7373 12.7502 6.7373C12.8994 6.7373 13.0425 6.79657 13.148 6.90206C13.2535 7.00755 13.3127 7.15062 13.3127 7.2998C13.3128 7.48351 13.3514 7.66515 13.4261 7.83297C13.5008 8.0008 13.6099 8.15108 13.7464 8.27409C13.8828 8.39709 14.0436 8.49009 14.2182 8.54706C14.3928 8.60403 14.5775 8.6237 14.7602 8.6048C15.0953 8.55527 15.4005 8.38421 15.6177 8.1242C15.8348 7.86418 15.9487 7.53338 15.9377 7.1948V6.6623C15.9345 6.40719 15.8915 6.15415 15.8102 5.9123L14.6402 2.3873C14.6266 2.35037 14.6021 2.31842 14.57 2.29566C14.5379 2.2729 14.4996 2.2604 14.4602 2.2598L3.54023 2.2373Z" fill="#1D1D1D" /><path d="M15 17.2373H3C2.65251 17.2354 2.31981 17.0965 2.0741 16.8507C1.82838 16.605 1.68947 16.2723 1.6875 15.9248V8.78484C1.6875 8.63566 1.74676 8.49259 1.85225 8.3871C1.95774 8.28161 2.10082 8.22234 2.25 8.22234C2.39918 8.22234 2.54226 8.28161 2.64775 8.3871C2.75324 8.49259 2.8125 8.63566 2.8125 8.78484V15.9248C2.8125 15.9746 2.83225 16.0223 2.86742 16.0574C2.90258 16.0926 2.95027 16.1123 3 16.1123H15C15.0497 16.1123 15.0974 16.0926 15.1326 16.0574C15.1677 16.0223 15.1875 15.9746 15.1875 15.9248V8.77734C15.1875 8.62816 15.2468 8.48509 15.3523 8.3796C15.4577 8.27411 15.6008 8.21484 15.75 8.21484C15.8992 8.21484 16.0423 8.27411 16.1477 8.3796C16.2532 8.48509 16.3125 8.62816 16.3125 8.77734V15.9248C16.3105 16.2723 16.1716 16.605 15.9259 16.8507C15.6802 17.0965 15.3475 17.2354 15 17.2373Z" fill="#1D1D1D" /><path d="M11.625 17.2373H6.375C6.22642 17.2354 6.08447 17.1755 5.9794 17.0704C5.87433 16.9653 5.81444 16.8234 5.8125 16.6748V12.9248C5.81447 12.5773 5.95338 12.2446 6.1991 11.9989C6.44481 11.7532 6.77751 11.6143 7.125 11.6123H10.875C11.2225 11.6143 11.5552 11.7532 11.8009 11.9989C12.0466 12.2446 12.1855 12.5773 12.1875 12.9248V16.6748C12.1856 16.8234 12.1257 16.9653 12.0206 17.0704C11.9155 17.1755 11.7736 17.2354 11.625 17.2373ZM6.9375 16.1123H11.0625V12.9248C11.0625 12.8751 11.0427 12.8274 11.0076 12.7922C10.9724 12.7571 10.9247 12.7373 10.875 12.7373H7.125C7.07527 12.7373 7.02758 12.7571 6.99242 12.7922C6.95725 12.8274 6.9375 12.8751 6.9375 12.9248V16.1123Z" fill="#1D1D1D" />	</svg>';
											$cost_of_del = wc_price($method->cost);
											$shipping_instance_id = $method->get_instance_id();
											$settings = get_option('woocommerce_local_pickup_' . $shipping_instance_id . '_settings');
											$method_class = 'local-shipping ' . $settings['attach_with_store'];
										} else {
											$method_icon = '<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_682_12176)"><path d="M16.9603 8.26031H16.5523L15.891 5.49429C15.7982 5.09329 15.4464 4.81334 15.0342 4.81334H12.3459V4.28575C12.3459 3.60692 11.7937 3.05469 11.1148 3.05469H1.23107C0.552234 3.05465 0 3.60689 0 4.28572V12.4811C0 13.1596 0.552234 13.7122 1.23107 13.7122H2.23879C2.47373 14.6214 3.30103 15.295 4.28235 15.295C5.2637 15.295 6.09096 14.6214 6.32591 13.7122H11.9554C12.1904 14.6214 13.0177 15.295 13.999 15.295C14.9803 15.295 15.8076 14.6214 16.0425 13.7122H16.7689C17.4477 13.7122 17.9999 13.16 17.9999 12.4811V9.30006C18 8.72673 17.5336 8.26031 16.9603 8.26031ZM14.8952 5.86852L15.4672 8.26031H12.3459V5.86852H14.8952ZM1.05521 12.4811V10.9377H2.17638C2.46776 10.9377 2.70397 10.7015 2.70397 10.4101C2.70397 10.1188 2.46776 9.88256 2.17638 9.88256H1.05521V4.28572C1.05521 4.18865 1.134 4.10987 1.23107 4.10987H11.1148C11.2119 4.10987 11.2906 4.18865 11.2906 4.28572V12.657H6.32595C6.091 11.7477 5.2637 11.0742 4.28238 11.0742C3.30103 11.0742 2.47377 11.7477 2.23882 12.657H1.23107C1.134 12.657 1.05521 12.5778 1.05521 12.4811ZM4.28235 14.2398C3.70048 14.2398 3.22713 13.7659 3.22713 13.1846C3.22713 12.1087 4.65754 11.7277 5.19578 12.657C5.38383 12.9797 5.38594 13.3846 5.19578 13.7122C5.01293 14.0273 4.67209 14.2398 4.28235 14.2398ZM13.999 14.2398C13.1869 14.2398 12.6804 13.3549 13.0855 12.657C13.6259 11.7242 15.0542 12.1114 15.0542 13.1846C15.0542 13.7635 14.5828 14.2398 13.999 14.2398ZM16.9448 12.4811C16.9448 12.5782 16.866 12.657 16.7689 12.657H16.0426C15.8077 11.7477 14.9804 11.0742 13.999 11.0742C13.3304 11.0742 12.7331 11.3869 12.3459 11.874V9.31549H16.9448V12.4811H16.9448Z" fill="#1D1D1D" /><path d="M5.08209 9.83641C5.27868 10.0612 5.62251 10.0781 5.84027 9.87378L8.1969 7.66207C8.40935 7.46266 8.41997 7.12878 8.22056 6.9163C8.02119 6.70385 7.68731 6.69323 7.47479 6.89264L5.51659 8.7304L4.90909 8.03585C4.71724 7.81651 4.38392 7.79426 4.16462 7.98607C3.94531 8.17792 3.92302 8.51123 4.11484 8.73054L5.08209 9.83641Z" fill="#1D1D1D" /></g><defs><clipPath id="clip0_682_12176"><rect width="18" height="18" fill="white" transform="translate(0 0.174805)" /></clipPath></defs></svg>';
											$method_class = 'standard-shipping';
										}
								?>
										<li class="shipping-method <?php echo esc_attr($method_class); ?>">
											<input type="radio" name="shipping_method[0]" data-index="0"
												id="shipping_method_<?php echo esc_attr($method_id); ?>"
												value="<?php echo esc_attr($method_id); ?>"
												class="shipping_method"
												<?php checked(WC()->session->get('chosen_shipping_methods')[0], $method_id); ?> />
											<label for="shipping_method_<?php echo esc_attr($method_id); ?>">
												<?php echo esc_html($method->label);
												echo wp_kses_post($method_icon);
												?>
											</label>
										</li>
								<?php
									endforeach;
								endforeach;
								?>
							</ul>

							<?php do_action('woocommerce_review_order_after_shipping'); ?>
						<?php endif; ?>
					<?php } ?>

				</div>
				<div class="woomen-checkout-store-locators" style="display: none;">
					<?php
					$store_locator_posts = get_posts(array(
						'post_type'      => 'store-locator',
						'post_status'    => 'publish',
						'numberposts'    => -1,
					));
					if (!empty($store_locator_posts)) {
						echo '<h3>' . esc_html__('Store locations', 'woomen') . '</h3>';
						echo '<p>' . sprintf(esc_html__('There are %s stores with stock close to your location.', 'woomen'), count($store_locator_posts)) . '</p>				<div class="woomen-store-locator-main">
			';
						foreach ($store_locator_posts as $post) {
							$postID = $post->ID;
							$store_name = get_the_title($postID);
							$store_address = get_post_meta($postID, 'store-locator-address', true);
					?>
							<div class="woomen-store-locator">
								<input type="radio" name="store_locator" id="<?php echo esc_attr($postID) ?>" value="<?php echo esc_attr($postID) ?>">
								<label for="<?php echo esc_attr($postID) ?>">
									<div class="woomen-store-locator-option">
										<div class="woomen-store-locator-title">
											<h4><?php echo esc_html($store_name); ?></h4>
											<?php if (isset($cost_of_del) && !empty($cost_of_del)) { ?>
												<h4><?php echo wp_kses_post($cost_of_del); ?></h4>
											<?php } ?>
										</div>
										<div class="woomen-store-locator-address">
											<?php
											if (!empty($store_address)) {
												echo '<p>' . wp_kses_post($store_address) . '</p>';
											}
											?>
											<?php if (isset($cost_of_del) && !empty($cost_of_del)) { ?>
												<p><?php echo wp_kses_post($cost_of_del) . ' ' . esc_html__('Usually ready in 24 hours', 'woomen'); ?></p>
											<?php } ?>	
										</div>
									</div>
								</label>
							</div>
					<?php
						}
						echo '</div>';
					} else {
						echo '<p>No stores found.</p>';
					}
					?>

				</div>
			</td>
		</tr>
		<?php
		if (WC()->cart->get_discount_total() > 0) : ?>
			<tr class="order-discount">
				<th>
					<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1.81335 13.6295L7.1698 18.9865C7.53326 19.3497 8.01605 19.5499 8.52997 19.5499C9.04388 19.5499 9.52667 19.3497 9.88983 18.9865L18.1866 10.69C18.6221 10.2548 18.8235 9.63165 18.7256 9.02374L17.9816 4.4107C17.8491 3.59039 17.2092 2.95074 16.3889 2.8183L11.7764 2.07428C11.1691 1.9754 10.5463 2.17682 10.1102 2.61322L1.81305 10.9097C1.4502 11.2729 1.25 11.7563 1.25 12.2696C1.25 12.7835 1.4502 13.2663 1.81335 13.6295ZM2.69714 11.7935L10.994 3.49701C11.1466 3.34427 11.3661 3.27409 11.5771 3.30841L16.1899 4.05243C16.4771 4.09881 16.701 4.32281 16.7474 4.60968L17.4915 9.22272C17.5256 9.43573 17.4551 9.65362 17.3029 9.80621L9.00574 18.1027C8.75183 18.3566 8.30841 18.3566 8.05389 18.1027L2.69714 12.7457C2.56989 12.6187 2.5 12.4496 2.5 12.2696C2.5 12.0901 2.57019 11.9205 2.69714 11.7935Z" fill="#1D1D1D" />
						<path d="M15.2999 6.13794C15.6451 6.13794 15.9249 5.8584 15.9249 5.51294C15.9249 5.16748 15.6451 4.88794 15.2999 4.88794H15.2935C14.9484 4.88794 14.6719 5.16748 14.6719 5.51294C14.6719 5.8584 14.9548 6.13794 15.2999 6.13794Z" fill="#1D1D1D" />
						<path d="M9.78821 8.99756C10.1334 8.99756 10.4132 8.71802 10.4132 8.37256C10.4132 8.0271 10.1334 7.74756 9.78821 7.74756H9.7818C9.43665 7.74756 9.16016 8.0271 9.16016 8.37256C9.16016 8.71802 9.44305 8.99756 9.78821 8.99756Z" fill="#1D1D1D" />
						<path d="M9.78821 13.0508H9.7818C9.43665 13.0508 9.16016 13.3303 9.16016 13.6758C9.16016 14.0212 9.44305 14.3008 9.78821 14.3008C10.1334 14.3008 10.4132 14.0212 10.4132 13.6758C10.4132 13.3303 10.1334 13.0508 9.78821 13.0508Z" fill="#1D1D1D" />
						<path d="M7.34766 11.6494H12.1832C12.5283 11.6494 12.8082 11.3699 12.8082 11.0244C12.8082 10.679 12.5283 10.3994 12.1832 10.3994H7.34766C7.0025 10.3994 6.72266 10.679 6.72266 11.0244C6.72266 11.3699 7.0025 11.6494 7.34766 11.6494Z" fill="#1D1D1D" />
					</svg>

					<?php echo esc_html__('TOTAL SAVINGS', 'woomen') . ' ' . wc_price(WC()->cart->get_discount_total()); ?>
				</th>
			</tr>
		<?php endif; ?>

		<?php do_action('woocommerce_review_order_after_order_total');

		?>
	</tfoot>
</table>