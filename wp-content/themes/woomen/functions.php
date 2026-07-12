<?php
defined('ABSPATH') || exit;

/**
 * WOOMEN_VERSION is defined for current Woomen version
 */
if (! defined('WOOMEN_VERSION')) {
	define('WOOMEN_VERSION', '1.0.3');
}

/**
 * WOOMEN_PATH Defines for load PHP files
 */
if (! defined('WOOMEN_PATH')) {
	define('WOOMEN_PATH', get_template_directory() . '/');
}

/**
 * WOOMEN_URL Defines for load JS and CSS files
 */
if (! defined('WOOMEN_URL')) {
	define('WOOMEN_URL', get_template_directory_uri() . '/');
}

/**
 * @function if_theme_can_load
 *
 * Check cubewp woomen plugin for Woomen Theme.
 *
 * @return bool
 */
if (!function_exists('if_theme_can_load')) {
	function if_theme_can_load()
	{
		return true;
	}
}

/**
 * @function if_cubewp_can_load
 *
 * Check all required plugins for Woomen Theme.
 *
 * @param bool $check_frontend Also Check CubeWP Frontend Pro.
 *
 * @return bool
 */
if (! function_exists('if_cubewp_can_load')) {

	function if_cubewp_can_load($check_frontend = false)
	{
		if (! $check_frontend) {
			if (function_exists('CWP')) {
				return true;
			}
		} else {
			if (function_exists('CWP')) {
				return true;
			}
		}
		return false;
	}
}

/**
 * @function woomen_theme_support
 *
 * Add Woomen Theme features support.
 */
if (! function_exists('woomen_theme_support')) {
	function woomen_theme_support()
	{
		global $content_width;
		add_theme_support('title-tag');
		add_theme_support('automatic-feed-links');
		add_theme_support('widgets');
		add_theme_support('html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
			'navigation-widgets'
		));
		add_theme_support('post-thumbnails');
		add_image_size('woomen-grid', 340, 195, true);

		if (! isset($content_width)) {
			$content_width = 900;
		}
	}

	add_filter("after_setup_theme", "woomen_theme_support", 11);
}

/**
 * @function woomen_register_sidebar_widget_area
 *
 * Register Default Sidebar.
 */
if (! function_exists('woomen_register_sidebar_widget_area')) {
	function woomen_register_sidebar_widget_area()
	{
		register_sidebar(array(
			'name'          => esc_html__("Default Sidebar", "woomen"),
			'id'            => 'woomen_default_sidebar',
			'before_widget' => '<div class="woomen-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>'
		));
	}

	add_action('widgets_init', 'woomen_register_sidebar_widget_area');
}

if (! function_exists('woomen_register_menu_locations')) {
	function woomen_register_menu_locations()
	{
		$menu_locations = array(
			'woomen_home_header'     => esc_html__("Primary Menu", "woomen"),
		);

		register_nav_menus($menu_locations);
	}

	add_filter("init", "woomen_register_menu_locations", 11);
}

/**
 * @function get_woomen_menus
 *
 * Return menus.
 */
if (!function_exists('get_woomen_menus')) {
	function get_woomen_menus($menu_location, $class = '')
	{

		$woomen_menus = wp_nav_menu(
			array(
				'theme_location' => $menu_location,
				'menu_class'     => $class,
				'container'      => false,
				'echo'           => false,
			)
		);
		return $woomen_menus;
	}
}

/**
 * @function woomen_get_site_logo_url
 *
 * Return site logo image url.
 */
if (! function_exists('woomen_get_site_logo_url')) {
	function woomen_get_site_logo_url()
	{
		$logo_url = '';
		if (if_cubewp_can_load()) {
			if (is_front_page() || is_home()) {
				$logo_url = woomen_get_setting('home_page_logo', 'media_url');
			} else {
				$logo_url = woomen_get_setting('inner_pages_logo', 'media_url');
			}
		}
		if (empty($logo_url)) {
			$logo_url = WOOMEN_URL . 'assets/images/logo.png';
		}

		return $logo_url;
	}
}

/**
 * @function woomen_get_setting
 *
 * Return settings from CubeWP Settings.
 */
/**
 * Get theme setting from CubeWP options
 *
 * @param string $setting Setting name
 * @param string $handle_as How to handle the setting (default|page_url|media_url)
 * @param string $find_array Array key to find if setting is array
 * @return mixed Setting value
 */
