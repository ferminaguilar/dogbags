<?php

defined('ABSPATH') || exit;

/**
 * Setup checkout customizations if WooCommerce is installed and the selected checkout style is 'style-2'.
 *
 * @since  1.0.0
 */ 
		remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
		add_action('woocommerce_after_checkout_billing_form', 'custom_woocommerce_payment_section', 20);
		add_action('wp', 'remove_checkout_coupon_form');
 
if (!function_exists('remove_checkout_coupon_form')) {
	/**
	 * Remove the coupon form from checkout page
	 *
	 * @since 1.0.0
	 * @hook woocommerce_before_checkout_form
	 */
	function remove_checkout_coupon_form()
	{
		remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
	}
}

if (!function_exists('custom_woocommerce_payment_section')) {
	/**
	 * Display custom payment section in checkout
	 * 
	 * @since 1.0.0
	 * @hook woocommerce_after_checkout_billing_form
	 */
	function custom_woocommerce_payment_section()
	{
?>
		<div class="custom-payment-section">
			<h3><?php esc_html_e('Payment', 'woomen'); ?></h3>
			<p class="payment-subtitle"><?php esc_html_e('All transactions are secure and encrypted.', 'woomen'); ?></p>
			<?php woocommerce_checkout_payment(); ?>
		</div>
<?php
	}
}

/**
 * Saves custom checkout fields (delivery method and store locator) to order meta.
 *
 * @since  1.0.0
 */
/**
 * Save delivery method and store locator to order meta
 *
 * @since 1.0.0
 * @hook woocommerce_checkout_update_order_meta
 *
 * @param int $order_id The order ID being processed
 */
function save_store_locator_if_local_pickup($order_id)
{
	if (!empty($_POST['shipping_method'])) {
		$shipping_methods = wc_clean($_POST['shipping_method']);
		// $text_me_news_offers = wc_clean($_POST['text_me_news_offers']);
		$selected_shipping_method = is_array($shipping_methods) ? sanitize_text_field($shipping_methods[0]) : '';

		// Save the selected shipping method
		$selected_shipping_method = str_replace(':', '_', $selected_shipping_method);
		$order_type = get_option('woocommerce_' . $selected_shipping_method . '_settings');
		update_post_meta($order_id, 'delivery_method', $selected_shipping_method);
		if (!empty($selected_shipping_method) && is_string($selected_shipping_method)) {
			$selected_shipping_method = preg_replace('/[:_]\d+/', '', $selected_shipping_method);
		}		

		if (isset($order_type['attach_with_store']) && $order_type['attach_with_store'] == 'yes') {
			if ($selected_shipping_method === 'local_pickup' && !empty($_POST['store_locator'])) {
				update_post_meta($order_id, 'store_locator', sanitize_text_field($_POST['store_locator']));
			}
		}
	}

	$customer_id = get_current_user_id();
	if ($_POST['save_user_details'] !== 'yes') {
		if ($customer_id) {
			delete_user_meta($customer_id, 'billing_first_name');
			delete_user_meta($customer_id, 'billing_last_name');
			delete_user_meta($customer_id, 'billing_address_1');
			delete_user_meta($customer_id, 'billing_address_2');
			delete_user_meta($customer_id, 'billing_city');
			delete_user_meta($customer_id, 'billing_state');
			delete_user_meta($customer_id, 'billing_postcode');
			delete_user_meta($customer_id, 'billing_country');
			delete_user_meta($customer_id, 'billing_email');
			delete_user_meta($customer_id, 'billing_phone');
		}

		WC()->session->set_customer_session_cookie(false); // Destroy session
		WC()->session->destroy_session(); // Completely clear checkout session
		WC()->cart->empty_cart(); // Optionally clear the cart to prevent saved data
	}
}
add_action('woocommerce_checkout_update_order_meta', 'save_store_locator_if_local_pickup');


/**
 * Displays custom checkout fields (delivery method and store locator) in the WooCommerce admin order panel.
 *
 * @since  1.0.0
 */
if (!function_exists('display_custom_checkout_fields_in_admin')) {
	/**
	 * Display custom checkout fields in admin order panel
	 *
	 * @since 1.0.0
	 * @hook woocommerce_admin_order_data_after_billing_address
	 *
	 * @param WC_Order $order The order object
	 */
	function display_custom_checkout_fields_in_admin($order)
	{
		$order_type = get_post_meta($order->get_id(), 'delivery_method', true);
		$order_type = get_option('woocommerce_' . $order_type . '_settings');

		$store_locator = get_post_meta($order->get_id(), 'store_locator', true);
		if ($order_type) {
			echo '<p><strong>' . __('Delivery:', 'woomen') . '</strong> ' . esc_html($order_type['title']) . '</p>';
		}
		if (isset($order_type['attach_with_store']) && $order_type['attach_with_store'] == 'yes') {
			if ($store_locator) {
				$store_name = get_the_title($store_locator);
				$store_address = get_post_meta($store_locator, 'store-locator-address', true);
				echo '<p><strong>' . __('Store Name:', 'woomen') . '</strong> ' . esc_html($store_name) . '</p>';
				echo '<p><strong>' . __('Store Location:', 'woomen') . '</strong> ' . esc_html($store_address) . '</p>';
			}
		}
	}
	add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_checkout_fields_in_admin', 10, 1);
}


