<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DBDR_Frontend_Form {

    public function __construct() {
        add_shortcode( 'dog_bag_report_form', [ $this, 'render_form' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );

        // Use proper WordPress form handler hooks
        add_action('admin_post_nopriv_dbdr_submit', [ $this, 'handle_submission' ]);
        add_action('admin_post_dbdr_submit', [ $this, 'handle_submission' ]);
    }

    /**
     * Enqueue plugin styles
     */
    public function enqueue_styles() {
        wp_enqueue_style( 'dbdr-style', DBDR_URL . 'assets/style.css' );
    }

    /**
     * Render the front-end report form
     */
    public function render_form() {
        $location_id = isset($_GET['location']) ? intval($_GET['location']) : 0;
        $dispenser   = isset($_GET['dispenser']) ? sanitize_text_field($_GET['dispenser']) : '';

        global $wpdb;
        $location_name = '';
        if ($location_id) {
            $table = $wpdb->prefix . 'dog_bag_locations';
            $location_name = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM $table WHERE id = %d", $location_id ) );
        }

        ob_start();

        // Show thanks message or error message if redirected
        if ( isset($_GET['dbdr_thanks']) && $_GET['dbdr_thanks'] == '1' ) {
            echo '<div class="dbdr-thanks" style="margin-bottom:10px;">✅ Thanks! Your report has been sent.</div>';
        } elseif ( isset($_GET['dbdr_error']) && $_GET['dbdr_error'] === 'empty' ) {
            echo '<div class="dbdr-error" style="margin-bottom:10px;">⚠️ Please check at least one box (Request Refill or Report Broken) or provide notes before submitting.</div>';
        }

        $protocol = is_ssl() ? 'https://' : 'http://';
        $current_url = (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) 
            ? $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] 
            : site_url('/report');
        ?>
        <div class="dbdr-form">
            <h3>Report for <?php echo esc_html($location_name ?: 'Location #' . $location_id); ?> — <?php echo esc_html($dispenser); ?></h3>

            <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('dbdr_frontend_submit', 'dbdr_nonce'); ?>
                <input type="hidden" name="action" value="dbdr_submit">
                <input type="hidden" name="dbdr_location" value="<?php echo $location_id; ?>">
                <input type="hidden" name="dbdr_dispenser" value="<?php echo esc_attr($dispenser); ?>">
                <input type="hidden" name="dbdr_redirect" value="<?php echo esc_url($current_url); ?>">

                <label><input type="checkbox" name="dbdr_refill" value="1"> Request Refill</label><br>
                <label><input type="checkbox" name="dbdr_broken" value="1"> Report Broken</label><br>

                <textarea name="dbdr_notes" placeholder="Optional notes..."></textarea>

                <button type="submit">Send Report</button>
            </form>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Handle form submission
     */
    public function handle_submission() {
        // Determine reliable fallback redirect URL
        $redirect_url = '';
        if ( ! empty($_POST['dbdr_redirect']) ) {
            $redirect_url = esc_url_raw($_POST['dbdr_redirect']);
        } elseif ( wp_get_referer() ) {
            $redirect_url = wp_get_referer();
        }

        // Safeguard: Ensure redirect_url is valid and does not point directly to admin-post.php
        if ( empty($redirect_url) || strpos($redirect_url, 'admin-post.php') !== false ) {
            $location  = isset($_POST['dbdr_location']) ? intval($_POST['dbdr_location']) : 0;
            $dispenser = isset($_POST['dbdr_dispenser']) ? sanitize_text_field($_POST['dbdr_dispenser']) : '';
            
            $query_args = array();
            if ( $location )  $query_args['location']  = $location;
            if ( $dispenser ) $query_args['dispenser'] = $dispenser;
            
            $redirect_url = add_query_arg($query_args, site_url('/report'));
        }

        // Optional Nonce verification
        if ( isset($_POST['dbdr_nonce']) && ! wp_verify_nonce($_POST['dbdr_nonce'], 'dbdr_frontend_submit') ) {
            wp_die('Invalid security token. Please try submitting again.', 'Security Error', array('response' => 403));
        }

        // Verify POST data exists
        if ( ! isset($_POST['dbdr_location'], $_POST['dbdr_dispenser']) ) {
            $clean_redirect = remove_query_arg(['dbdr_thanks', 'dbdr_error'], $redirect_url);
            wp_redirect(add_query_arg('dbdr_error', 'invalid', $clean_redirect));
            exit;
        }

        $location  = intval($_POST['dbdr_location']);
        $dispenser = sanitize_text_field($_POST['dbdr_dispenser']);
        $refill    = isset($_POST['dbdr_refill']) ? 1 : 0;
        $broken    = isset($_POST['dbdr_broken']) ? 1 : 0;
        $notes     = isset($_POST['dbdr_notes']) ? sanitize_textarea_field($_POST['dbdr_notes']) : '';

        // Process if at least one checkbox is checked or notes provided
        if ($refill || $broken || !empty($notes)) {

            // Save report to database
            DBDR_Report_Database::insert_report($location, $dispenser, $refill, $broken, $notes);

            // Prepare email
            $subject_parts = [];
            if ($refill) $subject_parts[] = 'Refill';
            if ($broken) $subject_parts[] = 'Broken';
            if ( empty($subject_parts) && !empty($notes) ) $subject_parts[] = 'Note';

            $subject = 'Dog Bag Report: ' . implode(' & ', $subject_parts);

            $message = "Location ID: $location\n";
            $message .= "Dispenser: $dispenser\n";
            if ($refill) $message .= "Refill needed: YES\n";
            if ($broken) $message .= "Broken: YES\n";
            if ($notes)  $message .= "Notes: $notes\n";

            // Proper headers for SMTP
            $headers = [];
            $headers[] = "From: Dog Bag Reports <bags@dogbags.dog>";
            $headers[] = "Reply-To: bags@dogbags.dog";
            $headers[] = "Content-Type: text/plain; charset=UTF-8";

            // Send email via WP Mail SMTP to multiple recipients for testing
            $to = ['bags@dogbags.dog', 'fermin@ferminaguilar.com', 'laugh5709@gmail.com'];
            $sent = wp_mail($to, $subject, $message, $headers);
            
            // Log result
            if (!$sent) {
                error_log("DBDR ERROR: wp_mail failed (SMTP)");
            } else {
                error_log("DBDR SUCCESS: wp_mail sent (SMTP)");
            }

            // Redirect back to show thanks message
            $clean_redirect = remove_query_arg(['dbdr_error', 'dbdr_thanks'], $redirect_url);
            wp_redirect(add_query_arg('dbdr_thanks', '1', $clean_redirect));
            exit;
        } else {
            // Nothing checked or filled in
            $clean_redirect = remove_query_arg(['dbdr_thanks', 'dbdr_error'], $redirect_url);
            wp_redirect(add_query_arg('dbdr_error', 'empty', $clean_redirect));
            exit;
        }
    }
}
