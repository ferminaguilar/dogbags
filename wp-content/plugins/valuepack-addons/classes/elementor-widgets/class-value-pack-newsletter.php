<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
class Value_Pack_Newsletter extends Value_Pack_Widget_Base {

    protected $value_pack_style_depends = ['value-pack-newsletter-styles'];

    public function get_name() {
        return 'vp_newsletter';
    }

    public function get_title() {
        return __( 'Newsletter', 'valuepack-addons' );
    }

    public function get_icon() {
        return 'fa fa-envelope';
    }

    public function get_categories() {
        return [ 'value_pack' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'valuepack-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'newsletter_icon',
            [
                'label' => __('Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-envelope',
                    'library' => 'fa-solid',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'hide_icon',
            [
                'label' => __('Hide Icon', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'valuepack-addons'),
                'label_off' => __('No', 'valuepack-addons'),
                'return_value' => 'no',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'newsletter_shortcode',
            [
                'label' => __( 'Shortcode', 'valuepack-addons' ),
                'type' => Controls_Manager::TEXTAREA,
                'input_type' => 'textarea',
                'placeholder' => __( '[your_shortcode]', 'valuepack-addons' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-newsletter-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .vp-newsletter-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
    
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Icon Size', 'valuepack-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
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
                    '{{WRAPPER}} .vp-newsletter-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-newsletter-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', 
                ],
            ]
        );

        $this->add_control(
            'newsletter_style',
            [
                'label' => __( 'Select Style', 'valuepack-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style_1' => __( 'Style 1', 'valuepack-addons' ),
                    'style_2' => __( 'Style 2', 'valuepack-addons' ),
                    'style_3' => __( 'Style 3', 'valuepack-addons' ),
                    'style_4' => __( 'Style 4', 'valuepack-addons' ),
                ],
                'default' => 'style_1',
            ]
        );

        $this->add_control(
            'newsletter_image',
            [
                'label' => __( 'Image', 'valuepack-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'newsletter_style' => array('style_3', 'style_4'),
                ],
               
            ]
        );

        $this->add_control(
            'newsletter_social_icons',
            [
                'label' => __( 'Social Icons', 'valuepack-addons' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'newsletter_social_icon',
                        'label' => __( 'Icon', 'valuepack-addons' ),
                        'type' => Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fab fa-facebook-f',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'name' => 'newsletter_social_link',
                        'label' => __( 'Link', 'valuepack-addons' ),
                        'type' => Controls_Manager::URL,
                        'placeholder' => __( 'your-link.com', 'valuepack-addons' ),
                        'default' => [
                            'url' => '',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                        'label_block' => true,
                    ],
                ],
                'default' => [
                    [
                        'newsletter_social_icon' => [
                            'value' => 'fab fa-facebook-f',
                            'library' => 'fa-brands',
                        ],
                        'newsletter_social_link' => [
                            'url' => '#',
                        ],
                    ],
                ],

                'title_field' => '<i class="{{ newsletter_social_icon.value }}"></i> {{{ newsletter_social_link.url }}}',
                'condition' => [
                    'newsletter_style' => 'style_3',
                ],
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $icon = $settings['newsletter_icon'];
        $style = sanitize_key($settings['newsletter_style']);
        $newsletter_shortcode = sanitize_text_field($settings['newsletter_shortcode']);
        $hide_icon = $settings['hide_icon'] === 'no';

        echo '<div class="vp-newsletter-widget ' . esc_attr($style) . '"
            aria-label="' . esc_attr__('Newsletter Signup', 'valuepack-addons') . '">';
        // Display the icon
        if ($hide_icon && !empty($icon['value'])) {
            echo '<span class="vp-newsletter-icon" aria-hidden="true">';
            \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            echo '</span>';
        }
        echo '</div>';
        
        
        if ($style === 'style_1') { ?>
            <div class="vp-newsletter-style-1-wrapper">
                <div class="vp-newsletter-close-x-main">
                    <span class="vp-newsletter-close-x" aria-label="<?php esc_attr_e('Close newsletter', 'valuepack-addons'); ?>">
                        <?php 
                        echo value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </span>
                </div>
                <?php echo do_shortcode($newsletter_shortcode); ?>
            </div>
            <?php } elseif($style === 'style_2'){ ?>
                <div class="vp-newsletter-style-2-wrapper">
                    <div class="vp-newsletter-style-2-popup">
                        <div class="vp-newsletter-close-x-main">
                            <span class="vp-newsletter-close-x" aria-label="<?php esc_attr_e('Close newsletter', 'valuepack-addons'); ?>">
                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="white"/>
                                <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="white"/>
                                </svg>
                            </span>
                        </div>
                        <?php  echo do_shortcode( $newsletter_shortcode ) ; ?>
                    </div>
                </div>
              <?php }  if ($style === 'style_3') { ?>
                <div class="vp-newsletter-style-3-wrapper">
                    <div class="row">
                        <div class="col-md-6 p-0">
                            <div class="vp-newsletter-style-3-thumbnail">
                                <?php if (!empty($settings['newsletter_image']['url'])) { ?>
                                    <img src="<?php echo esc_url($settings['newsletter_image']['url']); ?>" 
                                        alt="<?php echo esc_attr($settings['newsletter_image']['alt']); ?>"
                                        loading="lazy">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-6 p-0">
                            <div class="vp-newsletter-style-3-content">
                                <div class="vp-newsletter-close-x-main">
                                    <span class="vp-newsletter-close-x" aria-label="<?php esc_attr_e('Close newsletter', 'valuepack-addons'); ?>">
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D"/>
                                            <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D"/>
                                        </svg>
                                    </span>
                                </div>
                                <?php echo do_shortcode($newsletter_shortcode); ?>
                                <div class="vp-newsletter-style-3-social-icons">
                                    <ul>
                                        <?php
                                        foreach ($settings['newsletter_social_icons'] as $social_icon) {
                                            $url = esc_url($social_icon['newsletter_social_link']['url']);
                                            $target = $social_icon['newsletter_social_link']['is_external'] ? ' target="_blank"' : '';
                                            $nofollow = $social_icon['newsletter_social_link']['nofollow'] ? ' rel="nofollow"' : '';
                                            
                                            if (!empty($url)) {
                                                echo '<li><a href="' . esc_url($url) . '"' . esc_attr($target) . esc_attr($nofollow) . '>';
                                                \Elementor\Icons_Manager::render_icon($social_icon['newsletter_social_icon'], ['aria-hidden' => 'true']);
                                                echo '</a></li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } elseif($style === 'style_4'){ ?>
                <div class="vp-newsletter-style-4-wrapper">
                    <div class="vp-newsletter-style-4-thumbnail">
                                <?php if (!empty($settings['newsletter_image']['url'])) { ?>
                                    <img src="<?php echo esc_url($settings['newsletter_image']['url']); ?>" alt="<?php echo esc_attr($settings['newsletter_image']['alt']); ?>">
                                <?php } ?>
                        <div class="vp-newsletter-close-x-main">
                            <span class="vp-newsletter-close-x">
                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D"/>
                                    <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="vp-newsletter-style-4-popup">
                        <?php  echo do_shortcode( $newsletter_shortcode ) ; ?>
                    </div>
                </div>
       <?php     
        }
        
    }

}
