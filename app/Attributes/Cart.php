<?php
/**
 * Product Attributes
 *
 * @package    Disco
 * @subpackage \App\Attributes
 */

namespace Disco\App\Attributes;

/**
 * Class Product
 *
 * This class provides methods for retrieving various attributes of a WooCommerce product.
 *
 * @package    Disco
 * @subpackage \App\Attributes
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Attributes
 */
class Cart {

	/**
	 * Count Total Items in Cart.
	 *
	 * @return int
	 */
	public function line_items_count() {
		return WC()->cart->get_cart_contents_count();
	}

	/**
	 * Total Items Quantity in Cart.
	 *
	 * @return int
	 */
	public function cart_items_quantity() {
		if ( is_array( WC()->cart->get_cart_item_quantities() ) ) {
			return array_sum( WC()->cart->get_cart_item_quantities() );
		}

		return 0;
	}

	/**
	 * Total Items Weight in Cart.
	 *
	 * @return float
	 */
	public function cart_total_weight() {
		return WC()->cart->get_cart_contents_weight();
	}

	/**
	 * Cart Subtotal.
	 *
	 * @return float
	 */
	public function cart_subtotal() {
		return WC()->cart->get_subtotal();
	}

	/**
	 * Chosen Payment Method in Checkout.
	 *
	 * @return string
	 */
	public function cart_payment_method() {
		$payment_method = WC()->session->get( 'chosen_payment_method' );

		if ( is_array( $payment_method ) ) {
			$payment_method = implode( ',', $payment_method );
		}

		return $payment_method;
	}

	/**
	 * Cart Coupons.
	 *
	 * @return array
	 */
	public function cart_coupons() {
		return WC()->cart->get_coupons();
	}

}
