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



if ( ! function_exists( 'disco_update_cart_price_by_campaigns' ) ) {

	/**
	 * Run the Cart Intent campaigns before cart.
	 *
	 * @param \WC_Cart $cart Cart Object.
	 * @return \WC_Cart
	 */
	function disco_add_cart_discount( $cart ) {
		return ( new Disco )->get_cart_discount( $cart );
	}

	// Temporarily disabled: add_action( 'woocommerce_cart_calculate_fees', 'disco_add_cart_discount', 1, 1 );
	// To apply a cart discount based on certain conditions, uncomment the above line.

}

// Set Cart Items discounts.
if ( ! function_exists( 'disco_set_cart_item_price' ) ) {

	/**
	 * @return bool
	 */
	function disco_set_cart_item_price() {
		$disco = new Disco;

		return $disco->get_cart_items_discount();
	}

	// add_action( 'woocommerce_before_calculate_totals', 'disco_set_cart_item_price' );
	// To apply this action, uncomment the above line.
}

/**
 * Add a free product to the cart.
 *
 * @param \WC_Cart $cart Cart Object.
 * @return \WC_Cart
 */
function disco_cart_loaded_callback( $cart ) {
	// Get the product object of the product you want to duplicate
	$free_items = array(
		'key' => '32',
	);

	foreach ( $free_items as $item_key => $item ) { //phpcs:ignore
		$product_id   = $item;
		$quantity     = 1;
		$new_item_key = $cart->add_to_cart( $product_id, $quantity, 0, array(), array( 'free' => 'yes' ) );
		$cart_item    = $cart->get_cart_item( $new_item_key );
		$cart_item['data']->set_price( 0 );
		$cart->set_quantity( $new_item_key, 1 );
	}

	return $cart;
}

// Add the callback function to the hook
// add_action( 'woocommerce_cart_loaded_from_session', 'disco_cart_loaded_callback' );

if ( ! function_exists( 'disco_run_after_cart' ) ) {

	/**
	 * Display Cart Item Sale Price & Regular Price.
	 *
	 * @param string $price Cart Item Price.
	 * @param array  $cart_item Cart Item.
	 * @return string
	 */
	function disco_display_regular_and_sale_price_in_cart_item( $price, $cart_item ) {
		$product_id    = $cart_item['product_id'];
		$product       = wc_get_product( $product_id );
		$regular_price = $product->get_price();
		$sale_price    = $cart_item['data']->get_price();

		if ( isset( $cart_item['free'] ) && 'yes' === $cart_item['free'] ) {
			$cart_item['data']->set_price( 0 );

			return 0;
		}

		$offers = $cart_item['data']->get_meta( '_item_offers' );

		if ( $sale_price && $sale_price < $regular_price ) {
			$price = ' < del > ' . wc_price( $regular_price ) . ' < / del > < ins > ' . wc_price( $sale_price ) . ' < / ins > ';
		}

		if ( ! empty( $offers ) && isset( $offers['key'] ) ) {
			$key      = $offers['key'];
			$bogo_qty = $offers['bogo_qty'][ $key ];

			if ( ! empty( $bogo_qty ) ) {
				$price = $bogo_qty;
			}
		}

		return $price;
	}

	// add_filter( 'woocommerce_cart_item_price', 'disco_display_regular_and_sale_price_in_cart_item', 10, 2 );
	// To apply this action, uncomment the above line.

}

if ( ! function_exists( 'disco_display_subtotal_regular_and_sale_price_in_cart_item' ) ) {

	/**
	 * Display Cart Item Subtotal Sale Price & Regular Price.
	 *
	 * @param string $subtotal Cart Item Subtotal.
	 * @param array  $cart_item Cart Item.
	 * @return string
	 */
	function disco_display_subtotal_regular_and_sale_price_in_cart_item( $subtotal, $cart_item ) {
		$product_id    = $cart_item['product_id'];
		$quantity      = $cart_item['quantity'];
		$product       = wc_get_product( $product_id );
		$regular_price = $product->get_price() * $quantity;
		$sale_price    = $cart_item['data']->get_price() * $quantity;

		if ( $sale_price && $sale_price < $regular_price ) {
			$subtotal = '<del>' . wc_price( $regular_price ) . '</del><ins>' . wc_price( $sale_price ) . '</ins>';
		}

		return $subtotal;
	}

	// add_filter( 'woocommerce_cart_item_subtotal', 'disco_display_subtotal_regular_and_sale_price_in_cart_item', 10, 2 );
	// To apply this action, uncomment the above line.
}

if ( ! function_exists( 'disco_cart_item_quantity_notice' ) ) {

	/**
	 * Display Cart Item Quantity Badge.
	 *
	 * @param string $quantity_html Cart Item Quantity HTML.
	 * @param array  $cart_item Cart Item.
	 * @return string
	 */
	function disco_cart_item_quantity_notice( $quantity_html, $cart_item ) {//phpcs:ignore
		// Wrap the quantity in a badge element
		$item   = WC()->cart->get_cart()[ $cart_item ];
		$offers = $item['data']->get_meta( '_item_offers' );

		if ( ! empty( $offers['price'] ) ) {
			$message        = sprintf( 'Add % s get % s off', $offers['quantity'], wc_price( $offers['price'] ) );
			$quantity_html .= sprintf( '<span title="Add 3 get 1 Free" class="quantity-badge">%s</span>', $message );
		}

		return $quantity_html;
	}

	// add_filter( 'woocommerce_cart_item_quantity', 'disco_cart_item_quantity_notice', 10, 2 );
	// To apply this action, uncomment the above line.
}

if ( ! function_exists( 'disco_add_button_to_cart_item_name' ) ) {

	/**
	 * Add a button to the cart item name.
	 *
	 * @param string $product_name Product Name.
	 * @return string
	 */
	function disco_add_button_to_cart_item_name( $product_name ) {
		$button = ( new Disco )->get_offers(); // Replace '// ' with your desired link.

		return $product_name . '<br/>' . $button; // This will add the button after the product name.
	}

	// add_filter( 'woocommerce_cart_item_name', 'disco_add_button_to_cart_item_name', 10, 1 );
	// To apply this filter, uncomment the above line.
}

if ( ! function_exists( 'disco_override_shipping_rates_to_free_shipping' ) ) {

	/**
	 * @param array $rates Array of rates found for the package.
	 * @param array $package Array of package information.
	 * @return array
	 */
	function disco_override_shipping_rates_to_free_shipping( $rates, $package ) {
		$is_free_shipping = ( new Disco )->apply_free_shipping( WC()->cart );

		if ( ! $is_free_shipping ) {
			return $rates;
		}

		// Check if there are any existing rates
		if ( ! empty( $rates ) ) {
			foreach ( $rates as $rate_key => $rate ) {
				// Add only the first-rate but change it to free shipping
				if ( 'free_shipping' === $rate->method_id ) {
					unset( $rates[ $rate_key ] );
				} else {
					$rate->label     = 'Free Shipping';
					$rate->cost      = 0;
					$rate->taxes     = array();
					$rate->id        = 'free_shipping';
					$rate->method_id = 'free_shipping';
					$rate->package   = $package;
				}
			}
		}

		return $rates;
	}

	// add_filter( 'woocommerce_package_rates', 'disco_override_shipping_rates_to_free_shipping', 100, 2 );
	// To apply this filter, uncomment the above line.
}
