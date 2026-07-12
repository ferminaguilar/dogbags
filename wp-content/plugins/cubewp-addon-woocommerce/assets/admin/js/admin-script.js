(function ($) {
    "use strict";
    jQuery(document).ready(function ($) {   
      $(document).on('click', '.vp_tab_button', function () {
        var type = $(this).data('type');
        $('#vp_video_type').val(type);
        $('.vp_tab_button').removeClass('active');
        $(this).addClass('active');
        $('.vp_video-tab-content').removeClass('active');
        $('.vp_' + type + '-tab').addClass('active');
      });
  
      $(document).on('click', '.vp_upload_video_button', function (e) {
        e.preventDefault();
        var button = $(this);
        var input = button.prev();
  
        var uploader = wp.media({
          title: 'Select or Upload Video',
          button: { text: 'Use this video' },
          library: { type: 'video' },
          multiple: false
        });
  
        uploader.on('select', function () {
          var attachment = uploader.state().get('selection').first().toJSON();
          input.val(attachment.url);
        });
  
        uploader.open();
      });
    });
  })($);