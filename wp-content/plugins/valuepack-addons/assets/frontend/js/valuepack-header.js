jQuery(document).ready(function($) {
    $(".elementor.elementor-" + valuepackHeaderData.popupId).addClass("vp-sticky-header " + valuepackHeaderData.enableStickyBottom);
    let vp_headerlastScrollTop = 0;
    let vp_headerisHovered = false;
    let $vp_header = $(".vp-sticky-header");
    let $window = $(window);
    $vp_header.wrap('<div class="vp-sticky-wrapper"></div>');
    let $vp_wrapper = $(".vp-sticky-wrapper");
    let headerHeight = $vp_header.outerHeight(true);
    $vp_wrapper.css("min-height", headerHeight + "px");
    function checkScrollPosition() {
        let currentScroll = $window.scrollTop();
        let documentHeight = $(document).height();
        let windowHeight = $(window).height();
        if (currentScroll === 0) {
            $vp_header.addClass("scroll-top").removeClass("scroll-bottom");
        } else if (currentScroll + windowHeight >= documentHeight) {
            $vp_header.addClass("scroll-bottom").removeClass("scroll-top");
        } else {
            $vp_header.removeClass("scroll-top scroll-bottom");
        }
    }
    checkScrollPosition();
    $vp_header.hover(
        function () {
            vp_headerisHovered = true;
            $vp_header.addClass("sticky-active");
        },
        function () {
            vp_headerisHovered = false;
        }
    );
    $window.on("scroll", function () {
        let currentScroll = $window.scrollTop();
        if (currentScroll < vp_headerlastScrollTop || vp_headerisHovered) {
            $vp_header.addClass("sticky-active");
        } else {
            if (!vp_headerisHovered) {
                $vp_header.removeClass("sticky-active");
            }
        }
        checkScrollPosition();
        vp_headerlastScrollTop = currentScroll;
    });
});