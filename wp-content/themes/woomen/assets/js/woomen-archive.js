(function ($) {
  "use strict";

  jQuery(document).ready(function ($) {
    // new js for archive
    $(document).on("click", ".wo-women-archive-list-grids .wc-shop-results-views", function () {
      $(".wo-women-archive-list-grids .wc-shop-results-views").removeClass(
        "active"
      );
      $(this).addClass("active");
      var get_numbers = $(this).data("views");
      $(".cwp-grids-container").removeClass("columns-2");
      $(".cwp-grids-container").removeClass("columns-4");
      $(".cwp-grids-container").addClass("columns-" + get_numbers);
      $(".fa-chevron-left").trigger("click");
    });

    // new js for archive
    $(document).on("click", ".woomen-shop-loop-hide-filters", function (event) {
      event.preventDefault();

      // Toggle the filter visibility
      $(".cubewp-filters-style1.filters").toggleClass("hidefilters");

      // Toggle the filter button class
      $(this).toggleClass("filters");

      // Change the text based on the current state
      if ($(this).hasClass("filters")) {
        // If the button has the 'filters' class, show "SHOW FILTERS"
        $(this).find("span").text($(this).data("show"));
      } else {
        // Otherwise, show "HIDE FILTERS"
        $(this).find("span").text($(this).data("hide"));
      }
    });

    $(document).on("click", "#close-archive-filter", function (event) {
      event.preventDefault();

      // Toggle the filter visibility
      $(".cubewp-filters-style1.filters").removeClass("hidefilters");
    });

    $(document).on("click", ".cubewp-show-mobile-filters", function (event) {
      event.preventDefault();
      $(".cubewp-append-mobile-filters").addClass("active");
      $(".cubewp-append-mobile-filters").append(
        '<div class="woo-close-wp-filters"><svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D"/><path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D"/></svg></div>'
      );
    });
    $(document).on("click", ".woo-close-wp-filters", function (event) {
      event.preventDefault();
      $(".cubewp-append-mobile-filters").removeClass("active");
      $(this).remove();
    });

    $(document).on(
      "click",
      ".cwp-field-container .cwp-search-field>label, .cwp-field-container>label",
      function (event) {
        event.preventDefault();
        $(this).next("div").slideToggle();
        $(this).next("input").slideToggle();
        $(this).next("ul").slideToggle();
        $(this).toggleClass("active");
      }
    );

    $(document).on("cubewp_search_results_loaded", function () {
      $(".wc-women-remove-empty").each(function () {
        var get_TEXT = $(this).text();
        if (get_TEXT == "" || get_TEXT == null) {
          $(this).remove();
        }
      });
      jQuery(".wc-women-remove-empty-s").each(function () {
        var getText = jQuery(this).text();
        if (/\d/.test(getText)) {} else {
          jQuery(this).remove();
        }
      });
      if (jQuery(".woomen-shop-this-look-slider-4").length > 0) {
        if (jQuery(".woomen-shop-this-look-slider-4").hasClass('slick-initialized')) {
          jQuery(".woomen-shop-this-look-slider-4").slick('unslick');
        }
        jQuery(".woomen-shop-this-look-slider-4").find('.cwp-post-hidden-id').remove();
        setTimeout(function () {
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
        }, 200)
      }
    });

    $(".woomen-slider-range").each(function () {
      var $slider = $(this);
      var $inputMin = $slider
        .closest(".woomen-number-field-slider")
        .find(".woomen-min-number");
      var $inputMax = $slider
        .closest(".woomen-number-field-slider")
        .find(".woomen-max-number");
      // Check if $slider exists and has a length greater than 0
      if ($slider.length && $inputMin.length && $inputMax.length) {
        noUiSlider.create($slider[0], {
          start: [$inputMin.attr("value"), $inputMax.attr("value")],
          connect: true,
          range: {
            min: +$inputMin.attr("min"),
            max: +$inputMax.attr("max"),
          },
          step: +$inputMax.attr("step"),
        });
        $slider[0].noUiSlider.on("update", function (values, handle) {
          var value = values[handle];
          if (handle) {
            $inputMax.val(Math.round(value));
            $inputMax.trigger("keyup");
          } else {
            $inputMin.val(Math.round(value));
            $inputMin.trigger("keyup");
          }
        });
        $inputMin.on("change", function () {
          $slider[0].noUiSlider.set([this.value, null]);
        });
        $inputMax.on("change", function () {
          $slider[0].noUiSlider.set([null, this.value]);
        });
      }
    });
    $(document).on("click", ".refresh-page", function () {
      location.reload();
    });
    $(document).on("click", "#archive-side-filters", function () {
      $(".cubewp-filters-woomen-side-modal").addClass("active");
    });
    $(document).on("click", ".close-data-side-filter", function () {
      $(".cubewp-filters-woomen-side-modal").removeClass("active");
    });

    // Toggle visibility and change text on .woomen-see-more-category click
    jQuery(".woomen-see-more-category").on("click", function () {
      // Find the closest parent div and the elements inside it with class .woomen-term-container
      jQuery(this)
        .closest(".cwp-search-field-checkbox")
        .find("ul")
        .each(function () {
          if (jQuery(this).hasClass("d-none")) {
            jQuery(this).removeClass("d-none").addClass("d-flex");
          } else if (jQuery(this).hasClass("d-flex")) {
            jQuery(this).removeClass("d-flex").addClass("d-none");
          }
        });
      // Toggle the text between "See More" and "See Less"
      if (jQuery(this).text() === "SHOW MORE") {
        jQuery(this).text("SHOW LESS");
      } else {
        jQuery(this).text("SHOW MORE");
      }
    });
  });


  $('.cubewp-filters-style1.filter-show-style1 .cwp-field-container > label, .cubewp-filters-style1.filter-show-style1 .cwp-field-container .cwp-search-field > label').on('click', function (e) {
    e.stopPropagation();

    var parentContainer = $(this).closest('.cwp-search-field');
    var siblings = parentContainer.find('p, ul, div').not('.woomen-see-more-category');

    $('.cubewp-filters-style1.filter-show-style1 .cwp-search-field > p, .cubewp-filters-style1.filter-show-style1 .cwp-search-field > ul, .cubewp-filters-style1.filter-show-style1 .cwp-search-field > div').not('.woomen-see-more-category').removeClass('active');

    siblings.addClass('active');
  });

  $('.cubewp-filters-style1.filter-show-style1 .cwp-search-field > div').append(
    '<button class="cubewp-filters-style1-close-btn"><svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D"/><path d="M10.0117 10.4999C9.94753 10.5 9.88401 10.4874 9.82475 10.4628C9.7655 10.4383 9.71167 10.4023 9.66636 10.3569L0.643092 1.33364C0.551511 1.24206 0.500061 1.11785 0.500061 0.988337C0.500061 0.858822 0.551511 0.734611 0.643092 0.64303C0.734672 0.55145 0.858883 0.5 0.988398 0.5C1.11791 0.5 1.24212 0.55145 1.3337 0.64303L10.357 9.6663C10.4253 9.73459 10.4718 9.8216 10.4906 9.91633C10.5095 10.0111 10.4998 10.1092 10.4629 10.1985C10.4259 10.2877 10.3633 10.364 10.283 10.4176C10.2027 10.4713 10.1083 10.4999 10.0117 10.4999Z" fill="#1D1D1D"/></svg></button>'
  );

  $(document).on('click', '.cubewp-filters-style1-close-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).parent().removeClass('active');
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('.cwp-field-checkbox-container.woomen-category-card-have-collapse').length) {
      $('.cwp-field-checkbox-container.woomen-category-card-have-collapse').removeClass('active');
    }
  });

})(jQuery);