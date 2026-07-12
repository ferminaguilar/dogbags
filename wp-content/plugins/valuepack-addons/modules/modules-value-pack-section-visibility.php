<?php

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;

/**
 * Section Visibility Controls for Elementor Sections
 *
 * When enabled, the section is only shown if the selected HTML tag (e.g. p, h1–h6)
 * exists inside the section. Useful for dynamic content where the section wrapper
 * may render even when inner content is empty.
 */

if (!function_exists('vp_add_section_visibility_controls')) {
    /**
     * Add Section Visibility controls in Advanced tab for Section element only.
     */
    function vp_add_section_visibility_controls($widget, $section_id, $args)
    {
    
        if ('section_custom_css_pro' !== $section_id) {
            return;
        }

        $widget->start_controls_section(
            'vp_section_visibility_section',
            [
                'label' => __('Section Visibility - Value Pack', 'valuepack-addons'),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $widget->add_control(
            'vp_section_visibility_enable',
            [
                'label'        => __('Enable Section Visibility', 'valuepack-addons'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'valuepack-addons'),
                'label_off'    => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $widget->add_control(
            'vp_section_visibility_type',
            [
                'label'   => __('Check by', 'valuepack-addons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'tag',
                'options' => [
                    'tag'         => __('HTML tag', 'valuepack-addons'),
                    'class'       => __('Custom class', 'valuepack-addons'),
                    'multi_class' => __('Multi class (comma separated)', 'valuepack-addons'),
                ],
                'condition' => [
                    'vp_section_visibility_enable' => 'yes',
                ],
            ]
        );

        $tag_options = [
            'p'  => 'p', 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6',
            'div' => 'div', 'span' => 'span', 'a' => 'a', 'ul' => 'ul', 'ol' => 'ol', 'li' => 'li',
            'img' => 'img', 'table' => 'table', 'thead' => 'thead', 'tbody' => 'tbody', 'tr' => 'tr', 'td' => 'td', 'th' => 'th',
            'form' => 'form', 'input' => 'input', 'button' => 'button', 'label' => 'label', 'select' => 'select', 'option' => 'option', 'textarea' => 'textarea',
            'header' => 'header', 'footer' => 'footer', 'nav' => 'nav', 'main' => 'main', 'article' => 'article', 'section' => 'section', 'aside' => 'aside',
            'figure' => 'figure', 'figcaption' => 'figcaption', 'blockquote' => 'blockquote', 'pre' => 'pre', 'code' => 'code',
            'strong' => 'strong', 'em' => 'em', 'b' => 'b', 'i' => 'i', 'u' => 'u', 'br' => 'br',
            'iframe' => 'iframe', 'video' => 'video', 'audio' => 'audio', 'source' => 'source',
            'dl' => 'dl', 'dt' => 'dt', 'dd' => 'dd', 'address' => 'address', 'time' => 'time',
        ];

        $widget->add_control(
            'vp_section_visibility_tag',
            [
                'label'     => __('Required tag', 'valuepack-addons'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'p',
                'options'   => $tag_options,
                'condition' => [
                    'vp_section_visibility_enable' => 'yes',
                    'vp_section_visibility_type'  => 'tag',
                ],
            ]
        );

        $widget->add_control(
            'vp_section_visibility_class',
            [
                'label'       => __('Custom class', 'valuepack-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __('e.g. my-dynamic-content', 'valuepack-addons'),
                'description' => __('Enter class name without dot. Section is visible only if an element with this class exists (or is removed when found on entire page, if "Find in entire page" is on).', 'valuepack-addons'),
                'condition'   => [
                    'vp_section_visibility_enable' => 'yes',
                    'vp_section_visibility_type'  => 'class',
                ],
            ]
        );

        $widget->add_control(
            'vp_section_visibility_multi_class',
            [
                'label'       => __('Custom classes', 'valuepack-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __('e.g. class-one, class-two, class-three', 'valuepack-addons'),
                'description' => __('Enter comma-separated class names with dots. Section is visible when any one class exists (OR logic). If none exist, section is hidden.', 'valuepack-addons'),
                'condition'   => [
                    'vp_section_visibility_enable' => 'yes',
                    'vp_section_visibility_type'  => 'multi_class',
                ],
            ]
        );

      

        $widget->add_control(
            'vp_section_visibility_description',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __('By default: section is hidden when the selected tag or class is not found inside it. With "Find in entire page" ON: section is hidden when the tag or class exists anywhere on the page.', 'valuepack-addons'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'       => [
                    'vp_section_visibility_enable' => 'yes',
                ],
            ]
        );

        $widget->end_controls_section();
    }

    add_action('elementor/element/after_section_end', 'vp_add_section_visibility_controls', 25, 3);
}

if (!function_exists('vp_render_section_visibility')) {
    /**
     * Add data attributes to section wrapper for JS to hide section when tag is missing.
     */
    function vp_render_section_visibility($widget)
    {
    

        $settings = $widget->get_settings_for_display();
        $enable   = !empty($settings['vp_section_visibility_enable']) && $settings['vp_section_visibility_enable'] === 'yes';
        $type     = !empty($settings['vp_section_visibility_type']) ? $settings['vp_section_visibility_type'] : 'tag'; 

        if (!$enable) {
            return;
        }

        if ($type === 'class') {
            $value = !empty($settings['vp_section_visibility_class']) ? trim($settings['vp_section_visibility_class']) : '';
            $value = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $value);
            $value = str_replace(' ', '.', $value);
            if ($value === '') {
                return;
            }
        } elseif ($type === 'multi_class') {
            $raw_value = !empty($settings['vp_section_visibility_multi_class']) ? trim($settings['vp_section_visibility_multi_class']) : '';
            if ($raw_value === '') {
                return;
            }

            $classes = array_filter(array_map('trim', explode(',', $raw_value)));
            $classes = array_map(
                function ($class_name) {
                    $clean = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $class_name);
                    $clean = preg_replace('/\s+/', '', $clean);
                    return $clean;
                },
                $classes
            );
            $classes = array_values(array_unique(array_filter($classes)));

            if (empty($classes)) {
                return;
            }

            $value = implode(',', $classes);
        } else {
            $value = !empty($settings['vp_section_visibility_tag']) ? sanitize_key($settings['vp_section_visibility_tag']) : 'p';
        }

        // In editor, always show the section for preview.
        if (class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::$instance->editor) && method_exists(\Elementor\Plugin::$instance->editor, 'is_edit_mode') && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        $widget->add_render_attribute('_wrapper', 'data-vp-section-visibility', '1');
        $widget->add_render_attribute('_wrapper', 'data-vp-section-visibility-type', in_array($type, ['class', 'multi_class'], true) ? $type : 'tag');
        $widget->add_render_attribute('_wrapper', 'data-vp-section-visibility-value', esc_attr($value)); 
    }

    add_action('elementor/frontend/before_render', 'vp_render_section_visibility', 10);
}