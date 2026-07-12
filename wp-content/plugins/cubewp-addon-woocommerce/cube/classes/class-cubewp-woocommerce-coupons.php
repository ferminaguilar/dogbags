<?php

defined( 'ABSPATH' ) || exit;

/**
 * CubeWp_Woocommerce_Coupons
 */
class CubeWp_Woocommerce_Coupons {
	public static $wooCommerce_post_type = 'shop_coupon';

	private static $wooCommerce_product_cat_taxonomy = 'product_cat';

	public function __construct() {
		add_filter( 'cubewp/builder/post_types', array(
			$this,
			'cubewp_woocommerce_coupons_into_cubewp',
		), 10, 2 );

		add_filter( 'cubewp/builder/post_type/custom/cubes/sections', array(
			$this,
			'cubewp_woocommerce_coupons_custom_cubes_section',
		), 10, 2 );

		add_filter( 'cubewp/custom/cube/field/options', array(
			$this,
			'cubewp_woocommerce_coupons_custom_cubes_options',
		) );

		add_filter( 'cubewp/' . self::$wooCommerce_post_type . '/after/submit/actions', array(
			$this,
			'cubewp_woocommerce_after_coupon_submission',
		), 11, 2 );

		add_filter( 'cubewp/frontend/field/parametrs', array(
			$this,
			'cubewp_woocommerce_fields_display_settings',
		), 20 );

		add_filter( 'cubewp/admin/field/parametrs', array(
			$this,
			'cubewp_woocommerce_fields_display_settings',
		), 20 );
	}

	/**
	 * @return void
	 */
	public static function init() {
		$CubeClass = __CLASS__;
		new $CubeClass();
	}

	public function cubewp_woocommerce_fields_display_settings( $args ) {
		$wc_switch_fields = array(
			'individual_use',
			'free_shipping',
			'exclude_sale_items'
		);
		if ( isset( $args['coupon_field_name'] ) ) {
			if ( isset( $args['value'] ) && ! empty( $args['value'] ) ) {
				if ( in_array( $args['coupon_field_name'], $wc_switch_fields ) ) {
					$args['value'] = ucfirst( $args['value'] );
				} else if ( $args['coupon_field_name'] == 'customer_email' ) {
					if ( is_array( $args['value'] ) ) {
						$args['value'] = implode( ',', $args['value'] );
					}
				}
			}
		}

		return $args;
	}

	/**
	 * Handle coupon submission
	 *
	 * @param array $return Response array
	 * @param array $coupon Coupon data
	 * @return array Modified response
	 */
	public function cubewp_woocommerce_after_coupon_submission($return, $coupon) {

		if (isset($coupon['post_id']) && !empty($coupon['post_id'])) {
			$coupon_id = absint($coupon['post_id']);

			if (get_post_status($coupon_id) != 'publish') {
				wp_update_post([
					'ID' => $coupon_id,
					'post_status' => 'publish'
				]);
			}

			// Sanitize and update coupon meta
			$meta_fields = [
				'individual_use',
				'free_shipping', 
				'exclude_sale_items'
			];
			
			foreach ($meta_fields as $field) {
				$value = get_post_meta($coupon_id, $field, true);
				update_post_meta($coupon_id, $field, strtolower(sanitize_text_field($value)));
			}

			$customer_email = get_post_meta($coupon_id, 'customer_email', true);
			if (!is_array($customer_email)) {
				$emails = array_map('sanitize_email', explode(',', $customer_email));
				update_post_meta($coupon_id, 'customer_email', $emails);
			}

			global $cwpOptions;
			$cwpOptions = !empty($cwpOptions) ? $cwpOptions : get_option('cwpOptions');
			
			$messages = [
				'pending' => esc_html__('Great news! Your coupon submission has been successfully received. However, it is currently undergoing a pending review. In the meantime, you can manage your coupons from the dashboard and be redirected there.', 'cubewp-woocommerce'),
				'published' => esc_html__('Great news! Your coupon submission has been successfully received. You can manage your coupons from the dashboard and be redirected there.', 'cubewp-woocommerce')
			];

			$status = (isset($cwpOptions['post_admin_approved'][self::$wooCommerce_post_type]) && 
					  $cwpOptions['post_admin_approved'][self::$wooCommerce_post_type] == 'pending') ||
					  get_post_status($coupon_id) != 'publish' ? 'pending' : 'published';
			
			$return['msg'] = $messages[$status];
			$return['redirectURL'] = !empty($cwpOptions['dashboard_page']) ? 
				esc_url(get_permalink($cwpOptions['dashboard_page'])) : 
				esc_url(home_url());
		}

		return $return;
	}

