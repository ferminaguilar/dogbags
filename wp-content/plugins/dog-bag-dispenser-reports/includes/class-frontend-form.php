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

        // Show thanks message if redirected
        if ( isset($_GET['dbdr_thanks']) ) {
            echo '<div class="dbdr-thanks" style="margin-bottom:10px;">✅ Thanks! Your report has been sent.</div>';
        }
        ?>
        <div class="dbdr-form">
            <h3>Report for <?php echo esc_html($location_name); ?> — <?php echo esc_html($dispenser); ?></h3>

            <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="dbdr_submit">
                <input type="hidden" name="dbdr_location" value="<?php echo $location_id; ?>">
                <input type="hidden" name="dbdr_dispenser" value="<?php echo $dispenser; ?>">

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
        // Verify POST data exists
        if ( ! isset($_POST['dbdr_location'], $_POST['dbdr_dispenser']) ) {
            wp_redirect(add_query_arg('dbdr_thanks', '0', wp_get_referer()));
            exit;
        }

        $location  = intval($_POST['dbdr_location']);
        $dispenser = sanitize_text_field($_POST['dbdr_dispenser']);
        $refill    = isset($_POST['dbdr_refill']) ? 1 : 0;
        $broken    = isset($_POST['dbdr_broken']) ? 1 : 0;
        $removed   = 0;
        $notes     = isset($_POST['dbdr_notes']) ? sanitize_textarea_field($_POST['dbdr_notes']) : '';

        // Only process if at least one checkbox is checked
        if ($refill || $broken) {

            // Save report to database
            DBDR_Report_Database::insert_report($location, $dispenser, $refill, $broken, $notes, $removed);

            // Prepare email
            $subject_parts = [];
            if ($refill) $subject_parts[] = 'Refill';
            if ($broken) $subject_parts[] = 'Broken';
            $subject = 'Dog Bag Report: ' . implode(' & ', $subject_parts);

            $message = "Location ID: $location\n";
            $message .= "Dispenser: $dispenser\n";
            if ($refill) $message .= "Refill needed: YES\n";
            if ($broken) $message .= "Broken: YES\n";
            if ($notes) $message .= "Notes: $notes\n";

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

        }

        // Redirect back to show thanks message
        wp_redirect(add_query_arg('dbdr_thanks', '1', wp_get_referer()));
        exit;
    }
}
