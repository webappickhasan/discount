<?php

/**
 * Disco
 *
 * @package   Disco
 * @author    Ohidul Islam <wahid0003@gmail.com>
 * @link      http://domain.tld
 * @license   GPL 2.0+
 * @copyright 2022 WebAppick
 */

namespace Disco\Backend;

use Disco\Engine\Base;

/**
 * Create the settings page in the backend
 */
class Settings_Page extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( ! parent::initialize() ) {
			return;
		}

		// Add the options page and menu item.
		\add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		$realpath        = (string) \realpath( __DIR__ );
		$plugin_basename = \plugin_basename( \plugin_dir_path( $realpath ) . DISCO_TEXTDOMAIN . '.php' );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu
		 *
		 *
		 *
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities

		add_options_page( __( 'Page Title', DISCO_TEXTDOMAIN ), DISCO_NAME, 'manage_options', DISCO_TEXTDOMAIN, array( $this, 'display_plugin_admin_page' ) );
		 *
		 */
		/*
		 * Add a settings page for this plugin to the main menu
		 *
		 */
		add_menu_page(
			'Disco',
			DISCO_NAME,
			'manage_options',
			'disco-create-discount',
			array(
				$this,
				'display_plugin_admin_page_create_discount',
			),
			plugins_url('disco/assets/favicon.png'),
			10
		);
		add_submenu_page(
			DISCO_TEXTDOMAIN,
			__( 'Create a Discount', 'disco' ),
			__( 'Create a Discount', 'disco' ),
			'manage_options',
			'create-discount',
			array(
				$this,
				'display_plugin_admin_page_create_discount',
			)
		);
	}

	/**
	 * Render the create discount page for this plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function display_plugin_admin_page_create_discount() {
		// Create a nonce
		wp_create_nonce( 'disco_create_discount' );

		include_once DISCO_PLUGIN_ROOT . 'backend/views/create_discount.php';
	}
}
