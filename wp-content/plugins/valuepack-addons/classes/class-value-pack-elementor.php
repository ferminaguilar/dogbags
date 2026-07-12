<?php
defined('ABSPATH') || exit;

use Elementor\Widgets_Manager;

/**
 * Value Pack Elementor Widgets Loader
 *
 * @class Value_Pack_Elementor
 */
final class Value_Pack_Elementor
{
    /**
     * Instance
     *
     * @since  1.0.0
     * @access private
     * @static
     * @var Value_Pack_Elementor The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Constructor
     *
     * Perform some compatibility checks to make sure basic requirements are meet.
     * If all compatibility checks pass, initialize the functionality.
     *
     * @since  1.0.0
     * @access public
     */
    public function __construct()
    {
        add_action('elementor/init', array($this, 'init_elementor_widgets'));
        add_action('wp_ajax_valuepack_get_cubewp_fields', array($this, 'ajax_get_cubewp_fields'));
    }

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Value_Pack_Elementor
     * @since  1.0.0
     * @access public
     * @static
     */
    public static function init()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Initialize Elementor widgets
     *
     * Load the addons functionality only after Elementor is initialized.
     *
     * Fired by `elementor/init` action hook.
     *
     * @since  1.0.0
     * @access public
     */
    public function init_elementor_widgets()
    {
		require_once VALUE_PACK_PLUGIN_DIR . 'classes/elementor-widgets/class-value-pack-widget-base.php';

        add_action('elementor/elements/categories_registered', array($this, 'elementor_widget_category'));
        add_action('elementor/widgets/register', array($this, 'register_widgets'));
        spl_autoload_register(array($this, 'require_widgets_files'));
    }

