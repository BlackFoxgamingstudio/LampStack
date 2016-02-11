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
define('DB_NAME', 'db212369_pkwp');

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
define('AUTH_KEY',         'z_@QoH;?h()fB-8jbC|7tZ;yF3}aY>$,|O:!..ACRWFk%I8Ol.]fs_@Z%lz%MA~v');
define('SECURE_AUTH_KEY',  'SrZL`|NW#dExvAL3GZ 3>{kR$w;NcVsoZy3kM#?&l/MiC-ra>oo*_y9S!4%H:qL#');
define('LOGGED_IN_KEY',    '7HKmVhk+,_|na%y[C6]Cnkd&6)4~,,Nu]dJ5YkdN$k,1g;fpnX-?B!)sqn|1oki%');
define('NONCE_KEY',        'f_~Sw>#D+f5mDTnhj[L!wNqjm(qF$v>M!,dUnT>dS`ws}DVGQo5Skjt}0qG)/>mG');
define('AUTH_SALT',        '[:I8Z2[t4:-.9J$A;cT[&Xh~Z8tqQKW$y*CEU7]KZ~n2ux;*w|/<x/2d1?T~VElE');
define('SECURE_AUTH_SALT', 'N<wT[#g,Qt?aa)2aop`ltO51dqp`A,{43$_S1` QQVS=-y|ZiBOA}5aHE]PHj8YL');
define('LOGGED_IN_SALT',   '_d|b8o)U}:Gq-zIWJThX=?43e|teNcj:O5->jJPXHts$991aj8Jwe:Su!hZ+HB!*');
define('NONCE_SALT',       'nu8(B&q[ZQoy?|+2_~j6~B,}ZX=~-,kdMdCn%MN?)d9y+z@g!xYv1J@+6Ib`eVc*');

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