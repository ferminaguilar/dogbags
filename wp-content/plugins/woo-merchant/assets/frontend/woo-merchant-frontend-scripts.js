jQuery(function ($) {

    // Sale Countdown times
    sale_countdown();

    jQuery('body').on('click', '.add_to_cart_button', function (e) {
        e.preventDefault();

        var $thisbutton = jQuery(this);

        if ($thisbutton.is('.ajax_add_to_cart')) {
            // Trigger WooCommerce add to cart event
            jQuery.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_add_to_cart',
                    product_id: $thisbutton.data('product_id'),
                    product_sku: $thisbutton.data('product_sku'),
                    quantity: $thisbutton.data('quantity')
                },
                success: function (response) {
                    if (!response) {
                        return;
                    }

                    // Update fragments and open quick cart
                    if (response.fragments) {
                        jQuery.each(response.fragments, function (key, value) {
                            jQuery(key).replaceWith(value);
                        });

                        jQuery('#quick-cart').addClass('open');
                    }

                    // Trigger event after cart updated
                    jQuery('body').trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
            });

            return false;
        }
    });


    // Optional: Close the quick cart when clicking outside of it
    jQuery(document).on('click', function (event) {
        if (!jQuery(event.target).closest('#quick-cart, .add_to_cart_button').length) {
            jQuery('#quick-cart').removeClass('open');
        }
    });

    // Add all to cart Frequently bought products
    // Cross sell products
    if ($('.add-all-cross-sells-to-cart').length > 0) {
        $('.add-all-cross-sells-to-cart').on('click', function (e) {
            e.preventDefault(); // Prevent default action
            var $cross_container = $(this).closest('.woo-merchant-cross-sells');
            var products = [];
            $cross_container.find('.woo-cross-sell-checkbox:checked').each(function () {
                products.push({
                    id: $(this).val(),
                    quantity: $(this).data('qty') || 1 // Default to 1 if no quantity provided
                });
            });

            if (products.length > 0) {
                // Send a single AJAX request with all selected products
                $.ajax({
                    type: 'POST',
                    url: wooMerchantParams.ajax_url, // This URL is localized from PHP
                    data: {
                        action: 'add_multiple_to_cart',
                        products: products,
                    },
                    success: function (response) {
                        if (response.success) {
                            $(document.body).trigger('wc_fragment_refresh');
                            // Show success message after adding all items to cart
                            $('<div class="woocommerce-info" role="alert">' + wooMerchantParams.success + '</div>').appendTo('.woocommerce-notices-wrapper');
                            // Optionally scroll to the top of the page to show the notice
                            $('html, body').animate({
                                scrollTop: 0
                            }, 'slow');
                        } else {
                            // Show error message if something went wrong
                            if (typeof cwp_notification_ui == 'function') {
                                cwp_notification_ui('error', response.data);
                            } else {
                                $(document.body).trigger('wc_fragment_refresh');
                                $('<div class="woocommerce-error" role="alert">' + response.data + '</div>').appendTo('.woocommerce-notices-wrapper');
                                $('html, body').animate({
                                    scrollTop: 0
                                }, 'slow');
                            }

                        }
                    }
                });
            }
        });
    }

    jQuery(document).on('click', '.buy-now-button', function (e) {
        e.preventDefault();
        var $this = jQuery(this);
        var $form = $this.closest('form.cart');

        // Get product data
        var productID = $this.data('product-id');
        var quantity = $form.find('input[name="quantity"]').val() || 1;
        var variation_id = null;
        var variation_data = {};

        // Check if variable product
        if ($this.hasClass('wm-buy-now-variable')) {
            variation_id = $form.find('input[name="variation_id"]').val();

            // Collect selected variation attributes
            $form.find('select[name^="attribute_"]').each(function () {
                var attribute_name = jQuery(this).attr('name');
                var attribute_value = jQuery(this).val();
                if (attribute_value) {
                    variation_data[attribute_name] = attribute_value;
                }
            });
        }

        // Base checkout URL
        var checkoutUrl = $this.closest('.WM-buy-now-button-container').data('checkout-url');
        checkoutUrl += '?add-to-cart=' + productID + '&quantity=' + quantity;

        // Append variation data if applicable
        if (variation_id) {
            checkoutUrl += '&variation_id=' + variation_id;
            for (var key in variation_data) {
                if (variation_data.hasOwnProperty(key)) {
                    checkoutUrl += '&' + encodeURIComponent(key) + '=' + encodeURIComponent(variation_data[key]);
                }
            }
        }

        // Redirect to the checkout page
        window.location.href = checkoutUrl;
    });



    // Get the modal
    var modal = jQuery('#sizeGuideModal');

    // Get the button that opens the modal
    var btn = jQuery('.size-guide-button');

    // Get the <span> element that closes the modal
    var span = jQuery('.size-guide-modal-content .close');

    // When the user clicks the button, open the modal
    btn.on('click', function () {
        modal.show();
    });

    // When the user clicks on <span> (x), close the modal
    span.on('click', function () {
        modal.hide();
    });

    // When the user clicks anywhere outside of the modal, close it
    jQuery(window).on('click', function (event) {
        if (jQuery(event.target).is(modal)) {
            modal.hide();
        }
    });

    // Function to update totals
    function update_cross_sell_totals($this) {
        let totalItems = 0;
        let totalPrice = 0;
        let totalDiscount = 0;

        // Loop through each checked checkbox
        $this.find(".woo-cross-sell-checkbox:checked").each(function () {
            const price = parseFloat($(this).data("price")) || 0;
            const discount = parseFloat($(this).data("discount-value")) || 0;

            totalPrice += price;
            totalDiscount += discount;
            totalItems++;
        });

        // Update the count, total price, and discount display
        $this.find(".selected-items-count").text(totalItems);
        $this.find(".selected-items-price").text(wc_price_format(totalPrice));
        $this.find(".items-discount-value").text(wc_price_format(totalDiscount));

        if (totalItems > 0) {
            $this.find(".woo-merchant-cross-sells-pricing").show();
            $this.find(".add-all-cross-sells-to-cart").prop('disabled', false);
        } else {
            $this.find(".woo-merchant-cross-sells-pricing").hide();
            $this.find(".add-all-cross-sells-to-cart").prop('disabled', true);
        }

        if (totalDiscount > 0) {
            $this.find(".items-discount-value").show();
        } else {
            $this.find(".items-discount-value").hide();
        }
    }

    // Format price in WooCommerce format (example assumes $ as currency)
    function wc_price_format(amount) {
        return `$${amount.toFixed(2)}`;
    }

    // Event listener for checkbox toggle
    $(".woo-cross-sell-checkbox").on("change", function () {
        var $cross_container = $(this).closest('.woo-merchant-cross-sells');
        update_cross_sell_totals($cross_container);
    });

    // Initial update on page load
    $('.woo-merchant-cross-sells').each(function () {
        update_cross_sell_totals($(this));
    });


});

