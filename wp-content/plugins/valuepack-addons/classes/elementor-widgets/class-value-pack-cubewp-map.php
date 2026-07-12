<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

/**
 * CubeWP Single Map Widget.
 *
 * Elementor Widget For Single Map By CubeWP.
 *
 * @since 1.0.0
 */
class Value_Pack_CubeWp_Map extends Widget_Base
{

	private static $post_types = array();

	public function get_name()
	{
		return 'vp_cubewp_map';
	}

	public function get_title()
	{
		return esc_html__('CubeWP Map', 'valuepack-addons');
	}

	public function get_icon()
	{
		return 'eicon-map-pin vpack-icon';
	}

	public function get_categories()
	{
		return array('value_pack');
	}

	public function get_keywords()
	{
		return array(
			'cubewp',
			'map',
			'location',
			'google map',
			'address',
			'geolocation',
			'pin',
			'marker'
		);
	}

	/**
	 * Get script dependencies
	 */
	public function get_script_depends()
	{
		return array('value-pack-map', 'cubewp-leaflet', 'cubewp-leaflet-cluster', 'cubewp-map');
	}

	/**
	 * Get style dependencies
	 */
	public function get_style_depends()
	{
		return array('value-pack-styles', 'cwp-leaflet-css', 'cwp-map-cluster');
	}


