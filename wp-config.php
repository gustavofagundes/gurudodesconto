<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// Carrega variáveis do arquivo .env (local / Hostinger) se existir
if ( file_exists( __DIR__ . '/.env' ) ) {
	foreach ( file( __DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) as $line ) {
		$line = trim( $line );
		if ( $line === '' || str_starts_with( $line, '#' ) || ! str_contains( $line, '=' ) ) {
			continue;
		}
		list( $name, $value ) = explode( '=', $line, 2 );
		$name  = trim( $name );
		$value = trim( $value, " \t\n\r\0\x0B\"'" );
		if ( getenv( $name ) === false ) {
			putenv( "$name=$value" );
			$_ENV[ $name ]    = $value;
			$_SERVER[ $name ] = $value;
		}
	}
}

// Lê variável de ambiente (suporta VAR e VAR_FILE do Docker)
if ( ! function_exists( 'guru_env' ) ) {
	function guru_env( $keys, $default = '' ) {
		foreach ( (array) $keys as $key ) {
			if ( $file = getenv( $key . '_FILE' ) ) {
				return rtrim( file_get_contents( $file ), "\r\n" );
			}
			$val = getenv( $key );
			if ( $val !== false && $val !== '' ) {
				return $val;
			}
		}
		return $default;
	}
}

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

// ** Database settings — variáveis de ambiente ** //
// Aceita DB_* (padrão) ou WORDPRESS_DB_* (Docker)
define( 'DB_NAME',     guru_env( array( 'DB_NAME', 'WORDPRESS_DB_NAME' ), '' ) );
define( 'DB_USER',     guru_env( array( 'DB_USER', 'WORDPRESS_DB_USER' ), '' ) );
define( 'DB_PASSWORD', guru_env( array( 'DB_PASSWORD', 'WORDPRESS_DB_PASSWORD' ), '' ) );
define( 'DB_HOST',     guru_env( array( 'DB_HOST', 'WORDPRESS_DB_HOST' ), 'localhost' ) );

if ( empty( DB_NAME ) || empty( DB_USER ) ) {
	header( 'Content-Type: text/html; charset=utf-8' );
	die(
		'<h1>Configuração do banco ausente</h1>' .
		'<p>Crie o arquivo <code>.env</code> na raiz do site (<code>public_html</code>) com:</p>' .
		'<pre>DB_NAME=seu_banco
DB_USER=seu_usuario
DB_PASSWORD=sua_senha
DB_HOST=localhost</pre>' .
		'<p>Copie de <code>.env.example</code> e preencha com os dados do hPanel → Databases → Management.</p>'
	);
}

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '435c54f936db6e2bac35b7f0b437046c05628309') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '7d0d303245d7b12c20a3ba92d4de03cfe5883084') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    '830e7b365a355590dc8c484aa37b3f93755b9fd7') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '9fccffd5f338cb693b373ae46ec02aa7c915f6f0') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '9010aa61266c994f54f7fcf9a415313efa54b1bd') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', '517d259278318e66c8fbe667513bc86152f86ca2') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   'a3c1720ae5960e1177624af55f00600209105dac') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       '60d1534d5e5eaab930f8c13910facaeaabb5901a') );
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = guru_env( array( 'DB_TABLE_PREFIX', 'WORDPRESS_TABLE_PREFIX' ), 'wp_' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );

/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
	eval($configExtra);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
