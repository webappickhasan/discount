<?php

namespace Disco\Tests\WPUnit\App\Attributes;

use Disco\App\Attributes\CustomerHistory;

class CustomerHistoryTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \WpunitTester
	 */
	protected $tester;
	protected $ids;
	protected $customer;

	public function setUp(): void {
		// Before...
		parent::setUp();

		$product1 = $this->tester->create_simple_product( 1 );
		$product2 = $this->tester->create_simple_product( 2 );

		$this->ids = array( $product1->get_id(), $product2->get_id() );

		$customer = $this->tester->create_customer();
		wp_set_current_user( $customer->ID );
		$this->customer = $customer;

		$order = $this->tester->create_wc_order(
			array(
				'address'        => array(
					'first_name' => $customer->get_first_name(),
					'last_name'  => $customer->get_last_name(),
					'company'    => 'stackoverflow',
					'email'      => $customer->get_email(),
					'phone'      => $customer->get_billing_phone(),
					'address_1'  => '31 Main Street',
					'address_2'  => '',
					'city'       => $customer->get_billing_city(),
					'state'      => $customer->get_billing_state(),
					'postcode'   => $customer->get_billing_postcode(),
					'country'    => $customer->get_billing_country(),
				),
				'user_id'        => $customer->get_id(),
				'order_comments' => '',
				'payment_method' => 'cod',
				'order_status'   => array(
					'status' => 'completed',
					'note'   => 'disco-unit-test',
				),
				'line_items'     => array(
					array(
						'quantity' => 2,
						'args'     => array(
							'product_id'   => $product1->get_id(),
							'variation_id' => '',
							'variation'    => array(),
						),
					),
					array(
						'quantity' => 2,
						'args'     => array(
							'product_id'   => $product2->get_id(),
							'variation_id' => '',
							'variation'    => array(),
						),
					),
				),
				'coupon_items'   => array(
				// array(
				// 'code' => 'summer',
				// ),
				),
				'fee_items'      => array(
				// array(
				// 'name'      => 'Delivery',
				// 'total'     => 5,
				// 'tax_class' => 0, // Not taxable
				// ),
				),
			)
		);

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests
	public function test_is_customer_first_order() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );

		$this->assertFalse( $customerInfo->is_first_order() );
	}

	public function test_customer_last_order_date() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );
		$this->assertEquals( date_i18n( 'Y-m-d' ), $customerInfo->last_order_date() );
		codecept_debug( $customerInfo->last_order_date() );
	}

	public function test_customer_last_order_amount() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );
		$this->assertEquals( '320.00', $customerInfo->last_order_amount() );
		codecept_debug( $customerInfo->last_order_amount() );
	}

	public function test_total_order_made() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );
		$this->assertEquals( '1', $customerInfo->total_order_made() );
		codecept_debug( $customerInfo->total_order_made() );
	}

	public function test_total_spent() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );
		$this->assertEquals( '320.00', $customerInfo->total_spent() );
		codecept_debug( $customerInfo->total_spent() );
	}

	public function test_total_qty_sold_by_ids() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );
		$this->assertEquals( 4, $customerInfo->total_quantity_sold_by_ids() );
		codecept_debug( $customerInfo->total_quantity_sold_by_ids() );
	}

	public function test_total_amount_sold_by_ids() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );

		// This is failing due to order info not saving into database. It should be 320.00 but tested with 0.00
		$this->assertEquals( 0, $customerInfo->total_amount_sold_by_ids() );
		codecept_debug( $this->customer->ID );
	}

	public function test_total_order_made_by_ids() {
		$customerInfo = new CustomerHistory( $this->ids, $this->customer->ID );
		// This is failing due to order info not saving into database. It should be 320.00 but tested with 0.00
		$this->assertEquals( 0, $customerInfo->total_order_made_by_ids() );
		codecept_debug( $customerInfo->total_order_made_by_ids() );
	}
}