if (! function_exists('woomen_get_setting')) {
	function woomen_get_setting($setting, $handle_as = 'default', $find_array = '')
	{
		global $cwpOptions;

		if (empty($cwpOptions) || ! is_array($cwpOptions)) {
			$cwpOptions = get_option('cwpOptions');
		}

		$return = '';
		$setting = sanitize_text_field($setting);
		$handle_as = sanitize_text_field($handle_as);
		$find_array = sanitize_text_field($find_array);

		if ($handle_as == 'default') {
			$return = isset($cwpOptions[$setting]) ? $cwpOptions[$setting] : '';
		} elseif ($handle_as == 'page_url') {
			$return = isset($cwpOptions[$setting]) ? $cwpOptions[$setting] : false;
			if (is_array($return)) {
				$return = isset($return[$find_array]) ? $return[$find_array] : false;
			}
			if (is_numeric($return)) {
				$return = get_permalink(absint($return));
			}
		} elseif ($handle_as == 'media_url') {
			$return = isset($cwpOptions[$setting]) ? $cwpOptions[$setting] : '';
			if (is_numeric($return)) {
				$return = wp_get_attachment_url(absint($return));
			}
		}

		return apply_filters('woomen_get_setting', $return, $setting, $handle_as, $find_array);
	}
}


if (! function_exists('woomen_get_post_featured_image')) {
	function woomen_get_post_featured_image($post_id = 0, $id_only = false, $size = 'medium')
	{
		$return = '';
		if (! $post_id) {
			$post_id = get_the_ID();
		}
		$post_loop_image = get_post_meta($post_id, 'post_loop_image', true);
		if (!empty($post_loop_image)) {
			$return = wp_get_attachment_url($post_loop_image);
		} elseif (has_post_thumbnail($post_id)) {
			if ($id_only) {
				$return = get_post_thumbnail_id($post_id);
			} else {
				$return = get_the_post_thumbnail_url($post_id, $size);
			}
		} else {
			$gallery = get_post_meta($post_id, 'woomen_gallery', true);
			$gallery = $gallery['meta_value'] ?? '';
			if (! empty($gallery) && is_array($gallery)) {
				foreach ($gallery as $galleryItemID) {
					if ($id_only) {
						$return = $galleryItemID;
					} else {
						$return = wp_get_attachment_url($galleryItemID);
					}
					break;
				}
			}
		}

		if (empty($return)) {
			$return = woomen_get_setting('default_featured_image', 'media_url');
		}

		if (empty($return)) {
			$return = WOOMEN_URL . 'assets/images/placeholder.png';
		}
		return $return;
	}
}

/**
 * @function woomen_after_theme_setup
 *
 * Remove admin bar for roles which caps are lower or equal to subscriber
 */
if (! function_exists('woomen_after_theme_setup')) {
	function woomen_after_theme_setup()
	{

		// WooCommerce in general.
		add_theme_support('woocommerce');
		// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
		// zoom.
		add_theme_support('wc-product-gallery-zoom');
		// lightbox.
		add_theme_support('wc-product-gallery-lightbox');
		// swipe.
		add_theme_support('wc-product-gallery-slider');
		// Add support for wide alignment.
		add_theme_support('align-wide');
		if (!isset($content_width)) {
			$content_width = 900;
		}
		add_theme_support("wp-block-styles");
		add_theme_support("responsive-embeds");
		add_theme_support('custom-logo', array(
			'height'      => 35,
			'width'       => 200,
			'flex-width'  => true,
			'flex-height' => true,
		));
		add_theme_support('custom-header', array(
			'default-image' => '',
			'width'         => 1200,
			'height'        => 400,
			'flex-height'   => true,
			'flex-width'    => true,
			'default-text-color' => '000',
			'header-text'   => true,
		));
		add_theme_support('custom-background', array(
			'default-color' => 'ffffff',
			'default-image' => ''
		));
		add_editor_style('editor-style.css');

		add_theme_support('core-block-patterns');
		register_block_pattern(
			'woomen/sample-pattern',
			array(
				'title'       => __('Woomen Sample Pattern', 'woomen'),
				'description' => __('A sample block pattern.', 'woomen'),
				'content'     => "<!-- wp:paragraph --><p>" . __('This is a sample block pattern.', 'woomen') . "</p><!-- /wp:paragraph -->",
			)
		);
		add_theme_support('wp-block-styles');
		register_block_style(
			'core/paragraph',
			array(
				'name'         => 'custom-style',
				'label'        => __('Custom Style', 'woomen'),
				'style_handle' => 'custom-style', // You should define this style in your theme's style.css
			)
		);
	}

	add_action('after_setup_theme', 'woomen_after_theme_setup');
}




/**
 * All Woomen classes files to be loaded automatically.
 *
 * @param string $className Class name.
 */

if (! function_exists('woomen_autoload_classes')) {
	function woomen_autoload_classes($className)
	{
		if (! str_contains($className, 'Woomen')) {
			return null;
		}
		$file_name = str_replace('_', '-', strtolower($className));
		$files = array(
			WOOMEN_PATH . 'classes/class-' . $file_name . '.php',
		);
		foreach ($files as $file) {
			if (file_exists($file)) {
				require $file;
			}
		}
	}

	spl_autoload_register('woomen_autoload_classes');
}

/**
 * Class Woomen: Loads Woomen theme configurations.
 *
 * @since  1.0.0
 */