function sale_countdown() {
    var endTime = jQuery('.wm-sale-end-countdown').data('sale-end-time');
    var saleEndDate = new Date(endTime).getTime();
    var countdownTimer = setInterval(function () {
        var now = new Date().getTime();
        var distance = saleEndDate - now;

        // Calculate time components
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Add leading zero to single-digit values
        days = days < 10 ? "0" + days : days;
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Update the DOM with calculated values
        jQuery('#countdown-days .time').text(days);
        jQuery('#countdown-hours .time').text(hours);
        jQuery('#countdown-minutes .time').text(minutes);
        jQuery('#countdown-seconds .time').text(seconds);

        // Check if the countdown has expired
        if (distance < 0) {
            clearInterval(countdownTimer);
            jQuery('.time').text("00");
        }
    }, 1000);
}


jQuery(document).ready(function ($) {
    // Check if there's a discount message from the PHP side
    if (typeof wooMerchantDiscount !== 'undefined' && wooMerchantDiscount.discount_message) {
        // Add WooCommerce notice dynamically
        $('body').append('<div class="woocommerce-message">' + wooMerchantDiscount.discount_message + '</div>');

        // Optional: Auto-remove the notice after a few seconds
        setTimeout(function () {
            $('.woocommerce-message').fadeOut('slow', function () {
                $(this).remove();
            });
        }, 5000);
    }
});

