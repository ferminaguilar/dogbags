<?php
defined( 'ABSPATH' ) || exit;

get_header(); 
?>

<?php
$elementor_edit_mode = false;
if ( class_exists( 'Elementor\Plugin' ) ) {
    global $post;
    if ( \Elementor\Plugin::instance()->documents->get( $post->ID )->is_built_with_elementor() ) {
        $elementor_edit_mode = true;
    }
}
?>

<?php if ( $elementor_edit_mode ) : ?>
    <div class="elementor-edit-mode">
        <div class="woomen-page-content-container">
            <?php
            while ( have_posts() ): the_post();
                the_content();
                wp_link_pages();
            endwhile;
            ?>
        </div>
    </div>
<?php else : ?>
    <div class="container">
        <div class="woomen-page-content-container">
            <?php
            while ( have_posts() ): the_post();
                the_content();
                wp_link_pages();
            endwhile;
            ?>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>