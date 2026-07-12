<?php

/**
 * Value Pack Products by Type Widget
 *
 * Elementor widget to display WooCommerce products by type (related, recently viewed, upsells, cross-sells).
 *
 * @package ValuePack
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Dimensions;
use Elementor\Group_Control_Typography;

/**
 * Class Value_Pack_Products_By_Type
 */
class Value_Pack_Products_By_Type extends Value_Pack_Widget_Base
{

	protected $mega_menu_index = 1;

	public function get_name()
	{
		return 'vp_products_by_type';
	}

	public function get_title()
	{
		return esc_html__('Products by Type', 'valuepack-addons');
	}

	public function get_icon()
	{
		return 'eicon-products vpack-icon';
	}

	public function get_categories()
	{
		return array('value_pack');
	}

	public function get_keywords()
	{
		return array(
			'products',
			'related',
			'upsell',
			'cross-sell',
			'recently viewed',
			'ecommerce',
			'woocommerce',
			'recommendations',
			'shopping',
			'cart'
		);
	}

	private function get_wordpress_menu_options()
	{
		$menus = wp_get_nav_menus();
		$menu_options = [];


		foreach ($menus as $menu) {
			$menu_options[$menu->term_id] = $menu->name;
		}

		return $menu_options;
	}

	protected function register_controls()
	{
		$this->start_controls_section('product_settings', array(
			'label' => esc_html__('Product Settings', 'valuepack-addons'),
			'tab' => Controls_Manager::TAB_CONTENT,
		));

		$this->add_control('product_section_title', array(
			'type' => Controls_Manager::TEXT,
			'label' => esc_html__('Section Heading', 'valuepack-addons'),
			'description' => __('Enter the product section heading.', 'valuepack-addons'),
			'label_block' => true,
			'placeholder' => __('e.g Recently Viewed', 'valuepack-addons'),
		));

		$this->add_control('product_type', array(
			'type' => Controls_Manager::SELECT,
			'label' => esc_html__('Products Type', 'valuepack-addons'),
			'options' => array(
				'releated_products' => esc_html__('Related Products', 'valuepack-addons'),
				'recent_views' => esc_html__('Recent Viewd Products', 'valuepack-addons'),
				'up_sells' => esc_html__('Up-Sells Products', 'valuepack-addons'),
				'cross_sells' => esc_html__('Cross-Sells Products', 'valuepack-addons'),
			),
			'default' => 'releated_products'
		));
		$this->add_control('product_id', array(
			'type' => Controls_Manager::TEXT,
			'label' => esc_html__('Product ID', 'valuepack-addons'),
			'description' => __('Enter the WooCommerce product ID. If you want to use this element on the product single page, leave this field empty.', 'valuepack-addons'),
			'label_block' => true,
			'default' => value_pack_get_any_one_product_id(),
			'placeholder' => __('e.g., 123', 'valuepack-addons'),
		));

		$this->add_control('posts_per_page', array(
			'type' => Controls_Manager::SELECT,
			'label' => esc_html__('Posts Per Page', 'valuepack-addons'),
			'options' => array(
				'-1' => esc_html__('Show All Posts', 'valuepack-addons'),
				'3' => esc_html__('Show 3 Posts', 'valuepack-addons'),
				'4' => esc_html__('Show 4 Posts', 'valuepack-addons'),
				'5' => esc_html__('Show 5 Posts', 'valuepack-addons'),
				'6' => esc_html__('Show 6 Posts', 'valuepack-addons'),
				'8' => esc_html__('Show 8 Posts', 'valuepack-addons'),
				'9' => esc_html__('Show 9 Posts', 'valuepack-addons'),
				'12' => esc_html__('Show 12 Posts', 'valuepack-addons'),
				'16' => esc_html__('Show 16 Posts', 'valuepack-addons'),
				'15' => esc_html__('Show 15 Posts', 'valuepack-addons'),
				'20' => esc_html__('Show 20 Posts', 'valuepack-addons')
			),
			'default' => '3'
		));
		if (function_exists('CWP')) {
			$this->add_control('layout', array(
				'type' => Controls_Manager::SELECT2,
				'label' => esc_html__('Product Style', 'valuepack-addons'),
				'options' => cubewp_post_card_styles('product'),
				'default' => 'style_2',
			));

			$this->add_responsive_control(
				'column_per_row',
				[
					'label' => esc_html__('No Of Columns Per Row', 'valuepack-addons'),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'auto',
					'device_args' => [
						\Elementor\Controls_Stack::RESPONSIVE_DESKTOP => [
							'default' => '4',
							'options' => [
								'auto' => esc_html__('Auto', 'valuepack-addons'),
								'1' => esc_html__('1 Column', 'valuepack-addons'),
								'2' => esc_html__('2 Columns', 'valuepack-addons'),
								'3' => esc_html__('3 Columns', 'valuepack-addons'),
								'4' => esc_html__('4 Columns', 'valuepack-addons'),
								'5' => esc_html__('5 Columns', 'valuepack-addons'),
								'6' => esc_html__('6 Columns', 'valuepack-addons'),
							],
						],
						\Elementor\Controls_Stack::RESPONSIVE_TABLET => [
							'default' => '3',
							'options' => [
								'auto' => esc_html__('Auto', 'valuepack-addons'),
								'1' => esc_html__('1 Column', 'valuepack-addons'),
								'2' => esc_html__('2 Columns', 'valuepack-addons'),
								'3' => esc_html__('3 Columns', 'valuepack-addons'),
								'4' => esc_html__('4 Columns', 'valuepack-addons'),
								'5' => esc_html__('5 Columns', 'valuepack-addons'),
								'6' => esc_html__('6 Columns', 'valuepack-addons'),
							],
						],
						\Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
							'default' => '2',
							'options' => [
								'auto' => esc_html__('Auto', 'valuepack-addons'),
								'1' => esc_html__('1 Column', 'valuepack-addons'),
								'2' => esc_html__('2 Columns', 'valuepack-addons'),
								'3' => esc_html__('3 Columns', 'valuepack-addons'),
								'4' => esc_html__('4 Columns', 'valuepack-addons'),
								'5' => esc_html__('5 Columns', 'valuepack-addons'),
								'6' => esc_html__('6 Columns', 'valuepack-addons'),
							],
						],
					],
					'frontend_available' => true,
				]
			);
		}

