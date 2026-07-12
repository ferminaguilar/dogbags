<?php

class NextWP_Import {

    private $targetdir;
    private $nextcall;
    private $template_id;
    private $media_url;
    private $template_name;

    public function __construct() {
        // Hook into the 'wp_import_insert_post' action
        add_action('wp_import_insert_post', [$this, 'handle_imported_post'], 10, 4);

        // Hook into the 'wp_import_existing_post' filter
        add_filter('wp_import_existing_post', [$this, 'handle_existing_imported_post'], 10, 2);

        // Load WooCommerce import functionality if WooCommerce is active
        if (class_exists('WooCommerce')) {
            require_once __DIR__ . '/class-nextwp-wc-import.php';
        }
    }

    /**
     * Method nwp_import_dummy_content
     *
     * @return void
     * @since  1.0.0
     */
    public function nwp_import_files($setup = false,$content = false, $dynamic_data = false){
        if($setup == true){
            return '/cwp-setup.json';
        }else if($content == true){
            return '/content.xml';
        }else if($dynamic_data == true){
            return array(
                '/cwp_user_groups.json',
                '/cwp_post_groups.json',
                '/cwp_custom_forms.json',
            );
        }
    }

    public function import_cubewp_framework_not_installed() {
        if ( !class_exists('CubeWp_Import') ) {
            return array(
                'status' => 'failed',
                'message'     => esc_html__( "CubeWP Framework is not installed or active", 'nextwp' )
            );
        }
    }

    /**
     * Check if import operations should be rate limited.
     *
     * @param string $operation The operation being performed
     * @return bool Whether the operation should be rate limited
     */
    private function should_rate_limit_import($operation) {
        // Skip rate limiting for administrators in development mode
        if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
            return false;
        }
        
        $rate_limit_key = 'nextwp_import_rate_limit_' . $operation;
        $max_attempts = 50; // Allow 50 attempts per hour (more reasonable for development)
        $rate_limit = get_transient($rate_limit_key);
        
        // Check if this is an old rate limit (more than 2 hours)
        $last_activity_key = $rate_limit_key . '_last_activity';
        $last_activity = get_transient($last_activity_key);
        $current_time = current_time('timestamp');
        
        if ($last_activity && ($current_time - $last_activity) > (2 * HOUR_IN_SECONDS)) {
            // Clear old rate limit
            delete_transient($rate_limit_key);
            delete_transient($last_activity_key);
            $rate_limit = 0;
        }
        
        if ($rate_limit && $rate_limit >= $max_attempts) {
            error_log('NextWP: Import rate limit exceeded for operation: ' . $operation);
            return true;
        }
        
