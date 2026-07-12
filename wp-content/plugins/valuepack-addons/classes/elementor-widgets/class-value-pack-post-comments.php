<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;

/**
 * Value Pack Post Comments Widgets.
 *
 * Elementor Widget For Displaying Post Comments By Value Pack.
 *
 * @since 1.0.0
 */
class Value_Pack_Post_Comments extends Value_Pack_Widget_Base
{
    protected $mega_menu_index = 1;

    public function get_name()
    {
        return 'vp_post_comments';
    }

    public function get_title()
    {
        return esc_html__('Post Comments', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-comments vpack-icon';
    }

    public function get_categories()
    {
        return array('value_pack');
    }

    public function get_keywords()
    {
        return ['comments', 'post comments', 'replies', 'feedback', 'valuepack', 'discussion'];
    }

    protected function register_controls()
    {
        // Content Tab - Comments Settings
        $this->start_controls_section(
            'section_comments_settings',
            [
                'label' => esc_html__('Comments Settings', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__('Show Title', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => esc_html__('Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Comments', 'valuepack-addons'),
                'condition' => [
                    'show_title' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h3',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'comments_per_page',
            [
                'label' => esc_html__('Comments Per Page', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => get_option('comments_per_page', 10),
            ]
        );

        $this->add_control(
            'comment_order',
            [
                'label' => esc_html__('Order', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc' => esc_html__('Oldest First', 'valuepack-addons'),
                    'desc' => esc_html__('Newest First', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'thread_comments',
            [
                'label' => esc_html__('Threaded Comments', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => get_option('thread_comments') ? 'yes' : '',
            ]
        );

        $this->add_control(
            'thread_comments_depth',
            [
                'label' => esc_html__('Thread Depth', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => get_option('thread_comments_depth', 3),
                'condition' => [
                    'thread_comments' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_avatars',
            [
                'label' => esc_html__('Show Avatars', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => get_option('show_avatars') ? 'yes' : '',
            ]
        );

        $this->add_control(
            'avatar_size',
            [
                'label' => esc_html__('Avatar Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'condition' => [
                    'show_avatars' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => esc_html__('Show Date', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'date_format',
            [
                'label' => esc_html__('Date Format', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('WordPress Default', 'valuepack-addons'),
                    'F j, Y' => date_i18n('F j, Y'),
                    'Y-m-d' => date_i18n('Y-m-d'),
                    'm/d/Y' => date_i18n('m/d/Y'),
                    'd/m/Y' => date_i18n('d/m/Y'),
                    'relative' => esc_html__('Relative (e.g., 2 days ago)', 'valuepack-addons'),
                    'custom' => esc_html__('Custom', 'valuepack-addons'),
                ],
                'default' => 'default',
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_date_format',
            [
                'label' => esc_html__('Custom Date Format', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'F j, Y',
                'placeholder' => 'F j, Y',
                'condition' => [
                    'show_date' => 'yes',
                    'date_format' => 'custom',
                ],
                'description' => sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    esc_url('https://wordpress.org/support/article/formatting-date-and-time/'),
                    esc_html__('Documentation on date and time formatting.', 'valuepack-addons')
                ),
            ]
        );

        $this->add_control(
            'show_reply_link',
            [
                'label' => esc_html__('Show Reply Link', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'reply_link_text',
            [
                'label' => esc_html__('Reply Link Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Reply', 'valuepack-addons'),
                'condition' => [
                    'show_reply_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_edit_link',
            [
                'label' => esc_html__('Show Edit Link', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'edit_link_text',
            [
                'label' => esc_html__('Edit Link Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Edit', 'valuepack-addons'),
                'condition' => [
                    'show_edit_link' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_navigation',
            [
                'label' => esc_html__('Show Navigation', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'no_comments_text',
            [
                'label' => esc_html__('No Comments Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('No comments yet. Be the first to comment!', 'valuepack-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comments_closed_text',
            [
                'label' => esc_html__('Comments Closed Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Comments are closed.', 'valuepack-addons'),
            ]
        );

        $this->end_controls_section();

        // Content Tab - Moderator Badges
        $this->start_controls_section(
            'section_moderator_badges',
            [
                'label' => esc_html__('Moderator Badges', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'show_moderator_badges',
            [
                'label' => esc_html__('Show Moderator Badges', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'author_badge_text',
            [
                'label' => esc_html__('Author Badge Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Author', 'valuepack-addons'),
                'condition' => [
                    'show_moderator_badges' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'moderator_badge_text',
            [
                'label' => esc_html__('Moderator Badge Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Moderator', 'valuepack-addons'),
                'condition' => [
                    'show_moderator_badges' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'admin_badge_text',
            [
                'label' => esc_html__('Admin Badge Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Admin', 'valuepack-addons'),
                'condition' => [
                    'show_moderator_badges' => 'yes',
                ],
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

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-comments-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .vp-comments-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .vp-comments-wrapper',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Title
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .vp-comments-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__('Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Comment Item
        $this->start_controls_section(
            'section_comment_item_style',
            [
                'label' => esc_html__('Comment Item', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'comment_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-comment-item',
            ]
        );

        $this->add_responsive_control(
            'comment_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-item' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'comment_border',
                'selector' => '{{WRAPPER}} .vp-comment-item',
            ]
        );

        $this->add_responsive_control(
            'comment_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'comment_box_shadow',
                'selector' => '{{WRAPPER}} .vp-comment-item',
            ]
        );

        $this->end_controls_section();

        // Add this in the Style Tab after existing sections
        $this->start_controls_section(
            'section_comment_layout',
            [
                'label' => esc_html__('Comment Layout', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'comment_display',
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
                    '{{WRAPPER}} .vp-comment-inner' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_flex_direction',
            [
                'label' => esc_html__('Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => esc_html__('Row', 'valuepack-addons'),
                    'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                    'column' => esc_html__('Column', 'valuepack-addons'),
                    'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-inner' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'comment_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_flex_wrap',
            [
                'label' => esc_html__('Wrap', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'nowrap',
                'options' => [
                    'nowrap' => esc_html__('No Wrap', 'valuepack-addons'),
                    'wrap' => esc_html__('Wrap', 'valuepack-addons'),
                    'wrap-reverse' => esc_html__('Wrap Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-inner' => 'flex-wrap: {{VALUE}};',
                ],
                'condition' => [
                    'comment_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_justify_content',
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
                    'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-inner' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'comment_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                    'baseline' => esc_html__('Baseline', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-inner' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'comment_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_align_content',
            [
                'label' => esc_html__('Align Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                    'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                    'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-inner' => 'align-content: {{VALUE}};',
                ],
                'condition' => [
                    'comment_display' => ['flex', 'inline-flex'],
                    'comment_flex_wrap!' => 'nowrap',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_gap',
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
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'comment_display' => ['flex', 'inline-flex', 'grid'],
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Avatar
        $this->start_controls_section(
            'section_avatar_style',
            [
                'label' => esc_html__('Avatar', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_avatars' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_size_css',
            [
                'label' => esc_html__('Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-avatar img' => 'min-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .vp-comment-avatar img',
            ]
        );

        $this->add_responsive_control(
            'avatar_border_radius',
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
                    '{{WRAPPER}} .vp-comment-avatar img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'avatar_box_shadow',
                'selector' => '{{WRAPPER}} .vp-comment-avatar img',
            ]
        );

        $this->add_responsive_control(
            'avatar_spacing',
            [
                'label' => esc_html__('Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Add a new section for content wrapper styling
        $this->start_controls_section(
            'section_content_wrapper_style',
            [
                'label' => esc_html__('Content Wrapper', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_flex_grow',
            [
                'label' => esc_html__('Flex Grow', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'flex-grow: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_flex_shrink',
            [
                'label' => esc_html__('Flex Shrink', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'flex-shrink: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_flex_basis',
            [
                'label' => esc_html__('Flex Basis', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'auto',
                'default' => 'auto',
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'flex-basis: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_order',
            [
                'label' => esc_html__('Order', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => -10,
                'max' => 10,
                'step' => 1,
                'default' => 0,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'order: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_align_self',
            [
                'label' => esc_html__('Align Self', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__('Auto', 'valuepack-addons'),
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                    'baseline' => esc_html__('Baseline', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_display',
            [
                'label' => esc_html__('Display', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block' => esc_html__('Block', 'valuepack-addons'),
                    'flex' => esc_html__('Flex', 'valuepack-addons'),
                    'inline-flex' => esc_html__('Inline Flex', 'valuepack-addons'),
                    'grid' => esc_html__('Grid', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'column',
                'options' => [
                    'row' => esc_html__('Row', 'valuepack-addons'),
                    'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                    'column' => esc_html__('Column', 'valuepack-addons'),
                    'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'content_wrapper_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_justify_content',
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
                    'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'content_wrapper_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => esc_html__('Start', 'valuepack-addons'),
                    'flex-end' => esc_html__('End', 'valuepack-addons'),
                    'center' => esc_html__('Center', 'valuepack-addons'),
                    'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                    'baseline' => esc_html__('Baseline', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'content_wrapper_display' => ['flex', 'inline-flex'],
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_gap',
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
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'content_wrapper_display' => ['flex', 'inline-flex', 'grid'],
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_width',
            [
                'label' => esc_html__('Width', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-comment-content-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_wrapper_border',
                'selector' => '{{WRAPPER}} .vp-comment-content-wrapper',
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .vp-comment-content-wrapper',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Comment Author
        $this->start_controls_section(
            'section_author_style',
            [
                'label' => esc_html__('Author', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'author_name_color',
            [
                'label' => esc_html__('Name Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_name_typography',
                'selector' => '{{WRAPPER}} .vp-comment-author-name',
            ]
        );

        $this->add_responsive_control(
            'author_name_spacing',
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
                    '{{WRAPPER}} .vp-comment-author-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Comment Date
        $this->start_controls_section(
            'section_date_style',
            [
                'label' => esc_html__('Date', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .vp-comment-date',
            ]
        );

        $this->add_responsive_control(
            'date_spacing',
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
                    '{{WRAPPER}} .vp-comment-date' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Comment Content
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .vp-comment-content',
            ]
        );

        $this->add_responsive_control(
            'content_spacing',
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
                    '{{WRAPPER}} .vp-comment-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Action Links
        $this->start_controls_section(
            'section_action_links_style',
            [
                'label' => esc_html__('Action Links', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'action_links_typography',
                'selector' => '{{WRAPPER}} .vp-comment-actions a',
            ]
        );

        // Position Controls
        $this->add_control(
            'action_links_position',
            [
                'label' => esc_html__('Action Links Position', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'static',
                'options' => [
                    'static' => esc_html__('Static (Default)', 'valuepack-addons'),
                    'relative' => esc_html__('Relative', 'valuepack-addons'),
                    'absolute' => esc_html__('Absolute', 'valuepack-addons'),
                    'fixed' => esc_html__('Fixed', 'valuepack-addons'),
                    'sticky' => esc_html__('Sticky', 'valuepack-addons'),
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions' => 'position: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'action_links_top',
            [
                'label' => esc_html__('Top', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'action_links_position' => ['absolute', 'fixed', 'sticky'],
                ],
            ]
        );

        $this->add_responsive_control(
            'action_links_right',
            [
                'label' => esc_html__('Right', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'action_links_position' => ['absolute', 'fixed', 'sticky'],
                ],
            ]
        );

        $this->add_responsive_control(
            'action_links_bottom',
            [
                'label' => esc_html__('Bottom', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'action_links_position' => ['absolute', 'fixed', 'sticky'],
                ],
            ]
        );

        $this->add_responsive_control(
            'action_links_left',
            [
                'label' => esc_html__('Left', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'action_links_position' => ['absolute', 'fixed', 'sticky'],
                ],
            ]
        );

        $this->add_responsive_control(
            'action_links_z_index',
            [
                'label' => esc_html__('Z-Index', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => -9999,
                'max' => 9999,
                'step' => 1,
                'default' => 0,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions' => 'z-index: {{VALUE}};',
                ],
                'condition' => [
                    'action_links_position' => ['absolute', 'fixed', 'sticky'],
                ],
            ]
        );

        $this->start_controls_tabs('action_links_tabs');

        $this->start_controls_tab(
            'action_links_normal',
            [
                'label' => esc_html__('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'action_links_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'action_links_hover',
            [
                'label' => esc_html__('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'action_links_hover_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'action_links_spacing',
            [
                'label' => esc_html__('Spacing Between Links', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-actions a + a' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Moderator Badges
        $this->start_controls_section(
            'section_badges_style',
            [
                'label' => esc_html__('Moderator Badges', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_moderator_badges' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_author_color',
            [
                'label' => esc_html__('Author Badge Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge-author' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_author_bg_color',
            [
                'label' => esc_html__('Author Badge Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge-author' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_moderator_color',
            [
                'label' => esc_html__('Moderator Badge Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge-moderator' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'badge_moderator_bg_color',
            [
                'label' => esc_html__('Moderator Badge Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge-moderator' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_admin_color',
            [
                'label' => esc_html__('Admin Badge Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge-admin' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'badge_admin_bg_color',
            [
                'label' => esc_html__('Admin Badge Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge-admin' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'selector' => '{{WRAPPER}} .vp-comment-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_spacing',
            [
                'label' => esc_html__('Spacing', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-badge' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Navigation
        $this->start_controls_section(
            'section_navigation_style',
            [
                'label' => esc_html__('Navigation', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_navigation' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'navigation_typography',
                'selector' => '{{WRAPPER}} .vp-comments-navigation a',
            ]
        );

        $this->start_controls_tabs('navigation_tabs');

        $this->start_controls_tab(
            'navigation_normal',
            [
                'label' => esc_html__('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'navigation_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-navigation a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_background',
            [
                'label' => esc_html__('Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-navigation a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'navigation_hover',
            [
                'label' => esc_html__('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'navigation_hover_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-navigation a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_hover_background',
            [
                'label' => esc_html__('Background', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-navigation a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'navigation_border',
                'selector' => '{{WRAPPER}} .vp-comments-navigation a',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'navigation_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-navigation a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-navigation a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_spacing',
            [
                'label' => esc_html__('Spacing Between Links', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comments-navigation a + a' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - No Comments Message
        $this->start_controls_section(
            'section_no_comments_style',
            [
                'label' => esc_html__('No Comments Message', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'no_comments_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-no-comments' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'no_comments_typography',
                'selector' => '{{WRAPPER}} .vp-no-comments',
            ]
        );

        $this->add_responsive_control(
            'no_comments_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-no-comments' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'no_comments_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-no-comments',
            ]
        );

        $this->add_responsive_control(
            'no_comments_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-no-comments' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $post_id = get_the_ID();

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $post_id = $this->value_pack_get_elementor_preview_post_id();
        }

        // Don't show if comments are closed and there are no comments
        if (!comments_open($post_id) && get_comments_number($post_id) <= 0) {
            if (!empty($settings['comments_closed_text'])) {
                echo '<div class="vp-no-comments">' . esc_html($settings['comments_closed_text']) . '</div>';
            }
            return;
        }

        // Setup comment arguments
        $comment_args = [
            'post_id' => $post_id,
            'status' => 'approve',
            'order' => $settings['comment_order'],
            'number' => $settings['comments_per_page'],
        ];

        // Get comments
        $comments = get_comments($comment_args);
        $comments_count = get_comments_number($post_id);

        // Prepare list arguments
        $list_args = [
            'walker' => new VP_Comment_Walker($settings),
            'max_depth' => ($settings['thread_comments'] === 'yes') ? $settings['thread_comments_depth'] : 1,
            'style' => 'div',
            'callback' => [$this, 'comment_callback'],
            'end-callback' => [$this, 'end_comment_callback'],
            'type' => 'all',
            'page' => get_query_var('cpage') ?: 1,
            'per_page' => $settings['comments_per_page'],
            'reverse_top_level' => ($settings['comment_order'] === 'desc'),
            'reverse_children' => true,
            'format' => 'html5',
            'short_ping' => true,
            'echo' => false,
        ];

?>
        <div class="vp-comments-wrapper">
            <?php if ($settings['show_title'] === 'yes' && !empty($settings['title_text'])) : ?>
                <<?php echo esc_attr($settings['title_tag']); ?> class="vp-comments-title">
                    <?php echo esc_html($settings['title_text']); ?>
                    <?php if ($comments_count > 0) : ?>
                        <span class="vp-comments-count">(<?php echo esc_html($comments_count); ?>)</span>
                    <?php endif; ?>
                </<?php echo esc_attr($settings['title_tag']); ?>>
            <?php endif; ?>

            <?php if ($comments_count > 0) : ?>
                <div class="vp-comments-list">
                    <?php echo wp_list_comments($list_args, $comments); ?>
                </div>

                <?php if ($settings['show_navigation'] === 'yes') : ?>
                    <div class="vp-comments-navigation">
                        <?php
                        paginate_comments_links([
                            'prev_text' => '&laquo; ' . esc_html__('Previous', 'valuepack-addons'),
                            'next_text' => esc_html__('Next', 'valuepack-addons') . ' &raquo;',
                            'mid_size' => 2,
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="vp-no-comments">
                    <?php echo esc_html($settings['no_comments_text']); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php
    }

    
    public function comment_callback($comment, $args, $depth)
    {
        $settings = $this->get_settings_for_display();
        $GLOBALS['comment'] = $comment;
        $comment_id = $comment->comment_ID;
        $comment_author = $comment->comment_author;
        $comment_date = $comment->comment_date;
        $comment_content = $comment->comment_content;
        $post_author_id = get_post_field('post_author', $comment->comment_post_ID);
        $comment_user_id = $comment->user_id;

        // Date format
        if ($settings['date_format'] === 'default') {
            $date_display = get_comment_date('', $comment_id);
        } elseif ($settings['date_format'] === 'relative') {
            $date_display = human_time_diff(strtotime($comment_date), current_time('timestamp')) . ' ' . esc_html__('ago', 'valuepack-addons');
        } elseif ($settings['date_format'] === 'custom') {
            $date_display = get_comment_date($settings['custom_date_format'], $comment_id);
        } else {
            $date_display = get_comment_date($settings['date_format'], $comment_id);
        }

        // Check user roles for badges
        $badges = [];
        if ($settings['show_moderator_badges'] === 'yes') {
            if ($comment_user_id == $post_author_id) {
                $badges[] = [
                    'class' => 'vp-comment-badge-author',
                    'text' => $settings['author_badge_text'],
                ];
            } elseif ($comment_user_id && user_can($comment_user_id, 'moderate_comments')) {
                $badges[] = [
                    'class' => 'vp-comment-badge-moderator',
                    'text' => $settings['moderator_badge_text'],
                ];
            } elseif ($comment_user_id && user_can($comment_user_id, 'administrator')) {
                $badges[] = [
                    'class' => 'vp-comment-badge-admin',
                    'text' => $settings['admin_badge_text'],
                ];
            }
        }

        // Comment classes
        $comment_classes = ['vp-comment-item'];
        if ($depth > 1) {
            $comment_classes[] = 'vp-comment-child';
            $comment_classes[] = 'vp-comment-depth-' . $depth;
        }
        $comment_class_string = implode(' ', $comment_classes);
         ?>

        <div id="comment-<?php echo esc_attr($comment_id); ?>" class="<?php echo esc_attr($comment_class_string); ?>">
            <div class="vp-comment-inner vp-flex-container">
                <?php if ($settings['show_avatars'] === 'yes') : ?>
                    <div class="vp-comment-avatar vp-flex-item">
                        <?php echo get_avatar($comment, $settings['avatar_size']['size']); ?>
                    </div>
                <?php endif; ?>

                <div class="vp-comment-content-wrapper vp-flex-item">
                    <div class="vp-comment-header">
                        <div class="vp-comment-author">
                            <span class="vp-comment-author-name">
                                <?php echo esc_html($comment_author); ?>
                            </span>

                            <?php foreach ($badges as $badge) : ?>
                                <span class="vp-comment-badge <?php echo esc_attr($badge['class']); ?>">
                                    <?php echo esc_html($badge['text']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($settings['show_date'] === 'yes') : ?>
                            <div class="vp-comment-date">
                                <?php echo esc_html($date_display); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="vp-comment-content">
                        <?php comment_text(); ?>
                    </div>

                    <?php if ($settings['show_reply_link'] === 'yes' || $settings['show_edit_link'] === 'yes') : ?>
                        <div class="vp-comment-actions">
                            <?php
                            // Defensive: ensure $comment object exists and has a valid comment_post_ID before calling comments_open or comment_reply_link
                            $comment_post_id = null;
                            if (is_object($comment) && isset($comment->comment_post_ID)) {
                                $comment_post_id = $comment->comment_post_ID;
                            }

                            if (
                                $settings['show_reply_link'] === 'yes'
                                && $comment_post_id
                                && comments_open($comment_post_id)
                            ) : ?>
                                <?php
                                $reply_max_depth = isset($args['max_depth']) ? (int) $args['max_depth'] : 5;
                                comment_reply_link([
                                    'add_below' => 'comment',
                                    'depth' => $depth,
                                    'max_depth' => $reply_max_depth,
                                    'reply_text' => esc_html($settings['reply_link_text']),
                                    'before' => '',
                                    'after' => '',
                                ], $comment, $comment_post_id);
                                ?>
                            <?php endif; ?>

                            <?php if ($settings['show_edit_link'] === 'yes' && !empty($comment_id) && current_user_can('edit_comment', $comment_id)) : ?>
                                <a href="<?php echo esc_url(get_edit_comment_link($comment_id)); ?>" class="vp-comment-edit-link">
                                    <?php echo esc_html($settings['edit_link_text']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
    }

    public function end_comment_callback($comment, $args, $depth)
    {
        ?>
        </div>
<?php
    }

    protected function value_pack_get_elementor_preview_post_id()
    {
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'post_status'    => 'publish',
        );

        $products = get_posts($args);

        if (! empty($products)) {
            return $products[0]; // Return the first product ID
        }
    }
}

// Custom Comment Walker Class
class VP_Comment_Walker extends Walker_Comment
{
    private $widget_settings;

    public function __construct($settings)
    {
        $this->widget_settings = $settings;
    }

    protected function html5_comment($comment, $depth, $args)
    {
        // This is handled by the widget's callback method
    }
}
