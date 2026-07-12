<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and header content
 *
 * @package Woomen
 */

defined('ABSPATH') || exit;

?>
<!DOCTYPE html>
<!--[if IE 7 ]>
<html class="ie7"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie8"> <![endif]-->
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>
    

        <div class="woomen-content-container">
            <?php
            wp_enqueue_style('woomen-header-styles');
            $post_id = isset($post->ID) ? absint($post->ID) : get_the_ID();
            $home_menus = 'woomen_home_header';
            $theme_load_class = '';

            if (!if_cubewp_can_load()) {
                $theme_load_class = 'woomen-not-loaded-header';
            }

            $header_logo = woomen_get_site_logo_url();
            ?>
            <header id="woomen-header" class="<?php echo esc_attr($theme_load_class); ?>">
                <nav class="navbar navbar-expand-lg woomen-header-top-container">
                    <div class="container woomen-header-content">
                        <a class="navbar-brand" href="<?php echo esc_url(home_url()); ?>">
                            <img loading="lazy" width="100%" height="100%" src="<?php echo esc_url($header_logo); ?>" alt="<?php echo esc_attr(get_bloginfo()); ?>">
                        </a>
                        <button class="navbar-toggler woomen-mobile-menu" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="navbar-collapse collapse  d-lg-block" id="navbarContent">
                            <?php echo get_woomen_menus($home_menus, 'navigation onepage'); ?>
                        </div>
                    </div>
                </nav>
            </header>
        </div>
        <div id="content" class="site-content">