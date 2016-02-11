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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db212369_crowdws');

/** MySQL database username */
define('DB_USER', 'db212369');

/** MySQL database password */
define('DB_PASSWORD', 'PowersBros45me');

/** MySQL hostname */
define('DB_HOST', 'internal-db.s212369.gridserver.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'JIkmtZ]wa.Pt@Fj28M|PORa}5e+T]|`s[~ im2r5#n//j8gm*jQ#MDp~M lrT;T1');
define('SECURE_AUTH_KEY',  'c]&%^cUr+-[`amq],qBEv`SiNR dMv#z$bj C*JHfH*ApNDe$S{Ea%0%`)^KU^*-');
define('LOGGED_IN_KEY',    'f9Onk?+I>|CaWHAf)>spwG7*FOXfjJD[Yj(1Y%Xu!lw%R9@4+K-|4*XRl`X@]wcZ');
define('NONCE_KEY',        'G`376f5;h:PqqnL+!<-jk9n u!Ubr:bNs#$tM1Q>DoyM.CY@IKBZE>,(=__82{O.');
define('AUTH_SALT',        'G_84CPPV8dhywUS&HHPm*~s!OViX*bFTmjEn(^FF=0r-CM$5-CrB+e-Y_4S^;jhu');
define('SECURE_AUTH_SALT', '*nR]X::js7ORoF6|iwecm)iRU&N=%q@V1VF[+gY=X)U^o!WV6n,|,NO|keXS5alH');
define('LOGGED_IN_SALT',   '<vw,xvJCd+3JpEOf-yf5n/GHFpm}b/x*%b}]}pQ~$eBY}J<ZN^IKmEitQXpCwP#g');
define('NONCE_SALT',       '>.7gUF/nY$pz&xE|o>c5OWEm(iq[*@X8{4uzX+UmV6i7DUGw|Z4;p/nz-,5%geac');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
