<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * User Account Elementor Widget.
 * Shows logged-in user avatar, username/display name, and a dropdown with custom links, page links, or logout.
 */
class Value_Pack_User_Account extends Value_Pack_Widget_Base
{
    /** @var string[] */
    protected $value_pack_style_depends = ['value-pack-styles'];

    public function get_name()
    {
        return 'vp_user_account';
    }

    public function get_title()
    {
        return __('User Account', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-user-circle-o vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    public function get_keywords()
    {
        return ['user', 'login', 'avatar', 'dropdown', 'logout', 'profile'];
    }

    /**
     * Get list of all pages for dropdown.
     *
     * @return array Page ID => Page title.
     */
    private function get_pages_list()
    {
        $pages = get_pages([
            'post_status' => 'publish',
            'number'      => 500,
        ]);
        $options = [
            '' => __('— Select Page —', 'valuepack-addons'),
        ];
        foreach ($pages as $page) {
            $options[$page->ID] = $page->post_title;
        }
        return $options;
    }

    protected function register_controls()
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('username_type', [
            'label'   => __('Show Name As', 'valuepack-addons'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'display_name',
            'options' => [
                'display_name' => __('Display Name', 'valuepack-addons'),
                'user_login'    => __('Username', 'valuepack-addons'),
            ],
        ]);

        $this->add_responsive_control('avatar_size', [
            'label'      => __('Avatar Size', 'valuepack-addons'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => [
                'px' => ['min' => 24, 'max' => 120, 'step' => 2],
            ],
            'default'    => ['unit' => 'px', 'size' => 40],
            'selectors'  => [
                '{{WRAPPER}} .vp-user-account-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; border-radius: 50%; object-fit: cover;',
            ],
        ]);

        $repeater = new Repeater();

        $repeater->add_control('item_type', [
            'label'   => __('Link Type', 'valuepack-addons'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'page_link',
            'options' => [
                'custom_link' => __('Custom Link', 'valuepack-addons'),
                'page_link'   => __('Page Link', 'valuepack-addons'),
                'logout'      => __('Logout', 'valuepack-addons'),
            ],
        ]);

        $repeater->add_control('link_text', [
            'label'       => __('Link Text', 'valuepack-addons'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'placeholder' => __('e.g. My Account', 'valuepack-addons'),
        ]);

        $repeater->add_control('link_url', [
            'label'       => __('URL', 'valuepack-addons'),
            'type'        => Controls_Manager::URL,
            'placeholder' => __('https://example.com', 'valuepack-addons'),
            'condition'   => ['item_type' => 'custom_link'],
        ]);

        $repeater->add_control('page_id', [
            'label'     => __('Select Page', 'valuepack-addons'),
            'type'      => Controls_Manager::SELECT2,
            'options'   => $this->get_pages_list(),
            'default'   => '',
            'label_block' => true,
            'condition' => ['item_type' => 'page_link'],
        ]);

        $this->add_control('dropdown_items', [
            'label'       => __('Dropdown Menu Items', 'valuepack-addons'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                ['item_type' => 'logout', 'link_text' => __('Logout', 'valuepack-addons')],
            ],
            'title_field' => '{{{ item_type }}} - {{{ link_text }}}',
        ]);



        $this->end_controls_section();

        $this->start_controls_section('section_style_user', [
            'label' => __('User Bar', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('username_color', [
            'label'     => __('Name Color', 'valuepack-addons'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .vp-user-account-name' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'username_typography',
            'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
            'selector' => '{{WRAPPER}} .vp-user-account-name',
        ]);



        $this->add_responsive_control('gap', [
            'label'      => __('Gap', 'valuepack-addons'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 0, 'max' => 60]],
            'selectors'  => ['{{WRAPPER}} .vp-user-account-bar' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_dropdown', [
            'label' => __('Dropdown', 'valuepack-addons'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('dropdown_bg', [
            'label'     => __('Background', 'valuepack-addons'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .vp-user-account-dropdown' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'dropdown_link_typography',
            'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
            'selector' => '{{WRAPPER}} .vp-user-account-dropdown a',
        ]);

        $this->add_control('dropdown_link_color', [
            'label'     => __('Link Color', 'valuepack-addons'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .vp-user-account-dropdown a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('dropdown_link_hover_color', [
            'label'     => __('Link Hover Color', 'valuepack-addons'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .vp-user-account-dropdown a:hover' => 'color: {{VALUE}};'],
        ]);
        // li background color normal
        $this->add_control(
            'dropdown_li_bg_color',
            [
                'label' => __('List Item Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-menu a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .vp-user-account-menu li' => 'background-color: #fff0',
                ],
            ]
        );

        // li background color hover
        $this->add_control(
            'dropdown_li_bg_color_hover',
            [
                'label' => __('List Item Hover Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-menu li:hover,{{WRAPPER}} .vp-user-account-menu li:hover a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control('dropdown_padding', [
            'label'      => __('Padding', 'valuepack-addons'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .vp-user-account-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        // Min Width Control
        $this->add_responsive_control('dropdown_min_width', [
            'label'      => __('Min Width', 'valuepack-addons'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 120, 'max' => 400]],
            'selectors'  => ['{{WRAPPER}} .vp-user-account-dropdown' => 'min-width: {{SIZE}}{{UNIT}};'],
        ]);

        // Box Shadow Control
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'label' => __('Box Shadow', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-user-account-dropdown',
            ]
        );

        // Border Control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'label' => __('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-user-account-dropdown',
            ]
        );

        // Border Radius Control
        $this->add_responsive_control(
            'dropdown_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Dropdown Left Position Control
        $this->add_responsive_control(
            'dropdown_left',
            [
                'label' => __('Left Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw'],
                'range' => [
                    'px' => ['min' => -200, 'max' => 400],
                    '%' => ['min' => -100, 'max' => 100],
                    'em' => ['min' => -20, 'max' => 20],
                    'vw' => ['min' => -50, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-dropdown' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        
        // Dropdown Left Position Control
        $this->add_responsive_control(
            'dropdown_right',
            [
                'label' => __('Right Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw'],
                'range' => [
                    'px' => ['min' => -200, 'max' => 400],
                    '%' => ['min' => -100, 'max' => 100],
                    'em' => ['min' => -20, 'max' => 20],
                    'vw' => ['min' => -50, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-dropdown' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        // Dropdown Top Position Control
        $this->add_responsive_control(
            'dropdown_top',
            [
                'label' => __('Top Position', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vh'],
                'range' => [
                    'px' => ['min' => -200, 'max' => 400],
                    '%' => ['min' => -100, 'max' => 100],
                    'em' => ['min' => -20, 'max' => 20],
                    'vh' => ['min' => -50, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-dropdown' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // li padding control
        $this->add_responsive_control(
            'dropdown_li_padding',
            [
                'label' => __('List Item Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-menu li' => 'padding:0px !important;',
                    '{{WRAPPER}} .vp-user-account-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dropdown_li_margin',
            [
                'label' => __('List Item Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-menu li:not(:last-child)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // li border control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_li_border',
                'label' => __('List Item Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-user-account-menu li a',
            ]
        );
        // li border control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_li_border_hover',
                'label' => __('List Item Border Hover', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-user-account-menu li:hover a',
            ]
        );
        // li border radius control
        $this->add_responsive_control(
            'dropdown_li_border_radius',
            [
                'label' => __('List Item Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-menu li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->add_control(
            'dropdown_arrow_color',
            [
                'label' => __('Dropdown Arrow Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ddd',
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-dropdown::after' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_arrow_top',
            [
                'label' => __('Dropdown Arrow Top', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => -50, 'max' => 50],
                    '%' => ['min' => -50, 'max' => 50],
                    'em' => ['min' => -5, 'max' => 5],
                ],
                'default' => ['unit' => 'px', 'size' => -10],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-dropdown::after' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_arrow_left',
            [
                'label' => __('Dropdown Arrow Left', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                    '%' => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0, 'max' => 10],
                ],
                'default' => ['unit' => '%', 'size' => 50],
                'selectors' => [
                    '{{WRAPPER}} .vp-user-account-dropdown::after' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        if (!is_user_logged_in()) {
            if ($is_editor) {
                echo '<div class="vp-user-account vp-user-account-placeholder">';
                echo '<span class="vp-user-account-avatar">' . get_avatar(0, 40) . '</span>';
                echo '<span class="vp-user-account-name">' . esc_html__('Logged-in user name', 'valuepack-addons') . '</span>';
                echo '<div class="vp-user-account-dropdown"><ul><li><a href="#">' . esc_html__('Sample link', 'valuepack-addons') . '</a></li><li><a href="#">' . esc_html__('Logout', 'valuepack-addons') . '</a></li></ul></div>';
                echo '</div>';
            }
            return;
        }

        $user_id   = get_current_user_id();
        $user      = get_userdata($user_id);
        $name_type = isset($settings['username_type']) ? $settings['username_type'] : 'display_name';
        $name      = $user ? $user->$name_type : '';
        $avatar    = get_avatar($user_id, 80);
        $widget_id = $this->get_id();

        echo '<div id="vp-user-account-' . esc_attr($widget_id) . '" class="vp-user-account">';
        echo '<div class="vp-user-account-bar">';
        echo '<span class="vp-user-account-avatar">' . $avatar . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<span class="vp-user-account-name">' . esc_html($name) . '</span>';

        echo '</div>';

        echo '<div class="vp-user-account-dropdown">';
        echo '<ul class="vp-user-account-menu">';
        foreach ($settings['dropdown_items'] as $item) {
            $type  = isset($item['item_type']) ? $item['item_type'] : 'custom_link';
            $text  = isset($item['link_text']) ? $item['link_text'] : '';
            $url   = '#';
            $attrs = '';

            if ($type === 'logout') {
                $url   = wp_logout_url(home_url());
                $text  = $text ? $text : __('Logout', 'valuepack-addons');
                $attrs = ' rel="nofollow"';
            } elseif ($type === 'page_link' && !empty($item['page_id'])) {
                $url  = get_permalink((int) $item['page_id']);
                $page = get_post((int) $item['page_id']);
                if (!$text && $page) {
                    $text = $page->post_title;
                }
                $text = $text ? $text : __('Page', 'valuepack-addons');
            } elseif ($type === 'custom_link' && !empty($item['link_url']['url'])) {
                $url = $item['link_url']['url'];
                if (!empty($item['link_url']['is_external'])) {
                    $attrs .= ' target="_blank"';
                }
                if (!empty($item['link_url']['nofollow'])) {
                    $attrs .= ' rel="nofollow"';
                }
                $text = $text ? $text : $url;
            } else {
                continue;
            }

            echo '<li><a href="' . esc_url($url) . '"' . $attrs . '>' . esc_html($text) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        echo '</ul></div></div>';
    }
}