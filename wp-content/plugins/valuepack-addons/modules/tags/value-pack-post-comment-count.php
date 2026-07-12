<?php

/**
 * Post Comment Count Dynamic Tag for Elementor
 *
 * @package     ValuePackAddons
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Value_Pack_Post_Comment_Count extends \Elementor\Core\DynamicTags\Tag
{

    /**
     * Get tag name
     *
     * @return string
     */
    public function get_name()
    {
        return 'vp-post-comment-count';
    }

    /**
     * Get tag title
     *
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Post Comment Count', 'valuepack-addons');
    }

    /**
     * Get tag group (category)
     *
     * @return array
     */
    public function get_group()
    {
        return ['vp-tags'];
    }

    /**
     * Get tag categories
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
     * Render the dynamic tag value
     *
     * @return void
     */
    public function render()
    {

        $post_id = get_the_ID();

        if (! $post_id) {
            echo '0';
            return;
        }

        $comment_count = get_comments_number($post_id);

        echo esc_html($comment_count);
    }
}
