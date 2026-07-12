<?php
defined('ABSPATH') || exit;

class Woomen
{
    public static $is_theme_uptodate = false;

    /**
     * The single instance of the class.
     *
     * @var Woomen
     */
    protected static $Load = null;

    public function __construct()
    {
        self::includes();
    }

    /**
     * Include required files.
     */
    /**
     * Include required theme files with security checks.
     * 
     * @return void
     */
    private static function includes()
    {
        // Define core files to include
        $files = array(
            'include/settings.php',
            'include/helper.php', 
            'include/dynamic-css.php',
        );

        // Safely include each file
        foreach ($files as $file) {
            $file_path = WOOMEN_PATH . sanitize_text_field($file);
            if (is_readable($file_path) && 
                strpos($file_path, WOOMEN_PATH) === 0 && 
                file_exists($file_path)) {
                require_once $file_path;
            }
        }

        add_action( 'init', array( 'Woomen_Frontend_Enqueue', 'init' ) );
		add_action( 'init', array( 'Woomen_Theme_Maintenance', 'init' ) );
    }

    /**
     * Ensure a single instance of the class.
     *
     * @return Woomen
     */
    /**
     * Get the singleton instance of the class.
     *
     * @return Woomen Singleton instance
     */
    public static function instance()
    {
        if (is_null(self::$Load)) {
            self::$Load = new self();
        }

        return self::$Load;
    }
}
