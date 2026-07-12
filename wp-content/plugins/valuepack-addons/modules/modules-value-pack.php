<?php

/**
 * Additional modules and features for Elementor
 * 
 * @package valuepack-addons
 * @version 1.0.0
 */

defined('ABSPATH') || exit;


/**
 * Load all module files from the modules directory
 */
$value_pack_files = array(
	'buttons',
	'tooltip',
	'slider',
	'scroll',
	'tabs',
	'popups',
	'header',
	'animated-borders',
	'sticky-section',
	'accordion-slider',
	'image',
	'icon',
	'custom-css',
	'user-visibility',
	'section-visibility',
);
if (class_exists('WooCommerce')) {
	$value_pack_files[] = 'quick-add-to-cart';
}
foreach ($value_pack_files as $value_pack_file) {
	$value_pack_file_path = trailingslashit(VALUE_PACK_PLUGIN_DIR) . 'modules/modules-value-pack-' . $value_pack_file . '.php';
	if (class_exists('\Elementor\Plugin') && file_exists($value_pack_file_path) && is_readable($value_pack_file_path)) {
		require_once wp_normalize_path($value_pack_file_path);
	}
}

/**
 * Register custom dynamic tags for Elementor
 * 
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags The dynamic tags manager instance
 */
add_action('elementor/dynamic_tags/register', function ($dynamic_tags) {
	// Register custom group for dynamic tags
	$dynamic_tags->register_group('vp-tags', [
		'title' => esc_html__('Value Pack Custom Tags', 'valuepack-addons'),
	]);

	// Include tag files
	$tag_files = array(
		'value-pack-custom-link-tag.php',
		'value-pack-product-title.php',
		'value-pack-product-terms.php',
		'value-pack-product-term-url.php',
		'value-pack-product-term-image.php',
		'value-pack-product-term-count.php',
		'value-pack-custom-logo-tag.php',
		'value-pack-copywrite-text-tag.php',
		'value-pack-post-comment-count.php',
		'value-pack-dynamic-page-title.php',
		'value-pack-dynamic-page-url.php'
	);


	foreach ($tag_files as $file) {
		$path = trailingslashit(VALUE_PACK_PLUGIN_DIR) . 'modules/tags/' . $file;

		if (file_exists($path) && is_readable($path)) {
			require_once wp_normalize_path($path);
		}
	}

	// Register custom tags
	if (function_exists('CWP')) {
		$dynamic_tags->register(new Value_Pack_Custom_Logo_Tag());
		$dynamic_tags->register(new Value_Pack_Copywrite_Text_Tag());
	}

	$dynamic_tags->register(new Value_Pack_Custom_Link_Tag());
	$dynamic_tags->register(new Value_Pack_Post_Title());
	$dynamic_tags->register(new Value_Pack_Post_Comment_Count());
	$dynamic_tags->register(new Value_Pack_Dynamic_Page_Title_Tag());
	$dynamic_tags->register(new Value_Pack_Dynamic_Page_Url_Tag());

	// Register Product Terms tag only if WooCommerce is active
	if (class_exists('WooCommerce')) {
		$dynamic_tags->register(new Value_Pack_Product_Terms());
		$dynamic_tags->register(new Value_Pack_Product_Term_URL());
		$dynamic_tags->register(new Value_Pack_Product_Term_Image());
		$dynamic_tags->register(new Value_Pack_Product_Term_Count());
	}
});

/**
 * Add settings to CubeWP general settings section
 * 
 * @param array $section_fields Existing section fields
 * @return array Modified section fields with added settings
 */
if (!function_exists('value_pack_adding_general_settings')) {
	function value_pack_adding_general_settings($section_fields)
	{
		$fields = array();

		$fields[] = array(
			'id' => 'value-pack-sitelogo',
			'title' => esc_html__('Site Logo (Home)', 'valuepack-addons'),
			'type' => 'media',
			'sanitize_callback' => 'absint',
		);

		$fields[] = array(
			'id' => 'value-pack-sitelogo-pages',
			'title' => esc_html__('Site Logo (Pages)', 'valuepack-addons'),
			'type' => 'media',
			'sanitize_callback' => 'absint',
		);

		$fields[] = array(
			'id' => 'value-pack-sitelogo-footer',
			'title' => esc_html__('Site Logo (Footer)', 'valuepack-addons'),
			'type' => 'media',
			'sanitize_callback' => 'absint',
		);

		$fields[] = array(
			'id' => 'vp-copywrite-text',
			'title' => esc_html__('Copywrite Text', 'valuepack-addons'),
			'type' => 'textarea',
			'sanitize_callback' => 'wp_kses_post',
			'default' => 'Copyright 2025 WOOMEN. All Rights Reserved.',
		);

		return array_merge((array)$section_fields, $fields);
	}

	add_filter('cubewp/settings/section/general-settings', 'value_pack_adding_general_settings');
}

