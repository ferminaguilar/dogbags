<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class VP_Template_Library_Source extends Elementor\TemplateLibrary\Source_Base
{
    public $slug_post_map = [];
    const LICENSE_OPTION_KEY = 'valuepack-addons_key';
    const LICENSE_HEADER_KEY = 'X-VP-License-Key';
    const LICENSE_SITE_HEADER_KEY = 'X-VP-License-Site';

    public function get_id()
    {
        return 'vp-template-library';
    }

    public function get_title()
    {
        return esc_html__('Template Library', 'vp-library-syncer');
    }

    public function register_data()
    {
        // Register the data with Elementor
    }

    public function get_items($args = [])
    {
        // Force timeout for THIS request
        add_filter('http_request_timeout', function () {
            return 150;
        });

        add_filter('http_request_args', function ($request_args, $request_url) {
            if (strpos($request_url, 'vp-library') !== false) {
                $request_args['timeout'] = 150;
            }
            return $request_args;
        }, 10, 2);

        $response = wp_remote_get(
            VALUE_PACK_LIBRARY_SYNCER_API_URL . 'wp-json/vp-library/v1/templates',
            [
                'timeout'     => 150,
                'redirection' => 5,
                'httpversion' => '1.1',
                'blocking'    => true,
            ]
        );

        if (is_wp_error($response)) {
            error_log('VP Library API Error: ' . $response->get_error_message());
            return [];
        }

        $body = wp_remote_retrieve_body($response);

        if (empty($body)) {
            return [];
        }

        $data  = json_decode($body, true);
        $items = [];

        if (is_array($data)) {
            $templates = isset($data['elements']) && is_array($data['elements']) ? $data['elements'] : $data;
            foreach ($templates as $template) {
                if (!is_array($template) || empty($template['id'])) {
                    continue;
                }

                $items[$template['id']] = [
                    'template_id' => $template['id'],
                    'title'       => $template['title'] ?? '',
                    'type'        => $template['type'] ?? '',
                    'thumbnail'   => $template['image'] ?? ($template['thumbnail'] ?? ''),
                    'source'      => $this->get_id(),
                ];
            }
        }

        return $items;
    }



    public function get_item($template_id)
    {
        $templates = $this->get_items();

        return isset($templates[$template_id]) ? $templates[$template_id] : false;
    }

    private function get_template_content($template_id)
    {
        // Force WordPress timeout for this specific request
        add_filter('http_request_timeout', function () {
            return 150; // 150 seconds
        });

        add_filter('http_request_args', function ($args, $url) {
            if (strpos($url, 'vp-library') !== false) {
                $args['timeout'] = 150;
            }
            return $args;
        }, 10, 2);

        $license_key = trim((string) get_option(self::LICENSE_OPTION_KEY, ''));
        $headers = [];
        if ($license_key !== '') {
            $headers[self::LICENSE_HEADER_KEY] = sanitize_text_field($license_key);
        }
        $headers[self::LICENSE_SITE_HEADER_KEY] = esc_url_raw(home_url('/'));

        $response = wp_remote_get(
            VALUE_PACK_LIBRARY_SYNCER_API_URL . 'wp-json/vp-library/v1/templates?template-id=' . $template_id,
            [
                'timeout'     => 150,
                'redirection' => 5,
                'httpversion' => '1.1',
                'blocking'    => true,
                'headers'     => $headers,
            ]
        );

        if (is_wp_error($response) || !is_array($response)) {
            return $response;
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        $body   = wp_remote_retrieve_body($response);
        $data   = json_decode($body, true);

        if ($status < 200 || $status >= 300) {
            $message = __('The element can\'t be loaded from the server.', 'vp-library-syncer');
            if (is_array($data) && !empty($data['message'])) {
                $message = (string) $data['message'];
            }
            $code = is_array($data) && !empty($data['code']) ? (string) $data['code'] : 'http_error';

            return new WP_Error($code, $message, ['status' => $status, 'response' => $data]);
        }

        if (!is_array($data)) {
            return new WP_Error('template_error', __('Error whilst getting template', 'vp-library-syncer'));
        }

        if (isset($data['code'], $data['message']) && empty($data['content'])) {
            return new WP_Error(
                (string) $data['code'],
                (string) $data['message'],
                isset($data['data']) && is_array($data['data']) ? $data['data'] : []
            );
        }

        if (isset($data['error'])) {
            return new WP_Error('template_error', __('Error whilst getting template', 'vp-library-syncer'));
        }

        return $data;
    }

    /**
     * Normalize Elementor tree from remote API (array, JSON string, or { elements: [...] } export).
     *
     * @param mixed $raw Raw value from API `content.content`.
     * @return array|WP_Error
     */
    private function normalize_remote_elementor_tree($raw)
    {
        $value = $raw;

        while (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (JSON_ERROR_NONE !== json_last_error() || null === $decoded) {
                break;
            }
            $value = $decoded;
        }

        if (!is_array($value)) {
            return new WP_Error('vp_empty_template_data', __('Template contained no Elementor data.', 'vp-library-syncer'));
        }

        if (isset($value['elements']) && is_array($value['elements'])) {
            $value = $value['elements'];
        }

        if (isset($value['elType']) && is_string($value['elType'])) {
            $value = [$value];
        }

        if ($value === []) {
            return new WP_Error('vp_empty_template_data', __('Template contained no elements.', 'vp-library-syncer'));
        }

        return $value;
    }

    public function get_data(array $args, $context = 'display')
    {



        // Step 1: Prepare initial data
        $data = ('update' === $context)
            ? $args['data']
            : $this->get_template_content($args['template_id']);



        if (is_wp_error($data)) {
            return $data;
        }

        if (!is_array($data) || empty($data['content']) || !is_array($data['content'])) {
            return new WP_Error('vp_invalid_template_payload', __('Invalid template response from server.', 'vp-library-syncer'));
        }

        $raw_elements = $this->normalize_remote_elementor_tree($data['content']['content'] ?? null);
        if (is_wp_error($raw_elements)) {
            return $raw_elements;
        }

        Elementor\Plugin::$instance->uploads_manager->set_elementor_upload_state(true);
        $raw_elements = $this->replace_elements_ids($raw_elements);
        $raw_elements = $this->process_export_import_content($raw_elements, 'on_import');
        Elementor\Plugin::$instance->uploads_manager->set_elementor_upload_state(false);

        $data['content']['content']       = $raw_elements;
        $data['content']['elementor_data'] = $raw_elements;

        $post_id = $args['editor_post_id'];

        $document = Elementor\Plugin::$instance->documents->get($post_id);
        if ($document && true === $document::get_property('has_elements')) {
            $processed = $document->get_elements_raw_data($data['content']['elementor_data'], true);
            if (!empty($processed)) {
                $data['content']['elementor_data'] = $processed;
            }
        }
        $data['content']['elementor_data'] = $this->process_media_imports($data['content']['elementor_data'], $post_id);

        // Step 3: Try to get remote API data 

        $elementor_content = $data['content']['elementor_data'];



        // Step 4: Handle associated data (header and footer)
        // if (
        //     isset($data['content']['associated']) &&
        //     !empty($data['content']['associated']) &&
        //     is_array($data['content']['associated'])
        // ) {
        //     $elementor_content = $this->merge_associated_data($data['content']['associated'], $elementor_content);
        // }

        // Step 5: If remote meta found — handle Mega Menu cloning
        if (
            isset($data['content']['associated']) &&
            !empty($data['content']['associated'])
        ) {


            $is_header_processed = false;
            $is_footer_processed = false;

            foreach ($data['content']['associated'] as $key => $value) {

                // PROCESS HEADER FIRST
                if ($value['slug'] == 'header' && !$is_header_processed) {

                    $elementor_data = $value['elementor_data'];
                    $meta_values = $this->flatten_associated($value['nested_associated']);
                    $meta_values['header_data'] = $value;

                    $this->process_associated_cloning('header', $meta_values, $elementor_data, $post_id);

                    $is_header_processed = true;
                    continue;
                }

                // PROCESS FOOTER ONLY AFTER HEADER
                if ($value['slug'] == 'footer' && $is_header_processed && !$is_footer_processed) {

                    $elementor_data = $value['elementor_data'];
                    $meta_values = $this->flatten_associated($value['nested_associated']);
                    $meta_values['footer_data'] = $value;

                    $this->process_associated_cloning('footer', $meta_values, $elementor_data, $post_id);

                    $is_footer_processed = true;
                    continue;
                }
            }

            $meta_data =   $data['content']['associated'];

            if ($is_header_processed) {
                foreach ($data['content']['associated'] as $key => $value) {
                    if ($value['slug'] == 'header') {
                        unset($meta_data[$key]);
                    }
                }
            }
            if ($is_footer_processed) {
                foreach ($data['content']['associated'] as $key => $value) {
                    if ($value['slug'] == 'footer') {
                        unset($meta_data[$key]);
                    }
                }
            }

            $meta_values = $this->flatten_associated($meta_data);

            $elementor_content = $this->process_associated_cloning('block', $meta_values, $elementor_content, $post_id);
        }


        // Step 6: If context = 'update', update post meta
        if ('update' === $context) {
            update_post_meta($post_id, '_elementor_data', $elementor_content);
        }

        // Step 7: Return final processed data.
        // Elementor core sources return `content` as the elements array; this theme's modal JS also reads `elementor_data`.
        $data['content']['elementor_data'] = $elementor_content;
        $data['content']['content']        = $elementor_content;

        return [
            'content'        => $elementor_content,
            'elementor_data' => $elementor_content,
            'associated'     => isset($data['content']['associated']) ? $data['content']['associated'] : [],
        ];
    }


    public  function flatten_associated($associated)
    {
        $flat = [];

        foreach ($associated as $item) {

            // Store item copy WITHOUT nested_associated
            $clean = $item;
            unset($clean['nested_associated']);
            $flat[] = $clean;

            // If nested exists, flatten it too
            if (!empty($item['nested_associated']) && is_array($item['nested_associated'])) {
                $nested = $this->flatten_associated($item['nested_associated']);
                $flat = array_merge($flat, $nested);
            }
        }

        return $flat;
    }

    /**
     * Create a CubeWP form (cwp_forms) from API export data so it matches the source form.
     *
     * @param array $form_data From API: 'post' => post title/name/content, 'meta' => group meta, 'fields' => field_name => field_options.
     * @return int|null New form post ID or null on failure.
     */
    private function create_form_from_export_data($form_data)
    {
        if (empty($form_data['post']) || !is_array($form_data['post'])) {
            return null;
        }
        if (!function_exists('CWP') || !class_exists('CubeWp_Custom_Fields_Processor')) {
            return null;
        }

        $post_title   = isset($form_data['post']['post_title']) ? sanitize_text_field($form_data['post']['post_title']) : 'Imported Form';
        $post_name    = isset($form_data['post']['post_name']) ? sanitize_title($form_data['post']['post_name']) : 'imported-form';
        $post_content = isset($form_data['post']['post_content']) ? wp_kses_post($form_data['post']['post_content']) : '';

        $existing = get_page_by_path($post_name, OBJECT, 'cwp_forms');
        if ($existing) {
            return $existing->ID;
        }

        $new_post_id = wp_insert_post([
            'post_type'    => 'cwp_forms',
            'post_title'   => $post_title,
            'post_name'    => $post_name,
            'post_content' => $post_content,
            'post_status'  => 'publish',
        ]);

        if (is_wp_error($new_post_id) || !$new_post_id) {
            return null;
        }

        $meta = isset($form_data['meta']) && is_array($form_data['meta']) ? $form_data['meta'] : [];
        if (isset($meta['_cwp_group_display'])) {
            update_post_meta($new_post_id, '_cwp_group_display', $meta['_cwp_group_display']);
        }
        if (isset($meta['_cwp_group_login'])) {
            update_post_meta($new_post_id, '_cwp_group_login', $meta['_cwp_group_login']);
        }
        if (isset($meta['_cwp_group_button_text'])) {
            update_post_meta($new_post_id, '_cwp_group_button_text', sanitize_text_field($meta['_cwp_group_button_text']));
        }

        $field_options = CWP()->get_custom_fields('custom_forms');
        $field_options = $field_options === '' ? [] : (array) $field_options;
        $group_field_names = [];

        if (!empty($form_data['fields']) && is_array($form_data['fields'])) {
            foreach ($form_data['fields'] as $field_name => $field_opts) {
                if (!is_array($field_opts)) {
                    continue;
                }
                $field_name = sanitize_key($field_name);
                if (empty($field_name)) {
                    continue;
                }
                $unique_name = $field_name;
                $counter = 1;
                while (isset($field_options[$unique_name])) {
                    $unique_name = $field_name . '-' . $counter;
                    $counter++;
                }
                $field_opts['group_id'] = $new_post_id;
                $field_opts['name'] = $unique_name;
                $field_options[$unique_name] = $field_opts;
                $group_field_names[] = $unique_name;
            }
            CWP()->update_custom_fields('custom_forms', $field_options);
        }

        $group_fields_meta = isset($meta['_cwp_group_fields']) ? $meta['_cwp_group_fields'] : '';
        if (!empty($group_field_names)) {
            $group_fields_meta = implode(',', $group_field_names);
        }
        update_post_meta($new_post_id, '_cwp_group_fields', $group_fields_meta);

        return $new_post_id;
    }

    /**
     * Merge associated header and footer data with main elementor content
     * 
     * @param array $associated_data Array of associated items
     * @param array $elementor_content Main elementor data array
     * @return array Merged elementor content
     */
    private function merge_associated_data($associated_data, $elementor_content)
    {
        if (!is_array($elementor_content)) {
            $elementor_content = [];
        }

        $header_data = [];
        $footer_data = [];

        // Find header and footer data from associated items
        foreach ($associated_data as $item) {
            if (!isset($item['slug']) || empty($item['slug'])) {
                continue;
            }

            $slug = strtolower($item['slug']);

            // Check for header
            if ($slug === 'header' && isset($item['elementor_data']) && is_array($item['elementor_data'])) {
                if (isset($item['elementor_data'][0]) && is_array($item['elementor_data'][0])) {
                    // Multi-element header
                    $header_data = $item['elementor_data'];
                } else {
                    // Single-element header
                    $header_data = [$item['elementor_data']];
                }
            }

            // Check for footer
            if ($slug === 'footer' && isset($item['elementor_data']) && is_array($item['elementor_data'])) {
                if (isset($item['elementor_data'][0]) && is_array($item['elementor_data'][0])) {
                    // Multi-element footer
                    $footer_data = $item['elementor_data'];
                } else {
                    // Single-element footer
                    $footer_data = [$item['elementor_data']];
                }
            }
        }

        // Compose final output: header, main content, footer
        $result = [];
        if (!empty($header_data)) {
            $result = array_merge($result, $header_data);
        }
        if (!empty($elementor_content)) {
            $result = array_merge($result, $elementor_content);
        }
        if (!empty($footer_data)) {
            $result = array_merge($result, $footer_data);
        }

        return $result;
    }


    /**
     * Handle cloning of Mega Menu templates and replacing references
     */
    private function process_associated_cloning($slug_header_footer, $meta_values, $elementor_content, $post_id)
    {


        if ($slug_header_footer == 'header') {
            $slug_header =  $slug_header_footer;
        } else {
            $slug_header =  $slug_header_footer;
        }

        foreach ($meta_values as $item) {
            if (empty($item['element-id'])) {
                continue;
            }



            if ($item['slug'] == 'form') {
                $form_id = null;
                $display_on = $item['display_on'] ?? '';
                if (!empty($item['form_data']) && !empty($item['form_data']['post'])) {
                    $form_id = $this->create_form_from_export_data($item['form_data']);
                }
                if (!$form_id) {
                    $form_slug = isset($item['element-label']) ? sanitize_title($item['element-label']) : 'form';
                    $existing_form = get_page_by_path($form_slug, OBJECT, 'cwp_forms');
                    if ($existing_form) {
                        $form_id = $existing_form->ID;
                    } else {
                        $default_form_data = array(
                            'form_name'         => isset($item['element-label']) ? $item['element-label'] : 'Contact Form',
                            'form_slug'         => $form_slug,
                            'description'       => 'A simple contact form with email field',
                            'email_label'       => 'Email Address',
                            'email_name'        => 'contact-email',
                            'email_placeholder' => 'Enter your email address',
                            'email_required'     => true,
                        );
                        $form_id = self::cubewp_create_form_with_email($default_form_data);
                    }
                }

                if ($form_id) {
                    $this->cwp_replace_select_newsletter($elementor_content, $display_on, $form_id);
                }
            }


            $element_id = $item['element-id'];
            $post_type  = $item['post_type'];
            $elementor_data  = $item['elementor_data'];


            if ($post_type == 'cubewp-tb') {
                $display_on = $item['display_on'] ?? '';
                $location   = $item['location'] ?? '';
                if (!empty($display_on) && $display_on != 'header' && $display_on != 'footer') {
                    $new_post_id = $this->clone_remote_template($elementor_data, $element_id, $post_type, $display_on, $location);
                } else {
                    $new_post_id = '';
                }

                // Store mapping of old slug to new post ID for later use
                if (!isset($this->slug_post_map)) {
                    $this->slug_post_map[$display_on] = [];
                }
                // Only process slug mapping and replacements for mega-menus
                if ($display_on == 'mega-menu') {
                    if (isset($item['slug'])) {
                        $slug = $item['slug'];
                        $this->slug_post_map[$display_on][$slug] = $new_post_id;
                        $this->cwp_replace_select_mega_menu($elementor_content, $slug, $new_post_id);
                    }
                } elseif ($display_on == 'popup') {
                    if (isset($item['slug'])) {
                        $slug = $item['slug'];
                        $slug_new = get_post_field('post_name', $new_post_id);

                        // Example: popup_settings string: "popup_trigger,on_click|popup_position,center_position|popup_width,556,px|popup_height,500,px"
                        $popup_settings_string = isset($item['popup_settings']) ? $item['popup_settings'] : '';
                        $popup_settings_arr = array();

                        if (!empty($popup_settings_string)) {
                            $pairs = explode('|', $popup_settings_string);
                            foreach ($pairs as $pair) {
                                $parts = explode(',', $pair);
                                // Handle width/height with unit (e.g. popup_width,556,px)
                                if (count($parts) === 3 && in_array($parts[0], ['popup_width', 'popup_height'])) {
                                    $popup_settings_arr[$parts[0]] = [
                                        'size' => $parts[1],
                                        'unit' => $parts[2]
                                    ];
                                } elseif (count($parts) === 2) {
                                    $popup_settings_arr[$parts[0]] = $parts[1];
                                }
                            }
                        }

                        // Get current settings, merge and update
                        $settings_page = get_post_meta($new_post_id, '_elementor_page_settings', true);

                        if (!is_array($settings_page)) {
                            $settings_page = array();
                        }
                        $settings_page = array_merge($settings_page, $popup_settings_arr);

                        update_post_meta($new_post_id, '_elementor_page_settings', $settings_page);

 
                        update_post_meta($new_post_id, 'post_popup_close', "vpack-triger-close" . $slug_new);
                        update_post_meta($new_post_id, 'post_popup_open', "vpack-triger" . $slug_new);


                        $slugnew = 'vpack-triger' . $slug_new; 
                        $post_popup_open =    get_post_meta($new_post_id, 'post_popup_open', true);
                        $this->cwp_replace_select_popup($elementor_content, $slug, $post_popup_open);
                        $elementor_data_new = get_post_meta($new_post_id, '_elementor_data', true);
                        if (! empty($elementor_data_new)) {
                            $this->slug_post_map[$display_on][$slug] = $new_post_id;
                            $elementor_array = $elementor_data_new;
                            $slugnew_close = 'vpack-triger-close' . $slug_new;
                            $clean_slug = str_replace('vpack-triger', '', $slug);
                            $slug = 'vpack-triger-close' . $clean_slug;
                            $this->cwp_replace_select_popup_close($elementor_array, $slug, $slugnew_close);
                            update_post_meta($new_post_id, '_elementor_data', wp_slash($elementor_array));
                        }
                    }
                } elseif ($display_on == 'postcard') {
                    if (isset($item['slug'])) {
                        $slug = $item['slug'];
                        $slug_tax_postcard = isset($item['taxonomy-post-slug']) ? $item['taxonomy-post-slug'] : '';
                        $slug_new = get_post_field('post_name', $new_post_id);
                        $slugnew = '_cwp_elmentor_' . $slug_new;

                        $this->cwp_replace_select_post_card($elementor_content, $slug, $slugnew, $slug_tax_postcard);
                    }
                } elseif ($display_on == 'termcard') {
                    if (isset($item['slug'])) {
                        $slug = $item['slug'];
                        $slug_new = get_post_field('post_name', $new_post_id);
                        $slug_tax_postcard = isset($item['taxonomy-post-slug']) ? $item['taxonomy-post-slug'] : '';
                        $slugnew = '_vp_elmentor_term_' . $slug_new;
                        $this->cwp_replace_select_term_card($elementor_content, $slug, $slugnew, $slug_tax_postcard);
                    }
                }
            }
            if ($post_type == 'page') {
                if (isset($item['slug'])) {
                    $display_on = $item['display_on'] ?? '';

                    if ($display_on == 'page_content') {
                        $slug = $item['slug'];
                        $elementor_data  = $item['elementor_data'];
                        $new_post_id = $this->clone_remote_template_page($elementor_data);
                        $this->cwp_replace_select_page_content($elementor_content, $slug, $new_post_id);
                    }
                }
            }
        }
        if (!empty($this->slug_post_map['mega-menu'])) {
            foreach ($this->slug_post_map['mega-menu'] as $key_old => $new_post_id) {
                $slug_new = get_post_field('post_name', $new_post_id);
                if (!empty($this->slug_post_map['popup'])) {
                    foreach ($this->slug_post_map['popup'] as $key => $new_post_ids) {
                        $elementor_data_new = get_post_meta($new_post_ids, '_elementor_data', true);
                        $elementor_arrays = $elementor_data_new;
                        $this->cwp_replace_select_mega_menu($elementor_arrays, $key_old, $slug_new);
                        update_post_meta($new_post_ids, '_elementor_data', wp_slash($elementor_arrays));
                    }
                }
            }
        }

        if ($slug_header == 'header') {
            $post_type = $meta_values['header_data']['post_type'];
            $display_on = $meta_values['header_data']['display_on'];
            $element_id = $meta_values['header_data']['element-id'];
            $location = 'single_page_' . $post_id;
            $new_post_id = $this->clone_remote_template($elementor_content, $element_id, $post_type, $display_on, $location);
        } elseif ($slug_header == 'footer') {
            $post_type = $meta_values['footer_data']['post_type'];
            $display_on = $meta_values['footer_data']['display_on'];
            $element_id = $meta_values['footer_data']['element-id'];
            $location = 'single_page_' . $post_id;
            $this->clone_remote_template($elementor_content, $element_id, $post_type, $display_on, $location);
        } else {
            return $elementor_content;
        }
    }


    /**
     * Clone a remote Elementor template post
     */
    private function clone_remote_template_page($elementor_jsons)
    {
        if (empty($elementor_jsons)) {
            return false;
        }

        // Process media imports
        $elementor_jsons = $this->process_media_imports($elementor_jsons, 0);

        // Create page
        $new_post = array(
            'post_title'   => 'Template Page ' . time(),
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => ''
        );

        $new_post_id = wp_insert_post($new_post);

        if (is_wp_error($new_post_id)) {
            error_log('Post Creation Error: ' . $new_post_id->get_error_message());
            return false;
        }

        // Elementor requires JSON encoded data
        $elementor_data = wp_json_encode($elementor_jsons);

        // Elementor meta fields
        update_post_meta($new_post_id, '_elementor_data', wp_slash($elementor_data));
        update_post_meta($new_post_id, '_elementor_edit_mode', 'builder');
        update_post_meta($new_post_id, '_elementor_template_type', 'wp-page');
        update_post_meta($new_post_id, '_elementor_version', ELEMENTOR_VERSION);
        update_post_meta($new_post_id, '_wp_page_template', 'elementor_canvas');

        return $new_post_id;
    }


    /**
     * Clone a remote Elementor template post
     */
    private function clone_remote_template($elementor_jsons, $element_id, $post_type, $display_on, $location)
    {
        if (empty($elementor_jsons)) {
            return;
        }

        // Process media imports for associated templates
        $elementor_jsons = $this->process_media_imports($elementor_jsons, 0);

        $new_post = [
            'post_title'   => 'Template ' . $display_on . ' ' . $element_id,
            'post_status'  => 'publish',
            'post_type'    => $post_type,
            'post_content' => '',
        ];

        $new_post_id = wp_insert_post($new_post);

        if (is_wp_error($new_post_id)) {
            error_log('Post Creation Error: ' . $new_post_id->get_error_message());
            return;
        }

        // Add metadata
        update_post_meta($new_post_id, 'template_type', $display_on);
        update_post_meta($new_post_id, 'template_location', $location);
        update_post_meta($new_post_id, '_elementor_data', wp_slash($elementor_jsons));
        update_post_meta($new_post_id, '_elementor_edit_mode', 'builder');
        update_post_meta($new_post_id, '_source_template', $element_id);

        return $new_post_id;
    }
    /**
     * Recursively replace select_mega_menu IDs
     */
    private function cwp_replace_select_page_content(&$array, $old_value, $new_value)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->cwp_replace_select_page_content($value, $old_value, $new_value);
            } elseif ($key === 'claim_form_page_id' && $value == $old_value) {
                $value = $new_value;
            }
        }
    }

    /**
     * Recursively replace select_mega_menu IDs
     */
    private function cwp_replace_select_newsletter(&$array, $old_value, $new_value)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->cwp_replace_select_newsletter($value, $old_value, $new_value);
            } elseif ($key === 'select_form' && $value == $old_value) {
                $value = $new_value;
            }
        }
    }

    /**
     * Recursively replace select_mega_menu IDs
     */
    private function cwp_replace_select_mega_menu(&$array, $old_value, $new_value)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->cwp_replace_select_mega_menu($value, $old_value, $new_value);
            } elseif ($key === 'select_mega_menu' && $value == $old_value) {
                $value = $new_value;
            }
        }
    }
    /**
     * Recursively replace cwp_replace_select_popup IDs
     */
    private function cwp_replace_select_term_card(&$array, $old_value, $new_value, $slug_tax_postcard)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->cwp_replace_select_term_card($value, $old_value, $new_value, $slug_tax_postcard);
            } elseif ($key === 'output_style' && $value == $old_value) {
                $value = $new_value;
            } elseif ($key ===  'output_style_' . $slug_tax_postcard && $value == $old_value) {
                $value = $new_value;
            }
        }
    }
    /**
     * Recursively replace cwp_replace_select_popup IDs
     */
    private function cwp_replace_select_post_card(&$array, $old_value, $new_value, $slug_tax_postcard)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->cwp_replace_select_post_card($value, $old_value, $new_value, $slug_tax_postcard);
            } elseif (($key === 'product_card_style' && $value == $old_value) || ($key === 'post_card_style' && $value == $old_value) || ($key === 'store-locator_card_style' && $value == $old_value) || ($key === $slug_tax_postcard . '_card_style' && $value == $old_value)) {
                $value = $new_value;
            } elseif (is_string($value)) {
                $value = str_replace($old_value, $new_value, $value);
            }
        }
    }
    /**
     * Recursively replace cwp_replace_select_popup IDs
     */
    private function cwp_replace_select_popup(&$array, $old_value, $new_value)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->cwp_replace_select_popup($value, $old_value, $new_value);
            } elseif ($key === 'valuepack_popup_select_open' && $value == $old_value) {
                $value = $new_value;
            } elseif ($key === '_css_classes' && $value == $old_value) {
                $value = $new_value;
            } elseif ($key === '_element_id' && $value == $old_value) {
                $value = $new_value;
            }
        }
    }

    /**
     * Recursively replace cwp_replace_select_popup IDs
     */
    private function cwp_replace_select_popup_close(&$array, $old_value, $new_value)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->cwp_replace_select_popup_close($value, $old_value, $new_value);
            } elseif ($key === 'valuepack_popup_select_close' && $value == $old_value) {
                $value = $new_value;
            } elseif ($key === '_css_classes' && $value == $old_value) {
                $value = $new_value;
            } elseif ($key === '_element_id' && $value == $old_value) {
                $value = $new_value;
            }
        }
    }


    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
    private static function cubewp_create_form_with_email($form_data = array())
    {

        // Check if CubeWP framework is loaded
        if (! function_exists('CWP') || ! class_exists('CubeWp_Custom_Fields_Processor')) {
            return new WP_Error('cubewp_not_loaded', __('CubeWP Framework is not loaded.', 'cubewp-forms'));
        }

        // Default form data
        $defaults = array(
            'form_name'         => 'Contact Form',
            'form_slug'          => '',
            'description'        => '',
            'email_label'        => 'Email',
            'email_name'         => '',
            'email_placeholder'  => 'e.g. john@example.com',
            'email_required'     => true,
        );

        $form_data = wp_parse_args($form_data, $defaults);

        // Sanitize form name
        $form_name = sanitize_text_field($form_data['form_name']);
        if (empty($form_name)) {
            return new WP_Error('invalid_form_name', __('Form name cannot be empty.', 'cubewp-forms'));
        }

        // Generate form slug if not provided
        $form_slug = !empty($form_data['form_slug'])
            ? sanitize_title($form_data['form_slug'])
            : sanitize_title($form_name);

        // Generate email field name if not provided
        $email_name = !empty($form_data['email_name'])
            ? sanitize_text_field($form_data['email_name'])
            : sanitize_title($form_name) . '-email';

        // Ensure email field name is unique
        $email_name = self::cwp_forms_get_unique_field_name($email_name);

        // Create the form post
        $post_data = array(
            'post_type'    => 'cwp_forms',
            'post_title'   => $form_name,
            'post_content' => wp_strip_all_tags($form_data['description']),
            'post_status'  => 'publish',
            'post_name'    => $form_slug,
        );

        $form_id = wp_insert_post($post_data, true);

        if (is_wp_error($form_id)) {
            return $form_id;
        }

        // Create email field data structure
        $field_id = 'cwp_field_' . time() . '_' . wp_rand(10000, 99999);

        $email_field = array(
            'label'              => sanitize_text_field($form_data['email_label']),
            'name'               => $email_name,
            'type'               => 'email',
            'description'        => '',
            'default_value'      => '',
            'minimum_value'      => '0',
            'maximum_value'      => '100',
            'steps_count'        => '1',
            'file_types'         => '',
            'upload_size'        => '',
            'max_upload_files'   => '',
            'placeholder'       => sanitize_text_field($form_data['email_placeholder']),
            'options'            => '{"label":["",""],"value":["",""]}',
            'char_limit'         => '',
            'filter_post_types' => '',
            'filter_taxonomy'    => '',
            'filter_user_roles'  => '',
            'rel_attr'           => 'do-follow',
            'appearance'         => '',
            'required'           => $form_data['email_required'] ? '1' : '0',
            'validation_msg'     => __('Please provide a valid email address.', 'cubewp-forms'),
            'files_save'         => 'ids',
            'files_save_separator' => 'array',
            'id'                 => $field_id,
            'class'              => '',
            'container_class'    => '',
            'conditional_field'  => '',
            'conditional_operator' => '',
            'conditional_value'  => '',
            'group_id'           => $form_id,
        );

        // Save the email field using CubeWP framework
        // We need to use the custom_forms field type
        $field_options = CWP()->get_custom_fields('custom_forms');
        $field_options = $field_options == '' ? array() : $field_options;
        $field_options[$email_name] = $email_field;
        CWP()->update_custom_fields('custom_forms', $field_options);

        // Update form meta to include the field
        update_post_meta($form_id, '_cwp_group_fields', $email_name);

        // Set default form settings
        update_post_meta($form_id, '_cwp_group_display', '0');
        update_post_meta($form_id, '_cwp_group_login', '0');
        update_post_meta($form_id, '_cwp_group_button_text', __('Submit', 'cubewp-forms'));

        return $form_id;
    }

    /**
     * Get unique field name by checking if it already exists
     *
     * @param string $field_name Base field name.
     * @return string Unique field name.
     * @since  1.0.0
     */
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
    private static function cwp_forms_get_unique_field_name($field_name)
    {
        $custom_fields = CWP()->get_custom_fields('custom_forms');
        $custom_fields = $custom_fields == '' ? array() : $custom_fields;

        $original_name = $field_name;
        $counter = 1;

        while (isset($custom_fields[$field_name])) {
            $field_name = $original_name . '-' . $counter;
            $counter++;
        }

        return $field_name;
    }

    /**
     * Process media imports including SVGs from remote site
     * 
     * @param array $elementor_data Elementor data array
     * @param int $post_id Post ID where template is being imported
     * @return array Processed elementor data with updated attachment IDs
     */
    private function process_media_imports($elementor_data, $post_id)
    {
        if (!is_array($elementor_data)) {
            return $elementor_data;
        }

        // Map to store old attachment ID => new attachment ID
        $attachment_map = [];

        // Find all attachment IDs in the data
        $attachment_ids = $this->find_attachment_ids($elementor_data);

        // Process each attachment
        foreach ($attachment_ids as $old_attachment_id) {
            if (empty($old_attachment_id) || isset($attachment_map[$old_attachment_id])) {
                continue;
            }

            // Import the attachment from remote site
            $new_attachment_id = $this->import_remote_attachment($old_attachment_id);

            if ($new_attachment_id) {
                $attachment_map[$old_attachment_id] = $new_attachment_id;
            }
        }

        // Replace old attachment IDs with new ones
        if (!empty($attachment_map)) {
            $elementor_data = $this->replace_attachment_ids($elementor_data, $attachment_map);
        }

        return $elementor_data;
    }

    /**
     * Recursively find all attachment IDs in Elementor data
     * 
     * @param array $data Elementor data array
     * @param array $attachment_ids Found attachment IDs (passed by reference)
     * @return array Array of attachment IDs
     */
    private function find_attachment_ids($data, &$attachment_ids = [])
    {
        if (!is_array($data)) {
            return $attachment_ids;
        }

        foreach ($data as $key => $value) {
            // Check for common attachment ID keys
            if (
                in_array($key, ['id', 'attachment_id', 'image', 'image_id', 'icon', 'icon_id', 'background_image', 'background_image_id', '__id']) &&
                is_numeric($value) && $value > 0
            ) {
                $attachment_ids[] = (int) $value;
            }
            // Check for URL-based image references that might need importing
            elseif (
                in_array($key, ['url', 'image_url', 'icon_url', 'background_image_url']) &&
                is_string($value) && !empty($value)
            ) {
                // Extract attachment ID from URL if it's a WordPress attachment URL
                $attachment_id = $this->get_attachment_id_from_url($value);
                if ($attachment_id) {
                    $attachment_ids[] = $attachment_id;
                }
            }
            // Recursively search nested arrays
            elseif (is_array($value)) {
                $this->find_attachment_ids($value, $attachment_ids);
            }
        }

        // Remove duplicates
        $attachment_ids = array_unique($attachment_ids);
        return $attachment_ids;
    }

    /**
     * Get attachment ID from WordPress media URL
     * 
     * @param string $url Media URL
     * @return int|false Attachment ID or false if not found
     */
    private function get_attachment_id_from_url($url)
    {
        // Check if URL contains attachment ID in query string
        if (preg_match('/attachment_id=(\d+)/', $url, $matches)) {
            return (int) $matches[1];
        }

        // Try to find attachment by URL in database
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE guid = %s",
            $url
        ));

        if (!empty($attachment)) {
            return (int) $attachment[0];
        }

        return false;
    }

    /**
     * Import attachment from remote site
     * 
     * @param int $remote_attachment_id Remote attachment ID
     * @return int|false New local attachment ID or false on failure
     */
    private function import_remote_attachment($remote_attachment_id)
    {
        // Check if attachment already exists locally (by checking if we've imported it before)
        $existing_id = $this->get_local_attachment_by_remote_id($remote_attachment_id);
        if ($existing_id) {
            return $existing_id;
        }

        // Get attachment data from remote API
        $remote_url = VALUE_PACK_LIBRARY_SYNCER_API_URL . 'wp-json/wp/v2/media/' . $remote_attachment_id;
        $response = wp_remote_get($remote_url, [
            'timeout' => 30,
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            error_log('VP Library: Failed to fetch remote attachment ' . $remote_attachment_id);
            return false;
        }

        $attachment_data = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($attachment_data) || !isset($attachment_data['source_url'])) {
            return false;
        }

        $file_url = $attachment_data['source_url'];
        $file_name = basename($file_url);
        $mime_type = isset($attachment_data['mime_type']) ? $attachment_data['mime_type'] : '';

        // Download the file
        $file_array = [];
        $file_array['name'] = $file_name;

        // Use WordPress download_url function
        $temp_file = download_url($file_url);

        if (is_wp_error($temp_file)) {
            error_log('VP Library: Failed to download file: ' . $temp_file->get_error_message());
            return false;
        }

        $file_array['tmp_name'] = $temp_file;

        // Prepare file for upload
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Handle SVG files specifically
        if ($mime_type === 'image/svg+xml' || pathinfo($file_name, PATHINFO_EXTENSION) === 'svg') {
            // Ensure SVG uploads are allowed
            add_filter('upload_mimes', [$this, 'allow_svg_upload_for_import'], 10, 1);
            add_filter('wp_check_filetype_and_ext', [$this, 'fix_svg_mime_type'], 10, 4);
        }

        // Upload file to media library
        $attachment_id = media_handle_sideload($file_array, 0);

        // Clean up temporary file
        if (file_exists($temp_file)) {
            @unlink($temp_file);
        }

        // Remove filters
        remove_filter('upload_mimes', [$this, 'allow_svg_upload_for_import']);
        remove_filter('wp_check_filetype_and_ext', [$this, 'fix_svg_mime_type']);

        if (is_wp_error($attachment_id)) {
            error_log('VP Library: Failed to import attachment: ' . $attachment_id->get_error_message());
            return false;
        }

        // Store mapping for future reference
        update_post_meta($attachment_id, '_vp_remote_attachment_id', $remote_attachment_id);

        // Update attachment metadata if available
        if (isset($attachment_data['alt_text'])) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($attachment_data['alt_text']));
        }

        if (isset($attachment_data['title']['rendered'])) {
            wp_update_post([
                'ID' => $attachment_id,
                'post_title' => sanitize_text_field($attachment_data['title']['rendered']),
            ]);
        }

        return $attachment_id;
    }

    /**
     * Get local attachment ID by remote attachment ID
     * 
     * @param int $remote_attachment_id Remote attachment ID
     * @return int|false Local attachment ID or false
     */
    private function get_local_attachment_by_remote_id($remote_attachment_id)
    {
        global $wpdb;
        $local_id = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_vp_remote_attachment_id' AND meta_value = %d LIMIT 1",
            $remote_attachment_id
        ));

        return $local_id ? (int) $local_id : false;
    }

    /**
     * Replace attachment IDs in Elementor data
     * 
     * @param array $data Elementor data
     * @param array $attachment_map Map of old_id => new_id
     * @return array Updated data
     */
    private function replace_attachment_ids($data, $attachment_map)
    {
        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            // Replace attachment IDs in common keys
            if (
                in_array($key, ['id', 'attachment_id', 'image', 'image_id', 'icon', 'icon_id', 'background_image', 'background_image_id', '__id']) &&
                is_numeric($value) && isset($attachment_map[(int) $value])
            ) {
                $data[$key] = $attachment_map[(int) $value];
            }
            // Recursively process nested arrays
            elseif (is_array($value)) {
                $data[$key] = $this->replace_attachment_ids($value, $attachment_map);
            }
        }

        return $data;
    }

    /**
     * Allow SVG uploads during import
     * 
     * @param array $mimes Mime types
     * @return array Updated mime types
     */
    public function allow_svg_upload_for_import($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Fix SVG mime type during import
     * 
     * @param array $data File data
     * @param string $file File path
     * @param string $filename File name
     * @param array $mimes Mime types
     * @return array Updated file data
     */
    public function fix_svg_mime_type($data, $file, $filename, $mimes)
    {
        if (pathinfo($filename, PATHINFO_EXTENSION) === 'svg') {
            $data['type'] = 'image/svg+xml';
            $data['ext'] = 'svg';
        }
        return $data;
    }





    public function save_item($template_data)
    {
        return new WP_Error('invalid_request', __('Cannot save template to a remote source', 'vp-library-syncer'));
    }

    public function update_item($new_data)
    {
        return new WP_Error('invalid_request', __('Cannot update template to a remote source', 'vp-library-syncer'));
    }

    public function delete_template($template_id)
    {
        return new WP_Error('invalid_request', __('Cannot delete template from a remote source', 'vp-library-syncer'));
    }

    public function export_template($template_id)
    {
        return new WP_Error('invalid_request', __('Cannot export template from a remote source', 'vp-library-syncer'));
    }
}
