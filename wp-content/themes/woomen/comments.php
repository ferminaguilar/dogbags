<?php
/**
 * Comments Template
 *
 * Handles the display of existing comments and comment form.
 * Includes password protection check and comment navigation.
 *
 * @package Woomen
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( comments_open() ) {
    // Check if post is password protected
	if ( post_password_required() ) { ?>
        <p class="nocomments"><?php esc_html_e( 'This post is password protected. Enter the password to view comments.', 'woomen' ); ?></p>
		<?php
		return;
	}
	if ( have_comments() ) { ?>
        <h3 class="woomen-comments-title">
			<?php esc_html_e( 'Customer Reviews', 'woomen' ); ?>
        </h3>
        <ol class="woomen-comments-list">
			<?php
			wp_list_comments(array(
				'callback' => 'woomen_post_comments',
			));
			?>
		</ol>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h3 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'woomen' ); ?></h3>
                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'woomen' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'woomen' ) ); ?></div>
            </nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>
	<?php } else { ?>
		<?php if ( comments_open() ) { ?>
            <p class="woomen-no-comment-found"><?php esc_html_e( 'No Comment Found.', 'woomen' ); ?></p>
		<?php } else { ?>
            <p class="woomen-no-comment-found"><?php esc_html_e( 'Comments are closed.', 'woomen' ); ?></p>
		<?php }
	}
    
    // Output comment form with secure args
    comment_form();
}
