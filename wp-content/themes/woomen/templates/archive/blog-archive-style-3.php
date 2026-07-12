<?php
/**
 * Blog Archive Template - Style 3
 *
 * Displays posts in grid layout with sidebar
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
wp_enqueue_style('woomen-blogs-styles');
$blog_banner_title = sanitize_text_field(woomen_get_setting('blog_banner_title'));
if (is_home() || is_front_page()) {
    if (empty($blog_banner_title)) {
        $blog_banner_title = esc_html__('Blogs', 'woomen');
    }
} else {
    $blog_banner_title = get_the_archive_title();
}
?>
<div id="woomen-blogs">
    <div class="container">
        <div class="woomen-blogs-banner">
            <div class="woomen-blogs-title">
                <h1 class="heading"><?php echo wp_kses_post($blog_banner_title); ?></h1>
                <?php
                if (is_category()) {
                    $current_category = get_queried_object();
                    if ($current_category && isset($current_category->name)) {
                        echo '<p class="category">' . esc_html__("Let's Talk", "woomen") . ' ' . esc_html($current_category->name) . '</p>';
                    }
                } else {
                    echo '<p class="category">' . esc_html__("Let's Talk", "woomen") . '</p>';
                }
                ?>
            </div>
        </div>
        <div class="woomen-blogs-style-3">
            <div class="row">
                <div class="col-12 col-lg-3 woomen-blogs-sidebar">
                    <?php
                    // Sidebar handles its own widget escaping
                    get_sidebar();
                    ?>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="row">
                        <?php
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                $blog_default_style = sanitize_key(woomen_get_setting('blog_default_style')) ?: 'style_2';
                                get_template_part('templates/loop/blog-loop-style-3');

                            }
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
            <?php the_posts_pagination(array('class' => 'woomen-pagination')); ?>
        </div>
    </div>
</div>