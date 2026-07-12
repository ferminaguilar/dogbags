<?php
defined('ABSPATH') || exit;

if (! function_exists("woomen_dynamic_css")) {
	function woomen_dynamic_css()
	{
		$file_path = WOOMEN_PATH . 'assets/css/';
		$file_name = 'dynamic-css.css';

		//Options
		$primary_color        = woomen_get_setting('primary_color');
		$secondary_color      = woomen_get_setting('secondary_color');
		$a_hover_color      = woomen_get_setting('typography-a:hover');

		$button_text_color           = woomen_get_setting("button_text_color");
		$button_bg_color             = woomen_get_setting("button_bg_color");
		$button_border_color         = woomen_get_setting("button_border_color");
		$button_border_radius        = woomen_get_setting("button_border_radius");
		$button_hover_text_color     = woomen_get_setting("button_hover_text_color");
		$button_hover_bg_color       = woomen_get_setting("button_hover_bg_color");
		$button_hover_border_color   = woomen_get_setting("button_hover_border_color");


		$secondary_color_44 = $secondary_color . '44';


		$typography = '';
		$typos      = array(
			'body'  => true,
			'h1'    => true,
			'h2'    => true,
			'h3'    => true,
			'h4'    => true,
			'h5'    => true,
			'h6'    => true,
			'p'     => true,
			'label' => true,
			'a'     => true,
		);
		foreach ($typos as $tag => $is_tag) {
			$setting_id  = 'typography-' . $tag;
			$settings = woomen_get_setting($setting_id);
			$font_family = isset($settings["font-family"]) ? $settings["font-family"] : 'default-font-family';
			$font_weight = isset($settings["font-weight"]) ? $settings["font-weight"] : 'normal';
			$font_size   = isset($settings["font-size"]) ? $settings["font-size"] : '16px';
			$line_height = isset($settings["line-height"]) ? $settings["line-height"] : '1.5';
			$color       = isset($settings["color"]) ? $settings["color"] : '#000000';
			$letter_spacing = isset($settings["letter-spacing"]) ? $settings["letter-spacing"] : '0px';

			if (! $is_tag) {
				$tag = "." . $tag;
			}
			$typography .= $tag . '{';
			$typography .= 'font-size: ' . $font_size . ';';
			$typography .= 'line-height: ' . $line_height . ';';
			$typography .= 'font-weight: ' . $font_weight . ';';
			$typography .= 'font-family: ' . $font_family . ';';
			$typography .= 'letter-spacing: ' . $letter_spacing . ';';
			if ($tag !== 'span') {
				$typography .= 'color: ' . $color . ';';
			}
			$typography .= 'margin: 0 0 0 0;';
			$typography .= '}';
		}
		$body_typography = woomen_get_setting('typography-body')['font-family'] ?? '';

		$dynamic_css =
			/** @lang CSS */
			<<<CSS
		:host,
		:root,
		html,
		::after,
		::before {
			--primary-color: $primary_color; 
			--secondary-color: $secondary_color; 
			---secondary-color: $secondary_color_44;
			--a-hover-color: $secondary_color;
			--heading-color: #2c3e50;
			--descprition-color: #6e7da3;
			--deaf-font-color: #8894ad;
			--deaf-font-color-600: #8894ad1f;
			--deaf-font-color-400: #8894ad44;
			--label-font-color: #151e42;
			--input-font-color-700: #6d7ca3;
			--input-font-color-500: #6d7ca388;
			---input-font-color-500: #484f5f;
			--input-border-color: #d4dcff;
			--faded-font-color: #525c84;
			--primary-disabled: #0075ff22;
			--primary-border-color: #e5edf9;
			--primary-border-color-600: #e5edf966;
			---primary-border-color: #eaeef7;
			--grid-text-color: #424857;
			---grid-text-color: #838eaa;
			--overlay-color: #000000;
			--stats-bg-color: #fcfdfe;
			--stars-color: #d1e4fa;
			--dashboard-secondary-bg-color: #f5f9fd;
			--black-700: #000000;
			--white-700: #ffffff;
			--white-200: #ffffff22;
			--orange-700: #f8b849;
			--purple-700: #4339f2;
			--red-700: #fb295b;
			--green-700: #34b53a;
			--green-500: #5dc461;
			--grey-700: #808080;
			--cyan-700: #28F8C0;
			--input-radius: 0px;
			--border-radius-lg: 0px;
			--border-radius: 0px;
			--border-radius-md: 0px;
			--border-radius-xs: 0px;
			--primary-font: $body_typography, sans-serif;
			--icons-font: "Font Awesome 6 Free", emoji; 
		}
		$typography
		a,
		.woomen-navigation-nav li a
		{
			text-decoration: none;
		}
		a:hover{
			color: $a_hover_color;
		}
		body, button, input, textarea, select {
			font-family: $body_typography, sans-serif;
		}
		
		body .woomen-single-post-comments .comment-respond #commentform .form-submit #submit:hover,
        .wc-woomen-card-quick-checkout-popup.style1 .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button,
        body .wc-woomen-card-quick-checkout-popup .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .WM-buy-now-button-container .buy-now-button,
        body .wc-woomen-card-quick-checkout-popup.style3-combine .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button,
        body .woomen-checkout.woomen-checkout-classic .form-row.place-order #place_order:hover,
        body .woocommerce .cart .button[name="update_cart"]:hover, 
        body .woocommerce .woocommerce-cart-form .cart_totals .wc-proceed-to-checkout .checkout-button:hover,
        .wc-block-cart .wc-block-cart__submit-container:hover,
            body:not(.woocommerce-block-theme-has-button-styles) .wc-block-components-button:not(.is-link):hover,
        body .woo-sticky-cart-style_2 .woo-sticky-cart-product-attributes .cart .buy-now-button:hover,
        body .woo-sticky-cart-style_2 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button,
            body .woo-sticky-cart-style_1 .woo-sticky-cart-product-attributes .cart .buy-now-button:hover,
        body .woo-sticky-cart-style_1 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button,
            body .woo-sticky-cart-style_3 .woo-sticky-cart-product-attributes .cart .buy-now-button:hover,
        body .woo-sticky-cart-style_3 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button
        
        {
            color: $button_text_color !important;
            background: $button_bg_color !important;
            border-color:   $button_border_color !important;
        
        }
            body .woomen-single-post-comments .comment-respond #commentform .form-submit:hover:before{
                    color: $button_text_color !important;
            }
    body .woomen-single-post-comments .comment-respond #commentform .form-submit:before{
                    color: $button_hover_text_color !important;
            }
        body .woomen-single-post-comments .comment-respond #commentform .form-submit #submit,
        .wc-woomen-card-quick-checkout-popup.style1 .variations_form.cart .woocommerce-variation-add-to-cart .single_add_to_cart_button,
