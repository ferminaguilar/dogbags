<?php

/**
 * Value Pack Post Reactions Elementor Widget.
 *
 * @package valuepack-addons/classes/elementor-widgets
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Only load if Elementor is available
if (!class_exists('\Elementor\Widget_Base')) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

/**
 * Value Pack Post Reactions Widget.
 *
 * @class Value_Pack_Post_Reactions_Widget
 */
class Value_Pack_Post_Reactions_Widget extends Widget_Base
{

    /**
     * Get widget name
     *
     * @return string
     * @since  1.0.0
     */
    public function get_name()
    {
        return 'value-pack-post-reactions';
    }

    /**
     * Get widget title
     *
     * @return string
     * @since  1.0.0
     */
    public function get_title()
    {
        return esc_html__('Post Reactions', 'valuepack-addons');
    }

    /**
     * Get widget icon
     *
     * @return string
     * @since  1.0.0
     */
    public function get_icon()
    {
        return 'eicon-heart';
    }

    /**
     * Get widget categories
     *
     * @return array
     * @since  1.0.0
     */
    public function get_categories()
    {
        return array('value_pack');
    }

    /**
     * Get widget keywords
     *
     * @return array
     * @since  1.0.0
     */
    public function get_keywords()
    {
        return array('reactions', 'likes', 'post', 'valuepack', 'addons');
    }

    /**
     * Register controls
     *
     * @since  1.0.0
     */
    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Reactions', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'reaction_type',
            [
                'label' => esc_html__('Reaction Type', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'like',
                'placeholder' => esc_html__('e.g., interesting, lol, love', 'valuepack-addons'),
                'description' => esc_html__('Unique identifier for this reaction (lowercase, no spaces)', 'valuepack-addons'),
            ]
        );

        $repeater->add_control(
            'reaction_label',
            [
                'label' => esc_html__('Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Like', 'valuepack-addons'),
                'placeholder' => esc_html__('e.g., Interesting, LOL, Love', 'valuepack-addons'),
            ]
        );

