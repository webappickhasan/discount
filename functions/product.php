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

use Disco\App\Disco;

if ( !function_exists( 'disco_get_discounted_price' ) ) {

	/**
	 * Get the discounted price of a product.
	 *
	 * @param float       $price Product Price.
	 * @param \WC_Product $product Product Object.
	 * @return float
	 */
	function disco_get_discounted_price( $price, $product ) {
		return ( new Disco )->get_product_discounted_price( $price, $product );
	}

}

if ( ! function_exists( 'disco_update_product_price_by_campaigns' ) ) {

	/**
	 * Run the Product Intent campaigns and get the sale price before product.
	 *
	 * @param string      $price Product Price.
	 * @param \WC_Product $product Product Object.
	 * @return float
	 */
	function disco_discounted_price( $price, $product ) {

		if(  is_page('cart')  || is_cart() ) {
			// Make below filters active to get the sale price. TODO: Need to check if it's necessary.
			remove_filter( 'woocommerce_product_get_sale_price', '__return_false' );
			remove_filter( 'woocommerce_product_get_price', '__return_false' );

			return disco_get_discounted_price( $price, $product );
		}

		return $price;
	}


	add_filter( 'woocommerce_product_get_price', 'disco_discounted_price', PHP_INT_MAX, 2 );
	add_filter( 'woocommerce_product_get_sale_price', 'disco_discounted_price', PHP_INT_MAX, 2 );

}

if ( ! function_exists( 'disco_discounted_price_html' ) ) {

	/**
	 * Display Product Sale Price & Regular Price.
	 *
	 * @param string      $price_html Product Price HTML.
	 * @param \WC_Product $product Product Object.
	 * @return string
	 */
	function disco_discounted_price_html( $price_html, $product ) {
//		remove_filter( 'woocommerce_product_get_sale_price', '__return_false' );
		$regular_price = $product->get_regular_price();
		$sale_price    = disco_get_discounted_price( $product->get_price(), $product );

		if ( $sale_price < $regular_price ) {
			// Format the sale price HTML
			$price_html = '<del>' . wc_price( $regular_price ) . '</del> <ins>' . wc_price( $sale_price ) . '</ins>';
		}

		return $price_html;
	}

	 add_filter( 'woocommerce_get_price_html', 'disco_discounted_price_html', PHP_INT_MAX, 2 );
}
