(function ($) {
  "use strict";
  jQuery(document).ready(function ($) {
    // Sticky Section JS with GSAP
    const stickyEls = document.querySelectorAll(".vp-sticky-section");
    if (stickyEls.length > 0 && $(window).width() > 990) {
      $(".vp-sticky-section").each(function () {
        $(this).parent().css("overflow", "initial");
      });
      // Make all vp-sticky-section elements sticky
      $(".vp-sticky-section").css({
        position: "sticky",
        top: 0,
        zIndex: 10,
      });
      // Add GSAP animations
      stickyEls.forEach(el => {
        let tl = gsap.timeline({
          scrollTrigger: {
            trigger: el,
            start: "bottom 70%",
            end: "bottom center",
            markers: false,
            toggleActions: "play none reverse none",
            scrub: 1,
          },
        });
        tl.to(el, {
          opacity: 0,
          yPercent: -10
        });
      });
    }

    // Function to check width and handle the sticky sidebar
    const sidebar = jQuery(".single-vp-sidebar-top");
    if (sidebar.length > 0) {
      const container = jQuery(".vp-main-content-contaner");
      var sidebarWidth = sidebar.outerWidth();
      var $window = jQuery(window);
      if ($window.width() > 990) {
        jQuery(window).on("scroll", function () {
          const containerTop = container.offset().top;
          const containerBottom = containerTop + container.outerHeight();
          const scrollTop = jQuery(window).scrollTop();
          const windowHeight = jQuery(window).height();

          // Add 'sticky' class when the container top is at or above the viewport top
          if (scrollTop >= containerTop) {
            sidebar.addClass("sticky").css("width", sidebarWidth + "px");
          } else {
            sidebar.removeClass("sticky").css("width", "");
          }

          // Add 'footers-section' class when the container bottom is at or above the viewport bottom
          if (scrollTop + windowHeight >= containerBottom) {
            sidebar.addClass("footers-section");
          } else {
            sidebar.removeClass("footers-section");
          }
        });
      }
    }

    const loaderHTML = `
          <div class="woo-ajax-loader"> 
              <svg class="woo-ajax-loader-svg" viewBox="25 25 50 50">
                  <circle r="20" cy="50" cx="50"></circle>
              </svg>
          </div>`;

    function appendLoader($element, $parentContainer) {
      // Append loader to the appropriate container
      if (!$parentContainer.find(".woo-ajax-loader").length) {
        $parentContainer.append(loaderHTML);
        $parentContainer.addClass("loader-active"); // Add the new class to the parent container
      }
    }

    function removeLoader($element, $parentContainer) {
      $parentContainer.find(".woo-ajax-loader").remove();
      $parentContainer.removeClass("loader-active"); // Remove the new class from the parent container
    }

    function observeClassChanges($element, $parentContainer) {
      const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
          if (mutation.attributeName === "class") {
            const hasActiveClass =
              $element.hasClass("cubewp-processing-ajax") ||
              $element.hasClass("value-pack-processing-ajax") ||
              $element.hasClass("cubewp-active-ajax");
            if (hasActiveClass) {
              appendLoader($element, $parentContainer);
            } else {
              removeLoader($element, $parentContainer);
            }
          }
        });
      });

      // Start observing the element for attribute changes
      observer.observe($element[0], {
        attributes: true,
      });

      // Store the observer to ensure it doesn't get duplicated for the same element
      $element.data("observer", observer);
    }
    // Attach click event to submit button and save post button
    $(
      '.cwp-form-submit-container [type="submit"],.single_add_to_cart_button'
    ).on("click", function () {
      const $this = $(this);

      // Check if parent has .cwp-form-submit-container, if so, set it as $parentContainer
      const $parentContainer = $this.closest(".cwp-form-submit-container")
        .length ?
        $this.closest(".cwp-form-submit-container") :
        $this;

      // Check if the element already has an observer, if not, create one
      if (!$this.data("observer")) {
        observeClassChanges($this, $parentContainer);
      }
    });

    // user quick checkout
    $(document).on("click", ".women-wc-quick-checkout", function (event) {
      event.preventDefault();
      var wc_productID = $(this).data("productid"),
        $this = $(this);
      // $(this).append('<i class="fa-solid  fa-spin fa-arrows-spin"></i>');
      $this.attr("disabled", true);
      $this.append(`
      <div class="woo-ajax-loader"> 
        <svg class="woo-ajax-loader-svg" viewBox="25 25 50 50">
          <circle r="20" cy="50" cx="50"></circle>
        </svg>
      </div>
    `);
      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
          action: "value_pack_card_quick_checkout",
          pID: wc_productID,
        },
        success: function (response) {

          $this.attr("disabled", false);
          // $this.find('i').remove();
          if (response.type === "success") {
            $("body").append(response.html);
            $this.find(".woo-ajax-loader").remove();
            setTimeout(function () {
              if (
                $(".wc-woomen-card-quick-checkout-popup.style1 .product-gallery")
                  .length > 0
              ) {
                $(
                  ".wc-woomen-card-quick-checkout-popup.style1 .product-gallery"
                ).slick({
                  dots: false,
                  infinite: true,
                  speed: 300,
                  prevArrow: '<i class="fa-solid fa-chevron-left wc-gallery-prev-action"></i>',
                  nextArrow: '<i class="fa-solid fa-chevron-right wc-gallery-next-action"></i>',
                  variableWidth: true,
                  adaptiveHeight: true,
                });
              }
              if (
                $(
                  ".wc-woomen-card-quick-checkout-popup.style3-combine .wc-women-style2-checkout"
                ).length > 0
              ) {
                $(
                  ".wc-woomen-card-quick-checkout-popup.style3-combine .wc-women-style2-checkout"
                ).slick({
                  dots: false,
                  infinite: true,
                  speed: 300,
                  prevArrow: '<i class="fa-solid fa-chevron-left wc-gallery-prev-action"></i>',
                  nextArrow: '<i class="fa-solid fa-chevron-right wc-gallery-next-action"></i>',
                  variableWidth: false,
                  slidesToShow: 1,
                  adaptiveHeight: true,
                });
              }
              $(".wc-woomen-card-quick-checkout-popup").addClass("active");

              var $sizeGuideLink = $('.wc-woomen-card-quick-checkout-popup.style3-combine .wc-woomen-card-popup-details-main .wc-woomen-size-guide-link');
              if ($sizeGuideLink.length) {
                var $targetForm = $sizeGuideLink.closest('.wc-woomen-card-popup-details-main').find('.wm-product-attributes');
                if ($targetForm.length) {
                  $sizeGuideLink.appendTo($targetForm);
                }
              }
            }, 200);
            $('.variations_form').each(function () {
              $(this).wc_variation_form();
              $(this).find('.variations select').trigger('change');
            });
          } else {
            cwp_notification_ui("error", response.msg);
          }
        },
      });
    });

    $(document).on("click", ".wc-woomen-card-popup-close", function (event) {
      event.preventDefault();
      $(".wc-woomen-card-quick-checkout-popup").removeClass("active");
      setTimeout(() => {
        $(this).closest(".wc-woomen-card-quick-checkout-popup").remove();
      }, 300);
    });
    $(document).on('click', '.wc-woomen-card-quick-checkout-popup', function (e) {
      if (e.target === this) {
        $('.wc-woomen-card-quick-checkout-popup').removeClass('active');
        setTimeout(() => {
          $(this).closest(".wc-woomen-card-quick-checkout-popup").remove();
        }, 300);
      }
    });

    if (jQuery(".vp-login-modal").length > 1) {
      jQuery(".vp-login-modal:gt(0)").remove(); // Remove duplicates if any
    }

    // Move the modal to the body if it's not already there
    if (
      jQuery(".vp-login-modal").length === 1 &&
      jQuery(".vp-login-modal").parent()[0] !== document.body
    ) {
      jQuery(".vp-login-modal").appendTo("body");
    }
    if (jQuery(".woocommerce-open-search-main").length > 1) {
      jQuery(".woocommerce-open-search-main:gt(0)").remove();
    }

    // Move the modal to the body if it's not already there
    if (
      jQuery(".woocommerce-open-search-main").length === 1 &&
      jQuery(".woocommerce-open-search-main").parent()[0] !== document.body
    ) {
      jQuery(".woocommerce-open-search-main").appendTo("body");
    }

    $(document).on("click", ".vp-size-guide-icon-title", function () {
      $(".vp-size-guide-canvas").css("display", "flex").hide().fadeIn();
    });

    $(document).on("click", ".vp-size-guide-close", function () {
      $(".vp-size-guide-canvas").fadeOut();
    });

    if (jQuery(".vp-size-guide-canvas").length > 1) {
      jQuery(".vp-size-guide-canvas:gt(0)").remove(); // Remove duplicates if any
    }

    // Move the modal to the body if it's not already there
    if (
      jQuery(".vp-size-guide-canvas").length === 1 &&
      jQuery(".vp-size-guide-canvas").parent()[0] !== document.body
    ) {
      jQuery(".vp-size-guide-canvas").appendTo("body");
    }

    if (jQuery(".vp-cart-popup").length > 1) {
      jQuery(".vp-cart-popup:gt(0)").remove(); // Remove duplicates if any
    }

    if (
      jQuery(".vp-cart-popup").length === 1 &&
      jQuery(".vp-cart-popup").parent()[0] !== document.body
    ) {
      jQuery(".vp-cart-popup").appendTo("body");
    }

    // Handle Search Popup
    if (jQuery(".vp-search-popup-main").length > 1) {
      jQuery(".vp-search-popup-main:gt(0)").remove(); // Remove duplicates if any
    }

    if (
      jQuery(".vp-search-popup-main").length === 1 &&
      jQuery(".vp-search-popup-main").parent()[0] !== document.body
    ) {
      jQuery(".vp-search-popup-main").appendTo("body");
    }


    // sticky cart JS start
    if ($(".woo-sticky-cart").length > 0) {
      var stickyCart = $(".woo-sticky-cart");
      var ScrollDistance = 1000;
      $(window).on("scroll", function () {
        if ($(this).scrollTop() > ScrollDistance) {
          stickyCart.addClass("is-fixed");
        } else {
          stickyCart.removeClass("is-fixed");
        }
      });

      $(".woo-sticky-cart-style_2 .woo-sticky-cart-product-attributes").hide();

      $(".woo-sticky-cart-style2-content .cart-attributes").on(
        "click",
        function () {
          var $cartAttributes = $(this)
            .closest(".woo-sticky-cart-style2-content")
            .siblings(".woo-sticky-cart-product-attributes");
          $cartAttributes.slideDown(200);
          $(this).hide();
          $(this).siblings(".cart-attributes-closed").show();
        }
      );

      $(".woo-sticky-cart-style2-content .cart-attributes-closed").on(
        "click",
        function () {
          var $cartAttributes = $(this)
            .closest(".woo-sticky-cart-style2-content")
            .siblings(".woo-sticky-cart-product-attributes");
          $cartAttributes.slideUp(200);
          $(this).hide();
          $(this).siblings(".cart-attributes").show();
        }
      );
    }

    jQuery(document).on(
      "click",
      ".shop_table.woocommerce-cart-form__contents tbody tr .product-quantity .quantity button",
      function () {
        jQuery(this)
          .closest("form")
          .find('.button[name="update_cart"]')
          .removeAttr("disabled");
      }
    );

    if (jQuery(".vp-demo8-this-look-slider > .e-con-inner").length > 0) {
      jQuery(".vp-demo8-this-look-slider > .e-con-inner").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        infinite: true,
        fade: false,
        prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
      });
    }

    // js for compare products

    // Js for mini cart

    function maxVal(val, itsmax) {
      if (val <= itsmax && val >= 0) return val;
      else if (val < 0) return 0;
      else return itsmax;
    }
    setTimeout(function () {
      var get_width = jQuery(".wc-woo-img-comp-container").width();
      jQuery(".wc-woo-img-comp-responsive img").css("width", get_width + "px");
    });

    jQuery(".img-comp-overlay").each(function () {
      var slider = jQuery(this)
        .parents(".wc-woo-img-comp-container")
        .find(".wc-woo-img-comp-slider");
      var img = jQuery(this).find("img");

      var slidef = function () {
        var e = window.event;
        var x = img.offset().left;
        img
          .parent()
          .width(maxVal(e.pageX - x, jQuery(".img-comp-img").width()));
        slider.css(
          "left",
          maxVal(e.pageX - x, jQuery(".img-comp-img").width()) + "px"
        );
      };

      //Mouse was pressed
      slider.on("mousedown", function (e) {
        e.preventDefault();
        jQuery(window).on("mousemove.slideev", this, slidef);
      });

      jQuery(window).on("mouseup", function (e) {
        e.preventDefault();
        jQuery(window).off("mousemove.slideev");
      });

      //Finger is swiping
      slider.on("touchmove", function (e) {
        e.preventDefault();
        var t = e.touches[0];
        var x = img.offset().left;
        img
          .parent()
          .width(maxVal(t.pageX - x, jQuery(".img-comp-img").width()));
        slider.css(
          "left",
          maxVal(t.pageX - x, jQuery(".img-comp-img").width()) + "px"
        );
      });
    });

    // Show the popup and update cart content on mouse enter
    if ($(".mini-cart-widget").length > 0) {
      $(document).on("click", ".mini-cart-widget", function () {
        $(".vp-cart-popup").addClass("active");
        jQuery(
          ".vp-cart-style-two-may-also-like-items:not(.slick-initialized)"
        ).slick({
          slidesToShow: 3.25,
          slidesToScroll: 1,
          infinite: false,
          nextArrow: ".vp-cart-style-two-ymal-next",
          prevArrow: ".vp-cart-style-two-ymal-prev",
          responsive: [{
            breakpoint: 992,
            settings: {
              slidesToShow: 2.25,
            },
          },],
        });
      });
    }

    // Close the popup when clicking the close button
    $(document).on("click", ".vp-cart-items-close", function () {
      $(".vp-cart-popup").removeClass("active");
    });

    function initializeSlick_like_products() {
      if ($(window).width() < 990) {
        if (!$(".vp-cart-may-also-like-items").hasClass("slick-initialized")) {
          if (jQuery(".vp-cart-may-also-like-items").length > 0) {
            $(".vp-cart-may-also-like-items").slick({
              variableWidth: true,
              dots: false,
              arrows: true,
              prevArrow: '<button type="button" class="slick-prev"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.3194 5.01386C13.0509 4.74538 12.6156 4.74538 12.3471 5.01386L6.84715 10.5139C6.57865 10.7824 6.57865 11.2176 6.84715 11.4861L12.3471 16.9861C12.6156 17.2546 13.0509 17.2546 13.3194 16.9861C13.5879 16.7176 13.5879 16.2824 13.3194 16.0139L8.30556 11L13.3194 5.98613C13.5879 5.71765 13.5879 5.28234 13.3194 5.01386Z" fill="#1D1D1D"/></svg></button>',
              nextArrow: '<button type="button" class="slick-next"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.68057 5.01386C8.94906 4.74538 9.38437 4.74538 9.65285 5.01386L15.1528 10.5139C15.4213 10.7824 15.4213 11.2176 15.1528 11.4861L9.65285 16.9861C9.38437 17.2546 8.94906 17.2546 8.68057 16.9861C8.41208 16.7176 8.41208 16.2824 8.68057 16.0139L13.6944 11L8.68057 5.98613C8.41208 5.71765 8.41208 5.28234 8.68057 5.01386Z" fill="#1D1D1D"/></svg></button>',
            });
          }
        }
      } else {
        if ($(".vp-cart-may-also-like-items").hasClass("slick-initialized")) {
          $(".vp-cart-may-also-like-items").slick("unslick");
        }
      }
    }

    function initializeSlick_like_products_style2() {
      if (!$(".vp-cart-may-also-like-items").hasClass("slick-initialized")) {
        if (jQuery(".vp-cart-may-also-like-items").length > 0) {
          $(".vp-cart-may-also-like-items").slick({
            variableWidth: true,
            dots: false,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.3194 5.01386C13.0509 4.74538 12.6156 4.74538 12.3471 5.01386L6.84715 10.5139C6.57865 10.7824 6.57865 11.2176 6.84715 11.4861L12.3471 16.9861C12.6156 17.2546 13.0509 17.2546 13.3194 16.9861C13.5879 16.7176 13.5879 16.2824 13.3194 16.0139L8.30556 11L13.3194 5.98613C13.5879 5.71765 13.5879 5.28234 13.3194 5.01386Z" fill="#1D1D1D"/></svg></button>',
            nextArrow: '<button type="button" class="slick-next"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.68057 5.01386C8.94906 4.74538 9.38437 4.74538 9.65285 5.01386L15.1528 10.5139C15.4213 10.7824 15.4213 11.2176 15.1528 11.4861L9.65285 16.9861C9.38437 17.2546 8.94906 17.2546 8.68057 16.9861C8.41208 16.7176 8.41208 16.2824 8.68057 16.0139L13.6944 11L8.68057 5.98613C8.41208 5.71765 8.41208 5.28234 8.68057 5.01386Z" fill="#1D1D1D"/></svg></button>',
          });
        }
      }
    }

    // Trigger the AJAX request to get cart content
    function updateCartContent_related_items() {
      $(".related-item-data-ajax").show();
      var $element = $(".mini-cart-widget");
      var cart_style = $element.data("cart_style");
      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        type: "POST",
        data: {
          action: "value_pack_get_cart_ajax_related_items",
        },

        success: function (response) {
          const itemsHtml = response.items_html;
          const have_items = response.have_items;

          if (have_items) {
            $(".vp-cart-may-also-like-container").remove();
            if (cart_style == "style_1") {
              $(".vp-cart-popup .vp-cart-container").prepend(itemsHtml);
            } else if (cart_style == "style_2") {
              $(
                ".vp-cart-style-two-container .vp-cart-content-container"
              ).append(itemsHtml);
              setTimeout(() => {
                initializeSlick_like_products_style2();
              }, 200);
            } else if (cart_style == "style_3") { }
            initializeSlick_like_products();
          } else {
            $(".vp-cart-may-also-like-container").hide();
          }
        },
        error: function (xhr, status, error) {
          console.log("Error: " + error);
        },
      });
    }

    function updateCartContent() {
      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        type: "POST",
        data: {
          action: "get_cart_content",
          style: jQuery(".mini-cart-widget").data("style"),
        },
        success: function (response) {
          // Parse response
          const itemsHtml = response.items_html;
          const freeShippingHtml = response.free_shipping_html;
          const itemPrices = response.total_price;
          const cart_items_data = response.cart_items_data;

          // Insert all item HTML content into the cart container
          $("#vp-cart-content").html(itemsHtml.join(""));
          $(".vp-free-shipping-container").html(freeShippingHtml);
          updateCartContent_related_items();
          // Display item prices in the console for now
          $(".vp-cart-subtotal-value").html(itemPrices);
          $(".vp-cart-footer").show();
          // Optionally, you can sum up the prices or handle them as needed
          if (cart_items_data == false) $(".vp-cart-footer").hide();
        },
        error: function (xhr, status, error) {
          console.log("Error: " + error);
        },
      });
    }

    // Handle remove item from cartf
    $(document).on("click", ".vp-cart-item-shopping-remove", function () {
      var $removeItem = $(this).closest(".vp-cart-items-container .row");
      showRemoveSpinner($removeItem);
      $(".quantity-spinner").show();
      var itemKey = $(this).data("cart-item-key");
      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        type: "POST",
        data: {
          action: "remove_cart_item",
          cart_item_key: itemKey,
        },
        success: function (response) {
          if (response.success) {
            updateCartContent();
            $(".quantity-spinner").hide();
          }
        },
      });
    });

    // animation function for item increament and decreament
    function showQuantitySpinner($item) {
      $item.find(".quantity-spinner").show();
    }

    // animation function for remove item
    function showRemoveSpinner($removeItem) {
      $removeItem.find(".remove-spinner").show();
    }

    // Handle increment and decrement of item quantity
    $(document).on("click", ".vp-cart-item-minus-icon", function () {
      var $item = $(this).closest(".vp-cart-items-container .row");
      var itemKey = $(this)
        .closest(".vp-cart-item-quantity-selection")
        .data("cart-item-key");
      var quantity = parseInt(
        $(this).siblings(".vp-cart-item-action-count").text()
      );
      if (quantity > 1) {
        updateItemQuantity(itemKey, quantity - 1);
        showQuantitySpinner($item);
      }
    });

    $(document).on("click", ".vp-cart-item-plus-icon", function () {
      var $item = $(this).closest(".vp-cart-items-container .row");
      var itemKey = $(this)
        .closest(".vp-cart-item-quantity-selection")
        .data("cart-item-key");
      var quantity = parseInt(
        $(this).siblings(".vp-cart-item-action-count").text()
      );
      updateItemQuantity(itemKey, quantity + 1);
      showQuantitySpinner($item);
    });

    // Function to update item quantity
    function updateItemQuantity(itemKey, quantity) {
      $(".quantity-spinner").show();
      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        type: "POST",
        data: {
          action: "update_cart_item_quantity",
          cart_item_key: itemKey,
          quantity: quantity,
        },
        success: function (response) {
          if (response.success) {
            updateCartContent();
            $(".quantity-spinner").hide();
          }
        },
      });
    }

    if ($(".mini-cart-icon").length > 0) {
      // Initial cart content load
      setTimeout(function () {
        updateCartContent();
      }, 500); // 1000ms = 1 second
    }

    // Js for login widget

    // Function to show the modal
    function showModal(style) {
      var modal = $("#vp-login-modal");
      modal.attr("class", "vp-login-modal " + style).addClass("active");
    }
    $(document).on(
      "click",
      ".single_add_to_cart_button:not(.disabled)",
      function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.addClass("value-pack-processing-ajax");
        $this.append(
          '<svg class="woo-ajax-loader-dark" viewBox="25 25 50 50"><circle r="20" cy="50" cx="50"></circle></svg>'
        );

        var $cart = $this.closest(".cart");
        var $element = $(".mini-cart-widget");
        var cart_style = $element.data("cart_style");
        var productId = $cart.find('input[name="add-to-cart"]').val();
        if (!productId) {
          productId = $this.val();
        }
        var quantity = $cart.find('input[name="quantity"]').val() || 1;
        var variationId =
          $cart.find('input[name="variation_id"].variation_id').val() || "";

        $.ajax({
          type: "POST",
          url: value_pack_ajax_params.ajax_url,
          data: {
            action: "value_pack_add_to_cart",
            product_id: productId,
            quantity: quantity,
            variation_id: variationId,
            cart_style: cart_style,
          },
          success: function (response) {
            if (
              $(".mini-cart-main-title span").length &&
              $(".mini-cart-icon").length
            ) {
              // Get the position of the cart icon in the header
              var $cartCountElement = $(".mini-cart-main-title span").length ?
                $(".mini-cart-main-title span") :
                $(".mini-cart-icon");
              var cartPosition = $cartCountElement.offset();
              var buttonPosition = $this.offset();

              // Create the animated icon
              var $animatedIcon = $(
                '<i class="fa-solid fa-cart-shopping animated-cart-icon"></i>'
              );
              $animatedIcon.css({
                top: buttonPosition.top,
                left: buttonPosition.left,
              });
              $("body").append($animatedIcon);

              // Animate the icon towards the cart icon
              setTimeout(function () {
                $animatedIcon.css({
                  transform: `translate(${cartPosition.left - buttonPosition.left
                    }px, ${cartPosition.top - buttonPosition.top}px) scale(0.5)`,
                  opacity: 0,
                });
              }, 50);

              // Remove the animated icon after animation completes
              setTimeout(function () {
                $animatedIcon.remove();
              }, 1000);
            }

            $(".vp-cart-popup").remove();
            $("body").append(response.items_html);
            setTimeout(function () {
              $(".vp-cart-popup").addClass("active");
              if (cart_style == "style_2") {
                initializeSlick_like_products_style2();
              }
            }, 300);
            $this.removeClass("value-pack-processing-ajax");
            $this.find("svg").remove();
          },
        });
      }
    );

    // Function to hide the modal
    function hideModal() {
      $("#vp-login-modal").removeClass("active");
    }

    function hideModal2() {
      $("#vp-login-modal").removeClass("active");
    }

    // Open the modal on widget click
    $(document).on("click", ".vp-login-widget", function () {
      var style = $(this).data("style");
      showModal(style);
    });

    // Close the modal when clicking the close button
    $(document).on("click", ".vp-login-close", function () {
      hideModal();
    });

    $(document).on("click", ".vp-register-close", function () {
      hideModal2();
    });

    // Close the modal when clicking outside of the modal content
    $(document).on("click", "#vp-login-modal", function (event) {
      if ($(event.target).is("#vp-login-modal")) {
        hideModal();
      }
    });

    $(document).on("click", "#vp-register-modal", function (event) {
      if ($(event.target).is("#vp-register-modal")) {
        hideModal2();
      }
    });

    // Js for WooCommerce search widget

    let typingTimer;
    const doneTypingInterval = 1000;

    let searchStyle = $(".vp-search-widget").data("style");

    // Define the elements to toggle the active class on
    $(document).on("click", ".vp-search-widget", function () {
      var get_styles = $(this).data("style");
      if (get_styles === "style_1") {
        if ($(".woocommerce-open-search-main").length > 0) {
          $(".woocommerce-open-search-main").addClass("active");
        }
      } else if (get_styles === "style_2") {
        if ($(".vp-search-style-2-wrapper").length > 0) {
          $(".vp-search-style-2-wrapper").addClass("active");
        }
      } else if (get_styles === "style_3") {
        if ($(".open-serch-filter-and-content").length > 0) {
          $(".open-serch-filter-and-content").addClass("active");
        }
      } else if (get_styles === "style_4") {
        if ($(".vp-search-style-4-wrapper").length > 0) {
          $(".vp-search-style-4-wrapper").addClass("active");
        }
      } else if (get_styles === "style_5") {
        if ($(".vp-search-style-5-wrapper").length > 0) {
          $(".vp-search-style-5-wrapper").addClass("active");
        }
      }
    });

    // Hide the search results on clicking the close button
    $(document).on("click", ".vp-search-close-x", function () {
      $(
        ".woocommerce-open-search-main, .vp-search-style-2-wrapper, .open-serch-filter-and-content, .vp-search-style-4-wrapper, .vp-search-style-5-wrapper"
      ).removeClass("active");
    });

    function showSpinner() {
      $(".spinner").show();
    }

    function hideSpinner() {
      $(".spinner").hide();
    }

    function fetchSearchResults(keyword) {
      showSpinner();

      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        method: "POST",
        data: {
          action: "woocommerce_live_search",
          keyword: keyword,
          searchStyle: searchStyle,
          security: value_pack_ajax_params.nonce,
        },
        success: function (response) {
          const {
            products,
            suggestions,
            total
          } = response;

          hideSpinner();

          // Update the result count
          if (searchStyle === "style_1") {
            $(".display-result").text(`${total} results`);
            $(".woocommerce-left-main-content .left-content-items").html(
              products
            );
            $(".vp-suggestions-style-1 ul").html(suggestions);
          } else if (searchStyle === "style_2") {
            $(".vp-search-results-2-wrapper .vp-search-results-2").html(
              products
            );
            $(".vp-suggestions-style-2 ul").html(suggestions);
          } else if (searchStyle === "style_3") {
            $(".product-quantity-heading").text(`${total} results`);
            $(
              ".woocommerce-right-main-content-style-3 .search-results-style-3"
            ).html(products);
            $(".vp-suggestions-style-3 ul").html(suggestions);
          } else if (searchStyle === "style_4") {
            $(".vp-search-style-4-products .vp-search-product-items").html(
              products
            );
            $(".vp-suggestions-style-4 ul").html(suggestions);
          } else if (searchStyle === "style_5") {
            $(
              ".vp-search-style-5-products .vp-search-style-5-product-items"
            ).html(products);
            $(".vp-search-result-count").text(`${total} PRODUCTS FOUND`);
          }
        },
        error: function () {
          hideSpinner();
        },
      });
    }

    $(".woocommerce-live-search-input").on("keyup", function () {
      clearTimeout(typingTimer);
      const keyword = $(this).val();

      if (keyword.length > 0) {
        typingTimer = setTimeout(function () {
          fetchSearchResults(keyword);
          $(".open-search-left-content").addClass("active");
        }, doneTypingInterval);
        showSpinner();
      } else {
        fetchSearchResults("");
        hideSpinner();
        $(".open-search-left-content").removeClass("active");
      }
    });

    if ($("body").hasClass("logged-in")) {
      $(".elementor-widget-vp_login").hide();
    }
    if ($(".woocommerce-live-search-input").length > 0) {
      // Fetch initial suggestions on page load
      setTimeout(function () {
        fetchSearchResults("");
      }, 500); // 1000ms = 1 second

    }

    // vp-product-gallery-widget

    function galleryMobileSlider() {
      if ($(window).width() <= 767) {
        if ($(".vp-product-gallery-container .row").hasClass("vp-product-gallery-slider")) {
          $(".vp-product-gallery-container .row").removeClass("vp-product-gallery-slider");
        }
        $(".vp-product-gallery-container .row").addClass("mobile-gallery-slider");
        $(".vp-product-gallery-container .row.vp-product-gallery-as-navbar").remove();
        $('.vp-product-gallery-container').addClass('remove-loader');
        $('.vp-product-gallery-container .vp-gallery-loader').remove();
      } else {
        $(".vp-product-gallery-container .row").removeClass("mobile-gallery-slider");
      }
    }

    galleryMobileSlider();
    $(window).on("resize", function () {
      galleryMobileSlider();
    });

    if (jQuery(".mobile-gallery-slider").length > 0) {
      $(".mobile-gallery-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: "linear",
        autoplay: true,
        autoplaySpeed: 3000,
      });
    }

    if (jQuery(".vp-bridge-auto-slider").length > 0) {
      jQuery(".vp-bridge-auto-slider").slick({
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        arrows: false,
        dots: false,
        cssEase: "linear",
        variableWidth: true,
      });
    }

    function initProductSliders() {
      if ($(".vpack-product-slider").length > 0) {
        $(".vpack-product-slider").each(function () {
          var productSlider = $(this);
          // Detect RTL (Right-to-Left) support
          var isRTL = false;
          if ($("html").attr("dir") === "rtl" || $("body").attr("dir") === "rtl" ||
            $("html").hasClass("rtl") || $("body").hasClass("rtl") ||
            document.documentElement.dir === "rtl" || document.body.dir === "rtl") {
            isRTL = true;
          }
          // Fetching the data attributes
          var prevArrowContent = productSlider.data("prev-arrow");
          var wrap_dots_arrows = productSlider.data("wrap_dots_arrows");
          var enable_slider_counts = productSlider.data("enable_slider_counts");
          var nextArrowContent = productSlider.data("next-arrow");
          var showSlides = productSlider.data("slides-to-show");
          var scrollSlides = productSlider.data("slides-to-scroll");
          var autoPlay = productSlider.data("autoplay");
          var autoPlaySpeed = productSlider.data("autoplay-speed");
          var loopSlides = productSlider.data("infinite");
          var fadeTransition = productSlider.data("fade_effect");
          var isDraggable = productSlider.data("draggable");
          var isVertical = productSlider.data("vertical");
          var variableSlideWidth = productSlider.attr("data-variableWidth");
          var customDots = productSlider.data("custom_dots");
          var customArrows = productSlider.data("custom_arrows");
          var enable_title = productSlider.data("enable-title");
          var transitionSpeed = productSlider.data("custom_speed");
          var easingEffect = productSlider.data("easing");
          var progressbar = productSlider.data("progressbar");
          var slider_counts_custom_text = productSlider.data(
            "slider_counts_custom_text"
          );
          var slider_device_option = productSlider.attr(
            "data-slider_device_option"
          );

          var slidesToShowTablet = productSlider.data("slides_to_show_tablet");
          var slidesToShowTabletPortrait = productSlider.data(
            "slides_to_show_tablet_portrait"
          );
          var slidesToShowMobile = productSlider.data("slides_to_show_mobile");
          var slidesToScrollTablet = productSlider.data(
            "slides_to_scroll_tablet"
          );
          var slidesToScrollTabletPortrait = productSlider.data(
            "slides_to_scroll_tablet_portrait"
          );
          var slidesToScrollMobile = productSlider.data(
            "slides_to_scroll_mobile"
          );

          if (showSlides == null || showSlides == "") {
            showSlides = 1;
          }

          if (scrollSlides == null || scrollSlides == "") {
            scrollSlides = 1;
          }

          if (variableSlideWidth == "true") {
            variableSlideWidth = true;
          } else if (variableSlideWidth == "false") {
            variableSlideWidth = false;
          }

          // Defining the arrow buttons
          var prevArrowButton =
            '<button type="button" class="slick-prev">' +
            prevArrowContent +
            "</button>";
          var nextArrowButton =
            '<button type="button" class="slick-next">' +
            nextArrowContent +
            "</button>";

          // Initialize the slick slider
          if ($(this).hasClass("e-con-boxed")) {
            productSlider = productSlider.find(".e-con-inner");
          }

          var $get_slick_counts = "";
          productSlider.on(
            "init reInit afterChange",
            function (event, slick, currentSlide) {
              // Set current slide (0-indexed, so +1)
              $get_slick_counts = slick.slideCount;
            }
          );

          if (
            slider_device_option === "mobile_only" &&
            $(window).width() <= 767
          ) {
            // Initialize the slider for mobile-only devices
            productSlider
              .slick({
                slidesToShow: showSlides,
                slidesToScroll: scrollSlides,
                autoplay: autoPlay,
                arrows: customArrows,
                autoplaySpeed: autoPlaySpeed,
                infinite: loopSlides,
                speed: transitionSpeed,
                fade: fadeTransition,
                draggable: isDraggable,
                prevArrow: prevArrowButton,
                nextArrow: nextArrowButton,
                dots: customDots,
                cssEase: easingEffect,
                variableWidth: variableSlideWidth,
                vertical: isVertical,
                pauseOnHover: false,
                rtl: isRTL,
                responsive: [{
                  breakpoint: 481, // Mobile only settings
                  settings: {
                    slidesToShow: slidesToShowMobile,
                    slidesToScroll: slidesToScrollMobile,
                    vertical: false,
                  },
                },],
              })
              .slickAnimation();
          } else if (slider_device_option === "all_devices") {
            // Initialize the slider for all devices
            productSlider
              .slick({
                slidesToShow: showSlides,
                slidesToScroll: scrollSlides,
                autoplay: autoPlay,
                arrows: customArrows,
                autoplaySpeed: autoPlaySpeed,
                infinite: loopSlides,
                rtl: isRTL,
                speed: transitionSpeed,
                fade: fadeTransition,
                draggable: isDraggable,
                prevArrow: prevArrowButton,
                nextArrow: nextArrowButton,
                dots: customDots,
                customPaging: function (slider, i) {
                  if (enable_title == 'yes') {
                    var title = $(slider.$slides[i]).find('.elementor-element').data('container-title');
                    return '<button type="button"><span>' + title + '</span></button>';
                  } else {
                    return '<button type="button"></button>';
                  }
                },
                cssEase: easingEffect,
                variableWidth: variableSlideWidth,
                vertical: isVertical,
                pauseOnHover: false,
                responsive: [{
                  breakpoint: 1025,
                  settings: {
                    slidesToShow: slidesToShowTablet,
                    slidesToScroll: slidesToScrollTablet,
                    vertical: false,
                  },
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: slidesToShowTabletPortrait,
                    slidesToScroll: slidesToScrollTabletPortrait,
                    vertical: false,
                  },
                },
                {
                  breakpoint: 481,
                  settings: {
                    slidesToShow: slidesToShowMobile,
                    slidesToScroll: slidesToScrollMobile,
                    vertical: false,
                  },
                },
                ],
              })
              .slickAnimation();
          }

          if (wrap_dots_arrows == true) {
            if (enable_slider_counts == true) {
              productSlider.append(
                '<div class="slick-arrows-wrapper slider-counts"><div class="slick-counts-data"></div></div>'
              );

              if (
                slider_counts_custom_text &&
                slider_counts_custom_text.trim() !== ""
              ) {
                productSlider.append(
                  '<div class="slick-counter-text">' +
                  slider_counts_custom_text +
                  "</div>"
                );
                productSlider
                  .find(".slick-counter-text")
                  .prependTo(productSlider.find(".slick-arrows-wrapper"));
              }

              productSlider
                .find(".slick-prev")
                .appendTo(productSlider.find(".slick-counts-data"));
              productSlider.append('<div class="slick-arrows-counter"></div>');
              productSlider
                .find(".slick-arrows-counter")
                .text("1/" + $get_slick_counts);
              productSlider
                .find(".slick-arrows-counter")
                .appendTo(productSlider.find(".slick-counts-data"));
              productSlider.find(".slick-dots").remove();
              productSlider
                .find(".slick-next")
                .appendTo(productSlider.find(".slick-counts-data"));
            } else {
              productSlider.append('<div class="slick-arrows-wrapper"></div>');
              productSlider
                .find(".slick-prev")
                .appendTo(productSlider.find(".slick-arrows-wrapper"));
              productSlider
                .find(".slick-dots")
                .appendTo(productSlider.find(".slick-arrows-wrapper"));
              productSlider
                .find(".slick-next")
                .appendTo(productSlider.find(".slick-arrows-wrapper"));
            }
          }

          productSlider.on(
            "init reInit afterChange",
            function (event, slick, currentSlide, nextSlide) {
              var i = (currentSlide ? currentSlide : 0) + 1;
              productSlider
                .find(".slick-arrows-counter")
                .text(i + "/" + slick.slideCount);
            }
          );

          if (progressbar == true) {
            productSlider.after(
              '<div class="slick-progress"><div class="slick-progress-bar"></div></div>'
            );
            var totalSlides = productSlider.slick("getSlick").slideCount;
            productSlider.on(
              "afterChange",
              function (event, slick, currentSlide) {
                var progress = ((currentSlide + 1) / totalSlides) * 100;
                productSlider
                  .next(".slick-progress")
                  .find(".slick-progress-bar")
                  .css("width", progress + "%");
              }
            );
          }

          if ($(".cubewp-post-slider").length > 0) {
            $(".cubewp-post-slider").on(
              "afterChange",
              function (event, slick, currentSlide) {
                var $this = $(this);
                $this
                  .closest(".e-con-inner")
                  .find(productSlider)
                  .slick("slickGoTo", currentSlide);
              }
            );
          }

          if ($(".cubewp-post-slider").length > 0) {
            productSlider.on(
              "afterChange",
              function (event, slick, currentSlide) {
                var $this = $(this);
                $this
                  .closest(".e-con-inner")
                  .find(".cubewp-post-slider")
                  .slick("slickGoTo", currentSlide);
              }
            );
          }
        });
      }
    }

    // Run after browser is idle (non-blocking)
    if ("requestIdleCallback" in window) {
      requestIdleCallback(initProductSliders, { timeout: 2000 });
    } else {
      // Fallback for Safari < 18 and older browsers
      setTimeout(initProductSliders, 200);
    }

    var $tabButtons = $(".hover .e-n-tab-title");
    var $tabContents = $(".e-n-tabs-content > div");

    // Function to activate the tab
    function activateTab($tabButton) {
      // Get the index and aria-controls of the clicked/hovered tab button
      var targetContentId = $tabButton.attr("aria-controls");

      // Deactivate all buttons and content in the same `.hover` container
      var $hoverContainer = $tabButton.closest(".hover");

      $hoverContainer
        .find(".e-n-tab-title")
        .removeClass("e-active")
        .attr("aria-selected", "false")
        .attr("tabindex", "-1");

      $hoverContainer.find(".e-n-tabs-content > div").removeClass("e-active");

      // Activate the clicked/hovered tab button and corresponding content
      $tabButton
        .addClass("e-active")
        .attr("aria-selected", "true")
        .attr("tabindex", "0");
      $hoverContainer.find("#" + targetContentId).addClass("e-active");
    }

    // Event listeners for hover and click
    $tabButtons.on("mouseenter", function () {
      activateTab($(this));
    });

    $tabButtons.on("click", function () {
      activateTab($(this));
    });

    if (jQuery(".single-product .woocommerce-message").length > 0) {
      jQuery(".mini-cart-widget").trigger("click");
    }

    updateTooltipOnPageLoad();

    jQuery(document.body).on('cubewp_posts_loaded', function (event) {
      updateTooltipOnPageLoad();
    });

    jQuery(document).on("click", ".cwp-main", function () {
      var clickedElement = this;

      // Check if the class is already added, if so, insert the loader
      if (jQuery(clickedElement).hasClass("cubewp-active-ajax")) {
        insertLoader(clickedElement);
      }

      // Set up MutationObserver to listen for class changes
      const observer = new MutationObserver(function (mutationsList) {
        for (let mutation of mutationsList) {
          if (mutation.attributeName === "class") {
            const targetElement = mutation.target;
            if (jQuery(targetElement).hasClass("cubewp-active-ajax")) {
              insertLoader(targetElement);
            } else {
              removeLoaderMarkup(targetElement);
            }
          }
        }
      });

      // Start observing the clicked element for class changes
      observer.observe(clickedElement, {
        attributes: true,
      });

      // Set a timeout to update tooltip (as per your existing code)
      setTimeout(function () {
        updateTooltip(clickedElement);
      }, 1500);
    });

    const initLightbox = () => {
      const leftArrowSVGString =
        '<svg aria-hidden="true" focusable="false" fill="none" width="16" class="icon icon--direction-aware" viewBox="0 0 16 18"><path d="M11 1 3 9l8 8" stroke="currentColor" stroke-linecap="square" stroke-width="2"></path></svg>';
      const closeSvgString =
        '<svg aria-hidden="true" focusable="false" fill="none" width="16" class="icon" viewBox="0 0 16 16"><path d="m1 1 14 14M1 15 15 1" stroke="currentColor" stroke-width="1.2"></path></svg>';

      // Ensure your PhotoSwipe version is used (WooCommerce might override)
      const VPPhotoSwipe = window.VPPhotoSwipe || window.PhotoSwipe;
      const VPPhotoSwipeLightbox =
        window.VPPhotoSwipeLightbox || window.PhotoSwipeLightbox;

      if (typeof VPPhotoSwipeLightbox !== "undefined") {
        const lightbox = new VPPhotoSwipeLightbox({
          gallery: ".vp-product-gallery-container",
          children: "a.value-pack-open-gallery",
          bgOpacity: 1,
          showHideAnimationType: "zoom",
          loop: true,
          counter: true,
          zoom: false,
          bgClickAction: false,
          arrowPrevSVG: leftArrowSVGString,
          arrowNextSVG: leftArrowSVGString,
          closeSVG: closeSvgString,
          pswpModule: VPPhotoSwipe,
        });

        lightbox.init();
      }
    };

    setTimeout(initLightbox, 500); // Delay initialization to avoid conflicts

    // Function to insert loader
    function insertLoader(element) {
      jQuery(element).append(`
      <div class="woo-ajax-loader"> 
        <svg class="woo-ajax-loader-svg" viewBox="25 25 50 50">
          <circle r="20" cy="50" cx="50"></circle>
        </svg>
      </div>
    `);
    }

    // Function to remove loader markup
    function removeLoaderMarkup(element) {
      jQuery(element).find(".woo-ajax-loader").remove();
    }

    function updateTooltipOnPageLoad() {
      if ($(".cwp-main").length > 0) {
        jQuery(".cwp-main").each(function () {
          updateTooltip(this);
        });
      }
    }

    jQuery(document).on("mouseenter", ".wc-woo-icon-products", function () {
      var get_id = jQuery(this).attr("value-pack-data-slide-depends");
      jQuery(this).closest(".e-parent").find(".product-id-" + get_id).addClass("active");
      jQuery(this).closest(".e-parent").find(".wc-women-collective-products").addClass("inactive");
      jQuery(this).closest(".e-parent").find(".post-" + get_id).addClass("active-hover-effact");
      jQuery(this).closest(".e-parent").find(".cwp-row").addClass("inactive-hover-effact");
    });
    jQuery(document).on("mouseleave", ".wc-woo-icon-products", function () {
      jQuery(this).closest(".e-parent").find(".product-hover-cards").removeClass("active");
      jQuery(this).closest(".e-parent").find(".wc-women-collective-products").removeClass("inactive");

      jQuery(this).closest(".e-parent").find(".cwp-row>div").removeClass("active-hover-effact");
      jQuery(this).closest(".e-parent").find(".cwp-row").removeClass("inactive-hover-effact");
    });

    function updateTooltip(element) {
      if (jQuery(element).hasClass("cwp-save-post")) {
        jQuery(element)
          .closest(".save-tooltip")
          .attr("data-tooltip", "Add To Wishlist");
      } else if (jQuery(element).hasClass("cwp-saved-post")) {
        jQuery(element)
          .closest(".save-tooltip")
          .attr("data-tooltip", "Remove From Wishlist");
      }
    }

    function CubewpMobileSlider() {
      if ($(window).width() <= 767) {
        if (!$(".vp-product-slider-on-mobile").hasClass("slick-initialized")) {
          $(".vp-product-slider-on-mobile").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            infinite: true,
            speed: 500,
            fade: false,
            cssEase: "linear",
            centerMode: true,
            centerPadding: "100px",
          });
        }
      } else {
        if ($(".vp-product-slider-on-mobile").hasClass("slick-initialized")) {
          $(".vp-product-slider-on-mobile").slick("unslick");
        }
      }
    }

    CubewpMobileSlider();
    $(window).on("resize", function () {
      CubewpMobileSlider();
    });

    jQuery(document).on(
      "click",
      ".wc-women-best-section-slider-left",
      function () {
        jQuery(this).closest(".e-parent").find(".slick-prev").trigger("click");
      }
    );

    jQuery(document).on(
      "click",
      ".wc-women-best-section-slider-right",
      function () {
        jQuery(this).closest(".e-parent").find(".slick-next").trigger("click");
      }
    );

    jQuery(".vp-register-trigger").on("click", function (event) {
      event.preventDefault();
      jQuery(".vp-register-modal-content").removeClass("d-none");
      jQuery(".vp-login-modal-content").addClass("d-none");
    });

    jQuery(".vp-login-trigger").on("click", function (event) {
      event.preventDefault();
      jQuery(".vp-login-modal-content").removeClass("d-none");
      jQuery(".vp-register-modal-content").addClass("d-none");
    });

    jQuery(document).on(
      "click",
      ".vpack-nested-tabs.yes .e-n-tab-title",
      function () {
        var $this = jQuery(this);
        var $tabsContent = $this
          .closest(".elementor-element")
          .find(".e-n-tabs-content > .elementor-element");
        $('.cubewp-post-slider').slick('slickNext');
        if (!$this.hasClass("active")) {
          $this.closest(".elementor-element").find(".e-n-tab-title").removeClass("active");
          $this.addClass("active");
          $tabsContent.removeClass("wc-value-pack-fadeInUp wc-value-pack-fadeOutDown");
          $tabsContent.addClass("wc-value-pack-fadeOutDown");
          $tabsContent.find(".wc-vp-image-loader").remove();
          var $loader = jQuery('<div class="wc-vp-image-loader"></div>');
          $tabsContent.append($loader);
          setTimeout(function () {
            $tabsContent.find(".wc-vp-image-loader").remove();
            $tabsContent

              .removeClass("wc-value-pack-fadeOutDown")
              .addClass("wc-value-pack-fadeInUp");
          }, 500);
        }
      }
    );

    jQuery(document).on(
      "click",
      ".value-pack-slide-accordions .e-n-accordion-item-title",
      function (e) {
        e.preventDefault();
        const accordion = jQuery(this).closest(".value-pack-slide-accordions");
        const currentItem = jQuery(this).closest(".e-n-accordion-item");
        updateActiveSlide(accordion, currentItem);
        var slidenoid = jQuery(this).data("accordion-index");
        if (typeof slidenoid !== "undefined") {
          jQuery(this)
            .closest(".e-parent")
            .find(".ep-swiper-slider")
            .slick("slickGoTo", slidenoid - 1);
        }
        resetAutoCycle(accordion, currentItem);
      }
    );

    let cycleIntervals = {};

    function updateActiveSlide(accordion, activeItem) {
      accordion
        .find(".value-pack-active-slide")
        .removeClass("value-pack-active-slide");
      activeItem.addClass("value-pack-active-slide");
    }

    function resetAutoCycle(accordion, startItem) {
      const accordionId = accordion.data("id") || accordion.index();
      clearInterval(cycleIntervals[accordionId]);
      const items = accordion.find(".e-n-accordion-item");
      let currentIndex = items.index(startItem);
      cycleIntervals[accordionId] = setInterval(function () {
        const nextIndex = (currentIndex + 1) % items.length;
        const nextItem = items.eq(nextIndex);
        updateActiveSlide(accordion, nextItem);
        nextItem.find(".e-n-accordion-item-title").trigger("click");
        currentIndex = nextIndex;
      }, 6000);
    }
    if ($(".value-pack-slide-accordions").length > 0) {
      setTimeout(function () {
        jQuery(".value-pack-slide-accordions").each(function () {
          const $accordion = jQuery(this);
          const firstItem = $accordion.find(".e-n-accordion-item").first();
          resetAutoCycle($accordion, firstItem);
        });
      }, 1000);
    }

    $(".value-pack-sticky-section").each(function () {
      const stickySection = $(this);
      var stickyElement = stickySection.find(">.elementor-element");
      if (!stickyElement.length) {
        var stickyElement = stickySection.find(">.e-con-inner");
      }
      var allParents = stickyElement.parents();
      allParents.css('overflow', 'initial');
      const sticky_index = stickySection.data("sticky-z-index");
      stickyElement.css({
        position: "sticky",
        top: 0,
        zIndex: sticky_index || 10,
      });

    });

    // Hide Mini Cart, Search, Login Pop-ups while click outside the content.
    jQuery(document).ready(function ($) {
      const vp_custom_popups = [{
        mainSelector: '.vp-cart-popup',
        contentSelector: '.vp-cart-popup-content'
      },
      {
        mainSelector: '.vp-search-popup-main',
        contentSelector: '.vp-search-popup-inner'
      },
      {
        mainSelector: '.vp-login-modal',
        contentSelector: '.vp-login-register-modal-content'
      }
      ];

      vp_custom_popups.forEach(function (popup) {
        $(document).on('click', popup.mainSelector, function (event) {
          const $popupMain = $(this);
          const $popupContent = $popupMain.find(popup.contentSelector);

          // Close popup only when clicking on background
          if (!$popupContent.is(event.target) && $popupContent.has(event.target).length === 0) {
            $popupMain.removeClass('active');
          }
        });
      });
    });

    $('.cubewp-accordion-main .accordion.popup .accordion-collapse.collapse.show').on('click', function (event) {
      if ($(event.target).is('.cubewp-accordion-main .accordion.popup .accordion-collapse.collapse.show')) {
        $(this).removeClass('show');
      }
    });

    /*------------ Locale Switcher -------------*/
    if ($('.vp-locale-switcher-container').length > 0) {
      $('.vp-locale-switcher-container').each(function () {
        const $container = $(this);
        const $dropdown = $container.find('.vp-locale-dropdown');
        const $selectedOption = $container.find('.vp-locale-toggle');
        const $options = $dropdown.find('.vp-locale-option');

        $container.on('click', function (e) {
          e.stopPropagation();
          $container.toggleClass('vp-dropdown-toggled');
        });

        $options.on('click', function (e) {
          e.stopPropagation();
          $options.removeClass('vp-locale-active-option');
          $(this).addClass('vp-locale-active-option');
          const newContent = $(this).clone();
          const originalIcon = $selectedOption.find('.vp-locale-dropdown-arrow').html();
          newContent.find('.vp-locale-dropdown-arrow').remove();
          newContent.append('<span class="vp-locale-dropdown-arrow">' + originalIcon + '</span>');
          $selectedOption.html(newContent.html());
          $container.removeClass('vp-dropdown-toggled');
          // AJAX here
        });
      });
      $(document).on('click', function () {
        $('.vp-locale-switcher-container').removeClass('vp-dropdown-toggled');
      });
    }
    /*------------ End Local Swicther -------------*/

  });

  $(document).on("mouseenter", ".woo-overlay-move.onhover", function () {
    var $overlayImages = $(this).find(".woo-gallery-overlay-image");
    var currentIndex = 0;
    clearInterval($(this).data("hoverInterval"));

    function rotateImages() {
      $overlayImages.removeClass("active");
      if (currentIndex === $overlayImages.length) {
        currentIndex = 0;
      } else {
        $overlayImages.eq(currentIndex).addClass("active");
        currentIndex++;
      }
    }

    // Start the rotation immediately
    rotateImages();
    var hoverInterval = setInterval(rotateImages, 3000);
    $(this).data("hoverInterval", hoverInterval);
  });

  $(document).on("mouseleave", ".woo-overlay-move.onhover", function () {
    clearInterval($(this).data("hoverInterval"));
    $(this).find(".woo-gallery-overlay-image").removeClass("active");
  });

  $(document).on("click", ".wm-variation-attr", function () {
    var parentLi = $(this).closest("li");

    // Retrieve the attribute type, name, and term slug from data attributes
    var attrType = parentLi.data("attr-type");
    var attrName = parentLi.data("attr-name");
    var termSlug = parentLi.data("term-slug");

    // Construct the selector for the corresponding select element in the WooCommerce form
    var selectElement = $(this).closest('.wm-product-attributes').next('form').find('select[name="attribute_' + attrName + '"]');

    // Check if the select element exists
    if (selectElement.length) {
      // Set the value of the select element to match the termSlug
      selectElement.val(termSlug).trigger("change");
    }
  });
  // Special case for the select dropdown directly
  $(document).on(
    "change",
    "select.default-attribute-dropdown.wm-variation-attr",
    function () {
      // Get the attribute name and selected value
      var attrName = $(this).data("attr-name");
      var selectedValue = $(this).val();

      // Find the corresponding WooCommerce select element
      var selectElement = $('select[name="attribute_' + attrName + '"]');

      // Check if the select element exists
      if (selectElement.length) {
        // Set the value of the select element to match the selected value
        selectElement.val(selectedValue).trigger("change");
      }
    }
  );

  if (
    jQuery(".women-woocommerce.gallery-style-4 .vp-woommerce-gallery-slider")
      .length > 0
  ) {
    jQuery(
      ".women-woocommerce.gallery-style-4 .vp-woommerce-gallery-slider"
    ).slick({
      slidesToShow: 2,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      speed: 500,
      cssEase: "linear",
      infinite: false,
      asNavFor: ".women-woocommerce.gallery-style-4 .vp-woommerce-gallery-slider-bottom-bar",
      responsive: [{
        breakpoint: 991,
        settings: {
          slidesToShow: 1,
        },
      },],
    });
  }
  if (
    jQuery(
      ".women-woocommerce.gallery-style-4 .vp-woommerce-gallery-slider-bottom-bar"
    ).length > 0
  ) {
    jQuery(
      ".women-woocommerce.gallery-style-4 .vp-woommerce-gallery-slider-bottom-bar"
    ).slick({
      slidesToShow: 12,
      slidesToScroll: 1,
      asNavFor: ".women-woocommerce.gallery-style-4 .vp-woommerce-gallery-slider",
      dots: false,
      focusOnSelect: true,
      arrows: false,
      infinite: false,
      responsive: [{
        breakpoint: 991,
        settings: {
          slidesToShow: 8,
        },
      },],
    });
  }

  if (
    jQuery(
      ".women-woocommerce.gallery-style-6 .woocommerce-product-gallery__wrapper"
    ).length > 0
  ) {
    jQuery(
      ".women-woocommerce.gallery-style-6 .woocommerce-product-gallery__wrapper"
    ).slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      infinite: false,
    });
  }

  // First part with slider-counter
  var $slider = jQuery(
    ".women-woocommerce.gallery-style-7 .woocommerce-product-gallery__wrapper"
  );
  if ($slider.length > 0) {
    $slider.after(
      '<div class="slider-counter"><p><span id="current-slide">1</span> / <span id="total-slides">0</span><p></div>'
    );
    var $currentSlide = jQuery("#current-slide");
    var $totalSlides = jQuery("#total-slides");
    $slider.slick({
      slidesToShow: 2,
      slidesToScroll: 2,
      arrows: true,
      dots: false,
      infinite: false,
    });
    setTimeout(function () {
      var slick = $slider.slick("getSlick");
      var slideCount = slick.slideCount;
      $totalSlides.text(slideCount);
      updateCurrentSlide(slick.slickCurrentSlide(), slideCount);
    }, 100);

    $slider.on("afterChange", function (event, slick, currentSlide) {
      updateCurrentSlide(currentSlide, slick.slideCount);
    });

    function updateCurrentSlide(currentSlideIndex, slideCount) {
      var slidesToShow = 2;
      var visibleSlideNumber =
        Math.floor(currentSlideIndex / slidesToShow) * slidesToShow +
        slidesToShow;
      $currentSlide.text(visibleSlideNumber);
    }
  }

  // Second part with gallery-style-10
  var $gallerySlider = jQuery(
    ".women-woocommerce.gallery-style-10 .vp-woommerce-gallery-slider"
  );
  var $gallerySliderBottomBar = jQuery(
    ".women-woocommerce.gallery-style-10 .vp-woommerce-gallery-slider-bottom-bar"
  );

  if ($gallerySlider.length > 0 && $gallerySliderBottomBar.length > 0) {
    $gallerySlider.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      fade: true,
      infinite: false,
      asNavFor: ".women-woocommerce.gallery-style-10 .vp-woommerce-gallery-slider-bottom-bar",
    });

    $gallerySliderBottomBar.slick({
      slidesToShow: 11,
      slidesToScroll: 1,
      asNavFor: ".women-woocommerce.gallery-style-10 .vp-woommerce-gallery-slider",
      dots: false,
      focusOnSelect: true,
      arrows: false,
      infinite: false,
      vertical: true,
      verticalSwiping: true,
    });
  }

  function checkAndAddClass() {
    var $galleries = jQuery(
      ".women-woocommerce.gallery-style-1 .woocommerce-product-gallery__wrapper, .women-woocommerce.gallery-style-2 .woocommerce-product-gallery__wrapper, .women-woocommerce.gallery-style-3 .woocommerce-product-gallery__wrapper, .women-woocommerce.gallery-style-5 .woocommerce-product-gallery__wrapper, .women-woocommerce.gallery-style-8 .woocommerce-product-gallery__wrapper, .women-woocommerce.gallery-style-9 .woocommerce-product-gallery__wrapper"
    );
    if ($galleries.length > 0) {
      if (jQuery(window).width() <= 767) {
        $galleries.addClass("vp-mobile-slider");
      } else {
        $galleries.removeClass("vp-mobile-slider");
      }
    }
  }

  // Run on load
  checkAndAddClass();

  // Run on window resize
  jQuery(window).on("resize", function () {
    checkAndAddClass();
  });

  if (jQuery(".vp-mobile-slider").length > 0) {
    jQuery(".vp-mobile-slider").slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
    });
  }

  if (jQuery(".women-woocommerce .accordion .accordion-button").length > 0) {
    jQuery(".women-woocommerce .accordion .accordion-button").addClass(
      "collapsed"
    );
  }

  jQuery(document).on(
    "click",
    ".wm-product-attributes .attribute-container ul li",
    function () {
      jQuery(this)
        .closest(".attribute-container")
        .find("ul li")
        .removeClass("active");
      jQuery(this).addClass("active");
    }
  );

  jQuery(document).on(
    "click",
    ".woo-sticky-cart .wm-product-attributes .attribute-container ul li",
    function () {
      var slug = jQuery(this).attr("data-term-slug");
      var name = jQuery(this).attr("data-attr-name");
      setTimeout(function () {
        jQuery(
          '.wc-women-single-attributes li[data-term-name="' + name + '"]'
        ).removeClass("active");
        jQuery(
          '.wc-women-single-attributes li[data-attr-name="' + name + '"]'
        ).removeClass("active");
        jQuery(
          '.wc-women-single-attributes li[data-term-slug="' + slug + '"]'
        ).addClass("active");
      }, 300);
    }
  );

  jQuery(document).on(
    "click",
    ".wc-women-single-attributes .wm-product-attributes .attribute-container ul li",
    function () {
      var slug = jQuery(this).attr("data-term-slug");
      var name = jQuery(this).attr("data-attr-name");
      jQuery('.woo-sticky-cart li[data-term-slug="' + slug + '"]').trigger(
        "click"
      );
    }
  );

  // attribute-value-get
  jQuery(document).on(
    "click",
    ".wm-product-attributes .attribute-container ul li",
    function () {
      var slug = jQuery(this).attr("data-term-slug");

      var formattedSlug = slug.replace(/-/g, " ");

      var attributeContainer = jQuery(this).closest(".attribute-container");
      if (attributeContainer.find("h3 span.slug-display").length === 0) {
        attributeContainer
          .find("h3")
          .append(': <span class="slug-display"></span>');
      }
      attributeContainer
        .find("h3 span.slug-display")
        .empty()
        .append(formattedSlug);
    }
  );

  if (jQuery(".vp-about-reviews-slider").length > 0) {
    jQuery(".vp-about-reviews-slider").slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
    });
  }

  if (jQuery(".vp-about-history-slider").length > 0) {
    jQuery(".vp-about-history-slider").slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
    });
  }

  if (jQuery(".vp-sustainabillity-materials-slider").length > 0) {
    jQuery(".vp-sustainabillity-materials-slider").slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
    });
  }

  if (jQuery(".vp-sustainabillity-vertical-slider > .e-con-inner").length > 0) {
    jQuery(".vp-sustainabillity-vertical-slider > .e-con-inner").slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: true,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 2000,
      cssEase: "linear",
      vertical: true,
    });
  }

  jQuery("#details-and-care").on("click", function () {
    jQuery(this)
      .closest(".sidebar-popup")
      .find(".sidebar-popup-content")
      .addClass("show");
  });

  jQuery("#close-sidebar-popup").on("click", function () {
    jQuery(this).closest(".sidebar-popup-content").removeClass("show");
  });

  jQuery(document).on("click", function (event) {
    var $target = jQuery(event.target);
    if (
      !$target.closest(".sidebar-popup-content-inner, #details-and-care").length
    ) {
      jQuery(".sidebar-popup-content").removeClass("show");
    }
  });

  jQuery(
    ".women-woocommerce.gallery-style-1 .accordion .accordion-item .accordion-collapse .accordion-body"
  ).append('<div id="close-sidebar-popup"><i class="fa fa-times"></i></div>');
  jQuery(".women-woocommerce.gallery-style-1 .accordion .accordion-button").on(
    "click",
    function () {
      jQuery(this)
        .closest(".accordion-item")
        .find(".accordion-collapse")
        .addClass("active");
    }
  );
  jQuery(document).on("click", "#close-sidebar-popup", function () {
    jQuery(this).closest(".accordion-collapse").removeClass("active");
  });

  if (jQuery(window).width() < 768) {
    if (jQuery(".vp-product-gallery:not(.list-page)").length > 0) {
      jQuery(".vp-product-gallery:not(.list-page)").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        infinite: true,
        fade: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
      });
    }
  }

  jQuery(document).on("click", "[value-pack-data-slide-depends]", function (e) {
    var slidenoid = jQuery(this).attr("value-pack-data-slide-depends");
    jQuery(this).closest(".e-parent").find(".cubewp-post-slider").find(".product").removeClass('hover-active');
    var slideno = jQuery(this).closest(".e-parent").find(".cubewp-post-slider").find(".post-" + slidenoid).closest(".slick-slide").data("slick-index");
    jQuery(this).closest(".e-parent").find(".cubewp-post-slider").find(".post-" + slidenoid).addClass('hover-active');
    if (typeof slideno !== "undefined") {
      jQuery(".cubewp-post-slider").slick("slickGoTo", slideno);
    }
  });

  jQuery(document).ready(function () {
    jQuery(".vp-video-play-btn").on("click", function () {
      var button = jQuery(this); // The clicked button
      var video = button.closest(".vp-video-container").find("video").get(0); // Find the closest video
      if (video) {
        if (video.paused) {
          video.play();
          button.find(".elementor-button-text").text("Pause");
          button
            .find(".elementor-button-icon")
            .html(
              '<svg   viewBox="-1 0 8 10" fill="none" xmlns="https://www.w3.org/2000/svg"><path d="M1.47451 1.23481V8.81254C1.47451 9.10319 1.23884 9.33885 0.948191 9.33885C0.657538 9.33885 0.421875 9.10319 0.421875 8.81254V1.23481C0.421875 0.94416 0.657538 0.708496 0.948191 0.708496C1.23884 0.708496 1.47451 0.944118 1.47451 1.23481ZM5.57977 1.23481V8.81254C5.57977 9.10319 5.34411 9.33885 5.05345 9.33885C4.7628 9.33885 4.52714 9.10319 4.52714 8.81254V1.23481C4.52714 0.94416 4.7628 0.708496 5.05345 0.708496C5.34411 0.708496 5.57977 0.944118 5.57977 1.23481Z" fill="#1D1D1D"/></svg>'
            );
        } else {
          video.pause();
          button.find(".elementor-button-text").text("Play");
          button
            .find(".elementor-button-icon")
            .html(
              '<svg  viewBox="0 0 8 10" fill="none" xmlns="https://www.w3.org/2000/svg"><path d="M7.43704 4.09336L1.36679 0.638542C0.722884 0.270939 0.0664062 0.672967 0.0664062 1.3959V8.60818C0.0664062 9.22065 0.465142 9.49994 0.835739 9.49994C1.01026 9.49994 1.18808 9.44486 1.36469 9.33739L7.46218 5.60508C7.76872 5.41679 7.94085 5.13809 7.93546 4.84024C7.93037 4.54208 7.74896 4.26967 7.43704 4.09336ZM7.05357 4.93843L0.956378 8.66984C0.915367 8.69499 0.883037 8.70756 0.860885 8.71385C0.854898 8.69169 0.848911 8.65757 0.848911 8.60818V1.3962C0.848911 1.32196 0.862382 1.28454 0.862382 1.27526C0.88633 1.27676 0.928239 1.28903 0.980925 1.31897L7.05027 4.77378C7.12511 4.81659 7.14726 4.85371 7.15445 4.84563C7.15056 4.8558 7.12511 4.89382 7.05357 4.93843Z" fill="#1D1D1D"/></svg>'
            );
        }
      }
    });
  });

  jQuery(document).ready(function ($) {
    jQuery(document).on("click", ".vp-product-tabs-show", function () {
      jQuery(".cubewp-tabber-main.sidebar, .cubewp-accordion-main.sidebar").addClass("active");
      jQuery(".header-hide-before").addClass("active");
    });
    jQuery(document).on("click", ".vp-product-tabs-close", function () {
      jQuery(".cubewp-tabber-main.sidebar, .cubewp-accordion-main.sidebar").removeClass("active");
      jQuery(".header-hide-before").removeClass("active");
    });
    jQuery(document).on("click", ".popup-close-btn", function () {
      var $accordionCollapse = jQuery(this).closest(".accordion-collapse");
      $accordionCollapse.removeClass("show").collapse("hide");
      $accordionCollapse.closest(".accordion-item").removeClass("layer");
      jQuery(".vp-sticky-header").removeClass("remove-zindex");
      jQuery(".woo-sticky-ad-to-cart-container").show();
      jQuery(".accordion-z-index").removeClass("high-index");

    });

    function updateAccordionLayer() {
      jQuery(
        ".cubewp-accordion-main .accordion.popup .accordion-collapse.collapse"
      ).each(function () {
        if (jQuery(this).hasClass("show")) {
          jQuery(this).closest(".accordion-item").addClass("layer");
          jQuery(this).closest(".accordion-z-index").addClass("high-index");
        } else {
          jQuery(this).closest(".accordion-item").removeClass("layer");
          jQuery(this).closest(".accordion-z-index").removeClass("high-index");
        }
      });
    }
    updateAccordionLayer();
    jQuery(".cubewp-accordion-main .accordion-collapse.collapse").on(
      "shown.bs.collapse hidden.bs.collapse",
      function () {
        jQuery(".vp-sticky-header").addClass("remove-zindex");
        jQuery(".woo-sticky-ad-to-cart-container").hide();
        updateAccordionLayer();
      }
    );
  });

  /*----- Product Review Show More ----------*/
  jQuery(document).ready(function ($) {
    $(".vp-review-content").each(function () {
      var $reviewText = $(this).find(".vp-review-text");
      var $btn = $(this).find(".vp-review-show-more");

      var fullHeight = $reviewText.prop("scrollHeight");
      var lineHeight = parseInt($reviewText.css("line-height"));
      var maxHeight = lineHeight * 3;

      if (fullHeight > maxHeight) {
        $btn.show();
      }

      $btn.click(function () {
        if ($reviewText.css("-webkit-line-clamp") === "3") {
          $reviewText.css({
            "-webkit-line-clamp": "unset",
            overflow: "visible",
          });
          $(this).find(".btn-text").text("Less Detail");
          $(this)
            .find("i")
            .removeClass("fa-chevron-down")
            .addClass("fa-chevron-up");
        } else {
          $reviewText.css({
            "-webkit-line-clamp": "3",
            overflow: "hidden",
          });
          $(this).find(".btn-text").text("More Detail");
          $(this)
            .find("i")
            .removeClass("fa-chevron-up")
            .addClass("fa-chevron-down");
        }
      });
    });
  });

  jQuery(document).on(
    "mouseenter",
    ".vp-product-gallery-as-navbar .slick-slide",
    function () {
      const index = jQuery(this).data("slick-index");
      jQuery(".vp-product-gallery-as-navbar").slick("slickGoTo", index);
    }
  );

  var $accordionItems = $(".cubewp-accordion .accordion-item");

  if ($accordionItems.length > 0) {
    $accordionItems.each(function () {
      var $accordionBody = $(this).find(".accordion-body");

      if ($accordionBody.length > 0) {
        var contentClone = $accordionBody.clone();
        contentClone.find(".popup-close-btn").remove();

        if (
          contentClone.text().trim() === "" &&
          contentClone.find(":not(.popup-close-btn)").length === 0
        ) {
          $(this).remove(); // Or use $(this).hide();
        }
      }
    });
  }

  var $tabPanes = $(".cubewp-tabber .tab-pane");

  if ($tabPanes.length > 0) {
    $tabPanes.each(function () {
      var $tabPane = $(this);
      var paneContent = $tabPane.clone();

      paneContent.find(":empty").remove();

      if (
        paneContent.text().trim() === "" &&
        paneContent.children().length === 0
      ) {
        var tabId = $tabPane.attr("id");

        if (tabId && $('button[data-bs-target="#' + tabId + '"]').length > 0) {
          $('button[data-bs-target="#' + tabId + '"]')
            .closest(".nav-item")
            .hide();
        }
      }
    });
  }

  // 	add collective product to cart
  $(document).on("click", ".add-multiple-to-cart", function (e) {
    e.preventDefault();
    var $btn = $(this);
    var product_ids = $btn.data("pid");
    var variation_enabled = $btn.hasClass("variation-enables");
    $.ajax({
      url: value_pack_ajax_params.ajax_url,
      type: "POST",
      data: {
        action: "add_multiple_products_to_cart",
        product_ids: product_ids,
        variation_enabled: variation_enabled // true or false
      },
      success: function (response) {
        console.log(response);
        if (response.success === true) {
          window.location.href = response.url;
        } else {
          alert(response.text);
        }
      }
    });
  });


  function vp_product_image_list($scope) {
    var $mainSlider = $scope.find(".vp-product-image-list");
    var $thumbSlider = $scope.find(".vp-product-gallery-list");

    if (!$mainSlider.length || !$thumbSlider.length) return;

    $mainSlider.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: $thumbSlider,
    });

    $thumbSlider.slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      asNavFor: $mainSlider,
      dots: false,
      focusOnSelect: true,
    });
  }

  // sticky cart JS end
  function initializeQuantityButtons() {
    jQuery(".quantity").each(function () {
      var $quantity = jQuery(this);
      var $input = $quantity.find("input");
      var $input_type = $input.attr("type");
      if ($input_type != "hidden") {
        // Add the buttons only if they don't exist
        if (!$quantity.find(".minus-variations").length && !$quantity.find(".plus-variations").length) {
          jQuery('<button type="button" class="minus-variations">-</button>').insertBefore($input);
          jQuery('<button type="button" class="plus-variations">+</button>').insertAfter($input);
        }

      } else {
        $quantity.hide();
      }
    });
  }
  // Initialize on page load
  initializeQuantityButtons();

  // Reinitialize on cart totals update
  $(document.body).on("updated_cart_totals", function () {
    initializeQuantityButtons();
  });


  $(document).on("click", ".plus-variations", function () {
    var $input = $(this).closest('.quantity').find('input');
    var currentVal = parseInt($input.val());
    if (!isNaN(currentVal)) {
      $input.val(currentVal + 1);
      checkButtons($input);
    }
  });
  $(document).on("click", ".minus-variations", function () {
    var $input = $(this).closest('.quantity').find('input');
    var currentVal = parseInt($input.val());
    if (!isNaN(currentVal) && currentVal > 1) {
      $input.val(currentVal - 1);
      checkButtons($input);
    }
  });

  function checkButtons($input) {
    var currentVal = parseInt($input.val());
    var maxVal = parseInt($input.attr("max"));
    var $plusBtn = $input.siblings(".plus-variations");
    var $minusBtn = $input.siblings(".minus-variations");
    // Disable/Enable plus button
    if (!isNaN(maxVal) && currentVal >= maxVal) {
      $plusBtn.prop("disabled", true);
    } else {
      $plusBtn.prop("disabled", false);
    }
    // Disable/Enable minus button
    if (currentVal <= 1) {
      $minusBtn.prop("disabled", true);
    } else {
      $minusBtn.prop("disabled", false);
    }
  }


  function initProductGallerySlider($scope) {
    var $gallerySliders = $scope.find(".vp-product-gallery-slider");
    if (!$gallerySliders.length) return;
    $gallerySliders.each(function () {
      var $this = $(this);
      var slidesToShow = $this.data("slides-to-show");
      var MainSlidesToShow = $this.data("main-slide-to-show");
      var SidebarSlidesToShow = $this.data("navbar-slide-to-show");
      var slidesToScroll = $this.data("slide-to-scroll");
      var speed = $this.data("speed");
      var arrows = $this.data("arrows");
      var arrowIconLeft = $this.data("arrow-icon-left");
      var arrowIconRight = $this.data("arrow-icon-right");
      var infinite = $this.data("infinite");
      var autoplay = $this.data("autoplay");
      var autoplaySpeed = $this.data("autoplay-speed");
      var fade = $this.data("fade");
      var cssEase = $this.data("css-ease");
      var focusOnselect = $this.data("focus-on-select");
      var navbarVertical = $this.data("navbar-vertical");
      var centerMode = $this.data("center-mode");

      var $navbar = $scope.find(".vp-product-gallery-as-navbar");

      if ($navbar.length > 0 && $navbar.data("slider-as-nav") === "yes") {
        $this.slick({
          slidesToShow: MainSlidesToShow,
          slidesToScroll: slidesToScroll,
          asNavFor: $navbar,
          arrows: arrows,
          prevArrow: '<button type="button" class="slick-prev"><img src="' +
            arrowIconLeft +
            '" alt="Previous"></button>',
          nextArrow: '<button type="button" class="slick-next"><img src="' +
            arrowIconRight +
            '" alt="Next"></button>',
          fade: fade,
          speed: speed,
          autoplay: autoplay,
          autoplaySpeed: autoplaySpeed,
          infinite: infinite,
          dots: false,
          cssEase: cssEase,
        });

        $navbar.slick({
          slidesToShow: SidebarSlidesToShow,
          slidesToScroll: slidesToScroll,
          asNavFor: $this,
          arrows: false,
          focusOnSelect: focusOnselect,
          speed: speed,
          autoplay: autoplay,
          autoplaySpeed: autoplaySpeed,
          infinite: infinite,
          dots: false,
          cssEase: cssEase,
          vertical: navbarVertical,
          verticalSwiping: navbarVertical,
          centerMode: centerMode,
        });
      } else {
        $this.slick({
          slidesToShow: slidesToShow,
          slidesToScroll: slidesToScroll,
          speed: speed,
          arrows: arrows,
          prevArrow: '<button type="button" class="slick-prev"><img src="' +
            arrowIconLeft +
            '" alt="Previous"></button>',
          nextArrow: '<button type="button" class="slick-next"><img src="' +
            arrowIconRight +
            '" alt="Next"></button>',
          infinite: infinite,
          autoplay: autoplay,
          autoplaySpeed: autoplaySpeed,
          fade: fade,
          dots: false,
          cssEase: cssEase,
        });
      }
    });
  }

  function vpGetVariationImageData(variation) {
    if (!variation || !variation.image) return null;
    var img = variation.image;
    var fullSrc = img.full_src || img.src || "";
    if (!fullSrc) return null;
    return {
      full_src: fullSrc,
      src: img.src || fullSrc,
      src_w: img.src_w || img.full_src_w || img.full_src_width || null,
      src_h: img.src_h || img.full_src_h || img.full_src_height || null,
      gallery_thumbnail_src: img.gallery_thumbnail_src || img.src || fullSrc,
    };
  }

  function vpCacheOriginalGalleryState($container) {
    if (!$container || !$container.length) return;
    if ($container.data("vpVariationCacheReady")) return;

    var $zoomLink = $container.find("a.woo-gallery-images.value-pack-open-gallery").first();
    var $mainFirstLink = $container.find(".vp-product-gallery-slider a.value-pack-open-gallery, .mobile-gallery-slider a.value-pack-open-gallery, .row a.value-pack-open-gallery").first();
    var $mainFirstImg = $mainFirstLink.find("img").first();

    $container.data("vpVariationCacheReady", true);
    $container.data("vpOriginalZoomHref", $zoomLink.attr("href") || "");
    $container.data("vpOriginalZoomW", $zoomLink.attr("data-pswp-width") || "");
    $container.data("vpOriginalZoomH", $zoomLink.attr("data-pswp-height") || "");
    $container.data("vpOriginalFirstHref", $mainFirstLink.attr("href") || "");
    $container.data("vpOriginalFirstSrc", $mainFirstImg.attr("src") || "");
    $container.data("vpOriginalFirstDataSrc", $mainFirstImg.attr("data-src") || "");
  }

  function vpUpdateGalleryLinksAndImage($container, imgData) {
    if (!$container || !$container.length || !imgData) return;

    var $zoomLink = $container.find("a.woo-gallery-images.value-pack-open-gallery").first();
    if ($zoomLink.length) {
      $zoomLink.attr("href", imgData.full_src);
      if (imgData.src_w) $zoomLink.attr("data-pswp-width", imgData.src_w);
      if (imgData.src_h) $zoomLink.attr("data-pswp-height", imgData.src_h);
    }

    // Try to navigate to an existing slide first (no markup changes).
    var $mainSlider = $container.find(".vp-product-gallery-slider").first();
    if ($mainSlider.length && $mainSlider.hasClass("slick-initialized")) {
      var matchIndex = -1;
      $mainSlider.find(".slick-slide a.value-pack-open-gallery").each(function () {
        var href = $(this).attr("href") || "";
        if (href && href === imgData.full_src) {
          matchIndex = $(this).closest(".slick-slide").data("slick-index");
          return false;
        }
      });

      if (matchIndex !== -1 && typeof $mainSlider.slick === "function") {
        $mainSlider.slick("slickGoTo", matchIndex);
        var $nav = $container.find(".vp-product-gallery-as-navbar").first();
        if ($nav.length && $nav.hasClass("slick-initialized")) {
          $nav.slick("slickGoTo", matchIndex);
        }
        return;
      }
    }

    // Fallback: replace the first visible item (keeps layout intact).
    var $firstLink =
      $container.find(".vp-product-gallery-slider a.value-pack-open-gallery").first();
    if (!$firstLink.length) {
      $firstLink =
        $container.find(".mobile-gallery-slider a.value-pack-open-gallery").first();
    }
    if (!$firstLink.length) {
      $firstLink = $container.find(".row a.value-pack-open-gallery").first();
    }
    var $firstImg = $firstLink.find("img").first();

    if ($firstLink.length) {
      $firstLink.attr("href", imgData.full_src);
      if (imgData.src_w) $firstLink.attr("data-pswp-width", imgData.src_w);
      if (imgData.src_h) $firstLink.attr("data-pswp-height", imgData.src_h);
    }
    if ($firstImg.length) {
      $firstImg.attr("src", imgData.src);
      if ($firstImg.attr("data-src") !== undefined) {
        $firstImg.attr("data-src", imgData.src);
      }
    }

    // Update first navbar thumbnail if present.
    var $navFirstImg = $container.find(".vp-product-gallery-as-navbar img.wp-post-image").first();
    if ($navFirstImg.length) {
      $navFirstImg.attr("src", imgData.gallery_thumbnail_src);
    }
  }

  function vpRestoreOriginalGalleryState($container) {
    if (!$container || !$container.length) return;
    if (!$container.data("vpVariationCacheReady")) return;

    var $zoomLink = $container.find("a.woo-gallery-images.value-pack-open-gallery").first();
    if ($zoomLink.length) {
      var oz = $container.data("vpOriginalZoomHref");
      if (oz) $zoomLink.attr("href", oz);
      var ow = $container.data("vpOriginalZoomW");
      var oh = $container.data("vpOriginalZoomH");
      if (ow) $zoomLink.attr("data-pswp-width", ow);
      if (oh) $zoomLink.attr("data-pswp-height", oh);
    }

    var $firstLink =
      $container.find(".vp-product-gallery-slider a.value-pack-open-gallery").first();
    if (!$firstLink.length) {
      $firstLink =
        $container.find(".mobile-gallery-slider a.value-pack-open-gallery").first();
    }
    if (!$firstLink.length) {
      $firstLink = $container.find(".row a.value-pack-open-gallery").first();
    }
    var $firstImg = $firstLink.find("img").first();

    var ofHref = $container.data("vpOriginalFirstHref") || "";
    var ofSrc = $container.data("vpOriginalFirstSrc") || "";
    var ofDataSrc = $container.data("vpOriginalFirstDataSrc") || "";

    if ($firstLink.length && ofHref) $firstLink.attr("href", ofHref);
    if ($firstImg.length && ofSrc) {
      $firstImg.attr("src", ofSrc);
      if ($firstImg.attr("data-src") !== undefined) {
        $firstImg.attr("data-src", ofDataSrc || ofSrc);
      }
    }
  }

  // WooCommerce variation image support for vp-product-gallery-widget.
  // Keeps existing markup/design; only swaps active slide image/href.
  $(document).on("found_variation", "form.variations_form", function (e, variation) {
    var imgData = vpGetVariationImageData(variation);
    if (!imgData) return;

    var $form = $(this);
    var $product = $form.closest(".product");
    var $container = $product.find(".vp-product-gallery-container").first();
    if (!$container.length) {
      $container = $(".vp-product-gallery-container").first();
    }
    if (!$container.length) return;

    vpCacheOriginalGalleryState($container);
    vpUpdateGalleryLinksAndImage($container, imgData);
  });

  $(document).on("reset_data", "form.variations_form", function () {
    var $form = $(this);
    var $product = $form.closest(".product");
    var $container = $product.find(".vp-product-gallery-container").first();
    if (!$container.length) {
      $container = $(".vp-product-gallery-container").first();
    }
    if (!$container.length) return;

    vpRestoreOriginalGalleryState($container);
  });

  function initProducttype_slider($scope) {
    var sliderElement = $scope.find(".vp-product-slider");
    if (!sliderElement.length) return;
    sliderElement.each(function () {
      var $this = $(this);
      var enableArrow = $this.data("enable-arrow");
      var enableDots = $this.data("enable-dots");
      var prevArrowHtml = $this.data("prev-arrow");
      var nextArrowHtml = $this.data("next-arrow");
      var slidesToShow = $this.data("slides-to-show");
      var slidesToScroll = $this.data("slides-to-scroll");
      var autoplay =
        $this.data("autoplay") === true || $this.data("autoplay") === "true";
      var autoplaySpeed = $this.data("autoplay-speed");
      var infinite =
        $this.data("infinite") === true || $this.data("infinite") === "true";
      var prevArrowButton =
        '<button type="button" class="slick-prev">' +
        prevArrowHtml +
        "</button>";
      var nextArrowButton =
        '<button type="button" class="slick-next">' +
        nextArrowHtml +
        "</button>";

      $this.slick({
        slidesToShow: slidesToShow,
        slidesToScroll: slidesToScroll,
        autoplay: autoplay,
        autoplaySpeed: autoplaySpeed,
        infinite: infinite,
        arrows: enableArrow,
        prevArrow: prevArrowButton,
        nextArrow: nextArrowButton,
        dots: enableDots,
        responsive: [{
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: enableDots,
            arrows: enableArrow,
          }
        }],
      }); // End of Slick Slider
    });
  }


  function vp_add_custom_editor_css(css, context) {
    if (!context) {
      return css;
    }

    var model = context.model,
      customCSS = model.get('settings').get('vp_custom_css');
    var selector = '.elementor-element.elementor-element-' + model.get('id');

    if ('document' === model.get('elType')) {
      selector = elementor.config.document.settings.cssWrapperSelector;
    }

    if (customCSS) {
      css += customCSS.replace(/selector/g, selector);
    }

    return css;
  }

  (function checkElementor() {
    if (typeof elementorFrontend !== "undefined" && elementorFrontend.hooks) {
      elementorFrontend.hooks.addAction("frontend/element_ready/vp_product_gallery.default", initProductGallerySlider);
      elementorFrontend.hooks.addAction("frontend/element_ready/vp_single_product_detail.default", vp_product_image_list);
      elementorFrontend.hooks.addAction("frontend/element_ready/vp_single_product_detail.default", initializeQuantityButtons);
      elementorFrontend.hooks.addAction("frontend/element_ready/vp_product_variations.default", initializeQuantityButtons);
      elementorFrontend.hooks.addAction("frontend/element_ready/vp_products_by_type.default", initProducttype_slider);
      elementorFrontend.hooks.addFilter('editor/style/styleText', vp_add_custom_editor_css);
      if (typeof elementor !== 'undefined' && elementor.hooks) {
        elementor.hooks.addFilter('editor/style/styleText', vp_add_custom_editor_css);
      }
    } else {
      setTimeout(checkElementor, 50); // re-check every 50ms
    }
  })();


  function initializeCustomScrollbars() {
    if ($(".custom-scroll-enabled").length > 0) {
      $(".custom-scroll-enabled").each(function () {
        const $wrapper = $(this);
        const $items = $wrapper.children();
        $items.wrapAll(
          '<div class="vp-data-scrollbar-inner  d-flex overflow-hidden"></div>'
        );

        const $style = $wrapper.data("style");
        const $buttonss = $wrapper.data("buttons");
        const $icon_next = $wrapper.data("scroll-button-icon-next");
        const $icon_prev = $wrapper.data("scroll-button-icon-prev");

        const $scrollbar = $(`<div class="custom-scrollbar"><div class="scroll-thumb"></div></div>`);

        const $buttons = $(`<button class="vp_prevBtn_scroll" aria-label="Scroll left">` + $icon_next + `</button><button class="vp_nextBtn_scroll" aria-label="Scroll right">` + $icon_prev + `</button>`);

        if ($style == "style1") {
          $wrapper.append('<div class="vp-scrollbar-button-wrape"></div>');
          $wrapper.find(".vp-scrollbar-button-wrape").append($scrollbar);
          if ($buttonss == "yes") {
            $wrapper.find(".vp-scrollbar-button-wrape").append($buttons);
          }
        } else {
          $wrapper.append($scrollbar);
          $wrapper.append('<div class="vp-scrollbar-button-wrapes"></div>');
          if ($buttonss == "yes") {
            $wrapper.find(".vp-scrollbar-button-wrapes").append($buttons);
          }
        }

        const $container = $wrapper.find(".vp-data-scrollbar-inner");

        const $thumb = $wrapper.find(".scroll-thumb");
        const $vp_nextBtn_scroll = $wrapper.find(".vp_nextBtn_scroll");
        const $vp_prevBtn_scroll = $wrapper.find(".vp_prevBtn_scroll");
        const scrollStep = 220;
        const animationSpeed = 300;

        function updateThumb() {
          const containerWidth = $container.outerWidth();
          const scrollWidth = $container[0].scrollWidth;
          const scrollLeft = $container.scrollLeft();
          const trackWidth = $scrollbar.width();

          const thumbWidth = (containerWidth / scrollWidth) * trackWidth;
          const thumbLeft = (scrollLeft / scrollWidth) * trackWidth;

          $thumb.css({
            width: `${thumbWidth}px`,
            marginLeft: `${thumbLeft}px`,
          });
        }

        function scrollTo(direction) {
          const currentScroll = $container.scrollLeft();
          const maxScroll = $container[0].scrollWidth - $container.outerWidth();
          let target =
            direction === "next" ?
              currentScroll + scrollStep :
              currentScroll - scrollStep;

          target = Math.max(0, Math.min(target, maxScroll));
          $container.stop().animate({
            scrollLeft: target
          }, animationSpeed);
        }

        $vp_nextBtn_scroll.on("click", () => scrollTo("next"));
        $vp_prevBtn_scroll.on("click", () => scrollTo("prev"));

        $container.on("scroll", updateThumb);
        $(window).on("resize", updateThumb);

        // Dragging the custom thumb
        let isDragging = false;
        let startX = 0;
        let startScroll = 0;

        $thumb.on("mousedown", function (e) {
          isDragging = true;
          startX = e.pageX;
          startScroll = $container.scrollLeft();
          e.preventDefault();
        });

        $(document).on("mousemove", function (e) {
          if (!isDragging) return;
          const deltaX = e.pageX - startX;
          const scrollRatio = $container[0].scrollWidth / $scrollbar.width();
          $container.scrollLeft(startScroll + deltaX * scrollRatio);
        });

        $(document).on("mouseup", () => (isDragging = false));

        // Initial sync
        updateThumb();
      });
    }
  }

  $(document).ready(function () {

    initializeCustomScrollbars();

    jQuery(document.body).on('cubewp_posts_loaded', function (event) {
      $('.custom-scroll-enabled').each(function () {
        const $wrapper = $(this);
        const $container = $wrapper.find(".vp-data-scrollbar-inner");
        const $thumb = $wrapper.find(".scroll-thumb");
        const $scrollbar = $wrapper.find(".custom-scrollbar");
        const containerWidth = $container.outerWidth();
        const scrollWidth = $container[0].scrollWidth;
        const scrollLeft = $container.scrollLeft();
        const trackWidth = $scrollbar.width();
        const thumbWidth = (containerWidth / scrollWidth) * trackWidth;
        const thumbLeft = (scrollLeft / scrollWidth) * trackWidth;
        $thumb.css({
          width: `${thumbWidth}px`,
          marginLeft: `${thumbLeft}px`,
        });
      });
    });

    jQuery(document).on('click', '.e-n-tabs-heading .e-n-tab-title', function () {
      const ariaControls = jQuery(this).attr('aria-controls');

      if (ariaControls) {
        const $target = jQuery('#' + ariaControls);
        const $wrapper = $target.find('.custom-scroll-enabled');

        if ($wrapper.length) {
          const $container = $wrapper.find(".vp-data-scrollbar-inner");
          const $thumb = $wrapper.find(".scroll-thumb");
          const $scrollbar = $wrapper.find(".custom-scrollbar");

          const containerWidth = $container.outerWidth();
          const scrollWidth = $container[0].scrollWidth;
          const scrollLeft = $container.scrollLeft();
          const trackWidth = $scrollbar.width();

          const thumbWidth = (containerWidth / scrollWidth) * trackWidth;
          const thumbLeft = (scrollLeft / scrollWidth) * trackWidth;

          $thumb.css({
            width: `${thumbWidth}px`,
            marginLeft: `${thumbLeft}px`,
          });
        }
      }
    });

    if ($(".data-image-container").length > 0) {
      $(".data-image-container").each(function () {
        const $dataImage = $(this);
        const $imageContainer = $dataImage.find(".animated-fix-image-container");
        const $boxes = $imageContainer.find(".animated-fix-image-slide");
        const scrollStep = $(window).height();

        // Set z-index dynamically (top image = highest z-index)
        $boxes.each(function (index) {
          $(this).css("z-index", $boxes.length - index);
        });

        function updatePosition() {
          const dataImageTop = $dataImage.offset().top;
          const scrollTop = $(window).scrollTop();

          // If the top of .data-image-container touches the top of the viewport, fix it
          if (scrollTop >= dataImageTop) {
            $imageContainer.css("position", "fixed");
            $imageContainer.css("width", "50%");
            $imageContainer.css("top", "0");
          } else {
            $imageContainer.css("position", "absolute");
            $imageContainer.css("top", "");
            $imageContainer.css("width", "100%");
          }
        }

        function updateImages() {
          const dataImageTop = $dataImage.offset().top;
          const scrollTop = $(window).scrollTop();
          const currentTotalHeight = $(window).height();

          // Only start the image steps when container is fixed (scrolled past .data-image-container top)
          let effectiveScroll = 0;
          if (scrollTop >= dataImageTop) {
            effectiveScroll = scrollTop - dataImageTop;
          }

          $boxes.each(function (i) {
            const offset = effectiveScroll - i * scrollStep;
            const newHeight = Math.max(
              currentTotalHeight - Math.max(offset, 0),
              0
            );
            $(this).height(newHeight);
          });
        }

        // Bind scroll/resize for each .data-image-container section
        $(window).on("scroll resize", function () {
          updatePosition();
          updateImages();
        });

        // Initial trigger
        $(window).trigger("resize");
      });
    }
    if ($('.sticky-enable-icon-control').length > 0) {
      const $stickyEnableIconControl = $('.sticky-enable-icon-control');
      const maxScroll = $stickyEnableIconControl.data('transform');
      const startScale = $stickyEnableIconControl.data('scale');
      const endScale = 1;
      const startTranslate = $stickyEnableIconControl.data('transform');
      const endTranslate = 0;
      const completeClass = 'vp-animation-complete';

      $(window).on('scroll', function () {
        const scrollY = Math.min($(window).scrollTop(), maxScroll);

        // Calculate scroll progress (0 to 1)
        const progress = scrollY / maxScroll;

        // Interpolate values
        const scale = startScale - (startScale - endScale) * progress;
        const translateY = startTranslate - (startTranslate - endTranslate) * progress;

        // Apply transform
        $stickyEnableIconControl.css('transform', `translateY(${translateY}px) scale(${scale})`);

        // Add/remove completion class
        if (scrollY >= maxScroll) {
          $stickyEnableIconControl.addClass(completeClass);
        } else {
          $stickyEnableIconControl.removeClass(completeClass);
        }
      });
    }

  });


  jQuery(document).ready(function ($) {
    $('.product-hover-cards .variations_form').each(function () {
      var form = $(this);

      form.on('found_variation', function (event, variation) {
        var variationID = variation.variation_id;
        var variationPrice = variation.price_html;

        // Fallback: get default price if variation price is empty
        if (!variationPrice || variationPrice.trim() === '') {
          variationPrice = form.closest('.product-hover-cards').find('.default-price-html').html();
        }

        // Update price display
        form.closest('.product-hover-cards').find('.collective-p-price').html(variationPrice);
        form.find('input[name="variation_id"]').val(variationID);

        // Calculate total price and build combined PID|VID list
        let total = 0;
        let combinedIDs = [];

        form.closest('.wc-women-collective-products').find('.product-hover-cards').each(function () {
          var container = $(this);
          var pid = container.find('form.variations_form').data('product_id') || container.data('pid');
          var vid = container.find('input[name="variation_id"]').val();
          var priceText = container.find('.collective-p-price').text().replace(/[^0-9.,]/g, '').replace(',', '.');
          var price = parseFloat(priceText);

          if (!isNaN(price)) {
            total += price;
          }

          if (pid && vid) {
            combinedIDs.push(pid + '|' + vid);
          }
        });

        // Update total price and data-pid attribute
        form.closest('.wc-women-collective-products').find('.add-multiple-to-cart span').html(total.toFixed(2));
        form.closest('.wc-women-collective-products').find('.add-multiple-to-cart').attr('data-pid', combinedIDs.join(','));

      });
    });
  });




  jQuery(document).ready(function ($) {
    $('.product-hover-cards').each(function () {
      var card = $(this);
      var form = card.find('form.variations_form');
      setTimeout(function () {
        card.find('.wm-product-attributes ul li:first-child span').trigger('click');
      }, 200)
    });
    $(document).on("click", ".vp-iconic-main-container.product-look-view .wc-woo-icon-products .vp-elementor-icon", function (event) {
      event.preventDefault();
      $(this).closest('.wc-woo-icon-products').addClass("active");
    });
    $(document).on("click", ".product-hover-cards-look-data .vp-iconic-product-close ", function (event) {
      event.preventDefault();
      $(this).closest('.wc-woo-icon-products').removeClass("active");
    });

    jQuery(document).on('click', '.clear-filters', function ($) {
      var $container = jQuery(this).closest('.elementor[data-elementor-type="wp-post"]');

      if ($container.length === 0) {
        return;
      }

      // Optional: Log the number of such containers on the page (for debug)
      console.log('Found elementor containers:', jQuery('.elementor[data-elementor-type="wp-post"]').length);

      var $filters = $container.find('.cwp-search-filters');
      var PostType = $filters.find('.cwp-search-filters-fields input[name="post_type"]').val();

      $filters.find('.cwp-search-filters-fields input[type="text"]').val('');
      $filters.find('.cwp-search-filters-fields input[type="number"]').val('');
      $filters.find('.cwp-search-filters-fields input[type="cwp-date-range"]').val('');

      if ($filters.find('.cwp-search-filters-fields input[type="hidden"]').hasClass('is_tax')) {
        var currentVal = $filters.find('.is_tax').attr('data-current-tax');
        $filters.find('.is_tax').val(currentVal);
      } else {
        $filters.find('.cwp-search-filters-fields input[type="hidden"]').val('');
      }

      $filters.find('.cwp-search-filters-fields input[type="radio"]').prop("checked", false);
      $filters.find('.cwp-search-filters-fields input[type="checkbox"]').prop('checked', false);
      $filters.find('.cwp-search-filters-fields select').val('');
      $filters.find('input[name="page_num"]').val('1');
      jQuery('select[name="cwp_orderby"]').val('');
      $filters.find('.cwp-search-filters-fields input[type="google_address"]').val('');

      if ($filters.find('.cwp-address-range').length > 0) {
        $filters.find('.cwp-address-range').addClass("cwp-hide");
        $filters.find('.cwp-search-filters-fields input[type="range"]').attr('type', 'hidden').removeAttr("value min max");
        $filters.find('.cwp-search-filters-fields .cwp-search-field-google_address input[type="range"]').attr('type', 'hidden').removeAttr("value min max");
      }

      $filters.find('.cwp-search-filters-fields input[name="post_type"]').val(PostType);

      if ($filters.find(".cwp-select2 select").length > 0) {
        $filters.find(".cwp-select2 select").val(null).trigger("change");
      } else {
        cwp_search_filters_ajax_content('');
      }
    });

  });

  function initializeCustomScrollbarsElementor() {
    if ($(".custom-scroll-enabled").length > 0) {
      $(".custom-scroll-enabled").each(function () {
        const $wrapper = $(this);
        // Wrap items only if not already wrapped
        if ($wrapper.find(".vp-data-scrollbar-inner").length === 0) {
          const $items = $wrapper.children().not(".vp-data-scrollbar-inner, .custom-scrollbar, .vp-scrollbar-button-wrape, .vp-scrollbar-button-wrapes");
          $items.wrapAll(
            '<div class="vp-data-scrollbar-inner d-flex overflow-hidden"></div>'
          );
        }
        const $style = $wrapper.data("style");
        const $buttonss = $wrapper.data("buttons");
        const $icon_next = $wrapper.data("scroll-button-icon-next");
        const $icon_prev = $wrapper.data("scroll-button-icon-prev");
        if ($wrapper.find(".custom-scrollbar").length === 0) {
          $wrapper.append('<div class="custom-scrollbar"><div class="scroll-thumb"></div></div>');
        }
        const $scrollbar = $wrapper.find(".custom-scrollbar");
        if ($buttonss === "yes" && $wrapper.find(".vp_prevBtn_scroll").length === 0) {
          const $buttons = $(
            `<button class="vp_prevBtn_scroll" aria-label="Scroll left">${$icon_prev}</button>
<button class="vp_nextBtn_scroll" aria-label="Scroll right">${$icon_next}</button>`
          );
          if ($style === "style1") {
            if ($wrapper.find(".vp-scrollbar-button-wrape").length === 0) {
              $wrapper.append('<div class="vp-scrollbar-button-wrape"></div>');
            }
            $wrapper.find(".vp-scrollbar-button-wrape").append($buttons);
          } else {
            if ($wrapper.find(".vp-scrollbar-button-wrapes").length === 0) {
              $wrapper.append('<div class="vp-scrollbar-button-wrapes"></div>');
            }
            $wrapper.find(".vp-scrollbar-button-wrapes").append($buttons);
          }
        }
        const $container = $wrapper.find(".vp-data-scrollbar-inner");
        const $thumb = $wrapper.find(".scroll-thumb");
        const $vp_nextBtn_scroll = $wrapper.find(".vp_nextBtn_scroll");
        const $vp_prevBtn_scroll = $wrapper.find(".vp_prevBtn_scroll");
        const scrollStep = 220;
        const animationSpeed = 300;

        function updateThumb() {
          const containerWidth = $container.outerWidth();
          const scrollWidth = $container[0].scrollWidth;
          const scrollLeft = $container.scrollLeft();
          const trackWidth = $scrollbar.width();
          const thumbWidth = (containerWidth / scrollWidth) * trackWidth;
          const thumbLeft = (scrollLeft / scrollWidth) * trackWidth;
          $thumb.css({
            width: `${thumbWidth}px`,
            marginLeft: `${thumbLeft}px`,
          });
        }

        function scrollTo(direction) {
          const currentScroll = $container.scrollLeft();
          const maxScroll = $container[0].scrollWidth - $container.outerWidth();
          let target =
            direction === "next" ?
              currentScroll + scrollStep :
              currentScroll - scrollStep;
          target = Math.max(0, Math.min(target, maxScroll));
          $container.stop().animate({
            scrollLeft: target
          },
            animationSpeed
          );
        }
        // Prevent multiple event bindings by `.off().on()`
        $vp_nextBtn_scroll.off("click").on("click", () => scrollTo("next"));
        $vp_prevBtn_scroll.off("click").on("click", () => scrollTo("prev"));
        $container.off("scroll").on("scroll", updateThumb);
        $(window).off("resize.scrollbar").on("resize.scrollbar", updateThumb);
        // Dragging
        let isDragging = false;
        let startX = 0;
        let startScroll = 0;
        $thumb.off("mousedown").on("mousedown", function (e) {
          isDragging = true;
          startX = e.pageX;
          startScroll = $container.scrollLeft();
          e.preventDefault();
        });
        $(document)
          .off("mousemove.scrollbar")
          .on("mousemove.scrollbar", function (e) {
            if (!isDragging) return;
            const deltaX = e.pageX - startX;
            const scrollRatio = $container[0].scrollWidth / $scrollbar.width();
            $container.scrollLeft(startScroll + deltaX * scrollRatio);
          });
        $(document)
          .off("mouseup.scrollbar")
          .on("mouseup.scrollbar", () => (isDragging = false));
        // Initial sync
        updateThumb();
      });
    }
    // Also update the thumb when cubewp_posts_loaded event is triggered (for dynamic content)
  }

  window.addEventListener('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/container', function ($scope) {
      if (elementorFrontend.isEditMode()) {
        class ContainerHandler extends elementorModules.frontend.handlers.Base {
          onInit() {
            super.onInit();

            const settings = this.getElementSettings();
            // Parse slider settings safely
            const enable_slider = settings.enable_slider;
            const slides_to_show = parseInt(settings.slides_to_show) || 1;
            const slides_to_scroll = parseInt(settings.slides_to_scroll) || 1;
            const autoplay = settings.autoplay === 'yes';
            const autoplay_speed = parseInt(settings.autoplay_speed) || 3000;
            const custom_speed = parseInt(settings.custom_speed) || 500;
            const infinite = settings.infinite === 'yes';
            const variableWidth = settings.variableWidth === 'yes';
            const fade_effect = settings.fade_effect === 'yes';
            const draggable = settings.draggable === 'yes';
            const vertical = settings.vertical === 'yes';
            const easing = settings.easing || 'linear';



            if (enable_slider === 'yes') {
              var $slider = $scope.find('> .my-slider-wrapper');
              if ($scope.find('> .my-slider-wrapper').length === 0) {
                if ($scope.find('> .e-con-inner').length) {
                  $scope.find('> .e-con-inner').children('.elementor-element').wrapAll('<div class="my-slider-wrapper"></div>');
                } else {
                  $scope.children('.elementor-element').wrapAll('<div class="my-slider-wrapper"></div>');
                }
                $slider = $scope.find('.my-slider-wrapper');
              }

              if ($slider.hasClass('slick-initialized')) {
                $slider.slick('unslick');
              }

              $slider.slick({
                slidesToShow: slides_to_show,
                slidesToScroll: slides_to_scroll,
                autoplay: true,
                autoplaySpeed: autoplay_speed,
                speed: custom_speed,
                infinite: infinite,
                variableWidth: variableWidth,
                fade: fade_effect,
                draggable: draggable,
                vertical: vertical,
                easing: easing,
                dots: false,
                arrows: false,
              });
            }


            // Add custom scroll seciton
            const enable_scroll = settings.enable_scroll;
            if (enable_scroll === 'yes') {
              if ($scope.find('> .custom-scroll-enabled').length === 0) {
                if ($scope.find('> .e-con-inner').length) {
                  $scope.find('> .e-con-inner').children('.elementor-element').wrapAll('<div class="custom-scroll-enabled"></div>');
                } else {
                  $scope.children('.elementor-element').wrapAll('<div class="custom-scroll-enabled"></div>');

                }
                initializeCustomScrollbarsElementor();
              }
            }

          }
          onElementChange(settingName) {
            super.onElementChange?.apply(this, arguments);

            console.log('Setting changed:', settingName);

            const sliderSettings = [
              'enable_slider', 'slides_to_show', 'slides_to_scroll',
              'autoplay', 'autoplay_speed', 'custom_speed',
              'infinite', 'variableWidth', 'fade_effect',
              'draggable', 'vertical', 'easing', 'enable_scroll'
            ];

            if (sliderSettings.includes(settingName)) {
              this.onInit();
            }
          }
        }

        elementorFrontend.elementsHandler.addHandler(ContainerHandler, {
          $element: $scope
        });
      }
    });
  });


  jQuery(document).ready(function ($) {
    setTimeout(function () {
      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        type: "POST",
        data: {
          action: "vp_update_insta_feed_cache"
        },
        success: function (response) {
          // console.log("Cache update response:", response);
        }
      });
    }, 2000);
  });

})(jQuery);


