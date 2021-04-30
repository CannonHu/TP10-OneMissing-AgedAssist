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
define( 'DB_NAME', 'wordpress_it3' );

/** MySQL database username */
define( 'DB_USER', 'TP10' );

/** MySQL database password */
define( 'DB_PASSWORD', 'm1ssing_TP10' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '`Vce@dC#]Pvs#mk<_0~* e/==r7JT^-05I9mPBPoq`59O-ca.}u^R$>]q~V^X>4E' );
define( 'SECURE_AUTH_KEY',  '8A.at&U,.4W@9NUP]P);NB$k/nt,:on $6v,TDZ7lpZ]=s*$7 (O1mMRwwg?u1/o' );
define( 'LOGGED_IN_KEY',    'K)VKzq0y13IC3H({z=152pEO23Tcb_`d#pig4?4.i;h3)B7*W5,gi[EKj!aH31%J' );
define( 'NONCE_KEY',        'JN*.b*=R`]cq7}x-k!wXgPP4e(=N_T%L5:2YhW)B~, y#wiPZ*K8<g#e(S@}K;K9' );
define( 'AUTH_SALT',        '8Cj;Z|P785*>D7CxNWH;@lO{?pfHDw,TTSA<N#WhA*~)/wgnBKsCIA;%?NO7U97F' );
define( 'SECURE_AUTH_SALT', 't^S/HA&?Ov1#.8ir{UPL8<:te?6_1NGmhq1;P4{&jk<L#*SoquB^2`SW**Xp2P B' );
define( 'LOGGED_IN_SALT',   ' o3]9_YwD>^|W@x (eGk8<PcVYO$Botp7#t8{mPpA?$DRj5ngH^]-aJJ0RgL-u{m' );
define( 'NONCE_SALT',       ',J+}`JtM<C}@_v*3BKbj$4c5dvgj~Zo9GS[f*9lG+;^|FO&[F.<U:.o 57w6m:Rq' );

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
