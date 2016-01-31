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
define('AUTH_KEY',         '5H+@c*>X(enoo_P0dEGRCy4a7Z_M_Wzz#RU#X}/(u)fS{Y=c1I!fujCu?(S/=%^P');
define('SECURE_AUTH_KEY',  'CVq$LOs.GYvM@z}O~n|gS:?^+sAY)FPn;72ZiF6-fO5X]k-!dL+O[jiTf-,.YjqX');
define('LOGGED_IN_KEY',    'I^fkfuke+|%Y+.,bvl7v%M1c+CK),i+*1 p]NQdBh|oEY|h{({`@K<O-9pNN:t.H');
define('NONCE_KEY',        '&QY-3w+E-A*T5+FxEEK+aZD4n+W;c/jR*g+?A-Op9JTh+KbrDs@~<6.xqej+T*&p');
define('AUTH_SALT',        'lY|x-p<^ngzxs>13&xb{p:HRXEi2fS{Sd wIl%Wo|@bl6/td{*:0z{!{|%O*IwMi');
define('SECURE_AUTH_SALT', '>|8*e^SK 9(Z-[WIG+ZAn#+>K*XLi/I;6_,A>;-gJEFfVP B-~o{=+}/kp]4p!}N');
define('LOGGED_IN_SALT',   'NNbIq~!J4ptO6S:xd|e8F;Z7sI|5gTw!DO2ZI9)v!#<,FfVICe3$-ngr:.O$RJn!');
define('NONCE_SALT',       '-/gh!@kp_|Epz##a4{ JQ (V^(K/N+@nU@)6+Yp!i.k9sxahJbS_vz-lC~3T*+x-');

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
