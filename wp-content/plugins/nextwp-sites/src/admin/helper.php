<?php
/**
 * Helper functions for NextWP Sites plugin
 * 
 * Provides utility functions for template data handling, file operations, and AJAX processing.
 * 
 * @package    NextWP_Sites
 * @subpackage Admin
 * @since      1.0.0
 */

/**
 * Retrieves single template data including theme, plugins and tags information.
 *
 * @param string $template_id Optional template ID. If empty, will try to get from $_GET['id'].
 * @return array|string Array of template data or error message if no template ID found.
 */
function nextwp_single_template_data($template_id = ''){
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $template_id = absint($_GET['id']);
        if (!$template_id) {
            return new WP_Error('invalid_id', __('Invalid template ID', 'nextwp'));
        }
    }
    if(empty($template_id)) {
        return new WP_Error('missing_id', __('No template ID found', 'nextwp'));
    }
    
    $theme = [];
    $plugins = [];
    $tags = [];
    $template_data = get_and_save_template_data($template_id);

    $template_title = $template_data['title']['rendered'] ?? '';
    $template_source_url = $template_data['cubewp_post_meta']['nwp_demo_url']['meta_value'] ?? '';
    $template_image_url = isset($template_data['featured_image_url']) ? esc_url_raw($template_data['featured_image_url']) : '';
    $required_plugins = $template_data['cubewp_post_meta']['required_items']['meta_value'] ?? [];
    $template_tags = $template_data['taxonomies'] ?? [];

    if (!empty($required_plugins)){
        foreach ($required_plugins as $key => $plugin){
            if(isset($plugin['nwp_required_type']['value']) && $plugin['nwp_required_type']['value'] == 'Theme'){
                $theme['name'] = $plugin['nwp_required_name']['value'] ?? '';
                $theme['slug'] = $plugin['nwp_required_slug']['value'] ?? '';
                $theme['source'] = $plugin['nwp_required_source']['value'] ?? '';
            }
            if(isset($plugin['nwp_required_type']['value']) && $plugin['nwp_required_type']['value'] == 'Plugin'){
                $plugins[$key]['slug'] = $plugin['nwp_required_slug']['value'] ?? '';
                $plugins[$key]['name'] = $plugin['nwp_required_name']['value'] ?? '';
                $plugins[$key]['main_file'] = $plugin['nwp_main_file']['value'] ?? '';
                $plugins[$key]['source'] = $plugin['nwp_required_source']['value'] ?? '';
            }
        }
    }

    if (!empty($template_tags)){
        $tags = $template_tags;
    }
    return [
        'title' =>  $template_title,
        'template_source' =>  $template_source_url,
        'template_image' =>  $template_image_url,
        'theme' =>  $theme,
        'plugins' =>  $plugins,
        'tags' =>  $tags,
    ];
}

/**
 * Retrieves template resources including theme, plugins, content and media URLs.
 *
 * @param string $template_id Optional template ID. If empty, will try to get from $_GET['id'].
 * @param bool $status_only Whether to return only status check.
 * @return array|string|WP_Error Array of resources, status array, or error message.
 */
