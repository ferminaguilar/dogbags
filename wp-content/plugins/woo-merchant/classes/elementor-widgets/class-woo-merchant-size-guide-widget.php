<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

class Woo_Merchant_Size_Guide_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'wm_size_guide';
    }

    public function get_title()
    {
        return __('Size Guide', 'woo-merchant');
    }

    public function get_icon()
    {
        return 'eicon-custom';
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
                    '{{WRAPPER}} .wm-size-guide-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wm-size-guide-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', // For SVG icons
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
                    '{{WRAPPER}} .wm-size-guide-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .wm-size-guide-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'woo-merchant'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Size Guide',
                'placeholder' => __('Enter text here', 'woo-merchant'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'woo-merchant'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wm-size-guide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'woo-merchant'),
                'selector' => '{{WRAPPER}} .wm-size-guide-title',
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
                    '{{WRAPPER}} .wm-size-guide-icon-title' => 'gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wm-size-guide-icon-title' => 'display: flex; align-items: {{VALUE}};',
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
                    '{{WRAPPER}} .wm-size-guide-icon-title' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .wm-size-guide-icon-title' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render()
    {
        $wm_options = get_option('WM_woocommerce_features_options');
        if (empty($wm_options['size_guide_button']) && ($wm_options['manage_size_guide_button'] == 'hooks')) {
            return;
        }
        
        $settings = $this->get_settings_for_display();
        
        // Validate product exists
        $product_id = null;
        $size_guide_image = null;
        if (is_singular('product')) {
            $product_id = get_the_ID();
            if ($product_id && get_post($product_id) && get_post_type($product_id) === 'product') {
                $size_guide_image = get_post_meta($product_id, '_size_guide_image', true);
                $size_guide_image = $size_guide_image ? esc_url($size_guide_image) : null;
            }
        }

        // Build icon HTML with escaped attributes
        $icon_html = '';
        if (!empty($settings['icon']['value'])) {
            $icon_html = '<span class="wm-size-guide-icon">';
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
        if (!empty($settings['title'])) {
            $title_html = '<span class="wm-size-guide-title">' . esc_html($settings['title']) . '</span>';
        }

        if ($size_guide_image) { ?>
            <div class="wm-size-guide-container">
                <div class="wm-size-guide-icon-title">
                    <?php echo wp_kses_post($icon_html); ?>
                    <?php echo wp_kses_post($title_html); ?>
                </div>
                <div class="wm-size-guide-canvas">
                    <div class="wm-size-guide-image">
                        <span class="wm-size-guide-close">&times;</span>
                        <img src="<?php echo esc_url($size_guide_image); ?>" alt="<?php esc_attr_e('Size Guide', 'woo-merchant'); ?>" />
                    </div>
                </div>
            </div>
       <?php } ?>
<?php
    }
}

?>
