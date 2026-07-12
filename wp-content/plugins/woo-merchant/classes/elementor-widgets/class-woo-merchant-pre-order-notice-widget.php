<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

class Woo_Merchant_Pre_Order_Notice_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'wm_pre_order_notice';
    }

    public function get_title()
    {
        return __('Pre Order Notice', 'woo-merchant');
    }

    public function get_icon()
    {
        return 'eicon-clock';
    }

    public function get_categories()
    {
        return ['woo_merchant'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'woo-merchant'),
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'woo-merchant'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-search',
                    'library' => 'fa-solid',
                ],
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'woo-merchant'),
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
                    '{{WRAPPER}} .wm-pre-order-notice-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wm-pre-order-notice-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', // For SVG icons
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon Color', 'woo-merchant'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .wm-pre-order-notice-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'woo-merchant'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Pre-order now to make sure you do not miss out! As soon as it is in stock, we will ship it to you.', 'woo-merchant'),
                'placeholder' => __('Enter text here', 'woo-merchant'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'woo-merchant'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __('Typography', 'woo-merchant'),
                'selector' => '{{WRAPPER}} .wm-pre-order-notice-title',
            ]
        );

        $this->add_responsive_control(
            'text_icon_spacing',
            [
                'label' => __('Space Between', 'woo-merchant'),
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
                    '{{WRAPPER}} .wm-pre-order-notice-icon-title' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => __('Alignment', 'woo-merchant'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __('Start', 'woo-merchant'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'woo-merchant'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => __('End', 'woo-merchant'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-icon-title' => 'display: flex; align-items: {{VALUE}};',
                ],
                'toggle' => true,
            ]
        );

        $this->add_control(
            'justify_content',
            [
                'label' => esc_html__('Justify Content', 'woo-merchant'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'woo-merchant'),
                        'icon' => 'eicon-justify-start-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'woo-merchant'),
                        'icon' => 'eicon-justify-end-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'woo-merchant'),
                        'icon' => 'eicon-justify-center-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'woo-merchant'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'woo-merchant'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'woo-merchant'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-icon-title' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'woo-merchant'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'row',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'woo-merchant'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row Reverse', 'woo-merchant'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'woo-merchant'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Column Reverse', 'woo-merchant'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-icon-title' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'notice_container_padding',
            [
                'label' => __('Padding', 'woo-merchant'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'notice_container_margin',
            [
                'label' => __('Margin', 'woo-merchant'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'notice_container_bg_color',
            [
                'label'     => __('Background Color', 'woo-merchant'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wm-pre-order-notice-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $product_id = null;
        if (is_singular('product')) {
            $product_id = get_the_ID();
        }

        // Validate product exists and get pre-order status
        $wm_pre_order = '';
        $product = null;
        if ($product_id && get_post($product_id) && get_post_type($product_id) === 'product') {
            $product = wc_get_product($product_id);
            $wm_pre_order = get_post_meta($product_id, 'wm_pre_order', true);
        }

        // Build icon HTML with escaped attributes
        $icon_html = '';
        if (!empty($settings['icon']['value'])) {
            $icon_html = '<span class="wm-pre-order-notice-icon">';
            ob_start();
            \Elementor\Icons_Manager::render_icon(
                [
                    'value' => esc_attr($settings['icon']['value']),
                    'library' => esc_attr($settings['icon']['library'])
                ],
                ['aria-hidden' => 'true']
            );
            $icon_html .= ob_get_clean();
            $icon_html .= '</span>';
        }

        // Build title HTML with escaped text
        $title_html = '';
        if (!empty($settings['text'])) {
            $title_html = '<span class="wm-pre-order-notice-title">' . esc_html($settings['text']) . '</span>';
        }

        if ($product && $wm_pre_order === 'yes' && $product->is_type('simple') ) {
?>
                <div class="wm-pre-order-notice-container">
                    <div class="wm-pre-order-notice-icon-title">
                        <?php echo wp_kses_post($icon_html); ?>
                        <?php echo wp_kses_post($title_html); ?>
                    </div>
                </div>
        <?php } else { ?>
            <div class="wm-pre-order-notice-container wm-variable-product" style="display:none;">
                    <div class="wm-pre-order-notice-icon-title">
                        <?php echo wp_kses_post($icon_html); ?>
                        <?php echo wp_kses_post($title_html); ?>
                    </div>
                </div>
        <?php }
        }
    }

?>
