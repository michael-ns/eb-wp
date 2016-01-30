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
define('DB_NAME', 'ebdb');

/** MySQL database username */
define('DB_USER', 'adminroot');

/** MySQL database password */
define('DB_PASSWORD', 'adminroot');

/** MySQL hostname */
define('DB_HOST', 'aa17rf99wf0md4b.cjtuwxqtjctf.ap-southeast-2.rds.amazonaws.com');

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
define('AUTH_KEY',         'iVD|Gg]GaL,?xz>A>yhZ3?A)q4]-4r.Q2-!SmJVZ^5N.!YS{K8]B95(q |f#&v#~');
define('SECURE_AUTH_KEY',  'nSiW+<fX#|l|Y<k:`z;u-hwbttE*@:4H0EHa^kKW|-&=N%**EgaW|L0Y6X$TA?L{');
define('LOGGED_IN_KEY',    '/ak$#Rui<:9{@q<-;+<_DMOp<CMTS@O7`7(Od)4mOwxEF$r-;FN-vseIoS&O=BM8');
define('NONCE_KEY',        'tO<ek,P.jO&..cPeW=Fh&|T6Bb0dYmZ2b/.k8#80.V*dB+}Q+[1GnXxt|)Z%&@lb');
define('AUTH_SALT',        '/,X=%%-4-)D5.ZWpF#d:DeP(,|d))SQ:x3X|tR5&#XG|/9hy!*fTzCG87KoS>[H~');
define('SECURE_AUTH_SALT', 'o7Z%_KIfmsf$|bir{htx#AZ,O$+#=aEE5R9|<#H{H&-kj:oD32h=!:/VN/}r6_:0');
define('LOGGED_IN_SALT',   '=*ck-TKhlMTC+M*usgrKf4+c7AR:,|#yq:,60/@@uY.sUv{N3+RZAM}addiUTQd+');
define('NONCE_SALT',       'x@pf,wwcE{L{Xi_(+mMf6B-oj/||+r+(Z4%!O&E{-E-rM#s~2!9SAEZ!5?i=*dIO');

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