/**
 * Get a setting from CubeWP options with proper handling
 * 
 * @param string $setting The setting key to retrieve
 * @param string $handle_as How to handle the return value (default|page_url|media_url)
 * @param string $find_array If handling as array, the key to find
 * @return mixed The setting value, processed according to $handle_as
 */
if (!function_exists('value_pack_get_setting')) {
	function value_pack_get_setting($setting, $handle_as = 'default', $find_array = '')
	{
		static $cwpOptions = null;

		if ($cwpOptions === null) {
			$cwpOptions = get_option('cwpOptions', array());
		}

		if (!isset($cwpOptions[$setting])) {
			return '';
		}

		$value = $cwpOptions[$setting];

		switch ($handle_as) {
			case 'page_url':
				if (is_array($value)) {
					$value = $value[$find_array] ?? false;
				}
				if (is_numeric($value)) {
					$value = get_permalink((int)$value);
				}
				break;

			case 'media_url':
				$value = wp_get_attachment_url((int)$value);
				break;

			default:
				$value = $value;
				break;
		}

		/**
		 * Filter the setting value before returning
		 * 
		 * @param mixed $value The setting value
		 * @param string $setting The setting key
		 * @param string $handle_as How the value is being handled
		 * @param string $find_array Array key if handling as array
		 */
		return apply_filters('value_pack_get_setting', $value, $setting, $handle_as, $find_array);
	}
}



/**
 * Add WooCommerce settings section
 *
 * @param array $sections Existing settings sections
 * @return array Modified settings sections
 */
if (! function_exists('value_pack_Insta_feeds_settings')) {
	function value_pack_Insta_feeds_settings($sections)
	{
		$settings['valuepack-addons'] = [
			'title'  => esc_html__('Insta Feeds', 'valuepack-addons'),
			'id'     => 'cubewp_value_pack',
			'icon'   => 'dashicons-instagram',
			'fields' => [
				[
					'id'    => 'instagram_access_token',
					'type'  => 'text',
					'title' => esc_html__('Instagram Access Token', 'valuepack-addons'),
					'desc'  => esc_html__('Enter your Instagram access token here.', 'valuepack-addons'),
				],
				[
					'id'      => 'refresh_insta_feeds',
					'title'   => esc_html__('Regenerate Instagram Feeds', 'valuepack-addons'),
					'desc'    => esc_html__('Enable this option to force ReCreate the Instagram feeds and download files again from your feeds. (Please wait, It may take few seconds.)', 'valuepack-addons'),
					'type'    => 'switch',
					'default' => '0',
				],
				[
					'id'    => 'instagram_feed_cashe_duration',
					'type'  => 'select',
					'options' => [
						'day'    => esc_html__('Day', 'valuepack-addons'),
						'week'   => esc_html__('Week', 'valuepack-addons'),
						'15_days'   => esc_html__('15 Days', 'valuepack-addons'),
						'month'   => esc_html__('Month', 'valuepack-addons'),
					],
					'default' => '15_days',
					'title' => esc_html__('Instagram Feeds Cashe Duration', 'valuepack-addons'),
					'desc'  => esc_html__('Select Instagram feeds cashe duration for your site.', 'valuepack-addons'),
				],

			]
		];

		return array_merge($sections, $settings);
	}

	add_filter('cubewp/options/sections', 'value_pack_Insta_feeds_settings', 11);
}



