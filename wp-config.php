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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'PwigHRHL8b/3tEdHQ33oGp13WEC7oMVUzJCeeMNfbSIwe6ymakL8R1sLCcM3Y6t76U+LoP79cy6o7M2m9cmvWg==');
define('SECURE_AUTH_KEY',  'vr2xQI20hVD5grG+xVXi0B/NEIEQ8AYlIUTQIaOkc5bI7D26VCQZjtay2sjnPuX6z7Et3itxSvs8Fz1wJY83DQ==');
define('LOGGED_IN_KEY',    'aFzIjwNVO9vh4UaWM2enG0UO3dOIgMh7rEjms1/3hFGXzmOD6VoLXmL5HRHpvmh+VJmpodnwGwlJBqnuQuNurQ==');
define('NONCE_KEY',        '/s9VnJ8zrakRp9GHK6XtfFKsj/qfe/aBb/zjtQYcMCnaBUJhkJCT4Ck5E+q0+0DZ/tOu33LGa6o1aDbUAU5zSg==');
define('AUTH_SALT',        '99T8/gUbPWTPPvHihx4uOVt3QNt0SZ1NgSW0E0wbAnFb27/VWXHgv8e+Dj66dy6vmub4W+KEcAHyftdIcoUmuA==');
define('SECURE_AUTH_SALT', 'ZVc3i4TwSQuU7BIFc+9Y9vWpGzmxixe/6LU3g0mCnkK1op8VEqyXeBi5tsHcIGjIgF67ymbptmxTYjUekpP2qg==');
define('LOGGED_IN_SALT',   'QtiidLvFfT5sZEYM5h8hRTXGzq5WIFDkkJgax0QIpN2HPN9WAGkbUryJ1TGJJ/57YHVjy2czLLBbPzA+5L+gfw==');
define('NONCE_SALT',       '1hWLtmK0vDAds3WKD53SMHoxWBXBWgFvHflc3MDccZuc1dqMNJpbxm1aLJHQlVN2Tq7L3Xhk4i1b6xk7gy3YRg==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
