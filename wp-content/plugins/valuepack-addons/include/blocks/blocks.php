<?php



update_option('elementor_unfiltered_files_upload', true);  
if (! function_exists('vp_allow_svg_upload')) {
    function vp_allow_svg_upload($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter('upload_mimes', 'vp_allow_svg_upload');
}
// Sanitize SVG Files
if (! function_exists('vp_sanitize_svg')) {
    function vp_sanitize_svg($file)
    {
        $mime_types = ['image/svg+xml'];
        if (in_array($file['type'], $mime_types)) {
            $file['type'] = 'text/plain';
        }
        return $file;
    }
    add_filter('wp_check_filetype_and_ext', 'vp_sanitize_svg', 10, 4);
}



/* VALUE_PACK_PLUGIN_FILE Defines for file access */
if ( ! defined('VALUE_PACK_LIBRARY_SYNCER_API_URL')) {
    define('VALUE_PACK_LIBRARY_SYNCER_API_URL', 'https://vpaddons.com/');
}


require_once __DIR__ . '/class-library-source.php';
require_once __DIR__ . '/class-library.php';

add_action('init', function () {
    if (defined('ELEMENTOR_VERSION')) {
        new VP_Library;
    }
});


 



 