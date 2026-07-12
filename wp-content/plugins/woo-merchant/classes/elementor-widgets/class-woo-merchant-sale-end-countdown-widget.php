<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class Woo_Merchant_Sale_End_Countdown_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'wm_sale_end_countdown';
    }

    public function get_title()
    {
        return __('Sale End Countdown', 'woo-merchant');
    }

    public function get_icon()
    {
        return 'eicon-countdown';
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
            'layout_style',
            [
                'type'        => Controls_Manager::SELECT,
                'multiple'    => false,
                'label'       => esc_html__('Layout Styles', 'woo-merchant'),
                'description' => '',
                'options'     => [
                    'style_1' => esc_html__('Style 1', 'woo-merchant'), // Style 1 description
                    'style_2' => esc_html__('Style 2', 'woo-merchant'),
                ],
                'default'     => 'style_1',
            ]
        );

        $this->add_control(
            'wm_title',
            [
                'label' => __('Title', 'woo-merchant'),
                'type' => Controls_Manager::TEXT,
                'default' => __('HURRY UP! SALE ENDS IN:', 'woo-merchant'),
                'placeholder' => __('Enter text here', 'woo-merchant'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
        // Validate style against allowed values
        $allowed_styles = ['style_1', 'style_2'];
        $style = in_array($settings['layout_style'], $allowed_styles) 
            ? $settings['layout_style'] 
            : 'style_1';
            
        // Escape title output
        $title = !empty($settings['wm_title']) 
            ? esc_html($settings['wm_title'])
            : esc_html__('HURRY UP! SALE ENDS IN:', 'woo-merchant');
            
        if (is_product()) {
            $product_id = get_the_ID();
            if (!$product_id || !get_post($product_id) || get_post_type($product_id) !== 'product') {
                return;
            }
            
            // Output with proper escaping
            $countdown_html = woo_merchant_get_sale_end_countdown($product_id, $title, $style);
            echo wp_kses_post($countdown_html);
        }
    }
}