if (! function_exists('value_pack_after_cubewp_settings_saved')) {
	function value_pack_after_cubewp_settings_saved()
	{
		$saved_values = get_option('cwpOptions');
		if (! empty($saved_values['refresh_insta_feeds']) && $saved_values['refresh_insta_feeds'] == 1) {

			$cache_duration = isset($saved_values['instagram_feed_cashe_duration']) ? $saved_values['instagram_feed_cashe_duration'] : '15_days';
			$instagram_access_token = isset($saved_values['instagram_access_token']) ? $saved_values['instagram_access_token'] : '';
			$widget_usage = value_pack_insta_widget_usage('vp_insta_feeds');

			value_pack_update_insta_feeds_cache_settings($instagram_access_token, $cache_duration);
			$saved_values['refresh_insta_feeds'] = 0;
			update_option('cwpOptions', $saved_values);
		}
	}
	add_action('cubewp/after/settings/saved', 'value_pack_after_cubewp_settings_saved');
}
if (! function_exists('value_pack_insta_widget_usage')) {
	function value_pack_insta_widget_usage($widget_name = 'vp_insta_feeds')
	{
		global $wpdb;

		$results = $wpdb->get_results(// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			"SELECT pm.post_id, pm.meta_value 
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
             WHERE pm.meta_key = '_elementor_data'
               AND p.post_status = 'publish'"
		);

		$count         = 0;
		$settings_log  = [];
		$unique_groups = [];

		foreach ($results as $row) {
			$data = json_decode($row->meta_value, true);

			if (is_array($data)) {
				$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($data));
				foreach ($iterator as $key => $value) {
					if ($key === 'widgetType' && $value === $widget_name) {
						$count++;

						// Current widget array
						$widget_data = $iterator->getSubIterator()->getArrayCopy();
						$settings    = $widget_data['settings'] ?? [];

						$number_of_posts = !empty($settings['number_of_posts']) ? $settings['number_of_posts'] : 0;
						$hashtag_filter  = !empty($settings['hashtag_filter']) ? sanitize_title($settings['hashtag_filter']) : 'no-hashtag';

						// Log each usage
						$settings_log[] = [
							'number_of_posts' => $number_of_posts,
							'hashtag_filter'  => $hashtag_filter,
						];

						// Track unique combos
						$combo_key = $number_of_posts . '_' . $hashtag_filter;
						if (!isset($unique_groups[$combo_key])) {
							$unique_groups[$combo_key] = [
								'number_of_posts' => $number_of_posts,
								'hashtag_filter'  => $hashtag_filter,
							];
						}
					}
				}
			}
		}

		return [
			'count'    => $count,          // total active widgets found
			'settings' => $settings_log,   // all active instances
			'groups'   => $unique_groups,  // only unique combos
		];
	}
}

if (! function_exists('value_pack_update_insta_feeds_cache_settings')) {
	function value_pack_update_insta_feeds_cache_settings($access_token, $cache_duration = '15_days')
	{
		$widget_usage = value_pack_insta_widget_usage('vp_insta_feeds');

		if (empty($widget_usage['groups'])) {
			return;
		}
		$site_name = sanitize_title(get_bloginfo('name'));

		// Base cache directory (per site + per widget combo)
		$base_dir   = WP_CONTENT_DIR . '/caching/vp-insta-feeds/' . $site_name . '/';

		if (is_dir($base_dir)) {
			value_pack_rrmdir($base_dir);
		}

		foreach ($widget_usage['groups'] as $key => $group) {
			$cache_key       = 'vp_insta_feed_' . $key;
			$number_of_posts = $group['number_of_posts'];
			$hashtag_filter  = $group['hashtag_filter'];

			$cache_key = 'vp_insta_feed_' . $key;

			// Fetch + update cache
			$data = value_pack_fetch_instagram_feed(
				$access_token,
				$number_of_posts,
				($hashtag_filter !== 'no-hashtag' ? $hashtag_filter : ''),
				$cache_duration,
				$cache_key
			);

			if (!empty($data)) {
				update_option($cache_key, $data, false);
				update_option($cache_key . '_expiration', time() + DAY_IN_SECONDS, false);
			}
		}
	}
}

