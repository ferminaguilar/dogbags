<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 68.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">
	<div class="woocommerce-order-recieve-pg">
		<div class="woocommerce-orders-breadcrumbs">
			<ul class="woocommerce-order-complete">
				<li class="cart"><?php esc_html_e( 'SHOPPING CART', 'woomen' ); ?></li>
				<li class="woocommerce-breadcrumbs-saprator"><i class="fa-solid fa-chevron-right"></i></li>
				<li class="checkout"><?php esc_html_e( 'CHECKOUT', 'woomen' ); ?></li>
				<li class="woocommerce-breadcrumbs-saprator"><i class="fa-solid fa-chevron-right"></i></li>
				<li class="order"><?php esc_html_e( 'ORDER COMPLETE', 'woomen' ); ?></li>
			</ul>
		</div>
		<?php
		if ( $order ) :

			do_action( 'woocommerce_before_thankyou', $order->get_id() );
			?>

			<?php if ( $order->has_status( 'failed' ) ) : ?>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woomen' ); ?></p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woomen' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woomen' ); ?></a>
					<?php endif; ?>
				</p>

			<?php else : ?>

				<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

					<li class="woocommerce-order-overview__order order">
						<span><?php esc_html_e( 'Order number:', 'woomen' ); ?></span>
						<strong><?php echo esc_html( $order->get_order_number() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<li class="woocommerce-order-overview__date date">
						<span><?php esc_html_e( 'Date:', 'woomen' ); ?></span>
						<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<li class="woocommerce-order-overview__total total">
						<span><?php esc_html_e( 'Total:', 'woomen' ); ?></span>
						<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<span><?php esc_html_e( 'Payment method:', 'woomen' ); ?></span>
							<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</li>
					<?php endif; ?>

				</ul>

			<?php endif; ?>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

		<?php else : ?>

			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

		<?php endif; ?>
	</div>
</div>
