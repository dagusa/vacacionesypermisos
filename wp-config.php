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
define('DB_NAME', 'vacaciones');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '.Y3])mgVR&R~i<GGVeinK!zoZj7@UwH6>E[1z (LGl|#fey]2<Th{I^_y73D,{k,');
define('SECURE_AUTH_KEY',  'fMN|(0gkc{P:ozX*lKQz3eYe?>/#p];>PwvW<x0Q.tl8T/kHPcC(QpEzzjpHA@Gb');
define('LOGGED_IN_KEY',    'Wng3H.FR`):]SVy18g.=T77GM NqoH@z$.5;%#g1g-IFo/zO y%;?8viHEm1?W69');
define('NONCE_KEY',        '`LikJ1ic/~A&o(a!OR:gS6[/SPS_#(c$w$AH3NsCJ,5S{PY.q}J*J<3W]>VBKZ8u');
define('AUTH_SALT',        '>@/a: J;CWg:wWJnWz}5!V):1vT*U{c?S]/P,n_i+:|5pXbgZydk4WSU7I@]zr?q');
define('SECURE_AUTH_SALT', ']ow>^IW10RjK*ga)`8$|Z0KQ5umowPzS~q5kT.qNq;f[vcL.JYU`L~v;YvZLFw}e');
define('LOGGED_IN_SALT',   '{&&3-F@v%,gk#$+E<[a<P`)_32<g+SVFS)S96NjpCY4bcPuUgX>7b(HR32V[Wzp8');
define('NONCE_SALT',       '?{Y])9.yy@yHV[xEB,Nt+fb%{?!E_uB!DpXaQ!9i?.z_i-X=LyRJ>H=eNNPALHFG');

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
