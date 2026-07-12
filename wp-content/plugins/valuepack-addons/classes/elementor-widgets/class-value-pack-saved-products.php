<?php

/**
 * Saved Products Widget.
 *
 * This Elementor widget displays a list of WooCommerce products saved by the user (wishlist style).
 *
 * @package ValuePack
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;

/**
 * Class Value_Pack_Saved_Products
 */
class Value_Pack_Saved_Products extends Value_Pack_Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name()
	{
		return 'vp_saved_products';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title()
	{
		return esc_html__('Saved Products', 'valuepack-addons');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon()
	{
		return 'eicon-heart vpack-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories()
	{
		return array('value_pack');
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords()
	{
		return array(
			'saved',
			'wishlist',
			'products',
			'favorites',
			'woocommerce',
			'shopping',
			'cart',
			'list',
			'items',
		);
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls()
	{
		$this->start_controls_section('Cubewp_woo_settings', array(
			'label' => esc_html__('Item Settings', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		if (function_exists('cubewp_post_card_styles') && ! empty(cubewp_post_card_styles('product'))) {
			$this->add_control('layout', array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__('Card Style for Products', 'valuepack-addons'),
				'options' => cubewp_post_card_styles('product'),
				'default' => 'default_style',
			));
		}

		$this->add_control('posts_per_page', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Number of Products to Show', 'valuepack-addons'),
			'options' => array(
				'-1' => esc_html__('All Products', 'valuepack-addons'),
				'3'  => esc_html__('3 Products', 'valuepack-addons'),
				'4'  => esc_html__('4 Products', 'valuepack-addons'),
				'5'  => esc_html__('5 Products', 'valuepack-addons'),
				'6'  => esc_html__('6 Products', 'valuepack-addons'),
				'8'  => esc_html__('8 Products', 'valuepack-addons'),
				'9'  => esc_html__('9 Products', 'valuepack-addons'),
				'12' => esc_html__('12 Products', 'valuepack-addons'),
				'15' => esc_html__('15 Products', 'valuepack-addons'),
				'16' => esc_html__('16 Products', 'valuepack-addons'),
				'20' => esc_html__('20 Products', 'valuepack-addons'),
			),
			'default' => '3',
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

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$layout   = sanitize_text_field($settings['layout']);
		$per_page = intval($settings['posts_per_page']);

		$column_per_row = isset($settings['column_per_row']) ? $settings['column_per_row'] : '4';
		$column_per_row_tablet = isset($settings['column_per_row_tablet']) ? $settings['column_per_row_tablet'] : '3';
		$column_per_row_mobile = isset($settings['column_per_row_mobile']) ? $settings['column_per_row_mobile'] : '2';

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

		$saved_posts = CubeWp_Saved::cubewp_get_saved_posts();

		$args = array(
			'post_type'      => 'product',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => $per_page,
			'layout'         => $layout,
			'post__in'       => array_map('absint', (array) $saved_posts),
		);

		if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
			$args['tax_query']  = array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array('outofstock'),
					'operator' => 'NOT IN',
				),
			);
			$args['meta_query'] = array(// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => '!=',
				),
			);
		}

		self::cubewp_posts_outputs_woo($args, $posts_row_class);
	}

	/**
	 * Display saved WooCommerce products.
	 *
	 * @param array $parameters WP_Query arguments and layout.
	 */
	public static function cubewp_posts_outputs_woo($parameters, $posts_row_class = '')
	{
		$args = $parameters;

		// Override post__in if it's empty.
		if (empty($args['post__in']) || ! is_array($args['post__in'])) {
?>
			<div class="cwp-row-empty-list">
				<?php esc_html_e('No products were added to the wishlist.', 'valuepack-addons'); ?>
				<?php
				if (class_exists('WooCommerce')) {
					$shop_page_url = get_permalink(wc_get_page_id('shop'));
					echo '<a href="' . esc_url($shop_page_url) . '">' . esc_html__('Back to shopping', 'valuepack-addons') . '</a>';
				}
				?>
			</div>
		<?php
			return;
		}

		$layout = isset($args['layout']) ? sanitize_text_field($args['layout']) : 'default_style';

		$query = new WP_Query($args);

		if ($query->have_posts()) {
			if (function_exists('CWP')) {
				if ($posts_row_class) {
					add_filter('post_class', function ($classes) use ($posts_row_class) {
						$classes[] = $posts_row_class;
						return $classes;
					});
				}
				echo '<div class="cwp-row cubewp-posts-shortcode">';
			} else {
				echo '<ul class="product_list_widget">';
			}

			while ($query->have_posts()) {
				$query->the_post();
				if (function_exists('CWP')) {
					echo CubeWp_frontend_grid_HTML(get_the_ID(), '', $layout); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					wc_get_template('content-widget-product.php');
				}
			}

			wp_reset_postdata();

			if (function_exists('CWP')) {
				if ($posts_row_class) {
					remove_all_filters('post_class'); // or remove using the closure reference if needed
				}
				echo '</div>';
			} else {
				echo '</ul>';
			}
		} else {
		?>
			<div class="cwp-row-empty-list">
				<?php esc_html_e('No products were added to the wishlist.', 'valuepack-addons'); ?>
				<?php
				if (class_exists('WooCommerce')) {
					$shop_page_url = get_permalink(wc_get_page_id('shop'));
					echo '<a href="' . esc_url($shop_page_url) . '">' . esc_html__('Back to shopping', 'valuepack-addons') . '</a>';
				}
				?>
			</div>
<?php
		}
	}
}
