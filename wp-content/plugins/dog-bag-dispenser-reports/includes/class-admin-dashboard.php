<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DBDR_Admin_Dashboard {

    public function __construct() {
        add_action('admin_init', [ $this, 'handle_resolve_actions' ]);
    }

    public function handle_resolve_actions() {
        if ( isset($_GET['dbdr_action'], $_GET['id']) && current_user_can('manage_options') ) {
            $id = intval($_GET['id']);

            if ( $_GET['dbdr_action'] === 'resolve_refill' ) {
                DBDR_Report_Database::mark_refill_resolved( $id );
            }

            if ( $_GET['dbdr_action'] === 'resolve_broken' ) {
                DBDR_Report_Database::mark_broken_resolved( $id );
            }

            if ( $_GET['dbdr_action'] === 'mark_removed' ) {
                DBDR_Report_Database::mark_removed( $id );
                wp_redirect( admin_url('admin.php?page=dbdr-reports&marked_removed=1') );
                exit;
            }

            wp_redirect( admin_url( 'admin.php?page=dbdr-reports' ) );
            exit;
        }
    }

    public function render_reports_page() {
        global $wpdb;

        $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'created_at';
        $order   = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';
        
        $allowed_columns = ['id','created_at'];
        if (!in_array($orderby, $allowed_columns)) $orderby = 'created_at';
        
        $reports = $wpdb->get_results("
            SELECT r.*, l.name AS location_name, l.status AS location_status
            FROM {$wpdb->prefix}dog_bag_reports r
            LEFT JOIN {$wpdb->prefix}dog_bag_locations l ON r.location = l.id
            ORDER BY r.$orderby $order
        ");



        ?>
        <div class="wrap">
            <h1>Dog Bag Dispenser Reports</h1>

            <?php if ( isset($_GET['marked_removed']) ) : ?>
                <div class="notice notice-success"><p>✅ Dispenser marked as removed.</p></div>
            <?php endif; ?>

            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><a href="?page=dbdr-reports&orderby=id&order=asc">ID ↑</a> | <a href="?page=dbdr-reports&orderby=id&order=desc">↓</a></th>
                        <th>Location</th>
                        <th>Dispenser</th>
                        <th>Refill</th>
                        <th>Broken</th>
                        <th>Notes</th>
                        <th><a href="?page=dbdr-reports&orderby=created_at&order=asc">Date ↑</a> | <a href="?page=dbdr-reports&orderby=created_at&order=desc">↓</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($reports): ?>
                    <?php foreach ($reports as $r): ?>
                        <tr>
                            <td><?php echo intval($r->id); ?></td>
                            <td><?php echo esc_html($r->location_name); ?></td>
                            <td><?php echo esc_html($r->dispenser); ?></td>
                            <td>
                                <?php if ($r->refill): ?>
                                    <?php if ($r->refill_resolved): ?>
                                        ✅ Resolved
                                    <?php else: ?>
                                        <a href="<?php echo admin_url('admin.php?page=dbdr-reports&dbdr_action=resolve_refill&id=' . $r->id); ?>" class="button button-small">Mark Refill Done</a>
                                    <?php endif; ?>
                                <?php else: ?>—
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r->broken): ?>
                                    <?php if ($r->broken_resolved): ?>
                                        ✅ Fixed
                                    <?php else: ?>
                                        <a href="<?php echo admin_url('admin.php?page=dbdr-reports&dbdr_action=resolve_broken&id=' . $r->id); ?>" class="button button-small">Mark Broken Fixed</a>
                                    <?php endif; ?>
                                <?php else: ?>—
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($r->notes); ?></td>
                            <td><?php echo esc_html($r->created_at); ?></td>
                            <td>
                                <?php if (isset($r->location_status) && $r->location_status !== 'removed'): ?>
                                    <a href="<?php echo admin_url('admin.php?page=dbdr-reports&dbdr_action=mark_removed&id=' . $r->id); ?>" class="button button-small">Mark Removed</a>
                                <?php else: ?>
                                    ✅ Removed
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8">No reports found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
