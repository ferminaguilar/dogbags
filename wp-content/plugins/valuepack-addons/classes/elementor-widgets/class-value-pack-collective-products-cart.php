<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

/**
 * CubeWP Nav Menu Widgets.
 *
 * Elementor Widget For Nav Menu By CubeWP.
 *
 * @since 1.0.0
 */
class Value_Pack_Collective_Products_Cart extends Value_Pack_Widget_Base
{

	protected $mega_menu_index = 1;

	public function get_name()
	{
		return 'Collective_Products_Cart';
	}

	public function get_title()
	{
		return esc_html__('Collective Products Cart', 'valuepack-addons');
	}

	public function get_icon()
	{
		return 'eicon-product-add-to-cart vpack-icon';
	}

	public function get_categories()
	{
		return array('value_pack');
	}

	public function get_keywords()
	{
		return array(
			'products',
			'product ids',
			'custom products',
			'collection',
			'ecommerce',
			'woocommerce',
			'shop',
			'catalog',
			'showcase',
			'display'
		);
	}

	protected function register_controls()
	{
		$this->start_controls_section('product_settings', array(
			'label' => esc_html__('Product Settings', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		$this->add_control('enable_variations', [
			'type'        => \Elementor\Controls_Manager::SWITCHER,
			'label'       => esc_html__('Enable Variations', 'valuepack-addons'),
			'description' => esc_html__('Enable this to allow variation selection (e.g., color, size).', 'valuepack-addons'),
			'label_on'    => esc_html__('Yes', 'valuepack-addons'),
			'label_off'   => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default' => 'no',
		]);

		$this->add_control('product_ids', array(
			'type'        => Controls_Manager::TEXT,
			'label'       => esc_html__('Product By IDs', 'valuepack-addons'),
			'description' => esc_html__('Add Product IDs separated by commas', 'valuepack-addons'),
			'placeholder' => '125,236,2203',
			'default'     => ''
		));

		$default_products = value_pack_get_woocommerce_products();
		$default_list = '';
		$count = 0;
		foreach ($default_products as $id => $name) {
			if ($count >= 5) break; // limit to 5 default items
			$default_list .= '<li class="vp-multi-product-result-item" data-id="' . esc_attr($id) . '">' . esc_html($name) . '</li>';
			$count++;
		}

		$this->add_control(
			'product_ids_search_html',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '
					<div class="vp-multi-product-search-wrapper" style="display: none;">
						<input type="text" class="vp-multi-product-search" placeholder="Start typing product name..." />
						<ul class="vp-multi-product-results">' . $default_list . '</ul>
						<div class="vp-selected-products"></div>
					</div>
				',
				'content_classes' => 'vp-multi-product-search-box',
			]
		);


		$this->add_control('button_title', array(
			'type'        => Controls_Manager::TEXT,
			'label'       => esc_html__('Button Title', 'valuepack-addons'),
			'default'     => __('Add All to Cart', 'valuepack-addons')
		));
		$this->add_control(
			'button_title_arrow',
			[
				'label' => esc_html__('Enable Button Arrow', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section('product_settings_style', array(
			'label' => esc_html__('General Styling', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_STYLE,
		));
		$this->start_controls_tabs('button_style_tabs');

		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__('Normal', 'valuepack-addons'),
			]
		);

		$this->add_responsive_control(
			'color_button',
			[
				'label' => esc_html__('Button Text Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a ' => 'color: {{VALUE}};',
				]
			]
		);
		$this->add_responsive_control(
			'bg_color_button',
			[
				'label' => esc_html__('Button Background Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#1d1d1d',
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a ' => 'background-color: {{VALUE}};',
				]
			]
		);
		// Border Control
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => esc_html__('Button Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a',
			]
		);
		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__('Button Border Radius', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__('Button Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__('Button Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__('Title Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-women-collective-products ul li .product-hover-cards-content a',
			]
		);

		$this->add_responsive_control(
			'title_color',
			[
				'label' => esc_html__('Title Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul li .product-hover-cards-content a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__('Title Margin', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul li .product-hover-cards-content a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		// Position Type Control
		$this->add_control(
			'price_position_type',
			[
				'label'   => esc_html__('Price Position', 'valuepack-addons'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static'   => esc_html__('Static', 'valuepack-addons'),
					'absolute' => esc_html__('Absolute', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul li .collective-p-price' => 'position: {{VALUE}};',
				],
			]
		);

		// Top Position Toggle
		$this->add_control(
			'price_pos_top_enable',
			[
				'label' => esc_html__('Enable Top Position', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'price_position_type' => 'absolute',
				],
			]
		);

		// Top Value
		$this->add_responsive_control(
			'price_top',
			[
				'label' => esc_html__('Top', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul li .collective-p-price' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'price_position_type' => 'absolute',
					'price_pos_top_enable' => 'yes',
				],
			]
		);

		// Left Position Toggle
		$this->add_control(
			'price_pos_left_enable',
			[
				'label' => esc_html__('Enable Right Position', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'price_position_type' => 'absolute',
				],
			]
		);

		// Left Value
		$this->add_responsive_control(
			'price_left',
			[
				'label' => esc_html__('Right', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul li .collective-p-price' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'price_position_type' => 'absolute',
					'price_pos_left_enable' => 'yes',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'label' => esc_html__('Price Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-women-collective-products ul li  .woocommerce-Price-amount',
			]
		);

		$this->add_responsive_control(
			'price_color',
			[
				'label' => esc_html__('Price Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul li  .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'separator_border',
				'label' => esc_html__('Separator Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-women-collective-products ul .product-hover-cards',
			]
		);

		$this->add_responsive_control(
			'product_card_padding',
			[
				'label' => esc_html__('Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 20,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul .product-hover-cards' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_card_margin',
			[
				'label' => esc_html__('Margin', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 20,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul .product-hover-cards' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__('Hover', 'valuepack-addons'),
			]
		);

		$this->add_responsive_control(
			'color_button_hover',
			[
				'label' => esc_html__('Button Hover Text Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a:hover ' => 'color: {{VALUE}};',
				]
			]
		);
		$this->add_responsive_control(
			'bg_color_button_hover',
			[
				'label' => esc_html__('Button Hover Background Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .wc-checkout-url a:hover ' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'title_color:hover',
			[
				'label' => esc_html__('Title Hover Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul li:hover .product-hover-cards-content a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();

		$this->start_controls_section('product_image_settings_style', array(
			'label' => esc_html__('Product Image Styling', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_STYLE,
		));

		$this->add_control(
			'enable_image_styling',
			[
				'label' => esc_html__('Enable Image Styling', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->add_responsive_control(
			'image_gap_titles',
			[
				'label' => esc_html__('Space', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 28,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products ul .product-hover-cards' => 'gap: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__('Image Width', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_image_styling' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__('Image Height', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_image_styling' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_background_color',
			[
				'label' => esc_html__('Image Background Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image img' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_image_styling' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_object_fit',
			[
				'label' => esc_html__('Image Object Fit', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'fill' => esc_html__('Fill', 'valuepack-addons'),
					'contain' => esc_html__('Contain', 'valuepack-addons'),
					'cover' => esc_html__('Cover', 'valuepack-addons'),
					'none' => esc_html__('None', 'valuepack-addons'),
					'scale-down' => esc_html__('Scale Down', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image img' => 'object-fit: {{VALUE}};',
				],
				'condition' => [
					'enable_image_styling' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__('Image Border Radius', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wc-women-collective-products .product-hover-cards-image' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_image_styling' => 'yes',
				],
			]
		);



		$this->end_controls_section();

		$this->start_controls_section('product_counter_style', array(
			'label' => esc_html__('Counter', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_STYLE,
		));

		$this->add_control(
			'enable_counter',
			[
				'label' => esc_html__('Enable Counter', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'counter_font_size',
			[
				'label' => esc_html__('Counter Font Size', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0.5,
						'max' => 5,
						'step' => 0.1,
					],
					'%' => [
						'min' => 50,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .counter' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_font_color',
			[
				'label' => esc_html__('Counter Font Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .counter' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'counter_typography',
				'label' => esc_html__('Counter Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-women-collective-products .counter',
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_position_top',
			[
				'label' => esc_html__('Counter Position - Top', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .counter' => 'top: {{SIZE}}{{UNIT}};position: absolute;z-index: 999;display: flex;align-items: center;justify-content: center;',
					'{{WRAPPER}} .product-hover-cards' => 'position: relative;',
				],
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_position_left',
			[
				'label' => esc_html__('Counter Position - Left', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .counter' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_size',
			[
				'label' => esc_html__('Counter Size', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 50,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .counter' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_bg_color',
			[
				'label' => esc_html__('Counter Background Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff0000',
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .counter' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_border_radius',
			[
				'label' => esc_html__('Counter Border Radius', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-women-collective-products .counter' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_counter' => 'yes',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'variation_span_border_counter',
				'label' => esc_html__('Border', 'valuepack-addons'),
				'selector'  => '{{WRAPPER}} .wc-women-collective-products .counter',
			]
		);

		$this->end_controls_section();

		// Start new section: Variation Styling
		$this->start_controls_section('variation_styling_section', [
			'label'     => esc_html__('Variation Styling', 'valuepack-addons'),
			'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => ['enable_variations' => 'yes'],
		]);

		// -------------------------------------
		// Heading: Title Styling
		// -------------------------------------
		$this->add_control('variation_title_heading', [
			'type'  => \Elementor\Controls_Manager::HEADING,
			'label' => esc_html__('Title Styling', 'valuepack-addons'),
			'separator' => 'before',
		]);

		$this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'variation_title_typography',
			'selector' => '{{WRAPPER}} .product-hover-cards .attribute-container h3 span,{{WRAPPER}} .product-hover-cards .attribute-container,{{WRAPPER}} .product-hover-cards .wm-product-attributes::after',
		]);

		$this->add_control('variation_title_color', [
			'label'     => esc_html__('Text Color', 'valuepack-addons'),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-hover-cards .attribute-container h3,{{WRAPPER}} .product-hover-cards .attribute-container,{{WRAPPER}} .product-hover-cards .wm-product-attributes::after' => 'color: {{VALUE}};',
			],
		]);

		// -------------------------------------
		// Heading: Active Attribute Span
		// -------------------------------------
		$this->add_control('variation_active_heading', [
			'type'  => \Elementor\Controls_Manager::HEADING,
			'label' => esc_html__('Active Attribute Styling', 'valuepack-addons'),
			'separator' => 'before',
		]);

		$this->add_control('variation_active_border', [
			'label'     => esc_html__('Border', 'valuepack-addons'),
			'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors' => [
				'{{WRAPPER}} .product-hover-cards .wm-product-attributes ul .active span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
			],
		]);

		$this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
			'name'     => 'variation_active_shadow',
			'selector' => '{{WRAPPER}} .product-hover-cards .wm-product-attributes ul .active span',
		]);

		// -------------------------------------
		// Heading: Attribute Span Styling
		// -------------------------------------
		$this->add_control('variation_span_heading', [
			'type'  => \Elementor\Controls_Manager::HEADING,
			'label' => esc_html__('Attribute Span Styling', 'valuepack-addons'),
			'separator' => 'before',
		]);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'variation_span_border',
				'label' => esc_html__('Border', 'valuepack-addons'),
				'selector'  => '{{WRAPPER}} .product-hover-cards .wm-product-attributes ul span',
			]
		);

		$this->add_control('variation_span_padding', [
			'label'      => esc_html__('Padding', 'valuepack-addons'),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors'  => [
				'{{WRAPPER}} .product-hover-cards .wm-product-attributes ul span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]);

		$this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
			'name'     => 'variation_span_typography',
			'selector' => '{{WRAPPER}} .product-hover-cards .wm-product-attributes ul span',
		]);

		$this->add_control('variation_span_color', [
			'label'     => esc_html__('Text Color', 'valuepack-addons'),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-hover-cards .wm-product-attributes ul span' => 'color: {{VALUE}};',
			],
		]);

		$this->add_control('variation_span_margin', [
			'label'      => esc_html__('Margin', 'valuepack-addons'),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors'  => [
				'{{WRAPPER}} .product-hover-cards .wm-product-attributes ul span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]);

		// -------------------------------------
		// Heading: Attributes Container Styling
		// -------------------------------------
		$this->add_control('variation_ul_heading', [
			'type'  => \Elementor\Controls_Manager::HEADING,
			'label' => esc_html__('Attributes Container Styling', 'valuepack-addons'),
			'separator' => 'before',
		]);

		$this->add_control('variation_ul_padding', [
			'label'      => esc_html__('Padding', 'valuepack-addons'),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors'  => [
				'{{WRAPPER}} .product-hover-cards .wm-product-attributes ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]);


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'variation_ul_border',
				'label' => esc_html__('Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .product-hover-cards .wm-product-attributes ul',
			]
		);

		$this->add_control('variation_ul_border_radius', [
			'label'      => esc_html__('Border Radius', 'valuepack-addons'),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors'  => [
				'{{WRAPPER}} .product-hover-cards .wm-product-attributes ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]);

		$this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
			'name'     => 'variation_ul_shadow',
			'selector' => '{{WRAPPER}} .product-hover-cards .wm-product-attributes ul',
		]);

		$this->end_controls_section();

		// Start new section for Ratings
		$this->start_controls_section(
			'section_rating_manager',
			[
				'label' => __('Product Rating Settings', 'valuepack-addons'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		// Enable Rating Styling (as a select toggle)
		$this->add_control(
			'enable_rating_styles_show',
			[
				'label' => __('Show Ratings', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'flex' => __('Show', 'valuepack-addons'),
					'none' => __('Hide', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .product-rating.woocommerce' => 'display:{{VALUE}} !important;',
				],
			]
		);
		// Enable Rating Styling (as a select toggle)
		$this->add_control(
			'enable_rating_styles',
			[
				'label' => __('Enable Positions', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => __('Enable', 'valuepack-addons'),
					'no' => __('Disable', 'valuepack-addons'),
				],
				'condition' => [
					'enable_rating_styles_show' => 'block',
				],
			]
		);

		// Order Control
		$this->add_control(
			'rating_order_title',
			[
				'label' => __('Title Flex Order', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => __('1 (First)', 'valuepack-addons'),
					'1' => __('2', 'valuepack-addons'),
					'2' => __('3', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}}  .product-hover-cards-content a' => 'order: {{VALUE}};',
				],
				'condition' => [
					'enable_rating_styles' => 'yes',
					'enable_rating_styles_show' => 'flex',
				],
			]
		);
		$this->add_control(
			'rating_order',
			[
				'label' => __('Rating Flex Order', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => __('1 (First)', 'valuepack-addons'),
					'1' => __('2', 'valuepack-addons'),
					'2' => __('3', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .product-rating.woocommerce' => 'order: {{VALUE}};',
					'{{WRAPPER}} .product-hover-cards-content' => 'display: flex;flex-direction: column;',
				],
				'condition' => [
					'enable_rating_styles' => 'yes',
					'enable_rating_styles_show' => 'flex',
				],
			]
		);
		$this->add_control(
			'rating_order_price',
			[
				'label' => __('Price Flex Order', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => __('1 (First)', 'valuepack-addons'),
					'1' => __('2', 'valuepack-addons'),
					'2' => __('3', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .collective-p-price' => 'order: {{VALUE}};',
				],
				'condition' => [
					'enable_rating_styles' => 'yes',
					'enable_rating_styles_show' => 'flex',
				],
			]
		);

		// Text Color Control
		$this->add_control(
			'rating_color',
			[
				'label' => __('Rating Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-rating.woocommerce .star-rating::before,{{WRAPPER}} .woocommerce .star-rating span::before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_rating_styles_show' => 'flex',
				],
			]
		);

		// Margin Control
		$this->add_responsive_control(
			'rating_margin',
			[
				'label' => __('Rating Margin', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .product-rating.woocommerce' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_rating_styles_show' => 'flex',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_active_settings();
		$settings   = $this->get_settings_for_display();
		$post_IDS1 = sanitize_text_field($settings['product_ids']);
		$enable_variations = $settings['enable_variations'];
		$enable_counter = sanitize_text_field($settings['enable_counter']);
		$button_title = isset($settings['button_title']) ? sanitize_text_field($settings['button_title']) : __('Add All To Cart', 'valuepack-addons');

		$product_ids_check = array_map('absint', explode(',', $post_IDS1));

		$product_ids = array();
		if (empty($post_IDS1)) {
			$default_products = value_pack_get_woocommerce_products();
			$default_ids = array_slice(array_keys($default_products), 0, 3);
			$product_ids = implode(',', $default_ids);
		} else {

			$product_ids = array_filter(array_unique($product_ids_check));

			$valid_products = [];
			foreach ($product_ids as $pid) {
				if (get_post_status($pid) === 'publish' && get_post_type($pid) === 'product') {
					$valid_products[] = $pid;
				}
			}
			if (empty($valid_products)) {
				$default_products = value_pack_get_woocommerce_products();
				$default_ids = array_slice(array_keys($default_products), 0, 3);
				$valid_products = $default_ids;
			}

			$product_ids = implode(',', $valid_products);
		}
		$product_ids = array_map('absint', explode(',', $product_ids));
 
		$total_price = 0.0;
		if (class_exists('WooCommerce')) {
			foreach ($product_ids as $product_id) {
				if (empty($product_id)) {
					continue;
				}

				$product = wc_get_product($product_id);

				if ($product) {
					// If variable product, get first variation price
					if ($product->is_type('variable')) {
						$available_variations = $product->get_available_variations();
						if (! empty($available_variations)) {
							$first_variation = $available_variations[0];
							$variation_price = isset($first_variation['display_price']) ? $first_variation['display_price'] : 0;
							$total_price += floatval($variation_price);
						}
					} else {
						// Simple product or other types
						$price = $product->get_price();
						$total_price += floatval($price);
					}
				}
			}
			$currency_symbol = get_woocommerce_currency_symbol();
			$totalPrice =    number_format($total_price, 2);
			$enable_variation = '';
			if ($enable_variations == 'yes') {
				$enable_variation = 'variation-enables';
			}
			if (is_array($product_ids) && !empty($product_ids)) {

?>
				<div class="wc-women-collective-products <?php echo esc_attr($enable_variation); ?>">
					<ul>
						<?php
						$count = 1;
						foreach ($product_ids as $keys => $ids) {
							$productid = wc_get_product($ids);
							if ($productid) {
								$postThumbnail = $this->wc_woo_get_images($ids);
								$postTitle = get_the_title($ids);
								$posturl = get_permalink($ids);
								if ($productid->is_type('variable')) {
									$available_variations = $productid->get_available_variations();
									if (!empty($available_variations)) {
										$first_variation = $available_variations[0];
										$variation_price = isset($first_variation['display_price']) ? $first_variation['display_price'] : 0;
										$product_price = wc_price($variation_price);
									} else {
										$product_price = wc_price(0); // fallback
									}
								} else {
									$product_price = wc_price($productid->get_price());
								}

								$average = $productid->get_average_rating();
								$reviewStarts =  wc_get_rating_html($average);
								$review_count = $productid->get_review_count();
								global $product;
								setup_postdata(get_post($ids));

								// Ensure global $post is available
								global $post;
								$post = get_post($ids);

						?>
								<li>
									<div class="product-hover-cards product-id-<?php echo esc_attr(trim($ids)); ?>">
										<?php if ($enable_counter == 'yes') { ?>
											<div class="counter">
												<span><?php echo esc_html($count); ?></span>
											</div>
										<?php } ?>

										<div class="product-hover-cards-image">
											<a href="<?php echo esc_url($posturl); ?>"><img src="<?php echo esc_url($postThumbnail); ?>" alt="image"></a>
										</div>
										<div class="product-hover-cards-content">
											<a href="<?php echo esc_url($posturl); ?>"><?php echo esc_attr($postTitle); ?></a>
											<?php if (!empty($reviewStarts)) { ?>
												<div class="product-rating woocommerce">
													<?php echo wc_get_rating_html($average); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													<span class="counts">(<?php echo esc_html($review_count); ?>)</span>
												</div>
											<?php }
											if ($enable_variations == 'yes') {
												woocommerce_template_single_add_to_cart();
											}
											?>
											<h4 class="collective-p-price"><?php echo wp_kses_post($product_price); ?></h4>
										</div>
									</div>
								</li>
						<?php
							} else {
								echo esc_html__('This product is not available.', 'valuepack-addons');
							}
							$count++;
						}

						?>
					</ul>
					<div class="wc-checkout-url">

						<a class="add-multiple-to-cart <?php echo esc_attr($enable_variation); ?>" data-pID="<?php echo esc_attr($post_IDS1); ?>"><?php echo esc_html($button_title); ?> - <?php echo esc_html($currency_symbol); ?><span><?php echo esc_html($totalPrice); ?></span>
							<?php if ('yes' === $settings['button_title_arrow']) : ?>
								<svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.6725 2.19177H3.57657C3.46054 2.19177 3.34926 2.23787 3.26721 2.31991C3.18516 2.40196 3.13907 2.51324 3.13907 2.62927C3.13907 2.7453 3.18516 2.85658 3.26721 2.93863C3.34926 3.02068 3.46054 3.06677 3.57657 3.06677H10.8146L1.83751 12.0443C1.79411 12.0842 1.75924 12.1325 1.73499 12.1863C1.71074 12.24 1.6976 12.2982 1.69638 12.3571C1.69516 12.4161 1.70587 12.4747 1.72788 12.5294C1.74988 12.5841 1.78273 12.6338 1.82443 12.6755C1.86613 12.7172 1.91584 12.7501 1.97056 12.7721C2.02528 12.7941 2.08389 12.8048 2.14285 12.8036C2.20182 12.8024 2.25993 12.7892 2.31369 12.765C2.36745 12.7407 2.41575 12.7059 2.4557 12.6625L11.4332 3.68496V10.9234C11.4332 11.0394 11.4793 11.1507 11.5613 11.2328C11.6434 11.3148 11.7547 11.3609 11.8707 11.3609C11.9867 11.3609 12.098 11.3148 12.1801 11.2328C12.2621 11.1507 12.3082 11.0394 12.3082 10.9234V2.8279C12.308 2.65933 12.2409 2.49773 12.1218 2.3785C12.0026 2.25927 11.8411 2.19212 11.6725 2.19177Z" fill="white" />
								</svg>
							<?php endif; ?>
						</a>
					</div>
				</div>
<?php
			}
		}
	}
	private function wc_woo_get_images($tempID)
	{
		if (has_post_thumbnail($tempID)) {
			$getURL1 = get_the_post_thumbnail_url($tempID);
		} else {
			$getURL1 =  esc_url('https://placehold.co/800?text=Placeholder');;
		}
		return $getURL1;
	}

	private	function generate_add_to_cart_url($product_ids)
	{
		$base_url = wc_get_cart_url();
		$nonce = wp_create_nonce('vp_add_multiple_to_cart');
		$query_params = [];

		foreach ($product_ids as $product_id) {
			if (empty($product_id)) {
				continue;
			}
			$product = wc_get_product($product_id);
			if ($product) {
				$query_params[] = "add-to-cart={$product_id}&quantity=1&_wpnonce={$nonce}";
			}
		}
		// Combine the base URL with the query parameters
		$add_to_cart_url = add_query_arg(implode('&', $query_params), '', $base_url);

		return $add_to_cart_url;
	}
}
