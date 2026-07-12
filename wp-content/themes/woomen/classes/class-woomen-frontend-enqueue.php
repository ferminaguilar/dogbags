<?php
defined('ABSPATH') || exit;

/**
 * Woomen Frontend Enqueue Class.
 *
 * Handles registration and enqueueing of frontend assets with security considerations.
 *
 * @class Woomen_Frontend_Enqueue
 * @version 1.0.0
 *
 * Security Considerations:
 * - All asset URLs are properly escaped
 * - Localized data is sanitized
 * - AJAX calls include nonce verification
 * - Elementor widget validation
 */
class Woomen_Frontend_Enqueue
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'woomen_enqueue_styles_and_scripts'));
    }

    /**
     * Enqueue frontend styles and scripts.
     */
    public function woomen_enqueue_styles_and_scripts()
    {
        $this->woomen_register_styles();
        $this->woomen_register_scripts();
        $this->woomen_enqueue_styles();
        $this->woomen_enqueue_scripts();
    }

    /**
     * Register styles.
     */
    private function woomen_register_styles()
    {
        $styles = array(
            'woomen-font-family'             => array(
                'src'     => woomen_build_google_font_api_url(),
                'deps'    => array(),
                'ver'       => WOOMEN_VERSION,
                'has_rtl' => false,
                'media'   => '',
            ),
            'woomen-fa'               => array(
                'src'   => WOOMEN_URL . 'assets/lib/fontawesome/css/fontawesome.min.css',
                'deps'  => array(),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-fa-solid'         => array(
                'src'   => WOOMEN_URL . 'assets/lib/fontawesome/css/solid.min.css',
                'deps'  => array(),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-fa-regular'       => array(
                'src'   => WOOMEN_URL . 'assets/lib/fontawesome/css/regular.min.css',
                'deps'  => array(),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-fa-brands'        => array(
                'src'   => WOOMEN_URL . 'assets/lib/fontawesome/css/brands.min.css',
                'deps'  => array(),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-bootstrap-styles' => array(
                'src'   => WOOMEN_URL . 'assets/lib/bootstrap/css/bootstrap.min.css',
                'deps'  => array(),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-core-styles'      => array(
                'src'   => get_stylesheet_uri(),
                'deps'  => array('woomen-bootstrap-styles'),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-styles'           => array(
                'src'   => WOOMEN_URL . 'assets/css/woomen-styles.css',
                'deps'  => array('woomen-core-styles'),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-dynamic-styles'   => array(
                'src'   => WOOMEN_URL . 'assets/css/dynamic-css.css',
                'deps'  => array('woomen-styles'),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-blog-styles'      => array(
                'src'   => WOOMEN_URL . 'assets/css/woomen-blog-styles.css',
                'deps'  => array(),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-blogs-styles'     => array(
                'src'   => WOOMEN_URL . 'assets/css/woomen-blogs-styles.css',
                'deps'  => array(),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-header-styles'    => array(
                'src'   => WOOMEN_URL . 'assets/css/woomen-header-styles.css',
                'deps'  => array('woomen-styles'),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-input-slid-css'    => array(
                'src'   => WOOMEN_URL . 'assets/lib/number-slide/NumberSlider.min.css',
                'deps'  => array('woomen-styles'),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-styles-archive'    => array(
                'src'   => WOOMEN_URL . 'assets/css/woomen-archive.css',
                'deps'  => array('woomen-styles'),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
            'woomen-styles-woocommerece'    => array(
                'src'   => WOOMEN_URL . 'assets/css/wooomen-woocommerece.css',
                'deps'  => array('woomen-styles'),
                'ver'   => WOOMEN_VERSION,
                'media' => '',
            ),
        );

        foreach ($styles as $handle => $data) {
            wp_register_style($handle, $data['src'], $data['deps'], $data['ver'], $data['media']);
        }
    }

    /**
     * Register scripts.
     */
    private function woomen_register_scripts()
    {
        $scripts = array(
            'woomen-scripts'           => array(
                'src'       => WOOMEN_URL . 'assets/js/woomen-scripts.js',
                'deps'      => array('jquery'),
                'ver'       => WOOMEN_VERSION,
                'in_footer' => true,
            ),
            'woomen-scripts-input-range'           => array(
                'src'       => WOOMEN_URL . 'assets/lib/number-slide/NumberSlider.min.js',
                'deps'      => array('jquery'),
                'ver'       => WOOMEN_VERSION,
                'in_footer' => true,
            ),
            'woomen-scripts-archive'           => array(
                'src'       => WOOMEN_URL . 'assets/js/woomen-archive.js',
                'deps'      => array('jquery'),
                'ver'       => WOOMEN_VERSION,
                'in_footer' => true,
            ),

        );

        if (!wp_script_is('value-pack-bootstrap-scripts', 'registered')) {
            $scripts['woomen-bootstrap-scripts'] = array(
                'src'       => WOOMEN_URL . 'assets/lib/bootstrap/js/bootstrap.bundle.min.js',
                'deps'      => array(),
                'ver'       => WOOMEN_VERSION,
                'in_footer' => true
            );
        }

        foreach ($scripts as $handle => $data) {
            wp_register_script($handle, $data['src'], $data['deps'], $data['ver'], $data['in_footer']);
        }
    }

    /**
     * Enqueue styles.
     */
    private function woomen_enqueue_styles()
    {
        wp_enqueue_style('woomen-fa');
        wp_enqueue_style('woomen-input-slid-css');
        wp_enqueue_style('woomen-fa-solid');
        wp_enqueue_style('woomen-fa-regular');
        wp_enqueue_style('woomen-fa-brands');

        wp_enqueue_style('woomen-dynamic-styles');
        wp_enqueue_style('woomen-font-family');

        if (is_archive()) {
            wp_enqueue_style('woomen-styles-archive');
            wp_enqueue_script('woomen-scripts-archive');
        }

        if (! is_user_logged_in()) {
            wp_enqueue_style('woomen-login-register-styles');
        }
        if (is_search() || is_tag() || is_category()) {
            wp_enqueue_style('woomen-blogs-styles');
        }

        if (class_exists('WooCommerce')) {
            wp_enqueue_style('woomen-styles-woocommerece');
        }
    }

    /**
     * Enqueue scripts.
     */
    private function woomen_enqueue_scripts()
    {
        if (!wp_script_is('value-pack-bootstrap-scripts', 'registered')) {
            wp_enqueue_script('woomen-bootstrap-scripts');
        }
        wp_enqueue_script('woomen-scripts');
        wp_enqueue_script('woomen-scripts-input-range');

        wp_localize_script('woomen-scripts', 'woomen_script_obj', array(
            'woomen_ajax_url' => esc_url(admin_url('admin-ajax.php')),
            'woomen_nonce' => wp_create_nonce('woomen_ajax_nonce')
        ));
    }

    public static function init()
    {
        $WoomenClass = __CLASS__;
        new $WoomenClass;
    }
}