		$this->add_control(
			'slider_post_spacing',
			[
				'label' => esc_html__('Post Spacing', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => '0',
					'right' => '2',
					'bottom' => '0',
					'left' => '2',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .cwp-row .cwp-col-12' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'enable_related_slider',
			[
				'label' => esc_html__('Enable Slider', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'enable_slider_on_mobile' => '',
				],
			]
		);

		$this->add_control(
			'vp_overflow_control',
			[
				'label' => esc_html__('Overflow', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'hidden' => esc_html__('Hidden', 'valuepack-addons'),
					'initial' => esc_html__('Visible (Initial)', 'valuepack-addons'),
				],
				'default' => 'hidden',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider>.slick-list.draggable' => 'overflow: {{VALUE}};',
				],
			]
		);

		$this->add_control('slides_to_show', array(
			'type' => Controls_Manager::NUMBER,
			'label' => esc_html__('Slides To Show', 'valuepack-addons'),
			'default' => 3,
			'min' => 1,
			'max' => 10,
			'step' => 1,
			'description' => esc_html__('Number of slides to show at once in the slider.', 'valuepack-addons'),
			'condition' => [
				'enable_related_slider' => 'yes',
			],
		));

		$this->add_control(
			'slides_to_scroll',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__('Slides To Scroll', 'valuepack-addons'),
				'default' => 1,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'description' => esc_html__('Number of slides to scroll at once in the slider.', 'valuepack-addons'),
				'condition' => [
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__('Autoplay', 'valuepack-addons'),
				'default' => 'yes',
				'description' => esc_html__('Enable or disable autoplay for the slider.', 'valuepack-addons'),
				'condition' => [
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__('Autoplay Speed (ms)', 'valuepack-addons'),
				'default' => 2000,
				'min' => 500,
				'max' => 10000,
				'step' => 500,
				'description' => esc_html__('Set the speed for autoplay in milliseconds.', 'valuepack-addons'),
				'condition' => [
					'enable_related_slider' => 'yes',
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__('Infinite Loop', 'valuepack-addons'),
				'default' => 'yes',
				'description' => esc_html__('Enable or disable infinite loop for the slider.', 'valuepack-addons'),
				'condition' => [
					'enable_related_slider' => 'yes',
				],
			]
		);

		// arrow-settings

		$this->add_control(
			'enable_arrows',
			[
				'label' => esc_html__('Enable Arrows', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'prev_icon',
			[
				'label' => __('Previous Icon', 'valuepack-addons'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				],
				'label_block' => true,
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_icon',
			[
				'label' => __('Next Icon', 'valuepack-addons'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
				'label_block' => true,
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__('Icon Size (px)', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev i, {{WRAPPER}} .vp-product-slider .slick-next i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Icon Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev i, {{WRAPPER}} .vp-product-slider .slick-next i' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_background_color',
			[
				'label' => esc_html__('Icon Background Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev, {{WRAPPER}} .vp-product-slider .slick-next' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => esc_html__('Icon Hover Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev:hover i, {{WRAPPER}} .vp-product-slider .slick-next:hover i' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_hover_background_color',
			[
				'label' => esc_html__('Icon Hover Background Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev:hover, {{WRAPPER}} .vp-product-slider .slick-next:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'svg_color',
			[
				'label' => esc_html__('SVG Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev svg path, {{WRAPPER}} .vp-product-slider .slick-next svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'svg_hover_color',
			[
				'label' => esc_html__('SVG Hover Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF0000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev:hover svg path, {{WRAPPER}} .vp-product-slider .slick-next:hover svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'svg_width',
			[
				'label' => esc_html__('SVG Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 24,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev svg, {{WRAPPER}} .vp-product-slider .slick-next svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'svg_height',
			[
				'label' => esc_html__('SVG Height', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 24,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev svg, {{WRAPPER}} .vp-product-slider .slick-next svg' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_border',
			[
				'label' => esc_html__('Icon Border', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev, {{WRAPPER}} .vp-product-slider .slick-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__('Icon Border Radius', 'valuepack-addons'),
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
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev, {{WRAPPER}} .vp-product-slider .slick-next' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label' => esc_html__('Icon Border Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev, {{WRAPPER}} .vp-product-slider .slick-next' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_border_color_hover',
			[
				'label' => esc_html__('Icon Border Color on Hover', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev:hover, {{WRAPPER}} .vp-product-slider .slick-next:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_border_transition',
			[
				'label' => esc_html__('Transition Duration', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev, {{WRAPPER}} .vp-product-slider .slick-next' => 'transition: background-color {{SIZE}}s, color {{SIZE}}s, border-color {{SIZE}}s;',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => esc_html__('Icon Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev, {{WRAPPER}} .vp-product-slider .slick-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_top_position',
			[
				'label' => esc_html__('Icon Top Position', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev, {{WRAPPER}} .vp-product-slider .slick-next' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_prev_left_position',
			[
				'label' => esc_html__('Prev Icon Left Position', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_next_right_position',
			[
				'label' => esc_html__('Next Icon Right Position', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_arrows' => 'yes',
				],
			]
		);

		// dots-settings

		$this->add_control(
			'enable_dots',
			[
				'label' => esc_html__('Enable Dots', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_display_flex',
			[
				'label' => esc_html__('Dots Display', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'block' => esc_html__('Block', 'valuepack-addons'),
					'flex' => esc_html__('Flex', 'valuepack-addons'),
				],
				'default' => 'flex',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'display: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_flex_direction',
			[
				'label' => esc_html__('Dots Flex Direction', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'row' => esc_html__('Row', 'valuepack-addons'),
					'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
					'column' => esc_html__('Column', 'valuepack-addons'),
					'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_display_flex' => 'flex',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_justify_content',
			[
				'label' => esc_html__('Dots Justify Content', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
					'center' => esc_html__('Center', 'valuepack-addons'),
					'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
					'space-between' => esc_html__('Space Between', 'valuepack-addons'),
					'space-around' => esc_html__('Space Around', 'valuepack-addons'),
					'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_display_flex' => 'flex',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_align_items',
			[
				'label' => esc_html__('Dots Align Items', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
					'center' => esc_html__('Center', 'valuepack-addons'),
					'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
					'stretch' => esc_html__('Stretch', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_display_flex' => 'flex',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_container_width',
			[
				'label' => esc_html__('Dots Container Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_container_padding',
			[
				'label' => esc_html__('Dots Container Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_container_margin',
			[
				'label' => esc_html__('Dots Container Margin', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_gap',
			[
				'label' => __('Dots Gap', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'gap:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_position_select',
			[
				'label' => esc_html__('Dots Position', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'unset' => esc_html__('Unset', 'valuepack-addons'),
					'static' => esc_html__('Static', 'valuepack-addons'),
					'absolute' => esc_html__('Absolute', 'valuepack-addons'),
					'relative' => esc_html__('Relative', 'valuepack-addons'),
					'fixed' => esc_html__('Fixed', 'valuepack-addons'),
				],
				'default' => 'unset',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'position: {{VALUE}};',
				],
				'condition' => [
					'enable_related_slider' => 'yes',
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_top_position',
			[
				'label' => esc_html__('Dots Top Position', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_position_select' => 'absolute',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_bottom_position',
			[
				'label' => esc_html__('Dots Bottom Position', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_position_select' => 'absolute',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_left_position',
			[
				'label' => esc_html__('Dots Left Position', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_position_select' => 'absolute',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_right_position',
			[
				'label' => esc_html__('Dots Right Position', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_position_select' => 'absolute',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'related_dots_position_z_index',
			[
				'label' => esc_html__('Dots Z-Index', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -9999,
				'max' => 9999,
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots' => 'z-index: {{VALUE}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'related_dots_position_select' => 'absolute',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_padding',
			[
				'label' => esc_html__('Dots Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_border_style',
			[
				'label' => __('Dots Border Style', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'None',
				'options' => [
					'none' => __('None', 'valuepack-addons'),
					'solid' => __('Solid', 'valuepack-addons'),
					'dotted' => __('Dotted', 'valuepack-addons'),
					'dashed' => __('Dashed', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_border_width',
			[
				'label' => __('Dots Border Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
					'related_dots_border_style!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_outside_padding',
			[
				'label' => esc_html__('Dots Outside Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_outside_color',
			[
				'label' => esc_html__('Dots Outside Backgroud Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_active_dot_outside_color',
			[
				'label' => esc_html__('Active Dot Outside Backgroud Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots .slick-active button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_border_color',
			[
				'label' => __('Dots Border Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_active_border_color',
			[
				'label' => __('Active Dot Border Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots .slick-active' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_border_radius',
			[
				'label' => __('Dots Border Radius', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li,{{WRAPPER}} .vp-product-slider .slick-dots li button' => 'border-radius: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_background_color',
			[
				'label' => __('Dots Background Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_active_background_color',
			[
				'label' => __('Active Dot Background Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots .slick-active button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_width',
			[
				'label' => __('Dots Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li button' => 'width: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_dots_height',
			[
				'label' => __('Dots Height', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots li button' => 'height: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_active_dot_width',
			[
				'label' => __('Active Dot Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 12,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots .slick-active button' => 'width: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'related_active_dot_height',
			[
				'label' => __('Active Dot Height', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 12,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-product-slider .slick-dots .slick-active button' => 'height: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'enable_related_slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'enable_slider_on_mobile',
			[
				'label' => esc_html__('Slider For Mobile (Only)', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'enable_related_slider' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__('Section Heading Styles', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_section_title_color',
			[
				'label' => esc_html__('Heading Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-section-title h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_section_title_typography',
				'label' => esc_html__('Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .product-section-title h2',
			]
		);

		$this->add_control(
			'product_section_title_align',
			[
				'label' => esc_html__('Alignment', 'valuepack-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'valuepack-addons'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'valuepack-addons'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'valuepack-addons'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .product-section-title' => 'text-align: {{VALUE}};',
				],
				'default' => 'center',
			]
		);

		$this->add_responsive_control(
			'product_section_title_padding',
			[
				'label' => __('Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .product-section-title h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_section_title_margin',
			[
				'label' => __('Margin', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .product-section-title h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Renders product display with various options including sliders, related products, etc.
	 *
	 * @package ValuePack
	 * @since 1.0.0
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



		$product_type = isset($settings['product_type']) ? sanitize_text_field($settings['product_type']) : '';

		$enable_related_slider = !empty($settings['enable_related_slider']) ? 'vp-product-slider' : '';
		$enable_slider_on_mobile = !empty($settings['enable_slider_on_mobile']) ? 'vp-product-slider-on-mobile' : '';

		// Sanitize and validate slider icons
		$prev_icon = '';
		if (!empty($settings['prev_icon']['value'])) {
			if ('svg' === $settings['prev_icon']['library']) {
				$prev_icon_url = esc_url_raw($settings['prev_icon']['value']['url']);
				if (filter_var($prev_icon_url, FILTER_VALIDATE_URL)) {
					$response = wp_safe_remote_get($prev_icon_url);
					if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
						$prev_icon = wp_kses_post(wp_remote_retrieve_body($response));
					}
				}
			} else {
				$prev_icon = '<i class="' . esc_attr($settings['prev_icon']['value']) . '"></i>';
			}
		}

		$next_icon = '';
		if (!empty($settings['next_icon']['value'])) {
			if ('svg' === $settings['next_icon']['library']) {
				$next_icon_url = esc_url_raw($settings['next_icon']['value']['url']);
				$response = wp_safe_remote_get($next_icon_url);
				if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
					$next_icon = wp_kses_post(wp_remote_retrieve_body($response));
				}
			} else {
				$next_icon = '<i class="' . esc_attr($settings['next_icon']['value']) . '"></i>';
			}
		}

		// Sanitize slider settings
		$slides_to_show = isset($settings['slides_to_show']) ? absint($settings['slides_to_show']) : 3;
		$slides_to_scroll = isset($settings['slides_to_scroll']) ? absint($settings['slides_to_scroll']) : 1;
		$autoplay = isset($settings['autoplay']) && 'yes' === $settings['autoplay'];
		$enable_arrows = isset($settings['enable_arrows']) && 'yes' === $settings['enable_arrows'];
		$enable_dots = isset($settings['enable_dots']) && 'yes' === $settings['enable_dots'];
		$autoplay_speed = isset($settings['autoplay_speed']) ? absint($settings['autoplay_speed']) : 2000;
		$infinite = isset($settings['infinite']) && 'yes' === $settings['infinite'];
		$posts_per_page = isset($settings['posts_per_page']) ? absint($settings['posts_per_page']) : -1;

		 

		// Get products based on type
		$get_FinalIDs = array();
		switch ($product_type) {
			case 'releated_products':
				$get_FinalIDs = $this->get_related_products_by_post_ids($product_id, $posts_per_page);
				break;
			case 'recent_views':
				$get_FinalIDs = $this->get_recently_viewed_products();
				break;
			case 'up_sells':
				$get_FinalIDs = $this->get_upsells_products_by_post_ids($product_id);
				break;
			case 'cross_sells':
				$get_FinalIDs = $this->get_crosssells_products_by_post_ids($product_id);
				break;
			default:
				$get_FinalIDs = array();
		}

		$args = array(
			'post_type' => 'product',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => $posts_per_page,
			'layout' => isset($settings['layout']) ? sanitize_text_field($settings['layout']) : '',
			'column_per_row' => isset($settings['column_per_row']) ? sanitize_text_field($settings['column_per_row']) : '3',
			'column_per_row_tablet' => isset($settings['column_per_row_tablet']) ? sanitize_text_field($settings['column_per_row_tablet']) : '3',
			'column_per_row_mobile' => isset($settings['column_per_row_mobile']) ? sanitize_text_field($settings['column_per_row_mobile']) : '3',
			'post__in' => $get_FinalIDs,
			'enable_related_slider' => $enable_related_slider,
			'enable_slider_on_mobile' => $enable_slider_on_mobile,
			'enable_arrows' => $enable_arrows,
			'prev_icon' => $prev_icon,
			'next_icon' => $next_icon,
			'slides_to_show' => $slides_to_show,
			'slides_to_scroll' => $slides_to_scroll,
			'autoplay' => $autoplay,
			'autoplay_speed' => $autoplay_speed,
			'infinite' => $infinite,
			'enable_dots' => $enable_dots,
			'product_section_title' => isset($settings['product_section_title']) ? sanitize_text_field($settings['product_section_title']) : '',
		);

		// Hide out of stock items if setting is enabled
		if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
			$args['tax_query'] = array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => 'outofstock',
					'operator' => 'NOT IN',
				),
			);
		}

		$this->value_pack_products_outputs($args);
	}

	/**
	 * Outputs products based on the provided parameters.
	 *
	 * @param array $parameters Array of parameters for product display.
	 */
	public static function value_pack_products_outputs($parameters)
	{
		$args = wp_parse_args($parameters, array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'column_per_row' => '3',
			'layout' => '',
		));

		if (isset($parameters['post__in']) && !empty($parameters['post__in']) && is_array($parameters['post__in'])) {
			$args['post__in'] = array_map('absint', $parameters['post__in']);
			$column_per_row = isset($parameters['column_per_row']) ? sanitize_text_field($parameters['column_per_row']) : '3';

			// Set column classes based on layout
			$col_class = 'cwp-col-12 cwp-col-md-6';
			switch ($column_per_row) {
				case '0':
					$col_class = 'cwp-col-12 cwp-col-md-auto';
					break;
				case '1':
					$col_class = 'cwp-col-12';
					break;
				case '2':
					$col_class = 'cwp-col-12 cwp-col-md-6';
					break;
				case '3':
					$col_class = 'cwp-col-12 cwp-col-md-6 cwp-col-lg-4';
					break;
				case '4':
					$col_class = 'cwp-col-12 cwp-col-md-6 cwp-col-lg-3';
					break;
				case '5':
					$col_class = 'cwp-col-12 cwp-5cols-per-row';
					break;
			}

			$layout = isset($parameters['layout']) ? sanitize_text_field($parameters['layout']) : '';
			$row_class = 'grid-view';
			if ('list' === $layout) {
				$col_class = 'cwp-col-12';
				$row_class = 'list-view';
			}

			// Get slider settings
			$enable_related_slider = isset($parameters['enable_related_slider']) ? sanitize_text_field($parameters['enable_related_slider']) : '';
			$enable_slider_on_mobile = isset($parameters['enable_slider_on_mobile']) ? sanitize_text_field($parameters['enable_slider_on_mobile']) : '';
			$enable_arrows = isset($parameters['enable_arrows']) ? (bool) $parameters['enable_arrows'] : false;
			$prev_icon = isset($parameters['prev_icon']) ? wp_kses_post($parameters['prev_icon']) : '';
			$next_icon = isset($parameters['next_icon']) ? wp_kses_post($parameters['next_icon']) : '';
			$slides_to_show = isset($parameters['slides_to_show']) ? absint($parameters['slides_to_show']) : 3;
			$slides_to_scroll = isset($parameters['slides_to_scroll']) ? absint($parameters['slides_to_scroll']) : 1;
			$autoplay = isset($parameters['autoplay']) ? (bool) $parameters['autoplay'] : false;
			$autoplay_speed = isset($parameters['autoplay_speed']) ? absint($parameters['autoplay_speed']) : 2000;
			$infinite = isset($parameters['infinite']) ? (bool) $parameters['infinite'] : false;
			$enable_dots = isset($parameters['enable_dots']) ? (bool) $parameters['enable_dots'] : false;
			$product_section_title = isset($parameters['product_section_title']) ? sanitize_text_field($parameters['product_section_title']) : '';

			$column_per_row = isset($parameters['column_per_row']) ? $parameters['column_per_row'] : '3';
			$column_per_row_tablet = isset($parameters['column_per_row_tablet']) ? $parameters['column_per_row_tablet'] : '2';
			$column_per_row_mobile = isset($parameters['column_per_row_mobile']) ? $parameters['column_per_row_mobile'] : '2';

			if ($enable_related_slider) {
				$column_per_row = $column_per_row_tablet = $column_per_row_mobile = $slides_to_show;
			}

			if($enable_slider_on_mobile){
				$column_per_row_tablet = $column_per_row_mobile = $slides_to_show;
			}

			$posts_row_class = '';
			if ($column_per_row !== 'auto' && $column_per_row !== '' && $column_per_row !== null) {
				$desktop_val = intval($column_per_row);
				$tablet_val = ($column_per_row_tablet === 'auto' || $column_per_row_tablet === '' || !is_numeric($column_per_row_tablet))
					? $desktop_val
					: intval($column_per_row_tablet);

				$mobile_val = ($column_per_row_mobile === 'auto' || $column_per_row_mobile === '' || !is_numeric($column_per_row_mobile))
					? $desktop_val
					: intval($column_per_row_mobile);
				$posts_row_class = sprintf(
					'cubewp-posts-row-%1$s cubewp-posts-row-tablet-%2$s cubewp-posts-row-mobile-%3$s',
					$desktop_val,
					$tablet_val,
					$mobile_val
				);
			}

			// Query products
			$posts = new WP_Query($args);

			if ($posts->have_posts()) {


				// Output section title if exists
				if (!empty($product_section_title)) {
					printf('<div class="product-section-title"><h2>%s</h2></div>', esc_html($product_section_title));
				}

				// Open container
				if (function_exists('CWP')) {
					if ($posts_row_class) {
						add_filter('post_class', function ($classes) use ($posts_row_class) {
							$classes[] = $posts_row_class;
							return $classes;
						});
					}
					echo '<div class="cwp-row cubewp-posts-shortcode ';
				} else {
					echo '<ul class="product_list_widget ';
				}

				// Output slider attributes
				echo esc_attr($row_class . ' ' . $enable_related_slider . ' ' . $enable_slider_on_mobile);
				echo '"';
				echo ' data-enable-arrow="' . esc_attr($enable_arrows ? 'true' : 'false') . '"';
				echo ' data-prev-arrow="' . esc_attr($prev_icon) . '"';
				echo ' data-next-arrow="' . esc_attr($next_icon) . '"';
				echo ' data-slides-to-show="' . esc_attr($slides_to_show) . '"';
				echo ' data-slides-to-scroll="' . esc_attr($slides_to_scroll) . '"';
				echo ' data-autoplay="' . esc_attr($autoplay ? 'true' : 'false') . '"';
				echo ' data-autoplay-speed="' . esc_attr($autoplay_speed) . '"';
				echo ' data-enable-dots="' . esc_attr($enable_dots ? 'true' : 'false') . '"';
				echo ' data-infinite="' . esc_attr($infinite ? 'true' : 'false') . '">';

				// Loop through products
				while ($posts->have_posts()) {
					$posts->the_post();
					if (function_exists('CWP')) {
						set_query_var('col_class', $col_class);
						echo CubeWp_frontend_grid_HTML(get_the_ID(), $col_class, $layout); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						wc_get_template('content-widget-product.php');
					}
				}

				// Close container
				if (function_exists('CWP')) {
					if ($posts_row_class) {
						remove_all_filters('post_class'); // or remove using the closure reference if needed
					}
					echo '</div>';
				} else {
					echo '</ul>';
				}

				// Reset post data
				wp_reset_postdata();
			}
		}
	}

	/**
	 * Gets related products for a given product ID.
	 *
	 * @param int $product_id The product ID to get related products for.
	 * @return array Array of related product IDs.
	 */
	public static function get_related_products_by_post_ids($product_id, $posts_per_page)
	{
		if (!function_exists('wc_get_related_products')) {
			return array();
		}
		return wc_get_related_products(absint($product_id), $posts_per_page);
	}

	/**
	 * Gets recently viewed products from cookie.
	 *
	 * @return array Array of recently viewed product IDs.
	 */
	public static function get_recently_viewed_products()
	{
		if (!function_exists('wc_get_product')) {
			return array();
		}

		$viewed_products = !empty($_COOKIE['value_pack_recently_viewed']) ?
			(array) explode(',', sanitize_text_field( wp_unslash($_COOKIE['value_pack_recently_viewed']))) :
			array();

		return array_reverse(array_filter(array_map('absint', $viewed_products)));
	}

	/**
	 * Gets upsell products for a given product ID.
	 *
	 * @param int $product_id The product ID to get upsell products for.
	 * @return array Array of upsell product IDs.
	 */
	public static function get_upsells_products_by_post_ids($product_id)
	{
		if (!function_exists('wc_get_product')) {
			return array();
		}

		$product = wc_get_product(absint($product_id));
		return $product ? $product->get_upsell_ids() : array();
	}

	/**
	 * Gets cross-sell products for a given product ID.
	 *
	 * @param int $product_id The product ID to get cross-sell products for.
	 * @return array Array of cross-sell product IDs.
	 */
	public static function get_crosssells_products_by_post_ids($product_id)
	{
		if (!function_exists('wc_get_product')) {
			return array();
		}

		$product = wc_get_product(absint($product_id));
		return $product ? $product->get_cross_sell_ids() : array();
	}
}
