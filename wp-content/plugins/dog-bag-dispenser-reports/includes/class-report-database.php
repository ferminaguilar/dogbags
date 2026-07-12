<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class DBDR_Report_Database {

    // --- Reports table ---
    public static function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dog_bag_reports';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            location INT(11) NOT NULL,
            dispenser VARCHAR(200) NOT NULL,
            refill TINYINT(1) DEFAULT 0,
            broken TINYINT(1) DEFAULT 0,
            refill_resolved TINYINT(1) DEFAULT 0,
            broken_resolved TINYINT(1) DEFAULT 0,
            notes TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    // --- Insert report ---
    public static function insert_report( $location_id, $dispenser, $refill, $broken, $notes = '' ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dog_bag_reports';

        $wpdb->insert(
            $table_name,
            [
                'location' => intval($location_id),
                'dispenser' => sanitize_text_field($dispenser) ?: 'Unknown',
                'refill' => $refill ? 1 : 0,
                'broken' => $broken ? 1 : 0,
                'notes' => sanitize_textarea_field($notes),
                'refill_resolved' => 0,
                'broken_resolved' => 0,
                'created_at' => current_time('mysql')
            ],
            ['%d','%s','%d','%d','%s','%d','%d','%s']
        );

        if ($wpdb->last_error) {
            error_log('DB insert error: ' . $wpdb->last_error);
        }
    }

    // --- Get all reports ---
    public static function get_reports() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dog_bag_reports';
        return $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC" );
    }

    // --- Mark refill resolved ---
    public static function mark_refill_resolved( $id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'dog_bag_reports';
        $wpdb->update( $table, [ 'refill_resolved' => 1 ], [ 'id' => intval($id) ] );
    }

    // --- Mark broken resolved ---
    public static function mark_broken_resolved( $id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'dog_bag_reports';
        $wpdb->update( $table, [ 'broken_resolved' => 1 ], [ 'id' => intval($id) ] );
    }

    // --- Mark dispenser as removed ---
    public static function mark_removed( $report_id ) {
        global $wpdb;
        $report_id = intval($report_id);

        // Get the report to find its location
        $report = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}dog_bag_reports WHERE id = %d",
            $report_id
        ));

        if ( ! $report ) return;

        // Update location status
        $wpdb->update(
            $wpdb->prefix . 'dog_bag_locations',
            [ 'status' => 'removed' ],
            [ 'id' => intval($report->location) ],
            [ '%s' ],
            [ '%d' ]
        );
    }

    // --- Locations table ---
    public static function create_location_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dog_bag_locations';
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            dispenser_id INT(11) NOT NULL UNIQUE,
            name VARCHAR(200) NOT NULL,
            latitude DECIMAL(10, 8) NULL,
            longitude DECIMAL(11, 8) NULL,
            qr_code_url TEXT NULL,
            image_url TEXT NULL,
            status VARCHAR(50) DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
    
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

}