if (! function_exists('value_pack_update_insta_feeds_cache')) {
	function value_pack_update_insta_feeds_cache($access_token, $cache_duration = '15_days')
	{
		$widget_usage = value_pack_insta_widget_usage('vp_insta_feeds');

		if (empty($widget_usage['groups'])) {
			return;
		}

		foreach ($widget_usage['groups'] as $key => $group) {
			$cache_key       = 'vp_insta_feed_' . $key;
			$number_of_posts = $group['number_of_posts'];
			$hashtag_filter  = $group['hashtag_filter'];

			// Create unique cache key for this group
			$cache_key = 'vp_insta_feed_' . $key;
			$site_name   = sanitize_title(get_bloginfo('name'));
			$cache_dir   = WP_CONTENT_DIR . '/caching/vp-insta-feeds/' . $site_name . '/' . sanitize_title($cache_key) . '/';

			if (is_dir($cache_dir)) {
				continue;
			}

			// Fetch + update cache
			$data = value_pack_fetch_instagram_feed(
				$access_token,
				$number_of_posts,
				($hashtag_filter !== 'no-hashtag' ? $hashtag_filter : ''),
				$cache_duration,
				$cache_key
			);

			if (!empty($data)) {
				update_option($cache_key, $data, false);
				update_option($cache_key . '_expiration', time() + DAY_IN_SECONDS, false);
			}
		}
	}
}
if (! function_exists('value_pack_fetch_instagram_feed')) {
	function value_pack_fetch_instagram_feed($access_token, $number_of_posts, $hashtag_filter = '', $cache_duration = '15_days', $custom_cache_key = '')
	{
		if (empty($number_of_posts)) {
			$number_of_posts = 10;
		}

		$cache_key = !empty($custom_cache_key) ? $custom_cache_key : 'vp_instagram_feed_cache';

		$current_time = time();
		$expiration_time = 1;

		switch ($cache_duration) {
			case 'day':
				$expiration_time = DAY_IN_SECONDS;
				break;
			case 'week':
				$expiration_time = WEEK_IN_SECONDS;
				break;
			case '15_days':
				$expiration_time = DAY_IN_SECONDS * 15;
				break;
			case 'month':
				$expiration_time = MONTH_IN_SECONDS;
				break;
			case 'none':
				$expiration_time = 1;
				break;
			default:
				$expiration_time = DAY_IN_SECONDS;
		}

		$api_url = "https://graph.instagram.com/me/media?fields=id,caption,media_url,thumbnail_url,media_type,permalink,children{media_url}&limit=999999999&access_token=$access_token";
		$response = wp_remote_get($api_url);



		$profile_api_url = "https://graph.instagram.com/me?fields=username,account_type,id&access_token=$access_token";
		$responsess = wp_remote_get($profile_api_url);
		if (is_wp_error($responsess)) {
			return [];
		}



		$datass = json_decode(wp_remote_retrieve_body($responsess), true);



		if (empty($datass['username'])) {
			return [];
		}

		$username = $datass['username'];
		$profile_url = "https://instagram.com/$username";
		$results = [
			'username'     => $username,
			'profile_link' => $profile_url,
		];
		if (is_wp_error($response)) {
			return [];
		}
		$data = json_decode(wp_remote_retrieve_body($response), true);

		if (empty($data['data'])) {
			return [];
		}

		$posts = [];
		foreach ($data['data'] as $item) {

			// Stop adding posts if the limit is reached
			if (count($posts) >= $number_of_posts) {
				break;
			}

			if (!empty($hashtag_filter)) {
				$caption = strtolower($item['caption'] ?? '');      // make caption lowercase
				$filter  = strtolower('#' . $hashtag_filter);       // make filter lowercase

				if (strpos($caption, $filter) === false) {
					continue;
				}
			}
			// Handle carousel posts
			if ($item['media_type'] === 'CAROUSEL_ALBUM' && !empty($item['children']['data'])) {
				foreach ($item['children']['data'] as $child) {
					$local_url = value_pack_cache_instagram_image($child['media_url'], $cache_key);
					$posts[] = [
						'image_url' => $local_url,
						'permalink' => $item['permalink'], // Parent's permalink
					];
				}
			} else {
				$local_url = value_pack_cache_instagram_image($item['media_url'], $cache_key);
				$posts[] = [
					'image_url' => $local_url, // Cached image
					'permalink' => $item['permalink'],
				];
			}
		}
		$maxPosts = array_slice($posts, 0, $number_of_posts);
		$final_data =  array();
		$final_data = [
			'userdata' => $results,
			'feeds'    => $maxPosts,
		];

		if ($expiration_time > 0) {
			update_option($cache_key, $final_data, false);
			update_option($cache_key . '_expiration', $current_time + $expiration_time, false);
		}

		return $final_data;
	}
}


/**
 * Recursively delete a folder and its files
 */
if (!function_exists('value_pack_rrmdir')) {
	/**
	 * Recursively delete a folder and its files
	 */
	function value_pack_rrmdir( $dir ) {

		if ( ! is_dir( $dir ) ) {
			return false;
		}
	
		global $wp_filesystem;
	
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}
	
		return $wp_filesystem->delete( $dir, true );
	}
	
}


/**
 * Download and cache Instagram image locally.
 *
 * @param string $image_url
 * @return string Cached image URL
 */
