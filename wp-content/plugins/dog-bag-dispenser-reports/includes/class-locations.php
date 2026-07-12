<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DBDR_Locations {

    public function __construct() {
        add_action('admin_post_dbdr_add_location', [ $this, 'handle_add_location' ]);
        add_action('admin_post_dbdr_update_location', [ $this, 'handle_update_location' ]);
        add_action('admin_post_dbdr_delete_location', [ $this, 'handle_delete_location' ]);
    }

    // --- Add Location ---
    public function handle_add_location() {
        if ( ! current_user_can('manage_options') ) wp_die('Unauthorized');
        check_admin_referer('dbdr_add_location');

        if ( isset($_POST['location_name'], $_POST['latitude'], $_POST['longitude'], $_POST['status']) ) {
            global $wpdb;
            $table = $wpdb->prefix . 'dog_bag_locations';

            // Generate a unique Dispenser ID
            $last_id = $wpdb->get_var("SELECT MAX(dispenser_id) FROM $table");
            $new_dispenser_id = $last_id ? $last_id + 1 : 10001;

            $wpdb->insert($table, [
                'name' => sanitize_text_field($_POST['location_name']),
                'latitude' => floatval($_POST['latitude']),
                'longitude' => floatval($_POST['longitude']),
                'status' => sanitize_text_field($_POST['status']),
                'dispenser_id' => $new_dispenser_id,
            ]);

            $location_id = $wpdb->insert_id;

            // Generate QR code with location + dispenser
            $qr_url = $this->generate_qr_code(site_url("/report?location=$location_id&dispenser=$new_dispenser_id"), $location_id);
            $wpdb->update($table, ['qr_code_url' => $qr_url], ['id' => $location_id]);
        }

        wp_redirect(admin_url('admin.php?page=dbdr-locations'));
        exit;
    }

    // --- Update Location ---
    public function handle_update_location() {
        if ( ! current_user_can('manage_options') ) wp_die('Unauthorized');
        check_admin_referer('dbdr_update_location');

        if ( isset($_POST['location_id'], $_POST['location_name'], $_POST['latitude'], $_POST['longitude'], $_POST['status']) ) {
            global $wpdb;
            $table = $wpdb->prefix . 'dog_bag_locations';
            $location_id = intval($_POST['location_id']);

            $wpdb->update($table, [
                'name' => sanitize_text_field($_POST['location_name']),
                'latitude' => floatval($_POST['latitude']),
                'longitude' => floatval($_POST['longitude']),
                'status' => sanitize_text_field($_POST['status']),
            ], ['id' => $location_id]);

            // Regenerate QR code
            $dispenser_id = $wpdb->get_var($wpdb->prepare("SELECT dispenser_id FROM $table WHERE id=%d", $location_id));
            $qr_url = $this->generate_qr_code(site_url("/report?location=$location_id&dispenser=$dispenser_id"), $location_id);
            $wpdb->update($table, ['qr_code_url' => $qr_url], ['id' => $location_id]);
        }

        wp_redirect(admin_url('admin.php?page=dbdr-locations'));
        exit;
    }

    // --- Delete Location ---
    public function handle_delete_location() {
        if ( ! current_user_can('manage_options') ) wp_die('Unauthorized');
        check_admin_referer('dbdr_delete_location_' . $_GET['id']);

        if ( isset($_GET['id']) ) {
            global $wpdb;
            $table = $wpdb->prefix . 'dog_bag_locations';
            $wpdb->delete($table, ['id' => intval($_GET['id'])]);
        }

        wp_redirect(admin_url('admin.php?page=dbdr-locations'));
        exit;
    }

    // --- Main Page: Add + List ---
    public function render_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'dog_bag_locations';
        $locations = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");
        ?>
        <div class="wrap">
            <h1>Dog Bag Locations</h1>

            <h2>Add New Location</h2>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="dbdr_add_location">
                <?php wp_nonce_field('dbdr_add_location'); ?>

                <p>
                    <label>Name:</label><br>
                    <input type="text" name="location_name" required style="width:300px">
                </p>
                <p>
                    <label>Latitude:</label><br>
                    <input type="text" name="latitude" placeholder="37.4221">
                </p>
                <p>
                    <label>Longitude:</label><br>
                    <input type="text" name="longitude" placeholder="-122.0841">
                </p>
                <p>
                    <label>Status:</label><br>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="broken">Broken</option>
                        <option value="removed">Removed</option>
                    </select>
                </p>
                <p>
                    <button type="submit" class="button button-primary">Add Location</button>
                </p>
            </form>

            <hr>

            <h2>Existing Locations</h2>
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Dispenser ID</th>
                        <th>Map</th>
                        <th>Status</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($locations as $loc): ?>
                    <tr>
                        <td><?php echo intval($loc->id); ?></td>
                        <td><?php echo esc_html($loc->name); ?></td>
                        <td><?php echo esc_html($loc->dispenser_id); ?></td>
                        <td>
                            <?php if ($loc->latitude && $loc->longitude): ?>
                                <iframe width="200" height="150" frameborder="0" style="border:0"
                                        src="https://www.google.com/maps?q=<?php echo esc_attr($loc->latitude); ?>,<?php echo esc_attr($loc->longitude); ?>&z=15&output=embed"
                                        allowfullscreen></iframe>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td><?php echo esc_html(ucfirst($loc->status)); ?></td>
                        <td>
                            <?php if (!empty($loc->qr_code_url)): ?>
                                <img src="<?php echo esc_url($loc->qr_code_url); ?>" width="100">
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=dbdr-locations-edit&id=' . intval($loc->id)); ?>" class="button">Edit</a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=dbdr_delete_location&id=' . intval($loc->id)), 'dbdr_delete_location_' . intval($loc->id)); ?>"
                               class="button" onclick="return confirm('Are you sure you want to delete this location?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // --- Dedicated Edit Page ---
    public function render_edit_page() {
        if ( ! isset($_GET['id']) ) {
            wp_redirect(admin_url('admin.php?page=dbdr-locations'));
            exit;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'dog_bag_locations';
        $edit_location = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d", intval($_GET['id'])));

        if ( ! $edit_location ) {
            wp_redirect(admin_url('admin.php?page=dbdr-locations'));
            exit;
        }
        ?>
        <div class="wrap">
            <h1>Edit Location</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="dbdr_update_location">
                <?php wp_nonce_field('dbdr_update_location'); ?>
                <input type="hidden" name="location_id" value="<?php echo intval($edit_location->id); ?>">

                <p>
                    <label>Name:</label><br>
                    <input type="text" name="location_name" required style="width:300px"
                           value="<?php echo esc_attr($edit_location->name); ?>">
                </p>
                <p>
                    <label>Latitude:</label><br>
                    <input type="text" name="latitude" placeholder="37.4221"
                           value="<?php echo esc_attr($edit_location->latitude); ?>">
                </p>
                <p>
                    <label>Longitude:</label><br>
                    <input type="text" name="longitude" placeholder="-122.0841"
                           value="<?php echo esc_attr($edit_location->longitude); ?>">
                </p>
                <p>
                    <label>Status:</label><br>
                    <select name="status">
                        <option value="active" <?php selected($edit_location->status, 'active'); ?>>Active</option>
                        <option value="broken" <?php selected($edit_location->status, 'broken'); ?>>Broken</option>
                        <option value="removed" <?php selected($edit_location->status, 'removed'); ?>>Removed</option>
                    </select>
                </p>
                <p>
                    <label>Dispenser ID:</label><br>
                    <input type="text" name="dispenser_id" value="<?php echo esc_attr($edit_location->dispenser_id ?? ''); ?>" readonly style="width:150px">
                </p>
                <p>
                    <button type="submit" class="button button-primary">Update Location</button>
                    <a href="<?php echo admin_url('admin.php?page=dbdr-locations'); ?>" class="button">Cancel</a>
                </p>
            </form>
        </div>
        <?php
    }

    // --- QR Code Generation ---
    private function generate_qr_code($data, $location_id) {
        if (!file_exists(WP_CONTENT_DIR . '/uploads/dbdr_qrcodes')) {
            wp_mkdir_p(WP_CONTENT_DIR . '/uploads/dbdr_qrcodes');
        }

        require_once DBDR_PATH . 'includes/lib/phpqrcode.php';

        $file = WP_CONTENT_DIR . '/uploads/dbdr_qrcodes/location_' . $location_id . '.png';
        \QRcode::png($data, $file, 'L', 4, 2);

        return content_url('uploads/dbdr_qrcodes/location_' . $location_id . '.png');
    }
}
