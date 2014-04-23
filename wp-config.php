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
define('DB_NAME', 'tour');

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
define('AUTH_KEY',         '+1{48io7-Yg*/?cXZt-_:Rfn@M1 cL8stIO&NS/u3^8,]EEA2JuP*kwa-F8cda]4');
define('SECURE_AUTH_KEY',  '.k0a@B83;ra@hJ;C!&{@vix!<|2R!^,~x%_LTl5k3_ Hc+I2L}.az+7_]I4deds]');
define('LOGGED_IN_KEY',    '6:iL75ZQ+=Wu0:q:FiI&egXPo-+Q~zZN@LY3:<(/X9$eDSQHW4H-H@a7m?>21z~g');
define('NONCE_KEY',        '(6wa&RPe/J>~vo+U`#N8VT!8Qu|?+{|>mIP/DXG6eYW;[gVC&I6m#h3n|*2_:3sU');
define('AUTH_SALT',        '5eKN$K!IR>8Ui)2:(3lJ|w,G-F^g:q)([QJT9^Zh1:GBvPD!y|,4e*znw/+!-`V]');
define('SECURE_AUTH_SALT', ']L-/o#f^b G!`TL=}c}$.|$MNfwxB%NOkrUJb|D+_++VpV.( cOb!ajoKHQn6E#R');
define('LOGGED_IN_SALT',   'pw)L5Yw0-p|ppT4+z7~M+Z5Ta+k/2FZ9$x[yL8.[9- yo:4}2|99!FJtUWe*T+t(');
define('NONCE_SALT',       '1VJbS`wz$y9qW.-{Zh_6x-p51Q9-s)cxd/u=:pt;>{&`j+.=>C]6r?kf+95.=r@T');

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