function nextwp_single_resources($template_id = '', $status_only = false){
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $template_id = absint($_GET['id']);
        if (!$template_id) {
            return new WP_Error('invalid_id', __('Invalid template ID', 'nextwp'));
        }
    }
    if(empty($template_id)) {
        return new WP_Error('missing_id', __('No template ID found', 'nextwp'));
    }
    
    $theme = [];
    $plugins = [];
    $template_data = get_template_data($template_id, $status_only);

    // check if sending only status check
    $status = isset($template_data['status']) ?? '';
    if($status_only == true && !empty($status) && $status == 'success'){
        return $template_data;
    }

    // if getting complete data of template
    $template_source_url = $template_data['cubewp_post_meta']['nwp_demo_url']['meta_value'] ?? '';
    $content_url = $template_data['cubewp_post_meta']['nwp-xml-src']['meta_value'] ?? '';
    $media_url = $template_data['cubewp_post_meta']['nwp_media_files']['meta_value'] ?? '';
    $required_plugins = $template_data['cubewp_post_meta']['required_items']['meta_value'] ?? [];

    if (!empty($required_plugins)){
        foreach ($required_plugins as $key => $plugin){
            if(isset($plugin['nwp_required_type']['value']) && $plugin['nwp_required_type']['value'] == 'Theme'){
                $theme['name'] = $plugin['nwp_required_name']['value'] ?? '';
                $theme['slug'] = $plugin['nwp_required_slug']['value'] ?? '';
                $theme['source'] = $plugin['nwp_required_source']['value'] ?? '';
            }
            if(isset($plugin['nwp_required_type']['value']) && $plugin['nwp_required_type']['value'] == 'Plugin'){
                $plugins[$key]['slug'] = $plugin['nwp_required_slug']['value'] ?? '';
                $plugins[$key]['name'] = $plugin['nwp_required_name']['value'] ?? '';
                $plugins[$key]['main_file'] = $plugin['nwp_main_file']['value'] ?? '';
                $plugins[$key]['source'] = $plugin['nwp_required_source']['value'] ?? '';
            }
        }
    }

    
    return [
        'template_source' =>  $template_source_url,
        'theme' =>  $theme,
        'plugins' =>  $plugins,
        'content_url' =>  $content_url,
        'media_url' =>  $media_url,
    ];
}

/**
 * Fetches template data from the API and saves it locally if not already saved.
 *
 * @param int $template_id Template ID to fetch and save data for.
 * @return array|false Template data or false if saving failed.
 */
function get_and_save_template_data($template_id) {
    // Check if template data file already exists
    $file_path = get_template_file_path($template_id);
    if (file_exists($file_path)) {
        return json_decode(file_get_contents($file_path), true);
    }

    // Fetch data from API
    $response = wp_remote_get(NextWP_Load::$api_base_url . 'wp-json/wp/v2/library-templates/' . $template_id, array('timeout' => 60)); // Increased timeout
    if (is_wp_error($response)) {
        return false;
    }

    $template_data = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($template_data)) {
        save_template_to_file($template_data, $file_path);
        return $template_data;
    }

    return false;
}

/**
 * Fetches All templates data from the API and saves it locally if not already saved.
 *
 * @return array|false Template data or false if saving failed.
 */
function get_and_save_all_templates($is_ajax = false) {
    $file_path = get_template_file_path('all');

    // If AJAX request, delete the file and return success
    if ($is_ajax) {
        $upload_dir = wp_upload_dir();
        $template_dir = $upload_dir['basedir'] . '/nextwp-templates';
        if (is_dir($template_dir)) {
            // Remove all files in the directory
            $files = glob($template_dir . '/*'); // Get all files in the directory
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete file
                }
            }

            // Remove the directory itself
            rmdir($template_dir);
        }
        wp_send_json_success(['message' => 'Template file deleted successfully!']);
    }

    // Check if the template data file exists
    if (file_exists($file_path)) {
        return json_decode(file_get_contents($file_path), true);
    }

    // Fetch data from API
    $api_url_for_all_templates = add_query_arg(
        [
            'per_page' => '45',
        ],
        NextWP_Load::$api_base_url . 'wp-json/wp/v2/library-templates/'
    );
    $response = wp_remote_get($api_url_for_all_templates, array('timeout' => 60)); // Increased timeout
    if (is_wp_error($response)) {
        error_log('NextWP: Error fetching all templates data: ' . $response->get_error_message());
        return false; // Moved this return statement inside the if block
    }

    $template_data = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($template_data)) {
        save_template_to_file($template_data, $file_path);
        return $template_data;
    }

    return false;
}

function handle_ajax_get_and_save_templates() {
    // Verify nonce for security
    if (!check_ajax_referer('template_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => __('Nonce verification failed', 'nextwp')]);
        wp_die();
    }

    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => __('Insufficient permissions', 'nextwp')]);
        wp_die();
    }

    // Call the function with AJAX flag
    get_and_save_all_templates(true);
}
add_action('wp_ajax_get_and_save_templates', 'handle_ajax_get_and_save_templates');



