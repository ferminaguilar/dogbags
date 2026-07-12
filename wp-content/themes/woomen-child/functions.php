<?php
/**
 * Woomen Child
 */

if ( ! function_exists( 'woomen_enqueue_parent_styles' ) ) {
	function woomen_enqueue_parent_styles() {
		wp_enqueue_style( 'woomen-parent-styles', get_template_directory_uri() . '/style.css' );
	}

	add_action( 'wp_enqueue_scripts', 'woomen_enqueue_parent_styles' );
}

add_action('init', function () {
    if (isset($_GET['dbdr_mailtest'])) {

        $headers = [
            'From: Test <no-reply@' . $_SERVER['SERVER_NAME'] . '>',
            'Content-Type: text/plain; charset=UTF-8'
        ];

        $sent = wp_mail('YOUR-EMAIL-HERE', 'DBDR Mail Test', 'This is a test email', $headers);

        if ($sent) {
            echo "WordPress says mail was sent — if you did NOT receive it, your server is blocking mail.";
        } else {
            echo "WordPress could NOT send mail at all — mail() is disabled.";
        }
        exit;
    }
});

add_filter('wp_mail_from', function() { return 'bags@dogbags.dog'; });
add_filter('wp_mail_from_name', function() { return 'Dog Bag Reports'; });
