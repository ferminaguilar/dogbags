<?php
/**
 * Archive template selector
 *
 * Loads different archive templates based on theme settings
 *
 * @package Woomen
 */

defined('ABSPATH') || exit;

if ( if_cubewp_can_load() ) {
    $blog_default_style = woomen_get_setting('blog_default_style') ? sanitize_text_field(woomen_get_setting('blog_default_style')) : 'style_2';

  if ( $blog_default_style == 'style_1' ) {
		get_template_part('templates/archive/blog-archive-style-1');
	} elseif ( $blog_default_style == 'style_2' ) {
		get_template_part('templates/archive/blog-archive-style-2');
	} elseif ( $blog_default_style == 'style_3' ) {
		get_template_part('templates/archive/blog-archive-style-3');
	} elseif ( $blog_default_style == 'style_4' ) {
		get_template_part('templates/archive/blog-archive-style-4');
	} else {
		get_template_part('templates/archive/blog-archive-style-1');
	}
} else {
    get_template_part('templates/archive/blog-archive-style-2');
}
