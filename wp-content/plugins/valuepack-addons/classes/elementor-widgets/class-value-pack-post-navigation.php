<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Css_Filter;

/**
 * Value Pack Post Navigation Widget.
 *
 * Elementor Widget For Next/Previous Post Navigation.
 *
 * @since 1.0.0
 */
class Value_Pack_Post_Navigation extends Value_Pack_Widget_Base
{
    public function get_name()
    {
        return 'vp_post_navigation';
    }

    public function get_title()
    {
        return esc_html__('Post Navigation', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-post-navigation vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    public function get_keywords()
    {
        return ['navigation', 'post', 'next', 'previous', 'prev', 'pagination', 'blog'];
    }

    protected function register_controls()
    {
        // Content Tab - Settings
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__('Settings', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'show_both',
            [
                'label' => esc_html__('Show Both Navigation', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_labels',
            [
                'label' => esc_html__('Show Labels', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'prev_label',
            [
                'label' => esc_html__('Previous Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Previous', 'valuepack-addons'),
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => esc_html__('Next Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Next', 'valuepack-addons'),
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_titles',
            [
                'label' => esc_html__('Show Post Titles', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_length',
            [
                'label' => esc_html__('Title Length', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 0,
                'description' => esc_html__('0 for full title', 'valuepack-addons'),
                'condition' => [
                    'show_titles' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_thumbnails',
            [
                'label' => esc_html__('Show Thumbnails', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail_size',
                'default' => 'thumbnail',
                'condition' => [
                    'show_thumbnails' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => esc_html__('Show Arrows', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'prev_arrow',
            [
                'label' => esc_html__('Previous Arrow', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'next_arrow',
            [
                'label' => esc_html__('Next Arrow', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_position',
            [
                'label' => esc_html__('Arrow Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => esc_html__('Before Text', 'valuepack-addons'),
                    'after' => esc_html__('After Text', 'valuepack-addons'),
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'valuepack-addons'),
                    'vertical' => esc_html__('Vertical', 'valuepack-addons'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Justify', 'valuepack-addons'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'layout' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'same_term',
            [
                'label' => esc_html__('Same Category', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__('Navigate within same category/taxonomy', 'valuepack-addons'),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Container
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => esc_html__('Container', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_display',
            [
                'label' => esc_html__('Display', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'valuepack-addons'),
                    'flex' => esc_html__('Flex', 'valuepack-addons'),
                    'inline-flex' => esc_html__('Inline Flex', 'valuepack-addons'),
                    'grid' => esc_html__('Grid', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => esc_html__('Row', 'valuepack-addons'),
                    'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                    'column' => esc_html__('Column', 'valuepack-addons'),
                    'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_flex_wrap',
            [
                'label' => esc_html__('Flex Wrap', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'nowrap',
                'options' => [
                    'nowrap' => esc_html__('No Wrap', 'valuepack-addons'),
                    'wrap' => esc_html__('Wrap', 'valuepack-addons'),
                    'wrap-reverse' => esc_html__('Wrap Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => [
                    'container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'space-between',
                'options' => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                    'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                    'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                    'baseline' => esc_html__('Baseline', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'container_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_gap',
            [
                'label' => esc_html__('Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
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
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'container_display' => ['flex', 'inline-flex', 'grid'],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-post-nav-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .vp-post-nav-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .vp-post-nav-wrapper',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Navigation Items
        $this->start_controls_section(
            'section_nav_items_style',
            [
                'label' => esc_html__('Navigation Items', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'nav_item_display',
            [
                'label' => esc_html__('Display', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex',
                'options' => [
                    'block' => esc_html__('Block', 'valuepack-addons'),
                    'inline-block' => esc_html__('Inline Block', 'valuepack-addons'),
                    'flex' => esc_html__('Flex', 'valuepack-addons'),
                    'inline-flex' => esc_html__('Inline Flex', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_item_flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => esc_html__('Row', 'valuepack-addons'),
                    'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                    'column' => esc_html__('Column', 'valuepack-addons'),
                    'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'nav_item_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_item_align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                    'baseline' => esc_html__('Baseline', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'nav_item_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_item_justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                    'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'nav_item_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_item_gap',
            [
                'label' => esc_html__('Gap', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
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
                    '{{WRAPPER}} .vp-post-nav-link' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'nav_item_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_item_width',
            [
                'label' => esc_html__('Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_item_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_item_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('nav_item_tabs');

        $this->start_controls_tab(
            'nav_item_normal',
            [
                'label' => esc_html__('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'nav_item_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-post-nav-link',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_item_border',
                'selector' => '{{WRAPPER}} .vp-post-nav-link',
            ]
        );

        $this->add_responsive_control(
            'nav_item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_item_box_shadow',
                'selector' => '{{WRAPPER}} .vp-post-nav-link',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'nav_item_hover',
            [
                'label' => esc_html__('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'nav_item_background_hover',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-post-nav-link:hover',
            ]
        );

        $this->add_control(
            'nav_item_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'nav_item_border_border!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'nav_item_box_shadow_hover',
                'selector' => '{{WRAPPER}} .vp-post-nav-link:hover',
            ]
        );

        $this->add_control(
            'nav_item_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->add_control(
            'label_color_hover',
            [
                'label' => esc_html__('Label Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'nav_item_order_prev',
            [
                'label' => esc_html__('Previous Item Order', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => -10,
                'max' => 10,
                'step' => 1,
                'default' => 0,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-prev' => 'order: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'nav_item_order_next',
            [
                'label' => esc_html__('Next Item Order', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => -10,
                'max' => 10,
                'step' => 1,
                'default' => 0,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-next' => 'order: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Labels
        $this->start_controls_section(
            'section_labels_style',
            [
                'label' => esc_html__('Labels', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'labels_typography',
                'selector' => '{{WRAPPER}} .vp-post-nav-label',
            ]
        );

        $this->add_control(
            'labels_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'labels_text_shadow',
                'selector' => '{{WRAPPER}} .vp-post-nav-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'labels_text_stroke',
                'selector' => '{{WRAPPER}} .vp-post-nav-label',
            ]
        );

        $this->add_responsive_control(
            'labels_spacing',
            [
                'label' => esc_html__('Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'prev_label_color',
            [
                'label' => esc_html__('Previous Label Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-prev .vp-post-nav-label' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'next_label_color',
            [
                'label' => esc_html__('Next Label Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-next .vp-post-nav-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Titles
        $this->start_controls_section(
            'section_titles_style',
            [
                'label' => esc_html__('Titles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_titles' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'titles_typography',
                'selector' => '{{WRAPPER}} .vp-post-nav-title',
            ]
        );

        $this->start_controls_tabs('titles_tabs');

        $this->start_controls_tab(
            'titles_normal',
            [
                'label' => esc_html__('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'titles_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'titles_text_shadow',
                'selector' => '{{WRAPPER}} .vp-post-nav-title',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'titles_hover',
            [
                'label' => esc_html__('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'titles_color_hover',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'titles_text_shadow_hover',
                'selector' => '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-title',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'titles_spacing',
            [
                'label' => esc_html__('Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'titles_max_width',
            [
                'label' => esc_html__('Max Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-title' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'prev_title_color',
            [
                'label' => esc_html__('Previous Title Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-prev .vp-post-nav-title' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'next_title_color',
            [
                'label' => esc_html__('Next Title Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-next .vp-post-nav-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Arrows
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label' => esc_html__('Arrows', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label' => esc_html__('Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-post-nav-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('arrows_tabs');

        $this->start_controls_tab(
            'arrows_normal',
            [
                'label' => esc_html__('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'arrows_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-post-nav-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-post-nav-icon',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'selector' => '{{WRAPPER}} .vp-post-nav-icon',
            ]
        );

        $this->add_responsive_control(
            'arrows_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-icon' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'arrows_hover',
            [
                'label' => esc_html__('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'arrows_background_hover',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-icon',
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'arrows_border_border!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow_hover',
                'selector' => '{{WRAPPER}} .vp-post-nav-link:hover .vp-post-nav-icon',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'prev_arrow_color',
            [
                'label' => esc_html__('Previous Arrow Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-prev .vp-post-nav-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-post-nav-prev .vp-post-nav-icon svg' => 'fill: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'next_arrow_color',
            [
                'label' => esc_html__('Next Arrow Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-next .vp-post-nav-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-post-nav-next .vp-post-nav-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Thumbnails
        $this->start_controls_section(
            'section_thumbnails_style',
            [
                'label' => esc_html__('Thumbnails', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_thumbnails' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_width',
            [
                'label' => esc_html__('Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_height',
            [
                'label' => esc_html__('Height', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'thumbnail_border',
                'selector' => '{{WRAPPER}} .vp-post-nav-thumbnail',
            ]
        );

        $this->add_responsive_control(
            'thumbnail_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-thumbnail' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'thumbnail_box_shadow',
                'selector' => '{{WRAPPER}} .vp-post-nav-thumbnail',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'thumbnail_css_filters',
                'selector' => '{{WRAPPER}} .vp-post-nav-thumbnail',
            ]
        );

        $this->add_responsive_control(
            'thumbnail_spacing',
            [
                'label' => esc_html__('Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-post-nav-thumbnail' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'valuepack-addons'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }
        $settings = $this->get_settings_for_display();
        $prev_post = get_previous_post($settings['same_term'] === 'yes');
        $next_post = get_next_post($settings['same_term'] === 'yes');

        if (!$prev_post && !$next_post) {
            return;
        }

        $wrapper_classes = ['vp-post-nav-wrapper'];
        if ($settings['layout'] === 'vertical') {
            $wrapper_classes[] = 'vp-post-nav-vertical';
        }

?>
        <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
            <?php if ($settings['show_both'] === 'yes' || !$next_post) : ?>
                <?php $this->render_nav_item($prev_post, 'prev', $settings); ?>
            <?php endif; ?>

            <?php if ($settings['show_both'] === 'yes' || !$prev_post) : ?>
                <?php $this->render_nav_item($next_post, 'next', $settings); ?>
            <?php endif; ?>
        </div>
    <?php
    }

    private function render_nav_item($post, $type, $settings)
    {
        if (!$post) {
            return;
        }

        $post_title = get_the_title($post);
        if ($settings['title_length'] > 0 && strlen($post_title) > $settings['title_length']) {
            $post_title = substr($post_title, 0, $settings['title_length']) . '...';
        }

        $link_classes = ['vp-post-nav-link', 'vp-post-nav-' . $type];
        if ($settings['thumbnail_hover_animation']) {
            $link_classes[] = 'elementor-animation-' . $settings['thumbnail_hover_animation'];
        }

    ?>
        <a href="<?php echo esc_url(get_permalink($post)); ?>" class="<?php echo esc_attr(implode(' ', $link_classes)); ?>">
                <?php if ($settings['show_arrows'] === 'yes' && $type === 'prev') : ?>
                <div class="vp-post-nav-icon">
                    <?php
                        \Elementor\Icons_Manager::render_icon($settings['prev_arrow'], ['aria-hidden' => 'true']);
                    ?>
                </div>
            <?php endif; ?>
        <?php if ($settings['show_thumbnails'] === 'yes' && has_post_thumbnail($post)) : ?>
                <div class="vp-post-nav-thumbnail">
                    <?php echo get_the_post_thumbnail($post, $settings['thumbnail_size_size']); ?>
                </div>
            <?php endif; ?>

            <div class="vp-post-nav-content">
                <?php if ($settings['show_labels'] === 'yes') : ?>
                    <div class="vp-post-nav-label">
                        <?php echo esc_html($type === 'prev' ? $settings['prev_label'] : $settings['next_label']); ?>
                    </div>
                <?php endif; ?>

                <?php if ($settings['show_titles'] === 'yes') : ?>
                    <div class="vp-post-nav-title">
                        <?php echo esc_html($post_title); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($settings['show_arrows'] === 'yes' && $type === 'next') : ?>
                <div class="vp-post-nav-icon">
                    <?php
                        \Elementor\Icons_Manager::render_icon($settings['next_arrow'], ['aria-hidden' => 'true']);
                    ?>
                </div>
            <?php endif; ?>
        </a>
<?php
    }

    protected function content_template()
    {
        ?>
        <#
        var wrapperClasses = ['vp-post-nav-wrapper'];
        if (settings.layout === 'vertical') {
            wrapperClasses.push('vp-post-nav-vertical');
        }
        #>
        
        <div class="{{ wrapperClasses.join(' ') }}">
            <# if (settings.show_both === 'yes') { #>
                <a href="#" class="vp-post-nav-link vp-post-nav-prev">
                    <# if (settings.show_thumbnails === 'yes') { #>
                        <div class="vp-post-nav-thumbnail">
                            <img src="<?php echo esc_url(plugins_url('placeholder.jpg', __FILE__)); ?>" alt="Thumbnail">
                        </div>
                    <# } #>
                    
                    <# if (settings.show_arrows === 'yes' && settings.arrow_position === 'before') { #>
                        <div class="vp-post-nav-icon">
                            <i class="{{ settings.prev_arrow.value }}"></i>
                        </div>
                    <# } #>
                    
                    <div class="vp-post-nav-content">
                        <# if (settings.show_labels === 'yes') { #>
                            <div class="vp-post-nav-label">{{{ settings.prev_label }}}</div>
                        <# } #>
                        
                        <# if (settings.show_titles === 'yes') { #>
                            <div class="vp-post-nav-title">Previous Post Title Example</div>
                        <# } #>
                    </div>
                    
                    <# if (settings.show_arrows === 'yes' && settings.arrow_position === 'after') { #>
                        <div class="vp-post-nav-icon">
                            <i class="{{ settings.prev_arrow.value }}"></i>
                        </div>
                    <# } #>
                </a>
                
                <a href="#" class="vp-post-nav-link vp-post-nav-next">
                    <# if (settings.show_thumbnails === 'yes') { #>
                        <div class="vp-post-nav-thumbnail">
                            <img src="<?php echo esc_url(plugins_url('placeholder.jpg', __FILE__)); ?>" alt="Thumbnail">
                        </div>
                    <# } #>
                    
                    <# if (settings.show_arrows === 'yes' && settings.arrow_position === 'before') { #>
                        <div class="vp-post-nav-icon">
                            <i class="{{ settings.next_arrow.value }}"></i>
                        </div>
                    <# } #>
                    
                    <div class="vp-post-nav-content">
                        <# if (settings.show_labels === 'yes') { #>
                            <div class="vp-post-nav-label">{{{ settings.next_label }}}</div>
                        <# } #>
                        
                        <# if (settings.show_titles === 'yes') { #>
                            <div class="vp-post-nav-title">Next Post Title Example</div>
                        <# } #>
                    </div>
                    
                    <# if (settings.show_arrows === 'yes' && settings.arrow_position === 'after') { #>
                        <div class="vp-post-nav-icon">
                            <i class="{{ settings.next_arrow.value }}"></i>
                        </div>
                    <# } #>
                </a>
            <# } #>
        </div>
        <?php
    }
}
