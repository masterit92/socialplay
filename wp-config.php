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
define('DB_NAME', 'wp_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '#`BD:Qr>2)OaP)X0QYMl8 -ISq[ Z1?Ks|(-FY>wX64+6(c_eZ<#1`HYfuz7kS}s');
define('SECURE_AUTH_KEY',  'MPR.-*CBQ(:!+?#T`7}tce+KE/0y{NeQ.5t[II}>nWe5<FAwa AS}C%2[|cJ|;tM');
define('LOGGED_IN_KEY',    'J*hH@v3K1cc6v_JE+9hK=9f{,.J#-O?SWehX_tN`Ew#_1K(Q_mbMcum.&x*8akQ~');
define('NONCE_KEY',        'f>$~pI[S}s9h>PUN,ruk8 t?Zr?N$f<Ev=NuT|S/e[kNLq*jgvkF*gzqQ3#?G_$,');
define('AUTH_SALT',        'U]%46Ml2Z/;G85Y[dl%R7I(K3.=~[d$[BN4gibLl=Jug%Db`Yv{eif/zkiAzT,-D');
define('SECURE_AUTH_SALT', 'gr_z5P,>S4u 3f{[L1kLXy~c{X]3/#avaCGbpnBo>3S-:+&il/XP)|X_I(isVG@i');
define('LOGGED_IN_SALT',   '8Zt=_QcIRCyz/en%uELtqW{t`!D}o6-v6RXnhSC|_qgoylaSwIue?y55+Q,diWGH');
define('NONCE_SALT',       'c0>jGIl<@-k6I,YmYkxbEb=ms|)-P3Bx{)Z:x|L+gq k!)MV fm#N7s_~KX{^~.c');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'vi');

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
