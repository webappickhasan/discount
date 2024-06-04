<?php

/**
 * Search Utility
 *
 * @package    Disco
 * @subpackage App\Utility
 * @since      1.0.0
 * @category   Utility
 */

namespace Disco\App\Utility;

/**
 * Class Settings
 *
 * @package    Disco
 * @subpackage App\Utility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Utility
 */
class Settings {

	/**
	 * Get the product price according to settings.
	 *
	 * @param \WC_Product $product Product Object.
	 * @return float
	 */
	public static function get_price( $product ) {
		$price = self::get( 'product_price_type' );

		if ( 'price' === $price ) {
			return (float) $product->get_price();
		}

		if ( 'regular_price' === $price ) {
			return (float) $product->get_regular_price();
		}

		if ( 'sale_price' === $price ) {
			return (float) $product->get_sale_price();
		}

		return (float) $product->get_price();
	}

	/**
	 * Get settings.
	 * If key is not found, return all settings.
	 * If key is 'all', return all settings.
	 * If key is 'default', return default settings.
	 * If key is found, return the value.
	 * If key is not found, return false.
	 *
	 * @param string $key Settings Key.
	 * @return array|\WP_Error Settings.
	 */
	public static function get( $key = 'all' ) {
		$default = array(
			'product_price_type'      => 'price', // price, regular_price, sale_price.
			'min_max_discount_amount' => 'min', // min, max.
		);

		/**
		 * Add defaults without changing the core values.
		 *
		 * @param array $defaults
		 * @since 3.3.11
		 */
		$default = wp_parse_args( apply_filters( 'disco_settings', $default ), $default );

		if ( 'default' === $key ) {
			return $default;
		}

		$get_settings = get_option( 'disco_settings', array() );

		$settings = wp_parse_args( $get_settings, $default );

		if ( 'all' === $key || empty( $key ) ) {
			return $settings;
		}

		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		return new \WP_Error(
			'rest_not_found',
			__( 'Sorry, Invalid Settings Key.', 'disco' ),
			array( 'status' => 400 )
		);
	}

	/**
	 * Set settings.
	 *
	 * @param string $key   Settings Key.
	 * @param string $value Settings Value.
	 * @return bool|\WP_Error
	 * @since 1.0.0
	 */
	public static function set( $key, $value ) {
		$setting = self::get( 'all' );

		if ( is_wp_error( $setting ) ) {
			return $setting;
		}

		if ( isset( $setting[ $key ] ) ) {
			$setting[ $key ] = $value;

			return self::save( $setting );
		}

		return new \WP_Error(
			'rest_not_found',
			__( 'Sorry, Invalid Settings Key.', 'disco' ),
			array( 'status' => 400 )
		);
	}

	/**
	 * Save settings.
	 *
	 * @param array $args Settings.
	 * @return bool
	 * @since 1.0.0
	 */
	public static function save( $args ) {
		$settings = get_option( 'disco_settings', self::Get() );

		$new_settings = wp_parse_args( $args, $settings );

		return update_option( 'disco_settings', $new_settings );
	}

}