        return false;
    }

    /**
     * Track import operation attempt for rate limiting.
     *
     * @param string $operation The operation being performed
     */
    private function track_import_attempt($operation) {
        $rate_limit_key = 'nextwp_import_rate_limit_' . $operation;
        $last_activity_key = $rate_limit_key . '_last_activity';
        
        $attempts = get_transient($rate_limit_key) ?: 0;
        $current_time = current_time('timestamp');
        
        set_transient($rate_limit_key, $attempts + 1, HOUR_IN_SECONDS);
        set_transient($last_activity_key, $current_time, DAY_IN_SECONDS);
    }

    /**
     * Reset rate limiting for a specific operation (admin only).
     *
     * @param string $operation The operation to reset rate limiting for
     * @return bool Whether the reset was successful
     */
    public function reset_rate_limit($operation = '') {
        if (!current_user_can('manage_options')) {
            return false;
        }
        
        if (empty($operation)) {
            // Reset all rate limits
            $operations = ['content_import', 'theme_install', 'plugin_install'];
            foreach ($operations as $op) {
                $rate_limit_key = 'nextwp_import_rate_limit_' . $op;
                delete_transient($rate_limit_key);
            }
        } else {
            // Reset specific operation
            $rate_limit_key = 'nextwp_import_rate_limit_' . $operation;
            delete_transient($rate_limit_key);
        }
        
        return true;
    }

    public function import_template_content($template_id = '', $nextcall = '') {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            error_log('NextWP: Unauthorized import attempt by user: ' . get_current_user_id());
            return array(
                'status' => 'failed',
                'message' => esc_html__('Insufficient permissions to perform this operation.', 'nextwp')
            );
        }
        
        // Rate limiting check
        if ($this->should_rate_limit_import('content_import')) {
            return array(
                'status' => 'failed',
                'message' => esc_html__('Too many import attempts. Please try again in an hour.', 'nextwp')
            );
        }
        
        // Track this attempt
        $this->track_import_attempt('content_import');
        
        // Check if template ID is provided
        if (empty($template_id)) {
            return array(
                'status' => 'failed',
                'message' => esc_html__('There is no template selected.', 'nextwp')
            );
        }
    
        // Static ZIP file URL
        $template_id = intval($template_id);
        $template = nextwp_single_resources($template_id);
        
        if (!is_array($template)) {
            error_log('NextWP: Failed to get template resources for ID: ' . $template_id);
            return array(
                'status' => 'failed',
                'message' => esc_html__('Failed to retrieve template information.', 'nextwp')
            );
        }
        
        $zip_url = $template['content_url'];
        $media_url = $template['media_url'];
        $template_data = nextwp_single_template_data($template_id);

        $this->targetdir = $targetdir = $this->download_resources($zip_url);
        $this->nextcall = $nextcall;
        $this->template_id = $template_id;
        $this->media_url = $media_url;
        $this->template_name = $template_data['title'];

        if (empty($targetdir)) {
            return array(
                'status' => 'failed',
                'message' => esc_html__('There is problem to load content file.', 'nextwp')
            );
        }

        // check if it is first call for cubewp settings
        if($nextcall == null){
            return $this->import_cubewp_setup();
        }

        // check if it is call for cubewp dynamic data
        if($nextcall == 'dynamic_data'){
            return $this->import_cubewp_dynamic_data();
        }

        // check if it is call for cubewp Post Cards
        if($nextcall == 'post_cards'){
            return $this->import_cubewp_post_cards();
        }

        // check if it is call for downloading media files
        if($nextcall == 'media'){
            return $this->import_media_files();
        }

        // check if it is call for importing all wordpress default content without images
        if($nextcall == 'content'){
            return $this->import_wp_content();
        }

        return array(
            'status' => 'failed',
            'message' => esc_html__('Unknown error occurred during import.', 'nextwp')
        );
        
    }

    public function import_wp_content() {
        global $wpdb;
        
        // target directory
        $targetdir = $this->targetdir;
        $nextcall = $this->nextcall;
        $template_id = $this->template_id;
        $tb_template_ids = $this->get_tb_template_ids();

        if ( empty($targetdir) ) {
            return array(
                'status' => 'failed',
                'message' => esc_html__( "There is problem to find target directory.", 'nextwp' )
            );
        }
        
        // check if it is cubewp setup file
        $contents = $this->nwp_import_files(false,true,false);

        if(file_exists($targetdir. $contents)){
            // Start transaction
            $wpdb->query('START TRANSACTION');
            
            try {
                // Import content
                $message = $this->nwp_import_wordpress_content($targetdir, $contents);

                // change all Theme builder template's status to inactive
                $this->change_tb_template_status_to_inactive($tb_template_ids);

                // Delete all data
                delete_option( $template_id . '_data' );

                // Remove directory with import files
                $this->rmdir_recursive( $targetdir );
                
                // Commit if all operations succeeded
                $wpdb->query('COMMIT');

                do_action( 'nextwp_after_import_action' );
                
                return array(
                    'status'  => 'success',
                    //'message' => !empty($message) ? $message : esc_html__("All Posts are imported", 'nextwp'), 
                    'nextcall' => null
                );
                
            } catch (Exception $e) {
                // Rollback on any error
                $wpdb->query('ROLLBACK');
                error_log('Import failed: ' . $e->getMessage());
                
                return array(
                    'status' => 'failed',
                    'message' => esc_html__("Import failed. Please try again.", 'nextwp')
                );
            }
        }
        
        return array(
            'status' => 'failed',
            'message' => esc_html__("Content file not found.", 'nextwp')
        );
    }

    public function import_media_files() {
        global $wpdb;
        
        // Next call
        $targetdir = $message = '';
        $nextcall = $this->nextcall;
        $media_url = $this->media_url;
        $template_id = $this->template_id;
        
        if ( empty($nextcall) ) {
            return array(
                'status' => 'failed',
                'message' => esc_html__( "Media call is not initiated", 'nextwp' )
            );
        }

        if ( empty($media_url) ) {
            return array(
                'status' => 'continue',
                'message' => esc_html__( "Media URL is missing or no download-able media is associated to this site", 'nextwp' ),
                'nextcall' => 'content'
            );
        }

        // Start transaction
        $wpdb->query('START TRANSACTION');
        
        try {
            $targetdir = $this->download_resources($media_url);
            
            // check if $targetdir is an array then there is an error array is returning
            if(is_array($targetdir)){
                $wpdb->query('ROLLBACK');
                return $targetdir;
            }
            
            if(!empty($targetdir)){
                $message = $this->move_images_to_new_directory($targetdir);
            }
            
            // check if $message is an array then there is an error array is returning 
            if(is_array($message)){
                $wpdb->query('ROLLBACK');
                return $message;
            }
            
            delete_option( $template_id . '_data' );
            $wpdb->query('COMMIT');
            
            return array(
                'status' => 'continue',
                'message' => !empty($message) ? $message : esc_html__("Media Downloaded successfully - Now content import has been starting", 'nextwp'), 
                'nextcall' => 'content'
            );
            
        } catch (Exception $e) {
            $wpdb->query('ROLLBACK');
            error_log('Media import failed: ' . $e->getMessage());
            
            return array(
                'status' => 'failed',
                'message' => esc_html__("Media import failed. Please try again.", 'nextwp')
            );
        }
    }

    public function import_cubewp_setup() {
        $targetdir = $this->targetdir;

        // check if it is cubewp setup file
        $setup_file = $this->nwp_import_files(true,false,false);

        if(file_exists($targetdir. $setup_file)){
            $this->import_cubewp_framework_not_installed();
            ( new CubeWp_Import )->cwp_import_cubewp_data($targetdir, $setup_file);

            return array(
                'status'      => 'continue',
                'message'     => esc_html__( "Settings imported successfuly", 'nextwp' ), 
                'nextcall' => 'dynamic_data'
            );

        }
    }

    public function import_cubewp_dynamic_data() {
        $targetdir = $this->targetdir;

        // check if it is cubewp dynamic data files
        $content_files = $this->nwp_import_files(false,false,true);

        foreach($content_files as $content_file){
            if(file_exists($targetdir.$content_file)){
                $this->import_cubewp_framework_not_installed();
                $message = ( new CubeWp_Import )->cwp_import_wordpress_content($targetdir, $content_file);
            }
        }
        return array(
            'status'      => 'continue',
            'message'     => ! empty( $message ) ? $message : esc_html__( "Dynamic data imported successfuly", 'nextwp' ), 
            'nextcall' => 'post_cards'
        );
    }

    public function import_cubewp_post_cards() {
        $targetdir = $this->targetdir;

        // check if it is cubewp dynamic data files
        $post_card_dir = $targetdir  . '/cubewp-post-cards';
        $post_card_php = $post_card_dir  . '/cubewp-post-cards.php';
        $post_card_css = $post_card_dir  . '/cubewp-post-cards.css';

        if (is_dir($post_card_dir)) {
            if(file_exists($post_card_php) && file_exists($post_card_css)){
                $this->import_cubewp_framework_not_installed();
                ( new CubeWp_Import )->copy_cubewp_post_cards($post_card_dir) ;
            }
        }
        return array(
            'status'      => 'continue',
            'message'     => esc_html__( "Post Card data imported successfuly", 'nextwp' ), 
            'nextcall' => 'media'
        );
    }

    /**
     * Method nwp_import_wordpress_content
     *
     * @param $targetdir $targetdir path of files
     *
     * @return void
     */
    public function nwp_import_wordpress_content($targetdir = '', $file = ''){
        if($targetdir != '' && $file != ''){
            $file = $targetdir . $file;
            if (!defined('WP_LOAD_IMPORTERS')) {
                define('WP_LOAD_IMPORTERS', true);
            }
            require_once ABSPATH . 'wp-admin/includes/import.php';
            $importer_error = false;
            if (!class_exists('WP_Importer')) {
                $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                if (file_exists($class_wp_importer)) {
                    require_once $class_wp_importer;
                } else {
                    $importer_error = true;
                }
            }
            if (!class_exists('WP_Import')) {
                $class_wp_import = NEXTWP_DIR . 'importer/wordpress-importer.php';
                if (file_exists($class_wp_import)) {
                    require_once $class_wp_import;
                } else {
                    $importer_error = true;
                }
            }
            if ($importer_error) {
                return "Error on import";
            } else {
                if (!is_file($file)) {
                    return "The XML file containing the content is not available or could not be read. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work, please contact the community or email us for more help.";
                } else {
                    // Import content
                    ob_start();
                    $wp_import = new WP_Import();
                    $wp_import->fetch_attachments = true;
                    $wp_import->import($file);
                    ob_end_clean();
                }
            }
        }
    }

    public function download_resources($zip_url = '') {

        if(empty($zip_url)){
            return array(
                'status' => 'failed',
                'message'     => esc_html__( "Zip file URL is missing", 'nextwp' )
            );
        }
        $upload_dir  = wp_upload_dir();
        $path        = trailingslashit($upload_dir['path']) . '/nextwp/';
        $filename   = basename( $zip_url );
        $targetdir  = $path . pathinfo( $filename, PATHINFO_FILENAME );
        $targetzip  = $path . $filename;

        if(is_dir($targetdir)){
            return $targetdir;
        }

        if ( ! is_dir( $path ) ) {
            wp_mkdir_p($path); // Uses secure default permissions (0755)
        }

        // Fetch the ZIP file
        $response = $this->download_file_content($zip_url, $targetzip);
        if ( !$response ) {
            return array(
                'status' => 'failed',
                'message'     => esc_html__( "Failed to retrieve the ZIP file. Please check the file URL.", 'nextwp' )
            );
        }
    
        // Clean up the target directory if it already exists
        if ( is_dir( $targetdir ) ) {
            $this->rmdir_recursive( $targetdir );
        }
    
        wp_mkdir_p($targetdir); // Uses secure default permissions (0755)
    
        if (!class_exists('PclZip')) {
            require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';
        }
    
        $zip = new PclZip($targetzip);
    
        $extraction_result = $zip->extract(PCLZIP_OPT_PATH, $targetdir);

		if ($extraction_result == 0) {
			return array(
				'status' => 'failed',
				'message' => esc_html__("Failed to extract the ZIP file with PclZip.", 'nextwp')
			);
		}

		// Only unlink the ZIP file if extraction was successful
		if ($extraction_result > 0) {
			unlink($targetzip);
		}
    
        return $targetdir;

    }

    public function download_file_content($url, $destination) {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            error_log('NextWP: Invalid download URL provided: ' . $url);
            return false;
        }
        
        // Only allow http/https protocols
        $parsed = parse_url($url);
        if (!in_array($parsed['scheme'] ?? '', ['http', 'https'])) {
            error_log('NextWP: Invalid protocol in download URL: ' . $url);
            return false;
        }

        // Set size limit (300MB)
        $max_size = 300 * 1024 * 1024;
        
        // Use wp_remote_get instead of file_get_contents for security
        $response = wp_remote_get($url, [
            'timeout' => 300,
            'sslverify' => true,
            'headers' => [
                'Accept' => 'application/octet-stream',
                'User-Agent' => 'NextWP-Plugin/' . (defined('NEXTWP_VERSION') ? NEXTWP_VERSION : '1.0.0')
            ]
        ]);
        
        if (is_wp_error($response)) {
            error_log('NextWP: Download failed: ' . $response->get_error_message());
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            error_log('NextWP: Download failed with response code: ' . $response_code);
            return false;
        }
        
        $file_data = wp_remote_retrieve_body($response);
        if (empty($file_data)) {
            error_log('NextWP: Empty file data received');
            return false;
        }
        
        // Check file size
        if (strlen($file_data) > $max_size) {
            error_log('NextWP: File size exceeds limit: ' . strlen($file_data));
            return false;
        }
        
        // Verify it's a ZIP file
        if (strpos($file_data, 'PK') !== 0) { // ZIP file signature
            error_log('NextWP: File is not a valid ZIP file');
            return false;
        }

        // Write file with proper error handling
        $result = file_put_contents($destination, $file_data, LOCK_EX);
        if ($result === false) {
            error_log('NextWP: Failed to write file to destination: ' . $destination);
            return false;
        }
        
        // Verify file was written correctly
        if (!file_exists($destination) || filesize($destination) !== strlen($file_data)) {
            error_log('NextWP: File verification failed after write');
            return false;
        }

        return true;
    }

    public function move_images_to_new_directory($source_dir) {

        if(empty($source_dir)){
            return array(
                'status' => 'failed',
                'message'     => esc_html__( "Image Destination Directory is missing", 'nextwp' )
            );
        }

        // Get the WordPress upload directory
        $upload_dir = wp_get_upload_dir();

        $destination_dir = trailingslashit( $upload_dir['path'] ) . ''; // Path within the current month/year directory
    
        // Ensure the source directory exists
        if ( ! is_dir( $source_dir ) ) {
            error_log( "Source directory does not exist: $source_dir" );
            return;
        }
    
        // Create the destination directory if it doesn't exist
        if ( ! is_dir( $destination_dir ) ) {
            wp_mkdir_p( $destination_dir );
        }
    
        // Recursive function to move files from source to destination
        $move_files_recursive = function($dir) use (&$move_files_recursive, $destination_dir) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (in_array($file, array('.', '..'), true)) {
                    continue;
                }
                $source_file = trailingslashit($dir) . $file;
                if (is_dir($source_file)) {
                    // Recursive call for subdirectory
                    $move_files_recursive($source_file);
                } elseif (is_file($source_file)) {
                    $destination_file = trailingslashit($destination_dir) . $file;
                    rename($source_file, $destination_file);
                }
            }
        };
    
        // Start moving files recursively from the source directory
        $move_files_recursive($source_dir);
    
        $this->rmdir_recursive( $source_dir );
    }

    /**
     * Handle imported posts from the 'wp_import_insert_post' action.
     *
     * @param int    $post_id           The ID of the newly inserted post.
     * @param int    $original_post_ID  The original post ID in the import file.
     * @param array  $postdata          The data array for the post being inserted.
     * @param object $post              The WP_Post object for the inserted post.
     */
    public function handle_imported_post($post_id, $original_post_ID, $postdata, $post) {
        $this->process_page($post_id, $postdata['post_title'], $postdata['post_type']);
    }

    /**
     * Handle existing posts from the 'wp_import_existing_post' filter.
     *
     * @param int|bool $post_exists The ID of the existing post, or false if none exists.
     * @param array    $post        The data array for the post being checked.
     * @return int|bool The original $post_exists value.
     */
    public function handle_existing_imported_post($post_exists, $post) {
        // Check if the post type is 'cubewp-tb'
        if (isset($post['post_type']) && $post['post_type'] === 'cubewp-tb') {
            if ($post_exists) {
                return false; // Skip the post import for 'cubewp-tb'
            }
        }

        // If the post exists and is a page, process it
        if ($post_exists && $post['post_type'] === 'page') {
            $this->process_page($post_exists, $post['post_title'], $post['post_type']);
        }

        // Return the original post_exists value to allow the import process to continue
        return $post_exists;
    }


    /**
     * Process a page based on its title and type.
     *
     * @param int    $post_id   The ID of the post.
     * @param string $title     The title of the post.
     * @param string $post_type The type of the post.
     */
    private function process_page($post_id, $title, $post_type) {
        if ($post_type !== 'page') {
            return;
        }

        // Handle "Home" page
        if ($title === 'Home') {
            $this->process_home_page($post_id);
        }

        // Handle "Blog" page
        if ($title === 'Blog' || $title === 'Blogs') {
            $this->process_blog_page($post_id);
        }
    }

    /**
     * Process the "Home" page.
     *
     * @param int $post_id The ID of the imported "Home" page.
     */
    private function process_home_page($post_id) {
        // Add custom meta field to mark this page as the imported "Home" page
        update_post_meta($post_id, '_imported_page', 'home');

        // Rename the page (optional)
        wp_update_post([
            'ID'         => $post_id,
            'post_title' => 'Home-' . $this->template_name,
            'post_name'  => sanitize_title('Home-' . time()),
        ]);

        // Set this page as the static front page
        update_option('page_on_front', $post_id);
        update_option('show_on_front', 'page');
    }

    /**
     * Process the "Blog" page.
     *
     * @param int $post_id The ID of the imported "Blog" page.
     */
    private function process_blog_page($post_id) {
        // Add custom meta field to mark this page as the imported "Blog" page
        update_post_meta($post_id, '_imported_page', 'blog');

        // Set this page as the posts page
        update_option('page_for_posts', $post_id);
    }

    /**
     * Get all post IDs of the 'cubewp-tb' post type.
     *
     * @return array Array of post IDs or an empty array if no posts are found.
     */
    private function get_tb_template_ids() {
        global $wpdb;
        
        // Use direct SQL query with proper escaping
        $query = $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} 
             WHERE post_type = %s 
             AND post_status = %s",
            'cubewp-tb',
            'publish'
        );
        
        $results = $wpdb->get_col($query);
        
        return $results ? array_map('intval', $results) : array();
    }

    /**
     * Change the status of all 'cubewp-tb' posts to 'inactive'.
     *
     * @return void
     */
    private function change_tb_template_status_to_inactive($tb_template_ids = []) {
        global $wpdb;
        
        if (empty($tb_template_ids)) {
            return;
        }

        // Convert all IDs to integers
        $ids = array_map('intval', $tb_template_ids);
        $ids_placeholders = implode(',', array_fill(0, count($ids), '%d'));
        
        // Use a single prepared statement for all updates
        $query = $wpdb->prepare(
            "UPDATE {$wpdb->posts} 
             SET post_status = 'inactive' 
             WHERE ID IN ($ids_placeholders) 
             AND post_type = 'cubewp-tb'",
            $ids
        );
        
        $wpdb->query($query);
    }
        

    /**
     * Method rmdir_recursive
     *
     * @param  $dir
     *
     * @return string
     * @since  1.0.0
     */
    public function rmdir_recursive($dir) {
        if (!is_dir($dir)) {
            return false; // Return false if $dir is not a directory
        }
    
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue; // Skip special entries
            }
    
            $file_path = "$dir/$file";
            if (is_dir($file_path)) {
                $this->rmdir_recursive($file_path); // Recursive call
            } else {
                unlink($file_path); // Delete file
            }
        }
    
        return rmdir($dir); // Remove directory and return result
    }
}
new NextWP_Import();
