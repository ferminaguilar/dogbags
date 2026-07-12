<?php

/**
 * Value Pack Product Gallery Widget
 *
 * @package ValuePack
 * @subpackage ElementorWidgets
 * 
 * Elementor widget for displaying WooCommerce product galleries with various layout options.
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;

/**
 * Class Value_Pack_Product_Gallery
 * 
 * Handles the display of product galleries in various layouts with configurable options.
 */
class Value_Pack_Product_Gallery extends Value_Pack_Widget_Base
{

	public function get_name()
	{
		return 'vp_product_gallery';
	}

	public function get_title()
	{
		return __('Product Gallery', 'valuepack-addons');
	}

	public function get_icon()
	{
		return 'eicon-gallery-grid vpack-icon';
	}

	public function get_categories()
	{
		return ['value_pack'];
	}

	protected function _register_controls()
	{
		$this->start_controls_section(
			'gallery_content',
			[
				'label' => __('Gallery Content', 'valuepack-addons'),
			]
		);

		$this->add_control(
			'product_id',
			[
				'label' => __('Product ID', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => value_pack_get_any_one_product_id(),
				'description' => __('If this is not single page then write product ID', 'valuepack-addons'),
			]
		);
		// new changes aded 
		$default_products = value_pack_get_woocommerce_products();
		$default_list = '';
		$count = 0;
		foreach ($default_products as $id => $name) {
			if ($count >= 5) break; // limit to 5 default items
			$default_list .= '<li class="vp-product-result-item" data-id="' . esc_attr($id) . '">' . esc_html($name) . '</li>';
			$count++;
		}
		$this->add_control(
			'product_search_html',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '
                    <div class="vp-product-search-wrapper" style="display: none;">
                        <input type="text" class="vp-product-search" placeholder="Start typing product name..." />
                         <ul class="vp-product-results">' . $default_list . '</ul>
                    </div>
                ',
				'content_classes' => 'vp-product-search-box',
			]
		);


		$this->add_control(
			'show_images',
			[
				'label' => __('Show Images', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'3' => __('3', 'valuepack-addons'),
					'6' => __('6', 'valuepack-addons'),
					'9' => __('9', 'valuepack-addons'),
					'all' => __('All', 'valuepack-addons'),
				],
			]
		);

		$this->add_control(
			'show_images_in_row',
			[
				'label' => __('Show Images in row', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'two_two_three',
				'options' => [
					'one' => __('1', 'valuepack-addons'),
					'two' => __('2', 'valuepack-addons'),
					'one_two' => __('1-2', 'valuepack-addons'),
					'two_two_three' => __('2-2-3', 'valuepack-addons'),
				],
				'description' => __('This setting applies when the slider is disabled.', 'valuepack-addons'),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gallery_slider',
			[
				'label' => __('Gallery Slider', 'valuepack-addons'),
			]
		);

		$this->add_control(
			'slider_switch',
			[
				'label' => __('Slider', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'slider_as_nav',
			[
				'label' => __('As Nav Bar', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'slider_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'navebar_position',
			[
				'label' => __('Navbar Position', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top' => __('Top', 'valuepack-addons'),
					'bottom' => __('Bottom', 'valuepack-addons'),
				],
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
					'navbar_vertical' => '',
				],
			]
		);

		$this->add_control(
			'navbar_vertical',
			[
				'label' => __('Vertical Navbar', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'navbar_vertical_position',
			[
				'label' => __('Navbar Vertical Position', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __('Left', 'valuepack-addons'),
					'right' => __('Right', 'valuepack-addons'),
				],
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
					'navbar_vertical' => 'yes',
				],
			]
		);

		$this->add_control(
			'slide_to_show',
			[
				'label' => __('Slides To Show', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'condition' => [
					'slider_switch' => 'yes',
					'fade' => '',
				],
			]
		);

		$this->add_control(
			'main_slide_to_show',
			[
				'label' => __('Main slides To Show', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'condition' => [
					'slider_switch' => 'yes',
					'fade' => '',
					'slider_as_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'navbar_slide_to_show',
			[
				'label' => __('Navbar slides To Show', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'slide_to_scroll',
			[
				'label' => __('Slides To Scroll', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'condition' => [
					'slider_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __('Speed', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'condition' => [
					'slider_switch' => 'yes',
					'autoplay' => '',
				],
			]
		);

		$this->add_control(
			'focus_on_select',
			[
				'label' => __('Focus On Select', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'center_mode',
			[
				'label' => __('Center Mode', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrows',
			[
				'label' => __('Arrows', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'slider_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_icon_left',
			[
				'label' => __('Left Arrow Icon', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_icon_right',
			[
				'label' => __('Right Arrow Icon', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => __('Infinite', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'slider_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __('Autoplay', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'slider_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplaySpeed',
			[
				'label' => __('Autoplay Speed', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 2000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'fade',
			[
				'label' => __('Fade', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'slider_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'css_ease',
			[
				'label' => __('CSS Ease', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'ease',
				'options' => [
					'linear' => __('Linear', 'valuepack-addons'),
					'ease' => __('Ease', 'valuepack-addons'),
					'ease-in' => __('Ease-In', 'valuepack-addons'),
					'ease-out' => __('Ease-Out', 'valuepack-addons'),
					'ease-in-out' => __('Ease-In-Out', 'valuepack-addons'),
					'cubic-bezier' => __('Cubic Bezier', 'valuepack-addons'),
				],
				'condition' => [
					'slider_switch' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section('menu_style_section', [
			'label' => esc_html__('Gallery Style', 'valuepack-addons'),
			'tab' => Controls_Manager::TAB_STYLE,
		]);

		$this->add_control(
			'image_padding',
			[
				'label' => __('Image Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'rem', 'em', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'top' => '2',
					'right' => '2',
					'bottom' => '2',
					'left' => '2',
					'unit' => 'px',
				],
			]
		);

		$this->add_control(
			'main_slider_width',
			[
				'label' => __('Main Slider Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => '%',
					'size' => 90,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-gallery-slider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
					'navbar_vertical' => 'yes',
				],
			]
		);

		$this->add_control(
			'navbar_slider_width',
			[
				'label' => __('Navbar Slider Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-gallery-as-navbar' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
					'navbar_vertical' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_gallery_gap',
			[
				'label' => __('Slider Gap', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-gallery-container' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'slider_as_nav' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section('arrows_style', [
			'label' => esc_html__('Arrows Style', 'valuepack-addons'),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [
				'slider_switch' => 'yes',
				'arrows' => 'yes',
			],
		]);


		$this->add_control(
			'arrow_color',
			[
				'label' => __('Arrow Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'color: {{VALUE}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_bg_color',
			[
				'label' => __('Arrow Background Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_border_style',
			[
				'label' => __('Arrow Border Style', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'None',
				'options' => [
					'none' => __('None', 'valuepack-addons'),
					'solid' => __('Solid', 'valuepack-addons'),
					'dotted' => __('Dotted', 'valuepack-addons'),
					'dashed' => __('Dashed', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_border_width',
			[
				'label' => __('Arrow Border Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'default' => [
					'top' => '1',
					'right' => '1',
					'bottom' => '1',
					'left' => '1',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'arrow_border_style' => ['solid', 'dotted', 'dashed'],
				],
			]
		);

		$this->add_control(
			'arrow_border_color',
			[
				'label' => __('Arrow Border Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
					'arrow_border_style' => ['solid', 'dotted', 'dashed'],
				],
			]
		);

		$this->add_control(
			'arrow_border_radius',
			[
				'label' => __('Arrow Border Radius', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-radius: {{VALUE}}px;',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_width',
			[
				'label' => __('Arrow Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_height',
			[
				'label' => __('Arrow Height', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_padding',
			[
				'label' => __('Arrow Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '10',
					'right' => '10',
					'bottom' => '10',
					'left' => '10',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_top_position',
			[
				'label' => __('Arrow Top Position', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_left_position_horizontal',
			[
				'label' => __('Left Arrow Horizontal Position', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_right_position_horizontal',
			[
				'label' => __('Right Arrow Horizontal Position', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
					'%' => [
						'min' => -50,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_switch' => 'yes',
					'arrows' => 'yes',
				],
			]
		);

		$this->end_controls_section();
		// Image Styling Section
		$this->start_controls_section('vp_product_image_style_section', [
			'label' => esc_html__('Product Image Styling', 'valuepack-addons'),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		]);

		// Enable Styling Toggle
		$this->add_control('enable_image_styling', [
			'label' => esc_html__('Enable Styling', 'valuepack-addons'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__('Yes', 'valuepack-addons'),
			'label_off' => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default' => '',
		]);

		// Border for Gallery List Images
		$this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
			'name' => 'gallery_list_image_border',
			'label' => esc_html__('Gallery Image Border', 'valuepack-addons'),
			'selector' => '{{WRAPPER}} .vp-product-gallery-as-navbar .gallery-item img',
			'condition' => ['enable_image_styling' => 'yes'],
		]);

		$this->add_responsive_control('gallery_list_image_border_radius', [
			'label' => esc_html__('Gallery Image Border Radius', 'valuepack-addons'),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%'],
			'selectors' => [
				'{{WRAPPER}} .vp-product-gallery-as-navbar .gallery-item img' =>
				'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => ['enable_image_styling' => 'yes'],
		]);
		$this->add_responsive_control('gallery_list_image_height', [
			'label' => esc_html__('Gallery Image Height', 'valuepack-addons'),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => ['px', '%', 'vh'],
			'range' => [
				'px' => ['min' => 10, 'max' => 1000],
				'%'  => ['min' => 1, 'max' => 100],
				'vh' => ['min' => 1, 'max' => 100],
			],
			'selectors' => [
				'{{WRAPPER}} .vp-product-gallery-as-navbar .gallery-item img' =>
				'height: {{SIZE}}{{UNIT}}  !important; object-fit: cover;',
			],
			'condition' => ['enable_image_styling' => 'yes'],
		]);

		// Border for Main Product Image
		$this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
			'name' => 'main_image_border',
			'label' => esc_html__('Main Image Border', 'valuepack-addons'),
			'selector' => '{{WRAPPER}} .woocommerce-product-gallery-image img.wp-post-image',
			'condition' => ['enable_image_styling' => 'yes'],
		]);

		$this->add_responsive_control('main_image_border_radius', [
			'label' => esc_html__('Main Image Border Radius', 'valuepack-addons'),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => ['px', '%'],
			'selectors' => [
				'{{WRAPPER}} .woocommerce-product-gallery-image img.wp-post-image' =>
				'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => ['enable_image_styling' => 'yes'],
		]);

		$this->end_controls_section();

		$this->start_controls_section('zoom_icon_position_section', [
			'label' => esc_html__('Zoom Icon', 'valuepack-addons'),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		]);

		// new issue fixes
		// Day Visibility Switcher
		$this->add_control(
			'hide_zoom_icon',
			[
				'label' => esc_html__('Icon', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'valuepack-addons'),
				'label_off' => esc_html__('Hide', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}  .woo-gallery-images' => '{{VALUE}} !important;',
				],
				'selectors_dictionary' => [
					'yes' => 'display: inline-flex',
					'' => 'display: none',
				],
			]
		);
		$this->add_control(
			'zoom_icon_size',
			[
				'label' => __('Icon Size', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-product-gallery-container>.woo-gallery-images.value-pack-open-gallery' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		// close new issue fixes
		// Enable Toggle
		$this->add_control('enable_zoom_icon_position', [
			'label' => esc_html__('Enable Custom Position', 'valuepack-addons'),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__('Yes', 'valuepack-addons'),
			'label_off' => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default' => '',
		]);

		// Top Position
		$this->add_responsive_control('zoom_icon_top', [
			'label' => esc_html__('Top Offset', 'valuepack-addons'),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => ['px', '%'],
			'range' => [
				'px' => ['min' => -200, 'max' => 200],
				'%'  => ['min' => 0, 'max' => 100],
			],
			'condition' => ['enable_zoom_icon_position' => 'yes'],
			'selectors' => [
				'{{WRAPPER}} .woo-gallery-images' => 'top: {{SIZE}}{{UNIT}};',
			],
		]);

		// Left Position
		$this->add_responsive_control('zoom_icon_left', [
			'label' => esc_html__('Left Offset', 'valuepack-addons'),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => ['px', '%'],
			'range' => [
				'px' => ['min' => -200, 'max' => 200],
				'%'  => ['min' => 0, 'max' => 100],
			],
			'condition' => ['enable_zoom_icon_position' => 'yes'],
			'selectors' => [
				'{{WRAPPER}} .woo-gallery-images' => 'left: {{SIZE}}{{UNIT}};transform: translate(-50%, -50%);',
			],
		]);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings           = $this->get_settings_for_display();

		if (is_singular('product')) {
			$product_id     =  get_the_ID();
		} else {
			$product_id = isset($settings['product_id']) ? absint($settings['product_id']) : 0;
			if (empty($product_id) || $product_id <= 0 || get_post_type($product_id) !== 'product' || get_post_status($product_id) !== 'publish') {
				$default_products = value_pack_get_woocommerce_products();
				if (!empty($default_products)) {
					$product_id = absint(array_key_first($default_products));
				} else {
					$product_id = 0;
				}
			}
		}
		 
		$colum = '';
		$product            = wc_get_product($product_id);
		$show_images        = $settings['show_images'];
		$show_images_in_row = $settings['show_images_in_row'];
		$image_padding      = $settings['image_padding'];
		$padding_top        = isset($image_padding['top']) ? $image_padding['top'] . $image_padding['unit'] : '0';
		$padding_right      = isset($image_padding['right']) ? $image_padding['right'] . $image_padding['unit'] : '0';
		$padding_bottom     = isset($image_padding['bottom']) ? $image_padding['bottom'] . $image_padding['unit'] : '0';
		$padding_left       = isset($image_padding['left']) ? $image_padding['left'] . $image_padding['unit'] : '0';
		$slider_switch      = $settings['slider_switch'];
		$slider_as_nav      = $settings['slider_as_nav'];
		$navebar_position   = $settings['navebar_position'] ?? 'bottom';
		$navbar_vertical_position   = $settings['navbar_vertical_position'];
		$slides_to_show     = isset($settings['slide_to_show']) ? esc_attr($settings['slide_to_show']) : 1;
		$main_slide_to_show = isset($settings['main_slide_to_show']) ? esc_attr($settings['main_slide_to_show']) : 1;
		$navbar_slide_to_show = isset($settings['navbar_slide_to_show']) ? esc_attr($settings['navbar_slide_to_show']) : 1;
		$slide_to_scroll    = isset($settings['slide_to_scroll']) ? esc_attr($settings['slide_to_scroll']) : 1;
		$speed              = isset($settings['speed']) ? esc_attr($settings['speed']) : 500;
		$arrow_icon_left    = isset($settings['arrow_icon_left']['url']) ? $settings['arrow_icon_left']['url'] : '';
		$arrow_icon_right   = isset($settings['arrow_icon_right']['url']) ? $settings['arrow_icon_right']['url'] : '';
		$infinite = ($settings['infinite'] === 'yes') ? 'true' : 'false';
		$autoplay = ($settings['autoplay'] === 'yes') ? 'true' : 'false';
		$fade = ($settings['fade'] === 'yes') ? 'true' : 'false';
		$navbar_vertical = ($settings['navbar_vertical'] === 'yes') ? 'true' : 'false';
		$focus_on_select = ($settings['focus_on_select'] === 'yes') ? 'true' : 'false';
		$center_mode = ($settings['center_mode'] === 'yes') ? 'true' : 'false';
		$arrows = ($settings['arrows'] === 'yes') ? 'true' : 'false';

		$autoplaySpeed      = $settings['autoplaySpeed'];
		$css_ease           = $settings['css_ease'];
		$slider_gallery_slider = '';
		if ($slider_switch === 'yes') {
			$slider_gallery_slider = 'vp-product-gallery-slider';
		}

		if (empty($arrow_icon_left)) {
			$arrow_icon_left = plugins_url('../assets/frontend/images/left.png', __DIR__);
		}
		if (empty($arrow_icon_right)) {
			$arrow_icon_right = plugins_url('../assets/frontend/images/right.png', __DIR__);
		}

		$navebar_position = 'navebar-' . $navebar_position;
		if ($navbar_vertical === 'true') {
			$navebar_position = 'navebar-' . $navbar_vertical_position;
		}


		if ($product) {
			$post_thumbnail_id = $product->get_image_id();
			$attachment_ids = $product->get_gallery_image_ids();
			$thumbnail_metadata = wp_get_attachment_metadata($post_thumbnail_id);
			$width = isset($thumbnail_metadata['width']) ? $thumbnail_metadata['width'] : 800;
			$height = isset($thumbnail_metadata['height']) ? $thumbnail_metadata['height'] : 800;
?>
			<div class="vp-product-gallery-container  <?php echo esc_attr($navebar_position); ?>">
				<a class="woo-gallery-images value-pack-open-gallery" data-pswp-width="<?php echo esc_attr($width); ?>" data-pswp-height="<?php echo esc_attr($height); ?>" href="<?php echo esc_url(wp_get_attachment_image_url($post_thumbnail_id, 'full')); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
						<path d="M5.8885 7.61133C5.7605 7.61133 5.6325 7.66033 5.535 7.75783L1.5 11.7933V9.46483C1.5 9.18833 1.2765 8.96483 1 8.96483C0.7235 8.96483 0.5 9.18833 0.5 9.46483V12.9998C0.5 13.2763 0.7235 13.4998 1 13.4998H4.535C4.8115 13.4998 5.035 13.2763 5.035 12.9998C5.035 12.7233 4.8115 12.4998 4.535 12.4998H2.2075L6.242 8.46483C6.4375 8.26933 6.4375 7.95333 6.242 7.75783C6.1445 7.65983 6.0165 7.61133 5.8885 7.61133Z" fill="black" />
						<path d="M13.0555 0.55542H9.52053C9.24403 0.55542 9.02053 0.77892 9.02053 1.05542C9.02053 1.33192 9.24403 1.55542 9.52053 1.55542H11.8485L7.75753 5.64642C7.56203 5.84192 7.56203 6.15792 7.75753 6.35342C7.95303 6.54892 8.26903 6.54892 8.46453 6.35342L12.5555 2.26242V4.59042C12.5555 4.86692 12.779 5.09042 13.0555 5.09042C13.332 5.09042 13.5555 4.86692 13.5555 4.59042V1.05542C13.5555 0.77942 13.332 0.55542 13.0555 0.55542Z" fill="black" />
					</svg>
				</a>

				<div class="row
				<?php echo esc_attr($slider_gallery_slider); ?>"
					data-slides-to-show="<?php echo esc_attr($slides_to_show); ?>"
					data-slide-to-scroll="<?php echo esc_attr($slide_to_scroll); ?>"
					data-speed="<?php echo esc_attr($speed); ?>"
					data-arrows="<?php echo esc_attr($arrows); ?>"
					data-infinite="<?php echo esc_attr($infinite); ?>"
					data-autoplay="<?php echo esc_attr($autoplay); ?>"
					data-autoplaySpeed="<?php echo esc_attr($autoplaySpeed); ?>"
					data-fade="<?php echo esc_attr($fade); ?>"
					data-css-ease="<?php echo esc_attr($css_ease); ?>"
					data-arrow-icon-left="<?php echo esc_attr($arrow_icon_left); ?>"
					data-arrow-icon-right="<?php echo esc_attr($arrow_icon_right); ?>"
					data-main-slide-to-show="<?php echo esc_attr($main_slide_to_show); ?>"
					data-navbar-slide-to-show="<?php echo esc_attr($navbar_slide_to_show); ?>"
					data-focus-on-select="<?php echo esc_attr($focus_on_select); ?>"
					data-navbar-vertical="<?php echo esc_attr($navbar_vertical); ?>"
					data-center-mode="<?php echo esc_attr($center_mode); ?>"><?php


																				if ($show_images_in_row === 'one' || $show_images_in_row === 'two') {

																					if ($show_images_in_row === 'one') {
																						$colum = 'col-md-12';
																					} elseif ($show_images_in_row === 'two') {
																						$colum = 'col-md-6';
																					}

																					$image_count = 0;
																					if ($post_thumbnail_id) {
																						$thumbnail_url = wp_get_attachment_image_url($post_thumbnail_id, 'full'); // Get the thumbnail URL.
																						$metadata = wp_get_attachment_metadata($post_thumbnail_id);
																						$width = isset($metadata['width']) ? $metadata['width'] : 800;
																						$height = isset($metadata['height']) ? $metadata['height'] : 800;
																						echo '<div class="' . esc_attr($colum) . ' " style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
																						echo '<div class="gallery-item product-thumbnail  product-thumbnail  woocommerce-product-gallery-image  "  data-thumb="' .  esc_url($thumbnail_url) . '">';


																				?>
							<a class="value-pack-open-gallery" href="<?php echo esc_url($thumbnail_url); ?>" data-pswp-width="<?php echo esc_attr($width); ?>" data-pswp-height="<?php echo esc_attr($height); ?>">
								<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
							</a>
							<?php
																						echo '</div>';
																						echo '</div>';
																						$image_count++;
																					}

																					if ($attachment_ids) {
																						foreach ($attachment_ids as $attachment_id) {
																							$thumbnail_url = wp_get_attachment_image_url($attachment_id, 'full'); // Get the thumbnail URL.
																							$attachment_id = $attachment_id ?: 0;
																							$metadata = wp_get_attachment_metadata($attachment_id);
																							$width = isset($metadata['width']) ? $metadata['width'] : 800;
																							$height = isset($metadata['height']) ? $metadata['height'] : 800;
																							if ($show_images !== 'all' && $image_count >= intval($show_images)) {
																								break;
																							}

																							echo '<div class="' . esc_attr($colum) . '" style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
																							echo '<div class="gallery-item product-gallery-image   product-thumbnail  woocommerce-product-gallery-image  "  data-thumb="' .  esc_url($thumbnail_url) . '" data-thumb-srcset="' .  esc_url($thumbnail_url) . '">';
							?>
								<a class="value-pack-open-gallery" href="<?php echo esc_url($thumbnail_url); ?>" data-pswp-width="<?php echo esc_attr($width); ?>" data-pswp-height="<?php echo esc_attr($height); ?>">
									<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" data-src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
								</a>
							<?php
																							echo '</div>';
																							echo '</div>';

																							$image_count++;
																						}
																					}
																				} elseif ($show_images_in_row === 'one_two') {
																					if ($attachment_ids) {

																						$i = 0;
																						$image_count = 0;
																						if ($post_thumbnail_id) {
																							$thumbnail_url = wp_get_attachment_image_url($post_thumbnail_id, 'full'); // Get the thumbnail URL.
																							$metadata = wp_get_attachment_metadata($post_thumbnail_id);
																							$width = isset($metadata['width']) ? $metadata['width'] : 800;
																							$height = isset($metadata['height']) ? $metadata['height'] : 800;

																							echo '<div class="col-md-12" style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
																							echo '<div class="gallery-item product-thumbnail  product-thumbnail  woocommerce-product-gallery-image  "   data-thumb="' .  esc_url($thumbnail_url) . '" data-thumb-srcset="' .  esc_url($thumbnail_url) . '">';

							?>
								<a class="value-pack-open-gallery" href="<?php echo esc_url($thumbnail_url); ?>" data-pswp-width="<?php echo esc_attr($width); ?>" data-pswp-height="<?php echo esc_attr($height); ?>">
									<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" data-src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
								</a>
							<?php
																							echo '</div>';
																							echo '</div>';
																							$i++;
																							$image_count++;
																						}

																						foreach ($attachment_ids as $attachment_id) {

																							if ($show_images !== 'all' && $image_count >= intval($show_images)) {
																								break;
																							}

																							if ($i % 3 == 0) {
																								$col_class = 'col-md-12';
																							} else {
																								$col_class = 'col-md-6';
																							}
																							$thumbnail_url = wp_get_attachment_image_url($attachment_id, 'full'); // Get the thumbnail URL.
																							$metadata = wp_get_attachment_metadata($attachment_id);
																							$width = isset($metadata['width']) ? $metadata['width'] : 800;
																							$height = isset($metadata['height']) ? $metadata['height'] : 800;
																							echo '<div class="' . esc_attr($col_class) . '" style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
																							echo '<div class="gallery-item product-thumbnail  product-thumbnail  woocommerce-product-gallery-image  ">';

							?>
								<a class="value-pack-open-gallery" href="<?php echo esc_url($thumbnail_url); ?>" data-pswp-width="<?php echo esc_attr($width); ?>" data-pswp-height="<?php echo esc_attr($height); ?>">
									<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
								</a>
							<?php
																							echo '</div>';
																							echo '</div>';
																							$i++;
																							$image_count++;
																						}
																					}
																				} elseif ($show_images_in_row === 'two_two_three') {
																					if ($attachment_ids) {

																						$i = 0;
																						$image_count = 0;
																						if ($post_thumbnail_id) {
																							$thumbnail_url = wp_get_attachment_image_url($post_thumbnail_id, 'full'); // Get the thumbnail URL.
																							$metadata = wp_get_attachment_metadata($post_thumbnail_id);
																							$width = isset($metadata['width']) ? $metadata['width'] : 800;
																							$height = isset($metadata['height']) ? $metadata['height'] : 800;
																							echo '<div class="col-md-6" style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
																							echo '<div class="gallery-item product-gallery-image   product-thumbnail  woocommerce-product-gallery-image  "  data-thumb="' .  esc_url($thumbnail_url) . '">';

							?>


								<a class="value-pack-open-gallery" href="<?php echo esc_url($thumbnail_url); ?>" data-pswp-width="<?php echo esc_attr($width); ?>" data-pswp-height="<?php echo esc_attr($height); ?>">
									<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
								</a>
							<?php
																							echo '</div>';
																							echo '</div>';
																							$i++;
																							$image_count++;
																						}

																						foreach ($attachment_ids as $attachment_id) {

																							if ($show_images !== 'all' && $image_count >= intval($show_images)) {
																								break;
																							}

																							if ($i % 7 == 0 || $i % 7 == 1) {
																								$col_class = 'col-md-6';
																							} elseif ($i % 7 == 2 || $i % 7 == 3) {
																								$col_class = 'col-md-6';
																							} else {
																								$col_class = 'col-md-4';
																							}
																							$thumbnail_url = wp_get_attachment_image_url($attachment_id, 'full'); // Get the thumbnail URL.
																							$metadata = wp_get_attachment_metadata($attachment_id);
																							$width = isset($metadata['width']) ? $metadata['width'] : 800;
																							$height = isset($metadata['height']) ? $metadata['height'] : 800;
																							echo '<div class="' . esc_attr($col_class) . '" style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
																							echo '<div class="gallery-item product-gallery-image   product-thumbnail  woocommerce-product-gallery-image  "  data-thumb="' .  esc_url($thumbnail_url) . '">';

							?>
								<a class="value-pack-open-gallery" href="<?php echo esc_url($thumbnail_url); ?>" data-pswp-width="<?php echo esc_attr($width); ?>" data-pswp-height="<?php echo esc_attr($height); ?>">
									<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
								</a>
					<?php
																							echo '</div>';
																							echo '</div>';
																							$i++;
																							$image_count++;
																						}
																					}
																				} ?>
				</div>
				<?php
				if ($slider_as_nav === 'yes') { ?>
					<div class="row vp-product-gallery-as-navbar" data-slider-as-nav="<?php echo esc_attr($slider_as_nav); ?>">
						<?php
						$post_thumbnail_id = $product->get_image_id();
						$attachment_ids = $product->get_gallery_image_ids();
						$image_count = 0;
						if ($post_thumbnail_id) {
							$thumbnail_url = wp_get_attachment_image_url($post_thumbnail_id, 'full'); // Get the thumbnail URL.
							$thumbnail_metadata = wp_get_attachment_metadata($post_thumbnail_id);
							$width = isset($thumbnail_metadata['width']) ? $thumbnail_metadata['width'] : 800;
							$height = isset($thumbnail_metadata['height']) ? $thumbnail_metadata['height'] : 800;
							if (!empty($thumbnail_url)) {
								echo '<div class="' . esc_attr($colum) . '" style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
								echo '<div class="gallery-item product-gallery-image  product-thumbnail  product-thumbnail  woocommerce-product-gallery-image  "  data-thumb="' .  esc_url($thumbnail_url) . '">';

						?>

								<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />

								<?php
								echo '</div>';
								echo '</div>';
								$image_count++;
							}
						}

						if ($attachment_ids) {
							foreach ($attachment_ids as $attachment_id) {
								if (!empty($attachment_id)) {
									if ($show_images !== 'all' && $image_count >= intval($show_images)) {
										break;
									}

									$thumbnail_url = wp_get_attachment_image_url($attachment_id, 'full'); // Get the thumbnail URL.
									if (!$thumbnail_url) {
										continue;
									}
									$metadata = wp_get_attachment_metadata($attachment_id);
									$width = isset($metadata['width']) ? $metadata['width'] : 800;
									$height = isset($metadata['height']) ? $metadata['height'] : 800;
									echo '<div class="' . esc_attr($colum) . '" style="padding: ' . esc_attr("$padding_top $padding_right $padding_bottom $padding_left") . ';">';
									echo '<div class="gallery-item product-gallery-image   product-thumbnail  woocommerce-product-gallery-image  "  data-thumb="' .  esc_url($thumbnail_url) . '">';
								?>
									<img class="wp-post-image" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
						<?php
									echo '</div>';
									echo '</div>';

									$image_count++;
								}
							}
						}
						?>
					</div>
				<?php }
				if (wp_is_mobile()) {
					echo '<div class="vp-gallery-loader">
                    <div class="gallery-loader-dots">
                        <div class="gallery-loader-circle"></div>
                        <div class="gallery-loader-circle"></div>
                        <div class="gallery-loader-circle"></div>
                        <div class="gallery-loader-circle"></div>
                        </div>
                    </div>';
				} ?>
			</div>
<?php }
	}
}
?>