/*---------- Size Guide Canvas ------------*/
jQuery(document).ready(function ($) {
    $('.wm-size-guide-icon-title').on('click', function () {
        $('.wm-size-guide-canvas').css('display', 'flex').hide().fadeIn();
    });

    $('.wm-size-guide-close').on('click', function () {
        $('.wm-size-guide-canvas').fadeOut();
    });
});

/*------------ Buy Now Button -----------------*/
jQuery(document).ready(function ($) {
    function updateBuyNowButton($form) {
        const $forms = $form;
        const $buyNowBtn = $forms.find('.wm-buy-now-variable');
        const $preOrderNotice = $forms.find('.wm-pre-order-notice-container.wm-variable-product');
        const selectedVariation = $forms.find('input.variation_id').val();
        const isDisabled = $forms.find('.single_add_to_cart_button').hasClass('disabled');
        if (selectedVariation && !isDisabled) {
            $buyNowBtn.removeClass('disabled wc-variation-selection-needed')
                .prop('disabled', false)
                .attr('data-variation-id', selectedVariation);
        } else {
            $buyNowBtn.addClass('disabled wc-variation-selection-needed')
                .prop('disabled', true);
            $preOrderNotice.slideUp();
        }
        setTimeout(() => {
            if ($forms.find('.single_add_to_cart_button').hasClass('disabled wc-variation-selection-needed')) {
                $buyNowBtn.addClass('disabled wc-variation-selection-needed').prop('disabled', true);
                $('.woo-sticky-cart .wm-buy-now-variable,.elementor-widget-vp_product_variations .wm-buy-now-variable').addClass('disabled wc-variation-selection-needed').prop('disabled', true);
                $('.woo-sticky-cart .single_add_to_cart_button,.elementor-widget-vp_product_variations .single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                $preOrderNotice.slideUp();
            } else {
                $buyNowBtn.removeClass('disabled wc-variation-selection-needed').prop('disabled', false).attr('data-variation-id', selectedVariation);
                $('.woo-sticky-cart .single_add_to_cart_button,.elementor-widget-vp_product_variations .single_add_to_cart_button').removeClass('disabled wc-variation-selection-needed').prop('disabled', false).attr('data-variation-id', selectedVariation);
                $('.woo-sticky-cart .wm-buy-now-variable,.elementor-widget-vp_product_variations .wm-buy-now-variable').removeClass('disabled wc-variation-selection-needed').prop('disabled', false).attr('data-variation-id', selectedVariation);
            }
        }, 1000);
    }
    // Update on variation change
    jQuery(document).on('woocommerce_variation_has_changed', 'form.variations_form', function () {
        setTimeout(updateBuyNowButton($(this)), 600);
    });
    // Reset on reset
    jQuery(document).on('reset_data', 'form.variations_form', function () {
        jQuery('.wm-buy-now-variable').addClass('disabled wc-variation-selection-needed').prop('disabled', true);
        jQuery('.wm-pre-order-notice-container.wm-variable-product').slideUp();
    });
    jQuery(".single_variation_wrap").on("show_variation", function (event, variation) {
        if (variation.wm_pre_order == true) {
            jQuery('.single_add_to_cart_button').html(wooMerchantParams.preOrder);
            jQuery('.wm-pre-order-notice-container.wm-variable-product').slideDown();
        } else {
            jQuery('.wm-pre-order-notice-container.wm-variable-product').slideUp();
            jQuery('.single_add_to_cart_button').html(wooMerchantParams.addToCart);
        }
    });
    // Initial state on load
    setTimeout(updateBuyNowButton($('form.variations_form')), 600);
});