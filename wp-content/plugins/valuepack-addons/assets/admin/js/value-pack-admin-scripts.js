(function ($) {
  "use strict";
  jQuery(document).ready(function ($) {
    $('body').on('click', '.vp_upload_image', function (e) {
      e.preventDefault();

      var button = $(this);
      var customUploader = wp.media({
        title: 'Select Size Guide Image',
        button: {
          text: 'Use this image'
        },
        multiple: false
      }).on('select', function () {
        var attachment = customUploader.state().get('selection').first().toJSON();
        $('#vp_size_guide_image').val(attachment.url);
        $('#vp_size_guide_image_preview').attr('src', attachment.url).show();
        $('.vp_remove_image').show();
      }).open();
    });

    // Remove image
    $('body').on('click', '.vp_remove_image', function (e) {
      e.preventDefault();
      $('#vp_size_guide_image').val('');
      $('#vp_size_guide_image_preview').hide();
      $(this).hide();
    });

 
      $(document).on('click' , '.vp_tab_button' , function () {
        var type = $(this).data('type');
        $('#vp_video_type').val(type);
        $('.vp_tab_button').removeClass('active');
        $(this).addClass('active');
        $('.vp_video-tab-content').removeClass('active');
        $('.vp_' + type + '-tab').addClass('active');
      });

      $(document).on('click', '.vp_upload_video_button' , function (e) {
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