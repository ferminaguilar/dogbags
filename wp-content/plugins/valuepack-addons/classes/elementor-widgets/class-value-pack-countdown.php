<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;


/**
 * CubeWP Nav Menu Widgets.
 *
 * Elementor Widget For Nav Menu By CubeWP.
 *
 * @since 1.0.0
 */
class Value_Pack_Countdown extends Value_Pack_Widget_Base
{

	protected $value_pack_script_depends = array('value-pack-frontend-scripts', 'value-pack-countdown');

	protected $mega_menu_index = 1;

	public function get_name()
	{
		return 'vp_countdown';
	}

	public function get_title()
	{
		return esc_html__('Countdown', 'valuepack-addons');
	}

	public function get_icon()
	{
		return 'eicon-countdown vpack-icon';
	}

	public function get_categories()
	{
		return array('value_pack');
	}

	private function vp_getCurrentYear(): int
	{
		return (int) wp_date('Y');
	}

	function vp_getNextMonth(): int
	{
		$currentMonth = (int) wp_date('n'); // 'n' gives numeric month without leading zeros (1-12)
		return $currentMonth % 12 + 1;
	}

	public function get_keywords()
	{
		return array(
			'countdown',
			'timer',
			'countdown timer',
			'clock',
			'count',
			'event',
			'promotion',
			'promotional',
			'deal',
			'offer',
			'flash sale',
			'sale',
			'ecommerce',
			'product',
			'countdown clock',
			'countdown banner',
			'special offer',
		);
	}

