<?php
/**
 * Single Post Template - Style 1
 *
 * Displays single post content in a basic container
 *
 * @package Woomen  
 * @version 1.0.0
 *
 * Security Considerations:
 * - Output is properly escaped
 * - Post type is sanitized
 * - Follows WordPress template standards
 */

defined( 'ABSPATH' ) || exit;

global $post;

$post_id = get_the_ID() ?? $post->ID;
$post_type = sanitize_key(get_post_type($post_id)); 

$container_class = 'container';

if (function_exists('is_product') && is_product()) {
    $container_class = '';
}


?>
<section class="woomen-single-page <?php echo sprintf(esc_attr('woomen-%s-single-page'), $post_type) ?> p-5">
    <div class="<?php echo esc_attr($container_class ?? 'container') ?>">
        <?php the_content(); ?>
    </div>
</section>