body .wc-woomen-card-quick-checkout-popup .variations_form.cart .woocommerce-variation-add-to-cart .WM-buy-now-button-container .buy-now-button,
        body .wc-woomen-card-quick-checkout-popup.style3-combine .variations_form.cart .woocommerce-variation-add-to-cart .single_add_to_cart_button,
        body .woomen-checkout.woomen-checkout-classic .form-row.place-order #place_order,
        body .woocommerce .cart .button[name="update_cart"], 
        body .woocommerce .woocommerce-cart-form .cart_totals .wc-proceed-to-checkout .checkout-button,
        .wc-block-cart .wc-block-cart__submit-container,
            body:not(.woocommerce-block-theme-has-button-styles) .wc-block-components-button:not(.is-link),
    body .woo-sticky-cart-style_2 .woo-sticky-cart-product-attributes .cart .buy-now-button,
        body .woo-sticky-cart-style_2 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart .single_add_to_cart_button,
            body .woo-sticky-cart-style_1 .woo-sticky-cart-product-attributes .cart .buy-now-button,
        body .woo-sticky-cart-style_1 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart .single_add_to_cart_button,
            body .woo-sticky-cart-style_3 .woo-sticky-cart-product-attributes .cart .buy-now-button,
        body .woo-sticky-cart-style_3 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart .single_add_to_cart_button{
            border-radius: $button_border_radius !important;
            transition: all 0.3s ease !important; 
        } 
        body .woomen-single-post-comments .comment-respond #commentform .form-submit #submit,
        .wc-woomen-card-quick-checkout-popup.style1 .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button:hover,
        body .wc-woomen-card-quick-checkout-popup .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .WM-buy-now-button-container .buy-now-button,
        body .wc-woomen-card-quick-checkout-popup.style3-combine .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button:hover,
        body .woomen-checkout.woomen-checkout-classic .form-row.place-order #place_order,
        body .woocommerce .cart .button[name="update_cart"], 
        body .woocommerce .woocommerce-cart-form .cart_totals .wc-proceed-to-checkout .checkout-button,
        .wc-block-cart .wc-block-cart__submit-container,
        body:not(.woocommerce-block-theme-has-button-styles) .wc-block-components-button:not(.is-link),
        body .woo-sticky-cart-style_2 .woo-sticky-cart-product-attributes .cart .buy-now-button,
        body .woo-sticky-cart-style_2 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button:hover ,
            body .woo-sticky-cart-style_3 .woo-sticky-cart-product-attributes .cart .buy-now-button,
        body .woo-sticky-cart-style_3 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button:hover ,
            body .woo-sticky-cart-style_1 .woo-sticky-cart-product-attributes .cart .buy-now-button,
        body .woo-sticky-cart-style_1 .woo-sticky-cart-product-attributes .variations_form.cart .woocommerce-variation-add-to-cart:not(.woocommerce-variation-add-to-cart-disabled) .single_add_to_cart_button:hover 
        {
            color: $button_hover_text_color !important;
            background: $button_hover_bg_color !important;
            border-color: $button_hover_border_color !important;
        }
	 
CSS;
		$dynamic_css .= apply_filters('woomen_dynamic_css', '');
		$dynamic_css = str_replace(array("\r", "\n", "\t"), '', $dynamic_css);
		$dynamic_css = "/**\n* Woomen Dynamic CSS\n* Note: This file contains dynamically generated CSS so if you make any changes within this file you will lose it.\n*/\n" . $dynamic_css;
		if (function_exists('woomen_file_force_contents')) {
			woomen_file_force_contents($file_path . $file_name, $dynamic_css);
		}
	}
}
add_action('cubewp/after/settings/saved', 'woomen_dynamic_css');