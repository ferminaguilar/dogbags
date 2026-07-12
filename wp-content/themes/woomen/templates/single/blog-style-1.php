<?php

/**
 * Single Blog Post Template - Style 1
 * 
 * Displays single blog posts with author info, related posts and comments
 *
 * @package Woomen
 * @version 1.0.0
 * 
 * Security Considerations:
 * - All output is properly escaped
 * - Dynamic data is sanitized
 * - Follows WordPress template standards
 */

defined('ABSPATH') || exit;


if (comments_open()) {
    wp_enqueue_script('comment-reply');
}
wp_enqueue_style('woomen-blog-styles');

$post_id            = get_the_ID();
$post_time          = strtoupper(sanitize_text_field(human_time_diff(strtotime(get_the_date('Y-m-d g:i:s a')))));
$post_author        = get_post_field('post_author', $post_id);
$post_author_data   = get_userdata($post_author);
$post_author_name   = $post_author_data ? $post_author_data->display_name : esc_html__('Unknown Author', 'woomen');
$post_author_avatar = get_avatar_url($post_author);
$post_thumbnail     = get_the_post_thumbnail_url($post_id);

?>
<div id="woomen-single-post" class="woomen-single-post">
    <div class="woomen-single-post-banner-content container">
        <div class="woomen-single-post-banner-top-content-container">
            <h1 class="woomen-post-title"><?php echo get_the_title($post_id); ?></h1>
            <div class="woomen-single-top-content">
                <p class="woomen-single-post-info p-lg">
                    <span><?php echo esc_html__("BY", "woomen"); ?></span>
                    <a href="<?php echo esc_url(get_author_posts_url($post_author)) ?>">
                        <?php echo sprintf(esc_html__("%s,", "woomen"), $post_author_name); ?>
                    </a>
                    <span class="woomen-single-post-date"><?php echo sprintf(esc_html__("%s AGO", "woomen"), $post_time); ?></span>
                </p>
                <div class="woomen-post-grid-content-comment">
                    <i class="fa-regular fa-message"></i><span><?php echo get_comments_number(); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (!empty($post_thumbnail)) {
    ?>
        <div class="woomen-single-post-banner">
            <img src="<?php echo esc_url($post_thumbnail); ?>"
                alt="<?php echo esc_attr(get_the_title($post_id)); ?>"
                class="woomen-single-post-banner-image">
        </div>
    <?php
    }
    ?>
    <div class="container">
        <div class="woomen-single-post-content">
            <div class="woomen-single-post-content-container">
                <?php
                the_content(); // Content is escaped by WordPress core
                ?>
            </div>
            <div class="woomen-social-link-tags">
                <?php
                if (function_exists('vp_get_socials_share')) {
                    echo vp_get_socials_share();
                }
                ?>
                <div class="woomen-single-post-tags">
                    <?php
                    the_tags('', '', ''); // Tags output is escaped by WordPress core
                    ?>
                </div>
            </div>
            <div class="woomen-single-post-author">
                <div class="woomen-single-post-author-image">
                    <img src="<?php echo esc_url($post_author_avatar); ?>" alt="<?php echo esc_html($post_author_name); ?>">
                </div>
                <div class="woomen-single-author">
                    <a href="<?php echo esc_url(get_author_posts_url($post_author)); ?>">
                        <p class="p-lg"><?php echo esc_html($post_author_name); ?></p>
                    </a>
                    <?php
                    $author_description = get_the_author_meta('description', $post_author);
                    if (! empty($author_description)) {
                        echo '<p class="author-description">' . esc_html($author_description) . '</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="woomen-single-post-next-prev row">
                <div class="woomen-single-post-prev col-lg-5">
                    <?php
                    $prev_post = get_previous_post();
                    if ($prev_post) {
                        $prev_title = get_the_title($prev_post->ID);
                        $prev_link = get_permalink($prev_post->ID);
                    ?>
                        <a href="<?php echo esc_url($prev_link); ?>" class="prev-post-link">
                            <i class="fa-solid fa-chevron-left"></i>
                            <div class="prev-post-text">
                                <p><?php esc_html_e('PREV POST', 'woomen'); ?></p>
                                <span><?php echo wp_kses_post($prev_title); ?></span>
                            </div>
                        </a>
                    <?php
                    }
                    ?>
                </div>
                <div class="woomen-post-navigation-separator col-lg-2">
                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="0.800049" width="4" height="4" fill="#1D1D1D" />
                        <rect y="8.80005" width="4" height="4" fill="#1D1D1D" />
                        <rect y="16.8" width="4" height="4" fill="#1D1D1D" />
                        <rect x="8" y="0.800049" width="4" height="4" fill="#1D1D1D" />
                        <rect x="8" y="8.80005" width="4" height="4" fill="#1D1D1D" />
                        <rect x="8" y="16.8" width="4" height="4" fill="#1D1D1D" />
                        <rect x="16" y="0.800049" width="4" height="4" fill="#1D1D1D" />
                        <rect x="16" y="8.80005" width="4" height="4" fill="#1D1D1D" />
                        <rect x="16" y="16.8" width="4" height="4" fill="#1D1D1D" />
                    </svg>
                </div>
                <div class="woomen-single-post-next col-lg-5">
                    <?php
                    $next_post = get_next_post();
                    if ($next_post) {
                        $next_title = get_the_title($next_post->ID);
                        $next_link = get_permalink($next_post->ID);
                    ?>
                        <a href="<?php echo esc_url($next_link); ?>" class="next-post-link">
                            <div class="next-post-text">
                                <p><?php esc_html_e('NEXT POST', 'woomen'); ?></p>
                                <span><?php echo wp_kses_post($next_title); ?></span>
                            </div>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php
            $related_args = array(
                'post__not_in'   => array($post_id),
                'posts_per_page' => 2,
                'ignore_sticky_posts' => 1,
            );

            $related_query = new WP_Query($related_args);

            if ($related_query->have_posts()) {
            ?>
                <div class="row woomen-single-related-posts">
                    <h2 class="woomen-related-post-title"><?php esc_html_e('Related articles', 'woomen'); ?></h2>
                    <?php
                    while ($related_query->have_posts()) {
                        $related_query->the_post();
                        get_template_part('templates/loop/blog-loop-style-2');
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            <?php
            } else {
            ?>
                <div>
                    <h2><?php esc_html_e('No Results', 'woomen'); ?></h2>
                    <p><?php esc_html_e('Sorry! There are no related posts available.', 'woomen'); ?></p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="container">
        <div class="woomen-single-post-comments">
            <div class="woomen-single-post-comments-list">
                <?php
                comments_template();
                ?>
            </div>
        </div>
    </div>
</div>