/**
 * Fetches template data from the API.
 *
 * @param int $template_id Template ID to fetch and save data for.
 * @return array|false Template data or false if saving failed.
 */
function get_template_data($template_id, $status_only = false) {
    // Check if template data file already exists
    if($status_only == false){
        $exisiting_template_data = get_option($template_id . '_data');
        if (!empty($exisiting_template_data)) {
            return $exisiting_template_data;
        }
    }
    

    // Define parameters
    $site_url   = home_url('/'); // Get the site URL dynamically
    $admin_email = get_option('admin_email'); // Get the admin email dynamically
    $license_key = get_option('edd_license_key');

    // Build API URL with query parameters
    $api_url = add_query_arg(
        [
            'url'    => $site_url,
            'admin_email' => $admin_email,
            'template_id' => $template_id,
            'license_key' => $license_key,
            'status_only' => $status_only,
        ],
        NextWP_Load::$api_base_url . 'wp-json/nextwp/v1/get-template'
    );
    
    //var_dump($api_url);
    
    // Fetch data from API
    $response = wp_remote_get($api_url, array('timeout' => 60));
    if (is_wp_error($response)) {
        return false;
    }

    $template_data = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($template_data)) {
        if($status_only == false){
            update_option( $template_id . '_data', $template_data, true );
        }
        return $template_data;
    }

    return false;
}


/**
 * Saves template data to a file in the uploads directory.
 *
 * @param array $template_data Template data to save.
 * @param string $file_path File path to save the data.
 */
function save_template_to_file($template_data, $file_path) {
    $upload_dir = wp_upload_dir();
    $directory = $upload_dir['basedir'] . '/nextwp-templates';

    if (!file_exists($directory)) {
        if (!wp_mkdir_p($directory)) {
            return new WP_Error('dir_creation_failed', __('Failed to create template directory', 'nextwp'));
        }
    }

    $result = file_put_contents($file_path, json_encode($template_data), LOCK_EX);
    if ($result === false) {
        return new WP_Error('file_write_failed', __('Failed to write template file', 'nextwp'));
    }

    // Set proper file permissions
    chmod($file_path, 0644);

    return true;
}

/**
 * Generates the file path for storing template data.
 *
 * @param int $template_id Template ID.
 * @return string File path for saving template data.
 */
function get_template_file_path($template_id) {
    $upload_dir = wp_upload_dir();
    return $upload_dir['basedir'] . '/nextwp-templates/template-' . $template_id . '.json';
}

/**
 * Updates permalinks to use post name structure if not already set.
 * 
 * Forces permalinks to '/%postname%/' structure regardless of current setting.
 */
function update_plain_permalinks_to_post_name() {
    $desired_structure = '/%postname%/';
    $current_structure = get_option('permalink_structure');
    
    // Only update if current structure is different
    if ($current_structure !== $desired_structure) {
        // Update the permalink structure
        update_option('permalink_structure', $desired_structure);
        
        // Flush rewrite rules to apply the new structure
        flush_rewrite_rules();
        
        // Trigger action after update completes
        do_action('nextwp_permalinks_updated');
    }
}
// Hook this function into init (outside the function!)
add_action('init', 'update_plain_permalinks_to_post_name');


/**
 * Renames files in a specific upload directory and updates database references.
 * 
 * @param string $prefix The prefix to add to filenames.
 * @return array|WP_Error Array of renamed files or WP_Error on failure.
 */
