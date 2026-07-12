(function ($) {
  "use strict";

  jQuery('form#login-form .form-control, form#cwp-from-subscriber .form-control, form.woocommerce-checkout .woocommerce-billing-fields__field-wrapper p span input').each(function () {
    const $fieldContainer = jQuery(this).closest('.cwp-field-container, .form-row');

    if (jQuery(this).val().trim() !== '') {
      $fieldContainer.addClass('value-filled');
    } else {
      $fieldContainer.removeClass('value-filled');
    }
  });

  jQuery('form#login-form .form-control, form#cwp-from-subscriber .form-control, form.woocommerce-checkout .woocommerce-billing-fields__field-wrapper p span input').on('focus', function () {
    const $fieldContainer = jQuery(this).closest('.cwp-field-container, .form-row');
    $fieldContainer.addClass('active value-filled');
  });

  jQuery('form#login-form .form-control, form#cwp-from-subscriber .form-control, form.woocommerce-checkout .woocommerce-billing-fields__field-wrapper p span input').on('blur', function () {
    const $fieldContainer = jQuery(this).closest('.cwp-field-container, .form-row');
    $fieldContainer.removeClass('active');

    if (jQuery(this).val().trim() !== '') {
      $fieldContainer.addClass('value-filled');
    } else {
      $fieldContainer.removeClass('value-filled');
    }
  });

  $(window).on("load", function () {
    $(".loadingspinner-main-cotent").remove();
    if (jQuery('.woomen-replace-colls1 .col-lg-3').length > 0) {
      jQuery('.woomen-replace-colls1 .col-lg-3').each(function () {
        jQuery(this).removeClass('col-lg-3').addClass('col-lg-12');
      });
    }
    if (jQuery('.woomen-replace-colls2 .col-lg-3').length > 0) {
      jQuery('.woomen-replace-colls2 .col-lg-3').each(function () {
        jQuery(this).removeClass('col-lg-3').addClass('col-lg-6');
      });
    }
    if (jQuery('.woomen-replace-colls3 .col-lg-3').length > 0) {
      jQuery('.woomen-replace-colls3 .col-lg-3').each(function () {
        jQuery(this).removeClass('col-lg-3').addClass('col-lg-4');
      });
    }
    if (jQuery('.woomen-replace-colls5 .col-lg-3').length > 0) {
      jQuery('.woomen-replace-colls5 .col-lg-3').each(function () {
        jQuery(this).removeClass('col-lg-3').addClass('col-lg-12');
      });
    }
  });

  jQuery(document).ready(function ($) {

    var woomen_content_section = $(".woomen-hide-no-content-section");

    $(document).on(
      "click",
      ".woomen-shop-loop-filters-parent-style1 .cwp-search-field>label",
      function (event) {
        event.preventDefault();
        $(this).next("div").slideToggle();
        $(this).next("input").slideToggle();
        $(this).next("ul").slideToggle();
        $(this).toggleClass("active");
      }
    );


    if (woomen_content_section.length > 0) {
      woomen_content_section.each(function () {
        if ($(this).find(".woomen-section-have-content").length > 0) {
          $(this).show().find(".woomen-section-have-content").remove();
        }
      });
    }

    function init_tooltips() {
      var tooltip_trigger_list = [].slice.call(
        document.querySelectorAll('[data-woomen-tooltip="true"]')
      );
      tooltip_trigger_list.map(function (tooltip_trigger_element) {
        return new bootstrap.Tooltip(tooltip_trigger_element, {
          template: '<div class="tooltip woomen-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        });
      });
    }

    $(document.body).on("woomen_more_items_loaded", function () {
      init_tooltips(); // Initializing the tooltips
    });

    $(document.body).on("cubewp_search_results_loaded", function () {
      init_tooltips(); // Initializing the tooltips
    });

    init_tooltips(); // Initializing the tooltips

   
   

    $(document).on("click", ".wc-woomen-size-guide-link2", function (event) {
      var $slider = $('.wc-women-style2-checkout');
      if ($slider.length) {
        $slider.animate({
          scrollTop: $slider.prop("scrollHeight")
        }, 500);
      }
    });

    $(document).on("click", ".wc-woomen-size-guide-link", function (event) {
      event.preventDefault();
      var $slider = $('.wc-women-style2-checkout');
      if ($slider.length && $slider.hasClass('slick-initialized')) {
        var lastSlideIndex = $slider.slick('getSlick').slideCount - 1;
        $slider.slick('slickGoTo', lastSlideIndex);
      }
    });
    $(document).on("click", ".style1 .wc-woomen-size-guide-link2", function (event) {
      event.preventDefault();
      var $slider = $('.product-gallery');
      if ($slider.length && $slider.hasClass('slick-initialized')) {
        var lastSlideIndex = $slider.slick('getSlick').slideCount - 1;
        $slider.slick('slickGoTo', lastSlideIndex);
      }
    });

    function runRemoveEmptyChecks() {
      jQuery(".wc-women-remove-empty").each(function () {
        var get_TEXT = jQuery(this).text();
        if (get_TEXT == "" || get_TEXT == null) {
          jQuery(this).remove();
        }
      })

      jQuery(".wc-women-remove-empty-s").each(function () {
        var getText = jQuery(this).text();
        if (/\d/.test(getText)) {
          // has number → keep
        } else {
          jQuery(this).remove();
        }
      });
    }

    // Run once on DOM ready
    jQuery(document).ready(function ($) {
      runRemoveEmptyChecks();
    });

    // Run again when cubewp_posts_loaded fires
    jQuery(document).on("cubewp_posts_loaded", function () {
      runRemoveEmptyChecks();
    });

    jQuery(
      ".woomen-shop-loop-filters-parent-style1.filter-style2 .cwp-search-field .cwp-field-checkbox-container"
    ).each(function () {
      var wrapperDiv = $('<div class="cwp-field-checkbox-inner"></div>');
      $(this).wrapInner(wrapperDiv);
    });
    jQuery(
      ".woomen-shop-loop-filters-parent-style1.archive-style4 .woomen-shop-loop-filters-top-content button"
    ).on("click", function () {
      jQuery(
        ".woomen-shop-loop-filters-parent-style1.archive-style4 .woomen-shop-loop-filters-parent-box"
      ).slideToggle();
    });

    jQuery(".woomen-posts-container-view .grid-view").on("click", function () {
      jQuery(this)
        .closest(".woomen-shop-loop-parent")
        .find(".products.columns-4")
        .removeClass("list-view");
      jQuery(this)
        .closest(".woomen-shop-loop-parent")
        .find(".products.columns-4")
        .addClass("grid-view");
    });
    jQuery(".woomen-posts-container-view .list-view").on("click", function () {
      jQuery(this)
        .closest(".woomen-shop-loop-parent")
        .find(".products.columns-4")
        .removeClass("grid-view");
      jQuery(this)
        .closest(".woomen-shop-loop-parent")
        .find(".products.columns-4")
        .addClass("list-view");
    });


    if ($(".woomen-shop-this-look-slider-4").length > 0) {
      $(".woomen-shop-this-look-slider-4").find('.cwp-post-hidden-id').remove();
      jQuery(".woomen-shop-this-look-slider-4").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
        prevArrow: '<i class="fa-solid fa-chevron-left wc-grid17-prev-action"></i>',
        nextArrow: '<i class="fa-solid fa-chevron-right wc-grid17-gallery-next-action"></i>',
      });
    }
    jQuery(document).on("cubewp_posts_loaded", function () {
      setTimeout(function () {
        jQuery(".woomen-shop-this-look-slider-4").each(function () {

          var $slider = jQuery(this);
          $slider.find('.cwp-post-hidden-id').remove();
          if ($slider.hasClass("slick-initialized") && !$slider.closest(".slick-cloned").length) {
            $slider.slick("unslick");
          }
          setTimeout(function () {
            if (!$slider.hasClass("slick-initialized")) {
              $slider.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows: true,
                autoplay: true,
                variableWidth: false,
                autoplaySpeed: 2000,
                infinite: false,
                prevArrow: '<i class="fa-solid fa-chevron-left wc-grid17-prev-action"></i>',
                nextArrow: '<i class="fa-solid fa-chevron-right wc-grid17-gallery-next-action"></i>',
              });
            }
          }, 500);
        });
      }, 400);
    });
    
    $("form.woocommerce-ordering").on("submit", function (e) {
      e.preventDefault();
    });

    function checkPrefilledFields() {
      $(".woocommerce-billing-fields__field-wrapper .form-row input")
        .not("#billing_country_field input, #billing_state_field input")
        .each(function () {
          if ($(this).val() !== "") {
            $(this).closest(".form-row").find("label").addClass("active-label");
          }
        });

      $(".woocommerce-billing-fields__field-wrapper .form-row select")
        .not("#billing_country_field select, #billing_state_field select")
        .each(function () {
          if ($(this).val() !== "") {
            $(this).closest(".form-row").find("label").addClass("active-label");
          }
        });
    }
    checkPrefilledFields();
    $(".woocommerce-billing-fields__field-wrapper .form-row input")
      .not("#billing_country_field input, #billing_state_field input")
      .on("focus", function () {
        $(this).closest(".form-row").find("label").addClass("active-label");
      });
    $(".woocommerce-billing-fields__field-wrapper .form-row input")
      .not("#billing_country_field input, #billing_state_field input")
      .on("blur", function () {
        if ($(this).val() === "") {
          $(this)
            .closest(".form-row")
            .find("label")
            .removeClass("active-label");
        }
      });
    $(".woocommerce-billing-fields__field-wrapper .form-row select")
      .not("#billing_country_field select, #billing_state_field select")
      .on("focus", function () {
        $(this).closest(".form-row").find("label").addClass("active-label");
      });
    $(".woocommerce-billing-fields__field-wrapper .form-row select")
      .not("#billing_country_field select, #billing_state_field select")
      .on("blur", function () {
        if ($(this).val() === "") {
          $(this)
            .closest(".form-row")
            .find("label")
            .removeClass("active-label");
        }
      });


    $(
        "#respond form .comment-form-author #author, #respond form .comment-form-email #email"
      )
      .on("focus", function () {
        $(this).prev("label").addClass("active-label");
      })
      .on("blur", function () {
        if ($(this).val() !== "") {
          $(this).prev("label").addClass("active-label");
        } else {
          $(this).prev("label").removeClass("active-label");
        }
      });

    $(document).on(
      "click",
      ".wc-shop-results-views ul .wc-shop-results-views",
      function () {
        $(".wc-shop-results-views ul .wc-shop-results-views").removeClass(
          "active"
        );
        $(this).addClass("active");
        var get_numbers = $(this).data("views");
        $("ul.products").attr("class", "products");
        $("ul.products").addClass("columns-" + get_numbers);
      }
    );

    function checkWidth() {
      if ($(window).width() <= 1200) {
        $(".woomen-shop-loop-filters-parent-style1.shop-classic").addClass(
          "hidefilters"
        );
      } else {
        $(".woomen-shop-loop-filters-parent-style1.shop-classic").removeClass(
          "hidefilters"
        );
      }
    }
    checkWidth();
    $(window).on('resize', function () {
      checkWidth();
    });

    // Add event listener for slick-section before initializing slick sliders
    $('.slick-section').on('init afterChange', function (event, slick, currentSlide) {
      jQuery('.slick-slide').removeClass('prev-slide next-slide');
      let jQuerycenterSlide = jQuery('.slick-center');
      jQuerycenterSlide.prev().addClass('prev-slide');
      jQuerycenterSlide.next().addClass('next-slide');
    });

    // Women Bridge Fashion Slider
    if ($(".slick-section>.e-con-inner").length > 0) {
      jQuery(".slick-section>.e-con-inner").slick({
        arrows: true,
        slidesToShow: 5,
        centerMode: true,
        centerPadding: "0px",
        slidesToScroll: 1,
        asNavFor: ".content-part>.e-con-inner",
        prevArrow: '<svg width="100" height="100" class="left-arrow" viewBox="0 0 35 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M35 17H2.02708" stroke="black"/><path d="M8.96777 24.8097C7.02886 20.5424 5.49277 18.751 1.66569 17.012C5.57819 15.0863 7.09686 13.2883 8.96777 9.19092" stroke="black"/></svg>',
        nextArrow: '<svg width="100" height="100" class="right-arrow" viewBox="0 0 35 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 17H32.9729" stroke="black"/><path d="M26.0322 24.8097C27.9711 20.5424 29.5072 18.751 33.3343 17.012C29.4218 15.0863 27.9031 13.2883 26.0322 9.19092" stroke="black"/></svg>',
        responsive: [{
          breakpoint: 767,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 3000,
          },
        }, ],
      });
    }

    if ($(".content-part>.e-con-inner").length > 0) {
      jQuery(".content-part>.e-con-inner").slick({
        arrow: false,
        slidesToShow: 1,
        centerMode: true,
        centerPadding: "0px",
        slidesToScroll: 1,
        asNavFor: ".slick-section .e-con-inner",
        prevArrow: false,
        nextArrow: false,
        centerMode: true,
        responsive: [{
          breakpoint: 767,
          settings: {
            dots: true,
            autoplay: true,
            autoplaySpeed: 3000,
          },
        }, ],
      });
    }
    $(document).on("click", ".woo-style-18-arrow", function (e) {
      $(this).closest('.woo-style-18-main').addClass('active');
    });

    $(document).on("click", ".woo-style-18-hover-content svg", function (e) {
      $(this).closest('.woo-style-18-main').removeClass('active');
    });

  });

  if ($('#woomen-blogs .woomen-blogs-categories ul li').length > 7) {
    $('#woomen-blogs .woomen-blogs-categories ul').slick({
      infinite: true,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      variableWidth: true,
      swipeToSlide: true,
      touchThreshold: 10,
      prevArrow: '<button class="blog-prev-icon"><i class="fa fa-chevron-left"></i></button>',
      nextArrow: '<button class="blog-next-icon"><i class="fa fa-chevron-right"></i></button>',
    });
  }

  setTimeout(function () {
    if ($('.wm-size-guide-after-attributes').length > 0 && $('.wm-product-attributes').length > 0) {
      $('.wm-size-guide-after-attributes').appendTo('.wm-product-attributes');
    }
    if ($('.wm-size-guide-with-size-attributes').length > 0 && $('.wm-product-attributes .attribute-container .attribute-heading.attribute-heading-size').length > 0) {
      $('.wm-size-guide-with-size-attributes').appendTo('.wm-product-attributes .attribute-container .attribute-heading.attribute-heading-size');
    }
    if ($('.wm-size-guide-with-color-attributes').length > 0 && $('.wm-product-attributes .attribute-container .attribute-heading.attribute-heading-color').length > 0) {
      $('.wm-size-guide-with-color-attributes').appendTo('.wm-product-attributes .attribute-container .attribute-heading.attribute-heading-color');
    }
  }, 200);


  jQuery(document).ready(function ($) {
    if ($(".aboutUs-history-slider-content").length > 0) {
      $('.aboutUs-history-slider-content').slick({
        slidesToShow: 1.5,
        slidesToScroll: 1,
        speed: 1000,
        infinite: false,
        arrows: false,
        dots: false,
        variableWidth: true,
        responsive: [{
          breakpoint: 768,
          settings: {
            slidesToShow: 1
          }
        }]
      });
    }

    if ($(".aboutUs-history-slider-numbers").length > 0) {
      $('.aboutUs-history-slider-numbers').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        speed: 1000,
        infinite: false,
        arrows: false,
        dots: false,
      });
    }

    $('.aboutUs-history-slider-numbers .slick-slide').on('click', function () {
      var index = $(this).index();
      $('.aboutUs-history-slider-numbers .slick-slide').removeClass('active');
      $(this).addClass('active');
      $('.aboutUs-history-slider-content').slick('slickGoTo', index);
    });

    $('.aboutUs-history-slider-content').on('afterChange', function (event, slick, currentSlide) {
      $('.aboutUs-history-slider-numbers .slick-slide').removeClass('active');
      $('.aboutUs-history-slider-numbers .slick-slide').eq(currentSlide).addClass('active');
    });

    $('.aboutUs-history-slider-numbers .slick-slide:first-child').addClass('active');
  });

  jQuery(document).ready(function ($) {
    setTimeout(function () {
      $(".location-details-btn").each(function (index) {
        $(this).attr("id", "post-btn-" + index);
      });
    }, 1500);

    $(document).on("click", ".location-details-btn", function () {
      let btnID = $(this).attr("id");
      let scrollPos = $(window).scrollTop();
      $(this).data("scroll-position", scrollPos);

      $(this).closest(".woocomerce-location-details")
        .find(".woocomerce-location-details-sidebar")
        .addClass("active")
        .attr("data-btn-id", btnID);
      $("html, body").animate({
        scrollTop: 0
      }, "fast");
    });

    $(document).on("click", ".woocomerce-location-back-btn", function () {
      let sidebar = $(this).closest(".woocomerce-location-details-sidebar");
      sidebar.removeClass("active");
      let btnID = sidebar.attr("data-btn-id");
      if (btnID) {
        let scrollPos = $("#" + btnID).data("scroll-position");
        if (scrollPos !== undefined) {
          $("html, body").animate({
            scrollTop: scrollPos
          }, "fast");
        }
      }
    });
  });

  /*Checkout Style 2*/
  $(document.body).on('updated_checkout', function () {
    $(".woomen-checkout-delivery-options").html($(".get-delevery-options-ajax").html());
    $(".get-delevery-options-ajax").empty();
    $('.woomen-checkout-store-locators').slideUp(300);
    let shipping = $('.woomen-checkout-delivery-options-wrape .shipping-method input:checked').val();
    if ($('input[value="' + shipping + '"]').closest('li').length > 0) {
      if ($('input[value="' + shipping + '"]').closest('li').hasClass('yes')) {
        $('.woomen-checkout-store-locators').slideDown(300);
      } else {
        $('.woomen-checkout-store-locators').slideUp(300);
      }
    } else {
      $('.woomen-checkout-store-locators').slideUp(300);
    }
    $('.cart-shipping-method[data-ship-available]').hide();
    $('.cart-shipping-method[data-ship-available="' + shipping + '"]').show();
  });

  $(document).on('click', '.add-related-product', function () {
    var product_id = $(this).data('product-id');
    var variation_id = $(this).data('variation-id');
    var $button = $(this);
    $button.append('<i class="fa-solid fa-circle-notch fa-spin"></i>');
    $.ajax({
      type: 'POST',
      url: woocommerce_params.ajax_url,
      data: {
        action: 'add_related_product',
        product_id: product_id,
        variation_id: variation_id
      },
      success: function (response) {
        if (response.success) {
          $('body').trigger('update_checkout');
          $button.find('i.fa-solid').remove();
        } else {
          alert(response.data.message);
          $button.find('i.fa-solid').remove();
        }
      }
    });
  });

  $(document.body).on('checkout_error', function () {
    var WoonoticeGroup = $('.woomen-checkout-page-container.style-2 form.checkout.woocommerce-checkout > .woocommerce-NoticeGroup');
    var checkoutContentContainer = $('.woomen-checkout-fields-content');
    if (WoonoticeGroup.length && checkoutContentContainer.length) {
      checkoutContentContainer.prepend(WoonoticeGroup);
    }
    var WoonoticeGroups = jQuery('.woocommerce-notices-wrapper');
    if (WoonoticeGroups.length > 0) {
      var checkoutContentContainer = jQuery('.woomen-checkout-fields-content');
      if (WoonoticeGroups.length && checkoutContentContainer.length) {
        checkoutContentContainer.prepend(WoonoticeGroups);
      }
    }
  });

  setTimeout(function () {
    if (jQuery('body').hasClass('single-product')) {
      jQuery('html , body').css('overflow', 'initial');
    }
    var WoonoticeGroups = jQuery('.woocommerce-notices-wrapper');
    if (WoonoticeGroups.length > 0) {
      var checkoutContentContainer = jQuery('.woomen-checkout-fields-content');
      if (WoonoticeGroups.length && checkoutContentContainer.length) {
        checkoutContentContainer.prepend(WoonoticeGroups);
      }
    }
  }, 300);


  jQuery(document).on('click', '.cubewp-mega-menu-item-dropdown', function () {
    const $this = jQuery(this);
    $this.css({
      'transform': 'translateY(20px)',
      'opacity': '0',
      'height': '0 !important',
      'visibility': 'hidden'
    });

    setTimeout(function () {
      $this.css({
        'transform': '',
        'opacity': '',
        'height': '',
        'visibility': ''
      });
    }, 2000);
  });

  var $heading = $('.woo-kitchen-copy .elementor-heading-title');
  var $button = $('.woo-kitchencopy-code .elementor-button');
  if ($heading.length && $button.length) {
    var $wrapper = $('<div>').css('position', 'relative');
    $button.parent().prepend($wrapper);
    $wrapper.append($button);
    var $message = $('<div>').addClass('kitchen-dining-copy-message');
    $wrapper.append($message);
    $button.on('click', function () {
      var textToCopy = $.trim($heading.text());
      navigator.clipboard.writeText(textToCopy).then(function () {
        $message.text('Copied: ' + textToCopy).addClass('show');
        setTimeout(function () {
          $message.removeClass('show').text('');
        }, 2500);
      }).catch(function () {
        $message.text('Copy failed').addClass('show');
        setTimeout(function () {
          $message.removeClass('show').text('');
        }, 2500);
      });
    });
  }

  $(document).on('click', '.friday-wear-card-1-size ul li', function () {
    $(this).closest('.friday-wear-card-1-cart-btn').addClass('active');
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('.friday-wear-card-1-cart-btn, .friday-wear-card-1-size ul li').length) {
      $('.friday-wear-card-1-cart-btn').removeClass('active');
    }
  });

  $(document).on('click', '.maternity-cart-icon', function (e) {
    e.stopPropagation();
    var $parentCart = $(this).closest('.maternity-card-cart');
    $parentCart.find('.maternity-quick-checout').show();
    $parentCart.find('.maternity-cart-icon-main').hide();
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('.maternity-card-cart').length) {
      $('.maternity-quick-checout').hide();
      $('.maternity-cart-icon-main').show();
    }
  });

  jQuery('#vp-contact-form .cwp-field-container input, #vp-contact-form .cwp-field-container textarea, #vp-contact-form .cwp-field-container select').each(function () {
    if (jQuery(this).val()) {
      jQuery(this).closest('.cwp-field-container').addClass('focus');
    }
  });
  jQuery('#vp-contact-form .cwp-field-container input, #vp-contact-form .cwp-field-container textarea, #vp-contact-form .cwp-field-container select')
    .on('focus', function () {
      jQuery(this).closest('.cwp-field-container').addClass('focus');
    })
    .on('blur', function () {
      const value = jQuery(this).val();
      jQuery(this).closest('.cwp-field-container').toggleClass('focus', !!value);
    });

  // remove post slider in elementor editing mode 
  function removePostSlider_loop($scope) {
    if (elementorFrontend.isEditMode()) {
      var sliders = $scope.find('.woomen-shop-this-look-slider-4');
      if (!sliders.length) return;
      jQuery(".woomen-shop-this-look-slider-4").addClass("elementor-editing-mode");
    }
  }

  function initcontainer_section_slider($scope) {
    // If slick already initialized, unslick first
    if ($scope.find(".slick-section-wrapper").hasClass("slick-initialized")) {
      $scope.find(".slick-section-wrapper").slick("unslick");
    }
    if ($scope.find(".content-part-wrapper").hasClass("slick-initialized")) {
      $scope.find(".content-part-wrapper").slick("unslick");
    }

    // Wrap only if wrapper not already added
    if ($scope.find('.slick-section > .e-con-inner').length > 0 && !$scope.find('.slick-section-wrapper').length) {
      $scope.find('.slick-section > .e-con-inner')
        .children('.elementor-element')
        .wrapAll('<div class="slick-section-wrapper"></div>');
    }

    if ($scope.find('.content-part > .e-con-inner').length > 0 && !$scope.find('.content-part-wrapper').length) {
      $scope.find('.content-part > .e-con-inner')
        .children('.elementor-element')
        .wrapAll('<div class="content-part-wrapper"></div>');
    }

    // Event: Highlight prev/next slides
    $scope.find('.slick-section').off('init afterChange').on('init afterChange', function () {
      jQuery('.slick-slide').removeClass('prev-slide next-slide');
      let jQuerycenterSlide = jQuery('.slick-center');
      jQuerycenterSlide.prev().addClass('prev-slide');
      jQuerycenterSlide.next().addClass('next-slide');
    });

    // Women Bridge Fashion Slider
    if ($scope.find(".slick-section-wrapper").length > 0) {
      $scope.find(".slick-section-wrapper").slick({
        arrows: false,
        slidesToShow: 5,
        centerMode: true,
        centerPadding: "0px",
        autoplay: true,
        autoplaySpeed: 2000,
        slidesToScroll: 1,
        asNavFor: ".content-part-wrapper",
        responsive: [{
          breakpoint: 767,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 3000,
          },
        }],
      });
    }

    // Content part slider
    if ($scope.find(".content-part-wrapper").length > 0) {
      $scope.find(".content-part-wrapper").slick({
        arrow: false,
        slidesToShow: 1,
        centerMode: true,
        centerPadding: "0px",
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        asNavFor: ".slick-section-wrapper",
        prevArrow: false,
        nextArrow: false,
        responsive: [{
          breakpoint: 767,
          settings: {
            dots: true,
            autoplay: true,
            autoplaySpeed: 3000,
          },
        }],
      });
    }
  }

  jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/cubewp_posts.default', removePostSlider_loop);
    elementorFrontend.hooks.addAction("frontend/element_ready/container", initcontainer_section_slider);
  });


})(jQuery);