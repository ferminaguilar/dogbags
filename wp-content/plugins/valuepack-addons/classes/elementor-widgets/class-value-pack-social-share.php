<?php

/**
 * Value Pack - Social Share Widget
 * 
 * Provides social sharing functionality for Posts and Products
 * 
 * Features:
 * - Custom sharing icons 
 * - Flexible layout controls
 * - Responsive styling options
 * - Secure sharing implementation
 * 
 * @package ValuePack 
 * @since 1.0.0
 */
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

/**
 * Social Share Widget
 *
 * Displays social sharing buttons for Posts and Products
 */
class Value_Pack_Social_Share extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_social_share';
    }

    public function get_title()
    {
        return __('VP Social Share', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-share vpack-icon';
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
                'label' => __('Settings', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Post/Product ID', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => value_pack_get_any_one_product_id(),
                'description' => __('Add ID if you want to share specific post/product', 'valuepack-addons'),
            ]
        );

        $this->add_control('icon', array(
            'type'        => Controls_Manager::ICONS,
            'label'       => esc_html__('Icon For Slide', 'valuepack-addons'),
            'default' => [
                'value' => 'fas fa-share-alt',
                'library' => 'fa-solid',
            ],
        ));

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Share',
                'placeholder' => __('Enter text here', 'valuepack-addons'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Start of Style Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Container', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Normal tab controls
        $this->start_controls_tabs('container_tabs');

        // Normal Tab
        $this->start_controls_tab(
            'container_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_responsive_control(
            'icon_size_normal',
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
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-social-share-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_normal',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .vp-social-share-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color_normal',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography_normal',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-social-share-title',
                'field_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 15,
                            'unit' => 'px',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'share_container_background_color_normal',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'share_container_border_normal',
                'label' => esc_html__('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-social-share-icon-title',
            ]
        );

        $this->add_control(
            'share_container_border_radius_normal',
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
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Normal Tab

        // Hover Tab
        $this->start_controls_tab(
            'container_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_responsive_control(
            'icon_size_hover',
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
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => __('Icon Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography_hover',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-title',
            ]
        );

        $this->add_control(
            'share_container_background_color_hover',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'share_container_border_hover',
                'label' => esc_html__('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon-title',
            ]
        );

        $this->add_control(
            'share_container_border_radius_hover',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Hover Tab

        $this->end_controls_tabs(); // End Tabs

        $this->add_responsive_control(
            'share_container_width',
            [
                'label' => __( 'Button Width', 'valuepack-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 1200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Common controls (apply to both normal and hover)
        $this->add_responsive_control(
            'text_icon_spacing',
            [
                'label' => __('Space Between', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => __('Alignment', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __('Start', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => __('End', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'display: flex; align-items: {{VALUE}};',
                ],
                'toggle' => true,
            ]
        );

        $this->add_control(
            'justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-justify-center-h',
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
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'row',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'valuepack-addons'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'share_container_padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'share_container_margin',
            [
                'label' => __('Margin', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-icon-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_icon_spacing_right',
            [
                'label' => __('Dropdown Space', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon-outer' => 'right: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_icon_spacing_top',
            [
                'label' => __('Dropdown Vertical Space', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -500,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-social-share-container:hover .vp-social-share-icon-outer' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        //icon margin
        $this->add_responsive_control(
        'icon_margin',
        [
            'label' => __('Icon Margin', 'valuepack-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default' => [
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0,
                'unit' => 'px',
                'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .vp-social-share-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render()
    {
        $settings = $this->get_active_settings();
        $settings   = $this->get_settings_for_display();

        // Validate product ID

        if (is_singular()) {
            $product_id = get_the_ID();
        } else {
            $product_id = isset($settings['product_id']) ? absint($settings['product_id']) : 0;
            if ($product_id <= 0 || get_post_type($product_id) !== 'product' || get_post_status($product_id) !== 'publish') {
                $default_products = value_pack_get_woocommerce_products();
                if (!empty($default_products)) {
                    $first_default_id = array_key_first($default_products);
                    $product_id = absint($first_default_id);
                } else {
                    $product_id = 0;
                }
            }
        }

        if (!$product_id) {
            return;
        }

        // Generate title HTML with escaping
        $title_html = '';
        if (!empty($settings['title'])) {
            $title_html = '<span class="vp-social-share-title">' . esc_html($settings['title']) . '</span>';
        }

?>
        <div class="vp-social-share-container">
            <div class="vp-social-share-icon-title">
                <?php
                if (!empty($settings['icon']['value'])) {
                    echo '<span class="vp-social-share-icon">';
                    Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
                    echo ' </span>';
                }

                ?>
                <?php echo wp_kses_post($title_html); ?>
            </div>
            <div class="vp-social-share-icon-outer">
                <?php echo value_pack_get_socials_share($product_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
            </div>
        </div>
<?php
    }
}

?>