<?php
/**
 * Blog Archive Template - Style 1
 * 
 * Displays posts in a grid layout with category filtering
 *
 * @package Woomen  
 * @version 1.0.0
 *
 * Security Considerations:
 * - All output is properly escaped
 * - Inputs are sanitized
 * - Follows WordPress template standards
 */

defined('ABSPATH') || exit;
global $cubewp_frontend, $cwpOptions;
wp_enqueue_style('woomen-dynamic-styles');
wp_enqueue_style('woomen-blogs-styles');
$blog_banner_title = sanitize_text_field(woomen_get_setting('blog_banner_title'));
if (is_home() || is_front_page()) {
    if (empty($blog_banner_title)) {
        $blog_banner_title = esc_html__('Home', 'woomen');
    }
} else {
    $blog_banner_title = get_the_archive_title();
}

$blog_page_id = get_option('page_for_posts');
$categories = get_categories();
$current_category = get_queried_object();
$current_category_id = get_queried_object_id();
?>

<div id="woomen-blogs">
    <div class="container">
        <div class="woomen-blogs-banner">
            <div class="woomen-blogs-title">
                <h1 class="heading"><?php echo wp_kses_post($blog_banner_title); ?></h1>
                <?php
                if (is_category()) {
                    if ($current_category && isset($current_category->name)) {
                        echo '<p class="category">' . esc_html__("Let’s Talk", "woomen") . ' ' . esc_html($current_category->name) . '</p>';
                    }
                } else {
                    echo '<p class="category">' . esc_html__("Let’s Talk", "woomen") . '</p>';
                }
                ?>
            </div>
            <div class="woomen-blogs-categories">
                <ul>
                    <?php
                    $is_any_category_active = is_category();
                    $all_posts_class = $is_any_category_active ? '' : 'active';
                    ?>
                    <li><a href="<?php echo esc_url(get_permalink($blog_page_id)); ?>"
                            class="<?php echo esc_attr($all_posts_class); ?>">All Posts</a></li>
                    <?php
                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            $is_active = ($category->term_id === $current_category_id) ? 'active' : '';
                            ?>
                            <li><a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                                    class="<?php echo esc_attr($is_active); ?>"><?php echo esc_html($category->name); ?></a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="woomen-blogs-style-1">
            <div class="row">
                <?php
                if (have_posts()) {
                    $post_counter = 0;
                    while (have_posts()) {
                        the_post();
                        $post_counter++;
                        if ($post_counter === 1) {
                            echo '<div class="woomen-blogs-banner-post">';
                        }
                        $blog_default_style = sanitize_key(woomen_get_setting('blog_default_style')) ?: 'style_2';
                        // Template part handles its own escaping
                        get_template_part('templates/loop/blog-loop-style-1');
                        if ($post_counter === 1) {
                            echo '</div>';
                        }
                    }
                    the_posts_pagination(array('class' => 'woomen-pagination'));
                } else {
                    ?>
                    <div>
                        <h2><?php esc_html_e('No Results', 'woomen'); ?></h2>
                        <p><?php esc_html_e('Sorry! There is no post available.', 'woomen'); ?></p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>