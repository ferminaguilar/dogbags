<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

/**
 * CubeWP Gallery Widgets.
 *
 * Elementor Widget For Gallery By CubeWP.
 *
 * @since 1.0.0
 */
class Value_Pack_CubeWp_Gallery extends Widget_Base
{

	private static $post_types = array();

	public function get_name()
	{
		return 'vp_cubewp_gallery';
	}

	public function get_title()
	{
		return esc_html__('CubeWP Gallery', 'valuepack-addons');
	}

	public function get_icon()
	{
		return 'eicon-gallery-grid vpack-icon';
	}

	public function get_categories()
	{
		return array('value_pack');
	}

	public function get_keywords()
	{
		return array(
			'cubewp',
			'gallery',
			'images',
			'photo',
			'media',
			'lightbox',
			'slider',
			'grid'
		);
	}

	public function get_script_depends()
	{
		$scripts = array();
		if (class_exists('CubeWp_Enqueue')) {
			$scripts[] = 'cubewp-pretty-photo';
		}
		return $scripts;
	}

	public function get_style_depends()
	{
		$styles = array();
		if (class_exists('CubeWp_Enqueue')) {
			$styles[] = 'cubewp-pretty-photo';
		}
		return $styles;
	}

	protected function register_controls()
	{
		self::get_post_types();

		$this->start_controls_section('cubewp_gallery_section', array(
			'label' => esc_html__('Gallery Options', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		// Post Type Selection
		$this->add_control('post_type', array(
			'type'        => Controls_Manager::SELECT,
			'label'       => esc_html__('Select Post Type', 'valuepack-addons'),
			'description' => esc_html__('Select the post type to get gallery fields from.', 'valuepack-addons'),
			'options'     => self::$post_types,
			'default'     => 'post',
			'label_block' => true,
		));

		foreach (self::$post_types as $post_type => $post_type_label) {
			$gallery_fields = self::value_pack_get_cubewp_gallery_fields($post_type);
			$this->add_control('gallery_field_' . $post_type, array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__('Select Gallery Field', 'valuepack-addons'),
				'description' => esc_html__('Select the gallery field to display. Fields are filtered based on selected post type.', 'valuepack-addons'),
				'options'     => $gallery_fields,
				'label_block' => true,
				'condition'   => array(
					'post_type' => $post_type,
				),
			));
		}

		// Source Selection
		$this->add_control('gallery_source', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Gallery Source', 'valuepack-addons'),
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
			'description' => esc_html__('Enter the post ID to get gallery from.', 'valuepack-addons'),
			'condition'  => array(
				'gallery_source' => 'specific_post',
			),
		));

		// Multiple Posts IDs
		$this->add_control('multiple_posts_ids', array(
			'type'        => Controls_Manager::TEXTAREA,
			'label'       => esc_html__('Post IDs', 'valuepack-addons'),
			'description' => esc_html__('Enter post IDs separated by commas (e.g., 12, 45, 78).', 'valuepack-addons'),
			'condition'  => array(
				'gallery_source' => 'multiple_posts',
			),
		));

		$this->end_controls_section();

		// Image Settings Section
		$this->start_controls_section('image_settings_section', array(
			'label' => esc_html__('Image Settings', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		// Image Size
		$image_sizes = get_intermediate_image_sizes();
		$image_size_options = array('full' => esc_html__('Full Size', 'valuepack-addons'));
		foreach ($image_sizes as $size) {
			$image_size_options[$size] = ucfirst(str_replace(array('-', '_'), ' ', $size));
		}

		$this->add_control('image_size', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Image Size', 'valuepack-addons'),
			'options' => $image_size_options,
			'default' => 'medium',
		));

		// Gap Between Images
		$this->add_responsive_control('image_gap', array(
			'type'        => Controls_Manager::SLIDER,
			'label'       => esc_html__('Gap Between Images', 'valuepack-addons'),
			'size_units'  => array('px', '%', 'em'),
			'range'       => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1,
				),
			),
			'default'     => array(
				'unit' => 'px',
				'size' => 10,
			),
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'gap: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-slide' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2);',
			),
		));

