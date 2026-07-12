<?php
/**
 * Single Views Template
 *
 * Handles display of single posts with CubeWP builder fallback
 *
 * @package Woomen
 * @version 1.0.0
 *
 * Security Considerations:
 * - Uses template parts with proper escaping
 * - Follows WordPress template hierarchy
 * - No direct output from this file
 */

defined( 'ABSPATH' ) || exit;
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$post_id = get_the_ID();
		do_action( 'cubewp_single_page_notification', $post_id );
		$post_type = get_post_type( $post_id );
		if ( function_exists('is_cubewp_single_page_builder_active') &&  is_cubewp_single_page_builder_active( $post_type ) ) {
			// Output is escaped by CubeWP builder
			echo cubewp_single_page_builder_output( sanitize_text_field($post_type) ); 
		} else {
			if ( $post_type == 'post' ) {
				get_template_part( 'templates/single/blog-style-1' );
			} else {
				get_template_part( 'templates/single/single-style-1' );
			}
		}
		do_action( 'cubewp_post_confirmation', $post_id );
	}
}
