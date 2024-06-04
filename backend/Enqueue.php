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
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;

/**
 * This class contain the Enqueue stuff for the backend
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( ! parent::initialize() ) {
			return;
		}

		\add_action( AssetManager::ACTION_SETUP, array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue assets with Inpyside library https://inpsyde.github.io/assets
	 *
	 * @param \Inpsyde\Assets\AssetManager $asset_manager The class.
	 * @return void
	 */
	public function enqueue_assets( AssetManager $asset_manager ) {
		// Load admin style sheet and JavaScript.
		$assets = $this->enqueue_admin_styles();

		if ( ! empty( $assets ) ) {
			foreach ( $assets as $asset ) {
				$asset_manager->register( $asset );
			}
		}

		$assets = $this->enqueue_admin_scripts();

		if ( empty( $assets ) ) {
			return;
		}

		foreach ( $assets as $asset ) {
			$asset_manager->register( $asset );
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function enqueue_admin_styles() {
		$admin_page = \get_current_screen();
		$styles     = array();

//		if ( ! \is_null( $admin_page ) && 'toplevel_page_disco-create-discount' === $admin_page->id ) {
			$styles[0] = new Style( DISCO_TEXTDOMAIN . '-settings-style', \plugins_url( 'assets/build/plugin-settings.css', DISCO_PLUGIN_ABSOLUTE ) );
			$styles[0]->forLocation( Asset::BACKEND )->withVersion( DISCO_VERSION );
			$styles[0]->withDependencies( 'dashicons' );

			$styles[1] = new Style( DISCO_TEXTDOMAIN . '-admin-style', \plugins_url( 'assets/build/plugin-admin.css', DISCO_PLUGIN_ABSOLUTE ) );
			$styles[1]->forLocation( Asset::BACKEND )->withVersion( DISCO_VERSION );
			$styles[1]->withDependencies( 'dashicons' );

				$styles[2] = new Style( DISCO_TEXTDOMAIN . '-admin-tailwind-style', \plugins_url( 'backend/views/asset/tailwind.css', DISCO_PLUGIN_ABSOLUTE ) );
			$styles[2]->forLocation( Asset::BACKEND )->withVersion( DISCO_VERSION );
			$styles[2]->withDependencies( 'dashicons' );
//		}

		return $styles;
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function enqueue_admin_scripts() {
		$admin_page = \get_current_screen();
		$scripts    = array();

		if ( ! \is_null( $admin_page ) && 'toplevel_page_disco-create-discount' === $admin_page->id ) {
			$scripts[0] = new Script( DISCO_TEXTDOMAIN . '-settings-script', \plugins_url( 'assets/build/plugin-settings.js', DISCO_PLUGIN_ABSOLUTE ) );
			$scripts[0]->forLocation( Asset::BACKEND )->withVersion( DISCO_VERSION );
			$scripts[0]->withDependencies( 'jquery-ui-tabs' );
			$scripts[0]->canEnqueue(
				function () {
					return \current_user_can( 'manage_options' );
				}
			);

			$scripts[1] = new Script( DISCO_TEXTDOMAIN . '-settings-admin', \plugins_url( 'assets/build/plugin-admin.js', DISCO_PLUGIN_ABSOLUTE ) );
			$scripts[1]->forLocation( Asset::BACKEND )->withVersion( DISCO_VERSION );
			$scripts[1]->dependencies();
			$scripts[1]->withLocalize(
				'DISCO',
				array(
					'ajax_url'   => admin_url( 'admin-ajax.php' ),
					'json_url'   => esc_url_raw( rest_url( 'disco/v1' ) ),
					'rest_nonce' => wp_create_nonce( 'wp_rest' ),
					'admin_url'  => admin_url( 'admin.php' ),
					'site_url'   => site_url(),
					'TEXTDOMAIN' => DISCO_TEXTDOMAIN,
				)
			);
		}

		return $scripts;
	}

}
