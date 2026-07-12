(function ($) {
    "use strict";
    jQuery(document).ready(function ($) {  
        jQuery(document).on('click'  , '.play-video' , function () {
            if (jQuery(this).closest('.product').find('.vp_iframe_wrapper').length > 0) {
                jQuery(this).closest('.product').find('.vp_iframe_wrapper').find('iframe')[0].contentWindow.postMessage(
                    '{"event":"command","func":"playVideo","args":""}', '*'
                );
            }
            if (jQuery(this).closest('.product').find('.vp_custom_video_box').length > 0) {
                const video = jQuery(this).closest('.product').find('.vp_custom_video_box').find('.vp_custom_video').get(0);
                video.play();
            }
            jQuery(this).closest('.product').find('.pause-video').show();
            jQuery(this).hide();
        });
        jQuery(document).on('click'  , '.pause-video' , function () { 
            if (jQuery(this).closest('.product').find('.vp_iframe_wrapper').length > 0) {
                jQuery(this).closest('.product').find('.vp_iframe_wrapper').find('iframe')[0].contentWindow.postMessage(
                    '{"event":"command","func":"pauseVideo","args":""}', '*'
                );
            }
            if (jQuery(this).closest('.product').find('.vp_custom_video_box').length > 0) {
                const video = jQuery(this).closest('.product').find('.vp_custom_video_box').find('.vp_custom_video').get(0);
                video.pause();
            }
            jQuery(this).closest('.product').find('.play-video').show();
            jQuery(this).hide();
        });
    });
})($);