function rename_files_in_directory($prefix) {
    // Validate prefix
    if (!is_string($prefix) || empty($prefix)) {
        return new WP_Error('invalid_prefix', __('Invalid file prefix', 'nextwp'));
    }

    // Sanitize prefix
    $prefix = sanitize_file_name($prefix);
    $upload_dir = wp_get_upload_dir();
    $folder_path = trailingslashit($upload_dir['basedir']) . '2024/12';

    if (!is_dir($folder_path)) {
        return new WP_Error('dir_not_found', __('The specified folder does not exist', 'nextwp'));
    }

    // Check directory traversal
    if (strpos(realpath($folder_path), realpath($upload_dir['basedir'])) !== 0) {
        return new WP_Error('invalid_path', __('Invalid directory path', 'nextwp'));
    }

    $files = scandir($folder_path);
    if ($files === false) {
        return new WP_Error('scan_failed', __('Failed to scan directory', 'nextwp'));
    }
    
    if (count($files) <= 2) { // accounting for . and ..
        return new WP_Error('no_files', __('No files found in the specified folder', 'nextwp'));
    }

    $renamed_files = [];
    foreach ($files as $file) {
        $file_path = $folder_path . '/' . $file;

        // Skip directories and hidden files
        if (is_dir($file_path) || substr($file, 0, 1) === '.') {
            continue;
        }

        // Check if the file is an image
        $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = $prefix . $file;
            $new_path = $folder_path . '/' . $new_filename;

            if (rename($file_path, $new_path)) {
                $renamed_files[] = ['old' => $file, 'new' => $new_filename];
            } else {
                return "Failed to rename file: $file";
            }
        } else {
            // Delete non-image files
            if (!unlink($file_path)) {
                return "Failed to delete file: $file";
            }
        }
    }

    return $renamed_files;
}


/**
 * Updates database references after renaming files.
 * 
 * @param array $renamed_files Array of renamed files with old and new names.
 * @return string|WP_Error Success message or WP_Error on failure.
 */
function update_database_with_new_filenames($renamed_files) {
    if (!is_array($renamed_files) || empty($renamed_files)) {
        return new WP_Error('invalid_input', __('Invalid renamed files array', 'nextwp'));
    }
    global $wpdb;
    
    foreach ($renamed_files as $file) {
        $old_filename = '2024/12/' . $file['old'];
        $new_filename = '2024/12/' . $file['new'];
        
        // Update `_wp_attached_file`
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE $wpdb->postmeta
                 SET meta_value = %s
                 WHERE meta_key = '_wp_attached_file' AND meta_value = %s",
                $new_filename,
                $old_filename
            )
        );
        cwp_pre($file);
        // Update `_wp_attachment_metadata`
        $attachments = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT post_id, meta_value
                 FROM $wpdb->postmeta
                 WHERE meta_key = '_wp_attachment_metadata' AND meta_value LIKE %s",
                '%' . $wpdb->esc_like($old_filename) . '%'
            )
        );

        foreach ($attachments as $attachment) {
            $meta_value = maybe_unserialize($attachment->meta_value);
            if (is_array($meta_value)) {
                // Update file field
                if (isset($meta_value['file']) && $meta_value['file'] === $old_filename) {
                    $meta_value['file'] = $new_filename;
                }

                // Update sizes if present
                if (isset($meta_value['sizes']) && is_array($meta_value['sizes'])) {
                    foreach ($meta_value['sizes'] as $size_key => $size_data) {
                        if (isset($size_data['file']) && $size_data['file'] === $file['old']) {
                            $meta_value['sizes'][$size_key]['file'] = $file['new'];
                        }
                    }
                }

                // Save updated metadata back to the database
                $wpdb->update(
                    $wpdb->postmeta,
                    ['meta_value' => maybe_serialize($meta_value)],
                    ['post_id' => $attachment->post_id, 'meta_key' => '_wp_attachment_metadata']
                );
            }
        }
    }

    return "Database updated successfully!";
}

/**
 * Updates database references after renaming files.
 * 
 * @param array $renamed_files Array of renamed files with old and new names.
 * @return string|WP_Error Success message or WP_Error on failure.
 */
/**
 * Allows SVG file uploads in WordPress with proper sanitization.
 *
 * @param array $mimes Current allowed mime types.
 * @return array Modified mime types including SVG.
 */

