<?php
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
define( 'DB_NAME', 'dsv' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '.]_ufPW5YRmo=63-UeX3[^!w}QU^wTa5_-{Xo)&/FR=o3Y>}UXA>b+{5;doXsv7*' );
define( 'SECURE_AUTH_KEY',  '2(oG9U1ois|KWm5S%iIl+XIw8QHnXCqfIK*lLgB6>c4[*H#[QxzyDDye0ia[L>XE' );
define( 'LOGGED_IN_KEY',    'upIw4g1zZ7t(d9IAuPm%N}Q(GA<Y$KfR+p({i=i[QC$ZrLiQpNy}5LTG0:<tPfiQ' );
define( 'NONCE_KEY',        'gNo1R30+Q}ss>z<EjAD8|dm^s(7LEfqE/O/Qs0~zrm,1]Pn ]:cWB WeCe&lx,_/' );
define( 'AUTH_SALT',        'Eeb{ywtL@nUsfKd$roh)C(e+fO[awcGs*Ru;;Nr#}&{cwh7_SA8BR#n^>^KD90`p' );
define( 'SECURE_AUTH_SALT', 'K2tx_,@#(ftW%kDiuc.|cvw43/!O5*&5BLA5ze8)>UGqqk(7Z5/~% J9WabDz}<N' );
define( 'LOGGED_IN_SALT',   'lvdQ7OF|LBd]9=G7<8^Lz/Jm[J]lU9y$KD{{@^^vIpBL8k02s)W.~0~iz=/z,L.S' );
define( 'NONCE_SALT',       'vRA8l7JHM`?{J}T_1*L_rbhuS~{Kq;rQFGsUtH7Khw<F@Tu82uiue-w>R~cjCRGa' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
