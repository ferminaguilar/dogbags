<?php
/**
 * Header Module
 *
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;

// Register header Controls for Custom Post Type
if (!function_exists('value_pack_register_document_header_controls')) {
    function value_pack_register_document_header_controls($element)
    {
       

        $post_id = get_the_ID();
        $template_type = get_post_meta($post_id, 'template_type', true);

        if (get_post_type($post_id) == 'cubewp-tb' && $template_type == 'header') {
            $element->start_controls_section(
                'header_options_section',
                [
                    'label' => __('Header Options - Value Pack', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                ]
            );

            $element->add_control(
                'enable_hover_effect',
                [
                    'label' => __('Enable Hover Effect', 'valuepack-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'valuepack-addons'),
                    'label_off' => __('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $element->add_control(
                'gradient_start_color',
                [
                    'label' => __('Header Hover Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .site-header' => 'background: linear-gradient(to bottom, {{VALUE}}, {{gradient_end_color.VALUE}});',
                    ],
                    'condition' => [
                        'enable_hover_effect' => 'yes',
                    ],
                ]
            );


            $element->add_control(
                'overlay_color',
                [
                    'label' => __('Overlay Elements Color', 'valuepack-addons'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .site-header:hover' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'enable_hover_effect' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'sticky_header',
                [
                    'label' => __('Enable Sticky Header', 'valuepack-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'valuepack-addons'),
                    'label_off' => __('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );
            $element->add_control(
                'enable_sticky_bottom',
                [
                    'label' => __('Sticky on Scroll Up (Bottom Sticky)', 'valuepack-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'valuepack-addons'),
                    'label_off' => __('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'sticky_header' => 'yes',
                    ],
                ]
            );
            $element->add_control(
                'sticky_top_spaces',
                [
                    'label' => __('Sticky Top Space (%)', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['%', 'px'],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 49,
                    ],
                    'condition' => [
                        'sticky_header' => 'yes', 
                    ],
                ]
            );

            $element->end_controls_section();
        }
    }

    add_action('elementor/documents/register_controls', 'value_pack_register_document_header_controls');
}


if (!function_exists('value_pack_add_dynamic_header_css')) {
    function value_pack_add_dynamic_header_css()
    {
        $header_data = value_pack_get_popup_lists('header');
        if (!empty($header_data)) {
            foreach ($header_data as $popup_id => $popup_data) {
                if (value_pack_should_display_popup($popup_data['location'])) {
                    $settings_page = get_post_meta($popup_id, '_elementor_page_settings', true);
                    if (isset($settings_page) && !empty($settings_page)) {
                        $enable_hover_effect = !empty($settings_page['gradient_start_color']) ? $settings_page['enable_hover_effect'] : '';
                        $gradient_start_color = !empty($settings_page['gradient_start_color']) ? $settings_page['gradient_start_color'] : '';
                        $sticky_header = !empty($settings_page['sticky_header']) ? $settings_page['sticky_header'] : '';
                        $enable_sticky_bottom = !empty($settings_page['enable_sticky_bottom']) ? $settings_page['enable_sticky_bottom'] : '';
                       
                        $sticky_top_spaces = !empty($settings_page['sticky_top_spaces']) ? $settings_page['sticky_top_spaces'] : '0%';
                        $overlay_color = !empty($settings_page['overlay_color']) ? $settings_page['overlay_color'] : '';
                        if ($sticky_header == 'yes' && !wp_is_mobile()) {
                            if ($enable_sticky_bottom == 'yes') {
                                $enable_sticky_bottom = 'enable_sticky_bottom';
                            }
                            if (isset($sticky_top_spaces['unit']) && $sticky_top_spaces['unit']) {
                                $sticky_top_spaces = $sticky_top_spaces['size'] . $sticky_top_spaces['unit'];
                            }
                            // Enqueue header styles and scripts 
                            $inline_css = "body .vp-sticky-header.sticky-active,\n";
                            $inline_css .= "body .vp-sticky-header.enable_sticky_bottom:not(.scroll-top),\n";
                            $inline_css .= ".vp-sticky-header.enable_sticky_bottom:not(.scroll-top):not(.sticky-active){\n";
                            $inline_css .= "transform: translateY(var(--sticky-top-spaces));\n";
                            $inline_css .= "margin-top: 0;\n";
                            $inline_css .= "}\n";
                            $inline_css .= ":root {\n";
                            $inline_css .= "--sticky-top-spaces: {$sticky_top_spaces};\n";
                            // No longer define --popup-id as a CSS variable for selectors
                            $inline_css .= "--gradient-start-color: {$gradient_start_color};\n";
                            $inline_css .= "--overlay-color: {$overlay_color};\n";
                            $inline_css .= "}\n";
                            // Dynamically generate selectors with popup ID for elementor[data-elementor-id]
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]>.elementor-element{\n";
                            $inline_css .= "background-image: linear-gradient(180deg, {$gradient_start_color} 50%, #fff0 50%) !important;\n";
                            $inline_css .= "background-size: 100% 200%;\n";
                            $inline_css .= "background-position: bottom center;\n";
                            $inline_css .= "transition:0.4s;\n";
                            $inline_css .= "}\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover > .elementor-element{\n";
                            $inline_css .= "background-position: top center;\n";
                            $inline_css .= "background-size: 100% 200%;\n";
                            $inline_css .= "}\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top)>.elementor-element{\n";
                            $inline_css .= "background-color: {$gradient_start_color} !important;\n";
                            $inline_css .= "}\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) .logo-default,\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:hover .value-pack-sticky-section .logo-default,\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"] .logo-hover,\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover .logo-default {\n";
                            $inline_css .= "display: none !important;\n";
                            $inline_css .= "}\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) .logo-hover,\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover .logo-hover,\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"] .logo-default {\n";
                            $inline_css .= "display: block !important;\n";
                            $inline_css .= "}\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) div:not(.cubewp-mega-menu-item-dropdown div),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) span:not(.cubewp-mega-menu-item-dropdown span),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) select:not(.cubewp-mega-menu-item-dropdown span),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) i:not(.cubewp-mega-menu-item-dropdown i),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) path:not(.cubewp-mega-menu-item-dropdown path),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) p:not(.cubewp-mega-menu-item-dropdown p),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"].vp-sticky-header:not(.scroll-top) .cubewp-mega-menu-item:not(.cubewp-mega-menu-item-dropdown),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover div:not(.cubewp-mega-menu-item-dropdown div),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover span:not(.cubewp-mega-menu-item-dropdown span),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover select:not(.cubewp-mega-menu-item-dropdown span),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover i:not(.cubewp-mega-menu-item-dropdown i),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover path:not(.cubewp-mega-menu-item-dropdown path),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover p:not(.cubewp-mega-menu-item-dropdown p),\n";
                            $inline_css .= ".elementor[data-elementor-id=\"{$popup_id}\"]:not(.enable_sticky_bottom):hover .cubewp-mega-menu-item:not(.cubewp-mega-menu-item-dropdown) {\n";
                            $inline_css .= "color: {$overlay_color} !important;\n";
                            $inline_css .= "fill: {$overlay_color} !important;\n";
                            $inline_css .= "}\n";
                            if (!wp_is_mobile()) { 
                               echo '<style>'.$inline_css.'</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inline CSS is safe.
                            }
                            wp_enqueue_script('valuepack-header-script');
                            wp_localize_script('valuepack-header-script', 'valuepackHeaderData', array(
                                'popupId' => $popup_id,
                                'enableStickyBottom' => $enable_sticky_bottom,
                            ));
                        }
                    }
                }
            }
        }
    }
    add_action('wp_footer', 'value_pack_add_dynamic_header_css');
}