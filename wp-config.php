<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'iloveuwp' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '}]8x }Z6dMDuWn].g}]5B?Bl!]_TPS+WUDI/;[0;EddxvDXjs[VF#G^]14;Ww_a)' );
define( 'SECURE_AUTH_KEY',  'IM/:9J$$C^C9X1@/Nz@(`ph_:R,2NNb((oFA$oiG9E]=}1,QXs;(aqETT&g^3;*Y' );
define( 'LOGGED_IN_KEY',    'SCi|ra`4N),Rqvp wVm<6q3Fj0_{t!%y_ClWGLPMKCm*$Z^><)xY5.c5rhmW-OOJ' );
define( 'NONCE_KEY',        '^{7 _^9c[+[X,%niW%k;0Bj;(53>(Y,`?_v@YDTsTON1]r WIYf8!*ksU{{pwh^+' );
define( 'AUTH_SALT',        'zw@<WlEKn{]1L_b>rMKO&SnB0KQ-ICHf7%iLte(6=<{iD^dKFPj}fNSaxXkvRp?7' );
define( 'SECURE_AUTH_SALT', 'p<bydxtn<t V=2]<,ITgx#x*SeN^h-go8 }7%Hb)cd:}*V>!0x.g6?=KSgOBy4b@' );
define( 'LOGGED_IN_SALT',   '}]Pz{iLN7.A!h_Q_=s{hDx%8xAO6E>p+WQvh4Z)7~8_y4S0D$IJp4}E m9PM[Io ' );
define( 'NONCE_SALT',       '[7$pornVxv+im0k;~O,{K+~^eMX-9}+v^k.koxN9ji<MySgoxI=NlEm<.:!*5E=e' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
