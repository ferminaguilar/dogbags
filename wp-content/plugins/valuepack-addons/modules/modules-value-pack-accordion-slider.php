<?php

/**
 * Value Pack Accordion Slides for Elementor
 * 
 * @package     ValuePackAddons
 * 
 * Adds accordion slides functionality to Elementor's nested accordion widget.
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

if (!function_exists('value_pack_accordion_slides')) {
    /**
     * Adds controls for accordion slides to the nested accordion widget
     * 
     * @param \Elementor\Widget_Base $element The widget instance
     * @param string $section_id The section ID being modified
     * @param array $args Additional arguments
     */
    function value_pack_accordion_slides($element, $section_id, $args)
    {
        // Verify we're working with the correct widget and section
        if ('nested-accordion' !== $element->get_name() || 'section_interactions' !== $section_id) {
            return;
        }

        $element->start_controls_section(
            'value_pack_accordion_slides_title',
            [
                'label' => '<span class="vp-elementor-section-title">VP</span>' . esc_html__(' Accordion Slides', 'valuepack-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $element->add_control(
            'enable_accordion_slides',
            [
                'label' => esc_html__('Enable Accordion Slides', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__(
                    'Enable this option to use Accordion Slides for displaying slide images in a dynamic and interactive way.',
                    'valuepack-addons'
                ),
            ]
        );
        // new issue fixes
        $element->add_control(
            'hide_line_border',
            [
                'label' => __('Animated Border Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .value-pack-active-slide-line::after,{{WRAPPER}} .value-pack-active-slide::after' => '{{VALUE}} !important;',
                ],
                'selectors_dictionary' => [
                    'yes' => 'display: inline-flex',
                    '' => 'display: none',
                ],
            ]
        );
        $element->add_control(
            'accordion_line_color',
            [
                'label' => __('Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .value-pack-active-slide-line::after,{{WRAPPER}} .value-pack-active-slide::after' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'hide_line_border' => 'yes',
                ],
            ]
        );
        // new issue fixes
        $element->end_controls_section();
    }
    add_action('elementor/element/after_section_end', 'value_pack_accordion_slides', 10, 3);
}


if (!function_exists('value_pack_render_accordion_slides')) {
    /**
     * Adds rendering attributes for accordion slides functionality
     * 
     * @param \Elementor\Widget_Base $widget The widget instance being rendered
     */
    function value_pack_render_accordion_slides($widget)
    {
        // Only proceed for nested accordion widgets
        if ('nested-accordion' !== $widget->get_name()) {
            return;
        }

        $settings = $widget->get_settings_for_display();

        // Check if accordion slides are enabled
        if (!empty($settings['enable_accordion_slides']) && 'yes' === $settings['enable_accordion_slides']) {
            $widget->add_render_attribute(
                '_wrapper',
                [
                    'class' => esc_attr('value-pack-slide-accordions'),
                ]
            );
        }
    }
    add_action('elementor/frontend/before_render', 'value_pack_render_accordion_slides', 10, 1);
}
