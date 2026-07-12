<?php

/**
 * Elementor Widget: Size Guide
 *
 * This widget allows users to display a clickable icon and label which opens a modal 
 * showing a product-specific size guide image, retrieved from post meta.
 *
 * @package Value_Pack
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

/**
 * Class Value_Pack_Size_Guide
 */
class Value_Pack_Size_Guide extends Value_Pack_Widget_Base
{

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name()
    {
        return 'vp_size_guide';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Size Guide', 'valuepack-addons');
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-custom vpack-icon';
    }

    /**
     * Get widget categories.
     *
     * @return array
     */
    public function get_categories()
    {
        return ['value_pack'];
    }

    /**
     * Register widget controls.
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => esc_html__('Product ID', 'valuepack-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => value_pack_get_any_one_product_id(),
                'description' => esc_html__('If this is not a single product page, enter the Product ID.', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'valuepack-addons'),
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
                'label' => esc_html__('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 100, 'step' => 1],
                    'em' => ['min' => 1, 'max' => 10],
                    'rem' => ['min' => 1, 'max' => 10],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-size-guide-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-size-guide-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Icon Color', 'valuepack-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-size-guide-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .vp-size-guide-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Size Guide',
                'placeholder' => esc_html__('Enter text here', 'valuepack-addons'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1d1d1d',
                'selectors' => [
                    '{{WRAPPER}} .vp-size-guide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-size-guide-title',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 15,
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '500',
                    ],   
                    'font_family' => [
                        'default' => 'plus jakarta sans',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'text_icon_spacing',
            [
                'label' => esc_html__('Space Between', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                    '%' => ['min' => 0, 'max' => 100],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-size-guide-icon-title' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .vp-size-guide-icon-title' => 'display: flex; align-items: {{VALUE}};',
                ],
                'toggle' => true,
            ]
        );

        $this->add_control(
            'justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                    '{{WRAPPER}} .vp-size-guide-icon-title' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'column',
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
                    '{{WRAPPER}} .vp-size-guide-icon-title' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
       
        if (is_singular('product')) {
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
        


        $size_guide_image = $product_id ? get_post_meta($product_id, 'vp_size_guide_image', true) : '';

        if (!empty($size_guide_image)) {
            echo '<div class="vp-size-guide-container">';
            echo '<div class="vp-size-guide-icon-title">';

            if (!empty($settings['icon']['value'])) {
                echo '<span class="vp-size-guide-icon">';
                Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
                echo '</span>';
            }

            if (!empty($settings['title'])) {
                echo '<span class="vp-size-guide-title">' . esc_html($settings['title']) . '</span>';
            }

            echo '</div>';

            echo '<div class="vp-size-guide-canvas">';
            echo '<div class="vp-size-guide-image">';
            echo '<span class="vp-size-guide-close">';
            echo value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
            echo '</span><img src="' . esc_url($size_guide_image) . '" alt="' . esc_attr__('Size Guide', 'valuepack-addons') . '" />';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }else{
             echo value_pack_get_quoute_text_elementor_preview(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              return ;  
        }
    }
}
