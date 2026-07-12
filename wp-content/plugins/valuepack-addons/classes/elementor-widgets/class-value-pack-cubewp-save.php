<?php

/**
 * CubeWp Save Widget for Value Pack
 * 
 * @package ValuePack
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Check if Elementor is loaded
if (!class_exists('Elementor\Widget_Base')) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class Value_Pack_CubeWp_Save extends Value_Pack_Widget_Base
{

    public function get_name()
    {
        return 'vp_single_product_save';
    }

    public function get_title()
    {
        return __('CubeWP Save', 'valuepack-addons');
    }

    public function get_icon()
    {
        return 'eicon-star vpack-icon';
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
                'label' => __('Icons', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Post ID', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => value_pack_get_any_one_product_id(),
                'description' => __('Add ID if you want to share specific Post', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'save_icon',
            [
                'label' => __('Save Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'far fa-heart',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->add_control(
            'saved_icon',
            [
                'label' => __('Saved Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-heart',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_text',
            [
                'label' => __('Text', 'valuepack-addons'),
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
            'defualt_text',
            [
                'label' => __('Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Save', 'valuepack-addons'),
                'placeholder' => __('Type your text here', 'valuepack-addons'),
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'saved_text',
            [
                'label' => __('Saved Text', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Saved', 'valuepack-addons'),
                'placeholder' => __('Type your text here', 'valuepack-addons'),
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Normal State
        $this->start_controls_section('vp_style_normal', [
            'label' => esc_html__('Style', 'valuepack-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('style_tabs');

        // Normal Tab
        $this->start_controls_tab(
            'style_normal_tab',
            [
                'label' => __('Normal', 'valuepack-addons'),
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
                    '{{WRAPPER}} .vp-product-save-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-product-save-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon Color', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-product-save-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .vp-product-save-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_style',
            [
                'label' => __('Border Style', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'valuepack-addons'),
                    'solid' => __('Solid', 'valuepack-addons'),
                    'dashed' => __('Dashed', 'valuepack-addons'),
                    'dotted' => __('Dotted', 'valuepack-addons'),
                    'double' => __('Double', 'valuepack-addons'),
                    'groove' => __('Groove', 'valuepack-addons'),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'border-style: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => __('Border Width', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-save-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => __('Typography', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-save-text',
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label'    => __('Icon Hover Color', 'valuepack-addons'),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'default'  => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main:hover .vp-product-save-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .cwp-main:hover .vp-product-save-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_hover_color',
            [
                'label' => __('Background Hover Color', 'valuepack-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_hover_color',
            [
                'label' => __('Border Hover Color', 'valuepack-addons'), // Fixed label
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'text_hover_color',
            [
                'label' => __('Text Hover Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main:hover .vp-save-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Directions Section (moved to separate section for better organization)
        $this->start_controls_section(
            'vp_flex_style',
            [
                'label' => esc_html__('Layout', 'valuepack-addons'), // Better label
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_text_gap',
            [
                'label' => __('Gap Between Icon and Text', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cwp-main .vp-product-save-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cwp-main.flex-column .vp-product-save-icon' => 'margin-right: 0; margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cwp-main.flex-column-reverse .vp-product-save-icon' => 'margin-right: 0; margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cwp-main.flex-row-reverse .vp-product-save-icon' => 'margin-right: 0; margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label' => __('Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'valuepack-addons'),
                    'row-reverse' => __('Row Reverse', 'valuepack-addons'),
                    'column' => __('Column', 'valuepack-addons'),
                    'column-reverse' => __('Column Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'display:flex;flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'align_items',
            [
                'label' => __('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'stretch' => __('Stretch', 'valuepack-addons'),
                    'baseline' => __('Baseline', 'valuepack-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'justify_content',
            [
                'label' => __('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flex-start' => __('Flex Start', 'valuepack-addons'),
                    'center' => __('Center', 'valuepack-addons'),
                    'flex-end' => __('Flex End', 'valuepack-addons'),
                    'space-between' => __('Space Between', 'valuepack-addons'),
                    'space-around' => __('Space Around', 'valuepack-addons'),
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .cwp-main' => 'justify-content: {{VALUE}};',
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
                    '{{WRAPPER}} .vp-product-save-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (!empty($settings['product_id'])) {
            $product_id = $settings['product_id'];
        } else {
            global $post;
            $product_id = $post->ID;
        }

 
        if (empty($product_id)) {
            return;
        }

        $defualt_text = !empty($settings['defualt_text']) ? $settings['defualt_text'] : 'Save';
        $saved_text = !empty($settings['saved_text']) ? $settings['saved_text'] : 'Saved';
        $SavedClass = 'cwp-save-post';
        if ($product_id && class_exists('CubeWp_Saved')) {
            $SavedClass = CubeWp_Saved::is_cubewp_post_saved($product_id, false);
        }

        $icon_html = '';
        $icon_html = '<span class="vp-product-save-icon">';
        if (!empty($settings['save_icon']['value'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['save_icon'], ['aria-hidden' => 'true']);
            $icon_html .= ob_get_clean();
        }
        if (!empty($settings['saved_icon']['value'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['saved_icon'], ['aria-hidden' => 'true']);
            $icon_html .= ob_get_clean();
        }
        $icon_html .= '</span>';

        // Check if "Show Text" is enabled
        $show_text = !empty($settings['show_text']) && $settings['show_text'] === 'yes';

?>
        <div class="vp-product-save-container">
            <div class="cwp-single-save-btns cwp-single-widget">
                <span class="cwp-main <?php echo esc_attr($SavedClass); ?>" data-pid="<?php echo esc_attr($product_id); ?>">
                    <?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                    ?>
                    <?php if ($show_text) : ?>
                        <span class="vp-save-text defualt"><?php echo esc_attr($defualt_text); ?></span>
                        <span class="vp-save-text saved"><?php echo esc_attr($saved_text); ?></span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
<?php
    }
}

?>