if (!function_exists('woomen')) {
	function woomen()
	{
		return Woomen::instance();
	}

	woomen();
}

/**
 * Return google font api url for given google font families.
 *
 * @param array $families.
 *
 * @since  1.0.11
 */
if (!function_exists('woomen_build_google_font_api_url')) {
	function woomen_build_google_font_api_url()
	{
		$upload_dir = wp_upload_dir(); // Get upload directory in WordPress.
		if (file_exists($upload_dir['basedir'] . '/woomen-google-fonts/fonts.css')) {
			return $upload_dir['baseurl'] . '/woomen-google-fonts/fonts.css'; // Path to the locally saved CSS.
		} else {
			$fonts_url = woomen_google_fonts();
			return $fonts_url;
		}
	}
}

/**
 * Download Google fonts and save them to the uploads directory.
 *
 * @param array $families Array of font families.
 */
if (!function_exists('woomen_update_fonts')) {
	function woomen_update_fonts($saved)
	{
		if (isset($_POST['activeTab']) && $_POST['activeTab'] == 'woomen_typography' && $saved == 'saved') {
			$families = woomen_font_families();
			woomen_download_google_fonts($families);
		}
	}
	add_action('cubewp/after/settings/saved', 'woomen_update_fonts', 10, 1);
}

if (!function_exists('woomen_font_families')) {
	function woomen_font_families()
	{
		$typos           = array(
			'body'  => true,
			'h1'    => true,
			'h2'    => true,
			'h3'    => true,
			'h4'    => true,
			'h5'    => true,
			'h6'    => true,
			'p'     => true,
			'span'  => true,
			'label' => true,
			'a'     => true,
			'p-sm'  => false,
			'p-md'  => false,
			'p-lg'  => false
		);
		$enqueue_families = array();
		foreach ($typos as $tag => $is_tag) {
			$setting_id = 'typography-' . $tag;
			$settings   = woomen_get_setting($setting_id);
			if (isset($settings["font-family"]) && ! empty($settings["font-family"])) {
				$font_family     = $settings["font-family"];
				if (! in_array($font_family, $enqueue_families)) {
					$enqueue_families[] = $font_family;
				}
			}
		}
		return $enqueue_families;
	}
}

if (!function_exists('woomen_google_fonts')) {
	function woomen_google_fonts()
	{
		$families = woomen_font_families();
		$upload_dir = wp_upload_dir(); // Get upload directory in WordPress.
		$font_dir = $upload_dir['basedir'] . '/woomen-google-fonts/'; // Path to the fonts folder.

		// Create the fonts directory if it doesn't exist.
		if (!file_exists($font_dir)) {
			wp_mkdir_p($font_dir);
		}

		// Build the Google Fonts URL.
		foreach ($families as &$family) {
			$family = str_replace(' ', '+', $family) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
		}
		$fonts_url = sprintf('https://fonts.googleapis.com/css?family=%1$s%2$s', implode(rawurlencode('|'), $families), '&display=swap');
		$subsets = array(
			'cs_CZ' => 'latin-ext',
			'ro_RO' => 'latin-ext',
			'pl_PL' => 'latin-ext',
			'sk_SK' => 'latin-ext',
			'hr_HR' => 'latin-ext',
			'hu_HU' => 'latin-ext',
			'tr_TR' => 'latin-ext',
			'lt_LT' => 'latin-ext',
			'el'    => 'greek',
			'uk'    => 'cyrillic',
			'vi'    => 'vietnamese',
			'ru_RU' => 'cyrillic',
			'bg_BG' => 'cyrillic',
			'he_IL' => 'hebrew',
		);
		$subsets = apply_filters('woomen/google_font/subsets', $subsets);
		$locale = get_locale();
		if (isset($subsets[$locale])) {
			$fonts_url .= '&subset=' . $subsets[$locale];
		}
		return $fonts_url;
	}
}


// woomen-comment-overwrite
if (! function_exists('woomen_post_comments')) {
	function woomen_post_comments($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<div class="comment-image">
				<?php echo get_avatar($comment, 48); ?>
			</div>
			<div class="comment-box-content">
				<div class="comment-author vcard">
					<div class="comment-title-date">
						<?php printf(__('<h3 class="fn">%s</h3>', 'woomen'), get_comment_author_link()); ?>
						<a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
							<?php printf(__('%1$s at %2$s', 'woomen'), get_comment_date(), get_comment_time()); ?>
						</a>
					</div>
					<?php edit_comment_link(__('Edit', 'woomen'), '  ', ''); ?>
				</div>
				<?php if ($comment->comment_approved == '0') : ?>
					<em><?php esc_html_e('Your comment is awaiting moderation.', 'woomen'); ?></em>
					<br />
				<?php endif; ?>
				<div class="comment-text-reply">
					<?php comment_text(); ?>
					<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div>
			</div>
		</li>
	<?php
	}
}

