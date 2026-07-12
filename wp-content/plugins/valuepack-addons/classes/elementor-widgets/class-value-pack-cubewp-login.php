<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Value_Pack_CubeWp_Login extends Value_Pack_Widget_Base
{
    /**
     * Widget specific asset dependencies.
     *
     * @var array
     */
    protected $value_pack_style_depends = ['value-pack-login-styles'];

    /**
     * Widget specific script dependencies.
     *
     * @var array
     */
    protected $value_pack_script_depends = ['value-pack-frontend-scripts', 'cwp-user-login', 'cwp-user-register'];

    public function get_name()
    {
        return 'vp_login';
    }

    public function get_title()
    {
        return __('CubeWP Login', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-lock-user vpack-icon';
    }

    public function get_categories()
    {
        return ['value_pack'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user-lock',
                    'library' => 'fa-solid',
                ],
                'label_block' => true,
            ]
        );


        $this->end_controls_section();
        // Styling Section for Modal Content
        $this->start_controls_section(
            'section_modal_content_style_main',
            [
                'label' => __('Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_style',
            [
                'label' => __('Form Popup Style', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'style_1',
                'options' => [
                    'style_1' => __('Style 1', 'valuepack-addons'),
                    'style_2' => __('Style 2', 'valuepack-addons'),
                    'style_3' => __('Style 3', 'valuepack-addons'),
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'      => __('Icon Color', 'valuepack-addons'),
                'type'       => \Elementor\Controls_Manager::COLOR,
                'default'    => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-login-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .vp-login-icon i' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-login-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-login-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', // For SVG icons
                ],
            ]
        );

        // Icon Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'login_icon_border',
                'selector' => '{{WRAPPER}} .vp-login-icon',
            ]
        );

        // Icon Border Radius
        $this->add_responsive_control(
            'login_icon_border_radius',
            [
                'label' => __('Icon Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-login-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Icon Padding
        $this->add_responsive_control(
            'login_icon_padding',
            [
                'label' => __('Icon Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-login-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Icon Background Color
        $this->add_control(
            'login_icon_bg_color',
            [
                'label' => __('Icon Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-login-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_text',
            [
                'label' => __('Show Text', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter text here', 'valuepack-addons'),
                'condition' => [
                    'show_text' => 'yes',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-login-main-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'login_widget_flex_direction',
            [
                'label' => __('Flex Direction', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .vp-login-widget' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_widget_align_items',
            [
                'label' => __('Align Items', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'stretch' => __('Stretch', 'valuepack-addons'),
                    'baseline' => __('Baseline', 'valuepack-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-login-widget' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-login-widget' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_widget_gap',
            [
                'label' => __('Gap', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-login-widget' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typographies',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .vp-login-main-title',
                'selectors' => [
                    '{{WRAPPER}} .vp-login-main-title',
                ],
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        // Background Color
        $this->add_control(
            'vp_login_widget_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-login-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'vp_login_widget_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-login-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'vp_login_widget_border',
                'selector' => '{{WRAPPER}} .vp-login-widget',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'vp_login_widget_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .vp-login-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Styling Section for Modal Content
        $this->start_controls_section(
            'section_modal_content_style',
            [
                'label' => __('Modal Content Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'modal_content_background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vp-login-modal-content, .vp-register-modal-content' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'modal_content_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '.vp-login-modal-content, .vp-register-modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'modal_content_border',
                'selector' => '.vp-login-modal-content, .vp-register-modal-content',
            ]
        );

        $this->add_control(
            'modal_content_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '.vp-login-modal-content, .vp-register-modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'modal_content_box_shadow',
                'selector' => '.vp-login-modal-content, .vp-register-modal-content',
            ]
        );

        $this->end_controls_section();

        // Styling Section for Form Headings
        $this->start_controls_section(
            'section_form_heading_style',
            [
                'label' => __('Form Heading Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_heading_color',
            [
                'label' => __('Heading Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vp-login-modal-content .form-heading h2, .vp-register-modal-content .form-heading h2' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'form_description_color',
            [
                'label' => __('Description Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vp-login-modal-content .form-heading p, .vp-register-modal-content .form-heading p' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        // Styling Section for Form Bottom Text
        $this->start_controls_section(
            'section_form_bottom_text_style',
            [
                'label' => __('Form Bottom Text Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_bottom_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vp-login-modal-content .modal-bottom-text, .vp-register-modal-content .modal-bottom-text' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'form_bottom_link_color',
            [
                'label' => __('Link Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.modal-bottom-text .vp-register-trigger, .modal-bottom-text .vp-login-trigger' => 'color: {{VALUE}}  !important;',
                ],
            ]
        );



        $this->end_controls_section();

        // Styling Section for Submit Buttons
        $this->start_controls_section(
            'section_submit_button_style',
            [
                'label' => __('Submit Button Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        // Normal State
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit], body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => 'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit], body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]',
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit], body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => 'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit], body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit], body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => 'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit], body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit], body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit]:hover, body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit]:hover, body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit]:hover, body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => 'body .vp-login-modal-content .cwp-frontend-form-container input[type=submit]:hover, body .vp-register-modal-content .cwp-frontend-form-container input[type=submit]:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $title = $settings['title'];
        $icon = $settings['icon'];
        $show_text = $settings['show_text'];
        $form_style = $settings['form_style'];

        if (! is_user_logged_in() || \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            echo '<div class="vp-login-widget d-flex" data-style="' . esc_attr($form_style) . '">';

            // Display the icon
            if (! empty($icon['value'])) {
                echo '<span class="vp-login-icon">';
                \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
                echo '</span>';
            }

            // Display the text if 'show_text' is 'yes'
            if ($show_text === 'yes' && !empty($title)) {
                echo '<span class="vp-login-main-title">' . wp_kses_post($title) . '</span>';
            }

            echo '</div>';
        }

?>
        <div id="vp-login-modal" class="vp-login-modal" style="display: none;">
            <div class="vp-login-register-modal-content">
                <div class="vp-login-modal-content">
                    <span class="vp-login-close">
                        <?php 
                        echo value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </span>
                    <div class="modal-form-content">
                        <div class="form-heading">
                            <h2><?php esc_attr_e('Login', 'valuepack-addons') ?></h2>
                            <p><?php esc_attr_e('Log in and enjoy a personalised experience.', 'valuepack-addons') ?></p>
                        </div>
                        <?php
                        if (!is_user_logged_in()) {
                            echo do_shortcode('[vpLoginForm]');
                        }
                        ?>
                        <p class="modal-bottom-text">
                            <?php 
                            /* translators: %1$s: Create Account link, %2$s: Close icon */
                            echo sprintf(esc_html__('Don\'t have an account? %1$sCreate Account%2$s', 'valuepack-addons'), '<span class="vp-register-trigger">', '</span>');
                             ?>
                        </p>
                    </div>
                </div>
                <div class="vp-register-modal-content d-none">
                    <span class="vp-register-close">
                        <?php 
                        echo value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </span>
                    <div class="modal-form-content">
                        <div class="form-heading">
                            <h2><?php esc_attr_e('Create Account', 'valuepack-addons') ?></h2>
                            <p><?php esc_attr_e('Log in and enjoy a personalised experience.', 'valuepack-addons') ?></p>
                        </div>
                        <?php
                        if (!is_user_logged_in()) {
                            echo do_shortcode('[cwpRegisterForm role="subscriber"]');
                        }
                        ?>
                        <p class="modal-bottom-text">
                            <?php 
                            /* translators: %1$s: Login link, %2$s: Close icon */
                            echo sprintf(esc_html__('Have an account? %1$sLogin%2$s', 'valuepack-addons'), '<span class="vp-login-trigger">', '</span>');
                             ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
