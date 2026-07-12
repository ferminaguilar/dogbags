<?php
/**
 * 404 Template File
 * 
 * Handles the display of page not found errors.
 * Includes standard 404 message and home link.
 *
 * @package Woomen
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

get_header();
?>
<main class="container" role="main">
    <div class="woomen-section">
        <h1 class="woomen-error"><?php echo esc_html__('404', 'woomen'); ?></h1>
        <div class="woomen-page"><?php echo esc_html__('Oops! The page you are looking for cannot be found.', 'woomen'); ?></div>
        <a class="woomen-back-home" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
            <?php echo esc_html__('Back to home', 'woomen'); ?>
        </a>
    </div>
</main>
<?php
get_footer();
