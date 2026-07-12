<?php

/**
 * Elementor Widget for Instagram Feeds with Hashtag Filter
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background; // Make sure to include this at the top
use Elementor\Group_Control_Box_Shadow;

class Value_Pack_Insta_Feeds extends Value_Pack_Widget_Base
{
    public function get_name()
    {
        return 'vp_insta_feeds';
    }

    public function get_title()
    {
        return esc_html__('Instagram Feeds', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-instagram-post vpack-icon';
    }

    public function get_categories()
    {
        return array('value_pack');
    }

    public function get_keywords()
    {
        return array(
            'instagram',
            'social',
            'gallery',
            'feeds',
            'image',
            'layout',
            'interactive',
            'hashtags'
        );
    }

    protected function register_controls()
    {
        // Section: Feed Settings
        $this->start_controls_section('feed_settings', array(
            'label' => esc_html__('Feed Settings', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ));

        $this->add_control(
            'get_access_token_button',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    '<a href="%s" target="_blank" class="elementor-button elementor-button-success" style="width: 100%%; text-align: center; outline: none; border: none;">%s</a>',
                    site_url('/wp-admin/admin.php?page=cubewp-settings'),
                    esc_html__('Add Access Token', 'valuepack-addons')
                ),
                'content_classes' => 'elementor-control-field-description',
            ]
        );

        $this->add_control('number_of_posts', array(
            'type' => Controls_Manager::NUMBER,
            'label' => esc_html__('Number of Posts', 'valuepack-addons'),
            'default' => 6,
            'min' => 1,
        ));
        $this->add_control(
            'feeds_enable_hashtags',
            [
                'label' => __('Get By Hashtag', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control('hashtag_filter', array(
            'type' => Controls_Manager::TEXT,
            'label' => esc_html__('Filter by Hashtag', 'valuepack-addons'),
            'placeholder' => esc_html__('Enter hashtag (without #)', 'valuepack-addons'),
            'default' => '',
            'condition' => [
                'feeds_enable_hashtags' => 'yes',
            ],
            'description' => esc_html__('Only fetch posts containing this hashtag. Leave empty to fetch all posts.', 'valuepack-addons'),
        ));

        $this->end_controls_section();



        // Section: Layout Settings
        $this->start_controls_section('layout_settings', array(
            'label' => esc_html__('Layout Settings', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ));
        $this->add_control(
            'feeds_layout',
            [
                'label' => __('Layout', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'overlay' => __('Overlay', 'valuepack-addons'),
                    'classic' => __('Classic', 'valuepack-addons'),
                    'masonry' => __('Masonry', 'valuepack-addons'),
                ],
                'default' => 'overlay',
            ]
        );
        $this->add_responsive_control('mcolumns', array(
            'type' => Controls_Manager::NUMBER,
            'label' => esc_html__('Masonry Columns', 'valuepack-addons'),
            'default' => 3,
            'min' => 1,
            'max' => 12,
            'condition' => [
                'feeds_layout' => 'masonry',
            ],
            'selectors' => [
                '{{WRAPPER}}  .masonry-layout-parent' => 'grid-template-columns: repeat( {{VALUE}}, 1fr);',
                '{{WRAPPER}} .insta-feed-grid ' => 'grid-template-columns: repeat( 2 , 1fr);',
            ],
        ));
        $this->add_control('start_main', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Start Feeds From Main Image', 'valuepack-addons'),
            'default' => 'no',
            'condition' => [
                'feeds_layout' => 'masonry',
            ],
        ));

        // Dropdown to select what to show
        $this->add_control(
            'instagram_display_type',
            [
                'label' => __('Select To Show', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => __('Icon', 'valuepack-addons'),
                    // 'profile' => __('Profile Details', 'valuepack-addons'),
                    'follow_text' => __('Text', 'valuepack-addons'),
                ],
                'condition' => [
                    'feeds_layout' => 'overlay',
                ],
            ]
        );
        // Instagram Icon control
        $this->add_control(
            'Instagram_icon_overlay',
            [
                'label' => __('Instagram Icon', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fab fa-instagram',
                    'library' => 'fa-brands',
                ],
                'condition' => [
                    'feeds_layout' => 'overlay',
                    'instagram_display_type' => 'icon',
                ],
                'label_block' => true,
            ]
        );
        $this->add_control(
            'Instagram_icon_classic',
            [
                'label' => __('Instagram Icon', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fab fa-instagram',
                    'library' => 'fa-brands',
                ],
                'condition' => [
                    'feeds_layout' => 'classic',
                ],
                'label_block' => true,
            ]
        );
        $this->add_control(
            'instagram_follow_text',
            [
                'label' => __('Text', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Follow', 'valuepack-addons'),
                'placeholder' => __('Enter text', 'valuepack-addons'),
                'condition' => [
                    'feeds_layout' => 'overlay',
                    'instagram_display_type' => 'follow_text',
                ],
            ]
        );


        // Typography for Username
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'follow_username_typography',
                'label' => __('Username Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .insta-icon_overlay.follow-text .insta-follow-username',
                'condition' => [
                    'feeds_layout' => 'overlay',
                    'instagram_display_type' => 'follow_text',
                ],
            ]
        );

        // Typography for Wrapper
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'follow_wrapper_typography',
                'label' => __('Wrapper Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .insta-icon_overlay.follow-text a',
                'condition' => [
                    'feeds_layout' => 'overlay',
                    'instagram_display_type' => 'follow_text',
                ],
            ]
        );

        // Color for Username
        $this->add_control(
            'follow_username_color',
            [
                'label' => __('Username Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay.follow-text .insta-follow-username' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'feeds_layout' => 'overlay',
                    'instagram_display_type' => 'follow_text',
                ],
            ]
        );

        // Color for Wrapper
        $this->add_control(
            'follow_wrapper_color',
            [
                'label' => __('Wrapper Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay.follow-text a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'feeds_layout' => 'overlay',
                    'instagram_display_type' => 'follow_text',
                ],
            ]
        );

        // Gap/Margin for Wrapper
        // Flex Gap for Wrapper
        $this->add_responsive_control(
            'follow_text_gap',
            [
                'label' => __('Follow Text Gap', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay.follow-text a' => 'display: flex;gap: {{TOP}}{{UNIT}};justify-content: center;flex-direction: column;align-items: center;',
                ],
                'condition' => [
                    'feeds_layout' => 'overlay',
                    'instagram_display_type' => 'follow_text',
                ],
                'allowed_dimensions' => ['top'],

            ]
        );



        $this->add_control(
            'icon_position_top',
            [
                'label' => __('Icon Top Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'feeds_layout' => 'classic',
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'options' => [
                    'px' => __('Pixels', 'valuepack-addons'),
                    'percent' => __('Percentage', 'valuepack-addons'),
                    'rem' => __('Rem', 'valuepack-addons'),
                    'em' => __('Em', 'valuepack-addons'),
                    'custom' => __('Custom', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay ' => 'top: {{SIZE}}px;',
                    '{{WRAPPER}} .insta-icon_overlay ' => 'top: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_control(
            'icon_position_left',
            [
                'label' => __('Icon Left Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'feeds_layout' => 'classic',
                ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'options' => [
                    'px' => __('Pixels', 'valuepack-addons'),
                    'percent' => __('Percentage', 'valuepack-addons'),
                    'rem' => __('Rem', 'valuepack-addons'),
                    'em' => __('Em', 'valuepack-addons'),
                    'custom' => __('Custom', 'valuepack-addons'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay ' => 'left: {{SIZE}}px;',
                    '{{WRAPPER}} .insta-icon_overlay ' => 'left: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_responsive_control('columns', array(
            'type' => Controls_Manager::NUMBER,
            'label' => esc_html__('Columns', 'valuepack-addons'),
            'default' => 6,
            'min' => 1,
            'max' => 12,
            'selectors' => [
                '{{WRAPPER}} .insta-feed-grid ' => 'grid-template-columns: repeat( {{VALUE}}, 1fr);',
            ],
            'condition' => [
                'feeds_layout!' => 'masonry',
            ],
        ));

        $this->add_responsive_control(
            'gap',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__('Gap Between Images', 'valuepack-addons'),
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .insta-feed-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .masonry-layout-parent' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control('show_in_scroll', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Show In One Line (For mobile)', 'valuepack-addons'),
            'default' => 'no',
            'condition' => [
                'feeds_layout' => array('classic', 'overlay'),
            ],
        ));


        $this->end_controls_section();

        $this->start_controls_section('style_settings', array(
            'label' => esc_html__('Style Settings', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ));
        // Border radius and icon position controls
        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Image Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-insta-feed-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Image Size (Width/Height)', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'rem', 'em'], // Supported units
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 600,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-insta-feed-item' => 'width: {{SIZE}}{{UNIT}} !important;min-width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; object-fit: cover;',
                ],

            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'label' => esc_html__('Overlay Background', 'valuepack-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .vp-insta-feed-item .overlay',
                'exclude' => ['image'], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
                'fields_options' => [
                    'color' => [
                        'default' => 'rgba(0, 0, 0, 0.5)',
                    ],
                ],
            ]
        );

        $this->add_control('icon_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Icon Color', 'valuepack-addons'),
            'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .vp-insta-feed-item i ' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '{{WRAPPER}} .vp-insta-feed-item  svg ' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .vp-insta-feed-item svg path' => 'fill: {{VALUE}}; fill: {{VALUE}};',
            ],
        ));

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay i' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .insta-icon_overlay svg' => 'width: {{SIZE}}px;height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __('Icon Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control('icon_background_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Icon Background Color', 'valuepack-addons'),
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .insta-icon_overlay' => 'background-color: {{VALUE}};',
            ],
        ));

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .insta-icon_overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .insta-icon_overlay',
                'separator' => 'before',
            ]
        );



        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();


        $cwpOptions = get_option('cwpOptions');

        $instagram_access_token = isset($cwpOptions['instagram_access_token']) ? $cwpOptions['instagram_access_token'] : '';
        $access_token = $instagram_access_token;
        $number_of_posts = $settings['number_of_posts'];
        $show_in_scroll = $settings['show_in_scroll'];
        $hashtag_filter =  $settings['hashtag_filter'];
        $feeds_layout = $settings['feeds_layout'];
        $instagram_follow_text = $settings['instagram_follow_text'];
        $Instagram_icon = isset($settings['Instagram_icon_' . $feeds_layout]) ? $settings['Instagram_icon_' . $feeds_layout] : '';
        $instagram_display_type = $settings['instagram_display_type'];
        $start_main = $settings['start_main'];

        if (empty($access_token)) {
            echo esc_html__('Please provide an access token.', 'valuepack-addons');
            return;
        }

        // Fetch Instagram feed 
        $hashtag_filters  = $hashtag_filter;
        $hashtag_filter  = !empty($hashtag_filter) ? sanitize_title($hashtag_filter) : 'no-hashtag';


        $cache_key = 'vp_insta_feed_' . $number_of_posts . '_' . $hashtag_filter;

        $insta_feed = $this->vp_get_instagram_feed_no_cache_update(
            $access_token,
            $number_of_posts,
            $cache_key,
            $hashtag_filters
        );

        if (empty($insta_feed)) {
            echo esc_html__('No posts found for this access token or filter.', 'valuepack-addons');
            return;
        }

        $profile_link = $insta_feed['userdata'];
        $insta_feed = $insta_feed['feeds'];
        $show_in_scroll_class = '';
        if (isset($show_in_scroll) && $show_in_scroll == 'yes') {
            $show_in_scroll_class = 'scroll-to-right';
        }
        if ($feeds_layout == 'masonry') {
            if ($start_main != 'yes') {
                $counter = 0;
                echo '<div class="masonry-layout-parent ' . esc_attr($show_in_scroll_class) . '" style="display:grid;">';
                while ($counter < count($insta_feed)) {
                    if ($counter < count($insta_feed)) {
                        echo '<div class="insta-feed-grid" style="display: grid;">';
                        for ($i = 0; $i < 4 && $counter < count($insta_feed); $i++) {
                            echo $this->value_pack_update_insta_feeds_cache_overlay_html($insta_feed[$counter]);// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            $counter++;
                        }
                        echo "</div>";
                    }
                    if ($counter < count($insta_feed)) {
                        echo $this->value_pack_update_insta_feeds_cache_overlay_html($insta_feed[$counter]);// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        $counter++;
                    }
                }

                echo '</div>';
            } else {
                echo '<div class="masonry-layout-parent ' . esc_attr($show_in_scroll_class) . '" style="display:grid;">';
                $count_post = 1;
                foreach ($insta_feed as $post) {
                    if ($count_post % 5 == 1) {
                        echo $this->value_pack_update_insta_feeds_cache_overlay_html($post);// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    } elseif ($count_post % 5 == 2) {
                        echo '<div class="insta-feed-grid" style="display: grid;">';
                        echo $this->value_pack_update_insta_feeds_cache_overlay_html($post);// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    } elseif ($count_post % 5 == 0) {
                        echo $this->value_pack_update_insta_feeds_cache_overlay_html($post);// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '</div>';
                    } else {
                        echo $this->value_pack_update_insta_feeds_cache_overlay_html($post);// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }

                    $count_post++;
                }
                if (($count_post - 1) % 5 != 0) {
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
        } else {
            echo '<div class="insta-feed-grid ' . esc_attr($show_in_scroll_class) . '" style="display: grid;">';
            $count_post = 1;

            foreach ($insta_feed as $post) {
                echo '<div class="vp-insta-feed-item" style="position: relative; overflow: hidden;">';
                if ($feeds_layout == 'classic') {
                    if (isset($Instagram_icon['value']) && !empty($Instagram_icon['value'])) {
                        echo '<span class="insta-icon_overlay">';
                        \Elementor\Icons_Manager::render_icon($Instagram_icon, ['aria-hidden' => 'true']);
                        echo '</span>';
                    }
                    echo '<a href="' . esc_url($post['permalink']) . '" target="_blank" class="classic-overlay-insta-styles overlay" style="display: block;"></a>';
                }
                if ($instagram_display_type == 'icon') {
                    echo '<a href="' . esc_url($post['permalink']) . '" target="_blank" style="display: block;">';
                }
                echo '<img src="' . esc_url($post['image_url']) . '" loading="lazy" decoding="async" alt="insta feeds" style="width: 100%; height: auto; display: block;">';

                if ($feeds_layout == 'overlay') {
                    echo '<div class="overlay">';


                    if ($instagram_display_type == 'profile') {
                        echo '<a href="' . esc_url($profile_link['profile_link']) . '" target="_blank" style="display: block;">';
                        echo '<img src="' . esc_url($post['image_url']) . '" loading="lazy" decoding="async" alt="insta feeds" style="width: 100%; height: auto; display: block;">';
                        echo '</a>';
                    } elseif ($instagram_display_type == 'follow_text') {
                        echo '<div class="insta-icon_overlay follow-text"> 
                                 <a href="' . esc_url($profile_link['profile_link']) . '" target="_blank" >
                                    ' . wp_kses_post($instagram_follow_text) . '  
                                    <span class="insta-follow-username">@' . esc_html($profile_link['username']) . '</span>
                                  </a>';
                        echo '</div>';
                    } else {
                        if (!empty($Instagram_icon['value'])) {
                            echo '<span class="insta-icon_overlay">';
                            \Elementor\Icons_Manager::render_icon($Instagram_icon, ['aria-hidden' => 'true']);
                            echo '</span>';
                        }
                    }

                    echo '</div>';
                }
                if ($instagram_display_type == 'icon') {
                    echo '</a>';
                }
                echo '</div>';
                $count_post++;
            }

            echo '</div>';
        }
    }


    private function value_pack_update_insta_feeds_cache_overlay_html($post)
    {
        // Ensure post data is available
        $permalink = !empty($post['permalink']) ? esc_url($post['permalink']) : '#';
        $image_url = !empty($post['image_url']) ? esc_url($post['image_url']) : 'path/to/default-image.jpg';

        // Start building HTML
        $html = '<div class="vp-insta-feed-item" style="position: relative; overflow: hidden;">';
        $html .= '<a href="' . $permalink . '" target="_blank" style="display: block;">';
        $html .= '<img src="' . $image_url . '" loading="lazy" decoding="async" alt="insta feed" style="width: 100%; height: auto; display: block;">';
        $html .= '<div class="overlay">';
        // Add Instagram icon if available 
        $html .= '<span class="insta-icon_overlay">';
        $html .= '<svg aria-hidden="true" class="e-font-icon-svg e-fab-instagram" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg>    ';
        $html .= '</span>';


        $html .= '</div>'; // Close overlay div
        $html .= '</a>';
        $html .= '</div>'; // Close vp-insta-feed-item div

        return $html;
    }
    public function vp_get_instagram_feed_no_cache_update($access_token, $number_of_posts, $custom_cache_key, $hashtag_filters = '')
    {
        if (empty($number_of_posts)) {
            $number_of_posts = 10;
        }
        
        // Check cache and expiration
        $cached_feed = get_option($custom_cache_key, []);
        $expiration = get_option($custom_cache_key . '_expiration', 0);
        
        if (!empty($cached_feed) && !empty($cached_feed['feeds']) && $expiration > time()) {
            $cached_feed['feeds'] = array_slice($cached_feed['feeds'], 0, $number_of_posts);
            return $cached_feed;
        }
        
        // Cache expired or empty, fetch fresh data
        $api_url = "https://graph.instagram.com/me/media?fields=id,caption,media_url,thumbnail_url,media_type,permalink,children{media_url}&limit=999999999&access_token={$access_token}";
        $response = wp_remote_get($api_url);

        $profile_api_url = "https://graph.instagram.com/me?fields=username,account_type,id&access_token={$access_token}";
        $responsess = wp_remote_get($profile_api_url);

        if (is_wp_error($response) || is_wp_error($responsess)) {
            return [];
        }
        $datass = json_decode(wp_remote_retrieve_body($responsess), true);
        if (empty($datass['username'])) {
            return [];
        }

        $username = $datass['username'];
        $profile_url = "https://instagram.com/{$username}";

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($data['data'])) {
            return [];
        }

        // Get cache duration from settings
        $saved_values = get_option('cwpOptions');
        $cache_duration = $saved_values['instagram_feed_cashe_duration'] ?? '15_days';
        
        $expiration_time = 1;
        switch ($cache_duration) {
            case 'day':
                $expiration_time = DAY_IN_SECONDS;
                break;
            case 'week':
                $expiration_time = WEEK_IN_SECONDS;
                break;
            case '15_days':
                $expiration_time = DAY_IN_SECONDS * 15;
                break;
            case 'month':
                $expiration_time = MONTH_IN_SECONDS;
                break;
            case 'none':
                $expiration_time = 1;
                break;
            default:
                $expiration_time = DAY_IN_SECONDS;
        }

        $posts = [];
        foreach ($data['data'] as $item) {
            if (count($posts) >= $number_of_posts) {
                break;
            }

            if (!empty($hashtag_filters) && strpos($item['caption'] ?? '', '#' . $hashtag_filters) === false) {
                continue;
            }

            // Cache images and use cached URLs
            if ($item['media_type'] === 'CAROUSEL_ALBUM' && !empty($item['children']['data'])) {
                foreach ($item['children']['data'] as $child) {
                    $local_url = value_pack_cache_instagram_image($child['media_url'], $custom_cache_key);
                    $posts[] = [
                        'image_url' => $local_url,
                        'permalink' => $item['permalink'],
                    ];
                }
            } else {
                $local_url = value_pack_cache_instagram_image($item['media_url'], $custom_cache_key);
                $posts[] = [
                    'image_url' => $local_url,
                    'permalink' => $item['permalink'],
                ];
            }
        }

        $final_data = [
            'userdata' => [
                'username'     => $username,
                'profile_link' => $profile_url,
            ],
            'feeds' => array_slice($posts, 0, $number_of_posts),
        ];
        
        // Save to cache
        if ($expiration_time > 0) {
            update_option($custom_cache_key, $final_data, false);
            update_option($custom_cache_key . '_expiration', time() + $expiration_time, false);
        }

        return $final_data;
    }
}