	protected function register_controls()
	{
		self::get_post_types();

		$this->start_controls_section('cubewp_map_section', array(
			'label' => esc_html__('Map Options', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		// Post Type Selection
		$this->add_control('post_type', array(
			'type'        => Controls_Manager::SELECT,
			'label'       => esc_html__('Select Post Type', 'valuepack-addons'),
			'description' => esc_html__('Select the post type to get map fields from.', 'valuepack-addons'),
			'options'     => self::$post_types,
			'default'     => 'post',
			'label_block' => true,
		));

		foreach (self::$post_types as $post_type => $post_type_label) {
			$map_fields = self::value_pack_get_cubewp_map_fields($post_type);
			$this->add_control('map_field_' . $post_type, array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__('Select Map Field', 'valuepack-addons'),
				'description' => esc_html__('Select the map field to display. Fields are filtered based on selected post type.', 'valuepack-addons'),
				'options'     => $map_fields,
				'label_block' => true,
				'condition'   => array(
					'post_type' => $post_type,
				),
			));
		}

		// Source Selection
		$this->add_control('map_source', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Map Source', 'valuepack-addons'),
			'options' => array(
				'current_post' => esc_html__('Current Post', 'valuepack-addons'),
				'specific_post' => esc_html__('Specific Post', 'valuepack-addons'),
				'multiple_posts' => esc_html__('Multiple Posts', 'valuepack-addons'),
			),
			'default' => 'current_post',
		));

		// Specific Post ID
		$this->add_control('specific_post_id', array(
			'type'        => Controls_Manager::NUMBER,
			'label'       => esc_html__('Post ID', 'valuepack-addons'),
			'description' => esc_html__('Enter the post ID to get map from.', 'valuepack-addons'),
			'condition'  => array(
				'map_source' => 'specific_post',
			),
		));

		// Multiple Posts IDs
		$this->add_control('multiple_posts_ids', array(
			'type'        => Controls_Manager::TEXTAREA,
			'label'       => esc_html__('Post IDs', 'valuepack-addons'),
			'description' => esc_html__('Enter post IDs separated by commas (e.g., 12, 45, 78).', 'valuepack-addons'),
			'condition'  => array(
				'map_source' => 'multiple_posts',
			),
		));

		$this->end_controls_section();

		// Map Settings Section
		$this->start_controls_section('map_settings_section', array(
			'label' => esc_html__('Map Settings', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		// Map Height
		$this->add_responsive_control('map_height', array(
			'type'        => Controls_Manager::SLIDER,
			'label'       => esc_html__('Map Height', 'valuepack-addons'),
			'size_units'  => array('px', 'vh', 'em'),
			'range'       => array(
				'px' => array(
					'min' => 200,
					'max' => 1000,
					'step' => 10,
				),
				'vh' => array(
					'min' => 20,
					'max' => 100,
					'step' => 1,
				),
			),
			'default'     => array(
				'unit' => 'px',
				'size' => 400,
			),
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-map-container' => 'height: {{SIZE}}{{UNIT}};',
			),
		));

		// Default Zoom Level
		$this->add_control('map_zoom', array(
			'type'    => Controls_Manager::SLIDER,
			'label'   => esc_html__('Default Zoom Level', 'valuepack-addons'),
			'range'   => array(
				'px' => array(
					'min' => 1,
					'max' => 20,
					'step' => 1,
				),
			),
			'default' => array(
				'size' => 12,
			),
		));

		// Enable Zoom Controls
		$this->add_control('enable_zoom_controls', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Zoom Controls', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => 'yes',
		));

		// Enable Fullscreen Control
		$this->add_control('enable_fullscreen_control', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Fullscreen Control', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => 'yes',
		));

		// Enable Street View Control
		$this->add_control('enable_street_view_control', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Street View Control', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => '',
		));

		$this->end_controls_section();

		// Map Pin/Marker Settings Section
		$this->start_controls_section('map_pin_section', array(
			'label' => esc_html__('Map Pin/Marker Settings', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		// Custom Pin Icon
		$this->add_control('custom_pin_icon', array(
			'type' => Controls_Manager::ICONS,
			'label'       => esc_html__('Custom Pin Icon', 'valuepack-addons'),
			'description' => esc_html__('Upload a custom icon for the map pin. Leave empty to use default.', 'valuepack-addons'),
		));

		// Pin Icon Size
		$this->add_responsive_control('pin_icon_size', array(
			'type'        => Controls_Manager::SLIDER,
			'label'       => esc_html__('Pin Icon Size', 'valuepack-addons'),
			'size_units'  => array('px'),
			'range'       => array(
				'px' => array(
					'min' => 20,
					'max' => 100,
					'step' => 5,
				),
			),
			'default'     => array(
				'unit' => 'px',
				'size' => 40,
			),
		));

		// Pin Anchor Point X
		$this->add_control('pin_anchor_x', array(
			'type'    => Controls_Manager::SLIDER,
			'label'   => esc_html__('Pin Anchor Point X', 'valuepack-addons'),
			'description' => esc_html__('Horizontal position of the anchor point (0-1).', 'valuepack-addons'),
			'range'   => array(
				'px' => array(
					'min' => 0,
					'max' => 1,
					'step' => 0.1,
				),
			),
			'default' => array(
				'size' => 0.5,
			),
		));

		// Pin Anchor Point Y
		$this->add_control('pin_anchor_y', array(
			'type'    => Controls_Manager::SLIDER,
			'label'   => esc_html__('Pin Anchor Point Y', 'valuepack-addons'),
			'description' => esc_html__('Vertical position of the anchor point (0-1).', 'valuepack-addons'),
			'range'   => array(
				'px' => array(
					'min' => 0,
					'max' => 1,
					'step' => 0.1,
				),
			),
			'default' => array(
				'size' => 1,
			),
		));

		// Enable Info Window
		$this->add_control('enable_info_window', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Info Window', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => 'yes',
		));

		// Info Window Content
		$this->add_control('info_window_content', array(
			'type'        => Controls_Manager::TEXTAREA,
			'label'       => esc_html__('Info Window Content', 'valuepack-addons'),
			'description' => esc_html__('Custom content for the info window. Leave empty to use post title. You can use {title}, {address}, {post_link} as placeholders.', 'valuepack-addons'),
			'condition'  => array(
				'enable_info_window' => 'yes',
			),
		));

		$this->end_controls_section();

		// Address & Get Directions Section
		$this->start_controls_section('map_address_directions_section', array(
			'label' => esc_html__('Address & Get Directions', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		$this->add_control('show_address_after_map', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Show Address After Map', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => '',
		));

		$this->add_control('address_label', array(
			'type'      => Controls_Manager::TEXT,
			'label'     => esc_html__('Address Label', 'valuepack-addons'),
			'default'   => esc_html__('Address:', 'valuepack-addons'),
			'condition' => array(
				'show_address_after_map' => 'yes',
			),
		));

		$this->add_control('address_icon', array(
			'type'      => Controls_Manager::ICONS,
			'label'     => esc_html__('Address Icon', 'valuepack-addons'),
			'description' => esc_html__('Optional icon to show with the address (e.g. location pin).', 'valuepack-addons'),
			'condition' => array(
				'show_address_after_map' => 'yes',
			),
		));

		$this->add_control('address_icon_position', array(
			'type'      => Controls_Manager::SELECT,
			'label'     => esc_html__('Icon Position', 'valuepack-addons'),
			'options'   => array(
				'before_label' => esc_html__('Before Label', 'valuepack-addons'),
				'after_label'  => esc_html__('After Label', 'valuepack-addons'),
				'before_text'  => esc_html__('Before Address Text', 'valuepack-addons'),
				'after_text'   => esc_html__('After Address Text', 'valuepack-addons'),
			),
			'default'   => 'before_label',
			'condition' => array(
				'show_address_after_map' => 'yes',
				'address_icon[value]!' => '',
			),
		));

		$this->add_control('enable_get_directions', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Get Directions Button', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => '',
		));

		$this->add_control('get_directions_text', array(
			'type'      => Controls_Manager::TEXT,
			'label'     => esc_html__('Button Text', 'valuepack-addons'),
			'default'   => esc_html__('Get Directions', 'valuepack-addons'),
			'condition' => array(
				'enable_get_directions' => 'yes',
			),
		));

		$this->add_control('get_directions_icon', array(
			'type'      => Controls_Manager::ICONS,
			'label'     => esc_html__('Button Icon', 'valuepack-addons'),
			'condition' => array(
				'enable_get_directions' => 'yes',
			),
		));

		$this->add_control('get_directions_icon_position', array(
			'type'      => Controls_Manager::SELECT,
			'label'     => esc_html__('Icon Position', 'valuepack-addons'),
			'options'   => array(
				'before' => esc_html__('Before', 'valuepack-addons'),
				'after'  => esc_html__('After', 'valuepack-addons'),
			),
			'default'   => 'before',
			'condition' => array(
				'enable_get_directions' => 'yes',
				'get_directions_icon[value]!' => '',
			),
		));

		$this->add_control('get_directions_new_tab', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Open in New Tab', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'   => array(
				'enable_get_directions' => 'yes',
			),
		));

		$this->end_controls_section();

		// Map Style Section
		$this->start_controls_section('map_style_section', array(
			'label' => esc_html__('Map Style', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_STYLE,
		));

		$this->add_control(
			'map_container_overflow_hidden',
			[
				'label' => esc_html__('Overflow Hidden', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'valuepack-addons'),
				'label_off' => esc_html__('No', 'valuepack-addons'),
				'return_value' => 'hidden',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-map-container' => 'overflow: {{VALUE}};',
				],
			]
		);

		// Map Border Radius
		$this->add_control('map_border_radius', array(
			'label'      => esc_html__('Border Radius', 'valuepack-addons'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array('px', '%'),
			'default'    => array(
				'top' => '8',
				'right' => '8',
				'bottom' => '8',
				'left' => '8',
				'unit' => 'px',
				'isLinked' => true,
			),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		));

		// Map Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'map_border',
				'label'    => esc_html__('Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-map-container',
				'fields_options' => array(
					'color' => array('default' => '#e5e7eb'),
					'width' => array(
						'default' => array(
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'unit' => 'px',
							'isLinked' => true,
						),
					),
				),
			)
		);

		// Map Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'map_box_shadow',
				'label'    => esc_html__('Box Shadow', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-map-container',
				'fields_options' => array(
					'box_shadow' => array(
						'default' => array(
							'horizontal' => 0,
							'vertical' => 1,
							'blur' => 3,
							'spread' => 0,
							'color' => 'rgba(0,0,0,0.08)',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		// Address & Directions Container style
		$this->start_controls_section('address_directions_container', array(
			'label' => esc_html__('Address & Directions Container', 'valuepack-addons'),
			'tab' => Controls_Manager::TAB_STYLE,
		));



		$this->add_responsive_control('address_directions_container_padding', array(
			'type' => Controls_Manager::DIMENSIONS,
			'label' => esc_html__('Padding', 'valuepack-addons'),
			'size_units' => array('px', 'em', 'rem'),
			'default' => array(
				'top' => '10',
				'right' => '20',
				'bottom' => '10',
				'left' => '20',
				'unit' => 'px',
				'isLinked' => false,
			),
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		));

		$this->add_responsive_control('address_directions_container_margin', array(
			'type' => Controls_Manager::DIMENSIONS,
			'label' => esc_html__('Margin', 'valuepack-addons'),
			'size_units' => array('px', 'em', 'rem'),
			'default' => array(
				'top' => '15',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
				'isLinked' => false,
			),
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		));

		$this->add_responsive_control(
			'address_directions_display',
			[
				'label' => esc_html__('Display', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex' => [
						'title' => esc_html__('Flex', 'valuepack-addons'),
						'icon' => 'eicon-flex',
					],
					'block' => [
						'title' => esc_html__('Block', 'valuepack-addons'),
						'icon' => 'eicon-editor-list-ul',
					],
				],
				'default' => 'flex',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-map-get-directions-wrap' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'address_directions_direction',
			[
				'label' => esc_html__('Direction', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__('Row', 'valuepack-addons'),
						'icon' => 'eicon-arrow-right',
					],
					'column' => [
						'title' => esc_html__('Column', 'valuepack-addons'),
						'icon' => 'eicon-arrow-down',
					],
					'row-reverse' => [
						'title' => esc_html__('Row Reverse', 'valuepack-addons'),
						'icon' => 'eicon-arrow-left',
					],
					'column-reverse' => [
						'title' => esc_html__('Column Reverse', 'valuepack-addons'),
						'icon' => 'eicon-arrow-up',
					],
				],
				'default' => 'row',
				'condition' => [
					'address_directions_display' => 'flex',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-map-get-directions-wrap' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'address_directions_justify',
			[
				'label' => esc_html__('Justify Content', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__('Start', 'valuepack-addons'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'valuepack-addons'),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__('End', 'valuepack-addons'),
						'icon' => 'eicon-h-align-right',
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
				'condition' => [
					'address_directions_display' => 'flex',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-map-get-directions-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'address_directions_align',
			[
				'label' => esc_html__('Align Items', 'valuepack-addons'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__('Start', 'valuepack-addons'),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__('Center', 'valuepack-addons'),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__('End', 'valuepack-addons'),
						'icon' => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => esc_html__('Stretch', 'valuepack-addons'),
						'icon' => 'eicon-v-align-stretch',
					],
				],
				'condition' => [
					'address_directions_display' => 'flex',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-map-get-directions-wrap' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Address style
		$this->start_controls_section('map_address_style_section', array(
			'label'     => esc_html__('Address Style', 'valuepack-addons'),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'show_address_after_map' => 'yes',
			),
		));
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'address_typography',
				'label'    => esc_html__('Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-map-address-wrap',
				'fields_options' => array(
					'typography'  => array('default' => 'yes'),
					'font_size'   => array('default' => array('size' => 15, 'unit' => 'px')),
					'font_weight' => array('default' => '400'),
					'line_height' => array('default' => array('size' => 1.5, 'unit' => 'em')),
				),
			)
		);
		$this->add_control('address_label_color', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Label Color', 'valuepack-addons'),
			'default'   => '#6b7280',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-address-label' => 'color: {{VALUE}};',
			),
		));
		$this->add_control('address_text_color', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Text Color', 'valuepack-addons'),
			'default'   => '#374151',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-address-wrap' => 'color: {{VALUE}};',
			),
		));
		$this->add_responsive_control('address_spacing', array(
			'type'       => Controls_Manager::DIMENSIONS,
			'label'      => esc_html__('Spacing', 'valuepack-addons'),
			'size_units' => array('px', 'em', 'rem'),
			'default'    => array(
				'top' => '15',
				'right' => '0',
				'bottom' => '10',
				'left' => '0',
				'unit' => 'px',
				'isLinked' => false,
			),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-address-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		));
		$this->add_responsive_control('address_icon_size', array(
			'label'      => esc_html__('Icon Size', 'valuepack-addons'),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array('px', 'em'),
			'range'      => array('px' => array('min' => 8, 'max' => 48), 'em' => array('min' => 0.5, 'max' => 2)),
			'default'    => array('size' => 18, 'unit' => 'px'),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-address-wrap .vp-map-address-icon' => 'font-size: {{SIZE}}{{UNIT}}; display: inline-block; vertical-align: middle;',
				'{{WRAPPER}} .vp-cubewp-map-address-wrap .vp-map-address-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			),
		));
		$this->add_control('address_icon_color', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Icon Color', 'valuepack-addons'),
			'default'   => '#6b7280',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-address-wrap .vp-map-address-icon' => 'color: {{VALUE}};',
				'{{WRAPPER}} .vp-cubewp-map-address-wrap .vp-map-address-icon svg' => 'fill: {{VALUE}};',
			),
		));
		$this->add_responsive_control('address_icon_spacing', array(
			'label'      => esc_html__('Icon Spacing', 'valuepack-addons'),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array('px', 'em'),
			'range'      => array('px' => array('min' => 0, 'max' => 24), 'em' => array('min' => 0, 'max' => 1.5)),
			'default'    => array('size' => 6, 'unit' => 'px'),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-address-wrap .vp-map-address-icon-before_label' => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .vp-cubewp-map-address-wrap  .vp-map-address-icon-after_label' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .vp-cubewp-map-address-wrap .vp-map-address-icon-before_text' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .vp-cubewp-map-address-wrap .vp-map-address-icon-after_text' => 'margin-left: {{SIZE}}{{UNIT}};',
			),
		));

		// hover state 
		$this->add_control(
			'address_hover_state',
			[
				'label' => __('Hover State', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control('address_text_color_hover', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Text Hover Color', 'valuepack-addons'),
			'default'   => '#000000',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-address-wrap:hover .vp-cubewp-map-address-text' => 'color: {{VALUE}};',
			),
		));

		$this->add_control('address_icon_color_hover', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Icon Hover Color', 'valuepack-addons'),
			'default'   => '#000000',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-address-wrap:hover .vp-map-address-icon' => 'color: {{VALUE}};',
				'{{WRAPPER}} .vp-cubewp-map-address-wrap:hover .vp-map-address-icon svg' => 'fill: {{VALUE}};',
			),
		));
		$this->end_controls_section();

		// Get Directions button style
		$this->start_controls_section('map_get_directions_style_section', array(
			'label'     => esc_html__('Get Directions Button', 'valuepack-addons'),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'enable_get_directions' => 'yes',
			),
		));


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'get_directions_typography',
				'label'    => esc_html__('Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-map-get-directions',
				'fields_options' => array(
					'typography'  => array('default' => 'yes'),
					'font_size'   => array('default' => array('size' => 14, 'unit' => 'px')),
					'font_weight' => array('default' => '500'),
					'line_height' => array('default' => array('size' => 1.4, 'unit' => 'em')),
				),
			)
		);

		$this->add_responsive_control('get_directions_padding', array(
			'type'       => Controls_Manager::DIMENSIONS,
			'label'      => esc_html__('Padding', 'valuepack-addons'),
			'size_units' => array('px', 'em', 'rem'),
			'default'    => array(
				'top' => '10',
				'right' => '20',
				'bottom' => '10',
				'left' => '20',
				'unit' => 'px',
				'isLinked' => false,
			),
			'selectors'  => array(
				'{{WRAPPER}}  .vp-cubewp-map-get-directions' => 'max-width: max-content;align-items:center; display: flex; text-decoration: none;padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		));
		$this->add_responsive_control('get_directions_margin', array(
			'type'       => Controls_Manager::DIMENSIONS,
			'label'      => esc_html__('Margin', 'valuepack-addons'),
			'size_units' => array('px', 'em', 'rem'),
			'default'    => array(
				'top' => '15',
				'right' => '0',
				'bottom' => '0',
				'left' => '0',
				'unit' => 'px',
				'isLinked' => false,
			),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		));
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'get_directions_border',
				'label'    => esc_html__('Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-map-get-directions',
				'fields_options' => array(
					'color' => array('default' => '#2271b1'),
					'width' => array(
						'default' => array(
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'unit' => 'px',
							'isLinked' => true,
						),
					),
				),
			)
		);
		$this->add_control('get_directions_border_radius', array(
			'label'      => esc_html__('Border Radius', 'valuepack-addons'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array('px', '%', 'em'),
			'default'    => array(
				'top' => '6',
				'right' => '6',
				'bottom' => '6',
				'left' => '6',
				'unit' => 'px',
				'isLinked' => true,
			),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		));
		$this->add_responsive_control('get_directions_icon_size', array(
			'label'      => esc_html__('Icon Size', 'valuepack-addons'),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array('px', 'em'),
			'range'      => array('px' => array('min' => 8, 'max' => 48), 'em' => array('min' => 0.5, 'max' => 2)),
			'default'    => array('size' => 18, 'unit' => 'px'),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions .vp-map-directions-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .vp-cubewp-map-get-directions .vp-map-directions-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			),
		));
		$this->add_responsive_control('get_directions_icon_spacing', array(
			'label'      => esc_html__('Icon Spacing', 'valuepack-addons'),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array('px', 'em'),
			'range'      => array('px' => array('min' => 0, 'max' => 24), 'em' => array('min' => 0, 'max' => 1.5)),
			'default'    => array('size' => 6, 'unit' => 'px'),
			'selectors'  => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions .vp-map-directions-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .vp-cubewp-map-get-directions .vp-map-directions-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
			),
		));
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'get_directions_box_shadow',
				'label'    => esc_html__('Box Shadow', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-map-get-directions',
				'fields_options' => array(
					'box_shadow' => array(
						'default' => array(
							'horizontal' => 0,
							'vertical' => 1,
							'blur' => 2,
							'spread' => 0,
							'color' => 'rgba(34,113,177,0.2)',
						),
					),
				),
			)
		);
		$this->start_controls_tabs('get_directions_tabs');
		$this->start_controls_tab('get_directions_normal', array(
			'label' => esc_html__('Normal', 'valuepack-addons'),
		));
		$this->add_control('get_directions_color_normal', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Text Color', 'valuepack-addons'),
			'default'   => '#ffffff',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions' => 'color: {{VALUE}};',
			),
		));
		$this->add_control('get_directions_bg_normal', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Background', 'valuepack-addons'),
			'default'   => '#2271b1',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions' => 'background-color: {{VALUE}};',
			),
		));
		$this->add_control('get_directions_color_icon_normal', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Icon Color', 'valuepack-addons'),
			'default'   => '#ffffff',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions svg' => 'fill: {{VALUE}};',
				'{{WRAPPER}} .vp-cubewp-map-get-directions i' => 'color: {{VALUE}};',
			),
		));
		$this->end_controls_tab();
		$this->start_controls_tab('get_directions_hover', array(
			'label' => esc_html__('Hover', 'valuepack-addons'),
		));
		$this->add_control('get_directions_color_hover', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Text Color', 'valuepack-addons'),
			'default'   => '#ffffff',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions:hover' => 'color: {{VALUE}};',
			),
		));
		$this->add_control('get_directions_bg_hover', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Background', 'valuepack-addons'),
			'default'   => '#135e96',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions:hover' => 'background-color: {{VALUE}};',
			),
		));
		$this->add_control('get_directions_border_color_hover', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Border Color', 'valuepack-addons'),
			'default'   => '#135e96',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions:hover' => 'border-color: {{VALUE}};',
			),
		));
		$this->add_control('get_directions_color_icon_hover', array(
			'type'      => Controls_Manager::COLOR,
			'label'     => esc_html__('Icon Color (Hover)', 'valuepack-addons'),
			'default'   => '#ffffff',
			'selectors' => array(
				'{{WRAPPER}} .vp-cubewp-map-get-directions:hover svg' => 'fill: {{VALUE}};',
				'{{WRAPPER}} .vp-cubewp-map-get-directions:hover i' => 'color: {{VALUE}};',
			),
		));
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		// Map Pin Wrapper (.leaflet-marker-icon .cwp-pin)
		$this->start_controls_section(
			'map_pin_wrapper_style',
			[
				'label' => __('Pin Wrapper', 'cubewp-framework'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'map_pin_wrapper_bg_color',
			[
				'label' => __('Background Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'map_pin_wrapper_transition',
			[
				'label' => __('Transition', 'cubewp-framework'),
				'type' => Controls_Manager::TEXT,
				'default' => 'all 0.3s',
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin' => 'transition: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'map_pin_wrapper_width',
			[
				'label' => __('Width', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => ['px' => ['min' => 10, 'max' => 200], '%' => ['min' => 1, 'max' => 100], 'em' => ['min' => 1, 'max' => 10]],
				'default' => ['size' => 40, 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'map_pin_wrapper_height',
			[
				'label' => __('Height', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => ['px' => ['min' => 10, 'max' => 200], '%' => ['min' => 1, 'max' => 100], 'em' => ['min' => 1, 'max' => 10]],
				'default' => ['size' => 40, 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'map_pin_wrapper_padding',
			[
				'label' => __('Padding', 'cubewp-framework'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'map_pin_wrapper_border',
				'selector' => '{{WRAPPER}} .leaflet-marker-icon .cwp-pin',
			]
		);

		$this->add_responsive_control(
			'map_pin_wrapper_radius',
			[
				'label' => __('Border Radius', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default' => ['top' => '50', 'right' => '50', 'bottom' => '50', 'left' => '50', 'unit' => '%', 'isLinked' => true],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'map_pin_wrapper_shadow',
				'selector' => '{{WRAPPER}} .leaflet-marker-icon .cwp-pin',
				'fields_options' => [
					'box_shadow' => [
						'default' => [
							'horizontal' => 0,
							'vertical' => 2,
							'blur' => 6,
							'spread' => 0,
							'color' => 'rgba(0,0,0,0.15)',
						],
					],
				],
			]
		);

		$this->add_control(
			'map_pin_wrapper_transform_rotate',
			[
				'label' => __('Rotate (deg)', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => ['deg' => ['min' => 0, 'max' => 360]],
				'default' => ['size' => 0, 'unit' => 'deg'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);

		// Hover options for .cwp-pin
		$this->start_controls_tabs('map_pin_wrapper_style_tabs');
		$this->start_controls_tab(
			'map_pin_wrapper_normal',
			[
				'label' => __('Normal', 'cubewp-framework'),
			]
		);
		// Nothing - fields above apply to normal state
		$this->end_controls_tab();

		$this->start_controls_tab(
			'map_pin_wrapper_hover',
			[
				'label' => __('Hover', 'cubewp-framework'),
			]
		);
		$this->add_control(
			'map_pin_wrapper_hover_bg_color',
			[
				'label' => __('Background Color (Hover)', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f3f4f6',
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin:hover' => 'background-color: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'map_pin_wrapper_hover_transition',
			[
				'label' => __('Transition (Hover)', 'cubewp-framework'),
				'type' => Controls_Manager::TEXT,
				'default' => 'all 0.3s',
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin:hover' => 'transition: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();


		// Icon Wrapper (.leaflet-marker-icon .cwp-pin .vp-map-pin-icon)
		$this->start_controls_section(
			'map_pin_icon_wrapper_style',
			[
				'label' => __('Icon Wrapper', 'cubewp-framework'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'map_pin_icon_wrapper_width',
			[
				'label' => __('Width', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => ['px' => ['min' => 10, 'max' => 200], '%' => ['min' => 1, 'max' => 100], 'em' => ['min' => 1, 'max' => 10]],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon' => 'width: {{SIZE}}{{UNIT}};display: inline-block;',
				]
			]
		);
		$this->add_responsive_control(
			'map_pin_icon_wrapper_height',
			[
				'label' => __('Height', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'range' => ['px' => ['min' => 10, 'max' => 200], '%' => ['min' => 1, 'max' => 100], 'em' => ['min' => 1, 'max' => 10]],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'map_pin_icon_wrapper_padding',
			[
				'label' => __('Padding', 'cubewp-framework'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'map_pin_icon_wrapper_border',
				'selector' => '{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon',
			]
		);
		$this->add_responsive_control(
			'map_pin_icon_wrapper_radius',
			[
				'label' => __('Border Radius', 'cubewp-framework'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'map_pin_icon_wrapper_shadow',
				'selector' => '{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon',
			]
		);
		$this->add_control(
			'map_pin_icon_wrapper_bg_color',
			[
				'label' => __('Background Color', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon' => 'background-color: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'map_pin_icon_wrapper_transform_rotate',
			[
				'label' => __('Rotate (deg)', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => ['deg' => ['min' => 0, 'max' => 360]],
				'default' => ['size' => 0, 'unit' => 'deg'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);
		$this->end_controls_section();

		// Icon SVG (.leaflet-marker-icon .cwp-pin .vp-map-pin-icon svg)
		$this->start_controls_section(
			'map_pin_icon_svg_style',
			[
				'label' => __('Pin Icon SVG', 'cubewp-framework'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'map_pin_icon_svg_color',
			[
				'label' => __('SVG Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#2271b1',
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'map_pin_icon_svg_size',
			[
				'label' => __('SVG Size', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', '%'],
				'range' => [
					'px' => ['min' => 8, 'max' => 200],
					'em' => ['min' => 0.5, 'max' => 10],
					'rem' => ['min' => 0.5, 'max' => 10],
					'%' => ['min' => 10, 'max' => 100]
				],
				'default' => ['size' => 24, 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'map_pin_icon_svg_rotate',
			[
				'label' => __('SVG Rotate (deg)', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'range' => [
					'deg' => ['min' => 0, 'max' => 360],
				],
				'default' => ['size' => 0, 'unit' => 'deg'],
				'selectors' => [
					'{{WRAPPER}} .leaflet-marker-icon .cwp-pin .vp-map-pin-icon svg' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);

		$this->end_controls_section();
	}

	private static function get_post_types()
	{
		$post_types = get_post_types(['public' => true], 'objects');
		$options = [];
		foreach ($post_types as $post_type) {
			$options[$post_type->name] = $post_type->label;
		}
		unset($options['elementor_library']);
		unset($options['e-landing-page']);
		unset($options['attachment']);
		unset($options['page']);

		self::$post_types = $options;
	}

	private static function value_pack_get_cubewp_map_fields($post_type)
	{
		$fields = array('' => esc_html__('Select Map Field', 'valuepack-addons'));
		if (function_exists('get_fields_by_post_type')) {
			$post_type_fields = get_fields_by_post_type($post_type);
			foreach ($post_type_fields as $field_key => $field_label) {
				$field_options = get_field_options($field_key);
				if (isset($field_options['type']) && ($field_options['type'] === 'google_address' || $field_options['type'] === 'map')) {
					$fields[$field_key] = $field_label;
				}
			}
		}
		return $fields;
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		// Get map data based on settings
		$map_data = $this->get_map_data($settings);

		if (empty($map_data)) {
			return;
		}

		// Enqueue CubeWP map scripts and styles
		// Use both CubeWP's enqueue system and wp_enqueue as fallback
		if (function_exists('CubeWp_Enqueue')) {
			CubeWp_Enqueue::enqueue_style('cwp-map-cluster');
			CubeWp_Enqueue::enqueue_style('cwp-leaflet-css');
			CubeWp_Enqueue::enqueue_script('cubewp-leaflet');
			CubeWp_Enqueue::enqueue_script('cubewp-leaflet-cluster');
			CubeWp_Enqueue::enqueue_script('cubewp-leaflet-fullscreen');
			CubeWp_Enqueue::enqueue_script('cubewp-map');
		}

		// Also try direct wp_enqueue as fallback (in case CubeWP's system doesn't work)
		if (wp_script_is('cubewp-leaflet', 'registered')) {
			wp_enqueue_script('cubewp-leaflet');
		}
		if (wp_script_is('cubewp-leaflet-cluster', 'registered')) {
			wp_enqueue_script('cubewp-leaflet-cluster');
		}
		if (wp_script_is('cubewp-leaflet-fullscreen', 'registered')) {
			wp_enqueue_script('cubewp-leaflet-fullscreen');
		}
		if (wp_script_is('cubewp-map', 'registered')) {
			wp_enqueue_script('cubewp-map');
		}
		if (wp_style_is('cwp-leaflet-css', 'registered')) {
			wp_enqueue_style('cwp-leaflet-css');
		}
		if (wp_style_is('cwp-map-cluster', 'registered')) {
			wp_enqueue_style('cwp-map-cluster');
		}

		// Render map
		$this->render_map($map_data, $settings);
	}

	/**
	 * Get map data based on settings
	 */
	private function get_map_data($settings)
	{
		$map_data = array();
		$post_type = isset($settings['post_type']) ? $settings['post_type'] : '';
		$map_field = isset($settings['map_field_' . $post_type]) ? $settings['map_field_' . $post_type] : '';
		$map_source = isset($settings['map_source']) ? $settings['map_source'] : 'current_post';

		if (empty($post_type) || empty($map_field)) {
			return $map_data;
		}

		$post_ids = array();

		switch ($map_source) {
			case 'current_post':
				$post_ids = array(get_the_ID());
				if (cubewp_is_elementor_editing()) {
					$post_ids = array(cubewp_get_elementor_preview_post_id());
				} 
				break;
			case 'specific_post':
				$post_id = isset($settings['specific_post_id']) ? intval($settings['specific_post_id']) : 0;
				if ($post_id > 0) {
					$post_ids = array($post_id);
				}
				break;
			case 'multiple_posts':
				$ids_string = isset($settings['multiple_posts_ids']) ? $settings['multiple_posts_ids'] : '';
				if (!empty($ids_string)) {
					$post_ids = array_filter(array_map('intval', explode(',', $ids_string)));
				}
				break;
		}

		if (empty($post_ids)) {
			return $map_data;
		}

		// Get map data from posts
		foreach ($post_ids as $post_id) {
			$map_value = get_post_meta($post_id, $map_field, true);

			// CubeWP stores google_address as array with address, lat, lng
			if (is_array($map_value) && isset($map_value['address'])) {
				$address = $map_value['address'];
				$lat = isset($map_value['lat']) ? $map_value['lat'] : '';
				$lng = isset($map_value['lng']) ? $map_value['lng'] : '';
			} else {
				// Fallback to separate meta fields
				$address = $map_value;
				$lat = get_post_meta($post_id, $map_field . '_lat', true);
				$lng = get_post_meta($post_id, $map_field . '_lng', true);
			}

			// Only add if we have address or coordinates
			if (!empty($address) || (!empty($lat) && !empty($lng))) {
				$map_data[] = array(
					'post_id' => $post_id,
					'address' => $address,
					'lat' => $lat,
					'lng' => $lng,
					'title'   => get_the_title($post_id),
					'link'    => get_permalink($post_id),
				);
			}
		}

		return $map_data;
	}

	/**
	 * Render map
	 */
	private function render_map($map_data, $settings)
	{
		$widget_id = $this->get_id();
		$map_height = isset($settings['map_height']['size']) ? $settings['map_height']['size'] : 400;
		$map_height_unit = isset($settings['map_height']['unit']) ? $settings['map_height']['unit'] : 'px';
		$map_zoom = isset($settings['map_zoom']['size']) ? absint($settings['map_zoom']['size']) : 12;
		if ($map_zoom < 1 || $map_zoom > 20) {
			$map_zoom = 12;
		}

		// Prepare map pin HTML (like CubeWP does)
		$map_pin_html = '';
		if (isset($settings['custom_pin_icon']) && !empty($settings['custom_pin_icon']['value'])) {
			ob_start();
?>
			<span class="vp-map-pin-icon">
				<?php
				if (class_exists('Elementor\Icons_Manager')) {
					\Elementor\Icons_Manager::render_icon($settings['custom_pin_icon'], ['aria-hidden' => 'true']);
				}
				?>
			</span>
		<?php
			$map_pin_html = trim(ob_get_clean());
		}

		// Format map data for CWP_Cluster_Map() function
		// Expected format: [[lat, lng, title, url, thumbnail, pinIconUrl], ...]
		$map_coordinates = array();
		foreach ($map_data as $data) {
			if (empty($data['lat']) || empty($data['lng'])) {
				continue;
			}

			// Get post thumbnail
			$thumbnail = '';
			if (!empty($data['post_id'])) {
				$thumbnail_id = get_post_thumbnail_id($data['post_id']);
				if ($thumbnail_id) {
					$thumbnail = wp_get_attachment_image_url($thumbnail_id, 'thumbnail');
				}
			}

			// If no thumbnail, use a placeholder or empty
			if (empty($thumbnail)) {
				$thumbnail = '';
			}

			$map_coordinates[] = array(
				floatval($data['lat']),
				floatval($data['lng']),
				$data['title'],
				$data['link'],
				$thumbnail,
				'' // pinIconUrl - not used when using HTML pin
			);
		}

		// Unique per post when in a loop (e.g. CubeWP posts) so each card gets its own map
		$current_post_id = get_the_ID();
		$unique_map_id   = 'vp-map-' . $widget_id . ( $current_post_id ? ( '-' . $current_post_id ) : '' );
		if ( ! $current_post_id ) {
			$unique_map_id .= '-' . uniqid( 'vp', true );
		}
		$show_address  = ! empty($settings['show_address_after_map']) && $settings['show_address_after_map'] === 'yes';
		$show_directions = ! empty($settings['enable_get_directions']) && $settings['enable_get_directions'] === 'yes';
		$address_label  = isset($settings['address_label']) ? $settings['address_label'] : esc_html__('Address:', 'valuepack-addons');
		$address_icon   = isset($settings['address_icon']) ? $settings['address_icon'] : array();
		$address_icon_pos = isset($settings['address_icon_position']) ? $settings['address_icon_position'] : 'before_label';
		$directions_text = isset($settings['get_directions_text']) ? $settings['get_directions_text'] : esc_html__('Get Directions', 'valuepack-addons');
		$directions_new_tab = ! empty($settings['get_directions_new_tab']) && $settings['get_directions_new_tab'] === 'yes';
		$directions_icon = isset($settings['get_directions_icon']) ? $settings['get_directions_icon'] : array();
		$directions_icon_pos = isset($settings['get_directions_icon_position']) ? $settings['get_directions_icon_position'] : 'before';

		// Build Get Directions URL from first location (address or lat,lng)
		$directions_url = '';
		if ($show_directions && ! empty($map_data)) {
			$first = $map_data[0];
			if (!empty($first['lat']) && ! empty($first['lng'])) {
				$directions_url = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($first['lat'] . ',' . $first['lng']);
			}
		}
		?>
		<div class="vp-cubewp-map-wrapper">
			<div class="vp-cubewp-map-container vp-cubewp-map-<?php echo esc_attr($widget_id); ?>"
				data-map-id="<?php echo esc_attr($unique_map_id); ?>"
				data-map-locations="<?php echo esc_attr(wp_json_encode($map_coordinates)); ?>"
				data-map-zoom="<?php echo esc_attr($map_zoom); ?>"
				<?php if (! empty($map_pin_html)) {
					echo ' data-mappin-html="' . esc_attr($map_pin_html) . '"';
				} ?>
				style="height: <?php echo esc_attr($map_height . $map_height_unit); ?>; width: 100%;"></div>

				<?php if ( ($show_address && !empty($map_data)) || ($show_directions && $directions_url !== '') ) : ?>
                <div class="vp-cubewp-map-get-directions-wrap">
                    <?php if ($show_address && ! empty($map_data)) : ?>
                        <div class="vp-cubewp-map-address-wrap">
                            <?php
                            $address_icon_html = '';
                            if (! empty($address_icon['value']) && class_exists('Elementor\Icons_Manager')) {
                                ob_start();
                                echo '<span class="vp-map-address-icon vp-map-address-icon-' . esc_attr($address_icon_pos) . '">';
                                \Elementor\Icons_Manager::render_icon($address_icon, array('aria-hidden' => 'true'));
                                echo '</span>';
                                $address_icon_html = ob_get_clean();
                            }
                            foreach ($map_data as $item) : ?>
                                <?php if (! empty($item['address'])) : ?>
                                    <div class="vp-cubewp-map-address-item">
                                        <?php if ($address_icon_pos === 'before_label' && $address_icon_html) echo $address_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        ?>
                                        <?php if ($address_label !== '') : ?>
                                            <span class="vp-cubewp-map-address-label"><?php echo esc_html($address_label); ?></span>
                                        <?php endif; ?>
                                        <?php if ($address_icon_pos === 'after_label' && $address_icon_html) echo $address_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        ?>
                                        <?php if ($address_icon_pos === 'before_text' && $address_icon_html) echo $address_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        ?>
                                        <span class="vp-cubewp-map-address-text"><?php echo esc_html($item['address']); ?></span>
                                        <?php if ($address_icon_pos === 'after_text' && $address_icon_html) echo $address_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($show_directions && $directions_url !== '') : ?>
                        <div class="vp-cubewp-map-directions-wrap">
                            <a href="<?php echo esc_url($directions_url); ?>" class="vp-cubewp-map-get-directions" <?php echo $directions_new_tab ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                <?php if (! empty($directions_icon['value']) && $directions_icon_pos === 'before' && class_exists('Elementor\Icons_Manager')) : ?>
                                    <span class="vp-map-directions-icon vp-map-directions-icon-before"><?php \Elementor\Icons_Manager::render_icon($directions_icon, array('aria-hidden' => 'true')); ?></span>
                                <?php endif; ?>
                                <span class="vp-map-directions-text"><?php echo esc_html($directions_text); ?></span>
                                <?php if (! empty($directions_icon['value']) && $directions_icon_pos === 'after' && class_exists('Elementor\Icons_Manager')) : ?>
                                    <span class="vp-map-directions-icon vp-map-directions-icon-after"><?php \Elementor\Icons_Manager::render_icon($directions_icon, array('aria-hidden' => 'true')); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

		</div>
<?php
	}
}
