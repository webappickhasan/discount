<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://webappick.com
 * @since             1.0.0
 * @package           Disco
 * @wordpress-plugin
 * Plugin Name:       Disco
 * Plugin URI:        https://webappick.com/
 * Description:       Transform your WooCommerce store with Dynamic, Automated Discounts based on Cart, Customer and Product Attributes, enhanced by IFTTT logic.
 * Version:           1.0.3
 * Author:            Ohidul Islam
 * Author URI:        https://profiles.wordpress.org/wahid0003/
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       disco
 * Domain Path:       /languages
 *
 * WP Requirement & Test
 * Requires at least: 6.0
 * Tested up to: 6.5.3
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 *
 * WC Requirement & Test
 * WC requires at least: 6.0
 * WC tested up to: 8.9.1
 */

// If this file is called directly, abort.
use Disco\Engine\Initialize;
use Micropackage\Requirements\Requirements;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

const DISCO_VERSION    = '1.0.3';
const DISCO_TEXTDOMAIN = 'disco';
const DISCO_NAME       = 'Disco';
define( 'DISCO_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
const DISCO_PLUGIN_ABSOLUTE = __FILE__;
const DISCO_MIN_PHP_VERSION = '7.4';
const DISCO_WP_VERSION      = '5.3';
const DISCO_DB_VERSION      = '1.0.0';

// WooCommerce has detected that some of your active plugins are incompatible with currently enabled WooCommerce features. Please review the details.
add_action(
	'before_woocommerce_init',
	function () {
		if ( ! class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			return;
		}

		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
);

add_action(// @phpstan-ignore-line
	'init',
	static function () {
		load_plugin_textdomain( DISCO_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

if ( version_compare( PHP_VERSION, DISCO_MIN_PHP_VERSION, '<=' ) ) {
	add_action(// @phpstan-ignore-line
		'admin_init',
		static function () {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	add_action(// @phpstan-ignore-line
		'admin_notices',
		static function () {
			echo wp_kses_post(
				sprintf(
					'<div class="notice notice-error"><p>%s</p></div>',
					__( '"Disco" requires PHP 5.6 or newer.', 'disco' )// @phpstan-ignore-line
				)
			);
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

$disco_libraries = require DISCO_PLUGIN_ROOT . 'vendor/autoload.php'; //phpcs:ignore

add_action('woocommerce_init', function () {
	require_once DISCO_PLUGIN_ROOT . 'functions/common.php';
	require_once DISCO_PLUGIN_ROOT . 'functions/product.php';
});


$requirements = new Requirements(
	'Disco',
	array(
		'php'            => DISCO_MIN_PHP_VERSION,
		'php_extensions' => array( 'mbstring' ),
		'wp'             => DISCO_WP_VERSION,
		'plugins'        => array(
			array(
				'file'    => 'woocommerce/woocommerce.php',
				'name'    => 'WooCommerce',
				'version' => '3.5',
			),
		),
	)
);

if ( ! $requirements->satisfied() ) {
	$requirements->print_notice();

	return;
}

if ( ! wp_installing() ) {
	register_activation_hook(
		DISCO_TEXTDOMAIN . '/' . DISCO_TEXTDOMAIN . '.php',
		array(
			new \Disco\Backend\ActDeact(),
			'activate',
		)
	);
	register_deactivation_hook(
		DISCO_TEXTDOMAIN . '/' . DISCO_TEXTDOMAIN . '.php',
		array(
			new \Disco\Backend\ActDeact(),
			'deactivate',
		)
	);
	add_action(// @phpstan-ignore-line
		'plugins_loaded',
		static function () use ( $disco_libraries ) {
			new Initialize( $disco_libraries );
		}
	);
}
