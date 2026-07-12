jQuery(document).ready(function ($) {
    let searchTimer;

    /* -------------------------------
     * SINGLE PRODUCT SEARCH (existing)
     * ------------------------------- */
    $('.elementor-control-field_product_search input').on('keyup', function () {
        clearTimeout(searchTimer);
        const $input = $(this);
        const query = $input.val();
        const $control = $input.closest('.elementor-control');
        const $results = $control.find('.vp-search-results');

        if (query.length < 2) {
            $results.html('').hide();
            return;
        }

        searchTimer = setTimeout(function () {
            $.ajax({
                url: vp_product_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'vp_search_products',
                    nonce: vp_product_ajax.nonce,
                    query: query,
                },
                success: function (response) {
                    if (response.success && response.data.length > 0) {
                        let html = '<ul class="vp-search-results-list">';
                        response.data.forEach(function (product) {
                            html += '<li data-id="' + product.id + '">' + product.title + '</li>';
                        });
                        html += '</ul>';
                        $results.html(html).show();
                    } else {
                        $results.html('<p>No products found</p>').show();
                    }
                },
            });
        }, 300);
    });

    $(document).on('click', '.vp-search-results-list li', function () {
        const productId = $(this).data('id');
        const $control = $(this).closest('.elementor-control');
        $control.find('input[type="hidden"]').val(productId);
        $control.find('.elementor-control-field_product_search input').val($(this).text());
        $control.find('.vp-search-results').hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.elementor-control-field_product_search').length) {
            $('.vp-search-results').hide();
        }
    });

    // Close tag functionality
    $(document).on('click', '.vp-tag-remove', function () {
        const $tag = $(this).closest('.vp-tag');
        const $hiddenInput = $tag.siblings('input[type="hidden"]');
        const currentValue = $hiddenInput.val();
        const tagId = $tag.data('id');
        const ids = currentValue ? currentValue.split(',') : [];
        const newIds = ids.filter(function (id) {
            return id != tagId;
        });
        $hiddenInput.val(newIds.join(','));
        $tag.remove();
    });

    /* -------------------------------
     * CUBEWP SUBMIT FORM
     * Auto-detect field type from field_key
     * ------------------------------- */
    
    // Field type detection mapping
    const fieldTypeMapping = {
        // Taxonomy fields
        taxonomy: function(fieldKey) {
            return fieldKey.indexOf('taxonomy_') === 0 ? 'taxonomy' : null;
        },
        
        // Default post fields
        postFields: {
            'the_title': 'text',
            'the_content': 'textarea',
            'the_excerpt': 'textarea',
            'featured_image': 'file'
        },
        
        // Default user fields
        userFields: {
            'user_login': 'text',
            'user_email': 'email',
            'user_pass': 'password',
            'confirm_pass': 'password',
            'user_url': 'url',
            'display_name': 'text',
            'nickname': 'text',
            'first_name': 'text',
            'last_name': 'text',
            'description': 'textarea'
        }
    };
    
    function detectFieldType(fieldKey, context) {
        if (!fieldKey) {
            return 'text';
        }
        
        // Check taxonomy
        if (fieldTypeMapping.taxonomy(fieldKey) === 'taxonomy') {
            return 'taxonomy';
        }
        
        // Check user fields
        if (context === 'user' && fieldTypeMapping.userFields[fieldKey]) {
            return fieldTypeMapping.userFields[fieldKey];
        }
        
        // Check post fields
        if (context !== 'user' && fieldTypeMapping.postFields[fieldKey]) {
            return fieldTypeMapping.postFields[fieldKey];
        }
        
        // Default fallback
        return 'text';
    }
    
    // Handle field_key changes in repeater items
    $(document).on('change', '.elementor-control-field_key select, .elementor-control-field_key .elementor-select2', function() {
        const $control = $(this).closest('.elementor-control');
        const $repeaterItem = $control.closest('.elementor-repeater-row');
        
        if (!$repeaterItem.length) {
            return;
        }
        
        const fieldKey = $(this).val();
        const $widget = $control.closest('.elementor-controls-stack');
        const widgetName = $widget.data('widget-name') || '';
        
        // Determine context (check if it's user form or post form)
        let context = 'post';
        if (widgetName.indexOf('user') !== -1 || $widget.find('.elementor-control-form_type select').val() === 'user_form') {
            context = 'user';
        }
        
        // Detect field type
        const fieldType = detectFieldType(fieldKey, context);
        
        // Find and update field_type control
        const $fieldTypeControl = $repeaterItem.find('.elementor-control-field_type input[type="hidden"]');
        if ($fieldTypeControl.length) {
            $fieldTypeControl.val(fieldType);
            
            // Trigger change event to update Elementor
            $fieldTypeControl.trigger('change');
            
            // Also update via Elementor's API if available
            if (window.elementor && window.elementor.channels) {
                const editor = window.elementor.channels.editor;
                if (editor) {
                    const itemIndex = $repeaterItem.index();
                    const repeaterName = $repeaterItem.closest('.elementor-control').data('setting') || 'form_fields';
                    
                    // Update the control value via Elementor's API
                    const elementModel = editor.request('element:selected');
                    if (elementModel) {
                        const settings = elementModel.get('settings');
                        const currentRepeater = settings.attributes[repeaterName] || [];
                        if (currentRepeater[itemIndex]) {
                            currentRepeater[itemIndex].field_type = fieldType;
                            elementModel.setSetting(repeaterName, currentRepeater);
                        }
                    }
                }
            }
        }
    });
});
