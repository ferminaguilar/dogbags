<?php

/**
 * Value Pack Recent Posts Widget
 *
 * A custom widget that displays recent posts with thumbnails.
 *
 * @package valuepack-addons
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Recent Posts Widget class.
 */
class Value_Pack_Recent_Posts_Widget extends WP_Widget
{

	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'vp_recent_posts_widget',
			esc_html__('Value Pack Recent Posts', 'valuepack-addons'),
			array(
				'description' => esc_html__('Displays recent posts with thumbnails', 'valuepack-addons'),
				'classname'   => 'vp-recent-posts-widget',
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		// Extract and sanitize widget options
		$order = !empty($instance['order']) ? sanitize_key($instance['order']) : 'DESC';
		$num_posts = !empty($instance['num_posts']) ? absint($instance['num_posts']) : 3;
		$show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : true;

		// Before widget output (safe to echo)
		echo wp_kses_post($args['before_widget']);

		// Widget title
		if (!empty($instance['title'])) {
			$title_text = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
			echo wp_kses_post($args['before_title']);
			echo esc_html($title_text);
			echo wp_kses_post($args['after_title']);
		}

		// Query arguments
		$query_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $num_posts,
			'order'               => $order,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		$recent_posts = new WP_Query($query_args);

		if ($recent_posts->have_posts()) :
			echo '<div class="vp-recent-posts-list">';

			while ($recent_posts->have_posts()) : $recent_posts->the_post();
				$post_id = get_the_ID();
				$thumbnail = value_pack_get_post_featured_image($post_id);
?>
				<article class="vp-recent-post">
					<?php if ($thumbnail) : ?>
						<div class="vp-recent-post-thumbnail">
							<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
								<img
									loading="lazy"
									src="<?php echo esc_url($thumbnail); ?>"
									alt="<?php echo esc_attr(get_the_title()); ?>"
									width="300"
									height="200">
							</a>
						</div>
					<?php endif; ?>

					<div class="vp-recent-post-content">
						<?php if ($show_date) : ?>
							<time class="vp-recent-post-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
								<?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'valuepack-addons')); ?>
							</time>
						<?php endif; ?>

						<h3 class="vp-recent-post-title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h3>
					</div>
				</article>
		<?php
			endwhile;

			echo '</div>';
			wp_reset_postdata();
		else :
			echo '<p class="no-posts-found">' . esc_html__('No posts found', 'valuepack-addons') . '</p>';
		endif;

		// After widget output (safe to echo)
		echo wp_kses_post($args['after_widget']);
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$defaults = array(
			'title'     => esc_html__('Recent Posts', 'valuepack-addons'),
			'num_posts' => 3,
			'order'     => 'DESC',
			'show_date' => true,
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$title = sanitize_text_field($instance['title']);
		$num_posts = absint($instance['num_posts']);
		$order = sanitize_key($instance['order']);
		$show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : true;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
				<?php esc_html_e('Title:', 'valuepack-addons'); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
				name="<?php echo esc_attr($this->get_field_name('title')); ?>"
				type="text"
				value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('num_posts')); ?>">
				<?php esc_html_e('Number of posts to show:', 'valuepack-addons'); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('num_posts')); ?>"
				name="<?php echo esc_attr($this->get_field_name('num_posts')); ?>"
				type="number"
				step="1"
				min="1"
				value="<?php echo esc_attr($num_posts); ?>"
				size="3" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('order')); ?>">
				<?php esc_html_e('Order:', 'valuepack-addons'); ?>
			</label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>"
				name="<?php echo esc_attr($this->get_field_name('order')); ?>">
				<option value="DESC" <?php selected($order, 'DESC'); ?>>
					<?php esc_html_e('Newest first', 'valuepack-addons'); ?>
				</option>
				<option value="ASC" <?php selected($order, 'ASC'); ?>>
					<?php esc_html_e('Oldest first', 'valuepack-addons'); ?>
				</option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox"
				id="<?php echo esc_attr($this->get_field_id('show_date')); ?>"
				name="<?php echo esc_attr($this->get_field_name('show_date')); ?>"
				<?php checked($show_date); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>">
				<?php esc_html_e('Display post date?', 'valuepack-addons'); ?>
			</label>
		</p>
<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['num_posts'] = absint($new_instance['num_posts']);
		$instance['order'] = in_array($new_instance['order'], array('ASC', 'DESC')) ? $new_instance['order'] : 'DESC';
		$instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;

		return $instance;
	}
}
