jQuery(document).ready(function ($) {
    if ($('.woo-merchant-setting-page').length > 0) {
        $(document).on('change', 'input[type="checkbox"]', function () {
            var depends = $(this).data('name');
            if ($(this).is(':checked')) {
                $('.conditional-with-' + depends).show();
                $('.woo-merchant-setting-page select').trigger('change');
            } else {
                $('.conditional-with-' + depends).hide();
            }
        });

        $(document).on('change', '.woo-merchant-setting-page select', function () {
            var field = $(this).data('name');
            var value = $(this).val();
            var conditional_fields = $('.WM-conditional-field');
            if (conditional_fields.length > 0) {
                conditional_fields.each(function () {
                    var field_id = $(this).attr('data-conditional-field');
                    var field_val = $(this).attr('data-conditional-value');
                    if (field == field_id) {
                        if (value == field_val) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            }
        });

        $('.woo-merchant-setting-page select').trigger('change');
        $('.woo-merchant-setting-page input[type="checkbox"]').trigger('change');

    }



    // Upload image
    jQuery('body').on('click', '.upload_image_button', function (e) {
        e.preventDefault();

        var button = jQuery(this);
        var customUploader = wp.media({
            title: 'Select Size Guide Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = customUploader.state().get('selection').first().toJSON();
            jQuery('#size_guide_image').val(attachment.url);
            jQuery('#size_guide_image_preview').attr('src', attachment.url).show();
            jQuery('.remove_image_button').show();
        }).open();
    });

    // Remove image
    jQuery('body').on('click', '.remove_image_button', function (e) {
        e.preventDefault();
        jQuery('#size_guide_image').val('');
        jQuery('#size_guide_image_preview').hide();
        jQuery(this).hide();
    });

    $(document).on('click', '#add-new-content', function () {
        var index = $('.custom-content-row').length - 1; // Exclude the hidden template
        var newContent = $('.custom-content-row.hidden').clone().removeClass('hidden');
        newContent.find('textarea').attr('id', 'custom_content_' + index);
        newContent.find('select').attr('id', 'custom_content_position_' + index);
        newContent.find('textarea').attr('name', 'WM_woocommerce_features_options[custom_contents][' + index + '][content]');
        newContent.find('select').attr('name', 'WM_woocommerce_features_options[custom_contents][' + index + '][position]');
        newContent.attr('data-index', index);
        newContent.insertBefore($(this).closest('tr'));

        // Initialize the TinyMCE editor
        tinymce.init({
            selector: '#custom_content_' + index,
            height: 200,
            menubar: false,
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    });

    // Handle delete button
    $(document).on('click', '.remove-content', function () {
        $(this).closest('.custom-content-row').remove();
    });
});

jQuery(document).ready(function ($) {
    function toggleFields(triggerSelector, targetClass, showValue) {
        // Check if the trigger's value matches the showValue
        var showFields = $(triggerSelector).val() === showValue;
        // Toggle the target fields based on the trigger's value
        $(targetClass).toggle(showFields);
    }

    // Initialize and set up change events for each field group
    var fieldGroups = [{
            trigger: '#_enable_discount',
            target: '._discount_fields',
            value: 'yes'
        },
        {
            trigger: '#_WM_free_gift_yes_no',
            target: '._free_gift_fields',
            value: 'yes'
        },
        {
            trigger: '#_WM_cross_sells_yes_no',
            target: '._cross_sell_field',
            value: 'yes'
        }
    ];

    // Loop through each group to set up the toggling logic
    fieldGroups.forEach(function (group) {
        // Initialize the field visibility based on current values
        toggleFields(group.trigger, group.target, group.value);
        // Set up the change event to toggle visibility when the trigger changes
        $(group.trigger).on('change', function () {
            toggleFields(group.trigger, group.target, group.value);
        });
    });
});

jQuery(document).ready(function ($) {
    /*
     * Initialize datepicker on page load
     */
    function initDatepicker(selector, minDate) {
        if (typeof $.fn.datepicker !== 'function') {
            return;
        }

        $(selector).datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: minDate || new Date()
        });
    }

    // Check if jQuery UI datepicker is available
    if (typeof $.fn.datepicker === 'function') {
        // Initialize datepicker for static fields
        initDatepicker('.datepicker');

        // Handle dynamically loaded variation fields (Event Delegation)
        $('#variable_product_options').on('focus', '.datepicker', function () {
            if (!$(this).hasClass('hasDatepicker')) {
                initDatepicker(this);
            }
        });

        // Initialize datepicker for checkout form pre-order date
        var preOrderMinDate = $('#preorder_date').data('pre_order_date') || new Date();
        initDatepicker('#preorder_date', preOrderMinDate);
    }
});