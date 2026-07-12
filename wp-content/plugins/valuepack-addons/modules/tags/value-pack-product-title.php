<?php

/**
 * Custom Post Title Dynamic Tag for Elementor
 * 
 * @package     ValuePackAddons
 * 
 * Provides a dynamic tag for Elementor to fetch post titles.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Post_Title_Tag
 * 
 * Elementor dynamic tag that fetches post titles.
 */
class Value_Pack_Post_Title extends \Elementor\Core\DynamicTags\Data_Tag
{

    /**
     * Get the tag name
     * 
     * @return string
     */
    public function get_name()
    {
        return 'vp-post-title-tag';
    }

    /**
     * Get the tag title
     * 
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Post Title', 'valuepack-addons');
    }

    /**
     * Get the tag group
     * 
     * @return array
     */
    public function get_group()
    {
        return ['vp-tags'];
    }

    /**
     * Get the tag categories
     * 
     * @return array
     */
    public function get_categories()
    {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    /**
     * Determine if settings are required
     * 
     * @return bool
     */
    public function is_settings_required()
    {
        return true;
    }

    /**
     * Get the tag value
     * 
     * @param array $options Optional options
     * @return string The post title
     */
    public function get_value(array $options = [])
    {
        
        $post_type = $this->get_settings('post_type_field');
        $post_id = absint($this->get_settings('post_id_field'));

        if (empty($post_type) || !post_type_exists($post_type)) {
            return esc_html__('Invalid post type selected', 'valuepack-addons');
        }

        if (!empty($post_id)) {
            $post = get_post($post_id);

            if ($post && $post->post_type === $post_type && $post->post_status === 'publish') {
                return get_the_title($post_id);
            }
        }

        $fallback_post_id = $this->get_first_published_post_id($post_type);

        if (!$fallback_post_id) {
            return esc_html__('No published posts available', 'valuepack-addons');
        }

        return get_the_title($fallback_post_id);
    }

    /**
     * Register controls for the tag
     */
    protected function register_controls()
    {
        $this->add_control(
            'post_type_field',
            [
                'type'    => \Elementor\Controls_Manager::SELECT,
                'label'   => esc_html__('Select Post Type', 'valuepack-addons'),
                'options' => $this->get_post_type_options(),
                'default' => 'post',
            ]
        );

        $this->add_control(
            'post_id_field',
            [
                'type'    => \Elementor\Controls_Manager::TEXT,
                'label'   => esc_html__('Enter Post ID', 'valuepack-addons'),
                'default' => '',
            ]
        );
    }

    /**
     * Get available post type options
     * 
     * @return array
     */
    private function get_post_type_options()
    {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];

        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }

        return $options;
    }  
    
    /**
     * Get the first published post ID for a post type.
     *
     * @param string $post_type The post type to query.
     * @return int|null The post ID or null if not found.
     */
    private function get_first_published_post_id($post_type)
    {
        $posts = get_posts(
            [
                'post_type'      => $post_type,
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'fields'         => 'ids',
                'no_found_rows'  => true,
            ]
        );

        if (empty($posts) || !isset($posts[0])) {
            return null;
        }

        return (int) $posts[0];
    }
}

