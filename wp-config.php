<?php
define( 'WP_CACHE', true );



















































































































































/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fermpgjg_wpdb' );

/** Database username */
define( 'DB_USER', 'fermpgjg_wpdb' );

/** Database password */
define( 'DB_PASSWORD', '5D408](U(sspG[]S' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ttompkehntd3uiljteaphjqks8fg0yi2yunihjf6xd3yn3cd7xs60fhrth2hzvtg' );
define( 'SECURE_AUTH_KEY',  '71rhszy9meh1lrlx33p6ezo8c8494y5drgqpfqzf76lgf3szbrsk0l0kjmgi6okt' );
define( 'LOGGED_IN_KEY',    'jnzj9kbrv8cwntdfojxngezujdpbnwoijyce3svjtktht6cbrpg6amrqghfh4lec' );
define( 'NONCE_KEY',        'kxskqr9xqzqad7eh6givswilnksiw69klcn9llg9tzgrbcudqkzy8efuynmk2scf' );
define( 'AUTH_SALT',        'gc4cckfytnwt7zs40ldsproxqlzhjjsx2aiptr7zq2f0aub4277hgi37uyzcjebf' );
define( 'SECURE_AUTH_SALT', '7voruyhacmxprbtuopoo81cfhxcvbdkfexqjnjuw5y6cxjn3tchsvxecw5pdlgvw' );
define( 'LOGGED_IN_SALT',   'kwn1gn73qtpklcehz8sctsrsftwv0pco31ikxjdhgblakfssrvupklfjwwvxeuy6' );
define( 'NONCE_SALT',       '98wsaqnhi7snrpfrnrelxpwyuhdhuauni80nijibveg3v1wg54ivepit29yn4am0' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wpoh_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
