<?php
/**
 * Product Attributes
 *
 * @package    Disco
 * @subpackage \App\Attributes
 */

namespace Disco\App\Attributes;

use WC_Customer;

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
class Customer {

    /**
     * @var \WC_Customer
     */
    private $customer;

    /**
     * Customer constructor.
     *
     * @throws \Exception If the current user is not logged in.
     */
	public function __construct() {
		$this->customer = new WC_Customer( get_current_user_id() );
	}

	/**
	 * Get Customer ID
	 *
	 * @return string
	 */
	public function customer_email() {
		return $this->customer->get_email();
	}

	/**
	 * Get Customer Name
	 *
	 * @return string
	 */
	public function customer_name() {
		return $this->customer->get_first_name() . ' ' . $this->customer->get_last_name();
	}

	/**
	 * Get Customer is logged In
	 *
	 * @return bool
	 */
	public function customer_is_logged_in() {
		return is_user_logged_in();
	}

	/**
	 * Get Customer Role
	 *
	 * @return string
	 */
	public function customer_user_role() {
		return $this->customer->get_role();
	}

	/**
	 * Get Customer Country
	 *
	 * @return string
	 */
	public function customer_country() {
		return $this->customer->get_billing_country();
	}

	/**
	 * Get Customer City
	 *
	 * @return string
	 */
	public function customer_city() {
		return $this->customer->get_billing_city();
	}

	/**
	 * Get Customer State
	 *
	 * @return string
	 */
	public function customer_state() {
		return $this->customer->get_billing_state();
	}

	/**
	 * Get Customer Zip
	 *
	 * @return string
	 */
	public function customer_zip() {
		return $this->customer->get_billing_postcode();
	}

}