	/**
	 * Process coupon field options
	 *
	 * @param array $field Field data
	 * @return array Processed field data
	 */
	public function cubewp_woocommerce_coupons_custom_cubes_options($field) {
		if (empty($field) || !is_array($field)) {
			return [];
		}

		$field_name = !empty($field['name']) ? $field['name'] : '';
		if (empty($field_name)) {
			return $field;
		}

		$field['custom_name'] = $field_name == 'the_excerpt' ? 
			'cwp_user_form[the_excerpt]' : 
			'cwp_user_form[cwp_meta][' . $field_name . ']';
			
		$field['id'] = !empty($field['id']) ? $field['id'] : $field_name;
		$field['coupon_field_name'] = $field_name;

		if (isset($_GET['pid']) && !empty($_GET['pid'])) {
			$post_id = absint($_GET['pid']);
			$field['value'] = $field_name == 'the_excerpt' ? 
				get_the_excerpt($post_id) : 
				get_post_meta($post_id, $field_name, true);
		}

		return self::cubewp_woocommerce_coupons_add_custom_cubes_args($field_name, $field);
	}

	private static function cubewp_woocommerce_coupons_add_custom_cubes_args( $field_name, $field ) {
		$field['default_value'] = '';
		switch ( $field_name ) {
			case 'the_excerpt':
				$field['placeholder'] = esc_html__( 'Description (optional)', 'cubewp-woocommerce' );
				break;
			case 'discount_type':
				$_coupons_types = wc_get_coupon_types();
				$coupons_types  = array();
				if ( ! empty( $_coupons_types ) && is_array( $_coupons_types ) ) {
					$counter = 0;
					foreach ( $_coupons_types as $value => $label ) {
						$coupons_types['label'][ $counter ] = $label;
						$coupons_types['value'][ $counter ] = $value;
						$counter ++;
					}
				}
				$field['required']    = 1;
				$field['options']     = json_encode( $coupons_types );
				$field['description'] = '';
				break;
			case 'coupon_amount':
				$field['required']    = 1;
				$field['description'] = esc_html__( 'Value of the coupon.', 'cubewp-woocommerce' );
				break;
			case 'free_shipping':
				$field['description'] = esc_html__( 'Check this box if the coupon grants free shipping.', 'cubewp-woocommerce' );
				break;
			case 'expiry_date':
				$field['description'] = esc_html__( 'The coupon will expire at 00:00:00 of this date.', 'cubewp-woocommerce' );
				break;
			case 'minimum_amount':
				$field['description'] = esc_html__( 'This field allows you to set the minimum spend (subtotal) allowed to use the coupon.', 'cubewp-woocommerce' );
				$field['placeholder'] = esc_html__( 'No minimum', 'cubewp-woocommerce' );
				break;
			case 'maximum_amount':
				$field['description'] = esc_html__( 'This field allows you to set the maximum spend (subtotal) allowed when using the coupon.', 'cubewp-woocommerce' );
				$field['placeholder'] = esc_html__( 'No maximum', 'cubewp-woocommerce' );
				break;
			case 'individual_use':
				$field['description'] = esc_html__( 'Check this box if the coupon cannot be used in conjunction with other coupons.', 'cubewp-woocommerce' );
				break;
			case 'exclude_sale_items':
				$field['description'] = esc_html__( 'Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'cubewp-woocommerce' );
				break;
			case 'product_ids':
				$field['filter_post_types']    = CubeWp_Woocommerce_Products::$wooCommerce_post_type;
				$field['appearance']           = 'multi_select';
				$field['select2_ui']           = 1;
				$field['current_user_posts']   = 1;
				$field['relationship']         = 0;
				$field['auto_complete']        = 1;
				$field['files_save_separator'] = ',';
				$field['description']          = esc_html__( 'Products that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'cubewp-woocommerce' );
				break;
			case 'exclude_product_ids':
				$field['filter_post_types']    = CubeWp_Woocommerce_Products::$wooCommerce_post_type;
				$field['appearance']           = 'multi_select';
				$field['select2_ui']           = 1;
				$field['current_user_posts']   = 1;
				$field['relationship']         = 0;
				$field['auto_complete']        = 1;
				$field['files_save_separator'] = ',';
				$field['description']          = esc_html__( 'Products that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied.', 'cubewp-woocommerce' );
				break;
			case 'product_categories':
				$field['filter_taxonomy'] = self::$wooCommerce_product_cat_taxonomy;
				$field['appearance']      = 'multi_select';
				$field['select2_ui']      = 1;
				$field['auto_complete']   = 1;
				$field['required']        = 0;
				$field['description']     = esc_html__( 'Product categories that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'cubewp-woocommerce' );
				break;
			case 'exclude_product_categories':
				$field['filter_taxonomy'] = self::$wooCommerce_product_cat_taxonomy;
				$field['appearance']      = 'multi_select';
				$field['select2_ui']      = 1;
				$field['auto_complete']   = 1;
				$field['required']        = 0;
				$field['description']     = esc_html__( 'Product categories that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied.', 'cubewp-woocommerce' );
				break;
			case 'customer_email':
				$field['description'] = esc_html__( 'List of allowed billing emails to check against when an order is placed. Separate email addresses with commas. You can also use an asterisk (*) to match parts of an email. For example "*@gmail.com" would match all gmail addresses.', 'cubewp-woocommerce' );
				$field['placeholder'] = esc_html__( 'No restrictions', 'cubewp-woocommerce' );
				break;
			case 'usage_limit':
				$field['description'] = esc_html__( 'How many times this coupon can be used before it is void.', 'cubewp-woocommerce' );
				$field['placeholder'] = esc_html__( 'Unlimited Usage', 'cubewp-woocommerce' );
				break;
			case 'limit_usage_to_x_items':
				$field['conditional']          = true;
				$field['conditional_field']    = 'discount_type';
				$field['conditional_operator'] = '!=';
				$field['conditional_value']    = 'fixed_cart';
				$field['description']          = esc_html__( 'The maximum number of individual items this coupon can apply to when using product discounts. Leave blank to apply to all qualifying items in cart.', 'cubewp-woocommerce' );
				$field['placeholder']          = esc_html__( 'Apply to all qualifying items in cart', 'cubewp-woocommerce' );
				break;
			case 'usage_limit_per_user':
				$field['description'] = esc_html__( 'How many times this coupon can be used by an individual user. Uses billing email for guests, and user ID for logged in users.', 'cubewp-woocommerce' );
				$field['placeholder'] = esc_html__( 'Unlimited Usage', 'cubewp-woocommerce' );
				break;
		}
		$field['value'] = ! empty( $field['value'] ) ? $field['value'] : $field['default_value'];

		return $field;
	}

	public function cubewp_woocommerce_coupons_into_cubewp( array $post_types, $builder ) {
		if ( $builder == 'post_types' ) {
			$post_types[ self::$wooCommerce_post_type ] = esc_html__( 'Coupons', 'cubewp-woocommerce' );
		}

		return $post_types;
	}

	public function cubewp_woocommerce_coupons_custom_cubes_section( $sections, $post_type ) {
		if ( $post_type == self::$wooCommerce_post_type ) {
			$sections[] = [
				'section_title'       => esc_html__( 'Woo Coupon Fields', 'cube-framework' ),
				'section_description' => '',
				'section_class'       => '',
				'open_close_class'    => 'close',
				'form_relation'       => $post_type,
				'form_type'           => 'post_type',
				'fields'              => self::cubewp_woocommerce_coupons_custom_cubes(),
				'section_type'        => 'group_fields',
			];
		}

		return $sections;
	}

	private static function cubewp_woocommerce_coupons_custom_cubes() {
		$cubes                               = [];
		$cubes['the_excerpt']                = [
			'label' => esc_html__( 'Description', 'cubewp-woocommerce' ),
			'name'  => 'the_excerpt',
			'type'  => 'textarea',
		];
		$cubes['discount_type']              = [
			'label' => esc_html__( 'Discount Type', 'cubewp-woocommerce' ),
			'name'  => 'discount_type',
			'type'  => 'dropdown',
		];
		$cubes['coupon_amount']              = [
			'label' => esc_html__( 'Coupon Amount', 'cubewp-woocommerce' ),
			'name'  => 'coupon_amount',
			'type'  => 'number',
		];
		$cubes['free_shipping']              = [
			'label' => esc_html__( 'Allow Free Shipping', 'cubewp-woocommerce' ),
			'name'  => 'free_shipping',
			'type'  => 'switch',
		];
		$cubes['expiry_date']                = [
			'label' => esc_html__( 'Coupon Expiry Date', 'cubewp-woocommerce' ),
			'name'  => 'expiry_date',
			'type'  => 'date_picker',
		];
		$cubes['minimum_amount']             = [
			'label' => esc_html__( 'Minimum Spend', 'cubewp-woocommerce' ),
			'name'  => 'minimum_amount',
			'type'  => 'number',
		];
		$cubes['maximum_amount']             = [
			'label' => esc_html__( 'Maximum Spend', 'cubewp-woocommerce' ),
			'name'  => 'maximum_amount',
			'type'  => 'number',
		];
		$cubes['individual_use']             = [
			'label' => esc_html__( 'Individual Use Only', 'cubewp-woocommerce' ),
			'name'  => 'individual_use',
			'type'  => 'switch',
		];
		$cubes['exclude_sale_items']         = [
			'label' => esc_html__( 'Exclude Sale Items', 'cubewp-woocommerce' ),
			'name'  => 'exclude_sale_items',
			'type'  => 'switch',
		];
		$cubes['product_ids']                = [
			'label' => esc_html__( 'Products', 'cubewp-woocommerce' ),
			'name'  => 'product_ids',
			'type'  => 'post',
		];
		$cubes['exclude_product_ids']        = [
			'label' => esc_html__( 'Exclude Products', 'cubewp-woocommerce' ),
			'name'  => 'exclude_product_ids',
			'type'  => 'post',
		];
		$cubes['product_categories']         = [
			'label' => esc_html__( 'Product Categories', 'cubewp-woocommerce' ),
			'name'  => 'product_categories',
			'type'  => 'taxonomy',
		];
		$cubes['exclude_product_categories'] = [
			'label' => esc_html__( 'Exclude Categories', 'cubewp-woocommerce' ),
			'name'  => 'exclude_product_categories',
			'type'  => 'taxonomy',
		];
		$cubes['customer_email']             = [
			'label' => esc_html__( 'Allowed Emails', 'cubewp-woocommerce' ),
			'name'  => 'customer_email',
			'type'  => 'text',
		];
		$cubes['usage_limit']                = [
			'label' => esc_html__( 'Usage Limit Per Coupon', 'cubewp-woocommerce' ),
			'name'  => 'usage_limit',
			'type'  => 'number',
		];
		$cubes['limit_usage_to_x_items']     = [
			'label' => esc_html__( 'Limit usage to X items', 'cubewp-woocommerce' ),
			'name'  => 'limit_usage_to_x_items',
			'type'  => 'number',
		];
		$cubes['usage_limit_per_user']       = [
			'label' => esc_html__( 'Usage Limit Per User', 'cubewp-woocommerce' ),
			'name'  => 'usage_limit_per_user',
			'type'  => 'number',
		];

		return apply_filters( 'cubewp/builder/woocommerce/coupons/fields', $cubes );
	}
}
