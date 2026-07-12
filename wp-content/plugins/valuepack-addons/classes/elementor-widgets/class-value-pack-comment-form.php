<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

/**
 * Value Pack Comment Form Widgets.
 *
 * Elementor Widget For Comment Form By Value Pack.
 *
 * @since 1.0.0
 */
class Value_Pack_Comment_Form extends Value_Pack_Widget_Base
{
    protected $mega_menu_index = 1;

    public function get_name()
    {
        return 'vp_comment_form';
    }

    public function get_title()
    {
        return esc_html__('Comment Form', 'valuepack-addons');
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
        return ['comment', 'form', 'reply', 'feedback', 'valuepack'];
    }

    protected function register_controls()
    {
        // Content Tab - Form Settings
        $this->start_controls_section(
            'section_form_settings',
            [
                'label' => esc_html__('Form Settings', 'valuepack-addons'),
            ]
        );

        //post type
        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Post Type', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => self::get_post_types(),
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
                'default' => esc_html__('Leave a Reply', 'valuepack-addons'),
                'condition' => [
                    'show_title' => 'yes',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'logged_in_message',
            [
                'label' => esc_html__('Logged In Message', 'valuepack-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => sprintf(
                    /* translators: 1: User display name, 2: Log out URL */
                    esc_html__('Logged in as %1$s. %2$s', 'valuepack-addons'),
                    wp_get_current_user()->display_name,
                    '<a href="' . wp_logout_url(get_permalink()) . '">' . esc_html__('Log out?', 'valuepack-addons') . '</a>'
                ),
                'placeholder' => esc_html__('Enter logged in message', 'valuepack-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_notes_before',
            [
                'label' => esc_html__('Notes Before Form', 'valuepack-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Your email address will not be published.', 'valuepack-addons'),
                'default' => esc_html__('Your email address will not be published.', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'comment_notes_after',
            [
                'label' => esc_html__('Notes After Form', 'valuepack-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'label_placement',
            [
                'label' => esc_html__('Label Placement', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'above',
                'options' => [
                    'above' => esc_html__('Above Fields', 'valuepack-addons'),
                    'placeholder' => esc_html__('Placeholder Only', 'valuepack-addons'),
                    'both' => esc_html__('Both', 'valuepack-addons'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'submit_button_text',
            [
                'label' => esc_html__('Submit Button Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Post Comment', 'valuepack-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();

        // Content Tab - Fields
        $this->start_controls_section(
            'section_fields',
            [
                'label' => esc_html__('Fields', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'show_comment_field',
            [
                'label' => esc_html__('Comment Field', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'comment_label',
            [
                'label' => esc_html__('Comment Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Comment *', 'valuepack-addons'),
                'condition' => [
                    'show_comment_field' => 'yes',
                    'label_placement!' => 'placeholder',
                ],
            ]
        );

        $this->add_control(
            'comment_placeholder',
            [
                'label' => esc_html__('Comment Placeholder', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Enter your comment here...', 'valuepack-addons'),
                'condition' => [
                    'show_comment_field' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_author_field',
            [
                'label' => esc_html__('Author Field', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_label',
            [
                'label' => esc_html__('Author Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Name *', 'valuepack-addons'),
                'condition' => [
                    'show_author_field' => 'yes',
                    'label_placement!' => 'placeholder',
                ],
            ]
        );

        $this->add_control(
            'author_placeholder',
            [
                'label' => esc_html__('Author Placeholder', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Enter your name', 'valuepack-addons'),
                'condition' => [
                    'show_author_field' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_email_field',
            [
                'label' => esc_html__('Email Field', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'email_label',
            [
                'label' => esc_html__('Email Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Email *', 'valuepack-addons'),
                'condition' => [
                    'show_email_field' => 'yes',
                    'label_placement!' => 'placeholder',
                ],
            ]
        );

        $this->add_control(
            'email_placeholder',
            [
                'label' => esc_html__('Email Placeholder', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Enter your email', 'valuepack-addons'),
                'condition' => [
                    'show_email_field' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_website_field',
            [
                'label' => esc_html__('Website Field', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'website_label',
            [
                'label' => esc_html__('Website Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Website', 'valuepack-addons'),
                'condition' => [
                    'show_website_field' => 'yes',
                    'label_placement!' => 'placeholder',
                ],
            ]
        );

        $this->add_control(
            'website_placeholder',
            [
                'label' => esc_html__('Website Placeholder', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Enter your website URL', 'valuepack-addons'),
                'condition' => [
                    'show_website_field' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_cookies_consent',
            [
                'label' => esc_html__('Cookies Consent', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'valuepack-addons'),
                'label_off' => esc_html__('Hide', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cookies_consent_label',
            [
                'label' => esc_html__('Cookies Consent Label', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'valuepack-addons'),
                'condition' => [
                    'show_cookies_consent' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Form Container
        $this->start_controls_section(
            'section_form_container_style',
            [
                'label' => esc_html__('Form Container', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'form_background',
                'label' => esc_html__('Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper',
            ]
        );

        $this->add_responsive_control(
            'form_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_border',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper',
            ]
        );

        $this->add_responsive_control(
            'form_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'form_box_shadow',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper',
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
                    '{{WRAPPER}} .vp-comment-form-wrapper .vp-comment-form-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper .vp-comment-form-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper .vp-comment-form-title',
            ]
        );

        $this->add_responsive_control(
            'title_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper .vp-comment-form-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper .vp-comment-form-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper .vp-comment-form-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform p label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform p label',
            ]
        );

        $this->add_responsive_control(
            'label_spacing',
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
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform p label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'consent_label_color',
            [
                'label' => esc_html__('Cookie Consent Label Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform p.comment-form-cookies-consent label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Cookie Consent Label Typography', 'valuepack-addons'),
                'name' => 'consent_label_typography',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform p.comment-form-cookies-consent label',
            ]
        );

        $this->add_responsive_control(
            'consent_label_margin',
            [
                'label' => esc_html__('Cookie Consent Label Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform p.comment-form-cookies-consent label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Fields
        $this->start_controls_section(
            'section_fields_style',
            [
                'label' => esc_html__('Input Fields', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'field_typography',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="text"],
                             {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="email"],
                             {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="url"],
                             {{WRAPPER}} .vp-comment-form-wrapper form#commentform textarea',
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform input::placeholder,
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform textarea::placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform input,
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform textarea' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_background_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="text"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="email"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="url"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="text"],
                             {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="email"],
                             {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="url"],
                             {{WRAPPER}} .vp-comment-form-wrapper form#commentform textarea',
            ]
        );

        $this->add_responsive_control(
            'field_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="text"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="email"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="url"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="text"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="email"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform input[type="url"],
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_spacing',
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
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-form-comment,
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-form-author,
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-form-email,
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-form-url,
                     {{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-form-cookies-consent' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Submit Button
        $this->start_controls_section(
            'section_submit_button_style',
            [
                'label' => esc_html__('Submit Button', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => esc_html__('Width', 'valuepack-addons'),
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
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        //Bbutton text alignment
        $this->add_responsive_control(
            'button_text_alignment',
            [
                'label' => esc_html__('Text Alignment', 'valuepack-addons'),
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit',
            ]
        );

        $this->start_controls_tabs('button_tabs');

        $this->start_controls_tab(
            'button_normal',
            [
                'label' => esc_html__('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper .comment-respond form#commentform .form-submit input[type="submit"]#submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [
                'label' => esc_html__('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .form-submit input[type="submit"]#submit',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Messages
        $this->start_controls_section(
            'section_messages_style',
            [
                'label' => esc_html__('Messages', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'logged_in_message_color',
            [
                'label' => esc_html__('Logged In Message Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .logged-in-as' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'logged_in_message_typography',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .logged-in-as',
            ]
        );

        $this->add_responsive_control(
            'logged_in_message_spacing',
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
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .logged-in-as' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'notes_color',
            [
                'label' => esc_html__('Notes Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-notes' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'notes_typography',
                'selector' => '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-notes',
            ]
        );

        $this->add_responsive_control(
            'notes_spacing',
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
                    '{{WRAPPER}} .vp-comment-form-wrapper form#commentform .comment-notes' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private static function get_post_types()
    {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];
        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }
        unset($options['elementor_library']);
        unset($options['e-landing-page']);
        unset($options['attachment']);
        unset($options['page']);

        return $options;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $post_type = $settings['post_type'];
        $post_id = get_the_ID();

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $post_id = $this->value_pack_get_elementor_preview_post_id($post_type);
        }

        // Don't show comment form if comments are closed and there are no comments
        if (!comments_open($post_id) && get_comments_number($post_id) <= 0) {
            return;
        }

        // Prepare comment form args
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');
        $html_req = ($req ? " required='required'" : '');
        $required_text = sprintf(
            ' ' . esc_html__('Required fields are marked %s', 'valuepack-addons'),
            '<span class="required">*</span>'
        );

        // Fields array
        $fields = [];

        // Author field
        if ('yes' === $settings['show_author_field']) {
            $fields['author'] = sprintf(
                '<p class="comment-form-author">%s %s</p>',
                ('above' === $settings['label_placement'] || 'both' === $settings['label_placement']) ?
                    sprintf('<label for="author">%s%s</label>', $settings['author_label'], ($req ? ' <span class="required">*</span>' : '')) : '',
                sprintf(
                    '<input id="author" name="author" type="text" value="%s" size="30" maxlength="245"%s placeholder="%s" />',
                    esc_attr($commenter['comment_author']),
                    $aria_req,
                    esc_attr($settings['author_placeholder'])
                )
            );
        }

        // Email field
        if ('yes' === $settings['show_email_field']) {
            $fields['email'] = sprintf(
                '<p class="comment-form-email">%s %s</p>',
                ('above' === $settings['label_placement'] || 'both' === $settings['label_placement']) ?
                    sprintf('<label for="email">%s%s</label>', $settings['email_label'], ($req ? ' <span class="required">*</span>' : '')) : '',
                sprintf(
                    '<input id="email" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s placeholder="%s" />',
                    esc_attr($commenter['comment_author_email']),
                    $html_req,
                    esc_attr($settings['email_placeholder'])
                )
            );
        }

        // Website field
        if ('yes' === $settings['show_website_field']) {
            $fields['url'] = sprintf(
                '<p class="comment-form-url">%s %s</p>',
                ('above' === $settings['label_placement'] || 'both' === $settings['label_placement']) ?
                    sprintf('<label for="url">%s</label>', $settings['website_label']) : '',
                sprintf(
                    '<input id="url" name="url" type="url" value="%s" size="30" maxlength="200" placeholder="%s" />',
                    esc_attr($commenter['comment_author_url']),
                    esc_attr($settings['website_placeholder'])
                )
            );
        }

        // Cookies consent field
        if ('yes' === $settings['show_cookies_consent']) {
            $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
            $fields['cookies'] = sprintf(
                '<p class="comment-form-cookies-consent">%s %s</p>',
                sprintf('<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"%s />', $consent),
                sprintf('<label for="wp-comment-cookies-consent">%s</label>', $settings['cookies_consent_label'])
            );
        }

        // Comment field
        $comment_field = '';
        if ('yes' === $settings['show_comment_field']) {
            $comment_field = sprintf(
                '<p class="comment-form-comment">%s %s</p>',
                ('above' === $settings['label_placement'] || 'both' === $settings['label_placement']) ?
                    sprintf('<label for="comment">%s</label>', $settings['comment_label']) : '',
                sprintf(
                    '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" placeholder="%s"></textarea>',
                    esc_attr($settings['comment_placeholder'])
                )
            );
        }

        // Submit button
        $submit_button = sprintf(
            '<p class="form-submit">%s</p>',
            sprintf(
                '<input name="%s" type="submit" id="%s" class="%s" value="%s" />',
                'submit',
                'submit',
                'submit',
                esc_attr($settings['submit_button_text'])
            )
        );

        // Logged in as
        $logged_in_as = '';
        if (is_user_logged_in()) {
            $logged_in_as = sprintf(
                '<p class="logged-in-as">%s</p>',
                $settings['logged_in_message']
            );
        }

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $logged_in_as = '';
        }

        // Comment notes
        $comment_notes_before = '';
        if (!empty($settings['comment_notes_before'])) {
            $comment_notes_before = sprintf(
                '<p class="comment-notes">%s</p>',
                $settings['comment_notes_before']
            );
        }

        $comment_notes_after = '';
        if (!empty($settings['comment_notes_after'])) {
            $comment_notes_after = sprintf(
                '<p class="comment-notes">%s</p>',
                $settings['comment_notes_after']
            );
        }

        // Prepare form args
        $comment_form_args = [
            'title_reply' => ('yes' === $settings['show_title']) ? $settings['title_text'] : '',
            'title_reply_before' => '<h3 id="reply-title" class="vp-comment-form-title">',
            'title_reply_after' => '</h3>',
            'cancel_reply_link' => esc_html__('Cancel Reply', 'valuepack-addons'),
            'label_submit' => $settings['submit_button_text'],
            'comment_notes_before' => $comment_notes_before,
            'comment_notes_after' => $comment_notes_after,
            'fields' => apply_filters('comment_form_default_fields', $fields),
            'comment_field' => $comment_field,
            'logged_in_as' => $logged_in_as,
            'submit_field' => '%1$s %2$s',
            'submit_button' => $submit_button,
            'format' => 'html5',
        ];

        // Output the form
?>
        <div class="vp-comment-form-wrapper">
            <?php comment_form($comment_form_args, $post_id); ?>
        </div>
<?php
    }

    protected function value_pack_get_elementor_preview_post_id($post_type)
    {
        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'post_status'    => 'publish',
        );

        $posts = get_posts($args);

        if (! empty($posts)) {
            return $posts[0]; // Return the first post ID
        }
    }
}
