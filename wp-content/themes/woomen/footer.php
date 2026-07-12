<?php
/**
 * The footer for our theme
 *
 * Contains the closing of the site content and all content after
 *
 * @package Woomen
 */

defined('ABSPATH') || exit;
?>

    <?php
    ?>
    </div>
    <footer id="woomen-footer" class="woomen-footer-style-1">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="woomen-footer-copyright d-flex">
                        <p>
                            &copy;
                            <?php esc_html_e('Copyright', 'woomen'); ?>
                            <?php echo esc_html(date_i18n(_x('Y', 'copyright date format', 'woomen'))); ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>">
                                <?php echo esc_html(get_bloginfo('name')); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php wp_footer(); ?>
</body>
</html>