if (!function_exists('add_custom_checkout_details_to_email')) {
	/**
	 * Add custom checkout details to order emails
	 *
	 * @since 1.0.0
	 * @hook woocommerce_email_order_meta
	 *
	 * @param WC_Order $order Order object
	 * @param bool $sent_to_admin Whether sent to admin
	 * @param bool $plain_text Whether plain text email
	 * @param WC_Email $email Email object
	 */
	function add_custom_checkout_details_to_email($order, $sent_to_admin, $plain_text, $email)
	{
		$order_id = $order->get_id();
		$order_type = get_post_meta($order_id, 'delivery_method', true);
		$order_type = get_option('woocommerce_' . $order_type . '_settings');
		$store_locator = get_post_meta($order_id, 'store_locator', true);

		if ($order_type) {
			echo '<p><strong>' . __('Delivery:', 'woomen') . '</strong> ' . esc_html($order_type['title']) . '</p>';
		}

		if (isset($order_type['attach_with_store']) && $order_type['attach_with_store'] == 'yes') {
			if ($store_locator) {
				$store_name = get_the_title($store_locator);
				$store_address = get_post_meta($store_locator, 'store-locator-address', true);

				echo '<p><strong>' . __('Store Name:', 'woomen') . '</strong> ' . esc_html($store_name) . '</p>';
				echo '<p><strong>' . __('Store Location:', 'woomen') . '</strong> ' . esc_html($store_address) . '</p>';
			}
		}
	}
	add_action('woocommerce_email_order_meta', 'add_custom_checkout_details_to_email', 10, 4);
}


if (!function_exists('add_custom_checkout_details_to_thankyou_page')) {
	/**
	 * Add custom checkout details to thank you page
	 *
	 * @since 1.0.0
	 * @hook woocommerce_thankyou
	 *
	 * @param int $order_id The order ID
	 */
	function add_custom_checkout_details_to_thankyou_page($order_id)
	{
		$order_type = get_post_meta($order_id, 'delivery_method', true);
		$order_type = get_option('woocommerce_' . $order_type . '_settings');
		$store_locator = get_post_meta($order_id, 'store_locator', true);

		echo '<section class="woocommerce-customer-details">';
		echo '	<h2 class="woocommerce-column__title">' . __('Delivery Details', 'woomen') . '</h2> ';
		echo '	<address>';

		if ($order_type) {
			echo '<p><strong>' . __('Delivery:', 'woomen') . '</strong> ' . esc_html($order_type['title']) . '</p>';
		}

		if (isset($order_type['attach_with_store']) && $order_type['attach_with_store'] == 'yes') {
			if ($store_locator) {
				$store_name = get_the_title($store_locator);
				$store_address = get_post_meta($store_locator, 'store-locator-address', true);

				echo '<p><strong>' . __('Store Name:', 'woomen') . '</strong> ' . esc_html($store_name) . '</p>';
				echo '<p><strong>' . __('Store Location:', 'woomen') . '</strong> ' . esc_html($store_address) . '</p>';
			}
		}
		echo '</address></section>';
	}
	add_action('woocommerce_thankyou', 'add_custom_checkout_details_to_thankyou_page', 10, 1);
}



/**
 * Add a checkbox for "Text me with news and offers" after billing details.
 *
 * @since 1.0.0
 */
add_action('woocommerce_after_checkout_billing_form', function ($checkout) {
	echo '<p class="form-row form-row-wide woocommerce-validated" id="text_me_news_offers_field" data-priority="">';
	echo '<span class="woocommerce-input-wrapper">';
	echo '<input type="checkbox" name="text_me_news_offers" id="text_me_news_offers" value="1" class="input-checkbox">';
	echo '<label for="text_me_news_offers">' . __('Text me with news and offers', 'woomen') . ' <span class="optional">(' . __('optional', 'woomen') . ')</span></label>';
	echo '</span>';
	echo '</p>';
});


add_action('woocommerce_review_order_before_submit', function () {
	echo '<div id="save_user_details_field" class="save-user-details-field">
		<h3>' . esc_html__('Remember me', 'woomen') . '</h3>
            <p class="form-row">
                <input type="checkbox" id="save_user_details" name="save_user_details" value="yes" checked>
                <label for="save_user_details">' . __('Save my information for a faster checkout', 'woomen')  . '</label>
            </p>
          </div>';
});



if (!function_exists('custom_local_pickup_fields')) {
	/**
	 * Add custom fields to local pickup shipping method
	 *
	 * @since 1.0.0
	 * @hook woocommerce_shipping_instance_form_fields_local_pickup
	 *
	 * @param array $fields Existing shipping method fields
	 * @return array Modified fields array
	 */
	function custom_local_pickup_fields($fields)
	{
		$fields['attach_with_store'] = array(
			'title'       => __('Attach with Store', 'woomen'),
			'type'        => 'checkbox',
			'description' => __('Enable this option to attach this shipping method with a store.', 'woomen'),
			'default'     => 'no',
		);

		return $fields;
	}
	add_filter('woocommerce_shipping_instance_form_fields_local_pickup', 'custom_local_pickup_fields');
}



if (!function_exists('add_related_product_to_cart')) {
	function add_related_product_to_cart()
	{		
		// Validate inputs
		if (!isset($_POST['product_id'])) {
			wp_send_json_error(['message' => 'Product ID required']);
		}
		
		$product_id = absint($_POST['product_id']);
		$variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
		$quantity = 1;
		if ($variation_id > 0) {
			$cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
		} else {
			$cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
		}


		if ($cart_item_key) {
			wp_send_json_success(['message' => 'Product added to cart']);
		} else {
			wp_send_json_error(['message' => 'Failed to add product']);
		}
	}
	add_action('wp_ajax_add_related_product', 'add_related_product_to_cart');
	add_action('wp_ajax_nopriv_add_related_product', 'add_related_product_to_cart');
}
