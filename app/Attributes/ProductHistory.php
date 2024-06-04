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
class ProductHistory {

	/**
	 * Product Ids
	 *
	 * @var array
	 */
	private $ids;

	/**
	 * Product constructor.
	 *
	 * @param array $ids Product IDs.
	 */
	public function __construct( $ids = array() ) {
		if ( is_object( $ids ) ) {
			$ids = array( $ids );
		}

		$this->ids = $ids;
	}

	/**
	 * Get Net Revenue for specific products.
	 *
	 * @return int
	 */
	public function total_quantity_sold() {
		$total_sold = 0;

		if ( is_array( $this->ids ) && ! empty( $this->ids ) ) {
			$quantities = array();

			foreach ( $this->ids as $id ) {
				$product = wc_get_product( $id );

				if ( ! ( $product instanceof \WC_Product ) ) {
					continue;
				}

				$quantities[] = $product->get_total_sales();
			}

			$total_sold = array_sum( $quantities );
		}

		return $total_sold;
	}

	/**
	 * Get specific products net revenue.
	 *
	 * @return float|int
	 */
	public function total_amount_sold() {
		$total_sold = 0;

		if ( is_array( $this->ids ) && ! empty( $this->ids ) ) {
			$quantities = array();

			foreach ( $this->ids as $id ) {
				$quantities[] = $this->net_revenue( $id );
			}

			$total_sold = array_sum( $quantities );
		}

		return $total_sold;
	}

	/**
	 * Get products sum of total order quantity.
	 *
	 * @return float|int
	 */
	public function total_order_made() {
		$total_sold = 0;

		if ( ! empty( $this->ids ) ) {
			$quantities = array();

			foreach ( $this->ids as $id ) {
				$quantities[] = $this->order_quantity( $id );
			}

			$total_sold = array_sum( $quantities );
		}

		return $total_sold;
	}

	/**
	 * Get Product Last Order Date.
	 *
	 * @param int $id Product id.
	 * @return string
	 */
	public function last_order_date( $id ) {
		global $wpdb;

		$date = $wpdb->get_var(// phpcs:ignore
			$wpdb->prepare(
				"
        SELECT date_created
        FROM {$wpdb->prefix}wc_order_product_lookup
        WHERE product_id = %d ORDER BY order_id DESC LIMIT 1
    ",
				$id
			)
		);

		if ( $date ) {
			return gmdate( 'Y-m-d', strtotime( $date ) );
		}

		return '';
	}

	/**
	 * Get Single product total order quantity.
	 *
	 * @param int $product_id Product ID.
	 * @return float
	 */
	private function order_quantity( $product_id ) {
		global $wpdb;

		return (int) $wpdb->get_var(// phpcs:ignore
			$wpdb->prepare(
				"
        SELECT COUNT(DISTINCT(order_id))
        FROM {$wpdb->prefix}wc_order_product_lookup
        WHERE product_id = %d
    ",
				$product_id
			)
		);
	}

	/**
	 * Get Single product net revenue.
	 *
	 * @param int $product_id Product ID.
	 * @return float
	 */
	private function net_revenue( $product_id ) {
		global $wpdb;

		return (float) $wpdb->get_var(// phpcs:ignore
			$wpdb->prepare(
				"
        SELECT SUM(product_net_revenue)
        FROM {$wpdb->prefix}wc_order_product_lookup
        WHERE product_id = %d
    ",
				$product_id
			)
		);
	}

}