// Allow SVG Uploads
if (! function_exists('allow_svg_upload')) {
    function allow_svg_upload($mimes)
    {
        // Only allow SVG uploads for administrators
        if (!current_user_can('manage_options')) {
            return $mimes;
        }
        
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter('upload_mimes', 'allow_svg_upload');
}

/**
 * Sanitize SVG Files with enhanced security.
 * 
 * @param array $file The uploaded file data
 * @param string $filename The filename
 * @param array $mimes Allowed mime types
 * @param string $real_mime The real mime type
 * @return array The sanitized file data
 */
if (! function_exists('sanitize_svg')) {
    function sanitize_svg($file, $filename, $mimes, $real_mime)
    {
        // Only process SVG files
        if ($file['type'] !== 'image/svg+xml') {
            return $file;
        }
        
        // Check if user has permission to upload SVGs
        if (!current_user_can('manage_options')) {
            $file['error'] = UPLOAD_ERR_INI_SIZE; // Set error to prevent upload
            return $file;
        }
        
        // Additional security check - verify file content
        $file_content = file_get_contents($file['tmp_name']);
        if ($file_content === false) {
            $file['error'] = UPLOAD_ERR_INI_SIZE;
            return $file;
        }
        
        // Check for potentially dangerous content
        $dangerous_patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i'
        ];
        
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $file_content)) {
                error_log('NextWP: Potentially dangerous SVG content detected: ' . $filename);
                $file['error'] = UPLOAD_ERR_INI_SIZE;
                return $file;
            }
        }
        
        // Change mime type to text/plain for additional security
        $file['type'] = 'text/plain';
        
        return $file;
    }
    add_filter('wp_check_filetype_and_ext', 'sanitize_svg', 10, 4);
}

/**
 * Automatically clear rate limits based on time when accessing admin.
 * This function runs on admin_init to automatically clear expired rate limits.
 */
function nextwp_auto_clear_rate_limits() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Only run this check once per session to avoid performance issues
    if (get_transient('nextwp_rate_limit_check_done')) {
        return;
    }
    
    // Set a flag to prevent multiple checks in the same session
    set_transient('nextwp_rate_limit_check_done', true, 300); // 5 minutes
    
    // Get current time
    $current_time = current_time('timestamp');
    
    // Define rate limit transients to check
    $transients_to_check = [
        'nextwp_import_rate_limit_content_import',
        'nextwp_import_rate_limit_theme_install',
        'nextwp_import_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_theme_install',
        'nextwp_setup_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_content_import'
    ];
    
    $cleared_count = 0;
    
    foreach ($transients_to_check as $transient_key) {
        $transient_data = get_transient($transient_key);
        
        if ($transient_data !== false) {
            // Check if the transient has been active for more than 2 hours
            $last_activity_key = $transient_key . '_last_activity';
            $last_activity = get_transient($last_activity_key);
            
            if ($last_activity === false) {
                // First time seeing this transient, set the activity time
                set_transient($last_activity_key, $current_time, DAY_IN_SECONDS);
            } else {
                // Check if more than 2 hours have passed since last activity
                if (($current_time - $last_activity) > (2 * HOUR_IN_SECONDS)) {
                    // Clear the rate limit and reset activity time
                    delete_transient($transient_key);
                    set_transient($last_activity_key, $current_time, DAY_IN_SECONDS);
                    $cleared_count++;
                    
                    error_log("NextWP: Auto-cleared expired rate limit: {$transient_key}");
                }
            }
        }
    }
    
    if ($cleared_count > 0) {
        error_log("NextWP: Auto-cleared {$cleared_count} expired rate limits");
    }
}

// Hook to run on admin initialization
add_action('admin_init', 'nextwp_auto_clear_rate_limits');

/**
 * Clear all rate limits when user accesses NextWP admin page.
 * This provides immediate relief when users return to the admin.
 */
