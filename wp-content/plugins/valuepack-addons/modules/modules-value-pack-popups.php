<?php

/**
 * Popups Module
 *
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use Elementor\Conditions;
use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;

// Register Popup Controls for Custom Post Type
if (!function_exists('value_pack_register_popup_page_controls')) {
    function value_pack_register_popup_page_controls($element)
    {
        if (! $element instanceof \Elementor\Core\DocumentTypes\PageBase || ! $element::get_property('has_elements')) {
            return;
        }

        $post_id = get_the_ID();
        $template_type = get_post_meta($post_id, 'template_type', true);

        if (get_post_type($post_id) == 'cubewp-tb' && $template_type == 'popup') {

            $post_slug = get_post_field('post_name', $post_id);
            update_post_meta($post_id, 'post_popup_close', "vpack-triger-close" . $post_slug);
            update_post_meta($post_id, 'post_popup_open', "vpack-triger" . $post_slug);

            $post_popup_close =   get_post_meta($post_id, 'post_popup_close', true);
            $post_popup_open =    get_post_meta($post_id, 'post_popup_open', true);

            $element->start_controls_section(
                'popup_options_section',
                [
                    'label' => __('Popup Options - Value Pack', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                ]
            );
            $element->add_control(
                'popup_trigger',
                [
                    'label' => __('Popup Trigger Type', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'on_load_data' => __('On Page Load', 'valuepack-addons'),
                        'on_click' => __('On Click', 'valuepack-addons'),
                        'on_hover' => __('On Hover', 'valuepack-addons'),
                        'on_exit_intent' => __('On Exit Intent', 'valuepack-addons'),
                        'on_scroll' => __('After Scroll', 'valuepack-addons'),
                        'on_mouseenter' => __('On Mouse Enter', 'valuepack-addons'),
                        'on_mouseleave' => __('On Mouse Leave', 'valuepack-addons'),
                        'on_dblclick' => __('On Double Click', 'valuepack-addons'),
                    ],
                ]
            );
            $element->add_control(
                'popup_open_delay',
                [
                    'label' => __('Popup Open Delay (Seconds)', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'step' => 100,
                    'default' => 0,
                    'description' => __('Set the delay time in Seconds before the popup opens automatically.', 'valuepack-addons'),
                ]
            );

            $element->add_control(
                'popup_cookie_expiration',
                [
                    'label' => __('Popup Cookie Expiration (Minutes)', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'step' => 1,
                    'default' => 0,
                    'description' => __('Set the number of Minutes to prevent the popup from reopening after it is closed.', 'valuepack-addons'),
                ]
            );

            $element->add_control(
                'popup_position',
                [
                    'label' => __('Popup Position', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'center_position' => __('Center', 'valuepack-addons'),
                        'top_center' => __('Top Center', 'valuepack-addons'),
                        'bottom_center' => __('Bottom Center', 'valuepack-addons'),
                        'left_center' => __('Left Center', 'valuepack-addons'),
                        'right_center' => __('Right Center', 'valuepack-addons'),
                        'top_left_corner' => __('Top Left Corner', 'valuepack-addons'),
                        'top_right_corner' => __('Top Right Corner', 'valuepack-addons'),
                        'bottom_left_corner' => __('Bottom Left Corner', 'valuepack-addons'),
                        'bottom_right_corner' => __('Bottom Right Corner', 'valuepack-addons'),
                    ],
                ]
            );

            $element->add_control(
                'popup_width',
                [
                    'label' => __('Popup Width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em', 'vw'], // Add the units you want
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 1200,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        'em' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        'vw' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 600,
                    ],
                ]
            );

            $element->add_control(
                'popup_height',
                [
                    'label' => __('Popup Height', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em', 'vh', 'custom'], // Add the units you want, including 'custom' for user-defined units
               
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 1000,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        'em' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        'vh' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        'custom' => [
                            'min' => 100,
                            'max' => 1000,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 400,
                    ],
                ]
            );

            $element->add_control(
                'popup_overflow',
                [
                    'label'        => esc_html__('Overflow Auto', 'valuepack-addons'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                    'label_off'    => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default'      => '',
                ]
            );
            $element->add_control(
                'enable_popup_bg_layer',
                [
                    'label'        => esc_html__('Enable Popup Background Layer', 'valuepack-addons'),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'valuepack-addons'),
                    'label_off'    => esc_html__('No', 'valuepack-addons'),
                    'return_value' => 'yes',
                    'default'      => '',
                ]
            );

            $element->add_control(
                'popup_bg_layer_color',
                [
                    'label'     => __('Popup Background Layer Color', 'valuepack-addons'),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'default'   => '#000000', // Default color value
                    'condition' => [
                        'enable_popup_bg_layer' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'popup_bg_color',
                [
                    'label'     => __('Popup Background Color', 'valuepack-addons'),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                ]
            );

            $element->add_control(
                'popup_animation_in',
                [
                    'label' => __('Popup Animation In', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::ANIMATION,
                    'default' => 'fadeIn',
                ]
            );


            // Adding Popup Animation Out - Reusing entrance animations
            $element->add_control(
                'popup_animation_out',
                [
                    'label' => __('Popup Animation Out', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'options' => [
                        'none' => __('None', 'valuepack-addons'),
                        // Fading
                        'fadeOut' => __('Fade Out', 'valuepack-addons'),
                        'fadeOutDown' => __('Fade Out Down', 'valuepack-addons'),
                        'fadeOutLeft' => __('Fade Out Left', 'valuepack-addons'),
                        'fadeOutRight' => __('Fade Out Right', 'valuepack-addons'),
                        'fadeOutUp' => __('Fade Out Up', 'valuepack-addons'),
                        // Zooming
                        'zoomOut' => __('Zoom Out', 'valuepack-addons'),
                        'zoomOutDown' => __('Zoom Out Down', 'valuepack-addons'),
                        'zoomOutLeft' => __('Zoom Out Left', 'valuepack-addons'),
                        'zoomOutRight' => __('Zoom Out Right', 'valuepack-addons'),
                        'zoomOutUp' => __('Zoom Out Up', 'valuepack-addons'),
                        // Bouncing
                        'bounceOut' => __('Bounce Out', 'valuepack-addons'),
                        'bounceOutDown' => __('Bounce Out Down', 'valuepack-addons'),
                        'bounceOutLeft' => __('Bounce Out Left', 'valuepack-addons'),
                        'bounceOutRight' => __('Bounce Out Right', 'valuepack-addons'),
                        'bounceOutUp' => __('Bounce Out Up', 'valuepack-addons'),
                        // Sliding
                        'slideOutDown' => __('Slide Out Down', 'valuepack-addons'),
                        'slideOutLeft' => __('Slide Out Left', 'valuepack-addons'),
                        'slideOutRight' => __('Slide Out Right', 'valuepack-addons'),
                        'slideOutUp' => __('Slide Out Up', 'valuepack-addons'),
                        // Rotating
                        'rotateOut' => __('Rotate Out', 'valuepack-addons'),
                        'rotateOutDownLeft' => __('Rotate Out Down Left', 'valuepack-addons'),
                        'rotateOutDownRight' => __('Rotate Out Down Right', 'valuepack-addons'),
                        'rotateOutUpLeft' => __('Rotate Out Up Left', 'valuepack-addons'),
                        'rotateOutUpRight' => __('Rotate Out Up Right', 'valuepack-addons'),
                        // Attention Seekers
                        'bounce' => __('Bounce', 'valuepack-addons'),
                        'flash' => __('Flash', 'valuepack-addons'),
                        'pulse' => __('Pulse', 'valuepack-addons'),
                        'rubberBand' => __('Rubber Band', 'valuepack-addons'),
                        'shake' => __('Shake', 'valuepack-addons'),
                        'headShake' => __('Head Shake', 'valuepack-addons'),
                        'swing' => __('Swing', 'valuepack-addons'),
                        'tada' => __('Tada', 'valuepack-addons'),
                        'wobble' => __('Wobble', 'valuepack-addons'),
                        'jello' => __('Jello', 'valuepack-addons'),
                        // Light Speed
                        'lightSpeedOut' => __('Light Speed Out', 'valuepack-addons'),
                        // Specials
                        'rollOut' => __('Roll Out', 'valuepack-addons'),
                    ],
                    'default' => 'fadeOut',
                    'label_block' => true,
                ]
            );
            $element->add_control(
                'custom_popup_trigger_html',
                [
                    'label' => __('Popup Trigger For Open', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<p id="popup-trigger-open" style="background-color: #ddd;padding: 8px 8px;margin-top: 12px;border: 3px dashed #000;font-size: 16px;color:#1d1d1d;cursor: pointer;">#' . $post_popup_open . '</p>',
                ]
            );

            $element->add_control(
                'custom_popup_close_html',
                [
                    'label' => __('Popup Trigger For Close', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => '<p id="popup-trigger-close" style="background-color: #ddd;padding: 8px 8px;margin-top: 12px;border: 3px dashed #000;font-size: 16px;color:#1d1d1d;cursor: pointer;">#' . $post_popup_close . '</p>',
                ]
            );



            $element->end_controls_section();
        }
    }
    add_action('elementor/documents/register_controls', 'value_pack_register_popup_page_controls');
}

if (!function_exists('value_pack_get_popup_lists')) {
    function value_pack_get_popup_lists($type)
    {
        $args = array(
            'post_type'      => 'cubewp-tb',
            'fields'         => 'ids', // returns only IDs
            'post_status'    => 'any',
            'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                array(
                    'key'     => 'template_type',
                    'value'   => $type,
                    'compare' => '=',
                ),
            ),
            'posts_per_page' => -1,
        );
        $posts = get_posts($args); // returns array of IDs
        $popups = [];
        foreach ($posts as $post_id) {
            $location = get_post_meta($post_id, 'template_location', true);  // Get template_location meta
            $popups[$post_id] = array(
                'title'   => get_the_title($post_id),
                'location' => $location,
            );
        }
        $GLOBALS['valuepack_popups'] = $popups;
        return $popups;
    }
}


if (!function_exists('value_pack_display_popups_in_footer')) {
    function value_pack_display_popups_in_footer()
    {
        $popups = value_pack_get_popup_lists('popup');
        if (!empty($popups)) {
            // Enqueue the popup JS only if there are popups to display
            wp_enqueue_script('value-pack-popup-js');
            // Removed AJAX params as popup content is now loaded directly in footer
            foreach ($popups as $popup_id => $popup_data) {

              
                if (value_pack_should_display_popup($popup_data['location'])) {

                    $settings_page = get_post_meta($popup_id, '_elementor_page_settings', true);

                    // if (!isset($settings_page) || empty($settings_page)) {
                    //     $settings_page =  array();
                    //     $settings_page['popup_trigger'] = 'on_click';
                    //     $settings_page['popup_position'] = 'left_center';
                    //     $settings_page['popup_width'] = ['size' => '100', 'unit' => '%'];
                    //     $settings_page['popup_height'] = ['size' => '100', 'unit' => '%'];
                    //     $settings_page['popup_animation_in'] = 'fadeIn';
                    //     $settings_page['popup_animation_out'] = 'fadeOut';
                    //     $settings_page['popup_open_delay'] = 0;
                    //     $settings_page['popup_cookie_expiration'] = 0;
                    // }

                    if (isset($settings_page) && !empty($settings_page)) {
                    

                        $triggerType = !empty($settings_page['popup_trigger']) ? $settings_page['popup_trigger'] : 'on_click';
                        $popupPosition = !empty($settings_page['popup_position']) ? $settings_page['popup_position'] : '';
                        $popupWidth = !empty($settings_page['popup_width']) ? $settings_page['popup_width'] : ['size' => '100', 'unit' => '%'];
                        $popup_height = !empty($settings_page['popup_height']) ? $settings_page['popup_height'] : ['size' => '100', 'unit' => '%'];
                        $animationIn = !empty($settings_page['popup_animation_in']) ? $settings_page['popup_animation_in'] : 'fadeIn';
                        $animationOut = !empty($settings_page['popup_animation_out']) ? $settings_page['popup_animation_out'] : 'fadeOut';
                        $popup_open_delay = !empty($settings_page['popup_open_delay']) ? $settings_page['popup_open_delay'] : 0;
                        $popup_cookie_expiration = !empty($settings_page['popup_cookie_expiration']) ? $settings_page['popup_cookie_expiration'] : 0;



                        $enable_popup_bg_layer = !empty($settings_page['enable_popup_bg_layer']) ? $settings_page['enable_popup_bg_layer'] : 'no';
                        $popup_bg_layer_color = !empty($settings_page['popup_bg_layer_color']) ? $settings_page['popup_bg_layer_color'] : '#000000';
                        $popup_bg_color = !empty($settings_page['popup_bg_color']) ? $settings_page['popup_bg_color'] : '#fff';



                        $popussize = $popupWidth['size'] . $popupWidth['unit'];
                        if($popup_height['unit'] == 'custom'){
                            $popup_height = $popup_height['size'];
                        }else{
                            $popup_height = $popup_height['size'] . $popup_height['unit'];
                        }

                        $post_popup_close =   get_post_meta($popup_id, 'post_popup_close', true);
                        $post_popup_open =    get_post_meta($popup_id, 'post_popup_open', true);

                        if (isset($_COOKIE[$post_popup_open]) &&  $popup_cookie_expiration > 0) {
                            continue;
                        }

                        $overflows = '';
                        if (isset($settings_page['popup_overflow'])) {
                            if ($settings_page['popup_overflow'] == 'yes') {
                                $overflows = 'overflow-auto';
                            }
                        }
                        if ($enable_popup_bg_layer == 'yes') {
                            echo ' <div class="bg-layers"  style="background-color: ' . $popup_bg_layer_color . ';position: fixed; display: none; width: 100%;height: 100%;left: 0;top: 0;z-index: 99999999999;"></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inline CSS is safe.
                        }


                   
                        // Enqueue additional scripts/styles for widgets/elements used in the popup
                        $elementor_data = get_post_meta($popup_id, '_elementor_data', true);
                        $element_counts = array();
                        $element_types = array();

                        if ($elementor_data) {
                     
                            $data = is_array($elementor_data) ? $elementor_data : json_decode($elementor_data, true);

                            if (is_array($data)) {
                                // Recursive function to count and collect element types
                                $count_elements = function ($elements, &$element_counts, &$element_types) use (&$count_elements) {
                                    foreach ($elements as $element) {
                                        if (isset($element['elType'])) {
                                            $type = $element['elType'];
                                            $element_types[] = $type;
                                            if (!isset($element_counts[$type])) {
                                                $element_counts[$type] = 0;
                                            }
                                            $element_counts[$type]++;
                                        }
                                        if (isset($element['widgetType'])) {
                                            $type = $element['widgetType'];
                                            $element_types[] = $type;
                                            if (!isset($element_counts[$type])) {
                                                $element_counts[$type] = 0;
                                            }
                                            $element_counts[$type]++;
                                        }
                                        if (isset($element['elements']) && is_array($element['elements'])) {
                                            $count_elements($element['elements'], $element_counts, $element_types);
                                        }
                                    }
                                };
                                $count_elements($data, $element_counts, $element_types);
                                $element_types = array_unique($element_types);

                                // Try to enqueue scripts/styles for each widget type if possible
                                if (class_exists('\Elementor\Plugin')) {
                                    $widgets_manager = \Elementor\Plugin::$instance->widgets_manager;
                                    foreach ($element_types as $type) {
                                        $widget = $widgets_manager->get_widget_types($type);
                                        if ($widget && method_exists($widget, 'enqueue_scripts')) {
                                            $widget->enqueue_scripts();
                                        }
                                        if ($widget && method_exists($widget, 'enqueue_styles')) {
                                            $widget->enqueue_styles();
                                        }
                                    }
                                }
                            }
                        }
                       
?>
                        <div id="vpack-builder-popup-<?php echo esc_attr($popup_id) ?>"
                            data-ep-popup-id="<?php echo esc_attr($popup_id) ?>"
                            class="vpack-builder-popup"
                            data-ep-target="vpack-builder-popup-<?php echo esc_attr($popup_id) ?>"
                            data-ep-trigger-type="<?php echo esc_attr($triggerType) ?>"
                            data-ep-triger="<?php echo esc_attr($post_popup_open) ?>"
                            data-ep-trigerclose="<?php echo esc_attr($post_popup_close) ?>"
                            data-ep-popupsize="<?php echo esc_attr($popussize) ?>"
                            data-ep-animationIn="<?php echo esc_attr($animationIn) ?>"
                            data-ep-animationOut="<?php echo esc_attr($animationOut) ?>"
                            data-ep-popup_open_delay="<?php echo esc_attr($popup_open_delay) ?>"
                            data-ep-popup_height="<?php echo esc_attr($popup_height) ?>"
                            data-ep-popup_cookie_expiration="<?php echo esc_attr($popup_cookie_expiration) ?>"
                            data-ep-position="<?php echo esc_attr($popupPosition) ?>" style="position: fixed; z-index: 9999999999;  display: none;  transition: 0.9s;">

                            <div class="vpack-builder-popup-inner <?php echo esc_attr($overflows) ?>" style="max-width:100%; position: relative;">
                                <div class="vpack-builder-popup-content" style="display:none;max-width:100%;background-color:<?php echo esc_attr($popup_bg_color); ?>;">
                                    <?php
                                    // Render popup content directly instead of using AJAX
                                    if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->documents) {
                                        $document = \Elementor\Plugin::$instance->documents->get($popup_id);
                                        if ($document && $document->is_built_with_elementor()) {
                                            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($popup_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor content is safe.
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
<?php
                    }
                }
            }
        }
    }
    add_action('wp_footer', 'value_pack_display_popups_in_footer');
}



if (!function_exists('valuepack_get_popup_content')) {
    function valuepack_get_popup_content()
    {
        if (!isset($_POST['popup_id'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            wp_send_json_error('Invalid request');
        }

        $popup_id = intval($_POST['popup_id']); // phpcs:ignore WordPress.Security.NonceVerification.Missing

        if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->documents) {
            $document = \Elementor\Plugin::$instance->documents->get($popup_id);

            if ($document && $document->is_built_with_elementor()) {
                // Render the content with Elementor
                $popup_content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($popup_id);


                wp_send_json_success(array(
                    'html' => $popup_content,
                ));
            } else {
                wp_send_json_error('Elementor document not found or not built with Elementor.');
            }
        } else {
            wp_send_json_error('Elementor is not active.');
        }
    }
    add_action('wp_ajax_valuepack_get_popup_content', 'valuepack_get_popup_content');
    add_action('wp_ajax_nopriv_valuepack_get_popup_content', 'valuepack_get_popup_content');
}



if (!function_exists('value_pack_should_display_popup')) {
    function value_pack_should_display_popup($location)
    {
        // General options
        if ($location === 'entire_site') {
            return true;
        }
        if ($location === 'home') {
            return is_front_page();
        }
        if ($location === 'blog') {
            return is_home();
        }
        if ($location === 'single_all') {
            return is_singular();
        }
        if ($location === 'archive_all') {
            return is_archive();
        }
        if ($location === 'archive_author') {
            return is_author();
        }
        if ($location === 'archive_search') {
            return is_search();
        }
        // Specific page by ID: single_page_{id}
        if (strpos($location, 'single_page_') === 0) {
            $page_id = absint(str_replace('single_page_', '', $location));
            return $page_id > 0 && is_page($page_id);
        }
        // Single post types: single_{post_type} (including single_post, single_page, etc.)
        if (strpos($location, 'single_') === 0) {
            $post_type = str_replace('single_', '', $location);
            if (!empty($post_type)) {
                return is_singular($post_type);
            }
        }
        // Search results for a specific post type: archive_search_{post_type}
        if (strpos($location, 'archive_search_') === 0) {
            if (!is_search()) {
                return false;
            }
            $search_post_type = str_replace('archive_search_', '', $location);
            $query_post_type  = get_query_var('post_type');
            if (empty($query_post_type)) {
                return $search_post_type === 'post';
            }
            if (is_array($query_post_type)) {
                return in_array($search_post_type, $query_post_type, true);
            }
            return $query_post_type === $search_post_type;
        }
        // Taxonomy archives: archive_{taxonomy}
        if (strpos($location, 'archive_') === 0) {
            $taxonomy = str_replace('archive_', '', $location);
            if (empty($taxonomy)) {
                return false;
            }
            if ($taxonomy === 'category') {
                return is_category();
            }
            if ($taxonomy === 'post_tag') {
                return is_tag();
            }
            if ($taxonomy === 'post_format') {
                return is_tax('post_format');
            }
            return is_tax($taxonomy);
        }
        return false;
    }
}