/* Gallery Slider */
function initVPGallerySlider($scope, clicked) {
  // Handle cases where $scope is not provided (regular page loads)
  if (!$scope || !$scope.length) {
    $scope = jQuery(document);
  }
  var sliders = $scope.find('.vp-cubewp-gallery-slider');
  if (!sliders.length) return;
  sliders.each(function () {
    var sliderElement = jQuery(this);

    function vpToPositiveInt(value, fallback) {
      var n = parseInt(value, 10);
      return isNaN(n) || n <= 0 ? fallback : n;
    }

    if (sliderElement.hasClass('slick-initialized')) {
      if (clicked == 'clicked') {
        sliderElement.slick("unslick");
        console.log('have slider');
        sliderElement.addClass('sliderElement');
      } else {
        return;

      }

    }

    var isPrevSvg = sliderElement.data('is-prev-svg');
    var isNextSvg = sliderElement.data('is-next-svg');

    var prevArrowHtml = isPrevSvg ? sliderElement.attr('data-prev-arrow-svg') : sliderElement.data('prev-arrow');

    var nextArrowHtml = isNextSvg ? sliderElement.attr('data-next-arrow-svg') : sliderElement.data('next-arrow');

    var enable_wrapper = sliderElement.data('enable-wrapper');
    var slidesToShow = sliderElement.data('slides-to-show');
    var slidesToScroll = sliderElement.data('slides-to-scroll');
    var slidesToShowTablet = sliderElement.data('slides-to-show-tablet');
    var slidesToShowTabletPortrait = sliderElement.data('slides-show-tablet-portrait');
    var slidesToShowMobile = sliderElement.data('slides-to-show-mobile');
    var slidesToScrollTablet = sliderElement.data('slides-to-scroll-tablet');
    var slidesToScrollTabletPortrait = sliderElement.data('slides-scroll-tablet-portrait');
    var slidesToScrollMobile = sliderElement.data('slides-to-scroll-mobile');
    var autoplay = sliderElement.data('autoplay') === true || sliderElement.data('autoplay') === 'true';
    var autoplaySpeed = sliderElement.data('autoplay-speed');
    var Speed = sliderElement.data('speed');
    var infinite = sliderElement.data('infinite') === true || sliderElement.data('infinite') === 'true';
    var centerMode = sliderElement.data('center-mode') === true || sliderElement.data('center-mode') === 'true';
    var fade_effect = sliderElement.data('fade') === true || sliderElement.data('fade') === 'true';
    var variableWidth = sliderElement.data('variable-width') === true || sliderElement.data('variable-width') === 'true';
    var prevArrowButton, nextArrowButton;

    if (isPrevSvg) {
      prevArrowButton = '<button type="button" class="slick-prev">' + prevArrowHtml + '</button>';
    } else {
      prevArrowButton = '<button type="button" class="slick-prev"><i class="' + prevArrowHtml + '"></i></button>';
    }

    if (isNextSvg) {
      nextArrowButton = '<button type="button" class="slick-next">' + nextArrowHtml + '</button>';
    } else {
      nextArrowButton = '<button type="button" class="slick-next"><i class="' + nextArrowHtml + '"></i></button>';
    }
    var CustomArrows = sliderElement.data('custom-arrows') === true || sliderElement.data('custom-arrows') === 'true';
    var CustomDots = sliderElement.data('custom-dots') === true || sliderElement.data('custom-dots') === 'true';
    var WrapDotsArrows = sliderElement.data('wrap-dots-arrows') === true || sliderElement.data('wrap-dots-arrows') === 'true';
    var enableProgressBar = sliderElement.data('enable-progress-bar') === true || sliderElement.data('enable-progress-bar') === 'true';
    var asNavForSelector = sliderElement.attr('data-as-nav-for');

    // Fix "centered slides" when total slides are less than slidesToShow.
    // IMPORTANT: Do NOT clamp slidesToShow, otherwise Slick will expand slide widths (e.g. 3 slides become full width).
    // We only:
    // - add a class to remove slick-track auto margins (CSS)
    // - disable centerMode/infinite
    // - clamp slidesToScroll so it never exceeds slide count
    var totalSlidesBeforeInit = sliderElement.find('> .vp-cubewp-gallery-slide').length;
    if (!totalSlidesBeforeInit) {
      totalSlidesBeforeInit = sliderElement.children().length;
    }

    var baseSlidesToShow = vpToPositiveInt(slidesToShow, 1);
    var hasNotEnoughSlides = totalSlidesBeforeInit > 0 && totalSlidesBeforeInit < baseSlidesToShow;
    sliderElement.toggleClass('vp-slick-not-enough', hasNotEnoughSlides);

    var slickOptions = {
      slidesToShow: slidesToShow,
      slidesToScroll: slidesToScroll,
      autoplay: autoplay,
      autoplaySpeed: autoplaySpeed,
      speed: Speed,
      infinite: infinite,
      centerMode: centerMode,
      fade: fade_effect,
      variableWidth: variableWidth,
      prevArrow: prevArrowButton,
      nextArrow: nextArrowButton,
      arrows: CustomArrows,
      dots: CustomDots,
      responsive: [{
        breakpoint: 1025,
        settings: {
          slidesToShow: slidesToShowTablet,
          slidesToScroll: slidesToScrollTablet
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: slidesToShowTabletPortrait,
          slidesToScroll: slidesToScrollTabletPortrait
        }
      },
      {
        breakpoint: 481,
        settings: {
          slidesToShow: slidesToShowMobile,
          slidesToScroll: slidesToScrollMobile
        }
      }
      ]
    };

    if (totalSlidesBeforeInit > 0) {
      var clampScroll = function (n) {
        return Math.min(vpToPositiveInt(n, 1), totalSlidesBeforeInit);
      };

      slickOptions.slidesToScroll = clampScroll(slidesToScroll);
      slickOptions.responsive = slickOptions.responsive.map(function (r) {
        if (!r || !r.settings) return r;
        r.settings.slidesToScroll = clampScroll(r.settings.slidesToScroll);
        return r;
      });

      if (hasNotEnoughSlides) {
        slickOptions.centerMode = false;
        slickOptions.infinite = false;
      }
    }

    if (asNavForSelector) {
      slickOptions.asNavFor = asNavForSelector;
      slickOptions.focusOnSelect = true;
    }

    sliderElement.slick(slickOptions);

    if (enableProgressBar == true) {
      if (!sliderElement.next('.slick-progress').length) {
        sliderElement.after(
          '<div class="slick-progress"><div class="slick-progress-bar"></div></div>'
        );
        var totalSlides = sliderElement.slick("getSlick").slideCount;
        sliderElement.on("afterChange", function (event, slick, currentSlide) {
          var progress = ((currentSlide + 1) / totalSlides) * 100;
          sliderElement.next('.slick-progress').find('.slick-progress-bar').css("width", progress + "%");
        });
      }
    }
    if (WrapDotsArrows == true || WrapDotsArrows == 'true') {
      sliderElement.append('<div class="slick-arrows-wrapper"></div>');
      sliderElement.find(".slick-prev").appendTo(sliderElement.find(".slick-arrows-wrapper"));
      sliderElement.find(".slick-dots").appendTo(sliderElement.find(".slick-arrows-wrapper"));
      sliderElement.find(".slick-next").appendTo(sliderElement.find(".slick-arrows-wrapper"));
    }
  });
  if (clicked == 'clicked') {
    jQuery(document).trigger("vp_cubewp_gallery_slider_initialized", [$scope]);
  }
}
// Initialize for Elementor
(function checkElementor_vp_cubewp_gallery() {
  if (typeof elementorFrontend !== "undefined" && elementorFrontend.hooks) {
    elementorFrontend.hooks.addAction('frontend/element_ready/vp_cubewp_gallery.default', initVPGallerySlider);
  } else {
    setTimeout(checkElementor_vp_cubewp_gallery, 50); // re-check every 50ms
  }
})();