		// Enable Lightbox
		$this->add_control('enable_lightbox', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Lightbox', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => 'yes',
		));

		$this->add_control('gallery_link_type', array(
			'type'        => Controls_Manager::SELECT,
			'label'       => esc_html__('Gallery Link Type', 'valuepack-addons'),
			'default'     => 'gallery',
			'options'     => array(
				'gallery'  => esc_html__('Gallery Image (Lightbox)', 'valuepack-addons'),
				'post_url' => esc_html__('Post URL', 'valuepack-addons'),
			),
			'description' => esc_html__('Choose what the gallery image link opens.', 'valuepack-addons'),
		));

		// Number of images to show (0 = show all)
		$this->add_control('gallery_images_to_show', array(
			'type'        => Controls_Manager::NUMBER,
			'label'       => esc_html__('Number of Images to Show', 'valuepack-addons'),
			'description' => esc_html__('Leave empty or 0 to show all gallery images.', 'valuepack-addons'),
			'min'         => 0,
			'max'         => 500,
			'step'        => 1,
			'default'     => '',
		));

		$this->end_controls_section();

		// See All Button Section
		$this->start_controls_section('see_all_button_section', array(
			'label' => esc_html__('See All Button', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		$this->add_control('enable_see_all_button', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable See All Button', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => '',
			'description'  => esc_html__('Shows below gallery. Opens the gallery lightbox when clicked.', 'valuepack-addons'),
		));

		$this->add_control('see_all_button_icon', array(
			'label'       => esc_html__('Icon', 'valuepack-addons'),
			'type'        => Controls_Manager::ICONS,
			'default'     => array(
				'value'   => 'fas fa-camera',
				'library' => 'fa-solid',
			),
			'condition'   => array(
				'enable_see_all_button' => 'yes',
			),
		));

		$this->add_control('see_all_button_text', array(
			'type'        => Controls_Manager::TEXT,
			'label'       => esc_html__('Button Text', 'valuepack-addons'),
			'default'     => esc_html__('see all', 'valuepack-addons'),
			'placeholder' => esc_html__('see all', 'valuepack-addons'),
			'condition'   => array(
				'enable_see_all_button' => 'yes',
			),
		));

		$this->end_controls_section();

		// Layout Section
		$this->start_controls_section('layout_section', array(
			'label' => esc_html__('Layout', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		// Display Type
		$this->add_control('display_type', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Layout Type', 'valuepack-addons'),
			'options' => array(
				'grid' => esc_html__('Grid', 'valuepack-addons'),
				'flex' => esc_html__('Flex', 'valuepack-addons'),
			),
			'default' => 'grid',
		));

		// Enable Slider
		$this->add_control('vp_gallery_enable_slider', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Slider', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => '',
			'description'  => esc_html__('Enable slider functionality. Works with both Grid and Flex layouts.', 'valuepack-addons'),
		));

		$this->end_controls_section();

		// Grid & Flex Controls (Combined Section)
		$this->add_layout_controls();

		// Advanced Grid Settings
		$this->add_advanced_grid_controls();

		// Slider Controls
		$this->add_slider_controls();

		// See All Button Style
		$this->add_see_all_button_style_controls();
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

	private static function value_pack_get_cubewp_gallery_fields($post_type)
	{
		$fields = array('' => esc_html__('Select Gallery Field', 'valuepack-addons'));
		if (function_exists('get_fields_by_post_type')) {
			$post_type_fields = get_fields_by_post_type($post_type);
			foreach ($post_type_fields as $field_key => $field_label) {
				$field_options = get_field_options($field_key);
				if (isset($field_options['type']) && $field_options['type'] === 'gallery') {
					$fields[$field_key] = $field_label;
				}
			}
		}
		return $fields;
	}

	/**
	 * Add Layout Controls (Grid & Flex Combined)
	 */
	private function add_layout_controls()
	{
		$this->start_controls_section(
			'layout_settings_section',
			[
				'label' => esc_html__('Layout Settings', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		// Grid Controls
		$this->add_control(
			'grid_settings_heading',
			[
				'label' => esc_html__('Grid Settings', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'display_type' => 'grid',
				],
			]
		);

		$this->add_responsive_control('columns_per_row', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Columns Per Row', 'valuepack-addons'),
			'options' => array(
				'1' => esc_html__('1', 'valuepack-addons'),
				'2' => esc_html__('2', 'valuepack-addons'),
				'3' => esc_html__('3', 'valuepack-addons'),
				'4' => esc_html__('4', 'valuepack-addons'),
				'5' => esc_html__('5', 'valuepack-addons'),
				'6' => esc_html__('6', 'valuepack-addons'),
				'7' => esc_html__('7', 'valuepack-addons'),
				'8' => esc_html__('8', 'valuepack-addons'),
				'9' => esc_html__('9', 'valuepack-addons'),
				'10' => esc_html__('10', 'valuepack-addons'),
				'11' => esc_html__('11', 'valuepack-addons'),
				'12' => esc_html__('12', 'valuepack-addons'),
			),
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr);'
			),
			'default' => '3',
			'condition' => [
				'display_type' => 'grid',
				'enable_advanced_grid!' => 'yes',
			],
		));

		// Grid Template Rows (for advanced grid)
		$this->add_responsive_control('grid_template_rows', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Grid Template Rows', 'valuepack-addons'),
			'description' => esc_html__('Number of rows in the grid. Leave empty for auto.', 'valuepack-addons'),
			'min'     => 1,
			'max'     => 20,
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'grid-template-rows: repeat({{VALUE}}, 1fr);'
			),
			'condition' => [
				'display_type' => 'grid',
				'enable_advanced_grid' => 'yes',
			],
		));

		// Grid Auto Flow
		$this->add_control('grid_auto_flow', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Grid Auto Flow', 'valuepack-addons'),
			'options' => array(
				'row' => esc_html__('Row', 'valuepack-addons'),
				'column' => esc_html__('Column', 'valuepack-addons'),
				'dense' => esc_html__('Dense', 'valuepack-addons'),
				'row dense' => esc_html__('Row Dense', 'valuepack-addons'),
				'column dense' => esc_html__('Column Dense', 'valuepack-addons'),
			),
			'default' => 'row',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'grid-auto-flow: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'grid',
			],
		));

		// Grid Auto Rows
		$this->add_responsive_control('grid_auto_rows', array(
			'type'    => Controls_Manager::SLIDER,
			'label'   => esc_html__('Grid Auto Rows Size', 'valuepack-addons'),
			'size_units' => ['px', '%', 'em', 'rem', 'vh'],
			'range' => array(
				'px' => array(
					'min' => 0,
					'max' => 500,
					'step' => 1,
				),
			),
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'grid-auto-rows: {{SIZE}}{{UNIT}};'
			),
			'condition' => [
				'display_type' => 'grid',
			],
		));

		// Grid Auto Columns
		$this->add_responsive_control('grid_auto_columns', array(
			'type'    => Controls_Manager::SLIDER,
			'label'   => esc_html__('Grid Auto Columns Size', 'valuepack-addons'),
			'size_units' => ['px', '%', 'em', 'rem', 'vw'],
			'range' => array(
				'px' => array(
					'min' => 0,
					'max' => 500,
					'step' => 1,
				),
			),
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'grid-auto-columns: {{SIZE}}{{UNIT}};'
			),
			'condition' => [
				'display_type' => 'grid',
			],
		));

		$this->add_responsive_control(
			'grid_gap',
			[
				'label' => esc_html__('Grid Gap', 'valuepack-addons'),
				'type' => Controls_Manager::GAPS,
				'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
				'default' => [
					'row' => 10,
					'column' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}};',
				],
				'condition' => [
					'display_type' => 'grid',
				],
			]
		);

		// Grid Justify Items
		$this->add_control('grid_justify_items', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Justify Items', 'valuepack-addons'),
			'options' => array(
				'start' => esc_html__('Start', 'valuepack-addons'),
				'end' => esc_html__('End', 'valuepack-addons'),
				'center' => esc_html__('Center', 'valuepack-addons'),
				'stretch' => esc_html__('Stretch', 'valuepack-addons'),
			),
			'default' => 'stretch',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'justify-items: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'grid',
			],
		));

		// Grid Align Items
		$this->add_control('grid_align_items', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Align Items', 'valuepack-addons'),
			'options' => array(
				'start' => esc_html__('Start', 'valuepack-addons'),
				'end' => esc_html__('End', 'valuepack-addons'),
				'center' => esc_html__('Center', 'valuepack-addons'),
				'stretch' => esc_html__('Stretch', 'valuepack-addons'),
			),
			'default' => 'stretch',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-grid' => 'align-items: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'grid',
			],
		));

		// Flex Controls Heading
		$this->add_control(
			'flex_settings_heading',
			[
				'label' => esc_html__('Flex Settings', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_type' => 'flex',
				],
			]
		);

		// Flex Direction
		$this->add_responsive_control('flex_direction', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Flex Direction', 'valuepack-addons'),
			'options' => array(
				'row' => esc_html__('Row', 'valuepack-addons'),
				'column' => esc_html__('Column', 'valuepack-addons'),
				'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
				'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
			),
			'default' => 'row',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-flex' => 'display: flex; flex-direction: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'flex',
			],
		));

		// Flex Wrap
		$this->add_responsive_control('flex_wrap', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Flex Wrap', 'valuepack-addons'),
			'options' => array(
				'nowrap' => esc_html__('No Wrap', 'valuepack-addons'),
				'wrap' => esc_html__('Wrap', 'valuepack-addons'),
				'wrap-reverse' => esc_html__('Wrap Reverse', 'valuepack-addons'),
			),
			'default' => 'wrap',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-flex' => 'flex-wrap: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'flex',
			],
		));

		// Justify Content
		$this->add_responsive_control('flex_justify_content', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Justify Content', 'valuepack-addons'),
			'options' => array(
				'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
				'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
				'center' => esc_html__('Center', 'valuepack-addons'),
				'space-between' => esc_html__('Space Between', 'valuepack-addons'),
				'space-around' => esc_html__('Space Around', 'valuepack-addons'),
				'space-evenly' => esc_html__('Space Evenly', 'valuepack-addons'),
			),
			'default' => 'flex-start',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-flex' => 'justify-content: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'flex',
			],
		));

		// Align Items
		$this->add_responsive_control('flex_align_items', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Align Items', 'valuepack-addons'),
			'options' => array(
				'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
				'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
				'center' => esc_html__('Center', 'valuepack-addons'),
				'baseline' => esc_html__('Baseline', 'valuepack-addons'),
				'stretch' => esc_html__('Stretch', 'valuepack-addons'),
			),
			'default' => 'stretch',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-flex' => 'align-items: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'flex',
			],
		));

		// Align Content
		$this->add_responsive_control('flex_align_content', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Align Content', 'valuepack-addons'),
			'options' => array(
				'flex-start' => esc_html__('Flex Start', 'valuepack-addons'),
				'flex-end' => esc_html__('Flex End', 'valuepack-addons'),
				'center' => esc_html__('Center', 'valuepack-addons'),
				'space-between' => esc_html__('Space Between', 'valuepack-addons'),
				'space-around' => esc_html__('Space Around', 'valuepack-addons'),
				'stretch' => esc_html__('Stretch', 'valuepack-addons'),
			),
			'default' => 'stretch',
			'selectors'   => array(
				'{{WRAPPER}} .vp-cubewp-gallery-flex' => 'align-content: {{VALUE}};'
			),
			'condition' => [
				'display_type' => 'flex',
			],
		));

		// Gap
		$this->add_responsive_control(
			'flex_gap',
			[
				'label' => esc_html__('Gap', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-flex' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'display_type' => 'flex',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add Advanced Grid Controls
	 */
	private function add_advanced_grid_controls()
	{
		$this->start_controls_section(
			'advanced_grid_section',
			[
				'label' => esc_html__('Advanced Grid Settings', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'display_type' => 'grid',
				],
			]
		);

		// Enable Advanced Grid
		$this->add_control('enable_advanced_grid', array(
			'type'         => Controls_Manager::SWITCHER,
			'label'        => esc_html__('Enable Advanced Grid', 'valuepack-addons'),
			'label_on'     => esc_html__('Yes', 'valuepack-addons'),
			'label_off'    => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default'      => '',
			'description'  => esc_html__('Enable advanced grid with custom item spans and positions.', 'valuepack-addons'),
		));

		// Number of grid items
		$this->add_control('complex_grid_items_count', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Number of Grid Items', 'valuepack-addons'),
			'description' => esc_html__('How many grid items to create.', 'valuepack-addons'),
			'default' => 4,
			'min'     => 1,
			'max'     => 20,
			'condition' => [
				'enable_advanced_grid' => 'yes',
			],
		));

		//grid gap
		$this->add_responsive_control(
			'complex_grid_gap',
			[
				'label' => esc_html__('Complex Grid Gap', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-complex-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_advanced_grid' => 'yes',
				],
			]
		);

		// Repeater for grid item positions
		$repeater = new \Elementor\Repeater();

		$repeater->add_control('item_grid_column_span', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Column Span', 'valuepack-addons'),
			'default' => 1,
			'min'     => 1,
			'max'     => 12,
		));

		$repeater->add_control('item_grid_row_span', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Row Span', 'valuepack-addons'),
			'default' => 1,
			'min'     => 1,
			'max'     => 12,
		));

		$repeater->add_control('item_grid_column_start', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Column Start', 'valuepack-addons'),
			'description' => esc_html__('Leave empty for auto placement.', 'valuepack-addons'),
			'min'     => 1,
			'max'     => 12,
		));

		$repeater->add_control('item_grid_row_start', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Row Start', 'valuepack-addons'),
			'description' => esc_html__('Leave empty for auto placement.', 'valuepack-addons'),
			'min'     => 1,
			'max'     => 12,
		));

		$repeater->add_responsive_control('item_grid_width', array(
			'type'        => Controls_Manager::SLIDER,
			'label'       => esc_html__('Width', 'valuepack-addons'),
			'size_units'  => array('px', '%', 'em', 'rem'),
			'range'       => array(
				'px'  => array('min' => 1, 'max' => 2000),
				'%'   => array('min' => 1, 'max' => 100),
				'em'  => array('min' => 0.1, 'max' => 100),
				'rem' => array('min' => 0.1, 'max' => 100),
			),
			'description' => esc_html__('Optional. Leave empty for auto.', 'valuepack-addons'),
		));

		$repeater->add_responsive_control('item_grid_height', array(
			'type'        => Controls_Manager::SLIDER,
			'label'       => esc_html__('Height', 'valuepack-addons'),
			'size_units'  => array('px', '%', 'em', 'rem'),
			'range'       => array(
				'px'  => array('min' => 1, 'max' => 2000),
				'%'   => array('min' => 1, 'max' => 100),
				'em'  => array('min' => 0.1, 'max' => 100),
				'rem' => array('min' => 0.1, 'max' => 100),
			),
			'description' => esc_html__('Optional. Leave empty for auto.', 'valuepack-addons'),
		));

		$this->add_control('complex_grid_items', array(
			'type' => Controls_Manager::REPEATER,
			'label' => esc_html__('Grid Items', 'valuepack-addons'),
			'fields' => $repeater->get_controls(),
			'default' => array(
				array(
					'item_grid_column_span' => 2,
					'item_grid_row_span' => 4,
				),
				array(
					'item_grid_column_span' => 2,
					'item_grid_row_span' => 2,
					'item_grid_column_start' => 3,
				),
				array(
					'item_grid_column_span' => 1,
					'item_grid_row_span' => 2,
					'item_grid_column_start' => 3,
					'item_grid_row_start' => 3,
				),
				array(
					'item_grid_column_span' => 1,
					'item_grid_row_span' => 2,
					'item_grid_column_start' => 4,
					'item_grid_row_start' => 3,
				),
			),
			'title_field' => esc_html__('Grid Item', 'valuepack-addons') . ' {{{ item_grid_column_span }}}x{{{ item_grid_row_span }}}',
			'condition' => [
				'enable_advanced_grid' => 'yes',
			],
		));

		$this->end_controls_section();
	}

	/**
	 * Add Slider Controls (similar to Posts Widget)
	 */
	private function add_slider_controls()
	{
		$this->start_controls_section(
			'slider_settings_section',
			[
				'label' => esc_html__('Slider Settings', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'vp_gallery_enable_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'vp_gallery_slider_image_spacing',
			[
				'label'        => esc_html__('Image Spacing', 'valuepack-addons'),
				'type'         => Controls_Manager::DIMENSIONS,
				'size_units'   => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-slide>div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				]
			]
		);

		$this->add_responsive_control(
			'vp_gallery_slider_image_width',
			[
				'label'      => esc_html__('Image Width', 'valuepack-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range'      => [
					'px'  => ['min' => 1, 'max' => 2000],
					'%'   => ['min' => 1, 'max' => 100],
					'em'  => ['min' => 0.1, 'max' => 100],
					'rem' => ['min' => 0.1, 'max' => 100],
				],
				'description' => esc_html__('Set a fixed width for slider images. Leave empty for auto.', 'valuepack-addons'),
			]
		);

		$this->add_responsive_control(
			'vp_gallery_slider_image_height',
			[
				'label'      => esc_html__('Image Height', 'valuepack-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range'      => [
					'px'  => ['min' => 1, 'max' => 2000],
					'%'   => ['min' => 1, 'max' => 100],
					'em'  => ['min' => 0.1, 'max' => 100],
					'rem' => ['min' => 0.1, 'max' => 100],
				],
				'description' => esc_html__('Set a fixed height for slider images. Leave empty for auto.', 'valuepack-addons'),
			]
		);

		$this->add_control('vp_gallery_slides_to_show', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Slides To Show', 'valuepack-addons'),
			'default' => 3,
			'min'     => 1,
			'max'     => 10,
			'step'    => 1,
			'description' => esc_html__('Number of slides to show at once in the slider.', 'valuepack-addons'),
		));

		$this->add_control('vp_gallery_slides_to_scroll', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Slides To Scroll', 'valuepack-addons'),
			'default' => 1,
			'min'     => 1,
			'max'     => 10,
			'step'    => 1,
			'description' => esc_html__('Number of slides to scroll at once in the slider.', 'valuepack-addons'),
		));

		// Optional class wiring for linked sliders (main slider + nav slider)
		$this->add_control('vp_gallery_slider_custom_class', array(
			'type' => Controls_Manager::TEXT,
			'label' => esc_html__('Slider Class (for linking)', 'valuepack-addons'),
			'description' => esc_html__('Enter a CSS class name (without dot). Example: `gallery-main`. Used to connect with another gallery that has "Enable as Nav".', 'valuepack-addons'),
			'default' => '',
		));

		$this->add_control('vp_gallery_enable_as_nav', array(
			'type' => Controls_Manager::SWITCHER,
			'label' => esc_html__('Enable as Nav', 'valuepack-addons'),
			'label_on' => esc_html__('Yes', 'valuepack-addons'),
			'label_off' => esc_html__('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default' => '',
			'description' => esc_html__('When enabled, this slider becomes a navigation slider for another gallery slider using slick "asNavFor".', 'valuepack-addons'),
		));

		$this->add_control('vp_gallery_nav_target_class', array(
			'type' => Controls_Manager::TEXT,
			'label' => esc_html__('Nav target slider class', 'valuepack-addons'),
			'description' => esc_html__('Enter the CSS class name (without dot) that you set on the main slider. Example: `gallery-main`.', 'valuepack-addons'),
			'default' => '',
			'condition' => [
				'vp_gallery_enable_as_nav' => 'yes',
			],
		));

		$this->add_control('autoplay', array(
			'type'    => Controls_Manager::SWITCHER,
			'label'   => esc_html__('Autoplay', 'valuepack-addons'),
			'default' => 'yes',
			'description' => esc_html__('Enable or disable autoplay for the slider.', 'valuepack-addons'),
		));

		$this->add_control('autoplay_speed', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Autoplay Speed (ms)', 'valuepack-addons'),
			'default' => 2000,
			'min'     => 500,
			'max'     => 10000,
			'step'    => 500,
			'description' => esc_html__('Set the speed for autoplay in milliseconds.', 'valuepack-addons'),
			'condition' => [
				'autoplay' => 'yes',
			],
		));

		$this->add_control('speed', array(
			'type'    => Controls_Manager::NUMBER,
			'label'   => esc_html__('Speed (ms)', 'valuepack-addons'),
			'default' => 500,
			'min'     => 100,
			'max'     => 5000,
			'step'    => 100,
			'description' => esc_html__('Set the speed for the slider transition in milliseconds.', 'valuepack-addons'),
		));

		$this->add_control('fade_effect', array(
			'type' => Controls_Manager::SWITCHER,
			'label' => esc_html__('Fade Effect', 'valuepack-addons'),
			'default' => '',
			'description' => esc_html__('Enable fade effect for slides transition.', 'valuepack-addons'),
		));

		$this->add_control('infinite', array(
			'type'    => Controls_Manager::SWITCHER,
			'label'   => esc_html__('Infinite Loop', 'valuepack-addons'),
			'default' => 'yes',
			'description' => esc_html__('Enable or disable infinite loop for the slider.', 'valuepack-addons'),
		));
		
		$this->add_control('center_mode', array(
			'type'    => Controls_Manager::SWITCHER,
			'label'   => esc_html__('Center Mode', 'valuepack-addons'),
			'default' => 'no',
			'description' => esc_html__('Enable center mode for the slider.', 'valuepack-addons'),
		));

		$this->add_control('variable_width', array(
			'label' => __('Variable Width', 'valuepack-addons'),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => __('Yes', 'valuepack-addons'),
			'label_off' => __('No', 'valuepack-addons'),
			'return_value' => 'yes',
			'default' => 'no',
		));

		// Responsive Settings
		$this->add_control(
			'slider_responsive_settings_heading',
			[
				'label' => esc_html__('Responsive Settings', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control('vp_gallery_slides_to_show_tablet', array(
			'label' => esc_html__('Slides To Show On (Tablet)', 'valuepack-addons'),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 10,
			'step' => 1,
			'default' => 3,
		));

		$this->add_control('vp_gallery_slides_to_show_tablet_portrait', array(
			'label' => esc_html__('Slides To Show On (Tablet Portrait)', 'valuepack-addons'),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 10,
			'step' => 1,
			'default' => 2,
		));

		$this->add_control('vp_gallery_slides_to_show_mobile', array(
			'label' => esc_html__('Slides To Show On (Mobile)', 'valuepack-addons'),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 10,
			'step' => 1,
			'default' => 1,
		));

		$this->add_control('vp_gallery_slides_to_scroll_tablet', array(
			'label' => esc_html__('Slides To Scroll On (Tablet)', 'valuepack-addons'),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 10,
			'step' => 1,
			'default' => 1,
		));

		$this->add_control('vp_gallery_slides_to_scroll_tablet_portrait', array(
			'label' => esc_html__('Slides To Scroll On (Tablet Portrait)', 'valuepack-addons'),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 10,
			'step' => 1,
			'default' => 1,
		));

		$this->add_control('vp_gallery_slides_to_scroll_mobile', array(
			'label' => esc_html__('Slides To Scroll On (Mobile)', 'valuepack-addons'),
			'type' => Controls_Manager::NUMBER,
			'min' => 1,
			'max' => 10,
			'step' => 1,
			'default' => 1,
		));

		// Arrows Settings
		$this->add_control(
			'slider_arrows_heading',
			[
				'label' => esc_html__('Arrows Settings', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control('enable_arrows', array(
			'type' => Controls_Manager::SWITCHER,
			'label' => esc_html__('Enable Arrows', 'valuepack-addons'),
			'default' => 'yes',
		));

		$this->add_control('prev_icon', array(
			'label' => __('Previous Icon', 'valuepack-addons'),
			'type' => Controls_Manager::ICONS,
			'default' => [
				'value' => 'fas fa-chevron-left',
				'library' => 'fa-solid',
			],
			'label_block' => true,
			'condition' => [
				'enable_arrows' => 'yes',
			],
		));

		$this->add_control('next_icon', array(
			'label' => __('Next Icon', 'valuepack-addons'),
			'type' => Controls_Manager::ICONS,
			'default' => [
				'value' => 'fas fa-chevron-right',
				'library' => 'fa-solid',
			],
			'label_block' => true,
			'condition' => [
				'enable_arrows' => 'yes',
			],
		));

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Icon Size (px)', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev i, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_color',
			[
				'label' => esc_html__('Icon Color', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev i, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next i' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_hover_color',
			[
				'label' => esc_html__('Icon Hover Color', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev:hover i, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next:hover i' => 'color: {{VALUE}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_background_color',
			[
				'label' => esc_html__('Icon & Svg Background Color', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_hover_background_color',
			[
				'label' => esc_html__('Icon & Svg Hover Background Color', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev:hover, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'svg_color',
			[
				'label' => esc_html__('SVG Color', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev svg path, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'svg_hover_color',
			[
				'label' => esc_html__('SVG Hover Color', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF0000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev:hover svg path, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next:hover svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'svg_width',
			[
				'label' => esc_html__('SVG Width', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev svg, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'svg_height',
			[
				'label' => esc_html__('SVG Height', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev svg, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next svg' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__('Border', 'valuepack-addons'),
                'selector' => '{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next',
            ]
        );

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => esc_html__('Border Radius', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_icon_border_color_hover',
			[
				'label' => esc_html__('Border Color on Hover', 'cubewp-framework'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev:hover, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_border_transition',
			[
				'label' => esc_html__('Transition Duration', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next' => 'transition: background-color {{SIZE}}s, color {{SIZE}}s, border-color {{SIZE}}s;',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__('Icon & Svg Padding', 'cubewp-framework'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow',
				'label' => __('Arrow Box Shadow', 'cubewp-framework'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-slider .slick-arrow',
				'separator' => 'before',
				'condition' => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_position_divider_heading',
			[
				'label' => esc_html__('Set the Icons Positions', 'cubewp-framework'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'   => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_top_position',
			[
				'label' => esc_html__('Top Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'    => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_bottom_position',
			[
				'label' => esc_html__('Bottom Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev, {{WRAPPER}} .vp-cubewp-gallery-slider .slick-next' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'    => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_prev_left_position',
			[
				'label' => esc_html__('Left Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-prev' => 'left: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'    => [
					'enable_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_next_right_position',
			[
				'label' => esc_html__('Right Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-next' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'    => [
					'enable_arrows' => 'yes',
				],
			]
		);

		// Dots Settings
		$this->add_control(
			'slider_dots_heading',
			[
				'label' => esc_html__('Dots Settings', 'valuepack-addons'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control('enable_dots', array(
			'type' => Controls_Manager::SWITCHER,
			'label' => esc_html__('Enable Dots', 'valuepack-addons'),
			'default' => '',
		));

		$this->add_responsive_control(
			'dots_display_flex',
			[
				'label' => esc_html__('Dots Display', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'block' => esc_html__('Block', 'cubewp-framework'),
					'flex' => esc_html__('Flex', 'cubewp-framework'),
				],
				'default' => 'flex',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'display: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_flex_direction',
			[
				'label' => esc_html__('Dots Flex Direction', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'row' => esc_html__('Row', 'cubewp-framework'),
					'row-reverse' => esc_html__('Row Reverse', 'cubewp-framework'),
					'column' => esc_html__('Column', 'cubewp-framework'),
					'column-reverse' => esc_html__('Column Reverse', 'cubewp-framework'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'dots_display_flex' => 'flex',
				],
			]
		);

		$this->add_responsive_control(
			'dots_gap',
			[
				'label' => __('Dots Gap', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'gap:{{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_position_select',
			[
				'label' => esc_html__('Dots Position', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'static' => esc_html__('Static', 'cubewp-framework'),
					'absolute' => esc_html__('Absolute', 'cubewp-framework'),
					'relative' => esc_html__('Relative', 'cubewp-framework'),
					'fixed' => esc_html__('Fixed', 'cubewp-framework'),
				],
				'default' => 'static',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'position: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_top_position',
			[
				'label' => esc_html__('Dots Top Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'dots_position_select' => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			'dots_bottom_position',
			[
				'label' => esc_html__('Dots Bottom Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'dots_position_select' => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			'dots_left_position',
			[
				'label' => esc_html__('Dots Left Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'dots_position_select' => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			'dots_right_position',
			[
				'label' => esc_html__('Dots Right Position', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'dots_position_select' => 'absolute',
				],
			]
		);

		$this->add_control(
			'dots_position_z_index',
			[
				'label' => esc_html__('Dots Z-Index', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -9999,
				'max' => 9999,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots' => 'z-index: {{VALUE}} !important;',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'dots_position_select' => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			'dots_padding',
			[
				'label' => esc_html__('Dots Padding', 'cubewp-framework'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_style',
			[
				'label' => __('Dots Border Style', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'None',
				'options' => [
					'none' => __('None', 'cubewp-framework'),
					'solid' => __('Solid', 'cubewp-framework'),
					'dotted' => __('Dotted', 'cubewp-framework'),
					'dashed' => __('Dashed', 'cubewp-framework'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_width',
			[
				'label' => __('Dots Border Width', 'cubewp-framework'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
					'dots_border_style!' => 'none',
				],
			]
		);

		//Dots font size
		$this->add_responsive_control(
			'dots_font_size',
			[
				'label' => __('Dots Font Size', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li button' => 'font-size: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_outside_padding',
			[
				'label' => esc_html__('Dots Outside Padding', 'cubewp-framework'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_outside_color',
			[
				'label' => esc_html__('Dots Outside Backgroud Color', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'active_dot_outside_color',
			[
				'label' => esc_html__('Active Dot Outside Backgroud Color', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots .slick-active' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_color',
			[
				'label' => __('Dots Border Color', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_active_border_color',
			[
				'label' => __('Active Dot Border Color', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots .slick-active button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label' => __('Dots Border Radius', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li,{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li button' => 'border-radius: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_background_color',
			[
				'label' => __('Dots Background Color', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_active_background_color',
			[
				'label' => __('Active Dot Background Color', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots .slick-active button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_width',
			[
				'label' => __('Dots Width', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li button' => 'width: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_height',
			[
				'label' => __('Dots Height', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots li button' => 'height: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'active_dot_width',
			[
				'label' => __('Active Dot Width', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 12,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots .slick-active button' => 'width: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'active_dot_height',
			[
				'label' => __('Active Dot Height', 'cubewp-framework'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 12,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-slider .slick-dots .slick-active button' => 'height: {{VALUE}}px;',
				],
				'condition' => [
					'enable_dots' => 'yes',
				],
			]
		);

		// slider wrape dots 
		$this->add_control(
			'slider_dots_wrap_settings_heading',
			[
				'label' => esc_html__('Wrap Dots With Arrows', 'cubewp-framework'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'   => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_wrap_dots_arrows',
			[
				'label'        => esc_html__('Enable Wrap Dots With Arrows', 'cubewp-framework'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'cubewp-framework'),
				'label_off'    => esc_html__('No', 'cubewp-framework'),
				'return_value' => 'yes',
				'default'      => '',
				'condition'   => [
					'enable_dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_direction_position_vertical',
			[
				'label' => esc_html__('Vertical Direction', 'cubewp-framework'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__('Top', 'cubewp-framework'),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__('Bottom', 'cubewp-framework'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'bottom',
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'vp_scrollbar_Top_position',
			[
				'label' => esc_html__('Top Position', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => -10,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-arrows-wrapper' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
					'icon_direction_position_vertical' => 'top',
				],
			]
		);
		$this->add_responsive_control(
			'vp_scrollbar_bottom_position',
			[
				'label' => esc_html__('Bottom Position', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => -10,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-arrows-wrapper' =>  'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
					'icon_direction_position_vertical' => 'bottom',
				],
			]
		);

		$this->add_responsive_control(
			'icon_direction_position',
			[
				'label' => esc_html__('horizontal Direction', 'cubewp-framework'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'cubewp-framework'),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__('Right', 'cubewp-framework'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
				],

			]
		);

		$this->add_responsive_control(
			'vp_scrollbar_right_position',
			[
				'label' => esc_html__('Right Position', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => -10,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-arrows-wrapper' =>  'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
					'icon_direction_position' => 'right',
				],
			]
		);
		$this->add_responsive_control(
			'vp_scrollbar_left_position',
			[
				'label' => esc_html__('Left Position', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -500,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => -10,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-arrows-wrapper' =>  'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
					'icon_direction_position' => 'left',
				],
			]
		);
		$this->add_responsive_control(
			'gap_between_items',
			[
				'label' => esc_html__('Gap Between Items', 'cubewp-framework'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-arrows-wrapper' =>  'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'wrap_justify_content',
			[
				'label' => esc_html__('Justify Content', 'cubewp-framework'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'flex-start' => esc_html__('Flex Start', 'cubewp-framework'),
					'center' => esc_html__('Center', 'cubewp-framework'),
					'flex-end' => esc_html__('Flex End', 'cubewp-framework'),
					'space-between' => esc_html__('Space Between', 'cubewp-framework'),
					'space-around' => esc_html__('Space Around', 'cubewp-framework'),
					'space-evenly' => esc_html__('Space Evenly', 'cubewp-framework'),
				],
				'default' => 'center',
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
					'cwp_enable_slider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-arrows-wrapper' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'wrap_dots_arrows_padding',
			[
				'label' => esc_html__('Padding', 'cubewp-framework'),
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
					'{{WRAPPER}} .slick-arrows-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'enable_wrap_dots_arrows' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Image Styling Section
		$this->add_image_styling_controls();
	}

	/**
	 * Add Image Styling Controls
	 */
	private function add_image_styling_controls()
	{
		$this->start_controls_section(
			'image_styling_section',
			[
				'label' => esc_html__('Image Styling', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Image Width
		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__('Width', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'vw'],
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
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Image Height
		$this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__('Height', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'vh'],
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
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Object Fit
		$this->add_control(
			'image_object_fit',
			[
				'label' => esc_html__('Object Fit', 'valuepack-addons'),
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
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'object-fit: {{VALUE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		// Object Position
		$this->add_control(
			'image_object_position',
			[
				'label' => esc_html__('Object Position', 'valuepack-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'center' => esc_html__('Center', 'valuepack-addons'),
					'top' => esc_html__('Top', 'valuepack-addons'),
					'bottom' => esc_html__('Bottom', 'valuepack-addons'),
					'left' => esc_html__('Left', 'valuepack-addons'),
					'right' => esc_html__('Right', 'valuepack-addons'),
					'top left' => esc_html__('Top Left', 'valuepack-addons'),
					'top right' => esc_html__('Top Right', 'valuepack-addons'),
					'bottom left' => esc_html__('Bottom Left', 'valuepack-addons'),
					'bottom right' => esc_html__('Bottom Right', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'object-position: {{VALUE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'object-position: {{VALUE}};',
				],
			]
		);

		// Opacity
		$this->add_control(
			'image_opacity',
			[
				'label' => esc_html__('Opacity', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'opacity: {{SIZE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'opacity: {{SIZE}};',
				],
			]
		);

		// Border Radius
		$this->add_control(
			'image_border_radius',
			[
				'label' => __('Border Radius', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img, {{WRAPPER}} .vp-cubewp-gallery-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__('Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-item img, {{WRAPPER}} .vp-cubewp-gallery-slide img',
			]
		);

		// Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => esc_html__('Box Shadow', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-item img, {{WRAPPER}} .vp-cubewp-gallery-slide img',
			]
		);

		// Padding
		$this->add_responsive_control(
			'image_padding',
			[
				'label' => esc_html__('Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'image_margin',
			[
				'label' => esc_html__('Margin', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// CSS Filter
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-item img, {{WRAPPER}} .vp-cubewp-gallery-slide img',
			]
		);

		$this->end_controls_section();

		// Image Hover Styling Section
		$this->start_controls_section(
			'image_hover_styling_section',
			[
				'label' => esc_html__('Image Hover Styling', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Hover Opacity
		$this->add_control(
			'image_hover_opacity',
			[
				'label' => esc_html__('Hover Opacity', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item:hover img' => 'opacity: {{SIZE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-slide:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		// Hover Border Radius
		$this->add_control(
			'image_hover_border_radius',
			[
				'label' => __('Hover Border Radius', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item:hover img, {{WRAPPER}} .vp-cubewp-gallery-slide:hover img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Hover Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_hover_border',
				'label' => esc_html__('Hover Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-item:hover img, {{WRAPPER}} .vp-cubewp-gallery-slide:hover img',
			]
		);

		// Hover Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_hover_box_shadow',
				'label' => esc_html__('Hover Box Shadow', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-item:hover img, {{WRAPPER}} .vp-cubewp-gallery-slide:hover img',
			]
		);

		// Hover Transform Scale
		$this->add_control(
			'image_hover_scale',
			[
				'label' => esc_html__('Hover Scale', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
			]
		);

		// Hover Transform Rotate
		$this->add_control(
			'image_hover_rotate',
			[
				'label' => esc_html__('Hover Rotate', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -360,
						'max' => 360,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
			]
		);

		// Hover Transform Translate Y
		$this->add_control(
			'image_hover_translate_y',
			[
				'label' => esc_html__('Hover Translate Y', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
			]
		);

		// Note: Transforms are combined in render method to avoid conflicts

		// Transition Duration
		$this->add_control(
			'image_transition_duration',
			[
				'label' => esc_html__('Transition Duration (ms)', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 50,
					],
				],
				'default' => [
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-item img' => 'transition-property: opacity, transform, border-radius, box-shadow; transition-duration: {{SIZE}}ms;',
					'{{WRAPPER}} .vp-cubewp-gallery-slide img' => 'transition-property: opacity, transform, border-radius, box-shadow; transition-duration: {{SIZE}}ms;',
				],
			]
		);

		// Hover CSS Filter
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_hover_css_filters',
				'label' => esc_html__('Hover CSS Filters', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-item:hover img, {{WRAPPER}} .vp-cubewp-gallery-slide:hover img',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add See All Button style controls
	 */
	private function add_see_all_button_style_controls()
	{
		$this->start_controls_section(
			'see_all_button_style_section',
			[
				'label'     => esc_html__('See All Button', 'valuepack-addons'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_see_all_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'see_all_button_display',
			[
				'label'     => esc_html__('Display', 'valuepack-addons'),
				'type'      => Controls_Manager::SELECT,
				'default'  => 'inline-flex',
				'options'   => [
					'flex' => esc_html__('Flex', 'valuepack-addons'),
					'inline-flex' => esc_html__('Inline Flex', 'valuepack-addons'),
					'inline'      => esc_html__('Inline', 'valuepack-addons'),
					'block'       => esc_html__('Block', 'valuepack-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'display: {{VALUE}}; align-items: center;',
				],
			]
		);

		$this->add_responsive_control(
			'see_all_button_gap',
			[
				'label'      => esc_html__('Gap', 'valuepack-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range'      => [
					'px' => ['min' => 0, 'max' => 50],
					'em' => ['min' => 0, 'max' => 3],
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'see_all_button_display' => array('flex', 'inline-flex'),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'see_all_button_typography',
				'label'    => esc_html__('Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-see-all-btn',
			]
		);

		$this->add_control(
			'see_all_button_width',
			[
				'label'     => esc_html__('Width', 'valuepack-addons'),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range'      => [
					'px' => ['min' => 0, 'max' => 100],
					'%' => ['min' => 0, 'max' => 100],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_control(
			'see_all_button_text_color',
			[
				'label'     => esc_html__('Text Color', 'valuepack-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'see_all_button_hover_text_color',
			[
				'label'     => esc_html__('Hover Text Color', 'valuepack-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'see_all_button_icon_color',
			[
				'label'     => esc_html__('Icon Color', 'valuepack-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'see_all_button_icon_size',
			[
				'label'      => esc_html__('Icon Size', 'valuepack-addons'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range'      => [
					'px' => ['min' => 8, 'max' => 80],
					'em' => ['min' => 0.5, 'max' => 4],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'  => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'see_all_button_background',
				'label'    => esc_html__('Background', 'valuepack-addons'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-see-all-btn',
			]
		);

		$this->add_responsive_control(
			'see_all_button_padding',
			[
				'label'      => esc_html__('Padding', 'valuepack-addons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'see_all_button_margin',
			[
				'label'      => esc_html__('Margin', 'valuepack-addons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'see_all_button_align',
			[
				'label'     => esc_html__('Alignment', 'valuepack-addons'),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__('Left', 'valuepack-addons'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'valuepack-addons'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__('Right', 'valuepack-addons'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'justify-content: {{VALUE}};',
				],
			]
		);

		// Position controls for See All Button
		$this->add_control(
			'see_all_button_position_type',
			[
				'label' => esc_html__('Position', 'valuepack-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'static' => [
						'title' => esc_html__('Static', 'valuepack-addons'),
						'icon' => 'eicon-arrow-down',
					],
					'absolute' => [
						'title' => esc_html__('Absolute', 'valuepack-addons'),
						'icon' => 'eicon-lock',
					],
					'relative' => [
						'title' => esc_html__('Relative', 'valuepack-addons'),
						'icon' => 'eicon-undo',
					],
					'fixed' => [
						'title' => esc_html__('Fixed', 'valuepack-addons'),
						'icon' => 'eicon-target',
					],
				],
				'default' => 'static',
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-wrap' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'see_all_button_vertical_anchor',
			[
				'label' => esc_html__('Vertical', 'valuepack-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__('Top', 'valuepack-addons'),
						'icon' => 'eicon-arrow-up',
					],
					'bottom' => [
						'title' => esc_html__('Bottom', 'valuepack-addons'),
						'icon' => 'eicon-arrow-down',
					],
				],
				'default' => 'top',
				'condition' => [
					'see_all_button_position_type!' => 'static',
				],
			]
		);

		$this->add_control(
			'see_all_button_top_offset',
			[
				'label' => esc_html__('Top Offset', 'valuepack-addons'),
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
						'max' => 200,
						'step' => 1,
					],
				],
				'condition' => [
					'see_all_button_position_type!' => 'static',
					'see_all_button_vertical_anchor' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-wrap' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'see_all_button_bottom_offset',
			[
				'label' => esc_html__('Bottom Offset', 'valuepack-addons'),
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
						'max' => 200,
						'step' => 1,
					],
				],
				'condition' => [
					'see_all_button_position_type!' => 'static',
					'see_all_button_vertical_anchor' => 'bottom',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-wrap' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'see_all_button_horizontal_anchor',
			[
				'label' => esc_html__('Horizontal', 'valuepack-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'valuepack-addons'),
						'icon' => 'eicon-arrow-left',
					],
					'right' => [
						'title' => esc_html__('Right', 'valuepack-addons'),
						'icon' => 'eicon-arrow-right',
					],
				],
				'default' => 'left',
				'condition' => [
					'see_all_button_position_type!' => 'static',
				],
			]
		);

		$this->add_control(
			'see_all_button_left_offset',
			[
				'label' => esc_html__('Left Offset', 'valuepack-addons'),
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
						'max' => 200,
						'step' => 1,
					],
				],
				'condition' => [
					'see_all_button_position_type!' => 'static',
					'see_all_button_horizontal_anchor' => 'left',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-wrap' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'see_all_button_right_offset',
			[
				'label' => esc_html__('Right Offset', 'valuepack-addons'),
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
						'max' => 200,
						'step' => 1,
					],
				],
				'condition' => [
					'see_all_button_position_type!' => 'static',
					'see_all_button_horizontal_anchor' => 'right',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-wrap' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'see_all_button_height',
			[
				'label' => esc_html__('Height', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 20,
						'step' => 0.1,
					],
				],
				'condition' => [
					'see_all_button_position_type!' => 'static',
				],
				'selectors' => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'see_all_button_border',
				'label'    => esc_html__('Border', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-see-all-btn',
			]
		);

		$this->add_responsive_control(
			'see_all_button_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'valuepack-addons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .vp-cubewp-gallery-see-all-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'see_all_button_box_shadow',
				'label'    => esc_html__('Box Shadow', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .vp-cubewp-gallery-see-all-btn',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'centermode_styling_section',
			[
				'label' => esc_html__('Center Mode Styling', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'center_mode' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'centermode_transform_scale',
			[
				'label' => esc_html__('Center Slide Scale (Transform)', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [''],
				'range' => [
					'' => [
						'min' => 1,
						'max' => 1.3,
						'step' => 0.005,
					],
				],
				'default' => [
					'size' => 1.04,
				],
				'selectors' => [
					'{{WRAPPER}} .slick-slide  div' => 'transition:1s',
					'{{WRAPPER}} .slick-slide.slick-current.slick-active.slick-center' => 'overflow:hidden',
					'{{WRAPPER}} .slick-slide.slick-current.slick-active.slick-center div' => 'transform: scale({{SIZE}});',
				],
				'description' => esc_html__('Set the transform scale for the center slide. Default is 1.04.', 'valuepack-addons'),
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$use_post_url_links = isset($settings['gallery_link_type']) && $settings['gallery_link_type'] === 'post_url';

		// Get all gallery images
		$all_images = $this->get_gallery_images($settings);
		$image_post_urls = $this->get_gallery_image_post_urls($settings);

		if (empty($all_images)) {
			return;
		}

		// Limit number of images to display (0 or empty = show all)
		$total_count = count($all_images);
		$limit = isset($settings['gallery_images_to_show']) ? intval($settings['gallery_images_to_show']) : 0;
		$images = ($limit > 0) ? array_slice($all_images, 0, $limit) : $all_images;
		$displayed_count = count($images);

		// Render based on display type and slider
		$display_type = isset($settings['display_type']) ? $settings['display_type'] : 'grid';
		$vp_gallery_enable_slider = isset($settings['vp_gallery_enable_slider']) && $settings['vp_gallery_enable_slider'] === 'yes';
		$enable_advanced_grid = isset($settings['enable_advanced_grid']) && $settings['enable_advanced_grid'] === 'yes';
		$slide_to_show = isset($settings['vp_gallery_slides_to_show']) ? intval($settings['vp_gallery_slides_to_show']) : 3;
		// If slider is enabled, wrap the layout in slider
		//if ($vp_gallery_enable_slider && $slide_to_show > $displayed_count) {
		if ($vp_gallery_enable_slider) {
			$this->render_slider_image_dimensions_css($settings);
			if ($display_type === 'grid' && $enable_advanced_grid) {
				// Advanced grid with slider - each complete grid layout is a slide
				$this->render_advanced_grid_slider($images, $settings, $image_post_urls);
			} else {
				// Regular slider with grid or flex
				$this->render_slider_layout($images, $settings, $image_post_urls);
			}
		} else {
			// No slider - render layout directly
			if ($display_type === 'flex') {
				$this->render_flex($images, $settings, $image_post_urls);
			} else {
				if ($enable_advanced_grid) {
					$this->render_advanced_grid($images, $settings, $image_post_urls);
				} else {
					$this->render_grid($images, $settings, $image_post_urls);
				}
			}
		}

		// See All button: show when enabled and lightbox is on (independent of number of images shown)
		$enable_see_all = isset($settings['enable_see_all_button']) && $settings['enable_see_all_button'] === 'yes';
		$enable_lightbox = isset($settings['enable_lightbox']) && $settings['enable_lightbox'] === 'yes';
		if (!$use_post_url_links && $enable_see_all && $enable_lightbox && $total_count > 0) {
			$gallery_id = 'vp-cubewp-gallery-' . $this->get_id();
			$this->render_see_all_button($total_count, $settings, $all_images, $displayed_count, $gallery_id);
		}

	 
	}

	/**
	 * Get gallery images based on settings
	 */
	private function get_gallery_images($settings)
	{
		$images = array();
		$resolved = $this->resolve_gallery_posts_and_field($settings);
		$gallery_field = $resolved['gallery_field'];
		$post_ids = $resolved['post_ids'];

		if (empty($gallery_field)) {
			return $images;
		}

		if (empty($post_ids)) {
			return $images;
		}
		// Get gallery images from posts
		foreach ($post_ids as $post_id) {
			$gallery_value = get_post_meta($post_id, $gallery_field, true);


			if (is_array($gallery_value)) {
				$images = array_merge($images, $gallery_value);
			} elseif (is_string($gallery_value) && !empty($gallery_value)) {
				// Handle comma-separated IDs
				$ids = explode(',', $gallery_value);
				$images = array_merge($images, array_map('trim', $ids));
			}
		}

		// Filter empty values
		$images = array_filter($images);

		return $images;
	}

	private function get_gallery_image_post_urls($settings)
	{
		$image_post_urls = array();
		$resolved = $this->resolve_gallery_posts_and_field($settings);
		$gallery_field = $resolved['gallery_field'];
		$post_ids = $resolved['post_ids'];

		if (empty($gallery_field) || empty($post_ids)) {
			return $image_post_urls;
		}

		foreach ($post_ids as $post_id) {
			$post_url = get_permalink($post_id);
			if (!$post_url) {
				continue;
			}

			$gallery_value = get_post_meta($post_id, $gallery_field, true);
			$image_ids = array();
			if (is_array($gallery_value)) {
				$image_ids = $gallery_value;
			} elseif (is_string($gallery_value) && !empty($gallery_value)) {
				$image_ids = explode(',', $gallery_value);
			}

			foreach ($image_ids as $image_id) {
				$image_id = absint($image_id);
				if (!$image_id || isset($image_post_urls[$image_id])) {
					continue;
				}
				$image_post_urls[$image_id] = $post_url;
			}
		}

		return $image_post_urls;
	}

	private function resolve_gallery_posts_and_field($settings)
	{
		$post_type = isset($settings['post_type']) ? $settings['post_type'] : '';
		$gallery_field = isset($settings['gallery_field_' . $post_type]) ? $settings['gallery_field_' . $post_type] : '';
		$gallery_source = isset($settings['gallery_source']) ? $settings['gallery_source'] : 'current_post';
		$post_ids = array();

		if (empty($post_type) || empty($gallery_field)) {
			return array(
				'gallery_field' => '',
				'post_ids' => array(),
			);
		}

		switch ($gallery_source) {
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

		return array(
			'gallery_field' => $gallery_field,
			'post_ids' => $post_ids,
		);
	}

	/**
	 * Render Grid Layout
	 */
	private function render_grid($images, $settings, $image_post_urls = array())
	{
		$image_size = isset($settings['image_size']) ? $settings['image_size'] : 'medium';
		$enable_lightbox = isset($settings['enable_lightbox']) && $settings['enable_lightbox'] === 'yes';
		$use_post_url_links = isset($settings['gallery_link_type']) && $settings['gallery_link_type'] === 'post_url';
		$gallery_id = 'vp-cubewp-gallery-' . $this->get_id();

		echo '<div class="vp-cubewp-gallery-container vp-gallery-component-viewer">';
		echo '<div class="vp-cubewp-gallery-grid">';

		foreach ($images as $image_id) {
			$image_id = absint($image_id);
			$post_url = isset($image_post_urls[$image_id]) ? $image_post_urls[$image_id] : '';
			$this->render_image_item($image_id, $image_size, $enable_lightbox, $gallery_id, 'vp-cubewp-gallery-item', $use_post_url_links, $post_url);
		}

		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render Slider Layout (works with Grid or Flex)
	 */
	private function render_slider_layout($images, $settings, $image_post_urls = array())
	{
		$display_type = isset($settings['display_type']) ? $settings['display_type'] : 'grid';
		$image_size = isset($settings['image_size']) ? $settings['image_size'] : 'medium';
		$enable_lightbox = isset($settings['enable_lightbox']) && $settings['enable_lightbox'] === 'yes';
		$use_post_url_links = isset($settings['gallery_link_type']) && $settings['gallery_link_type'] === 'post_url';
		$gallery_id = 'vp-cubewp-gallery-' . $this->get_id();
		$widget_id = $this->get_id();

		// Slider settings
		$vp_gallery_slides_to_show = isset($settings['vp_gallery_slides_to_show']) ? intval($settings['vp_gallery_slides_to_show']) : 3;
		$vp_gallery_slides_to_scroll = isset($settings['vp_gallery_slides_to_scroll']) ? intval($settings['vp_gallery_slides_to_scroll']) : 1;
		$autoplay = isset($settings['autoplay']) && $settings['autoplay'] === 'yes';
		$autoplay_speed = isset($settings['autoplay_speed']) ? intval($settings['autoplay_speed']) : 2000;
		$speed = isset($settings['speed']) ? intval($settings['speed']) : 500;
		$infinite = isset($settings['infinite']) && $settings['infinite'] === 'yes';
		$center_mode = isset($settings['center_mode']) && $settings['center_mode'] === 'yes';
		$fade_effect = isset($settings['fade_effect']) && $settings['fade_effect'] === 'yes';
		$variable_width = isset($settings['variable_width']) && $settings['variable_width'] === 'yes';
		$enable_arrows = isset($settings['enable_arrows']) && $settings['enable_arrows'] === 'yes';
		$enable_dots = isset($settings['enable_dots']) && $settings['enable_dots'] === 'yes';
		$enable_wrap_dots_arrows = isset($settings['enable_wrap_dots_arrows']) && $settings['enable_wrap_dots_arrows'] === 'yes';

		// Responsive settings
		$vp_gallery_slides_to_show_tablet = isset($settings['vp_gallery_slides_to_show_tablet']) ? intval($settings['vp_gallery_slides_to_show_tablet']) : 3;
		$vp_gallery_slides_to_show_tablet_portrait = isset($settings['vp_gallery_slides_to_show_tablet_portrait']) ? intval($settings['vp_gallery_slides_to_show_tablet_portrait']) : 2;
		$vp_gallery_slides_to_show_mobile = isset($settings['vp_gallery_slides_to_show_mobile']) ? intval($settings['vp_gallery_slides_to_show_mobile']) : 1;
		$vp_gallery_slides_to_scroll_tablet = isset($settings['vp_gallery_slides_to_scroll_tablet']) ? intval($settings['vp_gallery_slides_to_scroll_tablet']) : 1;
		$vp_gallery_slides_to_scroll_tablet_portrait = isset($settings['vp_gallery_slides_to_scroll_tablet_portrait']) ? intval($settings['vp_gallery_slides_to_scroll_tablet_portrait']) : 1;
		$vp_gallery_slides_to_scroll_mobile = isset($settings['vp_gallery_slides_to_scroll_mobile']) ? intval($settings['vp_gallery_slides_to_scroll_mobile']) : 1;

		// Get icon attributes using helper
		$icon_attrs = $this->get_slider_icon_attributes($settings);

		$slider_extra_class = '';
		$custom_class_input = isset($settings['vp_gallery_slider_custom_class']) ? trim((string) $settings['vp_gallery_slider_custom_class']) : '';
		$custom_class_input = ltrim($custom_class_input, '.');
		if (! empty($custom_class_input)) {
			$slider_extra_class = ' ' . esc_attr($custom_class_input);
		}

		$as_nav_for_attr = '';
		$enable_as_nav = isset($settings['vp_gallery_enable_as_nav']) && $settings['vp_gallery_enable_as_nav'] === 'yes';
		$nav_target_class_input = isset($settings['vp_gallery_nav_target_class']) ? trim((string) $settings['vp_gallery_nav_target_class']) : '';
		$nav_target_class_input = ltrim($nav_target_class_input, '.');
		if ($enable_as_nav && ! empty($nav_target_class_input)) {
			$as_nav_for_attr = ' data-as-nav-for="' . esc_attr('.' . $nav_target_class_input) . '"';
		}

		echo '<div class="vp-cubewp-gallery-container vp-gallery-component-viewer">';
		echo '<div class="vp-cubewp-gallery-slider elementor-element-' . esc_attr($widget_id) . $slider_extra_class . '" 
			data-slides-to-show="' . esc_attr($vp_gallery_slides_to_show) . '" 
			data-slides-to-scroll="' . esc_attr($vp_gallery_slides_to_scroll) . '" 
			data-autoplay="' . ($autoplay ? 'true' : 'false') . '" 
			data-autoplay-speed="' . esc_attr($autoplay_speed) . '" 
			data-speed="' . esc_attr($speed) . '"
			data-infinite="' . ($infinite ? 'true' : 'false') . '" 
			data-center-mode="' . ($center_mode ? 'true' : 'false') . '"
			data-fade="' . ($fade_effect ? 'true' : 'false') . '"
			data-variable-width="' . ($variable_width ? 'true' : 'false') . '"
			data-custom-arrows="' . ($enable_arrows ? 'true' : 'false') . '" 
			data-custom-dots="' . ($enable_dots ? 'true' : 'false') . '"
			data-wrap-dots-arrows="' . ($enable_wrap_dots_arrows ? 'true' : 'false') . '"
			data-slides-to-show-tablet="' . esc_attr($vp_gallery_slides_to_show_tablet) . '"
			data-slides-to-show-tablet-portrait="' . esc_attr($vp_gallery_slides_to_show_tablet_portrait) . '"
			data-slides-to-show-mobile="' . esc_attr($vp_gallery_slides_to_show_mobile) . '"
			data-slides-to-scroll-tablet="' . esc_attr($vp_gallery_slides_to_scroll_tablet) . '"
			data-slides-to-scroll-tablet-portrait="' . esc_attr($vp_gallery_slides_to_scroll_tablet_portrait) . '"
			data-slides-to-scroll-mobile="' . esc_attr($vp_gallery_slides_to_scroll_mobile) . '"
			' . $as_nav_for_attr . '
			' . $icon_attrs . '">';

		// Determine layout class
		$layout_class = ($display_type === 'flex') ? 'vp-cubewp-gallery-flex' : 'vp-cubewp-gallery-grid';

		foreach ($images as $image_id) {
			$image_id = absint($image_id);
			if (!$image_id) {
				continue;
			}

			echo '<div class="vp-cubewp-gallery-slide">';
			echo '<div class="' . esc_attr($layout_class) . '">';

			$post_url = isset($image_post_urls[$image_id]) ? $image_post_urls[$image_id] : '';
			$this->render_image_item($image_id, $image_size, $enable_lightbox, $gallery_id, 'vp-cubewp-gallery-item', $use_post_url_links, $post_url);

			echo '</div>';
			echo '</div>';
		}

		echo '</div>';
		echo '</div>';

		// Enqueue slider script
		wp_enqueue_script('cubewp-alerts');
	}

	/**
	 * Render Flex Layout
	 */
	private function render_flex($images, $settings, $image_post_urls = array())
	{
		$image_size = isset($settings['image_size']) ? $settings['image_size'] : 'medium';
		$enable_lightbox = isset($settings['enable_lightbox']) && $settings['enable_lightbox'] === 'yes';
		$use_post_url_links = isset($settings['gallery_link_type']) && $settings['gallery_link_type'] === 'post_url';
		$gallery_id = 'vp-cubewp-gallery-' . $this->get_id();

		echo '<div class="vp-cubewp-gallery-container">';
		echo '<div class="vp-cubewp-gallery-flex">';

		foreach ($images as $image_id) {
			$image_id = absint($image_id);
			if (!$image_id) {
				continue;
			}

			$post_url = isset($image_post_urls[$image_id]) ? $image_post_urls[$image_id] : '';
			$this->render_image_item($image_id, $image_size, $enable_lightbox, $gallery_id, 'vp-cubewp-gallery-item', $use_post_url_links, $post_url);
		}

		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render Advanced Grid (without slider)
	 */
	private function render_advanced_grid($images, $settings, $image_post_urls = array())
	{
		$image_size = isset($settings['image_size']) ? $settings['image_size'] : 'medium';
		$enable_lightbox = isset($settings['enable_lightbox']) && $settings['enable_lightbox'] === 'yes';
		$use_post_url_links = isset($settings['gallery_link_type']) && $settings['gallery_link_type'] === 'post_url';
		$gallery_id = 'vp-cubewp-gallery-' . $this->get_id();
		$widget_id = $this->get_id();

		// Grid settings
		$columns = isset($settings['columns_per_row']) ? intval($settings['columns_per_row']) : 5;
		$rows = isset($settings['grid_template_rows']) ? intval($settings['grid_template_rows']) : 4;
		$grid_items = isset($settings['complex_grid_items']) ? $settings['complex_grid_items'] : array();
		$items_count = isset($settings['complex_grid_items_count']) ? intval($settings['complex_grid_items_count']) : 4;

		// Build grid item styles
		$grid_item_styles = '';
		foreach ($grid_items as $index => $item) {
			if (!is_array($item)) {
				continue;
			}
			$item_class = 'vp-cubewp-gallery-grid-item-' . ($index + 1);
			$col_span = isset($item['item_grid_column_span']) ? intval($item['item_grid_column_span']) : 1;
			$row_span = isset($item['item_grid_row_span']) ? intval($item['item_grid_row_span']) : 1;
			$col_start = isset($item['item_grid_column_start']) && !empty($item['item_grid_column_start']) ? intval($item['item_grid_column_start']) : '';
			$row_start = isset($item['item_grid_row_start']) && !empty($item['item_grid_row_start']) ? intval($item['item_grid_row_start']) : '';
			$item_w = isset($item['item_grid_width']) && is_array($item['item_grid_width']) && isset($item['item_grid_width']['size']) && $item['item_grid_width']['size'] !== '' ? $item['item_grid_width']['size'] . (isset($item['item_grid_width']['unit']) ? $item['item_grid_width']['unit'] : 'px') : '';
			$item_h = isset($item['item_grid_height']) && is_array($item['item_grid_height']) && isset($item['item_grid_height']['size']) && $item['item_grid_height']['size'] !== '' ? $item['item_grid_height']['size'] . (isset($item['item_grid_height']['unit']) ? $item['item_grid_height']['unit'] : 'px') : '';

			$grid_item_styles .= '.elementor-element-' . esc_attr($widget_id) . ' .' . esc_attr($item_class) . ' {';
			$grid_item_styles .= 'grid-column: span ' . esc_attr($col_span) . ' / span ' . esc_attr($col_span) . ';';
			$grid_item_styles .= 'grid-row: span ' . esc_attr($row_span) . ' / span ' . esc_attr($row_span) . ';';
			if ($col_start) {
				$grid_item_styles .= 'grid-column-start: ' . esc_attr($col_start) . ';';
			}
			if ($row_start) {
				$grid_item_styles .= 'grid-row-start: ' . esc_attr($row_start) . ';';
			}
			if ($item_w !== '') {
				$grid_item_styles .= 'width:' . esc_attr($item_w) . ';';
			}
			if ($item_h !== '') {
				$grid_item_styles .= 'height:' . esc_attr($item_h) . ';';
			}
			$grid_item_styles .= '}';
			if ($item_w !== '' || $item_h !== '') {
				$grid_item_styles .= '.elementor-element-' . esc_attr($widget_id) . ' .' . esc_attr($item_class) . ' img { width:100%; height:100%; object-fit:cover; }';
			}
		}

		// Output styles
		echo '<style>
			.elementor-element-' . esc_attr($widget_id) . ' .vp-cubewp-gallery-complex-grid {
				display: grid;
				grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr);
				grid-template-rows: repeat(' . esc_attr($rows) . ', 1fr);
			}
			' . $grid_item_styles . '
		</style>';

		echo '<div class="vp-cubewp-gallery-container vp-gallery-component-viewer">';

		// Split images into groups based on grid items count (array_chunk requires length > 0)
		$grid_items_count = max(1, count($grid_items));
		$total_images = count($images);
		$image_groups = array_chunk($images, $grid_items_count);

		// Render each group in its own complex grid container
		foreach ($image_groups as $group_index => $image_group) {
			echo '<div class="vp-cubewp-gallery-complex-grid">';

			// Render images in this group
			foreach ($image_group as $item_index => $image_id) {
				$image_id = absint($image_id);
				if (!$image_id) {
					continue;
				}

				// Use the grid item structure if available, otherwise just render normally
				if (isset($grid_items[$item_index]) && is_array($grid_items[$item_index])) {
					$item_class = 'vp-cubewp-gallery-grid-item-' . ($item_index + 1);
					$post_url = isset($image_post_urls[$image_id]) ? $image_post_urls[$image_id] : '';
					$this->render_image_item($image_id, $image_size, $enable_lightbox, $gallery_id, 'vp-cubewp-gallery-grid-item ' . $item_class, $use_post_url_links, $post_url);
				} else {
					// If no grid item definition for this position, render without special class
					$post_url = isset($image_post_urls[$image_id]) ? $image_post_urls[$image_id] : '';
					$this->render_image_item($image_id, $image_size, $enable_lightbox, $gallery_id, 'vp-cubewp-gallery-grid-item', $use_post_url_links, $post_url);
				}
			}

			echo '</div>'; // End this complex grid
		}

		echo '</div>'; // End container
	}

	/**
	 * Render Advanced Grid with Slider (each complete grid layout is a slide)
	 */
	private function render_advanced_grid_slider($images, $settings, $image_post_urls = array())
	{
		$image_size = isset($settings['image_size']) ? $settings['image_size'] : 'medium';
		$enable_lightbox = isset($settings['enable_lightbox']) && $settings['enable_lightbox'] === 'yes';
		$use_post_url_links = isset($settings['gallery_link_type']) && $settings['gallery_link_type'] === 'post_url';
		$gallery_id = 'vp-cubewp-gallery-' . $this->get_id();
		$widget_id = $this->get_id();

		// Grid settings
		$columns = isset($settings['columns_per_row']) ? intval($settings['columns_per_row']) : 5;
		$rows = isset($settings['grid_template_rows']) ? intval($settings['grid_template_rows']) : 4;
		$grid_items = isset($settings['complex_grid_items']) ? $settings['complex_grid_items'] : array();
		$items_count = isset($settings['complex_grid_items_count']) ? intval($settings['complex_grid_items_count']) : 4;

		// Slider settings
		$autoplay = isset($settings['autoplay']) && $settings['autoplay'] === 'yes';
		$autoplay_speed = isset($settings['autoplay_speed']) ? intval($settings['autoplay_speed']) : 2000;
		$speed = isset($settings['speed']) ? intval($settings['speed']) : 500;
		$infinite = isset($settings['infinite']) && $settings['infinite'] === 'yes';
		$variable_width = isset($settings['variable_width']) && $settings['variable_width'] === 'yes';
		$enable_arrows = isset($settings['enable_arrows']) && $settings['enable_arrows'] === 'yes';
		$enable_dots = isset($settings['enable_dots']) && $settings['enable_dots'] === 'yes';
		$enable_wrap_dots_arrows = isset($settings['enable_wrap_dots_arrows']) && $settings['enable_wrap_dots_arrows'] === 'yes';
		// Get icon attributes using helper
		$icon_attrs = $this->get_slider_icon_attributes($settings);

		$slider_extra_class = '';
		$custom_class_input = isset($settings['vp_gallery_slider_custom_class']) ? trim((string) $settings['vp_gallery_slider_custom_class']) : '';
		$custom_class_input = ltrim($custom_class_input, '.');
		if (! empty($custom_class_input)) {
			$slider_extra_class = ' ' . esc_attr($custom_class_input);
		}

		$as_nav_for_attr = '';
		$enable_as_nav = isset($settings['vp_gallery_enable_as_nav']) && $settings['vp_gallery_enable_as_nav'] === 'yes';
		$nav_target_class_input = isset($settings['vp_gallery_nav_target_class']) ? trim((string) $settings['vp_gallery_nav_target_class']) : '';
		$nav_target_class_input = ltrim($nav_target_class_input, '.');
		if ($enable_as_nav && ! empty($nav_target_class_input)) {
			$as_nav_for_attr = ' data-as-nav-for="' . esc_attr('.' . $nav_target_class_input) . '"';
		}

		// Build grid item styles
		$grid_item_styles = '';
		foreach ($grid_items as $index => $item) {
			if (!is_array($item)) {
				continue;
			}
			$item_class = 'vp-cubewp-gallery-grid-item-' . ($index + 1);
			$col_span = isset($item['item_grid_column_span']) ? intval($item['item_grid_column_span']) : 1;
			$row_span = isset($item['item_grid_row_span']) ? intval($item['item_grid_row_span']) : 1;
			$col_start = isset($item['item_grid_column_start']) && !empty($item['item_grid_column_start']) ? intval($item['item_grid_column_start']) : '';
			$row_start = isset($item['item_grid_row_start']) && !empty($item['item_grid_row_start']) ? intval($item['item_grid_row_start']) : '';
			$item_w = isset($item['item_grid_width']) && is_array($item['item_grid_width']) && isset($item['item_grid_width']['size']) && $item['item_grid_width']['size'] !== '' ? $item['item_grid_width']['size'] . (isset($item['item_grid_width']['unit']) ? $item['item_grid_width']['unit'] : 'px') : '';
			$item_h = isset($item['item_grid_height']) && is_array($item['item_grid_height']) && isset($item['item_grid_height']['size']) && $item['item_grid_height']['size'] !== '' ? $item['item_grid_height']['size'] . (isset($item['item_grid_height']['unit']) ? $item['item_grid_height']['unit'] : 'px') : '';

			$grid_item_styles .= '.elementor-element-' . esc_attr($widget_id) . ' .' . esc_attr($item_class) . ' {';
			$grid_item_styles .= 'grid-column: span ' . esc_attr($col_span) . ' / span ' . esc_attr($col_span) . ';';
			$grid_item_styles .= 'grid-row: span ' . esc_attr($row_span) . ' / span ' . esc_attr($row_span) . ';';
			if ($col_start) {
				$grid_item_styles .= 'grid-column-start: ' . esc_attr($col_start) . ';';
			}
			if ($row_start) {
				$grid_item_styles .= 'grid-row-start: ' . esc_attr($row_start) . ';';
			}
			if ($item_w !== '') {
				$grid_item_styles .= 'width:' . esc_attr($item_w) . ';';
			}
			if ($item_h !== '') {
				$grid_item_styles .= 'height:' . esc_attr($item_h) . ';';
			}
			$grid_item_styles .= '}';
			if ($item_w !== '' || $item_h !== '') {
				$grid_item_styles .= '.elementor-element-' . esc_attr($widget_id) . ' .' . esc_attr($item_class) . ' img { width:100%; height:100%; object-fit:cover; }';
			}
		}

		// Split images into groups - each group will be one slide
		// Each slide will contain the full grid structure
		$images_per_slide = max(1, $items_count); // One image per grid item per slide; array_chunk() requires length > 0
		$slides = array_chunk($images, $images_per_slide);

		// Output styles
		echo '<style>
			.elementor-element-' . esc_attr($widget_id) . ' .vp-cubewp-gallery-complex-grid {
				display: grid;
				grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr);
				grid-template-rows: repeat(' . esc_attr($rows) . ', 1fr);
			}
			' . $grid_item_styles . '
		</style>';

		echo '<div class="vp-cubewp-gallery-container vp-gallery-component-viewer">';
		// The slider wraps the entire grid structure
		echo '<div class="vp-cubewp-gallery-slider elementor-element-' . esc_attr($widget_id) . $slider_extra_class . '" 
			data-slides-to-show="1" 
			data-slides-to-scroll="1" 
			data-autoplay="' . ($autoplay ? 'true' : 'false') . '" 
			data-autoplay-speed="' . esc_attr($autoplay_speed) . '" 
			data-speed="' . esc_attr($speed) . '"
			data-infinite="' . ($infinite ? 'true' : 'false') . '" 
			data-fade="false"
			data-variable-width="' . ($variable_width ? 'true' : 'false') . '"
			data-custom-arrows="' . ($enable_arrows ? 'true' : 'false') . '" 
			data-custom-dots="' . ($enable_dots ? 'true' : 'false') . '"
			data-wrap-dots-arrows="' . ($enable_wrap_dots_arrows ? 'true' : 'false') . '"
			' . $as_nav_for_attr . '
			' . $icon_attrs . '">';

		// Each slide contains the full grid structure
		foreach ($slides as $slide_index => $slide_images) {
			echo '<div class="vp-cubewp-gallery-slide">';
			echo '<div class="vp-cubewp-gallery-complex-grid">';

			// Render grid items for this slide
			foreach ($grid_items as $item_index => $item) {
				if (!is_array($item)) {
					continue;
				}

				$item_class = 'vp-cubewp-gallery-grid-item-' . ($item_index + 1);
				$image_index = $item_index;

				// Get image for this grid item (cycle through available images)
				if (isset($slide_images[$image_index])) {
					$image_id = absint($slide_images[$image_index]);
				} else {
					// If not enough images, use the first available or skip
					$image_id = isset($slide_images[0]) ? absint($slide_images[0]) : 0;
				}

				if (!$image_id) {
					continue;
				}

				$post_url = isset($image_post_urls[$image_id]) ? $image_post_urls[$image_id] : '';
				$this->render_image_item($image_id, $image_size, $enable_lightbox, $gallery_id, 'vp-cubewp-gallery-grid-item ' . $item_class, $use_post_url_links, $post_url);
			}

			echo '</div>';
			echo '</div>';
		}

		echo '</div>';
		echo '</div>';

		// Enqueue slider script
		wp_enqueue_script('cubewp-alerts');
	}

	/**
	 * Render See All button below gallery (icon + count + " see all"). Opens lightbox when clicked.
	 * Outputs hidden lightbox links for non-displayed images so the lightbox shows the full gallery.
	 *
	 * @param int   $total_count     Total gallery image count
	 * @param array $settings        Widget settings
	 * @param array $all_images      All gallery image IDs
	 * @param int   $displayed_count Number of images already displayed in the gallery
	 * @param string $gallery_id     Lightbox group ID (same as gallery images)
	 * @return void
	 */
	private function render_see_all_button($total_count, $settings, $all_images, $displayed_count, $gallery_id)
	{
		$text = isset($settings['see_all_button_text']) && $settings['see_all_button_text'] !== '' ? $settings['see_all_button_text'] : esc_html__('see all', 'valuepack-addons');
		$icon_setting = isset($settings['see_all_button_icon']) ? $settings['see_all_button_icon'] : array();

		// First image full URL for the button link (opens lightbox at first image)
		$first_id = isset($all_images[0]) ? absint($all_images[0]) : 0;
		$first_url = $first_id ? wp_get_attachment_image_url($first_id, 'full') : '';

		// Hidden links for images not displayed on the page, so the lightbox has the full set
		if ($first_url && $displayed_count < $total_count) {
			echo '<div class="vp-cubewp-gallery-see-all-hidden-links" style="position:absolute;left:-9999px;visibility:hidden;pointer-events:none;">';
			for ($i = $displayed_count; $i < $total_count; $i++) {
				$img_id = isset($all_images[$i]) ? absint($all_images[$i]) : 0;
				if (!$img_id) {
					continue;
				}
				$full_url = wp_get_attachment_image_url($img_id, 'full');
				if ($full_url) {
					echo '<a href="' . esc_url($full_url) . '" data-type="image" data-elementor-open-lightbox="no" class="cv-item vp-cubewp-gallery-link cwp-cpt-single-gallery-item"></a>';
				}
			}
			echo '</div>';
		}

		echo '<div class="vp-cubewp-gallery-see-all-wrap vp-gallery-component-viewer">';
		echo '<a href="' . esc_url($first_url) . '" data-type="image" data-elementor-open-lightbox="no" class="cv-item vp-cubewp-gallery-see-all-btn vp-cubewp-gallery-link cwp-cpt-single-gallery-item">';

		// Icon (camera or selected) - render_icon returns HTML, do not echo its return value (can be true/1)
		$icon_html = Icons_Manager::render_icon($icon_setting, array('aria-hidden' => 'true'));
		if (is_string($icon_html) && $icon_html !== '') {
			echo '<span class="vp-cubewp-gallery-see-all-icon">' . $icon_html . '</span>';
		} 
		// Count and text: "4 see all"
		echo '<span class="vp-cubewp-gallery-see-all-text">' . esc_html((int) $total_count . ' ' . $text) . '</span>';
		echo '</a>';
		foreach($all_images as $index => $image_id){
			if($index == 0){
				continue;
			}
			$image_url = wp_get_attachment_image_url($image_id, 'full');
			echo '<a href="' . esc_url($image_url) . '" data-type="image" data-elementor-open-lightbox="no" class="cv-item vp-cubewp-gallery-link cwp-cpt-single-gallery-item hidden-link" sty
			style="display:none;"></a>';
		}
		echo '</div>';
	}

	/**
	 * Get slider icon attributes
	 * Helper method to reduce code duplication
	 *
	 * @param array $settings Widget settings
	 * @return string Icon attributes HTML
	 */
	private function get_slider_icon_attributes($settings)
	{
		$prev_icon = isset($settings['prev_icon']) ? $settings['prev_icon'] : 'fas fa-chevron-left';
		$next_icon = isset($settings['next_icon']) ? $settings['next_icon'] : 'fas fa-chevron-right';

		if (function_exists('cubewp_get_svg_content')) {
			$prev_icon = cubewp_get_svg_content($prev_icon);
			$next_icon = cubewp_get_svg_content($next_icon);
		}

		// Ensure icons are strings (handle any edge cases)
		if (is_array($prev_icon)) {
			$prev_icon = isset($prev_icon['value']) ? $prev_icon['value'] : (isset($prev_icon['url']) ? $prev_icon['url'] : '');
		}
		if (is_array($next_icon)) {
			$next_icon = isset($next_icon['value']) ? $next_icon['value'] : (isset($next_icon['url']) ? $next_icon['url'] : '');
		}
		$prev_icon = is_string($prev_icon) ? $prev_icon : '';
		$next_icon = is_string($next_icon) ? $next_icon : '';

		$is_prev_svg = (is_string($prev_icon) && strpos(trim($prev_icon), '<svg') === 0);
		$is_next_svg = (is_string($next_icon) && strpos(trim($next_icon), '<svg') === 0);

		$icon_attrs = '';
		if ($is_prev_svg) {
			$icon_attrs .= " data-prev-arrow-svg='" . esc_attr($prev_icon) . "'";
			$icon_attrs .= ' data-is-prev-svg="true"';
		} else {
			$icon_attrs .= ' data-prev-arrow="' . esc_attr($prev_icon) . '"';
			$icon_attrs .= ' data-is-prev-svg="false"';
		}

		if ($is_next_svg) {
			$icon_attrs .= " data-next-arrow-svg='" . esc_attr($next_icon) . "'";
			$icon_attrs .= ' data-is-next-svg="true"';
		} else {
			$icon_attrs .= ' data-next-arrow="' . esc_attr($next_icon) . '"';
			$icon_attrs .= ' data-is-next-svg="false"';
		}

		return $icon_attrs;
	}

	/**
	 * Render single image item
	 * Helper method to reduce code duplication
	 *
	 * @param int $image_id Image attachment ID
	 * @param string $image_size Image size
	 * @param bool $enable_lightbox Whether to enable lightbox
	 * @param string $gallery_id Gallery ID for lightbox grouping
	 * @param string $wrapper_class Optional wrapper class
	 * @param bool $use_post_url_links Whether to use post URL links
	 * @param string $post_url Post URL for link mode
	 * @return void
	 */
	 	private function render_image_item($image_id, $image_size, $enable_lightbox, $gallery_id, $wrapper_class = 'vp-cubewp-gallery-item', $use_post_url_links = false, $post_url = '')
	{
		$image_id = absint($image_id);
		if (!$image_id) {
			return;
		}

		$image_url = wp_get_attachment_image_url($image_id, $image_size);
		$full_image_url = wp_get_attachment_image_url($image_id, 'full');
		$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

		if (!$image_url) {
			return;
		}

		echo '<div class="' . esc_attr($wrapper_class) . '">';

		if ($use_post_url_links && !empty($post_url)) {
			echo '<a href="' . esc_url($post_url) . '" class="vp-cubewp-gallery-post-link">';
		} elseif ($enable_lightbox) {
			echo '<a class="cv-item" data-type="image" data-title="' . esc_attr($image_alt) . '" href="' . esc_url($full_image_url) . '" data-elementor-open-lightbox="no" title="" class="vp-cubewp-gallery-link cwp-cpt-single-gallery-item">';
		}

		echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';

		if (($use_post_url_links && !empty($post_url)) || $enable_lightbox) {
			echo '</a>';
		}

		echo '</div>';
	}
	/**
	 * Output CSS for slider image width/height when set in Slider Settings
	 *
	 * @param array $settings Widget settings
	 * @return void
	 */
	private function render_slider_image_dimensions_css($settings)
	{
		$widget_id = $this->get_id();
		$selector = '.elementor-element-' . esc_attr($widget_id) . ' .vp-cubewp-gallery-slider .vp-cubewp-gallery-item img, .elementor-element-' . esc_attr($widget_id) . ' .vp-cubewp-gallery-slider .vp-cubewp-gallery-grid-item img';
		$breakpoints = array(
			''          => null,
			'_tablet'   => 1024,
			'_mobile'   => 767,
		);
		$rules = array();
		foreach ($breakpoints as $suffix => $max_width) {
			$w = isset($settings['vp_gallery_slider_image_width' . $suffix]) ? $settings['vp_gallery_slider_image_width' . $suffix] : array();
			$h = isset($settings['vp_gallery_slider_image_height' . $suffix]) ? $settings['vp_gallery_slider_image_height' . $suffix] : array();
			if (!is_array($w)) {
				$w = array('size' => '', 'unit' => 'px');
			}
			if (!is_array($h)) {
				$h = array('size' => '', 'unit' => 'px');
			}
			$size_w = isset($w['size']) && $w['size'] !== '' ? $w['size'] . (isset($w['unit']) ? $w['unit'] : 'px') : '';
			$size_h = isset($h['size']) && $h['size'] !== '' ? $h['size'] . (isset($h['unit']) ? $h['unit'] : 'px') : '';
			if ($size_w === '' && $size_h === '') {
				continue;
			}
			$decls = array();
			if ($size_w !== '') {
				$decls[] = 'width:' . esc_attr($size_w) . ';';
			}
			if ($size_h !== '') {
				$decls[] = 'height:' . esc_attr($size_h) . ';';
				if ($size_w !== '') {
					$decls[] = 'object-fit:cover;';
				}
			}
			$rule = $selector . ' { ' . implode(' ', $decls) . ' }';
			if ($max_width !== null) {
				$rule = '@media (max-width:' . (int) $max_width . 'px) { ' . $rule . ' }';
			}
			$rules[] = $rule;
		}
		if (empty($rules)) {
			return;
		}
		echo '<style>' . implode(' ', $rules) . '</style>';
	}

	 

	/**
	 * Get setting value with default
	 * Helper method for cleaner settings access
	 *
	 * @param array $settings Settings array
	 * @param string $key Setting key
	 * @param mixed $default Default value
	 * @return mixed Setting value or default
	 */
	private function get_setting($settings, $key, $default = '')
	{
		return isset($settings[$key]) ? $settings[$key] : $default;
	}
}