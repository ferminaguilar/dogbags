<?php
defined('ABSPATH') || exit;

$post_id          = get_the_ID();
$post_time        = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
$post_author      = get_post_field('post_author', $post_id);
$post_author_name = get_userdata($post_author);
$post_term        = wp_get_post_terms($post_id, 'category');
$post_term        = $post_term[0] ?? '';
$post_categories = get_the_terms($post_id, 'category');
?>
<div class="col-md-12">
    <div <?php post_class( 'woomen-blogs-grid' ) ?>>
        <div class="woomen-blogs-grid-thumb">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="stretched-link"></a>
            <img loading="lazy" width="100%" height="100%"
                src="<?php echo woomen_get_post_featured_image($post_id, false, 'woomen'); ?>"
                alt="<?php echo esc_attr(get_the_title($post_id)); ?>">
            <div class="woomen-blogs-tags">
                <?php if (! empty($post_categories) && ! is_wp_error($post_categories)) {
                    $count = 0;
                    foreach ($post_categories as $category) {
                        if ($count > 5) {
                            break;
                        }
                ?>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="style-cat">
                            <?php echo esc_html($category->name); ?>
                        </a>
                <?php
                        $count++;
                    }
                } ?>
            </div>
        </div>
        <div class="woomen-blogs-grid-content">
            <div class="woomen-author-details">
                <p>
                    <a href="<?php echo esc_url(get_author_posts_url($post_author)) ?>">
                        <span
                            class="woomen-post-author"><?php echo sprintf(esc_html__("By %s,", "woomen"), $post_author_name->display_name); ?></span>
                    </a>
                    <span class="woomen-post-date"><?php echo esc_html($post_time); ?></span>
                </p>
                <div class="woomen-post-comments">
                    <i class="fa-regular fa-message"></i><span><?php echo get_comments_number(); ?></span>
                </div>
            </div>
            <div class="woomen-post-title">
                <a href="<?php echo esc_url(get_permalink()); ?>">
                    <h2 class="heading"><?php echo get_the_title(); ?></h2>
                </a>
            </div>
            <div class="woomen-post-description">
                <p>
                    <?php
                    $post_content = get_the_content();
                    $words = preg_split("/[\s,]+/", strip_tags($post_content));
                    $limited_content = implode(' ', array_slice($words, 0, 20));
                    echo wp_kses_post($limited_content) . '..';
                    ?>
                </p>
            </div>
            <a class="woomen-post-read-more" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html__("READ MORE", "woomen"); ?> <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</div>