if (! function_exists('value_pack_cache_instagram_image')) {
	function value_pack_cache_instagram_image($image_url, $cache_key)
	{


		// Use WordPress site name instead of domain
		$site_name = sanitize_title(get_bloginfo('name'));

		// Base cache directory (per site + per widget combo)
		$base_dir   = WP_CONTENT_DIR . '/caching/vp-insta-feeds/';
		$upload_dir = $base_dir . $site_name . '/' . sanitize_title($cache_key);



		if (!file_exists($upload_dir)) {
			wp_mkdir_p($upload_dir); // creates nested directories if missing
		}

		// Normalize URL → remove query string
		$clean_url = strtok($image_url, '?');

		// Generate filename from clean URL
		$ext = pathinfo(wp_parse_url($clean_url, PHP_URL_PATH), PATHINFO_EXTENSION);
		if (!$ext) {
			$ext = 'jpg'; // fallback
		}
		$filename  = md5($clean_url) . '.' . $ext;
		$file_path = $upload_dir . '/' . $filename;

		// If already cached, return its URL
		if (file_exists($file_path)) {
			return content_url('caching/vp-insta-feeds/' . $site_name . '/' . sanitize_title($cache_key) . '/' . $filename);
		}

		// Otherwise, download and save
		$response = wp_remote_get($image_url, ['timeout' => 15]);
		if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
			$body = wp_remote_retrieve_body($response);
			if (!empty($body)) {
				file_put_contents($file_path, $body);
			}
		}

		// Return local URL
		return content_url('caching/vp-insta-feeds/' . $site_name . '/' . sanitize_title($cache_key) . '/' . $filename);
	}
}


if (! function_exists('value_pack_check_and_update_insta_feeds_cache')) {
	function value_pack_check_and_update_insta_feeds_cache()
	{

		$saved_values = get_option('cwpOptions');
		$cache_duration = $saved_values['instagram_feed_cashe_duration'] ?? '15_days';
		$instagram_access_token = $saved_values['instagram_access_token'] ?? '';

		$widget_usage = value_pack_insta_widget_usage('vp_insta_feeds');
		if (empty($widget_usage['groups'])) {
			return;
		}

		foreach ($widget_usage['groups'] as $key => $group) {
			$cache_key = 'vp_insta_feed_' . $key;

			$site_name = sanitize_title(get_bloginfo('name'));

			// Base cache directory (per site + per widget combo)
			$base_dir   = WP_CONTENT_DIR . '/caching/vp-insta-feeds/';
			$upload_dir = $base_dir . $site_name . '/' . sanitize_title($cache_key);




			$expiration = get_option($cache_key . '_expiration', 0);
			if ($expiration < time()) {

				if (is_dir($upload_dir)) {
					value_pack_rrmdir($upload_dir); // custom recursive delete
				}
				value_pack_fetch_instagram_feed(
					$instagram_access_token,
					$group['number_of_posts'],
					($group['hashtag_filter'] !== 'no-hashtag' ? $group['hashtag_filter'] : ''),
					$cache_duration,
					$cache_key
				);
			}
		}
	}
	add_action('vp_refresh_insta_cache_event', 'value_pack_check_and_update_insta_feeds_cache');
}

// Schedule cron if not already
add_action('init', function () {
	if (!wp_next_scheduled('vp_refresh_insta_cache_event')) {
		wp_schedule_event(time(), 'twicedaily', 'vp_refresh_insta_cache_event');
	}
});



if (! function_exists('value_pack_update_insta_feed_cache_handler')) {
	function value_pack_update_insta_feed_cache_handler()
	{
		$saved_values = get_option('cwpOptions');
		$cache_duration = isset($saved_values['instagram_feed_cashe_duration']) ? $saved_values['instagram_feed_cashe_duration'] : '15_days';
		$instagram_access_token = isset($saved_values['instagram_access_token']) ? $saved_values['instagram_access_token'] : '';

		// Run your cache updater
		value_pack_update_insta_feeds_cache($instagram_access_token, $cache_duration);

		wp_send_json_success([
			'status' => 'updated',
			'message' => 'Instagram cache updated successfully.'
		]);
	}
	add_action('wp_ajax_vp_update_insta_feed_cache', 'value_pack_update_insta_feed_cache_handler');
	add_action('wp_ajax_nopriv_vp_update_insta_feed_cache', 'value_pack_update_insta_feed_cache_handler');
}
