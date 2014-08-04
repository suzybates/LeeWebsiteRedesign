<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'lee');

/** MySQL database username */
define('DB_USER', 'suzy');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'Q7C$o3dog-uMF/8{jEmcEqw+-xO!1Xx&?aAq%{Y#S(VO}~lpZ!,[leX/S[<YWTZ5');
define('SECURE_AUTH_KEY',  'yr%K|@|WfJL_[|^.OpQ8y[cvp|P-(,#!{Ath%05`z+^wqBf#!.K,)Xaq|y-mO~3b');
define('LOGGED_IN_KEY',    'g-ITKhbr/>qc2xygVE|(JZg6nOUyEfrJ%q.@C>y<yP.F[k1K7vvx]v@=A?ENL<fL');
define('NONCE_KEY',        'RZO69JLRyTAgW-s$2QqYm|mj:@-?m>CvJLh_v`)n.FoXRt^5)6K$->sa+1}9J@&L');
define('AUTH_SALT',        'PAn+fPyjAd/Y1`Eo@<EIm>/:}^hEWxGx@bH-sMV?RA *E%dkdrO?EJe{uDhwXE1A');
define('SECURE_AUTH_SALT', '?W`]kw9LD/9HD7(P$[:[Lc3w@;W^D.,fwrQC<r~6Hi|;`V]A?p-X/.:ZP0uh(u;=');
define('LOGGED_IN_SALT',   '$VOWHE:wK,$*Nsfu^+Dlvg+X7.m82v+lI|zN[^AkKi{_r=U*L^7FasY|)A>!Ojv_');
define('NONCE_SALT',       '-k jD3-r*^{uf#Z*T,64}!/_/@c;7+Ie@SwrEIID[fA-*`/v*RHsF+%P6`P/3`Tb');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_py5y64_';


/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_MEMORY_LIMIT', '64M');


