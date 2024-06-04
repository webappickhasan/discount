<?php
/**
 * Disco Class File.
 *
 * @package    Disco
 * @subpackage \App\Disco.php
 * @since      1.0.0
 */

namespace Disco\App;

/**
 * Class Disco
 *
 * @package    Disco
 * @subpackage \App
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Discount
 */
class Disco {

	use \Disco\App\Intents\IntentHelper;

	/**
	 * @var array $intents Intents.
	 */
	private $intents;


	/**
	 * Apply discount to product.
	 *
	 * @param float       $price   Product Price.
	 * @param \WC_Product $product Product Object.
	 * @return float
	 */
	public function get_product_discounted_price( $price, $product ) {//phpcs:ignore
		if ( $product instanceof \WC_Product ) {
			// Init Product Based Intents and exclude cart-based intents.
			$this->intents = $this->prepare_intents( array( 'Product' ) );

			// If no intent is available, return the original price.
			if ( empty( $this->intents ) ) {
				return $price;
			}

			$discounts = array();

			// Foreach intent, apply the discount.
			foreach ( $this->intents as $intent ) {
				$discount = $intent->get_discounts( (float) $price, $product );

				if ( empty( $discount ) ) {
					continue;
				}

				$discounts[] = $discount;
			}

			// If no discount is applied, return the original price.
			if ( empty( $discounts ) ) {
				return $price;
			}

			// Get the min or max discount amount according to plugin settings.
			$new_price = $this->min_max_average( $discounts );

			if ( $new_price < $price ) {
				// Update the products on sale status.
				apply_filters( 'woocommerce_product_is_on_sale', true, $product );

				return $new_price;
			}
		}

		return $price;
	}

	/**
	 * @param \WC_Cart $cart Cart Object.
	 * @return \WC_Cart
	 */
	public function get_cart_discount( $cart ) { //phpcs:ignore
		if ( $cart->is_empty() ) {
			return $cart;
		}

		// Init Cart - Based Intents except Product & Shipping Intent .
		$this->intents = $this->prepare_intents( array( 'Cart' ) );

		if ( ! empty( $this->intents ) && $cart->get_cart_contents_count() > 0 ) {
			$discounts = array();

			foreach ( $this->intents as $intent ) { // Foreach intent, apply the discount.
				// Get items for discount.
				$items    = $this->get_items_for_discount( $cart, $intent->campaign );
				$discount = $intent->get_discounts( $items, $cart );

				if ( empty( $discount ) ) {
					continue;
				}

				$discounts[] = $discount;
			}

			// Apply discount to cart subtotal .
			if ( ! empty( $discounts ) ) {
				// Get the min or max discount amount according to plugin settings.
				$cart_fee = $this->min_max_average( $discounts );

				$cart->add_fee( __( 'Discount', 'disco' ), -$cart_fee );
			}
		}

		return $cart;
	}

	/**
	 * Apply Bulk & Bundle discount to cart items.
	 *
	 * @return \WC_Cart|void
	 */
	public function get_cart_items_discount() {//phpcs:ignore
		if ( ! $this->cart_is_valid() ) {
			return;
		}

		$cart      = WC()->cart;
		$discounts = $this->prepare_item_discounts();

		if ( empty( $discounts ) ) {
			return $cart;
		}

		$items = $cart->get_cart();

		foreach ( $items as $item ) {
			// Get item id.
			$id = $this->get_cart_item_id( $item );

			if ( empty( $discounts[ $id ] ) ) {
				continue;
			}

			$item_object = $item['data'];

			$price_key         = $discounts[ $id ]['key'];
			$regular_price     = $discounts[ $id ]['original_price'];
			$discounted_price  = $discounts[ $id ]['final_price'];
			$discounted_amount = $discounts[ $id ]['discounts'][ $price_key ];

			// If the discounted price is greater than or equal to the regular price, then skip.
			if ( $discounted_price >= $regular_price ) {
				continue;
			}

			$item_object->update_meta_data( '_item_offers', $discounts[ $id ] );
			$item_object->update_meta_data( '_item_savings', $discounted_amount );

			if ( $discounted_price <= 0 ) {
				$discounted_price = $regular_price;
			}

			$item_object->set_price( $discounted_price );
		}

		$cart->set_session();

		return $cart;
	}

	/**
	 * Apply the discount to shipping.
	 *
	 * @param \WC_Cart $cart Cart Object.
	 * @return bool
	 */
	public function apply_free_shipping( $cart ) {
		$this->intents = $this->prepare_intents( array( 'Shipping' ) );
		$shippings     = array();

		if ( ! empty( $this->intents ) ) {
			foreach ( $this->intents as $intent ) {
				$shippings[] = $intent->get_discounts( array(), $cart );
			}
		}

		return in_array( 'free_shipping', $shippings, true );
	}

	/**
	 * Get offers for a product.
	 *
	 * @param array|null $items item ids.
	 * @return string
	 */
	public function get_offers( $items = null ) {
		// Init Cart-Based Intents except Product & Shipping Intent.
		$this->intents = $this->prepare_intents( array( 'Product', 'Shipping' ) );
		$table         = "<table style='border-collapse: collapse;' class=''>";
		$table        .= '<tr><th>Quantity</th><th>Discount</th></tr>';

		$cart = WC()->cart;

		foreach ( $this->intents as $intent ) {
			if ( is_null( $items ) || ! $cart->is_empty() ) {
				$items = $this->get_items_for_discount( $cart, $intent->campaign );
			} else {
				$items = array();
			}

			$discounts = $intent->get_offers( $items, $cart );

			if ( empty( $discounts ) ) {
				continue;
			}

			foreach ( $discounts as $discount ) {
				$table .= '<tr style="font-size: smaller;line-height:15px"><td>' . $discount['condition'] . '</td><td>' . $discount['discount'] . '</td></tr>';
			}
		}

		return $table . '</table>';
	}

}