    /**
     * Add Value Pack widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function elementor_widget_category($elements_manager)
    {
        $elements_manager->add_category(
            'value_pack',
            [
                'title' => __('Value Pack Addons', 'valuepack-addons'),
                'icon' => '',
            ]
        );
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @param Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets($widgets_manager)
    {
        $classes = array(
            'Do_Action',
            'Insta_Feeds',
            'Countdown',
            'Product_Accordian',
            'Social_Share',
            'Icon_Products',
            'Compare_Products',
            'Post_Reactions_Widget',
            'Comment_Form',
            'Post_Comments',
            'Post_Navigation',
            'User_Account',
        );

         

        if (function_exists('CWP')) {
            $cwp_classes = array(
                'CubeWp_Login',
                'CubeWp_Save',
                'CubeWp_Gallery',
                'CubeWp_Map',
                'CubeWp_Meta',
                'CubeWp_Repeater',
                'CubeWp_Business_Hours',
            );
            $classes = array_merge($classes, $cwp_classes);
        }

        // Conditionally append WooCommerce-dependent classes if WooCommerce is active
        if (class_exists('WooCommerce')) {
            $woocommerce_dependent_classes = array(
                'Products_By_Type',
                'Single_Product_Detail',
                'Collective_Products_Cart',
                'Product_Gallery',
                'Product_Price',
                'Product_Short_Description',
                'Product_Rating',
                'Product_Reviews',
                'Product_Meta',
                'Product_Variations',
                'Products_Search',
                'Size_Guide',
                'Mini_Cart',
                'Sticky_Ad_To_Cart',
                'Locale_Switcher',
                'Mobile_Sticky_Bottom_Bar'
            );
            $classes = array_merge($classes, $woocommerce_dependent_classes);
        }

        // WooCommerce-&-CubeWp-dependent classes
        if (function_exists('CWP') && class_exists('WooCommerce')) {
            $woo_cwp_classes = array(
                'Saved_Products',
            );
            $classes = array_merge($classes, $woo_cwp_classes);
        }

        $classes = apply_filters("valuepack-addons/widgets/classes", $classes);
        if (!empty($classes) && is_array($classes)) {
            foreach ($classes as $class) {
                $class = 'Value_Pack_' . $class;
                if (class_exists($class)) {
                    try {
                        $widgets_manager->register(new $class());
                    } catch (Exception $e) {
                        /**
                         * Fires when a widget fails to register.
                         *
                         * @param string    $class Widget class name.
                         * @param \Exception $e     Exception instance.
                         */
                        do_action('valuepack_addons_widget_register_error', $class, $e);
                    }
                } else {
                    /**
                     * Fires when a widget class is missing.
                     *
                     * @param string $class Missing widget class name.
                     */
                    do_action('valuepack_addons_widget_missing_class', $class);
                }
            }
        }
    }

    /**
     * Auto Load Widget Files
     *
     * @param string $className The class name to load
     * @return string|null
     */
    private static function require_widgets_files($className)
    {
        if (false === strpos($className, 'Value_Pack')) {
            return null;
        }

        $file_name = str_replace('_', '-', strtolower($className));
        $files = array(
            VALUE_PACK_PLUGIN_DIR . 'classes/elementor-widgets/class-' . $file_name . '.php'
        );

        $files = apply_filters("valuepack-addons/widgets/files", $files, $file_name);

        if (!empty($files) && is_array($files)) {
            foreach ($files as $file) {
                if (file_exists($file) && is_readable($file)) {
                    require_once $file;
                }
            }
        }

        return $className;
    }

    /**
     * AJAX handler to get CubeWP fields for a post type or user roles
     *
     * @return void
     */
    public function ajax_get_cubewp_fields()
    {
        // Check user permissions
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => esc_html__('Permission denied.', 'valuepack-addons')));
        }

        // Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'valuepack_elementor_nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'valuepack-addons')));
        }

        $field_type = isset($_POST['field_type']) ? sanitize_text_field(wp_unslash($_POST['field_type'])) : 'post';
        $post_type = isset($_POST['post_type']) ? sanitize_text_field(wp_unslash($_POST['post_type'])) : '';
        $roles = isset($_POST['roles']) ? array_map('sanitize_text_field', wp_unslash((array) $_POST['roles'])) : array('subscriber');

        $fields = array('' => esc_html__('Select Field', 'valuepack-addons'));

        if (!function_exists('get_fields_by_post_type')) {
            wp_send_json_success($fields);
        }

        if ($field_type === 'user') {
            // Get user fields
            $user_fields = get_fields_by_post_type($roles);
            if (!empty($user_fields) && is_array($user_fields)) {
                foreach ($user_fields as $field_key => $field_label) {
                    if (!empty($field_key) && !empty($field_label)) {
                        $fields[$field_key] = $field_label;
                    }
                }
            }
        } else {
            // Get post fields
            if (!empty($post_type)) {
                $post_type_fields = get_fields_by_post_type($post_type);
                if (!empty($post_type_fields) && is_array($post_type_fields)) {
                    foreach ($post_type_fields as $field_key => $field_label) {
                        if (!empty($field_key) && !empty($field_label)) {
                            $fields[$field_key] = $field_label;
                        }
                    }
                }
            }
        }

        wp_send_json_success($fields);
    }

    /**
     * Get widget settings from Elementor
     *
     * @param string $widget_id Widget ID.
     * @return array Widget settings or empty array if not found.
     */
    protected function get_widget_settings($widget_id)
    {
        // Extract element ID from widget ID
        // Widget ID format: "elementor-element-{element_id}" or just the ID
        $element_id = str_replace('elementor-element-', '', $widget_id);
        $element_id = trim($element_id);

        if (empty($element_id)) {
            return array();
        }

        // Get current post ID
        $post_id = get_the_ID();
        if (empty($post_id)) {
            // Try to get from referer
            $referer = wp_get_referer();
            if ($referer) {
                $post_id = url_to_postid($referer);
            }
        }

        // If still no post ID, try to get from POST data
        if (empty($post_id) && isset($_POST['_elementor_post_id'])) {
            $post_id = intval($_POST['_elementor_post_id']);
        }

        if (empty($post_id)) {
            return array();
        }

        // Try using Elementor's API first (if available)
        if (class_exists('\Elementor\Plugin')) {
            $document = \Elementor\Plugin::$instance->documents->get($post_id);
            if ($document && $document->is_built_with_elementor()) {
                $elements_data = $document->get_elements_data();
                
                // Try to find element in document
                $found_element = $this->find_element_in_data($elements_data, $element_id);
                if ($found_element && isset($found_element['settings'])) {
                    return $found_element['settings'];
                }
            }
        }

        // Fallback: Get elementor data from post meta
        $elementor_data = get_post_meta($post_id, '_elementor_data', true);
        if (empty($elementor_data)) {
            return array();
        }

        // Decode JSON if it's a string
        if (is_string($elementor_data)) {
            $elementor_data = json_decode($elementor_data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return array();
            }
        }

        // Find widget with matching ID (compare as both string and int)
        $widget = $this->find_element_in_data($elementor_data, $element_id);
        
        if ($widget && isset($widget['settings'])) {
            return $widget['settings'];
        }

        return array();
    }

    /**
     * Recursively find element in Elementor data
     *
     * @param array  $data Elementor data array.
     * @param string $element_id Element ID to find (can be string or numeric).
     * @return array|false Element data or false if not found.
     */
    protected function find_element_in_data($data, $element_id)
    {
        if (!is_array($data)) {
            return false;
        }

        // Normalize element_id for comparison (handle both string and numeric)
        $element_id_str = (string) $element_id;
        $element_id_int = is_numeric($element_id) ? intval($element_id) : null;

        foreach ($data as $element) {
            if (!isset($element['id'])) {
                continue;
            }

            // Compare as both string and int to handle different formats
            $element_id_current = (string) $element['id'];
            $element_id_current_int = is_numeric($element['id']) ? intval($element['id']) : null;

            if ($element_id_current === $element_id_str || 
                ($element_id_int !== null && $element_id_current_int === $element_id_int)) {
                return $element;
            }

            // Recursively search in child elements
            if (isset($element['elements']) && is_array($element['elements'])) {
                $found = $this->find_element_in_data($element['elements'], $element_id);
                if ($found) {
                    return $found;
                }
            }
        }

        return false;
    }
}
