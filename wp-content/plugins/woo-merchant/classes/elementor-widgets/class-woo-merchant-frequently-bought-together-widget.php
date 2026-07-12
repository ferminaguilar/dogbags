<?php
// Ensure Elementor is loaded
if (!class_exists('\Elementor\Widget_Base')) {
    return;
}

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class Woo_Merchant_Frequently_Bought_Together_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'wm_frequently_bought_together';
    }

    public function get_title()
    {
        return __('Frequently Bought Together', 'woo-merchant');
    }

    public function get_icon()
    {
        return 'eicon-products';
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
					'style1' => esc_html__('Style 1', 'woo-merchant'), // Style 1 description
					'style2' => esc_html__('Style 2', 'woo-merchant'),
				],
				'default'     => 'style1',
			]
		);	

        $this->end_controls_section();
    }

    /**
     * Render widget output.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $style = isset($settings['layout_style']) ? sanitize_text_field($settings['layout_style']) : 'style1';

        if (is_product()) {
            $product_id = get_the_ID();

            if ($product_id && is_numeric($product_id)) {
                echo woo_merchant_display_frequently_bought_together($style);
            }
        }
    }
}

?>
