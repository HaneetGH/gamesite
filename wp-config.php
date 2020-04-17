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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '98^-1}:7A!QiDl;h&0#vx36U_$vG<|6~W_yBn2EQ^GhWh-pp8_MT]4hs&:Pz*fdt' );
define( 'SECURE_AUTH_KEY',  'kmQD)~bFO[Ig&&X=ob1tjz;;+CXmrnfb~oqz6+0w?iT4{(NJAqGsG`8|:4s&;Hz]' );
define( 'LOGGED_IN_KEY',    'g<_pD4(q4qKQ`F&,5!wt&+IE;&PwhVxppj}qFUZFpJ~A%bNlgQ27sI<4CS|JMJ-k' );
define( 'NONCE_KEY',        '9n.u*y8{-:h#/`Yz4|yR>v,B~[s0F[`,M2ZHhhz//N,/$_sEIwlw7mMy9Xpp2Zvh' );
define( 'AUTH_SALT',        '8cQpUdy:n1Y9DeF)MBzn[5Xc@LfDvz./CplYt/W$WSA)yw{LS]Mt=#LN#{@QB j)' );
define( 'SECURE_AUTH_SALT', 'U:K#&h7>eA-I<}XR{E3he^DP)BlUyv8~Nb*w!1{0&sbk$G{vXB?bn)~/$k]?YPeQ' );
define( 'LOGGED_IN_SALT',   '~0Z-U}%BVr?O=/BtVB~PvOsXcm9ToWKxF$F(DSKg)F#qk;Sy{p&%M^ItdB#oIzw&' );
define( 'NONCE_SALT',       '[$Mq+6$5:@(YYE}k11h)wr>V-j8{&{[Jq,|k`OIdv^k%aUq$<E:EwMy}KGP fI>>' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