function nextwp_clear_rate_limits_on_admin_access() {
    // Only run on NextWP admin pages
    if (!isset($_GET['page']) || $_GET['page'] !== 'nextwp-sites') {
        return;
    }
    
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Check if user has been away for more than 30 minutes
    $last_access_key = 'nextwp_last_admin_access_' . get_current_user_id();
    $last_access = get_transient($last_access_key);
    $current_time = current_time('timestamp');
    
    if ($last_access === false || ($current_time - $last_access) > (30 * MINUTE_IN_SECONDS)) {
        // User has been away for more than 30 minutes, clear rate limits
        $transients_to_clear = [
            'nextwp_import_rate_limit_content_import',
            'nextwp_import_rate_limit_theme_install',
            'nextwp_import_rate_limit_plugin_install',
            'nextwp_setup_rate_limit_theme_install',
            'nextwp_setup_rate_limit_plugin_install',
            'nextwp_setup_rate_limit_content_import'
        ];
        
        $cleared_count = 0;
        foreach ($transients_to_clear as $transient) {
            if (delete_transient($transient)) {
                $cleared_count++;
            }
        }
        
        if ($cleared_count > 0) {
            error_log("NextWP: Cleared {$cleared_count} rate limits for returning admin user: " . get_current_user_id());
        }
        
        // Update last access time
        set_transient($last_access_key, $current_time, DAY_IN_SECONDS);
    }
}

// Hook to run when accessing NextWP admin pages
add_action('admin_init', 'nextwp_clear_rate_limits_on_admin_access');

/**
 * Enhanced rate limit clearing with time-based logic.
 * This function can be called manually or through other means.
 */
function nextwp_smart_clear_rate_limits() {
    if (!current_user_can('manage_options')) {
        return false;
    }
    
    $current_time = current_time('timestamp');
    $transients_to_check = [
        'nextwp_import_rate_limit_content_import',
        'nextwp_import_rate_limit_theme_install',
        'nextwp_import_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_theme_install',
        'nextwp_setup_rate_limit_plugin_install',
        'nextwp_setup_rate_limit_content_import'
    ];
    
    $cleared_count = 0;
    $total_checked = 0;
    
    foreach ($transients_to_check as $transient_key) {
        $total_checked++;
        $transient_data = get_transient($transient_key);
        
        if ($transient_data !== false) {
            // Check if this is an old rate limit (more than 1 hour)
            $last_activity_key = $transient_key . '_last_activity';
            $last_activity = get_transient($last_activity_key);
            
            if ($last_activity === false || ($current_time - $last_activity) > HOUR_IN_SECONDS) {
                // Clear old rate limit
                delete_transient($transient_key);
                set_transient($last_activity_key, $current_time, DAY_IN_SECONDS);
                $cleared_count++;
            }
        }
    }
    
    error_log("NextWP: Smart rate limit clearing - checked {$total_checked}, cleared {$cleared_count}");
    
    return $cleared_count;
}

/**
 * Get current rate limit status for debugging (admin only).
 */
function nextwp_get_rate_limit_status() {
    if (!current_user_can('manage_options')) {
        return ['error' => 'Insufficient permissions'];
    }
    
    $status = [];
    $current_time = current_time('timestamp');
    
    $transients_to_check = [
        'content_import' => 'nextwp_import_rate_limit_content_import',
        'theme_install' => 'nextwp_setup_rate_limit_theme_install',
        'plugin_install' => 'nextwp_setup_rate_limit_plugin_install'
    ];
    
    foreach ($transients_to_check as $operation => $transient_key) {
        $attempts = get_transient($transient_key) ?: 0;
        $limit = ($operation === 'content_import') ? 50 : 20;
        $last_activity_key = $transient_key . '_last_activity';
        $last_activity = get_transient($last_activity_key);
        
        $status[$operation] = [
            'attempts' => $attempts,
            'limit' => $limit,
            'remaining' => max(0, $limit - $attempts),
            'is_limited' => $attempts >= $limit,
            'last_activity' => $last_activity ? date('Y-m-d H:i:s', $last_activity) : 'Never',
            'time_since_activity' => $last_activity ? human_time_diff($last_activity, $current_time) : 'N/A'
        ];
    }
    
    return $status;
}