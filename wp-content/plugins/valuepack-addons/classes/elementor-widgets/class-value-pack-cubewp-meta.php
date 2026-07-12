<?php

/**
 * Cubewp Metas Widget
 *
 * @package valuepack-addons/cube/classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;


/**
 * Cubewp Meta Widget
 */
class Value_Pack_CubeWp_Meta extends Value_Pack_Widget_Base
{
    public function get_name()
    {
        return 'vp_cubewp_meta';
    }

    public function get_title()
    {
        return esc_html__('CubeWP Meta', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-meta-data vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'valuepack-addons'),
            ]
        );

        // Meta Source
        $this->add_control(
            'meta_source',
            [
                'label' => __('Meta Source', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'post' => __('Post Meta', 'valuepack-addons'),
                    'user' => __('User Meta', 'valuepack-addons'),
                ],
                'default' => 'post',
            ]
        );

        // Post Type Selector
        $post_types = get_post_types(['public' => true], 'objects');
        $post_type_options = array('' => __('Select Post Type', 'valuepack-addons'));
        foreach ($post_types as $post_type) {
            $post_type_options[$post_type->name] = $post_type->label;
        }

        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_type_options,
                'default' => '',
                'condition' => [
                    'meta_source' => 'post',
                ],
            ]
        );

        // Get all fields for all post types and create separate controls with conditions
        if (function_exists('get_fields_by_post_type')) {
            foreach ($post_types as $post_type_obj) {
                $post_type_name = $post_type_obj->name;
                $post_type_fields = get_fields_by_post_type($post_type_name);

                $field_options = array('' => __('Select Meta Field', 'valuepack-addons'));
                if (!empty($post_type_fields) && is_array($post_type_fields)) {
                    foreach ($post_type_fields as $field_key => $field_label) {
                        // Skip business_hours, repeating_field, and google_address types
                        if (function_exists('get_field_options')) {
                            $field_opts = get_field_options($field_key);
                            $field_type = isset($field_opts['type']) ? $field_opts['type'] : '';

                            // Skip business_hours, repeating_field, and google_address
                            if (in_array($field_type, array('business_hours', 'wysiwyg_editor', 'oembed', 'repeating_field', 'google_address'), true)) {
                                continue;
                            }
                        }

                        $field_options[$field_key] = $field_label;
                    }
                }

                // Create a separate meta field control for each post type
                $this->add_control(
                    'meta_field_' . $post_type_name,
                    [
                        'label' => __('Meta Field', 'valuepack-addons'),
                        'type' => Controls_Manager::SELECT,
                        'options' => $field_options,
                        'default' => '',
                        'condition' => [
                            'meta_source' => 'post',
                            'post_type' => $post_type_name,
                        ],
                        'description' => sprintf(__('Select a CubeWP custom field for %s post type.', 'valuepack-addons'), $post_type_obj->label),
                    ]
                );
            }
        }


        // User Source
        $this->add_control(
            'user_source',
            [
                'label' => __('User Source', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'current_author' => __('Current Post Author', 'valuepack-addons'),
                    'archive_author' => __('Current Archive Author', 'valuepack-addons'),
                    'current_user' => __('Logged in User', 'valuepack-addons'),
                    'custom' => __('Custom User ID', 'valuepack-addons'),
                ],
                'default' => 'current_author',
                'condition' => [
                    'meta_source' => 'user',
                ],
            ]
        );

        // Custom User ID
        $this->add_control(
            'user_id_custom',
            [
                'label' => __('User ID', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'meta_source' => 'user',
                    'user_source' => 'custom',
                ],
            ]
        );

        // User Meta Fields
        $user_fields = array();
        if (function_exists('CWP')) {
            $cwp_user_fields = CWP()->get_custom_fields('user');
            if (!empty($cwp_user_fields) && is_array($cwp_user_fields)) {
                $user_fields[''] = __('Select User Meta Field', 'valuepack-addons');
                foreach ($cwp_user_fields as $key => $field) {
                    $label = isset($field['label']) ? $field['label'] : $key;
                    $user_fields[$key] = $label;
                }
            }
        }

        $this->add_control(
            'user_meta_field',
            [
                'label' => __('User Meta Field', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $user_fields,
                'default' => '',
                'condition' => [
                    'meta_source' => 'user',
                ],
            ]
        );


        // Display Label
        $this->add_control(
            'show_label',
            [
                'label' => __('Show Field Label', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        // Extra Text (appears with label)
        $this->add_control(
            'label_extra_text',
            [
                'label' => __('Extra Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('e.g., : or ;', 'valuepack-addons'),
                'condition' => [
                    'show_label' => 'yes',
                ],
                'description' => __('Add extra text like colon or semicolon before or after the label.', 'valuepack-addons'),
            ]
        );

        // Extra Text Position
        $this->add_control(
            'label_extra_text_position',
            [
                'label' => __('Extra Text Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before' => __('Before Label', 'valuepack-addons'),
                    'after' => __('After Label', 'valuepack-addons'),
                ],
                'default' => 'after',
                'condition' => [
                    'show_label' => 'yes',
                    'label_extra_text!' => '',
                ],
            ]
        );

        // Add Icon to Label
        $this->add_control(
            'label_add_icon',
            [
                'label' => __('Add Icon to Label', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'show_label' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        // Label Icon
        $this->add_control(
            'label_icon',
            [
                'label' => __('Label Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => '',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                ],
            ]
        );

        // Label Icon Position
        $this->add_control(
            'label_icon_position',
            [
                'label' => __('Icon Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before' => __('Before Label', 'valuepack-addons'),
                    'after' => __('After Label', 'valuepack-addons'),
                ],
                'default' => 'before',
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                ],
            ]
        );

        // Render Field (use CubeWP rendering)
        $this->add_control(
            'render_field',
            [
                'label' => __('Render Field', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Use CubeWP field rendering (for complex fields like galleries, maps, etc.)', 'valuepack-addons'),
            ]
        );
        // Render Field (use CubeWP rendering)
        $this->add_control(
            'hide_empty_fields',
            [
                'label' => __('Hide Empty Field', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Hide the field if it is empty or "No"', 'valuepack-addons'),
            ]
        );

        // Add icon to multi-value items
        $this->add_control(
            'multi_value_add_icon',
            [
                'label' => __('Add Icon to Multi Values', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Show an icon with each multi-value item (e.g. checkboxes, multi-select).', 'valuepack-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'multi_value_icon',
            [
                'label' => __('Multi Value Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => '',
                ],
                'condition' => [
                    'multi_value_add_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'multi_value_icon_position',
            [
                'label' => __('Icon Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before' => __('Before Item', 'valuepack-addons'),
                    'after' => __('After Item', 'valuepack-addons'),
                ],
                'default' => 'before',
                'condition' => [
                    'multi_value_add_icon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Link/URL Section
        $this->start_controls_section(
            'section_link',
            [
                'label' => __('Link/URL Options', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Use value as URL
        $this->add_control(
            'use_value_as_url',
            [
                'label' => __('Use Field Value as URL', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Convert the field value into a clickable link.', 'valuepack-addons'),
            ]
        );

        // URL prefix (prepended to value in href)
        $this->add_control(
            'link_url_prefix',
            [
                'label' => __('URL Prefix', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('e.g. tel: or mailto:', 'valuepack-addons'),
                'condition' => [
                    'use_value_as_url' => 'yes',
                ],
                'description' => __('Text added before the field value in the link href (e.g. tel: for phone numbers, mailto: for email).', 'valuepack-addons'),
            ]
        );

        // Link Text Type
        $this->add_control(
            'link_text_type',
            [
                'label' => __('Link Text', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'value' => __('Use Field Value', 'valuepack-addons'),
                    'custom' => __('Custom Text', 'valuepack-addons'),
                ],
                'default' => 'value',
                'condition' => [
                    'use_value_as_url' => 'yes',
                ],
            ]
        );

        // Custom Link Text
        $this->add_control(
            'custom_link_text',
            [
                'label' => __('Custom Link Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter link text', 'valuepack-addons'),
                'condition' => [
                    'use_value_as_url' => 'yes',
                    'link_text_type' => 'custom',
                ],
            ]
        );

        // Add Icon/Text
        $this->add_control(
            'link_add_icon_text',
            [
                'label' => __('Add Icon or Text', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'use_value_as_url' => 'yes',
                ],
            ]
        );

        // Icon Field (using Elementor Icons)
        $this->add_control(
            'link_icon',
            [
                'label' => __('Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => '',
                ],
                'condition' => [
                    'use_value_as_url' => 'yes',
                    'link_add_icon_text' => 'yes',
                ],
            ]
        );

        // Icon/Text Position
        $this->add_control(
            'link_icon_text_position',
            [
                'label' => __('Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'before' => __('Before Text', 'valuepack-addons'),
                    'after' => __('After Text', 'valuepack-addons'),
                ],
                'default' => 'after',
                'condition' => [
                    'use_value_as_url' => 'yes',
                    'link_add_icon_text' => 'yes',
                ],
            ]
        );

        // Open in New Tab
        $this->add_control(
            'link_open_new_tab',
            [
                'label' => __('Open in New Tab', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'use_value_as_url' => 'yes',
                ],
            ]
        );

        // Open in Popup
        $this->add_control(
            'link_open_in_popup',
            [
                'label' => __('Open in Popup', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'use_value_as_url' => 'yes',
                ],
                'description' => __('Show the field value in a popup/modal instead of displaying it directly.', 'valuepack-addons'),
            ]
        );

        // Use iframe for URL
        $this->add_control(
            'use_iframe_for_url',
            [
                'label' => __('Use iframe', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'use_value_as_url' => 'yes',
                ],
                'description' => __('Display the URL as an iframe instead of a link.', 'valuepack-addons'),
            ]
        );

        $this->end_controls_section();

        // Link/Button Style Section
        $this->start_controls_section(
            'section_link_style',
            [
                'label' => __('Link/Button Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'use_value_as_url' => 'yes',
                ],
            ]
        );

        // Container Justify Content
        $this->add_control(
            'link_justify_content',
            [
                'label' => __('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('End', 'valuepack-addons'),
                    'space-between' => __('Space Between', 'valuepack-addons'),
                    'space-evenly' => __('Space Evenly', 'valuepack-addons'),
                    'space-around' => __('Space Around', 'valuepack-addons'),
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_align_items',
            [
                'label' => __('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'stretch' => __('Stretch', 'valuepack-addons'),
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'baseline' => __('Baseline', 'valuepack-addons'),
                ],
                'default' => 'stretch',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        // Link Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value a',
            ]
        );

        // Link Color
        $this->add_control(
            'link_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'align-items: center;transition: all 0.3s ease; display: inline-flex; color: {{VALUE}};',
                ],
            ]
        );

        // Link Background Color
        $this->add_control(
            'link_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Link Padding
        $this->add_control(
            'link_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Link Margin
        $this->add_control(
            'link_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Link Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'link_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value a',
            ]
        );

        // Link Border Radius
        $this->add_control(
            'link_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Link Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'link_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value a',
            ]
        );

        // Link Button width
        $this->add_control(
            'link_button_width',
            [
                'label' => __('Link Button Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10000,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Hover Heading
        $this->add_control(
            'link_hover_heading',
            [
                'label' => __('Hover State', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Link Hover Color
        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Hover Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Hover Background Color
        $this->add_control(
            'link_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Link Hover Border Color
        $this->add_control(
            'link_hover_border_color',
            [
                'label' => __('Hover Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        // Link Hover Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'link_hover_box_shadow',
                'label' => __('Hover Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value a:hover',
            ]
        );

        // Hover Heading
        // Icon Style Heading
        $this->add_control(
            'link_icon_style_heading',
            [
                'label' => __('Icon Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Icon Size
        $this->add_control(
            'link_icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'use_value_as_url' => 'yes',
                    'link_add_icon_text' => 'yes',
                ],
            ]
        );

        // Icon Color
        $this->add_control(
            'link_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'use_value_as_url' => 'yes',
                    'link_add_icon_text' => 'yes',
                ],
            ]
        );

        // Icon Hover Color
        $this->add_control(
            'link_icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a:hover svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'use_value_as_url' => 'yes',
                    'link_add_icon_text' => 'yes',
                ],
            ]
        );

        // Icon Spacing
        $this->add_control(
            'link_icon_spacing',
            [
                'label' => __('Icon Space', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 3,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value a' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'use_value_as_url' => 'yes',
                    'link_add_icon_text' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        // Wrapper Style Section
        $this->start_controls_section(
            'section_wrapper_style',
            [
                'label' => __('Wrapper Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Display Type
        $this->add_control(
            'wrapper_display',
            [
                'label' => __('Display', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => __('Block', 'valuepack-addons'),
                    'flex' => __('Flex', 'valuepack-addons'),
                ],
                'default' => 'block',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'display: {{VALUE}};',
                ],
            ]
        );

        // Flex Justify Content (only for flex)
        $this->add_control(
            'wrapper_justify_content',
            [
                'label' => __('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'space-between' => __('Space Between', 'valuepack-addons'),
                    'space-around' => __('Space Around', 'valuepack-addons'),
                    'space-evenly' => __('Space Evenly', 'valuepack-addons'),
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'wrapper_display' => 'flex',
                ],
            ]
        );

        // Flex Align Items (only for flex)
        $this->add_control(
            'wrapper_align_items',
            [
                'label' => __('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'stretch' => __('Stretch', 'valuepack-addons'),
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'baseline' => __('Baseline', 'valuepack-addons'),
                ],
                'default' => 'stretch',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'wrapper_display' => 'flex',
                ],
            ]
        );

        // Flex Direction (only when display is flex)
        $this->add_control(
            'wrapper_flex_direction',
            [
                'label' => __('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'wrapper_display' => 'flex',
                ],
            ]
        );

        // Gap (only when display is flex)
        $this->add_control(
            'wrapper_gap',
            [
                'label' => __('Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wrapper_display' => 'flex',
                ],
            ]
        );

        // Padding
        $this->add_control(
            'wrapper_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        $this->add_control(
            'wrapper_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-wrapper',
            ]
        );

        // Border Radius
        $this->add_control(
            'wrapper_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-wrapper',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Label Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Label Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-label',
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        // Label Color
        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        // Label Spacing
        $this->add_control(
            'label_spacing',
            [
                'label' => __('Label Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );

        // Label Icon Style Heading
        $this->add_control(
            'label_icon_style_heading',
            [
                'label' => __('Label Icon Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                ],
            ]
        );

        // Label Icon Size
        $this->add_control(
            'label_icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                ],
            ]
        );

        // Label Icon Color
        $this->add_control(
            'label_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                ],
            ]
        );

        // Label Icon Spacing
        $this->add_control(
            'label_icon_spacing',
            [
                'label' => __('Icon Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 3,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label' => 'display: inline-flex; align-items: center; position: relative;',
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon.vp-icon-before' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon.vp-icon-after' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                    'label_icon_position_type' => 'static',
                ],
            ]
        );

        // Label Icon Position Type
        $this->add_control(
            'label_icon_position_type',
            [
                'label' => __('Icon Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'static' => __('Static', 'valuepack-addons'),
                    'absolute' => __('Absolute', 'valuepack-addons'),
                ],
                'default' => 'static',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label' => 'position: relative;',
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon' => 'position: {{VALUE}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                ],
            ]
        );

        // Label Icon Top
        $this->add_control(
            'label_icon_top',
            [
                'label' => __('Top', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => -5,
                        'max' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon' => 'top: {{SIZE}}{{UNIT}}; margin-left: 0; margin-right: 0;',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                    'label_icon_position_type' => 'absolute',
                ],
            ]
        );

        // Label Icon Right
        $this->add_control(
            'label_icon_right',
            [
                'label' => __('Right', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => -5,
                        'max' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                    'label_icon_position_type' => 'absolute',
                ],
            ]
        );

        // Label Icon Bottom
        $this->add_control(
            'label_icon_bottom',
            [
                'label' => __('Bottom', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => -5,
                        'max' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                    'label_icon_position_type' => 'absolute',
                ],
            ]
        );

        // Label Icon Left
        $this->add_control(
            'label_icon_left',
            [
                'label' => __('Left', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => -5,
                        'max' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-label .vp-label-icon' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_label' => 'yes',
                    'label_add_icon' => 'yes',
                    'label_icon_position_type' => 'absolute',
                ],
            ]
        );

        // Value Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'label' => __('Value Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value',
            ]
        );

        // Value Color
        $this->add_control(
            'value_color',
            [
                'label' => __('Value Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Alignment
        $this->add_control(
            'alignment',
            [
                'label' => __('Alignment', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Multi-Value Items Style Section
        $this->start_controls_section(
            'section_multi_value_style',
            [
                'label' => __('Multi-Value Items Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'description' => __('These styles apply to fields with multiple values like checkboxes and multi-select dropdowns.', 'valuepack-addons'),
            ]
        );

        // Container Display
        $this->add_control(
            'multi_container_display',
            [
                'label' => __('Container Display', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => __('Block', 'valuepack-addons'),
                    'flex' => __('Flex', 'valuepack-addons'),
                    'inline-flex' => __('Inline Flex', 'valuepack-addons'),
                ],
                'default' => 'flex',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value' => 'width: 100%; display: {{VALUE}};',
                ],
            ]
        );

        // Flex Wrap Option
        $this->add_control(
            'multi_container_flex_wrap',
            [
                'label' => __('Flex Wrap', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'nowrap' => __('No Wrap', 'valuepack-addons'),
                    'wrap' => __('Wrap', 'valuepack-addons'),
                    'wrap-reverse' => __('Wrap Reverse', 'valuepack-addons'),
                ],
                'default' => 'wrap',
                'condition' => [
                    'multi_container_display' => ['flex', 'inline-flex'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value' => 'flex-wrap: {{VALUE}};',
                ],
            ]
        );

        // Container Flex Direction
        $this->add_control(
            'multi_container_flex_direction',
            [
                'label' => __('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'multi_container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );
        // Container Justify Content
        $this->add_control(
            'multi_container_justify_content',
            [
                'label' => __('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-start' => __('Start', 'valuepack-addons'),
                    'flex-end' => __('End', 'valuepack-addons'),
                    'space-between' => __('Space between', 'valuepack-addons'),
                    'space-evenly' => __('Space evenly', 'valuepack-addons'),
                    'space-around' => __('Space Arround', 'valuepack-addons'),
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'multi_container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );
        // Container Gap
        $this->add_control(
            'multi_container_gap',
            [
                'label' => __('Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'multi_container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        // Item Width
        $this->add_control(
            'multi_item_width',
            [
                'label' => __('Item Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'multi_container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        // Item Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'multi_item_typography',
                'label' => __('Item Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item',
            ]
        );

        // Item Color
        $this->add_control(
            'multi_item_color',
            [
                'label' => __('Item Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Item Background Color
        $this->add_control(
            'multi_item_bg_color',
            [
                'label' => __('Item Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Item Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'multi_item_border',
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item',
            ]
        );

        // Item Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'multi_item_box_shadow',
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item',
            ]
        );

        // Item Padding
        $this->add_control(
            'multi_item_padding',
            [
                'label' => __('Item Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Item Border Radius
        $this->add_control(
            'multi_item_border_radius',
            [
                'label' => __('Item Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Multi-value icon style
        $this->add_control(
            'multi_value_icon_style_heading',
            [
                'label' => __('Multi-Value Icon Style', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'multi_value_icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 8, 'max' => 80],
                    'em' => ['min' => 0.5, 'max' => 4],
                    'rem' => ['min' => 0.5, 'max' => 4],
                ],
                'default' => ['unit' => 'px', 'size' => 16],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item .vp-multi-value-item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item .vp-multi-value-item-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'multi_value_icon_color',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item .vp-multi-value-item-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item .vp-multi-value-item-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'multi_value_icon_spacing',
            [
                'label' => __('Icon Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 30],
                    'em' => ['min' => 0, 'max' => 2],
                ],
                'default' => ['unit' => 'px', 'size' => 6],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value.vp-multi-value .vp-multi-value-item.vp-multi-value-has-icon' => 'display: inline-flex; align-items: center; gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Image Style Section
        $this->start_controls_section(
            'section_image_style',
            [
                'label' => __('Image Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'description' => __('These styles apply to image and gallery fields.', 'valuepack-addons'),
            ]
        );

        // Image Width
        $this->add_control(
            'image_width',
            [
                'label' => __('Image Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Image Height
        $this->add_control(
            'image_height',
            [
                'label' => __('Image Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Object Fit
        $this->add_control(
            'image_object_fit',
            [
                'label' => __('Object Fit', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'fill' => __('Fill', 'valuepack-addons'),
                    'contain' => __('Contain', 'valuepack-addons'),
                    'cover' => __('Cover', 'valuepack-addons'),
                    'none' => __('None', 'valuepack-addons'),
                    'scale-down' => __('Scale Down', 'valuepack-addons'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        // Image Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __('Image Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value img',
            ]
        );

        // Image Border Radius
        $this->add_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-cubewp-meta-value img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Image Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-meta-value img',
            ]
        );

        // Gallery Container Display
        $this->add_control(
            'gallery_display',
            [
                'label' => __('Gallery Display', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'block' => __('Block', 'valuepack-addons'),
                    'flex' => __('Flex', 'valuepack-addons'),
                    'grid' => __('Grid', 'valuepack-addons'),
                ],
                'default' => 'flex',
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-gallery' => 'display: {{VALUE}};',
                ],
            ]
        );

        // Gallery Gap
        $this->add_control(
            'gallery_gap',
            [
                'label' => __('Gallery Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-gallery' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'gallery_display' => 'flex',
                ],
            ]
        );



        // Gallery Flex Wrap
        $this->add_control(
            'gallery_flex_wrap',
            [
                'label' => __('Flex Wrap', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'nowrap' => __('No Wrap', 'valuepack-addons'),
                    'wrap' => __('Wrap', 'valuepack-addons'),
                    'wrap-reverse' => __('Wrap Reverse', 'valuepack-addons'),
                ],
                'default' => 'wrap',
                'selectors' => [
                    '{{WRAPPER}} .cwp-cpt-single-gallery' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => [
                    'gallery_display' => 'flex',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $meta_source = isset($settings['meta_source']) ? $settings['meta_source'] : 'post';

        $post_type = '';
        $meta_field = '';

        if ($meta_source === 'post') {
            $post_type = $settings['post_type'];

            // Get meta_field based on selected post_type
            if (!empty($post_type) && isset($settings['meta_field_' . $post_type])) {
                $meta_field = $settings['meta_field_' . $post_type];
            }

            // Fallback to generic meta_field if post_type specific one doesn't exist
            if (empty($meta_field) && isset($settings['meta_field'])) {
                $meta_field = $settings['meta_field'];
            }
        } else {
            $meta_field = isset($settings['user_meta_field']) ? $settings['user_meta_field'] : '';
        }

        $show_label = $settings['show_label'] === 'yes';
        $render_field = $settings['render_field'] === 'yes';
        $use_value_as_url = isset($settings['use_value_as_url']) && $settings['use_value_as_url'] === 'yes';
        $link_text_type = isset($settings['link_text_type']) ? $settings['link_text_type'] : 'value';
        $custom_link_text = isset($settings['custom_link_text']) ? $settings['custom_link_text'] : '';
        $link_add_icon_text = isset($settings['link_add_icon_text']) && $settings['link_add_icon_text'] === 'yes';
        $link_icon = isset($settings['link_icon']) ? $settings['link_icon'] : array();
        $link_icon_text_position = isset($settings['link_icon_text_position']) ? $settings['link_icon_text_position'] : 'after';
        $link_open_new_tab = isset($settings['link_open_new_tab']) && $settings['link_open_new_tab'] === 'yes';
        $link_open_in_popup = isset($settings['link_open_in_popup']) && $settings['link_open_in_popup'] === 'yes';
        $use_iframe_for_url = isset($settings['use_iframe_for_url']) && $settings['use_iframe_for_url'] === 'yes';
        $hide_empty_fields = isset($settings['hide_empty_fields']) && $settings['hide_empty_fields'] === 'yes';
        $multi_value_add_icon = isset($settings['multi_value_add_icon']) && $settings['multi_value_add_icon'] === 'yes';
        $multi_value_icon = isset($settings['multi_value_icon']) ? $settings['multi_value_icon'] : array();
        $multi_value_icon_position = isset($settings['multi_value_icon_position']) ? $settings['multi_value_icon_position'] : 'before';

        if (($meta_source === 'post' && (empty($post_type) || empty($meta_field))) || ($meta_source === 'user' && empty($meta_field))) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="vp-cubewp-meta-placeholder">';
                echo '<p>' . esc_html__('Please select a Source and Meta Field to display the value.', 'valuepack-addons') . '</p>';
                echo '</div>';
            }
            return;
        }

        $post_id = 0;
        $user_id = 0;
        $field_value = '';
        $field_value_raw = '';

        if ($meta_source === 'post') {
            // Get post ID
            $post_id = get_the_ID();

            if (cubewp_is_elementor_editing()) {
                $post_id = cubewp_get_elementor_preview_post_id();
            }
        


            // Verify post type matches
            $current_post_type = get_post_type($post_id);
            if ($current_post_type !== $post_type) {
                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    echo '<div class="vp-cubewp-meta-placeholder">';
                    echo '<p>' . sprintf(esc_html__('The selected post (ID: %d) is not of type "%s". Please select a different post.', 'valuepack-addons'), $post_id, $post_type) . '</p>';
                    echo '</div>';
                }
                return;
            }
        } else {
            // User Meta Handling
            $user_source = isset($settings['user_source']) ? $settings['user_source'] : 'current_author';

            switch ($user_source) {
                case 'current_author':
                    if (is_singular()) {
                        $post_id = get_the_ID();
                        $post_obj = get_post($post_id);
                        if ($post_obj) {
                            $user_id = $post_obj->post_author;
                        }
                    } elseif (is_author()) {
                        $user_id = get_queried_object_id();
                    }
                    if (cubewp_is_elementor_editing()) {
                        $user_id = get_current_user_id();
                    }
                    break;
                case 'archive_author':
                    if (is_author()) {
                        $user_id = get_queried_object_id();
                    }
                    break;
                case 'current_user':
                    $user_id = get_current_user_id();
                    break;
                case 'custom':
                    $user_id = isset($settings['user_id_custom']) ? intval($settings['user_id_custom']) : 0;
                    break;
            }

            if (empty($user_id)) {
                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    echo '<div class="vp-cubewp-meta-placeholder">';
                    echo '<p>' . esc_html__('User ID not found.', 'valuepack-addons') . '</p>';
                    echo '</div>';
                }
                return;
            }
        }

        // Retrieval logic
        if ($meta_source === 'post') {
            if ($hide_empty_fields) {
                $check_value = get_post_meta($post_id, $meta_field, true);
                if ($check_value == '' || $check_value == 'No' || $check_value == 'no' || empty($check_value)) {
                    return;
                }
            }

            // Get field options
            $field_options = array();
            if (function_exists('get_field_options')) {
                $field_options = get_field_options($meta_field);
            }
        } else {
            if ($hide_empty_fields) {
                $check_value = get_user_meta($user_id, $meta_field, true);
                if ($check_value == '' || $check_value == 'No' || $check_value == 'no' || empty($check_value)) {
                    return;
                }
            }

            // Get user field options
            $field_options = array();
            if (function_exists('get_user_field_options')) {
                $field_options = get_user_field_options($meta_field);
            }
        }

        $field_label = '';
        if (!empty($field_options) && isset($field_options['label'])) {
            $field_label = $field_options['label'];
        } else {
            $field_label = $meta_field;
        }

        $field_type = isset($field_options['type']) ? $field_options['type'] : '';

        // Check if field is image or gallery
        $is_image_field = in_array($field_type, array('image', 'gallery'), true);

        // Check if field is oembed
        $is_oembed_field = ($field_type === 'oembed');

        // Check if field supports multiple values
        $is_multi = false;
        if (isset($field_options['multi']) && $field_options['multi'] == true) {
            $is_multi = true;
        }
        if (isset($field_options['multiple']) && $field_options['multiple'] == 1) {
            $is_multi = true;
        }
        $is_multi_value_field = $is_multi || in_array($field_type, array('checkbox', 'multi_dropdown', 'multi_select'), true);

        // Get value
        if ($meta_source === 'post') {
            if (function_exists('get_field_value') && !cubewp_is_elementor_editing()) {
                if ($is_image_field) {
                    $field_value = get_field_value($meta_field, false, $post_id);
                    $field_value_raw = $field_value;
                } elseif ($is_oembed_field) {
                    $field_value_raw = get_field_value($meta_field, false, $post_id);
                    $field_value = get_field_value($meta_field, true, $post_id);
                    if (empty($field_value) || $field_value === $field_value_raw) {
                        if (!empty($field_value_raw) && is_string($field_value_raw)) {
                            $oembed_html = wp_oembed_get($field_value_raw);
                            if ($oembed_html) {
                                $field_value = $oembed_html;
                            } else {
                                $field_value = $field_value_raw;
                            }
                        }
                    }
                } else {
                    $field_value = get_field_value($meta_field, $render_field, $post_id);
                    $field_value_raw = get_field_value($meta_field, false, $post_id);
                }
            } else {
                $field_value = get_post_meta($post_id, $meta_field, true);
                $field_value_raw = $field_value;
            }
        } else {
            // User Meta
            if (function_exists('get_user_field_value') && !cubewp_is_elementor_editing()) {
                if ($is_image_field) {
                    $field_value = get_user_field_value($meta_field, false, $user_id);
                    $field_value_raw = $field_value;
                } elseif ($is_oembed_field) {
                    $field_value_raw = get_user_field_value($meta_field, false, $user_id);
                    $field_value = get_user_field_value($meta_field, true, $user_id);
                    if (empty($field_value) || $field_value === $field_value_raw) {
                        if (!empty($field_value_raw) && is_string($field_value_raw)) {
                            $oembed_html = wp_oembed_get($field_value_raw);
                            if ($oembed_html) {
                                $field_value = $oembed_html;
                            } else {
                                $field_value = $field_value_raw;
                            }
                        }
                    }
                } else {
                    $field_value = get_user_field_value($meta_field, $render_field, $user_id);
                    $field_value_raw = get_user_field_value($meta_field, false, $user_id);
                }
            } else {
                $field_value = get_user_meta($user_id, $meta_field, true);
                $field_value_raw = $field_value;
            }
        }

        // Helper function to check if URL is YouTube
        $is_youtube_url = function ($url) {
            if (empty($url) || !is_string($url)) {
                return false;
            }
            $youtube_patterns = array(
                '#(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})#',
                '#youtube\.com/watch\?v=#',
                '#youtu\.be/#',
            );
            foreach ($youtube_patterns as $pattern) {
                if (preg_match($pattern, $url)) {
                    return true;
                }
            }
            return false;
        };

        // Only use oembed if field type is actually oembed (not for URL fields)
        $should_render_as_oembed = false;
        if ($is_oembed_field && !empty($field_value_raw) && is_string($field_value_raw)) {
            // For oembed fields, get rendered oembed HTML
            $oembed_html = wp_oembed_get($field_value_raw);
            if ($oembed_html) {
                $field_value = $oembed_html;
                $should_render_as_oembed = true;
            }
        }

        // Note: We removed automatic YouTube oembed detection for URL fields
        // URL fields will only render as links or iframes (if use_iframe_for_url is enabled)

        // If value is empty, show message in editor
        if (empty($field_value)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="vp-cubewp-meta-placeholder">';
                echo '<p>' . sprintf(esc_html__('No value found for field "%s" on post ID: %d', 'valuepack-addons'), $field_label, $post_id) . '</p>';
                echo '</div>';
            }
            return;
        }

        // Render output
        echo '<div class="vp-cubewp-meta-wrapper">';

        if ($show_label) {
            $extra_text = isset($settings['label_extra_text']) ? $settings['label_extra_text'] : '';
            $extra_text_position = isset($settings['label_extra_text_position']) ? $settings['label_extra_text_position'] : 'after';
            $label_add_icon = isset($settings['label_add_icon']) && $settings['label_add_icon'] === 'yes';
            $label_icon = isset($settings['label_icon']) ? $settings['label_icon'] : array();
            $label_icon_position = isset($settings['label_icon_position']) ? $settings['label_icon_position'] : 'before';

            echo '<div class="vp-cubewp-meta-label">';

            // Build icon HTML if enabled
            $icon_html = '';
            if ($label_add_icon && !empty($label_icon) && !empty($label_icon['value'])) {
                ob_start();
                Icons_Manager::render_icon($label_icon, array('aria-hidden' => 'true'));
                $icon_html = ob_get_clean();
                if (!empty($icon_html)) {
                    $icon_class = 'vp-label-icon vp-icon-' . esc_attr($label_icon_position);
                    $icon_html = '<span class="' . $icon_class . '">' . $icon_html . '</span>';
                }
            }

            // Output icon before label if position is 'before'
            if (!empty($icon_html) && $label_icon_position === 'before') {
                echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            // Output extra text before label if position is 'before'
            if (!empty($extra_text) && $extra_text_position === 'before') {
                echo '<span class="vp-cubewp-meta-label-extra">' . esc_html($extra_text) . '</span>';
            }

            // Output label
            echo esc_html($field_label);

            // Output extra text after label if position is 'after'
            if (!empty($extra_text) && $extra_text_position === 'after') {
                echo '<span class="vp-cubewp-meta-label-extra">' . esc_html($extra_text) . '</span>';
            }

            // Output icon after label if position is 'after'
            if (!empty($icon_html) && $label_icon_position === 'after') {
                echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            echo '</div>';
        }

        // Determine if this is a multi-value field or image field
        $is_multi_value = false;
        $multi_value_class = '';
        $image_class = '';

        if ($is_image_field) {
            // Add image class for styling
            if ($field_type === 'gallery') {
                $image_class = ' vp-image-gallery';
            } else {
                $image_class = ' vp-image-single';
            }
        } elseif ($is_multi_value_field) {
            $is_multi_value = true;
            $multi_value_class = ' vp-multi-value';
        } elseif (is_array($field_value) && count($field_value) > 1 && !$is_image_field) {
            // Check if value is an array with multiple items (but not images)
            $is_multi_value = true;
            $multi_value_class = ' vp-multi-value';
        } elseif (is_string($field_value) && strpos($field_value, ',') !== false && !$is_image_field) {
            // Check if value is a comma-separated string (common for multi-value fields, but not images)
            $is_multi_value = true;
            $multi_value_class = ' vp-multi-value';
        }

        echo '<div class="vp-cubewp-meta-value' . esc_attr($multi_value_class . $image_class) . '">';

        // If render_field is enabled and value is not already rendered HTML, use CubeWP rendering
        // Skip rendering for image fields as we handle them separately
        if ($render_field && !is_array($field_value) && !is_object($field_value) && !$is_multi_value && !$is_image_field) {
            // Check if it's a simple value that needs rendering
            if (!empty($field_type)) {
                // Re-get with rendering enabled
                if ($meta_source === 'post' && function_exists('get_field_value')) {
                    $field_value = get_field_value($meta_field, true, $post_id);
                } elseif ($meta_source === 'user' && function_exists('get_user_field_value')) {
                    $field_value = get_user_field_value($meta_field, true, $user_id);
                }
            }
        }

        // For image fields with render enabled, get the rendered HTML from CubeWP
        if ($is_image_field && $render_field) {
            $rendered_value = '';
            if ($meta_source === 'post' && function_exists('get_field_value')) {
                $rendered_value = get_field_value($meta_field, true, $post_id);
            } elseif ($meta_source === 'user' && function_exists('get_user_field_value')) {
                $rendered_value = get_user_field_value($meta_field, true, $user_id);
            }

            // If CubeWP returned rendered HTML, use it; otherwise use our custom rendering
            if (!empty($rendered_value) && is_string($rendered_value) && (strpos($rendered_value, '<img') !== false || strpos($rendered_value, '<div') !== false)) {
                echo $rendered_value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</div>';
                echo '</div>';
                return;
            }
        }

        // Output the value
        if ($is_image_field) {
            // Handle image and gallery fields
            if ($field_type === 'gallery' && is_array($field_value)) {
                // Gallery field - multiple images
                foreach ($field_value as $image_id) {
                    $image_id = is_numeric($image_id) ? intval($image_id) : 0;
                    if ($image_id > 0) {
                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                        if (empty($image_alt)) {
                            $image_alt = $field_label;
                        }
                        if ($image_url) {
                            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';
                        }
                    }
                }
            } elseif ($field_type === 'image') {
                // Single image field
                $image_id = is_numeric($field_value) ? intval($field_value) : 0;
                if (empty($image_id) && is_string($field_value)) {
                    // Try to get attachment ID from URL
                    $image_id = attachment_url_to_postid($field_value);
                }
                if ($image_id > 0) {
                    $image_url = wp_get_attachment_image_url($image_id, 'full');
                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                    if (empty($image_alt)) {
                        $image_alt = $field_label;
                    }
                    if ($image_url) {
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';
                    }
                } elseif (is_string($field_value) && filter_var($field_value, FILTER_VALIDATE_URL)) {
                    // Direct URL
                    echo '<img src="' . esc_url($field_value) . '" alt="' . esc_attr($field_label) . '" />';
                }
            } else {
                echo $field_value;
            }
        } elseif ($is_multi_value) {
            // Handle multi-value fields
            $values_array = array();

            if (is_array($field_value)) {
                $values_array = $field_value;
            } elseif (is_string($field_value) && strpos($field_value, ',') !== false) {
                // Split comma-separated values
                $values_array = array_map('trim', explode(',', $field_value));
            } else {
                $values_array = array($field_value);
            }

            // Filter out empty values
            $values_array = array_filter($values_array, function ($val) {
                return !empty($val) && $val !== '';
            });

            // Build multi-value icon HTML once if enabled
            $multi_value_icon_html = '';
            if ($multi_value_add_icon && !empty($multi_value_icon) && !empty($multi_value_icon['value'])) {
                ob_start();
                Icons_Manager::render_icon($multi_value_icon, array('aria-hidden' => 'true'));
                $multi_value_icon_html = '<span class="vp-multi-value-item-icon">' . ob_get_clean() . '</span>';
            }
            $has_multi_icon = !empty($multi_value_icon_html);
            $item_icon_class = $has_multi_icon ? ' vp-multi-value-has-icon vp-multi-value-icon-' . esc_attr($multi_value_icon_position) : '';

            if (!empty($values_array)) {
                foreach ($values_array as $value_item) {
                    // Check if value is a YouTube URL or oembed URL
                    $item_is_youtube = false;
                    $item_oembed_html = '';
                    $is_html_content = false;

                    if (is_string($value_item) && !empty($value_item)) {
                        // Check if value is already HTML (like iframe from CubeWP rendering)
                        $is_html_content = (strpos($value_item, '<iframe') !== false || strpos($value_item, '<div') !== false || strpos($value_item, '<img') !== false);

                        // Check if it's a YouTube URL
                        $item_is_youtube = $is_youtube_url($value_item);

                        // Check if it's an oembed field type or YouTube URL
                        if (($is_oembed_field || $item_is_youtube) && !$is_html_content) {
                            $item_oembed_html = wp_oembed_get($value_item);
                        }
                    }

                    // If we have oembed HTML or HTML content, use div wrapper (block-level)
                    // Otherwise use span wrapper (inline)
                    if (!empty($item_oembed_html) || $is_html_content) {
                        echo '<div class="vp-multi-value-item' . $item_icon_class . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                        if ($has_multi_icon && $multi_value_icon_position === 'before') {
                            echo $multi_value_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        if (!empty($item_oembed_html)) {
                            echo $item_oembed_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        } elseif ($is_html_content) {
                            echo $value_item; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        if ($has_multi_icon && $multi_value_icon_position === 'after') {
                            echo $multi_value_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo '</div>';
                    } else {
                        echo '<span class="vp-multi-value-item' . $item_icon_class . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                        if ($has_multi_icon && $multi_value_icon_position === 'before') {
                            echo $multi_value_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo esc_html($value_item);

                        if ($has_multi_icon && $multi_value_icon_position === 'after') {
                            echo $multi_value_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo '</span>';
                    }
                }
            }
        } elseif (is_array($field_value) || is_object($field_value)) {
            // For complex values, try to render them
            if (function_exists('render_taxonomy_value') && isset($field_options['type']) && $field_options['type'] === 'taxonomy') {
                echo render_taxonomy_value($field_value);
            } elseif (function_exists('render_post_value') && isset($field_options['type']) && $field_options['type'] === 'post') {
                echo render_post_value($field_value);
            } elseif (function_exists('render_user_value') && isset($field_options['type']) && $field_options['type'] === 'user') {
                echo render_user_value($field_value);
            } else {
                // Fallback: output as JSON or serialized
                echo '<pre>' . esc_html(print_r($field_value, true)) . '</pre>';
            }
        } elseif ($is_oembed_field || $should_render_as_oembed) {
            // Handle oembed fields or YouTube URLs
            if ($link_open_in_popup) {
                // Show popup trigger button/link
                $popup_id = 'vp-popup-' . uniqid();
                $popup_content = $field_value;

                // Build icon content using Elementor Icons Manager
                $icon_html = '';
                if ($link_add_icon_text && !empty($link_icon) && !empty($link_icon['value'])) {
                    ob_start();
                    Icons_Manager::render_icon($link_icon, array('aria-hidden' => 'true'));
                    $icon_html = ob_get_clean();
                }

                // Determine link text
                $link_text = '';
                if ($link_text_type === 'custom' && !empty($custom_link_text)) {
                    $link_text = esc_html($custom_link_text);
                } else {
                    $link_text = esc_html($field_label);
                }

                echo '<a href="#" class="vp-popup-trigger" data-popup-id="' . esc_attr($popup_id) . '">';

                // Output icon before if position is 'before'
                if ($link_icon_text_position === 'before' && !empty($icon_html)) {
                    echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }

                echo $link_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                // Output icon after if position is 'after'
                if ($link_icon_text_position === 'after' && !empty($icon_html)) {
                    echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }

                echo '</a>';

                // Output popup HTML
                echo '<div id="' . esc_attr($popup_id) . '" class="vp-popup-modal" style="display: none;">';
                echo '<div class="vp-popup-overlay"></div>';
                echo '<div class="vp-popup-content">';
                echo '<span class="vp-popup-close"><i class="fa fa-times"></i></span>';
                echo '<div class="vp-popup-body">';
                echo $popup_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } else {
                // Output oembed directly
                echo $field_value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        } else {
            // Simple value - output directly (already escaped by get_field_value or safe to output)
            $output_value = $field_value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            // If use_value_as_url is enabled, wrap in anchor tag (only for string values)
            if ($use_value_as_url && !empty($field_value) && is_string($field_value)) {
                $link_url_prefix = isset($settings['link_url_prefix']) ? trim($settings['link_url_prefix']) : '';

                // Get URL from field value - add prefix if set, otherwise ensure valid URL format
                if ($link_url_prefix !== '') {
                    $url = $link_url_prefix . trim($field_value);
                } else {
                    $url = trim($field_value);
                    // Add http:// if no protocol is present and it looks like a domain
                    if (!preg_match('#^https?://#i', $url) && !preg_match('#^/#', $url) && !preg_match('#^mailto:#i', $url) && !preg_match('#^tel:#i', $url)) {
                        if (strpos($url, '.') !== false && strpos($url, ' ') === false) {
                            $url = 'http://' . $url;
                        }
                    }
                }

                $url = esc_url($url);

                // Check if should use iframe
                if ($use_iframe_for_url) {
                    // Check if URL is YouTube
                    $url_is_youtube = $is_youtube_url($url);

                    if ($url_is_youtube) {
                        // For YouTube URLs, use oembed instead of plain iframe
                        $oembed_html = wp_oembed_get($url);
                        if ($oembed_html) {
                            $iframe_content = $oembed_html;
                        } else {
                            // Fallback to iframe if oembed fails
                            $iframe_content = '<iframe src="' . esc_url($url) . '" data-src="' . esc_url($url) . '" frameborder="0" allowfullscreen style="width: 100%; min-height: 400px;"></iframe>';
                        }
                    } else {
                        // Regular URL - use iframe
                        $iframe_content = '<iframe src="' . esc_url($url) . '" data-src="' . esc_url($url) . '" frameborder="0" allowfullscreen style="width: 100%; min-height: 400px;"></iframe>';
                    }

                    // Check if should open in popup
                    if ($link_open_in_popup) {
                        // Show popup trigger button/link
                        $popup_id = 'vp-popup-' . uniqid();

                        // Determine link text
                        $link_text = '';
                        if ($link_text_type === 'custom' && !empty($custom_link_text)) {
                            $link_text =  $custom_link_text;
                        } else {
                            $link_text = $field_value;
                        }

                        // Build icon content using Elementor Icons Manager
                        $icon_html = '';
                        if ($link_add_icon_text && !empty($link_icon) && !empty($link_icon['value'])) {
                            ob_start();
                            Icons_Manager::render_icon($link_icon, array('aria-hidden' => 'true'));
                            $icon_html = ob_get_clean();
                        }

                        echo '<a href="#" data-src="' . esc_url($url) . '" class="vp-popup-trigger" data-popup-id="' . esc_attr($popup_id) . '">';

                        // Output icon before if position is 'before'
                        if ($link_icon_text_position === 'before' && !empty($icon_html)) {
                            echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo $link_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                        // Output icon after if position is 'after'
                        if ($link_icon_text_position === 'after' && !empty($icon_html)) {
                            echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo '</a>';

                        // Output popup HTML with iframe/oembed
                        echo '<div id="' . esc_attr($popup_id) . '"   class="vp-popup-modal" style="display: none;">';
                        echo '<div class="vp-popup-overlay"></div>';
                        echo '<div class="vp-popup-content">';
                        echo '<span class="vp-popup-close"><i class="fa fa-times"></i></span>';
                        echo '<div class="vp-popup-body">';
                        echo $iframe_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    } else { 
                        echo $iframe_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                } else {
                    $link_text = '';
                    if ($link_text_type === 'custom' && !empty($custom_link_text)) {
                        $link_text = $custom_link_text;
                    } else {
                        // Use original field value for display text
                        $link_text =  $field_value;
                    }

                    // Build icon content using Elementor Icons Manager
                    $icon_html = '';
                    if ($link_add_icon_text && !empty($link_icon) && !empty($link_icon['value'])) {
                        // Render Elementor Icon
                        ob_start();
                        Icons_Manager::render_icon($link_icon, array('aria-hidden' => 'true'));
                        $icon_html = ob_get_clean();
                    }

                    // Check if should open in popup
                    if ($link_open_in_popup) {
                        // Show popup trigger button/link
                        $popup_id = 'vp-popup-' . uniqid();
                        $popup_content = esc_html($field_value);

                        echo '<a href="#" class="vp-popup-trigger" data-popup-id="' . esc_attr($popup_id) . '">';

                        // Output icon before if position is 'before'
                        if ($link_icon_text_position === 'before' && !empty($icon_html)) {
                            echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo $link_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                        // Output icon after if position is 'after'
                        if ($link_icon_text_position === 'after' && !empty($icon_html)) {
                            echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo '</a>';

                        // Output popup HTML
                        echo '<div id="' . esc_attr($popup_id) . '" class="vp-popup-modal" style="display: none;">';
                        echo '<div class="vp-popup-overlay"></div>';
                        echo '<div class="vp-popup-content">';
                        echo '<span class="vp-popup-close"><i class="fa fa-times"></i></span>';
                        echo '<div class="vp-popup-body">';
                        echo $popup_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        // Build anchor tag
                        $target_attr = $link_open_new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';

                        echo '<a href="' . $url . '"' . $target_attr . '>';

                        // Output icon before if position is 'before'
                        if ($link_icon_text_position === 'before' && !empty($icon_html)) {
                            echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        // Output link text
                        echo $link_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                        // Output icon after if position is 'after'
                        if ($link_icon_text_position === 'after' && !empty($icon_html)) {
                            echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }

                        echo '</a>';
                    }
                }
            } else {
                // Output value normally
                echo $output_value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        echo '</div>';
        echo '</div>';

        // Add popup JavaScript and CSS if popup is used
        static $popup_script_added = false;
        if (($link_open_in_popup || ($is_oembed_field && $link_open_in_popup) || ($should_render_as_oembed && $link_open_in_popup)) && !$popup_script_added) {
            $popup_script_added = true;
?>
        
<?php
        }
    }

    /**
     * Get meta fields for a post type (for AJAX)
     */
    public static function get_meta_fields_for_post_type($post_type)
    {
        $fields = array();

        if (empty($post_type) && empty($settings['meta_source'])) {
            return $fields;
        }

        if (function_exists('get_fields_by_post_type') && isset($post_type) && !empty($post_type)) {
            $fields = get_fields_by_post_type($post_type);
        }

        return $fields;
    }
}