// Initialize on regular page loads (fallback for non-Elementor pages)
jQuery(document).ready(function ($) {
  // Initialize on page load with a small delay to ensure DOM is fully ready
  setTimeout(function () {
    initVPGallerySlider($(document));
  }, 100);

  // Initialize on AJAX content updates
  $(document).on('ajaxComplete', function () {
    setTimeout(function () {
      initVPGallerySlider($(document));
    }, 100);
  });

  // Listen for custom event (if triggered elsewhere)
  $(document).on('vp_cubewp_gallery_slider_initialized', function (e, $scope) {
    if ($scope && $scope.length) {
      initVPGallerySlider($scope);
    }
  });

  jQuery(document).on("click", ".vpack-nested-tabs  .e-n-tab-title", function () {
    var $dataid = jQuery(this).attr('aria-controls');
    var $sliderContainer = jQuery(this).closest('.elementor-element').find('#' + $dataid);
    var clicked = 'clicked';
    initVPGallerySlider($sliderContainer, clicked);
    jQuery(this).addClass('init-clicked');
  });


  // Also initialize when window is fully loaded (for lazy-loaded content)
  $(window).on('load', function () {
    setTimeout(function () {
      initVPGallerySlider($(document));
    }, 50);
  });
});

/* CubeWP Widget */
(function ($) {
  $(document).ready(function () {
    // CubeWP Meta Widget
    $(document).on('click', '.vp-popup-trigger', function (e) {
      e.preventDefault();
      var popupId = $('.vp-popup-modal').attr('id');
      var $popup = $('#' + popupId);
      // Move the popup with this ID directly into the body (not append text 'body')
      if ($popup.length) {
        $popup.appendTo('body');
        $popup.fadeIn();
        $popup.addClass('vp-popup-modal-open');
        $('body').css('overflow', 'hidden');
      }
    });

    $(document).on('click', '.vp-popup-close, .vp-popup-overlay', function () {
      $(this).closest('.vp-popup-modal').removeClass('vp-popup-modal-open').fadeOut();
      $('body').css('overflow', '');
      var src = $('.vp-popup-modal iframe').attr('src');
      $('.vp-popup-modal iframe').attr('src', src);
    });

    // Close on ESC key
    $(document).on('keydown', function (e) {
      if (e.key === 'Escape' || e.keyCode === 27) {
        $('.vp-popup-modal').fadeOut().removeClass('vp-popup-modal-open').fadeOut();
        $('body').css('overflow', '');
        var src = $('.vp-popup-modal iframe').attr('src');
        $('.vp-popup-modal iframe').attr('src', src);
      }
    });

    // business hours 
    $(document).on("click", ".cubewp-business-hours-widget.active-toggle .hours-toggle", function () {
      var $btn = $(this);
      var $widget = $btn.closest('.cubewp-business-hours-widget');
      var $days = $widget.find('.cubewp-business-hours-days');
      var openText = $btn.data('open');
      var closeText = $btn.data('close');
      if ($btn.hasClass('is-closed')) {
        $days.slideDown();
        $btn.removeClass('is-closed').find('.hours-toggle-text').text(closeText);
        $widget.removeClass('is-closed');
      } else {
        $days.slideUp();
        $btn.addClass('is-closed').find('.hours-toggle-text').text(openText);
        $widget.addClass('is-closed');
      }
    });
  });

  /* Section Visibility - Value Pack */
  /* Section Visibility - Value Pack */
  var allowedTags = [
    'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'a', 'ul', 'ol', 'li',
    'img', 'table', 'thead', 'tbody', 'tr', 'td', 'th', 'form', 'input', 'button',
    'label', 'select', 'option', 'textarea', 'header', 'footer', 'nav', 'main',
    'article', 'section', 'aside', 'figure', 'figcaption', 'blockquote', 'pre',
    'code', 'strong', 'em', 'b', 'i', 'u', 'br', 'iframe', 'video', 'audio', 'source',
    'dl', 'dt', 'dd', 'address', 'time'
  ];

  function escapeClass(name) {
    if (!name || typeof name !== 'string') return '';
    var s = name.trim().split(/\s+/)[0];
    return s ? s.replace(/[^a-zA-Z0-9_\-.]/g, '') : '';
  }

  function getMultiClassSelector(value) {
    if (!value || typeof value !== 'string') return null;
    var selectors = value
      .split(',')
      .map(function (item) {
        var cls = escapeClass(item);
        return cls ? '.' + cls : null;
      })
      .filter(Boolean);
    return selectors.length ? selectors.join(',') : null;
  }

  function getSelector(type, value) {
    if (type === 'class') {
      var cls = escapeClass(value);
      return cls ? '.' + cls : null;
    }
    if (type === 'multi_class') {
      return getMultiClassSelector(value);
    }
    var tag = (value || 'p').toLowerCase();
    if (allowedTags.indexOf(tag) === -1) return null;
    return tag;
  }

  function queryInElement(container, type, value) {
    var selector = getSelector(type, value);
    if (!selector) return null;
    if (type === 'class') {
      return container.querySelector(selector);
    }
    return container.querySelector(selector);
  }

  function runSectionVisibility() {
    var sections = document.querySelectorAll('[data-vp-section-visibility="1"]');
    if (!sections.length) return;
    sections.forEach(function (section) {
      var type = section.getAttribute('data-vp-section-visibility-type') || 'tag';
      var value = section.getAttribute('data-vp-section-visibility-value') || '';
      var selector = getSelector(type, value);
      if (!selector) return;
      var found;
      found = section.querySelector(selector);
      if (!found) {
        if (section.parentNode) {
          section.parentNode.removeChild(section);
        }
      }
    });
  }
  // Helper for simple re-run
  function handleSectionVisibilityTrigger() {
    setTimeout(runSectionVisibility, 0);
  }
  // DOMContentLoaded or direct call
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runSectionVisibility);
  } else {
    runSectionVisibility();
  }
  // Elementor integration
  if (typeof window.elementorFrontend !== 'undefined' && window.elementorFrontend.on) {
    window.elementorFrontend.on('init', function () {
      setTimeout(runSectionVisibility, 100);
    });
  }
  // Listen for Cubewp search results loaded event
  jQuery(document.body).on('cubewp_search_results_loaded', function () {
    handleSectionVisibilityTrigger();
  });


  jQuery('.vp-gallery-component-viewer').componentViewer({
    toolbar: {
      download: true,
      zoom: true
    },
  });


  // value pack post reactions
  $(document).ready(function () { 
    initPostReactions();
  });

  /**
   * Initialize Post Reactions
   */
  function initPostReactions() {
    $(document).on('click', '.cwp-reaction-button:not(.cwp-reaction-disabled):not(:disabled)', function (e) {
      e.preventDefault();
      e.stopPropagation();

      var $button = $(this);
      var $item = $button.closest('.cwp-post-reaction-item');
      var postId = $button.data('post-id');
      var reactionType = $button.data('reaction-type');
      var $container = $button.closest('.cwp-post-reactions-container');

      // Check if already reacted with this specific reaction type
      if ($item.hasClass('cwp-reaction-active') || $button.hasClass('cwp-reaction-disabled') || $button.prop('disabled')) {
        // Only show tooltip if already reacted
        if ($item.hasClass('cwp-reaction-active')) {
          showTooltip($item);
        }
        return false;
      }

      // Add loading state
      $button.addClass('loader');
      $button.prop('disabled', true);

      // AJAX request
      $.ajax({
        url: value_pack_ajax_params.ajax_url,
        type: 'POST',
        data: {
          action: 'cwp_post_add_reaction',
          nonce: value_pack_ajax_params.nonce,
          post_id: postId,
          reaction_type: reactionType
        },
        success: function (response) {
          if (response.success) {
            // Update UI
            $item.addClass('cwp-reaction-active');
            $button.addClass('reacted cwp-reaction-disabled');
            $button.prop('disabled', true);
            $button.attr('aria-disabled', 'true');

            // Update count
            var $count = $button.find('.cwp-reaction-count');
            if ($count.length) {
              var currentCount = parseInt($count.text()) || 0;
              $count.text(currentCount + 1);
            } else if ($button.find('.cwp-reaction-count').length === 0) {
              // Add count if it doesn't exist but should be shown
              $button.append('<span class="cwp-reaction-count">1</span>');
            }

            // Update all reactions data
            if (response.data && response.data.reactions) {
              updateReactionCounts($container, response.data.reactions);
            }
          } else {
            // Handle error
            if (response.data && (response.data.reacted_type || response.data.reacted_types)) {
              // User already reacted with this type
              $item.addClass('cwp-reaction-active');
              $button.addClass('cwp-reaction-disabled');
              $button.prop('disabled', true);
              $button.attr('aria-disabled', 'true');
              showTooltip($item);
            } else {
              alert(response.data && response.data.message ? response.data.message : 'An error occurred.');
            }
          }
        },
        error: function () {
          alert('An error occurred while adding your reaction.');
        },
        complete: function () {
          $button.removeClass('loader');
          // Don't re-enable if already reacted
          if (!$item.hasClass('cwp-reaction-active')) {
            $button.prop('disabled', false);
          }
        }
      });
    });

    // Show tooltip on hover for already reacted items
    $(document).on('mouseenter', '.cwp-post-reaction-item.cwp-reaction-active', function () {
      var $item = $(this);
      $item.data('hover-tooltip', true);
      showTooltip($item);
    });

    // Hide tooltip on mouse leave for already reacted items
    $(document).on('mouseleave', '.cwp-post-reaction-item.cwp-reaction-active', function () {
      var $item = $(this);
      $item.removeData('hover-tooltip');
      hideTooltip($item);
    });

    // Load initial reaction states
    $('.cwp-post-reactions-container').each(function () {
      var $container = $(this);
      var postId = $container.data('post-id');

      if (postId) {
        loadReactionStates($container, postId);
      }
    });
  }

  /**
   * Load reaction states from server
   */
  function loadReactionStates($container, postId) {
    $.ajax({
      url: value_pack_ajax_params.ajax_url,
      type: 'POST',
      data: {
        action: 'cwp_post_get_reactions',
        post_id: postId
      },
      success: function (response) {
        if (response.success && response.data) {
          // Mark reacted items (handle both old single type and new array)
          var reactedTypes = response.data.reacted_types || [];
          if (response.data.reacted_type && !Array.isArray(reactedTypes)) {
            // Backward compatibility with old single type
            reactedTypes = [response.data.reacted_type];
          }

          if (Array.isArray(reactedTypes) && reactedTypes.length > 0) {
            $.each(reactedTypes, function (index, reactionType) {
              var $item = $container.find('.cwp-post-reaction-item[data-reaction-type="' + reactionType + '"]');
              var $button = $item.find('.cwp-reaction-button');

              $item.addClass('cwp-reaction-active');
              $button.addClass('reacted cwp-reaction-disabled')
                .prop('disabled', true)
                .attr('aria-disabled', 'true');
            });
          }

          // Update counts
          if (response.data.reactions) {
            updateReactionCounts($container, response.data.reactions);
          }
        }
      }
    });
  }

  /**
   * Update reaction counts
   */
  function updateReactionCounts($container, reactions) {
    $.each(reactions, function (reactionType, count) {
      var $item = $container.find('.cwp-post-reaction-item[data-reaction-type="' + reactionType + '"]');
      var $count = $item.find('.cwp-reaction-count');

      if ($count.length) {
        $count.text(count);
      } else {
        // Add count if it doesn't exist
        $item.find('.cwp-reaction-button').append('<span class="cwp-reaction-count">' + count + '</span>');
      }
    });
  }

  /**
   * Show tooltip
   */
  function showTooltip($item) {
    // Get tooltip text from data attribute (try both methods)
    var tooltip = $item.attr('data-reaction-tooltip') || $item.data('reaction-tooltip') || '';

    // If still empty, try to get from the button inside
    if (!tooltip || tooltip === '') {
      var $button = $item.find('.cwp-reaction-button');
      tooltip = $button.attr('data-reaction-tooltip') || $button.data('reaction-tooltip') || '';
    }

    if (!tooltip || tooltip === '') {
      // Default tooltip if none provided
      tooltip = 'You have already reacted with this reaction type.';
    }

    // Remove existing tooltip and clear any existing timeout
    if ($item.data('tooltip-timeout')) {
      clearTimeout($item.data('tooltip-timeout'));
      $item.removeData('tooltip-timeout');
    }

    $item.find('.cwp-reaction-tooltip').remove();
    $item.removeClass('show-tooltip');

    // Create tooltip element (escape HTML to prevent XSS)
    var $tooltip = $('<div class="cwp-reaction-tooltip"></div>').text(tooltip);

    // Append to item (which has position: relative)
    $item.append($tooltip);

    // Small delay to ensure DOM is updated
    setTimeout(function () {
      // Add show class to trigger CSS animation
      $item.addClass('show-tooltip');
    }, 10);

    // Only auto-hide tooltip after 3 seconds if it was triggered by click (not hover)
    // For hover, tooltip will be hidden on mouseleave
    if (!$item.data('hover-tooltip')) {
      var timeout = setTimeout(function () {
        if (!$item.is(':hover')) {
          hideTooltip($item);
        }
      }, 3000);
      $item.data('tooltip-timeout', timeout);
    }
  }

  /**
   * Hide tooltip
   */
  function hideTooltip($item) {
    // Clear any existing timeout
    if ($item.data('tooltip-timeout')) {
      clearTimeout($item.data('tooltip-timeout'));
      $item.removeData('tooltip-timeout');
    }

    $item.removeClass('show-tooltip');
    setTimeout(function () {
      $item.find('.cwp-reaction-tooltip').remove();
    }, 300);
  }

  // Handle dynamic content (for AJAX loaded posts)
  $(document).on('valuepack_posts_loaded', function () {
    initPostReactions();
  });



})(jQuery);