	protected function register_controls()
	{
		$this->start_controls_section('vp_widget_settings', array(
			'label' => esc_html__('Countdown Settings', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		$this->add_control(
			'style_type',
			[
				'type'        => Controls_Manager::SELECT,
				'multiple'    => false,
				'label'       => esc_html__('Countdown Display Style', 'valuepack-addons'),
				'description' => esc_html__('Choose how the countdown should be displayed', 'valuepack-addons'),
				'options'     => [
					'style1' => esc_html__('Classic', 'valuepack-addons'),
					'style2' => esc_html__('Inline (Colon Separated)', 'valuepack-addons'),
					'style3' => esc_html__('Bordered', 'valuepack-addons'),
					'style4' => esc_html__('Custom Styling', 'valuepack-addons'),
				],
				'default'     => 'style1',
			]
		);

		// Countdown Source: Static or Dynamic
		$this->add_control(
			'countdown_source',
			[
				'label' => esc_html__('Countdown Source', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'static' => esc_html__('Static (Manual Date)', 'valuepack-addons'),
					'dynamic' => esc_html__('Dynamic (From Post Field)', 'valuepack-addons'),
				],
				'default' => 'static',
				'description' => esc_html__('Choose whether to set a manual date or use a date field from the post.', 'valuepack-addons'),
			]
		);

		// Dynamic Countdown Controls
		$post_types = get_post_types(['public' => true], 'objects');
		$post_type_options = array('' => esc_html__('Select Post Type', 'valuepack-addons'));
		if (is_array($post_types)) {
			foreach ($post_types as $post_type_key => $post_type) {
				if (is_object($post_type) && isset($post_type->name) && $post_type->name !== 'attachment') {
					$post_type_options[$post_type->name] = $post_type->label;
				}
			}
		}

		$this->add_control(
			'dynamic_post_type',
			[
				'label' => esc_html__('Post Type', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => $post_type_options,
				'default' => '',
				'description' => esc_html__('Select the post type to get date fields from', 'valuepack-addons'),
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		//post_id
		$this->add_control(
			'post_id_source',
			[
				'label' => esc_html__('Post ID Source', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current_post' => esc_html__('Current Post', 'valuepack-addons'),
					'custom_post' => esc_html__('Custom Post', 'valuepack-addons'),
					'associated_post' => esc_html__('Associated Post', 'valuepack-addons'),
				],
				'default' => 'current_post',
				'description' => esc_html__('Select the post ID source as current post or as post ID', 'valuepack-addons'),
			]
		);

		//post_id
		$this->add_control(
			'post_id',
			[
				'label' => esc_html__('Post ID', 'valuepack-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'description' => esc_html__('Enter the post ID to get the date fields from', 'valuepack-addons'),
				'condition' => [
					'post_id_source' => 'custom_post',
				],
			]
		);

		//associated_post_meta_key
		$this->add_control(
			'associated_post_meta_key',
			[
				'label' => esc_html__('Associated Post Meta Key', 'valuepack-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => esc_html__('Enter the meta key to get the associated post ID from', 'valuepack-addons'),
				'condition' => [
					'post_id_source' => 'associated_post',
				],
			]
		);

		// Add end date field controls for each post type
		if (is_array($post_types)) {
			foreach ($post_types as $post_type_key => $post_type) {
				if (is_object($post_type) && isset($post_type->name) && $post_type->name !== 'attachment') {
					$date_fields = $this->value_pack_get_countdown_date_fields($post_type->name);
					$this->add_control(
						'dynamic_end_date_field_' . $post_type->name,
						[
							'label' => esc_html__('End Date Field', 'valuepack-addons'),
							'type' => Controls_Manager::SELECT,
							'options' => $date_fields,
							'default' => '',
							'description' => esc_html__('Select the date or datetime field to use as end date for countdown. Fields are filtered based on selected post type.', 'valuepack-addons'),
							'label_block' => true,
							'condition' => [
								'countdown_source' => 'dynamic',
								'dynamic_post_type' => $post_type->name,
							],
						]
					);
					
					// Add start date field controls for each post type
					$this->add_control(
						'dynamic_start_date_field_' . $post_type->name,
						[
							'label' => esc_html__('Start Date Field (Optional)', 'valuepack-addons'),
							'type' => Controls_Manager::SELECT,
							'options' => array_merge(['' => esc_html__('No Start Date', 'valuepack-addons')], $date_fields),
							'default' => '',
							'description' => esc_html__('Select the date or datetime field to use as start date. Countdown will only begin when start date is reached. Leave empty to start immediately.', 'valuepack-addons'),
							'label_block' => true,
							'condition' => [
								'countdown_source' => 'dynamic',
								'dynamic_post_type' => $post_type->name,
							],
						]
					);
				}
			}
		}

		// Dynamic Countdown Label
		$this->add_control(
			'dynamic_countdown_label',
			[
				'label' => esc_html__('Countdown Label', 'valuepack-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__('e.g., Validity:', 'valuepack-addons'),
				'description' => esc_html__('Enter a label to display before the countdown (e.g., "Validity:", "Expires in:", etc.)', 'valuepack-addons'),
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Static Countdown Controls
		$this->add_control(
			'static_countdown_heading',
			[
				'label' => esc_html__('Static Date Settings', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'countdown_source' => 'static',
				],
			]
		);

		// Select: Countdown Type (By Day or By Hour)
		$this->add_control(
			'countdown_type',
			[
				'label' => esc_html__('Countdown By', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'day' => esc_html__('Day', 'valuepack-addons'),
					'hour' => esc_html__('Hour', 'valuepack-addons'),
				],
				'default' => 'day',
				'description' => esc_html__('Choose whether the countdown is set by day or by hour.', 'valuepack-addons'),
				'condition' => [
					'countdown_source' => 'static',
				],
			]
		);

		$this->add_control('countdown_year', array(
			'type'        => Controls_Manager::NUMBER,
			'multiple'    => false,
			'label'       => esc_html__('Year', 'valuepack-addons'),
			'description' => esc_html__('YYYY Format', 'valuepack-addons'),
			'default'     => $this->vp_getCurrentYear(),
			'condition' => [
				'countdown_source' => 'static',
			],
		));
		$this->add_control('countdown_month', array(
			'type'        => Controls_Manager::NUMBER,
			'multiple'    => false,
			'label'       => esc_html__('Month', 'valuepack-addons'),
			'description' => esc_html__('1-12 Format', 'valuepack-addons'),
			'default'     => $this->vp_getNextMonth(),
			'condition' => [
				'countdown_source' => 'static',
			],
		));
		$this->add_control('countdown_days', array(
			'type'        => Controls_Manager::NUMBER,
			'multiple'    => false,
			'label'       => esc_html__('Day', 'valuepack-addons'),
			'description' => esc_html__('1-31 Format', 'valuepack-addons'),
			'default'     => 1,
			'condition' => [
				'countdown_source' => 'static',
			],
		));
		$this->add_control('countdown_hour', array(
			'type'        => Controls_Manager::NUMBER,
			'multiple'    => false,
			'label'       => esc_html__('Hour', 'valuepack-addons'),
			'description' => esc_html__('24 hour Format 0-23', 'valuepack-addons'),
			'default'     => 0,
			'condition' => [
				'countdown_source' => 'static',
			],
		));
		$this->add_control('countdown_minute', array(
			'type'        => Controls_Manager::NUMBER,
			'multiple'    => false,
			'label'       => esc_html__('Minute', 'valuepack-addons'),
			'description' => esc_html__('0-59 Format', 'valuepack-addons'),
			'default'     => 0,
			'condition' => [
				'countdown_source' => 'static',
			],
		));
		$this->add_control('countdown_second', array(
			'type'        => Controls_Manager::NUMBER,
			'multiple'    => false,
			'label'       => esc_html__('Second', 'valuepack-addons'),
			'description' => esc_html__('0-59 Format', 'valuepack-addons'),
			'default'     => 0,
			'condition' => [
				'countdown_source' => 'static',
			],
		));
		$this->add_control(
			'when_expired',
			[
				'label' => esc_html__('When Countdown Expires', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'show_zero' => esc_html__('Show 00 (Default)', 'valuepack-addons'),
					'hide' => esc_html__('Hide Countdown', 'valuepack-addons'),
				],
				'default' => 'show_zero',
				'description' => esc_html__('Choose what happens when the countdown reaches zero.', 'valuepack-addons'),
			]
		);

		// Display Units Section
		$this->add_control(
			'display_units_heading',
			[
				'label' => esc_html__('Display Units', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_days',
			[
				'label' => esc_html__('Show Days', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'days_label',
			[
				'label' => esc_html__('Days Label', 'valuepack-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('days', 'valuepack-addons'),
				'placeholder' => esc_html__('e.g., Days, D, d', 'valuepack-addons'),
				'condition' => [
					'show_days' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_hours',
			[
				'label' => esc_html__('Show Hours', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'hours_label',
			[
				'label' => esc_html__('Hours Label', 'valuepack-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('hours', 'valuepack-addons'),
				'placeholder' => esc_html__('e.g., Hours, H, h', 'valuepack-addons'),
				'condition' => [
					'show_hours' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_minutes',
			[
				'label' => esc_html__('Show Minutes', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'minutes_label',
			[
				'label' => esc_html__('Minutes Label', 'valuepack-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('minutes', 'valuepack-addons'),
				'placeholder' => esc_html__('e.g., Minutes, M, m', 'valuepack-addons'),
				'condition' => [
					'show_minutes' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_seconds',
			[
				'label' => esc_html__('Show Seconds', 'valuepack-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'seconds_label',
			[
				'label' => esc_html__('Seconds Label', 'valuepack-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('seconds', 'valuepack-addons'),
				'placeholder' => esc_html__('e.g., Seconds, S, s', 'valuepack-addons'),
				'condition' => [
					'show_seconds' => 'yes',
				],
			]
		);

		$this->add_control(
			'restart_countdown',
			[
				'label'        => esc_html__('Restart Countdown', 'valuepack-addons'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'valuepack-addons'),
				'label_off'    => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default'      => 'no',
				'description' => __('Enable this option to automatically restart the countdown after it finishes.', 'valuepack-addons'),
			]
		);
		$this->add_control(
			'restart_duration',
			[
				'label' => __('Restart Duration (in minutes)', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 60,
				'description' => __('Set how long (in minutes) the countdown should restart after it ends.', 'valuepack-addons'),
				'condition' => [
					'restart_countdown' => 'yes',
				],
			]
		);
		$this->add_control(
			'color_menu_item_normal',
			[
				'label' => esc_html__('Text Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper span,{{WRAPPER}}  .wc-woo-products-countdown.style2 .wc-timezone-data .wrapper::after ,{{WRAPPER}}  .wc-woo-products-countdown.style3 .wc-timezone-data .wrapper ' => 'color: {{VALUE}}; fill: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section('vp_widget_settings_styles', array(
			'label' => esc_html__('Widget Style', 'valuepack-addons'),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		));

		// Switcher: Enable Border
		$this->add_control(
			'enable_countdown_border',
			[
				'label' => __('Custom Styling', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'style_type' => 'style4',
				],
			]
		);

		// Border Control

		// Hide Labels Control
		$this->add_control(
			'hide_countdown_labels',
			[
				'label' => __('Hide Labels', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .label' => 'display: none;',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'countdown_border',
				'label' => __('Countdown Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data',
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'countdown_time_typography_time',
				'label' => __('Time Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-timezone-data .wrapper .time',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'countdown_time_typography_label',
				'label' => __('Labels Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-timezone-data .wrapper .label',
			]
		);
		// If you really need a second one for .time, give it a distinct purpose and name:
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'countdown_time_typography_secondary',
				'label' => __('Secondary Time Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-timezone-data .wrapper .time',
			]
		);

		// Gap Control (e.g. Margin or Padding)
		$this->add_responsive_control(
			'countdown_gap',
			[
				'label' => __('Countdown Gap', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'allowed_dimensions' => ['top', 'left'], // Using 'top' as row-gap, 'left' as column-gap
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown' => 'gap: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_spacing',
			[
				'label' => __('Countdown Spacing', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_control(
			'countdown_flex_direction',
			[
				'label' => __('Time and Label Direction', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row' => __('Horizontal', 'valuepack-addons'),
					'column' => __('Vertical', 'valuepack-addons'),
					'row-reverse' => __('Horizontal Reverse', 'valuepack-addons'),
					'column-reverse' => __('Vertical Reverse', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_control(
			'countdown_align_items',
			[
				'label' => __('Align Items', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'flex-start' => __('Start', 'valuepack-addons'),
					'center' => __('Center', 'valuepack-addons'),
					'flex-end' => __('End', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_control(
			'countdown_justify_content',
			[
				'label' => __('Justify Content', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'flex-start' => __('Start', 'valuepack-addons'),
					'center' => __('Center', 'valuepack-addons'),
					'flex-end' => __('End', 'valuepack-addons'),
					'space-between' => __('Space Between', 'valuepack-addons'),
					'space-around' => __('Space Around', 'valuepack-addons'),
					'space-evenly' => __('Space Evenly', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);


		$this->add_control(
			'countdown_bg_color',
			[
				'label' => __('Countdown Background Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_control(
			'countdown_time_bg_color',
			[
				'label' => __('Time Background Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);
		$this->add_responsive_control(
			'countdown_border_radius',
			[
				'label' => __('Countdown Border Radius', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_gap_Padding',
			[
				'label' => __('Countdown Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};margin-left: -1px;',
				],
				'condition' => [
					'enable_countdown_border' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section('vp_widget_settings_styles_Separator', array(
			'label' => esc_html__('Widget Separator', 'valuepack-addons'),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			'condition' => [
				'style_type' => 'style4',
			],
		));

		// Add Separator Switcher
		$this->add_control(
			'enable_countdown_separator',
			[
				'label' => __('Add Separator', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'valuepack-addons'),
				'label_off' => __('No', 'valuepack-addons'),
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'display: inline-block;',
				],
				'condition' => [
					'style_type' => 'style4',
				],
			]
		);
		$this->add_control(
			'separator_colors',
			[
				'label' => __('Separator Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_separator' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label' => __('Separator Size', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
					'em' => [
						'min' => 0.1,
						'max' => 5,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_countdown_separator' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_control(
			'separator_font_weight',
			[
				'label' => __('Separator Font Weight', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __('Default', 'valuepack-addons'),
					'100' => '100',
					'200' => '200',
					'300' => '300',
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
					'800' => '800',
					'900' => '900',
					'bold' => __('Bold', 'valuepack-addons'),
					'bolder' => __('Bolder', 'valuepack-addons'),
					'lighter' => __('Lighter', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'font-weight: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_separator' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __('Separator Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_countdown_separator' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_responsive_control(
			'separator_position_top',
			[
				'label' => __('Separator Position Top', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'em' => [
						'min' => -5,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'position: absolute; top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_countdown_separator' => 'yes',
					'style_type' => 'style4',
				],
			]
		);

		$this->add_responsive_control(
			'separator_position_right',
			[
				'label' => __('Separator Position Left', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
					'em' => [
						'min' => -5,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'position: absolute; right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data' => 'position: relative;',
				],
				'condition' => [
					'enable_countdown_separator' => 'yes',
					'style_type' => 'style4',
				],
			]
		);


		// Separator Symbol Type
		$this->add_control(
			'countdown_separator_symbol',
			[
				'label' => __('Separator Symbol', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '|',
				'options' => [
					'|' => __('Vertical Bar |', 'valuepack-addons'),
					':' => __('Colon :', 'valuepack-addons'),
					'-' => __('Dash -', 'valuepack-addons'),
					'/' => __('Slash /', 'valuepack-addons'),
					'custom' => __('Custom', 'valuepack-addons'),
				],
				'condition' => [
					'style_type' => 'style4',
					'enable_countdown_separator' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'content: "{{VALUE}}";   margin-top: 2px;',
				],
			]
		);

		// Custom Separator
		$this->add_control(
			'custom_countdown_separator',
			[
				'label' => __('Custom Separator', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('Enter custom separator', 'valuepack-addons'),
				'condition' => [
					'enable_countdown_separator' => 'yes',
					'countdown_separator_symbol' => 'custom',
					'style_type' => 'style4',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown.wc-wo-countdown.style4 .wc-timezone-data:not(:last-child) .wrapper::after' => 'content: "{{VALUE}}";   margin-top: 2px;',
				],
			]
		);
		$this->end_controls_section();
		
		// Unified Countdown Styling Section
		$this->register_unified_countdown_styling();
	}

	/**
	 * Register unified styling section for all countdown units
	 */
	private function register_unified_countdown_styling()
	{
		$this->start_controls_section(
			'vp_countdown_unified_style',
			array(
				'label' => esc_html__('Countdown Styling', 'valuepack-addons'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Wrapper Styling Heading
		$this->add_control(
			'countdown_wrapper_heading',
			[
				'label' => esc_html__('Wrapper Styling (Label & Countdown Container)', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Wrapper Display
		$this->add_control(
			'countdown_wrapper_display',
			[
				'label' => esc_html__('Display', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'flex',
				'options' => [
					'flex' => esc_html__('Flex', 'valuepack-addons'),
					'inline-flex' => esc_html__('Inline Flex', 'valuepack-addons'),
					'block' => esc_html__('Block', 'valuepack-addons'),
					'inline-block' => esc_html__('Inline Block', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-wrapper' => 'display: {{VALUE}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Wrapper Flex Direction
		$this->add_control(
			'countdown_wrapper_flex_direction',
			[
				'label' => esc_html__('Label Position', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row' => esc_html__('Left (Label before Countdown)', 'valuepack-addons'),
					'row-reverse' => esc_html__('Right (Label after Countdown)', 'valuepack-addons'),
					'column' => esc_html__('Top (Label above Countdown)', 'valuepack-addons'),
					'column-reverse' => esc_html__('Bottom (Label below Countdown)', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-wrapper' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
					'countdown_wrapper_display' => ['flex', 'inline-flex'],
				],
			]
		);

		// Wrapper Alignment
		$this->add_control(
			'countdown_wrapper_align_items',
			[
				'label' => esc_html__('Vertical Alignment', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'flex-start' => esc_html__('Top', 'valuepack-addons'),
					'center' => esc_html__('Center', 'valuepack-addons'),
					'flex-end' => esc_html__('Bottom', 'valuepack-addons'),
					'stretch' => esc_html__('Stretch', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-wrapper' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
					'countdown_wrapper_display' => ['flex', 'inline-flex'],
				],
			]
		);

		// Wrapper Justify Content
		$this->add_control(
			'countdown_wrapper_justify_content',
			[
				'label' => esc_html__('Horizontal Alignment', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'flex-start',
				'options' => [
					'flex-start' => esc_html__('Left', 'valuepack-addons'),
					'center' => esc_html__('Center', 'valuepack-addons'),
					'flex-end' => esc_html__('Right', 'valuepack-addons'),
					'space-between' => esc_html__('Space Between', 'valuepack-addons'),
					'space-around' => esc_html__('Space Around', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-wrapper' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
					'countdown_wrapper_display' => ['flex', 'inline-flex'],
				],
			]
		);

		// Wrapper Gap
		$this->add_responsive_control(
			'countdown_wrapper_gap',
			[
				'label' => esc_html__('Gap Between Label & Countdown', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
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
					'{{WRAPPER}} .vp-countdown-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
					'countdown_wrapper_display' => ['flex', 'inline-flex'],
				],
			]
		);

		// Wrapper Padding
		$this->add_responsive_control(
			'countdown_wrapper_padding',
			[
				'label' => esc_html__('Wrapper Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Wrapper Margin
		$this->add_responsive_control(
			'countdown_wrapper_margin',
			[
				'label' => esc_html__('Wrapper Margin', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Container Styling Heading
		$this->add_control(
			'countdown_container_heading',
			[
				'label' => esc_html__('Countdown Container Styling', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Background Color
		$this->add_control(
			'unified_countdown_background_color',
			[
				'label' => esc_html__('Background Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Label Color
		$this->add_control(
			'unified_countdown_label_color',
			[
				'label' => esc_html__('Label Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .label' => 'color: {{VALUE}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'unified_countdown_padding',
			[
				'label' => esc_html__('Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'unified_countdown_margin',
			[
				'label' => esc_html__('Margin', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'unified_countdown_border',
				'label' => esc_html__('Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'unified_countdown_border_radius',
			[
				'label' => esc_html__('Border Radius', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box Shadow
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'unified_countdown_box_shadow',
				'label' => esc_html__('Box Shadow', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data',
			]
		);

		// Typography for Label
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'unified_countdown_label_typography',
				'label' => esc_html__('Label Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .label',
			]
		);

		// Width
		$this->add_responsive_control(
			'unified_countdown_width',
			[
				'label' => esc_html__('Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'unified_countdown_height',
			[
				'label' => esc_html__('Height', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Individual Digit Styling Heading
		$this->add_control(
			'countdown_digit_heading',
			[
				'label' => esc_html__('Individual Digit Styling', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Gap Between Digits
		$this->add_responsive_control(
			'countdown_digit_gap',
			[
				'label' => esc_html__('Gap Between Digits', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time' => 'display: inline-flex; gap: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__('Set the spacing between individual digits.', 'valuepack-addons'),
			]
		);

		// Digit Text Color
		$this->add_control(
			'countdown_digit_text_color',
			[
				'label' => esc_html__('Digit Text Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'color: {{VALUE}};',
				],
			]
		);

		// Digit Background Color
		$this->add_control(
			'countdown_digit_background_color',
			[
				'label' => esc_html__('Digit Background Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Digit Padding
		$this->add_responsive_control(
			'countdown_digit_padding',
			[
				'label' => esc_html__('Digit Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Digit Margin
		$this->add_responsive_control(
			'countdown_digit_margin',
			[
				'label' => esc_html__('Digit Margin', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Digit Border
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'countdown_digit_border',
				'label' => esc_html__('Digit Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit',
			]
		);

		// Digit Border Radius
		$this->add_responsive_control(
			'countdown_digit_border_radius',
			[
				'label' => esc_html__('Digit Border Radius', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Digit Box Shadow
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'countdown_digit_box_shadow',
				'label' => esc_html__('Digit Box Shadow', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit',
			]
		);

		// Digit Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'countdown_digit_typography',
				'label' => esc_html__('Digit Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit',
			]
		);

		// Digit Width
		$this->add_responsive_control(
			'countdown_digit_width',
			[
				'label' => esc_html__('Digit Width', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Digit Height
		$this->add_responsive_control(
			'countdown_digit_height',
			[
				'label' => esc_html__('Digit Height', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Digit Display
		$this->add_control(
			'countdown_digit_display',
			[
				'label' => esc_html__('Digit Display', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'inline-block',
				'options' => [
					'inline' => esc_html__('Inline', 'valuepack-addons'),
					'inline-block' => esc_html__('Inline Block', 'valuepack-addons'),
					'block' => esc_html__('Block', 'valuepack-addons'),
					'flex' => esc_html__('Flex', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'display: {{VALUE}};',
				],
			]
		);

		// Digit Alignment
		$this->add_control(
			'countdown_digit_alignment',
			[
				'label' => esc_html__('Digit Alignment', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'left' => esc_html__('Left', 'valuepack-addons'),
					'center' => esc_html__('Center', 'valuepack-addons'),
					'right' => esc_html__('Right', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-products-countdown .wc-timezone-data .wrapper .time .digit' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Dynamic Countdown Label Styling Heading
		$this->add_control(
			'countdown_label_heading',
			[
				'label' => esc_html__('Countdown Label Styling', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Label Text Color
		$this->add_control(
			'countdown_label_text_color',
			[
				'label' => esc_html__('Label Text Color', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Label Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'countdown_label_typography',
				'label' => esc_html__('Label Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-countdown-label',
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Label Margin
		$this->add_responsive_control(
			'countdown_label_margin',
			[
				'label' => esc_html__('Label Margin', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Label Padding
		$this->add_responsive_control(
			'countdown_label_padding',
			[
				'label' => esc_html__('Label Padding', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		// Label Alignment
		$this->add_control(
			'countdown_label_alignment',
			[
				'label' => esc_html__('Label Alignment', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'valuepack-addons'),
					'center' => esc_html__('Center', 'valuepack-addons'),
					'right' => esc_html__('Right', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-countdown-label' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'countdown_source' => 'dynamic',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$style_type = sanitize_key($settings['style_type']);
		$countdown_source = isset($settings['countdown_source']) ? sanitize_key($settings['countdown_source']) : 'static';
		$when_expired = isset($settings['when_expired']) ? sanitize_key($settings['when_expired']) : 'show_zero';
		$restart_countdown = isset($settings['restart_countdown']) && $settings['restart_countdown'] === 'yes' ? 'yes' : 'no';
		$restart_duration = isset($settings['restart_duration']) && absint($settings['restart_duration']) > 0 ? absint($settings['restart_duration']) : 60;

		// Display units settings
		$show_days = isset($settings['show_days']) && $settings['show_days'] === 'yes' ? 'yes' : 'no';
		$show_hours = isset($settings['show_hours']) && $settings['show_hours'] === 'yes' ? 'yes' : 'no';
		$show_minutes = isset($settings['show_minutes']) && $settings['show_minutes'] === 'yes' ? 'yes' : 'no';
		$show_seconds = isset($settings['show_seconds']) && $settings['show_seconds'] === 'yes' ? 'yes' : 'no';

		// Custom labels
		$days_label = isset($settings['days_label']) && !empty($settings['days_label']) ? sanitize_text_field($settings['days_label']) : 'days';
		$hours_label = isset($settings['hours_label']) && !empty($settings['hours_label']) ? sanitize_text_field($settings['hours_label']) : 'hours';
		$minutes_label = isset($settings['minutes_label']) && !empty($settings['minutes_label']) ? sanitize_text_field($settings['minutes_label']) : 'minutes';
		$seconds_label = isset($settings['seconds_label']) && !empty($settings['seconds_label']) ? sanitize_text_field($settings['seconds_label']) : 'seconds';

		// Dynamic countdown label
		$dynamic_countdown_label = '';
		if ($countdown_source === 'dynamic') {
			$dynamic_countdown_label = isset($settings['dynamic_countdown_label']) && !empty($settings['dynamic_countdown_label']) ? sanitize_text_field($settings['dynamic_countdown_label']) : '';
		}

		// Initialize default values
		$countdown_year = $this->vp_getCurrentYear();
		$countdown_month = $this->vp_getNextMonth();
		$countdown_days = 1;
		$countdown_hour = 0;
		$countdown_minute = 0;
		$countdown_second = 0;
		$countdown_type = 'day';

		// Initialize start date variables
		$start_year = 0;
		$start_month = 0;
		$start_days = 0;
		$start_hour = 0;
		$start_minute = 0;
		$start_second = 0;
		$has_start_date = false;

		// Handle dynamic countdown
		if ($countdown_source === 'dynamic') {
			$dynamic_date = $this->get_dynamic_date($settings);
			// If there's no dynamic end date, do not render the countdown at all.
			// This prevents showing 00:00:00 when the end-date field is empty/missing.
			if (!$dynamic_date) {
				return;
			}

			$countdown_year = (int) date('Y', $dynamic_date);
			$countdown_month = (int) date('n', $dynamic_date);
			$countdown_days = (int) date('j', $dynamic_date);
			$countdown_hour = (int) date('G', $dynamic_date);
			$countdown_minute = (int) date('i', $dynamic_date);
			$countdown_second = (int) date('s', $dynamic_date);
			
			// Get dynamic start date
			$dynamic_start_date = $this->get_dynamic_start_date($settings);
			if ($dynamic_start_date) {
				$start_year = (int) date('Y', $dynamic_start_date);
				$start_month = (int) date('n', $dynamic_start_date);
				$start_days = (int) date('j', $dynamic_start_date);
				$start_hour = (int) date('G', $dynamic_start_date);
				$start_minute = (int) date('i', $dynamic_start_date);
				$start_second = (int) date('s', $dynamic_start_date);
				$has_start_date = true;
			}
		} else {
			// Static countdown
			$countdown_type = isset($settings['countdown_type']) ? sanitize_key($settings['countdown_type']) : 'day';
			$countdown_year = isset($settings['countdown_year']) ? min(max(absint($settings['countdown_year']), 1970), 2100) : $this->vp_getCurrentYear();
			$countdown_month = isset($settings['countdown_month']) ? min(max(absint($settings['countdown_month']), 1), 12) : $this->vp_getNextMonth();
			$countdown_days = isset($settings['countdown_days']) ? min(max(absint($settings['countdown_days']), 1), 31) : 1;
			$countdown_hour = isset($settings['countdown_hour']) ? min(max(absint($settings['countdown_hour']), 0), 23) : 0;
			$countdown_minute = isset($settings['countdown_minute']) ? min(max(absint($settings['countdown_minute']), 0), 59) : 0;
			$countdown_second = isset($settings['countdown_second']) ? min(max(absint($settings['countdown_second']), 0), 59) : 0;
			
			// Get static start date
			$enable_start_date = isset($settings['enable_start_date']) && $settings['enable_start_date'] === 'yes';
			if ($enable_start_date) {
				$start_year = isset($settings['start_countdown_year']) ? min(max(absint($settings['start_countdown_year']), 1970), 2100) : $this->vp_getCurrentYear();
				$start_month = isset($settings['start_countdown_month']) ? min(max(absint($settings['start_countdown_month']), 1), 12) : $this->vp_getNextMonth();
				$start_days = isset($settings['start_countdown_days']) ? min(max(absint($settings['start_countdown_days']), 1), 31) : 1;
				$start_hour = isset($settings['start_countdown_hour']) ? min(max(absint($settings['start_countdown_hour']), 0), 23) : 0;
				$start_minute = isset($settings['start_countdown_minute']) ? min(max(absint($settings['start_countdown_minute']), 0), 59) : 0;
				$start_second = isset($settings['start_countdown_second']) ? min(max(absint($settings['start_countdown_second']), 0), 59) : 0;
				$has_start_date = true;
			}
		}
?>
		<?php if (!empty($dynamic_countdown_label)) : ?>
			<div class="vp-countdown-wrapper">
				<span class="vp-countdown-label" id="vp-countdown-label-<?php echo esc_attr($this->get_id()); ?>"><?php echo esc_html($dynamic_countdown_label); ?></span>
		<?php endif; ?>
		<div id="wc-woo-products-countdown-<?php echo esc_attr($this->get_id()); ?>"
			class="wc-woo-products-countdown wc-wo-countdown <?php echo esc_attr($style_type); ?>"
			data-year='<?php echo esc_attr($countdown_year); ?>'
			data-month='<?php echo esc_attr($countdown_month); ?>'
			data-day='<?php echo esc_attr($countdown_days); ?>'
			data-hour='<?php echo esc_attr($countdown_hour); ?>'
			data-minute='<?php echo esc_attr($countdown_minute); ?>'
			data-second='<?php echo esc_attr($countdown_second); ?>'
			data-type='<?php echo esc_attr($countdown_type); ?>'
			data-restart='<?php echo esc_attr($restart_countdown); ?>'
			data-duration='<?php echo esc_attr($restart_duration); ?>'
			data-source='<?php echo esc_attr($countdown_source); ?>'
			data-when-expired='<?php echo esc_attr($when_expired); ?>'
			data-show-days='<?php echo esc_attr($show_days); ?>'
			data-show-hours='<?php echo esc_attr($show_hours); ?>'
			data-show-minutes='<?php echo esc_attr($show_minutes); ?>'
			data-show-seconds='<?php echo esc_attr($show_seconds); ?>'
			data-days-label='<?php echo esc_attr($days_label); ?>'
			data-hours-label='<?php echo esc_attr($hours_label); ?>'
			data-minutes-label='<?php echo esc_attr($minutes_label); ?>'
			data-seconds-label='<?php echo esc_attr($seconds_label); ?>'
			<?php if ($has_start_date) : ?>
			data-start-year='<?php echo esc_attr($start_year); ?>'
			data-start-month='<?php echo esc_attr($start_month); ?>'
			data-start-day='<?php echo esc_attr($start_days); ?>'
			data-start-hour='<?php echo esc_attr($start_hour); ?>'
			data-start-minute='<?php echo esc_attr($start_minute); ?>'
			data-start-second='<?php echo esc_attr($start_second); ?>'
			data-has-start-date='1'
			<?php else : ?>
			data-has-start-date='0'
			<?php endif; ?>>
		</div>
		<?php if (!empty($dynamic_countdown_label)) : ?>
			</div>
		<?php endif; ?>
<?php
	}

	/**
	 * Get date and datetime picker fields for a specific post type
	 *
	 * @param string $post_type Post type slug
	 * @return array Array of field_key => field_label
	 */
	private function value_pack_get_countdown_date_fields($post_type)
	{
		$fields = array('' => esc_html__('Select Date Field', 'valuepack-addons'));
		if (function_exists('get_fields_by_post_type')) {
			$post_type_fields = get_fields_by_post_type($post_type);
			if (!empty($post_type_fields) && is_array($post_type_fields)) {
				foreach ($post_type_fields as $field_key => $field_label) {
					$field_options = get_field_options($field_key);
					if (isset($field_options['type']) && in_array($field_options['type'], array('date_picker', 'date_time_picker'))) {
						$fields[$field_key] = $field_label;
					}
				}
			}
		}
		return $fields;
	}

	/**
	 * Get dynamic date from post field
	 *
	 * @param array $settings Widget settings
	 * @return int|false Timestamp or false on failure
	 */
	private function get_dynamic_date($settings)
	{
		$post_type = isset($settings['dynamic_post_type']) ? sanitize_text_field($settings['dynamic_post_type']) : '';
		if (empty($post_type)) {
			return false;
		}
		$post_id = get_the_ID();
		if ($settings['post_id_source'] === 'custom_post') {
			$post_id = $settings['post_id'];
		} elseif ($settings['post_id_source'] === 'associated_post') {
			$current_post_id = get_the_ID();
			$associated_post_meta_key = isset($settings['associated_post_meta_key']) ? sanitize_text_field($settings['associated_post_meta_key']) : '';
			$args = array(
				'post_type' => $post_type,
				'posts_per_page' => 1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC',
				'meta_query' => array(
					array(
						'key' => $associated_post_meta_key,
						'value' => $current_post_id,
						'compare' => '==',
					),
				),
			);
			$posts = get_posts($args);
			if (!empty($posts)) {
				$post_id = $posts[0]->ID;
			}
		}

		// In Elementor edit mode, get first post of selected post type for preview
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            if (empty($post_id) || get_post_type($post_id) !== $post_type) {
                $first_post = get_posts(array(
                    'post_type' => $post_type,
                    'posts_per_page' => 1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                if (!empty($first_post)) {
                    $post_id = $first_post[0]->ID;
                }
            }
        }

		if (empty($post_id)) {
			if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
				echo '<div class="elementor-alert elementor-alert-warning">' . esc_html__('No posts found for the selected post type.', 'valuepack-addons') . '</div>';
			}
			return false;
		}

		// Get date field based on post type (using the same pattern as gallery widget)
		// Default to end date field for backward compatibility
		$field_key = 'dynamic_end_date_field_';
		if (isset($settings['dynamic_end_date_field_' . $post_type])) {
			$field_key = 'dynamic_end_date_field_';
		} elseif (isset($settings['dynamic_date_field_' . $post_type])) {
			// Backward compatibility with old field name
			$field_key = 'dynamic_date_field_';
		}
		
		$date_field = isset($settings[$field_key . $post_type]) ? sanitize_text_field($settings[$field_key . $post_type]) : '';
		if (empty($date_field)) {
			return false;
		}

		// Get the date value from post meta
		$date_value = get_post_meta($post_id, $date_field, true);
		if (empty($date_value)) {
			return false;
		}

		// Convert to timestamp
		$timestamp = $this->convert_to_timestamp($date_value);
		return $timestamp;
	}

	/**
	 * Get dynamic start date from post field
	 *
	 * @param array $settings Widget settings
	 * @return int|false Timestamp or false on failure
	 */
	private function get_dynamic_start_date($settings)
	{
		$post_id = get_the_ID();
		
		$post_type = isset($settings['dynamic_post_type']) ? sanitize_text_field($settings['dynamic_post_type']) : '';
		if (empty($post_type)) {
			return false;
		}

		// In Elementor edit mode, get first post of selected post type for preview
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            if (empty($post_id) || get_post_type($post_id) !== $post_type) {
                $first_post = get_posts(array(
                    'post_type' => $post_type,
                    'posts_per_page' => 1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                if (!empty($first_post)) {
                    $post_id = $first_post[0]->ID;
                }
            }
        }

         if (empty($post_id)) {
             return false;
         }

		// Get start date field based on post type
		$date_field = isset($settings['dynamic_start_date_field_' . $post_type]) ? sanitize_text_field($settings['dynamic_start_date_field_' . $post_type]) : '';
		
		// Start date is optional, return false if not set
		if (empty($date_field)) {
			return false;
		}

		// Get the date value from post meta
		$date_value = get_post_meta($post_id, $date_field, true);
		if (empty($date_value)) {
			return false;
		}

		// Convert to timestamp
		$timestamp = $this->convert_to_timestamp($date_value);
		return $timestamp;
	}

	/**
	 * Convert date value to timestamp
	 *
	 * @param string|int $date_value Date value (can be timestamp or date string)
	 * @return int|false Timestamp or false on failure
	 */
	private function convert_to_timestamp($date_value)
	{
		if (empty($date_value)) {
			return false;
		}

		// If it's already a numeric timestamp
		if (is_numeric($date_value) && $date_value > 0) {
			$timestamp = intval($date_value);
			// If it's a Unix timestamp (reasonable range check: 2000-01-01 to 2038-01-19)
			if ($timestamp > 946684800 && $timestamp < 2147483647) {
				return $timestamp;
			}
		}

		// If it's a datetime string, try to parse it
		// Handle ISO 8601 format (e.g., "2024-01-15T14:30:00")
		$datetime_string = str_replace('T', ' ', $date_value);
		
		// Try to parse the datetime string
		$timestamp = strtotime($datetime_string);
		
		return $timestamp !== false ? $timestamp : false;
	}
}