        $repeater->add_control(
            'reaction_icon',
            [
                'label' => esc_html__('Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-thumbs-up',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'reaction_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0073aa',
            ]
        );

        $repeater->add_control(
            'reaction_tooltip',
            [
                'label' => esc_html__('Already Reacted Tooltip', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('You have already reacted to this post', 'valuepack-addons'),
                'placeholder' => esc_html__('Message shown when user tries to react again', 'valuepack-addons'),
            ]
        );

        // Styling for each reaction type
        $repeater->add_control(
            'reaction_style_heading',
            [
                'label' => esc_html__('Styling', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'reaction_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reaction_text_typography',
                'label' => esc_html__('Text Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-label',
            ]
        );

        $repeater->add_control(
            'reaction_icon_color',
            [
                'label' => esc_html__('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'reaction_icon_size',
            [
                'label' => esc_html__('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'reaction_icon_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'reaction_count_color',
            [
                'label' => esc_html__('Count Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'reaction_count_bg_color',
            [
                'label' => esc_html__('Count Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-count' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'reaction_count_padding',
            [
                'label' => esc_html__('Count Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'reaction_count_border_radius',
            [
                'label' => esc_html__('Count Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

   

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reaction_count_typography',
                'label' => esc_html__('Count Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-count',
            ]
        );

        $repeater->add_responsive_control(
            'reaction_count_spacing',
            [
                'label' => esc_html__('Count Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-count' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'reaction_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'reaction_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'reaction_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button',
            ]
        );

        $repeater->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'reaction_border',
                'label' => esc_html__('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button',
            ]
        );

        $repeater->add_control(
            'reaction_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'reaction_box_shadow',
                'label' => esc_html__('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button',
            ]
        );

        // Hover State
        $repeater->add_control(
            'reaction_hover_heading',
            [
                'label' => esc_html__('Hover State', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'reaction_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button:hover .cwp-reaction-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'reaction_hover_icon_color',
            [
                'label' => esc_html__('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button:hover .cwp-reaction-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button:hover .cwp-reaction-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'reaction_hover_count_color',
            [
                'label' => esc_html__('Count Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button:hover .cwp-reaction-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'reaction_hover_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button:hover',
            ]
        );

        $repeater->add_control(
            'reaction_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .cwp-reaction-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        // Active/Reacted State
        $repeater->add_control(
            'reaction_active_heading',
            [
                'label' => esc_html__('Active/Reacted State', 'valuepack-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'reaction_active_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.cwp-reaction-active .cwp-reaction-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'reaction_active_icon_color',
            [
                'label' => esc_html__('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.cwp-reaction-active .cwp-reaction-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}.cwp-reaction-active .cwp-reaction-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'reaction_active_count_color',
            [
                'label' => esc_html__('Count Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.cwp-reaction-active .cwp-reaction-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'reaction_active_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.cwp-reaction-active .cwp-reaction-button',
            ]
        );

        $repeater->add_control(
            'reaction_active_border_color',
            [
                'label' => esc_html__('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.cwp-reaction-active .cwp-reaction-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'reactions',
            [
                'label' => esc_html__('Reactions', 'valuepack-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'reaction_type' => 'interesting',
                        'reaction_label' => esc_html__('Interesting', 'valuepack-addons'),
                        'reaction_icon' => [
                            'value' => 'fas fa-lightbulb',
                            'library' => 'fa-solid',
                        ],
                        'reaction_color' => '#ff9800',
                        'reaction_tooltip' => esc_html__('You have already marked this as interesting', 'valuepack-addons'),
                    ],
                    [
                        'reaction_type' => 'lol',
                        'reaction_label' => esc_html__('LOL', 'valuepack-addons'),
                        'reaction_icon' => [
                            'value' => 'fas fa-laugh',
                            'library' => 'fa-solid',
                        ],
                        'reaction_color' => '#ffc107',
                        'reaction_tooltip' => esc_html__('You have already marked this as funny', 'valuepack-addons'),
                    ],
                    [
                        'reaction_type' => 'love',
                        'reaction_label' => esc_html__('Love', 'valuepack-addons'),
                        'reaction_icon' => [
                            'value' => 'fas fa-heart',
                            'library' => 'fa-solid',
                        ],
                        'reaction_color' => '#e91e63',
                        'reaction_tooltip' => esc_html__('You have already loved this post', 'valuepack-addons'),
                    ],
                ],
                'title_field' => '{{{ reaction_label }}}',
            ]
        );

        $this->add_control(
            'post_id',
            [
                'label' => esc_html__('Post ID', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Leave empty to use current post', 'valuepack-addons'),
                'description' => esc_html__('Specific post ID. Leave empty to automatically detect from current post.', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => esc_html__('Show Count', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section - Container
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => esc_html__('Container', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__('Layout Type', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'flex' => esc_html__('Flex', 'valuepack-addons'),
                    'grid' => esc_html__('Grid', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => esc_html__('Row', 'valuepack-addons'),
                    'column' => esc_html__('Column', 'valuepack-addons'),
                    'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                    'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                ],
                'condition' => [
                    'layout_type' => 'flex',
                ],
            ]
        );

        $this->add_control(
            'flex_justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                    'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                    'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
                ],
                'condition' => [
                    'layout_type' => 'flex',
                ],
            ]
        );

        $this->add_control(
            'flex_align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'baseline' => esc_html__('Baseline', 'valuepack-addons'),
                    'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                ],
                'condition' => [
                    'layout_type' => 'flex',
                ],
            ]
        );

        $this->add_control(
            'flex_wrap',
            [
                'label' => esc_html__('Flex Wrap', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'nowrap',
                'options' => [
                    'nowrap' => esc_html__('No Wrap', 'valuepack-addons'),
                    'wrap' => esc_html__('Wrap', 'valuepack-addons'),
                    'wrap-reverse' => esc_html__('Wrap Reverse', 'valuepack-addons'),
                ],
                'condition' => [
                    'layout_type' => 'flex',
                ],
            ]
        );

        $this->add_control(
            'grid_columns',
            [
                'label' => esc_html__('Columns', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 12,
                'condition' => [
                    'layout_type' => 'grid',
                ],
            ]
        );

        $this->add_control(
            'grid_gap',
            [
                'label' => esc_html__('Grid Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'layout_type' => 'grid',
                ],
            ]
        );

        $this->add_control(
            'gap',
            [
                'label' => esc_html__('Gap Between Items', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cwp-post-reactions-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cwp-post-reactions-container',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-post-reactions-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-post-reactions-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => esc_html__('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cwp-post-reactions-container',
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-post-reactions-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => esc_html__('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .cwp-post-reactions-container',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     *
     * @since  1.0.0
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $reactions = isset($settings['reactions']) ? $settings['reactions'] : array();
        $post_id = isset($settings['post_id']) ? intval($settings['post_id']) : 0;
        $show_count = isset($settings['show_count']) && $settings['show_count'] === 'yes';
        $layout_type = isset($settings['layout_type']) ? $settings['layout_type'] : 'flex';

        // Auto-detect post ID if not provided
        if (empty($post_id)) {
            global $post;
            if (isset($post)) {
                $post_id = $post->ID;
            } else {
                $post_id = get_queried_object_id();
            }
        }

        if (empty($post_id) || empty($reactions)) {
            return;
        }

      
        // Get current reactions
        $reactions_instance = Value_Pack_Post_Reactions::instance();
        $current_reactions = $reactions_instance->get_reactions($post_id);
        $user_reacted_types = $reactions_instance->get_user_reacted_types($post_id);

        // Build container styles
        $container_styles = array();
        if ($layout_type === 'flex') {
            $container_styles[] = 'display: flex;';
            $container_styles[] = 'flex-direction: ' . esc_attr($settings['flex_direction']) . ';';
            $container_styles[] = 'justify-content: ' . esc_attr($settings['flex_justify_content']) . ';';
            $container_styles[] = 'align-items: ' . esc_attr($settings['flex_align_items']) . ';';
            $container_styles[] = 'flex-wrap: ' . esc_attr($settings['flex_wrap']) . ';';
        } else {
            $container_styles[] = 'display: grid;';
            $container_styles[] = 'grid-template-columns: repeat(' . intval($settings['grid_columns']) . ', 1fr);';
            if (isset($settings['grid_gap']['size'])) {
                $container_styles[] = 'gap: ' . esc_attr($settings['grid_gap']['size']) . esc_attr($settings['grid_gap']['unit']) . ';';
            }
        }

        // Generate output
        $output = '<div class="cwp-post-reactions-container elementor-widget-container" 
                      data-post-id="' . esc_attr($post_id) . '"
                      style="' . implode(' ', $container_styles) . '">';
        
        foreach ($reactions as $index => $reaction) {
            $reaction_type = isset($reaction['reaction_type']) ? sanitize_text_field($reaction['reaction_type']) : '';
            $reaction_label = isset($reaction['reaction_label']) ? sanitize_text_field($reaction['reaction_label']) : '';
            $reaction_color = isset($reaction['reaction_color']) ? sanitize_hex_color($reaction['reaction_color']) : '#0073aa';
            $reaction_tooltip = isset($reaction['reaction_tooltip']) ? esc_attr($reaction['reaction_tooltip']) : '';
            
            if (empty($reaction_type)) {
                continue;
            }

            $count = isset($current_reactions[$reaction_type]) ? intval($current_reactions[$reaction_type]) : 0;
            $is_reacted = in_array($reaction_type, $user_reacted_types);
            $icon_html = '';

            // Render icon
            if (isset($reaction['reaction_icon']) && !empty($reaction['reaction_icon']['value'])) {
                if (isset($reaction['reaction_icon']['library']) && $reaction['reaction_icon']['library'] === 'svg') {
                    $icon_html = '<span class="cwp-reaction-icon-svg">' . $reaction['reaction_icon']['value'] . '</span>';
                } else {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon($reaction['reaction_icon'], ['aria-hidden' => 'true']);
                    $icon_html = ob_get_clean();
                    if (empty($icon_html)) {
                        $icon_html = '<i class="' . esc_attr($reaction['reaction_icon']['value']) . '"></i>';
                    }
                }
            }

            $output .= '<div class="cwp-post-reaction-item elementor-repeater-item-' . esc_attr($reaction['_id']) . ($is_reacted ? ' cwp-reaction-active' : '') . '" 
                           data-reaction-type="' . esc_attr($reaction_type) . '"
                           data-reaction-tooltip="' . $reaction_tooltip . '"
                           style="--reaction-color: ' . esc_attr($reaction_color) . '">';
            
            $output .= '<button type="button" class="cwp-reaction-button' . ($is_reacted ? ' cwp-reaction-disabled' : '') . '" 
                              data-post-id="' . esc_attr($post_id) . '"
                              data-reaction-type="' . esc_attr($reaction_type) . '"
                              data-reaction-tooltip="' . esc_attr($reaction_tooltip) . '"
                              ' . ($is_reacted ? 'disabled="disabled" aria-disabled="true"' : '') . '
                              aria-label="' . esc_attr($reaction_label) . '">';
            
            if (!empty($icon_html)) {
                $output .= '<span class="cwp-reaction-icon">' . $icon_html . '</span>';
            }
            $output .= '<span class="cwp-reaction-label">' . esc_html($reaction_label) . '</span>';
            
            if ($show_count) {
                $output .= '<span class="cwp-reaction-count">' . esc_html($count) . '</span>';
            }
            
            $output .= '</button>';
            $output .= '</div>';
        }
        
        $output .= '</div>';

        echo $output;
    }
}

