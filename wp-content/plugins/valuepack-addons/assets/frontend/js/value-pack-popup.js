(function ($) {
	"use strict";
	jQuery(window).on("load", function () {
		setTimeout(function () {
			if ($(".vpack-builder-popup").length > 0) {
				$(".vpack-builder-popup").each(function () {
					var popup = $(this);
					var popupId = popup.data("ep-popup-id");
					// Initialize Elementor widgets if content is already loaded
					initializePopupContent(popup);
					setTimeout(function () {
						loadPopupeach(popupId, popup);
					}, 100);
				});
			}
		}, 100);
	});
	jQuery(document).on('click', function (e) {
		// If click is inside .e-n-accordion-item-title, do nothing
		if (jQuery(e.target).closest('.e-n-accordion-item-title').length > 0) {
			return;
		}

		jQuery('.vpack-builder-popup-inner').each(function () {
			var popupInner = jQuery(this);
			var popup = popupInner.closest('.vpack-builder-popup');
			if (!popupInner.is(e.target) && popupInner.has(e.target).length === 0) {
				var popupContent = popup.find('.vpack-builder-popup-content');
				popupContent
					.stop(true, true)
					.removeClass(function (index, className) {
						return (className.match(/(^|\s)animate__\S+/g) || []).join(' ');
					})
					.addClass('animate__animated animated-duration1 animate__fadeOut');
				setTimeout(function () {
					popupContent.css('display', 'none');
					popup.hide();
					popup.prev('.bg-layers').hide();
				}, 300);
			}
		});
	});

	function initializePopupContent(popup) {
		// Content is already loaded in the DOM, just initialize Elementor widgets that need special handling
		const content = popup.find('.vpack-builder-popup-content');
		if (typeof elementorFrontend !== 'undefined' && content.length > 0 && content.children().length > 0) {
			// Initialize Elementor widgets that require special initialization (tabs, accordions, etc.)
			content.find('.elementor-widget-n-tabs, .elementor-widget-n-accordion').each(function () {
				elementorFrontend.elementsHandler.runReadyTrigger($(this));
			});
		}
	}

	function loadPopupeach(popupId, popup) {
		var popup = popup;
		var triggerType = popup.data("ep-trigger-type");
		var popupWidth = popup.data("ep-popupsize");
		var popupPosition = popup.data("ep-position");
		var animationIn = popup.data("ep-animationin");
		var animationOut = popup.data("ep-animationout");
		var popupHeight = popup.data("ep-popup_height");
		var triger = popup.attr("data-ep-triger");
		var popup_open_delay = popup.attr("data-ep-popup_open_delay");
		var popup_cookie_expiration = popup.attr("data-ep-popup_cookie_expiration");
		var trigerclose = popup.attr("data-ep-trigerclose");
		var animationDuration = 300;
		popup.find(".vpack-builder-popup-inner").css({
			width: popupWidth,
			height: popupHeight,
		});

		setPopupPosition(popup, popupPosition);

		function openPopup() {
			// Hide any other open popups so only this one is visible
			jQuery('.vpack-builder-popup').each(function () {
				var other = jQuery(this);
				if (other.get(0) !== popup.get(0) && other.is(':visible')) {
					other.prev('.bg-layers').hide();
					other.hide();
				}
			});

			var get_time_open_delay = popup_open_delay ? parseInt(popup_open_delay, 10) * 1000 : 0;

			setTimeout(function () {
				popup.find('.vpack-builder-popup-content')
					.stop(true, true)
					.removeClass(function (index, className) {
						return (className.match(/(^|\s)animate__\S+/g) || []).join(' ');
					})
					.addClass("animate__animated animated-duration1 animate__" + animationIn)
					.css({
						display: "block",
					});
				popup.css({
					display: "flex",
				});
				popup.prev('.bg-layers').fadeIn('slow');
			}, get_time_open_delay);
		}
		// Function to close the popup with animation
		function closePopup($this) {
			// Save the popup expiration time in cookies
			if (popup_cookie_expiration) {
				const expirationDate = new Date();
				expirationDate.setTime(expirationDate.getTime() + (popup_cookie_expiration * 60 * 1000));
				document.cookie = `${triger}=closed; expires=${expirationDate.toUTCString()}; path=/`;
			}
			var popupContent = $this.closest('.vpack-builder-popup-content');
			var popupWrap = $this.closest('.vpack-builder-popup');
			popupContent
				.stop(true, true)
				.removeClass(function (index, className) {
					return (className.match(/(^|\s)animate__\S+/g) || []).join(' ');
				})
				.addClass("animate__animated animated-duration1 animate__" + animationOut);
			setTimeout(function () {
				popupContent.css("display", "none");
				popupWrap.hide();
				popupWrap.prev('.bg-layers').hide();
			}, animationDuration);
		}

		if (triggerType === "on_load_data") {
			openPopup();
		} else if (triggerType === "on_click") {
			$(document).on("click", "#" + triger + ', .' + triger, function (e) {
				e.preventDefault();
				e.stopPropagation();
				openPopup();
			});
		} else if (triggerType === "on_hover") {
			$(document).on("mouseenter", "#" + triger + ', .' + triger, function () {
				openPopup();
			});
		} else if (triggerType === "on_exit_intent") {
			$(document).on("mouseleave", function (e) {
				if (e.clientY < 0) {
					openPopup();
				}
			});
		} else if (triggerType === "on_scroll") {
			$(window).on("scroll", function () {
				let scrollPercent =
					($(window).scrollTop() /
						($(document).height() - $(window).height())) *
					100;
				if (scrollPercent > 50) {
					// Trigger at 50% scroll
					openPopup();
					$("#" + trigerclose, popup).on("click", function (e) {
						popup
							.removeClass("animate__" + animationIn)
							.addClass("animated-duration1 animate__" + animationOut)
							.fadeOut();
						setTimeout(() => {
							popup.remove();
						}, 1000);
					});
				}
			});
		} else if (triggerType === "on_mouseenter") {
			$("#" + triger + ', .' + triger).on("mouseenter", function (e) {
				e.stopPropagation();
				openPopup();
			});
		} else if (triggerType === "on_mouseleave") {
			$("#" + triger + ', .' + triger).on("mouseleave", function (e) {
				e.stopPropagation();
				openPopup();
			});
		} else if (triggerType === "on_dblclick") {
			$("#" + triger + ', .' + triger).on("dblclick", function (e) {
				e.preventDefault();
				e.stopPropagation();
				openPopup();
			});
		}
		// Close popup on close button click
		$(document).on("click", "#" + trigerclose, function (e) {
			e.preventDefault();
			closePopup($(this));
		});
	}

	function setPopupPosition(popup, position) {
		var css = {};
		var transform = '';

		switch (position) {
			case "center_position":
				css = {
					top: "50%",
					left: "50%",
					transform: "translate(-50%, -50%)",
				};
				break;
			case "top_center":
				css = {
					top: "0",
					left: "50%",
					transform: "translateX(-50%)",
				};
				break;
			case "bottom_center":
				css = {
					bottom: "0",
					left: "50%",
					transform: "translateX(-50%)",
				};
				break;
			case "left_center":
				css = {
					top: "50%",
					left: "0",
					transform: "translateY(-50%)",
				};
				break;
			case "right_center":
				css = {
					top: "50%",
					right: "0",
					transform: "translateY(-50%)",
				};
				break;
			case "top_left_corner":
				css = {
					top: "0",
					left: "0",
				};
				break;
			case "top_right_corner":
				css = {
					top: "0",
					right: "0",
				};
				break;
			case "bottom_left_corner":
				css = {
					bottom: "0",
					left: "0",
				};
				break;
			case "bottom_right_corner":
				css = {
					bottom: "0",
					right: "0",
				};
				break;
		}
		popup.css(css);
	}
})(jQuery);