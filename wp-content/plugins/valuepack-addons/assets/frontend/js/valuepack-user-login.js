(function ($) {
    'use strict';
    $.cubewp_user_login = $.cubewp_user_login || {};

    $(document).on('click', '.vp-forget-password-form-trigger,.vp-login-form-trigger' , function (event) {
        event.preventDefault();
        $.cubewp_user_login.toggle_form(this);
    });

    $(document).on('submit', '#vp-login-form', function (event) {
        event.preventDefault();
        $.cubewp_user_login.login_form(this);
    });

    $(document).on('submit', '#reset-password-form', function (event) {
        event.preventDefault();
        $.cubewp_user_login.reset_password_form(this);
    });

    $(document).on('submit', '#vp-forget-password-form', function (event) {
        event.preventDefault();
        $.cubewp_user_login.forget_form(this);
    });

    $.cubewp_user_login.toggle_form = function (t) {
        var $this = jQuery(t);
        if ($this.hasClass("vp-forget-password-form-trigger")) {
            jQuery('#vp-login-form').hide();
            jQuery('#vp-forget-password-form').show();
        }else if ($this.hasClass("vp-login-form-trigger")) {
            jQuery('#vp-login-form').show();
            jQuery('#vp-forget-password-form').hide();
        }
    };

    $.cubewp_user_login.login_form = function (t) {
        var $this = jQuery(t),
            is_valid = cubewp_frontend_form_validation($this);
        if (is_valid === true) {
            $this.find("input[type=submit]").addClass("cubewp-processing-ajax");
            var formData = new FormData($this[0]);
            formData.append('action', 'vp_ajax_login');
            formData.append('security', cwp_user_login_params.nonce);
            jQuery.ajax({
                url: cwp_user_login_params.ajax_url,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    jQuery(".cwp-alert.cwp-js-alert").insertAfter("#vp-login-modal").css("z-index", "999999999999999999999");
                    cwp_notification_ui(response.type, response.msg);
                    $this.find("input[type=submit]").removeClass("cubewp-processing-ajax");
                    if (typeof response.redirectURL != 'undefined' && response.redirectURL !== '') {
                        setTimeout(function () {
                            if (response.redirectURL === 'self') {
                                location.reload();
                            }else {
                                window.location.href = response.redirectURL;
                            }
                        }, 3000);
                    }
                },
                error: function () {
                    jQuery(".cwp-alert.cwp-js-alert").insertAfter("#vp-login-modal").css("z-index", "999999999999999999999");
                    cwp_notification_ui("error", cwp_user_login_params.error_msg);
                    $this.find("input[type=submit]").removeClass("cubewp-processing-ajax");
                }
            });
        }
    };
    $.cubewp_user_login.reset_password_form = function (t) {
        var $this = jQuery(t),
            is_valid = cubewp_frontend_form_validation($this);
        if (is_valid === true) {
            $this.find("input[type=submit]").addClass("cubewp-processing-ajax");
            var formData = new FormData($this[0]);
            formData.append('action', 'cubewp_reset_password');
            jQuery.ajax({
                url: cwp_user_login_params.ajax_url,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    jQuery(".cwp-alert.cwp-js-alert").insertAfter("#vp-login-modal").css("z-index", "999999999999999999999");
                    cwp_notification_ui(response.type, response.msg);
                    $this.find("input[type=submit]").removeClass("cubewp-processing-ajax");
                    if (typeof response.redirectURL != 'undefined' && response.redirectURL !== '') {
                        setTimeout(function () {
                            if (response.redirectURL === 'self') {
                                location.reload();
                            }else {
                                window.location.href = response.redirectURL;
                            }
                        }, 3000);
                    }
                },
                error: function () {
                    jQuery(".cwp-alert.cwp-js-alert").insertAfter("#vp-login-modal").css("z-index", "999999999999999999999");
                    cwp_notification_ui("error", cwp_user_login_params.error_msg);
                    $this.find("input[type=submit]").removeClass("cubewp-processing-ajax");
                }
            });
        }
    };
    
    $.cubewp_user_login.forget_form = function (t) {
        var $this = jQuery(t),
            is_valid = cubewp_frontend_form_validation($this);
        if (is_valid === true) {
            $this.find("input[type=submit]").addClass("cubewp-processing-ajax");
            var formData = new FormData($this[0]);
            formData.append('action', 'cubewp_ajax_forget_password');
            jQuery.ajax({
                url: cwp_user_login_params.ajax_url,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    jQuery(".cwp-alert.cwp-js-alert").insertAfter("#vp-login-modal").css("z-index", "999999999999999999999");
                    cwp_notification_ui(response.type, response.msg);
                    $this.find("input[type=submit]").removeClass("cubewp-processing-ajax");
                    if (response.type === 'success') {
                        $this[0].reset();
                        jQuery(".vp-login-form-trigger").trigger("click");
                    }
                    if (typeof response.redirectURL != 'undefined' && response.redirectURL !== '') {
                        setTimeout(function () {
                            window.location.href = response.redirectURL;
                        }, 3000);
                    }
                },
                error: function () {
                    jQuery(".cwp-alert.cwp-js-alert").insertAfter("#vp-login-modal").css("z-index", "999999999999999999999");
                    cwp_notification_ui("error", cwp_user_login_params.error_msg);
                    $this.find("input[type=submit]").removeClass("cubewp-processing-ajax");
                }
            });
        }
    };
})(jQuery);